<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('subscription_payments')) {
            Schema::create('subscription_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('subscription_plan_id')->nullable()->constrained('subscription_plans')->nullOnDelete();
                $table->string('plan_slug')->nullable();
                $table->decimal('amount', 12, 2);
                $table->string('payment_reference')->nullable()->index();
                $table->string('provider_reference')->nullable();
                $table->string('payment_method')->default('wallet');
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->index('plan_slug');
                $table->index('paid_at');
            });
        }

        $this->backfill();
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }

    /**
     * Seed the ledger from the existing subscriptions table so historical revenue is preserved.
     */
    private function backfill(): void
    {
        if (! Schema::hasTable('subscriptions') || DB::table('subscription_payments')->exists()) {
            return;
        }

        DB::table('subscriptions')
            ->where('payment_status', 'completed')
            ->where('amount_paid', '>', 0)
            ->orderBy('id')
            ->chunk(200, function ($subscriptions) {
                $rows = [];

                foreach ($subscriptions as $subscription) {
                    $rows[] = [
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'subscription_plan_id' => $subscription->subscription_plan_id,
                        'plan_slug' => $subscription->plan_slug,
                        'amount' => $subscription->amount_paid,
                        'payment_reference' => $subscription->payment_reference,
                        'provider_reference' => $subscription->provider_reference ?? null,
                        'payment_method' => $subscription->payment_method ?? 'wallet',
                        'paid_at' => $subscription->starts_at ?? $subscription->created_at,
                        'created_at' => $subscription->created_at,
                        'updated_at' => $subscription->updated_at,
                    ];
                }

                if ($rows !== []) {
                    DB::table('subscription_payments')->insert($rows);
                }
            });
    }
};
