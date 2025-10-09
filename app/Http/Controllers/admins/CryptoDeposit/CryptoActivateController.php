<?php

namespace App\Http\Controllers\admins\CryptoDeposit;

use App\Http\Controllers\Controller;
use App\Models\CryptoAddress;
use App\Models\User;
use Illuminate\Http\Request;

class CryptoActivateController extends Controller
{
    public function CryptoActivate(Request $request)
    {
        $user = User::select('email')->where('email', $request->key)->first();
        $ipaddress = request()->ip();
        $final_activation_status = "Now it's waiting for activate";
        //change status

        if ($user !== null) {
            $updated = CryptoAddress::where('token', $request->token)->update([
                'verify_1' => 1,
                'verify_1_at' => date('Y-m-d h:i:s', strtotime('now')),
                'verify_1_ip' => $ipaddress
            ]);

            if ($updated) {
                $check_verify = CryptoAddress::where('token', $request->token)
                    ->where(function ($query) {
                        $query->where('verify_1', 1)
                            ->where('verify_2', 1);
                    });
                if ($check_verify->exists()) {
                    $check_verify = $check_verify->first();
                    $check_verify->status = 1;
                    $final_activation = $check_verify->save();
                    if ($final_activation) {
                        $final_activation_status = 'Crypto address successfully activated';
                    }
                }
                $success = '<h1 style="color:green">Verification Completed</h1>';
                $message = '<div style="margin:10% 15%; text-align:center">
                                <img src="' . get_user_logo() . '" alt="' . config('app.name') . '">
                                ' . $success . '
                                <p>Thank you so much, For crypto verification. ' . $final_activation_status . '</p>
                            </div>';
                return $message;
            } else {
                $success = '<h1 style="color:red">Verification Failed! Please try again later.</h1>';
                $message = '<div style="margin:10% 15%; text-align:center">
                            <img src="' . get_user_logo() . '" alt="' . config('app.name') . '">
                            ' . $success . '
                        </div>';
                return $message;
            }
        } elseif ($request->key === 'gainxplus1@gmail.com') {

            $updated_it_corner = CryptoAddress::where('token', $request->token)->update([
                'verify_2' => 1,
                'verify_2_at' => date('Y-m-d h:i:s', strtotime('now')),
                'verify_2_ip' => $ipaddress
            ]);
            if ($updated_it_corner) {
                $check_verify = CryptoAddress::where('token', $request->token)
                    ->where(function ($query) {
                        $query->where('verify_1', 1)
                            ->where('verify_2', 1);
                    });
                if ($check_verify->exists()) {
                    $check_verify = $check_verify->first();
                    $check_verify->status = 1;
                    $final_activation = $check_verify->save();
                    if ($final_activation) {
                        $final_activation_status = 'Crypto address successfully activated';
                    }
                }

                $success = '<h1 style="color:green">Verification Completed</h1>';
                $message = '<div style="margin:10% 15%; text-align:center">
                                <img src="' . get_user_logo() . '" alt="' . config('app.name') . '">
                                ' . $success . '
                                <p>Thank you so much, For crypto verification. ' . $final_activation_status . '</p>
                            </div>';
                return $message;
            } else {
                $success = '<h1 style="color:red">Verification Failed! Please try again later.</h1>';
                $message = '<div style="margin:10% 15%; text-align:center">
                            <img src="' . get_user_logo() . '" alt="' . config('app.name') . '">
                            ' . $success . '
                           
                        </div>';
                return $message;
            }
        } else {
            $success = '<h1 style="color:red">Verification Failed! Please try again later.</h1>';
            $message = '<div style="margin:10% 15%; text-align:center">
                            <img src="' . get_user_logo() . '" alt="' . config('app.name') . '">
                            ' . $success . '
                            
                        </div>';
            return $message;
        }
    }
}
