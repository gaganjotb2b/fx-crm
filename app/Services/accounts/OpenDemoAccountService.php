<?php

namespace App\Services\accounts;

use App\Models\ClientGroup;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\Log;
use App\Services\common\PasswordGenService;
use App\Services\common\UserService;
use App\Services\MT4API;
use App\Services\EmailService;
use App\Services\Mt5WebApi;
use App\Services\systems\AccountSettingsService;
use App\Services\systems\MtSerialService;

class OpenDemoAccountService
{
    public static function open_demo_account($data)
    {
        try {
            $user_id = $data['user_id'] ?? auth()->id();
    
            $client = User::where('users.id', $user_id)
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    'user_descriptions.address',
                    'user_descriptions.city',
                    'user_descriptions.state',
                    'user_descriptions.zip_code'
                )
                ->first();
    
            if (!$client) {
                return ['status' => false, 'message' => 'User not found'];
            }
    
            $phone_password     = "Pp#" . date('His') . rand(10, 99);
            $investor_password  = "Ip#" . date('His') . rand(10, 99);
            $master_password    = "Mp#" . date('His') . rand(10, 99);
    
            $comment       = 'Create account by User Registration';
            $custom_login  = AccountSettingsService::mt_serial();
            $country       = UserService::get_country($user_id) ?? 'na';
    
            if (!isset($data['group_id'])) {
                return ['status' => false, 'message' => 'Invalid group found'];
            }
    
            $client_group = ClientGroup::find($data['group_id']);
            if (!$client_group) {
                return ['status' => false, 'message' => 'Client group not found'];
            }
    
            // Leverage
            $leverage = $data['leverage'] ??
                ($data['is_register'] ?? false
                    ? optional(TradingAccount::where('user_id', $user_id)->first())->leverage
                    : 100);
    
            // Select platform
            $platform = strtolower(get_platform());
    
    
            // MT5 ACCOUNT CREATION
            $mt5_api = new Mt5WebApi(null, 'demo');
            $action = 'AccountCreate';
    
            $meta_data = [
                "Email"          => $client->email ?? 'na',
                "Login"          => $custom_login,
                "Group"          => str_replace('\\\\', '\\', $client_group->group_name),
                "Leverage"       => (int)$leverage,
                "Comment"        => $comment,
                "Phone"          => $client->phone ?? 'na',
                "Name"           => $client->name ?? 'na',
                "Country"        => $country,
                "City"           => $client->city ?? 'na',
                "State"          => $client->state ?? 'na',
                "ZipCode"        => $client->zip_code ?? 'na',
                "Address"        => $client->address ?? 'na',
                "Password"       => $master_password,
                "InvestPassword" => $investor_password,
            ];
    
    
            $result = $mt5_api->execute($action, $meta_data);
    
            if (!isset($result['success']) || !$result['success']) {
                return ['status' => false, 'message' => 'API Connection failed!'];
            }
    
            // Deposit
            $mt5_api->execute('BalanceUpdate', [
                "Login"   => $result['data']['Login'],
                "Balance" => $data['balance'] ?? 10000,
                "Comment" => "Demo funds"
            ]);
    
            $update = ($data['is_register'] ?? false)
                ? self::update_account_table([
                    'user_id'          => $user_id,
                    'account_number'   => $result['data']['Login'],
                    'phone_password'   => $phone_password,
                    'master_password'  => $master_password,
                    'investor_password'=> $investor_password,
                ])
                : self::create_account([
                    'user_id'          => $user_id,
                    'account_number'   => $result['data']['Login'],
                    'phone_password'   => $phone_password,
                    'master_password'  => $master_password,
                    'investor_password'=> $investor_password,
                    'platform'         => 'MT5',
                    'leverage'         => $leverage,
                    'comment'          => $comment,
                    'group_id'         => $data['group_id'],
                ]);
    
            if ($update) {
                $passwordLog = Log::where('user_id', $client->id)->first();
                $clientPassword = decrypt($passwordLog->password ?? '');
                $clientTransactionPassword = decrypt($passwordLog->transaction_password ?? '');
    
                // return [
                //     'user_id'                   => $client->id,
                //     'clientUsername'            => $client->email,
                //     'clientPassword'            => $clientPassword,
                //     'clientTransactionPassword' => $clientTransactionPassword,
                //     'clientMt4AccountNumber'    => $result['data']['Login'],
                //     'clientMt4AccountPassword'  => $master_password,
                //     'clientMt4InvestorPassword' => $investor_password,
                //     'server'                    => 'MT5',
                // ];
                
                // Try to send email, but don't fail if email fails
                try {
                    EmailService::send_email('open-trading-account', [
                        'user_id'                   => $client->id,
                        'clientUsername'            => $client->email,
                        'clientPassword'            => $clientPassword,
                        'clientTransactionPassword' => $clientTransactionPassword,
                        'clientMt4AccountNumber'    => $result['data']['Login'],
                        'clientMt4AccountPassword'  => $master_password,
                        'clientMt4InvestorPassword' => $investor_password,
                        'server'                    => 'MT5',
                    ]);
                } catch (\Throwable $emailError) {
                    \Log::warning('OpenDemoAccountService: Email sending failed, but account creation succeeded', [
                        'email_error' => $emailError->getMessage(),
                        'account_number' => $result['data']['Login']
                    ]);
                }
            }
    
            return [
                'status'          => true,
                'message'         => $update ? 'Demo MT5 account successfully created' : 'Demo MT5 account created, CRM update failed',
                'account_no'      => $result['data']['Login'],
                'master_password' => $master_password,
                'phone_password'  => $phone_password,
                'inv_password'    => $investor_password,
            ];
        } catch (\Throwable $th) {
            \Log::error('OpenDemoAccountService: Exception occurred', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString()
            ]);
            
            Throw $th;
            // Log::error('Error in open_demo_account: ' . $th->getMessage(), [
            //     'trace' => $th->getTraceAsString()
            // ]);
    
            return [
                'status'  => false,
                'message' => 'Got a server error',
                'error'   => $th->getMessage()
            ];
        }
    }

    // update trading account table
    private static function update_account_table($data = [])
    {
        $update = TradingAccount::where('user_id', $data['user_id'])
            ->update([
                'account_number' => $data['account_number'],
                'phone_password' => $data['phone_password'],
                'master_password' => $data['master_password'],
                'investor_password' => $data['investor_password'],
            ]);
        if ($update) {
            return (true);
        }
        return (false);
    }
    // create trading account
    private static function create_account($data)
    {
        $create = TradingAccount::create([
            'user_id' => $data['user_id'],
            'account_number' => $data['account_number'],
            'phone_password' => $data['phone_password'],
            'master_password' => $data['master_password'],
            'investor_password' => $data['investor_password'],
            'platform' => $data['platform'],
            'leverage' => $data['leverage'],
            'client_type' => 'demo',
            'comment' => $data['comment'],
            'group_id' => $data['group_id'],
        ]);
        if ($create) {
            return (true);
        }
        return (false);
    }
}
