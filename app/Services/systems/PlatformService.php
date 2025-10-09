<?php


namespace App\Services\systems;

use App\Models\admin\SystemConfig;

class PlatformService
{
    public static function get_platform()
    {
        try {
            $result = SystemConfig::select('platform_type')->first();
            return $result->platform_type;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
