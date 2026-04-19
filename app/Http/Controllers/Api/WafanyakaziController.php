<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WafanyakaziListingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WafanyakaziController extends Controller
{
    public function __invoke(Request $request, WafanyakaziListingService $service): JsonResponse
    {
        $result = $service->list([
            'search' => $request->input('search'),
            'skill' => $request->input('skill'),
            'location' => $request->input('location'),
            'filter' => $request->input('filter', 'mpya'),
            'per_page' => (int) $request->input('per_page', 12),
        ]);

        return response()->json($result);
    }
}
