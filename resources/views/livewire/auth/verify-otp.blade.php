<div class="max-w-md w-full mx-auto p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-lg mt-10">
    <div class="text-center mb-6">
        <div class="text-4xl mb-4">🛡️</div>
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.otp.title') }}</h2>
        <p class="text-zinc-600 dark:text-zinc-400 mt-2 text-sm">
            {{ __('messages.otp.desc') }}
        </p>
    </div>

    @if($successMessage)
        <div class="mb-4 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-sm border border-green-200 dark:border-green-800 text-center font-medium">
            ✅ {{ $successMessage }}
        </div>
    @endif

    <form wire:submit="verify" class="space-y-6">
        <div>
            <flux:label>{{ __('messages.otp.label') }}</flux:label>
            <flux:input 
                wire:model="otp" 
                maxlength="6"
                placeholder="0 0 0 0 0 0"
                class="!text-center !text-3xl !tracking-[0.5em] !font-mono !py-3"
            />
            @error('otp') <span class="text-red-500 text-xs text-center block mt-1">{{ $message }}</span> @enderror
            @if($errorMessage) <span class="text-red-500 text-xs text-center block mt-1 font-bold">{{ $errorMessage }}</span> @endif
        </div>

        <div class="pt-2">
            <flux:button type="submit" class="w-full !bg-winga-500 hover:!bg-winga-600 !text-white !font-bold py-3 text-lg rounded-xl">
                {{ __('messages.otp.submit') }}
            </flux:button>
        </div>
    </form>

    <div class="text-center mt-6">
        <p class="text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('messages.otp.no_code') }}
            <button wire:click="resend" type="button" class="text-winga-600 dark:text-winga-400 hover:text-winga-700 font-bold underline transition-colors">
                {{ __('messages.otp.resend') }}
            </button>
        </p>
    </div>
</div>
