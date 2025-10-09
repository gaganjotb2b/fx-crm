<?php

namespace App\Http\Controllers\IB\MyAdmin;

use App\Http\Controllers\Controller;
use App\Mail\IbAdmin\IbPasswordChange;
use App\Models\admin\SystemConfig;
use App\Models\TradingAccount;
use App\Models\Country;
use App\Models\Log;
use App\Models\Traders\SocialLink;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\EmailService;
use App\Services\GetBrowserService;
use App\Services\GoogleAuthenticator;
use App\Services\PermissionService;
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

class IbSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('settings', 'ib'));
        $this->middleware(AllFunctionService::access('my_admin', 'ib'));
        $this->middleware('is_ib');
        // if (request()->is('ib/ib-admin/settings')) {
        //     $this->middleware(PermissionService::is_combined());
        // }
    }
    public function settings()
    {
        $copyright = SystemConfig::select('copyright')->first();
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)
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
        return view('ibs.ib-admins.settings', [
            'avatar'            => $avatar,
            'user_description'  => $user_descriptions,
            'countries'         => $countries,
            'no_auth'           => (auth()->user()->g_auth == false && auth()->user()->email_auth == false) ? 'checked' : '',
            'e_auth'            => (auth()->user()->email_auth == true) ? 'checked' : '',
            'g_auth'            => (auth()->user()->g_auth == true) ? 'checked' : '',
            'server'            => $server,
            'social_link'       => $social_link,
            'copyright'         => $copyright,
        ]);
    }
    public function updateBasicInfo(Request $request)
    {
        $validation_rules = [
            'full_name'     => 'required',
            'state'         => 'required',
            'city'          => 'required',
            'phone'         => 'required',
            'date_of_birth' => 'required',
            'zipcode'       => 'required|numeric',
            'address'       => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        } else {
            $date_of_birth = date('Y-m-d H:i:s', strtotime($request->date_of_birth));

            $user = User::find(auth()->user()->id);
            $user->name = (isset($request->full_name) ? $request->full_name : '');
            $user->phone = (isset($request->phone) ? $request->phone : '');
            $update = $user->save();

            $user_descriptions = UserDescription::where('user_id', auth()->user()->id)->first();
            $user_descriptions->gender = $request->gender;
            $user_descriptions->date_of_birth = $date_of_birth;
            $user_descriptions->country_id = $request->country;
            $user_descriptions->state = $request->state;
            $user_descriptions->city = $request->city;
            $user_descriptions->address = $request->address;
            $user_descriptions->zip_code = $request->zipcode;

            $update = $user_descriptions->save();
            if ($update) {
                return Response::json([
                    'status'        => true,
                    'message'       => 'IB Updated Successfully',
                    'full_name'     => $request->full_name,
                    'gender'        => $request->gender,
                    'phone'         => $request->phone,
                    'date_of_birth' => $request->date_of_birth,
                    'state'         => $request->state,
                    'city'          => $request->city,
                    'zipcode'       => $request->zipcode,
                    'address'       => $request->address,
                ]);
            }
        }
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
    public function updatePassword(Request $request)
    {
        $validation_rules = [
            'current_password' => 'required|min:6',
            'new_password' => 'min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:6',
        ];
        $user_pass = User::where('id', auth()->user()->id)->first();
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'New Password not matched following errors!'
            ]);
        }
        $current_pass = $request->current_password;
        $check = Hash::check($current_pass, $user_pass->password);
        if (!$check) {
            return Response::json([
                'status' => false,
                'message' => 'Current password not matched'
            ]);
        } else {
            $newpass = $request->new_password;
            $hash_password = Hash::make($newpass);
            $user_pass->password = $hash_password;
            $update = $user_pass->save();
            // udpate log table
            Log::where('user_id', auth()->user()->id)->update([
                'password' => encrypt($newpass)
            ]);
            //mail script
            if ($update) {
                EmailService::send_email('change-password', [
                    'user_id' => auth()->user()->id,
                    'password' => $newpass,
                    'clientPassword' => $newpass,
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
                    'message' => 'Password Successfully Updated.'
                ]);
            }
        }
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
        $data = [];
        $devices = ModelsActivity::where(function ($query) {
            $query->where('causer_id', auth()->user()->id)
                ->where('event', 'login');
        })->latest()->limit(3)->select('properties', 'description', 'id')->get();
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)
            ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->first();

        $i = 0;
        foreach ($devices as $key => $value) {
            $check_loging = ModelsActivity::where(function ($query) {
                $query->where('causer_id', auth()->user()->id)
                    ->where('event', 'logout');
            })->where('batch_uuid', $value->id)->exists();
            $login_device = json_decode($value->properties);
            $data['device_' . ($i + 1)] = $user_agent->user_agent($login_device[0]);
            $ip_address = explode(' ', $value->description);
            $locationData = Location::get($ip_address[3]);
            $country = (isset($locationData->countryName)) ? $locationData->countryName : (isset($user_descriptions->name) ? $user_descriptions->name : '');
            $data['country_' . ($i + 1)] = $country;
            $data['active_' . ($i + 1)] = ($check_loging == true) ? 'Logout' : 'Active';
            $i++;
        }
        return Response::json($data);
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
    // update user transaction password
    public function updateTransactionPassword(Request $request)
    {
        $validation_rules = [
            'current_transaction_password' => 'required|min:4',
            'new_transaction_password' => 'min:4|',
            'confirm_transaction_password' => 'min:4|same:new_transaction_password',
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
        } else if (!$check) {
            return Response::json([
                'status' => false,
                'message' => 'Current Transaction Password Not Matched'
            ]);
        } else {
            // update user table
            $newpass = $request->new_transaction_password;
            $user_pass->transaction_password = Hash::make($newpass);
            $update = $user_pass->save();
            // update log password
            $log_pass = Log::select()->where('user_id', auth()->user()->id)->first();

            if (isset($log_pass->transaction_password)) {
                $log_pass->transaction_password = encrypt($newpass);
                $update = $log_pass->save();
            }
            //mail script
            if ($update) {
                EmailService::send_email('change-transaction-password', [
                    'user_id'   => auth()->user()->id,
                    'password'  => $newpass,
                    'transaction_pin'  => $newpass,
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
    }
    public function createTransectionPassword(Request $request)
    {
        $random_password = mt_rand(1000, 9999);
        // update log table
        $log = Log::where('user_id', auth()->user()->id)->update([
            'transaction_password' => encrypt($random_password),
        ]);
        // update user table
        $user = User::where('id', auth()->user()->id)->update([
            'transaction_password' => Hash::make($random_password),
        ]);

        //mail script
        $sendMail =  EmailService::send_email('reset-transaction-password', [
            'user_id' => auth()->user()->id,
            'password' => $random_password,
            'new_pin' => $random_password,
        ]);
        // insert activity-----------------
        $user = User::find(auth()->user()->id);
        //<---client email as user id
        activity("reset transaction password")
            ->causedBy(auth()->user()->id)
            ->withProperties($request->all())
            ->event("reset transaction password")
            ->performedOn($user)
            ->log("The IP address " . request()->ip() . " has been reset transaction password");
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
            'message' => 'Transaction password reset failed, Please try again later!'
        ]);
    }

    // reset password
    // ib 
    public function reset_password(Request $request)
    {
        // create randome password
        $random_password = chr(rand(97, 122)) . mt_rand(10000, 99999);
        // update user table
        $update = User::where('id', auth()->user()->id)->update([
            'password' => Hash::make($random_password),
        ]);
        // update log table
        Log::where('user_id', auth()->user()->id)->update([
            'password' => encrypt($random_password),
        ]);
        if ($update) {
            $mail_status = EmailService::send_email('reset-password', [
                'account_email' => auth()->user()->email,
                'new_password' => $random_password,
                'user_id' => auth()->user()->id,
            ]);
            //<---client email as user id
            $user = User::find(auth()->user()->id);
            activity("reset password")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event("reset password")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been reset password");
            // end activity log----------------->>
            return Response::json([
                'status' => true,
                'message' => 'Password successfully reset, Please check your mail',
            ]);
        }
        return Response::json([
            'status' => true,
            'message' => 'Password reset failed, Please try again later!',
        ]);
    }
}
