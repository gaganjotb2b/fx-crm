<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Mail\CryptoMailForITCorner;
use App\Mail\OTPverificationMail;
use App\Mail\withdraw\WithdrawRequest;
use App\Models\Admin;
use App\Models\admin\SystemConfig;
use App\Models\BankAccount;
use App\Models\CryptoAddress;
use App\Models\CryptoCurrency;
use App\Models\OtherTransaction;
use App\Models\OtpSetting;
use App\Models\TradingAccount;
use App\Services\MailNotificationService;
use App\Models\User;
use App\Models\UserOtpSetting;
use App\Models\Withdraw;
use App\Models\Log;
use App\Services\AllFunctionService;
use App\Services\BalanceService;
use App\Services\CryptoWallet;
use App\Services\EmailService;
use App\Services\IBManagementService;
use App\Services\OtpService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CryptoWithdrawController extends Controller
{
    public function __construct()
    {
        if (request()->is('/ib/withdraw/crypto-withdraw')) {
            $this->middleware(AllFunctionService::access('crypto_withdraw', 'ib'));
            $this->middleware(AllFunctionService::access('withdraw', 'ib'));
        } else {
            $this->middleware(AllFunctionService::access('crypto_withdraw', 'trader'));
            $this->middleware(AllFunctionService::access('withdraw', 'trader'));
        }
    }
    //basic view---------------------
    public function crypto_view(Request $request)
    {

        // all crypto address / all active addresses
        // $block_chains = CryptoCurrency::where('status', 'active')->get();
        $crypto_address = CryptoAddress::where(function ($query) {
            $query->where('verify_1', 1)
                ->where('verify_2', 1)
                ->where('status', 1);
        });
        // all crypto address / all active addresses
        $block_chains = $crypto_address->select('block_chain')->distinct('block_chain')->get();
        $last_transaction = Withdraw::where('user_id', auth()->user()->id)->where('transaction_type', 'crypto')->latest()->first();
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
        $trading_account = TradingAccount::where('user_id', auth()->user()->id)->get();
        return view('traders.withdraw.crypto-withdraw', [
            'last_transaction' => $last_transaction,
            'otp_settings' => $otp_settings,
            'user_otp_settings' => $user_otp_settings,
            'block_chains' => $block_chains,
            'trading_accounts' => $trading_account
        ]);
    }
    // crypto withdraw--------------
    public function crypto_withdraw(Request $request)
    {
        try {
            // check pending withdraw broker need 
            $pending_withdraw = Withdraw::where('user_id', auth()->user()->id)->where('approved_status', 'P')->first();
            if($pending_withdraw){
                return Response::json([
                    'valid_status' => false,
                    'message' => 'Your previous withdraw request is pending. After approve you can send new request.',
                ]);
            }
            
            $user = User::find(auth()->user()->id);
            $otp_settings = OtpSetting::first();
            $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
            //charge applied here
            $charge = TransactionService::charge('withdraw', $request->usd_amount, null);
            $data = [];
            // step 1 validation check
            $validation_rules = [
                'block_chain' => 'required',
                'instrument' => 'required',
                'crypto_address' => 'required|max:255',
            ];
            // step 2 validation check
            if ($request->op === 'step-2') {
                $validation_rules['usd_amount'] = 'required|numeric';
                $validation_rules['crypto_amount'] = 'required|numeric';
                // $validation_rules['transaction_password'] = 'required';
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
                $data['message'] = 'Please fix the following errors!';
                $data['errors'] = $validator->errors();
                // return status for otp validation
                if ($request->op === 'step-3') {
                    if ($otp_settings->withdraw == true) {
                        $data['otp_status'] = false;
                    } else {
                        $data['otp_status'] = true;
                    }
                }
                if ($request->op === 'step-1' || $request->op === 'step-2') {
                    $data['valid_status'] = false;
                }
                return Response::json($data);
            }
            // if step 1 is valid or true
            if ($request->op === 'step-1') {
                return Response::json(['step_1_status' => true]);
            }
            if ($request->op === 'step-2') {
                $all_fun = new AllFunctionService();
                $balance = $all_fun->get_self_balance(auth()->user()->id);
                if ($balance <= 0 || (($request->usd_amount + $charge) > $balance)) {
                    $data['valid_status'] = false;
                    $data['errors'] = ['usd_amount' => "You don't have available balance"];
                    $data['message'] = 'Please fix the following errors';
                    return Response::json($data);
                }
            }

            // sending otp
            if ($otp_settings->withdraw == true && $user_otp_settings->withdraw == true) {
            // if (OtpService::has_otp('withdraw')) {
                if ($request->op === 'step-2' || $request->op === 'resend') {
                    // // create otp
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
            // whene otp off by admin/client
            // withdraw without otp
            else {
                // check minimum withdraw
                if (BalanceService::check_minimum_withdraw($request->usd_amount) == false) {
                    $min_withdraw = BalanceService::min_withdraw_amount();
                    return Response::json([
                        'valid_status' => false,
                        'errors' => ['crypto_amount' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"],
                        'message' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"
                    ]);
                }
                $response = $this->trd_crypto_withdraw_create([
                    'block_chain' => $request->block_chain,
                    'instrument' => $request->instrument,
                    'crypto_address' => $request->crypto_address,
                    'crypto_amount' => $request->crypto_amount,
                    'usd_amount' => $request->usd_amount,
                    'charge' => $charge,
                    'all' => $request->all(),
                    'user' => $user,
                ]);
                return Response::json($response);
            }
            // when otp on by admin/client 
            // withdraws with otp verifictions
            if ($request->op === 'step-3') {
                // $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
                // if ($request->session()->get('crypto-withdraw-otp') == $request_otp) {
                //     $time = session('otp_set_time');
                //     $minutesBeforeSessionExpire = 5;
                //     if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                //         $response = $this->trd_crypto_withdraw_create([
                //             'block_chain' => $request->block_chain,
                //             'instrument' => $request->instrument,
                //             'crypto_address' => $request->crypto_address,
                //             'crypto_amount' => $request->crypto_amount,
                //             'usd_amount' => $request->usd_amount,
                //             'charge' => $charge,
                //             'all' => $request->all(),
                //             'user' => $user,
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
                
                if (!Hash::check($request->transaction_password, auth()->user()->transaction_password)) {
                    $data['valid_status'] = false;
                    $data['errors'] = ['transaction_password' => 'Transaction password not matched!'];
                    $valid_res['message'] = 'Please fix the following errors';
                    return Response::json($data);
                }else{
                    $response = $this->trd_crypto_withdraw_create([
                        'block_chain' => $request->block_chain,
                        'instrument' => $request->instrument,
                        'crypto_address' => $request->crypto_address,
                        'crypto_amount' => $request->crypto_amount,
                        'usd_amount' => $request->usd_amount,
                        'charge' => $charge,
                        'all' => $request->all(),
                        'user' => $user,
                    ]);
                    return Response::json($response);
                }
            }
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // trader crypto withdraw create
    private function trd_crypto_withdraw_create($data)
    {
        try {
            $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
            // check if withdraw option is direct account withdraw
            if (array_key_exists('withdraw_option', $data) && strtolower($data['withdraw_option']) === 'account') {
                $trading_account = TradingAccount::find($data['trading_account']);
                if (!$trading_account) {
                    return ([
                        'status' => false,
                        'message' => 'Trading account not found, in hour system',
                    ]);
                }
                // $create_internal = 
            }
            // crypto transaction
            $crypto_txn = OtherTransaction::create([
                'transaction_type' => 'crypto',
                'crypto_type' => $data['block_chain'],
                'crypto_instrument' => $data['instrument'],
                'crypto_address' => $data['crypto_address'],
                'crypto_amount' => $data['crypto_amount'],
            ])->id;

            $withdraw = Withdraw::create([
                'user_id' => auth()->user()->id,
                'transaction_id' => $invoice,
                'transaction_type' => 'crypto',
                'other_transaction_id' => $crypto_txn,
                'amount' => $data['usd_amount'],
                'charge' => $data['charge'],
                'wallet_type' => 'trader',
            ]);
            //mailer script
            if ($crypto_txn && $withdraw) {
                request()->session()->forget('crypto-withdraw-otp');
                request()->session()->forget('otp_set_time');
                $last_transaction = Withdraw::find($withdraw->id);

                // sending mail to user
                EmailService::send_email('crypto-withdraw-request', [
                    'cryptoAddress' => $data['crypto_address'],
                    'currency' => $data['block_chain'],
                    'blockchain' => $data['instrument'],
                    'amount' => $data['usd_amount'],
                    'cryptoAmount' => $data['crypto_amount'],
                    'status' => "Pending",
                    'user_id' => auth()->user()->id,
                ]);


                //start: mail for itcorner
                $message_to_itcorner = '<p> A crypto withdraw request to your software from <strong>' . auth()->user()->email . '.</strong> </p>
                <table style="text-align:left; border-collapse:collapse; margin-top:2rem">
                    <tbody>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Address</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $data['crypto_address'] . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Currency</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $data['block_chain'] . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Blockchain</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $data['instrument'] . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Amount</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> $' . $data['usd_amount'] . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Amount</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $data['crypto_amount'] . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Status</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#ffa442;padding:15px"> Pending </td>
                        </tr>
                    </tbody>
                </table>';
                // $to_itcorner = 'gainxplus1@gmail.com';
                $support_email = SystemConfig::select('support_email')->first();
                $support_email = ($support_email) ? $support_email->support_email : default_support_email();



                // $it_corner_data = [
                //     'name'                  => 'Author',
                //     'master-admin'          => $to_itcorner,
                //     'it_corner_message'     => $message_to_itcorner,
                //     'transaction'           => "crypto_withdraw",
                // ];

                // Mail::to($to_itcorner)->send(new CryptoMailForITCorner($it_corner_data));

                //end: mail for itcorner

                // admin notification after crypto withdraw 
                $admins = User::select('email')->where('type', 2)->where('active_status', 1)->get();
                foreach ($admins as $row) {
                    try {
                        $admin_data = [
                            'name'                  => 'Super Admin',
                            'master-admin'          => $row->email,
                            'it_corner_message'     => $message_to_itcorner,
                            'transaction'           => "crypto_withdraw",
                        ];
                        Mail::to($row->email)->send(new CryptoMailForITCorner($admin_data));
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                // insert activity-----------------
                // dmin notifiction
                MailNotificationService::admin_notification([
                    'amount' => $data['usd_amount'],
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'type' => 'withdraw',
                    'client_type' => 'trader',
                    'crypto_address' => $data['crypto_address']
                ]);
                //<---client email as user id
                activity("crypto withdraw")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($data['all'])
                    ->event("crypto withdraw")
                    ->performedOn($data['user'])
                    ->log("The IP address " . request()->ip() . " has been " .  "withdraw");
                // end activity log----------------->>

                // return if withdraw created
                return ([
                    'status' => true,
                    'message' => 'Withdraw request successfully submited',
                    'last_transaction' => $last_transaction
                ]);
            }
            // return if withdraw creation faild
            return ([
                'status' => false,
                'message' => 'Something went wrong please try again later!',
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error'
            ]);
        }
    }

    // ib section istart---------------------------------------------------
    //basic view---------------------
    public function ib_crypto_withdraw(Request $request)
    {
        $crypto_address = CryptoAddress::where(function ($query) {
            $query->where('verify_1', 1)
                ->where('verify_2', 1)
                ->where('status', 1);
        });
        // all crypto address / all active addresses
        $block_chains = $crypto_address->select('block_chain')->distinct('block_chain')->get();
        $last_transaction = Withdraw::where('user_id', auth()->user()->id)->where('transaction_type', 'crypto')->latest()->first();
        $otp_settings = OtpSetting::first();

        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();

        if ($request->ajax()) {
            $user = User::find(auth()->user()->id);
            $otp_settings = OtpSetting::first();
            $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
            //charge applied here
            $charge = TransactionService::charge('withdraw', $request->usd_amount, null);
            $data = [];
            // step 1 validation check
            $validation_rules = [
                'block_chain' => 'required',
                'instrument' => 'required',
                'crypto_address' => 'required|max:255',
            ];
            // step 2 validation check
            if ($request->op === 'step-2') {
                $validation_rules['usd_amount'] = 'required|numeric|min:1';
                $validation_rules['crypto_amount'] = 'required|numeric';
                // $validation_rules['transaction_password'] = 'required';
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
                $data['message'] = 'Please fix the following errors!';
                $data['errors'] = $validator->errors();
                // return status for otp validation
                if ($request->op === 'step-3') {
                    if ($otp_settings->withdraw == true) {
                        $data['otp_status'] = false;
                    } else {
                        $data['otp_status'] = true;
                    }
                }
                if ($request->op === 'step-1' || $request->op === 'step-2') {
                    $data['valid_status'] = false;
                }
                return Response::json($data);
            }
            // if step 1 is valid or true
            if ($request->op === 'step-1') {
                return Response::json(['step_1_status' => true]);
            }
            if ($request->op === 'step-2') {
                // ib withdraw
                // check ib balance available
                $balance = BalanceService::ib_balance(auth()->user()->id);
                if ($balance <= 0 || (($request->usd_amount + $charge) > $balance)) {
                    $data['valid_status'] = false;
                    $data['errors'] = ['usd_amount' => "You don't have available balance"];
                    $data['message'] = 'Please fix the following errors';
                    return Response::json($data);
                }
                // check minimum withdraw
                if (BalanceService::check_minimum_withdraw($request->usd_amount) == false) {
                    $min_withdraw = BalanceService::min_withdraw_amount();
                    return Response::json([
                        'valid_status' => false,
                        'errors' => ['usd_amount' => 'Minimum withdraw amount should be &dollar;' . $min_withdraw],
                        'message' => 'Minimum withdraw amount should be &dollar;' . $min_withdraw
                    ]);
                }
                // check max  withdraw amount
                if (BalanceService::check_max_withdraw($request->usd_amount) == false) {
                    $max_withdraw = BalanceService::max_withdraw_amount();
                    return Response::json([
                        'valid_status' => false,
                        'errors' => ['usd_amount' => 'Maximum withdraw amount should be &dollar;' . $max_withdraw],
                        'message' => 'Maximum withdraw amount should be &dollar;' . $max_withdraw
                    ]);
                }
                // // ib withdraw
                // // check transaction password
                // if (!Hash::check($request->transaction_password, auth()->user()->transaction_password)) {
                //     $data['valid_status'] = false;
                //     $data['errors'] = ['transaction_password' => 'Transaction password not matched!'];
                //     $valid_res['message'] = 'Please fix the following errors';
                //     return Response::json($data);
                // }
                // check in IB SETUP for daily, monthly, weekly or bi-weekly withdrawal permission
                $checkWithdralPermission = IBManagementService::checkWithdrawLimit();
                if ($checkWithdralPermission['status'] === false) {
                    return Response::json($checkWithdralPermission);
                }
            }
            // sending otp
            if (OtpService::has_otp('withdraw')) {
                if ($request->op === 'step-2' || $request->op === 'resend') {
                    // // create otp
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
            // whene otp off by admin/client
            // withdraw without otp
            else {
                // check minimum withdraw
                if (BalanceService::check_minimum_withdraw($request->usd_amount) == false) {
                    $min_withdraw = BalanceService::min_withdraw_amount();
                    return Response::json([
                        'valid_status' => false,
                        'errors' => ['crypto_amount' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"],
                        'message' => 'Minimum withdraw amount should be &dollar;' . "$min_withdraw"
                    ]);
                }
                $response = $this->create_ib_withdraw([
                    'block_chain' => $request->block_chain,
                    'instrument' => $request->instrument,
                    'crypto_address' => $request->crypto_address,
                    'crypto_amount' => $request->crypto_amount,
                    'usd_amount' => $request->usd_amount,
                    'charge' => $charge,
                    'all' => $request->all(),
                    'user' => $user
                ]);
                return Response::json($response);
            }
            // when otp on by admin/client 
            // withdraws with otp verifictions
            if ($request->op === 'step-3') {
                // $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
                // if ($request->session()->get('crypto-withdraw-otp') == $request_otp) {
                //     $time = session('otp_set_time');
                //     $minutesBeforeSessionExpire = 5;
                //     if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                //         $response = $this->create_ib_withdraw([
                //             'block_chain' => $request->block_chain,
                //             'instrument' => $request->instrument,
                //             'crypto_address' => $request->crypto_address,
                //             'crypto_amount' => $request->crypto_amount,
                //             'usd_amount' => $request->usd_amount,
                //             'charge' => $charge,
                //             'all' => $request->all(),
                //             'user' => $user
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
                // ib withdraw
                // check transaction password
                if (!Hash::check($request->transaction_password, auth()->user()->transaction_password)) {
                    $data['valid_status'] = false;
                    $data['errors'] = ['transaction_password' => 'Transaction password not matched!'];
                    $valid_res['message'] = 'Please fix the following errors';
                    return Response::json($data);
                }else{
                    $response = $this->create_ib_withdraw([
                        'block_chain' => $request->block_chain,
                        'instrument' => $request->instrument,
                        'crypto_address' => $request->crypto_address,
                        'crypto_amount' => $request->crypto_amount,
                        'usd_amount' => $request->usd_amount,
                        'charge' => $charge,
                        'all' => $request->all(),
                        'user' => $user
                    ]);
                    return Response::json($response);
                }
            }
        } //ending ajax request

        return view('ibs.withdraw.crypto-withdraw', [
            'last_transaction' => $last_transaction,
            'otp_settings' => $otp_settings,
            'user_otp_settings' => $user_otp_settings,
            'block_chains' => $block_chains,
        ]);
    }
    // create ib crypto withdraw
    private function create_ib_withdraw($data)
    {
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
        // crypto transaction
        $crypto_txn = OtherTransaction::create([
            'transaction_type' => 'crypto',
            'crypto_type' => $data['block_chain'],
            'crypto_instrument' => $data['instrument'],
            'crypto_address' => $data['crypto_address'],
            'crypto_amount' => $data['crypto_amount'],
        ])->id;

        $withdraw = Withdraw::create([
            'user_id' => auth()->user()->id,
            'transaction_id' => $invoice,
            'transaction_type' => 'crypto',
            'other_transaction_id' => $crypto_txn,
            'amount' => $data['usd_amount'],
            'charge' => $data['charge'],
            'wallet_type' => 'ib',
        ]);
        //mailer script
        if ($crypto_txn && $withdraw) {
            request()->session()->forget('crypto-withdraw-otp');
            request()->session()->forget('otp_set_time');
            $last_transaction = Withdraw::find($withdraw->id);


            // sending mail to user
            EmailService::send_email('crypto-withdraw-request', [
                'cryptoAddress' => $data['crypto_address'],
                'currency' => $data['block_chain'],
                'blockchain' => $data['instrument'],
                'amount' => $data['usd_amount'],
                'cryptoAmount' => $data['crypto_amount'],
                'status' => "Pending",
                'user_id' => auth()->user()->id,
            ]);


            //start: mail for itcorner
            $message_to_itcorner = '<p> A crypto withdraw request to your software from <strong>' . auth()->user()->email . '.</strong> </p>
                <table style="text-align:left; border-collapse:collapse; margin-top:2rem">
                    <tbody>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Address</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $data['crypto_address'] . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Currency</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $data['block_chain'] . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Blockchain</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $data['instrument'] . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Amount</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> $' . $data['usd_amount'] . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Amount</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $data['crypto_amount'] . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Status</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#ffa442;padding:15px"> Pending </td>
                        </tr>
                    </tbody>
                </table>';
            // $to_itcorner = 'gainxplus1@gmail.com';
            $support_email = SystemConfig::select('support_email')->first();
            $support_email = ($support_email) ? $support_email->support_email : default_support_email();



            // $it_corner_data = [
            //     'name'                  => 'Author',
            //     'master-admin'          => $to_itcorner,
            //     'it_corner_message'     => $message_to_itcorner,
            //     'transaction'           => "crypto_withdraw",
            // ];

            // Mail::to($to_itcorner)->send(new CryptoMailForITCorner($it_corner_data));

            //end: mail for itcorner

            // admin notification after crypto withdraw 
            $admins = User::select('email')->where('type', 2)->where('active_status', 1)->get();
            foreach ($admins as $row) {
                try {
                    $admin_data = [
                        'name'                  => 'Super Admin',
                        'master-admin'          => $row->email,
                        'it_corner_message'     => $message_to_itcorner,
                        'transaction'           => "crypto_withdraw",
                    ];
                    Mail::to($row->email)->send(new CryptoMailForITCorner($admin_data));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            // insert activity-----------------
            //<---client email as user id
            
            $user = User::find(auth()->user()->id);
            activity("crypto withdraw")
                ->causedBy(auth()->user()->id)
                ->withProperties($data['all'])
                ->event("crypto withdraw")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been " .  "withdraw");
            // end activity log----------------->>
            // ib withddraw
            return ([
                'status' => true,
                'message' => 'Withdraw request successfully submited',
                'last_transaction' => $last_transaction,
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Something went wrong please try again later!',
        ]);
    } //ending IB cruypto withdraw create
}
