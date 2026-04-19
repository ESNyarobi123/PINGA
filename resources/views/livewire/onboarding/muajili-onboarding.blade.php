<div class="min-h-screen bg-gradient-to-br from-winga-50 via-white to-winga-50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 py-8 lg:py-16">
    <div class="mx-auto max-w-2xl px-4 sm:px-6">
        {{-- Header --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 mb-4">
                <x-app-logo-icon class="size-10" />
                <span class="text-2xl font-bold text-zinc-900 dark:text-white">Winga</span>
            </a>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.employer_onboarding.welcome') }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">{{ __('messages.employer_onboarding.subtitle') }}</p>
        </div>

        {{-- Step Progress Bar --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                @for($i = 1; $i <= $totalSteps; $i++)
                    <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full text-sm font-bold transition-all duration-300
                            {{ $step > $i ? 'bg-winga-500 text-white' : ($step === $i ? 'bg-winga-500 text-white ring-4 ring-winga-200 dark:ring-winga-800' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-500 dark:text-zinc-400') }}">
                            @if($step > $i)
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $i }}
                            @endif
                        </div>
                        @if($i < $totalSteps)
                            <div class="flex-1 h-1 mx-2 rounded-full {{ $step > $i ? 'bg-winga-500' : 'bg-zinc-200 dark:bg-zinc-700' }} transition-all duration-300"></div>
                        @endif
                    </div>
                @endfor
            </div>
            <div class="flex justify-between text-xs text-zinc-500 dark:text-zinc-400 px-1">
                <span>{{ __('messages.employer_onboarding.step_location') }}</span>
                <span>{{ __('messages.employer_onboarding.step_whatsapp') }}</span>
                <span>{{ __('messages.employer_onboarding.step_payment') }}</span>
                <span>{{ __('messages.employer_onboarding.step_photo') }}</span>
            </div>
        </div>

        {{-- Card Container --}}
        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-zinc-100 dark:border-zinc-700 overflow-hidden">
            {{-- Step 1: Location --}}
            @if($step === 1)
                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-winga-100 dark:bg-winga-900/30 flex items-center justify-center text-winga-600 dark:text-winga-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                        <div>
                            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.employer_onboarding.step1_title') }}</h2>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.employer_onboarding.step1_desc') }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <flux:label>{{ __('messages.onboarding.region') }}</flux:label>
                            <flux:select wire:model="mkoa" :placeholder="__('messages.onboarding.region_placeholder')">
                                @foreach(['Dar es Salaam', 'Arusha', 'Mwanza', 'Dodoma', 'Tanga', 'Mbeya', 'Morogoro', 'Kilimanjaro', 'Iringa', 'Kigoma', 'Mara', 'Lindi', 'Mtwara', 'Ruvuma', 'Rukwa', 'Kagera', 'Shinyanga', 'Singida', 'Tabora', 'Pwani', 'Geita', 'Katavi', 'Njombe', 'Simiyu', 'Songwe'] as $region)
                                    <flux:select.option value="{{ $region }}">{{ $region }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            @error('mkoa') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <flux:label>{{ __('messages.onboarding.district') }}</flux:label>
                            <flux:input wire:model="wilaya" :placeholder="__('messages.onboarding.district_placeholder')" />
                            @error('wilaya') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <flux:label>{{ __('messages.onboarding.street') }}</flux:label>
                            <flux:input wire:model="mtaa" :placeholder="__('messages.onboarding.street_placeholder')" />
                            @error('mtaa') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <button type="button" onclick="getLocation()" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-winga-50 dark:bg-winga-900/20 text-winga-600 dark:text-winga-400 text-sm font-medium hover:bg-winga-100 dark:hover:bg-winga-900/40 transition-colors">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ __('messages.onboarding.use_gps') }}
                        </button>
                    </div>
                </div>
            @endif

            {{-- Step 2: WhatsApp --}}
            @if($step === 2)
                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-2xl">💬</div>
                        <div>
                            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.employer_onboarding.step2_title') }}</h2>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.employer_onboarding.step2_desc') }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <flux:label>{{ __('messages.employer_onboarding.whatsapp_label') }}</flux:label>
                            <flux:input wire:model="whatsapp" type="tel" placeholder="0712 345 678" icon="phone" />
                            @error('whatsapp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="bg-winga-50 dark:bg-winga-900/20 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <svg class="size-5 text-winga-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm text-winga-700 dark:text-winga-300">{{ __('messages.employer_onboarding.whatsapp_note') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Step 3: Payment Method --}}
            @if($step === 3)
                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-accent-orange-100 dark:bg-accent-orange-900/30 flex items-center justify-center text-2xl">💰</div>
                        <div>
                            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.employer_onboarding.step3_title') }}</h2>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.employer_onboarding.step3_desc') }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        @foreach([
                            ['id' => 'mpesa', 'name' => 'M-Pesa', 'icon' => '🟢', 'desc' => 'Vodacom M-Pesa'],
                            ['id' => 'tigopesa', 'name' => 'Tigo Pesa', 'icon' => '🔵', 'desc' => 'Tigo Pesa / MixxByYas'],
                            ['id' => 'airtelmoney', 'name' => 'Airtel Money', 'icon' => '🔴', 'desc' => 'Airtel Money'],
                        ] as $method)
                            <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200
                                {{ $njia_malipo === $method['id'] ? 'border-winga-500 bg-winga-50 dark:bg-winga-900/20 dark:border-winga-500' : 'border-zinc-200 dark:border-zinc-700 hover:border-winga-300 dark:hover:border-winga-600' }}">
                                <input type="radio" wire:model.live="njia_malipo" value="{{ $method['id'] }}" class="sr-only" />
                                <span class="text-2xl">{{ $method['icon'] }}</span>
                                <div class="flex-1">
                                    <p class="font-semibold text-zinc-900 dark:text-white">{{ $method['name'] }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $method['desc'] }}</p>
                                </div>
                                @if($njia_malipo === $method['id'])
                                    <svg class="size-6 text-winga-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                @endif
                            </label>
                        @endforeach
                        @error('njia_malipo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if($njia_malipo)
                        <div>
                            <flux:label>{{ __('messages.employer_onboarding.payment_number', ['method' => $njia_malipo === 'mpesa' ? 'M-Pesa' : ($njia_malipo === 'tigopesa' ? 'Tigo Pesa' : 'Airtel Money')]) }}</flux:label>
                            <flux:input wire:model="namba_malipo" type="tel" placeholder="0712 345 678" />
                            @error('namba_malipo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    @endif
                </div>
            @endif

            {{-- Step 4: Photo --}}
            @if($step === 4)
                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-2xl">📸</div>
                        <div>
                            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.employer_onboarding.step4_title') }}</h2>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.employer_onboarding.step4_desc') }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-center gap-6">
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="w-32 h-32 rounded-2xl object-cover shadow-lg" />
                        @else
                            <div class="w-32 h-32 rounded-2xl bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                                <svg class="size-12 text-zinc-300 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            </div>
                        @endif

                        <label class="cursor-pointer px-6 py-3 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 hover:border-winga-400 dark:hover:border-winga-500 transition-colors text-sm text-zinc-600 dark:text-zinc-400 font-medium">
                            <input type="file" wire:model="photo" accept="image/*" class="sr-only" />
                            {{ __('messages.employer_onboarding.choose_photo') }}
                        </label>
                    </div>

                    <div class="mt-8 bg-winga-50 dark:bg-winga-900/20 rounded-xl p-5 text-center">
                        <p class="text-lg font-bold text-winga-700 dark:text-winga-300">{{ __('messages.employer_onboarding.ready_title') }}</p>
                        <p class="text-sm text-winga-600/80 dark:text-winga-400 mt-1">{{ __('messages.employer_onboarding.ready_desc') }}</p>
                    </div>
                </div>
            @endif

            {{-- Footer Actions --}}
            <div class="flex items-center justify-between px-6 lg:px-8 py-4 bg-zinc-50 dark:bg-zinc-900/50 border-t border-zinc-100 dark:border-zinc-700">
                @if($step > 1)
                    <flux:button wire:click="prevStep" variant="ghost">
                        {{ __('messages.employer_onboarding.back') }}
                    </flux:button>
                @else
                    <div></div>
                @endif

                @if($step < $totalSteps)
                    <flux:button wire:click="nextStep" class="!bg-winga-500 hover:!bg-winga-600 !text-white !shadow-lg !shadow-winga-500/20">
                        {{ __('messages.employer_onboarding.continue') }}
                    </flux:button>
                @else
                    <flux:button wire:click="finish" class="!bg-winga-500 hover:!bg-winga-600 !text-white !shadow-lg !shadow-winga-500/20">
                        {{ __('messages.employer_onboarding.finish') }}
                    </flux:button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                @this.set('latitude', pos.coords.latitude);
                @this.set('longitude', pos.coords.longitude);
            });
        }
    }
</script>
