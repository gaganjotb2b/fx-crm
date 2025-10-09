<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\OtpCode;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\OtpService;
use App\Services\systems\AdminLogService;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Input\Input;

class IbWithdrawController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bank_id' => 'required|numeric|exists:bank_accounts,id',
                'amount' => 'required|numeric|min:1',
                'pin' => 'required'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'otp_status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // check the bank account is authenticated
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $bank = $ib_user->bankAccount()->where(function ($query) use ($request) {
                $query->where('id', $request->input('bank_id'))
                    ->where('approve_status', 'a')
                    ->where(function ($inquery) {
                        $inquery->where('status', 1)
                            ->orWhere('status', 0);
                    });
            })->first();
            if (!$bank) {
                return Response::json([
                    'status' => false,
                    'otp_status' => false,
                    'message' => 'Validation error, please fix the following errors',
                    'errors' => ['bank_id' => 'The bank account not found for this user, choose another bank']
                ]);
            }
            // check minimum withdraw
            if (BalanceService::check_minimum_withdraw($request->input('amount')) == false) {
                $min_withdraw = BalanceService::min_withdraw_amount();
                return Response::json([
                    'valid_status' => false,
                    'otp_status' => false,
                    'errors' => ['amount' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"],
                    'message' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"
                ]);
            }
            // check maximium withdraw
            if (BalanceService::check_max_withdraw($request->input('amount')) == false) {
                $max_withdraw = BalanceService::max_withdraw_amount();
                return Response::json([
                    'valid_status' => false,
                    'otp_status' => false,
                    'errors' => ['amount' => 'Maximum withdraw amount should be &dollar;' . $max_withdraw],
                    'message' => 'Maximum withdraw amount should be &dollar;' . $max_withdraw
                ]);
            }
            // check IB wallet balance
            $balance = BalanceSheetService::ib_wallet_balance($ib_user->id, $request->input('amount'));
            if ($balance <= 0 || $balance < $request->input('amount')) {
                return Response::json([
                    'status' => false,
                    'otp_status' => false,
                    'message' => "In your IB wallet don't have available balance to withdraw",
                    'amount' => 'You dont have available balance'
                ]);
            }
            // check transaction pin
            if (!Hash::check($request->input('pin'), $ib_user->transaction_password)) {
                return Response::json([
                    'valid_status' => false,
                    'otp_status' => false,
                    'message' => 'Please fix the following errors.',
                    'errors' => ['pin' => 'IB Transaction pin Not match!']
                ]);
            }
            // check otp is activated or not
            if (OtpService::has_otp('withdraw')) {
                // sending otp code
                $ib_user->otpCode()->delete();
                $data = [
                    'code' => mt_rand(100000, 999999),
                    'user_id' => $ib_user->id,
                    'bank_id' => $request->input('bank_id'),
                    'amount' => $request->input('amount'),
                    'type' => 'withdraw',
                ];
                $code_data = OtpCode::create($data);
                EmailService::send_email('otp-verification', [
                    'account_email' => $ib_user->email,
                    'otp' => $data['code'],
                    'user_id' => $ib_user->id,
                    'name' => $ib_user->name,
                ]);
                return Response::json([
                    'status' => true,
                    'otp_status' => true,
                    'message' => 'We sending the OTP code to your email, please check your mail',
                ]);
            }
            $invoice = strtoupper(Uuid::uuid4());
            $charge = TransactionService::charge('withdraw', $request->amount, null);
            $created = Withdraw::create([
                'user_id' => $ib_user->id,
                'transaction_id' => $invoice,
                'bank_account_id' => $request->input('bank_id'),
                'amount' => $request->input('amount'),
                'charge' => $charge,
                'approved_status' => 'P',
                'transaction_type' => 'bank',
                'currency' => $request->input('currency') ?? "",
                'wallet_type' => 'ib',
                'local_currency' => $request->input('currency_amount') ?? 0,
                'client_log' => AdminLogService::admin_log('withdraw from mobile app'),
                'note' => 'Withdraw from mobile app'
            ]);
            if ($created) {
                // $last_transaction = $created;
                $last_transaction = Withdraw::where('id', $created->id)->with(['bank', 'otherTransaction'])->first();
                $ib_wallet_balance = BalanceSheetService::ib_wallet_balance($ib_user->id);
                MailNotificationService::admin_notification([
                    'amount' => $request->input('amount'),
                    'name' => $ib_user->name,
                    'email' => $ib_user->email,
                    'type' => 'withdraw',
                    'client_type' => 'ib'
                ]);
                // sending mail to
                EmailService::send_email('withdraw-request', [
                    'clientWithdrawAmount'      => $request->input('amount'),
                    'user_id'                   => $ib_user->id,
                    'deposit_method'            => ($last_transaction) ? ucwords($last_transaction->transaction_type) : '',
                    'deposit_date'              => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance'          => ($ib_wallet_balance) + ($last_transaction->amount),
                    'approved_amount'           => $last_transaction->amount,
                    'total_balance'             => $ib_wallet_balance
                ]);
                // insert activity-----------------
                //<---client email as user id
                activity("ib bank withdraw from mobile app")
                    ->causedBy($ib_user)
                    ->withProperties($created)
                    ->event("bank withdraw")
                    ->performedOn($created)
                    ->log("The IP address " . request()->ip() . " has been " . "withdraw");
                // end activity log----------------->>
                return ([
                    'status' => true,
                    'otp_status' => false,
                    'submit_wait' => submit_wait('bank-withdraw', 15),
                    'message' => 'Withdraw Request successfully submited.',
                    'last_transaction' => $last_transaction
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'otp_status' => false,
                'message' => 'Got a server error, please contact for support',
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
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $code = $ib_user->otpCode()->where(function ($query) use ($request) {
                $query->where('code', $request->input('code'))
                    ->where('type', 'withdraw');
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
            if ($code->created_at->addMinutes(5) < Carbon::now()) {
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
            // now make a bank withdraw
            // check now balance is available or not
            $balance = BalanceSheetService::ib_wallet_balance($code->user_id, $code->amount);
            if ($balance <= 0 || $balance < $code->amount) {
                $code->delete();
                return Response::json([
                    'status' => false,
                    'otp_status' => false,
                    'message' => "In your IB wallet don't have available balance to withdraw",
                    'amount' => 'You dont have available balance'
                ]);
            }
            $invoice = strtoupper(Uuid::uuid4());
            $charge = TransactionService::charge('withdraw', $code->amount, null);
            $created = Withdraw::create([
                'user_id' => $code->user_id,
                'transaction_id' => $invoice,
                'bank_account_id' => $code->bank_id,
                'amount' => $code->amount,
                'charge' => $charge,
                'approved_status' => 'P',
                'transaction_type' => 'bank',
                'currency' =>  "",
                'wallet_type' => 'ib',
                'local_currency' => 0,
                'client_log' => AdminLogService::admin_log('withdraw from mobile app'),
                'note' => 'Withdraw from mobile app'
            ]);
            if ($created) {
                $code->delete();
                // $last_transaction = $created;
                $last_transaction = Withdraw::where('id', $created->id)->with(['bank', 'otherTransaction'])->first();
                $ib_wallet_balance = BalanceSheetService::ib_wallet_balance($code->user_id);
                MailNotificationService::admin_notification([
                    'amount' => $code->amount,
                    'name' => $ib_user->name,
                    'email' => $ib_user->email,
                    'type' => 'withdraw',
                    'client_type' => 'ib'
                ]);
                // sending mail to
                EmailService::send_email('withdraw-request', [
                    'clientWithdrawAmount'      => $request->input('amount'),
                    'user_id'                   => $ib_user->id,
                    'deposit_method'            => ($last_transaction) ? ucwords($last_transaction->transaction_type) : '',
                    'deposit_date'              => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance'          => ($ib_wallet_balance) + ($last_transaction->amount),
                    'approved_amount'           => $last_transaction->amount,
                    'total_balance'             => $ib_wallet_balance
                ]);
                // insert activity-----------------
                //<---client email as user id
                activity("ib bank withdraw from mobile app")
                    ->causedBy($ib_user)
                    ->withProperties($created)
                    ->event("bank withdraw")
                    ->performedOn($created)
                    ->log("The IP address " . request()->ip() . " has been " . "withdraw");
                // end activity log----------------->>
                return ([
                    'status' => true,
                    'otp_status' => false,
                    'message' => 'Withdraw Request successfully submited.',
                    'last_transaction' => $last_transaction
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // resend otp
    public function resend_otp()
    {
        // 
    }
    // get all ib withdraw
    public function get_ib_withdraw(Request $request)
    {
        try {
            $validation_rules = [
                'status' => 'nullable|in:approved,pending,declined',
                'method' => 'nullable|in:bank,cash,prexis,help2pay,m2pay',
                'min_amount' => 'nullable|min:0|numeric',
                'max_amount' => 'nullable|min:0|numeric',
                'date_to' => 'nullable|date',
                'date_from' => 'nullable|date'
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
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->TraderAccount()->first();
            }
            $result = Withdraw::where('user_id', $ib_user->id)->where('wallet_type', 'ib');
            // filter by status
            if ($request->input('status')) {
                $status = '';
                if (strtolower($request->input('status')) === 'approved') {
                    $status = 'A';
                } elseif (strtolower($request->input('status')) === 'pending') {
                    $status = 'P';
                } elseif (strtolower($request->input('status')) === 'declined') {
                    $status = 'D';
                }
                $result = $result->where('approved_status', $status);
            }
            // filter by min amount
            if ($request->input('min_amount')) {
                $result = $result->where('amount', '>=', $request->input('min_amount'));
            }
            if ($request->input('max_amount')) {
                $result = $result->where('amount', '<=', $request->input('max_amount'));
            }
            // filter by method
            if ($request->input('method')) {
                $result = $result->where('transaction_type', strtolower($request->input('method')));
            }
             // filter by date to
             if ($request->input('date_to')) {
                $to  = Carbon::parse($request->input('date_to'));
                $result = $result->whereDate('created_at', '<=', $to);
            }
            // filter by date from
            if ($request->input('date_from')) {
                $date_from  = Carbon::parse($request->input('date_from'));
                $result = $result->whereDate('created_at', '>=', $date_from);
            }
            $total_amount = $result->sum('amount');
            $result =  $result->paginate($request->input('per_page', 5) ?? 5);
            return Response::json([
                'status'=>true,
                'total_amount'=>$total_amount,
                'data'=>$result
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status'=>false,
                'total_amount'=>0,
                'data'=>[],
                'message'=>'Got a server error, please contact for suporrort'
            ]);
        }
    }
}
