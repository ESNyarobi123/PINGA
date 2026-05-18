<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">📢 {{ __('messages.admin_matangazo.title') }}</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.admin_matangazo.subtitle') }}</p>
        </div>
        <button wire:click="create" class="px-4 py-2 bg-winga-600 hover:bg-winga-700 text-white rounded-lg font-medium transition">
            + {{ __('messages.admin_matangazo.new') }}
        </button>
    </div>

    {{-- List --}}
    @if($announcements->count() === 0)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-12 text-center">
            <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_matangazo.empty') }}</p>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($announcements as $a)
                @php
                    $typeStyles = [
                        'info' => 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-200',
                        'success' => 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-200',
                        'warning' => 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-900/20 dark:border-amber-800 dark:text-amber-200',
                        'danger' => 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200',
                    ];
                    $typeStyle = $typeStyles[$a->type] ?? $typeStyles['info'];
                @endphp
                <div class="bg-white dark:bg-zinc-900 rounded-xl border {{ $a->is_active ? 'border-zinc-200 dark:border-zinc-700' : 'border-red-200 dark:border-red-800 opacity-70' }} p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                <span class="text-xs font-bold uppercase px-2 py-1 rounded {{ $typeStyle }}">{{ $a->type }}</span>
                                @foreach($a->audiences as $aud)
                                    <span class="text-xs px-2 py-1 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_matangazo.audiences.'.$aud) }}</span>
                                @endforeach
                                @if(!$a->is_active)
                                    <span class="text-xs px-2 py-1 rounded bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 font-bold">{{ __('messages.admin_matangazo.disabled') }}</span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ $a->title }}</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1 line-clamp-2">{{ $a->body }}</p>
                            <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-zinc-500 dark:text-zinc-400">
                                @if($a->starts_at)
                                    <span>📅 {{ __('messages.admin_matangazo.from') }}: {{ $a->starts_at->format('d M Y, H:i') }}</span>
                                @endif
                                @if($a->ends_at)
                                    <span>⏰ {{ __('messages.admin_matangazo.until') }}: {{ $a->ends_at->format('d M Y, H:i') }}</span>
                                @endif
                                @if($a->min_view_seconds > 0)
                                    <span>👁 {{ $a->min_view_seconds }}s {{ __('messages.admin_matangazo.min_view') }}</span>
                                @endif
                                @if(!$a->is_dismissible)
                                    <span>🔒 {{ __('messages.admin_matangazo.not_dismissible') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 shrink-0">
                            <button wire:click="toggleActive({{ $a->id }})"
                                    class="px-3 py-1 text-xs {{ $a->is_active ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded transition font-medium">
                                {{ $a->is_active ? __('messages.admin_matangazo.deactivate') : __('messages.admin_matangazo.activate') }}
                            </button>
                            <button wire:click="edit({{ $a->id }})"
                                    class="px-3 py-1 text-xs bg-blue-100 text-blue-700 hover:bg-blue-200 rounded transition font-medium">
                                {{ __('messages.admin_matangazo.edit') }}
                            </button>
                            <button wire:click="delete({{ $a->id }})"
                                    wire:confirm="{{ __('messages.admin_matangazo.confirm_delete') }}"
                                    class="px-3 py-1 text-xs bg-red-100 text-red-700 hover:bg-red-200 rounded transition font-medium">
                                {{ __('messages.admin_matangazo.delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
    @endif

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" wire:transition.fade>
            <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-zinc-200 dark:border-zinc-700">
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                        {{ $isEditing ? __('messages.admin_matangazo.edit_title') : __('messages.admin_matangazo.create_title') }}
                    </h2>
                    <button wire:click="closeModal" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200" type="button">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="save" class="p-6 space-y-4">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_matangazo.fields.title') }}</label>
                        <input type="text" wire:model="title" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-winga-500 focus:border-transparent" />
                        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Body --}}
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_matangazo.fields.body') }}</label>
                        <textarea wire:model="body" rows="3" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-winga-500 focus:border-transparent"></textarea>
                        @error('body') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_matangazo.fields.type') }}</label>
                        <select wire:model="type" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                            <option value="info">{{ __('messages.admin_matangazo.types.info') }}</option>
                            <option value="success">{{ __('messages.admin_matangazo.types.success') }}</option>
                            <option value="warning">{{ __('messages.admin_matangazo.types.warning') }}</option>
                            <option value="danger">{{ __('messages.admin_matangazo.types.danger') }}</option>
                        </select>
                    </div>

                    {{-- Audiences --}}
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('messages.admin_matangazo.fields.audiences') }}</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <label class="flex items-center gap-2 p-3 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <input type="checkbox" wire:model="audiences.public" class="rounded text-winga-600" />
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">🌍 {{ __('messages.admin_matangazo.audiences.public') }}</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <input type="checkbox" wire:model="audiences.mteja" class="rounded text-winga-600" />
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">💼 {{ __('messages.admin_matangazo.audiences.mteja') }}</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border border-zinc-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <input type="checkbox" wire:model="audiences.winga" class="rounded text-winga-600" />
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">🛠 {{ __('messages.admin_matangazo.audiences.winga') }}</span>
                            </label>
                        </div>
                        @error('audiences') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- CTA --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_matangazo.fields.cta_label') }}</label>
                            <input type="text" wire:model="cta_label" placeholder="{{ __('messages.admin_matangazo.placeholders.cta_label') }}" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_matangazo.fields.cta_url') }}</label>
                            <input type="url" wire:model="cta_url" placeholder="https://..." class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white" />
                            @error('cta_url') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Schedule --}}
                    <div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-2">🕒 {{ __('messages.admin_matangazo.help.timezone') }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_matangazo.fields.starts_at') }}</label>
                                <input type="datetime-local" wire:model="starts_at" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_matangazo.fields.ends_at') }}</label>
                                <input type="datetime-local" wire:model="ends_at" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white" />
                                @error('ends_at') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Min view seconds --}}
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_matangazo.fields.min_view_seconds') }}</label>
                        <input type="number" min="0" max="60" wire:model="min_view_seconds" class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white" />
                        <p class="text-xs text-zinc-500 mt-1">{{ __('messages.admin_matangazo.help.min_view_seconds') }}</p>
                    </div>

                    {{-- Toggles --}}
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="rounded text-winga-600" />
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_matangazo.fields.is_active') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_dismissible" class="rounded text-winga-600" />
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_matangazo.fields.is_dismissible') }}</span>
                        </label>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-2 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-lg font-medium hover:bg-zinc-200 dark:hover:bg-zinc-700 transition">
                            {{ __('messages.admin_matangazo.cancel') }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-winga-600 hover:bg-winga-700 text-white rounded-lg font-medium transition">
                            {{ $isEditing ? __('messages.admin_matangazo.save') : __('messages.admin_matangazo.publish') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
