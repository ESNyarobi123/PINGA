<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('withdrawal_requests', 'payout_error')) {
            Schema::table('withdrawal_requests', function (Blueprint $table): void {
                $table->text('payout_error')->nullable()->after('payout_status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('withdrawal_requests', 'payout_error')) {
            Schema::table('withdrawal_requests', function (Blueprint $table): void {
                $table->dropColumn('payout_error');
            });
        }
    }
};
