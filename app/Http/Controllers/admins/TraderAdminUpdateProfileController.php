<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\KycVerification;
use App\Models\Log;
use App\Models\Traders\SocialLink;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use App\Services\CombinedService;
use Illuminate\Support\Facades\Validator;

class TraderAdminUpdateProfileController extends Controller
{
    public function update_account_details(Request $request)
    {
        $validation_rules = [
            'email' => 'nullable|email',
            'app_investment' => 'required|numeric',
            'password' => 'nullable|max:32',
            'transaction_pin' => 'nullable|max:32',
            'trading_ac_limit' => 'required|min:0'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        }
        // update users table
        $user = User::find($request->user_id);
        // check email already exists or no
        if (CombinedService::is_combined()) {
            // check email for combined crm
            $exists = User::where('email', $request->input('email'))
            ->whereNot('id', $user->id)
            ->where(function ($query) use ($user) {
                $query->where('type', 0)
                    ->orWhere('type', 4);
            })->exists();
            if ($exists) {
                return Response::json([
                    'status' => false,
                    'message' => 'Email already taken by another user',
                    'errors' => ['email' => 'Email already taken by another user']
                ]);
            }
        } else {
            // check email for normal crm
            $exists = User::where('email', $request->input('email'))
            ->whereNot('id', $user->id)
            ->where(function ($query) use ($user) {
                $query->where('type', 0);
            })->exists();
            if ($exists) {
                return Response::json([
                    'status' => false,
                    'message' => 'Email already taken by another user',
                    'errors' => ['email' => 'Email already taken by another user']
                ]);
            }
        }
        // update user info
        // check user is null
        if ($request->input('email')) {
            $user->email = $request->input('email');
        }
        $user->app_investment = $request->app_investment;
        $user->password = Hash::make($request->password);
        $user->transaction_password = Hash::make($request->transaction_pin);
        $user->trading_ac_limit = $request->trading_ac_limit;
        $user->kyc_status  = (isset($request->kyc_status)) ? 1 : 0;
        $update = $user->Save();
        // update password log
        $password_log = Log::where('user_id', $request->user_id)->first();
        $password_log->password = encrypt($request->password);
        $password_log->transaction_password = encrypt($request->transaction_pin);
        $log_update = $password_log->save();
        if ($update && $log_update) {
            $status_mail = false;
            if (isset($request->has_mail)) {
                $status_mail = EmailService::send_email('update-profile', [
                    'user_id' => $request->user_id,
                    'customMessage' => 'Updated your profile by admin',
                    'new_password' => $request->password,
                    'transaction_pin' => $request->transaction_pin,
                    'admin' => ucwords(auth()->user()->name)
                ]);
            }
            if ($status_mail) {
                return Response::json([
                    'status' => true,
                    'message' => 'Profile updated and mail successfully sent'
                ]);
            }
            return Response::json([
                'status' => true,
                'message' => 'Profile updated and but mail not sending'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Something went wrong please try again later!'
        ]);
    }
    // update persoanl info
    public function update_personal_details(Request $request)
    {
        $validation_rules = [
            'name' => 'required',
            'phone' => 'required|max:32',
            'country' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'zip_code' => 'required|max:32',
            'address' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        }

        // update users table
        $user = User::find($request->user_id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user_kyc_status = (isset($request->kyc_status)) ? 1 : 0;
        $update = $user->Save();

        // update user description table
        $user_description = UserDescription::where('user_id', $request->user_id)->first();
        $user_description->country_id = $request->country;
        $user_description->state = $request->state;
        $user_description->city = $request->city;
        $user_description->zip_code = $request->zip_code;
        $user_description->address = $request->address;
        $des_update = $user_description->save();
        if ($update && $des_update) {

            return Response::json([
                'status' => true,
                'message' => 'Personal info updated Successfully'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Something went wrong please try again later!'
        ]);
    }
    // update social link
    public function update_social_details(Request $request)
    {
        $validation_rules = [
            'facebook' => 'nullable|max:191',
            'twitter' => 'nullable|max:191',
            'whatsapp' => 'nullable|max:191',
            'linkedin' => 'nullable|max:191',
            'telegram' => 'nullable|max:191',
            'skype' => 'nullable|max:191',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        }
        if (SocialLink::where('user_id', $request->user_id)->exists()) {
            $social_link = SocialLink::where('user_id', $request->user_id)->update([
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'whatsapp' => $request->whatsapp,
                'linkedin' => $request->linkedin,
                'telegram' => $request->telegram,
                'skype' => $request->skype,
            ]);
        } else {
            $social_link = SocialLink::create([
                'user_id' => $request->user_id,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'whatsapp' => $request->whatsapp,
                'linkedin' => $request->linkedin,
                'telegram' => $request->telegram,
                'skype' => $request->skype
            ]);
        }
        if ($social_link) {

            return Response::json([
                'status' => true,
                'message' => 'Social link updated updated Successfully'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Something went wrong please try again later!'
        ]);
    }
}
