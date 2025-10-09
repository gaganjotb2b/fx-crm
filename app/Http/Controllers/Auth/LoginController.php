<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPverificationMail;
use App\Models\admin\SystemConfig;
use App\Models\Log;
use App\Models\LoginAttempt;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\EmailService;
use App\Services\GoogleAuthenticator;
use App\Services\PermissionService;
use App\Services\systems\VersionControllService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Models\Activity as ModelsActivity;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // controll ib for combined crm
        if (request()->is('ib')) {
            $this->middleware(PermissionService::is_combined());
        }
    }
    // logout
    public function logoutMethod(Request $request)
    {
        $url = "/";
        if (auth()->user()->type === "ib") {
            $url = "ib.login";
        } else if (auth()->user()->type === "admin") {
            $url = "admin.login";
        } else if (auth()->user()->type === "manager") {
            $url = "manager.login";
        } else if (auth()->user()->type === "system") {
            $url = "system.login";
        } else {
            $url = "trader.login";
        }
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return redirect()->route($url);
    }
    // view trader login form
    public function showTraderLoginForm()
    {
        return view(VersionControllService::get_login_theme());
    }
    // view trader login form
    public function showIbLoginForm()
    {
        return view(VersionControllService::get_login_theme('ib'));
    }
    // view system login form
    public function showSystemLoginForm()
    {
        return view('auth.systems.login');
    }
    // view admin login form
    public function showAdminLoginForm()
    {
        return view('auth.admins.login');
    }
    // view system login form
    public function showManagerLoginForm()
    {
        return view('auth.managers.login');
    }
    // resend verification code
    public function resendVerificationCode(Request $request, $v_email)
    {
        $email = $v_email;
        $user = User::where('email', $email)->first();

        // otp sending to user email
        $company_info = SystemConfig::select()->first();
        $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();

        // create otp
        $otp = random_int(100000, 999999);
        $update_secret_key = User::where('email', $v_email)->update([
            'secret_key' => $otp
        ]);
        if ($update_secret_key) {

            EmailService::send_email('otp-verification', [
                'account_email' => $user->email,
                'otp' => $otp,
                'user_id' => $user->id,
                'name' => $user->name,
            ]);
            return Response::json([
                'status'    => true,
                'message'   => "OTP Code Is Successfully Send.",
            ]);
        }
    }
    // login form for trader
    public function traderLogin(Request $request)
    {
        $input = $request->all();
        $validation_rules = [
            'email' => 'required|email',
            'password' => 'required|max:32',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()]);
            }
        }
        $email = $request->email;
        $user = User::where('email', $email)->where('type', 0)->first();

        if (!empty($user->type)) { //if user type not empty
            if ($user->active_status == 1) {
                if (strtolower(trim($user->live_status)) === 'live') {
                    // START => check password mismatch
                    if (Hash::check($request->password, $user->password)) {
                        if ($user->email_verified_at != null && $user->type === 'trader') { // if email_verified_at not null
                            // check auth
                            if ($user->email_auth === 1 && $request->request_form == 'login_form') {
                                // otp sending to user email
                                $company_info = SystemConfig::select()->first();
                                $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();
                                // create otp
                                $otp = random_int(100000, 999999);
                                $update_secret_key = User::where('email', $request->email)->where('type', 0)->update([
                                    'secret_key' => $otp
                                ]);
                                if ($update_secret_key) {
                                    EmailService::send_email('otp-verification', [
                                        'account_email' => $user->email,
                                        'otp' => $otp,
                                        'user_id' => $user->id,
                                        'name' => $user->name,
                                    ]);

                                    return Response::json([
                                        'status' => true,
                                        'message' => "OTP Code Is Successfully Send.",
                                        'email' => $request->email,
                                        'password' => $request->password,
                                        'modal'     => "mail-verification-form",
                                    ]);
                                } else {
                                    return Response::json([
                                        'status' => false,
                                        'message' => 'Failed To Update Secret Key!',
                                    ]);
                                }
                            } elseif ($user->g_auth === 1 && $request->request_form == 'login_form') {
                                return Response::json([
                                    'status' => true,
                                    'email' => $request->email,
                                    'password' => $request->password,
                                    'modal'     => "google-verification-form",
                                ]);
                            } elseif ($request->request_form == 'mail_verify') {
                                $v_code = $request->v_code1 . $request->v_code2 . $request->v_code3 . $request->v_code4 . $request->v_code5 . $request->v_code6;

                                if ($user->secret_key === $v_code) {
                                    if (empty($request->remember_me)) {
                                        User::where('email', $input['email'])->where('type', 0)->update(['remember_token' => ""]);
                                    }
                                    if (auth()->attempt([
                                        'email' => $input['email'],
                                        'password' => $input['password'],
                                        'type' => 0,
                                    ], isset($request->remember_me)) && auth()->user()->type == 'trader') { //check login
                                        $request->session()->regenerate();
                                        return Response::json([
                                            'status' => true,
                                            'message' => 'You are successfully logged in.'
                                        ]);
                                    } else {
                                        return Response::json([
                                            'status' => false,
                                            'message' => 'User name or password error!'
                                        ]);
                                    }
                                } else {
                                    return Response::json([
                                        'status' => false,
                                        'message' => 'Incorrect Verification Key!'
                                    ]);
                                }
                            } elseif ($request->request_form == 'google_verify') {
                                $v_code = $request->v_code1 . $request->v_code2 . $request->v_code3 . $request->v_code4 . $request->v_code5 . $request->v_code6;
                                $secret_key = $user->secret_key;
                                $ga = new GoogleAuthenticator();
                                $checkResult = $ga->verifyCode($secret_key, $v_code, 2);
                                if ($checkResult) {
                                    if (empty($request->remember_me)) {
                                        User::where('email', $input['email'])->where('type', 0)->update(['remember_token' => ""]);
                                    }
                                    if (auth()->attempt([
                                        'email' => $input['email'],
                                        'password' => $input['password'],
                                        'type' => 0,
                                    ], isset($request->remember_me)) && auth()->user()->type == 'trader') { //check login
                                        $request->session()->regenerate();
                                        return Response::json([
                                            'status' => true,
                                            'message' => 'You are successfully logged in.'
                                        ]);
                                    } else {
                                        return Response::json([
                                            'status' => false,
                                            'message' => 'User name or password error!'
                                        ]);
                                    }
                                } else {
                                    return Response::json([
                                        'status' => false,
                                        'message' => 'Incorrect Verification Key!'
                                    ]);
                                }
                            } else {
                                if (empty($request->remember_me)) {
                                    User::where('email', $input['email'])->where('type', 0)->update(['remember_token' => ""]);
                                }
                                if (auth()->attempt([
                                    'email' => $input['email'],
                                    'password' => $input['password'],
                                    'type' => 0,
                                ], isset($request->remember_me)) && auth()->user()->type == 'trader') { //check login
                                    $request->session()->regenerate();
                                    return Response::json([
                                        'status' => true,
                                        'message' => 'You are successfully logged in.'
                                    ]);
                                } else {
                                    return Response::json([
                                        'status' => false,
                                        'message' => 'Email or password does not match!'
                                    ]);
                                }
                            }
                        } else if ($user->email_verified_at == null && $user->type === 'trader') { //if the user is a trader but he has not verify his account?
                            return Response::json([
                                'status' => false,
                                'message' => 'Your account is not activated. Please <a class="text-danger" href="#">activate your account</a>.',
                                'id' => $user->id
                            ]);
                        } else {
                            return Response::json([
                                'status' => false,
                                'message' => 'Please register a trader account before login.'
                            ]);
                        }
                    } else {
                        // start bad login attempt
                        $login_attempt = LoginAttempt::where('email', $request->email)->first();
                        // find brute force limit
                        $system_config = SystemConfig::select('brute_force_attack')->first();
                        if ($system_config->brute_force_attack > 0) {
                            if (isset($login_attempt->id)) {
                                if ($system_config->brute_force_attack <= $login_attempt->bad_login_attempt) {
                                    User::where('email', $request->email)->where('type', 0)->update([
                                        'active_status' => 0,
                                    ]);
                                    return Response::json([
                                        'status' => false,
                                        'message' => "This User Is Temporarily Blocked!"
                                    ]);
                                } else {
                                    LoginAttempt::where('id', $login_attempt->id)->update([
                                        'ip_address'        => request()->ip(),
                                        'bad_login_attempt' => $login_attempt->bad_login_attempt + 1,
                                        'email'             => $request->email,
                                        'date'              => Carbon::now(),
                                    ]);
                                }
                            } else {
                                LoginAttempt::create([
                                    'ip_address'        => request()->ip(),
                                    'bad_login_attempt' => 1,
                                    'email'             => $request->email,
                                    'date'              => Carbon::now(),
                                ]);
                            }
                        }
                        // end bad login attempt
                        // if password missmatch
                        return Response::json([
                            'status' => false,
                            'message' => ucwords("Please enter the correct password"),
                            'errors' => ['password' => 'Please enter the correct password']
                        ]);
                    }
                } else {
                    return Response::json([
                        'status' => false,
                        'message' => "You're A Demo User! Please Register To A Live Account.",
                    ]);
                }
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Account Locked : Too many failed attempts',
                ]);
            }
        }
        // if email address mismatch
        return Response::json([
            'status' => false,
            'errors' => ['email' => 'No Trader account exists for this email'],
            'message' => 'No Trader account exists for this email'
        ]);
    }

    //Start: trader forgot password
    public function userForgotPassword(Request $request)
    {
        $submit_form = $request->submit_form;
        $user_type = $request->user_type;
        if ($submit_form == "fp_email") {
            $user = User::where('email', $request->forgot_email)->where('type', $user_type)->first();
            if ($user) {
                // otp sending to user email
                $company_info = SystemConfig::select()->first();
                $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();

                // create otp
                $otp = random_int(100000, 999999);
                $update_secret_key = User::where('email', $request->forgot_email)->update([
                    'secret_key' => $otp
                ]);
                if ($update_secret_key) {
                    $email_data = [
                        'otp'                       => $otp,
                        'clientName'                => $user->name,
                        'companyName'               => $company_info->com_name,
                        'website'                   => $company_info->com_website,
                        'emailCommon'               => $support_email,
                        'phone1'                    => $user->phone,
                        'emailSupport'              => $support_email,
                        'clientDepositAmount'       => $request->amount,
                        'authority'                 => $company_info->com_authority,
                        'license'                   => $company_info->com_license,
                        'copy_right'                => $company_info->copyright
                    ];
                    Mail::to($user->email)->send(new OTPverificationMail($email_data));
                    return Response::json([
                        'status'    => true,
                        'fp_email'  => $request->forgot_email,
                        'message'   => "OTP Code Is Successfully Send.",
                    ]);
                }
            } else {
                return response(['status' => false, 'message' => "Cann't Find Your Account!"]);
            }
        } elseif ($submit_form == "fp_vcode") {
            $user = User::where('email', $request->fp_email)->where('type', $user_type)->first();
            $v_code = $request->v_code1 . $request->v_code2 . $request->v_code3 . $request->v_code4 . $request->v_code5 . $request->v_code6;
            if ($user->secret_key === $v_code) {
                return Response::json([
                    'status' => true,
                    'fp_email' => $request->fp_email,
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Incorrect Verification Key!'
                ]);
            }
        } elseif ($submit_form == "create_password") {
            $validation_rules = [
                'password' => 'min:6|required_with:repeat_password|same:repeat_password', // Minimum eight characters, at least one uppercase, lowercase letter and special character
                'repeat_password' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                if ($request->ajax()) {
                    return Response::json(['status' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()]);
                }
            } else {
                $update = User::where('email', $request->fp_email)->update([
                    'password' => Hash::make($request->password)
                ]);
                $user = User::where('email', $request->fp_email)->first();
                $update_log = Log::where('user_id', $user->id)->update([
                    'password' => encrypt($request->password)
                ]);

                if ($update && $update_log) {
                    if ($request->ajax()) {
                        return Response::json(['status' => true, 'message' => 'Your Password Is Successfully Changed.']);
                    } else {
                        return Redirect()->back()->with(['status' => true, 'message' => 'Your Password Is Successfully Changed.']);
                    }
                } else {
                    if ($request->ajax()) {
                        return Response::json(['status' => false, 'message' => 'Failed To Update!']);
                    } else {
                        return Redirect()->back()->with(['status' => false, 'message' => 'Failed To Update!']);
                    }
                }
            }
        }
    }
    //End: trader forgot password



    // login form for ib
    public function ibLogin(Request $request)
    {
        $input = $request->all();
        $validation_rules = [
            'email' => 'required|email',
            'password' => 'required|max:32',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()]);
            }
        }
        $email = $request->email;
        $user = User::where('email', $email)->where('type', 4)->first();

        if (!empty($user->type)) { //if user type not empty
            if ($user->active_status == 1) {
                if (Hash::check($request->password, $user->password)) {
                    if ($user->email_verified_at != null && $user->type === 'ib') { // if email_verified_at not null
                        // check auth
                        if ($user->email_auth === 1 && $request->request_form == 'login_form') {
                            // otp sending to user email
                            $company_info = SystemConfig::select()->first();
                            $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();
                            // create otp
                            $otp = random_int(100000, 999999);
                            $update_secret_key = User::where('email', $request->email)
                                ->where('type', 4)
                                ->update([
                                    'secret_key' => $otp
                                ]);
                            if ($update_secret_key) {
                                EmailService::send_email('otp-verification', [
                                    'account_email' => $user->email,
                                    'otp' => $otp,
                                    'user_id' => $user->id,
                                    'name' => $user->name,
                                ]);

                                return Response::json([
                                    'status' => true,
                                    'message' => "OTP Code Is Successfully Send.",
                                    'email' => $request->email,
                                    'password' => $request->password,
                                    'modal'     => "mail-verification-form",
                                ]);
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'Failed To Update Secret Key!',
                                ]);
                            }
                        } elseif ($user->g_auth === 1 && $request->request_form == 'login_form') {
                            return Response::json([
                                'status' => true,
                                'email' => $request->email,
                                'password' => $request->password,
                                'modal'     => "google-verification-form",
                            ]);
                        } elseif ($request->request_form == 'mail_verify') {
                            $v_code = $request->v_code1 . $request->v_code2 . $request->v_code3 . $request->v_code4 . $request->v_code5 . $request->v_code6;

                            if ($user->secret_key === $v_code) {
                                if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']), isset($request->remember_me)) && auth()->user()->type == 'ib') { //check login
                                    $request->session()->regenerate();
                                    return Response::json([
                                        'status' => true,
                                        'message' => 'You are successfully logged in.'
                                    ]);
                                } else {
                                    return Response::json([
                                        'status' => false,
                                        'message' => 'User name or password error!'
                                    ]);
                                }
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'Incorrect Verification Key!'
                                ]);
                            }
                        } elseif ($request->request_form == 'google_verify') {
                            $v_code = $request->v_code1 . $request->v_code2 . $request->v_code3 . $request->v_code4 . $request->v_code5 . $request->v_code6;
                            $secret_key = $user->secret_key;
                            $ga = new GoogleAuthenticator();
                            $checkResult = $ga->verifyCode($secret_key, $v_code, 2);
                            if ($checkResult) {
                                if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']), isset($request->remember_me)) && auth()->user()->type == 'ib') { //check login
                                    $request->session()->regenerate();
                                    return Response::json([
                                        'status' => true,
                                        'message' => 'You are successfully logged in.'
                                    ]);
                                } else {
                                    return Response::json([
                                        'status' => false,
                                        'message' => 'User name or password error!'
                                    ]);
                                }
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'Incorrect Verification Key!'
                                ]);
                            }
                        } else {
                            if (auth()->attempt([
                                'email' => $input['email'],
                                'password' => $input['password'],
                                'type' => 4,
                            ], isset($request->remember_me)) && auth()->user()->type == 'ib') { //check login
                                $request->session()->regenerate();
                                return Response::json([
                                    'status' => true,
                                    'message' => 'You are successfully logged in.'
                                ]);
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'User name or password error!'
                                ]);
                            }
                            // if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']), isset($request->remember_me)) && auth()->user()->type == 'ib') { //check login
                            //     $request->session()->regenerate();
                            //     return Response::json([
                            //         'status' => true,
                            //         'message' => 'You are successfully logged in.'
                            //     ]);
                            // } else {
                            //     return Response::json([
                            //         'status' => false,
                            //         'message' => 'User name or password error!'
                            //     ]);
                            // }
                        }
                    } else if ($user->email_verified_at == null && $user->type === 'ib') { //if the user is a ib but he has not verify his account?
                        return Response::json([
                            'status' => false,
                            'message' => 'Your account is not verified. Please <a class="text-danger" href="#">verify your account</a>.'
                        ]);
                    } else {
                        return Response::json([
                            'status' => false,
                            'message' => 'Please register a ib account before login.'
                        ]);
                    }
                } else {
                    // start bad login attempt
                    $login_attempt = LoginAttempt::where('email', $request->email)->first();
                    // find brute force limit
                    $system_config = SystemConfig::select('brute_force_attack')->first();
                    if ($system_config->brute_force_attack > 0) {
                        if (isset($login_attempt->id)) {
                            if ($system_config->brute_force_attack <= $login_attempt->bad_login_attempt) {
                                User::where('email', $request->email)->update([
                                    'active_status' => 0,
                                ]);
                                return Response::json([
                                    'status' => false,
                                    'message' => "This User Is Temporarily Blocked!"
                                ]);
                            } else {
                                LoginAttempt::where('id', $login_attempt->id)->update([
                                    'ip_address'        => request()->ip(),
                                    'bad_login_attempt' => $login_attempt->bad_login_attempt + 1,
                                    'email'             => $request->email,
                                    'date'              => Carbon::now(),
                                ]);
                            }
                        } else {
                            LoginAttempt::create([
                                'ip_address'        => request()->ip(),
                                'bad_login_attempt' => 1,
                                'email'             => $request->email,
                                'date'              => Carbon::now(),
                            ]);
                        }
                    }
                    // end bad login attempt
                    return Response::json([
                        'status' => false,
                        'message' => "Password Error!."
                    ]);
                }
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'The User Is Temporarily Blocked!',
                ]);
            }
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Access Denied! Invalid Email.',
            ]);
        }
    }

    // login form for system
    public function systemLogin(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $email = $request->email;
        $users = User::where('email', $email)->first();
        if (!empty($users->type)) { //if user type not empty
            if ($users->email_verified_at != null && $users->type === 'system') { // if email_verified_at not null
                if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))) { //check login
                    if (auth()->user()->type == 'system') { // check auth user
                        if ($request->ajax()) {
                            return Response::json(['status' => true, 'message' => 'You are successfully logged in.']);
                        } else {
                            return redirect()->route('system.dashboard');
                        }
                    } else {
                        // if not system user
                        if ($request->ajax()) {
                            return Response::json(['status' => false, 'message' => 'Access Denied!']);
                        } else {
                            return redirect()->route('system.login')
                                ->with('error', 'Access Denied!');
                        }
                    }
                } else {
                    if ($request->ajax()) {
                        return Response::json(['status' => false, 'message' => 'User name or password error!']);
                    } else {
                        return redirect()->route('system.login')
                            ->with('error', 'User name or password error!');
                    }
                }
            } else {
                if ($users->type === 'system') { //if the user is a system but he has not verify him account?
                    if ($request->ajax()) {
                        return Response::json(['status' => false, 'message' => 'Your account is not verified. Please <a class="text-danger" href="#">verify your account</a>.']);
                    } else {
                        return redirect()->route('system.login')
                            ->with('error', 'Your account is not verified. Please <a class="text-danger" href="#">verify your account</a>.');
                    }
                } else {
                    if ($request->ajax()) {
                        return Response::json(['status' => false, 'message' => 'Please register a system account before login.']);
                    } else {
                        return redirect()->route('system.login') // if the user is not a system
                            ->with('error', 'Please register a system account before login.');
                    }
                }
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'message' => 'Access Denied!']);
            } else {
                return redirect()->route('system.login') // error message for un authorize access
                    ->with('error', '');
            }
        }
    }

    // login form for admin or manager
    public function adminLogin(Request $request)
    {
        $input = $request->all();
        $validation_rules = [
            'email' => 'required|email',
            'password' => 'required|max:32',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()]);
            }
        }
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!empty($user->type)) { //if user type not empty
            if ($user->active_status == 1) {
                if (Hash::check($request->password, $user->password)) {
                    if ($user->email_verified_at != null && ($user->type === 'admin' || $user->type === 'manager')) { // if email_verified_at not null
                        // check auth
                        if ($user->email_auth === 1 && $request->request_form == 'login_form') {
                            // otp sending to user email
                            $company_info = SystemConfig::select()->first();
                            $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();
                            // create otp
                            $otp = random_int(100000, 999999);
                            $update_secret_key = User::where('email', $request->email)->update([
                                'secret_key' => $otp
                            ]);
                            if ($update_secret_key) {
                                $email_data = [
                                    'otp'                       => $otp,
                                    'clientName'                => $user->name,
                                    'companyName'               => $company_info->com_name,
                                    'website'                   => $company_info->com_website,
                                    'emailCommon'               => $support_email,
                                    'phone1'                    => $user->phone,
                                    'emailSupport'              => $support_email,
                                    'clientDepositAmount'       => $request->amount,
                                    'authority'                 => $company_info->com_authority,
                                    'license'                   => $company_info->com_license,
                                    'copy_right'                => $company_info->copyright
                                ];
                                Mail::to($user->email)->send(new OTPverificationMail($email_data));

                                return Response::json([
                                    'status' => true,
                                    'message' => "OTP Code Is Successfully Send.",
                                    'email' => $request->email,
                                    'password' => $request->password,
                                    'modal'     => "mail-verification-form",
                                ]);
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'Failed To Update Secret Key!',
                                ]);
                            }
                        } elseif ($user->g_auth === 1 && $request->request_form == 'login_form') {
                            return Response::json([
                                'status' => true,
                                'email' => $request->email,
                                'password' => $request->password,
                                'modal'     => "google-verification-form",
                            ]);
                        } elseif ($request->request_form == 'mail_verify') {
                            $v_code = $request->v_code1 . $request->v_code2 . $request->v_code3 . $request->v_code4 . $request->v_code5 . $request->v_code6;
                            if ($user->secret_key === $v_code) {
                                if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']), isset($request->remember_me)) && auth()->user()->type == 'admin') { //check login
                                    $request->session()->regenerate();
                                    return Response::json([
                                        'status' => true,
                                        'message' => 'You are successfully logged in.'
                                    ]);
                                } else {
                                    return Response::json([
                                        'status' => false,
                                        'message' => 'User name or password error!'
                                    ]);
                                }
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'Incorrect Verification Key!'
                                ]);
                            }
                        } elseif ($request->request_form == 'google_verify') {
                            $v_code = $request->v_code1 . $request->v_code2 . $request->v_code3 . $request->v_code4 . $request->v_code5 . $request->v_code6;
                            $secret_key = $user->secret_key;
                            $ga = new GoogleAuthenticator();
                            $checkResult = $ga->verifyCode($secret_key, $v_code, 2);
                            if ($checkResult) {
                                if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']), isset($request->remember_me)) && auth()->user()->type == 'admin') { //check login
                                    $request->session()->regenerate();
                                    return Response::json([
                                        'status' => true,
                                        'message' => 'You are successfully logged in.'
                                    ]);
                                } else {
                                    return Response::json([
                                        'status' => false,
                                        'message' => 'User name or password error!'
                                    ]);
                                }
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'Incorrect Verification Key!'
                                ]);
                            }
                        } else {
                            if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']), isset($request->remember_me)) && auth()->user()->type == 'admin' || auth()->user()->type == 'manager') { //check login
                                $request->session()->regenerate();
                                return Response::json([
                                    'status' => true,
                                    'message' => 'You are successfully logged in.'
                                ]);
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'User name or password error!'
                                ]);
                            }
                        }
                    } else if ($user->email_verified_at == null && $user->type === 'admin') { //if the user is a admin but he has not verify his account?
                        return Response::json([
                            'status' => false,
                            'message' => 'Your account is not verified. Please <a class="text-danger" href="#">verify your account</a>.'
                        ]);
                    } else {
                        return Response::json([
                            'status' => false,
                            'message' => 'Please register a admin account before login.'
                        ]);
                    }
                } else {
                    // start bad login attempt
                    $login_attempt = LoginAttempt::where('email', $request->email)->first();
                    // find brute force limit
                    $system_config = SystemConfig::select('brute_force_attack')->first();
                    if ($system_config->brute_force_attack > 0) {
                        if (isset($login_attempt->id)) {
                            if ($system_config->brute_force_attack <= $login_attempt->bad_login_attempt) {
                                User::where('email', $request->email)->update([
                                    'active_status' => 0,
                                ]);
                                return Response::json([
                                    'status' => false,
                                    'message' => "This User Is Temporarily Blocked!"
                                ]);
                            } else {
                                LoginAttempt::where('id', $login_attempt->id)->update([
                                    'ip_address'        => request()->ip(),
                                    'bad_login_attempt' => $login_attempt->bad_login_attempt + 1,
                                    'email'             => $request->email,
                                    'date'              => Carbon::now(),
                                ]);
                            }
                        } else {
                            LoginAttempt::create([
                                'ip_address'        => request()->ip(),
                                'bad_login_attempt' => 1,
                                'email'             => $request->email,
                                'date'              => Carbon::now(),
                            ]);
                        }
                    }
                    // end bad login attempt
                    return Response::json([
                        'status' => false,
                        'message' => "Password Error!."
                    ]);
                }
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'The User Is Temporarily Blocked!',
                ]);
            }
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Access Denied! Invalid Email.'
            ]);
        }
    }

    // login form for manager
    public function managerLogin(Request $request)
    {
        $input = $request->all();
        $validation_rules = [
            'email' => 'required|email',
            'password' => 'required|max:32',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors(), 'message' => $validator->errors()->first()]);
            }
        }
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!empty($user->type)) { //if user type not empty
            if ($user->active_status == 1) {
                if (Hash::check($request->password, $user->password)) {
                    if ($user->email_verified_at != null && ($user->type === 'manager')) { // if email_verified_at not null
                        // check auth
                        if ($user->email_auth === 1 && $request->request_form == 'login_form') {
                            // otp sending to user email
                            $company_info = SystemConfig::select()->first();
                            $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();
                            // create otp
                            $otp = random_int(100000, 999999);
                            $update_secret_key = User::where('email', $request->email)->update([
                                'secret_key' => $otp
                            ]);
                            if ($update_secret_key) {
                                $email_data = [
                                    'otp'                       => $otp,
                                    'clientName'                => $user->name,
                                    'companyName'               => $company_info->com_name,
                                    'website'                   => $company_info->com_website,
                                    'emailCommon'               => $support_email,
                                    'phone1'                    => $user->phone,
                                    'emailSupport'              => $support_email,
                                    'clientDepositAmount'       => $request->amount,
                                    'authority'                 => $company_info->com_authority,
                                    'license'                   => $company_info->com_license,
                                    'copy_right'                => $company_info->copyright
                                ];
                                Mail::to($user->email)->send(new OTPverificationMail($email_data));

                                return Response::json([
                                    'status' => true,
                                    'message' => "OTP Code Is Successfully Send.",
                                    'email' => $request->email,
                                    'password' => $request->password,
                                    'modal'     => "mail-verification-form",
                                ]);
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'Failed To Update Secret Key!',
                                ]);
                            }
                        } elseif ($user->g_auth === 1 && $request->request_form == 'login_form') {
                            return Response::json([
                                'status' => true,
                                'email' => $request->email,
                                'password' => $request->password,
                                'modal'     => "google-verification-form",
                            ]);
                        } elseif ($request->request_form == 'mail_verify') {
                            $v_code = $request->v_code1 . $request->v_code2 . $request->v_code3 . $request->v_code4 . $request->v_code5 . $request->v_code6;
                            if ($user->secret_key === $v_code) {
                                if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']), isset($request->remember_me)) && auth()->user()->type == 'manager') { //check login
                                    $request->session()->regenerate();
                                    return Response::json([
                                        'status' => true,
                                        'message' => 'You are successfully logged in.'
                                    ]);
                                } else {
                                    return Response::json([
                                        'status' => false,
                                        'message' => 'User name or password error!'
                                    ]);
                                }
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'Incorrect Verification Key!'
                                ]);
                            }
                        } elseif ($request->request_form == 'google_verify') {
                            $v_code = $request->v_code1 . $request->v_code2 . $request->v_code3 . $request->v_code4 . $request->v_code5 . $request->v_code6;
                            $secret_key = $user->secret_key;
                            $ga = new GoogleAuthenticator();
                            $checkResult = $ga->verifyCode($secret_key, $v_code, 2);
                            if ($checkResult) {
                                if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']), isset($request->remember_me)) && auth()->user()->type == 'manager') { //check login
                                    $request->session()->regenerate();
                                    return Response::json([
                                        'status' => true,
                                        'message' => 'You are successfully logged in.'
                                    ]);
                                } else {
                                    return Response::json([
                                        'status' => false,
                                        'message' => 'User name or password error!'
                                    ]);
                                }
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'Incorrect Verification Key!'
                                ]);
                            }
                        } else {
                            if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']), isset($request->remember_me)) && auth()->user()->type == 'manager') { //check login
                                $request->session()->regenerate();
                                return Response::json([
                                    'status' => true,
                                    'message' => 'You are successfully logged in.'
                                ]);
                            } else {
                                return Response::json([
                                    'status' => false,
                                    'message' => 'User name or password error!'
                                ]);
                            }
                        }
                    } else if ($user->email_verified_at == null && $user->type === 'manager') { //if the user is a manager but he has not verify his account?
                        return Response::json([
                            'status' => false,
                            'message' => 'Your account is not verified. Please <a class="text-danger" href="#">verify your account</a>.'
                        ]);
                    } else {
                        return Response::json([
                            'status' => false,
                            'message' => 'Please register a manager account before login.'
                        ]);
                    }
                } else {
                    // start bad login attempt
                    $login_attempt = LoginAttempt::where('email', $request->email)->first();
                    // find brute force limit
                    $system_config = SystemConfig::select('brute_force_attack')->first();
                    if ($system_config->brute_force_attack > 0) {
                        if (isset($login_attempt->id)) {
                            if ($system_config->brute_force_attack <= $login_attempt->bad_login_attempt) {
                                User::where('email', $request->email)->update([
                                    'active_status' => 0,
                                ]);
                                return Response::json([
                                    'status' => false,
                                    'message' => "This User Is Temporarily Blocked!"
                                ]);
                            } else {
                                LoginAttempt::where('id', $login_attempt->id)->update([
                                    'ip_address'        => request()->ip(),
                                    'bad_login_attempt' => $login_attempt->bad_login_attempt + 1,
                                    'email'             => $request->email,
                                    'date'              => Carbon::now(),
                                ]);
                            }
                        } else {
                            LoginAttempt::create([
                                'ip_address'        => request()->ip(),
                                'bad_login_attempt' => 1,
                                'email'             => $request->email,
                                'date'              => Carbon::now(),
                            ]);
                        }
                    }
                    // end bad login attempt
                    return Response::json([
                        'status' => false,
                        'message' => "Password Error!."
                    ]);
                }
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'The User Is Temporarily Blocked!',
                ]);
            }
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Access Denied! Invalid Email.'
            ]);
        }
    }
}
