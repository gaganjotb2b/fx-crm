<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Mail\ChangeMasterPassword;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\Mt5WebApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;
use App\Models\PasswordSettings;
use App\Services\AllFunctionService;
use App\Services\EmailService;
use App\Services\MT4API;
use App\Services\VertexApiCall;

class TradingAccountSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('trading_account_settings', 'trader'));
        $this->middleware(AllFunctionService::access('trading_accounts', 'trader'));
    }
    // basic view
    public function trading_account_settings(Request $request)
    {
        $PasswordSettings = PasswordSettings::first();
        return view('traders.trading-account.trading-account-settings', compact('PasswordSettings'));
    }
    public function fetch_data_dt(Request $request)
    {
        $draw = $request->input('draw');
        $search =  $_GET['search']['value'];
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $_GET['order'][0]["column"];
        $orderDir = $_GET["order"][0]["dir"];
        $columns = ['account_number', 'master_password', 'investor_password', 'client_type', 'platform', 'leverage', 'balance', 'balance', 'created_at'];
        $orderby = $columns[$order];
        // select type= 0 for trader 
        $result = TradingAccount::where('user_id', auth()->user()->id)->whereNotNull('account_number')->where('account_status', 1);
        if ($search != "") {
            $result = $result
                ->where('account_number', 'LIKE', '%' . $search . '%')
                ->orWhere('master_password', 'LIKE', '%' . $search . '%')
                ->orWhere('investor_password', 'LIKE', '%' . $search . '%')
                ->orWhere('client_type', 'LIKE', '%' . $search . '%')
                ->orWhere('platform', 'LIKE', '%' . $search . '%')
                ->orWhere('leverage', 'LIKE', '%' . $search . '%')
                ->orWhere('created_at', 'LIKE', '%' . $search . '%');
        }
        $count = $result->count(); // <------count total rows
        $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
        $data = array();
        $i = 0;

        $password_visibility = (get_platform() == "mt4") ? "d-none" : "";
        foreach ($result as $key => $value) {
            $PasswordSettings = PasswordSettings::first();
            $master_password_condi = '';
            $investor_password_condi = '';
            $leverage_change_condi = '';
            if ($PasswordSettings->master_password == 1) {
                $master_password_condi = '<li>
                    <a class="dropdown-item btn-change-password" href="javascript:;" data-id="' . encrypt($value->id) . '">
                        Change Master Password
                    </a>
                </li> ';
            }
            if ($PasswordSettings->investor_password == 1) {
                $investor_password_condi = '<li class="d-none">
                                                <a class="dropdown-item btn-inv-password" href="javascript:;" data-id="' . (encrypt($value->id)) . '">
                                                    Change Investor Password
                                                </a>
                                            </li>';
            }
            if ($PasswordSettings->leverage == 1) {
                $leverage_change_condi = '<li class="d-none">
                                                <a class="dropdown-item btn-change-leverage" href="javascript:;" data-server="' . $value->platform . '" data-accounttype="' . $value->client_type . '" data-clientgroup="' . encrypt($value->group_id) . '" data-id="' . encrypt($value->id) . '">
                                                    Chanage Leverage
                                                </a>
                                            </li> ';
            }
            $PasswordSettingsCondi = $data[$i]["action"]    = '<div class="d-flex justify-content-around">
                    <a href="#" class="more-actions dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="navbarDropdownMenuLink' . $value->id . '">
                        <i class="fas fa-ellipsis-v"></i>
                    </a> 
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink' . $value->id . '">                                              
                        ' . $master_password_condi . '                                       
                        ' . $investor_password_condi . '                                       
                        ' . $leverage_change_condi . '                                              
                    </ul>
                </div>';

            // tabl column
            // -------------------------------------
            $data[$i]["account"]   = $value->account_number;
            $data[$i]["password"]   = '<div class="d-flex justify-content-around">
                                            <a href="#" class="more-actions dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="PasswordDropdownMenuLink' . $value->id . '">
                                                <i class="fas fa-eye"></i>
                                            </a> 
                                            <ul class="dropdown-menu" aria-labelledby="PasswordDropdownMenuLink' . $value->id . '">                                              
                                                <li class="dd d-none">
                                                    <a class="dropdown-item btn-show-password" href="javascript:;" data-colomn="investor_password" data-id="' . encrypt($value->id) . '">
                                                        Investor Password
                                                    </a>
                                                </li>                                             
                                                <li class="d-none ' . $password_visibility . '">
                                                    <a class="dropdown-item btn-show-password" data-colomn="phone_password" href="javascript:;" data-id="' . encrypt($value->id) . '">
                                                       Phone Password
                                                    </a>
                                                </li>                                             
                                                <li>
                                                    <a class="dropdown-item btn-show-password" data-colomn="master_password" href="javascript:;" data-id="' . encrypt($value->id) . '">
                                                       Master Password
                                                    </a>
                                                </li>                                             
                                            </ul>
                                        </div>';
            $data[$i]["account_type"]   = ucwords($value->client_type);
            $data[$i]["server"]   = strtoupper($value->platform);
            $data[$i]["leverage"]   = $value->leverage;
            $data[$i]["balance"]   = '<a class="dropdown-item btn-load-balance" href="javascript:;" data-id="' . encrypt($value->id) . '">
                                        <span class="d-flex justify-content-between">
                                            <span class="balance-value amount">0</span>
                                            <span><i class="fas fa-sync"></i></span>
                                        </span
                                      </a>';
            $data[$i]["equity"]   = '<a class="dropdown-item btn-load-equity" href="javascript:;" data-id="' . encrypt($value->id) . '">
                                        <span class="d-flex justify-content-between">
                                            <span class="balance-value amount">0</span>
                                            <span><i class="fas fa-sync"></i></span>
                                        </span
                                    </a>';
            $data[$i]["action"]   = '<div class="d-flex justify-content-around">
                                        <a href="#" class="more-actions dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="ActionDropdownMenuLink' . $value->id . '">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a> 
                                        <ul class="dropdown-menu" aria-labelledby="ActionDropdownMenuLink' . $value->id . '">
                                            <li>
                                                <a class="dropdown-item btn-change-password" href="javascript:;" data-id="' . encrypt($value->id) . '">
                                                    <i class="fas fa-key me-2"></i>Change Password
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item btn-change-leverage d-none" href="javascript:;" data-id="' . encrypt($value->id) . '">
                                                    <i class="fas fa-chart-line me-2"></i>Change Leverage
                                                </a>
                                                <a class="dropdown-item btn-change-leverage" href="javascript:;" data-server="' . $value->platform . '" data-accounttype="' . $value->client_type . '" data-clientgroup="' . encrypt($value->group_id) . '" data-id="' . encrypt($value->id) . '">
                                                    Chanage Leverage
                                                </a>
                                                
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item btn-delete-account text-danger" href="javascript:;" data-id="' . encrypt($value->id) . '" data-account="' . $value->account_number . '">
                                                    <i class="fas fa-trash me-2"></i>Delete Account
                                                </a>
                                            </li>
                                        </ul>
                                    </div>';
            $PasswordSettingsCondi;
            $i++;
        }
        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $count, 'recordsFiltered' => $count);
        $output['data'] = $data;
        return Response::json($output);
    }
    // change trading account master password
    public function change_password(Request $request)
    {
        try {
            $status_data = [
                'status' => false
            ];
            $validation_rules = [
                'current_password' => 'required|min:4|max:32',
                'new_password' => 'required|min:4|max:32',
                'confirm_new_password' => 'required|min:4|max:32|same:new_password',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                $status_data['errors'] =  $validator->errors();
                $status_data['message'] = 'Please fix the following errors!';
                return Response::json($status_data);
            }
            $trading_account = TradingAccount::find(decrypt($request->account));
            // check investor password
            if ($request->op === 'investor-password') {
                if ($request->current_password !== $trading_account->investor_password) {
                    $errors['current_password'] = 'Current investor password not match!';
                    $status_data['errors'] =  $errors;
                    $status_data['message'] = 'Please fix the following errors!';
                    return Response::json($status_data);
                }
            }
            // check master password
            if ($request->op === 'password') {
                if ($request->current_password !== $trading_account->master_password) {
                    $errors['current_password'] = 'Current password not match!';
                    $status_data['errors'] =  $errors;
                    $status_data['message'] = 'Please fix the following errors!';
                    return Response::json($status_data);
                }
            }
            if (strtolower($trading_account->platform) == 'mt4') {
                // mt4 account password change
                $mt4api = new MT4API();
                if ($request->op === 'investor-password') {
                    $data = array(
                        'command' => 'user_password_set',
                        'data' => array(
                            'account_id' => (int)$trading_account->account_number,
                            'password' => $request->new_password,
                            'change_investor' => 1
                        ),
                    );
                } else {
                    $data = array(
                        'command' => 'user_password_set',
                        'data' => array(
                            'account_id' => (int)$trading_account->account_number,
                            'password' => $request->new_password,
                            'change_investor' => 0
                        ),
                    );
                }
                $result = $mt4api->execute($data, $trading_account->client_type);
            } else {
                // mt5 account change
                $mt5_api = new Mt5WebApi();
                // change password
                if ($request->op === 'password') {
                    $action = 'AccountChangePassword';

                    $data = array(
                        "Login" => (int)$trading_account->account_number,
                        "Password" => $request->new_password,
                    );
                }
                // change investor pasword
                if ($request->op === 'investor-password') {
                    $action = 'AccountChangeInvestorPassword';

                    $data = array(
                        "Login" => (int)$trading_account->account_number,
                        "Password" => $request->new_password,
                    );
                }

                $result = $mt5_api->execute($action, $data);
            }
            $updated = 0;
            if (isset($result['success'])) {
                if ($result['success']) {
                    if ($request->op === 'password') {
                        $trading_account->master_password = $request->new_password;
                    }
                    if ($request->op === 'investor-password') {
                        $trading_account->investor_password = $request->new_password;
                    }

                    $updated = $trading_account->save();
                }
            }
            if ($updated) {
                $user = User::find(auth()->user()->id);
                if ($request->op === 'investor-password') {
                    //<---client email as user id
                    activity("Change investor password")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("change investor password")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been change change investor password");
                    // end activity log----------------->>
                    $email_success = EmailService::send_email('change-investor-password', [
                        'user_id' => $user->id,
                        'clientInvestorPassword'    => ($user) ? $request->new_password : '',
                        'clientAccountNo'    => $trading_account->account_number
                    ]);
                } else {
                    //<---client email as user id
                    activity("Change master password")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("change master password")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been change change master password");
                    // end activity log----------------->>
                    // end activity log----------------->>
                    $email_success = EmailService::send_email('change-master-password', [
                        'user_id' => $user->id,
                        'master_password'    => ($user) ? $request->new_password : '',
                        'clientAccountNo'    => $trading_account->account_number
                    ]);
                }
                $status_data['status'] = true;
                $status_data['message'] = 'Password has been changed successfully';
                return Response::json($status_data);
            }
            $status_data['status'] = false;
            $status_data['message'] = 'Server Error please try again';
            return Response::json($status_data);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // change leverage
    public function change_leverage(Request $request)
    {
        try {
            $status_data['status'] = false;
            // mt4 leverage-----------
            $trading_account = TradingAccount::where('trading_accounts.id', decrypt($request->account))
                ->select('trading_accounts.*', 'group_name')
                ->join('client_groups', 'trading_accounts.group_id', '=', 'client_groups.id')->first();
            $user_details = User::where('users.id', $trading_account->user_id)
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                ->select(
                    'users.name',
                    'users.email',
                    'users.phone',
                    'user_descriptions.city',
                    'user_descriptions.state',
                    'user_descriptions.zip_code',
                    'user_descriptions.address',
                    'countries.name as country',
                )
                ->first();
            if ($user_details == "") {
                return Response::json([
                    'status' => false,
                    'message' => 'User details not found, Invalid request'
                ]);
            }
            if (strtolower($trading_account->platform) == 'mt4') {
                // change from api
                $mt4api = new MT4API();
                $data = array(
                    'command' => 'user_update',
                    'data' => array(
                        'account_id' => $trading_account->account_number,
                        'leverage' => $request->leverage
                    ),
                );
                $pass_result = $mt4api->execute($data);
                $updated = 0;
                // change from local system
                if (isset($pass_result['success'])) {
                    if ($pass_result['success']) {
                        $trading_account->leverage = $request->leverage;
                        $updated = $trading_account->save();
                    }
                }
                if ($updated) {
                    $user = User::find(auth()->user()->id);
                    //<---client email as user id
                    activity("Change leverage")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("change leverage")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been change account leverage");
                    // end activity log----------------->>
                    $status_data['status'] = true;
                    $status_data['message'] = 'Leverage has been changed successfully.';
                    // email sending
                    $email_success = EmailService::send_email('change-leverage', [
                        'user_id' => $user->id,
                        'leverage'    => ($user) ? $request->leverage : '',
                        'clientAccountNo'    => $trading_account->account_number
                    ]);

                    $status_data['message'] = 'Leverage has been changed, please check your mail';
                    return Response::json($status_data);
                }
                $status_data['status'] = false;
                $status_data['message'] = $pass_result['info']['message'];
                return Response::json($status_data);
            }
            // mt5 leverage--------------- 
            else if (strtolower($trading_account->platform) == 'mt5') {

                $mt5_api = new Mt5WebApi();
                $result = $mt5_api->execute('AccountUpdate', [
                    "Login" => (int)$trading_account->account_number,
                    "Leverage" => (int)$request->leverage,
                    "Group" => $trading_account->group_name,
                    "Comment" => "Leverage update",
                    "Phone" => ($user_details->phone) ? $user_details->phone : 'na',
                    "Name" => ($user_details->name) ? $user_details->name : 'na',
                    "Country" => ($user_details->country) ? $user_details->country : 'na',
                    "City" => ($user_details->city) ? $user_details->city : 'na',
                    "State" => ($user_details->state) ? $user_details->state : 'na',
                    "ZipCode" => ($user_details->zip_code) ? $user_details->zip_code : 'na',
                    "Address" => ($user_details->address) ? $user_details->address : 'na',
                    'Password' => $trading_account->master_password,
                    'PasswordPhone' => $trading_account->phone_password,
                    'PasswordInvestor' => $trading_account->investor_password,
                ]);
                $updated = 0;
                if (isset($result['success'])) {
                    if ($result['success']) {
                        $trading_account->leverage = $request->leverage;
                        $updated = $trading_account->save();
                    }
                }
                if ($updated) {
                    $user = User::find(auth()->user()->id);
                    //<---client email as user id
                    activity("Change leverage")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("change leverage")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been change account leverage");
                    // end activity log----------------->>
                    $status_data['status'] = true;
                    $status_data['message'] = 'Leverage has been changed successfully.';
                    // email sending

                    $email_success = EmailService::send_email('change-leverage', [
                        'user_id' => $user->id,
                        'leverage'    => ($user) ? $request->leverage : '',
                        'clientAccountNo'    => $trading_account->account_number
                    ]);

                    if ($email_success) {
                        $status_data['message'] = 'Leverage has been changed, please check your mail';
                    }
                    return Response::json($status_data);
                }
                $status_data['status'] = false;
                $status_data['message'] = 'Network error please try again later.';
                return Response::json($status_data);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // check balance equity--------------
    public function balance_equity(Request $request)
    {
        try {
            $trading_account = TradingAccount::find(decrypt($request->id));
            if (strtolower($trading_account->platform) === 'mt4') {

                $mt4api = new MT4API();
                $data = array(
                    'command' => 'user_data_get',
                    'data' => array('account_id' => $trading_account->account_number),
                );

                $result = $mt4api->execute($data, $trading_account->client_type);

                if ($result["success"]) {
                    $result1 = $result['data'];
                    $response['success'] = true;
                    $response['credit'] = 0;
                    $response['equity'] = $result1['equity'];
                    $response['balance'] = $result1['balance'];
                    $response['free_margin'] = 0;
                    $response['amount']  = ($request->search === 'balance') ? $result1['balance'] : $result1['equity'];
                    $response['platform_logo'] = asset('trader-assets/assets/img/mt4_icon.png');
                    $response['account_number'] = $trading_account->account_number ?? "";
                    $response['leverage'] = $trading_account->leverage ?? "";
                    return Response::json($response);
                } else {
                    $response['message'] = $result['error']['Description'];
                    $response['credit'] = 0;
                    $response['equity'] = 0;
                    $response['balance'] = 0;
                    $response['free_margin'] = 0;
                    $response['amount']  = 0;
                    $response['platform_logo'] = asset('trader-assets/assets/img/mt4_icon.png');
                    $response['account_number'] = $trading_account->account_number ?? "";
                    $response['leverage'] = $trading_account->leverage ?? "";
                    // return Response::json([
                    //     'success' => false,
                    //     'message' => $result['info']['message']
                    // ]);
                }
            } elseif (strtolower($trading_account->platform) === 'vertex') {
                $vertex = new VertexApiCall();
                $vertex->execute('BackofficeLogin');
                $result = $vertex->execute('GetAccountSummary', [
                    'AccountId' => $trading_account->account_number
                ]);
                // return $result;
                if ($result['success'] == true) {
                    return Response::json([
                        'credit' => $result['data']->Credit,
                        'equity' => $result['data']->Equity,
                        'balance' => $result['data']->Balance,
                        'free_margin' => isset($result['data']->FreeMargin) ? $result['data']->FreeMargin : 0,
                        'amount' => ($request->search === 'balance') ? $result['data']->Balance : $result['data']->Equity,
                    ]);
                } else {
                    return Response::json([
                        'success' => true,
                        'balcnec' => 0,
                        'equity' => 0,
                    ]);
                }
            }
            // for mt5 api-------------------
            else {

                $mt5_api = new Mt5WebApi();
                $action = 'AccountGetMargin';

                $data = array(
                    "Login" => $trading_account->account_number
                );
                $result = $mt5_api->execute($action, $data);
                $mt5_api->Disconnect();

                if (isset($result['success'])) {
                    if ($result['success']) {
                        $response['success'] = true;
                        $response['credit'] = $result['data']['Credit'];
                        $response['equity'] = $result['data']['Equity'];
                        $response['balance'] = $result['data']['Balance'];
                        $response['free_margin'] = isset($result['data']['MarginFree']) ? $result['data']['MarginFree'] : 0;
                        $response['amount']  = ($request->search === 'balance') ? $result['data']['Balance'] : $result['data']['Equity'];
                        $response['platform_logo'] = asset('trader-assets/assets/img/mt5_icon.png');
                        $response['account_number'] = $trading_account->account_number ?? "";
                        $response['leverage'] = $trading_account->leverage ?? "";
                        return Response::json($response);
                    } else if (isset($result['error'])) {
                        $response['message'] = $result['error']['Description'];
                        $response['credit'] = 0;
                        $response['equity'] = 0;
                        $response['balance'] = 0;
                        $response['free_margin'] = 0;
                        $response['amount']  = 0;
                        $response['platform_logo'] = asset('trader-assets/assets/img/mt5_icon.png');
                        $response['account_number'] = $trading_account->account_number ?? "";
                        $response['leverage'] = $trading_account->leverage ?? "";
                    }
                }

                return Response::json($response);
            }
        } catch (\Throwable $th) {
            throw $th;
            $response['message'] = $result['error']['Description'];
            $response['credit'] = 0;
            $response['equity'] = 0;
            $response['balance'] = 0;
            $response['free_margin'] = 0;
            $response['amount']  = 0;
            $response['platform_logo'] = asset('trader-assets/assets/img/mt5_icon.png');
            $response['account_number'] = $trading_account->account_number ?? "";
            $response['leverage'] = $trading_account->leverage ?? "";
            return Response::json($response);
        }
    }

    public function show_password(Request $request)
    {
        $status_data = [
            'status' => false
        ];
        $validation_rules = [
            'transaction_password' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            $status_data['errors'] =  $validator->errors();
            $status_data['message'] = 'Please fix the following errors!';
            return Response::json($status_data);
        } else {
            $pass_type = $request->pass_type;
            $trading_account = TradingAccount::select('user_id', $pass_type)->where('id', $request->account)->first();

            $user = User::select('transaction_password')->where('id', $trading_account->user_id)->first();
            if (Hash::check($request->transaction_password, $user->transaction_password)) {
                $status_data = [
                    'status' => true,
                    'password' => $trading_account->$pass_type,
                ];
                return Response::json($status_data);
            } else {
                $status_data = [
                    'status' => false,
                    'message' => 'Transaction Password Not match!',
                ];
                return Response::json($status_data);
            }
        }
    }

    //show all trading password
    public function showAllpassword(Request $request)
    {
        $pass_type = $request->pass_type;

        $password = TradingAccount::select($pass_type)->where('id', decrypt($request->id))->first();
        $status = 1;
        if ($password->$pass_type == '') {
            $status = 0;
        }
        $data = [
            'status' => $status,
            'password' => $password->$pass_type,
        ];
        return $data;
    }
    //trading password reset
    public function trading_pass_reset(Request $request)
    {
        try {
            $trading_account = TradingAccount::find(decrypt($request->id));
            if ($trading_account) {
                $trading_account->account_status = 0;
                $update = $trading_account->save();
                if ($update) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Trading account password reset successfully!'
                    ]);
                }
            }
            return Response::json([
                'status' => false,
                'message' => 'Trading account not found!'
            ]);
        } catch (\Throwable $th) {
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    // Delete trading account
    public function delete_account(Request $request)
    {
        try {
            $validation_rules = [
                'id' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please provide account ID!',
                    'errors' => $validator->errors()
                ]);
            }

            $trading_account = TradingAccount::find(decrypt($request->id));
            
            if (!$trading_account) {
                return Response::json([
                    'status' => false,
                    'message' => 'Trading account not found!'
                ]);
            }

            // Check if user owns this account
            if ($trading_account->user_id != auth()->user()->id) {
                return Response::json([
                    'status' => false,
                    'message' => 'You can only delete your own accounts!'
                ]);
            }

            // Soft delete by setting account_status to 0
            $trading_account->account_status = 0;
            $update = $trading_account->save();
            
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'Trading account deleted successfully!'
                ]);
            }

            return Response::json([
                'status' => false,
                'message' => 'Failed to delete trading account!'
            ]);
        } catch (\Throwable $th) {
            \Log::error('Error deleting trading account: ' . $th->getMessage());
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
}
