<?php

declare(strict_types=1);

namespace App\Services\common;

use App\Models\IB;
use App\Models\UserDescription;

final class UserService
{
    public static function get_country($user_id = null)
    {
        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            $user_description = UserDescription::where('user_id', $user_id)
                ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')->first();
            if ($user_description) {
                return ($user_description->name);
            }
            return ('N/A');
        } catch (\Throwable $th) {
            //throw $th;
            return ('N/A');
        }
    }
    // get user avatar
    public static function profile_avater($user_id = null)
    {
        try {
            $user_id  = ($user_id == null) ? auth()->user()->id : $user_id;
            $user_description = UserDescription::where('user_id', $user_id)->select('profile_avater', 'gender')->first();
            if ($user_description) {
                if ($user_description->profile_avater != null) {
                    return (asset('Uploads/profile/') . $user_description->profile_avater);
                } elseif ($user_description->gender != null) {
                    return (asset('admin-assets/app-assets/images/avatars/' . ((strtolower($user_description->gender) === 'male') ? 'avater-men.png' : 'avater-lady.png')));
                } else {
                    return (asset('admin-assets/app-assets/images/avatars/avater-men.png'));
                }
            }
            return (asset('admin-assets/app-assets/images/avatars/avater-men.png'));
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
    public static function get_ib_email($trader_id)
    {
        try {
            $ib = IB::where('reference_id', $trader_id)->select('email')->join('users', 'ib.ib_id', '=', 'users.id')->first();
            return $ib->email;
        } catch (\Throwable $th) {
            // throw $th;
        }
    }
}
