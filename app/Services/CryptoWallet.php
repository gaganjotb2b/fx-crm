<?php
namespace App\Services;

class CryptoWallet{

    static function actual_amount_trader($user_id, $fee){
        $all_function = new AllFunctionService();
        $actual_amount = $all_function->get_self_balance($user_id);
        if($actual_amount == 0){
            return 0;
        }else if($actual_amount < $fee){
            return 0;
        }
        return $actual_amount - $fee;
    }

}