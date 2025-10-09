<?php

namespace App\Http\Controllers\systems;

ini_set('max_execution_time', 1800);

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\Country;
use App\Models\IB;
use App\Models\Log;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserMigrationController extends Controller
{
    public function index()
    {

        return view('systems.user-migration');
    }

    private $rows = [];
    public function store(Request $request)
    {
        $validation_rules = [
            'csv_file' => 'required|mimes:csv',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Fix the following Errors'
            ]);
        }
        $path = $request->file('csv_file')->getRealPath();

        $records = array_map('str_getcsv', file($path));
        $created_user = [];
        if (!count($records) > 0) {
            return Response::json([
                'status' => false,
                'message' => 'This file is broken'
            ]);
        }
        // Get field names from header column
        $fields = array_map('strtolower', $records[0]);
        $fields = str_replace(' ', '_', $fields);
        // // chacking header 
        // return $fields;
        // Remove the header column
        // return $records;
        array_shift($records);

        for ($i = 0; $i < count($records); $i++) {
            for ($j = 0; $j < count($records[$i]); $j++) {
                // check mail exist in file
                if ($records[$i][array_search('name', $fields)] != "") {
                    $check = User::where('name', 'like', '%' . $records[$i][array_search('name', $fields)] . '%');
                    // check if existing email is ib/trader
                    if ($request->type === 'trader') {
                        $check = $check->where('type', CombinedService::type());
                    } else {
                        $check = $check->where('type', CombinedService::type());
                        // check for combined ib
                        // if (CombinedService::is_combined()) {
                        //     $check = $check->where('combine_access', 1);
                        // }
                    }

                    // get client group
                    if ($request->type === 'trader') {

                        $group_exist = ClientGroup::where('group_name', $records[$i][array_search('group', $fields)])->exists();
                        if (!$group_exist && array_search('group', $fields)) {
                            $new_leverage = [1, 25, 50, 100, 200, 500, 1000];
                            $new_leverage = json_encode($new_leverage);
                            ClientGroup::create([
                                'group_name' => $records[$i][array_search('group', $fields)],
                                'group_id' => $records[$i][array_search('group', $fields)],
                                'server' => get_platform(),
                                'account_category' => $records[$i][array_search('account_type', $fields)],
                                'leverage' => $new_leverage,
                                'max_leverage' => 1000,
                                'book' => 'B Book',
                                'min_deposit' => 500,
                                'deposit_type' => 'one time',
                                'visibility' => 'hidden'
                            ]);
                        }
                        $client_group = ClientGroup::where('group_name', $records[$i][array_search('group', $fields)])->first();
                        // check leverage exisis or not
                        $leverage = $records[$i][array_search('leverage', $fields)];
                        $leverage = explode(':', $leverage);
                        $leverage = (array_key_exists(1, $leverage)) ? $leverage[1] : null;
                    }
                    // take created ib
                    else {

                        array_push($created_user, [
                            'parent_id' => $records[$i][array_search('ib', $fields)],
                            'parent_email' => trim($records[$i][array_search('email', $fields)])
                        ]);
                    }
                    // check user exist in db
                    if (!$check->exists()) {
                        // email has or not
                        if ($records[$i][array_search('email', $fields)] != "") {
                            $password = 'M' . mt_rand(10000, 99999);
                            $transaction_pin = mt_rand(1000, 9999);
                            $user_create = User::create([
                                'name' => $records[$i][array_search('name', $fields)],
                                'email' => trim($records[$i][array_search('email', $fields)]),
                                'phone' => $records[$i][array_search('phone', $fields)],
                                'live_status' => ($request->type === 'trader') ? ((array_search('account_type', $fields)) ? $records[$i][array_search('account_type', $fields)] : 'live') : 'live', //live or demo
                                'password' => Hash::make($password),
                                'transaction_password' => Hash::make($transaction_pin),
                                'email_verified_at' => date('Y-m-d h:i:s', strtotime(now())),
                                'type' => ($request->type === 'trader') ? CombinedService::type() : CombinedService::type(),
                                'combine_access' => ($request->type === 'trader') ? 0 : 1,
                                'ib_group_id' => ($request->type === 'trader') ? null : 1,
                                'trading_ac_limit' => 10
                            ]);
                            // create password log
                            Log::create([
                                'user_id' => $user_create->id,
                                'password' => encrypt($password),
                                'transaction_password' => encrypt($transaction_pin)
                            ]);
                            // crete user descryption
                            $countries = Country::where('name', strtolower($records[$i][array_search('country', $fields)]))->first();
                            $country_id = ($countries) ? $countries->id : 1;
                            $userDes_create = UserDescription::create([
                                'user_id' => $user_create->id,
                                'country_id' => $country_id,
                                'state' => $records[$i][array_search('state', $fields)],
                                'city' => $records[$i][array_search('city', $fields)],
                                'address' => $records[$i][array_search('address', $fields)],
                                'zip_code' => $records[$i][array_search('zipcode', $fields)],
                            ]);
                            // create trading accounts
                            if ($request->type === 'trader') {
                                if ($client_group) {
                                    $trd_ac_create = TradingAccount::create([
                                        'user_id' => $user_create->id,
                                        'platform' => strtoupper(get_platform()),
                                        'account_number' => $records[$i][array_search('login', $fields)],
                                        'group_id' => ($client_group) ? $client_group->id : 1, //need to change by csv
                                        'leverage' => $records[$i][array_search('leverage', $fields)],
                                        'client_type' => ($client_group) ? $client_group->account_category : 'live', //live or demo
                                        'phone_password' => null,
                                        'master_password' => null,
                                        'investor_password' => null,
                                    ]);
                                }
                                if (array_search('ib_email', $fields)) {
                                    $get_ib =  User::where('email', trim($records[$i][array_search('ib_email', $fields)]))->where('type', CombinedService::type());
                                    if (CombinedService::is_combined()) {
                                        $get_ib = $get_ib->where('combine_access', 1);
                                    }
                                    $get_ib = $get_ib->first();
                                    if ($get_ib) {
                                        if ($get_ib->id != $user_create->id) {
                                            IB::create([
                                                'ib_id' => $get_ib->id,
                                                'reference_id' => $user_create->id
                                            ]);
                                        }
                                    }
                                }
                            }
                            // create ib reference
                            else {
                                $parent_email = '';
                                // if have a parent 
                                if (trim($records[$i][array_search('parent_ib', $fields)]) != "" && array_search('parent_ib', $fields) != false) {
                                    for ($k = 0; $k < count($created_user); $k++) {
                                        if ($created_user[$k]['parent_id'] == trim($records[$i][array_search('parent_ib', $fields)])) {
                                            $get_parent_id = User::where('email', trim($created_user[$k]['parent_email']))->where('type', CombinedService::type());
                                            if (CombinedService::is_combined()) {
                                                $get_parent_id = $get_parent_id->where('combine_access', 1);
                                            }
                                            $get_parent_id = $get_parent_id->first();
                                            // check existing reference
                                            $check_reference = IB::where('reference_id', $user_create->id)->exists();
                                            if (!$check_reference) {
                                                if ($get_parent_id->id != $user_create->id) {
                                                    IB::create([
                                                        'ib_id' => $get_parent_id->id,
                                                        'reference_id' => $user_create->id
                                                    ]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // if use exist in db
                    else {
                        $existing_user = User::where('name', 'like', '%' . $records[$i][array_search('name', $fields)] . '%');
                        // check if user exist in db
                        if ($request->type === 'trader') {
                            $existing_user = $existing_user->where('type', 0);
                        }

                        $existing_user = $existing_user->select()->first();
                        if ($request->type === 'trader') {
                            $account_exist = TradingAccount::where('account_number', $records[$i][array_search('login', $fields)])->exists();
                            if ((!$account_exist) && $client_group) {
                                $trd_ac_create = TradingAccount::create([
                                    'user_id' => $existing_user->id,
                                    'account_number' => $records[$i][array_search('login', $fields)],
                                    'platform' => strtoupper(get_platform()),
                                    'group_id' => ($client_group) ? $client_group->id : 1,
                                    'leverage' => $records[$i][array_search('leverage', $fields)],
                                    'client_type' => ($client_group) ? $client_group->account_category : 'live', //live or demo
                                    'phone_password' => $records[$i][array_search('password', $fields)],
                                    'master_password' => $records[$i][array_search('password', $fields)],
                                    'investor_password' => $records[$i][array_search('investor_password', $fields)],
                                ]);
                            }
                        } else {
                            $parent_email = '';
                            // if have a parent 
                            if (trim($records[$i][array_search('parent_ib', $fields)]) != "") {
                                for ($k = 0; $k < count($created_user); $k++) {
                                    if ($created_user[$k]['parent_id'] == trim($records[$i][array_search('parent_ib', $fields)])) {
                                        $get_parent_id = User::where('email', trim($created_user[$k]['parent_email']))->where('type', CombinedService::type());
                                        if (CombinedService::is_combined()) {
                                            $get_parent_id = $get_parent_id->where('combine_access', 1);
                                        }
                                        $get_parent_id = $get_parent_id->first();
                                        // check existing reference
                                        $check_reference = IB::where('reference_id', $existing_user->id)->exists();
                                        if (!$check_reference && $get_parent_id) {
                                            if ($get_parent_id->id != $existing_user->id) {
                                                IB::create([
                                                    'ib_id' => $get_parent_id->id,
                                                    'reference_id' => $existing_user->id
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } //end mail check in file
            }
        }
        return Response::json([
            'status' => true,
            'message' => 'Import success',
        ]);
    }
}
