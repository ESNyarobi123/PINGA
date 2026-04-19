<?php

namespace App\Livewire\Winga;

use App\Models\Job;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\WingaNotification;
use App\Services\SnippePayoutService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class WekaCode extends Component
{
    public string $code = '';

    public bool $verified = false;

    public ?string $error = null;

    public ?int $jobId = null;

    public ?Job $job = null;

    public array $myActiveJobs = [];

    public int $failedAttempts = 0;

    public function mount(): void
    {
        $workerId = auth()->id();
        $this->myActiveJobs = Job::where('hired_worker_id', $workerId)
            ->where('status', 'in_progress')
            ->with('employer:id,name')
            ->get(['id', 'title', 'employer_id'])
            ->toArray();
    }

    public function selectJob(int $jobId): void
    {
        $this->jobId        = $jobId;
        $this->job          = Job::where('id', $jobId)
            ->where('hired_worker_id', auth()->id())
            ->with(['employer:id,name,phone', 'payment'])
            ->first();
        $this->error        = null;
        $this->code         = '';
        $this->verified     = false;
        $this->failedAttempts = 0;
    }

    public function verify(): void
    {
        $this->validate(['code' => 'required|string|size:6']);

        if (! $this->job) {
            $this->error = 'Tafadhali chagua kazi kwanza.';
            return;
        }

        // 3-hour hold check
        if ($this->job->isOnCodeHold()) {
            $releaseIn   = now()->diffForHumans($this->job->code_hold_until, ['parts' => 2]);
            $this->error = "Muajili ameweka kazi hii kwenye hali ya tathmini. Code itaweza kutumika baada ya {$releaseIn}. Wasiliana na muajili wako.";
            return;
        }

        if (! $this->job->verifyCompletionCode($this->code)) {
            $this->failedAttempts++;
            $this->code  = '';
            $this->error = 'Code si sahihi. Mwambie muajili wako akuambie code yake.';

            // 3 failed attempts → alert admin as suspicious
            if ($this->failedAttempts >= 3) {
                $this->alertAdminSuspiciousActivity();
                $this->error .= ' Majaribio mengi yamefanywa. Admin amearifiwa.';
            }

            return;
        }

        // === CODE CORRECT: run payment release in DB transaction ===
        DB::transaction(function () {
            $worker  = auth()->user();
            $payment = $this->job->payment;

            // 1. Complete the job
            $this->job->update([
                'status'       => 'completed',
                'code_used_at' => now(),
            ]);

            if (! $payment || $payment->status !== 'escrowed') {
                return;
            }

            $workerAmount = (float) $payment->worker_amount;

            // 2. Credit worker's wallet immediately
            $worker->increment('wallet_balance', $workerAmount);

            // 3. Mark payment as released
            $payment->update([
                'status'             => 'released',
                'escrow_released_at' => now(),
                'payout_status'      => 'completed',
            ]);

            // 4. Create completed transaction for worker
            Transaction::create([
                'user_id'       => $worker->id,
                'payment_id'    => $payment->id,
                'type'          => 'credit',
                'amount'        => $workerAmount,
                'description'   => 'Malipo ya kazi: ' . $this->job->title,
                'balance_after' => $worker->fresh()->wallet_balance,
                'status'        => 'completed',
            ]);
        });

        // 5. Notify employer
        if ($this->job->employer) {
            $this->job->employer->notify(new WingaNotification(
                title: '✅ Kazi Imekamilika!',
                message: auth()->user()->name . ' ameweka code — kazi "' . $this->job->title . '" imekamilika. Malipo yametumwa kwa Winga.',
                icon: 'check-circle',
                color: 'green',
                action_url: route('mteja.kazi-zangu'),
                action_label: 'Angalia Kazi',
            ));
        }

        // 6. Notify worker
        $payment = $this->job->fresh()->payment;
        auth()->user()->notify(new WingaNotification(
            title: '💸 Malipo Yanakuja!',
            message: 'Code imethibitishwa! TZS ' . number_format($payment->worker_amount ?? 0) . ' yanakuja kwenye simu yako hivi karibuni.',
            icon: 'banknotes',
            color: 'green',
            action_url: route('winga.mapato'),
            action_label: 'Angalia Mapato',
        ));

        $this->error    = null;
        $this->verified = true;
        $this->dispatch('toast', message: 'Code imethibitishwa! Pesa ya TZS ' . number_format($payment->worker_amount ?? 0) . ' inakuja kwenye simu yako.', type: 'success');
    }

    protected function alertAdminSuspiciousActivity(): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new WingaNotification(
                title: '⚠️ Majaribio ya Kutata ya Code',
                message: auth()->user()->name . ' amejaribu code vibaya mara 3+ kwenye kazi "' . ($this->job->title ?? '') . '".',
                icon: 'exclamation-triangle',
                color: 'amber',
                action_url: route('admin.kazi'),
                action_label: 'Angalia',
            ));
        }
    }

    public function render()
    {
        return view('livewire.winga.weka-code')
            ->layout('layouts.winga')
            ->title('Weka Code ya Kukamilisha Kazi');
    }
}
