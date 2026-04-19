<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SelcomPayoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SelcomPayoutWebhookController extends Controller
{
    public function handle(Request $request, SelcomPayoutService $selcom): JsonResponse
    {
        $payload = $request->all();

        Log::info('Selcom Payout Webhook Received', [
            'reference'   => $payload['reference'] ?? $payload['order_id'] ?? 'unknown',
            'resultcode'  => $payload['resultcode'] ?? $payload['result_code'] ?? 'unknown',
        ]);

        try {
            $selcom->handleWebhook($payload);
        } catch (\Throwable $e) {
            Log::error('Selcom Payout Webhook Exception: ' . $e->getMessage(), [
                'payload' => $payload,
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

        return response()->json(['status' => 'success']);
    }
}
