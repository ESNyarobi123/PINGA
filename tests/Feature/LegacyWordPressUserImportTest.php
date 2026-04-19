<?php

use App\Models\User;
use App\Services\LegacyWordPressUserImporter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::query()->firstOrCreate(['name' => 'winga', 'guard_name' => 'web']);
    Role::query()->firstOrCreate(['name' => 'mteja', 'guard_name' => 'web']);

    config([
        'database.connections.legacy_wp_testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ],
    ]);
    DB::purge('legacy_wp_testing');

    $legacy = DB::connection('legacy_wp_testing');
    $legacy->getSchemaBuilder()->create('wp_users', function ($table) {
        $table->unsignedBigInteger('ID')->primary();
        $table->string('user_login');
        $table->string('user_pass')->default('');
        $table->string('user_nicename')->nullable();
        $table->string('user_email');
        $table->string('user_url')->nullable();
        $table->string('user_registered')->nullable();
        $table->string('user_activation_key')->nullable();
        $table->integer('user_status')->default(0);
        $table->string('display_name')->nullable();
    });
    $legacy->getSchemaBuilder()->create('wp_usermeta', function ($table) {
        $table->increments('umeta_id');
        $table->unsignedBigInteger('user_id');
        $table->string('meta_key');
        $table->longText('meta_value')->nullable();
    });
});

afterEach(function () {
    Schema::connection('legacy_wp_testing')->dropIfExists('wp_usermeta');
    Schema::connection('legacy_wp_testing')->dropIfExists('wp_users');
    DB::purge('legacy_wp_testing');
});

test('imports subscriber as mteja with legacy id', function () {
    $legacy = DB::connection('legacy_wp_testing');
    $legacy->table('wp_users')->insert([
        'ID' => 100,
        'user_login' => 'buyer',
        'user_email' => 'buyer@example.com',
        'user_registered' => '2025-01-01 10:00:00',
        'user_status' => 0,
        'display_name' => 'Buyer One',
    ]);
    $legacy->table('wp_usermeta')->insert([
        ['user_id' => 100, 'meta_key' => 'wp_capabilities', 'meta_value' => serialize(['customer' => true])],
    ]);

    $stats = app(LegacyWordPressUserImporter::class)->import($legacy, dryRun: false);

    expect($stats['created'])->toBe(1)
        ->and($stats['skipped'])->toBe(0);

    $user = User::query()->where('legacy_wp_user_id', 100)->first();
    expect($user)->not->toBeNull()
        ->and($user->email)->toBe('buyer@example.com')
        ->and($user->role)->toBe('mteja')
        ->and($user->hasRole('mteja'))->toBeTrue();
});

test('imports hp_vendor as winga', function () {
    $legacy = DB::connection('legacy_wp_testing');
    $legacy->table('wp_users')->insert([
        'ID' => 200,
        'user_login' => 'vendor',
        'user_email' => 'vendor@example.com',
        'user_registered' => '2025-01-02 10:00:00',
        'user_status' => 0,
        'display_name' => 'Vendor One',
    ]);
    $legacy->table('wp_usermeta')->insert([
        ['user_id' => 200, 'meta_key' => 'wp_capabilities', 'meta_value' => serialize(['subscriber' => true])],
        ['user_id' => 200, 'meta_key' => 'hp_vendor', 'meta_value' => '99'],
    ]);

    $stats = app(LegacyWordPressUserImporter::class)->import($legacy);

    expect($stats['created'])->toBe(1);
    $user = User::query()->where('legacy_wp_user_id', 200)->first();
    expect($user->role)->toBe('winga')
        ->and($user->hasRole('winga'))->toBeTrue();
});

test('skips wordpress administrators', function () {
    $legacy = DB::connection('legacy_wp_testing');
    $legacy->table('wp_users')->insert([
        'ID' => 300,
        'user_login' => 'admin',
        'user_email' => 'admin@example.com',
        'user_registered' => '2025-01-03 10:00:00',
        'user_status' => 0,
        'display_name' => 'Admin',
    ]);
    $legacy->table('wp_usermeta')->insert([
        ['user_id' => 300, 'meta_key' => 'wp_capabilities', 'meta_value' => serialize(['administrator' => true])],
    ]);

    $stats = app(LegacyWordPressUserImporter::class)->import($legacy);

    expect($stats['skipped'])->toBe(1)
        ->and($stats['created'])->toBe(0);
    expect(User::query()->where('email', 'admin@example.com')->exists())->toBeFalse();
});
