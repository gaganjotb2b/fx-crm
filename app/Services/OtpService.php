<?php

namespace App\Services;

use App\Models\OtpSetting;
use App\Models\UserOtpSetting;

class OtpService
{

    public static function has_otp($otp_for, $user_id = null)
    {
        switch (strtolower($otp_for)) {
            case 'all':
                $user_id = ($user_id == null) ? auth()->user()->id : $user_id;
                $otp_settings = OtpSetting::first();
                $user_otp_settings = UserOtpSetting::where('user_id', $user_id)->first();
                // $otp_for = (object) $top_for;
                if ($otp_settings && $user_otp_settings) {
                    return ([
                        'account_create' => ($otp_settings->account_create == true && $user_otp_settings->account_create == true) ? true : false,
                        'deposit' => ($otp_settings->deposit == true && $user_otp_settings->deposit == true) ? true : false,
                        'withdraw' => ($otp_settings->withdraw == true && $user_otp_settings->withdraw == true) ? true : false,
                        'transfer' => ($otp_settings->transfer == true && $user_otp_settings->transfer == true) ? true : false,
                    ]);
                } else {
                    return ([
                        'account_create' => false,
                        'deposit' => false,
                        'withdraw' => false,
                        'transfer' => false,
                    ]);
                }
                break;

            default:
                $user_id = ($user_id == null) ? auth()->user()->id : $user_id;
                $otp_settings = OtpSetting::first();
                $user_otp_settings = UserOtpSetting::where('user_id', $user_id)->first();
                // $otp_for = (object) $top_for;
                if ($otp_settings && $user_otp_settings) {
                    if ($otp_settings->{$otp_for} == true && $user_otp_settings->{$otp_for} == true) {
                        return (true);
                    } else {
                        return (false);
                    }
                } else {
                    return (false);
                }
                break;
        }
    }
    // chack has admin otp
    public static function has_admin_otp($otp_for)
    {
        $otp_settings = OtpSetting::first();
        switch ($otp_for) {
            case 'account_create':
                // get account create otp settings
                if ($otp_settings) {
                    return ($otp_settings->account_create) ? true : false;
                }
                return false;
                break;
            case 'deposit':
                // get deposit otp settings
                if ($otp_settings) {
                    return ($otp_settings->deposit) ? true : false;
                }
                return false;
                break;
            case 'withdraw':
                // get withdraw otp settings
                if ($otp_settings) {
                    return ($otp_settings->withdraw) ? true : false;
                }
                return false;
                break;
            case 'transfer':
                // get transfer otp settings
                if ($otp_settings) {
                    return ($otp_settings->transfer) ? true : false;
                }
                return false;
                break;

            default:
                // get all otp settings
                if ($otp_settings) {
                    return ([
                        'account_create' => ($otp_settings->account_create) ? true : false,
                        'deposit' => ($otp_settings->deposit) ? true : false,
                        'withdraw' => ($otp_settings->withdraw) ? true : false,
                        'transfer' => ($otp_settings->transfer) ? true : false,
                    ]);
                }
                return ([
                    'account_create' => ($otp_settings->account_create) ? true : false,
                    'deposit' => ($otp_settings->deposit) ? true : false,
                    'withdraw' => ($otp_settings->withdraw) ? true : false,
                    'transfer' => ($otp_settings->transfer) ? true : false,
                ]);
                break;
        }
    }
    // send otp
    public static function send_otp($user_id = null, $otp_for = null)
    {
        $user_id = ($user_id == null) ? auth()->user()->id : $user_id;
        $otp_for = ($otp_for != null) ? $otp_for : 'trader-transfer-otp';
        // create otp
        $otp = random_int(100000, 999999);
        request()->session()->put($otp_for, $otp);
        request()->session()->put('otp_set_time', time());
        // sending otp mail
        $mail_status = EmailService::send_email('otp-verification', [
            'user_id'  => $user_id,
            'otp'      => $otp,
        ]);
        if ($mail_status) {
            return (true);
        } else {
            return (false);
        }
    }
    // check otp time
    public static function otp_expire($request_otp)
    {

        if (isset($request_otp) && (time() - session('otp_set_time') < (5 * 60))) {
            return true;
        } else {
            return false;
        }
    }
}
