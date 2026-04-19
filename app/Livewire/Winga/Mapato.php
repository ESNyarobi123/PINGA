<?php

namespace App\Livewire\Winga;

use App\Models\Payment;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;

class Mapato extends Component
{
    use WithPagination;

    public string $filter = 'all'; // all, credit, withdrawal

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function downloadReceipt(int $paymentId)
    {
        $user = auth()->user();
        $payment = Payment::where('id', $paymentId)
            ->where('worker_id', $user->id)
            ->where('status', 'released')
            ->with(['job', 'employer'])
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.payment-receipt', [
            'payment' => $payment,
            'worker' => $user,
        ])->setPaper('a4');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'winga-receipt-'.$payment->id.'.pdf'
        );
    }

    public function render()
    {
        $user = auth()->user();

        $query = Transaction::where('user_id', $user->id)->latest();

        if ($this->filter !== 'all') {
            $query->where('type', $this->filter);
        }

        $transactions = $query->paginate(15);

        $totalEarned = Transaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');
        $totalWithdrawn = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->whereIn('status', ['completed', 'processing', 'paid'])
            ->sum('amount');
        $thisMonth = Transaction::where('user_id', $user->id)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        $payments = Payment::where('worker_id', $user->id)->where('status', 'released')
            ->with(['job'])
            ->latest('escrow_released_at')
            ->limit(5)
            ->get();

        return view('livewire.winga.mapato', [
            'transactions' => $transactions,
            'totalEarned' => (float) $totalEarned,
            'totalWithdrawn' => (float) $totalWithdrawn,
            'thisMonth' => (float) $thisMonth,
            'walletBalance' => (float) ($user->wallet_balance ?? 0),
            'recentPayments' => $payments,
        ])
            ->layout('layouts.winga')
            ->title('Mapato Yangu');
    }
}
