<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">📦 {{ __('messages.admin_sub_plans.title') }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.admin_sub_plans.subtitle') }}</p>
        </div>
        <button wire:click="create" class="px-4 py-2 bg-winga-600 hover:bg-winga-700 text-white rounded-lg font-medium transition">
            + {{ __('messages.admin_sub_plans.new_plan') }}
        </button>
    </div>

    {{-- Plans Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($plans as $plan)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border {{ $plan->is_active ? 'border-zinc-200 dark:border-zinc-700' : 'border-red-200 dark:border-red-800 bg-red-50/30 dark:bg-red-900/10' }} p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ $plan->name }}</h3>
                    <p class="text-xs text-zinc-500">{{ $plan->slug }}</p>
                </div>
                <div class="flex gap-2">
                    @if($plan->is_recommended)
                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-xs font-bold rounded">⭐</span>
                    @endif
                    @if(!$plan->is_active)
                        <span class="px-2 py-1 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 text-xs font-bold rounded">{{ __('messages.admin_sub_plans.disabled') }}</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <p class="text-3xl font-black text-winga-600 dark:text-winga-400">TZS {{ number_format($plan->price) }}</p>
                <p class="text-sm text-zinc-500">{{ __('messages.admin_sub_plans.per') }} {{ $plan->durationLabel() }}</p>
            </div>

            {{-- Features Preview --}}
            <div class="mb-4">
                <p class="text-xs font-bold text-zinc-400 uppercase mb-2">{{ __('messages.admin_sub_plans.features') }} ({{ count($plan->features ?? []) }})</p>
                <ul class="space-y-1">
                    @foreach(array_slice($plan->features ?? [], 0, 3) as $feature)
                        <li class="text-sm text-zinc-600 dark:text-zinc-400 flex items-center gap-2">
                            <span class="text-green-500">✓</span> {{ $feature }}
                        </li>
                    @endforeach
                    @if(count($plan->features ?? []) > 3)
                        <li class="text-xs text-zinc-400">+{{ count($plan->features) - 3 }} {{ __('messages.admin_sub_plans.more') }}...</li>
                    @endif
                </ul>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <span class="text-xs text-zinc-400">Order: {{ $plan->sort_order }}</span>
                <div class="flex gap-2">
                    <button wire:click="toggleActive({{ $plan->id }})" 
                            class="px-2 py-1 text-xs {{ $plan->is_active ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }} rounded transition">
                        {{ $plan->is_active ? __('messages.admin_sub_plans.deactivate') : __('messages.admin_sub_plans.activate') }}
                    </button>
                    <button wire:click="edit({{ $plan->id }})" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded transition">
                        {{ __('messages.admin_sub_plans.edit') }}
                    </button>
                    <button wire:click="delete({{ $plan->id }})" 
                            wire:confirm="{{ __('messages.admin_sub_plans.confirm_delete') }}"
                            class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded transition">
                        {{ __('messages.admin_sub_plans.delete') }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $plans->links() }}
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" wire:transition.fade>
        <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white dark:bg-zinc-900 rounded-2xl shadow-xl">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                        {{ $isEditing ? '✏️ ' . __('messages.admin_sub_plans.edit_plan') : '📦 ' . __('messages.admin_sub_plans.new_plan') }}
                    </h2>
                    <button wire:click="closeModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-4">
                {{-- Basic Info --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_sub_plans.slug') }}</label>
                        <input type="text" wire:model="slug" 
                               class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800"
                               placeholder="msingi, kawaida, bora">
                        @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_sub_plans.name_sw') }}</label>
                        <input type="text" wire:model="name" 
                               class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800"
                               placeholder="Msingi">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_sub_plans.name_en') }}</label>
                        <input type="text" wire:model="name_en" 
                               class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800"
                               placeholder="Basic">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Badge Label</label>
                        <input type="text" wire:model="badge_label" 
                               class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800"
                               placeholder="Mwanzo">
                    </div>
                </div>

                {{-- Pricing & Duration --}}
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_sub_plans.price') }}</label>
                        <input type="number" wire:model="price" 
                               class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800"
                               placeholder="15000">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_sub_plans.duration_days') }}</label>
                        <input type="number" wire:model="duration_days" 
                               class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800"
                               placeholder="30">
                        @error('duration_days') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_sub_plans.order_priority') }}</label>
                        <input type="number" wire:model="sort_order" 
                               class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800"
                               placeholder="0">
                    </div>
                </div>

                {{-- Color & Options --}}
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_sub_plans.badge_color') }}</label>
                        <select wire:model="badge_color" 
                                class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800">
                            <option value="amber">Amber (Njano)</option>
                            <option value="blue">Blue (Bluu)</option>
                            <option value="winga">Winga (Kijani)</option>
                            <option value="green">Green (Kijani)</option>
                            <option value="red">Red (Nyekundu)</option>
                            <option value="purple">Purple (Zambarau)</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 pt-6">
                        <input type="checkbox" wire:model="is_recommended" id="is_recommended"
                               class="w-4 h-4 text-winga-600 border-zinc-300 rounded">
                        <label for="is_recommended" class="text-sm text-zinc-700 dark:text-zinc-300">⭐ {{ __('messages.admin_sub_plans.recommended') }}</label>
                    </div>
                    <div class="flex items-center gap-2 pt-6">
                        <input type="checkbox" wire:model="is_active" id="is_active"
                               class="w-4 h-4 text-winga-600 border-zinc-300 rounded">
                        <label for="is_active" class="text-sm text-zinc-700 dark:text-zinc-300">✓ Active</label>
                    </div>
                </div>

                {{-- Enforced limits (drives post-huduma, portfolio, bids, etc.) --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 space-y-3 bg-zinc-50 dark:bg-zinc-800/40">
                    <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ __('messages.admin_sub_plans.limits_section') }}</p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_sub_plans.limits_hint') }}</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">{{ __('messages.admin_sub_plans.max_services') }}</label>
                            <input type="number" wire:model="limit_max_services" min="0"
                                   class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-sm">
                            @error('limit_max_services') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">{{ __('messages.admin_sub_plans.daily_bids') }}</label>
                            <input type="number" wire:model="limit_daily_bids" min="0"
                                   class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-sm">
                            @error('limit_daily_bids') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">{{ __('messages.admin_sub_plans.portfolio_imgs') }}</label>
                            <input type="number" wire:model="limit_portfolio_imgs" min="0"
                                   class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-sm">
                            @error('limit_portfolio_imgs') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">{{ __('messages.admin_sub_plans.search_boost') }}</label>
                            <input type="number" wire:model="limit_search_boost" min="0"
                                   class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-sm">
                            @error('limit_search_boost') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">{{ __('messages.admin_sub_plans.limit_analytics') }}</label>
                            <select wire:model="limit_analytics" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-sm">
                                <option value="none">none</option>
                                <option value="basic">basic</option>
                                <option value="advanced">advanced</option>
                                <option value="full">full</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">{{ __('messages.admin_sub_plans.limit_smart_match') }}</label>
                            <select wire:model="limit_smart_match" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-sm">
                                <option value="none">none</option>
                                <option value="normal">normal</option>
                                <option value="high">high</option>
                                <option value="highest">highest</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-x-4 gap-y-2 text-sm text-zinc-700 dark:text-zinc-300">
                        <label class="inline-flex items-center gap-2"><input type="checkbox" wire:model="limit_custom_url" class="rounded"> custom URL</label>
                        <label class="inline-flex items-center gap-2"><input type="checkbox" wire:model="limit_verified_badge" class="rounded"> verified</label>
                        <label class="inline-flex items-center gap-2"><input type="checkbox" wire:model="limit_chat_badge" class="rounded"> chat badge</label>
                        <label class="inline-flex items-center gap-2"><input type="checkbox" wire:model="limit_top_rated_eligible" class="rounded"> top rated</label>
                        <label class="inline-flex items-center gap-2"><input type="checkbox" wire:model="limit_featured_category" class="rounded"> featured</label>
                        <label class="inline-flex items-center gap-2"><input type="checkbox" wire:model="limit_priority_support" class="rounded"> priority support</label>
                    </div>
                </div>

                {{-- Features --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_sub_plans.features') }}</label>
                        <button type="button" wire:click="addFeature" class="text-xs text-winga-600 hover:text-winga-700 font-medium">
                            + {{ __('messages.admin_sub_plans.add_feature') }}
                        </button>
                    </div>
                    <div class="space-y-2">
                        @foreach($features as $index => $feature)
                        <div class="flex gap-2">
                            <input type="text" wire:model="features.{{ $index }}" 
                                   class="flex-1 px-3 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-sm"
                                   placeholder="{{ __('messages.admin_sub_plans.feature_placeholder') }}">
                            <button type="button" wire:click="removeFeature({{ $index }})" 
                                    class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-zinc-200 dark:border-zinc-700 flex justify-end gap-3">
                <button wire:click="closeModal" class="px-4 py-2 border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 rounded-lg font-medium transition">
                    {{ __('messages.admin_sub_plans.cancel') }}
                </button>
                <button wire:click="save" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-winga-600 hover:bg-winga-700 text-white rounded-lg font-medium transition flex items-center gap-2">
                    <span wire:loading.remove>{{ $isEditing ? __('messages.admin_sub_plans.update') : __('messages.admin_sub_plans.create_plan') }}</span>
                    <span wire:loading>{{ __('messages.admin_sub_plans.processing') }}</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
