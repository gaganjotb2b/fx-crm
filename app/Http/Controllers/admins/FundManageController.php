<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\AddCredit;
use App\Models\admin\InternalTransfer;
use App\Models\admin\SystemConfig;
use App\Models\Credit;
use App\Models\Deposit;
use App\Models\StaffTransaction;
use App\Models\TradingAccount;
use App\Models\TransactionSetting;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\WalletUpDown;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\EmailService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\systems\AdminLogService;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class FundManageController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:fund management"]);
        $this->middleware(["role:finance"]);
        // system module control
        $this->middleware(AllFunctionService::access('finance', 'admin'));
        $this->middleware(AllFunctionService::access('fund_management', 'admin'));
    }
    //basic view
    // -----------------------------------------------------------
    public function index()
    {
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)->first();
        if (isset($user_descriptions->gender)) {
            $avatar = (strtolower($user_descriptions->gender) == 'male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }
        return view('admins.finance.fund-management', ['avatar' => $avatar]);
    }
    // get client
    // ---------------------------------------------------------------
    public function get_client(Request $request)
    {
        $users = User::where('type', 0)->get();
        $options = '';
        foreach ($users as $key => $value) {
            $options .= '<option value="' . $value->id . '">' . $value->email . '</option>';
        }
        return Response::json($options);
    }

    // store data
    // -----------------------------------------------------------------
    public function store(Request $request)
    {
        $multiple_submission = false;
        $validation_rules = [
            'type' => 'required',
            'amount' => 'required',
            'trader' => 'required',
            'trading_account' => 'required',
            'transaction_method' => ($request->transaction_type === 'deposit' && $request->type === 'fund') ? 'required' : 'nullable',
            'note' => 'nullable',
            'expire_date' => ($request->type === 'credit') ? 'required' : 'nullable',
        ];
        $multiple_submission = has_multi_submit('fund-management', wait_second()); // <---global helper Helper/helper.php
        multi_submit('fund-management', 15); // <---global helper Helper/helper.php
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails() || $multiple_submission == true) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'multiple_submission' => $multiple_submission,
                'message' => 'Please fix the following error',
                'submit_wait' => submit_wait('fund-management', wait_second())
            ]);
        }
        switch ($request->type) {
            case 'fund':
                $txn_id = substr(Crypt::encryptString($request->token), 0, 20);
                // $meta_account = TradingAccount::find($request->trading_account);
                $meta_account = TradingAccount::where('account_number', $request->trading_account)->first();
                $user = new User();
                // check meta account
                if ($meta_account) {
                    $account_number = $meta_account->account_number;

                    $response['message'] = 'Meta Server Error. Try again!';
                    // transaction for mt5 platform
                    if (strtolower($meta_account->platform) == 'mt5') {
                        $mt5_api = new Mt5WebApi();
                        $action = 'BalanceUpdate';

                        $data = array(
                            'Login' => (int)$account_number,
                            'Comment' => 'Fund ' . $request->type . ' #' . $txn_id
                        );
                        if ($request->transaction_type == 'deposit') { //change add to deposit
                            $data['Balance'] = (float)$request->amount;
                        } else {
                            $data['Balance'] = -(float)$request->amount;
                        }
                        $result = $mt5_api->execute($action, $data);

                        if (isset($result['success'])) {
                            if ($result['success']) {
                                $response['success'] = true;
                                $order_id = $result['data']['order'];
                            }
                        }
                    }
                    // transaction for mt4 platform
                    else if (strtolower($meta_account->platform) == 'mt4') {
                        $mt4api = new MT4API(); // Credit in
                        // check balance available or not for account to wallet(atw)
                        if ($request->transaction_type !== 'deposit') {
                            $data_balance = array(
                                'command' => 'user_data_get',
                                'data' => array(
                                    'account_id' => $meta_account->account_number
                                )
                            );
                            $result_balance = $mt4api->execute($data_balance, 'live');
                            // return $result_balance['data']['balance'];
                            // die;
                            if ($result_balance['success'] && ($result_balance['data']['balance'] <= 0 || $result_balance['data']['balance'] < $request->amount)) {
                                return Response::json([
                                    'status' => false,
                                    'submit_wait' => submit_wait('fund-management', wait_second()),
                                    'message' => "User don't have available balance!"
                                ]);
                            }
                        }
                        // make deposit(wta)/withdraw(atw)
                        $data = array(
                            'command' => 'deposit_funds',
                            'data' => array(
                                'account_id' => $meta_account->account_number,
                                'comment' => "na"
                            ),
                        );
                        // make deposit (wta)
                        if ($request->transaction_type == 'deposit') {
                            $data['data']['amount'] = (float)$request->amount;
                        }
                        // make withdraw (atw)
                        else {
                            $data['data']['amount'] = -(float)$request->amount;
                        }

                        $result = $mt4api->execute($data);
                        // get api command status
                        if (isset($result['success'])) {
                            if ($result['success']) {
                                $response['success'] = true;
                                $order_id = $result['data']['order'];
                            }
                        }
                    }
                    ///===============Device track where from admin approve====================
                    $userAgent = $request->header('User-Agent');
                    $operatingSystems = [
                        'Windows\sNT\s(\d+\.\d+)' => 'Windows',
                        'Macintosh|Mac OS X\s(\d+\.\d+)' => 'macOS',
                        'iOS\s(\d+\.\d+)' => 'iOS',
                        'Android\s(\d+\.\d+)' => 'Android',
                        'Windows\sPhone\sOS\s(\d+\.\d+)' => 'Windows Phone',
                        'BlackBerry\s(\d+\.\d+)' => 'BlackBerry',
                        'Linux\s(.+)' => 'Linux',
                        'FreeBSD\s(\d+\.\d+)' => 'FreeBSD',
                        'OpenBSD\s(\d+\.\d+)' => 'OpenBSD',
                        'NetBSD\s(\d+\.\d+)' => 'NetBSD',
                    ];

                    $operatingSystem = 'Unknown';
                    foreach ($operatingSystems as $pattern => $name) {
                        if (preg_match('/' . $pattern . '/', $userAgent, $matches)) {
                            $version = isset($matches[1]) ? $matches[1] : '';
                            $operatingSystem = $name . ' ' . $version;
                            break;
                        }
                    }
                    $jsonData = json_encode(['ip' => request()->ip(), 'wname' => $operatingSystem]);
                    ///===============Device track where from admin approve====================
                    // check api response status
                    if ($response['success'] == true) {
                        $ip = $request->ip();
                        // make crm(deposit)
                        if ($request->transaction_type == 'deposit') {
                            $charge = TransactionSetting::where('transaction_type', 'Deposit')->where('active_status', 1)->first();
                            $deposit = Deposit::create([
                                'user_id' => $request->trader,
                                'invoice_id' => $txn_id,
                                'account' => $meta_account->id,
                                'charge' => TransactionService::charge('deposit', $request->amount, null),
                                'amount' => $request->amount,
                                'order_id' => $order_id,
                                'note' => $request->note,
                                'transaction_type' => $request->transaction_method,
                                'approved_status' => 'A',
                                'approved_by' => auth()->user()->id,
                                'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                                'ip_address' => $ip,
                                'device_name' => $operatingSystem
                            ]);
                            $charge = TransactionSetting::where('transaction_type', 'wta')->where('active_status', 1)->first();
                            // make internal transfer(wta)/deposit
                            $withdraw = InternalTransfer::create([
                                'user_id' => $request->trader,
                                'invoice_code' => $txn_id,
                                'account_id' => $meta_account->id,
                                'charge' => TransactionService::charge('wta', $request->amount, null),
                                'amount' => $request->amount,
                                'type' => 'wta',
                                'status' => 'A',
                                'approved_by' => auth()->user()->id,
                                'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                                'platform' => $meta_account->platform
                            ])->id;
                        }
                        // make internal transfer(atw)/withdraw
                        else {
                            $charge = TransactionSetting::where('transaction_type', 'wta')->where('active_status', 1)->first();
                            $withdraw = InternalTransfer::create([
                                'user_id' => $request->trader,
                                'invoice_code' => $txn_id,
                                'account_id' => $meta_account->id,
                                'charge' => TransactionService::charge('wta', $request->amount, null),
                                'amount' => $request->amount,
                                'type' => 'atw',
                                'status' => 'A',
                                'approved_by' => auth()->user()->id,
                                'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                                'platform' => $meta_account->platform
                            ])->id;
                            // make admin withdraw
                            $tbl_withdraw = WalletUpDown::create([
                                'wallet_type' => 'trader',
                                'user_id' => $request->trader,
                                'txn_by' => auth()->user()->id,
                                'txn_type' => 'deduct',
                                'amount' => $request->amount,
                                'note' => $request->note,
                                'admin_log' => $jsonData,
                                'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                                'method' => 'admin',
                                'status' => 'A',
                            ]);
                        }

                        $response['message'] = 'Transaction successfull Done';
                        // staff transaction 
                        $staff_trans = StaffTransaction::create([
                            'staff_id' => auth()->user()->id,
                            'type' => (strtolower($request->transaction_type) === 'deposit') ? 'add' : 'deduct',
                            'admin_log' => $jsonData,
                            'amount' => $request->amount,
                            'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                            'user_id' => $request->trader,
                            'approved_status' => 'A',
                            'wallet_type' => 'trader',
                        ]);
                        // insert activity-----------------
                        $ip_address = request()->ip();
                        $description = "The IP address $ip_address has been " . $request->type . " fund";
                        $user = User::find($request->trader); //<---client email as user id
                        activity("fund " . $request->type)
                            ->causedBy(auth()->user()->id)
                            ->withProperties($request->all())
                            ->event($request->type . " fund")
                            ->performedOn($user)
                            ->log($description);
                        // end activity log-----------------
                        return Response::json([
                            'status' => true,
                            'message' => $response['message'],
                            'account_id' => $meta_account->id,
                            'credit_id' => $withdraw,
                            'type' => $request->type,
                            'user_id' => $user->id,
                            'method' => $request->transaction_type,
                            'submit_wait' => submit_wait('fund-management', wait_second())
                        ]);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => $response['message'],
                        'submit_wait' => submit_wait('fund-management', wait_second())
                    ]);
                }
                $response['message'] = 'Account not found!';
                return Response::json([
                    'status' => false,
                    'message' => $response['message'],
                    'submit_wait' => submit_wait('fund-management', wait_second())
                ]);
                break;

            default:
                // credit add / deduct
                $response['success'] = false;
                $create = false;
                $txn_id = substr(Crypt::encryptString($request->token), 0, 20);
                $meta_account = TradingAccount::where('account_number', $request->trading_account)->first();
                if (strtolower($meta_account->platform) === 'mt5') {
                    $mt5_api = new Mt5WebApi();
                    $data = array(
                        'Login' => (int)$meta_account->account_number,
                        'Comment' => 'Fund ' . $request->type . ' #' . $txn_id
                    );
                    if ($request->type === 'add') {
                        $data['Balance'] = (float)$request->amount;
                        $data['Expiration'] = $request->expire_date;
                    } else {
                        $data['Balance'] = -(float)$request->amount;
                    }
                    $result = $mt5_api->execute('CreditUpdate', $data);
                    // $mt5_api->Disconnect();

                    if ($result['success'] == true) {
                        $response['success'] = true;
                    }
                } else if (strtolower($meta_account->platform) == 'mt4') {
                    $mt4api = new MT4API();
                    $data = array(
                        'command' => 'deposit_funds',
                        'data' => array(
                            'account_id' => $meta_account->account_number,
                            "comment" => 'Fund ' . $request->type . ' #' . $txn_id,
                        ),
                    );

                    if ($request->type === 'add') {
                        $data['data']['amount'] = (float)$request->amount;
                    } else {
                        $data['data']['amount'] = -(float)$request->amount;
                    }
                    $result = $mt4api->execute($data);
                    // return $result;
                    if (isset($result['success'])) {
                        if ($result['success']) {
                            $response['success'] = true;
                            $order_id = $result['data']['order'];
                        }
                    }
                }
                $customMessage = '';
                if ($response['success'] == true) {
                    $ip = $request->ip();
                    $trn_type = '';
                    if ($request->transaction_type === 'deposit') {
                        $trn_type = 'add';
                    } elseif ($request->transaction_type === 'withdraw') {
                        $trn_type = 'deduct';
                    } else {
                        $trn_type = $request->type ?? "";
                    }
                    $create = Credit::create([
                        'trading_account' => $meta_account->id,
                        'amount' => $request->amount,
                        'type' => $trn_type,
                        'expire_date' => $request->expire_date,
                        'transaction_id' => $txn_id,
                        'note' => $request->note,
                        'credited_by' => auth()->user()->id,
                        'ip' => $ip
                    ])->id;
                    $response['message'] = 'Credited successfully<br/>';
                }

                if ($create) {
                    // insert activity-----------------
                    $user = User::where($request->user_id)->first(); //<---client email as user id
                    activity($trn_type . " credit")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event($trn_type . " credit")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been " . $trn_type . " credited");
                    // end activity log-----------------

                    return Response::json([
                        'status' => true,
                        'message' => $response['message'],
                        'submit_wait' => submit_wait('finance-credit', wait_second()),
                        'account_id' => $meta_account->id,
                        'credit_id' => $create,
                        'user_id' => $user->id,
                        'type' => $request->type,
                    ]);
                } else {
                    return Response::json([
                        'status' => false,
                        'message' => 'Something went wrong! please try again later.',
                        'submit_wait' => submit_wait('finance-credit', wait_second())
                    ]);
                }
                break;
        }
    }
    // fund add / deduct 
    public function fund_mail(Request $request)
    {
        $internal_transfer = InternalTransfer::find($request->transaction_id);
        $amount = ($internal_transfer) ?  $internal_transfer->amount : 0;
        switch ($request->type) {
            case 'deposit':
                // for deposit/(wta) transfer
                $mail_status = EmailService::send_email('wta-transfer', [
                    'user_id' => $request->user_id,
                    'previous_balance' => BalanceSheetService::trader_wallet_balance($request->user_id) + $amount,
                    'transfer_amount' => $amount,
                    'total_balance' => BalanceSheetService::trader_wallet_balance($request->user_id),
                    'transfer_date' => date('d-M-Y', strtotime(now()))
                ]);
                break;

            default:
                // for withdraw/(atw) transfer
                $mail_status = EmailService::send_email('atw-transfer', [
                    'user_id' => $request->user_id,
                    'previous_balance' => BalanceSheetService::trader_wallet_balance($request->user_id) - $amount, //because first transaction done, than mail send
                    'transfer_amount' => $amount,
                    'total_balance' => BalanceSheetService::trader_wallet_balance($request->user_id),
                    'transfer_date' => date('d-M-Y', strtotime(now()))
                ]);
                break;
        }
        if ($mail_status) {
            return Response::json([
                'status' => true,
                'message' => 'Mail Successfully sent for fund add',
            ]);
        }
        return Response::json([
            'status' => true,
            'message' => 'Mail sending failed, Please try again later!',
        ]);
    }
    // mail for credit from fund managment
    public function mail_credit(Request $request)
    {
        $internal_transfer = InternalTransfer::find($request->transaction_id);
        $amount = ($internal_transfer) ?  $internal_transfer->amount : 0;
        switch ($request->type) {
            case 'deposit':
                // for deposit/(wta) transfer
                $mail_status = EmailService::send_email('wta-transfer', [
                    'user_id' => $request->user_id,
                    'previous_balance' => AllFunctionService::trader_total_balance($request->user_id) + $amount,
                    'transfer_amount' => $amount,
                    'total_balance' => AllFunctionService::trader_total_balance($request->user_id),
                    'transfer_date' => date('d-M-Y', strtotime(now()))
                ]);
                break;

            default:
                // for withdraw/(atw) transfer
                $mail_status = EmailService::send_email('atw-transfer', [
                    'user_id' => $request->user_id,
                    'previous_balance' => AllFunctionService::trader_total_balance($request->user_id) - $amount, //because first transaction done, than mail send
                    'transfer_amount' => $amount,
                    'total_balance' => AllFunctionService::trader_total_balance($request->user_id),
                    'transfer_date' => date('d-M-Y', strtotime(now()))
                ]);
                break;
        }
        if ($mail_status) {
            return Response::json([
                'status' => true,
                'message' => 'Mail Successfully sent for fund add',
            ]);
        }
        return Response::json([
            'status' => true,
            'message' => 'Mail sending failed, Please try again later!',
        ]);
    }
    // start fund add mail-----------------------------------------------------------------------------
    public function add_credit_mail(Request $request, $account_id, $credit_id, $type)
    {
        $meta_account = TradingAccount::find($account_id);
        $internal_transfer = InternalTransfer::find($credit_id);
        $platform = ($internal_transfer) ? ((strtolower($meta_account->platform) == 'mt4') ? "MetaTrader 4" : "MetaTrader 5") : '';
        $account  = ($internal_transfer) ? $meta_account->account_number : '';
        $amount  = ($internal_transfer) ?  $internal_transfer->amount : '';
        if (strtolower($type) == 'add') {
            $customMessage = "Your $platform account(" . ($account) . ") has been credited $" . $amount;
        } else {
            $customMessage = "Your $platform account(" . ($account) . ") has been deduced -$" . $amount;
        }

        $user = User::where('id', $meta_account->user_id)->first();
        if ($internal_transfer->type === 'wta') {
            // sending mail for wta wallet to account
            $mail_status = EmailService::send_email('wta-transfer', [
                'user_id' => $user->id,
                'previous_balance' => AllFunctionService::trader_total_balance($user->id) + $amount,
                'transfer_amount' => $amount,
                'total_balance' => AllFunctionService::trader_total_balance($user->id)
            ]);
        }
        // sending mail for atw transfer
        elseif ($internal_transfer->type === 'atw') {
            $mail_status = EmailService::send_email('atw-transfer', [
                'user_id' => $user->id,
                'previous_balance' => AllFunctionService::trader_total_balance($user->id) - $amount, //because first transaction done, than mail send
                'transfer_amount' => $amount,
                'total_balance' => AllFunctionService::trader_total_balance($user->id)
            ]);
        }

        if ($mail_status) {
            return Response::json([
                'status' => true,
                'message' => 'Mail Successfully sent for fund add',
            ]);
        } else {
            return Response::json([
                'status' => true,
                'message' => 'Mail sending failed, Please try again later!',
            ]);
        }
    }
    // end: credit add mail-----------------------------------------------------------------------------

    public function deposit(Request $request)
    {
        try {
            $validation_rules = [
                'amount' => 'required',
                'trader' => 'required',
                'trading_account' => 'required',
                'transaction_method' => 'required',
                'note' => 'nullable',
            ];

            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following error',
                ]);
            }

            $txn_id = substr(Crypt::encryptString($request->token), 0, 20);
            $meta_account = TradingAccount::where('id', $request->trading_account)->first();
            // check meta account
            if ($meta_account) {
                $account_number = $meta_account->account_number;
                if (strtolower($meta_account->platform) == 'mt5') {
                    $mt5_api = new Mt5WebApi();
                    $action = 'BalanceUpdate';
                    $data = array(
                        'Login' => (int)$account_number,
                        'Comment' => 'Fund ' . $request->type . ' #' . $txn_id
                    );
                    $data['Balance'] = (float)$request->amount;
                    $result = $mt5_api->execute($action, $data);

                    if (isset($result['success'])) {
                        if ($result['success']) {

                            $response['success'] = true;
                            $order_id = $result['data']['order'];
                        }
                    }
                }
                // transaction for mt4 platform
                else if (strtolower($meta_account->platform) == 'mt4') {
                    $mt4api = new MT4API(); // Credit in
                    // check balance available or not for account to wallet(atw)
                    // make deposit(wta)/withdraw(atw)
                    $data = array(
                        'command' => 'deposit_funds',
                        'data' => array(
                            'account_id' => $meta_account->account_number,
                            'comment' => "na"
                        ),
                    );
                    // make deposit (wta)
                    $data['data']['amount'] = (float)$request->amount;

                    $result = $mt4api->execute($data);
                    // get api command status
                    if (isset($result['success'])) {
                        if ($result['success']) {
                            $response['success'] = true;
                            $order_id = $result['data']['order'];
                        }
                    }
                }

                // check api response status
                if ($response['success'] == true) {
                    // make crm(deposit)
                    $charge = TransactionSetting::where('transaction_type', 'Deposit')->where('active_status', 1)->first();
                    $deposit = Deposit::create([
                        'user_id' => $request->trader,
                        'invoice_id' => $txn_id,
                        'account' => $meta_account->id,
                        'charge' => TransactionService::charge('deposit', $request->amount, null),
                        'amount' => $request->amount,
                        'order_id' => $order_id,
                        'note' => $request->note,
                        'transaction_type' => $request->transaction_method,
                        'approved_status' => 'A',
                        'approved_by' => auth()->user()->id,
                        'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                        'ip_address' => request()->ip(),
                        'device_name' => AdminLogService::admin_log(),
                        'admin_log' => AdminLogService::admin_log(),
                        'wallet_type' => 'trader',
                        'created_by' => 'admin',
                    ]);
                    $charge = TransactionSetting::where('transaction_type', 'wta')->where('active_status', 1)->first();
                    // make internal transfer(wta)/deposit
                    $wta_transfer = InternalTransfer::create([
                        'user_id' => $request->trader,
                        'invoice_code' => $txn_id,
                        'account_id' => $meta_account->id,
                        'charge' => TransactionService::charge('wta', $request->amount, null),
                        'amount' => $request->amount,
                        'type' => 'wta',
                        'status' => 'A',
                        'approved_by' => auth()->user()->id,
                        'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                        'platform' => $meta_account->platform,
                        'admin_log' => AdminLogService::admin_log(),
                        'created_by' => 'admin'
                    ])->id;

                    // insert activity-----------------

                    $user = User::find($request->trader); //<---client email as user id
                    activity("fund deposit")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event($request->type . " fund deposit")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been fund deposit");
                    // end activity log-----------------
                    if ($wta_transfer) {
                        return Response::json([
                            'status' => true,
                            'message' => 'Fund deposit successfully done',
                            'account_id' => $meta_account->id,
                            'credit_id' => $wta_transfer,
                            'type' => 'deposit',
                            'user_id' => $user->id,
                            'method' => $request->transaction_type,
                        ]);
                    }
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Something went wrong, please try again later'
                ]);
            }

            return Response::json([
                'status' => false,
                'message' => 'Trading account not matched, in our system',
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error',
            ]);
        }
    }
    public function withdraw(Request $request)
    {

        try {
            $validation_rules = [
                'amount' => 'required',
                'trader' => 'required',
                'trading_account' => 'required',
                'transaction_method' => 'required',
                'note' => 'nullable',
            ];

            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following error',
                ]);
            }
            $txn_id = substr(Crypt::encryptString($request->token), 0, 20);
            $meta_account = TradingAccount::where('id', $request->trading_account)->first();
            $user = new User();
            // check meta account
            if ($meta_account) {
                $account_number = $meta_account->account_number;
                // transaction for mt5 platform
                if (strtolower($meta_account->platform) == 'mt5') {
                    $mt5_api = new Mt5WebApi();
                    $action = 'BalanceUpdate';

                    $data = array(
                        'Login' => (int)$account_number,
                        'Comment' => 'Fund ' . $request->type . ' #' . $txn_id
                    );
                    $data['Balance'] = -(float)$request->amount;
                    $result = $mt5_api->execute($action, $data);

                    if (isset($result['success'])) {
                        if ($result['success']) {
                            $response['success'] = true;
                            $order_id = $result['data']['order'];
                        } else {
                            return Response::json([
                                'status' => false,
                                'message' => 'Request faild, Balance not available',
                                'amount' => 'Account balance not available'
                            ]);
                        }
                    } else {
                        return Response::json([
                            'status' => false,
                            'message' => 'Request faild, got a server error',
                        ]);
                    }
                }
                // transaction for mt4 platform
                else if (strtolower($meta_account->platform) == 'mt4') {
                    $mt4api = new MT4API(); // Credit in
                    // check balance available or not for account to wallet(atw)
                    $data_balance = array(
                        'command' => 'user_data_get',
                        'data' => array(
                            'account_id' => $meta_account->account_number
                        )
                    );
                    $result_balance = $mt4api->execute($data_balance, 'live');
                    // return $result_balance['data']['balance'];
                    // die;
                    if ($result_balance['data']['balance'] <= 0 || $result_balance['data']['balance'] < $request->amount) {
                        return Response::json([
                            'status' => false,
                            'submit_wait' => submit_wait('fund-management', wait_second()),
                            'message' => "User don't have available balance!"
                        ]);
                    }
                    // make deposit(wta)/withdraw(atw)
                    $data = array(
                        'command' => 'deposit_funds',
                        'data' => array(
                            'account_id' => $meta_account->account_number,
                            'comment' => "na"
                        ),
                    );
                    // make deposit (wta)
                    $data['data']['amount'] = -(float)$request->amount;

                    $result = $mt4api->execute($data);
                    // get api command status
                    if (isset($result['success'])) {
                        if ($result['success']) {
                            $response['success'] = true;
                            $order_id = $result['data']['order'];
                        }
                    }
                }

                // check api response status
                if ($response['success'] == true) {
                    // make crm(deposit)
                    $created_by = '';
                    if (strtolower(auth()->user()->type) === 'admin') {
                        $created_by = 'admin';
                    } elseif (strtolower(auth()->user()->type) === 'system') {
                        $created_by = 'system_admin';
                    } elseif (strtolower(auth()->user()->type) === 'manager') {
                        $created_by = 'manager';
                    }
                    $charge = TransactionSetting::where('transaction_type', 'wta')->where('active_status', 1)->first();
                    $withdraw = InternalTransfer::create([
                        'user_id' => $request->trader,
                        'invoice_code' => $txn_id,
                        'account_id' => $meta_account->id,
                        'charge' => TransactionService::charge('wta', $request->amount, null),
                        'amount' => $request->amount,
                        'type' => 'atw',
                        'status' => 'A',
                        'approved_by' => auth()->user()->id,
                        'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                        'platform' => $meta_account->platform,
                        'admin_log' => AdminLogService::admin_log(),
                        'created_by' => $created_by,
                    ])->id;
                    // make admin withdraw
                    $tbl_withdraw = Withdraw::create([
                        'wallet_type' => 'trader',
                        'user_id' => $request->trader,
                        'approved_by' => auth()->user()->id,
                        'transaction_type' => $request->transaction_method,
                        'amount' => $request->amount,
                        'note' => $request->note,
                        'admin_log' => AdminLogService::admin_log(),
                        'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                        'created_by' => $created_by,
                        'status' => 'A',
                        'transaction_id' => $txn_id,
                    ]);

                    // insert activity-----------------

                    $user = User::find($request->trader); //<---client email as user id
                    activity("fund withdraw")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("fund withdraw")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been  fund  withdraw");
                    // end activity log-----------------
                    if ($tbl_withdraw) {
                        return Response::json([
                            'status' => true,
                            'message' => 'Fund withdraw successfully done',
                            'account_id' => $meta_account->id,
                            'credit_id' => $withdraw,
                            'type' => 'withdraw',
                            'user_id' => $user->id,
                            'method' => $request->transaction_type,
                        ]);
                    }
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Something went wrong, pleasy try again later',
                ]);
            }

            return Response::json([
                'status' => false,
                'message' => 'Account not found!',
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
}
