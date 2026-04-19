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
            if (!Schema::hasColumn('payments', 'retry_count')) {
                $table->integer('retry_count')->default(0)->after('payout_status');
            }
            if (!Schema::hasColumn('payments', 'last_retry_at')) {
                $table->timestamp('last_retry_at')->nullable()->after('retry_count');
            }
            if (!Schema::hasColumn('payments', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->after('last_retry_at');
            }
        });

        Schema::table('withdrawal_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawal_requests', 'retry_count')) {
                $table->integer('retry_count')->default(0)->after('payout_status');
            }
            if (!Schema::hasColumn('withdrawal_requests', 'last_retry_at')) {
                $table->timestamp('last_retry_at')->nullable()->after('retry_count');
            }
            if (!Schema::hasColumn('withdrawal_requests', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('last_retry_at');
            }
            if (!Schema::hasColumn('withdrawal_requests', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->after('approved_at');
            }
            if (!Schema::hasColumn('withdrawal_requests', 'processed_by')) {
                $table->foreignId('processed_by')->nullable()->constrained('users')->after('approved_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['retry_count', 'last_retry_at', 'approved_by']);
        });

        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn(['retry_count', 'last_retry_at', 'approved_at', 'approved_by', 'processed_by']);
        });
    }
};
