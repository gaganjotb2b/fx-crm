<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExternalFundTransfers;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\balance\BalanceSheetService;
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
use PayPal\Api\ExternalFunding;
use Ramsey\Uuid\Uuid;

class IbBalanceTransferController extends Controller
{
    public function ib_to_trader(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'trader_id' => 'required|exists:users,id',
                'amount' => 'required|numeric|min:1',
                'pin' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Validation error, please ',
                    'errors' => $validator->errors(),
                ]);
            }
            $user = User::find(auth()->guard('api')->user()->id);
            $receiver = User::find($request->input('trader_id'));
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            // return $ib_user;
            if (!$ib_user) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'You dont have an IB account, please create one'
                ]);
            }
            // check the balance availalbe or not
            $balance = BalanceSheetService::ib_wallet_balance($ib_user->id);
            // return $balance;
            if ($balance <= 0 || $balance < $request->input('amount')) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'You dont have available balance for transfer',
                ]);
            }
            // pin conde validation
            if (!Hash::check($request->input('pin'), $ib_user->transaction_password)) {
                return ([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Please fix the following errors.',
                    'errors' => ['pin' => 'Transaction pin Not match!']
                ]);
            }
            $charge = TransactionService::charge('w_to_w', $request->amount, null);
            $data = [
                'sender_id' => $ib_user->id,
                'receiver_id' => $request->input('trader_id'),
                'amount' => $request->input('amount'),
                'charge' => $charge,
                'type' => 'ib_to_trader',
                'status' => 'P',
                'txnid' => strtoupper(Uuid::uuid4()->toString()),
                'sender_wallet_type' => 'ib',
                'receiver_wallet_type' => 'trader',
                'ip_address' => request()->ip,
            ];
            // check otp for ib account
            if (OtpService::has_otp('transfer', $ib_user->id)) {
                $ib_user->otpCode()->delete();
                $data = [
                    'code' => mt_rand(100000, 999999),
                    'user_id' => $ib_user->id,
                    'amount' => $request->input('amount'),
                    'type' => 'ib_to_trader',
                    'properties' => json_encode($data)
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
                    'otp' => true,
                    'message' => 'We sending the OTP code to your email, please check your mail',
                ]);
            }
            // if otp false direct create the transfer
            $create = ExternalFundTransfers::create($data);
            if ($create) {
                MailNotificationService::admin_notification([
                    'amount' => $request->input('amount'),
                    'name' => $ib_user->name,
                    'email' => $ib_user->email,
                    'type' => 'balance transfer',
                    'client_type' => 'ib'
                ]);
                // get last transaction----------------
                $last_transaction = TransactionService::last_transaction($ib_user->id, 'ib_to_trader');
                // return $last_transaction;
                // send mail to client
                EmailService::send_email('ib-to-trader-transfer', [
                    'clientWithdrawAmount'      => $request->input('amount'),
                    'user_id' => auth()->user()->id,
                    'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance' => (($balance) + ($last_transaction->amount)),
                    'transfer_amount' => $last_transaction->amount,
                    'total_balance' => $balance,
                    'reciever_name' => ucwords($receiver->name),
                    'reciever_email' => $receiver->email,
                ]);
                // make system notification
                NotificationService::system_notification([
                    'type' => 'ib_to_trader',
                    'user_id' => $ib_user->id,
                    'user_type' => 'ib',
                    'table_id' => $create->id,
                    'category' => 'App\Models\ExternalFundTransfers',
                ]);
                return Response::json([
                    'status' => true,
                    'otp' => false,
                    'message' => 'Balance transfer successfully done',
                ]);
            }
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // otp check fot ib to trader transfer
    public function ib_to_trader_otp(Request $request)
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
                    ->where('type', 'ib_to_trader');
            })->first();
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
            $data = json_decode($code->properties, true);
            $create = ExternalFundTransfers::create($data);
            if ($create) {
                $receiver = User::find($data['receiver_id']);
                MailNotificationService::admin_notification([
                    'amount' => $code->amount,
                    'name' => $ib_user->name,
                    'email' => $ib_user->email,
                    'type' => 'balance transfer',
                    'client_type' => 'ib'
                ]);
                // get last transaction----------------
                $last_transaction = TransactionService::last_transaction($ib_user->id, 'ib_to_trader');
                // return $last_transaction;
                // send mail to client
                EmailService::send_email('ib-to-trader-transfer', [
                    'clientWithdrawAmount'      => $code->amount,
                    'user_id' => $ib_user->id,
                    'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance' => (($balance) + ($last_transaction->amount)),
                    'transfer_amount' => $last_transaction->amount,
                    'total_balance' => $balance,
                    'reciever_name' => ucwords($receiver->name),
                    'reciever_email' => $receiver->email,
                ]);
                // make system notification
                NotificationService::system_notification([
                    'type' => 'ib_to_trader',
                    'user_id' => $ib_user->id,
                    'user_type' => 'ib',
                    'table_id' => $create->id,
                    'category' => 'App\Models\ExternalFundTransfers',
                ]);
                return Response::json([
                    'status' => true,
                    'otp' => false,
                    'message' => 'Balance transfer successfully done',
                ]);
            }
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // start IB to IB transfer
    // *************************************************************
    public function ib_to_ib(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ib_id' => 'required|exists:users,id',
                'amount' => 'required|numeric|min:1',
                'pin' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Validation error, please ',
                    'errors' => $validator->errors(),
                ]);
            }
            $user = User::find(auth()->guard('api')->user()->id);
            $receiver = User::find($request->input('ib_id'));
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            // check the receiver is ib or not
            if (strtolower($receiver->type) !== 'ib') {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'The selected user is not an IB, please choose a IB account',
                ]);
            }
            // receiver is self ib account
            if ($receiver->id == $ib_user->id) {
                return Response::json([
                    'status' => false,
                    'message' => 'You cannot transfer to your self IB account, you can try IB to Trader to your self'
                ]);
            }
            // return $ib_user;
            if (!$ib_user) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'You dont have an IB account, please create one'
                ]);
            }
            // check the balance availalbe or not
            $balance = BalanceSheetService::ib_wallet_balance($ib_user->id);
            // return $balance;
            if ($balance <= 0 || $balance < $request->input('amount')) {
                return Response::json([
                    'status' => false,
                    'otp' => false,
                    'message' => 'You dont have available balance for transfer',
                ]);
            }
            // pin code validation
            if (!Hash::check($request->input('pin'), $ib_user->transaction_password)) {
                return ([
                    'status' => false,
                    'otp' => false,
                    'message' => 'Please fix the following errors.',
                    'errors' => ['pin' => 'Transaction pin Not match!']
                ]);
            }
            $charge = TransactionService::charge('w_to_w', $request->amount, null);
            $data = [
                'sender_id' => $ib_user->id,
                'receiver_id' => $request->input('ib_id'),
                'amount' => $request->input('amount'),
                'charge' => $charge,
                'type' => 'ib_to_ib',
                'status' => 'P',
                'txnid' => strtoupper(Uuid::uuid4()->toString()),
                'sender_wallet_type' => 'ib',
                'receiver_wallet_type' => 'ib',
                'ip_address' => request()->ip,
            ];
            // check otp for ib account
            if (OtpService::has_otp('transfer', $ib_user->id)) {
                $ib_user->otpCode()->delete();
                $data = [
                    'code' => mt_rand(100000, 999999),
                    'user_id' => $ib_user->id,
                    'amount' => $request->input('amount'),
                    'type' => 'ib_to_ib',
                    'properties' => json_encode($data)
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
                    'otp' => true,
                    'message' => 'We sending the OTP code to your email, please check your mail',
                ]);
            }
            // if otp false direct create the transfer
            $create = ExternalFundTransfers::create($data);
            if ($create) {
                MailNotificationService::admin_notification([
                    'amount' => $request->input('amount'),
                    'name' => $ib_user->name,
                    'email' => $ib_user->email,
                    'type' => 'balance transfer',
                    'client_type' => 'ib'
                ]);
                // get last transaction----------------
                $last_transaction = TransactionService::last_transaction($ib_user->id, 'ib_to_ib');
                // return $last_transaction;
                // send mail to client
                EmailService::send_email('ib-to-ib-transfer', [
                    'clientWithdrawAmount'      => $request->input('amount'),
                    'user_id' => auth()->user()->id,
                    'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance' => (($balance) + ($last_transaction->amount)),
                    'transfer_amount' => $last_transaction->amount,
                    'total_balance' => $balance,
                    'reciever_name' => ucwords($receiver->name),
                    'reciever_email' => $receiver->email,
                ]);
                // make system notification
                NotificationService::system_notification([
                    'type' => 'ib_to_ib',
                    'user_id' => $ib_user->id,
                    'user_type' => 'ib',
                    'table_id' => $create->id,
                    'category' => 'App\Models\ExternalFundTransfers',
                ]);
                return Response::json([
                    'status' => true,
                    'otp' => false,
                    'message' => 'Balance transfer successfully done',
                ]);
            }
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    public function ib_to_ib_otp(Request $request)
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
                    ->where('type', 'ib_to_ib');
            })->first();
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
            $data = json_decode($code->properties, true);
            $create = ExternalFundTransfers::create($data);
            if ($create) {
                $receiver = User::find($data['receiver_id']);
                MailNotificationService::admin_notification([
                    'amount' => $code->amount,
                    'name' => $ib_user->name,
                    'email' => $ib_user->email,
                    'type' => 'balance transfer',
                    'client_type' => 'ib'
                ]);
                // get last transaction----------------
                $last_transaction = TransactionService::last_transaction($ib_user->id, 'ib_to_ib');
                // return $last_transaction;
                // send mail to client
                EmailService::send_email('ib-to-ib-transfer', [
                    'clientWithdrawAmount'      => $code->amount,
                    'user_id' => $ib_user->id,
                    'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                    'previous_balance' => (($balance) + ($last_transaction->amount)),
                    'transfer_amount' => $last_transaction->amount,
                    'total_balance' => $balance,
                    'reciever_name' => ucwords($receiver->name),
                    'reciever_email' => $receiver->email,
                ]);
                // make system notification
                NotificationService::system_notification([
                    'type' => 'ib_to_ib',
                    'user_id' => $ib_user->id,
                    'user_type' => 'ib',
                    'table_id' => $create->id,
                    'category' => 'App\Models\ExternalFundTransfers',
                ]);
                return Response::json([
                    'status' => true,
                    'otp' => false,
                    'message' => 'Balance transfer successfully done',
                ]);
            }
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'otp' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
}
