<div>
    {{-- Page Header --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ __('messages.portfolio.title') }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ __('messages.portfolio.subtitle') }}</p>
            </div>
            @if($canUpload)
            <button wire:click="toggleUploadModal" class="px-4 py-2 bg-winga-600 text-white font-medium rounded-lg hover:bg-winga-700 transition-colors">
                {{ __('messages.portfolio.add_work') }}
            </button>
            @endif
        </div>
    </div>

    {{-- Upload Limit Info --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-blue-900 dark:text-blue-100">{{ __('messages.portfolio.upload_limit') }}</p>
                    <p class="text-xs text-blue-700 dark:text-blue-300">{{ $remaining }}/{{ $max }} {{ __('messages.portfolio.remaining') }}</p>
                </div>
            </div>
            @if(!$canUpload)
            <div class="text-xs text-blue-700 dark:text-blue-300">
                <span class="font-medium">{{ __('messages.portfolio.upgrade_more') }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Portfolio Grid --}}
    @if($portfolios->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($portfolios as $portfolio)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-lg transition-shadow overflow-hidden group">
            {{-- Image --}}
            <div class="aspect-video bg-zinc-100 dark:bg-zinc-800 relative overflow-hidden">
                @if($portfolio->image_path)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($portfolio->image_path) }}"
                     alt="{{ $portfolio->title }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                @endif
                
                {{-- Category Badge --}}
                @if($portfolio->category)
                <div class="absolute top-3 left-3">
                    <span class="inline-flex items-center rounded-md bg-white/90 dark:bg-zinc-900/90 text-zinc-700 dark:text-zinc-300 text-xs font-medium px-2 py-1 backdrop-blur-sm">
                        {{ $portfolio->category->name }}
                    </span>
                </div>
                @endif
                
                {{-- Action Buttons --}}
                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $portfolio->id }})" class="w-8 h-8 rounded-lg bg-white/90 dark:bg-zinc-900/90 text-zinc-700 dark:text-zinc-300 hover:bg-white dark:hover:bg-zinc-900 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button wire:click="delete({{ $portfolio->id }})" class="w-8 h-8 rounded-lg bg-red-500/90 text-white hover:bg-red-600 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            {{-- Content --}}
            <div class="p-4">
                <h3 class="font-semibold text-zinc-900 dark:text-white mb-2">{{ $portfolio->title }}</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2 mb-3">{{ $portfolio->description }}</p>
                
                <div class="flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
                    <span>{{ $portfolio->created_at->diffForHumans() }}</span>
                    @if($portfolio->is_featured)
                    <span class="inline-flex items-center rounded-md bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-2 py-1">
                        {{ __('messages.portfolio.featured') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $portfolios->links() }}
    </div>
    @else
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
            <svg class="w-8 h-8 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">{{ __('messages.portfolio.no_portfolio') }}</h3>
        <p class="text-zinc-500 dark:text-zinc-400 mb-4">{{ __('messages.portfolio.no_portfolio_desc') }}</p>
        @if($canUpload)
        <button wire:click="toggleUploadModal" class="px-4 py-2 bg-winga-600 text-white font-medium rounded-lg hover:bg-winga-700 transition-colors">
            {{ __('messages.portfolio.add_first') }}
        </button>
        @else
        <a href="{{ route('winga.subscription') }}" class="px-4 py-2 bg-winga-600 text-white font-medium rounded-lg hover:bg-winga-700 transition-colors" wire:navigate>
            {{ __('messages.portfolio.upgrade_subscription') }}
        </a>
        @endif
    </div>
    @endif

    {{-- Upload Modal --}}
    @if($showUploadModal)
    <div wire:click="toggleUploadModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div wire:click.stop class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $editingId ? __('messages.portfolio.edit_title', ['default' => 'Hariri Portfolio']) : __('messages.portfolio.add_new_title') }}</h2>
                <button wire:click="toggleUploadModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form wire:submit="save">
                <div class="space-y-4">
                    <flux:input wire:model="title" type="text" placeholder="{{ __('messages.portfolio.title_placeholder') }}" required />
                    <flux:textarea wire:model="description" placeholder="{{ __('messages.portfolio.description_placeholder') }}" rows="3" />
                    <flux:select wire:model="categoryId" required>
                        <option value="">{{ __('messages.portfolio.category_placeholder') }}</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </flux:select>
                    @error('categoryId')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    
                    {{-- Image Upload --}}
                    <div>
                        <flux:label>{{ __('messages.portfolio.image_label') }}</flux:label>
                        <flux:input wire:model="image" type="file" accept="image/*" {{ $editingId ? '' : 'required' }} />
                        @error('image')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center">
                        <flux:checkbox wire:model="is_featured" />
                        <flux:label class="ml-2">{{ __('messages.portfolio.featured_label') }}</flux:label>
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="button" wire:click="toggleUploadModal" class="flex-1 px-4 py-2 border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        {{ __('messages.portfolio.cancel') }}
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-winga-600 text-white font-medium rounded-lg hover:bg-winga-700 transition-colors">
                        {{ __('messages.portfolio.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
