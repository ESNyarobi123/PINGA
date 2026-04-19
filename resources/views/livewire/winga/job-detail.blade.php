<div class="space-y-8">
    {{-- Breadcrumb & Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs uppercase tracking-[0.4em] text-zinc-400">
                    <a href="{{ route('winga.kazi-karibu') }}" class="text-winga-600 hover:text-winga-500 font-semibold" wire:navigate>{{ __('messages.winga_job_detail.breadcrumb') }}</a>
                    <span>•</span>
                    <span>{{ $job->category->name ?? __('messages.winga_job_detail.general') }}</span>
                </div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mt-2">{{ $job->getLocalizedTitle() }}</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ __('messages.winga_job_detail.posted') }} {{ $job->created_at->diffForHumans() }} • {{ $job->location ?? 'Remote' }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('winga.maombi-yangu') }}" class="px-4 py-2 rounded-xl border border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 text-sm" wire:navigate>
                    {{ __('messages.winga_job_detail.back_to_list') }}
                </a>
                @if($job->status === 'completed')
                    <span class="px-6 py-2 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-semibold text-sm inline-flex items-center gap-2">
                        <x-fluent-icon name="checkmark-circle-24" :size="20" />
                        Kazi Imekamilika
                    </span>
                @elseif($job->status === 'in_progress')
                    <span class="px-6 py-2 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-semibold text-sm inline-flex items-center gap-2">
                        <x-fluent-icon name="arrow-sync-24" :size="20" />
                        Kazi Inaendelea
                    </span>
                @elseif($this->existingApplicationId)
                    <button class="px-6 py-2 rounded-xl bg-green-600 text-white font-semibold shadow cursor-default" type="button" disabled>
                        {{ __('messages.winga_job_detail.already_applied') }}
                    </button>
                @else
                    <button wire:click="openApplyModal" class="px-6 py-2 rounded-xl bg-white border-2 border-winga-600 text-winga-600 hover:bg-winga-600 hover:text-white font-semibold shadow transition-colors" type="button">
                        {{ __('messages.winga_job_detail.apply_now') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Summary stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
            <p class="text-xs text-zinc-500">{{ __('messages.winga_job_detail.budget') }}</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">TZS {{ number_format($job->budget_min ?? 0) }} @if($job->budget_max) - {{ number_format($job->budget_max) }} @endif</p>
            <p class="text-xs text-zinc-400">{{ $job->budget_type === 'hourly' ? __('messages.winga_job_detail.hourly') : __('messages.winga_job_detail.fixed') }}</p>
        </div>
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
            <p class="text-xs text-zinc-500">{{ __('messages.winga_job_detail.applications_received') }}</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ $job->applications_count }}</p>
            <p class="text-xs text-zinc-400">{{ __('messages.winga_job_detail.workers_applied') }}</p>
        </div>
        <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-4">
            <p class="text-xs text-zinc-500">{{ __('messages.winga_job_detail.job_type') }}</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white mt-1">{{ ucfirst($job->urgency ?? __('messages.winga_job_detail.normal')) }}</p>
            <p class="text-xs text-zinc-400">{{ __('messages.winga_job_detail.client_priority') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <article class="lg:col-span-2 space-y-6">
            <section class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">{{ __('messages.winga_job_detail.description') }}</h2>
                <div class="prose dark:prose-invert max-w-none text-sm text-zinc-700 dark:text-zinc-300">
                    {!! nl2br(e($job->getLocalizedDescription())) !!}
                </div>
            </section>

            @if($job->requirements)
            <section class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">{{ __('messages.winga_job_detail.requirements') }}</h2>
                <div class="text-sm text-zinc-700 dark:text-zinc-300 whitespace-pre-line">{{ $job->getLocalizedRequirements() }}</div>
            </section>
            @endif

            @if($job->skills && $job->skills->isNotEmpty())
            <section class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-3">{{ __('messages.winga_job_detail.skills_needed') }}</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($job->skills as $skill)
                        <span class="px-3 py-1 rounded-full bg-winga-50 dark:bg-winga-900/40 text-winga-700 dark:text-winga-200 text-xs font-semibold">{{ $skill->name }}</span>
                    @endforeach
                </div>
            </section>
            @endif
        </article>

        <aside class="space-y-6">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-400">{{ __('messages.winga_job_detail.employer') }}</p>
                <div class="flex items-center gap-3 mt-3">
                    <img src="{{ $job->employer && $job->employer->avatar ? asset('storage/'.$job->employer->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($job->employer->name ?? 'M').'&background=0d9488&color=fff&size=128' }}" class="w-12 h-12 rounded-full object-cover" alt="{{ $job->employer->name ?? __('messages.winga_job_detail.employer') }}" />
                    <div>
                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $job->employer->name ?? __('messages.winga_job_detail.employer') }}</p>
                        <p class="text-xs text-zinc-500">{{ $job->employer->bio ?? __('messages.winga_job_detail.no_bio') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm space-y-4">
                @if($job->status === 'completed')
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800 text-center">
                        <p class="text-green-700 dark:text-green-400 font-semibold text-sm inline-flex items-center justify-center gap-2">
                            <x-fluent-icon name="checkmark-circle-24" :size="20" />
                            Umefanya kazi hii
                        </p>
                        <p class="text-xs text-zinc-500 mt-1">Malipo yametolewa</p>
                    </div>
                    <a href="{{ route('winga.mapato') }}" class="w-full flex items-center justify-center gap-2 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold py-2" wire:navigate>
                        <x-fluent-icon name="coin-multiple-24" :size="22" />
                        Angalia Mapato
                    </a>
                @elseif($job->status === 'in_progress')
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 text-center">
                        <p class="text-blue-700 dark:text-blue-400 font-semibold text-sm inline-flex items-center justify-center gap-2">
                            <x-fluent-icon name="arrow-sync-24" :size="20" />
                            Kazi Inaendelea
                        </p>
                        <p class="text-xs text-zinc-500 mt-1">Kazi hii bado haijafika mwisho</p>
                    </div>
                    <a href="{{ route('winga.weka-code') }}" class="w-full flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2" wire:navigate>
                        <x-fluent-icon name="person-key-24" :size="22" />
                        Weka Code ya Kukamilisha
                    </a>
                @elseif($this->existingApplicationId)
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('messages.winga_job_detail.next_step') }}</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('messages.winga_job_detail.next_step_desc') }}</p>
                    <a href="{{ route('winga.maombi-yangu') }}" class="w-full flex items-center justify-center gap-2 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold py-2" wire:navigate>
                        {{ __('messages.winga_job_detail.view_my_applications') }}
                    </a>
                @else
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('messages.winga_job_detail.next_step') }}</h3>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('messages.winga_job_detail.next_step_desc') }}</p>
                    <button wire:click="openApplyModal" class="w-full flex items-center justify-center gap-2 rounded-xl bg-white border-2 border-winga-600 text-winga-600 hover:bg-winga-600 hover:text-white font-semibold py-2 transition-colors">
                        {{ __('messages.winga_job_detail.apply_now') }}
                    </button>
                @endif
            </div>
        </aside>
    </div>

    {{-- Apply Modal --}}
    <flux:modal wire:model.live="showApplyModal" class="max-w-lg p-0 overflow-hidden bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl border border-white/20 dark:border-zinc-800/50 shadow-2xl rounded-2xl">
        <div class="relative w-full p-6 sm:p-7">
            {{-- Close Button --}}
            <button type="button" wire:click="closeApplyModal" class="absolute top-3 right-3 p-2 rounded-full text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100/80 dark:hover:text-zinc-200 dark:hover:bg-zinc-800/80 transition-all focus:outline-none">
                <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-2 tracking-tight">{{ __('messages.winga_job_detail.modal_title') }}</h2>
            <p class="text-zinc-500 dark:text-zinc-400 text-[13.5px] mb-6">{{ __('messages.winga_job_detail.modal_subtitle') }}</p>

            <form wire:submit="submitApplication" class="space-y-4">
                {{-- Cover Letter --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.winga_job_detail.cover_letter') }} <span class="text-red-500">*</span></label>
                    <textarea wire:model="coverLetter" rows="4" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-winga-500" placeholder="{{ __('messages.winga_job_detail.cover_letter_placeholder') }}"></textarea>
                    @error('coverLetter') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-zinc-400 mt-1">{{ __('messages.winga_job_detail.min_chars') }}</p>
                </div>

                {{-- Proposed Budget --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.winga_job_detail.proposed_budget') }} <span class="text-red-500">*</span></label>
                    <input type="number" wire:model.live="proposedBudget" min="1000" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-winga-500" placeholder="50000">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ __('messages.winga_job_detail.proposed_budget_help') }}</p>
                    @error('proposedBudget') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    {{-- Task 7: Commission deducted from Winga, not added on top for customer --}}
                    @php
                        $feePercent = \App\Models\Payment::getPlatformFeePercent();
                        $bidAmount  = (float) ($proposedBudget ?? 0);
                        $systemFee  = round($bidAmount * ($feePercent / 100), 2);
                        $wingaReceives = $bidAmount - $systemFee;
                    @endphp
                    @if($bidAmount > 0)
                    <div class="mt-2 p-2.5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-zinc-600 dark:text-zinc-400">Mteja atalipa:</span>
                            <span class="font-bold text-zinc-900 dark:text-white">TZS {{ number_format($bidAmount) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-zinc-600 dark:text-zinc-400">Komisioni ya mfumo ({{ $feePercent }}%) — yako:</span>
                            <span class="font-semibold text-red-500 dark:text-red-400">- TZS {{ number_format($systemFee) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs pt-1 border-t border-emerald-200 dark:border-emerald-700">
                            <span class="font-semibold text-zinc-700 dark:text-zinc-300">Utakayopokea:</span>
                            <span class="font-bold text-emerald-700 dark:text-emerald-400">TZS {{ number_format($wingaReceives) }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Proposed Duration --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.winga_job_detail.proposed_duration') }} <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="proposedDuration" class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-winga-500" placeholder="{{ __('messages.winga_job_detail.duration_placeholder') }}">
                    @error('proposedDuration') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit" wire:loading.attr="disabled" class="w-full flex items-center justify-center gap-2 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 px-4 py-3 text-[14px] font-bold shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-zinc-900/50">
                        <span wire:loading.remove>{{ __('messages.winga_job_detail.submit') }}</span>
                        <span wire:loading>{{ __('messages.winga_job_detail.submitting') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
