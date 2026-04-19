<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Geocodes free-text locations using OpenStreetMap Nominatim.
 *
 * @see https://operations.osmfoundation.org/policies/nominatim/
 */
class OpenStreetMapGeocodingService
{
    public function geocode(?string $location): ?array
    {
        if (! config('services.nominatim.enabled', true)) {
            return null;
        }

        $location = $location !== null ? trim($location) : '';
        if ($location === '') {
            return null;
        }

        $query = $location.', Tanzania';

        try {
            $response = Http::withHeaders([
                'User-Agent' => config('services.nominatim.user_agent'),
                'Accept-Language' => 'sw,en',
            ])
                ->timeout((int) config('services.nominatim.timeout', 12))
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 1,
                    'addressdetails' => 0,
                ]);
        } catch (\Throwable $e) {
            Log::warning('nominatim_geocode_failed', ['message' => $e->getMessage(), 'location' => $location]);

            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $row = $response->json()[0] ?? null;
        if (! is_array($row) || ! isset($row['lat'], $row['lon'])) {
            return null;
        }

        return [
            'latitude' => (float) $row['lat'],
            'longitude' => (float) $row['lon'],
        ];
    }

    public function fillJobCoordinatesIfMissing(Job $job): void
    {
        if ($job->latitude !== null && $job->longitude !== null) {
            return;
        }

        $coords = $this->geocode($job->location);
        if ($coords === null) {
            return;
        }

        $job->forceFill([
            'latitude' => $coords['latitude'],
            'longitude' => $coords['longitude'],
        ])->saveQuietly();
    }
}
