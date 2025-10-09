<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\ExternalFundTransfers;
use App\Models\IB;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IBFundTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:ib fund transfer"]);
        $this->middleware(["role:reports"]);

        // system module control
        $this->middleware(AllFunctionService::access('reports', 'admin'));
        $this->middleware(AllFunctionService::access('ib_fund_transfer', 'admin'));
    }
    public function fundTransferReport(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->fundTransferReportDT($request);
        }
        return view('admins.reports.fund-transfer-report');
    }

    public function fundTransferReportDT($request)
    {
        try {
            $approve_status = $request->input('approve_status');
            $columns = ['name', 'email', 'email', 'receiver_wallet_type','external_fund_transfers.status', 'external_fund_transfers.created_at', 'external_fund_transfers.amount'];
            $result = ExternalFundTransfers::select(
                'external_fund_transfers.sender_id',
                'external_fund_transfers.receiver_id',
                'external_fund_transfers.amount',
                'external_fund_transfers.charge',
                'external_fund_transfers.note',
                'external_fund_transfers.status',
                'external_fund_transfers.created_at',
                'external_fund_transfers.id as transfer_id',
                'external_fund_transfers.sender_wallet_type',
                'external_fund_transfers.receiver_wallet_type',
                'users.name',
                'users.email',
                'users.kyc_status',
                'users.type'
            )->whereIn('external_fund_transfers.type',['ib_to_trader','ib_to_ib']);
            $result = $result->join('users', 'external_fund_transfers.sender_id', '=', 'users.id');
            // check login is manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $users_id);
            }

            //-------------------------------------------------------------------------------------
            //Filter Start
            //-------------------------------------------------------------------------------------
            if ($approve_status != "") {;
                $result = $result->where('status', $approve_status);
            }
            // filter by kyc status
            if ($request->verifi_status != "") {
                $result = $result->where('users.kyc_status', $request->verifi_status);
            }
            // filter by receiver client type
            if ($request->receiver_client_type != "") {
                $result = $result->where('receiver_wallet_type', $request->receiver_client_type);
            }
            //Filter By Trader Name / Email
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $trader_id = User::where('type', 0)
                    ->where(function ($query) use ($trader_info) {
                        $query->where('users.name', 'LIKE', '%' . $trader_info . '%')
                            ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                            ->orWhere('users.phone', 'LIKE', '%' . $trader_info . '%')
                            ->orWhere('countries.name', 'LIKE', '%' . $trader_info . '%');
                    })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                $ib_id = IB::whereIn('reference_id', $trader_id)->select('ib_id')->get()->pluck('ib_id');
                $result = $result->where(function ($query) use ($ib_id) {
                    $query->whereIn('sender_id', $ib_id)
                        ->orWhereIn('receiver_id', $ib_id);
                });
            }
            //Filter By IB Name / Email
            if ($request->ib_info != "") {
                $ib_info = $request->ib_info;
                $ib_id = User::where('type', CombinedService::type())
                    ->where(function ($query) use ($ib_info) {
                        $query->where('users.name', 'LIKE', '%' . $ib_info . '%')
                            ->orWhere('users.email', 'LIKE', '%' . $ib_info . '%')
                            ->orWhere('users.phone', 'LIKE', '%' . $ib_info . '%')
                            ->orWhere('countries.name', 'LIKE', '%' . $ib_info . '%');
                    })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                $reference_id = IB::whereIn('ib_id', $ib_id)->select('reference_id')->get()->pluck('reference_id');

                $result = $result->where(function ($query) use ($reference_id) {
                    $query->whereIn('sender_id', $reference_id)
                        ->orWhereIn('receiver_id', $reference_id);
                });
            }
            // filter by sender info
            if ($request->sender_info != "") {
                $sender_info = $request->sender_info;
                $ib_id = User::where('type', CombinedService::type())
                    ->where(function ($query) use ($sender_info) {
                        $query->where('users.name', 'LIKE', '%' . $sender_info . '%')
                            ->orWhere('users.email', 'LIKE', '%' . $sender_info . '%')
                            ->orWhere('users.phone', 'LIKE', '%' . $sender_info . '%')
                            ->orWhere('countries.name', 'LIKE', '%' . $sender_info . '%');
                    })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');


                $result = $result->where(function ($query) use ($ib_id) {
                    $query->whereIn('sender_id', $ib_id);
                });
            }
            // filter by receiver info
            if ($request->receiver_info != "") {
                $receiver_info = $request->receiver_info;
                $user_id = User::whereIn('type', [0, CombinedService::type()])
                    ->where(function ($query) use ($receiver_info) {
                        $query->where('users.name', 'LIKE', '%' . $receiver_info . '%')
                            ->orWhere('users.email', 'LIKE', '%' . $receiver_info . '%')
                            ->orWhere('users.phone', 'LIKE', '%' . $receiver_info . '%')
                            ->orWhere('countries.name', 'LIKE', '%' . $receiver_info . '%');
                    })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');


                $result = $result->where(function ($query) use ($user_id) {
                    $query->whereIn('receiver_id', $user_id);
                });
            }

            // Filter By Account Number
            if ($request->account_number != "") {
                $trading_accounts  = TradingAccount::where('trading_accounts.account_number', $request->account_number)->select('user_id')->first();
                $result = $result->where('receiver_id', $trading_accounts->user_id);
            }
            //Filter By Manager Name / Email
            if ($request->manager_info != "") {
                $manager_info = $request->manager_info;
                $manager_id = User::select('users.id as manager_id')->where('type', '5')
                    ->where(function ($query) use ($manager_info) {
                        $query->where('users.name', 'LIKE', '%' . $manager_info . '%')
                            ->orWhere('users.email', 'LIKE', '%' . $manager_info . '%')
                            ->orWhere('users.phone', 'LIKE', '%' . $manager_info . '%')
                            ->orWhere('countries.name', 'LIKE', '%' . $manager_info . '%');
                    })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->get()->pluck('manager_id');
                $user_id = ManagerUser::whereIn('manager_id', $manager_id)->select('user_id')->get()->pluck('user_id');
                $result = $result->where(function ($query) use ($user_id) {
                    $query->whereIn('sender_id', $user_id)
                        ->orWhereIn('receiver_id', $user_id);
                });
            }
            // filter by minimum amount
            if ($request->min != "") {
                $result = $result->where("amount", '>=', $request->min);
            }
            // filter by max amount
            if ($request->max != "") {
                $result = $result->where("amount", '<=', $request->max);
            }
            // filter by date from
            if ($request->from != "") {
                $result = $result->whereDate('external_fund_transfers.created_at', '>=', $request->from);
            }
            // filter by date to
            if ($request->to != "") {
                $result = $result->whereDate('external_fund_transfers.created_at', '<=', $request->to);
            }

            /*<-------filter search script End here------------->*/

            $count = $result->count();
            $total_amount = $result->sum('amount');
            $result = $result->orderBy($columns[$request->order[0]['column']], $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $value) {
                if ($value->status == "A") {
                    $status = '<span class="bg-success badge badge-success">Approved</span>';
                }
                if ($value->status == "P") {
                    $status = '<span class="bg-warning badge badge-warning">Pending</span>';
                }
                if ($value->status == "D") {
                    $status = '<span class="bg-danger badge badge-danger">Declined</span>';
                }
                // wallet type
                if ($value->receiver_wallet_type === 'trader') {
                    $receiver_type = '<span class="bg-light-success badge badge-light-success">' . ucwords($value->receiver_wallet_type) . '</span>';
                } else {
                    $receiver_type = '<span class="bg-light-warning badge badge-light-warning">' . str_replace('Ib', 'IB', ucwords($value->receiver_wallet_type)) . '</span>';
                }
                $data[$i]['sender_name'] = '<a href="#" data-id=' . $value->transfer_id . ' class="dt-description d-flex justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span><span>' . $value->name . '</span></a>';
                $data[$i]['sender_email'] = $value->email;
                $data[$i]['receiver_email'] = AllFunctionService::user_email($value->receiver_id);
                $data[$i]['receiver_type'] = $receiver_type;
                $data[$i]['status'] = $status;
                $data[$i]['request_date'] = date('d F y, h:i A', strtotime($value->created_at));
                $data[$i]['amount'] = '$' . $value->amount;
                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total_amount' => round($total_amount,2),
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'total_amount' => 0,
                'data' => [],
            ]);
        }
    }

    //fund transfer description;
    public function fundTransferDescription(Request $request, $id)
    {
        $sender_info = ExternalFundTransfers::where('sender_wallet_type', 'ib')
            ->where('external_fund_transfers.id', $request->id)
            ->join('users', 'external_fund_transfers.sender_id', '=', 'users.id')
            ->leftJoin('user_descriptions', 'users.id', 'user_descriptions.user_id')
            ->select(
                'users.name',
                'user_descriptions.address',
                'amount',
                'users.email',
                'users.phone',
                'external_fund_transfers.created_at',
                'external_fund_transfers.charge',
                'external_fund_transfers.status',
                'external_fund_transfers.approved_by'
            )->first();
            
        $innerTH1 = "";
        $innerTD1 = "";
        $approved_by = "";
        if ($sender_info->status === 'A' || $sender_info->status === 'D') {
            $approved_by = ($sender_info->status) == 'A' ? 'Approved By' : 'Declined By';
            $admin_info = User::select('name', 'email')->where('id', $sender_info->approved_by)->first();
            $admin_name = isset($admin_info->name) ? $admin_info->name : '---';
            $admin_email = isset($admin_info->email) ? $admin_info->email : '---';
            $admin_json_data = json_decode($sender_info->admin_log);
            $ip = isset($admin_json_data->ip) ? $admin_json_data->ip : $sender_info->ip_address;
            $winName = isset($admin_json_data->wname) ? $admin_json_data->wname : $sender_info->device_name;
            $action_date = isset($sender_info->approved_date) ? date('d M Y,h:i A', strtotime($sender_info->approved_date)) : '---';

            $innerTH1 .= '
                <th>Admin Name</th>
                <th>Admin Email</th>
                <th>IP</th>
                <th>Device</th>
                <th>Action Date</th>
            ';
            $innerTD1 .= '
                <td>' . $admin_name . '</td>
                <td>' . $admin_email . '</td>
                <td>' . $ip . '</td>
                <td>' . $winName . '</td>
                <td>' . $action_date . '</td>
            ';
        }
            
        $receiver_info = ExternalFundTransfers::where('sender_wallet_type', 'ib')
            ->where('external_fund_transfers.id', $request->id)
            ->join('users', 'external_fund_transfers.receiver_id', '=', 'users.id')
            ->leftJoin('user_descriptions', 'users.id', 'user_descriptions.user_id')
            ->select(
                'users.name',
                'user_descriptions.address',
                'users.phone',
                'users.email',
                'external_fund_transfers.status',
                'external_fund_transfers.approved_by'
            )->first();
            $innerTH1 = "";
        $innerTD1 = "";
        $approved_by = "";
        if ($receiver_info->status === 'A' || $receiver_info->status === 'D') {
            $approved_by = ($receiver_info->status) == 'A' ? 'Approved By' : 'Declined By';
            $admin_info = User::select('name', 'email')->where('id', $receiver_info->approved_by)->first();
            $admin_name = isset($admin_info->name) ? $admin_info->name : '---';
            $admin_email = isset($admin_info->email) ? $admin_info->email : '---';
            $admin_json_data = json_decode($receiver_info->admin_log);
            $ip = isset($admin_json_data->ip) ? $admin_json_data->ip : $receiver_info->ip_address;
            $winName = isset($admin_json_data->wname) ? $admin_json_data->wname : $receiver_info->device_name;
            $action_date = isset($receiver_info->approved_date) ? date('d M Y,h:i A', strtotime($receiver_info->approved_date)) : '---';

            $innerTH1 .= '
                <th>Admin Name</th>
                <th>Admin Email</th>
                <th>IP</th>
                <th>Device</th>
                <th>Action Date</th>
            ';
            $innerTD1 .= '
                <td>' . $admin_name . '</td>
                <td>' . $admin_email . '</td>
                <td>' . $ip . '</td>
                <td>' . $winName . '</td>
                <td>' . $action_date . '</td>
            ';
        }
            
        $description = '<tr class="description" style="display:none;">
        <td colspan="8">
            <div class="details-section-dark border-start-3 border-start-primary p-2 bg-light-secondary">
                <div class="row">
                    <div class="col-lg-6">
                        <table class="table table-responsive tbl-balance sender-reciever-tbl">
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;"></th>
                                <th>Sender </th>
                                <th>Receiver</th>
                            </tr>
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;">Name</th>
                                <td>' . $sender_info->name . '</td>
                                <td>' . $receiver_info->name . '</td>
                            </tr>
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;">Address</th>
                                <td>' . $sender_info->address . '</td>
                                <td>' . $receiver_info->address . '</td>
                            </tr>
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;">Phone</th>
                                <td>' . $sender_info->phone . '</td>
                                <td>' . $receiver_info->phone . '</td>
                            </tr>
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;">Email</th>
                                <td>' . $sender_info->email . '</td>
                                <td>' . $receiver_info->email . '</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table class="table table-responsive tbl-balance payment-table">
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;">Transaction Charge</th>
                                <td>&dollar;' . $sender_info->charge . '</td>
                            </tr>
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;">Payment Due</th>
                                <td>' . date('d F y, h:i A', strtotime($sender_info->created_at)) . '</td>
                            </tr>
                            <tr>
                                <th style="border-left: 3px solid var(--custom-primary) !important;">Amount</th>
                                <td>&dollar;' . $sender_info->amount . '</td>
                            </tr>
                        </table>
                    </div>   
                     <div class="pt-2">
                    <span class="details-text">
                        ' . $approved_by . '
                    </span>
                    <table id="deposit-details' . $id . '" class="deposit-details table dt-inner-table-dark">
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
                </div>';
        $data = [
            'status' => true,
            'description' => $description
        ];
        return Response::json($data);
    }
}
