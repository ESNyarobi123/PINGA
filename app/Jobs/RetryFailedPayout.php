<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Notifications\WingaNotification;
use App\Services\SnippePayoutService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class RetryFailedPayout implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 3600; // 1 hour between retries

    public function __construct(
        public readonly string $originalReference,
        public readonly string $type,
        public readonly ?int $paymentId,
        public readonly ?int $withdrawalId,
        public readonly ?int $workerId,
        public readonly float $amount,
    ) {}

    public function handle(SnippePayoutService $snippe): void
    {
        Log::info("RetryFailedPayout: attempting retry for {$this->originalReference}");

        if ($this->type === 'withdrawal' && $this->withdrawalId) {
            $withdrawal = WithdrawalRequest::find($this->withdrawalId);

            if (! $withdrawal || $withdrawal->payout_status === 'completed') {
                return;
            }

            // The payout webhook refunds the wallet and marks the request rejected.
            // Retrying it automatically would pay the user a second time.
            if (in_array($withdrawal->status, ['rejected', 'refunded'], true)) {
                Log::info("RetryFailedPayout: withdrawal {$this->withdrawalId} was already refunded; skipping.");

                return;
            }

            $worker = User::find($this->workerId);
            if (! $worker) {
                return;
            }

            $result = $snippe->sendPayout([
                'amount' => (int) $this->amount,
                'phone' => $worker->phone,
                'name' => $worker->name,
                'narration' => 'Retry: Kutoa pesa - Winga Platform',
                'idempotency_key' => 'retry-withdrawal-'.$this->withdrawalId.'-'.now()->timestamp,
                'metadata' => [
                    'type' => 'withdrawal',
                    'user_id' => $this->workerId,
                    'withdrawal_id' => $this->withdrawalId,
                ],
            ]);

            if ($result['success']) {
                $withdrawal->update([
                    'payout_reference' => $result['reference'],
                    'payout_status' => 'processing',
                    'status' => 'pending',
                ]);

                Log::info("RetryFailedPayout: withdrawal {$this->withdrawalId} retried successfully.");
            } else {
                Log::error("RetryFailedPayout: withdrawal {$this->withdrawalId} retry failed again.", $result);
            }

            return;
        }

        if ($this->type === 'payout' && $this->paymentId) {
            $payment = Payment::with(['worker', 'job'])->find($this->paymentId);

            if (! $payment || $payment->payout_status === 'completed') {
                return;
            }

            if ($payment->status === 'refunded') {
                Log::info("RetryFailedPayout: payment {$this->paymentId} was refunded; skipping.");

                return;
            }

            $result = $snippe->sendPayout([
                'amount' => (int) $payment->worker_amount,
                'phone' => $payment->worker->phone,
                'name' => $payment->worker->name,
                'narration' => 'Retry: Malipo #'.$payment->id.' - '.($payment->escrowItemLabel() ?: ($payment->job?->title ?? 'huduma')),
                'idempotency_key' => 'retry-payout-'.$this->paymentId.'-'.now()->timestamp,
                'metadata' => [
                    'type' => 'payout',
                    'job_id' => $payment->job_id,
                    'payment_id' => $this->paymentId,
                    'worker_id' => $payment->worker_id,
                ],
            ]);

            if ($result['success']) {
                $payment->update([
                    'payout_reference' => $result['reference'],
                    'payout_status' => 'processing',
                ]);

                Log::info("RetryFailedPayout: payment {$this->paymentId} retried successfully.");
            } else {
                Log::error("RetryFailedPayout: payment {$this->paymentId} retry failed again.", $result);

                // After all retries exhausted, alert admin
                if ($this->attempts() >= $this->tries) {
                    $admins = User::where('role', 'admin')->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new WingaNotification(
                            title: '🚨 Payout Imeshindwa Mara Zote',
                            message: "Payout #{$this->paymentId} imeshindwa baada ya majaribio {$this->tries}. Inahitaji uingiliaji wa mkono.",
                            icon: 'x-circle',
                            color: 'red',
                            action_url: route('admin.maombi-kutoa'),
                            action_label: 'Angalia',
                        ));
                    }
                }
            }
        }
    }
}
