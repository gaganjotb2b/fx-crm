<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\UpdateProfile;
use App\Models\admin\SystemConfig;
use App\Models\IB;
use App\Models\Log;
use App\Models\Traders\SocialLink;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class IbProfileUpdateController extends Controller
{
    public function update_account_details(Request $request)
    {
        $unique_user = User::find($request->pro_user_id)->first();
        $validation_rules = [
            'email'                     => 'required|email',
            'pro_password'              => 'required',
            'pro_transaction_pin'       => 'required',
            'kyc_status'                => 'nullable',
            'pro_group'                 => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        }
        // get ib group
        // $update = IB::where('ib_id', $request->pro_user_id)->update(['ib_group_id' => $request->pro_group]);
        $update = User::where('id', $request->pro_user_id)->update([
            'email'                 => $request->email,
            'password'              => Hash::make($request->pro_password),
            'transaction_password'  => Hash::make($request->pro_transaction_pin),
            'kyc_status'         => (isset($request->kyc_status)) ? 1 : 0,
            'ib_group_id'=>$request->pro_group
        ]);
        // update password log
        Log::where('user_id',$request->pro_user_id)->update([
            'password'=>encrypt($request->pro_password),
            'transaction_password'=>encrypt($request->pro_transaction_pin)
        ]);
        // if mail send field checked
        if ($request->pro_send_email == 'on') {
            EmailService::send_email('update-profile', [
                'user_id' => $request->pro_user_id,
                'customMessage' => (isset($request->note)) ? $request->note : '',
                'new_password'=>$request->pro_password,
                'transaction_pin'=>$request->pro_transaction_pin
            ]);
        }
        if ($update) {
            return Response::json([
                'status' => true,
                'message' => 'Profile account details auccessfully updated.'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Somthing went wrong please try again later!'
        ]);
    }

    // update personal info
    public function update_personal_info(Request $request)
    {
        $unique_user = User::find($request->pro_user_id)->first();
        $validation_rules = [
            'pro_name'                  => 'required',
            'pro_phone'                 => 'required',
            'pro_city'                  => 'required',
            'pro_state'                 => 'required',
            'pro_zip_code'              => 'required',
            'pro_address'               => 'required',
            'pro_country'               => 'required',
        ];
        $valid_msg = [
            'pro_name.required'=>'The full name field is required',
            'pro_phone.required'=>'The phone field is required',
            'pro_city.required'=>'The city field is required',
            'pro_state.required'=>'The state field is required',
            'pro_zip_code.required'=>'The zipcode field is required',
            'pro_address.required'=>'The address field is required',
            'pro_country.required'=>'The country field is required',
        ];
        $validator = Validator::make($request->all(), $validation_rules,$valid_msg);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        }
        // update user table
        $update = User::where('id', $request->pro_user_id)->update([
            'name'                  => $request->pro_name,
            'phone'                 => $request->pro_phone,
        ]);
        // update user description table
        $update = UserDescription::where('user_id', $request->pro_user_id)->update([
            'city'          => $request->pro_city,
            'state'         => $request->pro_state,
            'zip_code'      => $request->pro_zip_code,
            'address'       => $request->pro_address,
            'country_id'    => $request->pro_country,
        ]);
        if ($update) {
            return Response::json([
                'status' => true,
                'message' => 'Profile personal info auccessfully updated.'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Somthing went wrong please try again later!'
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
        if (SocialLink::where('user_id', $request->pro_user_id)->exists()) {
            $social_link = SocialLink::where('user_id', $request->pro_user_id)->update([
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'whatsapp' => $request->whatsapp,
                'linkedin' => $request->linkedin,
                'telegram' => $request->telegram,
                'skype' => $request->skype,
            ]);
        } else {
            $social_link = SocialLink::create([
                'user_id' => $request->pro_user_id,
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
