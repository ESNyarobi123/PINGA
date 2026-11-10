<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        // A payout the provider already accepted is still in flight. Sending another one
        // would pay the user twice, so wait for the webhook or reconcile it manually.
        if ($withdrawal->hasPayoutInFlight()) {
            return [
                'success' => false,
                'error' => 'Payout ya ombi hili bado inasubiri uthibitisho wa mtoa huduma. Usitume tena — thibitisha kwenye dashboard ya Snippe kwanza.',
            ];
        }

        if ($this->isBankTransfer($withdrawal)) {
            return [
                'success' => false,
                'error' => 'Ombi hili ni la benki. Linafanywa kwa mkono, hakuna payout ya simu inayotumwa.',
            ];
        }

        $user = $withdrawal->user;

        if (! $user) {
            return ['success' => false, 'error' => 'User not found.'];
        }

        $phone = $withdrawal->account_number ?: ($withdrawal->phone ?: $user->phone);

        if (blank($phone)) {
            $withdrawal->update([
                'payout_status' => 'failed',
                'payout_error' => 'Namba ya simu ya kupokea haipo.',
            ]);

            return ['success' => false, 'error' => 'Namba ya simu ya kupokea haipo.'];
        }

        $wasRefunded = in_array($withdrawal->status, ['rejected', 'refunded'], true);

        if ($wasRefunded) {
            $reDeduct = $this->reDeductRefundedWithdrawal($withdrawal);

            if (! $reDeduct['success']) {
                return $reDeduct;
            }

            $withdrawal->refresh();
        }

        $attempt = ((int) $withdrawal->retry_count) + 1;

        // Flag the attempt as in-flight before the HTTP call so a double click cannot
        // start a second payout while the first one is still running.
        $withdrawal->update([
            'payout_status' => 'processing',
            'payout_error' => null,
            'last_retry_at' => now(),
        ]);

        $result = $this->snippe->sendPayout([
            'amount' => (int) $withdrawal->amount,
            'phone' => (string) $phone,
            'name' => $withdrawal->account_name ?: $user->name,
            'network' => $this->snippe->normaliseNetwork($withdrawal->network ?: $withdrawal->method, (string) $phone),
            'narration' => 'Kutoa pesa - Winga Platform',
            'idempotency_key' => 'withdrawal-'.$withdrawal->id.'-attempt-'.$attempt,
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
                'payout_error' => null,
                'retry_count' => $attempt,
                'last_retry_at' => now(),
            ]);
        } else {
            $withdrawal->update([
                'payout_status' => 'failed',
                'payout_error' => Str::limit((string) ($result['error'] ?? 'Payout imeshindwa bila maelezo.'), 480),
                'retry_count' => $attempt,
                'last_retry_at' => now(),
            ]);
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

        $attempt = ((int) $withdrawal->retry_count) + 1;

        DB::transaction(function () use ($withdrawal, $user, $total, $attempt): void {
            $user->decrement('wallet_balance', $total);

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => $total,
                'description' => 'Retry ya kutoa pesa (#'.$withdrawal->id.')',
                'balance_after' => $user->fresh()->wallet_balance,
                'reference' => 'WD-'.$withdrawal->id.'-retry-'.$attempt,
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

    private function isBankTransfer(WithdrawalRequest $withdrawal): bool
    {
        return strtolower((string) $withdrawal->method) === 'bank_transfer';
    }
}
