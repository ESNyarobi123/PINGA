<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->foreignId('service_package_id')
                ->nullable()
                ->after('service_id')
                ->constrained('service_packages')
                ->nullOnDelete();
        });

        foreach (DB::table('service_requests')->whereNull('service_package_id')->cursor() as $row) {
            $packageId = DB::table('service_packages')
                ->where('service_id', $row->service_id)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->value('id');
            if ($packageId) {
                DB::table('service_requests')->where('id', $row->id)->update([
                    'service_package_id' => $packageId,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_package_id');
        });
    }
};
