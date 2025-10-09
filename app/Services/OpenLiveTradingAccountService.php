<?php

namespace App\Services;

use App\Models\ClientGroup;
use App\Models\Country;
use App\Models\Log;
use App\Models\MtSerial;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\bonus\BonusCreditService;
use App\Services\systems\AccountSettingsService;
use Illuminate\Auth\Events\Login;

class OpenLiveTradingAccountService
{
    
    public static function generatePassword($length = 8) {
        $upper    = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $lower    = 'abcdefghijkmnopqrstuvwxyz';
        $digits   = '23456789';
        $symbols  = '!@#$%^&*_';
    
        // Combine all for random fill
        $all = $upper . $lower . $digits . $symbols;
    
        // Ensure at least one of each type
        $password = '';
        $password .= $upper[rand(0, strlen($upper) - 1)];
        $password .= $lower[rand(0, strlen($lower) - 1)];
        $password .= $digits[rand(0, strlen($digits) - 1)];
        $password .= $symbols[rand(0, strlen($symbols) - 1)];
    
        // Fill the rest
        for ($i = 4; $i < $length; $i++) {
            $password .= $all[rand(0, strlen($all) - 1)];
        }
    
        // Shuffle the password to mix guaranteed characters
        return str_shuffle($password);
    }
    public static function open_live_account($request_data, $registration = false)
    {
        if (array_key_exists('user_id', $request_data)) {
            $user = User::where('users.id', $request_data['user_id'])->select(
                'users.*',
                'user_descriptions.user_id',
                'user_descriptions.country_id',
                'user_descriptions.state',
                'user_descriptions.city',
                'user_descriptions.address',
                'user_descriptions.zip_code',
                'user_descriptions.gender',
                'user_descriptions.date_of_birth'
            )
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')->first();
        } else {
            $user = User::find(auth()->user()->id);
        }
        // check mt seriral settings
        $Login = AccountSettingsService::mt_serial();
        // check platform
        if (array_key_exists('platform', $request_data)) {
            $mt4api = new MT4API();
            $country = Country::find($user->country_id);
            $country = ($country) ? $country->name : '';
            $client_group = ClientGroup::find($request_data['account_type']);
            $comments = 'create account from user' . request()->ip();
            // create password
            // $mpass       = (strtolower($request_data['platform']) === 'vertex') ? VertexFnService::vertex_password() : ("Mp!") . date('His') . rand(10, 99);
            // $ipass       = ("Ip!") . date('His') . rand(10, 99);
            // $ppass       = ("Pp!") . date('His') . rand(10, 99);
            $svc = new self;            // create an instance
            $mpass = $svc->generatePassword();
            $ipass = $svc->generatePassword();
            $ppass = $svc->generatePassword();
            
            $username = 'fx' . mt_rand(100000, 999999);
            $vertex_client = ''; // only for vertex platform
            // create account api---------
            switch (strtolower($request_data['platform'])) {
                    // create mt5 account------------
                case 'mt5':
                    $action = 'AccountCreate';
                    $data = array(
                        "Email" => ($user->email) ? $user->email : "na",
                        "Login" => $Login,
                        "Group" => str_replace('\\\\', '\\', $client_group->group_name),
                        "Leverage" => (int) $request_data['leverage'],
                        "Comment" => ($comments) ? $comments : "na",
                        "Phone" => ($user->phone) ? $user->phone : "na",
                        "Name" => ($user->name) ? $user->name : "na",
                        "Country" => ($country) ? $country : "na",
                        "City" => ($user->city) ? $user->city : "na",
                        "State" => ($user->state) ? $user->state : "na",
                        "ZipCode" => ($user->zipcode) ? $user->zipcode : "na",
                        "Address" => ($user->address) ? $user->address : "na",
                        'Password' => $mpass,
                        'InvestPassword' => $ipass
                        // 'Password' => $mpass,
                        // 'InvestPassword' => $ipass
                    );
                    $mt5_api = new Mt5WebApi();
                    $result = $mt5_api->execute($action, $data);
                    break;
                case 'vertex':
                    $vertex = new VertexApiCall();
                    $login_result = $vertex->execute('BackofficeLogin');
                    $fullName = ($user->name) ? $user->name : "na";
                    $fullName = explode(' ', $fullName);
                    $firstName = (array_key_exists('0', $fullName)) ? $fullName[0] : $fullName;
                    $lastName = (array_key_exists('1', $fullName)) ? $fullName[1] : '';
                    $data = [
                        'ParentID' => (int)$client_group->group_name,
                        'FirstName' => $firstName,
                        'LastName' => $lastName,
                        'Username' => $username,
                        'Password' => $mpass,
                        'Mobile' => ($user->phone) ? $user->phone : "na",
                        'Address' => ($user->address) ? $user->address : "na",
                        'POB' => '',
                        'Country' => ($country) ? $country : "na",
                        'Email' => ($user->email) ? $user->email : "na",
                    ];
                    $response_client = $vertex->execute('CreateClient', $data);

                    if ($response_client['success']) {
                        $vertex_client = $response_client['data'];
                        $data = [
                            'ParentID' => $response_client['data'],
                            'AccountType' => 1,
                            'IsDemo' => false,
                            'IsLocked' => false,
                            'DontLiquidate' => true,
                            'IsMargin' => true,
                            'UserDefinedDate' => '28/12/2022 00:00:00',
                        ];
                        // return $data;

                        $result = $vertex->execute('CreateAccount', $data);
                    }

                    break;
                default:
                    // mt4 account create
                    $mt4api = new MT4API();
                    $user_data = array(
                        'name' => ($user->name) ? $user->name : "na",
                        'account_id' => $Login,
                        'address' => ($user->address) ? $user->address : "na",
                        'country' => ($country) ? $country : "na",
                        'city' => ($user->city) ? $user->city : "na",
                        'email' => ($user->email) ? $user->email : "na",
                        'comment' => ($comments) ? $comments : "na",
                        'group' => str_replace('\\\\', '\\', $client_group->group_name),
                        'state' => ($user->state) ? $user->state : "na",
                        'leverage' => (int) $request_data['leverage'],
                        'zipcode' => ($user->zipcode) ? $user->zipcode : "na",
                        'mqid' => 1,
                        'password_phone' => $ppass,
                        'id_number' => 'na',
                        'status' => 'RE',
                        'taxes' => 10.0,
                        'agent_account' => 1,
                        'phone' => ($user->phone) ? $user->phone : "na",
                        'password' => $mpass,
                        'password_investor' => $ipass,
                        'enable_change_password' => true,
                        'enable' => true,
                        'send_reports' => true,
                        'enable_read_only' => false,
                    );
                    $data = array(
                        'command' => 'user_create',
                        'data' => $user_data,
                    );
                    $result = $mt4api->execute($data, 'live');
                    break;
            }
            // return $result;
            // create account in local-----------
            if (isset($result['success'])) {
                if ($result['success']) {
                    if (strtolower($request_data['platform']) === 'vertex') {
                        $login = $result['data'];
                    } elseif (strtolower($request_data['platform']) === 'mt4') {
                        $login = $result['data']['login'];
                    } else {
                        $login = $result['data']['Login'];
                    }
                    if ($registration == false) {
                        $trading_account = TradingAccount::create([
                            'user_id' => $user->id,
                            'account_number' => $login,
                            'comment' => $comments,
                            'client_type' => 'live',
                            'leverage' => $request_data['leverage'],
                            'master_password' => $mpass,
                            'investor_password' => $ipass,
                            'phone_password' => $ppass,
                            'platform' => strtoupper($request_data['platform']),
                            'group_id' => $request_data['account_type'],
                            'user_name' => $username,
                            'client_id' => $vertex_client

                        ]);
                        // create bonus
                        BonusCreditService::account_bonus_credit(auth()->user()->id, $trading_account->account_number);
                    } else {
                        $trading_account = TradingAccount::where('user_id', $user->id)->update([
                            'account_number' => $login,
                            'phone_password' => $ppass,
                            'master_password' => $mpass,
                            'investor_password' => $ipass,
                            'user_name' => $username,
                            'client_id' => $vertex_client
                        ]);
                        $user->email_verified_at = date('Y-m-d h:i:s', strtotime(now()));
                        $user->save();
                    }

                    if ($user->trading_ac_limit != 0) {
                        $user->trading_ac_limit = ($user->trading_ac_limit - 1);
                        $user->save();
                    }
                    // Mail it------
                    // <----------------Mail script here-------------->
                    $password = Log::select()->where('user_id', $user->id)->first();
                    $clientPassword = decrypt(isset($password->password) ? $password->password : 'dsfsd');
                    $clientTransactionPassword = decrypt($password->transaction_password);
                    EmailService::send_email('open-trading-account', [
                        'user_id' => $user->id,
                        'clientUsername'            => ($user) ? $user->email : '',
                        'clientPassword'            => $clientPassword,
                        'clientTransactionPassword' => $clientTransactionPassword,
                        'clientMt4AccountNumber'    => $login,
                        'clientMt4AccountPassword'  => $mpass,
                        'clientMt4InvestorPassword' => $ipass,
                        'server'                    => strtoupper($request_data['platform']),
                    ]);
                    request()->session()->forget('account-otp');
                    request()->session()->forget('otp_set_time');

                    $status_data['status'] = true;
                    $status_data['message'] = 'Live Account created successfully!';
                    $status_data['account_no'] = $login;
                    $status_data['inv_password'] = $ipass;
                    $status_data['master_password'] = $mpass;
                    $status_data['phone_password'] = $ppass;
                    return ($status_data);
                }
            }
            $status_data['status'] = false;
            $status_data['message'] = 'Live Account Creation Failed!';
            return ($status_data);
        } else {
            return ([
                'status' => false,
                'message' => 'platform not found',
            ]);
        }
    }
}
