<?php

namespace App\Console\Commands;

use App\Livewire\Admin\Malipo;
use App\Models\Payment;
use Illuminate\Console\Command;

class CreditMissingEscrowRefunds extends Command
{
    protected $signature = 'payments:credit-missing-refunds {--dry-run : Report refunds that never reached the client wallet}';

    protected $description = 'Credit clients for escrow payments marked refunded without a wallet credit.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $credited = 0;

        Payment::query()
            ->where('status', 'refunded')
            ->with('employer')
            ->orderBy('id')
            ->each(function (Payment $payment) use ($dryRun, &$credited) {
                $alreadyCredited = $payment->transactions()
                    ->where('type', 'credit')
                    ->where('user_id', $payment->employer_id)
                    ->exists();

                if ($alreadyCredited || ! $payment->employer) {
                    return;
                }

                $this->line(sprintf(
                    'payment #%d employer #%d amount=%s',
                    $payment->id,
                    $payment->employer_id,
                    number_format((float) $payment->amount, 2),
                ));

                if ($dryRun) {
                    return;
                }

                app(Malipo::class)->creditEscrowRefund($payment);
                $credited++;
            });

        $this->info($dryRun
            ? 'Dry run only. No wallets were changed.'
            : "Credited {$credited} missing refund(s).");

        return self::SUCCESS;
    }
}
