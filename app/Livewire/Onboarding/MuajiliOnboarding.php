<?php

namespace App\Livewire\Onboarding;

use Livewire\Component;
use Livewire\WithFileUploads;

class MuajiliOnboarding extends Component
{
    use WithFileUploads;

    public int $step = 1;
    public int $totalSteps = 4;

    // Step 1: Location
    public string $mkoa = '';
    public string $wilaya = '';
    public string $mtaa = '';
    public ?float $latitude = null;
    public ?float $longitude = null;

    // Step 2: WhatsApp
    public string $whatsapp = '';

    // Step 3: Payment method
    public string $njia_malipo = '';
    public string $namba_malipo = '';

    // Step 4: Photo
    public $photo = null;

    protected array $messages = [
        'mkoa.required' => 'Tafadhali chagua mkoa wako',
        'wilaya.required' => 'Tafadhali andika wilaya yako',
        'mtaa.required' => 'Tafadhali andika mtaa/kata yako',
        'whatsapp.required' => 'Namba ya WhatsApp inahitajika',
        'whatsapp.unique' => 'Namba hii tayari inatumiwa na akaunti nyingine',
        'njia_malipo.required' => 'Tafadhali chagua njia ya malipo',
        'namba_malipo.required' => 'Namba ya malipo inahitajika',
    ];

    public function nextStep(): void
    {
        $this->validateCurrentStep();
        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function validateCurrentStep(): void
    {
        match ($this->step) {
            1 => $this->validate([
                'mkoa' => 'required|string',
                'wilaya' => 'nullable|string',
                'mtaa' => 'nullable|string',
            ]),
            2 => $this->validate([
                'whatsapp' => 'required|string|min:10|unique:users,phone,'.auth()->id(),
            ]),
            3 => $this->validate([
                'njia_malipo' => 'required|in:mpesa,tigopesa,airtelmoney',
                'namba_malipo' => 'required|string|min:10',
            ]),
            default => null,
        };
    }

    public function finish(): void
    {
        $user = auth()->user();

        try {
            $user->update([
                'mkoa' => $this->mkoa,
                'wilaya' => $this->wilaya,
                'mtaa' => $this->mtaa,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'phone' => $this->whatsapp,
                'payment_method' => $this->njia_malipo,
                'payment_number' => $this->namba_malipo,
                'onboarding_completed' => true,
            ]);
        } catch (\Exception $e) {
            \Log::error('Onboarding Update Error: '.$e->getMessage(), [
                'user_id' => $user->id,
                'data' => [
                    'phone' => $this->whatsapp,
                    'mkoa' => $this->mkoa,
                ]
            ]);
            session()->flash('error', 'Imeshindikana kuhifadhi taarifa zako. Huenda namba hii tayari inatumika.');
            return;
        }

        if ($this->photo) {
            $user->updateProfilePhoto($this->photo);
        }

        $this->redirect(route('mteja.dashboard'));
    }

    public function render()
    {
        return view('livewire.onboarding.muajili-onboarding')
            ->layout('layouts.public')
            ->title('Muajili - Kamilisha Wasifu');
    }
}
