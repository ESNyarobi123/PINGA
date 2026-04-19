<?php

use App\Livewire\Admin\Kazi as AdminKazi;
use App\Livewire\Mteja\KaziZangu;
use App\Livewire\Mteja\PostKazi;
use App\Livewire\Mteja\WingaProfile;
use App\Livewire\Winga\HudumaMaombi;
use App\Livewire\Winga\Subscription as SubscriptionComponent;
use App\Livewire\Winga\TombaOmbi;
use App\Livewire\Winga\WekaCode;
use App\Models\Category;
use App\Models\Job;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\ServiceRequest;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Services\SubscriptionService;
use Livewire\Livewire;

// ============================================================
// Phone Number Blocking (PostKazi)
// ============================================================
test('PostKazi blocks phone numbers in description', function () {
    $employer = User::factory()->create(['role' => 'mteja', 'onboarding_completed' => true]);

    Livewire::actingAs($employer)
        ->test(PostKazi::class)
        ->set('title', 'Mtunzaji wa nyumba')
        ->set('description', 'Piga simu 0712345678 kwa maelezo zaidi')
        ->set('category_id', 1)
        ->set('location', 'Dar es Salaam')
        ->set('budget_min', 5000)
        ->call('submit')
        ->assertHasErrors('description');
});

test('PostKazi allows valid description without phone numbers', function () {
    $employer = User::factory()->create(['role' => 'mteja', 'onboarding_completed' => true]);
    $category = Category::create(['name' => 'Nyumbani', 'slug' => 'nyumbani', 'icon' => '🏠']);

    Livewire::actingAs($employer)
        ->test(PostKazi::class)
        ->set('title', 'Mtunzaji wa nyumba')
        ->set('description', 'Natafuta mtu wa kutunza nyumba yangu kwa siku tano. Mahali: Kinondoni.')
        ->set('category_id', (string) $category->id)
        ->set('location', 'Dar es Salaam')
        ->set('budget_min', 5000)
        ->call('submit')
        ->assertHasNoErrors('description');
});

// ============================================================
// Admin Job Approval
// ============================================================
test('admin can approve a pending job', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employer = User::factory()->create(['role' => 'mteja']);
    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'is_approved' => false,
        'status' => 'open',
    ]);

    Livewire::actingAs($admin)
        ->test(AdminKazi::class)
        ->call('approveJob', $job->id);

    expect($job->fresh()->is_approved)->toBeTrue();
});

test('admin can reject a pending job', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $employer = User::factory()->create(['role' => 'mteja']);
    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'is_approved' => false,
        'status' => 'open',
    ]);

    Livewire::actingAs($admin)
        ->test(AdminKazi::class)
        ->call('rejectJob', $job->id);

    $fresh = $job->fresh();
    expect($fresh->is_approved)->toBeFalse();
    expect($fresh->status)->toBe('cancelled');
});

// ============================================================
// 12-Hour Code Hold Mechanism
// ============================================================
test('employer can hold completion code for 12 hours', function () {
    $employer = User::factory()->create(['role' => 'mteja', 'onboarding_completed' => true]);
    $worker = User::factory()->create(['role' => 'winga']);
    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'hired_worker_id' => $worker->id,
        'status' => 'in_progress',
    ]);

    Livewire::actingAs($employer)
        ->test(KaziZangu::class)
        ->call('holdCode', $job->id);

    $fresh = $job->fresh();
    expect($fresh->code_hold_until)->not->toBeNull();
    expect($fresh->code_hold_until->isFuture())->toBeTrue();
});

test('WekaCode blocks verification when code is on hold', function () {
    $worker = User::factory()->create(['role' => 'winga']);
    $job = Job::factory()->create([
        'hired_worker_id' => $worker->id,
        'status' => 'in_progress',
        'completion_code' => '123456',
        'code_hold_until' => now()->addHours(6),
    ]);

    Livewire::actingAs($worker)
        ->test(WekaCode::class)
        ->call('selectJob', $job->id)
        ->set('code', '123456')
        ->call('verify')
        ->assertSet('error', fn ($err) => str_contains($err, 'tathmini'));
});

// ============================================================
// Withdrawal Request
// ============================================================
test('worker can submit a withdrawal request', function () {
    $worker = User::factory()->create([
        'role' => 'mfanyakazi',
        'wallet_balance' => 50000,
    ]);

    Livewire::actingAs($worker)
        ->test(TombaOmbi::class)
        ->call('openForm')
        ->set('network', 'airtel')
        ->set('amount', '10000')
        ->set('phone', '0786000000')
        ->call('submit')
        ->assertSet('showForm', false);

    expect(WithdrawalRequest::where('user_id', $worker->id)->exists())->toBeTrue();
    expect((float) $worker->fresh()->wallet_balance)->toEqual(40000.0);
});

