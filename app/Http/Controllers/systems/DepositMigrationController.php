<?php

namespace App\Http\Controllers\systems;

ini_set('max_execution_time', 1800);

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\IB;
use App\Models\Log;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class DepositMigrationController extends Controller
{
    public function index()
    {
        return view('systems.migrations.deposit-migration');
    }

    public function store(Request $request)
    {
        try {
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
    
            // Remove the header column
            // return $records;
            array_shift($records);
            for ($i = 0; $i < count($records); $i++) {
                // check mail exist in file
                if ($records[$i][array_search('email', $fields)] != "") {
                    $exist_user = User::where('email', $records[$i][array_search('email', $fields)])
                        ->where('type', 0)
                        ->whereBetween('id', [4073, 4137])
                        ->first();
                    // check user exists or not 
                    if (!empty($exist_user)) {
                        $created = Deposit::create([
                            'user_id' => $exist_user->id,
                            'invoice_id' => $records[$i][array_search('invoice_id', $fields)],
                            'transaction_type' => $records[$i][array_search('transaction_type', $fields)],
                            'amount' => $records[$i][array_search('amount', $fields)],
                            'wallet_type' => $records[$i][array_search('wallet_type', $fields)],
                            'charge' => 0,
                            'approved_status' => $records[$i][array_search('approved_status', $fields)],
                            // 'other_transaction_id' => $records[$i][array_search('other_transaction_id', $fields)]??0,
                            'ip_address' => $records[$i][array_search('ip_address', $fields)]??"",
                            'bank_proof' => "",
                            'bank_id' => "",
                            'currency' => $records[$i][array_search('currency', $fields)],
                            // 'local_currency' => $records[$i][array_search('local_currency', $fields)],
                        ])->id;
                    }
                }
            }
    
            return Response::json([
                'status' => true,
                'message' => 'Import success',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
