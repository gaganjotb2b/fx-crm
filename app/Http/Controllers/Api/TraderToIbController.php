<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExternalFundTransfers;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\balance\BalanceSheetService;
use App\Services\CombinedService;
use App\Services\EmailService;
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

class TraderToIbController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $user = User::find(auth()->guard('api')->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $validtor = Validator::make($request->all(), [
                'receiver' => 'required|exists:users,id',
                'amount' => 'required|numeric|min:1',
                'transaction_pin' => 'required',
            ]);
            if ($validtor->fails()) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Validatoin error, please fix the following errors',
                    'errors' => $validtor->errors(),
                ]);
            }
            $charge = TransactionService::charge('w_to_w', $request->input('amount'), null);
            $balance = BalanceSheetService::trader_wallet_balance($trader_user->id);
            // check available balance---------
            if ($balance <= 0 || ($request->input('amount') + $charge) > $balance) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'You dont have available balance',
                    'errors' => ['amount' => 'You dont have available balance']
                ]);
            }
            // check transaction pin
            if (!Hash::check($request->input('transaction_pin'), $trader_user->transaction_password)) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Transaction Pin Not match!',
                    'errors' => ['transaction_pin' => 'Transaction Password Not match!']
                ]);
            }
            // receiver check is an IB or Not
            $receiver = User::where('id', $request->input('receiver'));
            if (CombinedService::is_combined()) {
                $receiver = $receiver->where('type', '0')->where('combine_access', 1);
            } else {
                $receiver = $receiver->where('type', '4');
            }
            $receiver = $receiver->select('id', 'email', 'name')->first();
            if (!$receiver) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Trader receiver not found, please choose a valid trader',
                    'errors' => ['receiver' => 'Receiver is not a trader']
                ]);
            }
            if (OtpService::has_otp('transfer')) {
                // we sending otp to client email
                $trader_user->otpCode()->delete();
                $data = [
                    'code' => mt_rand(100000, 999999),
                    'user_id' => $trader_user->id,
                    'amount' => $request->input('amount'),
                    'type' => 'trader_to_ib',
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
            // everithing is good
            else {
                $invoice = strtoupper(Uuid::uuid4()->toString());
                $created = ExternalFundTransfers::create([
                    'txnid' => $invoice,
                    'sender_id' => $trader_user->id,
                    'receiver_id' => $receiver->id,
                    'amount' => $request->input('amount'),
                    'charge' => $charge,
                    'type' => 'trader_to_ib',
                    'status' => 'P',
                    'sender_wallet_type' => 'trader', // sender wallet type
                    'receiver_wallet_type' => 'ib' // receiver wallet type
                ]);
                if ($created) {
                    MailNotificationService::admin_notification([
                        'amount' => $request->input('amount'),
                        'name' => $trader_user->name,
                        'email' => $trader_user->email,
                        'type' => 'balance transfer',
                        'client_type' => 'trader'
                    ]);
                    $last_transaction = ExternalFundTransfers::where('sender_id', $trader_user->id)
                        ->where('type', 'trader_to_ib')->latest()->first();
                    EmailService::send_email('trader-to-ib-transfer', [
                        'user_id' => $trader_user->id,
                        'clientDepositAmount' => $request->input('amount'),
                        'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                        'previous_balance' => ((BalanceSheetService::trader_wallet_balance($user->id)) + ($last_transaction->amount)),
                        'transfer_amount' => $last_transaction->amount,
                        'total_balance' => BalanceSheetService::trader_wallet_balance($user->id),
                        'reciever_name' => ucwords($receiver->name),
                        'reciever_email' => $receiver->email,
                    ]);
                    NotificationService::system_notification([
                        'type' => 'tradr_to_trader',
                        'user_id' => $trader_user->id,
                        'user_type' => 'trader',
                        'table_id' => $created->id,
                        'category' => 'client',
                    ]);
                    $user = User::find(auth()->user()->id);
                    //<---client email as user id
                    // activity("trader to ib")
                    //     ->causedBy(auth()->user()->id)
                    //     ->withProperties($created)
                    //     ->event("trader to ib")
                    //     ->performedOn($user)
                    //     ->log("The IP address " . request()->ip() . " has been trader to ib transfer");
                    // end activity log----------------->>
                    return Response::json([
                        'status' => true,
                        'otp' => false,
                        'message' => 'Transaction successfully done',
                    ], 201);
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
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // otp check / verification
    public function otp_check(Request $request)
    {
        try {
            $user = User::find(auth()->guard('api')->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $validator = Validator::make($request->all(), [
                'code' => 'required|exists:otp_codes,code'
            ]);
            if ($validator->fails()) {
                try {
                    $user_otp = OtpCode::where('user_id', $trader_user->id)
                        ->where('type', 'trader_to_ib')->first();
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
                    ->where('type', 'trader_to_ib');
            })->first();
            $properties = json_decode($code->properties);
            if (!$code) {
                return Response::json([
                    'status' => false,
                    'message' => 'You enter invalid code, please try with valid code',
                    'errors' => ['code' => 'The code is invalid'],
                ]);
            }
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
            // check too many attempt
            if ($code->action_count > 3) {
                $code->delete();
                return Response::json([
                    'status' => false,
                    'message' => 'You try too many, this OTP Code not work now, please resend new code',
                    'errors' => ['code' => 'Too many invalid attempt']
                ]);
            }
            // check balance again
            $amount = $properties->amount;
            $charge = TransactionService::charge('w_to_w', $properties->amount, null);
            $balance = BalanceSheetService::trader_wallet_balance($trader_user->id);
            $receiver = $properties->receiver;
            $receiver = User::find($receiver);
            if ($balance <= 0 || ($amount + $charge) > $balance) {
                $code->delete();
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'You dont have available balance',
                    'errors' => ['amount' => 'You dont have available balance']
                ]);
            }
            // everything good
            // now make a transactions
            $invoice = strtoupper(Uuid::uuid4()->toString());
            $created = ExternalFundTransfers::create([
                'txnid' => $invoice,
                'sender_id' => $trader_user->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
                'charge' => $charge,
                'type' => 'trader_to_ib',
                'status' => 'P',
                'sender_wallet_type' => 'trader', // sender wallet type
                'receiver_wallet_type' => 'ib' // receiver wallet type
            ]);
            if ($created) {
                $code->delete();
                MailNotificationService::admin_notification([
                    'amount' => $amount,
                    'name' => $trader_user->name,
                    'email' => $trader_user->email,
                    'type' => 'balance transfer',
                    'client_type' => 'trader'
                ]);
                $last_transaction = ExternalFundTransfers::where('sender_id', $trader_user->id)
                    ->where('type', 'trader_to_ib')->latest()->first();
                EmailService::send_email('trader-to-ib-transfer', [
                    'user_id' => $trader_user->id,
                    'clientDepositAmount' => $amount,
                    'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance' => ((BalanceSheetService::trader_wallet_balance($user->id)) + ($last_transaction->amount)),
                    'transfer_amount' => $last_transaction->amount,
                    'total_balance' => BalanceSheetService::trader_wallet_balance($user->id),
                    'reciever_name' => ucwords($receiver->name),
                    'reciever_email' => $receiver->email,
                ]);
                NotificationService::system_notification([
                    'type' => 'tradr_to_ib',
                    'user_id' => $trader_user->id,
                    'user_type' => 'trader',
                    'table_id' => $created->id,
                    'category' => 'client',
                ]);
                $user = User::find(auth()->user()->id);
                //<---client email as user id
                // activity("trader to ib")
                //     ->causedBy(auth()->user()->id)
                //     ->withProperties($created)
                //     ->event("trader to ib")
                //     ->performedOn($user)
                //     ->log("The IP address " . request()->ip() . " has been trader to ib transfer");
                // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'otp' => false,
                    'message' => 'Transaction successfully done',
                ], 201);
            }
            return Response::json([
                'status' => false,
                'otp' => false,
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