test('worker cannot withdraw more than wallet balance', function () {
    $worker = User::factory()->create([
        'role' => 'mfanyakazi',
        'wallet_balance' => 5000,
    ]);

    Livewire::actingAs($worker)
        ->test(TombaOmbi::class)
        ->call('openForm')
        ->set('network', 'airtel')
        ->set('amount', '10000')
        ->set('phone', '0786000000')
        ->call('submit')
        ->assertHasErrors('amount');
});

// ============================================================
// Home Page
// ============================================================
test('home page loads successfully', function () {
    $this->get(route('home'))->assertStatus(200);
});

// ============================================================
// Job Model Helpers
// ============================================================
test('Job containsPhoneNumber detects TZ phone numbers', function () {
    expect(Job::containsPhoneNumber('Namba yangu ni 0712345678'))->toBeTrue();
    expect(Job::containsPhoneNumber('Namba yangu ni +255712345678'))->toBeTrue();
    expect(Job::containsPhoneNumber('Hakuna namba hapa'))->toBeFalse();
});

test('Job sanitizePhoneNumbers removes phone numbers', function () {
    $result = Job::sanitizePhoneNumbers('Piga simu 0712345678 kwa maelezo');
    expect($result)->toContain('[NAMBA IMEFUTWA]');
    expect($result)->not->toContain('0712345678');
});

test('Job isOnCodeHold returns true when hold is active', function () {
    $job = Job::factory()->make(['code_hold_until' => now()->addHours(12)]);
    expect($job->isOnCodeHold())->toBeTrue();
});

test('Job isOnCodeHold returns false when hold has expired', function () {
    $job = Job::factory()->make(['code_hold_until' => now()->subHour()]);
    expect($job->isOnCodeHold())->toBeFalse();
});

// ============================================================
// Subscription Plans Seeding
// ============================================================
test('subscription plans are seeded correctly', function () {
    $this->artisan('db:seed', ['--class' => 'SubscriptionPlansSeeder'])->assertSuccessful();

    $msingi = SubscriptionPlan::where('slug', 'msingi')->first();
    $kawaida = SubscriptionPlan::where('slug', 'kawaida')->first();
    $bora = SubscriptionPlan::where('slug', 'bora')->first();

    expect($msingi)->not->toBeNull();
    expect($kawaida)->not->toBeNull();
    expect($bora)->not->toBeNull();

    expect($msingi->price)->toBe(5000);
    expect($msingi->duration_days)->toBe(30);
    expect($kawaida->price)->toBe(12000);
    expect($kawaida->duration_days)->toBe(90);
    expect($bora->price)->toBe(20000);
    expect($bora->duration_days)->toBe(180);
    expect((bool) $bora->is_recommended)->toBeFalse();
    expect((bool) $kawaida->is_recommended)->toBeTrue();
});

// ============================================================
// SubscriptionService — wallet payment activation
// ============================================================
test('worker can activate subscription via wallet', function () {
    $this->artisan('db:seed', ['--class' => 'SubscriptionPlansSeeder']);
    $worker = User::factory()->create(['role' => 'winga', 'wallet_balance' => 20000]);
    $plan = SubscriptionPlan::where('slug', 'msingi')->firstOrFail();

    $service = app(SubscriptionService::class);
    $sub = $service->payFromWallet($worker, $plan);

    expect($sub->status)->toBe('active');
    expect($sub->plan_slug)->toBe('msingi');
    expect($sub->payment_status)->toBe('completed');
    expect($sub->expires_at->isFuture())->toBeTrue();

    // Wallet was debited by plan price (5000)
    expect((float) $worker->fresh()->wallet_balance)->toBe(15000.0);
});

test('worker cannot activate subscription with insufficient wallet balance', function () {
    $this->artisan('db:seed', ['--class' => 'SubscriptionPlansSeeder']);
    $worker = User::factory()->create(['role' => 'winga', 'wallet_balance' => 1000]);
    $plan = SubscriptionPlan::where('slug', 'msingi')->firstOrFail();

    Livewire::actingAs($worker)
        ->test(SubscriptionComponent::class)
        ->call('selectPlan', $plan->id)
        ->set('paymentMethod', 'wallet')
        ->call('pay')
        ->assertDispatched('toast');

    // No subscription was created
    expect(Subscription::where('user_id', $worker->id)->where('status', 'active')->exists())->toBeFalse();
    // Wallet unchanged
    expect((float) $worker->fresh()->wallet_balance)->toBe(1000.0);
});

