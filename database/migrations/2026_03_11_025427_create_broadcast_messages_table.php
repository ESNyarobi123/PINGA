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
        if (! Schema::hasTable('broadcast_messages')) {
            Schema::create('broadcast_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
                $table->string('title');
                $table->longText('body');
                $table->string('announcement_type', 32)->nullable();
                $table->json('channels'); // ['app', 'email', 'sms']
                $table->enum('target_type', ['all', 'wingas', 'wateja', 'subscribed', 'mkoa', 'individual']);
                $table->json('target_segments')->nullable();
                $table->string('target_value')->nullable(); // mkoa name or user ID
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->integer('recipient_count')->default(0);
                $table->enum('status', ['draft', 'scheduled', 'sent', 'failed'])->default('draft');
                $table->timestamps();

                $table->index(['status', 'scheduled_at']);
                $table->index('target_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_messages');
    }
};
