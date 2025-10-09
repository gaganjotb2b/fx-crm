<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\OtpCode;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\BankService as ServicesBankService;
use App\Services\deposit\BankService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
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

class TraderBankWithdraw extends Controller
{
    public function bank_widraw(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $localcurrency = (ServicesBankService::is_multiCurrency('withdraw')) ? 'required' : 'nullable';
            $validator = Validator::make($request->all(), [
                'bank' => 'required|numeric|exists:bank_accounts,id',
                'amount' => 'required|numeric|min:1',
                'transaction_pin' => 'required',
                'local_amount' => "$localcurrency",
                'local_currency' => "$localcurrency"
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'validation error, please fix the following the errors',
                    'errors' => $validator->errors(),
                ]);
            }
            if (!$trader_user) {
                return Response::json([
                    'status' => false,
                    'message' => 'You dont have a trader account, please first open a trader account'
                ]);
            }
            $bank = BankAccount::where('user_id', $trader_user->id)
                ->where('id', $request->input('bank'))->first();
            if (!$bank) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Bank account not found, please choose your own bank account',
                    'errors' => ['bank' => 'Bank account not found']
                ]);
            }
            $balance = BalanceSheetService::trader_wallet_balance($trader_user->id);
            $charge = TransactionService::charge('withdraw', $request->input('amount'), null);
            if ($balance <= 0 || (($request->amount + $charge) > $balance)) {
                return Response::json([
                    'valid_status' => false,
                    'otp' => false,
                    'errors' => ['amount' => "You don't have available balance!"],
                    'message' => 'Please fix the following errors',
                ]);
            }
            // transaction password validation
            // trader
            if (!Hash::check($request->input('transaction_pin'), $trader_user->transaction_password)) {
                return Response::json([
                    'valid_status' => false,
                    'otp' => false,
                    'message' => 'Please fix the following errors.',
                    'errors' => ['transaction_pin' => 'Transaction Pin Not match!']
                ]);
            }

            if (OtpService::has_otp('withdraw', $trader_user->id)) {
                //    sending otp
                $trader_user->otpCode()->delete();
                $data = [
                    'code' => mt_rand(100000, 999999),
                    'user_id' => $trader_user->id,
                    'amount' => $request->input('amount'),
                    'type' => 'withdraw',
                    'properties' => json_encode($request->all()),
                ];
                OtpCode::create($data);
                EmailService::send_email('otp-verification', [
                    'account_email' => $trader_user->email,
                    'otp' => $data['code'],
                    'user_id' => $trader_user->id,
                    'name' => $trader_user->name,
                ]);
                return Response::json([
                    'status' => true,
                    'otp' => true,
                    'message' => 'We sending the OTP code to your email, please check your mail',
                ]);
            }
            // everything is good
            // now create a withdraw request
            $invoice = strtoupper(Uuid::uuid4()->toString());
            $created = Withdraw::create([
                'user_id' => $trader_user->id,
                'transaction_id' => $invoice,
                'bank_account_id' => $request->input('bank'),
                'amount' => $request->input('amount'),
                'charge' => $charge,
                'approved_status' => 'P',
                'transaction_type' => 'bank',
                'currency' => $request->input('local_currency'),
                'local_currency' => $request->input('amount_local', 0),
                'created_by' => 'system',
                'wallet_type' => 'trader',
                // / for direct account deposit
                'withdraw_option' => 'wallet',
                'client_log' => AdminLogService::admin_log(), //this function return data of browser, device and ip of action platform
            ]);
            if ($created) {

                MailNotificationService::admin_notification([
                    'amount' => $request->input('amount'),
                    'name' => $trader_user->name,
                    'email' => $trader_user->email,
                    'type' => 'withdraw',
                    'client_type' => 'trader'
                ]);
                $last_transaction = Withdraw::where('id', $created->id)->with(['bank', 'otherTransaction'])->first();
                $after_balance = AllFunctionService::trader_total_balance($trader_user->id);
                EmailService::send_email('withdraw-request', [
                    'clientWithdrawAmount'      => $request->input('amount'),
                    'user_id'                   => $trader_user->id,
                    'deposit_method'            => ($last_transaction) ? ucwords($last_transaction->transaction_type) : '',
                    'deposit_date'              => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance'          => (($after_balance) + ($last_transaction->amount)),
                    'approved_amount'           => $last_transaction->amount,
                    'total_balance'             => $after_balance
                ]);
                $last_transaction->balance = $after_balance;
                NotificationService::system_notification([
                    'type' => 'withdraw',
                    'user_id' => $trader_user->id,
                    'user_type' => 'trader',
                    'table_id' => $created->id,
                    'category' => 'client',
                ]);
                // <---client email as user id
                $user = User::find(auth()->user()->id);
                activity("bank withdraw")
                    ->causedBy($user)
                    ->withProperties($created)
                    ->event("bank withdraw")
                    ->performedOn($created)
                    ->log("The IP address " . request()->ip() . " has been " . "withdraw");
                // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'otp' => false,
                    'last_transaction' => $last_transaction,
                    'message' => 'Bank withdraw request successfully send'
                ]);
            }
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Somthing went wrong, please try agian later!.'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Got a server error, please contact for support!.'
            ]);
        }
    }
    // check otp code 
    public function otp_check(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $validator = Validator::make($request->all(), [
                'code' => 'required|exists:otp_codes,code'
            ]);
            if ($validator->fails()) {
                try {
                    $user_otp = OtpCode::where('user_id', $trader_user->id)->where('type', 'withdraw')->first();
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
            $code = $trader_user->otpCode()->where(function ($query) use ($request) {
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
                    ],
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
            $amount = $properties->amount;
            $bank_id = $properties->bank;
            $currency = isset($properties->local_currency) ? $properties->local_currency : '';
            $currency_value = isset($properties->local_amount) ? $properties->local_amount : 0;
            $bank = BankAccount::where('id', $bank_id)->where('user_id', $trader_user->id)->first();
            // finding the bank account
            if (!$bank) {
                $code->delete();
                return Response::json([
                    'status' => false,
                    'message' => 'Bank account not found, please try again'
                ]);
            }
            $balance = BalanceSheetService::trader_wallet_balance($trader_user->id);
            $charge = TransactionService::charge('withdraw', $amount, null);
            // check balance again
            if ($balance <= 0 || (($request->amount + $charge) > $balance)) {
                $code->delete();
                return Response::json([
                    'valid_status' => false,
                    'otp' => false,
                    'errors' => ['amount' => "You don't have available balance!"],
                    'message' => 'Please fix the following errors',
                ]);
            }
            // everything good / make transaction for withdraw
            $invoice = strtoupper(Uuid::uuid4()->toString());
            $created = Withdraw::create([
                'user_id' => $trader_user->id,
                'transaction_id' => $invoice,
                'bank_account_id' => $bank_id,
                'amount' => $amount,
                'charge' => $charge,
                'approved_status' => 'P',
                'transaction_type' => 'bank',
                'currency' => $currency,
                'local_currency' => $currency_value,
                'created_by' => 'system',
                'wallet_type' => 'trader',
                // / for direct account deposit
                'withdraw_option' => 'wallet',
                'client_log' => AdminLogService::admin_log(), //this function return data of browser, device and ip of action platform
            ]);
            if ($created) {
                MailNotificationService::admin_notification([
                    'amount' => $amount,
                    'name' => $trader_user->name,
                    'email' => $trader_user->email,
                    'type' => 'withdraw',
                    'client_type' => 'trader'
                ]);
                $last_transaction = Withdraw::where('id', $created->id)->with(['bank', 'otherTransaction'])->first();
                $after_balance = AllFunctionService::trader_total_balance($trader_user->id);
                EmailService::send_email('withdraw-request', [
                    'clientWithdrawAmount'      => $amount,
                    'user_id'                   => $trader_user->id,
                    'deposit_method'            => ($last_transaction) ? ucwords($last_transaction->transaction_type) : '',
                    'deposit_date'              => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance'          => (($after_balance) + ($last_transaction->amount)),
                    'approved_amount'           => $last_transaction->amount,
                    'total_balance'             => $after_balance
                ]);
                $last_transaction->balance = $after_balance;
                NotificationService::system_notification([
                    'type' => 'withdraw',
                    'user_id' => $trader_user->id,
                    'user_type' => 'trader',
                    'table_id' => $created->id,
                    'category' => 'client',
                ]);
                // <---client email as user id
                $user = User::find(auth()->user()->id);
                activity("bank withdraw")
                    ->causedBy($user)
                    ->withProperties($created)
                    ->event("bank withdraw")
                    ->performedOn($created)
                    ->log("The IP address " . request()->ip() . " has been " . "withdraw");
                // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'otp' => false,
                    'last_transaction' => $last_transaction,
                    'message' => 'Bank withdraw request successfully send'
                ], 201);
            }
            return Response::json([
                'status' => false,
                'message' => 'something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, pelase contact for support'
            ]);
        }
    }
}
