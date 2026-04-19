<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\KaziListingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KaziController extends Controller
{
    public function __invoke(Request $request, KaziListingService $service): JsonResponse
    {
        $result = $service->list([
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'location' => $request->input('location'),
            'filter' => $request->input('filter', 'mpya'),
            'per_page' => (int) $request->input('per_page', 12),
        ]);

        return response()->json($result);
    }
}
