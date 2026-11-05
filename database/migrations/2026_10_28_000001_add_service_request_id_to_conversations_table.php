<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('job_id')->nullable()->change();
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('service_request_id')
                ->nullable()
                ->after('job_id')
                ->constrained('service_requests')
                ->nullOnDelete();

            $table->unique(['service_request_id', 'employer_id', 'worker_id'], 'conversations_service_request_party_unique');
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropUnique('conversations_service_request_party_unique');
            $table->dropConstrainedForeignId('service_request_id');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('job_id')->nullable(false)->change();
        });
    }
};
