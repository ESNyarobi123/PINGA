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
        Schema::create('profile_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('Profile being viewed (mfanyakazi)');
            $table->foreignId('viewer_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('User who viewed (null if guest)');
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('viewed_at');
            $table->timestamps();

            // Indexes for analytics queries
            $table->index(['worker_id', 'viewed_at']);
            $table->index(['viewer_id', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_views');
    }
};
