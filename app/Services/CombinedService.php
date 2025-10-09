<?php

namespace App\Services;

use App\Models\admin\SystemConfig;
use App\Models\IbSetting;
use App\Models\SoftwareSetting;
use App\Models\SystemModule;
use App\Models\TraderSetting;
use App\Models\User;
use App\Services\AllFunctionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

/**
 * CrmApiService Api new
 */
class CombinedService
{
    // check combined access
    // controll ib modules
    public static function is_combined($use_as = null, $user_id = null)
    {
        switch ($use_as) {
            case 'client':
                // check combine and combine access in individual client
                $user_id = ($user_id == null) ? auth()->user()->id : $user_id;
                $combine_access = User::find($user_id);
                if (self::is_combined() && ($combine_access->combine_access == 1)) {
                    return (true);
                } else {
                    return false;
                }
                break;

            default:
                // use for check only combine
                $crm_type = SystemConfig::select('crm_type')->first();
                if ($crm_type) {
                    if ($crm_type->crm_type === 'combined') {
                        return (true);
                    } else {
                        return (false);
                    }
                }
                return (false);
                break;
        }
    }
    // set user type for combined user
    public static function type()
    {
        if (self::is_combined()) {
            return ('0');
        } else {
            return ('4');
        }
    }
    // check combined crm dual portal or single
    public static function is_single_portal()
    {
        $software_settings = SoftwareSetting::select()->first();
        if ($software_settings) {
            return $software_settings->is_single_portal;
        } else {
            return false;
        }
    }
    // check requested or not
    public static function is_requested($user_id = null)
    {
        if ($user_id == null) {
            $user_id = auth()->user()->id;
        }
        if (self::is_combined()) {
            $user = User::where('id', $user_id)->select('combine_access')->first();
            if ($user) {
                if ($user->combine_access == 2) {
                    return (true);
                }
                return (false);
            }
            return (false);
        }
        return (false);
    }
}
