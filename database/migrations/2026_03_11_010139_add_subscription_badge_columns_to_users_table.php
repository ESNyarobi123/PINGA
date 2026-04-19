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
        Schema::table('users', function (Blueprint $table) {
            $table->string('custom_profile_slug', 30)
                ->nullable()
                ->unique()
                ->after('phone')
                ->comment('Custom URL slug for public profile');

            $table->boolean('is_verified')
                ->default(false)
                ->after('email_verified_at')
                ->comment('Verified badge for Bora subscribers');

            $table->float('avg_response_hours')
                ->nullable()
                ->after('is_verified')
                ->comment('Average chat response time in hours');

            $table->boolean('is_top_rated')
                ->default(false)
                ->after('avg_response_hours')
                ->comment('Top Rated badge eligibility');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['custom_profile_slug', 'is_verified', 'avg_response_hours', 'is_top_rated']);
        });
    }
};
