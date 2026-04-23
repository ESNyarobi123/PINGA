@php
    /** @var array{email?: string, role?: string, role_label?: string, reason?: string|null}|null $appeal */
    $appeal = session('suspension_appeal');
    $appealEmail = \App\Services\SuspensionAppealContact::appealEmail();
    $whatsappUrl = \App\Services\SuspensionAppealContact::whatsappUrl(
        is_array($appeal) ? $appeal : null
    );
    $mailSubject = rawurlencode(__('messages.account_suspended.page_title'));
    $mailBody = rawurlencode(
        is_array($appeal)
            ? \App\Services\SuspensionAppealContact::whatsappPrefillMessage($appeal)
            : __('messages.account_suspended.no_reason')
    );
@endphp

<x-layouts::auth :title="__('messages.account_suspended.page_title')">
    <div class="flex flex-col gap-6 rounded-xl border border-red-200 bg-red-50/80 p-6 dark:border-red-900/60 dark:bg-red-950/40">
        <div class="flex flex-col gap-2 text-center sm:text-left">
            <flux:badge color="red" size="sm" class="w-fit mx-auto sm:mx-0">{{ __('messages.account_suspended.page_title') }}</flux:badge>
            <h1 class="text-xl font-bold text-zinc-900 dark:text-white">
                {{ __('messages.account_suspended.heading') }}
            </h1>
            <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">
                {{ __('messages.account_suspended.intro') }}
            </p>
            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                {{ __('messages.account_suspended.banned_note') }}
            </p>
        </div>

        @if(is_array($appeal))
            <div class="rounded-lg border border-zinc-200 bg-white/90 p-4 text-sm dark:border-zinc-700 dark:bg-zinc-900/80">
                <p class="font-semibold text-zinc-900 dark:text-white">{{ __('messages.account_suspended.reason_label') }}</p>
                <p class="mt-1 text-zinc-600 dark:text-zinc-400">
                    {{ ! empty($appeal['reason']) ? $appeal['reason'] : __('messages.account_suspended.no_reason') }}
                </p>
            </div>
        @endif

        <div class="rounded-lg border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="text-sm font-bold text-zinc-900 dark:text-white">{{ __('messages.account_suspended.contact_heading') }}</h2>
            <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-400">{{ __('messages.account_suspended.contact_intro') }}</p>

            <div class="mt-4 flex flex-col gap-3">
                <a
                    href="mailto:{{ $appealEmail }}?subject={{ $mailSubject }}&body={{ $mailBody }}"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100"
                >
                    {{ __('messages.account_suspended.email_cta') }} — {{ $appealEmail }}
                </a>

                @if($whatsappUrl)
                    <a
                        href="{{ $whatsappUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700"
                    >
                        {{ __('messages.account_suspended.whatsapp_cta') }}
                    </a>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        {{ __('messages.account_suspended.whatsapp_prefill_note') }}
                    </p>
                @else
                    <p class="text-xs text-amber-700 dark:text-amber-400">
                        {{ __('messages.admin_settings.suspension_appeal_hint') }}
                    </p>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:justify-center">
            <a
                href="{{ route('home') }}"
                wire:navigate
                class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800"
            >
                {{ __('messages.account_suspended.back_home') }}
            </a>
            <a
                href="{{ route('login') }}"
                wire:navigate
                class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-winga-700 hover:bg-winga-50 dark:text-winga-400 dark:hover:bg-winga-950/40"
            >
                {{ __('messages.account_suspended.login_again') }}
            </a>
        </div>
    </div>
</x-layouts::auth>
