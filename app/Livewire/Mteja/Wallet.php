<?php

namespace App\Livewire\Mteja;

use App\Models\Transaction;
use App\Models\WithdrawalRequest;
use App\Services\SettingsService;
use Livewire\Component;

class Wallet extends Component
{
    public bool $showDepositModal = false;

    public int $depositStep = 1;

    public string $paymentMethod = '';

    public $amount;

    public $phone;

    // Withdrawal properties (Task 8)
    public bool $showWithdrawModal = false;

    public $withdrawAmount;

    public string $withdrawMethod = 'mobile_money';

    public string $withdrawPhone = '';

    public string $withdrawNetwork = 'vodacom';

    public function mount()
    {
        if (request()->query('status') === 'success') {
            session()->flash('success_message', 'Muamala wa Kadi umefanikiwa! Salio lako litaongezeka hivi punde (Refresh baadaye kidogo).');
        } elseif (request()->query('status') === 'cancelled') {
            session()->flash('error_message', 'Muamala wa Kadi umehairishwa.');
        }

        $user = auth()->user();
        $this->phone = $user->phone ?? '';
    }

    public function openDepositModal()
    {
        $this->showDepositModal = true;
        $this->depositStep = 1;
        $this->paymentMethod = '';
        $this->amount = null;
        $this->resetValidation();
    }

    public function setPaymentMethod(string $method)
    {
        $this->paymentMethod = $method;
        $this->depositStep = 2;
    }

    public function processPayment()
    {
        if ($this->paymentMethod === 'mobile') {
            $this->initiateMobilePayment();
        } elseif ($this->paymentMethod === 'card') {
            $this->initiateCardPayment();
        }
    }

    public function initiateMobilePayment()
    {
        $this->validate([
            'amount' => 'required|numeric|min:500',
            'phone' => 'required|string|min:9',
        ], [
            'amount.min' => 'Kiwango cha chini ni TZS 500',
            'phone.required' => 'Namba ya simu inahitajika',
        ]);

        $user = auth()->user();
        $service = new \App\Services\SnippePaymentService;
        $orderId = 'DEP-'.strtoupper(\Illuminate\Support\Str::random(8));

        $phoneToUse = $this->phone;
        // Convert local 07xx to 2557xx standard format
        if (str_starts_with($phoneToUse, '0')) {
            $phoneToUse = '255'.substr($phoneToUse, 1);
        }

        $customer = [
            'firstname' => explode(' ', $user->name)[0] ?? 'Employer',
            'lastname' => explode(' ', $user->name)[1] ?? 'Winga',
            'email' => $user->email ?? 'noemail@winga.com',
            'user_id' => $user->id,
        ];

        $response = $service->createMobilePayment((float) $this->amount, $phoneToUse, $customer, $orderId);

        if ($response && ($response['status'] === 'success' || isset($response['data']['reference']))) {
            $this->depositStep = 3; // Show success screen
            $this->amount = null;
        } else {
            session()->flash('error_message', 'Imeshindikana kutengeneza muamala. Tafadhali jaribu tena.');
        }
    }

    public function initiateCardPayment()
    {
        try {
            $this->validate([
                'amount' => 'required|numeric|min:1000',
            ], [
                'amount.min' => 'Kodi ya chini ya kadi ni TZS 1,000',
            ]);

            $user = auth()->user();
            $service = new \App\Services\SnippePaymentService;
            $orderId = 'DEP-'.strtoupper(\Illuminate\Support\Str::random(8));

            $customer = [
                'firstname' => explode(' ', $user->name)[0] ?? 'Employer',
                'lastname' => explode(' ', $user->name)[1] ?? 'Winga',
                'email' => $user->email ?? 'noemail@winga.com',
                'address' => 'Dar Es Salaam',
                'city' => 'Dar Es Salaam',
                'state' => 'DSM',
                'postcode' => '14101',
                'country' => 'TZ',
                'user_id' => $user->id,
            ];

            $response = $service->createCardPayment((float) $this->amount, $customer, $orderId);

            if ($response && isset($response['data']['payment_url'])) {
                return redirect()->away($response['data']['payment_url']);
            }

            session()->flash('error_message', 'Imeshindikana kuunganisha na benki ya Snippe (Pay_001). Tafadhali jaribu tena baadaye.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Wallet Card Error: '.$e->getMessage());
            session()->flash('error_message', 'Kuna hitilafu imetokea wakati wa kuanza malipo.');
        }
    }

