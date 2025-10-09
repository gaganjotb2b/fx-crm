<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\IBApproveRequest;
use App\Mail\IBDeclinedRequest;
use App\Models\admin\SystemConfig;
use App\Models\BankAccount;
use App\Models\Country;
use App\Models\IB;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\CombinedService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\systems\VersionControllService;

class IBWithdrawController extends Controller
{


    public function __construct()
    {
        $this->middleware(["role:ib withdraw request"]);
        $this->middleware(["role:manage request"]);
        // system module control
        $this->middleware(AllFunctionService::access('manage_request', 'admin'));
        $this->middleware(AllFunctionService::access('ib_withdraw_request', 'admin'));
    }
    public function ibWithdrawRequest(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->IBWithdrawReport($request);
        }
        $countries = Country::all();
        $crmVarsion = VersionControllService::check_version();

        $methods = Withdraw::select('transaction_type')->distinct()->get();
        return view('admins.reports.ib-withdraw-request', compact('methods'), ['countries' => $countries, 'varsion' => $crmVarsion,]);
    }

    public function IBWithdrawReport($request)
    {
        try {
            $columns = ['users.name', 'users.email', 'withdraws.transaction_type', 'withdraws.approved_status', 'withdraws.created_at', 'withdraws.amount'];
            $orderBy = $columns[$request->order[0]['column']];
            $results = Withdraw::select(
                'withdraws.id',
                'withdraws.user_id',
                'withdraws.transaction_type',
                'withdraws.amount',
                'withdraws.approved_status',
                'withdraws.note',
                'withdraws.wallet_type',
                'withdraws.created_at',
                'users.name',
                'users.email',
                'users.type',
                'users.email_verified_at',
                'users.kyc_status'
            )
                ->join('users', 'users.id', '=', 'withdraws.user_id')
                ->where('withdraws.wallet_type', 'ib')
                ->where('users.type', CombinedService::type());
            // check crm is combined
            if (CombinedService::is_combined()) {
                $results = $results->where('users.combine_access', 1);
            }

            $total_amount = $results->sum('amount');

            /*<-------filter search script start here------------->*/
            // filter by login manager
            if (strtolower(auth()->user()->type) === 'manager') {
                $manager_user = ManagerUser::where('manager_id', auth()->user()->id)->get('user_id')->pluck('user_id');
                $results = $results->where('withdraws.user_id', $manager_user);
            }
            // filter by transaction method
            if ($request->method != "") {
                $results = $results->where('transaction_type', $request->method);
            }
            // filter by verification status
            if ($request->verification != "") {
                $results = $results->where('users.kyc_status', $request->verification);
            }
            // filter by withraw approved status
            if ($request->approved_status != "") {
                $results = $results->where('approved_status', $request->approved_status);
            }
            // filter by account manager email
            if ($request->ib_info != "") {
                // get ib id from info
                $ib_info = $request->ib_info;
                $country = Country::where(function ($q) use ($ib_info) {
                    $q->where('name', 'like', '%' . $ib_info . '%');
                })->get()->pluck('id');
                $ib_ids = User::where(function ($query) use ($ib_info, $country) {
                    $query->where('email', 'like', '%' . $ib_info . '%')
                        ->orWhere('name', 'like', '%' . $ib_info . '%')
                        ->orWhereIn('country_id', $country)
                        ->orWhere('phone', 'like', '%' . $ib_info . '%');
                })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->select('users.id as client_id')
                    ->get()->pluck('client_id');
                // get data from externalfund transfer
                $results = $results->where(function ($q) use ($ib_ids) {
                    $q->whereIn('user_id', $ib_ids);
                });
            }
            // filter by trader info
            if ($request->trader_info != "") {
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
                // get ib id from this client
                $ib_ids = IB::whereIn('reference_id', $traders)->get()->pluck('ib_id');
                // get data from externalfund transfer
                $results = $results->where(function ($q) use ($ib_ids) {
                    $q->whereIn('user_id', $ib_ids);
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
                    $q->whereIn('user_id', $manager_user);
                });
            }
            // filter by trading account
            if ($request->trading_account != "") {
                // find trader 
                $trading_account = TradingAccount::where('account_number', $request->trading_account)->select('user_id')->first();
                // get ib from this client
                $ib_ids = IB::where('reference_id', $trading_account->user_id)->select('ib_id')->first();
                $results = $results->where('user_id', $ib_ids);
            }
            // filter by min request amount
            if ($request->min != "") {
                $results = $results->where('amount', '>=', $request->min);
                $total_amount = $results->where('amount', '>=', $request->min)->sum('amount');
            }
            // filter by max request amount
            if ($request->max != "") {
                $results = $results->where('amount', '<=', $request->max);
                $total_amount = $results->where('amount', '<=', $request->max)->sum('amount');
            }
            // flter by date from
            if ($request->from != "") {
                $results = $results->whereDate('withdraws.created_at', '>=', $request->from);
                $total_amount = $results->whereDate('withdraws.created_at', '>=', $request->from)->sum('amount');
            }
            // filter by date to
            if ($request->to != "") {
                $results = $results->whereDate('withdraws.created_at', '<=', $request->to);
                $total_amount = $results->whereDate('withdraws.created_at', '<=', $request->to)->sum('amount');
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

            $count_row = $results->count();
            $recordsTotal = $count_row;
            $recordsFiltered = $count_row;
            $total_amount = $results->sum('withdraws.amount');
            // $results = $results->orderBy('withdraws.user_id', 'DESC')->skip($start)->take($request->length)->get();
            $results = $results->orderBy($orderBy, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;


            foreach ($results as $value) {
                if ($value->approved_status == 'P') {
                    $status = '<span class="bg-light-warning badge">Pending</span>';;
                }
                if ($value->approved_status == 'D') {
                    $status = '<span class="bg-light-danger badge">Declined</span>';
                }
                if ($value->approved_status == "A") {
                    $status = '<span class="bg-light-success badge">Approved</span>';
                }
                // make kyc status badge
                if ($value->kyc_status == 1) {
                    $check_uncheck = '<span class="badge badge-light-success">KYC Verified</span>';
                } else {
                    $check_uncheck = '<span class="badge badge-light-warning">KYC Unverified</span>';
                }

                $data[$i]['name'] = '<a href="#" data-id="' . $value->id . '" class="dt-description d-flex justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span>' . (isset($check_uncheck) ? '<span class="text-success">' . $value->name . '</span>' : '<span class="text-danger">' . $value->name . '</span>') . '</a>';
                $data[$i]['email'] = $value->email;
                $data[$i]['method'] = ucfirst($value->transaction_type);
                $data[$i]['status'] = $status;
                $data[$i]['date'] = date('d M y, h:i A', strtotime($value->created_at));
                $data[$i]['amount'] = '$' . $value->amount;
                $i++;
            }
            return Response::json([
                'draw' => $_REQUEST['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'total_amount' => $total_amount,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $_REQUEST['draw'],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'total_amount' => 0,
                'data' => [],
            ]);
        }
    }

    public function ibWithdrawDescription(Request $request, $id)
    {
        // return $table_id;
        $withdraw = Withdraw::select(
            'withdraws.*',

        )
            ->leftJoin('other_transactions', 'withdraws.other_transaction_id', '=', 'other_transactions.id')
            ->join('users', 'withdraws.user_id', '=', 'users.id')
            ->where('withdraws.id', $id)->first();
        $buttons = "";
        if (strtoupper($withdraw->approved_status) === 'P') {
            $auth_user = User::find(auth()->user()->id);
            if ($auth_user->hasDirectPermission('edit ib withdraw request')) {
                $buttons = '<div class="details-text px-2 pb-2" style="float:right;">
                        <button   data-type="button"  class="btn btn-primary waves-effect waves-float waves-light btn-transaction-approve"  data-loading="processing..."  data-id="' . $id . '">Approve</button>
                        <button   type="button"  class="btn btn-danger waves-effect waves-float waves-light btn-transaction-declined"  data-loading="processing..."  data-id="' . $id . '">Decline</button>
                    </div>';
            } else {
                $buttons = "";
            }
        }
        $innerTH = "";
        $innerTD = "";
        if (strtolower($withdraw->transaction_type) === 'bank') {
            $innerTH .= '
                <th>Amount Request</th>
                <th>Bank Name</th>
                <th>Bank AC Name</th>
                <th>Bank AC No</th>
                <th>Bank Swift Code</th>
                <th>Bank IBAN</th>
                <th>Bank Country</th>';
            $bank_country = Country::select('name')->where('id', $withdraw->bankAccount->bank_country)->first();
            $amount = '$' . $withdraw->amount;
            $innerTD .= '
                <td>' . $amount . '</td>
                <td>' . $withdraw->bankAccount->bank_name . '</td>
                <td>' . $withdraw->bankAccount->bank_ac_name . '</td>
                <td>' . $withdraw->bankAccount->bank_ac_number . '</td>
                <td>' . $withdraw->bankAccount->bank_swift_code . '</td>
                <td>' . $withdraw->bankAccount->bank_iban . '</td>
                <td>' . $bank_country->name . '</td>';
        } else if (strtolower($withdraw->transaction_type) === 'crypto') {
            $amount = '$' . $withdraw->amount;
            $innerTH .= '
                <th>Amount Request</th>
                <th>Crypto Type</th>
                <th>Crypto Address</th>';

            $innerTD .= '
                <th>' . $amount . '</th>
                <th>' . $withdraw->crypto_type . '</th>
                <th>' . $withdraw->crypto_address . '</th>';
        } else {
            $amount = '$' . $withdraw->amount;
            $innerTH .= '
                <th>Amount Request</th>
                <th>Account Name</th>
                <th>Account Email</th>';
            $innerTD .= '
                <th>' . $amount . '</th>
                <th>' . $withdraw->otherTransaction->account_name . '</th>
                <th>' . $withdraw->otherTransaction->account_email . '</th>';
        }

        //===========================Admin Information condition=================================////
        $innerTH1 = "";
        $innerTD1 = "";
        $approved_by = "";
        if ($withdraw->approved_status === 'A' || $withdraw->approved_status === 'D') {
            $approved_by = ($withdraw->approved_status) == 'A' ? "Approved By:" : "Declined By:";
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
                    <b>' . ucwords($withdraw->transaction_type) . ' Details: </b>
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

        $description = '<tr class="description" style="display:none">
            <td colspan="7">
                <div class="details-section-dark border-start-3 border-start-primary" style="display: flow-root;">
                    <table id="ib-withdraw-details' . $id . '" class="ib-withdraw-details table">
                        <thead>
                            ' . $withdraw_data . '
                        </thead>
                        
                    </table>
                    <br>
                    ' . $buttons . '
                </div>
            </td>
            
        </tr>';

        return Response::json([
            'status' => true,
            'description' => $description
        ]);
    }
    //<---------Script for Approved Request----------->
    public function ibApproveRequest(Request $request)
    {
        $id = $request->id;
        $withdraws = Withdraw::where('id', $id)->first();

        // update withdraw table
        $update = Withdraw::where('id', $id)->update([
            'approved_status' => 'A',
            'approved_by' => auth()->user()->id,
            'admin_log' => AdminLogService::admin_log(),
            'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
        ]);

        // sending email
        $self_balance = BalanceService::get_ib_balance_v2($withdraws->user_id);

        if ($update) {
            // insert activity-----------------
            $user = User::find($withdraws->user_id); //<---client email as user id
            activity(" approved IB withdraw request")
                ->causedBy(auth()->user()->id)
                ->withProperties($withdraws)
                ->event("IB withdraw approved")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been approved IB withdraw  request");
            // end activity log-----------------
            $mail_status = EmailService::send_email('ib-withdraw-approve', [
                'user_id' => $withdraws->user_id,
                'request_date' => date('Y M d', strtotime($withdraws->created_at)),
                'withdraw_method' => ucwords($withdraws->transaction_type),
                'previous_balance' => $self_balance,
                'approved_amount' => $withdraws->amount,
                'total_balance' => ($self_balance) + ($withdraws->amount)
            ]);
            MailNotificationService::admin_notification([
                'amount' => $withdraws->amount,
                'name' => $user->name,
                'email' => $user->email,
                'type' => 'withdraw approve',
                'client_type' => strtolower($withdraws->wallet_type)
            ]);
            if ($mail_status) {
                return Response::json([
                    'status' => true,
                    'message' => 'Mail successfully sent for IB Approved request',
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

    public function ibDeclinedRequest(Request $request)
    {
        $id = $request->id;
        $withdraws = Withdraw::where('id', $id)->first();


        // update withdraw table
        $update = Withdraw::where('id', $id)->update([
            'approved_status' => 'D',
            'note' => $request->note,
            'approved_by' => auth()->user()->id,
            'admin_log' => AdminLogService::admin_log(),
            'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
        ]);

        $self_balance = BalanceService::get_ib_balance_v2($withdraws->user_id);


        if ($update) {
            // insert activity-----------------
            $user = User::find($withdraws->user_id); //<---client email as user id
            activity(" decline IB withdraw request")
                ->causedBy(auth()->user()->id)
                ->withProperties($withdraws)
                ->event("IB withdraw decline")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been decline IB withdraw  request");
            // end activity log-----------------
            $mail_status = EmailService::send_email('ib-withdraw-decline', [
                'user_id' => $withdraws->user_id,
                'request_date' => date('Y M d', strtotime($withdraws->created_at)),
                'withdraw_method' => ucwords($withdraws->transaction_type),
                'previous_balance' => $self_balance,
                'approved_amount' => $withdraws->amount,
                'total_balance' => ($self_balance)
            ]);
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
                    'message' => 'Mail successfully sent for IB Declined request',
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
}
