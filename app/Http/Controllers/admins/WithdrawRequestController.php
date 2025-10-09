<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\ApproveWithdrawRequest;
use App\Mail\withdrawAmountUpdate;
use App\Mail\WithdrawDeclineRequest;
use App\Models\admin\SystemConfig;
use App\Models\BalanceSheet;
use App\Models\BankAccount;
use App\Models\BonusUser;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\IB;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\CombinedService;
use App\Services\BankService;
use App\Services\common\UserService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Termwind\Components\Span;
use App\Services\systems\VersionControllService;

class WithdrawRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:withdraw request"]);
        $this->middleware(["role:manage request"]);
        // system module control
        $this->middleware(AllFunctionService::access('manage_request', 'admin'));
        $this->middleware(AllFunctionService::access('withdraw_request', 'admin'));
    }
    public function withdrawRequest(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->withdrawRequestReport($request);
        }
        $countries = Country::all();
        $crmVarsion = VersionControllService::check_version();
        $withdraw = Withdraw::select('transaction_type')->distinct()->get();
        return view('admins.reports.withdraw-request-report', ['withdraw' => $withdraw, 'countries' => $countries, 'varsion' => $crmVarsion]);
    }

    public function withdrawRequestReport($request)
    {
        try {
            $status = $request->status;

            $columns = ['name', 'email', 'transaction_type', 'type', 'approved_status', 'withdraws.created_by', 'withdraws.created_at', 'amount'];
            $orderby = $columns[$request->order[0]['column']];

            $result = Withdraw::select(
                'withdraws.user_id as user_id',
                'withdraws.transaction_type',
                'withdraws.id as withdraw_id',
                'withdraws.user_id',
                'withdraws.approved_status',
                'withdraws.created_at as request_date',
                'withdraws.amount',
                'withdraws.created_by',
                'withdraws.wallet_type',
                'users.name',
                'users.email',
                'users.type',
                'users.email_verified_at'
            )
                ->join('users', 'withdraws.user_id', '=', 'users.id')
                ->whereIn('users.type', [0, CombinedService::type()]);

            //------------------------------------------------------------------------------
            //Filter Start
            //------------------------------------------------------------------------------

            //    filter by account/desk manager
            if (strtolower(auth()->user()->type) === 'manager') {
                $manager_user = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $result = $result->whereIn('user_id', $manager_user);
            }

            //Filter By Transaction Type
            if ($request->transaction_type != "") {
                $result = $result->where('withdraws.transaction_type', '=', $request->transaction_type);
                $total_amount = $result->where('withdraws.transaction_type', '=', $request->transaction_type)->sum('amount');
            }

            //Filter By Verification Status
            if ($request->verification_status != "") {
                if ($request->verification_status == 'Verified') {
                    $result = $result->where('users.email_verified_at', '!=', null);
                    $total_amount = $result->where('users.email_verified_at', '!=', null)->sum('amount');
                } elseif ($request->verification_status == 'Unverified') {
                    $result = $result->where('users.email_verified_at', '=', null);
                    $total_amount = $result->where('users.email_verified_at', '=', null)->sum('amount');
                }
            }
            //Filter By Approve Status
            if ($status != "") {
                $result = $result->where('withdraws.approved_status', $status);
            }
            // filter by create by
            if ($request->created_by != "") {
                $result = $result->where('withdraws.created_by', $request->created_by);
            }
            //Filter By Trader Name / Email /Phone
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $user_id = User::select('id')->where(function ($query) use ($trader_info) {
                    $query->where('name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $trader_info . '%');
                })->get()->pluck('id');
                $result = $result->whereIn('withdraws.user_id', $user_id);
            }
            //Filter By IB Name / Email /Phone
            if ($request->ib_info != "") {
                $ib = $request->ib_info;
                $user_id = User::select('id')->where('type', 4)->where(function ($query) use ($ib) {
                    $query->where('name', 'LIKE', '%' . $ib . '%')
                        ->orWhere('email', 'LIKE', '%' . $ib . '%')
                        ->orWhere('phone', $ib);
                })->get()->pluck('id');
                // get ib id
                $reference_id = IB::whereIn('ib_id', $user_id)->select('reference_id')->get()->pluck('reference_id');
                $result = $result->whereIn('withdraws.user_id', $reference_id);
            }

            //Filter By Country
            if ($request->country != "") {
                $user_country = $request->country;
                $user_id = User::select('countries.name')->where(function ($query) use ($user_country) {
                    $query->where('countries.name', 'LIKE', '%' . $user_country . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }

            //Filter By Amount
            if ($request->min != "") {
                $result = $result->where("withdraws.amount", '>=', $request->min);
            }
            if ($request->max != "") {
                $result = $result->where("withdraws.amount", '<=', $request->max);
            }

            if ($request->from != "") {
                $result = $result->whereDate('withdraws.created_at', '>=', $request->from);
            }

            //Filter By Date
            if ($request->to != "") {
                $result = $result->whereDate('withdraws.created_at', '<=', $request->to);
            }


            // filter by manager info
            if ($request->manager_info) {
                $maanger_info = $request->manager_info;
                $managers = User::where(function ($q) use ($maanger_info) {
                    $q->where('users.email', 'like', '%' . $maanger_info . '%')
                        ->orWhere('users.name', 'like', '%' . $maanger_info . '%')
                        ->orWhere('users.phone', 'like', '%' . $maanger_info . '%');
                })->select('id')->get()->pluck('id');
                $manager_user = ManagerUser::whereIn('manager_id', $managers)->select('user_id')->get()->pluck('user_id');
                $result = $result->where(function ($q) use ($manager_user) {
                    $q->whereIn('user_id', $manager_user);
                });
            }
            //Filter By Client Type
            if ($request->client_type === "trader") {
                $result = $result->where('withdraws.wallet_type', '=', $request->client_type);
            }
            if ($request->client_type === "ib") {
                $result = $result->where('withdraws.wallet_type', '=', $request->client_type);
            }
            // filter by trading account
            if ($request->trading_account != "") {
                $trading_account = TradingAccount::where('account_number', $request->trading_account)->select('user_id')->first();
                $user_id = $trading_account->user_id;
                $result = $result->where(function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });
            }
            /*<-------filter search script End here------------->*/

            $count = $result->count();
            $total_amount = $result->sum('amount');
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();

            $data = array();
            $i = 0;

            foreach ($result as $value) {
                // client type
                if ($value->approved_status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                } elseif ($value->approved_status == 'A') {
                    $status = '<span class="bg-light-success badge badge-success">Approved</span>';
                } elseif ($value->approved_status == 'D') {
                    $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
                }
                // client type
                if ($value->wallet_type === 'trader') {
                    $type = '<span class="bg-success badge badge-success">' . ucwords($value->wallet_type) . '</span>';
                } else {
                    $type = '<span class="bg-warning badge badge-warning">' . strtoupper($value->wallet_type) . '</span>';
                }

                $data[$i]['name'] = '<a href="#" data-id="' . $value->withdraw_id . '" class="dt-description justify-content-start text-truncate"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' .  $value->name . '</span></a>';;
                $data[$i]['email'] = $value->email;
                $data[$i]['method'] = ucwords($value->transaction_type);
                $data[$i]["client_type"] = $type;
                $data[$i]['status'] = $status;
                $data[$i]['created_by'] = ucwords(str_replace('_', ' ', $value->created_by));
                $data[$i]['request_date'] = '<span class="text-truncate">' . date('d M y, h:i:s', strtotime($value->request_date)) . '</span>';
                $data[$i]['amount'] = '$' . $value->amount;
                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count, 'recordsFiltered' => $count,
                'total' => ['<span> $' . round($total_amount, 2) . '</span>'],
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'total' => 0,
                'data' => []
            ]);
        }
    }

    public function withdrawRequestDescription(Request $request)
    {
        $id = $request->id;
        $withdraw = Withdraw::where('withdraws.id', $request->id)
            ->select(
                'withdraws.*',
                'other_transactions.crypto_address',
                'other_transactions.crypto_type',
                'other_transactions.crypto_instrument',
                'user_descriptions.gender',
                'users.kyc_status',
                'users.phone'
            )
            ->leftJoin('other_transactions', 'withdraws.other_transaction_id', 'other_transactions.id')
            ->join('users', 'withdraws.user_id', 'users.id')
            ->leftJoin('user_descriptions', 'users.id', 'user_descriptions.user_id')
            ->first();

        $innerTH = "";
        $innerTD = "";
        // bank withdraw
        if (strtolower($withdraw->transaction_type) === 'bank') {
            $multi_cur_visibility = BankService::is_multicurrency('all') ? "" : "d-none";
            $innerTH .= '
                <th>Amount in USD</th>
                <th class="' . $multi_cur_visibility . '">Amount in ' . $withdraw->currency . '</th>
                <th>Bank Name</th>
                <th>Bank Swift Code</th>
                <th>Bank IBAN</th>
                <th>Bank AC Name</th>
                <th>Bank AC No</th>';
            $bank_details = BankAccount::where('id', $withdraw->bank_account_id)->first();
            // $bank_country = Country::select('name')->where('id', $bank_details->bank_country)->first();
            $innerTD .= '
                <td>' . '$' . $withdraw->amount . '</td>
                <td class="' . $multi_cur_visibility . '">' . (($bank_details->local_currency) ? $bank_details->local_currency : '---') . '</td>
                <td>' . (isset($bank_details->bank_name) ? $bank_details->bank_name : '---') . '</td>
                <td>' . (isset($bank_details->bank_swift_code) ? $bank_details->bank_swift_code : '---') . '</td>
                <td>' . (isset($bank_details->bank_iban) ? $bank_details->bank_iban : '---') . '</td>
                <td>' . (isset($bank_details->bank_ac_name) ? $bank_details->bank_ac_name : '---') . '</td>
                <td>' . (isset($bank_details->bank_ac_number) ? $bank_details->bank_ac_number : '---') . '</td>';
        }
        // crypto withdraw
        else if (strtolower($withdraw->transaction_type) === 'crypto') {

            $innerTH .= '
                <th>Amount Request</th>
                <th>Crypto Type</th>
                <th>Blockchain Type</th>
                <th>Address</th>';

            $innerTD .= '
                <th>' . '$' . $withdraw->amount . '</th>
                <th>' . (($withdraw->crypto_type) ? $withdraw->crypto_type : '---') . '</th>
                <th>' . (($withdraw->crypto_instrument) ? $withdraw->crypto_instrument : '---') . '</th>
                <th>' . (($withdraw->crypto_address) ? $withdraw->crypto_address : '---') . '</th>';
        }
        // all others withdraw type
        else {
            $innerTH .= '
                <th>Amount Request</th>
                <th>Account Name</th>
                <th>Account Email</th>';
            $innerTD .= '
                <th>' . '$' . $withdraw->amount . '</th>
                <th>' . $withdraw->account_name . '</th>
                <th>' . $withdraw->account_email . '</th>';
        }

        //===========================Admin Information condition=================================////
        $innerTH1 = "";
        $innerTD1 = "";
        $approved_by = "";
        if ($withdraw->approved_status === 'A' || $withdraw->approved_status === 'D') {
            $approved_by = ($withdraw->approved_status) == 'A' ? "Approved By:" : "Declined By";
            $admin_info = User::select('name', 'email')->where('id', $withdraw->approved_by)->first();
            $admin_name = isset($admin_info->name) ? $admin_info->name : '---';
            $admin_email = isset($admin_info->email) ? $admin_info->email : '---';
            $admin_json_data = json_decode($withdraw->admin_log);
            $ip = isset($admin_json_data->ip) ? $admin_json_data->ip : '---';
            $wname = isset($admin_json_data->wname) ? $admin_json_data->wname : '---';
            $action_date = isset($withdraw->approved_date) ? date('d M Y, h:i A', strtotime($withdraw->approved_date)) : '---';

            $innerTH1 .= '
                 <th>ADMIN EMAIL</th>
                 <th>Admin Name</th>
                 <th>IP</th>
                 <th>Device</th>
                 <th>Action Date</th>';
            $innerTD1 .= '
                 <td>' . $admin_name . '</td>
                 <td>' . $admin_email . '</td>
                 <td>' . $ip . '</td>
                 <td>' . $wname . '</td>
                 <td>' . $action_date . '</td>';
        }
        //===========================Admin Information condition End=================================////

        $withdraw_data = '
        <tr>
            <td>
            <div class="details-section-dark p-2 " style="display: flow-root;">
                <span class="details-text" style="margin-left:5px;">
                    <b>' . ucwords($withdraw->transaction_type) . ' Details </b>
                </span>
                <table class="deposit-details table dt-inner-table-dark mb-2">
                    <thead>
                        <tr>
                            ' . $innerTH . '
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            ' . $innerTD . '
                        </tr>
                    </tbody>
                </table>
            
            
                    <span class="details-text" style="margin-left:5px;">
                    <b>' . $approved_by . '</b>
                    </span>
                <table class="deposit-details table dt-inner-table-dark">
                    <thead>
                        <tr>
                        ' . $innerTH1 . ' 
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            ' . $innerTD1 . ' 
                        </tr>
                    </tbody>
                </table>
            </div>    
            </td>
        </tr>';

        // inner datatable end--------------------------------------------
        if (isset($withdraw->gender)) {
            $avatar = ($withdraw->gender == 'male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png'; //<----avatar url
        }
        // kyc verification status
        if ($withdraw->kyc_status == 0) {
            $verify_status = '<span class="badge badge-light-danger bg-light-danger">Unverified</span>';
        } elseif ($withdraw->kyc_status == 1) {
            $verify_status = '<span class="badge badge-light-success bg-light-success">Verified</span>';
        } else {
            $verify_status = '<span class="badge badge-light-warning bg-light-warning">Pending</span>';
        }
        $country = UserService::get_country($withdraw->user_id);

        //check bonus-user
        if (BonusUser::where('bonus_users.user_id', $withdraw->user_id)->exists()) {
            $latest_bonus = BonusUser::where('bonus_users.user_id', $withdraw->user_id)->select()
                ->join('bonus_packages', 'bonus_users.bonus_package', '=', 'bonus_packages.id')
                ->join('deposits', 'bonus_users.deposit_id', '=', 'deposits.id')
                ->first();
            $bonus_amount = 0;
            if ($latest_bonus->bonus_amount == 0) {
                $deposit_amount = $latest_bonus->amount;
                $bonus_amount = $deposit_amount * ($latest_bonus->bonus_percent / 100);
            } else {
                $bonus_amount = $latest_bonus->bonus_amount;
            }
        } else {
            $bonus_amount = 0;
        }

        $ib_style = '';
        // trader total balance from all function service
        if ($withdraw->wallet_type === 'trader') {
            $total_balance = AllFunctionService::trader_total_balance($withdraw->user_id);
            $total_withdraw = AllFunctionService::trader_total_withdraw($withdraw->user_id);
            $total_deposit = AllFunctionService::trader_total_deposit($withdraw->user_id);

            $total_deposit_amount = Deposit::where('user_id', $withdraw->user_id)->where('wallet_type', 'trader')->sum('amount');
            $total_withdraw_amount = Withdraw::where('user_id', $withdraw->user_id)->where('wallet_type', 'trader')->sum('amount');
            $trading_account = TradingAccount::select('user_id', 'account_number')->where('user_id', $withdraw->user_id);
            $total_trading_account = $trading_account->count();
            $all_trading_accounts = $trading_account->get();
            
            $trading_account_list = "";
            if($total_trading_account>0){
                foreach($all_trading_accounts as $row){
                    $trading_account_list .= "<span class='text-success'>*" . $row->account_number . " </span>";
                }
            }
            $des =  '<tr>
                       <th>Wallet Balance</th>
                       <td>&dollar; ' . $total_balance . '</td>
                   </tr>
                   <tr>
                       <th>Total Withdraw</th>
                       <td>&dollar; ' . $total_withdraw . '</td>
                   </tr>
                   <tr>
                       <th>Total Deposit</th>
                       <td>&dollar; ' . $total_deposit . '</td>
                   </tr>
                   <tr>
                       <th>Latest Bonus Receive</th>
                       <td>&dollar; ' . $bonus_amount . '</td>
                   </tr>';
        }
        //ib balance from all function service
        else if ($withdraw->wallet_type === 'ib') {
            $total_balance = BalanceService::get_ib_balance_v2($withdraw->user_id);
            $total_deposit_amount = Deposit::where('user_id', $withdraw->user_id)->where('wallet_type', 'ib')->sum('amount');
            $total_withdraw_amount = Withdraw::where('user_id', $withdraw->user_id)->where('wallet_type', 'ib')->sum('amount');
            // $trading_account = TradingAccount::select('user_id')->where('user_id', $withdraw->user_id);
            $total_trading_account = 0;
            $all_trading_accounts = [];
            
            $trading_account_list = "";
            if(isset($all_trading_accounts)){
                foreach($all_trading_accounts as $row){
                    $trading_account_list .= "<span>*" . $row->account_number . " </span>";
                }
            }
            $total_withdraw = $total_withdraw_amount;
            $ib_style = 'style="display:none;"';
            $des =  '<tr>
                    <th>Wallet Balance</th>
                    <td>&dollar; ' . $total_balance . '</td>
                    </tr>
                    <tr>
                        <th>Total Withdraw</th>
                        <td>&dollar; ' . $total_withdraw . '</td>
                    </tr>
                
                    <tr>
                        <th>Latest Bonus Receive</th>
                        <td>&dollar; ' . $bonus_amount . '</td>
                    </tr>';
        }

        //button added
        $check = Withdraw::select('approved_status', 'transaction_type')->where('id', $id)->first();
        $buttons = "";
        if ($check->approved_status === 'P') {
            $auth_user = User::find(auth()->user()->id);
            if ($auth_user->hasDirectPermission('edit withdraw request')) {
                $buttons = '<div class="details-text w-100">
                    <div class="btn-container p-0 m-0" style="float:right;">
                    <button   data-type="button"  class="btn btn-secondary edit-amount-button waves-effect waves-float waves-light"  data-loading="processing..." data-bs-toggle="modal" data-bs-target="#amount_edit"  data-id="' . $id . '"  onclick="view_amount(this)">Edit</button>
                        <button data-type="button" class="btn btn-primary waves-effect waves-float waves-light btn-transaction-approve"  data-loading="processing..."  data-id="' . $id . '"  >Approve</button>
                        <button type="button" class="btn btn-danger btn-transaction-declined waves-effect waves-float waves-light"  data-loading="processing..."data-id="' . $id . '" >Decline</button>
                    </div>
                </div>';
            } else {
                $buttons = '';
            }
        }

        $description = '<tr class="description" style="display:none">
            <td colspan="8">
                <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="rounded-0 w-75">
                            <table class="table table-responsive tbl-trader-details">
                               ' . $des . '
                            </table>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justfy-content-between">    
                            <div class="rounded-0 w-100">
                                <table class="table table-responsive tbl-trader-details">
                                    <tr>
                                        <th>Verification Status</th>
                                        <td>' . $verify_status . '</td>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <td>' . $country . '</td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number</th>
                                        <td>' . $withdraw->phone . '</td>
                                    </tr>
                                    <tr>
                                        <th>Total Trading Account</th>
                                        <td>' . $total_trading_account . '</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">' . $trading_account_list . '</th>
                                    </tr>
                                </table>
                            </div> 
                            
                            <div class="card ms-1 dt-trader-img">
                                <div class="card-body bg-light-secondary p-0 m-0" style="height: 152px;">
                                <img class="img img-fluid" src="' . asset("admin-assets/app-assets/images/avatars/$avatar") . ' "alt="avatar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <!-- Filled Tabs starts -->
                            <div class="col-xl-12 col-lg-12">
                                <div class=" p-0">
                                    <div class=" p-0">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs  mb-1 tab-inner-dark" id="myTab' . $id . '" role="tablist">
                                            <li class="nav-item">
                                                <a data-id="' . $id . '"  class="nav-link total-withdraw-details-tab-fill active" id="total-withdraw-details-tab-fill-' . $id . '" data-bs-toggle="tab" href="#withdraw-account-details-fill-' . $id . '" role="tab" aria-controls="home-fill" aria-selected="true">Withdraw Details Report</a>
                                            </li>
                                            <li class="nav-item border-end-2 border-end-secondary" ' . $ib_style . '>
                                                <a data-id="' . $id . '"  class="nav-link deposit-tab total-deposit-tab-fill" id="total-deposit-tab-fill-' . $id . '" data-bs-toggle="tab" href="#deposit-fill-' . $id . '" role="tab" aria-controls="deposit-fill"  aria-selected="false">Total Deposit Report</a>
                                            </li>
                                            <li class="nav-item">
                                                <a data-id="' . $id . '"  class="nav-link total-withdraw-tab-fill" id="total-withdraw-tab-fill-' . $id . '" data-bs-toggle="tab" href="#withdraw-fill-' . $id . '" role="tab" aria-controls="withdraw-fill" aria-selected="false">Total Withdraw Report</a>
                                            </li>
                                            <li class="nav-item">
                                                <a data-id="' . $id . '" class="nav-link bonus-tab-fill" id="bonus-tab-fill-' . $id . '" data-bs-toggle="tab" href="#bonus-fill-' . $id . '" role="tab" aria-controls="bonus-fill" aria-selected="false">Total Bonus Report</a>
                                            </li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="withdraw-account-details-fill-' . $id . '" role="tabpanel" aria-labelledby="home-tab-fill">
                                                <div class="table-responsive">
                                                    <table class="datatable-inner trading_account withdraw-account-details table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                        <thead>
                                                           ' . $withdraw_data . '
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            
                                            <div class="tab-pane" id="deposit-fill-' . $id . '" role="tabpanel" aria-labelledby="total-deposit-tab-fill">
                                                <div class="table-responsive">
                                                    <table class="datatable-inner deposit table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Method</th>
                                                                <th>Status</th>
                                                                <th>Wallet</th>
                                                                <th>Created By</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                        <tr>
                                                            <th colspan="5" style="text-align: right;" class="details-control" rowspan="1">Total amount</th>
                                                            <th id="total_deposit_amount" rowspan="1" colspan="1">$ ' . $total_deposit_amount . '</th>
                                                        </tr>
                                                    </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="withdraw-fill-' . $id . '" role="tabpanel" aria-labelledby="total-withdraw-tab-fill">
                                                <div class="table-responsive">
                                                    <table class="datatable-inner withdraw table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Method</th>
                                                                <th>Status</th>
                                                                <th>Wallet</th>
                                                                <th>Created by</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                        <tr>
                                                            <th colspan="5" style="text-align: right;" class="details-control" rowspan="1">Total amount</th>
                                                            <th id="total_deposit_amount" rowspan="1" colspan="1">$ ' . $total_withdraw_amount . '</th>
                                                        </tr>
                                                    </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="bonus-fill-' . $id . '" role="tabpanel" aria-labelledby="bonus-tab-fill">
                                                <table class="datatable-inner bonus table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>Bonus Name</th>
                                                            <th>Ammount</th>
                                                            <th>Platform</th>
                                                            <th>Start Date</th>
                                                            <th>Status</th>
                                                            <th>End Date</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        ' . $buttons . '
                    </div> 
                </div>
            </td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
        </tr>';


        return Response::json([
            'status' => true,
            'description' => $description
        ]);
    }
    // withdraw request approve
    public function approveWithdrawRequest(Request $request)
    {
        $id = $request->id;
        $withdraw = Withdraw::where('id', $id)->first();
        // update withdraw table
        $update = Withdraw::where('id', $id)->update([
            'approved_status' => 'A',
            'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
            'approved_by' => auth()->user()->id,
            'admin_log' => AdminLogService::admin_log()
        ]);
        // sending email
        // if sender is trader/get trader self balance
        if (User::where('id', $withdraw->user_id)->where('type', 0)->exists()) {
            $self_balance = AllFunctionService::trader_total_balance($withdraw->user_id);
        }
        // else sender is an IB/get IB self balance
        else {
            $self_balance = BalanceService::ib_balance($withdraw->user_id);
        }
        $mail_status = EmailService::send_email('withdraw-approve', [
            'user_id' => $withdraw->user_id,
            'request_date' => date('Y M d', strtotime($withdraw->created_at)),
            'withdraw_method' => ucwords($withdraw->transaction_type),
            'previous_balance' => $self_balance,
            'approved_amount' => $withdraw->amount,
            'total_balance' => ($self_balance) + ($withdraw->amount)
        ]);

        if ($update) {
            // insert activity-----------------
            $user = User::find($withdraw->user_id); //<---client email as user id
            activity($withdraw->wallet_type . " withdraw approved")
                ->causedBy(auth()->user()->id)
                ->withProperties($withdraw)
                ->event($withdraw->wallet_type . " withdraw approved")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been approved withdraw request");
            // end activity log-----------------
            MailNotificationService::admin_notification([
                'amount' => $withdraw->amount,
                'name' => $user->name,
                'email' => $user->email,
                'type' => 'withdraw approve',
                'client_type' => strtolower($withdraw->wallet_type)
            ]);
            if ($mail_status) {
                return Response::json([
                    'status' => true,
                    'message' => 'Mail successfully sent for Approved request',
                ]);
            }
            return Response::json([
                'status' => true,
                'message' => 'Mail sending failed, Please try again later!',
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Something went wrong, Please try again later!',
        ]);
    }
    // withdraw request decline
    public function declineWithdrawRequest(Request $request)
    {
        $id = $request->id;
        $withdraws = Withdraw::where('id', $id)->first();
        // update withdraw table

        $update = Withdraw::where('id', $id)->update([
            'approved_status' => 'D',
            'note' => $request->note,
            'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
            'approved_by' => auth()->user()->id,
            'admin_log' => AdminLogService::admin_log(),
        ]);

        // if sender is trader/get trader self balance
        if (strtolower($withdraws->wallet_type) === 'trader') {
            $self_balance = BalanceSheetService::trader_wallet_balance($withdraws->user_id);
        }
        // else sender is an IB/get IB self balance
        else {
            $self_balance = BalanceSheetService::ib_wallet_balance($withdraws->user_id);
        }

        $mail_status = EmailService::send_email('withdraw-decline', [
            'user_id' => $withdraws->user_id,
            'request_date' => date('Y M d', strtotime($withdraws->created_at)),
            'withdraw_method' => ucwords($withdraws->transaction_type),
            'previous_balance' => $self_balance,
            'approved_amount' => $withdraws->amount,
            'total_balance' => ($self_balance)
        ]);

        if ($update) {
            // insert activity-----------------
            $user = User::find($withdraws->user_id); //<---client email as user id
            activity($withdraws->wallet_type . " withdraw declined")
                ->causedBy(auth()->user()->id)
                ->withProperties($withdraws)
                ->event($withdraws->wallet_type . " withdraw declined")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been declined withdraw request");
            // end activity log-----------------
            MailNotificationService::admin_notification([
                'amount' => $withdraws->amount,
                'name' => $user->name,
                'email' => $user->email,
                'type' => 'withdraw decline',
                'client_type' => strtolower($withdraws->wallet_type)
            ]);

            if ($mail_status) {
                return Response::json([
                    'status' => true,
                    'message' => 'Mail successfully sent for Declined request',
                ]);
            }
            return Response::json([
                'status' => true,
                'message' => 'Mail sending failed, Please try again later!',
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Something went wrong, Please try again later!',
        ]);
    }

    //total deposit report for withdraw request
    public function totalDepositReport(Request $request, $id)
    {

        $parent_withdraw = Withdraw::where('id', $id)->first();
        $columns = ['created_at', 'transaction_type', 'deposits.approved_status', 'deposits.wallet_type', 'deposits.created_by', 'amount',];
        $orderby = $columns[$request->order[0]['column']];
        // select type= 0 for trader 
        $result = Deposit::where('user_id', $parent_withdraw->user_id)->where('wallet_type', $parent_withdraw->wallet_type)->select();

        $count = $result->count();
        $total_amount = $result->sum('amount');
        $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
        $data = [];

        foreach ($result as $key => $value) {
            $status = (strtolower($value->approved_status) === 'p') ? '<span class="badge badge-light-warning">Pending</span>' : '<span class="badge badge-light-success">Approved</span>';

            $data[] = [
                "date"     => '<span class="text-truncate">' . date('d F y, h:i:s', strtotime($value->created_at)) . '</span>',
                "method"     => ucwords($value->transaction_type),
                "status"     => $status,
                'wallet' => ($value->wallet_type === 'trader') ? '<span class="badge badge-light-success bg-light-success">' . ucwords($value->wallet_type) . '</span>' : '<span class="badge badge-light-warning bg-light-warning">' . strtoupper($value->wallet_type) . '</span>',
                'created_by' => '<span class="text-truncate">' . ucwords(str_replace('_', ' ', $value->created_by)) . '</span>',
                "amount"       => '<span>&dollar; ' . $value->amount . '</span>',
            ];
        }

        return Response::json(
            [
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total_amount' => $total_amount,
                'data' => $data,
            ]
        );
    }

    //total withdraw report for withdraw request
    public function totalWithdrawReport(Request $request, $id)
    {
        try {
            $columns = ['created_at', 'transaction_type', 'withdraws.approved_status', 'withdraws.wallet_type', 'withdraws.created_by', 'amount',];
            $orderby = $columns[$request->order[0]['column']];

            // select type= 0 for trader 
            $parent_withdraw  = Withdraw::where('id', $id)->select(
                'wallet_type',
                'user_id'
            )->first();
            $result = Withdraw::where('user_id', $parent_withdraw->user_id)->where('wallet_type', $parent_withdraw->wallet_type)->select();

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = [];

            foreach ($result as $key => $value) {
                $status = (strtolower($value->approved_status) === 'p') ? '<span class="badge badge-light-warning">Pending</span>' : '<span class="badge badge-light-success">Approved</span>';

                $data[] = [
                    "date"       => '<span class="text-truncate">' . date('d F y, h:i:s', strtotime($value->created_at)) . '</span>',
                    "method"     => ucwords($value->transaction_type),
                    "status"     => $status,
                    "wallet"     => ($value->wallet_type === 'trader') ? '<span class="badge badge-light-success bg-light-success">' . ucwords($value->wallet_type) . '</span>' : '<span class="badge badge-light-warning bg-light-warning">' . strtoupper($value->wallet_type) . '</span>',
                    'created_by' => ucwords(str_replace('_', ' ', $value->created_by)),
                    "amount"     => '<span>&dollar; ' . $value->amount . '</span>',
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0, '
                recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
    //total bonus report for withdraw request
    public function totalBonusReport(Request $request, $user_id)
    {
        try {
            $columns = ['bonus_amount', 'pkg_name', 'platform', 'start_date', 'credit_type', 'end_date'];
            $orderby = $columns[$request->order[0]['column']];

            // select type= 0 for trader 
            $result = BonusUser::where('bonus_users.user_id', $user_id)->select()
                ->join('bonus_packages', 'bonus_users.bonus_package', '=', 'bonus_packages.id')
                ->join('deposits', 'bonus_users.deposit_id', '=', 'deposits.id')
                ->join('trading_accounts', 'bonus_users.user_id', '=', 'trading_accounts.user_id');

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();

            foreach ($result as $key => $value) {
                $bonus_amount = 0;
                if ($value->bonus_amount == 0) {
                    $deposit_amount = $value->amount;
                    $bonus_amount = $deposit_amount * ($value->bonus_percent / 100);
                } else {
                    $bonus_amount = $value->bonus_amount;
                }

                $data[] = [
                    "amount"           => '<span>&dollar; ' . $bonus_amount . '</span>',
                    "bonus_title"      => $value->pkg_name,
                    "platform"         => $value->platform,
                    "start_date"       => date('d F y, h:i A', strtotime($value->start_date)),
                    "status"           => $value->credit_type,
                    "ending_date"      => date('d F y, h:i A', strtotime($value->end_date)),
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    //total withdraw details balance report for withdraw request

    public function withDetailsReport(Request $request, $id, $user_id)
    {
        try {
            $columns = ['withdraws.amount', 'bank_accounts.bank_name', 'bank_swift_code', 'bank_iban', 'bank_address', 'bank_ac_name', 'bank_ac_number'];
            $orderby = $columns[$request->order[0]['column']];

            // select type= 0 for trader 
            $result = BankAccount::where('bank_accounts.user_id', $user_id)->select()
                ->join('withdraws', 'bank_accounts.user_id', '=', 'withdraws.user_id');

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = [];

            foreach ($result as $key => $value) {
                $country = Country::select('name')->where('id', $value->bank_country)->first()->name;

                $data[] = [
                    "amount_request"          => '<span>&dollar; ' . $value->amount . '</span>',
                    "bank_name"               => $value->bank_name,
                    "bank_swift_code"         => $value->bank_swift_code,
                    "bank_iban"               => $value->bank_iban,
                    "bank_address"            => $value->bank_address,
                    "bank_country"            => $country,
                    "bank_ac_name"            => $value->bank_ac_name,
                    "bank_ac_number"          => $value->bank_ac_number,
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }


    //request  amount view
    public function viewAmount(Request $request, $id)
    {
        $amount = Withdraw::select('amount')->where('id', $id)->first();
        $data = $amount->amount;
        return response()->json($data);
    }
    //update request amount
    public function amountUpdate(Request $request)
    {
        $validation_rules = [
            'request_amount' => 'required|numeric'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Please fix the following errors',
                'errors' => $validator->errors(),
            ]);
        }
        $id = $request->amount_id;
        $withdraw = Withdraw::where('id', $id)->first();
        // check wallet balance
        if (strtolower($withdraw->wallet_type) === 'trader') {
            $balance = BalanceSheetService::trader_wallet_balance($withdraw->user_id);
        }
        // else sender is an IB/get IB self balance
        else {
            $balance = BalanceSheetService::ib_wallet_balance($withdraw->user_id);
        }
        if ($request->request_amount > $balance) {
            return Response::json([
                'status' => false,
                'message' => 'User dont have available balance',
                'errors' => ['request_amount' => 'User dont have available balance']
            ]);
        }
        $update = Withdraw::where('id', $id)->update([
            'amount' => $request->request_amount,
            'admin_log' => AdminLogService::admin_log(),
        ]);

        if ($update) {
            // insert activity-----------------
            $user = User::find($withdraw->user_id); //<---client email as user id
            activity($withdraw->wallet_type . " withdraw amount updated")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event($withdraw->wallet_type . " withdraw amount")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been update withdraw amount");
            // end activity log-----------------
            EmailService::send_email('update-withdraw-amount', [
                'user_id' => $withdraw->user_id,
                'admin' => auth()->user()->name,
                'amount' => $request->request_amount,
            ]);
            return Response::json([
                'status' => true,
                'message' => 'Amount Successful Updated'
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Amount Update Failed, Please try again later!',
            ]);
        }
    }
}
