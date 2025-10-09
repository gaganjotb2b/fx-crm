<?php

namespace App\Services\Trader;

use App\Models\IB;
use App\Models\User;

class TraderAffiliatService
{
    public static function non_affiliat_trader_id()
    {
        $affiliated_id = self::affiliated_trader_id();

        $users = User::whereNotIn('id', $affiliated_id)->select('id')->get();
        $non_affiliat = [];
        foreach ($users as $key => $value) {
            array_push($non_affiliat, $value->id);
        }
        return $non_affiliat;
    }
    // get affiliated trader id
    // those whose came from IB
    public static function affiliated_trader_id()
    {
        $get_reference = IB::select('reference_id')->get();
        $affiliated_id = [];
        foreach ($get_reference as $key => $value) {
            array_push($affiliated_id, $value->reference_id);
        }
        return $affiliated_id;
    }
}
