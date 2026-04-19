<?php

namespace App\Livewire\Mteja;

use App\Models\Application;
use App\Models\Conversation;
use App\Models\Job;
use App\Models\User;
use App\Notifications\WingaNotification;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Maombi extends Component
{
    use WithPagination;

    #[Url]
    public string $filter = 'all';

    #[Url]
    public ?int $job_id = null;

    // Profile View State
    public ?int $viewingWorkerId = null;

    public ?array $selectedWorker = null;

    // Post-acceptance modal (chat / pay options)
    public bool $showAcceptedModal = false;

    public ?int $acceptedWorkerId = null;

    public ?int $acceptedConversationId = null;

    // Payment modal
    public bool $showPaymentModal = false;

    public ?int $pendingApplicationId = null;

    public string $paymentMethod = 'wallet';

    public ?float $paymentAmount = null;

    public ?float $workerBidAmount = null;

    public ?float $platformFeeAmount = null;

    // Rejection comment
    public bool $showRejectModal = false;

    public ?int $rejectingApplicationId = null;

    public string $rejectionComment = '';

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function initiateAccept(int $applicationId): void
    {
        $user = auth()->user();
        $app = Application::with(['job', 'worker'])
            ->where('id', $applicationId)
            ->whereHas('job', fn ($q) => $q->where('employer_id', $user->id))
            ->where('status', 'pending')
            ->first();

        if (! $app) {
            return;
        }

        // Task 9: Accept immediately without requiring payment upfront
        $conversation = $this->acceptApplicationWithoutPayment($app, $user);

        // Show post-acceptance modal with Chat / Pay options
        $workerBid = $app->proposed_budget ?: $app->job->budget_min;
        $fees = \App\Models\Payment::calculateFromWorkerBid($workerBid);
        $this->pendingApplicationId = $applicationId;
        $this->acceptedWorkerId = $app->worker_id;
        $this->acceptedConversationId = $conversation?->id;
        $this->workerBidAmount = $workerBid;
        $this->platformFeeAmount = $fees['platform_fee'];
        $this->paymentAmount = $fees['amount'];
        $this->paymentMethod = 'wallet';
        $this->showAcceptedModal = true;
    }

    public function closeAcceptedModal(): void
    {
        $this->showAcceptedModal = false;
        $this->acceptedWorkerId = null;
        $this->acceptedConversationId = null;
    }

    private function acceptApplicationWithoutPayment(Application $app, User $user): ?\App\Models\Conversation
    {
        // Mark application as accepted
        $app->update(['status' => 'accepted']);

        // Set hired_worker_id; job stays open until payment is made
        $app->job->update(['hired_worker_id' => $app->worker_id]);

        // Reject other pending applications for this job
        Application::where('job_id', $app->job_id)
            ->where('id', '!=', $app->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        // Auto-create conversation
        $conversation = Conversation::firstOrCreate([
            'job_id'      => $app->job_id,
            'employer_id' => $user->id,
            'worker_id'   => $app->worker_id,
        ]);

        // Notify worker
        if ($app->worker) {
            $app->worker->notify(new WingaNotification(
                title: 'Ombi Lako Limekubaliwa!',
                message: 'Hongera! Mteja amekubali ombi lako kwa kazi: "'.$app->job->title.'". Zungumza naye kukubaliana!',
                icon: 'check-circle',
                color: 'green',
                action_url: route('messages'),
                action_label: 'Fungua Chat',
            ));
        }

        return $conversation;
    }

    public function initiatePayment(int $applicationId): void
    {
        $user = auth()->user();
        $app = Application::with(['job', 'worker'])
            ->where('id', $applicationId)
            ->whereHas('job', fn ($q) => $q->where('employer_id', $user->id))
            ->where('status', 'accepted')
            ->first();

        if (! $app) {
            $this->dispatch('toast', message: 'Ombi hili halikupatikana au tayari limelipwa.', type: 'error');
            return;
        }

        if ($app->job->status === 'in_progress') {
            $this->dispatch('toast', message: 'Kazi hii tayari imelipwa na inaendelea.', type: 'warning');
            return;
        }

        $workerBid = $app->proposed_budget ?: $app->job->budget_min;
        $fees = \App\Models\Payment::calculateFromWorkerBid($workerBid);
        $this->pendingApplicationId = $applicationId;
        $this->workerBidAmount = $workerBid;
        $this->platformFeeAmount = $fees['platform_fee'];
        $this->paymentAmount = $fees['amount'];
        $this->paymentMethod = 'wallet';
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->pendingApplicationId = null;
        $this->paymentAmount = null;
        $this->workerBidAmount = null;
        $this->platformFeeAmount = null;
        $this->paymentMethod = 'wallet';
    }

    public function confirmPayment(): void
    {
        if (! $this->pendingApplicationId) {
            return;
        }

        $user = auth()->user();
        $app = Application::with(['job', 'worker'])
            ->where('id', $this->pendingApplicationId)
            ->whereHas('job', fn ($q) => $q->where('employer_id', $user->id))
            ->where('status', 'accepted')
            ->first();

        if (! $app) {
            $this->closePaymentModal();
            return;
        }

        $workerBid = $app->proposed_budget ?: $app->job->budget_min;
        $fees = \App\Models\Payment::calculateFromWorkerBid($workerBid);
        $totalAmount = $fees['amount'];

        if ($this->paymentMethod === 'mobile' || $this->paymentMethod === 'card') {
            $this->closePaymentModal();
            session()->flash('deposit_amount', $totalAmount);
            session()->flash('deposit_reason', 'Malipo ya kazi: '.$app->job->title);
            $this->redirect(route('mteja.wallet'));
            return;
        }

        if ($user->wallet_balance < $totalAmount) {
            $needed = $totalAmount - $user->wallet_balance;
            $this->closePaymentModal();
            $this->dispatch('toast',
                message: 'Salio lako la TZS '.number_format($user->wallet_balance).' halitosha. Unahitaji TZS '.number_format($totalAmount).'.',
                type: 'error'
            );
            $this->dispatch('show-error',
                title: 'Salio Halitoshi',
                message: 'Unahitaji TZS '.number_format($needed).' zaidi kulipa kazi hii. Tafadhali weka pesa kwenye wallet yako.',
                action_url: route('mteja.wallet'),
                action_label: 'Weka Pesa'
            );
            return;
        }

        $this->processPayment($app, $user, $workerBid);
        $this->closePaymentModal();
    }

    private function processPayment(Application $app, User $user, float $workerBid): void
    {
        // Task 7: Employer pays the bid amount. Commission deducted from winga's earnings.
        $fees = \App\Models\Payment::calculateFromWorkerBid($workerBid);
        $totalAmount = $fees['amount'];

        // 1. Deduct bid amount from employer's wallet
        $user->decrement('wallet_balance', $totalAmount);

        // 2. Create Escrow Payment record
        $payment = \App\Models\Payment::create(array_merge($fees, [
            'job_id'         => $app->job_id,
            'employer_id'    => $user->id,
            'worker_id'      => $app->worker_id,
            'status'         => 'escrowed',
            'payment_method' => $this->paymentMethod,
        ]));

        // 3. Create Transaction history for employer
        \App\Models\Transaction::create([
            'user_id'       => $user->id,
            'payment_id'    => $payment->id,
            'type'          => 'debit',
            'amount'        => $totalAmount,
            'description'   => "Malipo ya kushikiliwa (Escrow): {$app->job->title}",
            'balance_after' => $user->fresh()->wallet_balance,
            'status'        => 'completed',
        ]);

        // 4. Move job to in_progress
        $app->job->update(['status' => 'in_progress']);

        // 5. Notify worker
        if ($app->worker) {
            $app->worker->notify(new WingaNotification(
                title: 'Pesa Imewekwa! Anza Kufanya Kazi',
                message: 'Mteja amelipa TZS '.number_format($totalAmount).' kwa kazi: "'.$app->job->title.'". Pesa iko salama — anza kazi!',
                icon: 'currency-dollar',
                color: 'green',
                action_url: route('messages'),
                action_label: 'Fungua Chat',
            ));
        }

        session()->flash('success_message', 'Malipo ya TZS '.number_format($totalAmount).' yamefanikiwa. Kazi imeanza!');
    }

    public function openRejectModal(int $applicationId): void
    {
        $this->rejectingApplicationId = $applicationId;
        $this->rejectionComment = '';
        $this->showRejectModal = true;
    }

    public function closeRejectModal(): void
    {
        $this->showRejectModal = false;
        $this->rejectingApplicationId = null;
        $this->rejectionComment = '';
    }

    public function confirmReject(): void
    {
        if (! $this->rejectingApplicationId) {
            return;
        }

        $user = auth()->user();
        $app = Application::with(['job', 'worker'])
            ->where('id', $this->rejectingApplicationId)
            ->whereHas('job', fn ($q) => $q->where('employer_id', $user->id))
            ->where('status', 'pending')
            ->first();

        if (! $app) {
            $this->closeRejectModal();
            return;
        }

        $app->update([
            'status' => 'rejected',
            'rejection_comment' => $this->rejectionComment ?: null,
        ]);

        // Notify worker with rejection reason if provided
        if ($app->worker) {
            $message = 'Samahani, ombi lako kwa kazi "'.$app->job->title.'" halikukubaliwa.';
            if ($this->rejectionComment) {
                $message .= ' Sababu: "'.$this->rejectionComment.'"';
            }
            $message .= ' Endelea kutuma maombi mengine!';

            $app->worker->notify(new WingaNotification(
                title: 'Ombi Limekataliwa',
                message: $message,
                icon: 'x-circle',
                color: 'red',
                action_url: route('tafuta-kazi'),
                action_label: 'Tafuta Kazi Nyingine',
            ));
        }

        $this->closeRejectModal();
        session()->flash('success_message', 'Ombi limekataliwa.');
    }

    public function viewProfile(int $workerId)
    {
        $worker = User::with(['portfolios', 'reviewsReceived.reviewer', 'skills'])->find($workerId);
        if (! $worker) {
            return;
        }

        $ratingAvg = $worker->reviewsReceived->avg('rating') ?? 0.0;
        $ratingCount = $worker->reviewsReceived->count();

        $this->selectedWorker = [
            'id' => $worker->id,
            'name' => $worker->name,
            'avatar_url' => $worker->avatar ? asset('storage/'.$worker->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($worker->name).'&background=8b5cf6&color=fff',
            'location' => trim(implode(', ', array_filter([$worker->mtaa, $worker->wilaya, $worker->mkoa]))) ?: 'Hajajaza Eneo',
            'bio' => $worker->bio ?? 'Mfanyakazi huyu bado hajaandika maelezo yoyote kuhusu wasifu wake.',
            'category' => $worker->kategoria ?? 'Haijabainishwa',
            'bei' => number_format((int) $worker->bei_wastani).' / '.ucfirst($worker->bei_aina ?? 'siku'),
            'uzoefu' => $worker->uzoefu_miaka.' miaka',
            'rating' => round($ratingAvg, 1),
            'review_count' => $ratingCount,
            'skills' => $worker->skills->pluck('name')->toArray(),
            'portfolio' => $worker->portfolios->map(fn ($item) => [
                'title' => $item->title,
                'description' => $item->description,
                'image_url' => asset('storage/'.$item->image_path),
            ])->toArray(),
            'reviews' => $worker->reviewsReceived->map(fn ($rev) => [
                'reviewer_name' => $rev->reviewer->name,
                'reviewer_avatar' => $rev->reviewer->avatar ? asset('storage/'.$rev->reviewer->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($rev->reviewer->name).'&background=14b8a6',
                'rating' => $rev->rating,
                'comment' => $rev->comment,
                'date' => $rev->created_at->diffForHumans(),
            ])->toArray(),
        ];

        $this->viewingWorkerId = $workerId;
    }

    public function closeProfile()
    {
        $this->viewingWorkerId = null;
        $this->selectedWorker = null;
    }

    public function render()
    {
        $user = auth()->user();

        $query = Application::query()
            ->with(['job:id,title,slug,status', 'worker']);

        $selectedJob = null;

        if ($this->job_id) {
            $selectedJob = Job::where('employer_id', $user->id)->where('id', $this->job_id)->first();
            if ($selectedJob) {
                $query->where('job_id', $this->job_id);
            } else {
                $jobIds = Job::where('employer_id', $user->id)->pluck('id');
                $query->whereIn('job_id', $jobIds);
            }
        } else {
            $jobIds = Job::where('employer_id', $user->id)->pluck('id');
            $query->whereIn('job_id', $jobIds);
        }

        if ($this->filter !== 'all' && in_array($this->filter, ['pending', 'accepted', 'rejected', 'withdrawn'], true)) {
            $query->where('status', $this->filter);
        }

        // Get job IDs for counts
        $jobIds = Job::where('employer_id', $user->id)->pluck('id');
        
        $applications = $query->latest()->paginate(10);

        $counts = [
            'all' => Application::whereIn('job_id', $jobIds)->count(),
            'pending' => Application::whereIn('job_id', $jobIds)->where('status', 'pending')->count(),
            'accepted' => Application::whereIn('job_id', $jobIds)->where('status', 'accepted')->count(),
        ];

        return view('livewire.mteja.maombi', compact('applications', 'selectedJob', 'counts'))
            ->layout('layouts.mteja')
            ->title($selectedJob ? 'Maombi: '.$selectedJob->title : 'Maombi Yote');
    }
}
