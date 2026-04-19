<?php

namespace App\Livewire\Public;

use Livewire\Component;

class Kuhusu extends Component
{
    public function render()
    {
        return view('livewire.public.kuhusu')
            ->layout('layouts.public')
            ->title('Kuhusu Winga');
    }
}
