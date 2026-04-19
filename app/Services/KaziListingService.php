<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Support\Collection;

class KaziListingService
{
    /**
     * @param  array{search?: string, category?: string, location?: string, filter?: string, per_page?: int}  $params
     * @return array{data: array<int, array<string, mixed>>, meta: array{current_page: int, last_page: int, per_page: int, total: int}}
     */
    public function list(array $params = []): array
    {
        $query = Job::query()
            ->where('status', 'open')
            ->where('is_approved', true)
            ->withCount('applications')
            ->with(['employer', 'category', 'skills']);

        if (! empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "{$search}%")
                    ->orWhere('description', 'like', "{$search}%")
                    ->orWhereHas('skills', fn ($q) => $q->where('name', 'like', "{$search}%"));
            });
        }

        if (! empty($params['category'])) {
            $category = $params['category'];
            $query->whereHas('category', fn ($q) => $q->where('slug', $category)->orWhere('id', $category));
        }

        if (! empty($params['location'])) {
            $query->where('location', 'like', '%'.$params['location'].'%');
        }

        if (! empty($params['skill'])) {
            $skill = $params['skill'];
            $query->whereHas('skills', fn ($q) => $q->where('slug', $skill)->orWhere('name', $skill));
        }

        $filter = $params['filter'] ?? 'mpya';
        match ($filter) {
            'bei_kubwa' => $query->orderByDesc('budget_max'),
            'haraka' => $query->where('urgency', '!=', 'normal')->orderByRaw("FIELD(urgency, 'very_urgent', 'urgent')"),
            'karibu' => $query->orderByRaw('(latitude IS NOT NULL AND longitude IS NOT NULL) DESC'),
            default => $query->orderByDesc('created_at'),
        };

        $perPage = (int) ($params['per_page'] ?? 12);
        $paginator = $query->paginate($perPage);

        return [
            'data' => $this->mapCollection($paginator->getCollection()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function mapCollection(Collection $jobs): array
    {
        return $jobs->map(function (Job $job) {
            $employer = $job->employer;
            $priceType = $job->budget_type === 'hourly' ? __('messages.search_jobs.per_hour') : __('messages.search_jobs.fixed_price');
            $price = $job->budget_max
                ? 'TZS '.number_format($job->budget_min).'–'.number_format($job->budget_max).($job->budget_type === 'hourly' ? ' /hr' : '')
                : __('messages.search_jobs.under').' TZS '.number_format($job->budget_min ?? 0);

            $applicationsCount = (int) $job->applications_count;

            return [
                'id' => $job->id,
                'slug' => $job->slug,
                'title' => $job->getLocalizedTitle(),
                'description' => $job->getLocalizedDescription(),
                'price_type' => $priceType,
                'price' => $price,
                'posted_at_human' => $job->created_at->diffForHumans(),
                'applications_count' => $applicationsCount,
                'duration' => $job->duration,
                'client_name' => $employer?->name ?? '—',
                'client_avatar_url' => $employer && $employer->avatar
                    ? asset('storage/'.$employer->avatar)
                    : 'https://ui-avatars.com/api/?name='.urlencode($employer->name ?? 'U').'&background=0d9488&color=fff&size=64',
                'location' => $job->location ?? '—',
                'urgency' => $job->urgency,
                'tags' => $job->skills->pluck('name')->all(),
            ];
        })->all();
    }
}
