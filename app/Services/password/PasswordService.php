<?php

namespace App\Services\password;

class PasswordService
{
    public static function reset_password()
    {
        return chr(rand(97, 122)) . mt_rand(10000, 99999);
    }
    // reset transaction password
    public static function reset_transaction_pin()
    {
        return mt_rand(1000, 9999);
    }
}
