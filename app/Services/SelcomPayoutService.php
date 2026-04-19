<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Notifications\WingaNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SelcomPayoutService
{
    protected string $apiKey;

    protected string $apiSecret;

    protected string $vendor;

    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey    = config('services.selcom.api_key', '');
        $this->apiSecret = config('services.selcom.api_secret', '');
        $this->vendor    = config('services.selcom.vendor', 'WINGA');
        $this->baseUrl   = config('services.selcom.base_url', 'https://apigw.selcommobile.com/v1');
    }

    /**
     * Generate the authorization header for Selcom API.
     */
    protected function getAuthHeaders(string $timestamp): array
    {
        $digest = base64_encode(hash_hmac('sha256', $timestamp, $this->apiSecret, true));

        return [
            'Authorization' => 'SELCOM ' . base64_encode($this->apiKey),
            'Digest-Method' => 'HS256',
            'Digest'        => $digest,
            'Timestamp'     => $timestamp,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    /**
     * Format phone number to 255XXXXXXXXX format.
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
     * Send a disbursement/payout via Selcom API.
     *
     * @param array{
     *   amount: int,
     *   phone: string,
     *   name: string,
     *   narration: string,
     *   reference: string,
     *   metadata: array
     * } $data
     */
    public function sendPayout(array $data): array
    {
        $formattedPhone = $this->formatPhone($data['phone']);
        $timestamp      = now()->format('Y-m-d\TH:i:sP');

        $payload = [
            'vendor'    => $this->vendor,
            'msisdn'    => $formattedPhone,
            'amount'    => (int) $data['amount'],
            'reference' => $data['reference'],
            'narration' => $data['narration'] ?? 'Winga Platform Payout',
        ];

        Log::info('Selcom Payout Request', [
            'reference' => $data['reference'],
            'amount'    => $payload['amount'],
            'phone'     => $formattedPhone,
        ]);

        try {
            $response = Http::withHeaders($this->getAuthHeaders($timestamp))
                ->timeout(30)
                ->post("{$this->baseUrl}/checkout/wallet-payment", $payload);

            $result = $response->json() ?? [];

            Log::info('Selcom Payout Response', [
                'status'   => $response->status(),
                'response' => $result,
            ]);

            if ($response->successful() && ($result['resultcode'] ?? '') === '000') {
                return [
                    'success'   => true,
                    'reference' => $result['transid'] ?? $data['reference'],
                    'data'      => $result,
                ];
            }

            Log::error('Selcom Payout API Error', [
                'status'  => $response->status(),
                'body'    => $response->body(),
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'error'   => $result['message'] ?? $result['resultcode'] ?? 'Selcom payout failed',
                'data'    => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Selcom Payout Exception', [
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
     * Check payout status from Selcom.
     */
    public function getPayoutStatus(string $reference): array
    {
        $timestamp = now()->format('Y-m-d\TH:i:sP');

        try {
            $response = Http::withHeaders($this->getAuthHeaders($timestamp))
                ->timeout(15)
                ->get("{$this->baseUrl}/checkout/order-status", [
                    'order_id' => $reference,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => $response->body()];
        } catch (\Exception $e) {
            Log::error('Selcom Payout Status Exception', ['message' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Process incoming Selcom webhook payload.
     */
    public function handleWebhook(array $payload): void
    {
        Log::info('Selcom Payout Webhook', ['payload' => $payload]);

        $resultCode = $payload['resultcode'] ?? $payload['result_code'] ?? '';
        $reference  = $payload['reference'] ?? $payload['order_id'] ?? null;
        $transId    = $payload['transid'] ?? $payload['transaction_id'] ?? null;
        $amount     = (float) ($payload['amount'] ?? 0);

        if (! $reference) {
            Log::warning('Selcom Webhook: no reference found', $payload);
            return;
        }

        $withdrawal = WithdrawalRequest::where('payout_reference', $reference)
            ->orWhere('payout_reference', $transId)
            ->first();

        if (! $withdrawal) {
            Log::warning("Selcom Webhook: withdrawal not found for ref {$reference}");
            return;
        }

        if ($resultCode === '000') {
            $this->handlePayoutCompleted($withdrawal, $transId ?? $reference, $amount);
        } else {
            $this->handlePayoutFailed($withdrawal, $transId ?? $reference, $amount);
        }
    }

    /**
     * Handle a successfully completed Selcom payout.
     */
    protected function handlePayoutCompleted(WithdrawalRequest $withdrawal, string $reference, float $amount): void
    {
        $withdrawal->update([
            'status'           => 'paid',
            'payout_status'    => 'completed',
            'payout_reference' => $reference,
            'processed_at'     => now(),
        ]);

        $worker = $withdrawal->user;
        $worker?->notify(new WingaNotification(
            title: '💸 Pesa Imefika!',
            message: 'Umepokea TZS ' . number_format($amount) . ' kwenye simu yako kupitia Selcom. Asante kwa kutumia Winga!',
            icon: 'banknotes',
            color: 'green',
            action_url: route('winga.tomba-ombi'),
            action_label: 'Angalia Hali',
        ));

        Log::info("Selcom Payout Completed: {$reference} — TZS {$amount}");
    }

    /**
     * Handle a failed Selcom payout — refund wallet and notify.
     */
    protected function handlePayoutFailed(WithdrawalRequest $withdrawal, string $reference, float $amount): void
    {
        if ($withdrawal->status === 'refunded') {
            return;
        }

        $withdrawal->update([
            'status'           => 'rejected',
            'payout_status'    => 'failed',
            'payout_reference' => $reference,
            'admin_note'       => 'Selcom payout failed automatically.',
            'processed_at'     => now(),
        ]);

        $worker = $withdrawal->user;
        if ($worker) {
            $worker->increment('wallet_balance', $withdrawal->amount);

            Transaction::create([
                'user_id'       => $worker->id,
                'type'          => 'credit',
                'amount'        => $withdrawal->amount,
                'description'   => 'Refund: Selcom payout failed — ' . $reference,
                'balance_after' => $worker->fresh()->wallet_balance,
                'status'        => 'completed',
                'reference'     => 'refund-selcom-' . $reference,
            ]);

            $worker->notify(new WingaNotification(
                title: '⚠️ Tatizo la Malipo',
                message: 'Kulikuwa na tatizo na malipo yako ya TZS ' . number_format($amount) . '. Fedha imerudishwa kwenye wallet yako. Jaribu tena.',
                icon: 'exclamation-triangle',
                color: 'red',
                action_url: route('winga.tomba-ombi'),
                action_label: 'Jaribu Tena',
            ));
        }

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new WingaNotification(
                title: '🚨 Selcom Payout Imeshindwa',
                message: "Payout {$reference} ya TZS " . number_format($amount) . " imeshindwa kupitia Selcom.",
                icon: 'x-circle',
                color: 'red',
                action_url: route('admin.maombi-kutoa'),
                action_label: 'Angalia Maombi',
            ));
        }

        Log::error("Selcom Payout Failed: {$reference} — TZS {$amount}");
    }
}
