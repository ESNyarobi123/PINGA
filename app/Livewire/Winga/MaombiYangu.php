<?php

namespace App\Livewire\Winga;

use App\Models\Application;
use App\Notifications\WingaNotification;
use Livewire\Component;
use Livewire\WithPagination;

class MaombiYangu extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public bool $showWithdrawModal = false;

    public ?int $withdrawApplicationId = null;

    public function mount(): void
    {
        $filter = request()->query('filter');
        if (is_string($filter) && in_array($filter, ['all', 'pending', 'accepted', 'rejected', 'withdrawn'], true)) {
            $this->filter = $filter;
        }
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function confirmWithdraw(int $applicationId): void
    {
        $this->withdrawApplicationId = $applicationId;
        $this->showWithdrawModal = true;
    }

    public function withdraw(): void
    {
        $app = Application::where('id', $this->withdrawApplicationId)
            ->where('worker_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($app) {
            $app->update(['status' => 'withdrawn']);

            // Notify employer
            $job = $app->job()->with('employer')->first();
            if ($job?->employer) {
                $job->employer->notify(new WingaNotification(
                    title: 'Ombi limeondolewa',
                    message: auth()->user()->name.' ameondoa ombi lake kwa kazi: '.$job->title,
                    icon: 'x-circle',
                    color: 'gray',
                    action_url: route('mteja.maombi'),
                    action_label: 'Angalia Maombi',
                ));
            }

            $this->dispatch('toast', message: 'Ombi lako limeondolewa.', type: 'success');
        }

        $this->showWithdrawModal = false;
        $this->withdrawApplicationId = null;
    }

    public function cancelWithdraw(): void
    {
        $this->showWithdrawModal = false;
        $this->withdrawApplicationId = null;
    }

    public function render()
    {
        $user = auth()->user();

        $query = Application::where('worker_id', $user->id)
            ->with(['job.employer:id,name,avatar', 'job.category:id,name']);

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $applications = $query->latest()->paginate(10);

        $counts = [
            'all' => Application::where('worker_id', $user->id)->count(),
            'pending' => Application::where('worker_id', $user->id)->where('status', 'pending')->count(),
            'accepted' => Application::where('worker_id', $user->id)->where('status', 'accepted')->count(),
            'rejected' => Application::where('worker_id', $user->id)->where('status', 'rejected')->count(),
            'withdrawn' => Application::where('worker_id', $user->id)->where('status', 'withdrawn')->count(),
        ];

        return view('livewire.winga.maombi-yangu', compact('applications', 'counts'))
            ->layout('layouts.winga')
            ->title('Maombi Yangu');
    }
}
