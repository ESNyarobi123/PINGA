<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;

/**
 * Feature detection for service_packages migrations (graceful before migrate).
 */
final class ServicePackageSchema
{
    public static function hasPackagesTable(): bool
    {
        return Schema::hasTable('service_packages');
    }

    public static function serviceRequestsHavePackageIdColumn(): bool
    {
        if (! Schema::hasTable('service_requests')) {
            return false;
        }

        return Schema::hasColumn('service_requests', 'service_package_id');
    }
}
