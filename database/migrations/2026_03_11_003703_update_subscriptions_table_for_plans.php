<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Link to the plans table (nullable to not break existing rows)
            $table->foreignId('subscription_plan_id')
                ->nullable()
                ->after('user_id')
                ->constrained('subscription_plans')
                ->nullOnDelete();

            // Add 'pending' status for payments awaiting webhook confirmation
            // SQLite does not support ALTER TABLE for enums; recreate via raw SQL is complex.
            // Instead, add a plain string column and deprecate the old enum via a soft migration.
            $table->string('payment_status')->default('completed')->after('status');
            // payment_status: pending | completed | failed

            // Store the plan slug for quick lookup without joining
            $table->string('plan_slug')->nullable()->after('plan');

            // Notes column (admin use / system messages)
            $table->text('notes')->nullable()->after('payment_method');

            // Add payment_type column
            $table->string('payment_type')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['subscription_plan_id']);
            $table->dropColumn(['subscription_plan_id', 'payment_status', 'plan_slug', 'notes', 'payment_type']);
        });
    }
};
