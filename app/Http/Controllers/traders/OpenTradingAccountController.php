<?php

namespace App\Http\Controllers\Traders;

use App\Http\Controllers\Controller;
use App\Mail\DemoAccountMail;
use App\Mail\OTPverificationMail;
use App\Models\admin\SystemConfig;
use App\Models\BonusPackage;
use App\Models\BonusUser;
use App\Models\ClientGroup;
use App\Models\Country;
use App\Models\Log;
use App\Models\OtpSetting;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserOtpSetting;
use App\Services\AllFunctionService;
use App\Services\EmailService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\OpenAccountService;
use App\Services\OpenLiveTradingAccountService;
use App\Services\OtpService;
use App\Services\systems\AccountSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class OpenTradingAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('open_live_account', 'trader'));
        $this->middleware(AllFunctionService::access('trading_accounts', 'trader'));
    }

    public function openAccount()
    {
        $system_config = SystemConfig::select('platform_type')->first();
        $server = '';
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
        if ($system_config) {
            if ($system_config->platform_type === 'both') {
                $server .= '<option value="mt5">mt5</option>';
                $server .= '<option value="mt4">mt4</option>';
            } else {
                $server .= '<option value="' . $system_config->platform_type . '">' . strtoupper($system_config->platform_type) . '</option>';
            }
        }
        return view(
            "traders.trading-account.open-live-account",
            [
                'server' => $server,
                'otp_settings' => $otp_settings,
                'user_otp_settings' => $user_otp_settings
            ]
        );
    }
    // FORM SUBMIT
    // open LIVE trading account---------------
    public function open_live_account_form(Request $request)
    {

        try {
            $otp_settings = OtpSetting::first();
            $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
            // MUTIPLE SUBMITTION FO FINAL STEP
            if ($request->op === 'step-2') {
                $multiple_submission = has_multi_submit('live-account', 30);
                multi_submit('live-account', 30);
                $status_data = [
                    'submit_wait' => submit_wait('live-account', 30),
                ];
            }
            // CHECK VALIDATION
            $validation_rules = [
                'platform' => 'required',
                'account_type' => 'required',
                'leverage' => 'required',
            ];
            // STEP 2 OTP VALIDATION
            if ($request->op === 'step-2') {
                $validation_rules['otp_1'] = 'required|max:1';
                $validation_rules['otp_2'] = 'required|max:1';
                $validation_rules['otp_3'] = 'required|max:1';
                $validation_rules['otp_4'] = 'required|max:1';
                $validation_rules['otp_5'] = 'required|max:1';
                $validation_rules['otp_6'] = 'required|max:1';
            }

            // start session of form submit
            $validator = Validator::make($request->all(), $validation_rules);
            // DEFAULT VALIDATION FAILD
            if ($validator->fails()) {
                // step 1 validation faild
                if ($request->op === 'step-1') {
                    $status_data['valid_status'] = false;
                }
                // step 2 validation faild
                if ($request->op == 'step-2') {
                    $status_data['otp_status'] = false;
                }
                $status_data['errors'] =  $validator->errors();
                $status_data['message'] = 'Please fix the following errors!';
                return Response::json($status_data);
            }
            $user = User::where('users.id', auth()->user()->id)
                ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->first();

            if (!AccountSettingsService::user_account_limit(['user_id' => auth()->user()->id])) {
                $status_data['valid_status'] = false;
                $status_data['message'] = 'Your account limit is zero .You can not open account. Please contact with admin to get permission!';
                return Response::json($status_data);
            }
            // check otp
            // chreate otp & send it
            if (OtpService::has_otp('account_create')) {
                if ($request->op === 'step-1' || $request->op === 'resend') {
                    $otp_status = OtpService::send_otp();
                    if ($request->op === 'resend') {
                        return Response::json(['otp_send' => $otp_status]);
                    }
                }
            }
            // when otp off by admin/clients
            // create meta account
            else {
                $response = OpenLiveTradingAccountService::open_live_account([
                    'user_id' => auth()->user()->id,
                    'platform' => $request->platform,
                    'leverage' => $request->leverage,
                    'account_type' => decrypt($request->account_type),
                ]);

                return Response::json($response);
            }
            // when otp on by admin/clients
            // create meta account
            if ($request->op === 'step-2') {

                $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
                if ($request->session()->get('account-otp') == $request_otp) {
                    $time = session('otp_set_time');
                    $minutesBeforeSessionExpire = 5;
                    if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                        $response = OpenLiveTradingAccountService::open_live_account([
                            'user_id' => auth()->user()->id,
                            'platform' => $request->platform,
                            'leverage' => $request->leverage,
                            'account_type' => decrypt($request->account_type),
                        ]);
                        return Response::json($response);
                    } else {
                        $status_data['otp_status'] = false;
                        $status_data['message'] = 'OTP Timeout!';
                        return Response::json($status_data);
                    }
                }
                $status_data['otp_status'] = false;
                $status_data['message'] = 'OTP not matched!';
                return Response::json($status_data);
            }
            $status_data['valid_status'] = true;
            return Response::json($status_data);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status'=>false,
                'message'=>'Got a server error!'
            ]);
        }
    }
}
