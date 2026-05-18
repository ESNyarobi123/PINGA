<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_announcement_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_announcement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();

            $table->unique(['site_announcement_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_announcement_user');
    }
};
