<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Mail\BankDepositRequest;
use App\Models\admin\InternalTransfer;
use App\Models\admin\SystemConfig;
use App\Models\AdminBank;
use App\Models\BankAccount;
use App\Models\Deposit;
use App\Models\KycVerification;
use App\Models\TradingAccount;
use App\Services\MailNotificationService;
use App\Models\TransactionSetting;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\BankService;
use App\Services\CurrencyUpdateService;
use App\Services\EmailService;
use App\Services\systems\AdminLogService;
use App\Services\systems\NotificationService;
use App\Services\systems\TransactionSettings;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BankDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('bank_deposit', 'trader'));
        $this->middleware(AllFunctionService::access('deposit', 'trader'));
    }
    //basic view----------------
    public function form_view(Request $request)
    {
        $last_transaction = Deposit::where('user_id', auth()->user()->id)->where('transaction_type', 'bank')->latest()->first();
        $banks = AdminBank::where('account_number', '<>', '')->where('status', 1)->get();
        $trading_accounts = TradingAccount::where('user_id', auth()->user()->id)->select('account_number', 'id')->get();
        return view('traders.deposit.bank-deposit', [
            'banks' => $banks,
            'last_transaction' => $last_transaction,
            'trading_accounts' => $trading_accounts,
        ]);
    }
    // bank deposit request---------------
    // trader
    public function bank_deposit(Request $request)
    {
        try {
            $validation_rules = [
                'amount' => 'required|numeric',
                'file_document' => 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:3072', // Adjust the max file size as needed (in kilobytes).
                'local_amount' => (BankService::is_multicurrency('all')) ? 'required|numeric' : 'nullable',
                'account_number' => ($request->deposit_option === 'account') ? 'required|numeric|min:1' : 'nullable'
            ];
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
            // bank selection
            if ($request->bank_id == "") {
                return Response::json([
                    'status' => false,
                    'message' => 'Please select a bank first!'
                ]);
            }
            // trader bank deposit 
            $charge = TransactionService::charge('deposit', $request->amount, null);
            // check minimum deposit
            $bank = AdminBank::find($request->bank_id);
            if ($bank->minimum_deposit > $request->amount) {
                return Response::json([
                    'status' => false,
                    'errors' => ['amount' => "Minimum &dollar;" . $bank->minimum_deposit . " is required for bank deposit!"],
                    'message' => 'Please fix the following errors!'
                ]);
            }
            // check bank id
            // bank is enble or disable
            if ($bank->status != 1) {
                return Response::json([
                    'status' => false,
                    'message' => 'The bank is not activated!'
                ]);
            }
            $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
            $bank_proof = $request->file('file_document');
            $filename = time() . '_bank_proof_' . $bank_proof->getClientOriginalName();
            // filt move to contabo
            $client = FileApiService::s3_clients();
            $client->putObject([
                'Bucket' => FileApiService::contabo_bucket_name(),
                'Key' => $filename,
                'Body' => file_get_contents($bank_proof)
            ]);
            // ****************************************
            // check if multiple deposit one
            // **************************************
            if (TransactionSettings::is_account_deposit()) {
                $trading_accounts = TradingAccount::where('id', $request->account_number)->first();
                $internal_charge = TransactionService::charge('w_to_a', $request->amount, null);
                $internal_transfer = InternalTransfer::create(
                    [
                        'user_id' => auth()->user()->id,
                        'platform' => ($trading_accounts) ? $trading_accounts->platform : '',
                        'account_id' => $request->account_number,
                        'invoice_code' => $invoice,
                        'amount' => $request->amount,
                        'charge' => $internal_charge,
                        'type' => 'wta',
                        'status' => 'P',
                        'client_log' => AdminLogService::admin_log(),
                    ]
                );
            }
            // create deposit
            $created = Deposit::create([
                'user_id' => auth()->user()->id,
                'invoice_id' => $invoice,
                'transaction_type' => 'bank',
                'amount' => $request->amount,
                'charge' =>  $charge,
                'approved_status' => 'P',
                'ip_address' => request()->ip(),
                'bank_proof' => $filename,
                'bank_id' => $request->bank_id,
                'currency' => $request->currency ?? "",
                'local_currency' => $request->local_amount ?? 0,
                // for direct account deposit
                'account' => ($request->account_number && $request->deposit_option === 'account') ? $request->account_number : null,
                'deposit_option' => ($request->deposit_option) ? $request->deposit_option : 'wallet',
                'internal_transfer' => ($request->deposit_option === 'account') ? $internal_transfer->id : null,
                'client_log' => AdminLogService::admin_log(), //this function return data of browser, device and ip of action platform
            ])->id;

            $user = User::find(auth()->user()->id);
            // if sender is trader/get trader self balance
            if (User::where('id', auth()->user()->id)->where('type', 0)->exists()) {
                $self_balance = BalanceSheetService::trader_wallet_balance(auth()->user()->id);
            }
            // else sender is an IB/get IB self balance
            else {
                $self_balance = BalanceSheetService::ib_wallet_balance(auth()->user()->id);
            }
            $emailStatus = EmailService::send_email('bank-deposit-request', [
                'clientWithdrawAmount'      => $request->amount,
                'user_id' => $user->id,
                'deposit_status' => 'Pending',
                'previous_balance' => $self_balance,
                'request_amount' => $request->amount,
                'deposit_method' => 'Bank'
            ]);
            if ($created) {
                //<---client email as user id
                activity("Bank deposit")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("Bank deposit")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been " .  "request a bank deposit");
                // end activity log----------------->>
                // send software notification
                NotificationService::system_notification([
                    'type' => 'deposit',
                    'user_type' => 'trader',
                    'user_id' => auth()->user()->id,
                    'table_id' => $created,
                    'category' => 'client'
                ]);
                //notification mail to admin
                // MailNotificationService::notification('deposit', 'trader', 1, $user->name, $request->amount);
                MailNotificationService::admin_notification([
                    'amount' => $request->amount,
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'type' => 'deposit',
                    'client_type' => 'trader'
                ]);
                $last_transaction = Deposit::find($created);
                return Response::json([
                    'status' => true,
                    'last_transaction' => $last_transaction,
                    'submit_wait' => submit_wait('bank-deposit', 60),
                    'message' => 'Deposit Request successfully submited.'
                ]);
            }
            return Response::json([
                'status' => false,
                'submit_wait' => submit_wait('bank-deposit', 60),
                'message' => 'Somthing went wrong, please try agian later!.'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
}
