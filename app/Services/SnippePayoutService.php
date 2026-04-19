<?php

namespace App\Services;

use App\Jobs\RetryFailedPayout;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Notifications\WingaNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SnippePayoutService
{
    protected string $apiKey;

    protected string $baseUrl;

    protected string $webhookUrl;

    public function __construct()
    {
        $this->apiKey     = config('services.snippe.key');
        $this->baseUrl    = config('services.snippe.url', 'https://api.snippe.sh');
        $this->webhookUrl = 'https://winga.ericksky.online/api/webhooks/snippe-payout';
    }

    /**
     * Format a phone number to 255XXXXXXXXX format.
     * Handles: 0781000000 → 255781000000, +255781000000 → 255781000000
     */
    public function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '255') && strlen($phone) === 12) {
            return $phone;
        }

        if (str_starts_with($phone, '0') && strlen($phone) === 10) {
            return '255' . substr($phone, 1);
        }

        if (strlen($phone) === 9) {
            return '255' . $phone;
        }

        return $phone;
    }

    /**
     * Detect the mobile network from a phone number.
     * Returns Snippe network code: Airtel | Tigo | Halopesa
     */
    public function detectNetwork(string $phone): string
    {
        $phone = $this->formatPhone($phone);
        $prefix = substr($phone, 3, 2); // e.g. "78" from "255781000000"

        // Airtel Tanzania: 68, 69, 78 (some), 79 (some)
        if (in_array($prefix, ['68', '69', '78', '79'])) {
            return 'Airtel';
        }

        // Halopesa (TTCL): 41
        if ($prefix === '41') {
            return 'Halopesa';
        }

        // Mixx by Yas (TigoPesa): 65, 67, 71, 74, 75, 76
        return 'Tigo';
    }

    /**
     * Send a payout via Snippe API.
     *
     * @param array{
     *   amount: int,
     *   phone: string,
     *   name: string,
     *   narration: string,
     *   idempotency_key: string,
     *   metadata: array
     * } $data
     */
    public function sendPayout(array $data): array
    {
        $formattedPhone = $this->formatPhone($data['phone']);
        $network        = $data['network'] ?? $this->detectNetwork($formattedPhone);

        $payload = [
            'amount'           => (int) $data['amount'],
            'channel'          => 'mobile',
            'network'          => $network,
            'recipient_phone'  => $formattedPhone,
            'recipient_name'   => $data['name'],
            'narration'        => $data['narration'],
            'webhook_url'      => $this->webhookUrl,
            'metadata'         => $data['metadata'] ?? [],
        ];

        Log::info('Snippe Payout Request', [
            'idempotency_key' => $data['idempotency_key'],
            'amount'          => $payload['amount'],
            'phone'           => $formattedPhone,
            'network'         => $network,
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization'   => "Bearer {$this->apiKey}",
                'Idempotency-Key' => $data['idempotency_key'],
                'Accept'          => 'application/json',
                'Content-Type'    => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/v1/payouts/send", $payload);

            $result = $response->json() ?? [];

            Log::info('Snippe Payout Response', [
                'status'   => $response->status(),
                'response' => $result,
            ]);

            if ($response->successful()) {
                return [
                    'success'   => true,
                    'reference' => $result['data']['id'] ?? $result['id'] ?? null,
                    'data'      => $result,
                ];
            }

            Log::error('Snippe Payout API Error', [
                'status'  => $response->status(),
                'body'    => $response->body(),
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'error'   => $result['message'] ?? 'Snippe payout failed',
                'data'    => $result,
            ];

        } catch (\Exception $e) {
            Log::error('Snippe Payout Exception', [
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Get payout status from Snippe.
     */
    public function getPayoutStatus(string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept'        => 'application/json',
            ])->timeout(15)->get("{$this->baseUrl}/v1/payouts/{$reference}");

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => $response->body()];

        } catch (\Exception $e) {
            Log::error('Snippe Get Payout Status Exception', ['message' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Process incoming Snippe payout webhook payload.
     */
    public function handlePayoutWebhook(array $payload): void
    {
        Log::info('Snippe Payout Webhook', ['payload' => $payload]);

        $status    = strtolower($payload['status'] ?? $payload['data']['status'] ?? '');
        $reference = $payload['id'] ?? $payload['data']['id'] ?? $payload['reference'] ?? null;
        $metadata  = $payload['metadata'] ?? $payload['data']['metadata'] ?? [];

        if (! $reference) {
            Log::warning('Snippe Payout Webhook: no reference found', $payload);
            return;
        }

        $type         = $metadata['type'] ?? 'payout';
        $paymentId    = $metadata['payment_id'] ?? null;
        $withdrawalId = $metadata['withdrawal_id'] ?? null;
        $workerId     = $metadata['worker_id'] ?? $metadata['user_id'] ?? null;
        $amount       = $payload['amount'] ?? $payload['data']['amount'] ?? 0;

        match ($status) {
            'completed', 'success'  => $this->handlePayoutCompleted($reference, $type, $paymentId, $withdrawalId, $workerId, (float) $amount),
            'failed', 'error'       => $this->handlePayoutFailed($reference, $type, $paymentId, $withdrawalId, $workerId, (float) $amount),
            default                 => Log::info("Snippe Payout Webhook: unhandled status [{$status}]"),
        };
    }

    /**
     * Handle a successfully completed payout.
     */
    protected function handlePayoutCompleted(
        string $reference,
        string $type,
        ?int $paymentId,
        ?int $withdrawalId,
        ?int $workerId,
        float $amount,
    ): void {
        // Update job payment payout status
        if ($paymentId) {
            Payment::where('id', $paymentId)->update(['payout_status' => 'completed']);

            // Update the pending transaction to completed
            Transaction::where('reference', $reference)
                ->orWhere('payment_id', $paymentId)
                ->where('type', 'credit')
                ->where('status', 'processing')
                ->update(['status' => 'completed']);
        }

        // Update withdrawal request
        if ($withdrawalId) {
            WithdrawalRequest::where('id', $withdrawalId)->update([
                'status'            => 'paid',
                'payout_reference'  => $reference,
                'payout_status'     => 'completed',
                'processed_at'      => now(),
            ]);
        }

        // Notify worker
        if ($workerId) {
            $worker = User::find($workerId);
            if ($worker) {
                $worker->notify(new WingaNotification(
                    title: '💸 Pesa Imefika!',
                    message: "Umepokea TZS " . number_format($amount) . " kwenye simu yako. Asante kwa kutumia Winga!",
                    icon: 'banknotes',
                    color: 'green',
                    action_url: route('winga.mapato'),
                    action_label: 'Angalia Mapato',
                ));
            }
        }

        Log::info("Snippe Payout Completed: {$reference} — TZS {$amount}");
    }

    /**
     * Handle a failed payout — refund wallet and notify.
     */
    protected function handlePayoutFailed(
        string $reference,
        string $type,
        ?int $paymentId,
        ?int $withdrawalId,
        ?int $workerId,
        float $amount,
    ): void {
        // Mark payment payout as failed
        if ($paymentId) {
            Payment::where('id', $paymentId)->update(['payout_status' => 'failed']);
        }

        // Refund withdrawal wallet balance
        if ($withdrawalId) {
            $withdrawal = WithdrawalRequest::find($withdrawalId);
            if ($withdrawal && $withdrawal->status !== 'refunded') {
                $withdrawal->update([
                    'status'           => 'rejected',
                    'payout_status'    => 'failed',
                    'payout_reference' => $reference,
                    'admin_note'       => 'Snippe payout failed automatically.',
                    'processed_at'     => now(),
                ]);

                // Refund wallet
                if ($workerId) {
                    User::where('id', $workerId)->increment('wallet_balance', $withdrawal->amount);
                    $workerFresh = User::find($workerId);

                    Transaction::create([
                        'user_id'      => $workerId,
                        'type'         => 'credit',
                        'amount'       => $withdrawal->amount,
                        'description'  => 'Refund: kutoa fedha kumeshindwa — ' . $reference,
                        'balance_after' => $workerFresh?->wallet_balance ?? 0,
                        'status'       => 'completed',
                        'reference'    => 'refund-' . $reference,
                    ]);
                }
            }
        }

        // Notify worker
        if ($workerId) {
            $worker = User::find($workerId);
            $worker?->notify(new WingaNotification(
                title: '⚠️ Tatizo la Malipo',
                message: 'Kulikuwa na tatizo na malipo yako ya TZS ' . number_format($amount) . '. Fedha imerudishwa kwenye wallet yako. Jaribu tena.',
                icon: 'exclamation-triangle',
                color: 'red',
                action_url: route('winga.tomba-ombi'),
                action_label: 'Jaribu Tena',
            ));
        }

        // Notify admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new WingaNotification(
                title: '🚨 Payout Imeshindwa',
                message: "Payout {$reference} ya TZS " . number_format($amount) . " imeshindwa. Angalia na ujaribu tena.",
                icon: 'x-circle',
                color: 'red',
                action_url: route('admin.maombi-kutoa'),
                action_label: 'Angalia Maombi',
            ));
        }

        // Schedule auto-retry after 1 hour
        RetryFailedPayout::dispatch($reference, $type, $paymentId, $withdrawalId, $workerId, $amount)
            ->delay(now()->addHour());

        Log::error("Snippe Payout Failed: {$reference} — TZS {$amount}");
    }
}
