<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifySnippeWebhookSignature
{
    /**
     * Verify the Snippe webhook signature (HMAC-SHA256).
     *
     * Format: HMAC-SHA256 of "{timestamp}.{raw_body}" using the webhook secret.
     * Rejects timestamps older than 5 minutes to prevent replay attacks.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('services.snippe.webhook_secret');

        if (! $secret) {
            Log::warning('Snippe webhook secret not configured — skipping signature verification');

            return $next($request);
        }

        $signature = $request->header('X-Webhook-Signature');
        $timestamp = $request->header('X-Webhook-Timestamp');

        if (! $signature || ! $timestamp) {
            Log::warning('Snippe Webhook: Missing signature or timestamp headers');

            return response()->json(['error' => 'Missing signature headers'], 400);
        }

        if (abs(time() - (int) $timestamp) > 300) {
            Log::warning('Snippe Webhook: Timestamp too old', ['timestamp' => $timestamp]);

            return response()->json(['error' => 'Webhook timestamp expired'], 400);
        }

        $rawBody = $request->getContent();
        $expectedSignature = hash_hmac('sha256', "{$timestamp}.{$rawBody}", $secret);

        if (! hash_equals($expectedSignature, $signature)) {
            Log::warning('Snippe Webhook: Invalid signature', [
                'expected' => $expectedSignature,
                'received' => $signature,
            ]);

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        return $next($request);
    }
}
