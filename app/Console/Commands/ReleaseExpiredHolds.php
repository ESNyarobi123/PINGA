<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\WingaNotification;
use App\Services\SnippePayoutService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReleaseExpiredHolds extends Command
{
    protected $signature = 'app:release-expired-holds';

    protected $description = 'Auto-release payments for jobs with expired hold periods and pay the winga';

    public function handle(): int
    {
        $jobs = Job::where('status', 'in_progress')
            ->whereNotNull('code_hold_until')
            ->where('code_hold_until', '<=', now())
            ->whereHas('payment', fn ($q) => $q->where('status', 'escrowed'))
            ->with(['hiredWorker', 'employer'])
            ->get();

        if ($jobs->isEmpty()) {
            $this->info('Hakuna kazi zenye muda wa hold uliokwisha.');

            return self::SUCCESS;
        }

        foreach ($jobs as $job) {
            try {
                $payment = DB::transaction(function () use ($job): ?Payment {
                    /** @var Payment|null $payment */
                    $payment = Payment::query()
                        ->where('job_id', $job->id)
                        ->where('status', 'escrowed')
                        ->lockForUpdate()
                        ->latest('id')
                        ->first();

                    if (! $payment) {
                        return null;
                    }

                    $worker = $job->hiredWorker;

                    $job->update([
                        'status' => 'completed',
                        'code_used_at' => now(),
                        'code_hold_until' => null,
                    ]);

                    $payment->update([
                        'status' => 'released',
                        'escrow_released_at' => now(),
                        'payout_status' => 'processing',
                    ]);

                    if ($worker) {
                        Transaction::create([
                            'user_id' => $worker->id,
                            'payment_id' => $payment->id,
                            'type' => 'credit',
                            'amount' => $payment->worker_amount,
                            'description' => 'Malipo yaliyotolewa auto baada ya muda wa hold kuisha: '.$job->title,
                            'balance_after' => $worker->wallet_balance,
                            'reference' => 'auto-release-job-'.$job->id.'-'.now()->timestamp,
                            'status' => 'processing',
                        ]);
                    }

                    return $payment;
                });

                if (! $payment) {
                    continue;
                }

                $worker = $job->hiredWorker;

                if (! $worker) {
                    Log::warning("Auto-release skipped payout for job #{$job->id}: no hired worker");

                    continue;
                }

                $snippe = app(SnippePayoutService::class);
                $result = $snippe->sendPayout([
                    'amount' => (int) $payment->worker_amount,
                    'phone' => $worker->phone,
                    'name' => $worker->name,
                    'narration' => 'Auto-release malipo kazi #'.$job->id,
                    'idempotency_key' => 'auto-release-job-'.$job->id.'-'.now()->timestamp,
                    'metadata' => [
                        'type' => 'auto_release',
                        'job_id' => $job->id,
                        'payment_id' => $payment->id,
                        'worker_id' => $worker->id,
                    ],
                ]);

                if ($result['success']) {
                    $payment->update(['payout_reference' => $result['reference']]);
                } else {
                    $payment->update(['payout_status' => 'failed']);

                    Log::error("Auto-release payout failed for job #{$job->id}", $result);

                    $this->alertAdmins(
                        '🚨 Auto-release payout imeshindwa',
                        "Payout ya kazi #{$job->id} (TZS ".number_format((float) $payment->worker_amount).') imeshindwa: '.($result['error'] ?? 'Unknown error'),
                    );
                }

                $worker->notify(new WingaNotification(
                    title: '💰 Malipo Yametolewa Otomatiki!',
                    message: 'Muda wa tathmini kwa kazi "'.$job->title.'" umeisha. Malipo yako ya TZS '.number_format((float) $payment->worker_amount).' yametolewa.',
                    icon: 'banknotes',
                    color: 'green',
                    action_url: route('winga.mapato'),
                    action_label: 'Angalia Mapato',
                ));

                $job->employer?->notify(new WingaNotification(
                    title: '⏰ Muda wa Tathmini Umeisha',
                    message: 'Muda wa kushikilia malipo kwa kazi "'.$job->title.'" umeisha. Malipo yametolewa kwa winga otomatiki.',
                    icon: 'clock',
                    color: 'amber',
                    action_url: route('mteja.kazi-zangu'),
                    action_label: 'Angalia Kazi',
                ));

                $this->info("Released payment for job #{$job->id}");
                Log::info("Auto-released expired hold for job #{$job->id}");
            } catch (\Exception $e) {
                Log::error("Failed to auto-release hold for job #{$job->id}: ".$e->getMessage());
                $this->error("Failed for job #{$job->id}: ".$e->getMessage());
            }
        }

        return self::SUCCESS;
    }

    private function alertAdmins(string $title, string $message): void
    {
        User::where('role', 'admin')->get()->each(function (User $admin) use ($title, $message): void {
            $admin->notify(new WingaNotification(
                title: $title,
                message: $message,
                icon: 'exclamation-triangle',
                color: 'red',
                action_url: route('admin.maombi-kutoa'),
                action_label: 'Angalia Malipo',
            ));
        });
    }
}
