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
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->decimal('charge_percent', 5, 2)->default(0)->after('amount');
            $table->decimal('charge_amount', 12, 2)->default(0)->after('charge_percent');
            $table->decimal('net_amount', 12, 2)->default(0)->after('charge_amount');
            $table->string('phone')->nullable()->after('network');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn(['charge_percent', 'charge_amount', 'net_amount', 'phone']);
        });
    }
};
