<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing roles in the database
        DB::table('users')
            ->where('role', 'mfanyakazi')
            ->update(['role' => 'winga']);

        DB::table('users')
            ->where('role', 'muajili')
            ->update(['role' => 'mteja']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes
        DB::table('users')
            ->where('role', 'winga')
            ->update(['role' => 'mfanyakazi']);

        DB::table('users')
            ->where('role', 'mteja')
            ->update(['role' => 'muajili']);
    }
};