    public function openWithdrawModal(): void
    {
        $this->showWithdrawModal = true;
        $this->withdrawAmount = null;
        $this->withdrawMethod = 'mobile_money';
        $this->withdrawPhone = auth()->user()->phone ?? '';
        $this->withdrawNetwork = 'vodacom';
        $this->resetValidation();
    }

    public function closeWithdrawModal(): void
    {
        $this->showWithdrawModal = false;
        $this->withdrawAmount = null;
    }

    public function submitWithdrawal(): void
    {
        $minAmount = SettingsService::minWithdrawalAmount();

        $this->validate([
            'withdrawAmount' => ['required', 'numeric', 'min:' . $minAmount],
            'withdrawPhone' => ['required', 'string', 'min:9'],
            'withdrawNetwork' => ['required', 'string', 'in:vodacom,tigopesa,airtel,halotel'],
        ], [
            'withdrawAmount.required' => 'Tafadhali weka kiasi cha kutoa.',
            'withdrawAmount.min' => 'Kiwango cha chini ni TZS ' . number_format($minAmount),
            'withdrawPhone.required' => 'Namba ya simu inahitajika.',
        ]);

        $user = auth()->user();
        $chargePercent = SettingsService::get('withdrawal_charge_percent', 5);
        $chargeAmount = round($this->withdrawAmount * ($chargePercent / 100), 2);
        $totalDeduction = $this->withdrawAmount + $chargeAmount;

        if ($user->wallet_balance < $totalDeduction) {
            $this->addError('withdrawAmount', 'Salio lako halitosha. Unahitaji TZS ' . number_format($totalDeduction) . ' (pamoja na ada ya ' . $chargePercent . '%)');
            return;
        }

        // Check for active jobs in escrow
        $hasActiveEscrow = \App\Models\Payment::where('employer_id', $user->id)
            ->where('status', 'escrowed')
            ->exists();

        $availableBalance = $user->wallet_balance;
        if ($hasActiveEscrow) {
            $escrowedTotal = \App\Models\Payment::where('employer_id', $user->id)
                ->where('status', 'escrowed')
                ->sum('amount');
            $availableBalance = $user->wallet_balance - $escrowedTotal;
        }

        if ($availableBalance < $totalDeduction) {
            $this->addError('withdrawAmount', 'Salio lako linaloweza kutolewa ni TZS ' . number_format($availableBalance) . ' (baada ya kuzingatia malipo yaliyoshikiliwa).');
            return;
        }

        // Deduct from wallet
        $user->decrement('wallet_balance', $totalDeduction);

        // Create withdrawal request
        $withdrawal = WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount' => $this->withdrawAmount,
            'charge_percent' => $chargePercent,
            'charge_amount' => $chargeAmount,
            'net_amount' => $this->withdrawAmount,
            'method' => $this->withdrawMethod,
            'phone' => $this->withdrawPhone,
            'network' => $this->withdrawNetwork,
            'status' => 'pending',
            'notes' => 'Mteja withdrawal request',
        ]);

        // Create transaction record
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => $totalDeduction,
            'description' => 'Ombi la kutoa pesa: TZS ' . number_format($this->withdrawAmount) . ' + ada TZS ' . number_format($chargeAmount) . ' (' . $chargePercent . '%)',
            'balance_after' => $user->fresh()->wallet_balance,
            'reference' => 'WD-' . $withdrawal->id,
            'status' => 'completed',
        ]);

        $this->closeWithdrawModal();
        $this->dispatch('toast', message: 'Ombi la kutoa TZS ' . number_format($this->withdrawAmount) . ' limetumwa! Ada: TZS ' . number_format($chargeAmount) . ' (' . $chargePercent . '%).', type: 'success');
    }

    public function render()
    {
        $user = auth()->user();
        $balance = (float) ($user->wallet_balance ?? 0);
        $transactions = Transaction::where('user_id', $user->id)->latest()->take(50)->get();
        $withdrawalChargePercent = SettingsService::get('withdrawal_charge_percent', 5);
        $withdrawals = WithdrawalRequest::where('user_id', $user->id)->latest()->take(10)->get();

        return view('livewire.mteja.wallet', [
            'balance' => $balance,
            'transactions' => $transactions,
            'withdrawalChargePercent' => $withdrawalChargePercent,
            'withdrawals' => $withdrawals,
        ])
            ->layout('layouts.mteja')
            ->title('Wallet Kutengeneza Malipo');
    }
}
