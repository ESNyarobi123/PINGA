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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payout_reference')) {
                $table->string('payout_reference')->nullable()->after('payment_reference');
            }
            if (!Schema::hasColumn('payments', 'payout_status')) {
                $table->enum('payout_status', ['pending', 'processing', 'completed', 'failed'])
                    ->default('pending')
                    ->after('payout_reference');
            }
        });

        Schema::table('withdrawal_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawal_requests', 'payout_reference')) {
                $table->string('payout_reference')->nullable()->after('admin_note');
            }
            if (!Schema::hasColumn('withdrawal_requests', 'payout_status')) {
                $table->enum('payout_status', ['pending', 'processing', 'completed', 'failed'])
                    ->default('pending')
                    ->after('payout_reference');
            }
            if (!Schema::hasColumn('withdrawal_requests', 'network')) {
                $table->string('network')->nullable()->after('method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payout_reference', 'payout_status']);
        });

        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn(['payout_reference', 'payout_status', 'network']);
        });
    }
};
