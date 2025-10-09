<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CryptoAddress;
use App\Models\Deposit;
use App\Models\OtherTransaction;
use App\Models\TempTransaction;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;
use App\Services\systems\NotificationService;
use App\Services\TransactionService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class TraderCryptoDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('crypto_deposit', 'trader'));
        $this->middleware(AllFunctionService::access('deposit', 'trader'));
    }
    public function crypto_deposit(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $validator = Validator::make(
                $request->all(),
                [
                    'block_chain' => 'required|max:50|exists:crypto_addresses,block_chain',
                    'currency' => 'required|max:50|exists:crypto_addresses,name',
                    'crypto_address' => 'required|max:255|exists:crypto_addresses,address',
                    'usd_amount' => 'required|numeric|min:1',
                    'crypto_amount' => 'required|numeric',
                ]
            );
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // create temp transactions
            $create = TempTransaction::create([
                'transaction_id' => strtoupper(Uuid::uuid4()->toString()),
                'type' => 'deposit',
                'user_id' => $trader_user->id,
                'properties' => json_encode($request->all()),
                'log' => AdminLogService::admin_log('make as deposit request for crypto'),
            ]);
            if ($create) {
                return Response::json([
                    'status' => true,
                    'payment_id' => $create->transaction_id,
                    'message' => 'First step successfully done, You need one more step for verification',
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
                'errors' => $th->getMessage(),
            ]);
        }
    }
    // check crypto vlaidation
    public function deposit_validation(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $validator = Validator::make(
                $request->all(),
                [
                    'payment_id' => 'required|max:50|exists:temp_transactions,transaction_id',
                    'transaction_hash' => 'required|unique:deposits,transaction_id',
                ]
            );
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            $tempDeposit = TempTransaction::where(function ($query) use ($trader_user, $request) {
                $query->where('user_id', $trader_user->id)
                    ->where('type', 'deposit')
                    ->where('transaction_id', $request->input('payment_id'));
            })->first();
            if (!$tempDeposit) {
                return Response::json([
                    'status' => false,
                    'message' => 'Invalid request found'
                ]);
            }
            $properties = json_decode($tempDeposit->properties);
            // return $properties;
            $currency = $properties->currency;
            $crypto_amount = $properties->crypto_amount;
            $usd_amount = $properties->usd_amount;
            $block_chain = $properties->block_chain;
            $crypto_address = $properties->crypto_address;
            $result = $this->crypto_validation($currency, $request->input('transaction_hash'));
            $deposit_status = 'P';
            switch (strtolower($currency)) {
                    // trc20 validation check
                case 'trc20':
                    if (isset($result->ownerAddress)) {

                        // Convert milliseconds to seconds
                        // Create a Carbon instance from the timestamp in seconds
                        // Calculate the difference in seconds from now to the timestamp
                        $timestampInSeconds = $result->timestamp / 1000;
                        $transactionTimestamp = Carbon::createFromTimestamp($timestampInSeconds);
                        $timeDiffInSeconds = Carbon::now()->diffInSeconds($transactionTimestamp);
                        // deposti status
                        if ($timeDiffInSeconds < 86400) {
                            $deposit_status = 'P';
                        } else {
                            // Time check failed, do something else
                            $deposit_status = 'A';
                        }
                        if ($result->confirmations <= 3) {
                            $deposit_status = "P";
                        }
                        if (empty($result->tokenTransferInfo)) {
                            return Response::json([
                                'status' => false,
                                'message' => "Transaction hash invalid! This is not  $currency transaction."
                            ]);
                        }

                        // Contact address match
                        $tokenTransferInfo = $result->tokenTransferInfo;
                        $usdt_contact_address = "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t";
                        if ($tokenTransferInfo->contract_address != $usdt_contact_address) {
                            return Response::json([
                                'status' => false,
                                'message' => 'Contact address not mathed',
                            ]);
                        }
                        // broker address check
                        // return $tokenTransferInfo->to_address;
                        if (!CryptoAddress::where('address', $tokenTransferInfo->to_address)->exists()) {
                            return Response::json([
                                'status' => false,
                                'message' => 'Crypto address not mathced'
                            ]);
                        }
                        // symbol standard matching
                        if ($tokenTransferInfo->tokenType !== "trc20") {

                            return Response::json([
                                'status' => false,
                                'message' => 'Standard does not match!'
                            ]);
                        }
                        // amount check
                        $original_crypto_amount = $crypto_amount;
                        $changeable_amount = (($crypto_amount / 100) * 5);
                        $max_amount = $original_crypto_amount + $changeable_amount;
                        $min_amount = $original_crypto_amount - $changeable_amount;

                        if ($max_amount < ($tokenTransferInfo->amount_str / 1000000) || $min_amount > ($tokenTransferInfo->amount_str / 1000000)) {

                            return Response::json([
                                'status' => false,
                                'message' => 'Amount not mathced'
                            ]);
                        } elseif ($original_crypto_amount === ($tokenTransferInfo->amount_str / 1000000)) {
                            $deposit_status = "A";
                        } else {
                            $deposit_status = "P";
                        }
                    } else {
                        return Response::json([
                            'status' => false,
                            'message' => 'Transaction hash invalid! This is not ' . $currency . ' transaction.'
                        ]);
                    }
                    break;
                case 'erc20':
                    // erc 20 validation check
                    if (isset($result->success)) {
                        // Convert milliseconds to seconds
                        // Create a Carbon instance from the timestamp in seconds
                        // Calculate the difference in seconds from now to the timestamp
                        $timestampInSeconds = $result->timestamp / 1000;
                        $transactionTimestamp = Carbon::createFromTimestamp($timestampInSeconds);
                        $timeDiffInSeconds = Carbon::now()->diffInSeconds($transactionTimestamp);
                        // deposti status
                        if ($timeDiffInSeconds < 86400) {
                            $deposit_status = 'P';
                        } else {
                            // Time check failed, do something else
                            $deposit_status = 'A';
                        }
                        // deposit confirmation [minimum 3 is required]
                        if ($result->confirmations <= 3) {
                            $deposit_status = "P";
                        }
                        // invalid transaction has
                        if (empty($result->operations)) {
                            return Response::json([
                                'status' => false,
                                'message' => 'Transaction hash invalid! This is not ' . $currency . 'transaction.'
                            ]);
                        }
                        // broker address matching
                        $operations = $result->operations;
                        $crypto_address = CryptoAddress::where(function ($query) use ($operations) {
                            $query->where('verify_1', 1)
                                ->where('verify_2', 1)
                                ->where('status', 1)
                                ->where('address', $operations[0]->to);
                        })->where('name', 'erc20')->exists();
                        if (!$crypto_address) {
                            return Response::json([
                                'status' => false,
                                'message' => 'Broker address not found!'
                            ]);
                        }
                        // amount check
                        $original_crypto_amount = $crypto_amount;
                        $changeable_amount = (($crypto_amount / 100) * 5);
                        $max_amount = $original_crypto_amount + $changeable_amount;
                        $min_amount = $original_crypto_amount - $changeable_amount;

                        if ($max_amount < ($operations[0]->intValue / 1000000) || $min_amount > ($operations[0]->intValue / 1000000)) {
                            return Response::json([
                                'status' => false,
                                'message' => 'Amount does not match!'
                            ]);
                        } elseif ($original_crypto_amount == ($operations[0]->intValue / 1000000)) {
                            $deposit_status = "A";
                        } else {
                            $deposit_status = "P";
                        }
                        $token_info = $operations[0]->tokenInfo;
                        // check symbol 
                        if ($token_info->symbol != "USDT") {
                            return Response::json([
                                'status' => false,
                                'message' => 'Symbol does not match!'
                            ]);
                        }
                        $usdt_contact_address = "0xdac17f958d2ee523a2206206994597c13d831ec7";
                        // check contact address
                        if ($token_info->address !== $usdt_contact_address) {
                            return Response::json([
                                'status' => false,
                                'message' => 'Contact address does not match!'
                            ]);
                        }
                    } else {
                        return Response::json([
                            'status' => false,
                            'message' => 'Transaction hash invalid! This is not ' . $request->instrument . ' transaction.',
                        ]);
                    }
                    break;
                default:
                    return Response::json([
                        'status' => false,
                        'message' => 'Invalid currency found'
                    ]);
                    break;
            }
            $charge = TransactionService::charge('deposit', $usd_amount, null);
            $invoice = strtoupper(Uuid::uuid4()->toString());
            $create_other_transaction = OtherTransaction::create([
                'user_id' => $trader_user->id,
                'transaction_type' => 'crypto',
                'crypto_type' => $block_chain,
                'crypto_instrument' => strtolower($currency),
                'crypto_address' => $crypto_address,
                'crypto_amount' => $crypto_amount,
            ]);
            $create = Deposit::create([
                'user_id' => $trader_user->id,
                'invoice_id' => $invoice,
                'transaction_type' => 'crypto',
                'transaction_id' => $request->input('transaction_hash'),
                'incode' => '',
                'amount' => $usd_amount,
                'charge' => $charge,
                'ip_address' => request()->ip(),
                'other_transaction_id' => $create_other_transaction->id,
                'approved_status' => strtoupper($deposit_status),
            ]);
            if ($create) {
                MailNotificationService::admin_notification([
                    'amount' => $usd_amount,
                    'name' => $trader_user->name,
                    'email' => $trader_user->email,
                    'type' => 'deposit',
                    'client_type' => 'trader',
                    'crypto_address' => $crypto_address
                ]);
                EmailService::send_email('crypto-deposit-request', [
                    'user_id'               => $trader_user->id,
                    'clientWithdrawAmount'  => $usd_amount,
                    'previous_balance'  => "",
                    'deposit_method'  => "Crypto",
                    'deposit_status'  => "Pending",
                    'request_amount'  => $usd_amount,
                ]);
                NotificationService::system_notification([
                    'type' => 'deposit',
                    'user_id' => $trader_user->id,
                    'user_type' => 'trader',
                    'table_id' => $create->id,
                    'category' => 'client',
                ]);
                //<---client email as user id
                // activity("crypto deposit")
                //     ->causedBy(auth()->user()->id)
                //     ->withProperties($request->all())
                //     ->event($request->type . "crypto deposit")
                //     ->performedOn($user)
                //     ->log("The IP address " . request()->ip() . " has been " . $request->type . "deposit");
                // // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'message' => 'Deposit successfully done'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // check validtion using api
    private function crypto_validation($currency, $hash)
    {
        try {
            if (strtolower($currency) === 'trc20') {
                $client = new Client(['verify' => false]);
                $headers = [
                    'Accept' => 'application/json'
                ];
                $request = new Psr7Request('GET', "https://apilist.tronscan.org/api/transaction-info?hash=$hash", $headers);
                $res = $client->sendAsync($request)->wait();
                return json_decode($res->getBody());
            } elseif (strtolower($currency) === 'erc20') {
                $url = "https://api.ethplorer.io/getTxInfo/" . $hash . "?apiKey=freekey";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                $result = json_decode($response);
                curl_close($ch); // Close the connection
                return $result;
            }
        } catch (\Throwable $th) {
            // throw $th;
            return false;
        }
    }
}
