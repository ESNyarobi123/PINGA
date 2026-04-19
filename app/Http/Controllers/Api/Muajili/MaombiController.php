<?php

namespace App\Http\Controllers\Api\Muajili;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaombiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || (! $user->isMuajili() && ! $user->isAdmin())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $jobIds = Job::where('employer_id', $user->id)->pluck('id');

        $applications = Application::query()
            ->whereIn('job_id', $jobIds)
            ->with(['job:id,title,slug,status', 'worker:id,name,avatar,phone'])
            ->latest()
            ->paginate((int) $request->input('per_page', 15));

        return response()->json([
            'data' => $applications->getCollection()->map(fn ($app) => [
                'id' => $app->id,
                'job_id' => $app->job_id,
                'job_title' => $app->job?->title,
                'job_slug' => $app->job?->slug,
                'worker_name' => $app->worker?->name,
                'worker_avatar' => $app->worker && $app->worker->avatar
                    ? asset('storage/'.$app->worker->avatar)
                    : 'https://ui-avatars.com/api/?name='.urlencode($app->worker?->name ?? 'W').'&background=0d9488&color=fff&size=64',
                'cover_letter' => $app->cover_letter,
                'proposed_budget' => $app->proposed_budget,
                'proposed_duration' => $app->proposed_duration,
                'status' => $app->status,
                'created_at_human' => $app->created_at->diffForHumans(),
            ]),
            'meta' => [
                'current_page' => $applications->currentPage(),
                'last_page' => $applications->lastPage(),
                'per_page' => $applications->perPage(),
                'total' => $applications->total(),
            ],
        ]);
    }
}
