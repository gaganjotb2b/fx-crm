<?php

namespace App\Services\accounts;

use App\Models\ClientGroup;
use App\Models\TradingAccount;
use App\Services\MT4API;
use App\Services\Mt5WebApi;

class AccountService
{
    // check balance equity
    public static function check_balance_equity($account_number)
    {
        switch (strtolower(get_platform())) {
            case 'mt4':

                break;

            default:
                $mt5_api = new Mt5WebApi();
                $result = $mt5_api->execute('AccountGetMargin', [
                    'Login' => $account_number,
                ]);
                $mt5_api->Disconnect();

                if (isset($result['success'])) {
                    if ($result['success']) {
                        return ([
                            'balance' => $result['data']['Balance'],
                            'equity' => $result['data']['Equity']
                        ]);
                    }
                    return ([
                        'balance' => 0,
                        'equity' => 0,
                    ]);
                }
                return ([
                    'balance' => 0,
                    'equity' => 0,
                ]);
                break;
        }
    }
    // change account password / investor password / master password
    public static function change_password($data)
    {
        $mt5_api = new Mt5WebApi();
        $account = TradingAccount::where('account_number', $data['account_number'])->first();
        switch (strtolower($data['type'])) {
            case 'password':
                // change master password
                switch (strtolower($account->platform)) {
                    case 'mt4':
                        // change passwor dor mt4
                        break;

                    default:
                        // change password for mt5
                        $result = $mt5_api->execute('AccountChangePassword', [
                            "Login" => (int)$data['account_number'],
                            "Password" => $data['password'],
                        ]);
                        if (isset($result['success']) && $result['success'] == true) {
                            // update trading account table
                            $update = TradingAccount::where('account_number', $data['account_number'])->update([
                                'master_password' => $data['password'],
                            ]);
                            return ([
                                'status' => true,
                                'message' => 'Master password successfully updated'
                            ]);
                        }
                        return ([
                            'status' => false,
                            'message' => 'Connection error, Please try again later'
                        ]);
                        break;
                }
                break;
            case 'investor-password':
                // change investor password
                switch (strtolower($account->platform)) {
                    case 'mt4':
                        // change investor passwor dor mt4
                        break;

                    default:
                        // change investor password for mt5
                        $result = $mt5_api->execute('AccountChangeInvestorPassword', [
                            "Login" => (int)$data['account_number'],
                            "Password" => $data['password'],
                        ]);
                        if (isset($result['success']) && $result['success'] == true) {
                            // update trading account table
                            $update = TradingAccount::where('account_number', $data['account_number'])->update([
                                'investor_password' => $data['password'],
                            ]);
                            return ([
                                'status' => true,
                                'message' => 'Investor password successfully updated'
                            ]);
                        }
                        return ([
                            'status' => false,
                            'message' => 'Connection error, Please try again later'
                        ]);
                        break;
                }
                break;

            default:
                return ([
                    'status' => false,
                    'message' => 'Invalid request found'
                ]);
                break;
        }
    }
    // get all leverage for specific account group
    public static function get_leverage($data)
    {
        $account = TradingAccount::where('account_number', $data['account_number'])->select('group_id', 'leverage')->first();
        if ($account) {
            $client_group = ClientGroup::where('id', $account->group_id)->first();
            $leverage = $client_group->leverage;
            return ([
                'status' => true,
                'leverage' => json_decode($leverage),
                'current_leverage' => $account->leverage,
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Invalid request'
        ]);
    }
    // change leverage
    public static function change_leverage($data)
    {
        $account = TradingAccount::where('account_number', $data['account_number'])->select('id', 'account_number')->first();
        if (!$account) {
            return ([
                'status' => false,
                'message' => 'Account not found'
            ]);
        }
        switch (strtolower($data['platform'])) {
            case 'mt4':
                // mt4 pltform 
                $mt4api = new MT4API();
                $result = $mt4api->execute([
                    'command' => 'user_update',
                    'data' => array(
                        'account_id' => $account->account_number,
                        'leverage' => $data['leverage']
                    ),
                ]);

                if (isset($result['success']) && ($result['[success'] == true)) {
                    $update  = TradingAccount::where('account_number', $data['account_number'])->update([
                        'leverage' => $data['leverage']
                    ]);
                    if ($update) {
                        return ([
                            'status' => true,
                            'message' => 'Leverage successfully changed'
                        ]);
                    }
                    return ([
                        'status' => false,
                        'message' => 'Network error, Please try again later'
                    ]);
                }
                return ([
                    'status' => false,
                    'message' => 'Connection Error, Leverage not changed'
                ]);
                break;

            default:
                // mt5 leverage--------------- 
                $mt5_api = new Mt5WebApi();
                $result = $mt5_api->execute('AccountUpdate', [
                    "Login" => (int)$account->account_number,
                    "Leverage" => (int)$data['leverage'],
                ]);
                if (isset($result['success']) && ($result['success'] == true)) {
                    // update trading account table
                    $update = TradingAccount::where('account_number', $data['account_number'])->update([
                        'leverage' => $data['leverage']
                    ]);
                    if ($update) {
                        return ([
                            'status' => true,
                            'message' => 'Leverage successfully chagneed'
                        ]);
                    }
                    return ([
                        'status' => false,
                        'message' => 'Network error, Please trya again later'
                    ]);
                }
                return ([
                    'status' => false,
                    'message' => 'API error , Please try again later!'
                ]);
        }
    }
    public static function get_mt4_balance($account_number, $type = 'live')
    {
        $mt4api = new MT4API();
        $result = $mt4api->execute([
            'command' => 'user_data_get',
            'data' => array('account_id' => $account_number),
        ], $type);

        if ($result["success"]) {
            $data = $result['data'];
            return ([
                'status' => true,
                'balance' => $data['balance'],
                'equity' => $data['equity'],
            ]);
        }
        return ([
            'status' => false,
            'balance' => 0,
            'equity' => 0,
        ]);
    }
}
