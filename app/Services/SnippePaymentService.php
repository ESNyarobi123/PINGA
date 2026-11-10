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
        $this->webhookBaseUrl = rtrim((string) config('services.snippe.webhook_base_url', config('app.url')), '/');
    }

    /**
     * The webhook URL sent with every payment request (diagnostics helper).
     */
    public function webhookUrl(): string
    {
        return $this->webhookBaseUrl.'/api/webhooks/snippe';
    }

    /**
     * Warn when the webhook host does not match the public site host, which means
     * Snippe confirmations are sent to a domain this deployment does not serve.
     */
    public function warnIfWebhookHostMismatch(): void
    {
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);
        $webhookHost = parse_url($this->webhookUrl(), PHP_URL_HOST);

        if (! $appHost || ! $webhookHost || strcasecmp($appHost, $webhookHost) === 0) {
            return;
        }

        Log::warning('Snippe webhook host does not match APP_URL — payments may never be confirmed', [
            'app_url' => config('app.url'),
            'webhook_url' => $this->webhookUrl(),
            'fix' => 'Set SNIPPE_WEBHOOK_BASE_URL to the public site URL and clear the config cache.',
        ]);
    }

    /**
     * Create Mobile Money Payment (USSD Push)
     *
     * Returns the raw Snippe payload on success. A failure returns a structured
     * outcome so callers can tell a definite rejection apart from a timeout:
     *   ['status' => 'failed',  'transient' => false, 'message' => '...']
     *   ['status' => 'unknown', 'transient' => true,  'message' => '...']
     *
     * @return array<string, mixed>
     */
    public function createMobilePayment(float $amount, string $phoneNumber, array $customerData, string $orderId): array
    {
        try {
            // Clean phone number: remove any non-digits and ensure it's in 255 format
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '255'.substr($phoneNumber, 1);
            } elseif (strlen($phoneNumber) == 9) {
                $phoneNumber = '255'.$phoneNumber;
            }

            $webhookUrl = $this->webhookBaseUrl.'/api/webhooks/snippe';
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

            $this->warnIfWebhookHostMismatch();

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Idempotency-Key' => $orderId,
                'Accept' => 'application/json',
            ])->connectTimeout(10)->timeout(30)->post("{$this->baseUrl}/v1/payments", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Snippe Mobile Payment Error: '.$response->status().' - '.$response->body(), ['payload' => $payload]);

            $json = $response->json() ?? [];

            return [
                'status' => 'failed',
                'transient' => false,
                'http_status' => $response->status(),
                'message' => (string) ($json['message'] ?? 'Mtoa huduma amekataa ombi la malipo.'),
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // The request never got a reply. Snippe may still have queued the USSD
            // push, so this must not be reported as a definite failure.
            Log::error('Snippe Mobile Payment Timeout: '.$e->getMessage(), ['order_id' => $orderId]);

            return [
                'status' => 'unknown',
                'transient' => true,
                'message' => 'Mtoa huduma hakujibu kwa muda. Ombi linaweza kuwa limetumwa — subiri USSD kwenye simu yako kabla ya kujaribu tena.',
            ];
        } catch (\Exception $e) {
            Log::error('Snippe Mobile Payment Exception: '.$e->getMessage(), ['order_id' => $orderId]);

            return [
                'status' => 'failed',
                'transient' => false,
                'message' => 'Hitilafu ya mtandao wakati wa kutuma ombi la malipo.',
            ];
        }
    }

    /**
     * True when the caller should show the "wait for the USSD prompt" message
     * rather than a hard failure.
     */
    public static function isUnknownOutcome(array $result): bool
    {
        return ($result['status'] ?? null) === 'unknown' || ! empty($result['transient']);
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

            $webhookUrl = $this->webhookBaseUrl.'/api/webhooks/snippe';
            $redirectUrl = $this->webhookBaseUrl.'/mteja/wallet';
            $cancelUrl = $this->webhookBaseUrl.'/mteja/wallet?status=cancelled';
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
