<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add Winga-specific fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->unique()->after('email');
            $table->text('bio')->nullable()->after('password');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('location')->nullable()->after('avatar');
            $table->decimal('latitude', 10, 8)->nullable()->after('location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->decimal('wallet_balance', 12, 2)->default(0)->after('longitude');
            $table->timestamp('phone_verified_at')->nullable()->after('wallet_balance');
            $table->boolean('onboarding_completed')->default(false)->after('phone_verified_at');
        });

        // Categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Skills table
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

        // User-Skills pivot
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->unique(['user_id', 'skill_id']);
        });

        // Job Listings
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();
            $table->enum('budget_type', ['fixed', 'hourly'])->default('fixed');
            $table->string('duration')->nullable();
            $table->enum('status', ['draft', 'open', 'in_progress', 'completed', 'cancelled', 'disputed'])->default('draft');
            $table->string('completion_code')->nullable(); // hashed 6-digit code
            $table->timestamp('code_generated_at')->nullable();
            $table->timestamp('code_used_at')->nullable();
            $table->foreignId('hired_worker_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('urgency', ['normal', 'urgent', 'very_urgent'])->default('normal');
            $table->boolean('remote_allowed')->default(false);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('applications_count')->default(0);
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['latitude', 'longitude']);
        });

        // Job-Skills pivot
        Schema::create('job_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_listings')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->unique(['job_id', 'skill_id']);
        });

        // Applications
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_listings')->onDelete('cascade');
            $table->foreignId('worker_id')->constrained('users')->onDelete('cascade');
            $table->text('cover_letter')->nullable();
            $table->decimal('proposed_budget', 12, 2)->nullable();
            $table->string('proposed_duration')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            $table->timestamps();

            $table->unique(['job_id', 'worker_id']);
        });

        // Payments (Escrow)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_listings')->onDelete('cascade');
            $table->foreignId('employer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('worker_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('amount', 12, 2);
            $table->decimal('platform_fee', 12, 2)->default(0);
            $table->decimal('worker_amount', 12, 2)->default(0);
            $table->enum('status', ['pending', 'escrowed', 'released', 'refunded', 'disputed'])->default('pending');
            $table->string('payment_method')->nullable(); // mpesa, tigopesa, airtel_money
            $table->string('payment_reference')->nullable();
            $table->timestamp('escrow_released_at')->nullable();
            $table->timestamps();
        });

        // Transactions (wallet history)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['credit', 'debit', 'withdrawal', 'deposit']);
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->decimal('balance_after', 12, 2);
            $table->string('reference')->nullable();
            $table->timestamps();
        });

        // Portfolios
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('project_url')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

        // Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_listings')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewee_id')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['job_id', 'reviewer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('portfolios');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('job_skills');
        Schema::dropIfExists('job_listings');
        Schema::dropIfExists('user_skills');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('categories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'bio', 'avatar', 'location',
                'latitude', 'longitude', 'wallet_balance',
                'phone_verified_at', 'onboarding_completed',
            ]);
        });
    }
};
