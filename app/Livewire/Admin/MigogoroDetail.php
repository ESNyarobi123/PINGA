<?php

namespace App\Livewire\Admin;

use App\Models\AdminAuditLog;
use App\Models\Dispute;
use App\Models\DisputeEvidence;
use App\Models\Job;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MigogoroDetail extends Component
{
    public Dispute $dispute;

    public Job $job;

    // Resolution actions
    public string $resolutionType = '';

    public string $resolutionReason = '';

    public string $adminNotes = '';

    public string $penaltyType = '';

    public string $penaltyReason = '';

    public int $penaltyAmount = 0;

    // Evidence
    public array $evidenceFiles = [];

    public string $evidenceDescription = '';

    // Chat messages
    public array $chatMessages = [];

    public function mount(int $id): void
    {
        $this->dispute = Dispute::with(['job.employer', 'job.hiredWorker', 'evidence'])->findOrFail($id);
        $this->job = $this->dispute->job;
        $this->loadChatMessages();
        $this->loadEvidence();
    }

    private function loadChatMessages(): void
    {
        // Load chat messages for this job
        $this->chatMessages = [
            [
                'sender' => $this->job->employer->name,
                'sender_type' => 'client',
                'message' => 'Nimekupa kazi lakini umefanya vibaya sana',
                'timestamp' => $this->dispute->created_at->subMinutes(30)->format('d M Y, H:i'),
                'avatar' => $this->job->employer->avatar,
            ],
            [
                'sender' => $this->job->hiredWorker?->name ?? 'Unknown',
                'sender_type' => 'worker',
                'message' => 'Nimefanya kazi kwa bidii, unanidanganya wapi?',
                'timestamp' => $this->dispute->created_at->subMinutes(25)->format('d M Y, H:i'),
                'avatar' => $this->job->hiredWorker?->avatar,
            ],
            [
                'sender' => $this->job->employer->name,
                'sender_type' => 'client',
                'message' => 'Hujakamilisha kazi, nataka pesa zangu rudishiwe',
                'timestamp' => $this->dispute->created_at->subMinutes(20)->format('d M Y, H:i'),
                'avatar' => $this->job->employer->avatar,
            ],
        ];
    }

    private function loadEvidence(): void
    {
        $this->evidenceFiles = $this->dispute->evidence->map(function ($evidence) {
            return [
                'id' => $evidence->id,
                'type' => $evidence->type,
                'images' => $evidence->images,
                'content' => $evidence->content,
                'submitted_by' => $evidence->submitted_by,
                'created_at' => $evidence->created_at->format('d M Y, H:i'),
            ];
        })->toArray();
    }

    public function getEscrowAmount(): float
    {
        $escrowPayment = $this->job->payment;

        if (! $escrowPayment || $escrowPayment->status !== 'escrowed') {
            return 0;
        }

        return $escrowPayment->amount;
    }

    public function getDaysOpen(): int
    {
        return $this->dispute->created_at->diffInDays(now());
    }

    public function getPriorityColor(): string
    {
        return match ($this->dispute->priority) {
            'high' => 'red',
            'medium' => 'amber',
            'low' => 'green',
            default => 'zinc'
        };
    }

    public function getStatusColor(): string
    {
        return match ($this->dispute->status) {
            'open' => 'red',
            'investigating' => 'amber',
            'resolved' => 'green',
            'closed' => 'zinc',
            default => 'zinc'
        };
    }

    public function releaseToWorker(): void
    {
        if ($this->dispute->status !== 'open') {
            $this->dispatch('toast', message: 'Dispute is not in open status', type: 'error');

            return;
        }

        $escrowPayment = $this->job->escrowedPayment();

        $workerAmount = $escrowPayment?->releaseToWorkerWallet();

        if ($workerAmount === null) {
            $this->dispatch('toast', message: 'Hakuna escrow inayoweza kutolewa', type: 'error');

            return;
        }

        // Update dispute status
        $this->dispute->update([
            'status' => 'resolved',
            'admin_notes' => ($this->resolutionReason ?: 'Worker favored in dispute resolution')."\n".$this->adminNotes,
        ]);

        $this->logAdminAction('resolve_dispute_worker_favor', $this->dispute, [
            'resolution_type' => 'worker_favor',
            'amount' => $workerAmount,
        ]);

        $this->dispatch('toast', message: 'Escrow released to worker', type: 'success');
        $this->dispatch('disputeResolved');
    }

    public function refundToClient(): void
    {
        if ($this->dispute->status !== 'open') {
            $this->dispatch('toast', message: 'Dispute is not in open status', type: 'error');

            return;
        }

        $escrowPayment = $this->job->escrowedPayment();

        $refunded = $escrowPayment?->refundToClientWallet();

        if ($refunded === null) {
            $this->dispatch('toast', message: 'Hakuna escrow inayoweza kurudishwa', type: 'error');

            return;
        }

        // Update dispute status
        $this->dispute->update([
            'status' => 'resolved',
            'admin_notes' => ($this->resolutionReason ?: 'Client favored in dispute resolution')."\n".$this->adminNotes,
        ]);

        $this->logAdminAction('resolve_dispute_client_favor', $this->dispute, [
            'resolution_type' => 'client_favor',
            'amount' => $refunded,
        ]);

        $this->dispatch('toast', message: 'Escrow refunded to client', type: 'success');
        $this->dispatch('disputeResolved');
    }

    public function splitEscrow(): void
    {
        if ($this->dispute->status !== 'open') {
            $this->dispatch('toast', message: 'Dispute is not in open status', type: 'error');

            return;
        }

        $escrowPayment = $this->job->escrowedPayment();

        if (! $escrowPayment) {
            $this->dispatch('toast', message: 'Hakuna escrow inayoweza kugawanywa', type: 'error');

            return;
        }

        $split = DB::transaction(function () use ($escrowPayment): ?array {
            $locked = Payment::query()->lockForUpdate()->find($escrowPayment->id);

            if (! $locked || $locked->status !== 'escrowed') {
                return null;
            }

            $fees = Payment::calculateFromWorkerBid((float) $locked->amount);
            $clientAmount = round((float) $fees['worker_amount'] / 2, 2);
            $workerAmount = (float) $fees['worker_amount'] - $clientAmount;

            $employer = User::query()->lockForUpdate()->find($locked->employer_id);
            $worker = User::query()->lockForUpdate()->find($locked->worker_id);

            if ($employer && $clientAmount > 0) {
                $employer->increment('wallet_balance', $clientAmount);

                Transaction::create([
                    'user_id' => $employer->id,
                    'payment_id' => $locked->id,
                    'type' => 'credit',
                    'amount' => $clientAmount,
                    'description' => 'Mgawanyo wa escrow (mgogoro): '.$locked->escrowItemLabel(),
                    'balance_after' => $employer->fresh()->wallet_balance,
                    'status' => 'completed',
                ]);
            }

            if ($worker && $workerAmount > 0) {
                $worker->increment('wallet_balance', $workerAmount);

                Transaction::create([
                    'user_id' => $worker->id,
                    'payment_id' => $locked->id,
                    'type' => 'credit',
                    'amount' => $workerAmount,
                    'description' => 'Mgawanyo wa escrow (mgogoro): '.$locked->escrowItemLabel(),
                    'balance_after' => $worker->fresh()->wallet_balance,
                    'status' => 'completed',
                ]);
            }

            $locked->update([
                'platform_fee' => $fees['platform_fee'],
                'worker_amount' => $workerAmount,
                'status' => 'released',
                'payout_status' => 'completed',
                'escrow_released_at' => now(),
            ]);

            return ['client' => $clientAmount, 'worker' => $workerAmount];
        });

        if ($split === null) {
            $this->dispatch('toast', message: 'Hakuna escrow inayoweza kugawanywa', type: 'error');

            return;
        }

        // Update dispute status
        $this->dispute->update([
            'status' => 'resolved',
            'admin_notes' => ($this->resolutionReason ?: '50/50 split in dispute resolution')."\n".$this->adminNotes,
        ]);

        $this->logAdminAction('resolve_dispute_split', $this->dispute, [
            'resolution_type' => 'split',
            'client_amount' => $split['client'],
            'worker_amount' => $split['worker'],
        ]);

        $this->dispatch('toast', message: 'Escrow split 50/50 between parties', type: 'success');
        $this->dispatch('disputeResolved');
    }

    public function applyPenalty(): void
    {
        if (! $this->penaltyType || ! $this->penaltyReason) {
            $this->dispatch('toast', message: 'Please select penalty type and reason', type: 'error');

            return;
        }

        $penaltyData = [
            'type' => $this->penaltyType,
            'reason' => $this->penaltyReason,
            'amount' => $this->penaltyAmount,
            'applied_by' => auth()->id(),
            'applied_at' => now(),
        ];

        $this->dispute->update([
            'admin_notes' => ($this->dispute->admin_notes ? $this->dispute->admin_notes."\n" : '').'Penalty: '.json_encode($penaltyData),
        ]);

        $this->logAdminAction('apply_penalty', $this->dispute, [
            'penalty_type' => $this->penaltyType,
            'penalty_amount' => $this->penaltyAmount,
        ]);

        $this->dispatch('toast', message: 'Penalty applied successfully', type: 'success');
        $this->reset(['penaltyType', 'penaltyReason', 'penaltyAmount']);
    }

    public function uploadEvidence(): void
    {
        if (! $this->evidenceDescription) {
            $this->dispatch('toast', message: 'Please provide evidence description', type: 'error');

            return;
        }

        // Create evidence record
        DisputeEvidence::create([
            'dispute_id' => $this->dispute->id,
            'content' => $this->evidenceDescription,
            'submitted_by' => auth()->id(),
        ]);

        $this->logAdminAction('add_evidence', $this->dispute, [
            'evidence_type' => 'admin_note',
            'description' => $this->evidenceDescription,
        ]);

        $this->loadEvidence();
        $this->reset(['evidenceDescription']);
        $this->dispatch('toast', message: 'Evidence added successfully', type: 'success');
    }

    public function escalateDispute(): void
    {
        $this->dispute->update([
            'priority' => 'high',
            'escalated_at' => now(),
        ]);

        $this->logAdminAction('escalate_dispute', $this->dispute, [
            'old_priority' => $this->dispute->getOriginal('priority'),
            'new_priority' => 'high',
        ]);

        $this->dispatch('toast', message: 'Dispute escalated to high priority', type: 'success');
    }

    public function reopenDispute(): void
    {
        $this->dispute->update([
            'status' => 'open',
            'admin_notes' => ($this->dispute->admin_notes ? $this->dispute->admin_notes."\n" : '').'Reopened by admin at '.now()->format('d M Y, H:i'),
        ]);

        $this->logAdminAction('reopen_dispute', $this->dispute, []);

        $this->dispatch('toast', message: 'Dispute reopened', type: 'success');
    }

    private function logAdminAction(string $action, $model, array $changes = []): void
    {
        AdminAuditLog::create([
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
        return view('livewire.admin.migogoro-detail', [
            'dispute' => $this->dispute,
            'job' => $this->job,
            'escrowAmount' => $this->getEscrowAmount(),
            'daysOpen' => $this->getDaysOpen(),
        ])
            ->layout('layouts.admin')
            ->title("Dispute #{$this->dispute->id} - {$this->job->title}");
    }
}
