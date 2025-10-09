<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\BalanceApproveRequest;
use App\Mail\BalanceDeclineRequest;
use App\Models\admin\BalanceTransfer;
use App\Models\admin\SystemConfig;
use App\Models\BonusUser;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\ExternalFundTransfers;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\systems\VersionControllService;

class BalanceTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:balance transfer"]);
        $this->middleware(["role:manage request"]);
        // system module control
        $this->middleware(AllFunctionService::access('manage_request', 'admin'));
        $this->middleware(AllFunctionService::access('balance_transfer_request', 'admin'));
    }
    public function balanceTransfer(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->balanceTransferReport($request);
        }

        $crmVarsion = VersionControllService::check_version();
        $countries = Country::all();
        return view('admins.reports.balance-transfer-report',['varsion' => $crmVarsion,'countries' => $countries]);
    }

    public function balanceTransferReport($request)
    {
        try {
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $_GET['order'][0]["column"];
            $orderDir = $_GET["order"][0]["dir"];

            $columns = ['users.email', 'users.email', 'external_fund_transfers.sender_wallet_type', 'external_fund_transfers.receiver_wallet_type', 'external_fund_transfers.status', 'external_fund_transfers.status', 'external_fund_transfers.created_at', 'external_fund_transfers.amount'];
            $orderby = $columns[$order];

            $results = ExternalFundTransfers::select(
                'external_fund_transfers.sender_id',
                'external_fund_transfers.id as fund_id',
                'external_fund_transfers.amount',
                'external_fund_transfers.created_at',
                'external_fund_transfers.receiver_id',
                'external_fund_transfers.status',
                'external_fund_transfers.txnid',
                'external_fund_transfers.sender_wallet_type',
                'external_fund_transfers.receiver_wallet_type',
                'users.email',
                'users.email_verified_at',
                'users.type'
            )
                ->join('users', 'users.id', '=', 'external_fund_transfers.sender_id')
                ->whereIn('users.type', [0, 4]);

            //---------------------------------------------------------------------------------------
            //Filter Start
            //---------------------------------------------------------------------------------------
            // filter by login manager 
            if (strtolower(auth()->user()->type) === "manager") {
                $manager_user = ManagerUser::where('manager_id', auth()->user()->id)->get('user_id')->pluck('user_id');
                $results = $results->whereIn('sender_id', $manager_user)->orWhereIn('receiver_id', $manager_user);
            }
            // filter by verification status
            if ($request->verification != "") {
                $results = $results->where('users.kyc_status', $request->verification);
            }
            // approved status
            if ($request->approved_status != "") {
                $results = $results->where('status', $request->approved_status);
            }
            //Filter By  receiver Client Type
            if ($request->receiver_client_type != "") {
                $results = $results->where('receiver_wallet_type', $request->receiver_client_type);
            }
            //Filter By  sender Client Type
            if ($request->sender_client_type != "") {
                $results = $results->where('sender_wallet_type', $request->sender_client_type);
            }
            // filter by trader info
            if ($request->trader_info != "") {
                // get trader id from info
                $trader_info = $request->trader_info;
                $country = Country::where(function ($q) use ($trader_info) {
                    $q->where('name', 'like', '%' . $trader_info . '%');
                })->get()->pluck('id');
                $traders = User::where(function ($query) use ($trader_info, $country) {
                    $query->where('email', 'like', '%' . $trader_info . '%')
                        ->orWhere('name', 'like', '%' . $trader_info . '%')
                        ->orWhereIn('country_id', $country)
                        ->orWhere('phone', 'like', '%' . $trader_info . '%');
                })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->select('users.id as client_id')
                    ->get()->pluck('client_id');
                // get data from externalfund transfer
                $results = $results->where(function ($q) use ($traders) {
                    $q->whereIn('sender_id', $traders)
                        ->orWhereIn('receiver_id', $traders);
                });
            }
            // filter by ib name email phone
            if ($request->ib_info != "") {
                // get ib id from info
                $ib_info = $request->ib_info;
                $country = Country::where(function ($q) use ($ib_info) {
                    $q->where('name', 'like', '%' . $ib_info . '%');
                })->get()->pluck('id');
                $traders = User::where(function ($query) use ($ib_info, $country) {
                    $query->where('email', 'like', '%' . $ib_info . '%')
                        ->orWhere('name', 'like', '%' . $ib_info . '%')
                        ->orWhereIn('country_id', $country)
                        ->orWhere('phone', 'like', '%' . $ib_info . '%');
                })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->select('users.id as client_id')
                    ->get()->pluck('client_id');
                // get data from externalfund transfer
                $results = $results->where(function ($q) use ($traders) {
                    $q->whereIn('sender_id', $traders)
                        ->orWhereIn('receiver_id', $traders);
                });
            }
            // filter by sender info
            if ($request->sender_info != "") {
                // get sender id from info
                $sender_info = $request->sender_info;
                $senders = User::where(function ($query) use ($sender_info) {
                    $query->where('email', 'like', '%' . $sender_info . '%')
                        ->orWhere('name', 'like', '%' . $sender_info . '%')
                        ->orWhere('phone', 'like', '%' . $sender_info . '%');
                })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->select('users.id as client_id')
                    ->get()->pluck('client_id');
                // get data from externalfund transfer
                $results = $results->where(function ($q) use ($senders) {
                    $q->whereIn('sender_id', $senders);
                });
            }
            // filter by receiver info
            if ($request->receiver_info != "") {
                // get sender id from info
                $receiver_info = $request->receiver_info;
                $receiver = User::where(function ($query) use ($receiver_info) {
                    $query->where('email', 'like', '%' . $receiver_info . '%')
                        ->orWhere('name', 'like', '%' . $receiver_info . '%')
                        ->orWhere('phone', 'like', '%' . $receiver_info . '%');
                })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->select('users.id as client_id')
                    ->get()->pluck('client_id');
                // get data from externalfund transfer
                $results = $results->where(function ($q) use ($receiver) {
                    $q->whereIn('receiver_id', $receiver);
                });
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
                $results = $results->where(function ($q) use ($manager_user) {
                    $q->whereIn('sender_id', $manager_user)
                        ->orWhereIn('receiver_id', $manager_user);
                });
            }
            //Filter By Country
            if ($request->country != "") {
                $user_country = $request->country;
                $user_id = User::select('countries.name')->where(function ($query) use ($user_country) {
                    $query->where('countries.name', 'LIKE', '%' . $user_country . '%');  
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $results = $results->whereIn('users.id', $user_id);
            }
            // filter by trading account
            if ($request->trading_account != "") {
                $trading_account = TradingAccount::where('account_number', $request->trading_account)->select('user_id')->first();
                $user_id = $trading_account->user_id;
                $results = $results->where(function ($query) use ($user_id) {
                    $query->where('sender_id', $user_id)
                        ->orWhere('receiver_id', $user_id);
                });
            }
            // filter by amount
            // filter by min amount
            if ($request->min != "") {
                $results = $results->where('amount', '>=', $request->min);
            }
            // filter by max amount
            if ($request->max != "") {
                $results = $results->where('amount', '<=', $request->max);
            }
            // filte by date range
            // filter date from
            if ($request->from != "") {
                $results = $results->whereDate('external_fund_transfers.created_at', '>=', $request->from);
            }
            // filter by date to
            if ($request->to != "") {
                $results = $results->whereDate('external_fund_transfers.created_at', '<=', $request->to);
            }


            $total_amount = $results->sum('amount');
            $count = $results->count();
            $results = $results->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
            $data = array();
            $i = 0;

            foreach ($results as $value) {

                $sender = User::find($value->sender_id);
                $receiver = User::find($value->receiver_id);

                if ($value->status == 'P') {
                    $status = '<span class="bg-light-warning badge bg-warning">Pending</span>';
                } elseif ($value->status == 'A') {
                    $status = '<span class="bg-light-success badge bg-success">Approved</span>';
                } elseif ($value->status == 'D') {
                    $status = '<span class="bg-light-danger badge bg-danger">Declined</span>';
                }

                if ($value->receiver_wallet_type === 'trader') {
                    $receiver_type = '<span class="bg-success badge badge-success">' . $value->receiver_wallet_type . '</span>';
                } else {
                    $receiver_type = '<span class="bg-warning badge badge-warning">' . strtoupper($value->receiver_wallet_type) . '</span>';
                }
                // sender client type
                if ($value->sender_wallet_type === 'trader') {
                    $sender_client_type = '<span class="bg-success badge badge-success">' . $value->sender_wallet_type . '</span>';
                } else {
                    $sender_client_type = '<span class="bg-warning badge badge-warning">' . strtoupper($value->sender_wallet_type) . '</span>';
                }

                $data[$i]['sender_email'] = '<a href="#" data-id="' . $value->fund_id . '" class="dt-description justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span><span>' . $sender->email . '</span></a>';
                $data[$i]['receiver_email'] = isset($receiver->email) ? $receiver->email : '';
                $data[$i]["sender_client_type"] = $sender_client_type;
                $data[$i]["client_type"] = $receiver_type;
                $data[$i]['status'] = $status;
                $data[$i]['request_date'] = date('d M y, h:i A', strtotime($value->created_at));
                $data[$i]['amount'] = '$' . $value->amount;
                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total_amount' => $total_amount,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'total_amount' => 0,
                'data' => []
            ]);
        }
    }


    public function balanceTransferDescription(Request $request, $id)
    {
        $sender_info = ExternalFundTransfers::where('external_fund_transfers.id', $id)
            ->select(
                'external_fund_transfers.sender_id',
                'external_fund_transfers.receiver_id',
                'external_fund_transfers.status',
                'external_fund_transfers.approved_by',
                'external_fund_transfers.admin_log',
                'external_fund_transfers.approved_date',
                'external_fund_transfers.approved_date',
                'external_fund_transfers.txnid',
                'external_fund_transfers.created_at',
                'external_fund_transfers.amount',
                'users.name',
                'users.phone',
                'user_descriptions.address',
            )
            ->join('users', 'external_fund_transfers.sender_id', '=', 'users.id')
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')->first();

        $receiver_info = ExternalFundTransfers::where('external_fund_transfers.id', $id)
            ->select(
                'users.name',
                'users.phone',
                'user_descriptions.address'
            )
            ->join('users', 'external_fund_transfers.receiver_id', '=', 'users.id')
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')->first();

        // get trading account details
        if (TradingAccount::select('account_number')->where('user_id', $sender_info->sender_id)->exists()) {
            $sender_account = TradingAccount::select('account_number')->where('user_id', $sender_info->sender_id)->first()->account_number;
        } else {
            $sender_account = "";
        }

        $total_trading_account = TradingAccount::select('user_id')->where('user_id', $sender_info->sender_id)->count();

        // get deposit / withdraw / balance 
        if (strtolower($sender_info->sender_wallet_type) === 'trader') {
            $current_balance = BalanceSheetService::trader_wallet_balance($sender_info->sender_id);
            $total_withdraw = AllFunctionService::trader_total_withdraw($sender_info->sender_id);
            $total_deposit = AllFunctionService::trader_total_deposit($sender_info->sender_id);
            $total_withdraw = Withdraw::where('wallet_type', 'trader')->where('user_id', $sender_info->sender_id)->sum('amount');
        } else {
            $current_balance = BalanceSheetService::ib_wallet_balance($sender_info->sender_id);
            $total_withdraw = Withdraw::where('wallet_type', 'ib')->where('user_id', $sender_info->sender_id)->sum('amount');
        }


        // get bonus details
        if (BonusUser::where('bonus_users.user_id', $sender_info->sender_id)->exists()) {
            $latest_bonus = BonusUser::where('bonus_users.user_id', $sender_info->sender_id)->select()
                ->join('bonus_packages', 'bonus_users.bonus_package', '=', 'bonus_packages.id')
                ->join('deposits', 'bonus_users.deposit_id', '=', 'deposits.id')
                ->first();
            $pkg_name = $latest_bonus->pkg_name;
            if ($latest_bonus->bonus_amount == 0) {
                $deposit_amount = $latest_bonus->amount;
                $bonus_amount = $deposit_amount * ($latest_bonus->bonus_percent / 100);
            } else {
                $bonus_amount = $latest_bonus->bonus_amount;
            }
        } else {

            $bonus_amount = 0;
            $pkg_name = "No Package";
        }
        // tabl for balance sender
        if (strtolower($sender_info->sender_wallet_type) === 'trader') {
            $tbl_balance = '<tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2">Current Balance</th>
                                <td class="border-end-0">&dollar;' . $current_balance . ' </td>
                            </tr>
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2">Total Deposit</th>
                                <td class="border-end-0">&dollar;' . $total_deposit . '</td>
                            </tr>
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2">Latest Bonus Receive</th>
                                <td class="border-end-0">&dollar; ' . $bonus_amount . '</td>
                            </tr>';
        } else {
            $tbl_balance = '<tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2">Current Balance</th>
                                <td class="border-end-0">&dollar;' . $current_balance . ' </td>
                            </tr>
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2">Total Client</th>
                                <td class="border-end-0">&dollar;' . $total_trader = AllFunctionService::total_trader($sender_info->sender_id) . '</td>
                            </tr>
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2">Latest Bonus Receive</th>
                                <td class="border-end-0">&dollar; ' . $bonus_amount . '</td>
                            </tr>';
        }
        //button added

        $buttons = "";
        if ($sender_info->status === 'P') {
            $auth_user = User::find(auth()->user()->id);
            if ($auth_user->hasDirectPermission('edit balance transfer')) {
                $buttons = '<div class="details-text w-100">
                        <div class="btn-container p-0 m-0" style="float:right;">
                            <button data-type="button" class="btn btn-primary waves-effect waves-float waves-light"  data-loading="processing..."  data-id="' . $id . '"  onclick="balanceApproveRequest(this)">Approve</button>
                            <button type="button" class="btn btn-danger balance-decline-request-btn waves-effect waves-float waves-light"  data-loading="processing..." data-bs-toggle="modal" data-bs-target="#addNewCard"  data-id="' . $id . '">Decline</button>
                        </div>
                    </div>';
            } else {
                $buttons = "";
            }
        }


        //===========================Admin Information condition=================================////
        $innerTH1 = "";
        $innerTD1 = "";
        $approved_by = "";
        if ($sender_info->status === 'A' || $sender_info->status === 'D') {
            $approved_by = ($sender_info->status) == 'A' ? "APPROVED BY:" : "DECLINED BY:";
            $admin_info = User::select('name', 'email')->where('id', $sender_info->approved_by)->first();
            $admin_name = isset($admin_info->name) ? $admin_info->name : '---';
            $admin_email = isset($admin_info->email) ? $admin_info->email : '---';
            $admin_json_data = json_decode($sender_info->admin_log);
            $ip = isset($admin_json_data->ip) ? $admin_json_data->ip : '---';
            $wname = isset($admin_json_data->wname) ? $admin_json_data->wname : '---';
            $action_date = isset($sender_info->approved_date) ? date('d M Y, h:i A', strtotime($sender_info->approved_date)) : '---';

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

        $description = '<tr class="description" style="display:none;">
        <td colspan="8">
            <div class="details-section-dark border-start-3 border-start-primary p-2 bg-light-secondary">
                <div class="row">
                    <div class="col-lg-6">
                        <table class="table table-responsive tbl-balance sender-reciever-tbl">
                            <tr>
                                <th></th>
                                <th>Sender </th>
                                <th>Receiver</th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>' . $sender_info->name . '</td>
                                <td>' . $receiver_info->name . '</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>' . $sender_info->address . '</td>
                                <td>' . $receiver_info->address . '</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>' . $sender_info->phone . '</td>
                                <td>' . $receiver_info->phone . '</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table class="table table-responsive tbl-balance payment-table">
                            <tr>
                                <th>Transaction ID</th>
                                <td class="border-end-0">' . $sender_info->txnid . '</td>
                            </tr>
                            <tr>
                                <th>Payment Due</th>
                                <td>' . $sender_info->created_at . '</td>
                            </tr>
                            <tr>
                                <th>Account</th>
                                <td>' . $sender_account . '</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>&dollar;' . $sender_info->amount . '</td>
                            </tr>
                        </table>
                    </div>   
                </div>
                <div class="row">
                    <div class="col-lg-12" style="margin-top:10px;">
                            <span class="" style="margin-left:5px; color:#b4b7bd;">
                            <b>' . ucfirst($approved_by) . '</b>
                            </span>
                            <table class="table table-responsive tbl-balance border-start-3 border-start-primary">
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
                                            <a data-id="' . $sender_info->sender_id . '" class="nav-link total-withdraw-details-tab-fill active" id="total-withdraw-details-tab-fill-' . $id . '" data-bs-toggle="tab" href="#withdraw-account-details-fill-' . $id . '" role="tab" aria-controls="home-fill" aria-selected="true">Sender Trading Accounts</a>
                                        </li>
                                        <li class="nav-item border-end-2 border-end-secondary">
                                            <a data-id="' . $sender_info->sender_id . '" class="nav-link deposit-tab total-deposit-tab-fill" id="total-deposit-tab-fill-' . $id . '" data-bs-toggle="tab" href="#deposit-fill-' . $id . '" role="tab" aria-controls="deposit-fill" aria-selected="false">Total Deposit Report</a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-id="' . $sender_info->sender_id . '" class="nav-link total-withdraw-tab-fill" id="total-withdraw-tab-fill-' . $id . '" data-bs-toggle="tab" href="#withdraw-fill-' . $id . '" role="tab" aria-controls="withdraw-fill" aria-selected="false">Total Withdraw Report</a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-id="' . $sender_info->sender_id . '" class="nav-link bonus-tab-fill" id="bonus-tab-fill-' . $id . '" data-bs-toggle="tab" href="#bonus-fill-' . $id . '" role="tab" aria-controls="bonus-fill" aria-selected="false">Total Bonus Report</a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="withdraw-account-details-fill-' . $id . '" role="tabpanel" aria-labelledby="home-tab-fill">
                                            <div class="table-responsive">
                                                <table class="datatable-inner withdraw-account-details table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>Account No</th>
                                                            <th>Platform</th>
                                                            <th>Group</th>
                                                            <th>Leverage</th>
                                                            <th>Created At</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="deposit-fill-' . $id . '" role="tabpanel" aria-labelledby="total-deposit-tab-fill">
                                            <div class="table-responsive">
                                                <table class="datatable-inner deposit table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>Ammount</th>
                                                            <th>Method</th>
                                                            <th>Status</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="withdraw-fill-' . $id . '" role="tabpanel" aria-labelledby="total-withdraw-tab-fill">
                                            <div class="table-responsive">
                                                <table class="datatable-inner withdraw table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>Ammount</th>
                                                            <th>Method</th>
                                                            <th>Status</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
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
                </div>

            <div class="row w-100">
                <div class="col-lg-6 float-right">
                    <div class="rounded-0 w-100">
                        <div class="p-0">    
                            <table class="table table-responsive tbl-balance">
                                ' . $tbl_balance . '
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="rounded-0 w-100">
                        <div class="p-0">    
                            <table class="table table-responsive tbl-balance mb-3">
                                <tr>
                                    <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2">Total Trading Account</th>
                                    <td class="border-end-0">' . $total_trading_account . '</td>
                                </tr>
                                <tr>
                                    <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2">Total Withdraw</th>
                                    <td class="border-end-0">&dollar; ' . $total_withdraw . '</td>
                                </tr>
                                <tr>
                                    <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2">Latest Bonus Name</th>
                                    <td class="border-end-0">' . $pkg_name . '</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                 </div>
                 <br>
                 ' . $buttons . '
            </div>
            </div>';
        $data = [
            'status' => true,
            'description' => $description
        ];
        return Response::json($data);
    }

    //total deposit report for balance transfer request
    public function balanceDepositReport(Request $request, $id)
    {


        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $_GET['order'][0]["column"];
        $orderDir = $_GET["order"][0]["dir"];

        $columns = ['created_at', 'transaction_type', 'approved_status', 'amount'];
        $orderby = $columns[$order];

        $result = Deposit::where('user_id', $id)->select();

        $count = $result->count();
        $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
        $data = array();
        $i = 0;


        foreach ($result as $key => $value) {
            $status = (strtolower($value->approved_status) === 'p') ? '<span class="badge badge-light-warning">Pending</span>' : '<span class="badge badge-light-success">Approved</span>';
            $data[$i]["amount"]     = '<span>&dollar; ' . $value->amount . '</span>';
            $data[$i]["method"]     = $value->transaction_type;
            $data[$i]["status"]     = $status;
            $data[$i]["date"]       = date('d F y, h:i A', strtotime($value->created_at));
            $i++;
        }

        $output = array('draw' => $draw, 'recordsTotal' => $count, 'recordsFiltered' => $count);
        $output['data'] = $data;
        return Response::json($output);
    }
    //total withdraw report for balance transfer request
    public function balanceWithdrawReport(Request $request, $id)
    {
        $result = Withdraw::where('user_id', $id)
            ->count();
        $recordsTotal = $result;
        $recordsFiltered = $result;

        $limit = '';
        $sortBy = $_REQUEST['order'][0]['dir'];
        $order_a =  $_REQUEST['order'];
        $order = $order_a[0]['dir'];
        $oc = $order_a[0]['column'];
        $ocd = $_REQUEST['columns'][$oc]['data'];

        if (isset($_REQUEST['start']) && $_REQUEST['length'] != -1) {
            $limit = " ORDER BY copy_rebalances.$ocd $sortBy LIMIT " . intval($_REQUEST['start']) . ", " . intval($_REQUEST['length']);
        }
        // select type= 0 for trader 
        $result = Withdraw::where('user_id', $id)->select()
            ->get();
        $data = array();
        $i = 0;

        foreach ($result as $key => $value) {
            $status = (strtolower($value->approved_status) === 'p') ? '<span class="badge badge-light-warning">Pending</span>' : '<span class="badge badge-light-success">Approved</span>';
            $data[$i]["amount"]     = '<span>&dollar; ' . $value->amount . '</span>';
            $data[$i]["method"]     = $value->transaction_type;
            $data[$i]["status"]     = $status;
            $data[$i]["date"]       = date('d F y, h:i A', strtotime($value->created_at));
            $i++;
        }

        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered);
        $output['data'] = $data;
        return Response::json($output);
    }
    //total bonus report for balance transfer request
    public function balanceBonusReport(Request $request, $id)
    {
        try {
            $result = BonusUser::where('user_id', $id)
                ->count();
            $recordsTotal = $result;
            $recordsFiltered = $result;

            $limit = '';
            $sortBy = $_REQUEST['order'][0]['dir'];
            $order_a =  $_REQUEST['order'];
            $order = $order_a[0]['dir'];
            $oc = $order_a[0]['column'];
            $ocd = $_REQUEST['columns'][$oc]['data'];

            if (isset($_REQUEST['start']) && $_REQUEST['length'] != -1) {
                $limit = " ORDER BY copy_rebalances.$ocd $sortBy LIMIT " . intval($_REQUEST['start']) . ", " . intval($_REQUEST['length']);
            }
            // select type= 0 for trader 
            $result = BonusUser::where('bonus_users.user_id', $id)->select()
                ->join('bonus_packages', 'bonus_users.bonus_package', '=', 'bonus_packages.id')
                ->join('trading_accounts', 'bonus_users.user_id', '=', 'trading_accounts.user_id')
                ->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                $bonus_amount = 0;
                if ($value->bonus_amount == 0) {
                    $deposit_amount = $value->amount;
                    $bonus_amount = $deposit_amount * ($value->bonus_percent / 100);
                } else {
                    $bonus_amount = $value->bonus_amount;
                }
                $data[$i]["amount"]           = '<span>&dollar; ' . $bonus_amount . '</span>';
                $data[$i]["bonus_title"]      = $value->pkg_name;
                $data[$i]["platform"]         = $value->platform;
                $data[$i]["start_date"]       = date('d F y, h:i A', strtotime($value->start_date));
                $data[$i]["status"]           = $value->credit_type;
                $data[$i]["ending_date"]      = date('d F y, h:i A', strtotime($value->end_date));

                $i++;
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    public function accountTradingReport(Request $request, $id)
    {
        try {
            $result = TradingAccount::where('id', $id)
                ->count();
            $recordsTotal = $result;
            $recordsFiltered = $result;

            $limit = '';
            $sortBy = $_REQUEST['order'][0]['dir'];
            $order_a =  $_REQUEST['order'];
            $order = $order_a[0]['dir'];
            $oc = $order_a[0]['column'];
            $ocd = $_REQUEST['columns'][$oc]['data'];

            if (isset($_REQUEST['start']) && $_REQUEST['length'] != -1) {
                $limit = " ORDER BY copy_rebalances.$ocd $sortBy LIMIT " . intval($_REQUEST['start']) . ", " . intval($_REQUEST['length']);
            }
            // select type= 0 for trader 
            $result = TradingAccount::where('trading_accounts.id', $id)->select('trading_accounts.account_number', 'client_groups.server', 'client_groups.group_name', 'trading_accounts.leverage', 'client_groups.created_at')
                ->leftJoin('client_groups', 'trading_accounts.group_id', '=', 'client_groups.id')
                ->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                $data[$i]["acount_number"]     = $value->account_number;
                $data[$i]["platform"]          = strtoupper($value->server);
                $data[$i]["group"]             = $value->group_name;
                $data[$i]["leverage"]          = json_decode($value->leverage);
                $data[$i]["date"]              = date('d F y, h:i A', strtotime($value->created_at));
                $i++;
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    //Approve Balance Request
    public function approveBalanceRequest(Request $request, $id)
    {

        $ext_transfer = ExternalFundTransfers::where('id', $id)->first();
        $receiver = User::where('id', $ext_transfer->receiver_id)->first();
        // if sender is trader/get trader self balance
        if (User::where('id', $ext_transfer->sender_id)->where('type', 0)->exists()) {
            $self_balance = AllFunctionService::trader_total_balance($ext_transfer->sender_id);
        }
        // else sender is an IB/get IB self balance
        else {
            $self_balance = BalanceService::ib_balance($ext_transfer->sender_id);
        }


        //==============admin device track script======================
        $update = ExternalFundTransfers::where('id', $id)->update([
            'status' => 'A',
            'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
            'approved_by' => auth()->user()->id,
            'admin_log' => AdminLogService::admin_log()
        ]);

        if ($update) {
            // insert activity-----------------
            $user = User::find($ext_transfer->sender_id); //<---client email as user id
            activity($ext_transfer->sender_wallet_type . " approved balance transfer request")
                ->causedBy(auth()->user()->id)
                ->withProperties($ext_transfer)
                ->event($ext_transfer->sender_wallet_type . " balance transfer")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been approved balance transfer request");
            // end activity log-----------------
            $mailstatus = EmailService::send_email('balance-approve', [
                'user_id' => $ext_transfer->sender_id,
                'reciver_email' => ($receiver) ? $receiver->email : '',
                'transfer_date' => $ext_transfer->created_at,
                'previous_balance' => ($self_balance) + ($ext_transfer->amount),
                'approved_amount' => $ext_transfer->amount,
                'total_balance' => $self_balance
            ]);
            MailNotificationService::admin_notification([
                'amount' => $ext_transfer->amount,
                'name' => $user->name,
                'email' => $user->email,
                'type' => 'balance transfer approve',
                'client_type' => strtolower($ext_transfer->sender_wallet_type)
            ]);
            if ($mailstatus) {
                return Response::json([
                    'success' => true,
                    'message' => 'Mail successfully sent for Approved Balance request',
                    'success_title' => 'Approve request'
                ]);
            }
            return Response::json([
                'success' => true,
                'message' => 'Mail sending failed, Please try again later!',
                'success_title' => 'Approve request'
            ]);
        }
        return Response::json([
            'success' => false,
            'message' => 'Something went wrong, Please try again later!',
            'success_title' => 'Approve request'
        ]);
    }
    //Balance Decline Request 
    public function declineBalanceRequest(Request $request)
    {
        $table_id = $request->decline_id;
        $user = User::select()->where('id', auth()->user()->id)->first();
        $ext_transfer = ExternalFundTransfers::where('id', $table_id)->first();
        $receiver = User::where('id', $ext_transfer->receiver_id)->first();
        // if sender is trader/get trader self balance
        if (User::where('id', $ext_transfer->sender_id)->where('type', 0)->exists()) {
            $self_balance = AllFunctionService::trader_total_balance($ext_transfer->sender_id);
        }
        // else sender is an IB/get IB self balance
        else {
            $self_balance = BalanceService::ib_balance($ext_transfer->sender_id);
        }

        $reason = $request->input('reason');
        $update = ExternalFundTransfers::where('id', $table_id)->update([
            'status' => 'D',
            'note' => $reason,
            'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
            'approved_by' => auth()->user()->id,
            'admin_log' => AdminLogService::admin_log()
        ]);
        if ($update) {
            $mailstatus = EmailService::send_email('balance-decline', [
                'user_id' => $ext_transfer->sender_id,
                'reciver_email' => ($receiver) ? $receiver->email : '',
                'transfer_date' => $ext_transfer->created_at,
                'previous_balance' => ($self_balance),
                'approved_amount' => $ext_transfer->amount,
                'total_balance' => ($self_balance),
            ]);
            // insert activity-----------------
            $user = User::find($ext_transfer->sender_id); //<---client email as user id
            activity(" declined " . $ext_transfer->sender_wallet_type . " balance transfer request")
                ->causedBy(auth()->user()->id)
                ->withProperties($ext_transfer)
                ->event($ext_transfer->sender_wallet_type . " balance transfer")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been declined balance transfer request");
            // end activity log-----------------
            MailNotificationService::admin_notification([
                'amount' => $ext_transfer->amount,
                'name' => $user->name,
                'email' => $user->email,
                'type' => 'balance transfer decline',
                'client_type' => strtolower($ext_transfer->sender_wallet_type)
            ]);
            if ($mailstatus) {
                return Response::json([
                    'success' => true,
                    'message' => 'Mail successfully sent for Balance Declined request',
                    'success_title' => 'Declined request'
                ]);
            }
            return Response::json([
                'success' => false,
                'message' => 'Mail sending failed, Please try again later!',
                'success_title' => 'Declined request'
            ]);
        }
        return Response::json([
            'success' => false,
            'message' => 'Something went wrong, Please try again later!',
            'success_title' => 'Declined request'
        ]);
    }
}
