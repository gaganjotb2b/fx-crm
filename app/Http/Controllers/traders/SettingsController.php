<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Mail\OTPverificationMail;
use App\Models\admin\SystemConfig;
use App\Models\TradingAccount;
use App\Mail\TraderPasswordChange;
use App\Models\Country;
use App\Models\Log;
use App\Models\Traders\SocialLink;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\EmailService;
use App\Services\GetBrowserService;
use App\Services\GoogleAuthenticator;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Models\Activity as ModelsActivity;
use Stevebauman\Location\Facades\Location;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('settings', 'trader'));
        $this->middleware(AllFunctionService::access('my_admin', 'trader'));
    }
    public function settings(Request $request)
    {
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)
            ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->join('users', 'user_descriptions.user_id', '=', 'users.id')
            ->first();

        $social_link = SocialLink::where('user_id', auth()->user()->id)->first();

        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }
        // countries
        $countries = Country::all();
        // system configure data
        $system_config = SystemConfig::select('platform_type')->first();
        $server = '';
        if ($system_config) {
            if ($system_config->platform_type === 'both') {
                $server .= '<option value="mt5">mt5</option>';
                $server .= '<option value="mt4">mt4</option>';
            } else {
                $server .= '<option value="' . $system_config->platform_type . '">' . strtoupper($system_config->platform_type) . '</option>';
            }
        }

        if (isset($user_descriptions->date_of_birth)) {
            $time = strtotime($user_descriptions->date_of_birth);
            $month = date("F", $time);
            $year = date("Y", $time);
            $date = date('d', $time);
        }

        $user_agent = new GetBrowserService();
        $data = [];
        $devices = ModelsActivity::where(function ($query) {
            $query->where('causer_id', auth()->user()->id)
                ->where('event', 'login');
        })->latest()->limit(5)->select('properties', 'description', 'id', 'created_at')->get();
        $i = 0;
        foreach ($devices as $key => $value) {
            $check_loging = ModelsActivity::where(function ($query) {
                $query->where('causer_id', auth()->user()->id)
                    ->where('event', 'logout');
            })->where('batch_uuid', $value->id)->exists();
            $login_device = json_decode(isset($value->properties) ? $value->properties : '');
            $data['device_' . ($i + 1)] = $user_agent->user_agent($login_device[0]);
            $ip_address = explode(' ', (isset($value->description) ? $value->description : ''));
            $data['device_' . ($i + 1)]['ip_address'] = $ip_address[3];
            $data['device_' . ($i + 1)]['login_at'] = $value->created_at->diffForHumans();
            $i++;
        }
        // exit;
        return view('traders.my-admin.settings', [
            'login_history' => $data,
            'avatar' => $avatar,
            'user_description' => $user_descriptions,
            'countries' => $countries,
            'no_auth' => (auth()->user()->g_auth == false && auth()->user()->email_auth == false) ? 'checked' : '',
            'e_auth' => (auth()->user()->email_auth == true) ? 'checked' : '',
            'g_auth' => (auth()->user()->g_auth == true) ? 'checked' : '',
            'server' => $server,
            'social_link' => $social_link,
            'bith_year' => $year??"",
        ]);
    }
    // get trading accounts for settings
    public function get_trading_account_dt(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $_GET['order'][0]["column"];
        $orderDir = $_GET["order"][0]["dir"];
        $columns = ['account_number', 'platform', 'leverage', 'balance', 'balance'];
        $orderby = $columns[$order];
        // select type= 0 for trader 
        $result = TradingAccount::where('user_id', auth()->user()->id);

        $count = $result->count(); // <------count total rows
        $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
        $data = array();
        $i = 0;
        foreach ($result as $key => $value) {

            // tabl column
            // -------------------------------------
            $data[$i]["account"]   = $value->account_number;
            $data[$i]["server"]   = $value->platform;
            $data[$i]["leverage"]   = $value->leverage;
            $data[$i]["balance"]   = $value->balance;
            $data[$i]["action"]    = '<div class="d-flex justify-content-between">
                                            <a href="#" class="more-actions dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="navbarDropdownMenuLink' . $value->id . '">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a> 
                                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink' . $value->id . '">                                              
                                                <li>
                                                    <a class="dropdown-item btn-change-password" href="javascript:;">
                                                        Change Password
                                                    </a>
                                                </li>                                             
                                                <li>
                                                    <a class="dropdown-item" href="javascript:;">
                                                        Change Investor Password
                                                    </a>
                                                </li>                                             
                                                <li>
                                                    <a class="dropdown-item" href="javascript:;">
                                                        Chanage Leverage
                                                    </a>
                                                </li>                                             
                                            </ul>
                                        </div>';
            $i++;
        }
        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $count, 'recordsFiltered' => $count);
        $output['data'] = $data;
        return Response::json($output);
    }
    // update user password
    public function updatePassword(Request $request)
    {
        $validation_rules = [
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:new_password',
        ];
        $user_pass = User::where('id', auth()->user()->id)->first();

        $current_pass = $request->current_password;
        $check = Hash::check($current_pass, $user_pass->password);
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix follwing errors!'
            ]);
        } else if (!$check) {
            return Response::json([
                'status' => false,
                'message' => 'Current Password Not Matched'
            ]);
        } else {
            $newpass = $request->new_password;
            $hash_password = Hash::make($newpass);
            $user_pass->password = $hash_password;
            $update = $user_pass->save();

            $log_pass = Log::select()->where('user_id', auth()->user()->id)->first();
            $password_encrpt = encrypt($newpass);

            if (isset($log_pass->password)) {
                $log_pass->password = $password_encrpt;
                $update = $log_pass->save();
            }
            //mail script
            if ($update) {
                EmailService::send_email('trader-password-change', [
                    'user_id' => auth()->user()->id,
                    'password'             => $newpass,
                ]);
                // insert activity-----------------
                $user = User::find(auth()->user()->id);
                //<---client email as user id
                activity("password change")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("password change")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been password change");
                // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'message' => 'Password Successfully Updated'
                ]);
            }
        }
    }
    // trader
    // change user transaction pin
    public function updateTransactionPassword(Request $request)
    {
        $validation_rules = [
            'current_transaction_password' => 'required|min:4',
            'new_transaction_password' => 'required|min:4',
            'confirm_transaction_password' => 'required|min:4|same:new_transaction_password',
        ];
        $user_pass = User::where('id', auth()->user()->id)->first();

        $current_pass = $request->current_transaction_password;
        $check = Hash::check($current_pass, $user_pass->transaction_password);
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix follwing errors!'
            ]);
        }
        if (!$check) {
            return Response::json([
                'status' => false,
                'message' => 'Current Transaction Password Not Matched'
            ]);
        }
        // udpate user table
        $newpass = $request->new_transaction_password;
        $hash_password = Hash::make($newpass);
        $user_pass->transaction_password = $hash_password;
        $update = $user_pass->save();
        // update log table
        $log_pass = Log::select()->where('user_id', auth()->user()->id)->first();
        $password_encrpt = encrypt($newpass);
        if (isset($log_pass->password)) {
            $log_pass->password = $password_encrpt;
            $update = $log_pass->save();
        }
        //mail script
        if ($update) {
            EmailService::send_email('change-transaction-password', [
                'user_id' => auth()->user()->id,
                'password'             => $newpass,
                'transaction_pin'      => $newpass,
            ]);
            // insert activity-----------------
            $user = User::find(auth()->user()->id);
            //<---client email as user id
            activity("transaction password change")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event("transaction password change")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been transaction password change");
            // end activity log----------------->>
            return Response::json([
                'status' => true,
                'message' => 'Transaction Password Successfully Updated'
            ]);
        }
    }
    // forgot user transaction pin
    public function forgotTransactionPin(Request $request)
    {
        try{
            $log_pass = Log::select()->where('user_id', auth()->user()->id)->first();
            $decrypted_password = decrypt($log_pass->transaction_password);
            //mail script
            if ($decrypted_password) {
                EmailService::send_email('send-transaction-pin', [
                    'user_id' => auth()->user()->id,
                    'transaction_pin'      => $decrypted_password,
                ]);
                return Response::json([
                    'status' => true,
                    'message' => 'Transaction pin is sent to your email.'
                ]);
            }
        }catch(Exception $th){
            return Response::json([
                'status' => false,
                'message' => 'Failed to send transaction pin!'
            ]);
        }
    }
    // trader
    // reset transaction pin
    public function createTransectionPassword(Request $request)
    {
        $validation_rules = [
            'forgot_email' => 'required'
        ];
        $user_pass = User::where('id', auth()->user()->id)->first();
        $forgot_email = $request->forgot_email;
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix follwing errors!'
            ]);
        }
        $random_password = base64_encode(random_bytes(6));
        $log = Log::where('user_id', auth()->user()->id)->update([
            'transaction_password' => encrypt($random_password),
        ]);
        $user_data = [
            'transaction_password' => Hash::make($random_password),
        ];
        $user = User::where('id', auth()->user()->id)->update($user_data);

        //mail script
        $sendMail =  EmailService::send_email('reset-transaction-password', [
            'user_id' => auth()->user()->id,
            'password'             => $random_password,
            'new_pin'             => $random_password,
        ]);
        // insert activity-----------------
        $user = User::find(auth()->user()->id);
        //<---client email as user id
        activity("rest transaction password")
            ->causedBy(auth()->user()->id)
            ->withProperties($request->all())
            ->event("rest transaction password")
            ->performedOn($user)
            ->log("The IP address " . request()->ip() . " has been rest transaction password");
        // end activity log----------------->>
        if ($log && $user && $sendMail) {
            return Response::json([
                'status' => true,
                'message' => 'Transaction Password Create Successfully, Check your mail',
                'password' => $random_password,
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'somthing went to wrong'
        ]);
    }
    //social link add or updated
    public function addUpdateSocialLink(Request $request)
    {
        $fb = $request->fb_link;
        $twt = $request->twitter_link;
        $skype = $request->skype_link;
        $linkedin = $request->linkedin_link;
        $telegram = $request->telegram_link;
        $user_id = auth()->user()->id;

        $check_link = SocialLink::where('user_id', $user_id)->first();

        if ($check_link) {
            $check_link->facebook = $fb;
            $check_link->twitter = $twt;
            $check_link->skype = $skype;
            $check_link->linkedin = $linkedin;
            $check_link->telegram = $telegram;
            $update = $check_link->save();
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'Social Links Successfully Updated',
                    'fb_link' => $request->fb_link,
                    'twitter_link' => $request->twitter_link,
                    'skype_link' => $request->skype_link,
                    'linkedin_link' => $request->linkedin_link,
                    'telegram_link' => $request->telegram_link,
                ]);
            }
        } else {
            $crete_link = SocialLink::create([
                'user_id'  => $user_id,
                'facebook' => $fb,
                'twitter'  => $twt,
                'skype' => $skype,
                'linkedin' => $linkedin,
                'telegram' => $telegram
            ]);
            if ($crete_link) {
                return Response::json([
                    'status' => true,
                    'message' => 'Social Links Successfully Created'
                ]);
            }
        }
    }
    //modal social link updated
    public function SocialLink(Request $request)
    {

        $link = $request->link_input;
        $social_link = SocialLink::where('user_id', auth()->user()->id);
        $count = $social_link->count();
        $social_link = $social_link->first();


        if (strtolower($request->op) === 'facebook') {
            if ($count) {
                if ($social_link != null) {
                    $social_link->facebook = $link;
                }
            } else {
                $update = SocialLink::create([
                    'facebook' => $link,
                    'user_id' => auth()->user()->id
                ]);
            }
        } else if (strtolower($request->op) === 'twitter') {
            if ($count) {
                if ($social_link != null) {
                    $social_link->twitter = $link;
                }
            } else {
                $update = SocialLink::create([
                    'twitter' => $link,
                    'user_id' => auth()->user()->id
                ]);
            }
        } else if (strtolower($request->op) === 'skype') {
            if ($count) {
                if ($social_link != null) {
                    $social_link->skype = $link;
                }
            } else {
                $update = SocialLink::create([
                    'skype' => $link,
                    'user_id' => auth()->user()->id
                ]);
            }
        } else if (strtolower($request->op) === 'linkedin') {
            if ($count) {
                if ($social_link != null) {
                    $social_link->linkedin = $link;
                }
            } else {
                $update = SocialLink::create([
                    'linkedin' => $link,
                    'user_id' => auth()->user()->id
                ]);
            }
        } else if (strtolower($request->op) === 'whatsapp') {
            if ($count) {
                if ($social_link != null) {
                    $social_link->whatsapp = $link;
                }
            } else {
                $update = SocialLink::create([
                    'whatsapp' => $link,
                    'user_id' => auth()->user()->id
                ]);
            }
        } else if (strtolower($request->op) === 'telegram') {
            if ($count) {
                if ($social_link != null) {
                    $social_link->telegram = $link;
                }
            } else {
                $update = SocialLink::create([
                    'telegram' => $link,
                    'user_id' => auth()->user()->id
                ]);
            }
        }
        if ($count) {
            $update = $social_link->save();
        }

        if ($update) {
            return Response::json([
                'status' => true,
                'message' => 'Social Links Successfully ' . ($count) ? 'Updated' : 'Added',
                'id' => strtolower($request->op),
                'link' => $link
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Something went wrong, Please try again later'
        ]);
    }
    // session section----------------
    public function get_session_device(Request $request)
    {
        $user_agent = new GetBrowserService();
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)
            ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->first();
        $data = [];
        $devices = ModelsActivity::where(function ($query) {
            $query->where('causer_id', auth()->user()->id)
                ->where('event', 'login');
        })->latest()->limit(5)->select('properties', 'description', 'id', 'created_at')->get();
        $i = 0;
        foreach ($devices as $key => $value) {
            $check_loging = ModelsActivity::where(function ($query) {
                $query->where('causer_id', auth()->user()->id)
                    ->where('event', 'logout');
            })->where('batch_uuid', $value->id)->exists();
            $login_device = json_decode($value->properties);
            $data['device_' . ($i + 1)] = $user_agent->user_agent($login_device[0]);
            $ip_address = explode(' ', $value->description);
            $data['device_' . ($i + 1)]['ip_address'] = $ip_address[3];
            $data['device_' . ($i + 1)]['login_at'] = $value->created_at->diffForHumans();
            $i++;
        }
        $row = '';
        // $j=0;
        for ($j = 0; $j < count($data); $j++) :
            $device = ($j + 1);
            $browser = $data["device_$device"]['name'];
            $browser_logo = AllFunctionService::login_browser($browser);
            $login_device = $data["device_$device"]['platform'];
            $device_icon = AllFunctionService::login_device($login_device);
            $ip_address = $data["device_$device"]['ip_address'];
            $locationData = Location::get($ip_address);
            $country = (isset($locationData->countryName)) ? $locationData->countryName : (isset($user_descriptions->name) ? $user_descriptions->name : '');
            $login_at = $data["device_$device"]['login_at'];

            $icon = asset('admin-assets/app-assets/images/icons/' . $browser_logo);
            $device_logo = AllFunctionService::device_icon($login_device);
            $row .= '<div class="d-flex align-items-center">
                                <div class="text-center w-5">
                                <img src="' . $icon . '" class="rounded me-1" height="30" alt="Google Chrome" />
                                </div>
                                <div class="my-auto ms-3">
                                    <div class="h-100">
                                        <p class="text-sm mb-1">
                                            ' . $browser . '
                                        </p>
                                        <p class="mb-0 text-xs">
                                            ' . $country . '
                                        </p>
                                    </div>
                                </div>
                                <span class="my-auto ms-auto me-3">' . $login_at . '</span>
                                <p class="text-secondary text-sm my-auto me-3">
                                    <img src="' . $device_logo . '" height="25">
                                </p>
                            </div>
                        <hr class="horizontal dark">';
        endfor;
        return Response::json($row);
    }

    // update security settings
    public function securitySettingUpdate(Request $request, $check_auth)
    {
        $update = "";
        if ($check_auth == "no_auth") {
            $update = User::where('id', auth()->user()->id)->update([
                'g_auth' => 0,
                'email_auth' => 0,
                'secret_key' => ""
            ]);
        } else if ($check_auth = "mail_auth") {
            $update = User::where('id', auth()->user()->id)->update([
                'g_auth' => 0,
                'email_auth' => 1,
                'secret_key' => ""
            ]);
        }
        if ($update) {
            if ($request->ajax()) {
                return Response::json(['success' => true, 'message' => 'Successfully Updated.']);
            } else {
                return Redirect()->back()->with(['success' => true, 'message' => 'Successfully Updated.']);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['success' => false, 'message' => 'Failed To Update!']);
            } else {
                return Redirect()->back()->with(['success' => false, 'message' => 'Failed To Update!']);
            }
        }
    }
    // update security settings
    public function googleAuthenticationUpdate(Request $request)
    {
        $user_id = $request->user_id;
        $secret_key = $request->secret_key;
        $v_code = $request->v_code;
        $ga = new GoogleAuthenticator();
        $checkResult = $ga->verifyCode($secret_key, $v_code, 2);
        if ($checkResult) {

            $update = User::where('id', $user_id)->update([
                'g_auth' => 1,
                'email_auth' => 0,
                'secret_key' => $secret_key
            ]);
            if ($update) {
                if ($request->ajax()) {
                    return Response::json(['success' => true, 'message' => 'Successfully Updated.']);
                } else {
                    return Redirect()->back()->with(['success' => true, 'message' => 'Successfully Updated.']);
                }
            } else {
                if ($request->ajax()) {
                    return Response::json(['success' => false, 'message' => 'Failed To Update!']);
                } else {
                    return Redirect()->back()->with(['success' => false, 'message' => 'Failed To Update!']);
                }
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['success' => false, 'message' => 'Failed To Update!']);
            } else {
                return Redirect()->back()->with(['success' => false, 'message' => 'Failed To Update!']);
            }
        }
    }
    public function resetUserPassword(Request $request)
    {
        $user_id = auth()->user()->id;
        $random_password = base64_encode(random_bytes(6));

        $log = Log::where('user_id', auth()->user()->id)->update([
            'password' => encrypt($random_password),
        ]);
        $user_data = [
            'password' => Hash::make($random_password),
        ];
        $user = User::where('id', auth()->user()->id)->update($user_data);

        //mail script
        $sendMail =  EmailService::send_email('trader-reset-password', [
            'user_id' => auth()->user()->id,
            'password'             => $random_password,
        ]);

        if ($sendMail) {
            return Response::json([
                'status' => true,
                'message' => 'Password Update Successfully'
            ]);
        }

        return Response::json([
            'status' => false,
            'message' => 'Failed to Update Password'
        ]);
    }
}
