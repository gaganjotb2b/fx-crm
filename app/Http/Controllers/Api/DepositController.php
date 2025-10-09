<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MailNotificationService;
use App\Models\CryptoAddress;
use App\Models\Deposit;
use App\Models\OtherTransaction;
use App\Models\User;
use App\Services\EmailService;
use App\Services\TransactionService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
    //crypto deposit 
    public function crypto_deposit(Request $request)
    {
        $data = [
            'status' => false,
            'submit_wait' => '',
            'message' => ''
        ];
        $validation_rules = [
            'block_chain' => 'required',
            'instrument' => 'required',
            'crypto_address' => 'required|max:191',
            'usd_amount' => 'required|numeric',
            'crypto_amount' => 'required|numeric',
        ];
        if (!isset($request->wizer_no)) {
            $validation_rules['transaction_id'] = 'required|unique:deposits|max:191';
        }

        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $data['status'] = false;

        if ($request->wizer_no == 1) {
            $data['status'] = true;
            return Response::json($data);
        }
        $deposit_success_status = "A";

        // transaction hash exists or not 
        $trxn_id_exists = Deposit::where('transaction_id', $request->transaction_id)->first();
        if ($trxn_id_exists) {
            $data['message'] = 'Transaction hash already exists!';
            return Response::json($data);
        }
        if (strtolower($request->instrument) == 'btc' ||  strtolower($request->instrument) == 'eth') {

            $url = "http://api.blockcypher.com/v1/'.strtolower($request->instrument).'/main/txs/'";
            $client = new Client();
            $response = $client->request('GET', $url);
            // return Response::json($response);
            // Price 
            // if( strtolower($wallet) == 'btc' ){
            //     $usd_price = $btc_price; 
            //     $convert = 100000000;
            // }else if( strtolower($wallet) == 'eth' ){
            //     $usd_price = $eth_price;
            //     $convert = 1000000000000000000;
            // }


            // if ($curl->error) {
            //     $response['message'] = "Transaction not valid.";
            // } else {
            //     $data = $curl->response;
            // }

            // if($response['message'] != ""){
            //     echo json_encode($response);
            //     exit;
            // }


            // //BTC
            // if (isset($data->outputs)) {

            //   $st_array = $data->outputs;

            //   for ($j = 0; $j < count($st_array); $j++) {

            //     $amount = number_format(($st_array[$j]->value / $convert), 10, '.', '');
            //     $addresses = $st_array[$j]->addresses;

            //     if(count($addresses) > 0){
            //       foreach ($addresses as $key => $address) {
            //         if($address == $recieve_address){
            //           $sent_amount = $amount * $usd_price;
            //           $sent_address = $data->inputs[0]->addresses[0];
            //           $r = true;
            //           break;
            //         }
            //       }
            //     }
            //   }
            // }else{
            //     $response['message'] = "Transaction ID is not valid";
            // }
        } else if (strtolower($request->instrument) == 'trc20') {
            // 83d06b3890858634d0400429d8487dfbc10414a072019baa674c98cdb3613902

            $txnid = $request->transaction_id;
            $url = "https://apilist.tronscan.org/api/transaction-info?hash=" . $txnid;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $result = json_decode($response);
            curl_close($ch); // Close the connection

            // echo "<pre>";
            // print_r($result->ownerAddress);
            // die;

            if (isset($result->ownerAddress)) {
                // // owner address does not match
                // if ($request->crypto_address != $result->ownerAddress) {
                //     $data['message'] = 'Invalid owner address!';
                //     return Response::json($data);
                // }

                //time check [minimum deposit time 24 hours]
                if ($result->timestamp < 86400) {
                    $deposit_success_status = "P";
                }

                // deposit confirmation [minimum 3 is required]
                if ($result->confirmations <= 3) {
                    $deposit_success_status = "P";
                }

                // invalid transaction has
                if (empty($result->tokenTransferInfo)) {
                    $data['message'] = 'Transaction hash invalid! This is not ' . $request->instrument . 'transaction.';
                    return Response::json($data);
                }

                $token_transfer_info = $result->tokenTransferInfo;
                // Contact address match
                $usdt_contact_address = "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t";
                if ($token_transfer_info->contract_address !== $usdt_contact_address) {
                    $data['message'] = 'Contact address does not match!';
                    return Response::json($data);
                }

                // broker address matching
                $crypto_address = CryptoAddress::where(function ($query) {
                    $query->where('verify_1', 1)
                        ->where('verify_2', 1)
                        ->where('status', 1);
                })->where('name', 'trc20');
                // all crypto address / all active addresses
                $block_chains = $crypto_address->select('address')->first();

                if (!empty($block_chains)) {
                    if ($token_transfer_info->to_address !== $block_chains->address) {
                        $data['message'] = 'Broker address does not match!';
                        return Response::json($data);
                    }
                }

                // symbol standard matching
                if ($token_transfer_info->tokenType !== "trc20") {
                    $data['message'] = 'Standard does not match!';
                    return Response::json($data);
                }

                // amount check
                $original_crypto_amount = $request->crypto_amount;
                $changeable_amount = (($request->crypto_amount / 100) * 5);
                $max_amount = $original_crypto_amount + $changeable_amount;
                $min_amount = $original_crypto_amount - $changeable_amount;

                if ($max_amount < ($token_transfer_info->amount_str / 1000000) || $min_amount > ($token_transfer_info->amount_str / 1000000)) {
                    $data['message'] = 'Amount does not match!';
                    return Response::json($data);
                } elseif ($original_crypto_amount === ($token_transfer_info->amount_str / 1000000)) {
                    $deposit_success_status = "A";
                } else {
                    $deposit_success_status = "P";
                }
            } else {
                $data['message'] = 'Transaction hash invalid! This is not ' . $request->instrument . ' transaction.';
                return Response::json($data);
            }
        } else if (strtolower($request->instrument) == 'erc20') {
            $txnid = $request->transaction_id;
            $url = "https://api.ethplorer.io/getTxInfo/" . $txnid . "?apiKey=freekey";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $result = json_decode($response);
            curl_close($ch); // Close the connection
            if (isset($result->success)) {
                // // owner address does not match
                // if ($request->crypto_address != $result->from) {
                //     $data['message'] = 'Invalid owner address!';
                //     return Response::json($data);
                // }

                //time check [minimum deposit time 24 hours]
                if ($result->timestamp < 86400) {
                    $deposit_success_status = "P";
                }

                // deposit confirmation [minimum 3 is required]
                if ($result->confirmations <= 3) {
                    $deposit_success_status = "P";
                }

                // invalid transaction has
                if (empty($result->operations)) {
                    $data['message'] = 'Transaction hash invalid! This is not ' . $request->instrument . 'transaction.';
                    return Response::json($data);
                }

                $operations = $result->operations;

                // broker address matching
                $crypto_address = CryptoAddress::where(function ($query) {
                    $query->where('verify_1', 1)
                        ->where('verify_2', 1)
                        ->where('status', 1);
                })->where('name', 'erc20');
                // all crypto address / all active addresses
                $block_chains = $crypto_address->select('address')->first();

                if (!empty($block_chains)) {
                    if ($operations[0]->to !== $block_chains->address) {
                        $data['message'] = 'Broker address does not match!';
                        return Response::json($data);
                    }
                } else {
                    $data['message'] = 'Broker address does not found!';
                    return Response::json($data);
                }

                // amount check
                $original_crypto_amount = $request->crypto_amount;
                $changeable_amount = (($request->crypto_amount / 100) * 5);
                $max_amount = $original_crypto_amount + $changeable_amount;
                $min_amount = $original_crypto_amount - $changeable_amount;

                if ($max_amount < ($operations[0]->intValue / 1000000) || $min_amount > ($operations[0]->intValue / 1000000)) {
                    $data['message'] = 'Amount does not match!';
                    return Response::json($data);
                } elseif ($original_crypto_amount === ($operations[0]->intValue / 1000000)) {
                    $deposit_success_status = "A";
                } else {
                    $deposit_success_status = "P";
                }

                $token_info = $operations[0]->tokenInfo;
                // check symbol 
                if ($token_info->symbol != "USDT") {
                    $data['message'] = 'Symbol does not match!';
                    return Response::json($data);
                }

                $usdt_contact_address = "0xdac17f958d2ee523a2206206994597c13d831ec7";
                // check contact address
                if ($token_info->address !== $usdt_contact_address) {
                    $data['message'] = 'Contact address does not match!';
                    return Response::json($data);
                }

                // echo "<pre>";
                // print_r($result);
                // die;
            } else {
                $data['message'] = 'Transaction hash invalid! This is not ' . $request->instrument . ' transaction.';
                return Response::json($data);
            }
        }
        $charge = TransactionService::charge('deposit', $request->usd_amount, null);
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);

        $create_other_transaction = OtherTransaction::create([
            'user_id' => auth()->user()->id,
            'transaction_type' => 'crypto',
            'crypto_type' => $request->block_chain,
            'crypto_instrument' => strtolower($request->instrument),
            'crypto_address' => $request->crypto_address,
            'crypto_amount' => $request->crypto_amount,
        ])->id;

        $create = Deposit::create([
            'user_id' => auth()->user()->id,
            'invoice_id' => $invoice,
            'transaction_type' => 'crypto',
            'transaction_id' => $request->transaction_id,
            'incode' => '',
            'amount' => $request->usd_amount,
            'charge' => $charge,
            'ip_address' => request()->ip(),
            'other_transaction_id' => $create_other_transaction,
            'approved_status' => $deposit_success_status,
        ]);

        $user = User::find(auth()->user()->id);
        if ($create) {
            //notification mail to admin
            MailNotificationService::notification('deposit', 'trader', 1, $user->name, $request->usd_amount);
            $last_transaction = Deposit::find($create->id);
            // sending email
            EmailService::send_email('crypto-deposit-request', [
                'user_id'               => auth()->user()->id,
                'clientWithdrawAmount'  => $request->usd_amount,
            ]);
            // insert activity-----------------
            //<---client email as user id
            activity("crypto deposit")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event($request->type . "crypto deposit")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been " . $request->type . "deposit");
            // end activity log----------------->>
            $data['status'] = true;
            $data['message'] = 'Deposit successfully done';
            $data['last_transaction'] = $last_transaction;
            return Response::json($data);
        }
        $data['status'] = false;
        $data['message'] = 'Somthing went wrong please try again later!';
        return Response::json($data);
    }
    // get all client deposit
    public function get_client_deposit(Request $request)
    {
        try {
            $validation_rules = [
                'status' => 'nullable|in:approved,pending,declined',
                'method' => 'nullable|in:bank,cash,prexis,help2pay,m2pay,nowpay,crypto,perfect-mony',
                'min_amount' => 'nullable|min:0|numeric',
                'max_amount' => 'nullable|min:0|numeric',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => "Validation Error",
                    'message' => "The request data is invalid.",
                    'errors' => $validator->errors(),
                ], 400);
            }
            // check autenticated user type
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $deposit = Deposit::where('user_id', $trader_user->id)
                ->where('wallet_type', 'trader');
            // fileter by  status
            if (isset($request->status) && $request->status != "") {
                $status = '';
                if (strtolower($request->status) === 'approved') {
                    $status = 'A';
                } elseif (strtolower($request->status) === 'pending') {
                    $status = 'P';
                } elseif (strtolower($request->status) === 'declined') {
                    $status = 'D';
                }
                $deposit = $deposit->where('approved_status', $status);
            }
            // filter by transaction method
            if (isset($request->method) && $request->method != "") {
                $deposit = $deposit->where('transaction_type', strtolower($request->method));
            }
            // filter by min amount
            if ($request->min_amount) {
                $deposit = $deposit->where('amount', '>=', $request->min_amount);
            }
            if ($request->max_amount) {
                $deposit = $deposit->where('amount', '<=', $request->max_amount);
            }
            // filter by date to
            if ($request->input('date_to')) {
                $to  = Carbon::parse($request->input('date_to'));
                $deposit = $deposit->whereDate('created_at', '<=', $to);
            }
            // filter by date from
            if ($request->input('date_from')) {
                $date_from  = Carbon::parse($request->input('date_from'));
                $deposit = $deposit->whereDate('created_at', '>=', $date_from);
            }
            $total_amount = $deposit->sum('amount');
            $deposit = $deposit->orderBy('deposits.created_at', 'DESC')
                ->with(['otherTransaction' => function ($query) {
                    $query->select('id', 'transaction_type', 'crypto_type', 'crypto_instrument');
                }, 'accountTransfer'])

                ->paginate($request->input('per_page', 5));
            return Response::json([
                'status' => true,
                'deposits' => $deposit,
                'total_amount' => $total_amount,

            ], 200);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
}
