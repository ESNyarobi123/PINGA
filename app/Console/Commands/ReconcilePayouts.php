<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\WithdrawalRequest;
use App\Services\SnippePayoutService;
use Illuminate\Console\Command;

class ReconcilePayouts extends Command
{
    protected $signature = 'payouts:reconcile {--limit=25 : Maximum rows to poll per run} {--dry-run : Only report what would change}';

    protected $description = 'Poll Snippe for payouts stuck in "processing" and settle them when the webhook never arrived';

    public function handle(SnippePayoutService $snippe): int
    {
        $limit = max(1, (int) $this->option('limit'));
        $dryRun = (bool) $this->option('dry-run');
        $checked = 0;
        $completed = 0;
        $failed = 0;

        $withdrawals = WithdrawalRequest::query()
            ->where('payout_status', 'processing')
            ->whereNotNull('payout_reference')
            ->where('status', '!=', 'paid')
            ->latest('id')
            ->limit($limit)
            ->get();

        foreach ($withdrawals as $withdrawal) {
            $status = $this->providerStatus($snippe, (string) $withdrawal->payout_reference);

            if ($status === null) {
                continue;
            }

            $checked++;

            if ($this->isCompleted($status)) {
                $this->line("WD#{$withdrawal->id} {$withdrawal->payout_reference}: completed");

                if (! $dryRun) {
                    $snippe->handlePayoutWebhook([
                        'id' => $withdrawal->payout_reference,
                        'status' => 'completed',
                        'amount' => (float) $withdrawal->amount,
                        'metadata' => [
                            'type' => 'withdrawal',
                            'user_id' => $withdrawal->user_id,
                            'withdrawal_id' => $withdrawal->id,
                        ],
                    ]);
                }

                $completed++;

                continue;
            }

            if ($this->isFailed($status)) {
                $this->line("WD#{$withdrawal->id} {$withdrawal->payout_reference}: {$status} → refund");

                if (! $dryRun) {
                    $snippe->handlePayoutWebhook([
                        'id' => $withdrawal->payout_reference,
                        'status' => 'failed',
                        'amount' => (float) $withdrawal->amount,
                        'metadata' => [
                            'type' => 'withdrawal',
                            'user_id' => $withdrawal->user_id,
                            'withdrawal_id' => $withdrawal->id,
                        ],
                    ]);
                }

                $failed++;
            }
        }

        $payments = Payment::query()
            ->where('payout_status', 'processing')
            ->whereNotNull('payout_reference')
            ->latest('id')
            ->limit($limit)
            ->get();

        foreach ($payments as $payment) {
            $status = $this->providerStatus($snippe, (string) $payment->payout_reference);

            if ($status === null) {
                continue;
            }

            $checked++;

            if ($this->isCompleted($status)) {
                $this->line("PAY#{$payment->id} {$payment->payout_reference}: completed");

                if (! $dryRun) {
                    $snippe->handlePayoutWebhook([
                        'id' => $payment->payout_reference,
                        'status' => 'completed',
                        'amount' => (float) $payment->worker_amount,
                        'metadata' => [
                            'type' => 'payout',
                            'payment_id' => $payment->id,
                            'worker_id' => $payment->worker_id,
                        ],
                    ]);
                }

                $completed++;
            } elseif ($this->isFailed($status)) {
                $this->line("PAY#{$payment->id} {$payment->payout_reference}: {$status}");

                if (! $dryRun) {
                    $snippe->handlePayoutWebhook([
                        'id' => $payment->payout_reference,
                        'status' => 'failed',
                        'amount' => (float) $payment->worker_amount,
                        'metadata' => [
                            'type' => 'payout',
                            'payment_id' => $payment->id,
                            'worker_id' => $payment->worker_id,
                        ],
                    ]);
                }

                $failed++;
            }
        }

        if ($checked === 0) {
            $this->info('Hakuna payout inayosubiri uthibitisho.');

            return self::SUCCESS;
        }

        $this->info("Payouts zilizokaguliwa: {$checked}. Zilizokamilika: {$completed}. Zilizofeli: {$failed}.");

        return self::SUCCESS;
    }

    private function providerStatus(SnippePayoutService $snippe, string $reference): ?string
    {
        $result = $snippe->getPayoutStatus($reference);

        if (! ($result['success'] ?? false)) {
            return null;
        }

        $data = $result['data'] ?? [];
        $status = $data['data']['status'] ?? $data['status'] ?? null;

        return is_string($status) ? strtolower($status) : null;
    }

    private function isCompleted(string $status): bool
    {
        return in_array($status, ['completed', 'success', 'successful', 'paid'], true);
    }

    private function isFailed(string $status): bool
    {
        return in_array($status, ['failed', 'error', 'cancelled', 'canceled', 'rejected', 'expired'], true);
    }
}
