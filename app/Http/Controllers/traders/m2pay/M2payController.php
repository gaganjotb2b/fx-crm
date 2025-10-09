<?php

namespace App\Http\Controllers\traders\m2pay;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\CryptoCurrency;
use App\Models\Deposit;
use App\Models\OtherTransaction;
use App\Services\balance\BalanceSheetService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;

class M2payController extends Controller
{
    public function index(Request $request)
    {
        try {
            $symbol = CryptoCurrency::select('symbol')->distinct()->get('symbol');
            $bank_accounts = BankAccount::where('user_id',auth()->user()->id)->select('bank_ac_number')->get();
            return view('traders/deposit/m2pay', [
                'symbols' => $symbol,
                'bank_accounts' => $bank_accounts
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function deposit(Request $request)
    {
        try {
            $validation_rules = [
                'currency' => 'required|string',
                'amount' => 'required|numeric',
                'usd_amount' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            $time = now();
            $crypto_currency = CryptoCurrency::where('symbol', $request->crypto_symbol)->where('currency', $request->currency)->first();
            // make signature
            $signature = $this->signature([
                "amount" => $request->usd_amount,
                'apiToken' => "ZDEog9EEbY7Ftb8LVAiAUcEtE62O6u2X0d7W",
                'callbackUrl' => 'https://crm.xflowmarkets.com/user/deposit/matchpay/gateway',
                'currency' => 'USD',
                'paymentCurrency' => $crypto_currency->payment_currency,
                'paymentGatewayName' => $crypto_currency->gateway_name,
                'timestamp' => strtotime($time),
                'secretKey' => "4YIAymXWgU6YjOV16TdC51UCNizr1cvgCSIm"
            ]);
            $signature = strtolower($signature);
            // api request
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json'
            ];
            $body = '{
                "amount": "' . $request->usd_amount . '",
                "apiToken" : "ZDEog9EEbY7Ftb8LVAiAUcEtE62O6u2X0d7W",
                "callbackUrl" : "https://crm.xflowmarkets.com/user/deposit/matchpay/gateway",
                "currency" : "USD",
                "paymentCurrency" : "' . $crypto_currency->payment_currency . '",
                "paymentGatewayName":"' . $crypto_currency->gateway_name . '",
                "signature":"' . $signature . '",
                "timestamp" : "' . strtotime($time) . '"
                }';
            $api_request = new Psr7Request('POST', 'https://pp-staging-vaadin.fx-edge.com/api/v2/deposit/crypto_agent', $headers, $body);
            $res = $client->sendAsync($api_request)->wait();
            $result  = json_decode($res->getBody());
            return $result;
            // store in database
            $create = OtherTransaction::create([
                'transaction_type' => 'm2pay',
                'crypto_type' => $crypto_currency->symbol,
                'crypto_instrument' => $crypto_currency->currency,
                'block_chain' => $crypto_currency->payment_currency,
                'gateway_name' => $crypto_currency->gateway_name,
                'crypto_address' => $result->address,
                'crypto_amount' => $request->amount,
                'ip_address' => $request->ip(),
                'status' => $result->status,
                'payment_id' => $result->paymentId,
            ]);
            if ($create) {
                $create_deposit = Deposit::create([
                    'user_id' => auth()->user()->id,
                    'transaction_type' => 'm2pay',
                    'transaction_id' => $result->paymentId, //payment id as transaction Id,
                    'amount' => $request->usd_amount,
                    'other_transaction_id' => $create->id,
                    'approved_status' => 'P',
                    'ip_address' => $request->ip(),
                ]);
                return Response::json([
                    'status' => true,
                    'checkoutUrl' => $result->checkoutUrl,
                    'message' => 'Please dont refresh this page, we redirect you to checkout page',
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Transaction faild, please try again later!',
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error Or selected currency not supported'
            ]);
        }
    }
    public function signature($data = []): string
    {
        $created_signature = implode('', $data);
        $hash = hash('sha384', $created_signature);
        return $hash;
    }

    // m2pay callback
    function callback(Request $request)
    {
        try {
            if (strtolower($request->status) === 'pending') {
                // update other transaction
                $update_other_txn = OtherTransaction::where('payment_id', $request->paymentId)
                    ->update([
                        'status' => $request->status,
                    ]);
                // update deposit table
                $transaction_info = $request->cryptoTransactionInfo;
                $update_deposit = Deposit::where('transaction_id', $request->paymentId)
                    ->update(['invoice_id' => $transaction_info[0]['txid']]);
                return Response::json($update_deposit);
            }
            // confirmation 2
            if (strtolower($request->status) === 'done') {
                // update other transaction 
                $update_other_txn = OtherTransaction::where('payment_id', $request->paymentId)
                    ->update([
                        'status' => $request->status,
                    ]);
                // update deposit table
                $update_deposit = Deposit::where('transaction_id', $request->paymentId)
                    ->update([
                        'approved_status' => 'A'
                    ]);
                if ($update_deposit) {
                    $deposits = Deposit::where('transaction_id', $request->paymentId)->first();
                    BalanceSheetService::trader_wallet_deposit($deposits->user_id, $deposits->amount);
                    return redirect()->route('user.deposit.match2pay-success')
                        ->with([
                            'amount' => $request->finalAmount,
                            'status' => $request->status,
                            'currencty' => $request->transactionCurrency,
                            'crypto_address' => $request->depositAddress,
                        ]);
                }
            }
            // return $request->status;
            return Response::json([
                'status' => false,
                'message' => 'API response failed'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
        }
    }
    function success(Request $request)
    {
        try {
            return view('traders/deposit/m2pay-success');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
