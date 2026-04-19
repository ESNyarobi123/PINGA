<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class LocaleSwitcher extends Component
{
    public string $locale = 'sw';

    public function mount(): void
    {
        $this->locale = session('locale', config('app.locale', 'sw'));
    }

    public function switchLocale(string $locale): void
    {
        if (! in_array($locale, ['sw', 'en'], true)) {
            return;
        }

        $this->locale = $locale;
        session(['locale' => $locale]);
        $this->dispatch('locale-changed');

        // Reload page so all strings re-render in new locale
        $this->redirect(request()->header('Referer') ?? url()->current(), navigate: false);
    }

    public function render()
    {
        return view('livewire.shared.locale-switcher');
    }
}
