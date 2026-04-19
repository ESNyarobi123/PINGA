<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('messages.admin_categories.title') }}</h1>
            <p class="text-zinc-500 dark:text-zinc-400">{{ __('messages.admin_categories.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="openModal"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                ➕ {{ __('messages.admin_categories.add_category') }}
            </button>
            <button wire:click="exportCategories"
                    class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-white rounded-lg text-sm font-medium transition">
                📤 {{ __('messages.admin_categories.export_csv') }}
            </button>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_categories.total') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($totalCategories) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_categories.all_categories') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_categories.active') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($activeCategories) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_categories.active_categories') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_categories.parents') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($parentCategoriesCount) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_categories.parent_categories') }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A9.002 9.002 0 0015.745 4.254 9.002 9.002 0 006.255 13.255 9.002 9.002 0 0012 21.745a9.002 9.002 0 006.745-8.49z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-zinc-500">{{ __('messages.admin_categories.with_jobs') }}</span>
            </div>
            <p class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($categoriesWithJobs) }}</p>
            <p class="text-xs text-zinc-500">{{ __('messages.admin_categories.categories_with_jobs') }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-4 mb-6">
        <div class="flex flex-wrap gap-4">
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="{{ __('messages.admin_categories.search_placeholder') }}"
                   class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">

            <select wire:model.live="filterStatus" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="">{{ __('messages.admin_categories.all_status') }}</option>
                <option value="active">{{ __('messages.admin_categories.active') }}</option>
                <option value="inactive">{{ __('messages.admin_categories.inactive') }}</option>
                <option value="with_jobs">{{ __('messages.admin_categories.with_jobs') }}</option>
                <option value="without_jobs">{{ __('messages.admin_categories.without_jobs') }}</option>
                <option value="parent">{{ __('messages.admin_categories.parent_only') }}</option>
                <option value="child">{{ __('messages.admin_categories.child_only') }}</option>
            </select>

            <select wire:model.live="sortBy" class="px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                <option value="name">{{ __('messages.admin_categories.sort_name') }}</option>
                <option value="created_at">{{ __('messages.admin_categories.sort_created') }}</option>
                <option value="sort_order">{{ __('messages.admin_categories.sort_order') }}</option>
                <option value="jobs_count">{{ __('messages.admin_categories.sort_jobs') }}</option>
            </select>

            <div class="flex items-center gap-2 text-sm text-zinc-500">
                <span>{{ $categories->total() }} {{ __('messages.admin_categories.categories') }}</span>
            </div>
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('name')">
                            {{ __('messages.admin_categories.category') }}
                            @if($sortBy === 'name')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_categories.parent') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_categories.jobs') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_categories.status') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('created_at')">
                            {{ __('messages.admin_categories.created') }}
                            @if($sortBy === 'created_at')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('messages.admin_categories.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($categories as $category)
                    @php $stats = $this->getCategoryStats($category); @endphp
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg" style="background-color: {{ $category->color ?? '#0d9488' }}20; color: {{ $category->color ?? '#0d9488' }}">
                                    {{ $category->icon ?? '📁' }}
                                </div>
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">{{ $category->name }}</p>
                                    @if($category->description)
                                    <p class="text-xs text-zinc-500">{{ Str::limit($category->description, 50) }}</p>
                                    @endif
                                    @if($category->children->count() > 0)
                                    <p class="text-xs text-zinc-400">{{ $category->children->count() }} {{ __('messages.admin_categories.subcategories') }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($category->parent)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded flex items-center justify-center text-xs" style="background-color: {{ $category->parent->color ?? '#0d9488' }}20; color: {{ $category->parent->color ?? '#0d9488' }}">
                                    {{ $category->parent->icon ?? '📁' }}
                                </div>
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $category->parent->name }}</span>
                            </div>
                            @else
                            <span class="text-sm text-zinc-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-zinc-900 dark:text-white">{{ $stats['total_jobs'] }}</span>
                                    <span class="text-xs text-zinc-500">{{ __('messages.admin_categories.total') }}</span>
                                </div>
                                <div class="flex gap-3 text-xs">
                                    <span class="text-green-600">{{ $stats['active_jobs'] }} {{ __('messages.admin_categories.active') }}</span>
                                    <span class="text-amber-600">{{ $stats['pending_jobs'] }} {{ __('messages.admin_categories.pending') }}</span>
                                    <span class="text-blue-600">{{ $stats['completed_jobs'] }} {{ __('messages.admin_categories.completed') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-bold rounded-lg {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $category->is_active ? __('messages.admin_categories.active') : __('messages.admin_categories.inactive') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $category->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <button wire:click="openModal({{ $category->id }})"
                                        class="px-2 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                                    ✏️
                                </button>
                                <button wire:click="toggleCategoryStatus({{ $category->id }})"
                                        wire:confirm="Toggle status for {{ $category->name }}?"
                                        class="px-2 py-1 text-xs {{ $category->is_active ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded transition">
                                    {{ $category->is_active ? '⏸️' : '▶️' }}
                                </button>
                                <button wire:click="deleteCategory({{ $category->id }})"
                                        wire:confirm="Delete category {{ $category->name }}? This cannot be undone!"
                                        class="px-2 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded transition">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center text-zinc-400">
                            <div class="text-4xl mb-3">🏷️</div>
                            <p class="font-medium">{{ __('messages.admin_categories.no_categories') }}</p>
                            <button wire:click="openModal" class="mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                                {{ __('messages.admin_categories.create_first') }}
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

    {{-- Category Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                    {{ $isEditing ? __('messages.admin_categories.edit_category') : __('messages.admin_categories.create_category') }}
                </h2>
                <button wire:click="closeModal"
                        class="text-zinc-400 hover:text-zinc-600 transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <div class="p-6">
                <form wire:submit="saveCategory" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_categories.category_name') }} *</label>
                            <input wire:model.live="categoryForm.name"
                                   type="text"
                                   required
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm"
                                   placeholder="{{ __('messages.admin_categories.enter_name') }}">
                            @error('categoryForm.name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_categories.parent_category') }}</label>
                            <select wire:model.live="categoryForm.parent_id" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                                <option value="">{{ __('messages.admin_categories.none_parent') }}</option>
                                @foreach($parentCategories as $parent)
                                @if(!$isEditing || $parent->id != $categoryForm['id'])
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_categories.description') }}</label>
                        <textarea wire:model.live="categoryForm.description"
                                  rows="3"
                                  class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm"
                                  placeholder="{{ __('messages.admin_categories.enter_description') }}"></textarea>
                        @error('categoryForm.description')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_categories.icon') }}</label>
                            <input wire:model.live="categoryForm.icon"
                                   type="text"
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm"
                                   placeholder="📁">
                            <p class="text-xs text-zinc-500 mt-1">{{ __('messages.admin_categories.icon_hint') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_categories.color') }}</label>
                            <div class="flex gap-2">
                                <input wire:model.live="categoryForm.color"
                                       type="color"
                                       class="w-16 h-9 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded">
                                <input wire:model.live="categoryForm.color"
                                       type="text"
                                       class="flex-1 px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm"
                                       placeholder="#0d9488">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">{{ __('messages.admin_categories.sort_order_label') }}</label>
                            <input wire:model.live="categoryForm.sort_order"
                                   type="number"
                                   min="0"
                                   class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input wire:model.live="categoryForm.is_active" type="checkbox" id="is_active" class="rounded">
                        <label for="is_active" class="text-sm text-zinc-700 dark:text-zinc-300">{{ __('messages.admin_categories.active') }}</label>
                    </div>

                    {{-- Preview --}}
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('messages.admin_categories.preview') }}</label>
                        <div class="p-4 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg" style="background-color: {{ $categoryForm['color'] ?? '#0d9488' }}20; color: {{ $categoryForm['color'] ?? '#0d9488' }}">
                                    {{ $categoryForm['icon'] ?? '📁' }}
                                </div>
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white">{{ $categoryForm['name'] ?: 'Category Name' }}</p>
                                    @if($categoryForm['description'])
                                    <p class="text-xs text-zinc-500">{{ Str::limit($categoryForm['description'], 50) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 text-zinc-700 rounded-lg text-sm font-medium transition">
                            {{ __('messages.admin_categories.cancel') }}
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                            {{ $isEditing ? __('messages.admin_categories.update_category') : __('messages.admin_categories.create_category') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
