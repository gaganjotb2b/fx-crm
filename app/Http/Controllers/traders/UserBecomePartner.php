<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\Country;
use App\Models\FinanceOp;
use App\Models\IB;
use App\Models\IbGroup;
use App\Models\Log;
use App\Models\ManagerUser;
use App\Models\RequiredField;
use App\Models\Traders\SocialLink;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\UserOtpSetting;
use App\Services\AgeCalculatorService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\systems\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserBecomePartner extends Controller
{
    // user become a partner
    public function becomePartner(Request $request)
    {
        $user_description = UserDescription::where('user_id', auth()->user()->id)->first();
        $user_social = SocialLink::where('user_id', auth()->user()->id)->first();
        $country = Country::all();
        // $required_fields = RequiredField::select()->first();
        $system_config = SystemConfig::select(
            'create_meta_acc',
            'social_account',
            'acc_limit',
            'platform_type'
        )->first();
        $social_account = isset($system_config->social_account) ? $system_config->social_account : 0;
        $trans_pass = mt_rand(1111, 9999);
        // check request from ajax
        // ib regist.
        if ($request->ajax()) {
            // step personal validation check
            // ib regist.
            if ($request->op === 'step-persional') {
                $validation_rules = [
                    'full_name' => 'required|max:100',
                    'email' => 'required|email',
                    'confirm_email' => 'required|email|same:email',
                    'date_of_birth' => 'required',
                    'gender' => 'required',
                ];
            }
            // check for duplicate email across all user types
            // ib regist.
            $email_exists = User::where('email', $request->email)->first();
            if ($email_exists) {
                return Response::json([
                    'status' => false,
                    'message' => 'This email is already registered. Please use a different email address.'
                ]);
            }

            // check for duplicate phone number across all user types
            // ib regist.
            $phone_exists = User::where('phone', $request->phone)->first();
            if ($phone_exists) {
                return Response::json([
                    'status' => false,
                    'message' => 'This phone number is already registered. Please use a different phone number.'
                ]);
            }
            // ib step address validation check
            // ib regist.
            if ($request->op === 'step-address') {
                $validation_rules['address'] = 'required|max:191';
                $validation_rules['zip_code'] =  'required|max:10';
                $validation_rules['city'] =  'required|max:70';
                // $validation_rules['state'] =  'required|max:70';
                // $validation_rules['sstate'] =  'required|max:70';
                $validation_rules['country'] = 'required';
            }
            if ($request->op === 'step-social') {
                $validation_rules['skype'] = "nullable|";
                $validation_rules['linkedin'] = "nullable|";
                $validation_rules['facebook'] = "nullable|";
                $validation_rules['twitter'] = "nullable|";
            }
            if ($request->op === 'step-confirm') {
                $validation_rules['password'] = "required|min:6";
                $validation_rules['confirm_password'] = "same:password";
                // $validation_rules['transaction_password'] = "required";
                // $validation_rules['confirm_transaction_password'] = "required";
            }
            $getAgeOfInput = new AgeCalculatorService();
            if ($request->op === 'step-persional' || $request->op === 'step-address' || $request->op === 'step-account' || $request->op === 'step-social' || $request->op === 'step-confirm') {
                $validator = Validator::make($request->all(), $validation_rules);
                // check default validation faild
                // ib regist.
                if ($validator->fails()) {
                    return Response::json([
                        'status' => false,
                        'errors' => $validator->errors(),
                        'message' => 'Please fix the following errors!'
                    ]);
                }
                // check age validation
                // ib regist.
                if ($getAgeOfInput->getAgeDiffer($request->date_of_birth)) {
                    return Response::json([
                        'status' => false,
                        'errors' => ['date_of_birth' => "Minimum age required 18 years old"],
                        'message' => 'Please fix the following errors!'
                    ]);
                }
            }
            // validaton success
            // ib step personal success
            if ($request->op === 'step-persional') {
                return Response::json([
                    'persional_status' => true,
                ]);
            }
            // ib step address success
            if ($request->op === 'step-address') {
                return Response::json([
                    'address_status' => true,
                ]);
            }
            // ib step social success
            if ($request->op === 'step-social') {
                return Response::json([
                    'social_status' => true,
                ]);
            }
            // ib step confirm
            $result = "";
            if ($request->op === 'step-confirm') {
                if (!empty($request->password)) {
                    $password = $request->password;
                } else {
                    $password = base64_encode(random_bytes(6));
                }
                $ib_group = IbGroup::first();
                $user_data = [
                    'name' => $request->full_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($password),
                    'transaction_password' => Hash::make($trans_pass),
                    'trading_ac_limit' => ($system_config->acc_limit != "") ? $system_config->acc_limit : 0,
                    'type' => 4,
                    'live_status' => 'live',
                    'email_verified_at' => date("Y-m-d H:i:s"),
                    'active_status' => 1,
                    'kyc_status' => auth()->user()->kyc_status,
                    'ib_group_id' => $ib_group->id,
                    'live_status' => "live",
                    'login_status' => auth()->user()->login_status,
                    'g_auth' => auth()->user()->g_auth,
                    'email_auth' => auth()->user()->email_auth,
                    'email_verification' => auth()->user()->email_verification,
                    'commission_operation' => auth()->user()->commission_operation,
                    'tmp_pass' => auth()->user()->tmp_pass,
                    'tmp_tran_pass' => auth()->user()->tmp_tran_pass,
                ];
                $user = User::create($user_data);

                if ($user) {
                    // ib
                    // log for retrive password-------------
                    $log = Log::create([
                        'user_id' => $user->id,
                        'password' => encrypt($password),
                        'transaction_password' => encrypt($trans_pass),
                    ]);
                    // ib
                    // user descriptions-------------------------
                    $user_description = UserDescription::create([
                        'country_id' => $request->country,
                        'address' => $request->address,
                        'city' => $request->city,
                        'state' => $request->state,
                        'zip_code' => $request->zip_code,
                        'user_id' => $user->id,
                        'date_of_birth' => $request->date_of_birth,
                        'gender' => $request->gender,
                    ]);
                    UserOtpSetting::create([
                        'account_create' => 0,
                        'deposit' => 0,
                        'withdraw' => 0,
                        'transfer' => 0,
                        'user_id' => $user->id,
                    ]);
                    // finance operation
                    FinanceOp::create([
                        'withdraw_operation' => 1,
                        'ib_to_ib' => 1,
                        'ib_to_trader' => 1,
                        'user_id' => $user->id,
                    ]);
                    // ib
                    // create social account
                    if ($social_account == 1) {
                        $social = SocialLink::create([
                            'user_id' => $user->id,
                            'skype' => $request->skype,
                            'linkedin' => $request->linkedin,
                            'facebook' => $request->facebook,
                            'twitter' => $request->twitter,
                            'telegram' => $request->telegram
                        ]);
                    }
                    // // ib
                    // //if have referale id 
                    // if (!empty($request->referKey)) {
                    //     $referral_ib = json_decode(base64_decode($request->referKey), true);
                    //     $referral_ib = $referral_ib['rKey'];
                    //     $manager = ManagerUser::where('user_id', $referral_ib)->first();
                    //     if ($manager) {
                    //         $manager_user = ManagerUser::create([
                    //             'user_id' => $user->id,
                    //             'manager_id' => $manager->manager_id
                    //         ]);
                    //     }
                    //     $refer_ib = IB::create([
                    //         'ib_id' => $referral_ib,
                    //         'reference_id' => $user->id
                    //     ]);
                    // }
                    // // if have manager id
                    // if (isset($request->manager) && $request->manager != "") {
                    //     $manager_id = json_decode(base64_decode($request->manager), true);
                    //     $manager_user = ManagerUser::create([
                    //         'user_id' => $user->id,
                    //         'manager_id' => $manager_id['rKey']
                    //     ]);
                    // }
                    // ib sending mail
                    // create activation link
                    // $activation_link = url('/ib/activation/ac/' . encrypt($user->id));

                    $response_status = [
                        'status' => true,
                        'message' => 'You successfully created your profile',
                        'user_id' => $user->id,
                    ];
                    // for email send
                    $email_status = EmailService::send_email('ib-registration', [
                        'loginUrl'                  => url('/ib/login'),
                        'activation_link'           => url('/ib/login'),
                        'clientPassword'            => $password, //for template v1
                        'password'                  => $password, //for template v2
                        'clientTransactionPassword' => $trans_pass, //for teplate v1
                        'transaction_password'      => $trans_pass, //for teplate v1
                        'platform'                  => $request->platform,
                        'user_id'                   => ($user) ? $user->id : '',
                    ]);
                    if ($email_status) {
                        // //notification mail to admin
                        // MailNotificationService::notification('registration', 'ib', 1, $user->name, null);
                        // $response_status['message'] = 'Profile successfully created, please check your email';
                        // // $response_status['activation_url'] = $activation_link;
                    }

                    // Send notification to admin about IB registration request
                    NotificationService::system_notification([
                        'type' => 'ib_request',
                        'user_id' => $user->id,
                        'user_type' => 'trader',
                        'table_id' => $user->id,
                        'category' => 'client',
                        'message' => 'New IB registration request from ' . $user->name
                    ]);
                    return Response::json($response_status);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Somthing went wrong please try again later!'
                ]);
            }
        }

        // END: create operation
        return view('traders.become-a-partner', [
            'countries' => $country,
            'social_account' => $social_account,
            'referKey' => $request->refer,
            'manager' => $request->manager,
            'user_descriptions' => $user_description,
            'user_social' => $user_social,
        ]);
    }
}
