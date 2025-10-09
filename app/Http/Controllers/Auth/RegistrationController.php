<?php

namespace App\Http\Controllers\Auth;

ini_set('max_execution_time', 1800);

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Mail\AdminMailChange;
use App\Mail\MailNotification;
use App\Models\admin\SystemConfig;
use App\Models\ClientGroup;
use App\Models\Country;
use App\Models\FinanceOp;
use App\Models\IB;
use App\Models\Log;
use App\Models\ManagerUser;
use App\Models\RequiredField;
use App\Models\Traders\SocialLink;
use App\Models\TradingAccount;
use App\Models\UserDescription;
use App\Models\UserOtpSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Services\accounts\OpenDemoAccountService;
use App\Services\AgeCalculatorService;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use App\Services\EmailService;
use App\Services\EmailValidationService;
use App\Services\MailNotificationService;
use App\Services\manager\ManagerService;
use App\Services\OpenLiveTradingAccountService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    // use RegistrationsUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:admin');
        $this->middleware('guest:manager');
        if (request()->is('trader/demo/registration')) {
            $this->middleware(AllFunctionService::access('open_demo_account', 'trader'));
            $this->middleware(AllFunctionService::access('trading_accounts', 'trader'));
        }
        // control ib for combined crm
        if (request()->is('ib/registration')) {
            $this->middleware(PermissionService::is_combined());
        }
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSystemRegistrationForm()
    {
        return view('auth.systems.registration');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    // **********************************************
    // trader registration
    // create live account
    // ***********************************************
    public function trader_registration(Request $request)
    {
        try {
            $country = Country::all();
            $required_fields = RequiredField::select()->first();
            $system_config = SystemConfig::select(
                'create_meta_acc',
                'social_account',
                'acc_limit',
                'platform_type'
            )->first();
            $social_account = isset($system_config->social_account) ? $system_config->social_account : 0;
            $create_meta_account = isset($system_config->create_meta_acc) ? $system_config->create_meta_acc : 0;
            $trans_pin = mt_rand(1111, 9999);
            // check request ajax
            if ($request->ajax()) {
                // trader live
                // validation step personal
                if ($request->op === 'step-persional') {
                    $validation_rules = [
                        'full_name' => 'required|max:100',
                        'email' => 'required|email',
                        'confirm_email' => 'required|email|same:email',
                        // 'date_of_birth' => 'required',
                    ];
                    if ($required_fields->phone === 1) {
                        $validation_rules += [
                            'phone' => 'required|max:20',
                            'country_code' => 'required|max:10'
                        ];
                    }
                    // if ($required_fields->phone === 1) {
                    //     $validation_rules += [
                    //         'gender' => 'required'
                    //     ];
                    // }

                    $country    =  ($required_fields->country === 1) ? 'required' : 'nullable';
                    $validation_rules['country']    = $country;
                }
                // check duplicate email across all user types
                if (User::where('email', $request->email)->exists()) {
                    return Response::json([
                        'status' => false,
                        'errors' => ['email' => 'This email is already registered. Please use a different email address.'],
                        'message' => 'This email is already registered. Please use a different email address.'
                    ]);
                }

                // check duplicate phone number across all user types
                if (User::where('phone', $request->phone)->exists()) {
                    return Response::json([
                        'status' => false,
                        'errors' => ['phone' => 'This phone number is already registered. Please use a different phone number.'],
                        'message' => 'This phone number is already registered. Please use a different phone number.'
                    ]);
                }

                // Add Abstract API email validation with Gmail account existence check
                $emailValidation = new EmailValidationService();
                $emailValidationResult = $emailValidation->isEmailValidForRegistration($request->email);

                if (!$emailValidationResult['is_valid']) {
                    $errorMessage = $emailValidationResult['message'];
                    if (!empty($emailValidationResult['suggestion'])) {
                        $errorMessage .= ' Suggestion: ' . $emailValidationResult['suggestion'];
                    }
                    
                    return Response::json([
                        'status' => false,
                        'errors' => ['email' => $errorMessage],
                        'message' => 'Email validation failed'
                    ]);
                }

                // Additional Gmail specific check
                if (str_contains($request->email, '@gmail.com')) {
                    $gmailCheck = $emailValidation->checkGmailAccountExists($request->email);
                    if (!$gmailCheck['exists']) {
                        return Response::json([
                            'status' => false,
                            'errors' => ['email' => 'This Gmail account does not exist. Please use a valid Gmail address.'],
                            'message' => 'Gmail account validation failed'
                        ]);
                    }
                }
                // trader live
                // validation step address
                if ($request->op === 'step-address') {
                    // $address    =  ($required_fields->address === 1) ? 'required|max:191' : 'nullable';
                    // $zip_code   =  ($required_fields->zip_code === 1) ? 'required|max:10' : 'nullable';
                    // $city       =  ($required_fields->city === 1) ? 'required|max:70' : 'nullable';
                    // $state      =  ($required_fields->state === 1) ? 'required' : 'nullable';
                    $country    =  ($required_fields->country === 1) ? 'required' : 'nullable';

                    $validation_rules['address']    = '';
                    $validation_rules['zip_code']   = '';
                    $validation_rules['city']       =  '';
                    $validation_rules['state']      =  '';
                    $validation_rules['country']    = $country;
                }
                // trader live
                // validation step account
                if ($request->op === 'step-account') {
                    $validation_rules['platform'] = 'required';
                    $validation_rules['account_type'] = 'required'; //<----client group i;
                    $validation_rules['leverage'] = 'required';
                }
                // trader live
                // validation step social
                if ($request->op === 'step-social') {
                    $validation_rules['skype'] = "nullable|";
                    $validation_rules['linkedin'] = "nullable|";
                    $validation_rules['facebook'] = "nullable|";
                    $validation_rules['twitter'] = "nullable|";
                    $validation_rules['twitter'] = "nullable|min:5|max:100";
                }
                // trader live
                // validation step confirm
                if ($request->op === 'step-persional') {
                    $password    =  ($required_fields->password === 1) ? 'required|min:6' : 'nullable';
                    $confirm_password    =  ($required_fields->password === 1) ? 'same:password' : 'nullable';

                    $validation_rules['password'] = $password;
                    $validation_rules['confirm_password'] = $confirm_password;
                }

                // dd($request->op);
                // trader live
                // validation faild
                $getAgeOfInput = new AgeCalculatorService();
                if ($request->op === 'step-persional' || $request->op === 'step-address' || $request->op === 'step-account' ||   $request->op    === 'step-social' || $request->op === 'step-confirm') {
                    $validator = Validator::make($request->all(), $validation_rules);
                    // trader live
                    // default validation faild
                    if ($validator->fails()) {
                        return Response::json([
                            'status' => false,
                            'errors' => $validator->errors(),
                            'message' => 'Please fix the following errors!'
                        ]);
                    }
                    // trader live
                    // age validation faild
                    // if ($getAgeOfInput->getAgeDiffer($request->date_of_birth)) {
                    //     return Response::json([
                    //         'status' => false,
                    //         'errors' => ['date_of_birth' => "Minimum age required 18 years old"],
                    //         'message' => 'Please fix the following errors!'
                    //     ]);
                    // }
                }

                // trader live
                // step personal success
                // if ($request->op === 'step-persional') {
                //     return Response::json([
                //         'persional_status' => true,
                //     ]);
                // }
                // trader live
                // step address success
                if ($request->op === 'step-address') {
                    return Response::json([
                        'address_status' => true,
                    ]);
                }
                // trader live
                // step social success
                if ($request->op === 'step-social') {
                    return Response::json([
                        'social_status' => true,
                    ]);
                }
                // trader live
                // step account success
                if ($request->op === 'step-account') {
                    return Response::json([
                        'account_status' => true,
                    ]);
                }
                $result = "";
                // trader live
                // step confirm
                if ($request->op === 'step-persional') {
                    if ($required_fields->password === 1 && !empty($request->password)) {
                        $password = $request->password;
                    } else {
                        $password = base64_encode(random_bytes(6));
                    }
                    // // trader live
                    // if (User::where('email', $request->email)->where('type', CombinedService::type())->exists()) {
                    //     return Response::json([
                    //         'status' => false,
                    //         'errors' => ['email' => 'This email already taken'],
                    //         'message' => 'This email already taken, Please try another'
                    //     ]);
                    // }
                    // crete user table
                    $user_data = [
                        'name' => $request->full_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'country_code' => $request->country_code,
                        'password' => Hash::make($password),
                        'transaction_password' => Hash::make($trans_pin),
                        'trading_ac_limit' => ($system_config->acc_limit != "") ? $system_config->acc_limit : 0,
                        'type' => 0,
                        'live_status' => 'live',
                        'client_groups'=> '',
                        'is_lead' => 0,
                        'ip_address' => request()->ip(),
                    ];
                    $user = User::create($user_data);
                    // create finance op
                    // trader live
                    FinanceOp::create([
                        'user_id' => $user->id,
                        'deposit_operation' => 1,
                        'withdraw_operation' => 1,
                        'internal_transfer' => 1,
                        'wta_transfer' => 1,
                        'trader_to_trader' => 1,
                        'trader_to_ib' => 1,
                        'ib_to_ib' => 1,
                        'ib_to_trader' => 1,
                        'kyc_verify' => 1,
                    ]);
                    // create otp settings
                    // trader live
                    UserOtpSetting::create([
                        'account_create' => 0,
                        'deposit' => 0,
                        'withdraw' => 0,
                        'transfer' => 0,
                        'user_id' => $user->id,
                    ]);
                    if ($user) {
                        // trader live
                        // log for retrieve password-------------
                        $log = Log::create([
                            'user_id' => $user->id,
                            'password' => encrypt($password),
                            'transaction_password' => encrypt($trans_pin),
                        ]);
                        // trader live
                        // user descriptions-------------------------
                        $user_description = UserDescription::create([
                            'country_id' => $request->country,
                            'address' => '',
                            'city' => '',
                            'state' => '',
                            'zip_code' => '',
                            'user_id' => $user->id,
                            'date_of_birth' => $request->date_of_birth,
                            'gender' => $request->gender,
                        ]);
                        // trader live
                        //if have referable id
                        if (!empty($request->referKey)) {
                           
                            $referral_ib = json_decode(base64_decode($request->referKey), true);
                            $referral_ib = $referral_ib['rKey'];
                            $manager = ManagerUser::where('user_id', $referral_ib)->first();
                            if ($manager) {
                                $manager_user = ManagerUser::create([
                                    'user_id' => $user->id,
                                    'manager_id' => $manager->manager_id
                                ]);
                            }
                            $refer_user = IB::create([
                                'ib_id' => $referral_ib,
                                'reference_id' => $user->id
                            ]);

                            $parent_user = User::where("id", $referral_ib)->first();
                            $user->client_groups = $parent_user->client_groups;
                            $user->save();

                        }
                        // check manager reference
                        if (isset($request->manager) && $request->manager != "") {
                            $manager_id = json_decode(base64_decode($request->manager), true);
                            $manager_user = ManagerUser::create([
                                'user_id' => $user->id,
                                'manager_id' => $manager_id['rKey']
                            ]);
                            
                            // Assign manager's client groups to new user
                            $manager = User::find($manager_id['rKey']);
                            if ($manager && !empty($manager->client_groups)) {
                                $user->client_groups = $manager->client_groups;
                                $user->save();
                                \Log::info("Manager groups assigned to new user: User ID {$user->id}, Manager ID {$manager_id['rKey']}, Groups: {$manager->client_groups}");
                            } else {
                                \Log::info("No manager groups to assign: User ID {$user->id}, Manager ID {$manager_id['rKey']}, Manager Groups: " . ($manager ? $manager->client_groups : 'Manager not found'));
                            }
                            
                            // find and assign desk manager
                            $desk_managers = ManagerService::find_desk_manager($manager_id['rKey']);
                            for ($i = 0; $i < count($desk_managers); $i++) {
                                ManagerUser::create([
                                    'user_id' => $user->id,
                                    'manager_id' => $desk_managers[$i],
                                ]);
                            }
                        }
                        // check manager reference by IB
                        if (isset($request->referKey) && $request->referKey != "") {
                            $referral_ib = json_decode(base64_decode($request->referKey), true);
                            $referral_ib = $referral_ib['rKey'];
                        }
                        // trader live
                        // create trading account
                        if ($create_meta_account == 1) {
                            $client_group = ClientGroup::find($request->account_type);
                            $trading_account = TradingAccount::create([
                                'user_id' => $user->id,
                                'comment' => 'Trader By Admin Registration #' . $request->email,
                                'client_type' => $client_group->account_category,
                                'leverage' => $request->leverage,
                                'platform' => strtoupper($request->platform),
                                'group_id' => $request->account_type,
                                'approve_status' => 1
                            ]);
                        }
                        // trader live
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
                        // trader live
                        // sending mail
                        // create activation link
                        $activation_link = url('/activation/user/' . encrypt($user->id));
                        // sending welcome mail
                        EmailService::send_email('trader-registration', [
                            'loginUrl'                   => $activation_link,
                            'activation_link'            => $activation_link,
                            'clientPassword'             => $password,
                            'password'                   => $password,
                            'clientTransactionPassword'  => $trans_pin,
                            'transaction_password'       => $trans_pin,
                            'server'                     => $request->platform,
                            'user_id' => $user->id,
                        ]);
                        //notification mail to admin
                        // MailNotificationService::notification('registration', 'trader', 1, $user->name, null);
                        MailNotificationService::admin_notification([
                            'name' => $user->name,
                            'email' => $user->email,
                            'type' => 'registration',
                            'client_type' => 'trader',
                        ]);
                        $response_status = [
                            'status' => true,
                            'user_id' => $user->id,
                            'message' => 'Profile successfully created, please check your email',
                            'userId' => encrypt($user->id)
                        ];
                        return Response::json($response_status);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'Something went wrong please try again later!'
                    ]);
                }
                // create meta account
                $user = User::find($request->user_id);
                if ($create_meta_account == 1 && $request->op === 'meta-account') {

                    $user_description = $user->user_description()->first();
                    $countries = Country::find($user_description->country_id);
                    $trading_account = TradingAccount::where('user_id', $user->id)->first();
                    $client_group = ClientGroup::find($trading_account->group_id);
                    // check if account already created
                    if ($user->email_verified_at != null) {
                        return Response::json([
                            'status' => 2,
                            'message' => 'Your profile already activated, Please try to login.'
                        ]);
                    }
                    // open trading account by service
                    $response = OpenLiveTradingAccountService::open_live_account([
                        'user_id' => $request->user_id,
                        'platform' => strtoupper($trading_account->platform),
                        'leverage' => $trading_account->leverage,
                        'account_type' => $client_group->id,
                    ], true);
                    // if respons success
                    if ($response['status'] == true) {
                        return Response::json([
                            'status' => true,
                            'message' => 'Activation success, You can login now.'
                        ]);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'Activation failed, Please reopen your mail click to activation.'
                    ]);
                } else if ($create_meta_account == 0 && $request->op === 'meta-account') {
                    $user->email_verified_at = date('Y-m-d h:i:s', strtotime('now'));
                    $update = $user->save();
                    if ($update) {
                        return Response::json([
                            'status' => true,
                            'message' => 'Activation success, You can login now.'
                        ]);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'Activation failed, Please reopen your mail click to activation.'
                    ]);
                } else {
                    $user->email_verified_at = date('Y-m-d h:i:s', strtotime('now'));
                    $update = $user->save();
                    if ($update) {
                        return Response::json([
                            'status' => true,
                            'message' => 'Activation success, You can login now.'
                        ]);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'Activation failed, Please reopen your mail click to activation.'
                    ]);
                }
            }
            // END: create operation

            // view form with data
            return view(
                'auth.traders.registration',
                [
                    'countries' => $country,
                    'create_meta_account' => $create_meta_account,
                    'social_account' => $social_account,
                    'platform' => isset($system_config->platform_type) ? $system_config->platform_type : '',
                    'referKey' => $request->refer,
                    'manager' => $request->manager,
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, Please contact for support'
            ]);
        }
    }
    // ************************************************************
    // demo trader
    // demo registration trader
    // *************************************************************
    public function demo_registration(Request $request)
    {
        $country = Country::all();
        $system_config = SystemConfig::select(
            'create_meta_acc',
            'social_account',
            'acc_limit',
            'platform_type'
        )->first();
        $social_account = isset($system_config->social_account) ? $system_config->social_account : 0;
        $create_meta_account = isset($system_config->create_meta_acc) ? $system_config->create_meta_acc : 0;
        // check request from ajax
        if ($request->ajax()) {
            // step personal validation check
            // demo trader
            if ($request->op === 'step-persional') {
                $validation_rules = [
                    'full_name' => 'required|max:100',
                    'email' => 'required|unique:users|email',
                    'confirm_email' => 'required|email|same:email',
                    'phone' => 'required|max:20',
                    'date_of_birth' => 'required',
                    'gender' => 'required',
                ];
            }
            // step address validation check
            // demo trader
            if ($request->op === 'step-address') {
                $validation_rules['address'] = 'required|max:191';
                $validation_rules['zip_code'] =  'required|max:10';
                $validation_rules['city'] =  'required|max:70';
                $validation_rules['state'] =  'required|max:70';
                $validation_rules['country'] = 'required';
            }
            // step account validation check
            // demo trader
            if ($request->op === 'step-account') {
                $validation_rules['platform'] = 'required';
                $validation_rules['account_type'] = 'required'; //<----client group i;
                $validation_rules['leverage'] = 'required';
            }
            // step social validation check
            // demo trader
            if ($request->op === 'step-social') {
                $validation_rules['skype'] = "nullable|";
                $validation_rules['linkedin'] = "nullable|";
                $validation_rules['facebook'] = "nullable|";
                $validation_rules['twitter'] = "nullable|";
                $validation_rules['twitter'] = "nullable|min:5|max:100";
            }
            // step confirm validation check
            // demo trader
            if ($request->op === 'step-confirm') {
                $validation_rules['password'] = "required";
                $validation_rules['confirm_password'] = "required";
                $validation_rules['transaction_password'] = "required";
                $validation_rules['confirm_transaction_password'] = "required";
            }
            $getAgeOfInput = new AgeCalculatorService();
            if ($request->op === 'step-persional' || $request->op === 'step-address' || $request->op === 'step-account' || $request->op === 'step-social' || $request->op === 'step-confirm') {
                $validator = Validator::make($request->all(), $validation_rules);
                // check default validaton faild
                // demo trader
                if ($validator->fails()) {
                    return Response::json([
                        'status' => false,
                        'errors' => $validator->errors(),
                        'message' => 'Please fix the following errors!'
                    ]);
                }
                // check age validation
                // demo trader
                if ($getAgeOfInput->getAgeDiffer($request->date_of_birth)) {
                    return Response::json([
                        'status' => false,
                        'errors' => ['date_of_birth' => "Minimum age required 18 years old"],
                        'message' => 'Please fix the following errors!'
                    ]);
                }
            }
            // step personal success
            // demo trader
            if ($request->op === 'step-persional') {
                return Response::json([
                    'persional_status' => true,
                ]);
            }
            // step address success
            // demo trader
            if ($request->op === 'step-address') {
                return Response::json([
                    'address_status' => true,
                ]);
            }
            // step social success
            // demo trader
            if ($request->op === 'step-social') {
                return Response::json([
                    'social_status' => true,
                ]);
            }
            // step account succcess
            // demo trader
            if ($request->op === 'step-account') {
                return Response::json([
                    'account_status' => true,
                ]);
            }
            $result = "";
            // step confirm success
            // demo trader
            if ($request->op === 'step-confirm') {
                $user_data = [
                    'name' => $request->full_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'transaction_password' => Hash::make($request->transaction_password),
                    'active_status' => (isset($request->mark_as_activated)) ? 1 : 0,
                    'trading_ac_limit' => ($system_config->acc_limit != "") ? $system_config->acc_limit : 0,
                    'type' => 0,
                    'live_status' => 'demo',
                    'active_status' => '1',
                    'ip_address' => request()->ip(),
                ];
                $user = User::create($user_data);

                if ($user) {
                    // log for retrive password-------------
                    // demo trader
                    $log = Log::create([
                        'user_id' => $user->id,
                        'password' => encrypt($request->password),
                        'transaction_password' => encrypt($request->transaction_password),
                    ]);
                    // user descriptions-------------------------
                    // demo trader
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
                    // create trading account
                    // demo trader
                    if ($create_meta_account == 1) {
                        $trading_account = TradingAccount::create([
                            'user_id' => $user->id,
                            'comment' => 'Trader By Admin Registration #' . $request->email,
                            'client_type' => 'demo',
                            'leverage' => $request->leverage,
                            'platform' => $request->platform,
                            'group_id' => $request->account_type,
                        ]);
                    }
                    // create social account
                    // demo trader
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
                    // sending mail
                    // create activation link
                    // demo trader
                    $activation_link = url('/activation/user/demo/' . encrypt($user->id)) . "?balance=" . $request->ammount;

                    $response_status = [
                        'status' => true,
                        'message' => 'You successfully created your profile',
                        'user_id' => $user->id,
                    ];
                    // seding welcome mail
                    // demo trader
                    EmailService::send_email('trader-registration', [
                        'loginUrl'                   => $activation_link,
                        'clientPassword'             => $request->password,
                        'clientTransactionPassword'  => $request->transaction_password,
                        'server'                     => $request->platform,
                        'user_id' => $user->id,
                    ]);
                    //notification mail to admin
                    // demo trader
                    // MailNotificationService::notification('demo registration', 'trader', 1, $user->name, null);
                    MailNotificationService::admin_notification([
                        'name' => $user->name,
                        'email' => $user->email,
                        'type' => 'registration',
                        'client_type' => 'trader',
                    ]);
                    $response_status['message'] = 'Profile successfully created, please check your email';
                    $response_status['activation_url'] = $activation_link;
                    return Response::json($response_status);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Somthing went wrong please try again later!'
                ]);
            }
            // create meta account
            // demo trader
            $user = User::find($request->user_id);
            if ($create_meta_account == 1 && $request->op === 'meta-account') {
                $open_account = OpenDemoAccountService::open_demo_account([
                    'user_id' => $user->id,
                ]);
                if ($open_account['status'] == true) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Account verification success, Now, you are a demo trader.'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Activation failed, Please reopen your mail click to activation.'
                ]);
            }
            // account verification without meta account
            // demo trader
            elseif ($create_meta_account == 0 && $request->op === 'meta-account') {
                $user->email_verified_at = date('Y-m-d h:i:s', strtotime('now'));
                $update = $user->save();
                if ($update) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Account verification success, Now, you are a demo trader.'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Activation failed, Please reopen your mail click to activation.'
                ]);
            }
        }
        // END: create operation
        return view(
            'auth.traders.demo-registration',
            [
                'countries' => $country,
                'create_meta_account' => $create_meta_account,
                'social_account' => $social_account,
                'platform' => isset($system_config->platform_type) ? $system_config->platform_type : '',
            ]
        );
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    // ib registration
    // ************************************************************************
    public function ib_registration(Request $request)
    {
        try {
            $country = Country::all();
            $required_fields = RequiredField::select()->first();
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
                    ];

                    if ($required_fields->phone === 1) {
                        $validation_rules += [
                            'phone' => 'required|max:20'
                        ];
                    }
                    if ($required_fields->phone === 1) {
                        $validation_rules += [
                            'gender' => 'required'
                        ];
                    }
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
                    $validation_rules['address']    = ($required_fields->address === 1) ? 'required|max:191' : 'nullable';
                    $validation_rules['zip_code']   = ($required_fields->zip_code === 1) ? 'required|max:10' : 'nullable';
                    $validation_rules['city']       =  ($required_fields->city === 1) ? 'required|max:70' : 'nullable';
                    $validation_rules['state']      =  ($required_fields->state === 1) ? 'required' : 'nullable';
                    $validation_rules['country']    = ($required_fields->country === 1) ? 'required' : 'nullable';
                }
                // ib step social validation check
                // ib regist.
                if ($request->op === 'step-social') {
                    $validation_rules['skype'] = "nullable|";
                    $validation_rules['linkedin'] = "nullable|";
                    $validation_rules['facebook'] = "nullable|";
                    $validation_rules['twitter'] = "nullable|";
                    $validation_rules['twitter'] = "nullable|min:5|max:100";
                }
                // ib step confirm validation check
                // ib regist.
                if ($request->op === 'step-confirm') {
                    $password    =  ($required_fields->password === 1 && !empty($request->password)) ? 'required|min:6' : 'nullable';

                    $validation_rules['password'] = $password;
                    $validation_rules['confirm_password'] = 'same:password';
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
                    if ($required_fields->password === 1 && !empty($request->password)) {
                        $password = $request->password;
                    } else {
                        $password = base64_encode(random_bytes(6));
                    }
                    $email_exists = User::where('email', $request->email)->first();
                    if ($email_exists) {
                        return Response::json([
                            'status' => false,
                            'message' => 'This email is already registered. Please use a different email address.'
                        ]);
                    }

                    // check for duplicate phone number across all user types
                    $phone_exists = User::where('phone', $request->phone)->first();
                    if ($phone_exists) {
                        return Response::json([
                            'status' => false,
                            'message' => 'This phone number is already registered. Please use a different phone number.'
                        ]);
                    }
                    $user_data = [
                        'name' => $request->full_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'password' => Hash::make($password),
                        'transaction_password' => Hash::make($trans_pass),
                        'trading_ac_limit' => ($system_config->acc_limit != "") ? $system_config->acc_limit : 0,
                        'type' => CombinedService::type(),
                        'live_status' => 'live',
                        'ib_group_id' => 1,
                        'combine_access' => 1,
                        'ip_address' => request()->ip(),
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
                        // ib
                        //if have referale id
                        if (!empty($request->referKey)) {
                            $referral_ib = json_decode(base64_decode($request->referKey), true);
                            $referral_ib = $referral_ib['rKey'];
                            $manager = ManagerUser::where('user_id', $referral_ib)->first();
                            if ($manager) {
                                $manager_user = ManagerUser::create([
                                    'user_id' => $user->id,
                                    'manager_id' => $manager->manager_id
                                ]);
                            }
                            $refer_ib = IB::create([
                                'ib_id' => $referral_ib,
                                'reference_id' => $user->id
                            ]);
                        }
                        // if have manager id
                        if (isset($request->manager) && $request->manager != "") {
                            $manager_id = json_decode(base64_decode($request->manager), true);
                            $manager_user = ManagerUser::create([
                                'user_id' => $user->id,
                                'manager_id' => $manager_id['rKey']
                            ]);
                        }
                        // ib sending mail
                        // create activation link
                        $activation_link = url('/ib/activation/ac/' . encrypt($user->id));

                        $response_status = [
                            'status' => true,
                            'message' => 'You successfully created your profile',
                            'user_id' => $user->id,
                        ];
                        // for email send
                        $email_status = EmailService::send_email('ib-registration', [
                            'loginUrl'                  => $activation_link,
                            'activation_link'           => $activation_link,
                            'clientPassword'            => $password, //for template v1
                            'password'                  => $password, //for template v2
                            'clientTransactionPassword' => $trans_pass, //for teplate v1
                            'transaction_password'      => $trans_pass, //for teplate v1
                            'platform'                  => $request->platform,
                            'user_id'                   => ($user) ? $user->id : '',
                        ]);
                        if ($email_status) {
                            //notification mail to admin
                            // MailNotificationService::notification('registration', 'ib', 1, $user->name, null);
                            MailNotificationService::admin_notification([
                                'name' => $user->name,
                                'email' => $user->email,
                                'type' => 'registration',
                                'client_type' => 'ib',
                            ]);
                            $response_status['message'] = 'Profile successfully created, please check your email';
                            $response_status['activation_url'] = $activation_link;
                        }
                        return Response::json($response_status);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'Something went wrong please try again later!'
                    ]);
                }
                if ($request->op === 'activation') {
                    $user = User::find($request->user_id);
                    $user->email_verified_at = date('Y-m-d h:i:s', strtotime('now'));
                    $update = $user->save();
                    if ($update) {
                        return Response::json([
                            'status' => true,
                            'message' => 'Profile successfully activated'
                        ]);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'Something went wrong please try again later'
                    ]);
                }
            }

            // END: create operation
            return view('auth.ibs.registration', [
                'countries' => $country,
                'social_account' => $social_account,
                'referKey' => $request->refer,
                'manager' => $request->manager,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            // return Response::json()
        }
    }
    // view ib registrtion success
    public function ib_success(Request $request)
    {
        return view('auth.ibs.success');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdminRegistrationForm()
    {
        return view('auth.admins.registration');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showManagerRegistrationForm()
    {
        return view('auth.managers.registration');
    }
    // ib activation
    public function ib_activation(Request $request)
    {
        return view('auth.ibs.activation');
    }

    public function admin_activation(Request $request)
    {
        return view('auth.admins.activation');
    }
    public function admin_activation_request(Request $request)
    {
        if ($request->ajax()) {
            if ($request->op === 'activation') {
                $user = User::find($request->user_id);
                $user->email_verified_at = date('Y-m-d h:i:s', strtotime('now'));
                $update = $user->save();
                if ($update) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Profile successully activated'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Somthing went wrong please try again later'
                ]);
            }
        }
    }

    public function admin_change_mail(Request $request)
    {
        return view('auth.admins.change-mail');
    }
    public function admin_change_phone(Request $request)
    {
        return view('auth.admins.change-phone');
    }
    public function admin_change_mail_req(Request $request)
    {
        if ($request->ajax()) {
            if ($request->op === 'change_email') {
                $user_id = decrypt($request->user_id);

                $user = User::find($user_id);
                $validation = Validator::make($request->all(), [
                    'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|unique:users', //unique admin
                    'password' => 'required', // Minimum eight characters, at least one uppercase, lowercase letter and special character

                ]);
                if ($validation->fails()) {
                    return Response::json(['status' => false, 'errors' => $validation->errors(), 'message' => 'Please fix the following errors']);
                }
                if (Hash::check($request->password, $user->password)) {
                    $user->email = $request->email;
                    $update = $user->save();
                    if ($update) {
                        $admin = User::select()->where('id', $user_id)->first();
                        $support_email = SystemConfig::select('support_email')->first();
                        $support_email = ($support_email) ? $support_email->support_email : default_support_email();
                        $email_data = [
                            'name'              => ($admin) ? $admin->name : config('app.name') . ' User',
                            'account_email'     => ($admin) ? $admin->email : '',
                            'admin'             => $admin->name,
                            'login_url'         => route('login'),
                            'support_email'     => $support_email,
                            'phone'             => $admin->phone,

                        ];
                        Mail::to($admin->email)->send(new AdminMailChange($email_data));
                        return Response::json([
                            'status' => true,
                            'message' => 'Mail change Success'
                        ]);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'Somthing went wrong please try again later',
                    ]);
                } else {
                    return Response::json([
                        'status' => false,
                        'message' => 'Current password not match'
                    ]);
                }
            } else if ($request->op === 'change_phone') {
                $user_id = decrypt($request->user_id);

                $user = User::find($user_id);
                $validation = Validator::make($request->all(), [
                    'phone' => 'required', //unique admin
                    'password' => 'required', // Minimum eight characters, at least one uppercase, lowercase letter and special character

                ]);
                if ($validation->fails()) {
                    return Response::json(['status' => false, 'errors' => $validation->errors(), 'message' => 'Please fix the following errors']);
                }
                if (Hash::check($request->password, $user->password)) {
                    $user->phone = $request->phone;
                    $update = $user->save();
                    if ($update) {
                        $admin = User::select()->where('id', $user_id)->first();
                        $support_email = SystemConfig::select('support_email')->first();
                        $support_email = ($support_email) ? $support_email->support_email : default_support_email();
                        $email_data = [
                            'name'              => ($admin) ? $admin->name : config('app.name') . ' User',
                            'account_email'     => ($admin) ? $admin->email : '',
                            'admin'             => $admin->name,
                            'login_url'         => route('login'),
                            'support_email'     => $support_email,
                            'phone'             => $admin->phone,

                        ];
                        Mail::to($admin->email)->send(new AdminMailChange($email_data));
                        return Response::json([
                            'status' => true,
                            'message' => 'Phone change Success'
                        ]);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'Somthing went wrong please try again later',
                    ]);
                } else {
                    return Response::json([
                        'status' => false,
                        'message' => 'Current password not match'
                    ]);
                }
            }
        }
    }

    // system registration action
    protected function createSystem(Request $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];

        // system field validation
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|unique:users', //unique system
            'password' => 'required|same:password_confirmation|regex:/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[#?!@$%^&*-]).{8,}/i', // Minimum eight characters, at least one uppercase, lowercase letter and special character
            'password_confirmation' => 'required',
        ]);
        // check validation failed or not
        if ($validation->fails()) {
            // if failed
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validation->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validation->errors()]);
            }
        } else {
            // if success
            $add_system = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'type' => 1,
                'password' => Hash::make($request->password),
            ]);
            if ($add_system) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => 'System Registration Successfull.']);
                } else {
                    return redirect()->intended('/system');
                }
            } else {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => 'System Registration Failed!']);
                } else {
                    return Redirect()->back()->with(['status' => true, 'message' => 'system Registration Failed!']);
                }
            }
        }
    }

    protected function createAdmin(Request $request)
    {

        if ($request->op == "account") {
            $validation = Validator::make($request->all(), [
                'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|unique:users', //unique admin
                'phone' => 'required|min:11|numeric',
            ]);
            // check validation failed or not
            if ($validation->fails()) {
                // if failed
                if ($request->ajax()) {
                    return Response::json(['status' => false, 'errors' => $validation->errors()]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'errors' => $validation->errors()]);
                }
            }
            session()->put('account', [
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'gender'    => $request->gender,
                'country'   => $request->country,
                'address'   => $request->address,
                'city'      => $request->city,
                'state'     => $request->state,
                'zip_code'  => $request->zip_code,
                'dob'       => $request->dob,
            ]);
        } else if ($request->op == "profile") {
            $validation = Validator::make($request->all(), [
                'platform' => 'required',
                'acc_type' => 'required',
            ]);

            // check validation failed or not
            if ($validation->fails()) {
                // if failed
                if ($request->ajax()) {
                    return Response::json(['status' => false, 'errors' => $validation->errors()]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'errors' => $validation->errors()]);
                }
            }
            session()->put('profile', [
                'approx_investment' => (isset($request->approx_investment) ? $request->approx_investment : ""),
                'platform'          => $request->platform,
                'acc_type'          => $request->acc_type,
                'leverage'          => $request->leverage,
                'est_net_income'    => (isset($request->est_net_income) ? $request->est_net_income : ""),
                'est_net_worth'     => (isset($request->est_net_worth) ? $request->est_net_worth : ""),
                'emp_info'          => $request->emp_info,
                'nob'               => $request->nob,
            ]);
        } else if ($request->op == "confirm") {
            $validation = Validator::make($request->all(), [
                'password' => 'required|same:confirm_password|regex:/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[#?!@$%^&*-]).{6,}/i', // Minimum six characters, at least one uppercase, lowercase letter and special character
                'confirm_password' => 'required',
            ]);
            // check validation failed or not
            if ($validation->fails()) {
                // if failed
                if ($request->ajax()) {
                    return Response::json(['status' => false, 'errors' => $validation->errors()]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'errors' => $validation->errors()]);
                }
            }
        }
    }

    // manager registration action
    protected function createManager(Request  $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];

        // manager field validation
        $validation = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput($data)->withErrors($validation);
        } else {
            $add_manager = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'type' => 3,
                'password' => Hash::make($request->password),
            ]);
            if ($add_manager) {
                return redirect()->intended('/manager');
            } else {
                return redirect()->route('manager.registraion') // error message for un authorize access
                    ->with('error', 'Manager Registration Failed!');
            }
        }
    }
    
        public function resendVerificationLink($id)
    {
        $user = User::where('id', $id)->first();

    
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }
    
        // Check if user has a recent resend attempt (within 50 seconds)
        $lastResendKey = 'resend_verification_' . $user->id;
        $lastResendTime = cache()->get($lastResendKey);
        
        if ($lastResendTime) {
            $timeDiff = time() - $lastResendTime;
            $remainingTime = 50 - $timeDiff;
            
            if ($remainingTime > 0) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please wait before requesting another verification email.',
                    'cooldown' => true,
                    'remaining_time' => $remainingTime
                ]);
            }
        }

        $activation_link = url('/activation/user/' . encrypt($user->id));

        // Send email
        EmailService::send_email('trader-registration', [
            'activation_link' => $activation_link,
            'user_id' => $id
        ]);

        // Store the resend time in cache for 50 seconds
        cache()->put($lastResendKey, time(), 60);

        return response()->json(['success' => true, 'message' => 'Verification email sent.']);
    }
}
