<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Mail\MailNotification;
use App\Mail\OTPverificationMail;
use App\Mail\withdraw\WithdrawRequest;
use App\Models\admin\InternalTransfer;
use App\Models\admin\SystemConfig;
use App\Models\BankAccount;
use App\Models\Country;
use App\Models\Log;
use App\Models\CurrencySetup;
use App\Models\TransactionSetting;
use App\Services\MailNotificationService;
use App\Models\User;
use App\Models\OtpSetting;
use App\Models\TradingAccount;
use App\Models\UserOtpSetting;
use App\Models\Withdraw;
use App\Services\accounts\AccountService;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\CryptoWallet;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpOption\Option;
use App\Services\BankService;
use App\Services\CurrencyUpdateService;
use App\Services\EmailService;
use App\Services\IBManagementService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\OtpService;
use App\Services\systems\AdminLogService;
use App\Services\systems\TransactionSettings;

class BankWithdrawController extends Controller
{
    public function __construct()
    {
        // access control for ib bank withdraw
        if (request()->is('ib/withdraw/bank-withdraw')) {
            $this->middleware(AllFunctionService::access('bank_withdraw', 'ib'));
            $this->middleware(AllFunctionService::access('withdraw', 'ib'));
        }
        // access controll of trader bank withdraw
        elseif (request()->is('user/withdraw/bank-withdraw')) {
            $this->middleware(AllFunctionService::access('bank_withdraw', 'trader'));
            $this->middleware(AllFunctionService::access('withdraw', 'trader'));
        }
    }
    //form view---------------------------------
    public function form_view(Request $request)
    {
        $last_transaction = Withdraw::where('user_id', auth()->user()->id)->where('transaction_type', 'bank')->latest()->first();
        $banks = BankAccount::where('user_id', auth()->user()->id)
            ->where('approve_status', 'a')
            ->whereNot('status', 2)->select('bank_name')
            ->distinct()->get();
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
        $trading_accounts = TradingAccount::where('user_id', auth()->user()->id)->get();
        return view('traders.withdraw.bank-withdraw', [
            'banks' => $banks,
            'last_transaction' => $last_transaction,
            'otp_settings' => $otp_settings,
            'user_otp_settings' => $user_otp_settings,
            'trading_accounts' => $trading_accounts
        ]);
    }
    // get bank account --------------
    public function bank(Request $request)
    {
        $options = '';
        $data = [
            'bank_account_name' => '',
            'swift_code' => '',
            'country' => '',
            'address' => '',
        ];
        // operation chnage bank accounts------------
        // return true;
        if ($request->op === 'banks') {
            $bank_accounts = BankAccount::where('bank_name', $request->bank_name)->where('user_id', auth()->user()->id)->get();
            foreach ($bank_accounts as $key => $value) {
                $options .= '<option value="' . encrypt($value->id) . '">' . $value->bank_ac_number . '</option>';
            }
            if (!$bank_accounts->isEmpty()) {
                $countries = Country::all();
                $country_options = '';
                $bank_country_name = '';
                foreach ($countries as $key => $value) {
                    if ($bank_accounts[0]->bank_country == $value->id) {
                        $bank_country_name = $value->name;
                    }

                    $selected = ($bank_accounts[0]->bank_country == $value->id) ? 'selected' : '';
                    $country_options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->name . '</option>';
                }
                $currency_setup = "";
                if ($bank_accounts[0]->currency_id) {
                    $currency_setup = CurrencySetup::find($bank_accounts[0]->currency_id);
                }
                $data['bank_account_name'] = $bank_accounts[0]->bank_ac_name;
                $data['swift_code'] = $bank_accounts[0]->bank_swift_code;
                $data['iban'] = $bank_accounts[0]->bank_iban;
                $data['country'] = $country_options;
                $data['currency_name'] = $currency_setup->currency ?? "";
                $data['transaction_type'] = $currency_setup->transaction_type ?? "";
                $data['address'] = $bank_accounts[0]->bank_address;
                $data['swift_code_label'] = BankService::swift_code_label($bank_country_name);
            }
        }
        // operation bank change bank accounts
        if ($request->op === 'bank-accounts') {
            $bank_accounts = BankAccount::where('id', $request->bank_account)->where('user_id', auth()->user()->id)->first();
            if ($bank_accounts) {
                $countries = Country::all();
                $country_options = '';
                foreach ($countries as $key => $value) {
                    $selected = ($bank_accounts->bank_country == $value->id) ? 'selected' : '';
                    $country_options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->name . '</option>';
                }
                $currency_setup = "";
                if ($bank_accounts->currency_id) {
                    $currency_setup = CurrencySetup::find($bank_accounts->currency_id);
                }
                $data['bank_account_name'] = $bank_accounts->bank_ac_name;
                $data['swift_code'] = $bank_accounts->bank_swift_code;
                $data['iban'] = $bank_accounts->bank_iban;
                $data['country'] = $country_options;
                $data['currency_name'] = $currency_setup->currency ?? "";
                $data['transaction_type'] = $currency_setup->transaction_type ?? "";
                $data['address'] = $bank_accounts->bank_address;
            }
        }

        return Response::json(['bank_options' => $options, 'bank_accounts' => $data]);
    }
    // bank withdraw------------------------------
    // trader
    public function bank_withdraw(Request $request)
    {
        try {
            $amount_local = is_numeric($request->amount_local) ? $request->amount_local : 0;
            $user = User::find(auth()->user()->id);
            // start session of form submit
            // trader
            $multiple_submission = false;
            $otp_settings = OtpSetting::first();
            //charge applied here
            // trader
            $charge = TransactionService::charge('withdraw', $request->amount, null);
            $valid_res = [];
            // validation check step 1
            // trader
            $validation_rules = [
                'bank' => 'required',
                'bank_account' => 'required',
                'trading_account_number' => ($request->deposit_option === 'account') ? 'required|numeric|min:1' : 'nullable'
            ];
            // validation check step 2
            if ($request->op === 'step-2') {
                $validation_rules['country'] = 'required';
                $validation_rules['address'] = 'required';
                $validation_rules['amount'] = 'required|numeric|min:1';
                // $validation_rules['transaction_password'] = 'required';
                $validation_rules['amount_local'] = (BankService::is_multiCurrency('withdraw')) ? 'required' : 'nullable'; //validation check for multi currency
                if (check_otp(auth()->user()->id, 'withdraw') == false) {
                    $multiple_submission = has_multi_submit('bank-withdraw', 30);
                    multi_submit('bank-withdraw', 30);
                }
            }
            // validation check otp
            if ($request->op === 'step-3') {
                // $validation_rules['otp_1'] = 'required|max:1';
                // $validation_rules['otp_2'] = 'required|max:1';
                // $validation_rules['otp_3'] = 'required|max:1';
                // $validation_rules['otp_4'] = 'required|max:1';
                // $validation_rules['otp_5'] = 'required|max:1';
                // $validation_rules['otp_6'] = 'required|max:1';
                
                $validation_rules['transaction_password'] = 'required';
            }
            // return validation status
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                $valid_res['message'] = 'Please fix the following errors!';
                $valid_res['errors'] = $validator->errors();
                // return status for otp validation
                if ($request->op === 'step-3') {
                    if ($otp_settings->withdraw == true) {
                        $valid_res['otp_status'] = false;
                    } else {
                        $valid_res['otp_status'] = true;
                    }
                }
                if ($request->op === 'step-1' || $request->op === 'step-2') {
                    $valid_res['valid_status'] = false;
                }
                return Response::json($valid_res);
            }
            // check authenticated bank
            $requested_bank = BankAccount::find(decrypt($request->bank_account));
            // return $requested_bank;
            if ($requested_bank->user_id != auth()->user()->id) {
                return Response::json([
                    'valid_status' => false,
                    'message' => 'You try with invalid bank account!',
                ]);
            }
            // check pending withdraw broker need 
            $pending_withdraw = Withdraw::where('user_id', auth()->user()->id)->where('approved_status', 'P')->first();
            if($pending_withdraw){
                return Response::json([
                    'valid_status' => false,
                    'message' => 'Your previous withdraw request is pending. After approve you can send new request.',
                ]);
            }
            
            // if step 1 vaidation status true
            // trader
            if ($request->op === 'step-1') {
                $valid_res['step_1_status'] = true;
                return Response::json($valid_res);
            }
            if ($request->op === 'step-2') {
                $balance = BalanceSheetService::trader_wallet_balance(auth()->user()->id);
                // check balance available or not
                // trader
                if ($balance <= 0 || (($request->amount + $charge) > $balance)) {
                    return Response::json([
                        'valid_status' => false,
                        'errors' => ['amount' => "You don't have available balance!"],
                        'message' => 'Please fix the following errors',
                    ]);
                }

                // // transaction password validation
                // // trader
                // if (!Hash::check($request->transaction_password, $user->transaction_password)) {
                //     return Response::json([
                //         'valid_status' => false,
                //         'message' => 'Please fix the following errors.',
                //         'errors' => ['transaction_password' => 'Transaction Password Not match!']
                //     ]);
                // }
            }
            // check otp enable or disable
            // trader
            if (OtpService::has_otp('withdraw')) {
                if ($request->op === 'step-2' || $request->op === 'resend') {
                    // create otp and send otp
                    // $otp_status = OtpService::send_otp(null, 'bank-withdraw-otp');
                    // return Response::json(['otp_send' => $otp_status]);
                    
                    try{
                        $log_pass = Log::select()->where('user_id', auth()->user()->id)->first();
                        $decrypted_password = decrypt($log_pass->transaction_password);
                        //mail script
                        if ($decrypted_password) {
                            EmailService::send_email('send-transaction-pin', [
                                'user_id' => auth()->user()->id,
                                'transaction_pin'      => $decrypted_password,
                            ]);
                            return Response::json(['otp_send' => true]);
                        }
                    }catch(Exception $th){
                        return Response::json(['otp_send' => false]);
                    }
                }
            }
            // when otp stop by admin or trader self
            else {
                // check minimum withdraw
                if (BalanceService::check_minimum_withdraw($request->amount) == false) {
                    $min_withdraw = BalanceService::min_withdraw_amount();
                    return Response::json([
                        'valid_status' => false,
                        'errors' => ['amount' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"],
                        'message' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"
                    ]);
                }
                // create withdraw
                // trader
                $response = $this->trader_bank_withdraw_create([
                    'bank_account' => decrypt($request->bank_account),
                    'amount' => $request->amount,
                    'charge' => $charge,
                    'currency_name' => $request->currency_name,
                    'amount_local' => $amount_local,
                    'all' => $request->all(),
                    'trading_account' => decrypt($request->trading_account_number),
                    'withdraw_option' => $request->withdraw_option,
                    'transaction_pin' => $request->transaction_password,
                ]);

                return Response::json($response);
            }
            // final step after otp verification
            // when otp on and send to user
            if ($request->op === 'step-3') {
                // $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
                // if ($request->session()->get('bank-withdraw-otp') == $request_otp) {
                //     $time = session('otp_set_time');
                //     $minutesBeforeSessionExpire = 5;
                //     if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                //         // create withdraw
                //         // trader
                //         $response = $this->trader_bank_withdraw_create([
                //             'bank_account' => decrypt($request->bank_account),
                //             'amount' => $request->amount,
                //             'charge' => $charge,
                //             'currency_name' => $request->currency_name,
                //             'amount_local' => $amount_local,
                //             'all' => $request->all(),
                //             'trading_account' => decrypt($request->trading_account_number),
                //             'withdraw_option' => $request->withdraw_option,
                //             'transaction_pin' => $request->transaction_password,
                //         ]);

                //         return Response::json($response);
                //     } else {
                //         return Response::json([
                //             'otp_status' => false,
                //             'message' => 'OTP Time Out!'
                //         ]);
                //     }
                // }
                // return Response::json([
                //     'otp_status' => false,
                //     'message' => 'OTP not matched!'
                // ]);
                
                
                // transaction password validation
                // trader
                if (!Hash::check($request->transaction_password, $user->transaction_password)) {
                    return Response::json([
                        'valid_status' => false,
                        'message' => 'Please fix the following errors.',
                        'errors' => ['transaction_password' => 'Transaction Password Not match!']
                    ]);
                }else{
                    // create withdraw
                    // trader
                    $response = $this->trader_bank_withdraw_create([
                        'bank_account' => decrypt($request->bank_account),
                        'amount' => $request->amount,
                        'charge' => $charge,
                        'currency_name' => $request->currency_name,
                        'amount_local' => $amount_local,
                        'all' => $request->all(),
                        'trading_account' => decrypt($request->trading_account_number),
                        'withdraw_option' => $request->withdraw_option,
                        'transaction_pin' => $request->transaction_password,
                    ]);

                    return Response::json($response);
                }
            }
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error'
            ]);
        }
    }
    // trader bank withdraw create
    // trader
    public function trader_bank_withdraw_create($data)
    {
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
        // check if multiple withdraw on
        if (TransactionSettings::is_account_withdraw() && (array_key_exists('withdraw_option', $data) && strtolower($data['withdraw_option']) === 'account')) {
            $trading_accounts = TradingAccount::where('id', $data['trading_account'])->first();
            // withdraw from mt5 accounts
            if (strtolower($trading_accounts->platform) === 'mt5') {
                $mt5_api = new Mt5WebApi();
                $result = $mt5_api->execute('BalanceUpdate', [
                    "Login" => (int)$trading_accounts->account_number,
                    "Balance" => -(float)$data['amount'],
                    "Comment" => "account to wallet for direct withdraw#" . $invoice
                ]);
            }
            // withdraw from mt4 accounts
            if (strtolower($trading_accounts->platform) === 'mt4') {
                $mt4_api = new MT4API();
                // check mt4 balance
                $mt4_balance = AccountService::get_mt4_balance($trading_accounts->account_number, 'live');
                if ($data['amount'] > $mt4_balance['equity']) {
                    return ([
                        'status' => false,
                        'submit_wait' => submit_wait('atw-transfer', 60),
                        'errors' => ['amount' => "You don't have available balance!"],
                        'message' => "You don't have available balance!"
                    ]);
                }
                $data = array();
                $result = $mt4_api->execute([
                    'command' => 'deposit_funds',
                    'data' => array(
                        'account_id' => $trading_accounts->account_number,
                        'amount' => -(float)$data['amount'],
                        'comment' => "account to wallet for direct withdraw#" . $invoice
                    ),
                ], 'live');
            }
            if (!isset($result['success']) || !$result['success']) {
                return ([
                    'status' => false,
                    'message' => 'API Connection failed! please try again later.'
                ]);
            }
            $internal_charge = TransactionService::charge('w_to_a', $data['amount'], null);
            $internal_transfer = InternalTransfer::create(
                [
                    'user_id' => auth()->user()->id,
                    'platform' => ($trading_accounts) ? $trading_accounts->platform : '',
                    'account_id' => $data['trading_account'],
                    'invoice_code' => $invoice,
                    'amount' => $data['amount'],
                    'charge' => $internal_charge,
                    'type' => 'atw',
                    'status' => 'P',
                    'client_log' => AdminLogService::admin_log(),
                ]
            );
        }

        $created = Withdraw::create([
            'user_id' => auth()->user()->id,
            'transaction_id' => $invoice,
            'bank_account_id' => $data['bank_account'],
            'amount' => $data['amount'],
            'charge' => $data['charge'],
            'approved_status' => 'P',
            'transaction_type' => 'bank',
            'currency' => $data['currency_name'] ?? "",
            'local_currency' => $data['amount_local'] ?? 0,
            'created_by' => 'system',
            'wallet_type' => 'trader',
            // / for direct account deposit
            'trading_account' => ($data['trading_account'] && $data['withdraw_option'] === 'account') ? $data['trading_account'] : null,
            'withdraw_option' => ($data['withdraw_option']) ? $data['withdraw_option'] : 'wallet',
            'internal_transfer' => ($data['withdraw_option'] === 'account') ? $internal_transfer->id : null,
            'client_log' => AdminLogService::admin_log(), //this function return data of browser, device and ip of action platform
        ]);

        //mailer script
        if ($created) {
            //notification mail to admin
            // MailNotificationService::notification('withdraw', 'trader', 1, auth()->user()->name, $data['amount']);
            MailNotificationService::admin_notification([
                'amount' => $data['amount'],
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'type' => 'withdraw',
                'client_type' => 'trader'
            ]);
            // sending mail to
            $last_transaction = Withdraw::find($created->id);
            EmailService::send_email('withdraw-request', [
                'clientWithdrawAmount'      => $data['amount'],
                'user_id'                   => auth()->user()->id,
                'transaction_pin'           => $data['transaction_pin'],
                'deposit_method'            => ($last_transaction) ? ucwords($last_transaction->transaction_type) : '',
                'deposit_date'              => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                'previous_balance'          => ((AllFunctionService::trader_total_balance(auth()->user()->id)) + ($last_transaction->amount)),
                'approved_amount'           => $last_transaction->amount,
                'total_balance'             => AllFunctionService::trader_total_balance(auth()->user()->id)
            ]);

            request()->session()->forget('bank-withdraw-otp');
            request()->session()->forget('otp_set_time');
            // insert activity-----------------
            //<---client email as user id
            $user = User::find(auth()->user()->id);
            activity("bank withdraw")
                ->causedBy(auth()->user()->id)
                ->withProperties($data['all'])
                ->event("bank withdraw")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been " . "withdraw");
            // end activity log----------------->>
            return ([
                'status' => true,
                'submit_wait' => submit_wait('bank-withdraw', 30),
                'message' => 'Withdraw Request successfully submited.',
                'last_transaction' => $last_transaction
            ]);
        }
        return ([
            'status' => false,
            'submit_wait' => submit_wait('bank-withdraw', 30),
            'message' => 'Somthing went wrong, please try agian later!.'
        ]);
    }
    // start ib section------------------------------------------
    // ib
    public function ib_bank_withdraw(Request $request)
    {

        try {
            $amount_local = is_numeric($request->amount_local) ? $request->amount_local : 0;

            $otp_settings = OtpSetting::first();
            $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
            //charge applied here
            // ib
            $charge = TransactionService::charge('withdraw', $request->amount, null);
            if ($request->ajax()) {
                $user = User::find(auth()->user()->id);
                // start session of form submit
                // ib
                $multiple_submission = has_multi_submit('bank-withdraw', 15);
                multi_submit('bank-withdraw', 15);
                $valid_res = [];
                // validation check step 1
                // ib
                $validation_rules = [
                    'bank' => 'required',
                    'bank_account' => 'required',
                    // 'code' => 'required',
                ];
                // validation check step 2
                // ib
                if ($request->op === 'step-2') {
                    $validation_rules['country'] = 'required';
                    $validation_rules['address'] = 'required';
                    $validation_rules['amount'] = 'required|numeric|min:1';
                    // $validation_rules['transaction_password'] = 'required';
                    // $validation_rules['amount_local'] = (BankService::is_multicurrency('all') || BankService::is_multicurrency('withdraw')) ? 'required' : 'nullable'; //validation check for multi currency
                }
                // validation check otp
                if ($request->op === 'step-3') {
                    // $validation_rules['otp_1'] = 'required|max:1';
                    // $validation_rules['otp_2'] = 'required|max:1';
                    // $validation_rules['otp_3'] = 'required|max:1';
                    // $validation_rules['otp_4'] = 'required|max:1';
                    // $validation_rules['otp_5'] = 'required|max:1';
                    // $validation_rules['otp_6'] = 'required|max:1';
                    $validation_rules['transaction_password'] = 'required';
                }
                // return validation status
                $validator = Validator::make($request->all(), $validation_rules);
                if ($validator->fails()) {
                    $valid_res['message'] = 'Please fix the following errors!';
                    $valid_res['errors'] = $validator->errors();
                    // return status for otp validation
                    if ($request->op === 'step-3') {
                        if ($otp_settings->withdraw == true) {
                            $valid_res['otp_status'] = false;
                        } else {
                            $valid_res['otp_status'] = true;
                        }
                    }

                    if ($request->op === 'step-1' || $request->op === 'step-2') {
                        $valid_res['valid_status'] = false;
                    }
                    return Response::json($valid_res);
                }
                // check authenticated bank
                $requested_bank = BankAccount::find(decrypt($request->bank_account));
                if ($requested_bank->user_id != auth()->user()->id) {
                    return Response::json([
                        'valid_status' => false,
                        'message' => 'You try with invalid bank account!',
                    ]);
                }
                // if step 1 vaidation status true
                if ($request->op === 'step-1') {
                    $valid_res['step_1_status'] = true;
                    return Response::json($valid_res);
                }
                if ($request->op === 'step-2') {
                    $balance = BalanceSheetService::ib_wallet_balance(auth()->user()->id, $request->amount);
                    // check balance available or not
                    // ib
                    if ($balance <= 0 || $request->amount > $balance) {
                        $valid_res['valid_status'] = false;
                        $valid_res['errors'] = ['amount' => "You don't have available balance!"];
                        $valid_res['message'] = 'Please fix the following errors';
                        return Response::json($valid_res);
                    }
                    // check minimum withdraw
                    // ib
                    if (BalanceService::check_minimum_withdraw($request->amount) == false) {
                        $min_withdraw = BalanceService::min_withdraw_amount();
                        return Response::json([
                            'valid_status' => false,
                            'errors' => ['amount' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"],
                            'message' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"
                        ]);
                    }
                    // check max  withdraw amount
                    // ib
                    if (BalanceService::check_max_withdraw($request->amount) == false) {
                        $max_withdraw = BalanceService::max_withdraw_amount();
                        return Response::json([
                            'valid_status' => false,
                            'errors' => ['amount' => 'Maximum withdraw amount should be &dollar;' . $max_withdraw],
                            'message' => 'Maximum withdraw amount should be &dollar;' . $max_withdraw
                        ]);
                    }
                    // // transaction password validation
                    // // ib
                    // if (!Hash::check($request->transaction_password, $user->transaction_password)) {
                    //     return Response::json([
                    //         'valid_status' => false,
                    //         'message' => 'Please fix the following errors.',
                    //         'errors' => ['transaction_password' => 'Transaction Password Not match!']
                    //     ]);
                    // }
                    
                    // check in IB SETUP for daily, monthly, weekly or bi-weekly withdrawal permission
                    // ib
                    // $checkWithdralPermission = IBManagementService::checkWithdrawLimit();
                    // if ($checkWithdralPermission['status'] === false) {
                    //     return Response::json($checkWithdralPermission);
                    // }
                }
                // check otp enable or disable
                if (OtpService::has_otp('withdraw')) {
                    if ($request->op === 'step-2' || $request->op === 'resend') {
                        // // create otp and send otp
                        // $otp_status = OtpService::send_otp();
                        // return Response::json(['otp_send' => $otp_status]);
                        try{
                            $log_pass = Log::select()->where('user_id', auth()->user()->id)->first();
                            $decrypted_password = decrypt($log_pass->transaction_password);
                            //mail script
                            if ($decrypted_password) {
                                EmailService::send_email('send-transaction-pin', [
                                    'user_id' => auth()->user()->id,
                                    'transaction_pin'      => $decrypted_password,
                                ]);
                                return Response::json(['otp_send' => true]);
                            }
                        }catch(Exception $th){
                            return Response::json(['otp_send' => false]);
                        }
                    }
                }
                // if otp is disabled
                else {
                    // check minimum withdraw
                    if (BalanceService::check_minimum_withdraw($request->amount) == false) {
                        $min_withdraw = BalanceService::min_withdraw_amount();
                        return Response::json([
                            'valid_status' => false,
                            'errors' => ['amount' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"],
                            'message' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"
                        ]);
                    }
                    
                    $response = $this->ib_bank_withdraw_create([
                        'bank_account_id' => decrypt($request->bank_account),
                        'amount' => $request->amount,
                        'charge' => $charge,
                        'currency_name' => $request->currency_name ?? "",
                        'amount_local' => $amount_local ?? 0,
                        'all' => $request->all(),
                        'user' => $user,
                    ]);

                    return Response::json($response);
                }

                // final step after otp verification
                if ($request->op === 'step-3') {
                    // $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
                    // if ($request->session()->get('bank-withdraw-otp') == $request_otp) {
                    //     $time = session('otp_set_time');
                    //     $minutesBeforeSessionExpire = 5;
                    //     // check otp match or not
                    //     if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                    //         // make withdraw
                    //         $response = $this->ib_bank_withdraw_create([
                    //             'bank_account_id' => decrypt($request->bank_account),
                    //             'amount' => $request->amount,
                    //             'charge' => $charge,
                    //             'currency_name' => $request->currency_name ?? "",
                    //             'amount_local' => $amount_local ?? 0,
                    //             'all' => $request->all(),
                    //             // 'user' => $user,
                    //         ]);
                    //         return Response::json($response);
                    //     }
                    //     // return if otp time out
                    //     return Response::json([
                    //         'otp_status' => false,
                    //         'message' => 'OTP Time Out!',
                    //     ]);
                    // }
                    // // return if otp not match
                    // return Response::json([
                    //     'otp_status' => false,
                    //     'message' => 'OTP not matched!',
                    // ]);
                    // transaction password validation
                    // ib
                    if (!Hash::check($request->transaction_password, $user->transaction_password)) {
                        return Response::json([
                            'valid_status' => false,
                            'message' => 'Please fix the following errors.',
                            'errors' => ['transaction_password' => 'Transaction Password Not match!']
                        ]);
                    } else {
                        $response = $this->ib_bank_withdraw_create([
                            'bank_account_id' => decrypt($request->bank_account),
                            'amount' => $request->amount,
                            'charge' => $charge,
                            'currency_name' => $request->currency_name ?? "",
                            'amount_local' => $amount_local ?? 0,
                            'all' => $request->all(),
                            // 'user' => $user,
                        ]);
                        return Response::json($response);
                    }
                }
            }
            // ending ajax request for make wnthdraw
            $last_transaction = Withdraw::where('user_id', auth()->user()->id)->where('transaction_type', 'bank')->latest()->first();
            $banks = BankAccount::where('user_id', auth()->user()->id)->where('approve_status', 'a')->select('bank_name', 'id')->distinct()->get();
            // start rendaring view file
            return view('ibs.withdraw.bank-withdraw', [
                'banks' => $banks,
                'last_transaction' => $last_transaction,
                'otp_settings' => $otp_settings,
                'user_otp_settings' => $user_otp_settings,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // *********************************************************************************************************************************
    // ib bank withdraw create
    // ib
    // **************************************************************************
    private function ib_bank_withdraw_create($data)
    {
        // return $data;
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
        $created = Withdraw::create([
            'user_id' => auth()->user()->id,
            'transaction_id' => $invoice,
            'bank_account_id' => $data['bank_account_id'],
            'amount' => $data['amount'],
            'charge' => $data['charge'],
            'approved_status' => 'P',
            'transaction_type' => 'bank',
            'currency' => $data['currency_name'] ?? "",
            'wallet_type' => 'ib',
            'local_currency' => $data['amount_local'] ?? 0
        ]);

        //mailer script
        if ($created) {
            //notification mail to admin
            // MailNotificationService::notification('withdraw', 'ib', 1, auth()->user()->name, $data['amount']);
            $last_transaction = $created;
            $ib_wallet_balance = BalanceSheetService::ib_wallet_balance(auth()->user()->id);
            MailNotificationService::admin_notification([
                'amount' => $data['amount'],
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'type' => 'withdraw',
                'client_type' => 'ib'
            ]);
            // sending mail to
            EmailService::send_email('withdraw-request', [
                'clientWithdrawAmount'      => $data['amount'],
                'user_id'                   => auth()->user()->id,
                'deposit_method'            => ($last_transaction) ? ucwords($last_transaction->transaction_type) : '',
                'deposit_date'              => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                'previous_balance'          => ($ib_wallet_balance) + ($last_transaction->amount),
                'approved_amount'           => $last_transaction->amount,
                'total_balance'             => $ib_wallet_balance
            ]);
            // insert activity-----------------
            //<---client email as user id
            $user = User::find(auth()->user()->id);
            activity("ib bank withdraw")
                ->causedBy(auth()->user()->id)
                ->withProperties($data['all'])
                ->event("bank withdraw")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been " . "withdraw");
            // end activity log----------------->>

            request()->session()->forget('bank-withdraw-otp');
            request()->session()->forget('otp_set_time');
            return ([
                'status' => true,
                'submit_wait' => submit_wait('bank-withdraw', 15),
                'message' => 'Withdraw Request successfully submited.',
                'last_transaction' => $last_transaction
            ]);
        }
        return ([
            'status' => false,
            'submit_wait' => submit_wait('bank-withdraw', 15),
            'message' => 'Somthing went wrong, please try agian later!.'
        ]);
    }
}
