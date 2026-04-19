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
            $table->text('hold_comment')->nullable()->after('code_hold_until');
            $table->boolean('hold_extended')->default(false)->after('hold_comment');
            $table->text('rejection_comment')->nullable()->after('hold_extended');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn(['hold_comment', 'hold_extended', 'rejection_comment']);
        });
    }
};
