<?php

use App\Services\LegacyWordPressUserImporter;
use Carbon\Carbon;

test('detects wordpress administrator capabilities', function () {
    expect(LegacyWordPressUserImporter::isWordPressAdministrator(null))->toBeFalse();
    expect(LegacyWordPressUserImporter::isWordPressAdministrator(''))->toBeFalse();
    expect(LegacyWordPressUserImporter::isWordPressAdministrator('a:0:{}'))->toBeFalse();

    $caps = serialize(['administrator' => true]);
    expect(LegacyWordPressUserImporter::isWordPressAdministrator($caps))->toBeTrue();

    $subscriber = serialize(['subscriber' => true]);
    expect(LegacyWordPressUserImporter::isWordPressAdministrator($subscriber))->toBeFalse();
});

test('resolves winga when hp_vendor meta is set', function () {
    expect(LegacyWordPressUserImporter::resolveAppRole('57'))->toBe('winga');
    expect(LegacyWordPressUserImporter::resolveAppRole(' 12 '))->toBe('winga');
    expect(LegacyWordPressUserImporter::resolveAppRole(null))->toBe('mteja');
    expect(LegacyWordPressUserImporter::resolveAppRole(''))->toBe('mteja');
    expect(LegacyWordPressUserImporter::resolveAppRole('0'))->toBe('mteja');
});

test('normalizes phone digits', function () {
    expect(LegacyWordPressUserImporter::normalizePhone('+255 712 345 678'))->toBe('255712345678');
    expect(LegacyWordPressUserImporter::normalizePhone(null))->toBeNull();
});

test('parses wordpress registration timestamps', function () {
    expect(LegacyWordPressUserImporter::parseWpDate('2024-11-17 18:21:23'))
        ->toBeInstanceOf(Carbon::class);
    expect(LegacyWordPressUserImporter::parseWpDate('0000-00-00 00:00:00'))->toBeNull();
    expect(LegacyWordPressUserImporter::parseWpDate(null))->toBeNull();
});

test('resolves display name from meta fallbacks', function () {
    $wp = (object) [
        'display_name' => '',
        'user_login' => 'login_only',
    ];
    $meta = collect([
        (object) ['meta_key' => 'first_name', 'meta_value' => 'Jane'],
        (object) ['meta_key' => 'last_name', 'meta_value' => 'Doe'],
    ])->keyBy('meta_key');

    expect(LegacyWordPressUserImporter::resolveDisplayName($wp, $meta))->toBe('Jane Doe');

    $wp2 = (object) ['display_name' => 'Shown', 'user_login' => 'x'];
    expect(LegacyWordPressUserImporter::resolveDisplayName($wp2, collect()))->toBe('Shown');
});
