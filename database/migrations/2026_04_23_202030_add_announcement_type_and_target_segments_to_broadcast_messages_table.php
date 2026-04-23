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
            return;
        }

        if (! Schema::hasColumn('broadcast_messages', 'announcement_type')) {
            Schema::table('broadcast_messages', function (Blueprint $table) {
                $table->string('announcement_type', 32)->nullable()->after('body');
            });
        }

        if (! Schema::hasColumn('broadcast_messages', 'target_segments')) {
            Schema::table('broadcast_messages', function (Blueprint $table) {
                $table->json('target_segments')->nullable()->after('target_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('broadcast_messages')) {
            return;
        }

        Schema::table('broadcast_messages', function (Blueprint $table) {
            if (Schema::hasColumn('broadcast_messages', 'target_segments')) {
                $table->dropColumn('target_segments');
            }
            if (Schema::hasColumn('broadcast_messages', 'announcement_type')) {
                $table->dropColumn('announcement_type');
            }
        });
    }
};
