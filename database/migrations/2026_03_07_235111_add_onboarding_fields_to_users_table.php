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
            $table->string('mkoa')->nullable()->after('longitude');
            $table->string('wilaya')->nullable()->after('mkoa');
            $table->string('mtaa')->nullable()->after('wilaya');
            $table->string('payment_method')->nullable()->after('mtaa');
            $table->string('payment_number')->nullable()->after('payment_method');
            $table->string('bei_aina')->nullable()->after('payment_number');
            $table->unsignedInteger('bei_wastani')->nullable()->after('bei_aina');
            $table->unsignedTinyInteger('uzoefu_miaka')->nullable()->after('bei_wastani');
            $table->json('siku_zinazopatikana')->nullable()->after('uzoefu_miaka');
            $table->string('nida')->nullable()->after('siku_zinazopatikana');
            $table->string('veta')->nullable()->after('nida');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'mkoa', 'wilaya', 'mtaa', 'payment_method', 'payment_number',
                'bei_aina', 'bei_wastani', 'uzoefu_miaka', 'siku_zinazopatikana',
                'nida', 'veta',
            ]);
        });
    }
};
