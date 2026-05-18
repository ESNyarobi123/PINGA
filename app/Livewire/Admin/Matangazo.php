<?php

namespace App\Livewire\Admin;

use App\Models\SiteAnnouncement;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Matangazo extends Component
{
    use WithPagination;

    /**
     * Datetime-local fields are typed in Tanzania local time and converted to UTC for storage.
     */
    private const DISPLAY_TZ = 'Africa/Dar_es_Salaam';

    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $announcementId = null;

    public string $title = '';

    public string $body = '';

    public string $type = 'info';

    /** @var array<string, bool> */
    public array $audiences = [
        SiteAnnouncement::AUDIENCE_PUBLIC => false,
        SiteAnnouncement::AUDIENCE_MTEJA => false,
        SiteAnnouncement::AUDIENCE_WINGA => false,
    ];

    public bool $is_active = true;

    public bool $is_dismissible = true;

    public string $min_view_seconds = '0';

    public string $cta_label = '';

    public string $cta_url = '';

    public string $starts_at = '';

    public string $ends_at = '';

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'body' => 'required|string|max:2000',
            'type' => 'required|in:'.implode(',', SiteAnnouncement::TYPES),
            'audiences' => 'required|array',
            'audiences.*' => 'boolean',
            'is_active' => 'boolean',
            'is_dismissible' => 'boolean',
            'min_view_seconds' => 'required|integer|min:0|max:60',
            'cta_label' => 'nullable|string|max:50',
            'cta_url' => 'nullable|url|max:500',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $a = SiteAnnouncement::findOrFail($id);
        $this->announcementId = $a->id;
        $this->title = $a->title;
        $this->body = $a->body;
        $this->type = $a->type;

        $this->audiences = [
            SiteAnnouncement::AUDIENCE_PUBLIC => in_array(SiteAnnouncement::AUDIENCE_PUBLIC, $a->audiences, true),
            SiteAnnouncement::AUDIENCE_MTEJA => in_array(SiteAnnouncement::AUDIENCE_MTEJA, $a->audiences, true),
            SiteAnnouncement::AUDIENCE_WINGA => in_array(SiteAnnouncement::AUDIENCE_WINGA, $a->audiences, true),
        ];

        $this->is_active = $a->is_active;
        $this->is_dismissible = $a->is_dismissible;
        $this->min_view_seconds = (string) $a->min_view_seconds;
        $this->cta_label = $a->cta_label ?? '';
        $this->cta_url = $a->cta_url ?? '';
        $this->starts_at = $a->starts_at?->copy()->setTimezone(self::DISPLAY_TZ)->format('Y-m-d\TH:i') ?? '';
        $this->ends_at = $a->ends_at?->copy()->setTimezone(self::DISPLAY_TZ)->format('Y-m-d\TH:i') ?? '';

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $selectedAudiences = array_keys(array_filter($validated['audiences']));

        if (count($selectedAudiences) === 0) {
            $this->addError('audiences', __('messages.admin_matangazo.errors.audience_required'));

            return;
        }

        $payload = [
            'title' => $validated['title'],
            'body' => $validated['body'],
            'type' => $validated['type'],
            'audiences' => array_values($selectedAudiences),
            'is_active' => $validated['is_active'],
            'is_dismissible' => $validated['is_dismissible'],
            'min_view_seconds' => (int) $validated['min_view_seconds'],
            'cta_label' => $validated['cta_label'] ?: null,
            'cta_url' => $validated['cta_url'] ?: null,
            'starts_at' => $this->parseLocalDatetime($validated['starts_at'] ?? null),
            'ends_at' => $this->parseLocalDatetime($validated['ends_at'] ?? null),
        ];

        if ($this->isEditing) {
            SiteAnnouncement::findOrFail($this->announcementId)->update($payload);
            $this->dispatch('toast', message: __('messages.admin_matangazo.updated'), type: 'success');
        } else {
            $payload['created_by'] = auth()->id();
            SiteAnnouncement::create($payload);
            $this->dispatch('toast', message: __('messages.admin_matangazo.created'), type: 'success');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $a = SiteAnnouncement::findOrFail($id);
        $a->update(['is_active' => ! $a->is_active]);
        $this->dispatch('toast', message: __('messages.admin_matangazo.toggled'), type: 'success');
    }

    public function delete(int $id): void
    {
        SiteAnnouncement::findOrFail($id)->delete();
        $this->dispatch('toast', message: __('messages.admin_matangazo.deleted'), type: 'success');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function parseLocalDatetime(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        return Carbon::parse($value, self::DISPLAY_TZ)->utc();
    }

    private function resetForm(): void
    {
        $this->announcementId = null;
        $this->title = '';
        $this->body = '';
        $this->type = 'info';
        $this->audiences = [
            SiteAnnouncement::AUDIENCE_PUBLIC => false,
            SiteAnnouncement::AUDIENCE_MTEJA => false,
            SiteAnnouncement::AUDIENCE_WINGA => false,
        ];
        $this->is_active = true;
        $this->is_dismissible = true;
        $this->min_view_seconds = '0';
        $this->cta_label = '';
        $this->cta_url = '';
        $this->starts_at = '';
        $this->ends_at = '';
        $this->resetValidation();
    }

    public function render()
    {
        $announcements = SiteAnnouncement::query()
            ->latest('id')
            ->paginate(10);

        return view('livewire.admin.matangazo', [
            'announcements' => $announcements,
        ])
            ->layout('layouts.admin')
            ->title(__('messages.admin_matangazo.title'));
    }
}
