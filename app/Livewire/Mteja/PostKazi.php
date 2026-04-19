<?php

namespace App\Livewire\Mteja;

use App\Jobs\TranslateJobPosting;
use App\Models\Job;
use App\Services\OpenStreetMapGeocodingService;
use Livewire\Component;

class PostKazi extends Component
{
    public string $title = '';

    public string $description = '';

    public string $category_id = '';

    public string $location = '';

    public string $budget_type = 'fixed'; // fixed, hourly, daily

    public int $budget_min = 0;

    public int $budget_max = 0;

    public string $urgency = 'normal'; // urgent, normal

    public array $skills = [];

    public string $duration = '';

    protected array $rules = [
        'title' => 'required|string|min:5|max:200',
        'description' => 'required|string|min:20',
        'category_id' => 'required',
        'location' => 'required|string',
        'budget_min' => 'required|integer|min:1000',
    ];

    protected array $messages = [
        'title.required' => 'Jina la kazi linahitajika',
        'description.required' => 'Maelezo ya kazi yanahitajika',
        'category_id.required' => 'Chagua kategoria',
        'location.required' => 'Eneo linahitajika',
        'budget_min.required' => 'Bajeti ya chini inahitajika',
    ];

    public function submit(): void
    {
        $this->validate();

        if (Job::containsPhoneNumber($this->description) || Job::containsPhoneNumber($this->title)) {
            $this->addError('description', 'Tafadhali usiandike namba ya simu kwenye maelezo. Namba ya simu itaonyeshwa kwa mteja baada ya malipo.');

            return;
        }

        $job = Job::create([
            'employer_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'location' => $this->location,
            'budget_type' => $this->budget_type,
            'budget_min' => $this->budget_min,
            'budget_max' => $this->budget_max ?: $this->budget_min,
            'urgency' => $this->urgency,
            'duration' => $this->duration,
            'status' => 'open',
            'is_approved' => false,
        ]);

        app(OpenStreetMapGeocodingService::class)->fillJobCoordinatesIfMissing($job);

        TranslateJobPosting::dispatch($job);

        session()->flash('success', 'Kazi imetumwa kwa ufanisi! Inasubiri ukaguzi wa admin kabla ya kuonekana kwa Winga.');
        $this->redirect(route('mteja.kazi-zangu'));
    }

    public function render()
    {
        $categories = \App\Models\Category::all();

        return view('livewire.mteja.post-kazi', compact('categories'))
            ->layout('layouts.mteja')
            ->title('Tuma Kazi Mpya');
    }
}