// ============================================================
// SubscriptionService — expiry
// ============================================================
test('subscriptions:expire command marks expired subscriptions', function () {
    $this->artisan('db:seed', ['--class' => 'SubscriptionPlansSeeder']);
    $worker = User::factory()->create(['role' => 'winga']);
    $plan = SubscriptionPlan::where('slug', 'msingi')->firstOrFail();

    // Create a subscription that expired yesterday
    Subscription::create([
        'user_id' => $worker->id,
        'subscription_plan_id' => $plan->id,
        'plan' => 'basic',
        'plan_slug' => 'msingi',
        'amount_paid' => $plan->price,
        'starts_at' => now()->subDays(31),
        'expires_at' => now()->subDay(),
        'status' => 'active',
        'payment_status' => 'completed',
        'payment_reference' => 'test-expire-'.uniqid(),
        'payment_method' => 'wallet',
    ]);

    $this->artisan('subscriptions:expire')->assertSuccessful();

    expect(
        Subscription::where('user_id', $worker->id)->where('status', 'expired')->exists()
    )->toBeTrue();
});

// ============================================================
// SubscriptionPlan model helpers
// ============================================================
test('SubscriptionPlan durationLabel returns correct label', function () {
    $this->artisan('db:seed', ['--class' => 'SubscriptionPlansSeeder']);
    $plan = SubscriptionPlan::where('slug', 'msingi')->firstOrFail();
    expect($plan->durationLabel())->toBe('Mwezi 1');

    $plan2 = SubscriptionPlan::where('slug', 'kawaida')->firstOrFail();
    expect($plan2->durationLabel())->toBe('Miezi 3');

    $plan3 = SubscriptionPlan::where('slug', 'bora')->firstOrFail();
    expect($plan3->durationLabel())->toBe('Miezi 6');
});

// ============================================================
// Snippe Webhook — subscription activation
// ============================================================
test('snippe webhook activates pending subscription on success', function () {
    $this->artisan('db:seed', ['--class' => 'SubscriptionPlansSeeder']);
    $worker = User::factory()->create(['role' => 'winga']);
    $plan = SubscriptionPlan::where('slug', 'kawaida')->firstOrFail();
    $ref = 'sub-webhook-test-'.uniqid();

    // Create pending subscription
    $pending = Subscription::create([
        'user_id' => $worker->id,
        'subscription_plan_id' => $plan->id,
        'plan' => 'basic',
        'plan_slug' => 'kawaida',
        'amount_paid' => $plan->price,
        'starts_at' => null,
        'expires_at' => null,
        'status' => 'cancelled',
        'payment_status' => 'pending',
        'payment_reference' => $ref,
        'payment_method' => 'snippe',
    ]);

    $webhookPayload = [
        'type' => 'payment.completed',
        'data' => [
            'status' => 'completed',
            'reference' => $ref,
            'amount' => ['value' => $plan->price],
            'metadata' => [
                'user_id' => (string) $worker->id,
                'payment_type' => 'subscription',
                'subscription_id' => $pending->id,
            ],
        ],
    ];

    $this->postJson('/api/webhooks/snippe', $webhookPayload)
        ->assertJson(['status' => 'success']);

    // Pending record deleted, new active one created
    expect(Subscription::find($pending->id))->toBeNull();
    expect(
        Subscription::where('user_id', $worker->id)
            ->where('status', 'active')
            ->where('plan_slug', 'kawaida')
            ->exists()
    )->toBeTrue();
});

test('mteja can request a winga service and winga can accept it', function () {
    $winga = User::factory()->create([
        'role' => 'winga',
        'onboarding_completed' => true,
    ]);
    $mteja = User::factory()->create([
        'role' => 'mteja',
        'onboarding_completed' => true,
    ]);

    $category = Category::query()->first();
    if ($category === null) {
        $category = Category::create([
            'name' => 'Test Cat',
            'slug' => 'test-cat-'.uniqid(),
            'is_active' => true,
        ]);
    }

    $service = Service::create([
        'user_id' => $winga->id,
        'category_id' => $category->id,
        'title' => 'Uandishi wa CV',
        'description' => str_repeat('Maelezo ya huduma ya CV. ', 5),
        'price' => 25000,
        'price_type' => 'fixed',
        'status' => 'active',
        'images' => null,
    ]);

    $package = ServicePackage::create([
        'service_id' => $service->id,
        'title' => 'Standard',
        'description' => null,
        'price' => 25000,
        'sort_order' => 0,
    ]);

    Livewire::actingAs($mteja)
        ->test(WingaProfile::class, ['id' => $winga->id])
        ->call('openRequestModal', $service->id)
        ->set('requestPackageId', $package->id)
        ->set('requestMessage', 'Nahitaji CV ya kitaaluma.')
        ->call('submitServiceRequest')
        ->assertSet('showRequestModal', false);

    expect(ServiceRequest::count())->toBe(1)
        ->and(ServiceRequest::first()->status)->toBe('pending');

    Livewire::actingAs($winga)
        ->test(HudumaMaombi::class)
        ->call('accept', ServiceRequest::first()->id);

    expect(ServiceRequest::first()->fresh()->status)->toBe('accepted');
});
