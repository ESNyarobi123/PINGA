<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('phone_block_attempts')) {
            Schema::create('phone_block_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('attempted_content');
                $table->string('blocked_pattern', 255);
                $table->enum('form_type', ['job', 'application', 'profile', 'portfolio']);
                $table->ipAddress('ip_address')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'form_type']);
                $table->index('blocked_pattern');
                $table->index('created_at');
            });
        }

        // dispute_evidence, broadcast_messages, and admin_audit_logs tables
        // are created by their own dedicated migrations (025008, 025410, 025427, 023312)

        // Add approval columns to job_listings if not exists
        Schema::table('job_listings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_listings', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            }
            if (!Schema::hasColumn('job_listings', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('approval_status');
            }
            if (!Schema::hasColumn('job_listings', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('rejection_reason');
            }
            if (!Schema::hasColumn('job_listings', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
        });

        // Add indexes if they don't exist
        if (!Schema::hasColumn('job_listings', 'approval_status')) {
            Schema::table('job_listings', function (Blueprint $table) {
                $table->index(['approval_status', 'status']);
                $table->index('approved_by');
            });
        }

        // Add hired_worker_id to job_listings if not exists
        Schema::table('job_listings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_listings', 'hired_worker_id')) {
                $table->foreignId('hired_worker_id')->nullable()->constrained('users')->onDelete('set null')->after('employer_id');
            }
            if (!Schema::hasColumn('job_listings', 'hired_at')) {
                $table->timestamp('hired_at')->nullable()->after('hired_worker_id');
            }
        });

        // Add index if it doesn't exist
        if (!Schema::hasColumn('job_listings', 'hired_worker_id')) {
            Schema::table('job_listings', function (Blueprint $table) {
                $table->index('hired_worker_id');
            });
        }

        // Add hold_status to job_listings if not exists
        Schema::table('job_listings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_listings', 'hold_status')) {
                $table->enum('hold_status', ['active', 'released'])->default('released')->after('completion_code');
            }
            if (!Schema::hasColumn('job_listings', 'hold_started_at')) {
                $table->timestamp('hold_started_at')->nullable()->after('hold_status');
            }
        });

        // Add index if it doesn't exist
        if (!Schema::hasColumn('job_listings', 'hold_status')) {
            Schema::table('job_listings', function (Blueprint $table) {
                $table->index('hold_status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_block_attempts');
        Schema::dropIfExists('dispute_evidence');
        Schema::dropIfExists('broadcast_messages');
        Schema::dropIfExists('admin_audit_log');
        
        // Note: disputes table columns are handled in the disputes table creation migration
        
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn([
                'approval_status', 'rejection_reason', 'approved_by', 'approved_at',
                'hired_worker_id', 'hired_at', 'hold_status', 'hold_started_at'
            ]);
        });
    }
};
