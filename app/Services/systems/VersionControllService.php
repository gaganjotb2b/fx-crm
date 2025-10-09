<?php

namespace App\Services\systems;

use App\Models\SoftwareSetting;
use App\Services\CombinedService;

class VersionControllService
{
    public static function check_version()
    {
        try {
            $software_settings = SoftwareSetting::select('version')->first();
            if ($software_settings) {
                return $software_settings->version;
            }
            return ('pro');
        } catch (\Throwable $th) {
            //throw $th;
            return 'pro';
        }
    }
    // get layout 
    public static function get_layout($portal)
    {
        switch ($portal) {
            case 'trader':
                if (self::check_version() === 'lite') {
                    return ('layouts.lite-layout.trader-layout-metronic');
                }
                return ('layouts.trader-layout');
                break;

            default:
                if (self::check_version() === 'lite') {
                    return ('layouts.lite-layout.ib-layout-metronic');
                }
                return ('layouts.ib-layout');
                break;
        }
    }
    // version selected
    public static function version_selected()
    {
        switch (self::check_version()) {
            case 'lite':
                return ('');
                break;

            default:
                return ('checked');
                break;
        }
    }
    public static function get_ib_layout()
    {
        if (CombinedService::is_combined('client') == true && CombinedService::is_single_portal() == true) {
            // check crm version lite/pro
            return self::get_layout('trader');
        } else {
            return self::get_layout('ib');
        }
    }
    // get login theme
    public static function get_login_theme($portal = 'client')
    {
        switch ($portal) {
            case 'client':
                switch (self::check_version()) {
                    case 'lite':
                        return ('auth.lite.login-softui-basic');
                        break;

                    default:
                        return ('auth.login');
                        break;
                }
                break;
                // ib login template

            case 'ib':
                switch (self::check_version()) {
                    case 'lite':
                        return ('auth.lite.ib-login-softui-basic');
                        break;

                    default:
                        return ('auth.ibs.login');
                        break;
                }
                break;

            default:
                # code...
                break;
        }
    }
}
