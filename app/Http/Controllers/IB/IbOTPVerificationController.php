<?php

namespace App\Http\Controllers\IB;


use App\Http\Controllers\Controller;
use App\Models\UserOtpSetting;
use Illuminate\Http\Request;

class IbOTPVerificationController extends Controller
{
    public function otpVerification(Request $request, $name, $check)
    {
        $result = UserOtpSetting::where('user_id', auth()->user()->id)->first();
        if (!empty($result)) {
            if ($name === 'otp_all' and $check == 1) {
                $created = UserOtpSetting::where('user_id', auth()->user()->id)->update([
                    'account_create' => 1,
                    'deposit' => 1,
                    'withdraw' => 1,
                    'transfer' => 1,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP All Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_all' and $check == 0) {
                $created = UserOtpSetting::where('user_id', auth()->user()->id)->update([
                    'account_create' => 0,
                    'deposit' => 0,
                    'withdraw' => 0,
                    'transfer' => 0,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP All Deactivate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_deposit' and $check == 1) {
                $created = UserOtpSetting::where('user_id', auth()->user()->id)->update([
                    'deposit' => 1,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Deposit Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_deposit' and $check == 0) {
                $created = UserOtpSetting::where('user_id', auth()->user()->id)->update([
                    'deposit' => 0,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Deposit Deactivate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_withdraw' and $check == 1) {
                $created = UserOtpSetting::where('user_id', auth()->user()->id)->update([
                    'withdraw' => 1,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Withdraw Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_withdraw' and $check == 0) {
                $created = UserOtpSetting::where('user_id', auth()->user()->id)->update([
                    'withdraw' => 0,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Withdraw Deactivate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_transfer' and $check == 1) {
                $created = UserOtpSetting::where('user_id', auth()->user()->id)->update([
                    'transfer' => 1,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Transfer Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_transfer' and $check == 0) {
                $created = UserOtpSetting::where('user_id', auth()->user()->id)->update([
                    'transfer' => 0,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Transfer Deactivate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_live_account' and $check == 1) {
                $created = UserOtpSetting::where('user_id', auth()->user()->id)->update([
                    'account_create' => 1,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Live Account Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_live_account' and $check == 0) {
                $created = UserOtpSetting::where('user_id', auth()->user()->id)->update([
                    'account_create' => 0,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Live Account Deactivate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
        } else {
            if ($name === 'otp_all' and $check == 1) {
                $created = UserOtpSetting::create([
                    'account_create' => 1,
                    'deposit' => 1,
                    'withdraw' => 1,
                    'transfer' => 1,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP All Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_deposit' and $check == 1) {
                $created = UserOtpSetting::create([
                    'deposit' => 1,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Deposit Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_withdraw' and $check == 1) {
                $created = UserOtpSetting::create([
                    'withdraw' => 1,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Withdraw Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_transfer' and $check == 1) {
                $created = UserOtpSetting::create([
                    'transfer' => 1,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Transfer Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
            if ($name === 'otp_live_account' and $check == 1) {
                $created = UserOtpSetting::create([
                    'account_create' => 1,
                    'user_id' => auth()->user()->id
                ]);
                if ($created) {
                    return response()->json([
                        'status' => true,
                        'message' => 'OTP Live Account Activate'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'something went to wrong'
                    ]);
                }
            }
        }
    }
}
