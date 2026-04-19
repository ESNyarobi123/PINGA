@php
    $w = $this->winga;
    $avatarUrl = $w->avatar ? asset('storage/'.$w->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($w->name).'&background=0d9488&color=fff&size=256';
    $location = trim(implode(', ', array_filter([$w->mtaa, $w->wilaya, $w->mkoa])));
    $rating = round((float) ($w->reviews_received_avg_rating ?? 0), 1);
    $reviewCount = $w->reviews_received_count ?? 0;
    $bio = $w->bio ?? 'Mfanyakazi huyu hajaweka maelezo ya kina kuhusu wasifu wake.';
@endphp

<div class="max-w-6xl mx-auto px-4 py-6">
    {{-- Compact Header --}}
    <div class="mb-6">
        <a href="{{ route('mteja.mawinga') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 transition-colors mb-4" wire:navigate>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Rudi Mawinga
        </a>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white inline-flex items-center gap-2">
            <x-fluent-icon name="person-24" :size="28" />
            Wasifu wa Mfanyakazi
        </h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Profile Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden sticky top-6">
                {{-- Header Gradient --}}
                <div class="h-24 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20"></div>
                
                {{-- Avatar --}}
                <div class="px-6 -mt-12">
                    <img src="{{ $avatarUrl }}" class="w-24 h-24 rounded-full border-4 border-white dark:border-zinc-900 object-cover shadow-lg" alt="{{ $w->name }}">
                </div>

                <div class="px-6 pb-6">
                    {{-- Name & Badges --}}
                    <div class="mt-3 mb-4">
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">{{ $w->name }}</h2>
                        
                        {{-- Badges --}}
                        <div class="flex flex-wrap gap-2 mb-3">
                            @if(!empty($highlights['plan']))
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $highlights['plan']['class'] }}">
                                {{ $highlights['plan']['name'] }}
                            </span>
                            @endif
                            @if(!empty($highlights['verified']))
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $highlights['verified']['class'] }}">
                                <x-fluent-icon name="checkmark-circle-16" :size="12" class="shrink-0" />
                                {{ $highlights['verified']['label'] }}
                            </span>
                            @endif
                            @if(!empty($highlights['top_rated']))
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $highlights['top_rated']['class'] }}">
                                {{ $highlights['top_rated']['label'] }}
                            </span>
                            @endif
                        </div>

                        {{-- Rating --}}
                        @if($rating > 0)
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5 text-amber-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-lg font-bold text-zinc-900 dark:text-white">{{ $rating }}</span>
                                <span class="text-sm text-zinc-500 dark:text-zinc-400">({{ $reviewCount }} reviews)</span>
                            </div>
                        </div>
                        @endif

                        {{-- Location --}}
                        @if($location)
                        <div class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400 mb-3">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $location }}
                        </div>
                        @endif

                        {{-- Price --}}
                        @if($w->bei_wastani && $w->bei_wastani > 0)
                        <div class="flex items-center gap-2 text-sm mb-4">
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 text-lg">TZS {{ number_format($w->bei_wastani) }}</span>
                            <span class="text-zinc-500 dark:text-zinc-400">/ {{ $w->bei_aina ?? 'siku' }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="space-y-2">
                        <a href="{{ route('messages') }}" class="w-full px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-semibold rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Tuma Ujumbe
                        </a>
                        <button type="button" class="w-full px-4 py-2.5 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white text-sm font-semibold rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- About --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 inline-flex items-center gap-2">
                    <x-fluent-icon name="document-text-24" :size="22" />
                    Kuhusu
                </h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $bio }}</p>
            </div>

            {{-- Skills --}}
            @if($w->skills && $w->skills->count() > 0)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 inline-flex items-center gap-2">
                    <x-fluent-icon name="molecule-24" :size="22" />
                    Ujuzi
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($w->skills as $skill)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 text-sm font-medium">
                        {{ $skill->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Services (Winga offerings — mteja can request) --}}
            @if($w->services && $w->services->count() > 0)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-1 inline-flex items-center gap-2">
                    <x-fluent-icon name="clipboard-24" :size="22" />
                    {{ __('messages.huduma_request.section_title') }}
                </h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">{{ __('messages.huduma_request.section_subtitle') }}</p>
                <div class="space-y-3">
                    @foreach($w->services as $service)
                    <div id="huduma-service-{{ $service->id }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 scroll-mt-24">
                        <div class="min-w-0">
                            <p class="font-semibold text-zinc-900 dark:text-white">{{ $service->title }}</p>
                            @if($service->category)
                                <p class="text-xs text-zinc-500 mt-0.5">{{ $service->category->name }}</p>
                            @endif
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2 mt-1">{{ $service->description }}</p>
                            @if($usesServicePackages && $service->packages->isNotEmpty())
                                <ul class="mt-2 space-y-1 text-xs text-zinc-600 dark:text-zinc-400">
                                    @foreach($service->packages as $pkg)
                                        <li class="flex flex-wrap gap-x-2">
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $pkg->title }}</span>
                                            @if($pkg->price)
                                                <span class="text-emerald-600 dark:text-emerald-400">TZS {{ number_format($pkg->price) }}</span>
                                            @else
                                                <span>{{ __('messages.huduma_zangu.negotiable') }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @elseif($service->price)
                                <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400 mt-2">
                                    TZS {{ number_format($service->price) }}
                                    <span class="text-zinc-500 font-normal">({{ $service->price_type }})</span>
                                </p>
                            @else
                                <p class="text-sm text-zinc-500 mt-2">{{ __('messages.huduma_zangu.negotiable') }}</p>
                            @endif
                        </div>
                        <div class="shrink-0">
                            @auth
                                @if(auth()->user()->isMteja())
                                    @if($usesServicePackages && $service->packages->isNotEmpty())
                                        <flux:button size="sm" variant="primary" wire:click="openRequestModal({{ $service->id }})">{{ __('messages.huduma_request.cta') }}</flux:button>
                                    @elseif(! $usesServicePackages)
                                        <flux:button size="sm" variant="primary" wire:click="openRequestModal({{ $service->id }})">{{ __('messages.huduma_request.cta') }}</flux:button>
                                    @else
                                        <span class="text-xs text-zinc-400">{{ __('messages.huduma_request.no_packages') }}</span>
                                    @endif
                                @else
                                    <span class="text-xs text-zinc-400">{{ __('messages.huduma_request.clients_only') }}</span>
                                @endif
                            @else
                                <flux:button size="sm" variant="outline" href="{{ route('login') }}" wire:navigate>{{ __('messages.huduma_request.login_to_request') }}</flux:button>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Portfolio --}}
            @if($w->portfolios && $w->portfolios->count() > 0)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4 inline-flex items-center gap-2">
                    <x-fluent-icon name="briefcase-24" :size="22" />
                    Portfolio
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($w->portfolios as $item)
                    <div class="group rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors">
                        @if($item->image_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->image_path) }}" class="w-full h-40 object-cover" alt="{{ $item->title }}">
                        @endif
                        <div class="p-3">
                            <h4 class="font-semibold text-sm text-zinc-900 dark:text-white mb-1">{{ $item->title }}</h4>
                            @if($item->description)
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 line-clamp-2">{{ $item->description }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Reviews --}}
            @if($w->reviewsReceived && $w->reviewsReceived->count() > 0)
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-4 inline-flex items-center gap-2">
                    <x-fluent-icon name="star-24" :size="22" />
                    Maoni ({{ $reviewCount }})
                </h3>
                <div class="space-y-4">
                    @foreach($w->reviewsReceived->take(5) as $review)
                    <div class="border-b border-zinc-100 dark:border-zinc-800 pb-4 last:border-0 last:pb-0">
                        <div class="flex items-start gap-3">
                            <img src="{{ $review->reviewer->avatar ? asset('storage/'.$review->reviewer->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($review->reviewer->name).'&background=random&color=fff' }}" class="w-10 h-10 rounded-full object-cover" alt="{{ $review->reviewer->name }}">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-semibold text-sm text-zinc-900 dark:text-white">{{ $review->reviewer->name }}</h4>
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-1 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400 fill-current' : 'text-zinc-300 dark:text-zinc-600' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>
                                @if($review->comment)
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $review->comment }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    @if($showRequestModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click="closeRequestModal">
            <div wire:click.stop class="w-full max-w-md rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-xl p-6 space-y-4">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('messages.huduma_request.modal_title') }}</h3>
                @php $reqService = $w->services->firstWhere('id', $requestServiceId); @endphp
                @if($usesServicePackages && $reqService && $reqService->packages->isNotEmpty())
                    <div>
                        <flux:label>{{ __('messages.huduma_request.choose_package') }}</flux:label>
                        <div class="mt-2 space-y-2 max-h-48 overflow-y-auto pe-1">
                            @foreach($reqService->packages as $pkg)
                                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-zinc-200 dark:border-zinc-700 p-3 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50 dark:has-[:checked]:bg-emerald-900/20">
                                    <input type="radio" wire:model.live="requestPackageId" value="{{ $pkg->id }}" class="mt-1 text-emerald-600">
                                    <span class="min-w-0 flex-1">
                                        <span class="font-medium text-zinc-900 dark:text-white">{{ $pkg->title }}</span>
                                        @if($pkg->description)
                                            <span class="block text-xs text-zinc-500 mt-0.5">{{ \Illuminate\Support\Str::limit($pkg->description, 120) }}</span>
                                        @endif
                                        @if($pkg->price)
                                            <span class="block text-sm text-emerald-600 dark:text-emerald-400 mt-1">TZS {{ number_format($pkg->price) }}</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('requestPackageId')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @endif
                <flux:textarea wire:model="requestMessage" rows="4" :label="__('messages.huduma_request.message_label')" :placeholder="__('messages.huduma_request.message_placeholder')" />
                @error('requestMessage')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="flex gap-2 justify-end">
                    <flux:button variant="ghost" wire:click="closeRequestModal">{{ __('messages.huduma_request.cancel') }}</flux:button>
                    <flux:button variant="primary" wire:click="submitServiceRequest">{{ __('messages.huduma_request.submit') }}</flux:button>
                </div>
            </div>
        </div>
    @endif
</div>
