<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\User;
use App\Models\KycVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Services\GoogleAuthenticator;
use App\Services\OtpService;

class SecuritySettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:security setting"]);
        $this->middleware(["role:settings"]);
    }

    public function securitySetting()
    {
        $users = User::find(auth()->user()->id)->first();
        $system_configs = SystemConfig::select('kyc_back_part')->first();
        $system_otp = OtpService::has_admin_otp('all');
        return view(
            'admins.settings.security-setting',
            [
                'users' => $users,
                'system_configs' => $system_configs,
                'withdraw_check' => ($system_otp['withdraw']) ? 'checked' : '',
                'deposit_check' => ($system_otp['deposit']) ? 'checked' : '',
                'transfer_check' => ($system_otp['transfer']) ? 'checked' : '',
                'open_account_check' => ($system_otp['account_create']) ? 'checked' : '',
                'all_check' => ($system_otp['withdraw'] == true && $system_otp['transfer'] == true && $system_otp['account_create'] == true) ? 'checked' : '',
            ]
        );
    }

    // kyc back part settings
    public function kycBackPartSetting(Request $request, $check_value)
    {
        $update = SystemConfig::where('id', 1)->update([
            'kyc_back_part' => $request->check_value,
        ]);
        if ($update) {

            return Response::json(['success' => true, 'message' => 'Successfully Updated.']);

            return Redirect()->back()->with(['success' => true, 'message' => 'Successfully Updated.']);
        }

        return Response::json(['success' => false, 'message' => 'Failed To Update!']);

        return Redirect()->back()->with(['success' => false, 'message' => 'Failed To Update!']);
    }
    // update security settings
    public function securitySettingUpdate(Request $request, $check_auth)
    {
        $update = "";
        if ($check_auth == "no_auth") {
            $update = User::where('id', auth()->user()->id)->update([
                'g_auth' => 0,
                'email_auth' => 0,
                'secret_key' => ""
            ]);
        } else if ($check_auth = "mail_auth") {
            $update = User::where('id', auth()->user()->id)->update([
                'g_auth' => 0,
                'email_auth' => 1,
                'secret_key' => ""
            ]);
        }
        if ($update) {
            return Response::json(['success' => true, 'message' => 'Successfully Updated.']);

            return Redirect()->back()->with(['success' => true, 'message' => 'Successfully Updated.']);
        }
        return Response::json(['success' => false, 'message' => 'Failed To Update!']);

        return Redirect()->back()->with(['success' => false, 'message' => 'Failed To Update!']);
    }
    // update security settings
    public function googleAuthenticationSet(Request $request)
    {
        $user_id = $request->user_id;
        $secret_key = $request->secret_key;
        $v_code = $request->v_code;
        $ga = new GoogleAuthenticator();
        $checkResult = $ga->verifyCode($secret_key, $v_code, 2);
        if ($checkResult) {

            $update = User::where('id', $user_id)->update([
                'g_auth' => 1,
                'email_auth' => 0,
                'secret_key' => $secret_key
            ]);
            if ($update) {

                return Response::json(['success' => true, 'message' => 'Successfully Updated.']);

                return Redirect()->back()->with(['success' => true, 'message' => 'Successfully Updated.']);
            }

            return Response::json(['success' => false, 'message' => 'Failed To Update!']);

            return Redirect()->back()->with(['success' => false, 'message' => 'Failed To Update!']);
        }

        return Response::json(['success' => false, 'message' => 'Failed To Update!']);

        return Redirect()->back()->with(['success' => false, 'message' => 'Failed To Update!']);
    }
}
