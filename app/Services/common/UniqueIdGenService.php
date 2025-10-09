<?php

namespace App\Services\common;

class UniqueIdGenService
{
    public static function payment_ref_no($user_id)
    {
        return (time() . str_pad($user_id, 8, "0", STR_PAD_LEFT));
    }
}
