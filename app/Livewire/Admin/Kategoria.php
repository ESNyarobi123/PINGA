<?php

namespace App\Livewire\Admin;

use App\Models\AdminAuditLog;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Kategoria extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterStatus = '';

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    // Category form
    public array $categoryForm = [
        'id' => null,
        'name' => '',
        'description' => '',
        'icon' => '',
        'color' => '#0d9488',
        'is_active' => true,
        'parent_id' => null,
        'sort_order' => 0,
    ];

    public bool $showModal = false;

    public bool $isEditing = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount(): void
    {
        $this->resetForm();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    private function getCategoriesQuery()
    {
        return Category::query()
            ->with(['parent', 'children', 'jobs' => fn ($q) => $q->select('id', 'category_id')])
            ->when($this->search, fn ($query) => $query
                ->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('description', 'like', '%'.$this->search.'%');
                })
            )
            ->when($this->filterStatus, fn ($query) => match ($this->filterStatus) {
                'active' => $query->where('is_active', true),
                'inactive' => $query->where('is_active', false),
                'with_jobs' => $query->whereHas('jobs'),
                'without_jobs' => $query->whereDoesntHave('jobs'),
                'parent' => $query->whereNull('parent_id'),
                'child' => $query->whereNotNull('parent_id'),
                default => $query,
            })
            ->orderBy($this->sortBy, $this->sortDirection);
    }

    public function getCategoriesProperty()
    {
        return $this->getCategoriesQuery()->paginate(25);
    }

    public function getTotalCategoriesProperty(): int
    {
        return Category::count();
    }

    public function getActiveCategoriesProperty(): int
    {
        return Category::where('is_active', true)->count();
    }

    public function getParentCategoriesProperty(): int
    {
        return Category::whereNull('parent_id')->count();
    }

    public function getCategoriesWithJobsProperty(): int
    {
        return Category::whereHas('jobs')->count();
    }

    public function resetForm(): void
    {
        $this->categoryForm = [
            'id' => null,
            'name' => '',
            'description' => '',
            'icon' => '',
            'color' => '#0d9488',
            'is_active' => true,
            'parent_id' => null,
            'sort_order' => 0,
        ];
        $this->isEditing = false;
    }

    /**
     * @param  Category|int|string|null  $category  Model (Livewire), id from blade (✏️), or null for new.
     */
    public function openModal(mixed $category = null): void
    {
        if ($category instanceof Category) {
            $model = $category;
        } elseif ($category !== null && $category !== '' && is_numeric($category)) {
            $model = Category::find((int) $category);
            if (! $model) {
                $this->dispatch('toast', message: __('messages.admin_categories.category_not_found'), type: 'error');

                return;
            }
        } else {
            $this->resetForm();
            $this->showModal = true;

            return;
        }

        $this->isEditing = true;
        $this->categoryForm = [
            'id' => $model->id,
            'name' => $model->name,
            'description' => $model->description ?? '',
            'icon' => $model->icon ?? '',
            'color' => $model->color ?? '#0d9488',
            'is_active' => $model->is_active,
            'parent_id' => $model->parent_id,
            'sort_order' => $model->sort_order ?? 0,
        ];
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function saveCategory(): void
    {
        $categoryId = $this->categoryForm['id'] ?? null;

        $this->validate([
            'categoryForm.name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'categoryForm.description' => 'nullable|string|max:1000',
            'categoryForm.icon' => 'nullable|string|max:50',
            'categoryForm.color' => 'required|string|max:7',
            'categoryForm.parent_id' => 'nullable|exists:categories,id',
            'categoryForm.sort_order' => 'required|integer|min:0',
        ]);

        if (filled($categoryId)) {
            $category = Category::find($categoryId);
            if (! $category) {
                $this->dispatch('toast', message: __('messages.admin_categories.category_not_found'), type: 'error');
                $this->closeModal();

                return;
            }

            $oldData = $category->toArray();

            $category->update([
                'name' => $this->categoryForm['name'],
                'description' => $this->categoryForm['description'],
                'icon' => $this->categoryForm['icon'],
                'color' => $this->categoryForm['color'],
                'is_active' => $this->categoryForm['is_active'],
                'parent_id' => $this->categoryForm['parent_id'],
                'sort_order' => $this->categoryForm['sort_order'],
            ]);

            $this->logAdminAction('update_category', $category, [
                'old' => $oldData,
                'new' => $category->toArray(),
            ]);

            $this->dispatch('toast', message: 'Category updated successfully', type: 'success');
        } else {
            $category = Category::create([
                'name' => $this->categoryForm['name'],
                'description' => $this->categoryForm['description'],
                'icon' => $this->categoryForm['icon'],
                'color' => $this->categoryForm['color'],
                'is_active' => $this->categoryForm['is_active'],
                'parent_id' => $this->categoryForm['parent_id'],
                'sort_order' => $this->categoryForm['sort_order'],
            ]);

            $this->logAdminAction('create_category', $category, [
                'category_data' => $category->toArray(),
            ]);

            $this->dispatch('toast', message: 'Category created successfully', type: 'success');
        }

        $this->closeModal();
    }

    public function deleteCategory(Category $category): void
    {
        if ($category->jobs()->count() > 0) {
            $this->dispatch('toast', message: 'Cannot delete category with associated jobs', type: 'error');

            return;
        }

        if ($category->children()->count() > 0) {
            $this->dispatch('toast', message: 'Cannot delete category with subcategories', type: 'error');

            return;
        }

        $this->logAdminAction('delete_category', $category, [
            'category_data' => $category->toArray(),
        ]);

        $category->delete();
        $this->dispatch('toast', message: 'Category deleted successfully', type: 'success');
    }

    public function toggleCategoryStatus(Category $category): void
    {
        $category->update(['is_active' => ! $category->is_active]);

        $this->logAdminAction('toggle_category_status', $category, [
            'old_status' => ! $category->is_active,
            'new_status' => $category->is_active,
        ]);

        $this->dispatch('toast', message: 'Category status updated', type: 'success');
    }

    public function getCategoryStats(Category $category): array
    {
        return [
            'total_jobs' => $category->jobs()->count(),
            'active_jobs' => $category->jobs()->where('status', 'open')->count(),
            'pending_jobs' => $category->jobs()->where('is_approved', false)->count(),
            'completed_jobs' => $category->jobs()->where('status', 'completed')->count(),
            'children_count' => $category->children()->count(),
            'parent_name' => $category->parent?->name,
        ];
    }

    public function reorderCategories(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index]);
        }

        $this->logAdminAction('reorder_categories', null, [
            'ordered_ids' => $orderedIds,
        ]);

        $this->dispatch('toast', message: 'Categories reordered successfully', type: 'success');
    }

    public function exportCategories(): void
    {
        $categories = Category::with(['parent', 'jobs'])->get();

        $csv = "ID,Name,Description,Parent,Color,Icon,Active,Jobs Count,Created At\n";

        foreach ($categories as $category) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $category->id,
                str_replace(',', '', $category->name),
                str_replace(',', '', $category->description ?? ''),
                $category->parent?->name ?? '',
                $category->color ?? '',
                $category->icon ?? '',
                $category->is_active ? 'Yes' : 'No',
                $category->jobs->count(),
                $category->created_at->format('Y-m-d H:i')
            );
        }

        $this->dispatch('download', data: $csv, filename: 'categories_export.csv');
    }

    private function logAdminAction(string $action, $model, array $changes = []): void
    {
        AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $changes['old'] ?? null,
            'new_values' => $changes['new'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        return view('livewire.admin.kategoria', [
            'categories' => $this->categories,
            'parentCategories' => Category::whereNull('parent_id')->orderBy('name')->get(),
            'totalCategories' => $this->totalCategories,
            'activeCategories' => $this->activeCategories,
            'parentCategoriesCount' => $this->parentCategories,
            'categoriesWithJobs' => $this->categoriesWithJobs,
        ])
            ->layout('layouts.admin')
            ->title('Category Management');
    }
}
