<div>
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">{{ $isEditing ? __('messages.post_huduma.edit_title') : __('messages.post_huduma.title') }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ $isEditing ? __('messages.post_huduma.edit_subtitle') : __('messages.post_huduma.subtitle') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <flux:button variant="outline" :href="route('winga.huduma-zangu')" wire:navigate>{{ __('messages.post_huduma.view_list') }}</flux:button>
                @if($showLimitError && $suggestedUpgrade)
                    <flux:button variant="primary" :href="route('winga.subscription')" wire:navigate>{{ __('messages.post_huduma.upgrade') }}</flux:button>
                @endif
            </div>
        </div>
    </div>

    @if(! $isEditing)
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800 p-4 mb-6">
        <p class="text-sm font-medium text-emerald-900 dark:text-emerald-100">
            {{ __('messages.post_huduma.limit_info', ['remaining' => $remaining, 'max' => $max]) }}
        </p>
    </div>
    @endif

    @if($showLimitError && ! $isEditing)
        <div class="mb-6 rounded-xl border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 p-4">
            <p class="font-semibold text-amber-900 dark:text-amber-100">{{ __('messages.post_huduma.at_limit_title') }}</p>
            <p class="text-sm text-amber-800 dark:text-amber-200 mt-1">{{ $limitMessage }}</p>
            <p class="text-sm text-amber-800 dark:text-amber-200 mt-2">{{ __('messages.post_huduma.at_limit_body') }}</p>
            <flux:button class="mt-3" variant="primary" :href="route('winga.subscription')" wire:navigate>{{ __('messages.post_huduma.upgrade') }}</flux:button>
        </div>
    @endif

    <form wire:submit="submit" class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 space-y-5">
        <flux:input wire:model="title" :label="__('messages.post_huduma.title_label')" type="text" :placeholder="__('messages.post_huduma.title_placeholder')" required />
        @error('title')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <flux:textarea wire:model="description" :label="__('messages.post_huduma.description_label')" rows="6" :placeholder="__('messages.post_huduma.description_placeholder')" required />
        @error('description')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <flux:select wire:model="categoryId" :label="__('messages.post_huduma.category_label')" required>
            <option value="">{{ __('messages.post_huduma.category_placeholder') }}</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </flux:select>
        @error('categoryId')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <flux:select wire:model.live="priceType" :label="__('messages.post_huduma.price_type_label')">
            <option value="fixed">{{ __('messages.post_huduma.price_fixed') }}</option>
            <option value="hourly">{{ __('messages.post_huduma.price_hourly') }}</option>
            <option value="negotiable">{{ __('messages.post_huduma.price_negotiable') }}</option>
        </flux:select>
        @error('priceType')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 space-y-4">
            <div>
                <flux:label>{{ __('messages.post_huduma.packages_heading') }}</flux:label>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ __('messages.post_huduma.packages_help') }}</p>
            </div>
            @foreach($packages as $index => $_row)
                <div class="rounded-lg border border-zinc-100 dark:border-zinc-800 p-4 space-y-3 bg-zinc-50/50 dark:bg-zinc-800/30">
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-xs font-semibold uppercase tracking-wide text-zinc-500">{{ __('messages.post_huduma.package_number', ['n' => $index + 1]) }}</span>
                        @if(count($packages) > 1)
                            <button type="button" wire:click="removePackageRow({{ $index }})" class="text-xs text-red-600 hover:underline">{{ __('messages.post_huduma.remove_package') }}</button>
                        @endif
                    </div>
                    <flux:input wire:model="packages.{{ $index }}.title" :label="__('messages.post_huduma.package_title_label')" type="text" :placeholder="__('messages.post_huduma.package_title_placeholder')" />
                    @error('packages.'.$index.'.title')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <flux:textarea wire:model="packages.{{ $index }}.description" :label="__('messages.post_huduma.package_desc_label')" rows="2" :placeholder="__('messages.post_huduma.package_desc_placeholder')" />
                    @error('packages.'.$index.'.description')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <flux:input wire:model="packages.{{ $index }}.price" :label="__('messages.post_huduma.package_price_label')" type="number" step="0.01" min="0" :placeholder="__('messages.post_huduma.package_price_placeholder')" />
                    @error('packages.'.$index.'.price')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
            @error('packages')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            <flux:button type="button" variant="outline" size="sm" wire:click="addPackageRow">{{ __('messages.post_huduma.add_package') }}</flux:button>
        </div>

        <div>
            <flux:label>{{ __('messages.post_huduma.images_label') }}</flux:label>
            @if($isEditing && count($existingImages) > 0)
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 mb-2">{{ __('messages.post_huduma.existing_images_help') }}</p>
                <div class="flex flex-wrap gap-3 mb-3">
                    @foreach($existingImages as $index => $path)
                        <div class="relative size-24 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden shrink-0">
                            <img src="{{ asset('storage/'.$path) }}" alt="" class="size-full object-cover">
                            <button type="button" wire:click="removeExistingImage({{ $index }})" class="absolute top-1 end-1 size-7 rounded-md bg-black/60 text-white text-xs font-bold hover:bg-black/80">
                                ×
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
            <input type="file" wire:model="images" accept="image/*" multiple
                class="mt-1 block w-full text-sm text-zinc-600 file:mr-4 file:rounded-lg file:border-0 file:bg-winga-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-winga-700 dark:text-zinc-400" />
            @error('images')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror

            @if(count($images) > 0)
                <div class="mt-3 flex flex-wrap gap-3">
                    @foreach($images as $index => $file)
                        @if(is_object($file) && method_exists($file, 'temporaryUrl'))
                            <div class="relative size-24 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden shrink-0">
                                <img src="{{ $file->temporaryUrl() }}" alt="" class="size-full object-cover">
                                <button type="button" wire:click="removeImage({{ $index }})" class="absolute top-1 end-1 size-7 rounded-md bg-black/60 text-white text-xs font-bold hover:bg-black/80">
                                    ×
                                </button>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <div class="pt-2">
            <flux:button type="submit" variant="primary" :disabled="! $canPost" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submit">{{ $isEditing ? __('messages.post_huduma.submit_update') : __('messages.post_huduma.submit') }}</span>
                <span wire:loading wire:target="submit">{{ $isEditing ? __('messages.post_huduma.submit_update') : __('messages.post_huduma.submit') }}…</span>
            </flux:button>
        </div>
    </form>
</div>
