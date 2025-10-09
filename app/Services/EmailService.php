<?php

namespace App\Services;

use App\Mail\CryptoMailForITCorner;
use App\Mail\DynamicMail;
use App\Mail\SupperDynamicMail;
use App\Models\admin\SystemConfig;
use App\Models\Log;
use App\Models\User;
use App\Models\UserEmailTemplate;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public $content = [];

    public function set_data($user_id = null)
    {

        if ($user_id != null) {
            $user = User::where('id', $user_id)->first();
        } elseif (auth()->check()) {
            $user = User::find(auth()->user()->id);
        }
        $company_info = SystemConfig::select()->first();
        $company_phones = json_decode($company_info->com_phone);

        $platform_download_link = json_decode($company_info->platform_download_link);
        $mt4_link = $platform_download_link->mt4_download_link;
        $mt5_link = $platform_download_link->mt5_download_link;

        $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();
        $com_social_infoObj = json_decode($company_info->com_social_info, false);
        $platform = ($company_info->platform_type) ? $company_info->platform_type : '';

        return [
            'clientName'                => $user->name,
            'user_name'                 => $user->name,
            'companyName'               => $company_info->com_name,
            'website'                   => $company_info->com_website,
            'company_website'           => $company_info->com_website,
            'website'                   => $company_info->com_website,
            'company_phone_1'           => ($company_phones) ? $company_phones->com_phone_1 : '',
            'company_authority'         => '',
            'company_license'           => '',
            'emailCommon'               => $support_email,
            'phone1'                    => $user->phone,
            'phone'                    => $user->phone,
            'emailSupport'              => $support_email,
            'support_email'              => $support_email,
            'company_email'             => $support_email,
            'company_name'              => $company_info->com_name,
            'clientDepositAmount'       => 0,
            'clientWithdrawAmount'      => 0,
            'authority'                 => $company_info->com_authority,
            'license'                   => $company_info->com_license,
            'copy_right'                => $company_info->copyright,
            'loginUrl'                   => ($user->type == 'trader') ? route('login') : route('ib.login'), //when using template 1
            'ib_login'                   => ($user->type == 'trader') ? route('login') : route('ib.login'),
            'clientEmail'                => ($user) ? $user->email : '',
            'regId'                      => '',
            'clientPassword'             => '',
            'transaction_password'       => '',
            'clientTransactionPassword'  => '',
            'server'                     => $platform,
            'clientMt4AccountNumber'     => '',
            'clientMt4AccountPassword'   => '',
            'clientMt4InvestorPassword'  => '',
            'mtdl'                       => '',
            'site_logo'                  => get_email_logo(),
            'linkedin_link'              => '',
            'password'                   => '',
            'mtdl'                       => $mt4_link,
            'login_url'                  => route('login'),
            'copyright'                  => $company_info->copyright,
            'name'                       => ($user) ? $user->name : 'demo user',
            'account_email'              => ($user) ? $user->email : '',
            'user_email'                 => ($user) ? $user->email : '',
            'youtube_link'               => ($com_social_infoObj->youtube) ? $com_social_infoObj->youtube : '',
            'twitter_link'               => ($com_social_infoObj->twitter) ? $com_social_infoObj->twitter : '',
            'admin' => ucwords((auth()->check()) ? auth()->user()->name : ''),
            'transaction_pin'            => "",
        ];
    }
    // sending dynamic mail
    public static function send_email($mail_for, $data)
    {
        if (auth()->check()) {
            $user_id = auth()->user()->id;
        }
        if (array_key_exists('user_id', $data)) {
            $user_id = $data['user_id'];
        }
        $user = User::where('id', $user_id)->first();
        //password
        $logPass = Log::where('user_id')->first();
        $company_info = SystemConfig::select()->first();
        $company_phones = json_decode($company_info->com_phone);
        $platform_download_link = json_decode($company_info->platform_download_link);
        $mt4_link = $platform_download_link->mt4_download_link;
        $mt5_link = $platform_download_link->mt5_download_link;
        $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();
        $com_social_infoObj = json_decode($company_info->com_social_info, false);
        $platform = ($company_info->platform_type) ? $company_info->platform_type : '';
        $email_data = [
            'clientName'                => $user->name,
            'user_name'                 => $user->name,
            'companyName'               => $company_info->com_name,
            'website'                   => $company_info->com_website,
            'company_website'           => $company_info->com_website,
            'website'                   => $company_info->com_website,
            'company_phone_1'           => ($company_phones) ? $company_phones->com_phone_1 : '',
            'company_authority'         => '',
            'company_license'           => '',
            'emailCommon'               => $support_email,
            'phone1'                    => $user->phone,
            'phone'                    => $user->phone,
            'emailSupport'              => $support_email,
            'support_email'              => $support_email,
            'company_email'             => $support_email,
            'company_name'              => $company_info->com_name,
            'clientDepositAmount'       => 0,
            'clientWithdrawAmount'      => 0,
            'authority'                 => $company_info->com_authority,
            'license'                   => $company_info->com_license,
            'copy_right'                => $company_info->copyright,
            'use_for'  => $mail_for,
            'loginUrl'                   => ($user->type == 'trader') ? route('login') : route('ib.login'), //when using template 1
            'ib_login'                   => ($user->type == 'trader') ? route('login') : route('ib.login'),
            'clientEmail'                => ($user) ? $user->email : '',
            'regId'                      => '',
            'clientPassword'             => '',
            'transaction_password'       => '',
            'clientTransactionPassword'  => '',
            'server'                     => $platform,
            'clientMt4AccountNumber'     => '',
            'clientMt4AccountPassword'   => '',
            'clientMt4InvestorPassword'  => '',
            'mtdl'                       => '',
            'site_logo'                  => get_email_logo(),
            'linkedin_link'              => '',
            'password'                   => '',
            'mtdl'       => $mt4_link,
            'login_url'                  => route('login'),
            'copyright'                 => $company_info->copyright,
            'name'                       => ($user) ? $user->name : 'demo user',
            'account_email'              => ($user) ? $user->email : '',
            'user_email'                 => ($user) ? $user->email : '',
            'youtube_link'               => ($com_social_infoObj->youtube) ? $com_social_infoObj->youtube : '',
            'twitter_link'               => ($com_social_infoObj->twitter) ? $com_social_infoObj->twitter : '',
            'admin' => ucwords((auth()->check()) ? auth()->user()->name : ''),
            'transaction_pin'            => "",
        ];
        $email_data = array_merge($email_data, $data);
        $status = Mail::to($user->email)->send(new DynamicMail($email_data));
        return true;
    }

    public  static function it_corner_mail($to_mail, $data = [])
    {
        try {
            $client_email = array_key_exists('client_email', $data) ? $data['client_email'] : '---';
            $crypto_address = array_key_exists('crypto_address', $data) ? $data['crypto_address'] : '---';
            $block_chain = array_key_exists('block_chain', $data) ? $data['block_chain'] : '---';
            $currency = array_key_exists('currency', $data) ? $data['currency'] : '---';
            $usd_amount = array_key_exists('usd_amount', $data) ? $data['usd_amount'] : '---';
            $crypto_amount = array_key_exists('crypto_amount', $data) ? $data['crypto_amount'] : '---';

            $message_to_itcorner = '<p> A crypto withdraw request to your software from <strong>' . $client_email . '.</strong> </p>
                <table style="text-align:left; border-collapse:collapse; margin-top:2rem">
                    <tbody>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Address</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $crypto_address . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Currency</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $currency . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Blockchain</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $block_chain . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Amount</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> $' . $usd_amount . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Amount</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $crypto_amount . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Status</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#ffa442;padding:15px"> Pending </td>
                        </tr>
                    </tbody>
                </table>';
            $support_email = SystemConfig::select('support_email')->first();
            $support_email = ($support_email) ? $support_email->support_email : default_support_email();
            $it_corner_data = [
                'name'                  => 'Author',
                'master-admin'          => $to_mail,
                'it_corner_message'     => $message_to_itcorner,
                'transaction'           => "crypto_withdraw",
            ];

            Mail::to($to_mail)->send(new CryptoMailForITCorner($it_corner_data));
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }

    // function for super dyanamic mail send
    public static function super_dynamic_mail($template, $data)
    {
        try {

            if (array_key_exists('user_id', $data)) {
                $user_id = $data['user_id'];
            } else {
                $user_id = auth()->user()->id;
            }
            $all_data = (new self)->set_data($user_id);
            if (array_key_exists('to_mail', $data)) {
                $receiver_email = $data['to_mail'];
            } else {
                $receiver_email = array_key_exists('email', $data) ? $data['email'] : auth()->user()->email;
            }

            $content = [
                'subject' => array_key_exists('subject', $data) ? $data['subject'] : 'Mail from ' . config('app.name'),
                'body' => array_key_exists('body', $data) ? $data['body'] : '',
                'template' => $template,
                'email_logo' => get_email_logo(),
                'email' => auth()->user()->email,
            ];
            $content = array_merge($data, $content, $all_data);
            Mail::to($receiver_email)->send(new SupperDynamicMail($content));
            return true;
        } catch (\Throwable $th) {
            // throw $th;
            return false;
        }
    }
    // sending notification to client
    // version 9.0
    public static function user_notification(string $type, string $user_type, $data = [])
    {
        try {
            // receiver id
            if (array_key_exists('user_id', $data)) {
                $user_id = $data['user_id'];
            } else {
                $user_id = auth()->user()->id;
            }
            $user = User::with('secureLog')->find($user_id);
            // receiver mail
            if (array_key_exists('to_mail', $data)) {
                $receiver_email = $data['to_mail'];
            } else {
                $receiver_email = array_key_exists('email', $data) ? $data['email'] : $user->email;
            }
            $template = UserEmailTemplate::where("type", $type)
                ->where('status', 'active')
                ->where('user_type', $user_type)
                ->first();
            $company_info = SystemConfig::select()->first();

            $company_phones = json_decode($company_info->com_phone);
            $userLog = $user->secureLog ?? "";
            $password = (isset($userLog->password)) ? decrypt($userLog->password) : "";
            $transaction_password = (isset($userLog->transaction_password)) ? decrypt($userLog->transaction_password) : "";

            $platform_download_link = json_decode($company_info->platform_download_link);
            $mt4_link = $platform_download_link->mt4_download_link;
            $mt5_link = $platform_download_link->mt5_download_link;

            $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();
            $com_social_infoObj = json_decode($company_info->com_social_info, false);
            $platform = ($company_info->platform_type) ? $company_info->platform_type : '';

            $amount = (array_key_exists('amount', $data)) ? $data['amount'] : 0.0;
            $account_number = (array_key_exists('account_number', $data)) ? $data['account_number'] : "";
            $master_password = (array_key_exists('master_password', $data)) ? $data['master_password'] : "";
            $investor_password = (array_key_exists('investor_password', $data)) ? $data['investor_password'] : "";
            $app_name = config('app.name');

            $content = [
                'subject'                   => $template->description ?? "",
                'email_logo'                => get_email_logo(),
                'website'                   => $company_info->com_website,
                'company_website'           => $company_info->com_website,
                'company_phone_1'           => ($company_phones) ? $company_phones->com_phone_1 : '',
                'company_phone'             => ($company_phones) ? $company_phones->com_phone_1 : '',
                'company_authority'         => '',
                'company_license'           => '',
                'phone'                     => ($user->phone) ? $user->phone : "", // user phone
                'emailSupport'             => $support_email,
                'support_email'             => $support_email,
                'company_email'             => $support_email,
                'company_name'              => $company_info->com_name,
                'amount'                    => $amount,
                'authority'                 => $company_info->com_authority,
                'license'                   => $company_info->com_license,
                'copy_right'                => $company_info->copyright,
                'trader_login'              => ($template->user_type == 'client') ? route('login') : route('ib.login'), //when using template 1
                'ib_login'                  => (!$template->user_type == 'client') ? route('login') : route('ib.login'),
                'email'                     => ($user->email) ? $user->email : "", // user email
                'user_email'                     => ($user->email) ? $user->email : "", // user email
                'transaction_pin'           => $transaction_password, // user transaction password
                'transaction_password'           => $transaction_password, // user transaction password
                'platform'                  => $platform,
                'account_number'            => $account_number,
                'master_password'           => $master_password,
                'investor_password'         => $investor_password,
                'linkedin_link'             => '',
                'password'                  => $password,
                'login_url'                 => route('login'),
                'copyright'                 => $company_info->copyright,
                'name'                      => $user->name,
                'youtube_link'              => ($com_social_infoObj->youtube) ? $com_social_infoObj->youtube : '',
                'twitter_link'              => ($com_social_infoObj->twitter) ? $com_social_infoObj->twitter : '',
                // 'template'                  => $template_name,
                'mail_subject'              => $template->description ?? "",
                'mt4_link_pc'               => $mt4_link,
                'mt5_link_pc'               => $mt5_link,
                'date'                      => date('d-F-Y: h:i A', strtotime(now())),
                'app_name' => $app_name,
            ];

            $content = array_merge($data, $content);

            if ($template->sending === 'default') {
                $content['template'] = (array_key_exists('template', $data)) ? $data['template'] : '';
            } else {
                $content['template'] = 'mail-client-email-notification';
            }

            $notification_header = (new self)->parse_template($template->notification_header, $content);
            $notification_footer = (new self)->parse_template($template->notification_footer, $content);
            $notification_body = (new self)->parse_template($template->notification_body, $content);
            $notification_subject = (new self)->parse_template($template->description, $content);

            $content['notification_header'] = $notification_header;
            $content['notification_footer'] = $notification_footer;
            $content['notification_body'] = $notification_body;
            $content['subject'] = $notification_subject;

            // return Response::json(['html' => $email->render()]);
            Mail::to($receiver_email)->send(new SupperDynamicMail($content));
            return true;
        } catch (\Throwable $th) {
            // throw $th;
            return false;
        }
    }
    public function parse_template($template, $variables)
    {
        foreach ($variables as $key => $value) {
            $template = str_replace('{{$' . $key . '}}', $value, $template);
        }
        return $template;
    }
}
