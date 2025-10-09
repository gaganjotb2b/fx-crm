<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtherTransaction;
use App\Models\OtpCode;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\EmailService;
use App\Services\IBManagementService;
use App\Services\MailNotificationService;
use App\Services\OtpService;
use App\Services\systems\NotificationService;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class IbCryptoWithdraw extends Controller
{
    public function crypto_withdraw(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $validator = Validator::make($request->all(), [
                'block_chain' => 'required',
                'currency' => 'required',
                'crypto_address' => 'required|max:255',
                'usd_amount' => 'required|numeric',
                'crypto_amount' => 'required|numeric',
                'transaction_pin' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Validation error, please fix the following error',
                    'errors' => $validator->errors()
                ]);
            }
            // check minimum withdraw
            if (BalanceService::check_minimum_withdraw($request->input('usd_amount')) == false) {
                $min_withdraw = BalanceService::min_withdraw_amount();
                return Response::json([
                    'status' => false,
                    'errors' => ['usd_amount' => 'Minimum withdraw amount should be &dollar;' . $min_withdraw],
                    'message' => 'Minimum withdraw amount should be &dollar;' . $min_withdraw
                ]);
            }
            // check max  withdraw amount
            if (BalanceService::check_max_withdraw($request->input('usd_amount')) == false) {
                $max_withdraw = BalanceService::max_withdraw_amount();
                return Response::json([
                    'status' => false,
                    'errors' => ['usd_amount' => 'Maximum withdraw amount should be &dollar;' . $max_withdraw],
                    'message' => 'Maximum withdraw amount should be &dollar;' . $max_withdraw
                ]);
            }
            // check withdraw period
            $checkWithdralPermission = IBManagementService::checkWithdrawLimit();
            if ($checkWithdralPermission['status'] === false) {
                return Response::json($checkWithdralPermission);
            }
            if (!Hash::check($request->input('transaction_pin'), $ib_user->transaction_password)) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Transaction pin not matched',
                    'errors' => ['transaction_pin' => 'Transaction password not matched!']
                ]);
            }
            $charge = TransactionService::charge('withdraw', $request->input('usd_amount'), null);
            $balance = BalanceSheetService::ib_wallet_balance($ib_user->id);
            if ($balance <= 0 || (($request->usd_amount + $charge) > $balance)) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => "You don't have available balance",
                    'errors' => ['usd_amount' => "You don't have available balance"],
                ]);
            }
            if (OtpService::has_otp('withdraw', $ib_user->id)) {
                //    sending otp
                $ib_user->otpCode()->delete();
                $data = [
                    'code' => mt_rand(100000, 999999),
                    'user_id' => $ib_user->id,
                    'amount' => $request->input('usd_amount'),
                    'type' => 'withdraw',
                    'properties' => json_encode($request->all()),
                ];
                OtpCode::create($data);
                EmailService::send_email('otp-verification', [
                    'account_email' => $ib_user->email,
                    'otp' => $data['code'],
                    'user_id' => $ib_user->id,
                    'name' => $ib_user->name,
                ]);
                return Response::json([
                    'status' => true,
                    'otp' => true,
                    'message' => 'We sending the OTP code to your email, please check your mail',
                ]);
            }
            // else everyting is good
            // make transactions
            else {
                $invoice = strtoupper(Uuid::uuid4()->toString());
                $crypto_txn = OtherTransaction::create([
                    'transaction_type' => 'crypto',
                    'crypto_type' => $request->input('block_chain'),
                    'crypto_instrument' => $request->input('currency'),
                    'crypto_address' => $request->input('crypto_address'),
                    'crypto_amount' => $request->input('crypto_amount'),
                ]);
                $create = Withdraw::create([
                    'user_id' => $ib_user->id,
                    'transaction_id' => $invoice,
                    'transaction_type' => 'crypto',
                    'other_transaction_id' => $crypto_txn->id,
                    'amount' => $request->input('usd_amount'),
                    'charge' => $charge,
                    'wallet_type' => 'ib',
                ]);
                if ($create) {
                    $last_transaction = Withdraw::find($create->id);
                    // sending mail to user
                    EmailService::send_email('crypto-withdraw-request', [
                        'cryptoAddress' => $request->input('crypto_address'),
                        'currency' => $request->input('currency'),
                        'blockchain' => $request->input('block_chain'),
                        'amount' => $request->input('usd_amount'),
                        'cryptoAmount' => $request->input('crypto_amount'),
                        'status' => "Pending",
                        'user_id' => $ib_user->id,
                    ]);
                    MailNotificationService::admin_notification([
                        'amount' => $request->input('usd_amount'),
                        'name' => $ib_user->name,
                        'email' => $ib_user->email,
                        'type' => 'withdraw',
                        'client_type' => 'ib',
                        'crypto_address' => $request->input('crypto_address')
                    ]);
                    NotificationService::system_notification([
                        'type' => 'withdraw',
                        'user_id' => $ib_user->id,
                        'user_type' => 'ib',
                        'table_id' => $create->id,
                        'category' => 'client',
                    ]);
                    EmailService::it_corner_mail('gainxplus1@gmail.com', [
                        'client_email' => $ib_user->email,
                        'crypto_address' => $request->input('crypto_address'),
                        'block_chain' => $request->input('block_chain'),
                        'currency' => $request->input('currency'),
                        'usd_amount' => $request->input('usd_amount'),
                        'crypto_amount' => $request->input('crypto_amount')
                    ]);
                    //<---client email as user id
                    // activity("crypto withdraw")
                    //     ->causedBy(auth()->user()->id)
                    //     ->withProperties($create)
                    //     ->event("crypto withdraw")
                    //     ->performedOn($trader_user)
                    //     ->log("The IP address " . request()->ip() . " has been " .  "withdraw");
                    // end activity log----------------->>
                    return Response::json([
                        'status' => true,
                        'otp' => false,
                        'message' => 'Withdraw request successfully done, please wait for approve'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Something went wrong, please try again later'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // otp check for withdraw
    public function otp_check(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $validator = Validator::make($request->all(), [
                'code' => 'required|exists:otp_codes,code'
            ]);
            if ($validator->fails()) {
                try {
                    $user_otp = OtpCode::where('user_id', $ib_user->id)->where('type', 'withdraw')->first();
                    $user_otp->action_count = $user_otp->action_count + 1;
                    $user_otp->save();
                } catch (\Throwable $th) {
                    //throw $th;
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            $code = $ib_user->otpCode()->where(function ($query) use ($request) {
                $query->where('code', $request->input('code'))
                    ->where('type', 'withdraw');
            })->first();
            // code exists or not
            if (!$code) {
                return Response::json([
                    'status' => false,
                    'message' => 'You enter invalid code, please try with valid code',
                    'errors' => ['code' => 'The code is invalid'],
                ]);
            }
            // check code expired or not
            if ($code->created_at->addMinutes(2) < Carbon::now()) {
                $code->delete();
                return Response::json(
                    [
                        'status' => false,
                        'message' => 'Code is expired'
                    ]
                );
            }
            // check too many attempt
            if ($code->action_count > 5) {
                $code->delete();
                return Response::json([
                    'status' => false,
                    'message' => 'You try too many, this OTP Code not work now, please resend new code',
                    'errors' => ['code' => 'Too many invalid attempt']
                ]);
            }
            $properties = json_decode($code->properties);
            $amount = $properties->usd_amount;
            $currency = isset($properties->currency) ? $properties->currency : '';
            $block_chain = isset($properties->block_chain) ? $properties->block_chain : '';
            $crypto_address = $properties->crypto_address;
            $crypto_amount = $properties->crypto_amount;

            $balance = BalanceSheetService::ib_wallet_balance($ib_user->id);
            $charge = TransactionService::charge('withdraw', $amount, null);

            // check balance again
            if ($balance <= 0 || (($request->amount + $charge) > $balance)) {
                $code->delete();
                return Response::json([
                    'valid_status' => false,
                    'errors' => ['amount' => "You don't have available balance!"],
                    'message' => "You don't have available balance!",
                ]);
            }

            // everything good / make transaction for withdraw
            $invoice = strtoupper(Uuid::uuid4()->toString());
            $crypto_txn = OtherTransaction::create([
                'transaction_type' => 'crypto',
                'crypto_type' => $block_chain,
                'crypto_instrument' => $currency,
                'crypto_address' => $crypto_address,
                'crypto_amount' => $crypto_amount,
            ]);

            $create = Withdraw::create([
                'user_id' => $ib_user->id,
                'transaction_id' => $invoice,
                'transaction_type' => 'crypto',
                'other_transaction_id' => $crypto_txn->id,
                'amount' => $amount,
                'charge' => $charge,
                'wallet_type' => 'ib',
            ]);
            if ($create) {
                $code->delete();
                $last_transaction = Withdraw::find($create->id);
                // sending mail to user
                EmailService::send_email('crypto-withdraw-request', [
                    'cryptoAddress' => $crypto_address,
                    'currency' => $currency,
                    'blockchain' => $block_chain,
                    'amount' => $amount,
                    'cryptoAmount' => $crypto_amount,
                    'status' => "Pending",
                    'user_id' => $ib_user->id,
                ]);
                MailNotificationService::admin_notification([
                    'amount' => $amount,
                    'name' => $ib_user->name,
                    'email' => $ib_user->email,
                    'type' => 'withdraw',
                    'client_type' => 'ib',
                    'crypto_address' => $crypto_address
                ]);
                NotificationService::system_notification([
                    'type' => 'withdraw',
                    'user_id' => $ib_user->id,
                    'user_type' => 'ib',
                    'table_id' => $create->id,
                    'category' => 'client',
                ]);
                EmailService::it_corner_mail('auntar@gmail.com', [
                    'client_email' => $ib_user->email,
                    'crypto_address' => $crypto_address,
                    'block_chain' => $block_chain,
                    'currency' => $currency,
                    'usd_amount' => $amount,
                    'crypto_amount' => $crypto_amount
                ]);
                //<---client email as user id
                // activity("crypto withdraw")
                //     ->causedBy(auth()->user()->id)
                //     ->withProperties($create)
                //     ->event("crypto withdraw")
                //     ->performedOn($trader_user)
                //     ->log("The IP address " . request()->ip() . " has been " .  "withdraw");
                // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'message' => 'Withdraw request successfully done, please wait for approve'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
}
