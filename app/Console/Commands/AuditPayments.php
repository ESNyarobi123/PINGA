<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\WithdrawalRequest;
use App\Services\SettingsService;
use Illuminate\Console\Command;

class AuditPayments extends Command
{
    protected $signature = 'payments:audit {--days=7 : How far back to look for stuck transactions} {--fix : Repair withdrawal/payout statuses that are obviously out of sync}';

    protected $description = 'Audit escrow, payouts, withdrawals and subscriptions for inconsistencies (use --fix to repair ledger statuses)';

    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $rate = (float) SettingsService::commissionRate();
        $rows = [];
        $critical = false;

        if ($this->option('fix')) {
            $this->repairWithdrawalLedger();
        }

        $duplicateJobs = Payment::query()
            ->selectRaw('job_id, COUNT(*) as total')
            ->whereNotNull('job_id')
            ->whereIn('status', ['escrowed', 'released'])
            ->groupBy('job_id')
            ->having('total', '>', 1)
            ->pluck('job_id');

        $rows[] = ['Jobs with more than one active payment', $duplicateJobs->count(), $duplicateJobs->take(10)->implode(', ')];
        $critical = $critical || $duplicateJobs->isNotEmpty();

        $duplicateRequests = Payment::query()
            ->selectRaw('service_request_id, COUNT(*) as total')
            ->whereNotNull('service_request_id')
            ->whereIn('status', ['escrowed', 'released'])
            ->groupBy('service_request_id')
            ->having('total', '>', 1)
            ->pluck('service_request_id');

        $rows[] = ['Service requests with more than one active payment', $duplicateRequests->count(), $duplicateRequests->take(10)->implode(', ')];
        $critical = $critical || $duplicateRequests->isNotEmpty();

        $feeMismatches = Payment::query()
            ->where('status', 'escrowed')
            ->get()
            ->filter(function (Payment $payment) use ($rate): bool {
                $expected = round((float) $payment->amount * ($rate / 100), 2);

                return abs((float) $payment->platform_fee - $expected) > 0.01;
            })
            ->pluck('id');

        $rows[] = ['Escrowed payments whose fee does not match the commission rate', $feeMismatches->count(), $feeMismatches->take(10)->implode(', ')];
        $critical = $critical || $feeMismatches->isNotEmpty();

        $missingReleaseDate = Payment::query()
            ->where('status', 'released')
            ->whereNull('escrow_released_at')
            ->count();

        $rows[] = ['Released payments without escrow_released_at', $missingReleaseDate, ''];
        $critical = $critical || $missingReleaseDate > 0;

        $paidButProcessing = WithdrawalRequest::query()
            ->where('status', 'paid')
            ->where(function ($q): void {
                $q->whereNull('payout_status')->orWhere('payout_status', '!=', 'completed');
            })
            ->count();

        $rows[] = ['Withdrawals marked paid but payout not completed', $paidButProcessing, ''];
        $critical = $critical || $paidButProcessing > 0;

        $paidTransactionStuck = Transaction::query()
            ->where('type', 'withdrawal')
            ->where('status', 'processing')
            ->where('created_at', '<', now()->subDays($days))
            ->count();

        $rows[] = ["Withdrawal transactions still processing after {$days} days", $paidTransactionStuck, ''];
        $critical = $critical || $paidTransactionStuck > 0;

        $legacyWithdrawalRows = Transaction::query()
            ->where('type', 'withdrawal')
            ->where('status', 'processing')
            ->whereNull('reference')
            ->count();

        $rows[] = ['Withdrawal transactions without a reference', $legacyWithdrawalRows, ''];
        $critical = $critical || $legacyWithdrawalRows > 0;

        $stuckCredits = Transaction::query()
            ->where('type', 'credit')
            ->where('status', 'processing')
            ->where('created_at', '<', now()->subDays($days))
            ->count();

        $rows[] = ["Worker credits still processing after {$days} days", $stuckCredits, ''];
        $critical = $critical || $stuckCredits > 0;

        $stuckSubscriptions = Subscription::query()
            ->where('payment_status', 'pending')
            ->where('created_at', '<', now()->subDay())
            ->count();

        $rows[] = ['Subscription payments pending for over 24 hours', $stuckSubscriptions, ''];
        $critical = $critical || $stuckSubscriptions > 0;

        $duplicateRefundReferences = Transaction::query()
            ->select('reference')
            ->whereNotNull('reference')
            ->whereIn('type', ['credit'])
            ->groupBy('reference')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('reference');

        $rows[] = ['Duplicate transaction references', $duplicateRefundReferences->count(), $duplicateRefundReferences->take(10)->implode(', ')];
        $critical = $critical || $duplicateRefundReferences->isNotEmpty();

        $this->newLine();
        $this->table(['Check', 'Count', 'Sample IDs'], $rows);
        $this->line('Commission rate used: '.number_format($rate, 2).'%');
        $this->newLine();

        if ($critical) {
            $this->warn('Audit found issues that need attention.');

            return self::FAILURE;
        }

        $this->info('No payment inconsistencies detected.');

        return self::SUCCESS;
    }

    /**
     * Align withdrawal ledger rows with the withdrawal status.
     */
    private function repairWithdrawalLedger(): void
    {
        $paidIds = WithdrawalRequest::query()->where('status', 'paid')->pluck('id');
        $rejectedIds = WithdrawalRequest::query()->whereIn('status', ['rejected', 'refunded'])->pluck('id');

        $completed = Transaction::query()
            ->where('type', 'withdrawal')
            ->where('status', 'processing')
            ->whereIn('reference', $paidIds->map(fn (int $id) => 'WD-'.$id))
            ->update(['status' => 'completed']);

        $failed = Transaction::query()
            ->where('type', 'withdrawal')
            ->where('status', 'processing')
            ->whereIn('reference', $rejectedIds->map(fn (int $id) => 'WD-'.$id))
            ->update(['status' => 'failed']);

        // Legacy rows created before references were stored: match by user, amount and creation time.
        $legacyFixed = 0;

        Transaction::query()
            ->where('type', 'withdrawal')
            ->where('status', 'processing')
            ->whereNull('reference')
            ->get()
            ->each(function (Transaction $transaction) use (&$legacyFixed): void {
                $withdrawal = WithdrawalRequest::query()
                    ->where('user_id', $transaction->user_id)
                    ->where('amount', $transaction->amount)
                    ->whereIn('status', ['paid', 'rejected', 'refunded'])
                    ->whereBetween('created_at', [
                        $transaction->created_at->copy()->subMinutes(5),
                        $transaction->created_at->copy()->addMinutes(5),
                    ])
                    ->orderBy('id')
                    ->first();

                if (! $withdrawal) {
                    return;
                }

                $reference = 'WD-'.$withdrawal->id;
                $referenceTaken = Transaction::query()
                    ->where('reference', $reference)
                    ->whereKeyNot($transaction->id)
                    ->exists();

                $transaction->update([
                    'status' => $withdrawal->status === 'paid' ? 'completed' : 'failed',
                    'reference' => $referenceTaken ? $transaction->reference : $reference,
                ]);

                $legacyFixed++;
            });

        $syncedPaid = WithdrawalRequest::query()
            ->where('status', 'paid')
            ->where(function ($q): void {
                $q->whereNull('payout_status')->orWhere('payout_status', '!=', 'completed');
            })
            ->update(['payout_status' => 'completed']);

        $this->line("Ledger repair: {$completed} withdrawal transactions completed, {$failed} marked failed, {$legacyFixed} legacy rows matched, {$syncedPaid} paid withdrawals synced.");
    }
}
