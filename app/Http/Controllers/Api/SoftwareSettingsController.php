<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOtpSetting;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SoftwareSettingsController extends Controller
{
    // check otp settings enable or not
    public function has_otp(Request $request)
    {
        try {
            $validation_rules = [
                'operation' => 'required|in:deposit,withdraw,transfer,account_create,all',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => 'Validation Error',
                    'message' => "The request data is invalid.",
                    'errors' => $validator->errors(),
                ], 400);
            }
            switch (strtolower($request->operation)) {
                case 'all':
                    return Response::json([
                        'status' => true,
                        'otp_status' => OtpService::has_otp($request->operation, auth()->user()->id),
                        'description' => 'If otp_staus= false, otp not needed for requested operation. Otherwise required'
                    ]);
                    break;

                default:
                    if (OtpService::has_otp($request->operation, auth()->user()->id)) {
                        return Response::json([
                            'status' => true,
                            'otp_status' => true,
                            'description' => 'If otp_staus= false, otp not needed for requested operation. Otherwise required'
                        ]);
                    }
                    return Response::json([
                        'status' => true,
                        'otp_status' => false,
                        'description' => 'If otp_staus= false, otp not needed for requested operation. Otherwise required'
                    ]);
                    break;
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
    public function has_admin_otp(Request $request)
    {

        switch (strtolower($request->otp_for)) {
            case 'account_create':
                // otp check for account create
                return ([
                    'status' => true,
                    'otp' => OtpService::has_admin_otp('account_create'),
                ]);
                break;
            case 'deposit':
                // otp for deposit
                return ([
                    'status' => true,
                    'otp' => OtpService::has_admin_otp('deposit'),
                ]);
                break;
            case 'withdraw':
                // otp for withdraw
                return ([
                    'status' => true,
                    'otp' => OtpService::has_admin_otp('withdraw'),
                ]);
                break;
            case 'transfer':
                // otp for transfer
                return ([
                    'status' => true,
                    'otp' => OtpService::has_admin_otp('transfer'),
                ]);
                break;

            default:
                return ([
                    'status' => true,
                    'otp' => OtpService::has_admin_otp('all')
                ]);
                break;
        }
    }
    // client otp settings
    public function otp_settings(Request $request)
    {
        try {
            $validation_rules = [
                'account_create' => 'required|boolean',
                'deposit' => 'required|boolean',
                'withdraw' => 'required|boolean',
                'transfer' => 'required|boolean',
            ];
            $validator = Validator::make($request->all(), $validation_rules);

            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => "Validation Error",
                    'message' => "The request data is invalid.",
                    'errors' => $validator->errors(),
                ], 400);
            }
            $create = UserOtpSetting::updateOrCreate(
                [
                    'user_id' => auth()->user()->id,
                ],
                [
                    'account_create' => $request->account_create,
                    'deposit' => $request->deposit,
                    'withdraw' => $request->withdraw,
                    'transfer' => $request->transfer,
                ]
            );
            if ($create) {
                // insert activity-----------------
                $user = User::find(auth()->user()->id);
                activity("Update OTP Settings")
                    ->causedBy(auth()->user())
                    ->withProperties($request->all())
                    ->event("Update otp settings")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been update OTP Settings");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Otp Settings successfully done!'
                ], 200);
            }
            return Response::json([
                'status' => false,
                'error' => "Database Update Failed",
                'message' => "An error occurred while updating the database. Please try again later."
            ], 500);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
}
