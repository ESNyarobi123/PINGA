<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SnippePayoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SnippePayoutWebhookController extends Controller
{
    public function handle(Request $request, SnippePayoutService $snippe): JsonResponse
    {
        $payload = $request->all();

        Log::info('Snippe Payout Webhook Received', [
            'id'     => $payload['id'] ?? $payload['data']['id'] ?? 'unknown',
            'status' => $payload['status'] ?? $payload['data']['status'] ?? 'unknown',
        ]);

        try {
            $snippe->handlePayoutWebhook($payload);
        } catch (\Throwable $e) {
            Log::error('Snippe Payout Webhook Exception: ' . $e->getMessage(), [
                'payload' => $payload,
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

        return response()->json(['status' => 'success']);
    }
}
