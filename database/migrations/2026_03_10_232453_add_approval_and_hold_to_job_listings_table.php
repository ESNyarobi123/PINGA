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
        Schema::table('job_listings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_listings', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('status');
            }
            if (!Schema::hasColumn('job_listings', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('is_approved');
            }
            if (!Schema::hasColumn('job_listings', 'code_hold_until')) {
                $table->timestamp('code_hold_until')->nullable()->after('code_used_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn(['is_approved', 'approved_at', 'code_hold_until']);
        });
    }
};
