<?php

use App\Livewire\Admin\Malipo;
use App\Models\Job;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\User;
use Carbon\CarbonInterface;
use Livewire\Livewire;

function revenueAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'onboarding_completed' => true]);
}

function releasedPaymentForRevenue(float $amount, float $fee, CarbonInterface $releasedAt, ?CarbonInterface $createdAt = null): Payment
{
    $payment = Payment::create([
        'employer_id' => User::factory()->create(['role' => 'mteja'])->id,
        'worker_id' => User::factory()->create(['role' => 'winga'])->id,
        'amount' => $amount,
        'platform_fee' => $fee,
        'worker_amount' => $amount - $fee,
        'status' => 'released',
        'payment_method' => 'wallet',
        'escrow_released_at' => $releasedAt,
    ]);

    if ($createdAt) {
        $payment->created_at = $createdAt;
        $payment->updated_at = $createdAt;
        $payment->save();
    }

    return $payment;
}

function activeSubscriptionWithLedger(User $winga, int $amount = 5000): Subscription
{
    $plan = SubscriptionPlan::query()->firstOrCreate(
        ['slug' => 'msingi'],
        [
            'name' => 'Msingi',
            'name_en' => 'Basic',
            'price' => $amount,
            'duration_days' => 30,
            'features' => [],
            'is_active' => true,
            'sort_order' => 1,
        ]
    );

    $subscription = Subscription::create([
        'user_id' => $winga->id,
        'subscription_plan_id' => $plan->id,
        'plan' => 'basic',
        'plan_slug' => $plan->slug,
        'amount_paid' => $amount,
        'starts_at' => now(),
        'expires_at' => now()->addDays(30),
        'status' => 'active',
        'payment_status' => 'completed',
        'payment_method' => 'wallet',
        'payment_reference' => 'wallet-sub-'.$winga->id.'-'.now()->timestamp,
    ]);

    SubscriptionPayment::create([
        'subscription_id' => $subscription->id,
        'user_id' => $winga->id,
        'subscription_plan_id' => $plan->id,
        'plan_slug' => $plan->slug,
        'amount' => $amount,
        'payment_reference' => $subscription->payment_reference,
        'payment_method' => 'wallet',
        'paid_at' => now(),
    ]);

    return $subscription;
}

test('monthly revenue is filtered by escrow release date, not creation date', function () {
    $admin = revenueAdmin();

    releasedPaymentForRevenue(10000, 1000, now());
    // Created this month but released two months ago → must not count this month.
    releasedPaymentForRevenue(20000, 2000, now()->subMonths(2), now());

    $malipo = Livewire::actingAs($admin)->test(Malipo::class)->instance();

    expect($malipo->monthlyRevenue)->toBe(1000.0);
    expect($malipo->todayRevenue)->toBe(1000.0);
    expect($malipo->commissionRevenue)->toBe(3000.0);
});

test('subscription payments are counted as full platform revenue alongside commission', function () {
    $admin = revenueAdmin();
    $winga = User::factory()->create(['role' => 'winga']);

    releasedPaymentForRevenue(10000, 1000, now());
    activeSubscriptionWithLedger($winga, 5000);

    $malipo = Livewire::actingAs($admin)->test(Malipo::class)->instance();

    expect($malipo->commissionRevenue)->toBe(1000.0);
    expect($malipo->subscriptionRevenue)->toBe(5000.0);
    expect($malipo->totalRevenue)->toBe(6000.0);
    expect($malipo->monthlyRevenue)->toBe(6000.0);
});

test('releasing escrow recomputes the fee, credits the worker and stamps the release time', function () {
    $admin = revenueAdmin();
    $worker = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $employer = User::factory()->create(['role' => 'mteja']);

    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'hired_worker_id' => $worker->id,
        'status' => 'in_progress',
    ]);

    $payment = Payment::create([
        'job_id' => $job->id,
        'employer_id' => $employer->id,
        'worker_id' => $worker->id,
        'amount' => 10000,
        'platform_fee' => 1200,
        'worker_amount' => 8800,
        'status' => 'escrowed',
        'payment_method' => 'wallet',
    ]);

    Livewire::actingAs($admin)
        ->test(Malipo::class)
        ->call('releaseEscrow', $payment->id);

    $payment->refresh();

    expect($payment->status)->toBe('released');
    expect((float) $payment->platform_fee)->toBe(1000.0);
    expect((float) $payment->worker_amount)->toBe(9000.0);
    expect($payment->escrow_released_at)->not->toBeNull();
    expect((float) $worker->fresh()->wallet_balance)->toBe(9000.0);

    expect(Transaction::where('payment_id', $payment->id)->where('type', 'credit')->count())->toBe(1);
});

test('releasing the same escrow twice does not double credit the worker', function () {
    $admin = revenueAdmin();
    $worker = User::factory()->create(['role' => 'winga', 'wallet_balance' => 0]);
    $employer = User::factory()->create(['role' => 'mteja']);

    $job = Job::factory()->create([
        'employer_id' => $employer->id,
        'hired_worker_id' => $worker->id,
        'status' => 'in_progress',
    ]);

    $payment = Payment::create([
        'job_id' => $job->id,
        'employer_id' => $employer->id,
        'worker_id' => $worker->id,
        'amount' => 5000,
        'platform_fee' => 500,
        'worker_amount' => 4500,
        'status' => 'escrowed',
        'payment_method' => 'wallet',
    ]);

    $component = Livewire::actingAs($admin)->test(Malipo::class);
    $component->call('releaseEscrow', $payment->id);
    $component->call('releaseEscrow', $payment->id);

    expect((float) $worker->fresh()->wallet_balance)->toBe(4500.0);
    expect(Transaction::where('payment_id', $payment->id)->where('type', 'credit')->count())->toBe(1);
});
