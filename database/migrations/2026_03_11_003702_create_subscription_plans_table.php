<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();          // msingi, kawaida, bora
            $table->string('name');                    // Msingi, Kawaida, Bora
            $table->string('name_en')->nullable();     // Basic, Standard, Premium
            $table->unsignedInteger('price');          // TZS amount
            $table->unsignedSmallInteger('duration_days'); // 30, 90, 180
            $table->json('features');                  // array of benefit strings
            $table->string('badge_label')->default('Winga Bora');
            $table->string('badge_color')->default('amber'); // amber, blue, winga
            $table->boolean('is_recommended')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
