<?php

namespace App\Livewire\Winga;

use App\Models\Transaction;
use App\Models\WithdrawalRequest;
use App\Notifications\WingaNotification;
use App\Services\SelcomPayoutService;
use App\Services\SnippePayoutService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class TombaOmbi extends Component
{
    use WithPagination;

    public bool $showForm = false;

    /** Supported: airtel | tigo | halopesa */
    public string $network = 'airtel';

    public string $phone = '';

    public string $amount = '';

    public function mount(): void
    {
        $user        = auth()->user();
        $this->phone = $user->phone ?? '';

        // Auto-detect network from phone
        $snippe        = app(SnippePayoutService::class);
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
        $snippe        = app(SnippePayoutService::class);
        $this->network = strtolower($snippe->detectNetwork($this->phone));
    }

    public function submit(): void
    {
        $user = auth()->user();

        $this->validate([
            'amount'  => ['required', 'numeric', 'min:1000', 'max:' . (float) $user->wallet_balance],
            'phone'   => ['required', 'string', 'min:10'],
            'network' => ['required', 'in:airtel,tigo,halopesa'],
        ], [
            'amount.min' => 'Kiwango cha chini cha kutoa ni TZS 1,000',
            'amount.max' => 'Hauwezi kutoa zaidi ya salio lako la TZS ' . number_format((float) $user->wallet_balance),
            'phone.required' => 'Namba ya simu inahitajika',
        ]);

        $withdrawalAmount = (float) $this->amount;
        $networkCode      = ucfirst($this->network); // airtel → Airtel, tigo → Tigo

        DB::transaction(function () use ($user, $withdrawalAmount, $networkCode) {
            // 1. Deduct from wallet immediately (held pending payout)
            $user->decrement('wallet_balance', $withdrawalAmount);

            // Map network to existing method enum value
            $methodValue = match (strtolower($this->network)) {
                'airtel'   => 'airtel_money',
                'tigo'     => 'tigopesa',
                default    => 'mpesa',
            };

            // 2. Create withdrawal record
            $withdrawal = WithdrawalRequest::create([
                'user_id'        => $user->id,
                'amount'         => $withdrawalAmount,
                'method'         => $methodValue,
                'network'        => $networkCode,
                'account_number' => $this->phone,
                'status'         => 'pending',
                'payout_status'  => 'processing',
            ]);

            // 3. Create debit transaction record
            Transaction::create([
                'user_id'      => $user->id,
                'type'         => 'withdrawal',
                'amount'       => $withdrawalAmount,
                'description'  => 'Kutoa pesa — ' . $networkCode,
                'balance_after' => $user->fresh()->wallet_balance,
                'status'       => 'processing',
            ]);

            // 4. Auto-disburse: try Selcom first, fallback to Snippe
            $reference = 'withdrawal-' . $withdrawal->id . '-' . now()->timestamp;
            $payoutSuccess = false;

            // Try Selcom first
            if (config('services.selcom.api_key')) {
                $selcom = app(SelcomPayoutService::class);
                $result = $selcom->sendPayout([
                    'amount'    => (int) $withdrawalAmount,
                    'phone'     => $this->phone,
                    'name'      => $user->name,
                    'narration' => 'Kutoa pesa - Winga Platform',
                    'reference' => $reference,
                    'metadata'  => [
                        'type'          => 'withdrawal',
                        'user_id'       => $user->id,
                        'withdrawal_id' => $withdrawal->id,
                    ],
                ]);

                if ($result['success']) {
                    $withdrawal->update(['payout_reference' => $result['reference']]);
                    Log::info("TombaOmbi: Selcom payout sent for withdrawal {$withdrawal->id}", $result);
                    $payoutSuccess = true;
                } else {
                    Log::warning("TombaOmbi: Selcom payout failed, falling back to Snippe for withdrawal {$withdrawal->id}", $result);
                }
            }

            // Fallback to Snippe if Selcom failed or not configured
            if (! $payoutSuccess) {
                $snippe = app(SnippePayoutService::class);
                $result = $snippe->sendPayout([
                    'amount'          => (int) $withdrawalAmount,
                    'phone'           => $this->phone,
                    'name'            => $user->name,
                    'network'         => $networkCode,
                    'narration'       => 'Kutoa pesa - Winga Platform',
                    'idempotency_key' => $reference,
                    'metadata'        => [
                        'type'          => 'withdrawal',
                        'user_id'       => $user->id,
                        'withdrawal_id' => $withdrawal->id,
                    ],
                ]);

                if ($result['success']) {
                    $withdrawal->update(['payout_reference' => $result['reference']]);
                    Log::info("TombaOmbi: Snippe payout sent for withdrawal {$withdrawal->id}", $result);
                } else {
                    Log::error("TombaOmbi: All payout providers failed for withdrawal {$withdrawal->id}", $result);

                    $user->notify(new WingaNotification(
                        title: '⏳ Ombi Linashughulikiwa',
                        message: 'Kuna tatizo la muda na mtoa huduma. Malipo yako ya TZS ' . number_format($withdrawalAmount) . ' yatafanyika ndani ya dakika 30.',
                        icon: 'clock',
                        color: 'amber',
                        action_url: route('winga.tomba-ombi'),
                        action_label: 'Angalia Hali',
                    ));
                }
            }
        });

        $displayAmount = $withdrawalAmount;
        $this->showForm = false;
        $this->reset(['amount']);
        $this->dispatch('toast', message: 'Ombi lako linashughulikiwa. Utapokea TZS ' . number_format($displayAmount) . ' kwenye simu yako hivi karibuni!', type: 'success');
    }

    public function render()
    {
        $user     = auth()->user();
        $balance  = (float) ($user->wallet_balance ?? 0);

        $requests = WithdrawalRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('livewire.winga.tomba-ombi', [
            'balance'  => $balance,
            'requests' => $requests,
        ])->layout('layouts.winga')->title('Omba Kutoa Fedha');
    }
}
