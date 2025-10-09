<?php

namespace App\Http\Controllers;

use App\Models\admin\SystemConfig;
use App\Services\mobile\MobileAppService;
use Illuminate\Http\Request;

class ConfigApiController extends Controller
{
    public function get_logo(Request $request)
    {
        switch ($request->type) {
            case 'favicon':
                return get_favicon_icon();
                break;
            case 'loader':
                return MobileAppService::app_log('logo_loader');
                break;

            default:
                return MobileAppService::app_log('brand_logo');
                break;
        }
    }
}
