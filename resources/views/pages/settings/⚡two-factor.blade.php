<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Two-factor authentication')] class extends Component {
    public bool $twoFactorEnabled;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->twoFactorEnabled = (bool) auth()->user()->two_factor_enabled;
    }

    /**
     * Enable two-factor authentication for the user.
     */
    public function enable(): void
    {
        auth()->user()->update(['two_factor_enabled' => true]);
        $this->twoFactorEnabled = true;
        session()->flash('success', 'Ulinzi wa OTP kupitia barua pepe umewezeshwa kikamilifu!');
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disable(): void
    {
        auth()->user()->update(['two_factor_enabled' => false]);
        $this->twoFactorEnabled = false;
        session()->flash('success', 'Ulinzi wa OTP kupitia barua pepe umezimwa.');
    }
} ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Two-factor authentication settings') }}</flux:heading>

    <x-pages::settings.layout
        :heading="__('Thibitisha Njia Mbili (2FA)')"
        :subheading="__('Tumia OTP kupitia barua pepe kulinda akaunti yako na Winga.')"
    >
        <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
            
            @if (session()->has('success'))
                <div class="p-3 bg-red-50 text-winga-600 font-medium rounded-md border border-winga-100 flex items-center gap-2">
                    <svg class="size-5 text-winga-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($twoFactorEnabled)
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="green">Ipo Hewani (Enabled)</flux:badge>
                    </div>

                    <flux:text class="text-zinc-600 dark:text-zinc-400">
                        Uthibitisho wa hatua mbili unatumia <strong>OTP (Namba Siri)</strong> inayotumwa kwenye barua pepe yako kila unapoingia. Inalinda akaunti yako dhidi ya wizi wa mtandaoni.
                    </flux:text>

                    <div class="flex justify-start pt-4 border-t border-zinc-200 dark:border-zinc-800">
                        <flux:button
                            variant="danger"
                            icon="shield-exclamation"
                            icon:variant="outline"
                            wire:click="disable"
                        >
                            Zima 2FA (Disable)
                        </flux:button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="red">Imezimwa (Disabled)</flux:badge>
                    </div>

                    <flux:text variant="subtle" class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Uthibitisho wa hatua mbili (2FA) umezimwa. Tunashauri uwashe ulinzi huu kupokea <strong>namba maalumu (OTP)</strong> kupitia barua pepe kila unapoingia kwenye akaunti yako kuepuka utapeli.
                    </flux:text>

                    <div class="flex justify-start pt-4 border-t border-zinc-200 dark:border-zinc-800">
                        <flux:button
                            icon="shield-check"
                            class="!bg-winga-500 hover:!bg-winga-600 !text-white !font-bold"
                            icon:variant="outline"
                            wire:click="enable"
                        >
                            Washa 2FA (Enable)
                        </flux:button>
                    </div>
                </div>
            @endif
        </div>
    </x-pages::settings.layout>
</section>
