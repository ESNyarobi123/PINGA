<div>
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.huduma_zangu.title') }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.huduma_zangu.subtitle') }}</p>
        </div>
        <flux:button :href="route('winga.post-huduma')" variant="primary" wire:navigate>{{ __('messages.huduma_zangu.add_service') }}</flux:button>
    </div>

    @if($services->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($services as $service)
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $service->title }}</h2>
                        <flux:badge size="sm" :color="$service->status === 'active' ? 'green' : 'zinc'">{{ $service->status }}</flux:badge>
                    </div>
                    @if($service->category)
                        <p class="text-sm text-zinc-500 mb-2">{{ $service->category->name }}</p>
                    @endif
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-3 mb-3">{{ $service->description }}</p>
                    @if($usesServicePackages && $service->packages->isNotEmpty())
                        <ul class="text-xs text-zinc-600 dark:text-zinc-400 space-y-1 mb-3">
                            @foreach($service->packages as $pkg)
                                <li class="flex flex-wrap gap-x-2">
                                    <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $pkg->title }}</span>
                                    @if($pkg->price)
                                        <span class="text-emerald-600 dark:text-emerald-400">TZS {{ number_format($pkg->price) }}</span>
                                    @else
                                        <span class="text-zinc-500">{{ __('messages.huduma_zangu.negotiable') }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="flex flex-wrap items-center justify-between gap-2 text-sm">
                        <span class="font-semibold text-zinc-900 dark:text-white">
                            @if($service->price)
                                {{ __('messages.huduma_zangu.from_price') }} TZS {{ number_format($service->price) }}
                                <span class="text-zinc-500 font-normal">({{ $service->price_type }})</span>
                            @else
                                <span class="text-zinc-500">{{ __('messages.huduma_zangu.negotiable') }}</span>
                            @endif
                        </span>
                        @if($service->pending_requests_count > 0)
                            <flux:badge color="amber">{{ __('messages.huduma_zangu.pending_requests', ['count' => $service->pending_requests_count]) }}</flux:badge>
                        @endif
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <flux:button size="sm" variant="primary" :href="route('winga.edit-huduma', $service)" wire:navigate>{{ __('messages.huduma_zangu.edit') }}</flux:button>
                        <flux:button size="sm" variant="outline" :href="route('winga.huduma-maombi', ['filter' => 'pending'])" wire:navigate>{{ __('messages.huduma_zangu.view_requests') }}</flux:button>
                        <flux:button size="sm" variant="danger" wire:click="deleteService({{ $service->id }})" wire:confirm="{{ __('messages.huduma_zangu.confirm_delete') }}">{{ __('messages.huduma_zangu.delete') }}</flux:button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $services->links() }}</div>
    @else
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-12 text-center">
            <p class="text-zinc-600 dark:text-zinc-400 mb-4">{{ __('messages.huduma_zangu.empty') }}</p>
            <flux:button :href="route('winga.post-huduma')" variant="primary" wire:navigate>{{ __('messages.huduma_zangu.add_first') }}</flux:button>
        </div>
    @endif
</div>
