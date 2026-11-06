<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\Job;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Services\OpenStreetMapGeocodingService;
use App\Services\SettingsService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class KaziDetail extends Component
{
    public Job $job;

    public ?Application $selectedApplicant = null;

    public string $activeTab = 'info';

    public float $commissionRate = 10;

    public float $totalAmountSpent = 0;

    // Rejection modal
    public bool $showRejectionModal = false;

    public string $rejectionReason = '';

    // Payment split modal
    public bool $showSplitModal = false;

    public int $workerPercentage = 50;

    public int $clientPercentage = 50;

    protected $listeners = [
        'refreshJobDetail' => '$refresh',
        'selectApplicant' => 'selectApplicant',
    ];

    public function mount(int $id): void
    {
        $this->commissionRate = SettingsService::commissionRate();

        $this->job = Job::with([
            'employer',
            'category',
            'applications.worker',
            'applications.worker.activeSubscription.subscriptionPlan',
            'hiredWorker',
            'payment',
        ])->findOrFail($id);

        // Calculate total amount spent by employer
        if ($this->job->employer) {
            $this->totalAmountSpent = Payment::where('employer_id', $this->job->employer->id)->sum('amount');
        }
    }

    public function approveJob(): void
    {
        $this->job->update([
            'is_approved' => true,
            'status' => 'open',
            'approved_at' => now(),
        ]);

        app(OpenStreetMapGeocodingService::class)->fillJobCoordinatesIfMissing($this->job->fresh());
        $this->job->refresh();

        $this->logAdminAction('approve_job', $this->job, [
            'old' => ['is_approved' => false],
            'new' => ['is_approved' => true],
        ]);

        $this->dispatch('toast', message: 'Kazi imethibitishwa', type: 'success');
        $this->dispatch('refreshJobDetail');
    }

    public function rejectJob(): void
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:5',
        ]);

        $this->job->update([
            'status' => 'cancelled',
            'approved_at' => now(),
            'rejection_reason' => $this->rejectionReason,
        ]);

        $this->logAdminAction('reject_job', $this->job, [
            'old' => ['is_approved' => false],
            'new' => ['status' => 'cancelled', 'reason' => $this->rejectionReason],
        ]);

        $this->showRejectionModal = false;
        $this->rejectionReason = '';
        $this->dispatch('toast', message: 'Kazi imekataliwa', type: 'error');
        $this->dispatch('refreshJobDetail');
    }

    public function cancelJob(): void
    {
        $this->job->update(['status' => 'cancelled']);

        $this->logAdminAction('cancel_job', $this->job, [
            'old' => ['status' => $this->job->getOriginal('status')],
            'new' => ['status' => 'cancelled'],
        ]);

        $this->dispatch('toast', message: 'Kazi imefutwa', type: 'warning');
        $this->dispatch('refreshJobDetail');
    }

    public function forceComplete(): void
    {
        $this->job->update(['status' => 'completed']);

        $this->logAdminAction('force_complete_job', $this->job, [
            'old' => ['status' => $this->job->getOriginal('status')],
            'new' => ['status' => 'completed'],
        ]);

        $this->dispatch('toast', message: 'Kazi imemalizika kwa nguvu', type: 'success');
        $this->dispatch('refreshJobDetail');
    }

    public function resetApproval(): void
    {
        $this->job->update([
            'is_approved' => false,
            'approved_at' => null,
        ]);

        $this->logAdminAction('reset_approval', $this->job, [
            'old' => ['is_approved' => true],
            'new' => ['is_approved' => false],
        ]);

        $this->dispatch('toast', message: 'Idhini imerudishwa', type: 'info');
        $this->dispatch('refreshJobDetail');
    }

    public function selectApplicant(Application $applicant): void
    {
        $this->selectedApplicant = $applicant;
    }

    public function hireWorker(Application $application): void
    {
        $this->job->update([
            'hired_worker_id' => $application->worker_id,
            'status' => 'in_progress',
        ]);

        // Update application status
        $application->update(['status' => 'hired']);

        // Reject other applications
        $this->job->applications()
            ->where('id', '!=', $application->id)
            ->update(['status' => 'rejected']);

        $this->logAdminAction('hire_worker', $this->job, [
            'worker_id' => $application->worker_id,
        ]);

        $this->dispatch('toast', message: 'Mfanyakaji amechaguliwa', type: 'success');
        $this->dispatch('refreshJobDetail');
    }

    public function releasePayment(): void
    {
        if (! $this->job->hired_worker_id) {
            $this->dispatch('toast', message: 'Hakuna mfanyakaji aliyechaguliwa', type: 'error');

            return;
        }

        $escrowPayment = $this->job->escrowedPayment();
        $workerAmount = $escrowPayment?->releaseToWorkerWallet();

        if ($workerAmount === null) {
            $this->dispatch('toast', message: 'Hakuna malipo ya escrow yanayoweza kutolewa', type: 'error');

            return;
        }

        $this->logAdminAction('release_payment', $this->job, [
            'amount' => $workerAmount,
        ]);

        $this->dispatch('toast', message: 'Malipo yametolewa', type: 'success');
        $this->dispatch('refreshJobDetail');
    }

    public function refundPayment(): void
    {
        $escrowPayment = $this->job->escrowedPayment();
        $refundAmount = $escrowPayment?->refundToClientWallet();

        if ($refundAmount === null) {
            $this->dispatch('toast', message: 'Hakuna malipo ya escrow yanayoweza kurudishwa', type: 'error');

            return;
        }

        $this->logAdminAction('refund_payment', $this->job, [
            'amount' => $refundAmount,
        ]);

        $this->dispatch('toast', message: 'Pesa imerudishwa kwa mteja', type: 'success');
        $this->dispatch('refreshJobDetail');
    }

    public function splitPayment(): void
    {
        $escrowPayment = $this->job->escrowedPayment();

        if (! $escrowPayment) {
            $this->dispatch('toast', message: 'Hakuna malipo ya escrow yanayoweza kugawanywa', type: 'error');

            return;
        }

        $split = DB::transaction(function () use ($escrowPayment): ?array {
            $locked = Payment::query()->lockForUpdate()->find($escrowPayment->id);

            if (! $locked || $locked->status !== 'escrowed') {
                return null;
            }

            // Split the worker_amount (the fee has already been accounted for)
            $totalForSplit = (float) $locked->worker_amount;
            $workerAmount = round($totalForSplit * ($this->workerPercentage / 100), 2);
            $clientAmount = round($totalForSplit * ($this->clientPercentage / 100), 2);

            $worker = User::query()->lockForUpdate()->find($locked->worker_id);
            $employer = User::query()->lockForUpdate()->find($locked->employer_id);

            if ($worker && $workerAmount > 0) {
                $worker->increment('wallet_balance', $workerAmount);
                Transaction::create([
                    'user_id' => $worker->id,
                    'payment_id' => $locked->id,
                    'type' => 'credit',
                    'amount' => $workerAmount,
                    'description' => "Sehemu ya malipo ({$this->workerPercentage}%): {$this->job->title}",
                    'balance_after' => $worker->fresh()->wallet_balance,
                    'status' => 'completed',
                ]);
            }

            if ($employer && $clientAmount > 0) {
                $employer->increment('wallet_balance', $clientAmount);
                Transaction::create([
                    'user_id' => $employer->id,
                    'payment_id' => $locked->id,
                    'type' => 'credit',
                    'amount' => $clientAmount,
                    'description' => "Rudisha sehemu ({$this->clientPercentage}%): {$this->job->title}",
                    'balance_after' => $employer->fresh()->wallet_balance,
                    'status' => 'completed',
                ]);
            }

            $locked->update([
                'status' => 'released',
                'payout_status' => 'completed',
                'escrow_released_at' => now(),
            ]);

            return ['worker' => $workerAmount, 'client' => $clientAmount];
        });

        if ($split === null) {
            $this->dispatch('toast', message: 'Hakuna malipo ya escrow yanayoweza kugawanywa', type: 'error');

            return;
        }

        $this->logAdminAction('split_payment', $this->job, [
            'worker_percentage' => $this->workerPercentage,
            'client_percentage' => $this->clientPercentage,
            'worker_amount' => $split['worker'],
            'client_amount' => $split['client'],
        ]);

        $this->showSplitModal = false;
        $this->dispatch('toast', message: 'Malipo yamegawanywa', type: 'success');
        $this->dispatch('refreshJobDetail');
    }

    public function resetCompletionCode(): void
    {
        $newCode = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $this->job->update([
            'completion_code' => $newCode,
            'code_generated_at' => now(),
            'code_hold_until' => null,
        ]);

        $this->logAdminAction('reset_completion_code', $this->job, [
            'new_code' => $newCode,
        ]);

        $this->dispatch('toast', message: "Code mpya imewekwa: {$newCode}", type: 'success');
        $this->dispatch('refreshJobDetail');
    }

    public function getEscrowAmount(): float
    {
        $escrowPayment = $this->job->escrowedPayment();

        return $escrowPayment ? (float) $escrowPayment->amount : 0.0;
    }

    public function getPlatformFee(): float
    {
        $payment = $this->job->escrowedPayment();

        return $payment ? (float) $payment->platform_fee : 0.0;
    }

    public function getWorkerAmount(): float
    {
        $payment = $this->job->escrowedPayment();

        return $payment ? (float) $payment->worker_amount : 0.0;
    }

    public function getTimeline(): array
    {
        $events = [];

        // Job posted
        $events[] = [
            'type' => 'created',
            'icon' => '🆕',
            'title' => 'Kazi imewasilishwa',
            'description' => 'Na '.($this->job->employer?->name ?? 'Unknown'),
            'time' => $this->job->created_at?->format('d M Y, H:i') ?? 'Unknown time',
            'color' => 'blue',
        ];

        // Approval
        if ($this->job->approved_at) {
            $events[] = [
                'type' => 'approved',
                'icon' => '👁️',
                'title' => 'Kazi imeidhinishwa',
                'description' => 'Na Admin',
                'time' => $this->job->approved_at->format('d M Y, H:i'),
                'color' => 'green',
            ];
        }

        // Applications
        foreach ($this->job->applications as $application) {
            $events[] = [
                'type' => 'application',
                'icon' => '📋',
                'title' => 'Maombi yamepokelewa',
                'description' => 'Na '.($application->worker?->name ?? 'Unknown'),
                'time' => $application->created_at->format('d M Y, H:i'),
                'color' => 'purple',
            ];
        }

        // Hired
        if ($this->job->hiredWorker) {
            $events[] = [
                'type' => 'hired',
                'icon' => '✅',
                'title' => 'Mfanyakaji amechaguliwa',
                'description' => $this->job->hiredWorker->name,
                'time' => $this->job->updated_at->format('d M Y, H:i'),
                'color' => 'green',
            ];
        }

        // Payments
        $paymentForTimeline = $this->job->escrowedPayment() ?? $this->job->payment;

        if ($paymentForTimeline) {
            $events[] = [
                'type' => 'payment',
                'icon' => '💰',
                'title' => 'Malipo',
                'description' => 'Escrow: TZS '.number_format((float) $paymentForTimeline->amount),
                'time' => $paymentForTimeline->created_at->format('d M Y, H:i'),
                'color' => 'amber',
            ];
        }

        // Code hold
        if ($this->job->code_hold_until) {
            $events[] = [
                'type' => 'hold',
                'icon' => '⏸️',
                'title' => 'Code imeshikiliwa',
                'description' => 'Na '.($this->job->employer?->name ?? 'Unknown'),
                'time' => $this->job->code_hold_until->format('d M Y, H:i'),
                'color' => 'orange',
            ];
        }

        // Sort by time
        usort($events, fn ($a, $b) => strtotime($a['time']) - strtotime($b['time']));

        return $events;
    }

    private function logAdminAction(string $action, $model, array $changes = []): void
    {
        \App\Models\AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $changes['old'] ?? null,
            'new_values' => $changes['new'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        return view('livewire.admin.kazi-detail')
            ->layout('layouts.admin')
            ->title("Kazi: {$this->job->title}");
    }
}
