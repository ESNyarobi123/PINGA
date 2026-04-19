<?php

namespace App\Http\Controllers\Api\Muajili;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || (! $user->isMuajili() && ! $user->isAdmin())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $balance = (float) ($user->wallet_balance ?? 0);

        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(50)
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'type' => $t->type,
                'amount' => $t->amount,
                'balance_after' => $t->balance_after,
                'description' => $t->description,
                'created_at_human' => $t->created_at->diffForHumans(),
                'created_at' => $t->created_at->toIso8601String(),
            ]);

        return response()->json([
            'balance' => $balance,
            'transactions' => $transactions,
        ]);
    }
}
