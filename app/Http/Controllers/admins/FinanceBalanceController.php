<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ManagerUser;
use App\Models\Withdraw;
use App\Models\Deposit;
use App\Models\ClientGroup;
use App\Models\StaffTransaction;
use App\Models\BankAccount;
use App\Models\TradingAccount;
use App\Models\UserDescription;
use Illuminate\Support\Facades\Crypt;
use App\Rules\ThrottleSubmission;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\EmailService;
use App\Services\systems\AdminLogService;

class FinanceBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:balance management"]);
        $this->middleware(["role:finance"]);
        // system module control
        $this->middleware(AllFunctionService::access('finance', 'admin'));
        $this->middleware(AllFunctionService::access('balance_management', 'admin'));
    }
    //Basic view
    // ---------------------------------------------------------------------
    public function index(Request $request)
    {
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)->first();
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }
        return view('admins.finance.balance-management', ['avatar' => $avatar]);
    }

    // get client
    // -----------------------------------------------------------------------------------
    public function get_client(Request $request, $user_type)
    {
        $users = User::where('type', $user_type);
        if (auth()->user()->type === 'manager') {
            $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
            $users = $users->whereIn('users.id', $users_id);
        }
        $users = $users->get();
        $client_options = '';
        foreach ($users as $value) :
            $client_options .= '<option value="' . $value->id . '">' . $value->email . '</option>';
        endforeach;
        $data = [
            'status' => true,
            'users' => $client_options
        ];
        return Response::json($data);
    }

    // get finance details by user 
    // ----------------------------------------------------------------------------------------
    public function get_finance(Request $request)
    {
        try {
            if (is_numeric($request->client)) {
                $users = User::where('id', $request->client)->first();
            } else {
                $users = User::where('email', $request->client)->first();
            }

            $last_deposit = Deposit::Where('user_id', $users->id)->latest('created_at')->first();
            $last_withdraw = Withdraw::Where('user_id', $users->id)->latest('created_at')->first();
            $trading_accounts = TradingAccount::where('user_id', $users->id)->get();
            $account_options = "";
            foreach($trading_accounts as $row){
                $client_group = ClientGroup::find($row->group_id);
                // $account_options.= "<option value='".$row->id."'>'.$row->account_number.'('. $client_group->group_id.')</option>"; 
                $account_options .= "<option value='{$row->id}'>{$row->account_number} ({$client_group->group_id})</option>";

                
            }
            // last deposit approved status
            if (isset($last_deposit->approved_status) && $last_deposit->approved_status === 'A') {
                $last_deposit_status = 'Approved';
            } elseif (isset($last_deposit->approved_status) && $last_deposit->approved_status === 'P') {
                $last_deposit_status = 'Pending';
            } elseif (isset($last_deposit->approved_status) && $last_deposit->approved_status === 'D') {
                $last_deposit_status = 'Decline';
            } else {
                $last_deposit_status = 'N/A';
            }

            // last withdraw approved status
            if (isset($last_withdraw->approved_status) && $last_withdraw->approved_status === 'A') {
                $last_withdraw_status = 'Approved';
            } elseif (isset($last_withdraw->approved_status) && $last_withdraw->approved_status === 'P') {
                $last_withdraw_status = 'Pending';
            } elseif (isset($last_withdraw->approved_status) && $last_withdraw->approved_status === 'D') {
                $last_withdraw_status = 'Decline';
            } else {
                $last_withdraw_status = 'N/A';
            }
            $wallet_balance = 0;
            if (strtolower($request->client_type) === 'trader') {
                $wallet_balance = BalanceSheetService::trader_wallet_balance($users->id);
            } else {
                $wallet_balance = BalanceSheetService::ib_wallet_balance($users->id);
            }
            $data = [
                'status' => true,
                'wallet_balance' => $wallet_balance,
                'last_deposit' => (isset($last_deposit->amount)) ? $last_deposit->amount : 0,
                'last_withdraw' => (isset($last_withdraw->amount)) ? $last_withdraw->amount : 0,
                'last_deposit_date' => (isset($last_deposit->created_at)) ? date('d-M-y h:i:s A', strtotime($last_deposit->created_at)) : 'yy-mm-dd',
                'last_withdraw_date' => (isset($last_withdraw->created_at)) ? date('d-M-y h:i:s A', strtotime($last_withdraw->created_at)) : 'yy-mm-dd',
                'user_name' => $users->name,
                'user_type' => $request->client_type,
                'withdraw_status' => $last_withdraw_status,
                'deposit_status' => $last_deposit_status,
                'avatar' => AllFunctionService::user_profile($users->id),
                // 'trading_account' => $trading_account,
                'account_options' => $account_options,
            ];
            return Response::json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // *************************************************************************************\
    // client financ status / v2
    public function finance_status(Request $request)
    {
        try {
            $user_type = $request->client_type;
            $user = User::select('name')->where('id', $request->client)->first();
            switch ($user_type) {
                case 'IB':
                    # for ib finance
                    $ib_total_balance = BalanceSheetService::ib_wallet_balance($request->client);
                    $ib_last_withdraw = BalanceService::ib_last_withdraw($request->client);
                    // ib withdraw status
                    $ib_withdraw_status = '';
                    if ($ib_last_withdraw['status'] === 'A') {
                        $ib_withdraw_status = 'Approved';
                    } elseif ($ib_last_withdraw['status'] === 'P') {
                        $ib_withdraw_status = 'Pending';
                    } elseif ($ib_last_withdraw['status'] === 'D') {
                        $ib_withdraw_status = 'Declined';
                    }

                    return Response::json(
                        [
                            'total_balance' => $ib_total_balance,
                            'last_withdraw' => $ib_last_withdraw['amount'],
                            'last_withdraw_status' => $ib_withdraw_status,
                            'user_name' => $user->name,
                        ]
                    );
                    break;

                default:
                    # for trader finance
                    $trader_total_balance = BalanceSheetService::trader_wallet_balance($request->client);
                    $trader_last_withdraw = BalanceService::trader_last_withdraw($request->client);
                    $trader_last_deposit = BalanceService::trader_last_deposit($request->client);
                    // deposit status
                    $deposit_status = '';
                    if ($trader_last_deposit['status'] === 'A') {
                        $deposit_status = 'Approved';
                    } elseif ($trader_last_deposit['status'] === 'D') {
                        $deposit_status = 'Declined';
                    } elseif ($trader_last_deposit['status'] === 'P') {
                        $deposit_status = 'Pending';
                    }
                    // withdraw status
                    $withdraw_status = '';
                    if ($trader_last_withdraw['status'] === 'A') {
                        $withdraw_status = 'Approved';
                    } elseif ($trader_last_withdraw['status'] === 'P') {
                        $withdraw_status = 'Pending';
                    } elseif ($trader_last_withdraw['status'] === 'D') {
                        $withdraw_status = 'Declined';
                    }
                    return Response::json([
                        'total_balance' => $trader_total_balance,
                        'last_withdraw' => $trader_last_withdraw['amount'],
                        'last_withdraw_status' => $withdraw_status,
                        'last_deposit' => $trader_last_deposit['amount'],
                        'last_deposit_status' => $deposit_status,
                        'user_name' => $user->name,
                    ]);
                    break;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    // Client Bank account 
    // -----------------------------------------------------------------------------------------------
    public function banks(Request $request, $id)
    {
        $banks = BankAccount::where('user_id', $id)->get();
        $bank_options = '';
        foreach ($banks as $key => $value) {
            $bank_options .= '<option value="' . $value->id . '">' . $value->bank_name . '</option>';
        }
        if ($banks->count()) {
            return Response::json(['status' => true, 'banks' => $bank_options]);
        } else {
            return Response::json(['status' => false, 'message' => 'somthing went wrong! please try again later.']);
        }
    }
    // finance balance add version 2
    public function add_balance(Request $request)
    {
        try {
            $validation_rules = [
                'client_type' => 'required',
                'client' => 'required',
                'transaction_method' => 'required',
                'amount' => 'required|min:1',
                'invoice_code' => 'nullable',
                'note' => 'nullable|max:191',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following error',
                    'errors' => $validator->errors(),
                ]);
            }
            $admin_type = '';
            if (auth()->user()->type === 'admin') {
                $admin_type = 'admin';
            } elseif (auth()->user()->type === 'manager') {
                $admin_type = 'manager';
            } elseif (auth()->user()->type === 'system') {
                $admin_type = 'system_admin';
            }
            switch (strtolower($request->client_type)) {
                case 'ib':
                    # ib wallet balance add
                    $deposit = Deposit::create([
                        'user_id' => $request->client,
                        'invoice_id' => $request->invoice_code ?? "",
                        'transaction_type' => $request->transaction_method,
                        'amount' => $request->amount,
                        'admin_log' => AdminLogService::admin_log(),
                        'approved_status' => 'A',
                        'approved_by' => auth()->user()->id,
                        'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                        'wallet_type' => 'ib',
                        'created_by' => $admin_type,
                        'note' => $request->note,
                    ]);
                    break;

                default:
                    # trader wallet balance add
                    $deposit = Deposit::create([
                        'user_id' => $request->client,
                        'invoice_id' => $request->invoice_code ?? "",
                        'transaction_type' => $request->transaction_method,
                        'amount' => $request->amount,
                        'admin_log' => AdminLogService::admin_log(),
                        'approved_status' => 'A',
                        'approved_by' => auth()->user()->id,
                        'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                        'wallet_type' => 'trader',
                        'created_by' => $admin_type,
                        'note' => $request->note,
                    ]);
                    break;
            }
            if ($deposit) {
                // insert activity-----------------
                $user = User::find(auth()->user()->id); //<---client email as user id
                activity($request->client_type . " wallet balance add")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event($request->client_type . " balance add")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been added wallet balance");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Wallet balance successfully add to ' . $request->client_type,
                    'user_id' => $request->client,
                    'amount' => $request->amount,
                    'client_type' => $request->client_type,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Transaction failed, please try again later'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, contact for support'
            ]);
        }
    }
    // add balance mail 
    // *********************************************************************
    // add balance mail
    public function mail_add_balance(Request $request)
    {
        try {
            $request_data = json_decode($request->getContent());
            if ($request_data->client_type) {
                $wallet_balance = BalanceSheetService::ib_wallet_balance($request_data->user_id);
            } else {
                $wallet_balance = BalanceSheetService::trader_wallet_balance($request_data->user_id);
            }
            $mail_status = EmailService::send_email('wallet-deposit', [
                'user_id' => $request_data->user_id,
                'deposit_amount' => $request_data->amount,
                'total_balance' => $wallet_balance,
                'transfer_date' => date('Y-m-d h:i:s', strtotime(now())),
            ]);
            if ($mail_status) {
                return Response::json([
                    'status' => true,
                    'message' => 'Mail successfully send to client.'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Mail sending failed.'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, Mail sending failed.'
            ]);
        }
    }

    // balance deduct/withdraw
    public function deduct_balance(Request $request)
    {
        try {
            $validation_rules = [
                'client_type' => 'required',
                'client' => 'required',
                'amount' => 'required|min:1',
                'invoice_code' => 'nullable|max:64',
                'transaction_method' => 'required',
                'note' => 'nullable|max:100',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // check client have available balance
            if (strtolower($request->client_type) === 'ib') {
                $wallet_balance = BalanceSheetService::ib_wallet_balance($request->client);
            } else {
                $wallet_balance = BalanceSheetService::trader_wallet_balance($request->client);
            }
            if ($wallet_balance <= 0 || $request->amount > $wallet_balance) {
                return Response::json([
                    'status' => false,
                    'message' => 'Client dont haave available balance',
                    'errors' => ['amount' => 'client dont have available balance'],
                ]);
            }
            // make withdraw for ib
            // *****************************************************
            $admin_type = '';
            if (auth()->user()->type === 'admin') {
                $admin_type = 'admin';
            } elseif (auth()->user()->type === 'manager') {
                $admin_type = 'manager';
            } elseif (auth()->user()->type === 'system') {
                $admin_type = 'system_admin';
            }
            if (strtolower($request->client_type) === 'ib') {
                $withdraw = Withdraw::create([
                    'user_id' => $request->client,
                    'transaction_id' => $request->invoice_code,
                    'transaction_type' => $request->transaction_method,
                    'amount' => $request->amount,
                    'charge' => 0,
                    'approved_status' => 'A',
                    'note' => $request->note,
                    'approved_by' => auth()->user()->id,
                    'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                    'admin_log' => AdminLogService::admin_log(),
                    'wallet_type' => 'ib',
                    'created_by' => $admin_type,
                ]);
            } else {
                $withdraw = Withdraw::create([
                    'user_id' => $request->client,
                    'transaction_id' => $request->invoice_code,
                    'transaction_type' => $request->transaction_method,
                    'amount' => $request->amount,
                    'charge' => 0,
                    'approved_status' => 'A',
                    'note' => $request->note,
                    'approved_by' => auth()->user()->id,
                    'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                    'admin_log' => AdminLogService::admin_log(),
                    'wallet_type' => 'trader',
                    'created_by' => $admin_type,
                ]);
            }

            // return success
            if ($withdraw) {
                // insert activity-----------------
                $user = User::find(auth()->user()->id); //<---client email as user id
                activity($request->client_type . " wallet balance withdraw")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event($request->client_type . " balance withdraw")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been withdraw wallet balance");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Wallet balance successfully withdraw from ' . $request->client_type,
                    'user_id' => $request->client,
                    'amount' => $request->amount,
                    'client_type' => $request->client_type,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Transaction failed, please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, Mail sending failed.'
            ]);
        }
    }
    // ********************************************************
    // withdraw balance mail
    // deduct balance mail
    public function mail_withdraw_balance(Request $request)
    {
        try {
            $request_data = json_decode($request->getContent());
            if (strtolower($request_data->client_type) === 'ib') {
                $wallet_balance = BalanceSheetService::ib_wallet_balance($request_data->user_id);
            } else {
                $wallet_balance = BalanceSheetService::trader_wallet_balance($request_data->user_id);
            }
            $mail_status = EmailService::send_email('wallet-withdraw', [
                'user_id' => $request_data->user_id,
                'withdraw_amount' => $request_data->amount,
                'total_balance' => $wallet_balance,
                'transfer_date' => date('Y-m-d h:i:s', strtotime(now())),
            ]);
            if ($mail_status) {
                return Response::json([
                    'status' => true,
                    'message' => 'Mail successfully send to client.'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Mail sending failed.'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, Mail sending failed.'
            ]);
        }
    }
}
