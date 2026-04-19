<?php

namespace App\Http\Controllers\Api\Muajili;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KaziZanguController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || (! $user->isMuajili() && ! $user->isAdmin())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $query = Job::where('employer_id', $user->id)->latest();

        $filter = $request->input('filter', 'all');
        if ($filter !== 'all' && in_array($filter, ['open', 'in_progress', 'completed', 'cancelled', 'draft'], true)) {
            $query->where('status', $filter);
        }

        $jobs = $query->paginate((int) $request->input('per_page', 10));

        return response()->json([
            'data' => $jobs->getCollection()->map(fn ($job) => [
                'id' => $job->id,
                'title' => $job->title,
                'slug' => $job->slug,
                'location' => $job->location,
                'status' => $job->status,
                'budget_min' => $job->budget_min,
                'budget_max' => $job->budget_max,
                'created_at_human' => $job->created_at->diffForHumans(),
                'applications_count' => $job->applications_count ?? 0,
            ]),
            'meta' => [
                'current_page' => $jobs->currentPage(),
                'last_page' => $jobs->lastPage(),
                'per_page' => $jobs->perPage(),
                'total' => $jobs->total(),
            ],
        ]);
    }
}
