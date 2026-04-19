<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MuajiliDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MuajiliDashboardController extends Controller
{
    public function __invoke(Request $request, MuajiliDashboardService $service): JsonResponse
    {
        $user = $request->user();
        if (! $user || (! $user->isMuajili() && ! $user->isAdmin())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $service->data($user);
        $data['recent_jobs'] = $data['recent_jobs']->map(fn ($job) => [
            'id' => $job->id,
            'title' => $job->title,
            'location' => $job->location,
            'status' => $job->status,
            'budget_min' => $job->budget_min,
            'created_at_human' => $job->created_at->diffForHumans(),
        ])->values();

        return response()->json($data);
    }
}
