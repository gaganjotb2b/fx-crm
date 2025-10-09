<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //update profile
    public function update_profile(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            // update user table
            $update = User::where('id', $request->user_id)->update([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);
            // update user description
            $update_description = UserDescription::where('user_id', $request->user_id)->update([
                'country_id' => $request->country_id,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zipcode,
            ]);
            if ($update) {
                return ([
                    'status' => true,
                    'message' => 'Profile successfully updated!'
                ]);
            }
            return ([
                'status' => false,
                'message' => 'Profile update failed, Network error',
            ]);
        }
        return ([
            'status' => false,
            'message' => 'User not found, Please try again later'
        ]);
    }
    // get profile data
    public function get_profile_data(Request $request)
    {
        $user = User::where('users.id', $request->user_id)
            ->select(
                'users.id',
                'address',
                'city',
                'zip_code',
                'state',
                'name',
                'email',
                'phone',
                'country_id',
                'email_verified_at',
                'email_auth',
                'email_verification'
            )
            ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')->first();
        if ($user) {
            return ([
                'status' => true,
                'name' => $user->name,
                'email' => $user->email,
                'user_id' => $request->id,
                'phone' => $user->phone,
                'address' => $user->address,
                'country_id' => $user->country_id,
                'country_name' => ($user->country_id != null) ? Country::find($user->country_id) : '',
                'city' => $user->city,
                'state' => $user->state,
                'zipcode' => $user->zip_code,
                'profile_image' => AllFunctionService::user_profile($user->id),
                'email_auth' => $user->email_auth,
                'email_verification' => $user->email_verification,
            ]);
        }
        return ([
            'status' => false,
            'message' => 'User not found on the server'
        ]);
    }
}
