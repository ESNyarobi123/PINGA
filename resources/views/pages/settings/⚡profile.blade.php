<?php

use App\Concerns\ProfileValidationRules;
use App\Models\Skill;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Profile settings')] class extends Component {
    use ProfileValidationRules, WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';

    // Eneo (both)
    public string $mkoa = '';
    public string $wilaya = '';
    public string $mtaa = '';

    // Muajili
    public string $payment_method = '';
    public string $payment_number = '';

    // Mfanyakazi
    public array $ustadi = [];
    public string $bei_aina = 'siku';
    public int $bei_wastani = 0;
    public int $uzoefu_miaka = 0;
    public array $siku_zinazopatikana = [];
    public string $nida = '';
    public string $veta = '';

    public $photo = null;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
        $this->mkoa = $user->mkoa ?? '';
        $this->wilaya = $user->wilaya ?? '';
        $this->mtaa = $user->mtaa ?? '';
        $this->payment_method = $user->payment_method ?? '';
        $this->payment_number = $user->payment_number ?? '';
        $this->bei_aina = $user->bei_aina ?? 'siku';
        $this->bei_wastani = (int) ($user->bei_wastani ?? 0);
        $this->uzoefu_miaka = (int) ($user->uzoefu_miaka ?? 0);
        $this->siku_zinazopatikana = $user->siku_zinazopatikana ?? [];
        $this->nida = $user->nida ?? '';
        $this->veta = $user->veta ?? '';
        if ($user->isMfanyakazi()) {
            $this->ustadi = $user->skills->pluck('name')->map(fn ($n) => (string) $n)->all();
        }
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();
        $validated = $this->validate($this->profileRules($user->id));
        $user->fill($validated);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();
        $this->dispatch('profile-updated', name: $user->name);
    }

    public function updateOnboardingInformation(): void
    {
        $user = Auth::user();
        $rules = [
            'mkoa' => 'nullable|string|max:100',
            'wilaya' => 'nullable|string|max:100',
            'mtaa' => 'nullable|string|max:100',
        ];
        if ($user->isMuajili()) {
            $rules['payment_method'] = 'nullable|in:mpesa,tigopesa,airtelmoney';
            $rules['payment_number'] = 'nullable|string|max:20';
        }
        if ($user->isMfanyakazi()) {
            $rules['bei_aina'] = 'nullable|in:siku,saa,kazi';
            $rules['bei_wastani'] = 'nullable|integer|min:0';
            $rules['uzoefu_miaka'] = 'nullable|integer|min:0';
            $rules['nida'] = 'nullable|string|max:50';
            $rules['veta'] = 'nullable|string|max:50';
        }
        $validated = $this->validate($rules);

        $user->fill(array_merge($validated, [
            'siku_zinazopatikana' => $this->siku_zinazopatikana,
        ]));
        $user->save();

        if ($user->isMfanyakazi() && ! empty($this->ustadi)) {
            $skillIds = collect($this->ustadi)->map(function ($name) {
                return Skill::firstOrCreate(
                    ['name' => $name],
                    ['slug' => \Illuminate\Support\Str::slug($name)]
                )->id;
            })->all();
            $user->skills()->sync($skillIds);
        }

        if ($this->photo) {
            $user->updateProfilePhoto($this->photo);
            $this->photo = null;
            $user->refresh();
        }

        $this->dispatch('profile-updated', name: $user->name);
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

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();
        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }
        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }

    public static function getRegions(): array
    {
        return ['Dar es Salaam', 'Arusha', 'Mwanza', 'Dodoma', 'Tanga', 'Mbeya', 'Morogoro', 'Kilimanjaro', 'Iringa', 'Kigoma', 'Mara', 'Lindi', 'Mtwara', 'Ruvuma', 'Rukwa', 'Kagera', 'Shinyanga', 'Singida', 'Tabora', 'Pwani', 'Geita', 'Katavi', 'Njombe', 'Simiyu', 'Songwe'];
    }

    public static function getSkillsList(): array
    {
        return [
            ['name' => 'Kinyozi', 'icon' => '💈'],
            ['name' => 'Fundi Bomba', 'icon' => '🔧'],
            ['name' => 'Usafi', 'icon' => '🧹'],
            ['name' => 'Shamba', 'icon' => '🌿'],
            ['name' => 'Tailor', 'icon' => '🧵'],
            ['name' => 'Urembo', 'icon' => '💅'],
            ['name' => 'Mpishi', 'icon' => '👨‍🍳'],
            ['name' => 'Fundi Umeme', 'icon' => '⚡'],
            ['name' => 'Ujenzi', 'icon' => '🏗️'],
            ['name' => 'Dereva', 'icon' => '🚗'],
            ['name' => 'Mlinzi', 'icon' => '💂'],
            ['name' => 'Mhudumu', 'icon' => '🍽️'],
            ['name' => 'Teknolojia', 'icon' => '💻'],
            ['name' => 'Usafiri', 'icon' => '🏍️'],
            ['name' => 'Uchoraji', 'icon' => '🎨'],
            ['name' => 'Kupaka Rangi', 'icon' => '🖌️'],
            ['name' => 'Bustani', 'icon' => '🌺'],
            ['name' => 'Fundi Simu', 'icon' => '📱'],
        ];
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile settings') }}</flux:heading>

    @php
        $user = auth()->user();
        $roleLabel = $user->isMuajili() ? 'Muajili' : ($user->isMfanyakazi() ? 'Mfanyakazi' : 'Admin');
        $regions = $this::getRegions();
        $skillsList = $this::getSkillsList();
    @endphp

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Wasifu wako na taarifa za akaunti')">
        <div class="my-6 w-full space-y-6 max-w-2xl">

            {{-- Profile header --}}
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
                <div class="p-6 flex flex-col sm:flex-row items-center sm:items-start gap-4">
                    <div class="shrink-0">
                        @if($user->avatar)
                            <img src="{{ asset('storage/'.$user->avatar) }}?v={{ $user->updated_at?->timestamp ?? time() }}" alt="" class="w-24 h-24 rounded-2xl object-cover ring-2 ring-zinc-100 dark:ring-zinc-800" />
                        @else
                            <div class="w-24 h-24 rounded-2xl bg-winga-100 dark:bg-winga-900/30 flex items-center justify-center text-3xl font-bold text-winga-600 dark:text-winga-400">
                                {{ $user->initials() }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 text-center sm:text-start min-w-0">
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white truncate">{{ $user->name }}</h2>
                        <p class="text-zinc-500 dark:text-zinc-400 text-sm truncate">{{ $user->email }}</p>
                        @if($user->phone)
                            <p class="text-zinc-500 dark:text-zinc-400 text-sm">{{ $user->phone }}</p>
                        @endif
                        <span class="inline-block mt-2 px-2.5 py-1 rounded-lg text-xs font-bold bg-winga-100 dark:bg-winga-900/30 text-winga-700 dark:text-winga-300">{{ $roleLabel }}</span>
                    </div>
                </div>
            </div>

            {{-- Taarifa za msingi: Jina, Email, Simu --}}
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Taarifa za msingi</h3>
                <form wire:submit="updateProfileInformation" class="space-y-4">
                    <flux:input wire:model="name" label="Jina kamili" type="text" required autocomplete="name" />
                    <div>
                        <flux:input wire:model="email" label="Barua pepe" type="email" required autocomplete="email" />
                        @if ($this->hasUnverifiedEmail)
                            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                Barua pepe haijathibitishwa.
                                <button type="button" wire:click="resendVerificationNotification" class="text-winga-600 dark:text-winga-400 font-medium hover:underline">Tuma kiungo tena</button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">Kiungo kipya kimetumwa.</p>
                            @endif
                        @endif
                    </div>
                    <flux:input wire:model="phone" label="Namba ya simu" type="tel" placeholder="0712345678" autocomplete="tel" />
                    <div class="flex items-center gap-3 pt-2">
                        <flux:button variant="primary" type="submit" data-test="update-profile-button">Hifadhi</flux:button>
                        <x-action-message on="profile-updated" class="text-sm text-green-600 dark:text-green-400">Imehifadhiwa.</x-action-message>
                    </div>
                </form>
            </div>

            {{-- Hariri wasifu (Eneo + Muajili/Mfanyakazi + Picha) --}}
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Hariri wasifu wako</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Data ulizojaza wakati wa set profile — unaweza kuzihariri hapa.</p>

                <form wire:submit="updateOnboardingInformation" class="space-y-6">
                    {{-- Eneo (both) --}}
                    <div>
                        <h4 class="text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-3 flex items-center gap-2">
                            <span class="text-lg">📍</span> Eneo
                        </h4>
                        <div class="grid gap-4 sm:grid-cols-1">
                            <div>
                                <flux:label>Mkoa</flux:label>
                                <flux:select wire:model="mkoa" placeholder="Chagua mkoa">
                                    @foreach($regions as $region)
                                        <flux:select.option value="{{ $region }}">{{ $region }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </div>
                            <div>
                                <flux:label>Wilaya</flux:label>
                                <flux:input wire:model="wilaya" placeholder="Mfano: Ilala, Kinondoni" />
                            </div>
                            <div>
                                <flux:label>Mtaa / Kata</flux:label>
                                <flux:input wire:model="mtaa" placeholder="Mfano: Sinza, Manzese" />
                            </div>
                        </div>
                    </div>

                    @if($user->isMuajili())
                    {{-- Muajili: Malipo --}}
                    <div>
                        <h4 class="text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-3 flex items-center gap-2">
                            <span class="text-lg">💰</span> Njia ya malipo
                        </h4>
                        <div class="space-y-3">
                            @foreach([['id' => 'mpesa', 'name' => 'M-Pesa'], ['id' => 'tigopesa', 'name' => 'Tigo Pesa'], ['id' => 'airtelmoney', 'name' => 'Airtel Money']] as $method)
                                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer {{ $payment_method === $method['id'] ? 'border-winga-500 bg-winga-50 dark:bg-winga-900/20' : 'border-zinc-200 dark:border-zinc-700' }}">
                                    <input type="radio" wire:model.live="payment_method" value="{{ $method['id'] }}" class="sr-only" />
                                    <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $method['name'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <flux:label>Namba ya malipo</flux:label>
                            <flux:input wire:model="payment_number" type="tel" placeholder="0712 345 678" />
                        </div>
                    </div>
                    @endif

                    @if($user->isMfanyakazi())
                    {{-- Mfanyakazi: Ustadi --}}
                    <div>
                        <h4 class="text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-3 flex items-center gap-2">
                            <span class="text-lg">🔧</span> Ustadi wako
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($skillsList as $skill)
                                <button type="button" wire:click="toggleSkill('{{ $skill['name'] }}')"
                                    class="flex items-center gap-2 px-3 py-2.5 rounded-xl border-2 text-sm font-medium transition-all
                                    {{ in_array($skill['name'], $ustadi) ? 'border-winga-500 bg-winga-50 dark:bg-winga-900/20 text-winga-700 dark:text-winga-300' : 'border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400 hover:border-winga-300' }}">
                                    <span>{{ $skill['icon'] }}</span>
                                    <span class="truncate">{{ $skill['name'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Mfanyakazi: Bei --}}
                    <div>
                        <h4 class="text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-3 flex items-center gap-2">
                            <span class="text-lg">💵</span> Bei
                        </h4>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex gap-2">
                                @foreach([['id' => 'siku', 'l' => 'Kwa siku'], ['id' => 'saa', 'l' => 'Kwa saa'], ['id' => 'kazi', 'l' => 'Kwa kazi']] as $t)
                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                        <input type="radio" wire:model.live="bei_aina" value="{{ $t['id'] }}" class="rounded border-zinc-300" />
                                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $t['l'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="min-w-[140px]">
                                <flux:input wire:model="bei_wastani" type="number" placeholder="50000" min="0" step="1000" />
                            </div>
                        </div>
                    </div>

                    {{-- Mfanyakazi: Uzoefu na siku --}}
                    <div>
                        <h4 class="text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-3 flex items-center gap-2">
                            <span class="text-lg">📋</span> Uzoefu na siku
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <flux:label>Uzoefu (miaka)</flux:label>
                                <flux:select wire:model="uzoefu_miaka">
                                    <option value="0">Chini ya mwaka 1</option>
                                    <option value="1">1-2</option>
                                    <option value="3">3-5</option>
                                    <option value="5">5-10</option>
                                    <option value="10">10+</option>
                                </flux:select>
                            </div>
                            <div>
                                <flux:label>Siku unazopatikana</flux:label>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach(['Jmt', 'Jtt', 'Jnn', 'Jtn', 'Alh', 'Ijm', 'Jpi'] as $day)
                                        <button type="button" wire:click="toggleDay('{{ $day }}')"
                                            class="px-3 py-1.5 rounded-lg border-2 text-xs font-bold transition-all
                                            {{ in_array($day, $siku_zinazopatikana) ? 'border-winga-500 bg-winga-500 text-white' : 'border-zinc-200 dark:border-zinc-700 text-zinc-500 hover:border-winga-300' }}">
                                            {{ $day }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Mfanyakazi: NIDA & VETA --}}
                    <div>
                        <h4 class="text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-3 flex items-center gap-2">
                            <span class="text-lg">🪪</span> Uthibitisho (hiari)
                        </h4>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <flux:label>NIDA</flux:label>
                                <flux:input wire:model="nida" placeholder="Namba ya kitambulisho" />
                            </div>
                            <div>
                                <flux:label>VETA</flux:label>
                                <flux:input wire:model="veta" placeholder="VETA-XXXX" />
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Picha ya wasifu (both) --}}
                    <div>
                        <h4 class="text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-3 flex items-center gap-2">
                            <span class="text-lg">📸</span> Picha ya wasifu
                        </h4>
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="w-20 h-20 rounded-xl object-cover mb-2" alt="" />
                        @endif
                        <label class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 cursor-pointer text-sm text-zinc-600 dark:text-zinc-400 hover:border-winga-400">
                            <input type="file" wire:model="photo" accept="image/*" class="sr-only" />
                            Chagua picha
                        </label>
                        <p class="text-xs text-zinc-500 mt-1">Si lazima. Inabadilisha picha yako ya wasifu.</p>
                    </div>

                    <div class="flex items-center gap-3 pt-2 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:button variant="primary" type="submit">Hifadhi wasifu</flux:button>
                        <x-action-message on="profile-updated" class="text-sm text-green-600 dark:text-green-400">Wasifu umehifadhiwa.</x-action-message>
                    </div>
                </form>
            </div>

            @if ($this->showDeleteUser)
                <livewire:pages::settings.delete-user-form />
            @endif
        </div>
    </x-pages::settings.layout>
</section>
