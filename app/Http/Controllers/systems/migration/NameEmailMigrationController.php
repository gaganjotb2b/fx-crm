<?php

namespace App\Http\Controllers\systems\migration;

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

class NameEmailMigrationController extends Controller
{
    public function index(Request $request)
    {
        return view('systems.migrations.name-email-migration');
    }
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
                'message' => 'This file is broken or empty!'
            ]);
        }
        // Get field names from header column
        $fields = array_map('strtolower', $records[0]);
        $fields = str_replace(' ', '_', $fields);
        array_shift($records); // remove header columns | first row
        // check the required field is exists in the csv
        // check name field is available
        if (!in_array('name', $fields)) {
            return Response::json([
                'status' => false,
                'message' => 'The name field is required in this csv file',
            ]);
        }
        // check email field is available
        if (!in_array('email', $fields)) {
            return Response::json([
                'status' => false,
                'message' => 'The email field is required in this csv file',
            ]);
        }
        $count_created = 0;
        for ($i = 0; $i < count($records); $i++) {
            for ($j = 0; $j < count($records[$i]); $j++) {
                // check mail exist in file
                if ($records[$i][array_search('email', $fields)] != "") {
                    $check = User::where('email', 'like', '%' . $records[$i][array_search('email', $fields)] . '%');
                    // check if existing email is ib/trader
                    if ($request->type === 'trader') {
                        $check = $check->where('type', CombinedService::type());
                    } else {
                        $check = $check->where('type', CombinedService::type());
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
                                'live_status' => 'live', //live or demo
                                'password' => Hash::make($password),
                                'transaction_password' => Hash::make($transaction_pin),
                                'email_verified_at' => date('Y-m-d h:i:s', strtotime(now())),
                                'type' => ($request->type === 'trader') ? CombinedService::type() : CombinedService::type(),
                                'combine_access' => ($request->type === 'trader') ? 0 : 1,
                                'ib_group_id' => ($request->type === 'trader') ? 1 : 1,
                            ]);
                            // create password log
                            Log::create([
                                'user_id' => $user_create->id,
                                'password' => encrypt($password),
                                'transaction_password' => encrypt($transaction_pin)
                            ]);
                            // crete user descryption
                            $userDes_create = UserDescription::create([
                                'user_id' => $user_create->id,
                                'state' => 'N/A',
                                'city' => 'N/A',
                                'address' => 'N/A',
                                'zip_code' => 'N/A',
                            ]);
                            if ($userDes_create) {
                                $count_created++;
                            }
                        }
                    }
                } //end mail check in file
            }
        }
        return Response::json([
            'status' => true,
            'message' => 'Import success, Total ' . $count_created . ' user created',
        ]);
    }
}
