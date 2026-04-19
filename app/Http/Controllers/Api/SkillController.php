<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $skills = Skill::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return response()->json(['data' => $skills]);
    }
}
