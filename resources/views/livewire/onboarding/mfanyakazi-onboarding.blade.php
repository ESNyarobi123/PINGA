<div class="min-h-screen bg-gradient-to-br from-winga-50 via-white to-winga-50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 py-8 lg:py-16">
    <div class="mx-auto max-w-2xl px-4 sm:px-6">
        {{-- Header --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 mb-4">
                <x-app-logo-icon class="size-10" />
                <span class="text-2xl font-bold text-zinc-900 dark:text-white">Winga</span>
            </a>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.onboarding.welcome') }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">{{ __('messages.onboarding.subtitle') }}</p>
        </div>

        {{-- Step Progress Bar --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                @for($i = 1; $i <= $totalSteps; $i++)
                    <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                        <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full text-xs sm:text-sm font-bold transition-all duration-300
                            {{ $step > $i ? 'bg-winga-500 text-white' : ($step === $i ? 'bg-winga-500 text-white ring-4 ring-winga-200 dark:ring-winga-800' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-500 dark:text-zinc-400') }}">
                            @if($step > $i)
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $i }}
                            @endif
                        </div>
                        @if($i < $totalSteps)
                            <div class="flex-1 h-1 mx-1 rounded-full {{ $step > $i ? 'bg-winga-500' : 'bg-zinc-200 dark:bg-zinc-700' }} transition-all duration-300"></div>
                        @endif
                    </div>
                @endfor
            </div>
            <p class="text-center text-xs text-zinc-400 dark:text-zinc-500 mt-1">{{ __('messages.onboarding.step_of', ['step' => $step, 'total' => $totalSteps]) }}</p>
        </div>

        {{-- Card --}}
        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-zinc-100 dark:border-zinc-700 overflow-hidden">

            {{-- Step 1: Location --}}
            @if($step === 1)
            <div class="p-6 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-winga-100 dark:bg-winga-900/30 flex items-center justify-center text-2xl">📍</div>
                    <div>
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.onboarding.step1_title') }}</h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.onboarding.step1_desc') }}</p>
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

                    <button type="button" onclick="getLocation()" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-winga-50 dark:bg-winga-900/20 text-winga-600 dark:text-winga-400 text-sm font-medium hover:bg-winga-100 transition-colors">
                        {{ __('messages.onboarding.use_gps') }}
                    </button>
                </div>
            </div>
            @endif

            {{-- Step 2: Skills --}}
            @if($step === 2)
            <div class="p-6 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-accent-orange-100 dark:bg-accent-orange-900/30 flex items-center justify-center text-2xl">🔧</div>
                    <div>
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.onboarding.step2_title') }}</h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.onboarding.step2_desc') }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($dbSkills as $categoryName => $categoryData)
                        @if(count($categoryData['skills']) > 0)
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-xl p-4 border border-zinc-100 dark:border-zinc-800">
                            <h3 class="text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-3 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-white dark:bg-zinc-800 flex items-center justify-center text-lg shadow-sm">{{ $categoryData['icon'] }}</span>
                                <span>{{ $categoryName }}</span>
                                <span class="text-xs font-normal text-zinc-400 ml-auto">{{ count($categoryData['skills']) }} skills</span>
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                @foreach($categoryData['skills'] as $skill)
                                    <button wire:click="toggleSkill('{{ $skill }}')" type="button"
                                        class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border-2 text-sm font-medium transition-all duration-200
                                        {{ in_array($skill, $ustadi) ? 'border-winga-500 bg-winga-50 dark:bg-winga-900/20 text-winga-700 dark:text-winga-300 dark:border-winga-500' : 'border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400 hover:border-winga-300 bg-white dark:bg-zinc-800' }}">
                                        <span class="truncate">{{ $skill }}</span>
                                        @if(in_array($skill, $ustadi))
                                            <svg class="w-4 h-4 text-winga-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                @if(count($ustadi) > 0)
                    <div class="mt-4 p-3 rounded-xl bg-winga-50 dark:bg-winga-900/20">
                        <p class="text-sm font-medium text-winga-700 dark:text-winga-300">{{ __('messages.onboarding.selected') }} {{ implode(', ', $ustadi) }}</p>
                    </div>
                @endif
                @error('ustadi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            @endif

            {{-- Step 3: Pricing --}}
            @if($step === 3)
            <div class="p-6 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-2xl">💵</div>
                    <div>
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.onboarding.step3_title') }}</h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.onboarding.step3_desc') }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <flux:label>{{ __('messages.onboarding.price_type') }}</flux:label>
                        <div class="grid grid-cols-3 gap-2 mt-2">
                            @foreach([
                                ['id' => 'siku', 'label' => __('messages.onboarding.per_day'), 'icon' => '📅'],
                                ['id' => 'saa', 'label' => __('messages.onboarding.per_hour'), 'icon' => '⏰'],
                                ['id' => 'kazi', 'label' => __('messages.onboarding.per_job'), 'icon' => '✅'],
                            ] as $type)
                                <label class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 cursor-pointer text-center transition-all
                                    {{ $bei_aina === $type['id'] ? 'border-winga-500 bg-winga-50 dark:bg-winga-900/20 dark:border-winga-500' : 'border-zinc-200 dark:border-zinc-700 hover:border-winga-300' }}">
                                    <input type="radio" wire:model.live="bei_aina" value="{{ $type['id'] }}" class="sr-only" />
                                    <span class="text-xl">{{ $type['icon'] }}</span>
                                    <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ $type['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <flux:label>{{ __('messages.onboarding.price_label') }}</flux:label>
                        <flux:input wire:model="bei_wastani" type="number" placeholder="50000" min="1000" step="1000" />
                        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">{{ __('messages.onboarding.price_hint') }}</p>
                        @error('bei_wastani') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            @endif

            {{-- Step 4: Experience & Days --}}
            @if($step === 4)
            <div class="p-6 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-2xl">📋</div>
                    <div>
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.onboarding.step4_title') }}</h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.onboarding.step4_desc') }}</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <flux:label>{{ __('messages.onboarding.experience') }}</flux:label>
                        <flux:select wire:model="uzoefu_miaka">
                            <flux:select.option value="0">{{ __('messages.onboarding.exp_beginner') }}</flux:select.option>
                            <flux:select.option value="1">{{ __('messages.onboarding.exp_1_2') }}</flux:select.option>
                            <flux:select.option value="3">{{ __('messages.onboarding.exp_3_5') }}</flux:select.option>
                            <flux:select.option value="5">{{ __('messages.onboarding.exp_5_10') }}</flux:select.option>
                            <flux:select.option value="10">{{ __('messages.onboarding.exp_10_plus') }}</flux:select.option>
                        </flux:select>
                    </div>

                    <div>
                        <flux:label>{{ __('messages.onboarding.available_days') }}</flux:label>
                        <div class="grid grid-cols-4 sm:grid-cols-7 gap-2 mt-2">
                            @foreach(['Jmt', 'Jtt', 'Jnn', 'Jtn', 'Alh', 'Ijm', 'Jpi'] as $day)
                                <button wire:click="toggleDay('{{ $day }}')" type="button"
                                    class="px-2 py-2.5 rounded-xl border-2 text-xs font-bold transition-all duration-200
                                    {{ in_array($day, $siku_zinazopatikana) ? 'border-winga-500 bg-winga-500 text-white' : 'border-zinc-200 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400 hover:border-winga-300' }}">
                                    {{ $day }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Step 5: Profile photo --}}
            @if($step === 5)
            <div class="p-6 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-2xl">📸</div>
                    <div>
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.onboarding.step5_title') }}</h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.onboarding.step5_desc') }}</p>
                    </div>
                </div>

                <div class="flex flex-col items-center gap-6">
                    @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-32 h-32 rounded-2xl object-cover shadow-lg" alt="" />
                    @else
                        <div class="w-32 h-32 rounded-2xl bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                            <svg class="size-12 text-zinc-300 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        </div>
                    @endif

                    <label class="cursor-pointer px-6 py-3 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 hover:border-winga-400 dark:hover:border-winga-500 transition-colors text-sm text-zinc-600 dark:text-zinc-400 font-medium">
                        <input type="file" wire:model="photo" accept="image/*" class="sr-only" />
                        {{ __('messages.onboarding.choose_photo') }}
                    </label>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500">{{ __('messages.onboarding.photo_optional') }}</p>
                </div>
            </div>
            @endif

            {{-- Step 6: Portfolio --}}
            @if($step === 6)
            <div class="p-6 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-2xl">🖼️</div>
                    <div>
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.onboarding.step6_title') }}</h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.onboarding.step6_desc') }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @if(count($portfolio_photos) > 0)
                        <div class="grid grid-cols-3 gap-3">
                            @foreach($portfolio_photos as $idx => $photo)
                                <div class="relative aspect-square rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-700">
                                    <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover" />
                                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                        <span class="text-white text-xs font-bold bg-red-500 px-2 py-1 rounded-lg cursor-pointer" wire:click="$set('portfolio_photos', [])">✕</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <label class="flex flex-col items-center gap-3 p-8 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 hover:border-winga-400 dark:hover:border-winga-500 cursor-pointer transition-colors">
                        <input type="file" wire:model="portfolio_photos" accept="image/*" multiple class="sr-only" />
                        <svg class="size-10 text-zinc-300 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/></svg>
                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ __('messages.onboarding.upload_photos') }}</span>
                        <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ __('messages.onboarding.upload_hint') }}</span>
                    </label>

                    <p class="text-xs text-zinc-400 dark:text-zinc-500 text-center italic">{{ __('messages.onboarding.portfolio_optional') }}</p>
                </div>
            </div>
            @endif

            {{-- Step 7: NIDA (optional only) --}}
            @if($step === 7)
            <div class="p-6 lg:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-2xl">🪪</div>
                    <div>
                        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.onboarding.step7_title') }}</h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('messages.onboarding.step7_desc') }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <flux:label>{{ __('messages.onboarding.nida_label') }}</flux:label>
                        <flux:input wire:model="nida" placeholder="12345678901234567890" />
                        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">{{ __('messages.onboarding.nida_hint') }}</p>
                    </div>

                    <div class="bg-accent-orange-50 dark:bg-accent-orange-900/20 rounded-xl p-4 mt-4">
                        <div class="flex items-start gap-3">
                            <span class="text-xl">🏅</span>
                            <div>
                                <p class="text-sm font-semibold text-accent-orange-700 dark:text-accent-orange-300">{{ __('messages.onboarding.nida_benefit_title') }}</p>
                                <p class="text-xs text-accent-orange-600/80 dark:text-accent-orange-400 mt-1">{{ __('messages.onboarding.nida_benefit_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            {{-- Footer Actions --}}
            <div class="flex items-center justify-between px-6 lg:px-8 py-4 bg-zinc-50 dark:bg-zinc-900/50 border-t border-zinc-100 dark:border-zinc-700">
                @if($step > 1)
                    <flux:button wire:click="prevStep" variant="ghost">{{ __('messages.onboarding.back') }}</flux:button>
                @else
                    <div></div>
                @endif

                @if($step < $totalSteps)
                    <flux:button wire:click="nextStep" class="!bg-winga-500 hover:!bg-winga-600 !text-white !shadow-lg !shadow-winga-500/20">
                        {{ __('messages.onboarding.continue') }}
                    </flux:button>
                @else
                    <flux:button wire:click="finish" class="!bg-winga-500 hover:!bg-winga-600 !text-white !shadow-lg !shadow-winga-500/20">
                        {{ __('messages.onboarding.finish') }}
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
