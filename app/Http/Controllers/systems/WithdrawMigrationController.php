<?php

namespace App\Http\Controllers\systems;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\TradingAccount;
use App\Models\admin\InternalTransfer;
use App\Models\ExternalFundTransfers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class WithdrawMigrationController extends Controller
{
    public function index()
    {
        return view('systems.migrations.withdraw-migration');
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
            if ($records[$i][array_search('sender_email', $fields)] != "" && $records[$i][array_search('receiver_email', $fields)] != "") {
                $exist_sender_user = User::where('email', $records[$i][array_search('sender_email', $fields)])->whereBetween('id', [4073, 4137])->where('type', 0)->first();
                $exist_receiver_user = User::where('email', $records[$i][array_search('receiver_email', $fields)])->first();

                // return $records[$i][array_search('method', $fields)];
                // check user exists or not 
                if (!empty($exist_sender_user) && !empty($exist_receiver_user)) {
                    $created = ExternalFundTransfers::create([
                        'sender_id' => $exist_sender_user->id,
                        'receiver_id' => $exist_receiver_user->id,
                        'amount' => $records[$i][array_search('amount', $fields)],
                        'charge' => 0,
                        'status' => $records[$i][array_search('status', $fields)],
                        'type' => $records[$i][array_search('type', $fields)],
                        'txnid' => $records[$i][array_search('txnid', $fields)],
                        // 'approved_by' => $records[$i][array_search('approved_by', $fields)]??1,
                        // 'approved_date' => $records[$i][array_search('approved_date', $fields)]??NULL,
                        // 'admin_log' => $records[$i][array_search('admin_log', $fields)]??"",
                        'sender_wallet_type' => $records[$i][array_search('sender_wallet_type', $fields)],
                        'receiver_wallet_type' => $records[$i][array_search('receiver_wallet_type', $fields)]
                    ])->id;
                    
                    // `sender_id`, `receiver_id`, `amount`, `charge`, `type`, `status`, `note`, `txnid`, `approved_by`, `admin_log`, `approved_date`, `sender_wallet_type`, `receiver_wallet_type`, 
                }
            }
        }
        return Response::json([
            'status' => true,
            'message' => 'Import success',
        ]);
    }
    // public function store(Request $request)
    // {
    //     try {
    //         $validation_rules = [
    //             'csv_file' => 'required|mimes:csv',
    //         ];
    //         $validator = Validator::make($request->all(), $validation_rules);
    //         if ($validator->fails()) {
    //             return Response::json([
    //                 'status' => false,
    //                 'errors' => $validator->errors(),
    //                 'message' => 'Fix the following Errors'
    //             ]);
    //         }
    //         $path = $request->file('csv_file')->getRealPath();

    //         $records = array_map('str_getcsv', file($path));
    //         $created_user = [];
    //         if (!count($records) > 0) {
    //             return Response::json([
    //                 'status' => false,
    //                 'message' => 'This file is broken'
    //             ]);
    //         }
    //         // Get field names from header column
    //         $fields = array_map('strtolower', $records[0]);
    //         $fields = str_replace(' ', '_', $fields);
    //         // // chacking header 

    //         // Remove the header column
    //         // return $records;
    //         array_shift($records);
    //         for ($i = 0; $i < count($records); $i++) {
    //             // check mail exist in file
    //             if ($records[$i][array_search('email', $fields)] != "") {
    //                 $exist_user = User::where('email', $records[$i][array_search('email', $fields)])
    //                     ->where('type', 0)
    //                     ->whereBetween('id', [4073, 4137])
    //                     ->first();

    //                 // return $records[$i][array_search('method', $fields)];
    //                 // check user exists or not 
    //                 if (!empty($exist_user)) {
    //                     $trading_account = TradingAccount::where('account_number', $records[$i][array_search('account_number', $fields)])->first();
    //                     if(isset($trading_account)){
    //                         $created = InternalTransfer::create([
    //                             'user_id' => $exist_user->id,
    //                             'platform' => "MT5",
    //                             'account_id' => $trading_account->id,
    //                             'invoice_code' => $records[$i][array_search('invoice_code', $fields)] ?? 100,
    //                             'amount' => $records[$i][array_search('amount', $fields)] ?? "",
    //                             'order_id' => $records[$i][array_search('order_id', $fields)] ?? "",
    //                             'type' => $records[$i][array_search('type', $fields)] ?? "",
    //                             'status' => $records[$i][array_search('status', $fields)] ?? "D"
    //                         ])->id;
    //                     }
    //                 }
    //             }
    //         }

    //         // `user_id`, `platform`, `account_id`, `invoice_code`, `amount`, `charge`, `order_id`, `type`, `status`, `note`, `client_log`

    //         // `user_id`, `account_number`, `account_status`, `platform`, `group_id`, `leverage`, `base_currency`, `client_type`, `phone_password`, `master_password`, `investor_password`, `balance`, `comment`, `block_status`, `commission_status`, `deposit_status`, `withdraw_status`, `user_name`, `client_id`, `approve_status`, `approve_date`, `approved_by`, `page`
    //         // SELECT * FROM `pro_external_fund_transfers` WHERE receiver_id = 109 OR receiver_id = 269 OR receiver_id = 277 or receiver_id = 88 OR receiver_id = 182 OR receiver_id = 135 OR receiver_id = 76 OR receiver_id = 274 OR receiver_id = 275 OR receiver_id = 312 OR receiver_id = 260 OR receiver_id = 29 OR receiver_id = 282 OR receiver_id = 348;

    //         return Response::json([
    //             'status' => true,
    //             'message' => 'Import success',
    //         ]);
    //     } catch (\Throwable $th) {
    //         throw $th;
    //     }
    // }

    // public function store(Request $request)
    // {
    //     $validation_rules = [
    //         'csv_file' => 'required|mimes:csv',
    //     ];
    //     $validator = Validator::make($request->all(), $validation_rules);
    //     if ($validator->fails()) {
    //         return Response::json([
    //             'status' => false,
    //             'errors' => $validator->errors(),
    //             'message' => 'Fix the following Errors'
    //         ]);
    //     }
    //     $path = $request->file('csv_file')->getRealPath();

    //     $records = array_map('str_getcsv', file($path));
    //     $created_user = [];
    //     if (!count($records) > 0) {
    //         return Response::json([
    //             'status' => false,
    //             'message' => 'This file is broken'
    //         ]);
    //     }
    //     // Get field names from header column
    //     $fields = array_map('strtolower', $records[0]);
    //     $fields = str_replace(' ', '_', $fields);
    //     // // chacking header 

    //     // Remove the header column
    //     // return $records;
    //     array_shift($records);
    //     for ($i = 0; $i < count($records); $i++) {
    //         // check mail exist in file
    //         if ($records[$i][array_search('email', $fields)] != "") {
    //             $exist_user = User::where('email', $records[$i][array_search('email', $fields)])
    //                 ->where('type', 0)
    //                 ->whereBetween('id', [4073, 4137])
    //                 ->first();

    //             // return $records[$i][array_search('method', $fields)];
    //             // check user exists or not 
    //             if (!empty($exist_user)) {
    //                 $created = TradingAccount::create([
    //                     'user_id' => $exist_user->id,
    //                     'account_number' => $records[$i][array_search('account_number', $fields)],
    //                     'account_status' => 1,
    //                     'platform' => "MT5",
    //                     'group_id' => 18,
    //                     'leverage' => $records[$i][array_search('leverage', $fields)] ?? 100,
    //                     'client_type' => $records[$i][array_search('client_type', $fields)] ?? "",
    //                     'phone_password' => $records[$i][array_search('phone_password', $fields)] ?? "",
    //                     'master_password' => $records[$i][array_search('master_password', $fields)] ?? "",
    //                     'investor_password' => $records[$i][array_search('investor_password', $fields)] ?? "",
    //                     'comment' => $records[$i][array_search('comment', $fields)] ?? "",
    //                     'comment' => $records[$i][array_search('comment', $fields)] ?? "",
    //                 ])->id;
    //             }
    //         }
    //     }

    //     // `user_id`, `account_number`, `account_status`, `platform`, `group_id`, `leverage`, `base_currency`, `client_type`, `phone_password`, `master_password`, `investor_password`, `balance`, `comment`, `block_status`, `commission_status`, `deposit_status`, `withdraw_status`, `user_name`, `client_id`, `approve_status`, `approve_date`, `approved_by`, `page`

    //     return Response::json([
    //         'status' => true,
    //         'message' => 'Import success',
    //     ]);
    // }


    // public function store(Request $request)
    // {
    //     $validation_rules = [
    //         'csv_file' => 'required|mimes:csv',
    //     ];
    //     $validator = Validator::make($request->all(), $validation_rules);
    //     if ($validator->fails()) {
    //         return Response::json([
    //             'status' => false,
    //             'errors' => $validator->errors(),
    //             'message' => 'Fix the following Errors'
    //         ]);
    //     }
    //     $path = $request->file('csv_file')->getRealPath();

    //     $records = array_map('str_getcsv', file($path));
    //     $created_user = [];
    //     if (!count($records) > 0) {
    //         return Response::json([
    //             'status' => false,
    //             'message' => 'This file is broken'
    //         ]);
    //     }
    //     // Get field names from header column
    //     $fields = array_map('strtolower', $records[0]);
    //     $fields = str_replace(' ', '_', $fields);
    //     // // chacking header 

    //     // Remove the header column
    //     // return $records;
    //     array_shift($records);
    //     for ($i = 0; $i < count($records); $i++) {
    //         // check mail exist in file
    //         if ($records[$i][array_search('email', $fields)] != "") {
    //             $exist_user = User::where('email', $records[$i][array_search('email', $fields)])
    //                 ->where('type', 0)
    //                 ->whereBetween('id', [4073, 4137])
    //                 ->first();

    //             // return $records[$i][array_search('method', $fields)];
    //             // check user exists or not 
    //             if (!empty($exist_user)) {
    //                 $created = Withdraw::create([
    //                     'user_id' => $exist_user->id,
    //                     'invoice_id' => $records[$i][array_search('invoice_id', $fields)] ?? "",
    //                     'transaction_id' => "",
    //                     'transaction_type' => $records[$i][array_search('method', $fields)] ?? "",
    //                     'amount' => $records[$i][array_search('amount', $fields)],
    //                     'charge' => 0,
    //                     'approved_status' => $records[$i][array_search('approved_status', $fields)],
    //                     'ip_address' => "",
    //                     'bank_proof' => "",
    //                     'bank_id' => "",
    //                     'currency' => $records[$i][array_search('currency', $fields)],
    //                     // 'local_currency' => $records[$i][array_search('local_currency', $fields)],
    //                 ])->id;
    //             }
    //         }
    //     }

    //     return Response::json([
    //         'status' => true,
    //         'message' => 'Import success',
    //     ]);
    // }
}
