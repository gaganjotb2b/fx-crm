<?php

namespace App\Http\Controllers\admins\Profile;

use App\Http\Controllers\Controller;
use App\Mail\AdminMailChange;
use App\Mail\AdminMailChangConfermation;
use App\Mail\AdminPhoneChangConfermation;
use App\Mail\TransactionPassReset;
use App\Models\admin\SystemConfig;
use App\Models\Country;
use App\Models\Log;
use App\Models\Traders\SocialLink;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\common\UserService;
use App\Services\EmailService;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:change profile"]);
        $this->middleware(["role:admin profile"]);
        // system module controll
        $this->middleware(AllFunctionService::access('admin_profile', 'admin'));
        $this->middleware(AllFunctionService::access('change_profile', 'admin'));
    }
    public function profileSetting()
    {
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)
            ->join('users', 'users.id', '=', 'user_descriptions.user_id')
            ->first();

        // get all countries
        // --------------------------------------------------------------------------------------------------------
        $countries = Country::all();
        $country_options = '';
        foreach ($countries as $key => $value) {
            $selected = ($value->id == $user_descriptions->country_id) ? 'selected' : "";
            $country_options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->name . '</option>';
        }
        $social_link = SocialLink::select()->where('user_id', auth()->user()->id)->first();

        // security setting 
        $users = User::find(auth()->user()->id)->first();
        return view(
            'admins.profile.admin-manage-profile',
            [
                'avatar'            => UserService::profile_avater(),
                'country_options'   => $country_options,
                'user'              => $user_descriptions,
                'country'           => UserService::get_country(),
                'link'              => $social_link,
                'users'             => $users,
                'state' => ($user_descriptions) ? $user_descriptions->state : '',
                'city' => ($user_descriptions) ? $user_descriptions->city : '',
                'zipcode' => ($user_descriptions) ? $user_descriptions->zip_code : '',
                'address' => ($user_descriptions) ? $user_descriptions->address : '',
                'date_of_birth' => ($user_descriptions) ? $user_descriptions->date_of_birth : '',
            ]
        );
    }
    //send mail from admin profile
    public function sendEmail(Request $request)
    {
        $activation_link = url('/admin/change/mail/' . encrypt(auth()->user()->id));
        $admin = User::select()->where('id', auth()->user()->id)->first();
        $support_email = SystemConfig::select('support_email')->first();
        $support_email = ($support_email) ? $support_email->support_email : default_support_email();
        $email_data = [
            'name'              => ($admin) ? $admin->name : config('app.name') . ' User',
            'account_email'     => ($admin) ? $admin->email : '',
            'admin'             => $admin->name,
            'login_url'         => route('login'),
            'support_email'     => $support_email,
            'phone'             => $admin->phone,
            'activation_link'   => $activation_link
        ];
        if ($admin) {
            if (Mail::to($admin->email)->send(new AdminMailChangConfermation($email_data))) {
                return Response::json(['status' => true, 'message' => 'Mail successfully sent', 'success_title' => 'Admin']);
            } else {
                return Response::json(['status' => false, 'message' => 'Mail sending failed, Please try again later!', 'success_title' => 'Admin']);
            }
        }
    }
    //send phone mail from admin profile
    public function sendPhoneEmail(Request $request)
    {
        $activation_link = url('/admin/change/phone/' . encrypt(auth()->user()->id));
        $admin = User::select()->where('id', auth()->user()->id)->first();
        $support_email = SystemConfig::select('support_email')->first();
        $support_email = ($support_email) ? $support_email->support_email : default_support_email();
        $email_data = [
            'name'              => ($admin) ? $admin->name : config('app.name') . ' User',
            'account_email'     => ($admin) ? $admin->email : '',
            'admin'             => $admin->name,
            'login_url'         => route('login'),
            'support_email'     => $support_email,
            'phone'             => $admin->phone,
            'activation_link'   => $activation_link

        ];
        if ($admin) {
            if (Mail::to($admin->email)->send(new AdminPhoneChangConfermation($email_data))) {
                return Response::json(['status' => true, 'message' => 'Mail successfully sent', 'success_title' => 'Admin']);
            } else {
                return Response::json(['status' => false, 'message' => 'Mail sending failed, Please try again later!', 'success_title' => 'Admin']);
            }
        }
    }
    //security settings transaction pass reset code here !!!
    public function resetTransactionPass(Request $request)
    {
        try {
            $transaction_pass = Str::random(16);
            $encrpt_pass = encrypt($transaction_pass);
            $hash_pass = Hash::make($transaction_pass);
            $user_tran_pass = User::select()->where('id', auth()->user()->id)->first();
            $user_pass_log = Log::select()->where('user_id', auth()->user()->id)->first();

            if ($user_tran_pass && $user_pass_log) {
                $user_tran_pass->transaction_password = $hash_pass;
                $update = $user_tran_pass->save();
                $user_pass_log->transaction_password = $encrpt_pass;
                $update = $user_pass_log->save();
                $support_email = SystemConfig::select('support_email')->first();
                $support_email = ($support_email) ? $support_email->support_email : default_support_email();
                $email_data = [
                    'name'              => ($user_tran_pass) ? $user_tran_pass->name : config('app.name') . ' User',
                    'account_email'     => ($user_tran_pass) ? $user_tran_pass->email : '',
                    'support_email'     => $support_email,
                    'transaction_pass'  => $transaction_pass,
                ];
                if ($update) {
                    Mail::to($user_tran_pass->email)->send(new TransactionPassReset($email_data));
                    //<---client email as user id
                    $user = User::find(auth()->user()->id);
                    activity("Admin reset transaction password")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("transaction password reset")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been " .  "reset admin transaction password");
                    // end activity log----------------->>
                    return Response::json([
                        'status' => true,
                        'message' => 'Mail successfully sent',
                        'success_title' => 'Update'
                    ]);
                }
            } else {
                $create_tran = User::create([
                    'transaction_password' => $hash_pass
                ]);
                $create_tran = Log::create([
                    'transaction_password' => $encrpt_pass
                ]);
                if ($create_tran) {
                    //<---client email as user id
                    $user = User::find(auth()->user()->id);
                    activity("Admin reset transaction password")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("transaction password reset")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been " .  "reset admin transaction password");
                    // end activity log----------------->>
                    return Response::json([
                        'status' => true,
                        'message' => 'Mail successfully sent',
                        'success_title' => 'Created'
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    //transaction pass change here
    public function changeTransactionPass(Request $request)
    {
        try {
            $validation_rules = [
                'current_tran_pass' => 'required|min:6',
                'new_tran_pass' => 'required|min:6|same:confirm_tran_pass',
                'confirm_tran_pass' => 'required|min:6',
            ];

            $user_id = auth()->user()->id;
            $user = User::select()->where('id', $user_id)->first();
            $current_pass = $request->current_tran_pass;

            $check = Hash::check($current_pass, $user->transaction_password);

            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following errors'
                ]);
            } else if (!$check) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Current Password Not Matched'
                ]);
            } else {
                $new_pass = $request->new_tran_pass;
                $encrpt_pass = encrypt($new_pass);
                $hash_pass = Hash::make($new_pass);

                $user_tran_pass = User::select()->where('id', auth()->user()->id)->first();
                $user_pass_log = Log::select()->where('user_id', auth()->user()->id)->first();
                if ($user_tran_pass && $user_pass_log) {
                    $user_tran_pass->transaction_password = $hash_pass;
                    $update = $user_tran_pass->save();
                    $user_pass_log->transaction_password = $encrpt_pass;
                    $update = $user_pass_log->save();
                    if ($update) {
                        //<---client email as user id
                        $user = User::find(auth()->user()->id);
                        activity("Admin transaction password change")
                            ->causedBy(auth()->user()->id)
                            ->withProperties($request->all())
                            ->event("transaction password change")
                            ->performedOn($user)
                            ->log("The IP address " . request()->ip() . " has been " .  "change admin admin transaction password");
                        // end activity log----------------->>
                        return Response::json([
                            'status' => true,
                            'message' => 'Transaction Password successfully Changed',
                            'success_title' => 'Update'
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // update account details
    public function account_details(Request $request)
    {
        try {
            $validation_rules = [
                'old_password' => 'required|min:6|max:32',
                'new_password' => 'required|min:6|max:32',
                'confirm_password' => 'required|min:6|max:32|same:new_password'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following errors!'
                ]);
            }
            // check old password
            $password = User::where('id', auth()->user()->id)->select('password')->first();
            if (!Hash::check($request->old_password, $password->password)) {
                return Response::json([
                    'status' => false,
                    'errors' => ['old_password' => 'Old Password Not Matched!'],
                    'message' => 'Old Password not matched!'
                ]);
            }
            // update user table
            $update = User::where('id', auth()->user()->id)->update([
                'password' => Hash::make($request->new_password),
            ]);
            // update log table
            Log::where('user_id', auth()->user()->id)->update(
                [
                    'password' => encrypt($request->new_password),
                ]
            );
            //<---client email as user id
            $user = User::find(auth()->user()->id);
            activity("Admin account details update")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event("account details update")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been " .  "Update admin account details");
            // end activity log----------------->>
            // send email to admin
            EmailService::send_email('trader-password-change', [
                'user_id' => auth()->user()->id,
                'password'             => $request->new_password,
            ]);
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'Admin account details successfully updated'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Admin Account Details could not updated'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error'
            ]);
        }
    }
    // update address
    public function update_address(Request $request)
    {
        try {
            $validation_rules = [
                'country' => 'nullable',
                'state' => 'nullable|min:3|max:32',
                'city' => 'nullable|min:3|max:32',
                'zipcode' => 'nullable|min:3|max:32',
                'address' => 'nullable|min:3|max:100',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            $update = UserDescription::updateOrCreate(
                [
                    'user_id' => auth()->user()->id,
                ],
                [
                    'country' => $request->country,
                    'state' => $request->state,
                    'city' => $request->city,
                    'zip_code' => $request->zipcode,
                    'address' => $request->address,
                ]
            );
            if ($update) {
                //<---client email as user id
                $user = User::find(auth()->user()->id);
                activity("Admin address update")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("address update")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been " .  "Update admin address");
                // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'message' => 'Admin address successfully update!'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Upadte failed, Please try again later!'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // update personal info
    public function update_personal_info(Request $request)
    {
        try {
            $validation_rules = [
                'name' => 'required|min:3|max:100',
                'gender' => 'nullable',
                'date_of_birth' => 'nullable'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // update user table
            $update = User::where('users.id', auth()->user()->id)->update([
                'name' => $request->name,
            ]);
            // update user description
            $update = UserDescription::updateOrCreate(
                [
                    'user_id' => auth()->user()->id,
                ],
                [
                    'gender' => $request->gender,
                    'date_of_birth' => $request->date_of_birth
                ]
            );
            if ($update) {
                //<---client email as user id
                $user = User::find(auth()->user()->id);
                activity("Admin personal info update")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("personal update")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been " .  "Update admin personal info");
                // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'message' => 'Admin personal info successfully update!'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Upadte failed, Please try again later!'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // update social links
    public function update_social_links(Request $request)
    {
        try {
            $validation_rules = [
                'twitter' => 'nullable|min:3|max:100',
                'facebook' => 'nullable|min:3|max:191',
                'telegram' => 'nullable|min:3|max:191',
                'linkedin' => 'nullable|min:3|max:191',
                'skype' => 'nullable|min:3|max:191',
                'whatsapp' => 'nullable|min:3|max:191',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // update social links
            $update = SocialLink::updateOrCreate(
                [
                    'user_id' => auth()->user()->id
                ],
                [
                    'user_id' => auth()->user()->id,
                    'skype' => $request->skype,
                    'whatsapp' => $request->whatsapp,
                    'linkedin' => $request->linkedin,
                    'facebook' => $request->facebook,
                    'twitter' => $request->twitter,
                    'telegram' => $request->telegram
                ],
            );
            if ($update) {
                //<---client email as user id
                $user = User::find(auth()->user()->id);
                activity("Admin social link update")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("address update")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been " .  "Update admin social link");
                // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'message' => 'Admin social links successfully update!'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Upadte failed, Please try again later!'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // sending mail change otp
    public function email_change_otp(Request $request)
    {
        try {
            $status = OtpService::send_otp(auth()->user()->id, 'admin-mail-change');
            if ($status) {
                //<---client email as user id
                $user = User::find(auth()->user()->id);
                activity("Admin send otp for email change")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("admin email otp")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been " .  "send otp for email change");
                // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'message' => 'Email change OTP successfully send!'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'OTP sending failed please resubmit request!'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // phone change otp
    public function phone_change_otp(Request $request)
    {
        try {
            $status = OtpService::send_otp(auth()->user()->id, 'admin-phone-change');
            if ($status) {
                //<---client email as user id
                $user = User::find(auth()->user()->id);
                activity("Admin send otp for phone change")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("admin phone otp")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been " .  "send otp for phone change");
                // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'message' => 'Email change OTP successfully send!'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'OTP sending failed please resubmit request!'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // change email
    public function email_change(Request $request)
    {
        try {
            $validation_rules = [
                'new_email' => 'required|email',
                'confirm_new_email' => 'required|email|same:new_email',
                'otp_1' => 'required',
                'otp_2' => 'required',
                'otp_3' => 'required',
                'otp_4' => 'required',
                'otp_5' => 'required',
                'otp_6' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // check mail already exists across all user types (excluding current user)

            if (User::where('email', $request->new_email)->where('id', '!=', auth()->user()->id)->exists()) {
                return Response::json([
                    'status' => false,
                    'errors' => ['new_email' => 'This email is already registered by another user'],
                    'message' => 'This email is already registered by another user'
                ]);
            }
            $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
            if ($request->session()->get('admin-mail-change') == $request_otp) {
                $time = session('otp_set_time');
                $minutesBeforeSessionExpire = 5;
                if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                    $update = User::where('id', auth()->user()->id)->update([
                        'email' => $request->new_email,
                    ]);
                    if ($update) {
                        //<---client email as user id
                        $user = User::find(auth()->user()->id);
                        activity("Admin email change")
                            ->causedBy(auth()->user()->id)
                            ->withProperties($request->all())
                            ->event("email change")
                            ->performedOn($user)
                            ->log("The IP address " . request()->ip() . " has been " .  "change admin email");
                        // end activity log----------------->>
                        return Response::json([
                            'status' => true,
                            'message' => 'Admin email successfully updated!'
                        ]);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'Upldate failed, Please try again later!'
                    ]);
                }
                return Response::json([
                    'otp_status' => false,
                    'message' => 'OTP Time Out! Please resend OTP',
                    'errors' => ['otp' => 'OTP time out! resend OTP']
                ]);
            }
            return Response::json([
                'otp_status' => false,
                'message' => 'OTP not matched! Please resend OTP',
                'errors' => ['otp' => 'OTP not matched! resend OTP']
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    public function phone_change(Request $request)
    {
        try {
            $validation_rules = [
                'new_phone' => 'required',
                'confirm_new_phone' => 'required|same:new_phone',
                'otp_1' => 'required',
                'otp_2' => 'required',
                'otp_3' => 'required',
                'otp_4' => 'required',
                'otp_5' => 'required',
                'otp_6' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // check phone number already exists across all user types (excluding current user)

            if (User::where('phone', $request->new_phone)->where('id', '!=', auth()->user()->id)->exists()) {
                return Response::json([
                    'status' => false,
                    'errors' => ['new_phone' => 'This phone number is already registered by another user'],
                    'message' => 'This phone number is already registered by another user'
                ]);
            }
            $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
            if ($request->session()->get('admin-phone-change') == $request_otp) {
                $time = session('otp_set_time');
                $minutesBeforeSessionExpire = 5;
                if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                    $update = User::where('id', auth()->user()->id)->update([
                        'phone' => $request->new_phone,
                    ]);
                    if ($update) {
                        //<---client email as user id
                        $user = User::find(auth()->user()->id);
                        activity("Admin phone change")
                            ->causedBy(auth()->user()->id)
                            ->withProperties($request->all())
                            ->event("phone number change")
                            ->performedOn($user)
                            ->log("The IP address " . request()->ip() . " has been " .  "change admin phone number");
                        // end activity log----------------->>
                        return Response::json([
                            'status' => true,
                            'message' => 'Admin email successfully updated!'
                        ]);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'Upldate failed, Please try again later!'
                    ]);
                }
                return Response::json([
                    'otp_status' => false,
                    'message' => 'OTP Time Out! Please resend OTP',
                    'errors' => ['otp' => 'OTP time out! resend OTP']
                ]);
            }
            return Response::json([
                'otp_status' => false,
                'message' => 'OTP not matched! Please resend OTP',
                'errors' => ['otp' => 'OTP not matched! resend OTP']
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
}
