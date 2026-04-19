<div class="max-w-md w-full mx-auto p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-lg mt-10">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.auth.login_title') }}</h2>
        <p class="text-zinc-600 dark:text-zinc-400 mt-1">{{ __('messages.auth.login_subtitle') }}</p>
    </div>

    <form wire:submit="login" class="space-y-4">
        <div>
            <flux:label>{{ __('messages.auth.email') }}</flux:label>
            <flux:input wire:model="email" type="email" required placeholder="{{ __('messages.auth.email_placeholder') }}" />
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <flux:label>{{ __('messages.auth.password') }}</flux:label>
            <flux:input wire:model="password" type="password" required />
            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center text-sm text-zinc-600 dark:text-zinc-400 gap-2 cursor-pointer">
                <flux:checkbox wire:model="remember" /> 
                {{ __('messages.auth.remember_me') }}
            </label>
        </div>

        <div class="pt-2">
            <flux:button type="submit" class="w-full !bg-winga-500 hover:!bg-winga-600 !text-white !font-bold">
                {{ __('messages.auth.login_button') }}
            </flux:button>
        </div>
    </form>

    <p class="text-center text-sm text-zinc-600 dark:text-zinc-400 mt-6">
        {{ __('messages.auth.no_account') }} 
        <a href="{{ route('register') }}" class="text-winga-600 hover:text-winga-700 font-bold" wire:navigate>{{ __('messages.auth.register_here') }}</a>
    </p>
</div>
