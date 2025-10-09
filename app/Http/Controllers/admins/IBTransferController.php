<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\IBTransferApproveRequest;
use App\Mail\IbTransferDeclineRequest;
use App\Models\admin\SystemConfig;
use App\Models\Country;
use App\Models\ExternalFundTransfers;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\BalanceService;
use App\Services\CombinedService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;
use App\Services\Transfer\ExternalTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\systems\VersionControllService;
class IBTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:ib transfer"]);
        $this->middleware(["role:manage request"]);
        // system module control
        $this->middleware(AllFunctionService::access('manage_request', 'admin'));
        $this->middleware(AllFunctionService::access('ib_transfer_request', 'admin'));
    }
    public function IBTransfer(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->IBTrasnferReport($request);
        }
        $crmVarsion = VersionControllService::check_version();
        $countries = Country::all();
        return view('admins.reports.ib-transfer-report',['varsion' => $crmVarsion, 'countries' => $countries]);
    }

    public function IBTrasnferReport($request)
    {
        try {
            $columns = ['users.name', 'users.email', 'receiver_id', 'receiver_wallet_type', 'external_fund_transfers.status', 'external_fund_transfers.created_at', 'amount'];
            $orderBy = $columns[$request->order[0]['column']];
            $result = ExternalFundTransfers::select(
                'external_fund_transfers.id',
                'external_fund_transfers.sender_id',
                'external_fund_transfers.receiver_id',
                'external_fund_transfers.amount',
                'external_fund_transfers.charge',
                'external_fund_transfers.created_at',
                'external_fund_transfers.status',
                'external_fund_transfers.sender_wallet_type',
                'external_fund_transfers.receiver_wallet_type',
                'users.id as client_id',
                'users.name as sender_name',
                'users.email as sender_email',
                'users.kyc_status',
                'users.type'
            )
                ->join('users', 'external_fund_transfers.sender_id', '=', 'users.id')
                ->where('sender_wallet_type', 'ib')
                ->where('users.type', CombinedService::type());
            // check crm is combined
            if (CombinedService::is_combined()) {
                $result = $result->where('users.combine_access', 1);
            }

            //------------------------------------------------------------------------------------------
            //Filter Start
            //------------------------------------------------------------------------------------------
            // filter by kyc verification status
            if ($request->verification != "") {
                $result = $result->where('users.kyc_status', $request->verification);
            }
            // filter by approved status
            if ($request->approved_status != "") {
                $result = $result->where('status', '=', $request->approved_status);
            }
            //Filter By  receiver Client Type
            if ($request->client_type != "") {
                $result = $result->where('receiver_wallet_type', $request->client_type);
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
                $result = $result->where(function ($q) use ($traders) {
                    $q->whereIn('sender_id', $traders)
                        ->orWhereIn('receiver_id', $traders);
                });
            }

              //Filter By Country
              if ($request->country != "") {
                $trader_country = $request->country;
                $user_id = User::select('countries.name')->where(function ($query) use ($trader_country) {
                    $query->where('countries.name', 'LIKE', '%' . $trader_country . '%');  
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
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
                $result = $result->where(function ($q) use ($traders) {
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
                $result = $result->where(function ($q) use ($senders) {
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
                $result = $result->where(function ($q) use ($receiver) {
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
                $result = $result->where(function ($q) use ($manager_user) {
                    $q->whereIn('sender_id', $manager_user)
                        ->orWhereIn('receiver_id', $manager_user);
                });
            }
            // filter by trading account
            if ($request->trading_account != "") {
                $trading_account = TradingAccount::where('account_number', $request->trading_account)->select('user_id')->first();
                $user_id = $trading_account->user_id;
                $result = $result->where(function ($query) use ($user_id) {
                    $query->where('sender_id', $user_id)
                        ->orWhere('receiver_id', $user_id);
                });
            }
            // filter by min / max amount
            // filter by min amount
            if ($request->min != "") {
                $result = $result->where('amount', '>=', $request->min);
            }
            // filter by max amount
            if ($request->max != "") {
                $result = $result->where('amount', '<=', $request->max);
            }
            // filter by date from
            if ($request->from != "") {
                $result = $result->whereDate('external_fund_transfers.created_at', '>=', $request->from);
            }
            // filter by date to
            if ($request->to != "") {
                $result = $result->whereDate('external_fund_transfers.created_at', '<=', $request->to);
            }

            $count = $result->count();
            $total_amount = $result->sum('amount');

            $result = $result->orderBy($orderBy, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();

            $data = array();
            $i = 0;

            foreach ($result as $value) {
                if ($value->status == 'P') {
                    $status = '<span class="bg-light-warning badge">Pending</span>';
                } elseif ($value->status == 'A') {
                    $status = '<span class="bg-light-success badge">Approved</span>';
                } elseif ($value->status == 'D') {
                    $status = '<span class="bg-light-danger badge">Declined</span>';
                }

                // receiver client type
                $receiver_type = '';
                if ($value->receiver_wallet_type === 'ib') {
                    $receiver_type = '<span class="bg-warning badge">' . strtoupper($value->receiver_wallet_type) . '</span>';
                } else {
                    $receiver_type = '<span class="bg-success badge">' . ucwords($value->receiver_wallet_type) . '</span>';
                }

                $data[$i]['sender_name'] = '<a href="#" data-id=' . $value->id . '  class="dt-description d-flex justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span><span>' . $value->sender_name . '</span></a>';
                $data[$i]['sender_email'] = $value->sender_email;
                $data[$i]['receiver_email'] = AllFunctionService::user_email($value->receiver_id);
                $data[$i]['receiver_type'] = $receiver_type;
                $data[$i]['status'] = $status;
                $data[$i]['request_date'] = $value->created_at->toDateTimeString();
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

    public function ibTransferDescription(Request $request, $id)
    {
        $external_transfer = ExternalFundTransfers::where('external_fund_transfers.id', $id)
            ->join('users', 'external_fund_transfers.sender_id', '=', 'users.id')
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->select(
                'external_fund_transfers.*',
                'users.name as sender_name',
                'users.email as sender_email',
                'users.phone as sender_phone',
                'user_descriptions.address',
                'user_descriptions.city'
            )
            ->first();
        $receiver_information = User::where('users.id', $external_transfer->receiver_id)
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->select(
                'users.name as receiver_name',
                'users.email as receiver_email',
                'users.phone as receiver_phone',
                'user_descriptions.address as receiver_address'
            )
            ->first();
        $buttons = "";
        if ($external_transfer->status === 'P') {
            $auth_user = User::find(auth()->user()->id);
            if ($auth_user->hasDirectPermission('edit ib transfer')) {
                $buttons = '<div class="details-text w-100">
                        <div class="btn-container p-0 m-0" style="float:right;">
                            <button data-type="button" class="btn btn-primary waves-effect waves-float waves-light btn-transaction-approve"  data-loading="processing..." data-id="' . $id . '">Approve</button>
                            <button type="button" class="btn btn-danger waves-effect waves-float waves-light btn-transaction-declined"  data-loading="processing..." data-id="' . $id . '"   >Decline</button>
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
        if ($external_transfer->status === 'A' || $external_transfer->status === 'D') {
            $approved_by = ($external_transfer->status) == 'A' ? "APPROVED BY:" : "DECLINED BY:";
            $admin_info = User::select('name', 'email')->where('id', $external_transfer->approved_by)->first();
            $admin_name = isset($admin_info->name) ? $admin_info->name : '---';
            $admin_email = isset($admin_info->email) ? $admin_info->email : '---';
            $admin_json_data = json_decode($external_transfer->admin_log);
            $ip = isset($admin_json_data->ip) ? $admin_json_data->ip : '---';
            $wname = isset($admin_json_data->wname) ? $admin_json_data->wname : '---';
            $action_date = isset($external_transfer->approved_date) ? date('d M Y, h:i A', strtotime($external_transfer->approved_date)) : '---';

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
                    <div class="col-lg-4">
                        <div class="rounded-0 w-75">
                            <div class="p-0">    
                                <table class="table table-responsive tbl-balance">
                                    <tr>
                                        <th class="" colspan="2">Sender :</th>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2 ">Name</th>
                                        <td class="border-end-0 ">' . $external_transfer->sender_name . '</td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2 ">Address</th>
                                        <td class="border-end-0 ">' . $external_transfer->address . '</td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2 ">Phone</th>
                                        <td class="border-end-0 ">' . $external_transfer->sender_phone . '</td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2 border-bottom-0">Email</th>
                                        <td class="border-end-0 border-bottom-0">' . $external_transfer->sender_email . '</td>
                                    </tr>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="rounded-0 w-75">
                            <div class="p-0">    
                                <table class="table table-responsive tbl-balance">
                                    <tr>
                                        <th class="" colspan="2">Receiver :</th>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2 ">Name</th>
                                        <td class="border-end-0 ">' . $receiver_information->receiver_name . '</td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2 ">Address</th>
                                        <td class="border-end-0 ">' . $receiver_information->receiver_address . '</td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2 ">Phone</th>
                                        <td class="border-end-0 ">' . $receiver_information->receiver_phone . '</td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2 border-bottom-0">Email</th>
                                        <td class="border-end-0 border-bottom-0">' . $receiver_information->receiver_email . '</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="rounded-0 w-75">
                            <div class="p-0">    
                                <table class="table table-responsive tbl-balance">
                                    <tr>
                                        <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2">Transaction</th>
                                        <td class="border-end-0">' . $external_transfer->txnid . '</td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2 text-truncate">Payment Due</th>
                                        <td class="border-end-0 text-truncate">' . date('Y M d h:i:s', strtotime($external_transfer->created_at)) . '</td>
                                    </tr>
                                    <tr>
                                        <th style="border-left: 3px solid var(--custom-primary) !important;" class="border-end-2 border-bottom-0">Amount</th>
                                        <td class="border-end-0 border-bottom-0">' . round($external_transfer->amount, 2) . '</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12" style="margin-top: 10px;">
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
    //IB Transfer Approve Request operation
    public function ibTransferApprove(Request $request)
    {
        $id = $request->id;
        $external_transfer = ExternalFundTransfers::where('external_fund_transfers.id', $id)
            ->select('external_fund_transfers.*')->first();

        // update ib transfer table
        $update = ExternalFundTransfers::where('id', $id)->update([
            'status' => 'A',
            'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
            'approved_by' => auth()->user()->id,
            'admin_log' => AdminLogService::admin_log()
        ]);
        $self_balance = BalanceService::get_ib_balance_v2($external_transfer->sender_id);
        $receiver = User::find($external_transfer->receiver_id);

        if ($update) {
            $email_status = EmailService::send_email('ib-balance-transfer-approved', [
                'user_id' => $external_transfer->sender_id,
                'reciver_email' => ($receiver) ? $receiver->email : '',
                'transfer_date' => date('Y M d h:i:s', strtotime($external_transfer->created_at)),
                'previous_balance' => $self_balance,
                'approved_amount' => $external_transfer->amount,
                'total_balance' => $self_balance
            ]);
            // insert activity-----------------
            $user = User::find($external_transfer->sender_id); //<---client email as user id
            activity(" approved IB balance transfer request")
                ->causedBy(auth()->user()->id)
                ->withProperties($external_transfer)
                ->event("IB balance transfer")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been approved balance transfer request");
            // end activity log-----------------
            MailNotificationService::admin_notification([
                'amount' => $external_transfer->amount,
                'name' => $user->name,
                'email' => $user->email,
                'type' => 'balance transfer approve',
                'client_type' => strtolower($external_transfer->sender_wallet_type)
            ]);
            if ($email_status) {
                return Response::json([
                    'status' => true,
                    'message' => 'Mail successfully sent for Approved IB Transfer request',
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

    // IB Transfer Decline Request Operation
    public function ibTransferDecline(Request $request)
    {
        $id = $request->id;
        $external_transfer = ExternalFundTransfers::where('id', $id)->first();

        // update ib_transfer table
        $update = ExternalFundTransfers::where('id', $id)->update([
            'status' => 'D',
            'note' => $request->note,
            'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
            'approved_by' => auth()->user()->id,
            'admin_log' => AdminLogService::admin_log()
        ]);
        $self_balance = BalanceService::get_ib_balance_v2($external_transfer->sender_id);
        $receiver = User::find($external_transfer->receiver_id);

        if ($update) {
            // insert activity-----------------
            $user = User::find($external_transfer->sender_id); //<---client email as user id
            activity(" decline IB balance transfer request")
                ->causedBy(auth()->user()->id)
                ->withProperties($external_transfer)
                ->event("IB balance transfer")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been decline balance transfer request");
            // end activity log-----------------

            $email_status = EmailService::send_email('balance-decline', [
                'user_id' => $external_transfer->sender_id,
                'reciver_email' => ($receiver) ? $receiver->email : '',
                'transfer_date' => $external_transfer->created_at,
                'previous_balance' => $self_balance - $external_transfer->amount,
                'approved_amount' => $external_transfer->amount,
                'total_balance' => $self_balance
            ]);
            MailNotificationService::admin_notification([
                'amount' => $external_transfer->amount,
                'name' => $user->name,
                'email' => $user->email,
                'type' => 'balance transfer decline',
                'client_type' => strtolower($external_transfer->sender_wallet_type)
            ]);
            if ($email_status) {
                return Response::json([
                    'status' => true,
                    'message' => 'Mail successfully sent for IB Transfer Declined request'
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
