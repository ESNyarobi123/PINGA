<?php

namespace App\Services;

use App\Jobs\RetryFailedPayout;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Notifications\WingaNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SnippePayoutService
{
    protected string $apiKey;

    protected string $baseUrl;

    protected string $webhookUrl;

    public function __construct()
    {
        $this->apiKey = config('services.snippe.key');
        $this->baseUrl = config('services.snippe.url', 'https://api.snippe.sh');
        $webhookBase = config('services.snippe.webhook_base_url', 'https://winga.hisgc.net');
        $this->webhookUrl = $webhookBase.'/api/webhooks/snippe-payout';
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
            return '255'.substr($phone, 1);
        }

        if (strlen($phone) === 9) {
            return '255'.$phone;
        }

        return $phone;
    }

    /**
     * Detect the mobile network from a phone number.
     * Returns Snippe network code: Airtel | Vodacom | Tigo | Halopesa
     */
    public function detectNetwork(string $phone): string
    {
        $phone = $this->formatPhone($phone);
        $prefix = substr($phone, 3, 2); // e.g. "78" from "255781000000"

        // Vodacom (M-Pesa): 74, 75, 76, 77
        if (in_array($prefix, ['74', '75', '76', '77'])) {
            return 'Vodacom';
        }

        // Airtel Tanzania: 68, 69, 78, 79
        if (in_array($prefix, ['68', '69', '78', '79'])) {
            return 'Airtel';
        }

        // Halopesa (TTCL): 41
        if ($prefix === '41') {
            return 'Halopesa';
        }

        // Mixx by Yas (TigoPesa): 65, 67, 71
        if (in_array($prefix, ['65', '67', '71'])) {
            return 'Tigo';
        }

        // Default fallback
        return 'Vodacom';
    }

    /**
     * Map any stored network/method value to the code Snippe expects:
     * Airtel | Vodacom | Tigo | Halopesa
     *
     * Withdrawal rows store mixed values ("Tigo", "tigopesa", "halotel", ...),
     * so every payout has to be normalised before it reaches the API.
     */
    public function normaliseNetwork(?string $value, string $phone): string
    {
        $key = strtolower(trim((string) $value));

        $map = [
            'airtel' => 'Airtel',
            'airtel_money' => 'Airtel',
            'airtelmoney' => 'Airtel',
            'vodacom' => 'Vodacom',
            'mpesa' => 'Vodacom',
            'm-pesa' => 'Vodacom',
            'tigo' => 'Tigo',
            'tigopesa' => 'Tigo',
            'mixx' => 'Tigo',
            'mixx by yas' => 'Tigo',
            'yas' => 'Tigo',
            'halopesa' => 'Halopesa',
            'halotel' => 'Halopesa',
        ];

        return $map[$key] ?? $this->detectNetwork($phone);
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
        $network = $this->normaliseNetwork($data['network'] ?? null, $formattedPhone);

        $payload = [
            'amount' => (int) $data['amount'],
            'channel' => 'mobile',
            'network' => $network,
            'recipient_phone' => $formattedPhone,
            'recipient_name' => $data['name'],
            'narration' => $data['narration'],
            'webhook_url' => $this->webhookUrl,
            'metadata' => $data['metadata'] ?? [],
        ];

        Log::info('Snippe Payout Request', [
            'idempotency_key' => $data['idempotency_key'],
            'amount' => $payload['amount'],
            'phone' => $formattedPhone,
            'network' => $network,
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Idempotency-Key' => $data['idempotency_key'],
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/v1/payouts/send", $payload);

            $result = $response->json() ?? [];

            Log::info('Snippe Payout Response', [
                'status' => $response->status(),
                'response' => $result,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'reference' => $result['data']['id'] ?? $result['id'] ?? null,
                    'data' => $result,
                ];
            }

            Log::error('Snippe Payout API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'error' => $result['message'] ?? 'Snippe payout failed',
                'data' => $result,
            ];

        } catch (\Exception $e) {
            Log::error('Snippe Payout Exception', [
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
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
                'Accept' => 'application/json',
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

        $status = strtolower($payload['status'] ?? $payload['data']['status'] ?? '');
        $reference = $payload['id'] ?? $payload['data']['id'] ?? $payload['reference'] ?? null;
        $metadata = $payload['metadata'] ?? $payload['data']['metadata'] ?? [];

        if (! $reference) {
            Log::warning('Snippe Payout Webhook: no reference found', $payload);

            return;
        }

        $type = $metadata['type'] ?? 'payout';
        $paymentId = $metadata['payment_id'] ?? null;
        $withdrawalId = $metadata['withdrawal_id'] ?? null;
        $workerId = $metadata['worker_id'] ?? $metadata['user_id'] ?? null;
        $rawAmount = $payload['amount'] ?? $payload['data']['amount'] ?? 0;
        $amount = is_array($rawAmount) ? ($rawAmount['value'] ?? 0) : $rawAmount;

        // Providers do not always echo metadata back. Fall back to the stored payout
        // reference so a confirmed/failed payout never leaves a withdrawal stuck.
        if (! $withdrawalId) {
            $withdrawalId = WithdrawalRequest::query()->where('payout_reference', $reference)->value('id');
        }

        if (! $withdrawalId && ! $paymentId) {
            $paymentId = Payment::query()->where('payout_reference', $reference)->value('id');
        }

        if (! $paymentId && ! $withdrawalId) {
            Log::warning('Snippe Payout Webhook: could not match a ledger row', [
                'reference' => $reference,
                'metadata' => $metadata,
            ]);

            return;
        }

        $type = $withdrawalId ? 'withdrawal' : 'payout';

        match ($status) {
            'completed', 'success' => $this->handlePayoutCompleted($reference, $type, $paymentId, $withdrawalId, $workerId, (float) $amount),
            'failed', 'error' => $this->handlePayoutFailed($reference, $type, $paymentId, $withdrawalId, $workerId, (float) $amount),
            default => Log::info("Snippe Payout Webhook: unhandled status [{$status}]"),
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
        DB::transaction(function () use ($reference, $paymentId, $withdrawalId, &$workerId): void {
            // Update job payment payout status
            if ($paymentId) {
                Payment::where('id', $paymentId)->update(['payout_status' => 'completed']);

                // Update the pending transaction to completed
                Transaction::query()
                    ->where('status', 'processing')
                    ->where(function ($query) use ($reference, $paymentId): void {
                        $query->where('reference', $reference)
                            ->orWhere(function ($sub) use ($paymentId): void {
                                $sub->where('payment_id', $paymentId)
                                    ->where('type', 'credit');
                            });
                    })
                    ->update(['status' => 'completed']);
            }

            // Update withdrawal request
            if ($withdrawalId) {
                $withdrawal = WithdrawalRequest::query()->lockForUpdate()->find($withdrawalId);

                if ($withdrawal) {
                    $workerId = $workerId ?: $withdrawal->user_id;

                    $withdrawal->update([
                        'status' => 'paid',
                        'payout_reference' => $reference,
                        'payout_status' => 'completed',
                        'payout_error' => null,
                        'processed_at' => now(),
                    ]);

                    // Close every debit row for this withdrawal (including retries)
                    // so the history does not stay "processing" forever.
                    $withdrawal->settleLedger('completed');
                }
            }
        });

        // Notify worker
        if ($workerId) {
            $worker = User::find($workerId);
            if ($worker) {
                $worker->notify(new WingaNotification(
                    title: '💸 Pesa Imefika!',
                    message: 'Umepokea TZS '.number_format($amount).' kwenye simu yako. Asante kwa kutumia Winga!',
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

        $refunded = false;
        $recipientId = $workerId;

        // Refund withdrawal wallet balance (idempotently)
        if ($withdrawalId) {
            $refunded = DB::transaction(function () use ($reference, $withdrawalId, &$recipientId): bool {
                $withdrawal = WithdrawalRequest::query()->lockForUpdate()->find($withdrawalId);

                if (! $withdrawal) {
                    return false;
                }

                $recipientId = $withdrawal->user_id ?: $recipientId;

                // A late webhook from an older attempt must not touch the current one.
                if (filled($withdrawal->payout_reference) && $withdrawal->payout_reference !== $reference) {
                    Log::warning('Snippe Payout Webhook: stale reference ignored', [
                        'withdrawal_id' => $withdrawal->id,
                        'current' => $withdrawal->payout_reference,
                        'incoming' => $reference,
                    ]);

                    return false;
                }

                // Already cancelled/refunded or already flagged as failed: never refund twice.
                if (in_array($withdrawal->status, ['rejected', 'refunded'], true)
                    || in_array($withdrawal->payout_status, ['failed', 'completed'], true)) {
                    return false;
                }

                $withdrawal->update([
                    'status' => 'rejected',
                    'payout_status' => 'failed',
                    'payout_reference' => $reference,
                    'payout_error' => 'Payout ilishindwa kwenye mtoa huduma ('.$reference.').',
                    'admin_note' => 'Snippe payout failed automatically.',
                    'processed_at' => now(),
                ]);

                $refundReference = 'refund-'.$reference;

                if (! $recipientId || Transaction::query()->where('reference', $refundReference)->exists()) {
                    return false;
                }

                $refundAmount = (float) $withdrawal->amount + (float) ($withdrawal->charge_amount ?? 0);

                User::whereKey($recipientId)->increment('wallet_balance', $refundAmount);
                $workerFresh = User::find($recipientId);

                $withdrawal->settleLedger('failed');

                Transaction::create([
                    'user_id' => $recipientId,
                    'type' => 'credit',
                    'amount' => $refundAmount,
                    'description' => 'Refund: kutoa fedha kumeshindwa — '.$reference,
                    'balance_after' => $workerFresh?->wallet_balance ?? 0,
                    'status' => 'completed',
                    'reference' => $refundReference,
                ]);

                return true;
            });
        }

        // Notify worker
        if ($recipientId) {
            $worker = User::find($recipientId);
            $worker?->notify(new WingaNotification(
                title: '⚠️ Tatizo la Malipo',
                message: $refunded
                    ? 'Kulikuwa na tatizo na malipo yako ya TZS '.number_format($amount).'. Fedha imerudishwa kwenye wallet yako. Jaribu tena.'
                    : 'Kulikuwa na tatizo na malipo yako ya TZS '.number_format($amount).'. Wasiliana na msaada wa Winga.',
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
                message: "Payout {$reference} ya TZS ".number_format($amount).' imeshindwa. '
                    .($refunded ? 'Fedha zimerudishwa kwenye wallet ya mtumiaji.' : 'Hakuna refund iliyofanyika.'),
                icon: 'x-circle',
                color: 'red',
                action_url: route('admin.maombi-kutoa'),
                action_label: 'Angalia Maombi',
            ));
        }

        // Auto-retry only job payouts. A withdrawal must be retried by an admin because
        // the wallet has to be re-debited first (see WithdrawalService::retry()).
        if (! $refunded && $paymentId) {
            RetryFailedPayout::dispatch($reference, $type, $paymentId, $withdrawalId, $workerId, $amount)
                ->delay(now()->addHour());
        }

        Log::error("Snippe Payout Failed: {$reference} — TZS {$amount}");
    }

    /**
     * Calculate the payout fee for a given amount.
     *
     * @return array{amount: int, fee_amount: int, total_amount: int, currency: string}|null
     */
    public function calculateFee(int $amount): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json',
            ])->timeout(15)->get("{$this->baseUrl}/v1/payouts/fee", ['amount' => $amount]);

            if ($response->successful()) {
                return $response->json('data');
            }

            Log::error('Snippe Payout Fee Error', ['status' => $response->status(), 'body' => $response->body()]);

            return null;
        } catch (\Exception $e) {
            Log::error('Snippe Payout Fee Exception', ['message' => $e->getMessage()]);

            return null;
        }
    }
}
