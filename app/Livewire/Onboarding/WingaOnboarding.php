<?php

namespace App\Livewire\Onboarding;

use Livewire\Component;
use Livewire\WithFileUploads;

class WingaOnboarding extends Component
{
    use WithFileUploads;

    public function mount(): void
    {
        // Load skills from database grouped by category
        $this->loadSkillsFromDatabase();
    }

    public array $dbSkills = []; // Skills loaded from database

    public function loadSkillsFromDatabase(): void
    {
        $categories = \App\Models\Category::with('skills')->where('is_active', true)->get();
        
        $this->dbSkills = [];
        foreach ($categories as $category) {
            $this->dbSkills[$category->name] = [
                'icon' => $category->icon,
                'skills' => $category->skills->pluck('name')->toArray(),
            ];
        }
    }
    public int $step = 1;
    public int $totalSteps = 7;

    // Step 1: Location
    public string $mkoa = '';
    public string $wilaya = '';
    public string $mtaa = '';
    public ?float $latitude = null;
    public ?float $longitude = null;

    // Step 2: Skills
    public array $ustadi = [];

    // Step 3: Pricing
    public string $bei_aina = 'siku'; // siku, saa, kazi
    public int $bei_wastani = 0;

    // Step 4: Experience & Availability
    public int $uzoefu_miaka = 0;
    public array $siku_zinazopatikana = [];

    // Step 5: Profile photo
    public $photo = null;

    // Step 6: Portfolio
    public array $portfolio_photos = [];

    // Step 7: NIDA (optional)
    public string $nida = '';

    protected array $messages = [
        'mkoa.required' => 'Tafadhali chagua mkoa wako',
        'wilaya.required' => 'Tafadhali andika wilaya yako',
        'mtaa.required' => 'Tafadhali andika mtaa/kata yako',
        'ustadi.required' => 'Tafadhali chagua ustadi angalau mmoja',
        'ustadi.min' => 'Chagua ustadi angalau mmoja',
        'bei_wastani.required' => 'Tafadhali weka bei yako',
        'bei_wastani.min' => 'Bei lazima iwe kubwa kuliko sifuri',
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

    public function toggleSkill(string $skill): void
    {
        if (in_array($skill, $this->ustadi)) {
            $this->ustadi = array_values(array_diff($this->ustadi, [$skill]));
        } else {
            $this->ustadi[] = $skill;
        }
    }

    public function toggleDay(string $day): void
    {
        if (in_array($day, $this->siku_zinazopatikana)) {
            $this->siku_zinazopatikana = array_values(array_diff($this->siku_zinazopatikana, [$day]));
        } else {
            $this->siku_zinazopatikana[] = $day;
        }
    }

    public function validateCurrentStep(): void
    {
        match ($this->step) {
            1 => $this->validate([
                'mkoa' => 'required|string',
                'wilaya' => 'required|string',
                'mtaa' => 'required|string',
            ]),
            2 => $this->validate([
                'ustadi' => 'required|array|min:1',
            ]),
            3 => $this->validate([
                'bei_wastani' => 'required|integer|min:1000',
            ]),
            4 => $this->validate([
                'uzoefu_miaka' => 'required|integer|min:0',
            ]),
            default => null,
        };
    }

    public function finish(): void
    {
        $user = auth()->user();

        $user->update([
            'mkoa' => $this->mkoa,
            'wilaya' => $this->wilaya,
            'mtaa' => $this->mtaa,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'bei_aina' => $this->bei_aina,
            'bei_wastani' => $this->bei_wastani,
            'uzoefu_miaka' => $this->uzoefu_miaka,
            'siku_zinazopatikana' => $this->siku_zinazopatikana,
            'nida' => $this->nida ?: null,
            'onboarding_completed' => true,
        ]);

        if ($this->photo) {
            $user->updateProfilePhoto($this->photo);
        }

        // Sync skills
        if (!empty($this->ustadi)) {
            $skillIds = \App\Models\Skill::whereIn('name', $this->ustadi)->pluck('id');
            $user->skills()->sync($skillIds);
        }

        // Save portfolio photos
        foreach ($this->portfolio_photos as $photo) {
            $path = $photo->store('portfolios/' . $user->id, 'public');
            $user->portfolio()->create([
                'title' => 'Kazi yangu',
                'image_path' => $path,
            ]);
        }

        $this->redirect(route('winga.dashboard'));
    }

    public function render()
    {
        return view('livewire.onboarding.mfanyakazi-onboarding')
            ->layout('layouts.public')
            ->title('Mfanyakazi - Kamilisha Wasifu');
    }
}
