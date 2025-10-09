<?php

namespace App\Http\Controllers\Admins;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\SystemConfig;
use App\Models\TransactionSetting;
use App\Models\PasswordSettings;
use App\Models\SocialLogin;
use App\Models\SoftwareSetting;
use App\Services\AllFunctionService;
use Carbon\Carbon;
use PhpParser\Builder\Trait_;
use Illuminate\Support\Facades\DB;

class SoftwareSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:software settings"]);
        $this->middleware(["role:settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('software_settings', 'admin'));
    }
    public function softwareSetting()
    {
        $configs = SystemConfig::select()->first();
        $softwareSettings = SoftwareSetting::select('direct_deposit','direct_withdraw','crypto_deposit')->first();
        $social_logins = SocialLogin::select()->first();
        $password_settings = PasswordSettings::select()->first(); 
        // return $social_logins;
        return view('admins.settings.software_setting', [
            'configs' => $configs,
            'softwareSettings' => $softwareSettings,
            'social_logins'    => $social_logins,
            'password_settings' => $password_settings
        ]);
    }
    // software setting
    public function softwareSettingAdd(Request $request)
    {
        // end company social media
        $create_meta_acc = ($request->create_meta_acc == "on") ? 1 : 0;
        $platform_book = (isset($request->platform_book)) ? strtolower($request->platform_book) : "";
        $social_account = ($request->social_account == "on") ? 1 : 0;
        $acc_limit = (isset($request->acc_limit)) ? $request->acc_limit : 0;
        $brute_force_attack = (isset($request->brute_force_attack)) ? $request->brute_force_attack : 0;

        $data = [
            // 'crm_type' => strtolower($request->crm_type),
            'create_meta_acc' => $create_meta_acc,
            'platform_book' => $platform_book,
            'social_account' => $social_account,
            'acc_limit' => $acc_limit,
            'brute_force_attack' => $brute_force_attack,

        ];
        if (SystemConfig::where('id', $request->config_id)->update($data)) {
            if ($request->ajax()) {
                return Response::json(['status' => true, 'message' => 'Successfully Updated.']);
            } else {
                return Redirect()->back()->with(['status' => true, 'message' => 'Successfully Updated.']);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'message' => 'Failed To Update!']);
            } else {
                return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Update!']);
            }
        }
    }
}
