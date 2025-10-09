<?php

namespace App\Services\deposit;

use App\Models\AdminBank;
use App\Models\BankAccount;

class BankService
{
    // get client bank
    public static function get_client_bank($data)
    {
        $banks = BankAccount::where('status', '1')->where('user_id', $data['user_id'])->get();
        if ($banks) {
            return ([
                'status' => true,
                'banks' => $banks,
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Bank account not found'
        ]);
    }
    // get admin bank
    public static function get_admin_banks($data)
    {
        $banks = AdminBank::where('status', '1')->get();
        if ($banks) {
            return ([
                'status' => true,
                'banks' => $banks,
            ]);
        }
        return ([
            'status' => false,
            'message' => 'No bank account available!'
        ]);
    }
}
