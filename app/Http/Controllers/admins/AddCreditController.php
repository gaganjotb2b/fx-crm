<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\AddCredit;
use App\Models\admin\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

use App\Models\Credit;
use App\Models\User;
use App\Models\TradingAccount;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\EmailService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\systems\AdminLogService;
use Illuminate\Support\Facades\Mail;
use DateTime;

class AddCreditController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:credit management"]);
        $this->middleware(["role:finance"]);
        // system module control
        $this->middleware(AllFunctionService::access('finance', 'admin'));
        $this->middleware(AllFunctionService::access('credit_management', 'admin'));
    }
    // basic view
    // -----------------------------------------------------------------------
    public function index(Request $request)
    {
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)->first();
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }

        return view('admins.finance.credit-add', ['avatar' => $avatar]);
    }

    // add or decution credit
    // store
    // -------------------------------------------------------------------------
    public function store(Request $request)
    {
        $validation_rules = [
            'type' => 'required',
            'amount' => 'required|numeric',
            'trader' => 'required',
            'trading_account' => 'required',
            'expire_date' => 'required',
            // 'note' => 'nullable|min:10|max:100',
        ];
        // start session of form submit
        $multiple_submission = has_multi_submit('finance-credit', wait_second());
        multi_submit('finance-credit', 15);
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails() || $multiple_submission == true) {
            if ($request->ajax()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'submit_wait' => submit_wait('finance-credit', wait_second())
                ]);
            }
        } else {
            $response['success'] = false;
            $create = false;
            $txn_id = substr(encrypt($request->token), 0, 20);
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
                    'command' => 'credit_funds',
                    'data' => array(
                        'account_id' => $meta_account->account_number,
                        // "comment" => 'Fund ' . $request->type . ' #' . $txn_id,
                    ),
                );

                // count expiration date start
                $chosen_date = $request->expire_date;
                $current_date = new DateTime();
                $expiration_date = new DateTime($chosen_date);
                $expiration_days = 1;
                if ($expiration_date < $current_date) {
                    $expiration_days = 1;
                } else {
                    $diff = $current_date->diff(new DateTime($chosen_date));
                    $expiration_days = $diff->format('%a') + 1;
                }
                // count expiration date end

                if ($request->type === 'add') {
                    $data['data']['amount'] = $request->amount;
                    $data['data']['comment'] = "Credit-In";
                    $data['data']['expiration'] = strtotime('+' . $expiration_days . 'days');
                    // $data['data']['expiration'] = strtotime( '+1 month' );
                } else {
                    $data['data']['amount'] = -$request->amount;
                    $data['data']['comment'] = "Credit-Out";
                    $data['data']['expiration'] = strtotime('+' . $expiration_days . 'days');
                }
                // return $data;

                $result = $mt4api->execute($data);
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
                $create = Credit::create([
                    'trading_account' => $meta_account->id,
                    'amount' => $request->amount,
                    'type' => $request->type,
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
                $user = User::find($request->trader); //<---client email as user id
                activity($request->type . " credit")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event($request->type . " credit")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been " . $request->type . " credited");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => $response['message'],
                    'submit_wait' => submit_wait('finance-credit', wait_second()),
                    'account_id' => $meta_account->id,
                    'credit_id' => $create
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Something went wrong! please try again later.',
                    'submit_wait' => submit_wait('finance-credit', wait_second())
                ]);
            }
        }
    }

    // start credit add mail-----------------------------------------------------------------------------
    public function add_credit_mail(Request $request, $account_id, $credit_id)
    {
        $meta_account = TradingAccount::find($account_id);
        $credit = Credit::find($credit_id);
        $platform = ($meta_account->platform == 'mt4') ? "MetaTrader 4" : "MetaTrader 5";
        $user = User::where('id', $meta_account->user_id)->first();

        if ($credit->type == 'add') {
            $customMessage = "Your $platform account(" . $meta_account->account_number . ") has been credited $" . $credit->amount;
            $mail_status = EmailService::send_email('add-credit-mail', [
                'user_id' => $user->id,
                'account_number' => ($meta_account->account_number),
                'credit_date' => date('d M Y', strtotime($credit->created_at)),
                'expire_date' => date('d M Y h:i:s', strtotime($credit->expire_date)),
                'credit_type' => 'Add',
                'amount' => $credit->amount,
            ]);
        } else {
            $customMessage = "Your $platform account(" . $meta_account->account_number . ") has been deducted -$" . $credit->amount;
            $mail_status = EmailService::send_email('deduct-credit-mail', [
                'user_id' => $user->id,
                'account_number' => ($meta_account->account_number),
                'credit_date' => date('d M Y', strtotime($credit->created_at)),
                'expire_date' => date('d M Y h:i:s', strtotime($credit->expire_date)),
                'credit_type' => 'Deduct',
                'amount' => $credit->amount,
            ]);
        }
        // sending mail


        if ($mail_status) {
            return Response::json([
                'status' => true,
                'message' => 'Mail Successfully sent for Credit add',
            ]);
        }
        return Response::json([
            'status' => true,
            'message' => 'Mail sending failed, Please try again later!',
        ]);
    }
    // end: credit add mail-----------------------------------------------------------------------------

    // get client
    // --------------------------------------------------------------------------------------------------
    public function client(Request $request)
    {
        $users = User::whereIn('type', [0, 4])->get();
        $options = '';
        foreach ($users as $key => $value) {
            $options .= '<option value="' . $value->id . '">' . $value->email . '</option>';
        }
        return Response::json($options);
    }
    // get trading account
    public function trading_account(Request $request, $client_id)
    {
        $users = User::where('users.id', $client_id)->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')->first();
        $trading_account = TradingAccount::where('user_id', $client_id)->get();
        $options = '';
        foreach ($trading_account as $key => $value) {
            $options .= '<option value="' . $value->account_number . '">' . $value->account_number . '</option>';
            // $options .= '<option value="' . $value->id . '">' . $value->account_number . '</option>';
        }

        return Response::json(['options' => $options, 'users' => $users]);
    }
    // credit add / deduct-------------------------updated---------------------
    // add credit to client account
    public function credit_add(Request $request)
    {
        try {
            $validation_rules = [
                'amount' => 'required|numeric',
                'trader' => 'required|exists:users,id',
                'trading_account' => 'required',
                'expire_date' => 'required',
                'note' => 'nullable|max:100',
            ];
            // start session of form submit
            $multiple_submission = has_multi_submit('finance-credit', wait_second());
            multi_submit('finance-credit', wait_second());
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails() || $multiple_submission == true) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'submit_wait' => submit_wait('finance-credit', wait_second())
                ]);
            }
            $response['success'] = false;
            $create = false;
            $txn_id = substr(encrypt($request->token), 0, 20);
            $order_id = '';
            $meta_account = TradingAccount::where('account_number', $request->trading_account)->first();
            if (strtolower($meta_account->platform) === 'mt5') {
                $mt5_api = new Mt5WebApi();
                $result = $mt5_api->execute('CreditUpdate', [
                    'Login' => (int)$meta_account->account_number,
                    'Comment' => 'Fund ' . $request->type . ' #' . $txn_id,
                    'Balance' => (float)$request->amount,
                    'Expiration' => $request->expire_date,
                ]);
                if ($result['success'] == true) {
                    $response['success'] = true;
                }
            } else if (strtolower($meta_account->platform) == 'mt4') {
                $mt4api = new MT4API();
                $result = $mt4api->execute([
                    'command' => 'credit_funds',
                    'data' => [
                        'account_id' => $meta_account->account_number,
                        'comment' => $request->note,
                        'expiration' => strtotime($request->expire_date),
                        'amount' => (float)$request->amount,
                    ]
                ]);
                if (isset($result['success'])) {
                    if ($result['success']) {
                        $response['success'] = true;
                        $order_id = $result['data']['order'];
                    }
                }
            }
            if ($response['success'] == true) {
                $create = Credit::create([
                    'trading_account' => $request->trading_account,
                    'amount' => $request->amount,
                    'type' => strtolower('add'),
                    'expire_date' => $request->expire_date,
                    'transaction_id' => $txn_id,
                    'note' => $request->note,
                    'credited_by' => auth()->user()->id,
                    'ip' => $request->ip(),
                    'admin_log' => AdminLogService::admin_log(),
                    'order_number' => $order_id,
                ])->id;
            }

            if ($create) {
                // insert activity-----------------
                $user = User::find($request->trader); //<---client email as user id
                activity("add credit")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("add credit")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been add credit");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Credit successfully added',
                    'submit_wait' => submit_wait('finance-credit', wait_second()),
                    'account_id' => $meta_account->id,
                    'amount' => $request->amount,
                    'user_id' => $user->id,
                    'expire_date' => $request->expire_date,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong! please try again later.',
                'submit_wait' => submit_wait('finance-credit', wait_second())
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error'
            ]);
        }
    }
    // deduct credit
    public function credit_deduct(Request $request)
    {
        try {
            $validation_rules = [
                'amount' => 'required|numeric',
                'trader' => 'required',
                'trading_account' => 'required',
                'note' => 'nullable|min:10|max:100',
            ];
            // start session of form submit
            $multiple_submission = has_multi_submit('finance-credit-deduct', wait_second());
            multi_submit('finance-credit-deduct', 15);
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails() || $multiple_submission == true) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'submit_wait' => submit_wait('finance-credit-deduct', wait_second())
                ]);
            }
            $response['success'] = false;
            $create = false;
            $order_id = '';
            $txn_id = substr(encrypt($request->token), 0, 20);
            $meta_account = TradingAccount::where('account_number', $request->trading_account)->first();
            if (strtolower($meta_account->platform) === 'mt5') {
                $mt5_api = new Mt5WebApi();
                $result = $mt5_api->execute('CreditUpdate', [
                    'Login' => (int)$meta_account->account_number,
                    'Comment' => 'Fund ' . $request->type . ' #' . $txn_id,
                    'Balance' => -(float)$request->amount,
                ]);
                if ($result['success'] == true) {
                    $response['success'] = true;
                }
            } else if (strtolower($meta_account->platform) == 'mt4') {
                $mt4api = new MT4API();
                $result = $mt4api->execute([
                    'command' => 'credit_funds',
                    'data' => [
                        'account_id' => $meta_account->account_number,
                        'comment' => $request->note,
                        'expiration' => strtotime($request->expire_date),
                        'amount' => -(float)$request->amount,
                    ],
                ]);
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
                $create = Credit::create([
                    'trading_account' => $request->trading_account,
                    'amount' => $request->amount,
                    'type' => 'deduct',
                    'transaction_id' => $txn_id,
                    'note' => $request->note,
                    'credited_by' => auth()->user()->id,
                    'ip' => $ip,
                    'order_number' => $order_id,
                    'admin_log' => AdminLogService::admin_log(),
                ])->id;
            }

            if ($create) {
                // insert activity-----------------
                $user = User::find($request->trader); //<---client email as user id
                activity("deduct credit")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("deduct credit")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been deduct credit");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Credit successfully deducted',
                    'submit_wait' => submit_wait('finance-credit-deduct', wait_second()),
                    'account_id' => $meta_account->id,
                    'user_id' => $request->trader,
                    'amount' => $request->amount,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong! please try again later.',
                'submit_wait' => submit_wait('finance-credit-deduct', wait_second())
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    public function credit_add_mail(Request $request)
    {
        try {
            $request_data = json_decode($request->getContent());
            $trading_account = TradingAccount::find($request_data->account_id);
            $mail_status = EmailService::send_email('add-credit-mail', [
                'user_id' => $request_data->user_id,
                'amount' => $request_data->amount,
                'account_number' => $trading_account->account_number,
                'credit_type' => 'Deposit',
                'expire_date' => date('d-M-Y ', strtotime($request->expire_date)),
                'credit_date' => date('d-M-Y ', strtotime(now()))
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
                'message' => 'Mail sending failed.'
            ]);
        }
    }
    public function credit_deduct_mail(Request $request)
    {
        try {
            $request_data = json_decode($request->getContent());
            $trading_account = TradingAccount::find($request_data->account_id);
            $mail_status = EmailService::send_email('deduct-credit-mail', [
                'user_id' => $request_data->user_id,
                'amount' => $request_data->amount,
                'account_number' => $trading_account->account_number,
                'credit_type' => 'Withdraw',
                'credit_date' => date('d-M-Y ', strtotime(now()))
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
                'message' => 'Mail sending failed.'
            ]);
        }
    }
}
