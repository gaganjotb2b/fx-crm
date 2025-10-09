<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\OtpCode;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\accounts\AccountService;
use App\Services\balance\BalanceSheetService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\OtpService;
use App\Services\systems\AdminLogService;
use App\Services\systems\NotificationService;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class AccountToWalletController extends Controller
{
    public function __invoke(Request $request)
    {
        // return $request->all();
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'account' => 'required|exists:trading_accounts,account_number',
                    'amount' => 'required|numeric',
                    'trnsaction_pin' => 'required',
                ]
            );
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // get the accounts
            $account = TradingAccount::where('account_number', $request->account)->where('user_id', auth()->guard('api')->user()->id)->first();
            $client = User::find($account->user_id);
            if (!$account) {
                return Response::json([
                    'statu' => false,
                    'message' => 'Account not found, please choose a valid account'
                ]);
            }
            // check the account is live or demo
            if (strtolower($account->client_type) === 'demo') {
                return Response::json([
                    'status' => false,
                    'message' => 'You can not use demo account',
                    'errors' => ['account' => 'You can not use demo account to transfer balance']
                ]);
            }
            // check transaction pin
            if (!Hash::check($request->input('trnsaction_pin'), auth()->guard('api')->user()->transaction_password)) {
                return Response::json([
                    'valid_status' => false,
                    'otp' => false,
                    'message' => 'Please fix the following errors.',
                    'errors' => ['pin' => 'Transaction pin Not match!']
                ]);
            }
            // check the account platform
            $invoice = Uuid::uuid4()->toString();
            $charge = TransactionService::charge('a_to_w', $request->input('amount'), null);
            if (OtpService::has_otp('transfer', auth()->guard('api')->user()->id)) {
                // sending otp code
                $client->otpCode()->delete();
                $data = [
                    'code' => mt_rand(100000, 999999),
                    'user_id' => $client->id,
                    'amount' => $request->input('amount'),
                    'type' => 'atw',
                    'properties' => json_encode($request->all()),
                ];
                $code_data = OtpCode::create($data);
                EmailService::send_email('otp-verification', [
                    'account_email' => $client->email,
                    'otp' => $data['code'],
                    'user_id' => $client->id,
                    'name' => $client->name,
                ]);
                return Response::json([
                    'status' => true,
                    'otp' => true,
                    'message' => 'We sending the OTP code to your email, please check your mail',
                ]);
            } else {
                // mt5 transfer
                if (strtolower($account->platform) === 'mt5') {
                    $mt5_api = new Mt5WebApi();
                    $result = $mt5_api->execute('BalanceUpdate', [
                        "Login" => (int)$account->account_number,
                        "Balance" => -(float)$request->input('amount'),
                        "Comment" => "account to wallet #" . $invoice
                    ]);
                }
                // mt4 transfer
                elseif (strtolower($account->platform) === 'mt4') {
                    $mt4_api = new MT4API();
                    // check mt4 balance
                    $mt4_balance = AccountService::get_mt4_balance($account->account_number, 'live');
                    if ($request->input('amount') > $mt4_balance['equity']) {
                        return Response::json([
                            'status' => false,
                            'errors' => ['amount' => "You don't have available balance!"],
                            'message' => "You don't have available balance!"
                        ]);
                    }

                    // $result = $mt4_api->execute([
                    //     'command' => 'deposit_funds',
                    //     'data' => array(
                    //         'account_id' => $account->account_number,
                    //         'amount' => -(float)$request->input('amount'),
                    //         'comment' => "account to wallet #" . $invoice
                    //     )
                    // ], 'live');\
                    $data = array(
                        'command' => 'BalanceUpdate',
                        'data' => array(
                            'Login' => $account->account_number,
                            'Balance' => -(float)$request->input('amount'),
                            'Comment' => "account to wallet #" . $invoice
                        ),
                    );
                    $result = $mt4_api->execute($data, 'live');
                }
                $result['success'] = true;
                $result['data']['order'] = rand(10000, 99999);
                if (isset($result['success']) || isset($result['status'])) {
                    $order = $result['data']['order'];
                    $create = InternalTransfer::create([
                        'user_id' => $account->user_id,
                        'platform' => $account->platform,
                        'invoice_code' => $invoice,
                        'account_id' => $account->id,
                        'charge' => $charge,
                        'amount' => $request->input('amount'),
                        'type' => 'atw',
                        'order_id' => $order,
                        'status' => 'A'
                    ]);
                    if ($create) {
                        $last_transaction = InternalTransfer::where('user_id', auth()->guard('api')->user()->id)->where('type', 'atw')->latest()->first();
                        MailNotificationService::admin_notification([
                            'amount' => $request->input('amount'),
                            'name' => $client->name,
                            'email' => $client->email,
                            'type' => 'account to wallet transfer',
                            'client_type' => 'trader'
                        ]);
                        EmailService::send_email('atw-transfer', [
                            'user_id' => $account->user_id,
                            'clientDepositAmount' => $request->input('amount'),
                            'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                            'previous_balance' => ((BalanceSheetService::trader_wallet_balance($client->id)) - ($last_transaction->amount)),
                            'transfer_amount' => $last_transaction->amount,
                            'total_balance' => BalanceSheetService::trader_wallet_balance($client->id)
                        ]);
                        NotificationService::system_notification([
                            'type' => 'account_to_wallet_transfer',
                            'user_id' => $account->id,
                            'user_type' => 'trader',
                            'table_id' => $create->id,
                            'category' => 'client',
                        ]);
                        // insert activity-----------------
                        // $user = User::find(auth()->user()->id);
                        // //<---client email as user id
                        // activity("account to wallet")
                        //     ->causedBy(auth()->user()->id)
                        //     ->withProperties($create)
                        //     ->event("account to wallet")
                        //     ->performedOn($user)
                        //     ->log("The IP address " . request()->ip() . " has been Account to wallet transfer");
                        // end activity log----------------->>
                        return Response::json([
                            'status' => true,
                            'message' => 'Transaction successfully done.'
                        ]);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'Something went wrong please try again later'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Got a server error, please contact for support, API connection failed'
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // otp check
    public function otp_check(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|exists:otp_codes,code'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }

            $code = $trader_user->otpCode()->where(function ($query) use ($request) {
                $query->where('code', $request->input('code'))
                    ->where('type', 'atw');
            })->first();
            // check valid otp code exist or not for this user
            if (!$code) {
                return Response::json([
                    'status' => false,
                    'message' => 'You enter invalid code, please try with valid code',
                    'errors' => ['code' => 'The code is invalid'],
                ]);
            }
            // check otp code is expired or not
            // check if it does not expired: the time is one hour
            if ($code->created_at->addMinutes(2) < Carbon::now()) {
                $code->delete();
                return Response::json(
                    [
                        'status' => false,
                        'message' => 'Code is expired'
                    ],
                    422
                );
            }
            // everything is good
            // now make a account to atw transfer
            $properties = json_decode($code->properties);
            $account = TradingAccount::where('id', $properties->account)
                ->where('user_id', auth()->guard('api')->user()->id)->first();
            $amount = $properties->amount;
            if (!$account) {
                return Response::json([
                    'statu' => false,
                    'message' => 'Account not found, please choose a valid account'
                ]);
            }
            // check the account is live or demo
            if (strtolower($account->client_type) === 'demo') {
                return Response::json([
                    'status' => false,
                    'message' => 'You can not use demo account',
                    'errors' => ['account' => 'You can not use demo account to transfer balance']
                ]);
            }
            // check the account platform
            $invoice = strtoupper(Uuid::uuid4()->toString());
            $charge = TransactionService::charge('a_to_w', $amount, null);
            // mt5 transfer
            if (strtolower($account->platform) === 'mt5') {
                $mt5_api = new Mt5WebApi();
                $result = $mt5_api->execute('BalanceUpdate', [
                    "Login" => (int)$account->account_number,
                    "Balance" => -(float)$amount,
                    "Comment" => "account to wallet #" . $invoice
                ]);
            }
            // mt4 transfer
            elseif (strtolower($account->platform) === 'mt4') {
                $mt4_api = new MT4API();
                // check mt4 balance
                $mt4_balance = AccountService::get_mt4_balance($account->account_number, 'live');
                if ($amount > $mt4_balance['equity']) {
                    return Response::json([
                        'status' => false,
                        'errors' => ['amount' => "You don't have available balance!"],
                        'message' => "You don't have available balance!"
                    ]);
                }

                $result = $mt4_api->execute([
                    'command' => 'deposit_funds',
                    'data' => array(
                        'account_id' => $account->account_number,
                        'amount' => -(float)$amount,
                        'comment' => "account to wallet #" . $invoice
                    )
                ], 'live');
            }

            if (isset($result['success']) && $result['success']) {
                $order = $result['data']['order'];
                $create = InternalTransfer::create([
                    'user_id' => $account->user_id,
                    'platform' => $account->platform,
                    'invoice_code' => $invoice,
                    'account_id' => $account->id,
                    'charge' => $charge,
                    'amount' => $amount,
                    'type' => 'atw',
                    'order_id' => $order,
                    'status' => 'A'
                ]);
                if ($create) {
                    $last_transaction = InternalTransfer::where('user_id', auth()->guard('api')->user()->id)->where('type', 'atw')->latest()->first();
                    $client = User::find($account->user_id);
                    $code->delete();
                    MailNotificationService::admin_notification([
                        'amount' => $amount,
                        'name' => $client->name,
                        'email' => $client->email,
                        'type' => 'account to wallet transfer',
                        'client_type' => 'trader'
                    ]);
                    EmailService::send_email('atw-transfer', [
                        'user_id' => $account->user_id,
                        'clientDepositAmount' => $amount,
                        'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                        'previous_balance' => ((BalanceSheetService::trader_wallet_balance($client->id)) - ($last_transaction->amount)),
                        'transfer_amount' => $last_transaction->amount,
                        'total_balance' => BalanceSheetService::trader_wallet_balance($client->id)
                    ]);
                    NotificationService::system_notification([
                        'type' => 'account_to_wallet_transfer',
                        'user_id' => $account->id,
                        'user_type' => 'trader',
                        'table_id' => $create->id,
                        'category' => 'client',
                    ]);
                    // insert activity-----------------
                    $user = User::find(auth()->user()->id);
                    //<---client email as user id
                    // activity("account to wallet")
                    //     ->causedBy(auth()->user()->id)
                    //     ->withProperties($create)
                    //     ->event("account to wallet")
                    //     ->performedOn($user)
                    //     ->log("The IP address " . request()->ip() . " has been Account to wallet transfer");
                    // end activity log----------------->>
                    return Response::json([
                        'status' => true,
                        'message' => 'Transaction successfully done.'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Something went wrong please try again later'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support, API connection failed'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
}
