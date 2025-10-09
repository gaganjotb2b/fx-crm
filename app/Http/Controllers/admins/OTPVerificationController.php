<?php

namespace App\Http\Controllers\Admins;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\OtpSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class OTPVerificationController extends Controller
{
    public function otpVerification(Request $request)
    {
        $result = OtpSetting::select()->first();
        $create = OtpSetting::updateOrCreate(
            [
                'id' => ($result) ? $result->id : 1
            ],
            [
                'account_create' => isset($request->open_account) ? 1 : 0,
                'deposit' => isset($request->deposit) ? 1 : 0,
                'withdraw' => isset($request->withdraw) ? 1 : 0,
                'transfer' => isset($request->transfer) ? 1 : 0,
                'admin_id' => auth()->user()->id,
            ]
        );
        if ($create) {
            return Response::json([
                'status' => true,
                'message' => 'OTP Settings Successfully Done!'
            ]);
        }
        return ([
            'status' => false,
            'message' => 'OTP Settings Failed, Please Try Again Later!'
        ]);
    }
}
