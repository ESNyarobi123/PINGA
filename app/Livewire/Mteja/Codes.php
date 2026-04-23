<?php

namespace App\Livewire\Mteja;

use App\Models\Job;
use App\Models\ServiceRequest;
use App\Notifications\WingaNotification;
use App\Support\ServicePackageSchema;
use Livewire\Component;
use Livewire\WithPagination;

class Codes extends Component
{
    use WithPagination;

    public array $generatedCodes = [];

    public string $holdComment = '';

    public ?string $extendingHoldKind = null;

    public ?int $extendingHoldId = null;

    public function generateCode(int $jobId): void
    {
        $job = Job::where('employer_id', auth()->id())
            ->where('id', $jobId)
            ->where('status', 'in_progress')
            ->first();

        if (! $job) {
            return;
        }

        $code = $job->generateCompletionCode();
        $this->generatedCodes[$jobId] = $code;

        if ($job->hiredWorker) {
            $job->hiredWorker->notify(new WingaNotification(
                title: __('messages.codes.notify_worker_code_title'),
                message: __('messages.codes.notify_worker_code_job', ['title' => $job->title]),
                icon: 'key',
                color: 'green',
                action_url: route('winga.weka-code'),
                action_label: __('messages.codes.notify_worker_code_action')
            ));
        }

        $this->dispatch('toast', message: __('messages.codes.toast_code_generated'), type: 'success');
    }

    public function generateServiceRequestCode(int $serviceRequestId): void
    {
        $req = ServiceRequest::query()
            ->where('client_id', auth()->id())
            ->where('id', $serviceRequestId)
            ->where('status', 'in_progress')
            ->whereHas('payment', fn ($q) => $q->where('status', 'escrowed'))
            ->with('service.user')
            ->first();

        if (! $req) {
            return;
        }

        $code = $req->generateCompletionCode();
        $this->generatedCodes['sr_'.$serviceRequestId] = $code;

        $winga = $req->service->user;
        if ($winga) {
            $winga->notify(new WingaNotification(
                title: __('messages.codes.notify_worker_code_title'),
                message: __('messages.codes.notify_worker_code_service', ['title' => $req->service->title]),
                icon: 'key',
                color: 'green',
                action_url: route('winga.weka-code'),
                action_label: __('messages.codes.notify_worker_code_action')
            ));
        }

        $this->dispatch('toast', message: __('messages.codes.toast_code_generated'), type: 'success');
    }

    public function holdCode(int $jobId): void
    {
        $job = Job::where('employer_id', auth()->id())
            ->where('id', $jobId)
            ->where('status', 'in_progress')
            ->with('hiredWorker:id,name')
            ->first();

        if (! $job) {
            return;
        }

        $job->holdCode(3);

        if ($job->hiredWorker) {
            $job->hiredWorker->notify(new WingaNotification(
                title: __('messages.codes.notify_hold_title'),
                message: __('messages.codes.notify_hold_job', ['title' => $job->title]),
                icon: 'clock',
                color: 'amber',
                action_url: route('messages'),
                action_label: __('messages.codes.notify_hold_action')
            ));
        }

        $this->dispatch('toast', message: __('messages.codes.toast_hold'), type: 'warning');
    }

    public function holdServiceRequestCode(int $serviceRequestId): void
    {
        $req = ServiceRequest::query()
            ->where('client_id', auth()->id())
            ->where('id', $serviceRequestId)
            ->where('status', 'in_progress')
            ->with(['service.user:id,name', 'service:id,title,user_id'])
            ->first();

        if (! $req) {
            return;
        }

        $req->holdCode(3);

        $winga = $req->service->user;
        if ($winga) {
            $winga->notify(new WingaNotification(
                title: __('messages.codes.notify_hold_title'),
                message: __('messages.codes.notify_hold_service', ['title' => $req->service->title]),
                icon: 'clock',
                color: 'amber',
                action_url: route('messages'),
                action_label: __('messages.codes.notify_hold_action')
            ));
        }

        $this->dispatch('toast', message: __('messages.codes.toast_hold'), type: 'warning');
    }

