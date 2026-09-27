<?php

namespace App\Livewire\Winga;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\WithdrawalRequest;
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
            ->with(['job', 'employer', 'serviceRequest.service'])
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

        $isNotRefund = function ($query) {
            $query->whereNotNull('payment_id')
                ->orWhere(function ($sub) {
                    $sub->where('description', 'not like', '%efund%')
                        ->where('description', 'not like', '%kataliwa%')
                        ->where('description', 'not like', '%ghairiwa%')
                        ->where(function ($ref) {
                            $ref->whereNull('reference')
                                ->orWhere(function ($r) {
                                    $r->where('reference', 'not like', '%reject%')
                                        ->where('reference', 'not like', '%refund%');
                                });
                        });
                });
        };

        // 1. Total Earned: Actual released earnings from jobs and services
        $totalEarned = (float) Payment::where('worker_id', $user->id)
            ->where('status', 'released')
            ->sum('worker_amount');

        // Fallback: If no payment records, sum completed credit transactions excluding refunds
        if ($totalEarned == 0) {
            $totalEarned = (float) Transaction::where('user_id', $user->id)
                ->where('type', 'credit')
                ->where('status', 'completed')
                ->where($isNotRefund)
                ->sum('amount');
        }

        // 2. Total Withdrawn: Only successful/paid withdrawals (not rejected/refunded ones)
        $totalWithdrawn = (float) WithdrawalRequest::where('user_id', $user->id)
            ->whereIn('status', ['paid', 'approved'])
            ->sum('amount');

        if ($totalWithdrawn == 0) {
            $totalWithdrawn = (float) Transaction::where('user_id', $user->id)
                ->where('type', 'withdrawal')
                ->whereIn('status', ['completed', 'paid'])
                ->sum('amount');
        }

        // 3. This Month's Earnings: Only actual released earnings from this month (excluding refunds)
        $thisMonth = (float) Payment::where('worker_id', $user->id)
            ->where('status', 'released')
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNotNull('escrow_released_at')
                        ->whereMonth('escrow_released_at', now()->month)
                        ->whereYear('escrow_released_at', now()->year);
                })->orWhere(function ($sub) {
                    $sub->whereNull('escrow_released_at')
                        ->whereMonth('updated_at', now()->month)
                        ->whereYear('updated_at', now()->year);
                });
            })
            ->sum('worker_amount');

        if ($thisMonth == 0 && $totalEarned > 0) {
            $thisMonth = (float) Transaction::where('user_id', $user->id)
                ->where('type', 'credit')
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where($isNotRefund)
                ->sum('amount');
        }

        $payments = Payment::where('worker_id', $user->id)->where('status', 'released')
            ->with(['job', 'serviceRequest.service'])
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
