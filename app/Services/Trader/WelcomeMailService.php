<?php

namespace App\Services\Trader;

use App\Models\Log;
use App\Models\User;
use App\Services\EmailService;
use App\Services\systems\PlatformService;
use Illuminate\Support\Facades\Response;

class WelcomeMailService
{
    public static function send_welcome_mail($user_id)
    {
        try {
            $user = User::find($user_id);
            $activation_link = url('/activation/user/' . encrypt($user_id));
            $password_log = Log::where('user_id', $user_id)->first();
            $mail_status = EmailService::send_email('trader-registration', [
                'loginUrl'                   => $activation_link,
                'activation_link'                   => $activation_link,
                'clientPassword'             => decrypt($password_log->password),
                'password'             => decrypt($password_log->password),
                'clientTransactionPassword'  => decrypt($password_log->transaction_password),
                'transaction_password'  => decrypt($password_log->transaction_password),
                'server'                     => PlatformService::get_platform(),
                'user_id' => $user_id,
            ]);
            if ($mail_status) {
                // save activity log
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been send verification mail";
                activity('send welcome mail')
                    ->causedBy(1)
                    ->withProperties($user)
                    ->event('email send')
                    ->performedOn($user)
                    ->log($description);
                // end: activity log------------------
                return ([
                    'status' => true,
                    'message' => 'Mail successfully send'
                ]);
            }
            return ([
                'status' => false,
                'message' => 'Somthing went wrong please try again later!'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return ([
                'status' => false,
                'message' => 'Somthing went wrong please try again later!'
            ]);
        }
    }
    // send all clients welcome mail
    public  static function sen_all_welecome_mail()
    {
        $clients = User::where('type', 0)->get();
        $total_send = 0;
        foreach ($clients as $value) {
            $response = self::send_welcome_mail($value->id);
            $total_send = ($response['status'] == true) ? $total_send + 1 : $total_send;
        }
        if ($total_send) {
            return ([
                'status' => true,
                'message' => 'Total ' . $total_send . ' mail send',
            ]);
        }
        return ([
            'status' => false,
            'message' => ' Something wend wrong, please try again later'
        ]);
    }
}
