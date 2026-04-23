<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('service_requests')) {
            Schema::table('service_requests', function (Blueprint $table) {
                if (! Schema::hasColumn('service_requests', 'completion_code')) {
                    $table->string('completion_code', 12)->nullable()->after('responded_at');
                    $table->timestamp('code_generated_at')->nullable()->after('completion_code');
                    $table->timestamp('code_used_at')->nullable()->after('code_generated_at');
                    $table->timestamp('code_hold_until')->nullable()->after('code_used_at');
                    $table->text('hold_comment')->nullable()->after('code_hold_until');
                    $table->boolean('hold_extended')->default(false)->after('hold_comment');
                }
            });
        }

        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'service_request_id')) {
                $table->foreignId('service_request_id')
                    ->nullable()
                    ->after('job_id')
                    ->constrained('service_requests')
                    ->cascadeOnDelete();
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('job_id')->nullable()->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('job_id')
                ->references('id')
                ->on('job_listings')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'service_request_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropForeign(['service_request_id']);
                $table->dropColumn('service_request_id');
            });
        }

        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropForeign(['job_id']);
            });
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('job_id')->nullable(false)->change();
            });
            Schema::table('payments', function (Blueprint $table) {
                $table->foreign('job_id')
                    ->references('id')
                    ->on('job_listings')
                    ->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('service_requests')) {
            $cols = array_values(array_filter(
                ['completion_code', 'code_generated_at', 'code_used_at', 'code_hold_until', 'hold_comment', 'hold_extended'],
                fn (string $col) => Schema::hasColumn('service_requests', $col)
            ));
            if ($cols !== []) {
                Schema::table('service_requests', function (Blueprint $table) use ($cols) {
                    $table->dropColumn($cols);
                });
            }
        }
    }
};
