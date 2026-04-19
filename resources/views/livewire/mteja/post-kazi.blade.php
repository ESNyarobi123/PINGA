<div>
    {{-- Page Header --}}
    <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <div class="relative z-10">
            <h1 class="text-2xl font-bold mb-2 text-black">{{ __('messages.post_job.title') }}</h1>
            <p class="text-zinc-800">{{ __('messages.post_job.subtitle') }}</p>
            <p class="text-sm text-zinc-900/90 mt-3 max-w-2xl">{{ __('messages.post_job.bidding_note') }}</p>
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center gap-3 rounded-xl bg-white/25 dark:bg-black/10 px-4 py-3 border border-white/30">
                <p class="text-sm text-zinc-900 flex-1">{{ __('messages.post_job.browse_services_banner') }}</p>
                <flux:button variant="outline" class="shrink-0 border-zinc-800/20 text-zinc-900" :href="route('mteja.huduma')" wire:navigate>{{ __('messages.post_job.browse_services_cta') }}</flux:button>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form wire:submit="submit">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 space-y-6">
            
            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                    {{ __('messages.post_job.job_title') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="title" placeholder="{{ __('messages.post_job.job_title_placeholder') }}" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:text-white">
                @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                    {{ __('messages.post_job.description') }} <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="description" rows="6" placeholder="{{ __('messages.post_job.description_placeholder') }}" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:text-white"></textarea>
                @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                <p class="text-xs text-zinc-500 mt-1">{{ __('messages.post_job.description_warning') }}</p>
            </div>

            {{-- Category & Location --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        {{ __('messages.post_job.category') }} <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="category_id" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:text-white">
                        <option value="">{{ __('messages.post_job.category_placeholder') }}</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        {{ __('messages.post_job.location') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="location" placeholder="{{ __('messages.post_job.location_placeholder') }}" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:text-white">
                    @error('location') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Budget Type & Range --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">
                    {{ __('messages.post_job.budget_type') }}
                </label>
                <div class="flex gap-4 mb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="budget_type" value="fixed" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-zinc-700 dark:text-zinc-300">{{ __('messages.post_job.budget_fixed') }}</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="budget_type" value="hourly" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-zinc-700 dark:text-zinc-300">{{ __('messages.post_job.budget_hourly') }}</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('messages.post_job.budget_min') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="budget_min" placeholder="50000" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:text-white">
                        @error('budget_min') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                            {{ __('messages.post_job.budget_max') }}
                        </label>
                        <input type="number" wire:model="budget_max" placeholder="100000" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:text-white">
                        <p class="text-xs text-zinc-500 mt-1">{{ __('messages.post_job.budget_max_note') }}</p>
                    </div>
                </div>

                {{-- System Fee Info --}}
                @php $feePercent = \App\Models\Payment::getPlatformFeePercent(); @endphp
                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-xs text-blue-700 dark:text-blue-400">
                            <p class="font-semibold">Ada ya mfumo: {{ $feePercent }}%</p>
                            <p class="mt-0.5">Ada ya {{ $feePercent }}% <strong>inakwatwa kwa Winga</strong> (mfanyakazi) kutoka kwa malipo yake — wewe kama mteja <strong>unalipa dau la mfanyakazi tu</strong>, bila ongezeko lolote.</p>
                            <p class="mt-1 font-medium">Mfano: Mfanyakazi akidai TZS 100,000 → wewe utalipa TZS 100,000. Winga anapokea TZS {{ number_format(100000 - round(100000 * $feePercent / 100)) }}.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Duration & Urgency --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        {{ __('messages.post_job.duration') }}
                    </label>
                    <input type="text" wire:model="duration" placeholder="{{ __('messages.post_job.duration_placeholder') }}" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        {{ __('messages.post_job.urgency') }}
                    </label>
                    <select wire:model="urgency" class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-zinc-800 dark:text-white">
                        <option value="normal">{{ __('messages.post_job.urgency_normal') }}</option>
                        <option value="urgent">{{ __('messages.post_job.urgency_urgent') }}</option>
                        <option value="very_urgent">{{ __('messages.post_job.urgency_very_urgent') }}</option>
                    </select>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center justify-between pt-6 border-t border-zinc-200 dark:border-zinc-800">
                <a href="{{ route('mteja.kazi-zangu') }}" class="px-6 py-3 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" wire:navigate>
                    {{ __('messages.post_job.cancel') }}
                </a>
                <button type="submit" class="px-6 py-3 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('messages.post_job.submit') }}
                </button>
            </div>
        </div>
    </form>

    {{-- Info Card --}}
    <div class="bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 mt-6">
        <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ __('messages.post_job.tips_title') }}
        </h3>
        <ul class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
            <li class="flex items-start gap-2">
                <span class="mt-0.5 shrink-0"><x-fluent-icon name="checkmark-circle-16" :size="16" /></span>
                <span>{{ __('messages.post_job.tip_1') }}</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="mt-0.5 shrink-0"><x-fluent-icon name="checkmark-circle-16" :size="16" /></span>
                <span>{{ __('messages.post_job.tip_2') }}</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="mt-0.5 shrink-0"><x-fluent-icon name="checkmark-circle-16" :size="16" /></span>
                <span>{{ __('messages.post_job.tip_3') }}</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="mt-0.5 shrink-0"><x-fluent-icon name="warning-24" :size="18" /></span>
                <span>{{ __('messages.post_job.tip_4') }}</span>
            </li>
        </ul>
    </div>
</div>