    public function openExtendForm(string $kind, int $id): void
    {
        $this->extendingHoldKind = $kind;
        $this->extendingHoldId = $id;
        $this->holdComment = '';
    }

    public function closeExtendForm(): void
    {
        $this->extendingHoldKind = null;
        $this->extendingHoldId = null;
        $this->holdComment = '';
    }

    public function extendHold(): void
    {
        if (! $this->extendingHoldKind || ! $this->extendingHoldId) {
            return;
        }

        $this->validate([
            'holdComment' => 'required|string|min:10|max:500',
        ], [
            'holdComment.required' => __('messages.codes.extend_comment_required'),
            'holdComment.min' => __('messages.codes.extend_comment_min'),
        ]);

        if ($this->extendingHoldKind === 'job') {
            $this->extendHoldJob();

            return;
        }

        if ($this->extendingHoldKind === 'service_request') {
            $this->extendHoldServiceRequest();
        }
    }

    private function extendHoldJob(): void
    {
        $job = Job::where('employer_id', auth()->id())
            ->where('id', $this->extendingHoldId)
            ->where('status', 'in_progress')
            ->with('hiredWorker:id,name')
            ->first();

        if (! $job) {
            return;
        }

        if (! $job->extendHold($this->holdComment)) {
            $this->dispatch('toast', message: __('messages.codes.extend_once'), type: 'error');

            return;
        }

        if ($job->hiredWorker) {
            $job->hiredWorker->notify(new WingaNotification(
                title: __('messages.codes.notify_extend_title'),
                message: __('messages.codes.notify_extend_job', [
                    'title' => $job->title,
                    'comment' => $this->holdComment,
                ]),
                icon: 'clock',
                color: 'amber',
                action_url: route('messages'),
                action_label: __('messages.codes.notify_hold_action')
            ));
        }

        $this->closeExtendForm();
        $this->dispatch('toast', message: __('messages.codes.toast_extend'), type: 'warning');
    }

    private function extendHoldServiceRequest(): void
    {
        $req = ServiceRequest::query()
            ->where('client_id', auth()->id())
            ->where('id', $this->extendingHoldId)
            ->where('status', 'in_progress')
            ->with(['service.user:id,name', 'service:id,title,user_id'])
            ->first();

        if (! $req) {
            return;
        }

        if (! $req->extendHold($this->holdComment)) {
            $this->dispatch('toast', message: __('messages.codes.extend_once'), type: 'error');

            return;
        }

        $winga = $req->service->user;
        if ($winga) {
            $winga->notify(new WingaNotification(
                title: __('messages.codes.notify_extend_title'),
                message: __('messages.codes.notify_extend_service', [
                    'title' => $req->service->title,
                    'comment' => $this->holdComment,
                ]),
                icon: 'clock',
                color: 'amber',
                action_url: route('messages'),
                action_label: __('messages.codes.notify_hold_action')
            ));
        }

        $this->closeExtendForm();
        $this->dispatch('toast', message: __('messages.codes.toast_extend'), type: 'warning');
    }

    public function render()
    {
        $jobs = Job::where('employer_id', auth()->id())
            ->where('status', 'in_progress')
            ->whereNotNull('hired_worker_id')
            ->with(['hiredWorker:id,name,phone', 'payment'])
            ->latest()
            ->paginate(10, ['*'], 'jobsPage');

        $serviceWith = ['service.user:id,name,phone', 'payment'];
        if (ServicePackageSchema::hasPackagesTable()) {
            $serviceWith[] = 'package:id,title,service_id';
        }

        $serviceRequests = ServiceRequest::query()
            ->where('client_id', auth()->id())
            ->where('status', 'in_progress')
            ->whereHas('payment', fn ($q) => $q->where('status', 'escrowed'))
            ->with($serviceWith)
            ->latest()
            ->paginate(10, ['*'], 'srPage');

        return view('livewire.mteja.codes', [
            'jobs' => $jobs,
            'serviceRequests' => $serviceRequests,
            'usesServicePackages' => ServicePackageSchema::hasPackagesTable(),
        ])->layout('layouts.mteja')
            ->title(__('messages.codes.title'));
    }
}
