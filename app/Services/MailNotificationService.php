<?php

namespace App\Services;

use App\Mail\MailNotification;
use App\Models\admin\SystemConfig;
use App\Models\AdminNotification;
use App\Models\ManagerUser;
use App\Models\Notification;
use App\Models\SoftwareSetting;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class MailNotificationService
{

    public function __call($name, $data)
    {
        if ($name == 'notification') {
            return $this->get_notification($data[0], $data[1], $data[2], $data[3], $data[4], (array_key_exists(5, $data)) ? $data[5] : null, (array_key_exists(6, $data)) ? $data[6] : null);
        }
    }
    public static function __callStatic($name, $data)
    {
        if ($name == 'notification') {
            return (new self)->get_notification($data[0], $data[1], $data[2], $data[3], $data[4], (array_key_exists(5, $data)) ? $data[5] : null, (array_key_exists(6, $data)) ? $data[6] : null);
        }
    }

    private function get_notification($type, $user_type, $status, $username, $request_amount = null, $user_id = null)
    {
        $notification = Notification::where([
            ['type', $type],
            ['user_type', $user_type],
            ['status', $status]
        ])->first();
        if ($notification) {
            $company_info = SystemConfig::select()->first();
            $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();
            $email_data = [
                'clientName'                => $username,
                'companyName'               => $company_info->com_name,
                'website'                   => $company_info->com_website,
                'emailCommon'               => $support_email,
                'emailSupport'              => $support_email,
                'amount'                    => $request_amount,
                'authority'                 => $company_info->com_authority,
                'license'                   => $company_info->com_license,
                'copy_right'                => $company_info->copyright,
                'notification_type'         => $notification->type,
                'admin_name'                => 'Admin'
            ];
            if ($notification->email != '') {
                Mail::to($notification->email)->send(new MailNotification($email_data));
            } else {
                $admins = User::select('email')->where('type', 2)->where('active_status', 1)->where('email_verified_at', '!=', null)->get();
                foreach ($admins as $admin) {
                    $email = $admin->email;
                    Mail::to($email)->send(new MailNotification($email_data));
                }
            }
            // get account manager email
            if ($user_id != null) {
                $managers = ManagerUser::where('user_id', $user_id)
                    ->where('group_type', 1)
                    ->join('managers', 'manager_users.manager_id', '=', 'managers.user_id')
                    ->join('users','manager_users.manager_id','=','users.id')
                    ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
                    ->select('users.email')
                    ->first();
                if ($managers) {
                    Mail::to($managers->email)->send(new MailNotification($email_data));
                }
            }
        }
    }
    // send notification to admin
    public static function admin_notification($data = [])
    {
        try {
            $table = '<table>';
            if (array_key_exists('amount', $data)) {
                $table .= '<tr>
                    <th>Amount</th>
                    <th>:</th>
                    <th>' . $data['amount'] . '</th>
                </tr>';
            }
            if (array_key_exists('name', $data)) {
                $table .= '<tr>
                    <th>Name</th>
                    <th>:</th>
                    <th>' . $data['name'] . '</th>
                </tr>';
            }
            if (array_key_exists('email', $data)) {
                $table .= '<tr>
                    <th>Email</th>
                    <th>:</th>
                    <th>' . $data['email'] . '</th>
                </tr>';
            }
            if (array_key_exists('crypto_address', $data)) {
                $table .= '<tr>
                    <th>Crypto address</th>
                    <th>:</th>
                    <th>' . $data['crypto_address'] . '</th>
                </tr>';
            }
            $table .= '</table>';

            // get settings from adminnotification table
            $notification = Notification::where('type', $data['type'])->where('user_type', $data['client_type'])->first();
            $notification_name = str_replace(' ', '', $data['client_type'] . $data['type']);

            $admins = AdminNotification::where(function ($q) {
                $q->where('users.type', 2)->orWhere('users.type', 1);
            })->where('users.active_status', 1)
                ->join('users', 'admin_notifications.admin_id', '=', 'users.id')->select('admin_notifications.*')->get();

            // get all admin from adminitstration groups

            $admin_email = [];
            $admin_id = [];
            // $notifification_ruls = $admins[0]->nofitication_ruls;
            // return $admins;
            foreach ($admins as $value) {
                $notifification_ruls = json_decode($value->nofitication_ruls);
                if ($notifification_ruls->{$notification_name} === 'on') {
                    array_push($admin_email, $value->notification_email);
                    array_push($admin_id, $value->admin_id);
                }
            }
            if (array_key_exists('type', $data) && $data['type'] === 'withdraw') {
                $group_admin = User::where('group_name', 'Administration')
                    ->whereNotIn('users.id', $admin_id)
                    ->where('users.active_status', 1)
                    ->join('admins', 'users.id', '=', 'admins.user_id')
                    ->join('admin_groups', 'admins.group_id', '=', 'admin_groups.id')->get();
                foreach ($group_admin as $value) {
                    array_push($admin_email, $value->email);
                }
            }


            $temp_version = SoftwareSetting::select('email_template')->first();
            if ($temp_version) {
                $temp_dir = ($temp_version->email_template === 'v2') ? 'email/email2' : 'email';
            } else {
                $temp_dir = 'email/email2';
            }
            $email_data = [
                'admin_name' => 'Admin',
                'notification_body' => $notification->notification_body,
                'notification_table' => $table,
                'notification_footer' => $notification->notification_footer,
                'mail_subject' => $notification->description,
                'notification_header' => $notification->notification_header,
                'emailSupport' => ''
            ];
            $mail_subject = $notification->description;
            $mail_template = $temp_dir . '.mail-user-notification';
            if (Mail::send($mail_template, $email_data, function ($message) use ($admin_email, $mail_subject) {
                $message->to($admin_email)->subject($mail_subject);
            })) {
                return true;
            }
            return false;
            // get notification from notificaton table
        } catch (\Throwable $th) {
            // throw $th;
            return false;
        }
    }
}
