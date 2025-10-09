<?php

namespace App\Services\common;

class PasswordGenService
{
    public static function platform_password($type = 'master_password')
    {
        $random_number = mt_rand(1, 99999999);
        switch ($type) {
            case 'phone_password':
                return ('P' . str_pad("$random_number", 8, '0', STR_PAD_LEFT));
                break;
            case 'investor_password':
                return ('IN' . str_pad("$random_number", 8, '0', STR_PAD_LEFT));
                break;
            default:
                return ('IN' . str_pad("$random_number", 8, '0', STR_PAD_LEFT));
                break;
        }
    }
}
