<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;

class WithdrawalService
{
    public function __construct(private readonly SnippePayoutService $snippe) {}

    /**
     * Retry a withdrawal payout safely.
     *
     * Withdrawals refunded by a failed payout webhook are re-debited from the wallet
     * before being sent again, so the user is never paid twice.
     *
     * @return array{success: bool, error?: string, reference?: string|null}
     */
    public function retry(WithdrawalRequest $withdrawal): array
    {
        $withdrawal->refresh();

        if ($withdrawal->status === 'paid' || $withdrawal->payout_status === 'completed') {
            return ['success' => false, 'error' => 'Withdrawal is already paid.'];
        }

        if (in_array($withdrawal->status, ['rejected', 'refunded'], true)) {
            $reDeduct = $this->reDeductRefundedWithdrawal($withdrawal);

            if (! $reDeduct['success']) {
                return $reDeduct;
            }

            $withdrawal->refresh();
        }

        $user = $withdrawal->user;

        if (! $user) {
            return ['success' => false, 'error' => 'User not found.'];
        }

        $phone = $withdrawal->account_number ?: ($withdrawal->phone ?: $user->phone);

        $result = $this->snippe->sendPayout([
            'amount' => (int) $withdrawal->amount,
            'phone' => (string) $phone,
            'name' => $withdrawal->account_name ?: $user->name,
            'network' => $withdrawal->network ?: $this->snippe->detectNetwork((string) $phone),
            'narration' => 'Kutoa pesa - Winga Platform',
            'idempotency_key' => 'retry-withdrawal-'.$withdrawal->id.'-'.now()->timestamp,
            'metadata' => [
                'type' => 'withdrawal',
                'user_id' => $withdrawal->user_id,
                'withdrawal_id' => $withdrawal->id,
            ],
        ]);

        if ($result['success']) {
            $withdrawal->update([
                'payout_reference' => $result['reference'],
                'payout_status' => 'processing',
                'status' => 'pending',
                'retry_count' => ($withdrawal->retry_count ?? 0) + 1,
                'last_retry_at' => now(),
            ]);
        } else {
            $withdrawal->update(['payout_status' => 'failed']);
        }

        return $result;
    }

    /**
     * Take the money out of the wallet again before retrying a refunded withdrawal.
     *
     * @return array{success: bool, error?: string}
     */
    private function reDeductRefundedWithdrawal(WithdrawalRequest $withdrawal): array
    {
        $user = $withdrawal->user;
        $total = (float) $withdrawal->amount + (float) ($withdrawal->charge_amount ?? 0);

        if (! $user || (float) $user->wallet_balance < $total) {
            return ['success' => false, 'error' => 'Salio la mtumiaji halitoshi kwa retry hii.'];
        }

        DB::transaction(function () use ($withdrawal, $user, $total): void {
            $user->decrement('wallet_balance', $total);

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => $total,
                'description' => 'Retry ya kutoa pesa (#'.$withdrawal->id.')',
                'balance_after' => $user->fresh()->wallet_balance,
                'reference' => 'WD-'.$withdrawal->id.'-retry-'.now()->timestamp,
                'status' => 'processing',
            ]);

            $withdrawal->update([
                'status' => 'pending',
                'payout_status' => 'processing',
                'admin_note' => 'Retry baada ya refund.',
            ]);
        });

        return ['success' => true];
    }
}
