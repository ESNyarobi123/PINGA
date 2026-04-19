<div class="max-w-md w-full mx-auto p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-lg mt-10">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.auth.register_title') }}</h2>
        <p class="text-zinc-600 dark:text-zinc-400 mt-1">{{ __('messages.auth.register_subtitle') }}</p>

        {{-- Step indicator --}}
        <div class="flex items-center justify-center gap-2 mt-4">
            <div class="flex items-center gap-1">
                <span class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold {{ $step === 1 ? 'bg-winga-500 text-white ring-2 ring-winga-200 dark:ring-winga-800' : 'bg-winga-500 text-white' }}">
                    @if($step > 1) ✓ @else 1 @endif
                </span>
                <span class="text-xs text-zinc-500 dark:text-zinc-400 hidden sm:inline">{{ __('messages.auth.step_info_location') }}</span>
            </div>
            <div class="w-8 h-0.5 rounded {{ $step >= 2 ? 'bg-winga-500' : 'bg-zinc-200 dark:bg-zinc-700' }}"></div>
            <div class="flex items-center gap-1">
                <span class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold {{ $step === 2 ? 'bg-winga-500 text-white ring-2 ring-winga-200 dark:ring-winga-800' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-500' }}">2</span>
                <span class="text-xs text-zinc-500 dark:text-zinc-400 hidden sm:inline">{{ __('messages.auth.step_password_role') }}</span>
            </div>
        </div>
    </div>

    @if($step === 1)
        {{-- Step 1: Jina, Email, Simu, Mkoa, Wilaya --}}
        <form wire:submit="nextStep" class="space-y-4">
            <div>
                <flux:label>{{ __('messages.auth.full_name') }} <span class="text-red-500">*</span></flux:label>
                <flux:input wire:model="name" type="text" placeholder="{{ __('messages.auth.full_name_placeholder') }}" />
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <flux:label>{{ __('messages.auth.email') }} <span class="text-red-500">*</span></flux:label>
                <flux:input wire:model="email" type="email" placeholder="{{ __('messages.auth.email_placeholder') }}" />
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <flux:label>{{ __('messages.auth.phone') }} <span class="text-red-500">*</span></flux:label>
                <flux:input wire:model="phone" type="tel" placeholder="{{ __('messages.auth.phone_placeholder') }}" />
                @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <flux:label>{{ __('messages.auth.region') }} <span class="text-red-500">*</span></flux:label>
                    <flux:select wire:model.live="mkoa" placeholder="{{ __('messages.auth.region_placeholder') }}">
                        @foreach(['Dar es Salaam','Arusha','Mwanza','Dodoma','Tanga','Mbeya','Morogoro','Kilimanjaro','Kagera','Mara','Pwani','Iringa','Ruvuma','Shinyanga','Singida','Tabora','Kigoma','Lindi','Mtwara','Geita','Katavi','Njombe','Rukwa','Simiyu','Songwe'] as $r)
                            <flux:select.option value="{{ $r }}">{{ $r }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error('mkoa') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <flux:label>{{ __('messages.auth.district') }} <span class="text-xs text-zinc-400">({{ __('messages.common.optional') }})</span></flux:label>
                    <flux:select wire:model="wilaya" placeholder="{{ __('messages.auth.district_placeholder') }}" :disabled="empty($mkoa)">
                        @foreach($wilayaOptions as $w)
                            <flux:select.option value="{{ $w }}">{{ $w }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error('wilaya') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <flux:button type="submit" class="w-full !bg-winga-500 hover:!bg-winga-600 !text-white !font-bold">
                {{ __('messages.common.next') }} →
            </flux:button>
        </form>
    @else
        {{-- Step 2: Role, Nenosiri, WhatsApp --}}
        <form wire:submit="register" class="space-y-4">
            <div>
                <flux:label>{{ __('messages.auth.role_question') }} <span class="text-red-500">*</span></flux:label>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    <label class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 cursor-pointer text-center transition-all
                        {{ $role === 'winga' ? 'border-winga-500 bg-winga-50 dark:bg-winga-900/20 dark:border-winga-500' : 'border-zinc-200 dark:border-zinc-700 hover:border-winga-300' }}">
                        <input type="radio" wire:model.live="role" value="winga" class="sr-only" />
                        <span class="text-2xl">🛠️</span>
                        <span class="text-sm font-bold text-zinc-800 dark:text-zinc-200">{{ __('messages.auth.winga_role') }}</span>
                        <span class="text-xs text-zinc-500">{{ __('messages.auth.winga_role_desc') }}</span>
                    </label>
                    <label class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 cursor-pointer text-center transition-all
                        {{ $role === 'mteja' ? 'border-winga-500 bg-winga-50 dark:bg-winga-900/20 dark:border-winga-500' : 'border-zinc-200 dark:border-zinc-700 hover:border-winga-300' }}">
                        <input type="radio" wire:model.live="role" value="mteja" class="sr-only" />
                        <span class="text-2xl">💼</span>
                        <span class="text-sm font-bold text-zinc-800 dark:text-zinc-200">{{ __('messages.auth.mteja_role') }}</span>
                        <span class="text-xs text-zinc-500">{{ __('messages.auth.mteja_role_desc') }}</span>
                    </label>
                </div>
                @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            @if($role === 'winga')
            <div>
                <flux:label>{{ __('messages.auth.whatsapp') }} <span class="text-red-500">*</span></flux:label>
                <flux:input wire:model="whatsapp" type="tel" placeholder="{{ __('messages.auth.phone_placeholder') }}" />
                <p class="text-xs text-zinc-400 mt-1">{{ __('messages.auth.whatsapp_note') }}</p>
                @error('whatsapp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            @else
            <div>
                <flux:label>{{ __('messages.auth.whatsapp') }} ({{ __('messages.common.optional') }})</flux:label>
                <flux:input wire:model="whatsapp" type="tel" placeholder="{{ __('messages.auth.phone_placeholder') }}" />
                @error('whatsapp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            @endif

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <flux:label>{{ __('messages.auth.password') }} <span class="text-red-500">*</span></flux:label>
                    <flux:input wire:model="password" type="password" placeholder="{{ __('messages.auth.password_placeholder') }}" />
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <flux:label>{{ __('messages.auth.password_confirm') }}</flux:label>
                    <flux:input wire:model="password_confirmation" type="password" placeholder="{{ __('messages.auth.password_confirm_placeholder') }}" />
                </div>
            </div>

            <div class="flex gap-2 pt-1">
                <flux:button type="button" wire:click="prevStep" variant="ghost" class="flex-1">
                    ← {{ __('messages.common.back') }}
                </flux:button>
                <flux:button type="submit" class="flex-1 !bg-winga-500 hover:!bg-winga-600 !text-white !font-bold" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('messages.auth.register_button') }}</span>
                    <span wire:loading>{{ __('messages.common.loading') }}</span>
                </flux:button>
            </div>
        </form>
    @endif

    <p class="text-center text-sm text-zinc-600 dark:text-zinc-400 mt-6">
        {{ __('messages.auth.have_account') }}
        <a href="{{ route('login') }}" class="text-winga-600 hover:text-winga-700 font-bold" wire:navigate>{{ __('messages.auth.login_here') }}</a>
    </p>
</div>
