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
                <option value="in_progress">{{ __('messages.huduma_maombi.filter_in_progress') }} ({{ $counts['in_progress'] }})</option>
                <option value="completed">{{ __('messages.huduma_maombi.filter_completed') }} ({{ $counts['completed'] }})</option>
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
                        @if($req->status === 'accepted' && ! $req->payment)
                            <p class="text-xs text-amber-800 dark:text-amber-200 mt-3 rounded-lg border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/30 px-3 py-2">{{ __('messages.huduma_maombi.awaiting_client_payment') }}</p>
                        @endif
                        @if($req->status === 'declined' && $req->decline_reason)
                            <div class="mt-3 rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/30 px-3 py-2">
                                <p class="text-xs font-semibold text-red-700 dark:text-red-300 mb-0.5">Sababu ya kukataa:</p>
                                <p class="text-xs text-red-600 dark:text-red-400">{{ $req->decline_reason }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                        <flux:badge :color="match ($req->status) {
                            'pending' => 'amber',
                            'accepted' => 'green',
                            'in_progress' => 'blue',
                            'completed' => 'zinc',
                            default => 'zinc',
                        }">{{ $req->status }}</flux:badge>
                        @if($req->status === 'pending')
                            <flux:button size="sm" variant="primary" wire:click="accept({{ $req->id }})" wire:loading.attr="disabled">{{ __('messages.huduma_maombi.accept') }}</flux:button>
                            <flux:button size="sm" variant="ghost" wire:click="openDeclineModal({{ $req->id }})" wire:loading.attr="disabled">{{ __('messages.huduma_maombi.decline') }}</flux:button>
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

    {{-- Decline Reason Modal --}}
    @if($decliningRequestId)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" wire:click.self="closeDeclineModal">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-md" wire:click.stop>
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Kataa Ombi</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Eleza sababu ya kukataa ombi hili. Mteja ataona sababu hii.</p>
            </div>
            <div class="p-6">
                <form wire:submit="confirmDecline">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Sababu ya kukataa</label>
                            <textarea wire:model="declineReason"
                                      rows="3"
                                      placeholder="Mfano: Sina uwezo wa kufanya kazi hii kwa sasa..."
                                      class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                      required></textarea>
                            @error('declineReason') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" wire:click="closeDeclineModal"
                                    wire:loading.attr="disabled" wire:target="confirmDecline"
                                    class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 text-zinc-700 rounded-lg text-sm font-medium transition disabled:opacity-50">
                                Ghairi
                            </button>
                            <button type="submit"
                                    wire:loading.attr="disabled" wire:target="confirmDecline"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition disabled:opacity-70 inline-flex items-center gap-2">
                                <svg wire:loading wire:target="confirmDecline" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                <span wire:loading.remove wire:target="confirmDecline">Kataa Ombi</span>
                                <span wire:loading wire:target="confirmDecline">Inakataa...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
