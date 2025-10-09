<?php

namespace App\Services\accounts;

use App\Services\Mt5WebApi;
use App\Models\TradingAccount;

class ResetAccountPasswordService
{
    public function resetPasswords()
    {
        $trading_accounts = TradingAccount::where('investor_password', null)->where('master_password', null)->select('account_number')->limit(50)->get();
        // $trading_accounts = TradingAccount::where('investor_password', null)->where('master_password', null)->select('account_number')->count();
        // return $trading_accounts;
        foreach ($trading_accounts as $row) {
            // $trading_accounts

            $mt5_api = new Mt5WebApi();
            // change password
            $master_pass = 'M' . mt_rand(1000000, 9999999);

            $action = 'AccountChangePassword';
            $data = array(
                "Login" => (int)$row->account_number,
                "Password" => $master_pass,
            );
            // $result = $mt5_api->execute($action, $data);

            // change investor pasword
            $investor_pass = 'I' . mt_rand(1000000, 9999999);

            $action = 'AccountChangeInvestorPassword';
            $data = array(
                "Login" => (int)$row->account_number,
                "Password" => $investor_pass,
            );
            // $result = $mt5_api->execute($action, $data);

            // password update
            TradingAccount::where('account_number', $row->account_number)->update([
                'master_password' => $master_pass,
                'investor_password' => $investor_pass
            ]);
        }
    }
}
