<?php

use App\Models\Job;
use App\Services\OpenStreetMapGeocodingService;
use Illuminate\Support\Facades\Http;

test('geocode returns coordinates when nominatim responds', function () {
    config(['services.nominatim.enabled' => true]);

    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response([
            [
                'lat' => '-6.8235',
                'lon' => '39.2695',
            ],
        ], 200),
    ]);

    $svc = new OpenStreetMapGeocodingService;
    $out = $svc->geocode('Kinondoni, Dar es Salaam');

    expect($out)->not->toBeNull()
        ->and($out['latitude'])->toBe(-6.8235)
        ->and($out['longitude'])->toBe(39.2695);
});

test('fillJobCoordinatesIfMissing updates job when coords missing', function () {
    config(['services.nominatim.enabled' => true]);

    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response([
            ['lat' => '-3.37', 'lon' => '36.68'],
        ], 200),
    ]);

    $job = Job::factory()->create([
        'location' => 'Arusha',
        'latitude' => null,
        'longitude' => null,
    ]);

    app(OpenStreetMapGeocodingService::class)->fillJobCoordinatesIfMissing($job);

    expect($job->fresh()->latitude)->toBe(-3.37)
        ->and($job->fresh()->longitude)->toBe(36.68);
});
