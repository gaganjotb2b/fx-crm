<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminBank;
use App\Models\BankAccount;
use App\Models\Deposit;
use App\Models\User;
use App\Services\api\FileApiService;
use App\Services\balance\BalanceSheetService;
use App\Services\BankService;
use App\Services\deposit\BankDepositService;
use App\Services\deposit\BankService as DepositBankService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;
use App\Services\systems\NotificationService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class BankDepositController extends Controller
{
    // bank deposit from api request
    public function bank_deposit(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) == 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $validation_rules = [
                'amount' => 'required|numeric',
                'file_document' => 'required|mimes:jpeg,png,pdf|max:2048',
                'bank_id' => 'required|exists:admin_banks,id'
            ];
            if (BankService::is_multicurrency('all')) {
                $validation_rules['local_amount'] = 'required|numeric';
            }
            $validator = Validator::make($request->all(), $validation_rules);
            // bank deposit 
            // trader/ default validation check
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following errors!'
                ]);
            }
            // trader bank deposit 
            // file type validation check
            $file_document = $request->file('file_document');
            $fileExtension = $file_document->getClientOriginalExtension();
            $filename = Uuid::uuid4()->toString() . time() . '_bank_proof_' . join_app_name() . '.' . $fileExtension;
            $client = FileApiService::s3_clients();
            $client->putObject([
                'Bucket' => FileApiService::contabo_bucket_name(),
                'Key' => $filename,
                'Body' => file_get_contents($file_document)
            ]);

            $charge = TransactionService::charge('deposit', $request->input('amount'), $trader_user->id);
            $invoice = Uuid::uuid4()->toString();
            // check minimum deposit
            $bank = AdminBank::find($request->input('bank_id'));
            if ($bank->minimum_deposit > $request->input('amount')) {
                return Response::json([
                    'status' => false,
                    'errors' => ['amount' => "Minimum &dollar;" . $bank->minimum_deposit . " is required for bank deposit!"],
                    'message' => ['amount' => "Minimum &dollar;" . $bank->minimum_deposit . " is required for bank deposit!"]
                ]);
            }
            $create = Deposit::create([
                'user_id' => $trader_user->id,
                'invoice_id' => $invoice,
                'transaction_type' => 'bank',
                'amount' => $request->input('amount'),
                'charge' =>  $charge,
                'approved_status' => 'P',
                'ip_address' => request()->ip(),
                'bank_proof' => $filename,
                'bank_id' => $request->input('bank_id'),
                'currency' => $request->input('currency') ?? "",
                'local_currency' => $request->input('local_amount') ?? 0,
                // for direct account deposit
                'wallet_type' => 'trader',
                'deposit_option' => 'wallet',
                'client_log' => AdminLogService::admin_log(),
            ]);
            $balance = BalanceSheetService::trader_wallet_balance($trader_user->id);
            if ($create) {
                EmailService::send_email('bank-deposit-request', [
                    'clientWithdrawAmount'      => $request->amount,
                    'user_id' => $trader_user->id,
                    'deposit_status' => 'Pending',
                    'previous_balance' => $balance,
                    'request_amount' => $request->input('amount'),
                    'deposit_method' => 'Bank'
                ]);
                NotificationService::system_notification([
                    'type' => 'deposit',
                    'user_type' => 'trader',
                    'user_id' => $trader_user->id,
                    'table_id' => $create,
                    'category' => 'client'
                ]);
                MailNotificationService::admin_notification([
                    'amount' => $request->input('amount'),
                    'name' => $trader_user->name,
                    'email' => $trader_user->email,
                    'type' => 'deposit',
                    'client_type' => 'trader'
                ]);
                //<---client email as user id
                //     activity("Bank deposit")
                //     ->causedBy(auth()->user()->id)
                //     ->withProperties($request->all())
                //     ->event("Bank deposit")
                //     ->performedOn($user)
                //     ->log("The IP address " . request()->ip() . " has been " .  "request a bank deposit");
                // // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'message' => 'Bank deposit successfully done'
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
    // get client bank
    public function get_client_bank(Request $request)
    {
        try {
            $banks = BankAccount::whereNot('approve_status', 'd')->where('user_id', auth()->user()->id)->get();
            if ($banks) {
                return ([
                    'status' => true,
                    'banks' => $banks,
                ]);
            }
            return ([
                'status' => false,
                'message' => 'No bank account available!'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return ([
                'status' => false,
                'message' => 'No bank account available!'
            ]);
        }
    }
}
