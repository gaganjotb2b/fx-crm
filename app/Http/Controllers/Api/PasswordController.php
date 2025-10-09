<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use App\Services\EmailService;
use App\Services\password\PasswordService;
use App\Services\systems\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    // reset password
    public function reset_password(Request $request)
    {
        try {
            $password = PasswordService::reset_password();
            // update user table
            $update = User::where('id', auth()->user()->id)->update(
                [
                    'password' => Hash::make($password),
                    'admin_log' => AdminLogService::admin_log('client update password'),
                ]
            );
            // update log table
            Log::updateOrCreate(
                [
                    'user_id' => auth()->user()->id,
                ],
                [
                    'password' => encrypt($password)
                ]
            );
            // send mail
            EmailService::send_email('reset-password', [
                'user_id' => auth()->user()->id,
                'new_password' => $password,
                'account_email' => auth()->user()->email,
                'type' => 'trader'
            ]);
            if ($update) {
                // insert activity-----------------
                $user = User::find(auth()->user()->id);
                activity("Trader password reset")
                    ->causedBy(auth()->user())
                    ->withProperties(AdminLogService::admin_log())
                    ->event("Trader password reset")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been reset trader password");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Password successfully reset, please check your email'
                ], 200);
            }
            return Response::json([
                'status' => false,
                'message' => 'Password reset failed, Please try again later'
            ], 500);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error'
            ], 500);
        }
    }
    // reset transaction password
    public function reset_transaction_pin(Request $request)
    {
        try {
            $transaction_pin = PasswordService::reset_transaction_pin();
            $user = User::find(auth()->guard('api')->user()->id);
            // update user table
            $update = User::where('id', auth()->user()->id)->update(
                [
                    'transaction_password' => Hash::make($transaction_pin),
                    'admin_log' => AdminLogService::admin_log(),
                ]
            );
            // update log table
            Log::updateOrCreate(
                [
                    'user_id' => auth()->guard('api')->user()->id,
                ],
                [
                    'transaction_password' => encrypt($transaction_pin)
                ]
            );
            // send mail
            EmailService::send_email('reset-transaction-password', [
                'user_id' => auth()->guard('api')->user()->id,
                'new_pin' => $transaction_pin,
                'account_email' => auth()->user()->email,
                'type' => 'trader'
            ]);
            if ($update) {
                // insert activity log
                activity("Trader reset trnsaction pin")
                    ->causedBy(auth()->guard('api')->user())
                    ->withProperties(AdminLogService::admin_log())
                    ->event("Trader reset trnsaction pin")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been reset transaction pin");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Transaction password successfully reset'
                ], 200);
            }
            return Response::json([
                'status' => false,
                'message' => 'Transaction password reset failed, Please try again later'
            ], 500);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error'
            ], 500);
        }
    }
    // change password
    public function change_password(Request $request)
    {
        try {
            $user = User::find(auth()->guard('api')->user()->id);
            $validation_rules = [
                'old_password' => 'required',
                'new_password' => 'required|min:6|max:32',
                'confirm_password' => 'required|same:new_password',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    "error" => "Validation Error",
                    "message" => "The request data is invalid.",
                    'errors' => $validator->errors(),
                ], 400);
            }
            // return $user->password;
            // check password matched or not
            if (!Hash::check($request->input('old_password'), $user->password)) {
                return Response::json([
                    'status' => false,
                    'message' => 'Old password not matched!',
                    'errors' => ['old_password' => 'Old password not matched!'],
                ], 400);
            }
            // update user table
            $update = User::where('id', auth()->user()->id)->update([
                'password' => Hash::make($request->new_password),
                'admin_log' => AdminLogService::admin_log(),
            ]);
            // update log table
            $update_log = Log::updateOrCreate(
                [
                    'user_id' => auth()->user()->id,
                ],
                [
                    'password' => encrypt($request->new_password)
                ]
            );
            EmailService::send_email('change-password', [
                'user_id' => auth()->user()->id,
                'clientPassword' => $request->new_password,
                'account_email' => $user->email,
                'type' => 'trader'
            ]);
            if ($update) {
                // insert activity-------------------------
                activity("Trader change password")
                    ->causedBy(auth()->user())
                    ->withProperties($request->all())
                    ->event("Trader change password")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been change trader password");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Password successfully changed!',
                ], 200);
            }
            return Response::json([
                'status' => false,
                'message' => "An error occurred while updating the password. Please try again later.",
                'error' => 'Password Update Failed'
            ], 500);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => "An unexpected error occurred while processing your request.",
                'error' => "Internal Server Error",
            ], 500);
        }
    }
    // change transaction password
    public function change_transaction_pin(Request $request)
    {
        try {
            // validation check
            $validation_rules = [
                'old_pin' => 'required',
                'new_pin' => 'required|digits_between:4,6|numeric',
                'confirm_pin' => 'required|same:new_pin',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => 'Validation Error',
                    'message' => "The request data is invlaid",
                    'errors' => $validator->errors(),
                ], 400);
            }
            $user = User::find(auth()->user()->id);
            // check password matched or not
            if (!Hash::check($request->old_pin, $user->transaction_password)) {
                return Response::json([
                    'status' => false,
                    'message' => 'Old transaction pin not matched!',
                    'errors' => ['old_pin'=>'Old transaction pin not matched']
                ]);
            }
            // update user table
            $update = User::where('id', auth()->user()->id)->update([
                'transaction_password' => Hash::make($request->new_pin),
                'admin_log' => AdminLogService::admin_log('change transaction pin', ['old' => $user->transaction_password, 'new' => $request->new_pin])
            ]);
            // update log table
            $update_log = Log::updateOrCreate(
                [
                    'user_id' => auth()->user()->id,
                ],
                [
                    'transaction_password' => encrypt($request->new_pin)
                ]
            );
            EmailService::send_email('change-transaction-password', [
                'user_id' => auth()->user()->id,
                'transaction_pin' => $request->new_pin,
                'account_email' => $user->email,
                'type' => 'trader'
            ]);
            if ($update) {
                // insert activity log-----------------
                activity("Trader change transaction pin")
                    ->causedBy(auth()->user())
                    ->withProperties(AdminLogService::admin_log('change transaction pin', ['old' => $user->transaction_password, 'new' => $request->new_pin]))
                    ->event("Change transaction pin")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been changes transaction pin");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Transaction password successfully changed!'
                ], 200);
            }
            return Response::json([
                'status' => false,
                'error' => ucwords('Transaction pin changed failed'),
                'message' => "An error occurred while change the transaction pin. Please try again later.",
            ], 500);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => "An unexpected error occurred while processing your request.",
                'error' => "Internal Server Error",
            ], 500);
        }
    }
}
