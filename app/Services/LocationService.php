<?php

namespace App\Services;

use Illuminate\Support\Collection;

class LocationService
{
    /**
     * Get all regions from users table
     */
    public static function getRegions(): Collection
    {
        return \DB::table('users')
            ->whereNotNull('mkoa')
            ->where('mkoa', '!=', '')
            ->distinct()
            ->orderBy('mkoa')
            ->pluck('mkoa');
    }

    /**
     * Get all districts from users table
     */
    public static function getDistricts(): Collection
    {
        return \DB::table('users')
            ->whereNotNull('wilaya')
            ->where('wilaya', '!=', '')
            ->distinct()
            ->orderBy('wilaya')
            ->pluck('wilaya');
    }

    /**
     * Get regions from jobs table
     */
    public static function getJobRegions(): Collection
    {
        return \DB::table('job_listings')
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');
    }

    /**
     * Get all locations (regions + districts + job locations)
     */
    public static function getAllLocations(): Collection
    {
        $regions = self::getRegions();
        $districts = self::getDistricts();
        $jobLocations = self::getJobRegions();
        
        return $regions->merge($districts)->merge($jobLocations)
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }
}
