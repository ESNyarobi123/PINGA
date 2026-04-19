<div>
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.huduma_maombi.title') }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.huduma_maombi.subtitle') }}</p>
            </div>
            <flux:select wire:model.live="filter" class="w-full sm:w-auto">
                <option value="all">{{ __('messages.huduma_maombi.filter_all') }} ({{ $counts['all'] }})</option>
                <option value="pending">{{ __('messages.huduma_maombi.filter_pending') }} ({{ $counts['pending'] }})</option>
                <option value="accepted">{{ __('messages.huduma_maombi.filter_accepted') }} ({{ $counts['accepted'] }})</option>
                <option value="declined">{{ __('messages.huduma_maombi.filter_declined') }} ({{ $counts['declined'] }})</option>
            </flux:select>
        </div>
    </div>

    @if($requests->count() > 0)
        <div class="space-y-4">
            @foreach($requests as $req)
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-5 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold uppercase tracking-wide text-winga-600 dark:text-winga-400 mb-1">{{ $req->service->title }}</p>
                        @if($usesServicePackages && $req->package)
                            <p class="text-sm text-emerald-700 dark:text-emerald-300 font-medium mb-1">{{ __('messages.huduma_maombi.package_label') }}: {{ $req->package->title }}
                                @if($req->package->price)
                                    <span class="text-zinc-500 font-normal">· TZS {{ number_format($req->package->price) }}</span>
                                @endif
                            </p>
                        @endif
                        <p class="text-lg font-bold text-zinc-900 dark:text-white">{{ $req->client->name }}</p>
                        @if($req->service->category)
                            <p class="text-sm text-zinc-500 mt-1">{{ $req->service->category->name }}</p>
                        @endif
                        @if($req->message)
                            <p class="text-sm text-zinc-600 dark:text-zinc-300 mt-3 whitespace-pre-line border-s-2 border-zinc-200 dark:border-zinc-700 ps-3">{{ $req->message }}</p>
                        @endif
                        <p class="text-xs text-zinc-400 mt-2">{{ $req->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                        <flux:badge :color="$req->status === 'pending' ? 'amber' : ($req->status === 'accepted' ? 'green' : 'zinc')">{{ $req->status }}</flux:badge>
                        @if($req->status === 'pending')
                            <flux:button size="sm" variant="primary" wire:click="accept({{ $req->id }})" wire:loading.attr="disabled">{{ __('messages.huduma_maombi.accept') }}</flux:button>
                            <flux:button size="sm" variant="ghost" wire:click="decline({{ $req->id }})" wire:loading.attr="disabled">{{ __('messages.huduma_maombi.decline') }}</flux:button>
                        @endif
                        <flux:button size="sm" :href="route('messages')" variant="outline" wire:navigate>{{ __('messages.huduma_maombi.open_messages') }}</flux:button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $requests->links() }}</div>
    @else
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-12 text-center">
            <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.huduma_maombi.empty') }}</p>
            <flux:button class="mt-4" :href="route('winga.huduma-zangu')" variant="primary" wire:navigate>{{ __('messages.huduma_maombi.cta_services') }}</flux:button>
        </div>
    @endif
</div>
