<?php

namespace App\Livewire\Winga;

use App\Models\Job;
use App\Models\ServiceRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\WingaNotification;
use App\Support\ServicePackageSchema;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WekaCode extends Component
{
    public string $code = '';

    public bool $verified = false;

    public ?string $error = null;

    public ?int $jobId = null;

    public ?Job $job = null;

    public ?int $serviceRequestId = null;

    public ?ServiceRequest $serviceRequest = null;

    public int $failedAttempts = 0;

    /**
     * @return array{myActiveJobs: array, myActiveServiceRequests: array, awaitingPaymentServiceRequests: array}
     */
    private function wekaCodeListsForWorker(int $workerId): array
    {
        $myActiveJobs = Job::where('hired_worker_id', $workerId)
            ->where('status', 'in_progress')
            ->with('employer:id,name')
            ->get(['id', 'title', 'employer_id'])
            ->toArray();

        $srWith = ['client:id,name', 'service:id,title'];
        if (ServicePackageSchema::hasPackagesTable()) {
            $srWith[] = 'package:id,title';
        }

        $myActiveServiceRequests = ServiceRequest::query()
            ->where('status', 'in_progress')
            ->whereHas('service', fn ($q) => $q->where('user_id', $workerId))
            ->whereHas('payment', fn ($q) => $q->where('status', 'escrowed'))
            ->with($srWith)
            ->get(['id', 'service_id', 'client_id', 'service_package_id'])
            ->toArray();

        $awaitingPaymentServiceRequests = ServiceRequest::query()
            ->where('status', 'accepted')
            ->whereHas('service', fn ($q) => $q->where('user_id', $workerId))
            ->whereDoesntHave('payment')
            ->with($srWith)
            ->get(['id', 'service_id', 'client_id', 'service_package_id'])
            ->toArray();

        return [
            'myActiveJobs' => $myActiveJobs,
            'myActiveServiceRequests' => $myActiveServiceRequests,
            'awaitingPaymentServiceRequests' => $awaitingPaymentServiceRequests,
        ];
    }

    public function selectJob(int $jobId): void
    {
        $this->jobId = $jobId;
        $this->job = Job::where('id', $jobId)
            ->where('hired_worker_id', auth()->id())
            ->with(['employer:id,name,phone', 'payment'])
            ->first();
        $this->serviceRequestId = null;
        $this->serviceRequest = null;
        $this->error = null;
        $this->code = '';
        $this->verified = false;
        $this->failedAttempts = 0;
    }

    public function selectServiceRequest(int $serviceRequestId): void
    {
        $workerId = auth()->id();
        $this->serviceRequest = ServiceRequest::query()
            ->where('id', $serviceRequestId)
            ->where('status', 'in_progress')
            ->whereHas('service', fn ($q) => $q->where('user_id', $workerId))
            ->with(['client:id,name,phone', 'payment', 'service:id,title'])
            ->when(ServicePackageSchema::hasPackagesTable(), fn ($q) => $q->with('package:id,title'))
            ->first();

        $this->serviceRequestId = $this->serviceRequest?->id;
        $this->jobId = null;
        $this->job = null;
        $this->error = null;
        $this->code = '';
        $this->verified = false;
        $this->failedAttempts = 0;
    }

    public function verify(): void
    {
        $this->validate(['code' => 'required|string|size:6']);

        if ($this->job) {
            $this->verifyJob();

            return;
        }

        if ($this->serviceRequest) {
            $this->verifyServiceRequest();

            return;
        }

        $this->error = __('messages.weka_code.select_first');
    }

    private function verifyJob(): void
    {
        $job = $this->job;
        if (! $job) {
            return;
        }

        if ($job->isOnCodeHold()) {
            $releaseIn = now()->diffForHumans($job->code_hold_until, ['parts' => 2]);
            $this->error = __('messages.weka_code.hold_error', ['when' => $releaseIn]);

            return;
        }

        if (! $job->verifyCompletionCode($this->code)) {
            $this->failedAttempts++;
            $this->code = '';
            $this->error = __('messages.weka_code.wrong_code');
            if ($this->failedAttempts >= 3) {
                $this->alertAdminSuspiciousActivityJob($job);
                $this->error .= ' '.__('messages.weka_code.admin_notified');
            }

            return;
        }

        DB::transaction(function () use ($job) {
            $worker = auth()->user();
            $payment = $job->payment;

            $job->update([
                'status' => 'completed',
                'code_used_at' => now(),
            ]);

            if (! $payment || $payment->status !== 'escrowed') {
                return;
            }

            $workerAmount = (float) $payment->worker_amount;

            $worker->increment('wallet_balance', $workerAmount);

            $payment->update([
                'status' => 'released',
                'escrow_released_at' => now(),
                'payout_status' => 'completed',
            ]);

            Transaction::create([
                'user_id' => $worker->id,
                'payment_id' => $payment->id,
                'type' => 'credit',
                'amount' => $workerAmount,
                'description' => 'Malipo ya kazi: '.$job->title,
                'balance_after' => $worker->fresh()->wallet_balance,
                'status' => 'completed',
            ]);
        });

        $job->refresh();

        if ($job->employer) {
            $job->employer->notify(new WingaNotification(
                title: __('messages.weka_code.notify_employer_done_title'),
                message: __('messages.weka_code.notify_employer_done_job', [
                    'worker' => auth()->user()->name,
                    'title' => $job->title,
                ]),
                icon: 'check-circle',
                color: 'green',
                action_url: route('mteja.kazi-zangu'),
                action_label: __('messages.weka_code.notify_employer_action'),
            ));
        }

        $payment = $job->fresh()->payment;
        auth()->user()->notify(new WingaNotification(
            title: __('messages.weka_code.notify_worker_paid_title'),
            message: __('messages.weka_code.notify_worker_paid_body', [
                'amount' => number_format($payment->worker_amount ?? 0),
            ]),
            icon: 'banknotes',
            color: 'green',
            action_url: route('winga.mapato'),
            action_label: __('messages.weka_code.notify_worker_action'),
        ));

        $this->finishVerifySuccess($payment->worker_amount ?? 0);
    }

    private function verifyServiceRequest(): void
    {
        $req = $this->serviceRequest;
        if (! $req) {
            return;
        }

        if ($req->isOnCodeHold()) {
            $releaseIn = now()->diffForHumans($req->code_hold_until, ['parts' => 2]);
            $this->error = __('messages.weka_code.hold_error', ['when' => $releaseIn]);

            return;
        }

        if (! $req->verifyCompletionCode($this->code)) {
            $this->failedAttempts++;
            $this->code = '';
            $this->error = __('messages.weka_code.wrong_code');
            if ($this->failedAttempts >= 3) {
                $this->alertAdminSuspiciousActivityServiceRequest($req);
                $this->error .= ' '.__('messages.weka_code.admin_notified');
            }

            return;
        }

        DB::transaction(function () use ($req) {
            $worker = auth()->user();
            $payment = $req->payment;

            $req->update([
                'status' => 'completed',
                'code_used_at' => now(),
            ]);

            if (! $payment || $payment->status !== 'escrowed') {
                return;
            }

            $workerAmount = (float) $payment->worker_amount;

            $worker->increment('wallet_balance', $workerAmount);

            $payment->update([
                'status' => 'released',
                'escrow_released_at' => now(),
                'payout_status' => 'completed',
            ]);

            $label = $req->service->title ?? 'huduma';

            Transaction::create([
                'user_id' => $worker->id,
                'payment_id' => $payment->id,
                'type' => 'credit',
                'amount' => $workerAmount,
                'description' => 'Malipo ya huduma: '.$label,
                'balance_after' => $worker->fresh()->wallet_balance,
                'status' => 'completed',
            ]);
        });

        $req->refresh();

        if ($req->client) {
            $req->client->notify(new WingaNotification(
                title: __('messages.weka_code.notify_client_done_title'),
                message: __('messages.weka_code.notify_client_done_service', [
                    'worker' => auth()->user()->name,
                    'title' => $req->service->title ?? '',
                ]),
                icon: 'check-circle',
                color: 'green',
                action_url: route('mteja.huduma-malipo'),
                action_label: __('messages.weka_code.notify_client_action'),
            ));
        }

        $payment = $req->fresh()->payment;
        auth()->user()->notify(new WingaNotification(
            title: __('messages.weka_code.notify_worker_paid_title'),
            message: __('messages.weka_code.notify_worker_paid_body', [
                'amount' => number_format($payment->worker_amount ?? 0),
            ]),
            icon: 'banknotes',
            color: 'green',
            action_url: route('winga.mapato'),
            action_label: __('messages.weka_code.notify_worker_action'),
        ));

        $this->finishVerifySuccess($payment->worker_amount ?? 0);
    }

    private function finishVerifySuccess(float $workerAmount): void
    {
        $this->error = null;
        $this->verified = true;
        $this->dispatch('toast', message: __('messages.weka_code.toast_success', ['amount' => number_format($workerAmount)]), type: 'success');
    }

    protected function alertAdminSuspiciousActivityJob(Job $job): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new WingaNotification(
                title: __('messages.weka_code.admin_alert_title'),
                message: __('messages.weka_code.admin_alert_job', [
                    'worker' => auth()->user()->name,
                    'title' => $job->title ?? '',
                ]),
                icon: 'exclamation-triangle',
                color: 'amber',
                action_url: route('admin.kazi'),
                action_label: __('messages.weka_code.admin_alert_action'),
            ));
        }
    }

    protected function alertAdminSuspiciousActivityServiceRequest(ServiceRequest $req): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new WingaNotification(
                title: __('messages.weka_code.admin_alert_title'),
                message: __('messages.weka_code.admin_alert_service', [
                    'worker' => auth()->user()->name,
                    'title' => $req->service->title ?? '',
                ]),
                icon: 'exclamation-triangle',
                color: 'amber',
                action_url: route('admin.kazi'),
                action_label: __('messages.weka_code.admin_alert_action'),
            ));
        }
    }

    public function render()
    {
        $lists = $this->wekaCodeListsForWorker((int) auth()->id());

        return view('livewire.winga.weka-code', $lists)
            ->layout('layouts.winga')
            ->title(__('messages.weka_code.title'));
    }
}
