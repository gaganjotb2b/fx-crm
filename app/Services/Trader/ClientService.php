<?php

namespace App\Services\Trader;

use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use PHPUnit\Framework\Constraint\Count;

class ClientService
{
    public static function count_active_client($data = [])
    {
        // $users = 
    }
    // get client country id
    public static function user_country_id($user_id)
    {
        try {
            $result = UserDescription::where('user_id', $user_id)->first();
            return $result->country_id;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // last account created_at
    public static function last_account_created_at($user_id)
    {
        try {
            $result = TradingAccount::where('user_id', $user_id)->latest()->first();
            return $result->created_at;
        } catch (\Throwable $th) {
            // throw $th;
            return '1970-01-01';
        }
    }
    // profile created at
    public static function profile_created_at($user_id)
    {
        try {
            $result = User::where('id', $user_id)->select('created_at')->first();
            return $result->created_at;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
}
