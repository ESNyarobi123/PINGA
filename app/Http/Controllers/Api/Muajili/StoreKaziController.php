<?php

namespace App\Http\Controllers\Api\Muajili;

use App\Http\Controllers\Controller;
use App\Jobs\TranslateJobPosting;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreKaziController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || (! $user->isMuajili() && ! $user->isAdmin())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'title' => 'required|string|min:5|max:200',
            'description' => 'required|string|min:20',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'budget_type' => 'nullable|in:fixed,hourly',
            'budget_min' => 'required|numeric|min:1000',
            'budget_max' => 'nullable|numeric|min:0',
            'urgency' => 'nullable|in:normal,urgent,very_urgent',
            'duration' => 'nullable|string|max:100',
        ]);

        $job = Job::create([
            'employer_id' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'location' => $validated['location'],
            'budget_type' => $validated['budget_type'] ?? 'fixed',
            'budget_min' => $validated['budget_min'],
            'budget_max' => $validated['budget_max'] ?? $validated['budget_min'],
            'urgency' => $validated['urgency'] ?? 'normal',
            'duration' => $validated['duration'] ?? null,
            'status' => 'open',
        ]);

        TranslateJobPosting::dispatch($job);

        return response()->json([
            'message' => 'Kazi imetumwa',
            'data' => [
                'id' => $job->id,
                'slug' => $job->slug,
                'title' => $job->title,
            ],
        ], 201);
    }
}
