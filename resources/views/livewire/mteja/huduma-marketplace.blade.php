<div class="max-w-7xl mx-auto px-4 py-6">
    @if(! $usesServicePackages)
        <div class="mb-6 rounded-xl border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 px-4 py-3 text-sm text-amber-900 dark:text-amber-100">
            {{ __('messages.huduma_marketplace.migrations_pending') }}
        </div>
    @endif
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white inline-flex items-center gap-2">
            <x-fluent-icon name="clipboard-24" :size="28" />
            {{ __('messages.huduma_marketplace.title') }}
        </h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 max-w-2xl">{{ __('messages.huduma_marketplace.subtitle') }}</p>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <flux:input wire:model.live.debounce.400ms="search" icon="magnifying-glass" :placeholder="__('messages.huduma_marketplace.search_placeholder')" />
            <flux:select wire:model.live="categoryId" :placeholder="__('messages.huduma_marketplace.all_categories')">
                <option value="">{{ __('messages.huduma_marketplace.all_categories') }}</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </flux:select>
            <div class="flex items-end">
                <flux:button variant="outline" class="w-full md:w-auto" :href="route('mteja.post-kazi')" wire:navigate>
                    {{ __('messages.huduma_marketplace.post_job_instead') }}
                </flux:button>
            </div>
        </div>
    </div>

    @if($services->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($services as $service)
                @php
                    $u = $service->user;
                    $avatarUrl = $u?->avatar ? asset('storage/'.$u->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($u?->name ?? 'W').'&background=0d9488&color=fff&size=128';
                    $loc = trim(implode(', ', array_filter([$u?->mtaa, $u?->wilaya, $u?->mkoa])));
                @endphp
                <article class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-5 flex flex-col gap-4">
                    <div class="flex gap-3 min-w-0">
                        <img src="{{ $avatarUrl }}" alt="" class="size-12 rounded-full object-cover shrink-0 border border-zinc-200 dark:border-zinc-700">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400 truncate">{{ $u?->name }}</p>
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white leading-snug">{{ $service->title }}</h2>
                            @if($service->category)
                                <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $service->category->name }}</p>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-3">{{ $service->description }}</p>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-2">{{ __('messages.huduma_marketplace.packages') }}</p>
                        @if($usesServicePackages && $service->packages->isNotEmpty())
                            <ul class="space-y-1.5 text-sm">
                                @foreach($service->packages->take(4) as $pkg)
                                    <li class="flex justify-between gap-2 text-zinc-700 dark:text-zinc-300">
                                        <span class="truncate">{{ $pkg->title }}</span>
                                        @if($pkg->price)
                                            <span class="shrink-0 font-medium text-emerald-600 dark:text-emerald-400">TZS {{ number_format($pkg->price) }}</span>
                                        @else
                                            <span class="shrink-0 text-zinc-500 text-xs">{{ __('messages.huduma_zangu.negotiable') }}</span>
                                        @endif
                                    </li>
                                @endforeach
                                @if($service->packages->count() > 4)
                                    <li class="text-xs text-zinc-400">+{{ $service->packages->count() - 4 }} {{ __('messages.huduma_marketplace.more_packages') }}</li>
                                @endif
                            </ul>
                        @elseif($service->price)
                            <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">TZS {{ number_format($service->price) }} <span class="text-zinc-500 font-normal">({{ $service->price_type }})</span></p>
                        @else
                            <p class="text-sm text-zinc-500">{{ __('messages.huduma_zangu.negotiable') }}</p>
                        @endif
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-zinc-100 dark:border-zinc-800 mt-auto">
                        @if($loc)
                            <span class="text-xs text-zinc-500 truncate max-w-[55%]">{{ $loc }}</span>
                        @else
                            <span></span>
                        @endif
                        <div class="flex gap-2 shrink-0">
                            <flux:button size="sm" variant="ghost" :href="route('mteja.winga-profile', ['id' => $u->id])" wire:navigate>{{ __('messages.huduma_marketplace.view_profile') }}</flux:button>
                            @if($usesServicePackages && $service->packages->isNotEmpty())
                                <flux:button size="sm" variant="primary" :href="route('mteja.winga-profile', ['id' => $u->id]).'?service='.$service->id" wire:navigate>{{ __('messages.huduma_marketplace.choose_package') }}</flux:button>
                            @else
                                <flux:button size="sm" variant="primary" :href="route('mteja.winga-profile', ['id' => $u->id]).'?service='.$service->id" wire:navigate>{{ __('messages.huduma_marketplace.open_service') }}</flux:button>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-8">{{ $services->links() }}</div>
    @else
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-12 text-center">
            <p class="text-zinc-600 dark:text-zinc-400 mb-4">{{ __('messages.huduma_marketplace.empty') }}</p>
            <div class="flex flex-wrap justify-center gap-2">
                <flux:button variant="outline" wire:click="$set('search', ''); $set('categoryId', '')">{{ __('messages.huduma_marketplace.clear_filters') }}</flux:button>
                <flux:button variant="primary" :href="route('mteja.post-kazi')" wire:navigate>{{ __('messages.huduma_marketplace.post_job_instead') }}</flux:button>
            </div>
        </div>
    @endif
</div>
