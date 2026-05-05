<div class="max-w-md w-full mx-auto mt-10">
    {{-- Logo / Brand --}}
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-winga-500 shadow-lg shadow-winga-500/25 mb-3">
            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
    </div>

    {{-- Step Indicator --}}
    <div class="flex items-center justify-center gap-0 mb-6 px-4">
        @php
            $steps = [
                1 => ['label' => 'Barua Pepe'],
                2 => ['label' => 'OTP'],
                3 => ['label' => 'Nenosiri'],
                4 => ['label' => 'Imefanikiwa'],
            ];
        @endphp

        @foreach($steps as $num => $s)
            @if($loop->index > 0)
                <div class="flex-1 h-0.5 mx-1 rounded-full transition-colors duration-500 {{ $step >= $num ? 'bg-winga-500' : 'bg-zinc-200 dark:bg-zinc-700' }}"></div>
            @endif
            <div class="flex flex-col items-center">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-500
                    {{ $step > $num ? 'bg-winga-500 text-white shadow-md shadow-winga-500/25' : ($step === $num ? 'bg-winga-500 text-white ring-4 ring-winga-500/20 shadow-lg shadow-winga-500/30' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400 dark:text-zinc-500') }}">
                    @if($step > $num)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    @else
                        {{ $num }}
                    @endif
                </div>
                <span class="text-[10px] mt-1.5 font-medium transition-colors duration-300 {{ $step >= $num ? 'text-winga-600 dark:text-winga-400' : 'text-zinc-400 dark:text-zinc-600' }}">{{ $s['label'] }}</span>
            </div>
        @endforeach
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-xl overflow-hidden">

        @if($step === 1)
            {{-- Step 1: Enter Email --}}
            <div class="px-6 pt-6 pb-2 text-center">
                <div class="text-3xl mb-2">✉️</div>
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Umesahau Nenosiri?</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1.5 text-sm leading-relaxed">
                    Ingiza barua pepe yako na tutakutumia nambari ya OTP kubadilisha nenosiri lako.
                </p>
            </div>

            @if($errorMessage)
                <div class="mx-6 mb-4 p-3 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm border border-red-200 dark:border-red-800 text-center font-medium">
                    {{ $errorMessage }}
                </div>
            @endif

            <form wire:submit="sendOtp" class="px-6 pb-6 space-y-5">
                <div>
                    <flux:label>Barua Pepe</flux:label>
                    <flux:input
                        wire:model="email"
                        type="email"
                        placeholder="email@example.com"
                        required
                        autofocus
                    />
                    @error('email') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <flux:button type="submit" class="w-full !bg-winga-500 hover:!bg-winga-600 !text-white !font-bold py-3 text-base rounded-xl">
                    <span wire:loading.remove wire:target="sendOtp">Tuma OTP →</span>
                    <span wire:loading wire:target="sendOtp" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Inatuma...
                    </span>
                </flux:button>
            </form>

            <div class="px-6 pb-5 text-center border-t border-zinc-100 dark:border-zinc-800 pt-4">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    Unakumbuka nenosiri?
                    <a href="{{ route('login') }}" class="text-winga-600 dark:text-winga-400 hover:text-winga-700 font-semibold transition-colors" wire:navigate>
                        Ingia
                    </a>
                </p>
            </div>

        @elseif($step === 2)
            {{-- Step 2: Verify OTP only --}}
            <div class="px-6 pt-6 pb-2 text-center">
                <div class="text-3xl mb-2">🛡️</div>
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Thibitisha OTP</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1.5 text-sm leading-relaxed">
                    Tumekutumia nambari ya OTP kwenye
                    <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $email }}</span>
                </p>
            </div>

            @if($successMessage)
                <div class="mx-6 mb-4 p-3 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-sm border border-green-200 dark:border-green-800 text-center font-medium">
                    ✅ {{ $successMessage }}
                </div>
            @endif

            @if($errorMessage)
                <div class="mx-6 mb-4 p-3 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm border border-red-200 dark:border-red-800 text-center font-medium">
                    ❌ {{ $errorMessage }}
                </div>
            @endif

            <form wire:submit="verifyOtp" class="px-6 pb-6 space-y-5">
                <div>
                    <flux:label>Nambari ya OTP</flux:label>
                    <flux:input
                        wire:model="otp"
                        maxlength="6"
                        placeholder="0 0 0 0 0 0"
                        class="!text-center !text-2xl !tracking-[0.4em] !font-mono !py-3"
                    />
                    @error('otp') <span class="text-red-500 text-xs text-center block mt-1">{{ $message }}</span> @enderror
                </div>

                <flux:button type="submit" class="w-full !bg-winga-500 hover:!bg-winga-600 !text-white !font-bold py-3 text-base rounded-xl">
                    <span wire:loading.remove wire:target="verifyOtp">Thibitisha →</span>
                    <span wire:loading wire:target="verifyOtp" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Inathibitisha...
                    </span>
                </flux:button>
            </form>

            <div class="px-6 pb-5 text-center border-t border-zinc-100 dark:border-zinc-800 pt-4">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    Hukupokea OTP?
                    <button wire:click="resendOtp" type="button" class="text-winga-600 dark:text-winga-400 hover:text-winga-700 font-semibold transition-colors">
                        Tuma tena ↻
                    </button>
                </p>
            </div>

        @elseif($step === 3)
            {{-- Step 3: New Password (only after OTP verified) --}}
            <div class="px-6 pt-6 pb-2 text-center">
                <div class="text-3xl mb-2">🔐</div>
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Weka Nenosiri Jipya</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1.5 text-sm leading-relaxed">
                    OTP imethibitishwa! Sasa weka nenosiri jipya la akaunti yako.
                </p>
            </div>

            @if($errorMessage)
                <div class="mx-6 mb-4 p-3 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm border border-red-200 dark:border-red-800 text-center font-medium">
                    ❌ {{ $errorMessage }}
                </div>
            @endif

            <form wire:submit="resetPassword" class="px-6 pb-6 space-y-5">
                <div>
                    <flux:label>Nenosiri Jipya</flux:label>
                    <flux:input
                        wire:model="password"
                        type="password"
                        placeholder="Angalau herufi 8"
                        viewable
                        autofocus
                    />
                    @error('password') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <flux:label>Thibitisha Nenosiri</flux:label>
                    <flux:input
                        wire:model="password_confirmation"
                        type="password"
                        placeholder="Rudia nenosiri"
                        viewable
                    />
                </div>

                <flux:button type="submit" class="w-full !bg-winga-500 hover:!bg-winga-600 !text-white !font-bold py-3 text-base rounded-xl">
                    <span wire:loading.remove wire:target="resetPassword">Badilisha Nenosiri →</span>
                    <span wire:loading wire:target="resetPassword" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Inabadilisha...
                    </span>
                </flux:button>
            </form>

        @elseif($step === 4)
            {{-- Step 4: Success --}}
            <div class="px-6 py-10 text-center space-y-5">
                <div class="relative mx-auto w-20 h-20">
                    <div class="absolute inset-0 rounded-full bg-emerald-500/20 animate-ping"></div>
                    <div class="relative w-20 h-20 rounded-full bg-emerald-500 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>

                <div class="space-y-2">
                    <h2 class="text-xl font-bold text-emerald-700 dark:text-emerald-400">Nenosiri Limebadilishwa!</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">
                        Nenosiri lako limebadilishwa kikamilifu.<br>Sasa unaweza kuingia na nenosiri jipya.
                    </p>
                </div>

                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-winga-500 hover:bg-winga-600 text-white font-bold rounded-xl transition-colors shadow-lg shadow-winga-500/25" wire:navigate>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Ingia Sasa
                </a>
            </div>
        @endif
    </div>
</div>
