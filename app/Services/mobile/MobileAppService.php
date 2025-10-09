<?php

namespace App\Services\mobile;

use App\Models\MobileAppSetting;

class MobileAppService
{
    public static function app_log($type = 'brand_logo')
    {
        switch (strtolower($type)) {
            case 'brand_logo':
                $logos = MobileAppSetting::select('logo_brand')->first();
                return (isset($logos->logo_brand) && ($logos->logo_brand != null)) ? asset('Uploads/mobile/' . $logos->logo_brand) : get_user_logo();
                break;
            case 'logo_loader':
                $logos = MobileAppSetting::select('logo_loader')->first();
                return (isset($logos->logo_loader) && ($logos->logo_loader != null)) ? asset('Uploads/mobile/' . $logos->logo_loader) : get_user_logo();
                break;

            default:
                return get_user_logo();
                break;
        }
    }
}
