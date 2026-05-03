<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SnippePaymentService
{
    protected string $apiKey;

    protected string $baseUrl;

    protected string $webhookBaseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.snippe.key');
        $this->baseUrl = config('services.snippe.url', 'https://api.snippe.sh');
        $this->webhookBaseUrl = config('services.snippe.webhook_base_url', 'https://winga.ericksky.online');
    }

    /**
     * Create Mobile Money Payment (USSD Push)
     */
    public function createMobilePayment(float $amount, string $phoneNumber, array $customerData, string $orderId)
    {
        try {
            // Clean phone number: remove any non-digits and ensure it's in 255 format
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '255'.substr($phoneNumber, 1);
            } elseif (strlen($phoneNumber) == 9) {
                $phoneNumber = '255'.$phoneNumber;
            }

            $webhookUrl = $this->webhookBaseUrl . '/api/webhooks/snippe';
            $payload = [
                'payment_type' => 'mobile',
                'details' => [
                    'amount' => (int) $amount,
                    'currency' => 'TZS',
                ],
                'phone_number' => $phoneNumber,
                'customer' => array_filter([
                    'firstname' => $customerData['firstname'] ?? 'Customer',
                    'lastname' => $customerData['lastname'] ?? 'Name',
                    'email' => $customerData['email'] ?? 'noemail@winga.com',
                ]),
                'webhook_url' => $webhookUrl,
                'metadata' => array_filter([
                    'order_id' => strval($orderId),
                    'payment_method' => 'mobile',
                    'user_id' => strval(auth()->id() ?? $customerData['user_id'] ?? ''),
                    'payment_type' => $customerData['payment_type'] ?? null,
                    'subscription_id' => isset($customerData['subscription_id']) ? strval($customerData['subscription_id']) : null,
                ]),
            ];

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Idempotency-Key' => $orderId,
                'Accept' => 'application/json',
            ])->post("{$this->baseUrl}/v1/payments", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Snippe Mobile Payment Error: '.$response->status().' - '.$response->body(), ['payload' => $payload]);

            return null;

        } catch (\Exception $e) {
            Log::error('Snippe Mobile Payment Exception: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Create Card Payment (Redirect)
     */
    public function createCardPayment(float $amount, array $customerData, string $orderId)
    {
        try {
            $user = auth()->user();
            $phoneNumber = $user->phone ?? ($customerData['phone'] ?? '255700000000');
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '255'.substr($phoneNumber, 1);
            }

            $webhookUrl = $this->webhookBaseUrl . '/api/webhooks/snippe';
            $redirectUrl = $this->webhookBaseUrl . '/mteja/wallet';
            $cancelUrl = $this->webhookBaseUrl . '/mteja/wallet?status=cancelled';
            $payload = [
                'payment_type' => 'card',
                'details' => [
                    'amount' => (int) $amount,
                    'currency' => 'TZS',
                    'redirect_url' => $redirectUrl,
                    'cancel_url' => $cancelUrl,
                ],
                'phone_number' => $phoneNumber,
                'customer' => [
                    'firstname' => $customerData['firstname'] ?? 'Customer',
                    'lastname' => $customerData['lastname'] ?? 'Name',
                    'email' => $customerData['email'] ?? 'customer@email.com',
                    'address' => $customerData['address'] ?? 'Dar Es Salaam',
                    'city' => $customerData['city'] ?? 'Dar Es Salaam',
                    'state' => 'DSM',
                    'postcode' => '14101',
                    'country' => 'TZ',
                ],
                'webhook_url' => $webhookUrl,
                'metadata' => array_filter([
                    'order_id' => strval($orderId),
                    'payment_method' => 'card',
                    'user_id' => strval(auth()->id() ?? $customerData['user_id'] ?? ''),
                    'payment_type' => $customerData['payment_type'] ?? null,
                    'subscription_id' => isset($customerData['subscription_id']) ? strval($customerData['subscription_id']) : null,
                ]),
            ];

            Log::info('Initiating Snippe Card Payment - Payload:', ['payload' => $payload]);

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Idempotency-Key' => $orderId,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/v1/payments", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Snippe Card Payment Error: '.$response->status().' - '.$response->body());

            return null;

        } catch (\Exception $e) {
            Log::error('Snippe Card Payment Exception: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Check payment status by reference (polling fallback for mobile money).
     */
    public function checkPaymentStatus(string $reference): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/v1/payments/{$reference}");

            if ($response->successful()) {
                $json = $response->json();

                return $json['data'] ?? $json ?? null;
            }

            Log::warning('Snippe Status Check Failed: '.$response->status().' - '.$response->body(), ['reference' => $reference]);

            return null;
        } catch (\Exception $e) {
            Log::error('Snippe Status Check Exception: '.$e->getMessage(), ['reference' => $reference]);

            return null;
        }
    }

    /**
     * Helper to ensure URLs use HTTPS scheme
     */
    private function ensureHttps(string $url, bool $force = false): string
    {
        if ($force && str_starts_with($url, 'http://')) {
            return str_replace('http://', 'https://', $url);
        }

        return $url;
    }
}
