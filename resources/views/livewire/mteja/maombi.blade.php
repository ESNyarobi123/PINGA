<div>
    {{-- Compact Header with Filters --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white inline-flex items-center gap-2">
                    <x-fluent-icon name="document-text-24" :size="28" />
                    {{ $selectedJob ? __('messages.applications.title_for_job') . ' ' . $selectedJob->title : __('messages.applications.title_all') }}
                </h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $selectedJob ? __('messages.applications.subtitle_for_job') : __('messages.applications.subtitle_all') }}</p>
            </div>
            @if($selectedJob)
            <a href="{{ route('mteja.kazi-zangu') }}" class="group px-4 py-2 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-medium rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 hover:scale-105 transition-all duration-200" wire:navigate>
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('messages.applications.back') }}
                </span>
            </a>
            @endif
        </div>

        {{-- Success Message --}}
        @if(session('success_message'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl mb-4 flex items-center gap-3 animate-in fade-in slide-in-from-top-2 duration-300">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm">{{ session('success_message') }}</span>
        </div>
        @endif

        {{-- Modern Filter Pills --}}
        <div class="flex flex-wrap gap-2">
            <button wire:click="$set('filter', 'all')" class="group relative px-4 py-2 rounded-full font-medium transition-all duration-200 {{ $filter === 'all' ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg scale-105' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 hover:scale-105 border border-zinc-200 dark:border-zinc-700' }}">
                <span class="relative z-10">{{ __('messages.applications.filter_all') }} ({{ $counts['all'] ?? 0 }})</span>
                @if($filter === 'all')
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-full blur opacity-50 group-hover:opacity-75 transition-opacity"></div>
                @endif
            </button>
            <button wire:click="$set('filter', 'pending')" class="group relative px-4 py-2 rounded-full font-medium transition-all duration-200 {{ $filter === 'pending' ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg scale-105' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 hover:scale-105 border border-zinc-200 dark:border-zinc-700' }}">
                <span class="relative z-10">{{ __('messages.applications.filter_pending') }} ({{ $counts['pending'] ?? 0 }})</span>
                @if($filter === 'pending')
                <div class="absolute inset-0 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full blur opacity-50 group-hover:opacity-75 transition-opacity"></div>
                @endif
            </button>
            <button wire:click="$set('filter', 'accepted')" class="group relative px-4 py-2 rounded-full font-medium transition-all duration-200 {{ $filter === 'accepted' ? 'bg-gradient-to-r from-emerald-500 to-green-500 text-white shadow-lg scale-105' : 'bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 hover:scale-105 border border-zinc-200 dark:border-zinc-700' }}">
                <span class="relative z-10">{{ __('messages.applications.filter_accepted') }} ({{ $counts['accepted'] ?? 0 }})</span>
                @if($filter === 'accepted')
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-green-500 rounded-full blur opacity-50 group-hover:opacity-75 transition-opacity"></div>
                @endif
            </button>
        </div>
    </div>

    {{-- Applications List - Compact Modern Cards --}}
    <div class="space-y-3">
        @forelse($applications as $application)
        <div class="group bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="p-4">
                <div class="flex items-start gap-3">
                    {{-- Worker Avatar --}}
                    <div class="flex-shrink-0">
                        <img src="{{ $application->worker->avatar ? asset('storage/' . $application->worker->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($application->worker->name) . '&background=8b5cf6&color=fff' }}" 
                             alt="{{ $application->worker->name }}" 
                             class="w-12 h-12 rounded-full object-cover border-2 border-zinc-200 dark:border-zinc-700 group-hover:border-emerald-400 transition-colors">
                    </div>

                    {{-- Application Details --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-zinc-900 dark:text-white truncate group-hover:text-emerald-600 transition-colors">{{ $application->worker->name }}</h3>
                                <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                                    @if($application->worker->reviewsReceived && $application->worker->reviewsReceived->count() > 0)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ round($application->worker->reviewsReceived->avg('rating'), 1) }}
                                    </span>
                                    @endif
                                    <span class="flex items-center gap-1 truncate">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ trim(implode(', ', array_filter([$application->worker->wilaya, $application->worker->mkoa]))) ?: 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $application->status === 'pending' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                {{ $application->status === 'accepted' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                                {{ $application->status === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : '' }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>

                        {{-- Job Title (if showing all applications) --}}
                        @if(!$selectedJob)
                        <div class="mb-2 pb-2 border-b border-zinc-100 dark:border-zinc-800">
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('messages.applications.job_label') }} <span class="font-semibold text-zinc-900 dark:text-white">{{ $application->job->title }}</span></p>
                        </div>
                        @endif

                        {{-- Cover Letter --}}
                        @if($application->cover_letter)
                        <div class="mb-2">
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 bg-zinc-50 dark:bg-zinc-800 p-2 rounded-lg line-clamp-2">{{ $application->cover_letter }}</p>
                        </div>
                        @endif

                        {{-- Compact Info Row --}}
                        <div class="flex flex-wrap gap-3 text-xs mb-3">
                            @if($application->proposed_budget)
                            <span class="flex items-center gap-1 font-semibold text-emerald-600 dark:text-emerald-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ number_format($application->proposed_budget) }}
                            </span>
                            @endif
                            @if($application->proposed_duration)
                            <span class="flex items-center gap-1 text-zinc-500 dark:text-zinc-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $application->proposed_duration }}
                            </span>
                            @endif
                            <span class="flex items-center gap-1 text-zinc-500 dark:text-zinc-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $application->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Skills --}}
                        @if($application->worker->skills && $application->worker->skills->count() > 0)
                        <div class="mb-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($application->worker->skills->take(4) as $skill)
                                <span class="inline-flex items-center rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-xs px-2 py-0.5">
                                    {{ $skill->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex flex-wrap gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                            <button wire:click="viewProfile({{ $application->worker->id }})" class="px-3 py-1.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-xs font-medium rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700 hover:scale-105 transition-all duration-200">
                                {{ __('messages.applications.profile') }}
                            </button>

                            @if($application->status === 'pending')
                                {{-- Task 11: No workerBusy check — Winga can work multiple jobs --}}
                                <button wire:click="initiateAccept({{ $application->id }})" wire:loading.attr="disabled" wire:target="initiateAccept({{ $application->id }})" class="group/btn px-3 py-1.5 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-xs font-medium rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 group-hover/btn:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('messages.applications.accept') }}
                                    </span>
                                </button>
                                <button wire:click="openRejectModal({{ $application->id }})" class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 hover:scale-105 transition-all duration-200">
                                    {{ __('messages.applications.reject') }}
                                </button>
                            @endif

                            @if($application->status === 'accepted')
                                {{-- Task 9: Chat + Pay buttons for accepted applications --}}
                                <a href="{{ route('messages') }}" class="group/btn px-3 py-1.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-xs font-medium rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200" wire:navigate>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 group-hover/btn:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        {{ __('messages.applications.chat') }}
                                    </span>
                                </a>
                                @if($application->job->status !== 'in_progress')
                                <button wire:click="initiatePayment({{ $application->id }})" class="group/btn px-3 py-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-medium rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        Lipa
                                    </span>
                                </button>
                                @else
                                <span class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-medium rounded-lg">
                                    Imelipwa
                                </span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.applications.no_applications') }}</h3>
            <p class="text-zinc-500 dark:text-zinc-400">
                @if($filter === 'all')
                    {{ __('messages.applications.no_applications_all') }}
                @else
                    {{ __('messages.applications.no_applications_filter', ['filter' => $filter]) }}
                @endif
            </p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($applications->hasPages())
    <div class="mt-6">
        {{ $applications->links() }}
    </div>
    @endif

    {{-- Rejection Comment Modal (Task 9) --}}
    @if($showRejectModal)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click="closeRejectModal">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl max-w-md w-full p-6" wire:click.stop>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Kataa Ombi</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Unaweza kuandika sababu (si lazima)</p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Sababu ya kukataa (hiari)</label>
                <textarea wire:model="rejectionComment" rows="3" placeholder="Mfano: Tunahitaji mtu mwenye uzoefu zaidi katika eneo hili..." class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-2 text-sm text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                <p class="text-xs text-zinc-400 mt-1">Maoni haya yataonekana kwa winga kwenye arifa yake.</p>
            </div>

            <div class="flex gap-3">
                <button wire:click="closeRejectModal" class="flex-1 px-4 py-2.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-sm font-medium rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                    Ghairi
                </button>
                <button wire:click="confirmReject" class="flex-1 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                    <span wire:loading.remove wire:target="confirmReject">Kataa Ombi</span>
                    <span wire:loading wire:target="confirmReject">Inaendelea...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Payment Method Selection Modal (Task 6) --}}
    @if($showPaymentModal)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click="closePaymentModal">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl max-w-md w-full" wire:click.stop>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Chagua Njia ya Malipo</h3>
                    <button wire:click="closePaymentModal" class="text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 p-1 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-3 mb-5 space-y-1.5">
                    @php $feePercent = \App\Models\Payment::getPlatformFeePercent(); @endphp
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-zinc-600 dark:text-zinc-400">Dau la Winga:</span>
                        <span class="font-semibold text-zinc-900 dark:text-white">TZS {{ number_format($workerBidAmount ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-zinc-400 dark:text-zinc-500">
                        <span>Komisioni ya Winga ({{ $feePercent }}%) — inalipwa na Winga</span>
                        <span>- TZS {{ number_format($platformFeeAmount ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm pt-1.5 border-t border-emerald-200 dark:border-emerald-700">
                        <span class="text-emerald-700 dark:text-emerald-400 font-bold">Unalipa:</span>
                        <span class="text-lg font-bold text-emerald-700 dark:text-emerald-400">TZS {{ number_format($paymentAmount ?? 0) }}</span>
                    </div>
                </div>

                <div class="space-y-3 mb-6">
                    {{-- Wallet Option --}}
                    <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 {{ $paymentMethod === 'wallet' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="wallet" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span class="font-semibold text-zinc-900 dark:text-white text-sm">Wallet</span>
                            </div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Salio: TZS {{ number_format(auth()->user()->wallet_balance) }}</p>
                        </div>
                    </label>

                    {{-- Mobile Money Option --}}
                    <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 {{ $paymentMethod === 'mobile' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="mobile" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span class="font-semibold text-zinc-900 dark:text-white text-sm">Mobile Money</span>
                            </div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">M-Pesa, TigoPesa, AirtelMoney</p>
                        </div>
                    </label>

                    {{-- Card Option --}}
                    <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 {{ $paymentMethod === 'card' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="card" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span class="font-semibold text-zinc-900 dark:text-white text-sm">Kadi (Card)</span>
                            </div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Visa, Mastercard</p>
                        </div>
                    </label>
                </div>

                @if($paymentMethod !== 'wallet')
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3 mb-4">
                    <p class="text-xs text-amber-700 dark:text-amber-400">
                        <span class="font-semibold">Kumbuka:</span> Utapelekwa kwenye ukurasa wa wallet kukamilisha malipo kwanza, kisha urudi kukubali ombi.
                    </p>
                </div>
                @endif

                {{-- Task 10: Disclaimer about off-platform payments --}}
                <div class="mb-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg">
                    <p class="text-xs text-red-700 dark:text-red-400">
                        <span class="font-bold">Onyo:</span> Winga haiwajibiki kwa malipo yanayofanywa nje ya mfumo huu. Lipa kupitia Winga pekee ili kulindwa na sera yetu ya escrow.
                    </p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="closePaymentModal" class="flex-1 px-4 py-2.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-sm font-medium rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                        Ghairi
                    </button>
                    <button wire:click="confirmPayment" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-medium rounded-xl hover:shadow-lg transition-all duration-200">
                        <span wire:loading.remove wire:target="confirmPayment">Endelea na Malipo</span>
                        <span wire:loading wire:target="confirmPayment">Inaendelea...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Post-Acceptance Modal: Chat or Pay options (Task 9) --}}
    @if($showAcceptedModal)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click="closeAcceptedModal">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl max-w-sm w-full p-6" wire:click.stop>
            <div class="text-center mb-5">
                <div class="w-14 h-14 mx-auto rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Mfanyakazi Amekubaliwa!</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Unaweza kuzungumza naye kwanza, kisha mlipane ukikubaliana.</p>
            </div>

            {{-- Fee preview --}}
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-xl p-3 mb-5 space-y-1.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-zinc-500 dark:text-zinc-400">Dau la Winga:</span>
                    <span class="font-semibold text-zinc-900 dark:text-white">TZS {{ number_format($workerBidAmount ?? 0) }}</span>
                </div>
                <div class="flex justify-between text-xs text-zinc-400">
                    <span>Komisioni ya mfumo ({{ \App\Models\Payment::getPlatformFeePercent() }}%) — inalipwa na Winga</span>
                    <span>- TZS {{ number_format($platformFeeAmount ?? 0) }}</span>
                </div>
            </div>

            <div class="space-y-2">
                <a href="{{ route('messages') }}" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-semibold rounded-xl hover:shadow-lg transition-all" wire:navigate>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Chat na Winga
                </a>
                <button wire:click="closeAcceptedModal; initiatePayment({{ $pendingApplicationId ?? 0 }})" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:shadow-lg transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Lipa Sasa
                </button>
                <button wire:click="closeAcceptedModal" class="w-full px-4 py-2 text-zinc-500 dark:text-zinc-400 text-sm font-medium hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors">
                    Funga (Lipa Baadaye)
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Worker Profile Modal --}}
    @if($viewingWorkerId && $selectedWorker)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click="closeProfile">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            {{-- Modal Header --}}
            <div class="sticky top-0 bg-gradient-to-r from-emerald-600 to-teal-500 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <img src="{{ $selectedWorker['avatar_url'] }}" alt="{{ $selectedWorker['name'] }}" class="w-16 h-16 rounded-full border-4 border-white/30">
                        <div>
                            <h2 class="text-2xl font-bold text-black">{{ $selectedWorker['name'] }}</h2>
                            <p class="text-zinc-800">{{ $selectedWorker['location'] }}</p>
                        </div>
                    </div>
                    <button wire:click="closeProfile" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Content --}}
            <div class="p-6 space-y-6">
                {{-- Rating & Stats --}}
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $selectedWorker['rating'] }}</span>
                        <span class="text-zinc-500">({{ $selectedWorker['review_count'] }} reviews)</span>
                    </div>
                    <div class="text-zinc-600 dark:text-zinc-400">
                        <span class="font-semibold">{{ __('messages.applications.price') }}</span> TZS {{ $selectedWorker['bei'] }}
                    </div>
                    <div class="text-zinc-600 dark:text-zinc-400">
                        <span class="font-semibold">{{ __('messages.applications.experience') }}</span> {{ $selectedWorker['uzoefu'] }}
                    </div>
                </div>

                {{-- Bio --}}
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.applications.about') }}</h3>
                    <p class="text-zinc-600 dark:text-zinc-400">{{ $selectedWorker['bio'] }}</p>
                </div>

                {{-- Skills --}}
                @if(count($selectedWorker['skills']) > 0)
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.applications.skills') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($selectedWorker['skills'] as $skill)
                        <span class="px-3 py-1 bg-winga-100 text-winga-700 rounded-full text-sm font-medium">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Portfolio --}}
                @if(count($selectedWorker['portfolio']) > 0)
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Portfolio</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($selectedWorker['portfolio'] as $item)
                        <div class="border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden">
                            <img src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}" class="w-full h-32 object-cover">
                            <div class="p-3">
                                <p class="font-semibold text-zinc-900 dark:text-white text-sm">{{ $item['title'] }}</p>
                                <p class="text-xs text-zinc-500 mt-1">{{ $item['description'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Reviews --}}
                @if(count($selectedWorker['reviews']) > 0)
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white mb-3">{{ __('messages.applications.reviews') }}</h3>
                    <div class="space-y-3">
                        @foreach($selectedWorker['reviews'] as $review)
                        <div class="border border-zinc-200 dark:border-zinc-800 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <img src="{{ $review['reviewer_avatar'] }}" alt="{{ $review['reviewer_name'] }}" class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="font-semibold text-zinc-900 dark:text-white">{{ $review['reviewer_name'] }}</p>
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review['rating'] ? 'text-amber-500' : 'text-zinc-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $review['comment'] }}</p>
                                    <p class="text-xs text-zinc-500 mt-1">{{ $review['date'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
