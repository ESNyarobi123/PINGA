<?php

use App\Models\User;

describe('Admin Access Control', function () {
    test('guest users cannot access admin dashboard', function () {
        $response = $this->get(route('admin.dashboard'));
        
        $response->assertRedirect(route('login'));
    });

    test('mteja users cannot access admin dashboard', function () {
        $mteja = User::factory()->create(['role' => 'mteja']);
        
        $response = $this->actingAs($mteja)->get(route('admin.dashboard'));
        
        $response->assertStatus(403);
    });

    test('winga users cannot access admin dashboard', function () {
        $winga = User::factory()->create(['role' => 'winga']);
        
        $response = $this->actingAs($winga)->get(route('admin.dashboard'));
        
        $response->assertStatus(403);
    });

    test('admin users can access admin dashboard', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        
        $response->assertStatus(200);
    });

    test('non-admin cannot access watumiaji page', function () {
        $mteja = User::factory()->create(['role' => 'mteja']);
        
        $response = $this->actingAs($mteja)->get(route('admin.watumiaji'));
        
        $response->assertStatus(403);
    });

    test('non-admin cannot access kazi management', function () {
        $winga = User::factory()->create(['role' => 'winga']);
        
        $response = $this->actingAs($winga)->get(route('admin.kazi'));
        
        $response->assertStatus(403);
    });

    test('non-admin cannot access malipo page', function () {
        $mteja = User::factory()->create(['role' => 'mteja']);
        
        $response = $this->actingAs($mteja)->get(route('admin.malipo'));
        
        $response->assertStatus(403);
    });

    test('non-admin cannot access migogoro page', function () {
        $winga = User::factory()->create(['role' => 'winga']);
        
        $response = $this->actingAs($winga)->get(route('admin.migogoro'));
        
        $response->assertStatus(403);
    });

    test('non-admin cannot access kategoria page', function () {
        $mteja = User::factory()->create(['role' => 'mteja']);
        
        $response = $this->actingAs($mteja)->get(route('admin.kategoria'));
        
        $response->assertStatus(403);
    });

    test('non-admin cannot access settings page', function () {
        $winga = User::factory()->create(['role' => 'winga']);
        
        $response = $this->actingAs($winga)->get(route('admin.settings'));
        
        $response->assertStatus(403);
    });

    test('non-admin cannot access audit logs', function () {
        $mteja = User::factory()->create(['role' => 'mteja']);
        
        $response = $this->actingAs($mteja)->get(route('admin.audit-logs'));
        
        $response->assertStatus(403);
    });

    test('non-admin cannot access subscriptions page', function () {
        $winga = User::factory()->create(['role' => 'winga']);
        
        $response = $this->actingAs($winga)->get(route('admin.subscriptions'));
        
        $response->assertStatus(403);
    });

    test('non-admin cannot access maombi kutoa page', function () {
        $mteja = User::factory()->create(['role' => 'mteja']);
        
        $response = $this->actingAs($mteja)->get(route('admin.maombi-kutoa'));
        
        $response->assertStatus(403);
    });

    test('non-admin cannot access mazungumzo page', function () {
        $winga = User::factory()->create(['role' => 'winga']);
        
        $response = $this->actingAs($winga)->get(route('admin.mazungumzo'));
        
        $response->assertStatus(403);
    });
});

describe('Admin Route Protection', function () {
    test('all admin routes require authentication', function () {
        $adminRoutes = [
            'admin.dashboard',
            'admin.watumiaji',
            'admin.kazi',
            'admin.malipo',
            'admin.migogoro',
            'admin.kategoria',
            'admin.mazungumzo',
            'admin.maombi-kutoa',
            'admin.subscriptions',
            'admin.settings',
            'admin.audit-logs',
        ];

        foreach ($adminRoutes as $routeName) {
            $response = $this->get(route($routeName));
            $response->assertRedirect(route('login'));
        }
    });

    test('all admin routes require admin role', function () {
        $mteja = User::factory()->create(['role' => 'mteja']);
        $winga = User::factory()->create(['role' => 'winga']);

        $adminRoutes = [
            'admin.dashboard',
            'admin.watumiaji',
            'admin.kazi',
            'admin.malipo',
            'admin.migogoro',
            'admin.kategoria',
        ];

        foreach ($adminRoutes as $routeName) {
            $this->actingAs($mteja)->get(route($routeName))->assertStatus(403);
            $this->actingAs($winga)->get(route($routeName))->assertStatus(403);
        }
    });
});

describe('CSRF Protection', function () {
    test('admin forms have CSRF protection', function () {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        
        $response->assertStatus(200);
        expect($response->getContent())->toContain('csrf');
    });
});
