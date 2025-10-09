<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\ClientGroup;
use App\Models\FinanceOp;
use App\Models\Log;
use App\Models\RequiredField;
use App\Models\SoftwareSetting;
use App\Models\TempUser;
use App\Models\Traders\SocialLink;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\UserOtpSetting;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\password\PasswordService;
use App\Services\systems\NotificationService;
use Database\Seeders\SoftwareSetings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TraderSignupController extends Controller
{
    public function trader_signup(Request $request)
    {

        try {
            $required_field = RequiredField::first();
            $phone = isset($required_field->phone) ? $required_field->phone : 0;
            $phone = $phone ? 'required' : 'nullable';

            $gender = isset($required_field->gender) ? $required_field->gender : 0;
            $gender = $gender ? 'required' : 'nullable';
            $password = isset($required_field->password) ? $required_field->password : 0;


            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'gender' => "$gender|string|in:male,female,other",
                'email' => 'required|email',
                'phone' => "$phone|string|max:25",
                'date_of_birth' => ['required', 'date', 'before_or_equal:' . now()->subYears(16)->format('d-m-Y')],
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following error',
                    'errors' => $validator->errors(),
                ]);
            }
            // check the email already exists or not across all user types
            $email_exists = User::where('email', $request->input('email'))->exists();
            if ($email_exists) {
                return Response::json([
                    'status' => false,
                    'message' => 'This email is already registered. Please use a different email address.',
                    'errors' => ['email' => 'This email is already registered. Please use a different email address.'],
                ]);
            }

            // check the phone number already exists or not across all user types
            $phone_exists = User::where('phone', $request->input('phone'))->exists();
            if ($phone_exists) {
                return Response::json([
                    'status' => false,
                    'message' => 'This phone number is already registered. Please use a different phone number.',
                    'errors' => ['phone' => 'This phone number is already registered. Please use a different phone number.'],
                ]);
            }
            // check system configuration
            $system_config = SystemConfig::first();
            $meta_account = $system_config->create_meta_acc;
            $social_account = $system_config->social_account;
            $address_section = $system_config->address_section;
            // software settings
            $software_settings = SoftwareSetting::first();
            $auto_activation = $software_settings->auto_activation;

            $gender = $request->input('gender', 'male');
            $gender = $gender ? $gender : 'male';

            if (!$meta_account && !$social_account && !$password && !$address_section) {
                $password = PasswordService::reset_password();
                $pin = PasswordService::reset_transaction_pin();
                $create = User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'type' => 0,
                    'password' => Hash::make($password),
                    'transaction_password' => Hash::make($pin),
                    'live_status' => 'live',
                    'active_status' => 1,
                    'email_verified_at' => $auto_activation ? now() : null,
                    'ib_group_id' => 1,
                    'combine_access' => 1,
                    'trading_ac_limit' => 500,
                    'ip_address' => request()->ip(),
                ]);
                // user descriptions
                $userDescription = new UserDescription([
                    'gender' => $gender,
                    'date_of_birth' => date('Y-m-d H:i:s', strtotime($request->input('date_of_birth'))),
                ]);
                $create->description()->save($userDescription);
                // finance options
                $financeOptions = new FinanceOp([
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
                $create->financeOptions()->save($financeOptions);
                // user otp settings
                $otpOptions = new UserOtpSetting([
                    'account_create' => 0,
                    'deposit' => 0,
                    'withdraw' => 1,
                    'transfer' => 1,
                ]);
                $create->otpOptions()->save($otpOptions);
                // log
                $userLog = new Log([
                    'password' => encrypt($password),
                    'transaction_password' => encrypt($pin),
                ]);
                $create->secureLog()->save($userLog);
                // social link
                $socialLink = new SocialLink([
                    'skype' => 'NA',
                ]);
                $create->socialLink()->save($socialLink);
                $activation_link = url('/activation/user/' . encrypt($create->id));
                if ($create) {
                    
                    EmailService::send_email('trader-registration', [
                        'loginUrl'                   => $activation_link,
                        'activation_link'            => $activation_link,
                        'clientPassword'             => $password,
                        'password'                   => $password,
                        'clientTransactionPassword'  => $pin,
                        'transaction_password'       => $pin,
                        'server'                     => $request->platform,
                        'user_id' => $create->id,
                    ]);
                    MailNotificationService::admin_notification([
                        'name' => $create->name,
                        'email' => $create->email,
                        'type' => 'registration',
                        'client_type' => 'trader',
                    ]);
                    NotificationService::system_notification([
                        'type' => 'trader_registration',
                        'user_id' => $create->id,
                        'user_type' => 'trader',
                        'table_id' => $create->id,
                        'category' => 'client',
                    ]);
                    return Response::json([
                        'status' => true,
                        'mext_step' => false,
                        'message' => 'Trader signup successfully done, we send a mail to ' . $request->input('email')
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'mext_step' => false,
                    'message' => 'Trader signup failed, Please try again later'
                ]);
            } else {
                $create = TempUser::updateOrCreate(
                    [
                        'email' => $request->input('email'),
                    ],
                    [
                        'name' => $request->input('name'),
                        'phone' => $request->input('phone'),
                        'date_of_birth' => date('Y-m-d H:i:s', strtotime($request->input('date_of_birth'))),
                        'gender' => $gender,
                    ]
                );
                if ($create) {
                    return Response::json([
                        'status' => true,
                        'next_step' => $create->id,
                        'message' => 'Basic info successfully submited',
                    ]);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // address sections
    public function trader_address(Request $request)
    {
        try {
            $required_field = RequiredField::first();
            $phone = isset($required_field->phone) ? $required_field->phone : 0;
            $gender = isset($required_field->gender) ? $required_field->gender : 0;

            $country = isset($required_field->country) ? $required_field->country : 0;
            $country = $country ? 'required' : 'nullable';

            $state = isset($required_field->state) ? $required_field->state : 0;
            $state = $state ? 'required' : 'nullable';

            $city = isset($required_field->city) ? $required_field->city : 0;
            $city = $city ? 'required' : 'nullable';

            $zip_code = isset($required_field->zip_code) ? $required_field->zip_code : 0;
            $zip_code = $zip_code ? 'required' : 'nullable';

            $address = isset($required_field->address) ? $required_field->address : 0;
            $address = $address ? 'required' : 'nullable';

            $password = isset($required_field->password) ? $required_field->password : 0;

            $validator = Validator::make($request->all(), [
                'country' => "$country|numeric|exists:countries,id",
                'state' => "$state|string|max:100",
                'city' => "$city|max:100",
                'zipcode' => "$zip_code|string|max:25",
                'address' => "$address|string|max:100",
                'previous_id' => 'required|exists:temp_users,id'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following error',
                    'errors' => $validator->errors(),
                ]);
            }
            $system_config = SystemConfig::first();
            $meta_account = $system_config->create_meta_acc;
            $social_account = $system_config->social_account;
            // software settings
            $software_settings = SoftwareSetting::first();
            $auto_activation = $software_settings->auto_activation;

            $temp_user = TempUser::find($request->input('previous_id'));
            // check basic info exist or not
            if (!$temp_user) {
                return Response::json([
                    'status' => false,
                    'message' => 'The basic info is required, go to first step fill the required form'
                ]);
            }
            // check validation for basic info
            if (!isset($temp_user->name) || $temp_user->name == "") {
                return Response::json([
                    'status' => false,
                    'message' => 'The mane filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->gender) && $gender) || ($gender && $temp_user->gender == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The gender filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->email)) || ($temp_user->email == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The email filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->phone) && $phone) || ($temp_user->phone == "" && $phone)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The phone filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->date_of_birth)) || ($temp_user->date_of_birth == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The date of birth filled required, thats missing in first step',
                ]);
            }
            // check the email already taken or not across all user types
            if (User::where('email', $temp_user->email)->exists()) {
                return Response::json([
                    'status' => false,
                    'message' => 'The email ' . $temp_user->email . ' is already registered. Please use a different email address.',

                ]);
            }

            // check the phone number already taken or not across all user types
            if (User::where('phone', $temp_user->phone)->exists()) {
                return Response::json([
                    'status' => false,
                    'message' => 'The phone number ' . $temp_user->phone . ' is already registered. Please use a different phone number.',

                ]);
            }

            if (!$meta_account && !$social_account && !$password) {
                $password = PasswordService::reset_password();
                $pin = PasswordService::reset_transaction_pin();

                $create = User::create([
                    'name' => $temp_user->name,
                    'email' => $temp_user->email,
                    'phone' => $temp_user->phone,
                    'type' => 0,
                    'password' => Hash::make($password),
                    'transaction_password' => Hash::make($pin),
                    'live_status' => 'live',
                    'active_status' => 1,
                    'email_verified_at' => $auto_activation ? now() : null,
                    'ib_group_id' => 1,
                    'combine_access' => 1,
                    'trading_ac_limit' => 500,
                    'ip_address' => request()->ip(),
                ]);
                // user descriptions
                $userDescription = new UserDescription([
                    'gender' => $gender,
                    'date_of_birth' => date('Y-m-d', strtotime($temp_user->date_of_birth)),
                    'country_id' => $request->input('country'),
                    'state' => $request->input('state'),
                    'zip_code' => $request->input('zipcode'),
                    'address' => $request->input('address'),
                ]);
                $create->description()->save($userDescription);
                // finance options
                $financeOptions = new FinanceOp([
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
                $create->financeOptions()->save($financeOptions);
                // user otp settings
                $otpOptions = new UserOtpSetting([
                    'account_create' => 0,
                    'deposit' => 0,
                    'withdraw' => 1,
                    'transfer' => 1,
                ]);
                $create->otpOptions()->save($otpOptions);
                // log
                $userLog = new Log([
                    'password' => encrypt($password),
                    'transaction_password' => encrypt($pin),
                ]);
                $create->secureLog()->save($userLog);
                // social link
                $socialLink = new SocialLink([
                    'skype' => 'NA',
                ]);
                $create->socialLink()->save($socialLink);
                $activation_link = url('/activation/user/' . encrypt($create->id));
                if ($create) {
                    $temp_user->delete();
                    EmailService::send_email('trader-registration', [
                        'loginUrl'                   => $activation_link,
                        'activation_link'            => $activation_link,
                        'clientPassword'             => $password,
                        'password'                   => $password,
                        'clientTransactionPassword'  => $pin,
                        'transaction_password'       => $pin,
                        'server'                     => $request->platform,
                        'user_id' => $create->id,
                    ]);
                    MailNotificationService::admin_notification([
                        'name' => $create->name,
                        'email' => $create->email,
                        'type' => 'registration',
                        'client_type' => 'trader',
                    ]);
                    NotificationService::system_notification([
                        'type' => 'trader_registration',
                        'user_id' => $create->id,
                        'user_type' => 'trader',
                        'table_id' => $create->id,
                        'category' => 'client',
                    ]);
                    return Response::json([
                        'status' => true,
                        'mext_step' => false,
                        'message' => 'Trader signup successfully done, we send a mail to ' . $request->input('email')
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'mext_step' => false,
                    'message' => 'Trader signup failed, Please try again later'
                ]);
            }
            // if need  to store temp 
            else {
                $temp_user->country = $request->input('country');
                $temp_user->state = $request->input('state');
                $temp_user->city = $request->input('city');
                $temp_user->zipcode = $request->input('zipcode');
                $temp_user->address = $request->input('address');
                $update = $temp_user->save();
                if ($update) {
                    return Response::json([
                        'status' => true,
                        'next_step' => $temp_user->id,
                        'message' => 'Address data successfully saved, please go next step'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'next_step' => false,
                    'message' => 'Something went wrong, please try again later'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'next_step' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // social accounts
    public function trader_socail_link(Request $request)
    {
        try {
            $required_field = RequiredField::first();
            $phone = isset($required_field->phone) ? $required_field->phone : 0;
            $gender = isset($required_field->gender) ? $required_field->gender : 0;
            $country = isset($required_field->country) ? $required_field->country : 0;
            $state = isset($required_field->state) ? $required_field->state : 0;
            $city = isset($required_field->city) ? $required_field->city : 0;
            $zip_code = isset($required_field->zip_code) ? $required_field->zip_code : 0;
            $address = isset($required_field->address) ? $required_field->address : 0;
            $password = isset($required_field->password) ? $required_field->password : 0;

            $validator = Validator::make($request->all(), [
                'skype' => 'nullable|string|max:255', // Example validation for Skype
                'linkedin' => 'nullable|url|max:255', // Example validation for LinkedIn (valid URL)
                'facebook' => 'nullable|url|max:255', // Example validation for Facebook (valid URL)
                'twitter' => 'nullable|url|max:255', // Example validation for Twitter (valid URL)
                'telegram' => 'nullable|string|max:255', // Example validation for Telegram
                'previous_id' => 'required'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'next_step' => false,
                    'message' => 'Validation error, please fix the following error',
                    'errors' => $validator->errors(),
                ]);
            }
            $system_config = SystemConfig::first();
            $meta_account = $system_config->create_meta_acc;
            $social_account = $system_config->social_account;
            // software settings
            $software_settings = SoftwareSetting::first();
            $auto_activation = $software_settings->auto_activation;

            $temp_user = TempUser::find($request->input('previous_id'));
            // check basic info and address exist or not exist or not
            if (!$temp_user) {
                return Response::json([
                    'status' => false,
                    'message' => 'The basic info is required, go to first step fill the required form'
                ]);
            }
            // check validation for basic info
            if (!isset($temp_user->name) || $temp_user->name == "") {
                return Response::json([
                    'status' => false,
                    'message' => 'The mane filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->gender) && $gender) || ($gender && $temp_user->gender == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The gender filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->email)) || ($temp_user->email == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The email filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->phone) && $phone) || ($temp_user->phone == "" && $phone)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The phone filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->date_of_birth)) || ($temp_user->date_of_birth == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The date of birth filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->country) && $country) || ($temp_user->country == "" && $country)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The country filled required, thats missing in address section',
                ]);
            }
            if ((!isset($temp_user->state) && $state) || ($temp_user->state == "" && $state)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The state filled required, thats missing in address section',
                ]);
            }
            if ((!isset($temp_user->city) && $city) || ($temp_user->city == "" && $city)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The city filled required, thats missing in address section',
                ]);
            }
            if ((!isset($temp_user->address) && $address) || ($temp_user->address == "" && $address)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The Address filled required, thats missing in address section',
                ]);
            }
            // check the email already taken or not across all user types
            if (User::where('email', $temp_user->email)->exists()) {
                return Response::json([
                    'status' => false,
                    'message' => 'The email ' . $temp_user->email . ' is already registered. Please use a different email address.',

                ]);
            }

            // check the phone number already taken or not across all user types
            if (User::where('phone', $temp_user->phone)->exists()) {
                return Response::json([
                    'status' => false,
                    'message' => 'The phone number ' . $temp_user->phone . ' is already registered. Please use a different phone number.',

                ]);
            }

            if (!$meta_account && !$password) {
                $password = PasswordService::reset_password();
                $pin = PasswordService::reset_transaction_pin();

                $create = User::create([
                    'name' => $temp_user->name,
                    'email' => $temp_user->email,
                    'phone' => $temp_user->phone,
                    'type' => 0,
                    'password' => Hash::make($password),
                    'transaction_password' => Hash::make($pin),
                    'live_status' => 'live',
                    'active_status' => 1,
                    'email_verified_at' => $auto_activation ? now() : null,
                    'ib_group_id' => 1,
                    'combine_access' => 1,
                    'trading_ac_limit' => 500,
                    'ip_address' => request()->ip(),
                ]);
                // user descriptions
                $userDescription = new UserDescription([
                    'gender' => $gender,
                    'date_of_birth' => date('Y-m-d', strtotime($temp_user->date_of_birth)),
                    'country_id' => $temp_user->country,
                    'state' => $temp_user->state,
                    'zip_code' => $temp_user->zipcode,
                    'address' => $temp_user->address,
                ]);
                $create->description()->save($userDescription);
                // finance options
                $financeOptions = new FinanceOp([
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
                $create->financeOptions()->save($financeOptions);
                // user otp settings
                $otpOptions = new UserOtpSetting([
                    'account_create' => 0,
                    'deposit' => 0,
                    'withdraw' => 1,
                    'transfer' => 1,
                ]);
                $create->otpOptions()->save($otpOptions);
                // log
                $userLog = new Log([
                    'password' => encrypt($password),
                    'transaction_password' => encrypt($pin),
                ]);
                $create->secureLog()->save($userLog);
                // social link
                $socialLink = new SocialLink([
                    'skype' => $request->input('skype'),
                    'linkedin' => $request->input('linkedin'),
                    'facebook' => $request->input('facebook'),
                    'twitter' => $request->input('twitter'),
                    'telegram' => $request->input('telegram'),
                ]);
                $create->socialLink()->save($socialLink);
                $activation_link = url('/activation/user/' . encrypt($create->id));
                if ($create) {
                    $temp_user->delete();
                    EmailService::send_email('trader-registration', [
                        'loginUrl'                   => $activation_link,
                        'activation_link'            => $activation_link,
                        'clientPassword'             => $password,
                        'password'                   => $password,
                        'clientTransactionPassword'  => $pin,
                        'transaction_password'       => $pin,
                        'server'                     => $request->platform,
                        'user_id' => $create->id,
                    ]);
                    MailNotificationService::admin_notification([
                        'name' => $create->name,
                        'email' => $create->email,
                        'type' => 'registration',
                        'client_type' => 'trader',
                    ]);
                    NotificationService::system_notification([
                        'type' => 'trader_registration',
                        'user_id' => $create->id,
                        'user_type' => 'trader',
                        'table_id' => $create->id,
                        'category' => 'client',
                    ]);
                    return Response::json([
                        'status' => true,
                        'mext_step' => false,
                        'message' => 'Trader signup successfully done, we send a mail to ' . $request->input('email')
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'mext_step' => false,
                    'message' => 'Trader signup failed, Please try again later'
                ]);
            }
            // if need  to store temp 
            else {
                $temp_user->skype = $request->input('skype');
                $temp_user->linkedin = $request->input('linkedin');
                $temp_user->facebook = $request->input('facebook');
                $temp_user->twitter = $request->input('twitter');
                $temp_user->telegram = $request->input('telegram');
                $update = $temp_user->save();
                if ($update) {
                    return Response::json([
                        'status' => true,
                        'next_step' => $temp_user->id,
                        'message' => 'Social accounts successfully saved, please go to next step'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'next_step' => false,
                    'message' => 'Something went wrong, please try again later'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // create meta account
    public function trader_meta_account(Request $request)
    {
        try {
            $required_field = RequiredField::first();
            $phone = isset($required_field->phone) ? $required_field->phone : 0;
            $gender = isset($required_field->gender) ? $required_field->gender : 0;
            $country = isset($required_field->country) ? $required_field->country : 0;
            $state = isset($required_field->state) ? $required_field->state : 0;
            $city = isset($required_field->city) ? $required_field->city : 0;
            $zip_code = isset($required_field->zip_code) ? $required_field->zip_code : 0;
            $address = isset($required_field->address) ? $required_field->address : 0;
            $password = isset($required_field->password) ? $required_field->password : 0;

            $validator = Validator::make($request->all(), [
                'platform' => 'required|string|max:50|in:mt5,mt4,edgeTrader',
                'account_type' => 'required|numeric|exists:client_groups,id',
                'leverage' => 'required|numeric',
                'previous_id' => 'required'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following error',
                    'errors' => $validator->errors(),
                ]);
            }
            $system_config = SystemConfig::first();
            $meta_account = $system_config->create_meta_acc;
            $social_account = $system_config->social_account;
            // software settings
            $software_settings = SoftwareSetting::first();
            $auto_activation = $software_settings->auto_activation;

            $temp_user = TempUser::find($request->input('previous_id'));
            // check basic info and address exist or not exist or not
            if (!$temp_user) {
                return Response::json([
                    'status' => false,
                    'message' => 'The basic info is required, go to first step fill the required form'
                ]);
            }
            // check validation for basic info
            if (!isset($temp_user->name) || $temp_user->name == "") {
                return Response::json([
                    'status' => false,
                    'message' => 'The mane filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->gender) && $gender) || ($gender && $temp_user->gender == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The gender filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->email)) || ($temp_user->email == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The email filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->phone) && $phone) || ($temp_user->phone == "" && $phone)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The phone filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->date_of_birth)) || ($temp_user->date_of_birth == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The date of birth filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->country) && $country) || ($temp_user->country == "" && $country)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The country filled required, thats missing in address section',
                ]);
            }
            if ((!isset($temp_user->state) && $state) || ($temp_user->state == "" && $state)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The state filled required, thats missing in address section',
                ]);
            }
            if ((!isset($temp_user->city) && $city) || ($temp_user->city == "" && $city)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The city filled required, thats missing in address section',
                ]);
            }
            if ((!isset($temp_user->address) && $address) || ($temp_user->address == "" && $address)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The Address filled required, thats missing in address section',
                ]);
            }
            // check the email already taken or not across all user types
            if (User::where('email', $temp_user->email)->exists()) {
                return Response::json([
                    'status' => false,
                    'message' => 'The email ' . $temp_user->email . ' is already registered. Please use a different email address.',

                ]);
            }

            // check the phone number already taken or not across all user types
            if (User::where('phone', $temp_user->phone)->exists()) {
                return Response::json([
                    'status' => false,
                    'message' => 'The phone number ' . $temp_user->phone . ' is already registered. Please use a different phone number.',

                ]);
            }

            if (!$password) {
                $password = PasswordService::reset_password();
                $pin = PasswordService::reset_transaction_pin();

                $create = User::create([
                    'name' => $temp_user->name,
                    'email' => $temp_user->email,
                    'phone' => $temp_user->phone,
                    'type' => 0,
                    'password' => Hash::make($password),
                    'transaction_password' => Hash::make($pin),
                    'live_status' => 'live',
                    'active_status' => 1,
                    'email_verified_at' => $auto_activation ? now() : null,
                    'ib_group_id' => 1,
                    'combine_access' => 1,
                    'trading_ac_limit' => 500,
                    'ip_address' => request()->ip(),
                ]);
                // user descriptions
                $userDescription = new UserDescription([
                    'gender' => $gender,
                    'date_of_birth' => date('Y-m-d', strtotime($temp_user->date_of_birth)),
                    'country_id' => $temp_user->country,
                    'state' => $temp_user->state,
                    'zip_code' => $temp_user->zipcode,
                    'address' => $temp_user->address,
                ]);
                $create->description()->save($userDescription);
                // finance options
                $financeOptions = new FinanceOp([
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
                $create->financeOptions()->save($financeOptions);
                // user otp settings
                $otpOptions = new UserOtpSetting([
                    'account_create' => 0,
                    'deposit' => 0,
                    'withdraw' => 1,
                    'transfer' => 1,
                ]);
                $create->otpOptions()->save($otpOptions);
                // log
                $userLog = new Log([
                    'password' => encrypt($password),
                    'transaction_password' => encrypt($pin),
                ]);
                $create->secureLog()->save($userLog);
                // social link
                $socialLink = new SocialLink([
                    'skype' => $temp_user->skype,
                    'linkedin' => $temp_user->linkedin,
                    'facebook' => $temp_user->facebook,
                    'twitter' => $temp_user->twitter,
                    'telegram' => $temp_user->telegram,
                ]);
                $create->socialLink()->save($socialLink);
                // create trading account
                $client_group = ClientGroup::find($request->input('account_type'));
                $TtradingAccount = new TradingAccount([
                    'comment' => 'Open with mobile app ' . $temp_user->email,
                    'client_type' => $client_group->account_category,
                    'leverage' => $request->input('leverage'),
                    'platform' => strtoupper($request->input('platform')),
                    'group_id' => $request->input('account_type'),
                    'approve_status' => 1,
                ]);
                $create->tradingAccount()->save($TtradingAccount);
                $activation_link = url('/activation/user/' . encrypt($create->id));
                if ($create) {
                    $temp_user->delete();
                    EmailService::send_email('trader-registration', [
                        'loginUrl'                   => $activation_link,
                        'activation_link'            => $activation_link,
                        'clientPassword'             => $password,
                        'password'                   => $password,
                        'clientTransactionPassword'  => $pin,
                        'transaction_password'       => $pin,
                        'server'                     => $request->platform,
                        'user_id' => $create->id,
                    ]);
                    MailNotificationService::admin_notification([
                        'name' => $create->name,
                        'email' => $create->email,
                        'type' => 'registration',
                        'client_type' => 'trader',
                    ]);
                    NotificationService::system_notification([
                        'type' => 'trader_registration',
                        'user_id' => $create->id,
                        'user_type' => 'trader',
                        'table_id' => $create->id,
                        'category' => 'client',
                    ]);
                    return Response::json([
                        'status' => true,
                        'mext_step' => false,
                        'message' => 'Trader signup successfully done, we send a mail to ' . $request->input('email')
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'mext_step' => false,
                    'message' => 'Trader signup failed, Please try again later'
                ]);
            }
            // if need  to store temp 
            else {
                $temp_user->platform = $request->input('platform');
                $temp_user->account_type = $request->input('account_type');
                $temp_user->leverage = $request->input('leverage');
                $temp_user->twitter = $request->input('twitter');
                $update = $temp_user->save();
                if ($update) {
                    return Response::json([
                        'status' => true,
                        'next_step' => $temp_user->id,
                        'message' => 'Trading account successfully saved, please go to next step'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'next_step' => false,
                    'message' => 'Something went wrong, please try again later'
                ]);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // last step 
    public function trader_password(Request $request)
    {
        try {
            $required_field = RequiredField::first();
            $phone = isset($required_field->phone) ? $required_field->phone : 0;
            $gender = isset($required_field->gender) ? $required_field->gender : 0;
            $country = isset($required_field->country) ? $required_field->country : 0;
            $state = isset($required_field->state) ? $required_field->state : 0;
            $city = isset($required_field->city) ? $required_field->city : 0;
            $zip_code = isset($required_field->zip_code) ? $required_field->zip_code : 0;
            $address = isset($required_field->address) ? $required_field->address : 0;
            $password = isset($required_field->password) ? $required_field->password : 0;

            $validator = Validator::make($request->all(), [
                'password' => 'required|min:6|same:confirm_password',
                'confirm_password' => 'required|min:6',
                'previous_id' => 'required|exists:temp_users,id'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following error',
                    'errors' => $validator->errors(),
                ]);
            }
            $system_config = SystemConfig::first();
            $meta_account = $system_config->create_meta_acc;
            $social_account = $system_config->social_account;
            // software settings
            $software_settings = SoftwareSetting::first();
            $auto_activation = $software_settings->auto_activation;

            $temp_user = TempUser::find($request->input('previous_id'));
            // check basic info and address exist or not exist or not
            if (!$temp_user) {
                return Response::json([
                    'status' => false,
                    'message' => 'The basic info is required, go to first step fill the required form'
                ]);
            }
            // check validation for basic info
            if (!isset($temp_user->name) || $temp_user->name == "") {
                return Response::json([
                    'status' => false,
                    'message' => 'The mane filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->gender) && $gender) || ($gender && $temp_user->gender == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The gender filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->email)) || ($temp_user->email == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The email filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->phone) && $phone) || ($temp_user->phone == "" && $phone)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The phone filled required, thats missing in first step',
                ]);
            }
            if ((!isset($temp_user->date_of_birth)) || ($temp_user->date_of_birth == "")) {
                return Response::json([
                    'status' => false,
                    'message' => 'The date of birth filled required, thats missing in first step',
                ]);
            }
            // for address section

            if ((!isset($temp_user->country) && $country) || ($temp_user->country == "" && $country)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The country filled required, thats missing in address section',
                ]);
            }
            if ((!isset($temp_user->state) && $state) || ($temp_user->state == "" && $state)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The state filled required, thats missing in address section',
                ]);
            }
            if ((!isset($temp_user->city) && $city) || ($temp_user->city == "" && $city)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The city filled required, thats missing in address section',
                ]);
            }
            if ((!isset($temp_user->address) && $address) || ($temp_user->address == "" && $address)) {
                return Response::json([
                    'status' => false,
                    'message' => 'The Address filled required, thats missing in address section',
                ]);
            }
            // check the email already taken or not across all user types
            if (User::where('email', $temp_user->email)->exists()) {
                return Response::json([
                    'status' => false,
                    'message' => 'The email ' . $temp_user->email . ' is already registered. Please use a different email address.',

                ]);
            }

            // check the phone number already taken or not across all user types
            if (User::where('phone', $temp_user->phone)->exists()) {
                return Response::json([
                    'status' => false,
                    'message' => 'The phone number ' . $temp_user->phone . ' is already registered. Please use a different phone number.',

                ]);
            }

            $password = $request->input('password');
            $pin = PasswordService::reset_transaction_pin();

            $create = User::create([
                'name' => $temp_user->name,
                'email' => $temp_user->email,
                'phone' => $temp_user->phone,
                'type' => 0,
                'password' => Hash::make($password),
                'transaction_password' => Hash::make($pin),
                'live_status' => 'live',
                'active_status' => 1,
                'email_verified_at' => $auto_activation ? now() : null,
                'ib_group_id' => 1,
                'combine_access' => 1,
                'trading_ac_limit' => 500,
                'ip_address' => request()->ip(),
            ]);
            // user descriptions
            $userDescription = new UserDescription([
                'gender' => $temp_user->gender,
                'date_of_birth' => date('Y-m-d', strtotime($temp_user->date_of_birth)),
                'country_id' => $temp_user->country,
                'state' => $temp_user->state,
                'zip_code' => $temp_user->zipcode,
                'address' => $temp_user->address,
            ]);
            $create->description()->save($userDescription);
            // finance options
            $financeOptions = new FinanceOp([
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
            $create->financeOptions()->save($financeOptions);
            // user otp settings
            $otpOptions = new UserOtpSetting([
                'account_create' => 0,
                'deposit' => 0,
                'withdraw' => 1,
                'transfer' => 1,
            ]);
            $create->otpOptions()->save($otpOptions);
            // log
            $userLog = new Log([
                'password' => encrypt($password),
                'transaction_password' => encrypt($pin),
            ]);
            $create->secureLog()->save($userLog);
            // social link
            $socialLink = new SocialLink([
                'skype' => $temp_user->skype,
                'linkedin' => $temp_user->linkedin,
                'facebook' => $temp_user->facebook,
                'twitter' => $temp_user->twitter,
                'telegram' => $temp_user->telegram,
            ]);
            $create->socialLink()->save($socialLink);
            // create trading account
            if ($meta_account) {
                $client_group = ClientGroup::find($temp_user->account_type);
                $TtradingAccount = new TradingAccount([
                    'comment' => 'Open with mobile app ' . $temp_user->email,
                    'client_type' => $client_group->account_category,
                    'leverage' => $temp_user->account_type,
                    'platform' => strtoupper($temp_user->platform),
                    'group_id' => $temp_user->account_type,
                    'approve_status' => 1,
                ]);
                $create->tradingAccount()->save($TtradingAccount);
            }
            $activation_link = url('/activation/user/' . encrypt($create->id));
            if ($create) {
                $temp_user->delete();
                EmailService::send_email('trader-registration', [
                    'loginUrl'                   => $activation_link,
                    'activation_link'            => $activation_link,
                    'clientPassword'             => $password,
                    'password'                   => $password,
                    'clientTransactionPassword'  => $pin,
                    'transaction_password'       => $pin,
                    'server'                     => $request->platform,
                    'user_id' => $create->id,
                ]);
                MailNotificationService::admin_notification([
                    'name' => $create->name,
                    'email' => $create->email,
                    'type' => 'registration',
                    'client_type' => 'trader',
                ]);
                NotificationService::system_notification([
                    'type' => 'trader_registration',
                    'user_id' => $create->id,
                    'user_type' => 'trader',
                    'table_id' => $create->id,
                    'category' => 'client',
                ]);
                return Response::json([
                    'status' => true,
                    'mext_step' => false,
                    'message' => 'Trader signup successfully done, we send a mail to ' . $request->input('email')
                ]);
            }
            return Response::json([
                'status' => false,
                'mext_step' => false,
                'message' => 'Trader signup failed, Please try again later'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
}
