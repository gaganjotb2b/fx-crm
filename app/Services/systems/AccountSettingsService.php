<?php

namespace App\Services\systems;

use App\Models\MtSerial;
use App\Models\TradingAccount;
use App\Models\User;

class AccountSettingsService
{
    public static function mt_serial()
    {
        $mtSerial = MtSerial::select()->first();
        if ($mtSerial) {
            switch ($mtSerial->login_gen) {
                case 'custom':
                    // get last login from trading_account table
                    if ($mtSerial->login_start == $mtSerial->last) {
                        return ($mtSerial->last);
                    }
                    return ($mtSerial->last + 1);
                    break;

                default:
                    // auto generate
                    $Login = 0;
                    break;
            }
        }
    }
    public static function user_account_limit($data = [])
    {
        $user_id = (array_key_exists('user_id', $data)) ? $data['user_id'] : auth()->user()->id;
        $user = User::where('users.id', $user_id)->select('trading_ac_limit')->first();
        if ($user->trading_ac_limit == 0) {
            return true;
        }
        // check account limit
        $count = TradingAccount::where('user_id', $user_id)->count();
        if ($count > $user->trading_ac_limit) {
            return false;
        }
        return true;
    }
}
