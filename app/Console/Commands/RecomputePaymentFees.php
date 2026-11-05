<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\SettingsService;
use Illuminate\Console\Command;

class RecomputePaymentFees extends Command
{
    protected $signature = 'payments:recompute-fees {--dry-run : Report mismatches without changing anything}';

    protected $description = 'Recompute escrow platform fees at the current commission rate (released rows are only reported).';

    public function handle(): int
    {
        $rate = SettingsService::commissionRate();
        $dryRun = (bool) $this->option('dry-run');

        $this->info("Current commission rate: {$rate}%".($dryRun ? ' (dry run)' : ''));

        $escrowed = Payment::query()->where('status', 'escrowed')->get();
        $updated = 0;
        $mismatchedEscrow = 0;

        foreach ($escrowed as $payment) {
            $expected = Payment::calculateFromWorkerBid((float) $payment->amount);

            if ($this->matches($payment, $expected)) {
                continue;
            }

            $mismatchedEscrow++;
            $this->line(sprintf(
                'escrow #%d: amount=%s fee=%s -> %s worker=%s -> %s',
                $payment->id,
                number_format((float) $payment->amount, 2),
                number_format((float) $payment->platform_fee, 2),
                number_format($expected['platform_fee'], 2),
                number_format((float) $payment->worker_amount, 2),
                number_format($expected['worker_amount'], 2),
            ));

            if (! $dryRun) {
                $payment->update([
                    'platform_fee' => $expected['platform_fee'],
                    'worker_amount' => $expected['worker_amount'],
                ]);
                $updated++;
            }
        }

        $releasedMismatches = Payment::query()
            ->where('status', 'released')
            ->get()
            ->filter(fn (Payment $payment) => ! $this->matches($payment, Payment::calculateFromWorkerBid((float) $payment->amount)));

        foreach ($releasedMismatches as $payment) {
            $expected = Payment::calculateFromWorkerBid((float) $payment->amount);
            $this->warn(sprintf(
                'released #%d (historical, not changed): fee=%s expected=%s',
                $payment->id,
                number_format((float) $payment->platform_fee, 2),
                number_format($expected['platform_fee'], 2),
            ));
        }

        $this->info(sprintf(
            'Escrow mismatches: %d, updated: %d. Released mismatches (reported only): %d.',
            $mismatchedEscrow,
            $updated,
            $releasedMismatches->count(),
        ));

        return self::SUCCESS;
    }

    /**
     * @param  array{amount: float, platform_fee: float, worker_amount: float}  $expected
     */
    private function matches(Payment $payment, array $expected): bool
    {
        return abs((float) $payment->platform_fee - $expected['platform_fee']) < 0.01
            && abs((float) $payment->worker_amount - $expected['worker_amount']) < 0.01;
    }
}
