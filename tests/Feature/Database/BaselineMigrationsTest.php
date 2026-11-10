<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

function migrationFileNames(): array
{
    return collect(glob(database_path('migrations/*.php')))
        ->map(fn (string $path): string => basename($path, '.php'))
        ->sort()
        ->values()
        ->all();
}

test('baseline records every existing migration when the migrations table is empty', function () {
    DB::table('migrations')->delete();

    $this->artisan('db:baseline', ['--force' => true])->assertSuccessful();

    $recorded = DB::table('migrations')->pluck('migration')->all();

    expect($recorded)->toHaveCount(count(migrationFileNames()))
        ->and($recorded)->toContain('0001_01_01_000000_create_users_table');
});

test('baseline leaves migrations newer than the cutoff pending', function () {
    DB::table('migrations')->delete();

    $this->artisan('db:baseline', ['--force' => true, '--before' => '2026-11-01'])
        ->expectsOutputToContain('php artisan migrate --force')
        ->assertSuccessful();

    $recorded = DB::table('migrations')->pluck('migration')->all();

    expect($recorded)->toContain('0001_01_01_000000_create_users_table')
        ->and($recorded)->toContain('2026_10_28_000001_add_service_request_id_to_conversations_table')
        ->and($recorded)->not->toContain('2026_11_10_061918_add_payout_error_to_withdrawal_requests_table');
});

test('baseline can baseline one specific migration with only', function () {
    DB::table('migrations')->delete();

    $target = '2026_11_10_061918_add_payout_error_to_withdrawal_requests_table';

    $this->artisan('db:baseline', ['--force' => true, '--only' => $target])->assertSuccessful();

    expect(DB::table('migrations')->pluck('migration')->all())->toBe([$target]);
});

test('baseline can skip a migration with except', function () {
    DB::table('migrations')->delete();

    $skipped = '2026_11_10_061918_add_payout_error_to_withdrawal_requests_table';

    $this->artisan('db:baseline', ['--force' => true, '--except' => $skipped])->assertSuccessful();

    expect(DB::table('migrations')->pluck('migration')->all())->not->toContain($skipped);
});

test('baseline dry run never writes to the database', function () {
    DB::table('migrations')->delete();

    $this->artisan('db:baseline', ['--dry-run' => true])->assertSuccessful();

    expect(DB::table('migrations')->count())->toBe(0);
});

test('baseline creates the migrations table when it is missing', function () {
    Schema::drop('migrations');

    $this->artisan('db:baseline', ['--force' => true])->assertSuccessful();

    expect(Schema::hasTable('migrations'))->toBeTrue()
        ->and(DB::table('migrations')->count())->toBeGreaterThan(0);
});

test('baseline is a no-op once everything is recorded', function () {
    $this->artisan('db:baseline', ['--force' => true])
        ->expectsOutputToContain('Hakuna cha kufanya')
        ->assertSuccessful();
});
