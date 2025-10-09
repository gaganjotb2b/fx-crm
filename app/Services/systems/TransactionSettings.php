<?php

namespace App\Services\systems;

use App\Models\SoftwareSetting;

class TransactionSettings
{
    public  static function is_wallet_deposit()
    {
        try {
            $wallet_deposit = SoftwareSetting::where('direct_deposit', 'wallet')->exists();
            if ($wallet_deposit) {
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
    public  static function is_account_deposit()
    {
        try {
            $account_deposit = SoftwareSetting::where('direct_deposit', 'account')->exists();
            if ($account_deposit) {
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }

    // Withdraw Transaction Setting
    public  static function is_wallet_withdraw()
    {
        try {
            $wallet_withdraw = SoftwareSetting::where('direct_withdraw', 'wallet')->exists();
            if ($wallet_withdraw) {
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
    public  static function is_account_withdraw()
    {
        try {
            $account_withdraw = SoftwareSetting::where('direct_withdraw', 'account')->exists();
            if ($account_withdraw) {
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            throw $th;
            return false;
        }
    }
}
