<?php

namespace App\Livewire\Winga;

use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Notifications\WingaNotification;
use App\Services\SettingsService;
use App\Services\SnippePayoutService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class TombaOmbi extends Component
{
    use WithPagination;

    public bool $showForm = false;

    /** Supported: airtel | vodacom | tigo | halopesa */
    public string $network = 'vodacom';

    public string $phone = '';

    public string $amount = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->phone = $user->phone ?? '';

        // Auto-detect network from phone
        $snippe = app(SnippePayoutService::class);
        $this->network = strtolower($snippe->detectNetwork($this->phone));
    }

    public function openForm(): void
    {
        $this->showForm = true;
        $this->resetValidation();
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->reset(['amount']);
    }

    public function updatedPhone(): void
    {
        $snippe = app(SnippePayoutService::class);
        $this->network = strtolower($snippe->detectNetwork($this->phone));
    }

    public function submit(): void
    {
        $user = auth()->user();
        $minAmount = SettingsService::minWithdrawalAmount();

        $this->validate([
            'amount' => ['required', 'numeric', 'min:'.$minAmount, 'max:'.(float) $user->wallet_balance],
            'phone' => ['required', 'string', 'min:10'],
            'network' => ['required', 'in:airtel,vodacom,tigo,halopesa'],
        ], [
            'amount.min' => 'Kiwango cha chini cha kutoa ni TZS '.number_format($minAmount),
            'amount.max' => 'Hauwezi kutoa zaidi ya salio lako la TZS '.number_format((float) $user->wallet_balance),
            'phone.required' => 'Namba ya simu inahitajika',
        ]);

        $withdrawalAmount = (float) $this->amount;
        $networkLabel = app(SnippePayoutService::class)
            ->normaliseNetwork($this->network, (string) $this->phone);

        $methodValue = match (strtolower($this->network)) {
            'airtel' => 'airtel_money',
            'vodacom' => 'mpesa',
            'tigo' => 'tigopesa',
            default => 'mpesa',
        };

        $withdrawal = DB::transaction(function () use ($user, $withdrawalAmount, $networkLabel, $methodValue) {
            // Hold the money in escrow inside the wallet: it is released to the user as a
            // payout only after an admin approves the request.
            $user->decrement('wallet_balance', $withdrawalAmount);

            $withdrawal = WithdrawalRequest::create([
                'user_id' => $user->id,
                'amount' => $withdrawalAmount,
                'method' => $methodValue,
                'network' => $networkLabel,
                'account_number' => $this->phone,
                'status' => 'pending',
                'payout_status' => 'pending',
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => $withdrawalAmount,
                'description' => 'Ombi la kutoa pesa — '.$networkLabel,
                'balance_after' => $user->fresh()->wallet_balance,
                'reference' => 'WD-'.$withdrawal->id,
                'status' => 'processing',
            ]);

            return $withdrawal;
        });

        // Nothing is sent to Snippe/Selcom here on purpose. The admin reviews the request
        // in /admin/maombi-kutoa and only then does "Approve" trigger the payout.
        $user->notify(new WingaNotification(
            title: '⏳ Ombi Lako Limepokelewa',
            message: 'Ombi la kutoa TZS '.number_format($withdrawalAmount).' linahitaji idhini ya admin. Utapata taarifa mara litakapokamilika.',
            icon: 'clock',
            color: 'amber',
            action_url: route('winga.tomba-ombi'),
            action_label: 'Angalia Hali',
        ));

        User::where('role', 'admin')->get()->each(function (User $admin) use ($withdrawal, $withdrawalAmount, $networkLabel): void {
            $admin->notify(new WingaNotification(
                title: '💸 Ombi Jipya la Kutoa Pesa',
                message: $withdrawal->user->name.' anaomba kutoa TZS '.number_format($withdrawalAmount)
                    .' kwa '.$networkLabel.' '.$withdrawal->account_number.'. Idhinisha au kataa.',
                icon: 'banknotes',
                color: 'amber',
                action_url: route('admin.maombi-kutoa'),
                action_label: 'Angalia Maombi',
            ));
        });

        Log::info("TombaOmbi: withdrawal {$withdrawal->id} created and waiting for admin approval");

        $this->showForm = false;
        $this->reset(['amount']);
        $this->dispatch(
            'toast',
            message: 'Ombi la TZS '.number_format($withdrawalAmount).' limetumwa. Litasubiri idhini ya admin kabla ya malipo.',
            type: 'success',
        );
    }

    public function render()
    {
        $user = auth()->user();
        $balance = (float) ($user->wallet_balance ?? 0);

        $requests = WithdrawalRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('livewire.winga.tomba-ombi', [
            'balance' => $balance,
            'requests' => $requests,
        ])->layout('layouts.winga')->title('Omba Kutoa Fedha');
    }
}
