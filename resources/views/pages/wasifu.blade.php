<x-layouts::public>
    <div class="bg-white dark:bg-zinc-950 py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h2 class="text-4xl font-semibold tracking-tight text-pretty text-zinc-900 dark:text-white sm:text-5xl">{{ __('messages.profile.hero_title') }}</h2>
                <p class="mt-6 text-lg/8 text-zinc-600 dark:text-zinc-400">{{ __('messages.profile.hero_subtitle') }}</p>
            </div>
            
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base/7 font-semibold text-zinc-900 dark:text-white">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-winga-500">
                                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            </span>
                            {{ __('messages.profile.complete_skills') }}
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base/7 text-zinc-600 dark:text-zinc-400">
                            <p class="flex-auto">{{ __('messages.profile.hero_subtitle') }}</p>
                            <p class="mt-6">
                                <a href="{{ route('register') }}" class="text-sm/6 font-semibold text-winga-600">{{ __('messages.profile.cta_text') }} <span aria-hidden="true">→</span></a>
                            </p>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base/7 font-semibold text-zinc-900 dark:text-white">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-winga-500">
                                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                            </span>
                            {{ __('messages.profile.build_portfolio') }}
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base/7 text-zinc-600 dark:text-zinc-400">
                            <p class="flex-auto">{{ __('messages.profile.hero_subtitle') }}</p>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="flex items-center gap-x-3 text-base/7 font-semibold text-zinc-900 dark:text-white">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-winga-500">
                                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" /></svg>
                            </span>
                            {{ __('messages.profile.get_reviews') }}
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base/7 text-zinc-600 dark:text-zinc-400">
                            <p class="flex-auto">Tanzania ya sasa inaenda kwa vitendo. Picha au viungo vya kazi zilizopita huongeza ushawishi kwa zaidi ya 70%. Mpe mwajiri sababu ya kukuajiri kwa kumuonyesha ulichowahi kufanya.</p>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layouts::public>