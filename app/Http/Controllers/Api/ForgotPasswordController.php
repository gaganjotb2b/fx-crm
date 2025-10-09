<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\OtpCode;
use App\Models\PasswordLog;
use App\Models\User;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgot_password(Request $request)
    {
        try {
            $validtor = Validator::make($request->all(), [
                'email' => 'required|exists:users,email',
            ]);
            if ($validtor->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, plese fix the following error',
                    'errors' => $validtor->errors(),
                ]);
            }
            $user = User::where(function ($query) use ($request) {
                $query->where('type', 0)
                    ->orWhere('type', 4);
            })->where('email', $request->input('email'))->first();
            if (!$user) {
                return Response::json([
                    'status' => false,
                    'message' => 'User not found with this email'
                ]);
            }
            // check user is blocked
            if ($user->active_status != 1) {
                return Response::json([
                    'status' => false,
                    'message' => 'The user is blocked, please contact for support'
                ]);
            }
            $user->otpCode()->delete();
            $data = [
                'code' => mt_rand(100000, 999999),
                'user_id' => $user->id,
                'email' => $user->email,
                'type' => 'forgot_password',
                'properties' => json_encode(['email' => $request->input('email')])
            ];
            OtpCode::create($data);
            EmailService::send_email('otp-verification', [
                'account_email' => $user->email,
                'otp' => $data['code'],
                'user_id' => $user->id,
                'name' => $user->name,
            ]);
            return Response::json([
                'status' => true,
                'message' => 'We sending the OTP code to your email ' . $request->input('email') . ', please check your mail',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // check otp
    public function otp_check(Request $request)
    {
        try {
            $validtor = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'code' => 'required|exists:otp_codes,code',
            ]);
            if ($validtor->fails()) {
                // count wrong input
                $code = OtpCode::where('type', 'forgot_password')
                    ->where('email', $request->input('email'))->first();
                if ($code) {
                    $code->action_count = $code->action_count + 1;
                    $code->save();
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following error',
                    'error' => $validtor->errors(),
                ]);
            }
            $code = OtpCode::where('code', $request->input('code'))->where('type', 'forgot_password')->first();
            if (!$code) {
                return Response::json([
                    'status' => false,
                    'message' => 'Code not exists, please enter correct code'
                ]);
            }
            // check wrong input
            if ($code->action_count > 3) {
                $code->delete();
                return Response::json([
                    'status' => false,
                    'message' => 'You try more than three times, the OTP is not work now'
                ]);
            }
            // check otp code is expired or not
            // check if it does not expired: the time is one hour
            if ($code->created_at->addMinutes(1) < Carbon::now()) {
                $code->delete();
                return Response::json(
                    [
                        'status' => false,
                        'message' => 'Code is expired'
                    ],
                    422
                );
            }
            return Response::json([
                'status' => true,
                'code' => encrypt($code->code),
                'message' => 'Verification successfully done'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // set new password
    public function set_new_password(Request $request)
    {
        try {
            $validtor = Validator::make($request->all(), [
                'code' => 'required',
                'password' => 'required|min:6|same:confirm_password',
                'confirm_password' => 'required|min:6',
            ]);
            if ($validtor->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following error',
                    'errors' => $validtor->errors(),
                ]);
            }
            $code = OtpCode::where('code', decrypt($request->input('code')))
                ->where('type', 'forgot_password')
                ->with(['user', 'user.secureLog'])
                ->first();
            if (!$code) {
                return Response::json([
                    'status' => false,
                    'message' => 'Data got with invalid code'
                ]);
            }
            // check the password already used or not
            $password_log = PasswordLog::where('user_id', $code->user_id)
                ->where('code', $request->input('password'))->exists();
            // return $password_log;
            if ($password_log) {
                return Response::json([
                    'status' => false,
                    'message' => 'You can not set the old password again, please try another'
                ]);
            }
            $user = $code->user;
            $user->password = Hash::make($request->input('password'));
            $update = $user->save();
            // log password
            $log = $code->user->secureLog;
            Log::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'password' => encrypt($request->input('password'))
                ]
            );
            if ($update) {
                // add password to log
                PasswordLog::create([
                    'user_id' => $user->id,
                    'code' => decrypt($log->password),
                ]);
                $code->delete();
                return Response::json([
                    'status' => true,
                    'message' => 'Password successfully changed'
                ]);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
}
