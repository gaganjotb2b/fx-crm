<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminGroup;
use App\Models\ManagerGroup;
use App\Models\ManagerUser;
use App\Models\StaffTransaction;
use App\Models\User;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;

class FinanceReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:fund management"]);
        $this->middleware(["role:finance report"]);
        // system module control
        $this->middleware(AllFunctionService::access('finance', 'admin'));
        $this->middleware(AllFunctionService::access('finance_reports', 'admin'));
    }
    //basic view-------------------------------------------------
    public function finance_report(Request $request)
    {
        // get manager and admin group
        $admin_group = AdminGroup::select()->get();
        $manager_group = ManagerGroup::select()->get();
        return view(
            'admins.finance.finance-report',
            [
                'admin_group' => $admin_group,
                'manager_group' => $manager_group
            ]
        );
    }
    // endP: basic view--------------------------------------
    // satart: finance report dt--------------------------------
    public function finance_report_dt(Request $request)
    {
        try {
            $columns = ['name', 'users.type', 'staff_transactions.type', 'amount', 'approved_status', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];
            // sql result
            // return $request->all();
            $result = StaffTransaction::select(
                'staff_transactions.id as table_id',
                'staff_transactions.approved_status',
                'users.name as admin_name',
                'staff_transactions.amount',
                'users.type as admin_type',
                'staff_transactions.created_at',
                'staff_transactions.user_id as source_id',
                'staff_transactions.wallet_type',
                'staff_transactions.type as transaction_type',
                'staff_transactions.id as st_tble_id'
            )->join('users', 'staff_transactions.staff_id', '=', 'users.id');
            // filter by current month----------------
            if ($request->month === 'this_month') {
                $result = $result->whereMonth('staff_transactions.created_at', Carbon::now()->month);
            }
            // filter by last month--------------------
            if ($request->month === 'last_month') {
                $result = $result->whereMonth(
                    'staff_transactions.created_at',
                    '=',
                    Carbon::now()->subMonth()->month
                );
            }
            // Filter by date-------------------
            // return $request->start_date;
            if ($request->start_date != "") {
                $from = Carbon::parse($request->start_date);
                $to = '';
                if ($request->end_date != "") {
                    $to  = Carbon::parse($request->end_date);
                }
                $result = $result->whereDate('staff_transactions.created_at', '<=', $to)->whereDate('staff_transactions.created_at', '>=', $from);
            }
            // FILTER:  by transaction for----------------
            if ($request->has('transaction_for') && $request->transaction_for != "") {
                $admin_group = explode('_', $request->transaction_for);
                // IF admin is an admin
                if ($admin_group[0] === 'admin') {
                    $group =  AdminGroup::find($admin_group[1]);
                    $result = $result->whereIn('users.id', $group->admin_id()->get());
                }
                // if admin is a manager
                if ($admin_group[0] === 'manager') {
                    $group = ManagerGroup::find($admin_group[1]);
                    $result = $result->whereIn('users.id', $group->manager_id()->get());
                }
            }
            //Filter by transaction type
            if($request->transaction_type != ""){
                $result = $result->where('staff_transactions.type', $request->transaction_type);
            }
            // FILTER: by admin email
            if ($request->email != "") {
                // return $request->email;
                $admin_email = $request->email;
                $result = $result->where('users.type',2)->where('users.email', $admin_email);
            }

            //Filter by manager name / email
            if ($request->manager_info != "") {
                // return $request->manager_info;
                $manager_id = User::select('id')->where('email', $request->manager_info)
                    ->orWhere('name', $request->manager_info)
                    ->first();
                if (isset($manager_id)) {
                    $user_id = ManagerUser::where('manager_id', $manager_id->id)->get()->pluck('user_id');
                    $result = $result->whereIn('users.id', $user_id);
                } else {
                    $result = $result->where('users.id', null);
                }
            }

            // end: filter option query--------------------------
            $count = $result->count(); // <------count total rows
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;
            foreach ($result as $key => $value) {
                $source = User::find($value->source_id);
                $source = (strtolower($source->type) == 'ib') ? 'IB' : $source->type;
                $status = '';
                if ($value->approved_status == 'A') {
                    $status = '<span class="badge badge-light-warning">Approved</span>'; // <------status badge
                } elseif ($value->approved_status == 'P') {
                    $status = '<span class="badge badge-light-success">Pending</span>'; // <------status badge
                } else {
                    $status = '<span class="badge badge-light-danger">Declined</span>'; //  <----Status badge
                }

                $buttons = '<a type="button" data-id="' . $value->st_tble_id . '" data-value="A" class="dropdown-item change_data_status" data-bs-toggle="modal" >
                <i data-feather="shield"></i>
                <span>Approved</span>
            </a>';

                $buttons  .= '<a type="button" data-id="' . $value->st_tble_id . '" data-value="D" class="dropdown-item change_data_status" data-bs-toggle="modal"   >
                <i data-feather="shield-off"></i>
                <span>Decline</span>
            </a>';
                $buttons  .= '<a type="button" data-id="' . $value->st_tble_id . '" data-value="P" class="dropdown-item change_data_status" data-bs-toggle="modal"   >
                <i data-feather="refresh-ccw"></i>
                <span>Pending</span>
            </a>';


                // tabl column
                // -------------------------------------
                $data[$i]['name']               = '<a href="#" data-id=' . $value->table_id . '  class="dt-description text-color justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' .  ucwords($value->admin_name) . '</span></a>';
                // $data[$i]["name"]               = ucwords($value->admin_name);
                $data[$i]["source"]             = ucwords($source);
                $data[$i]['transaction_type'] = '';
                switch ($value->transaction_type) {
                    case 'add':
                        $data[$i]['transaction_type'] = '<span class="bg-success badge badge-success">' . ucwords($value->transaction_type) . '</span>';
                        break;
                    case 'deduct':
                        $data[$i]['transaction_type'] = '<span class="bg-warning badge badge-warning">' . ucwords($value->transaction_type) . '</span>';
                        break;
                    default:
                        $data[$i]['transaction_type'] = '<span class="bg-success badge badge-success">' . ucwords($value->transaction_type) . '</span>';
                        break;
                }

                $data[$i]["amount"]             = "$" . $value->amount;
                $data[$i]["status"]             = '<a href="#" class="text-danger">' . $status . '</a>';
                $data[$i]["date"]               = date('d F y, h:i A', strtotime($value->created_at));
                $data[$i]["action"]             = '<td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                        ' . $buttons . '
                                                        </div>
                                                    </div>
                                                </td>';
                $i++;
            }

            return Response::json([
                'draw' => $request->draw, 
                'recordsTotal' => $count, 
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            // return Response::json([
            //     'draw' => $request->draw, 
            //     'recordsTotal' => 0, 
            //     'recordsFiltered' => 0,
            //     'data' => []
            // ]);
        }
    }
    // end: finance report dt--------------------------------


    // Start Finace change status operaction 
    public function changeStatus(Request $request)
    {
        $success = false;
        $result  =  StaffTransaction::where('id', $request->id)->update([
            'approved_status' => $request->sts
        ]);
        if ($result) {
            $success = true;
        }
        return Response::json([
            'success' => $success
        ]);
    }
    public function AddDeductLog(Request $request)
    {
        $staff = StaffTransaction::find($request->id);

        //===========================Admin Information condition=================================////
        $innerTH1 = "";
        $innerTD1 = "";
        $approved_by = "";
        // if ($staff->status === 1 || $staff->status === 2) {
        $approved_by = ($staff->type === 'add') ? "Added By:" : "Deduct By:";
        $admin_info = User::select('name', 'email')->where('id', $staff->staff_id)->first();
        $admin_name = isset($admin_info->name) ? $admin_info->name : '---';
        $admin_email = isset($admin_info->email) ? $admin_info->email : '---';
        $admin_json_data = json_decode($staff->admin_log);
        $ip = isset($admin_json_data->ip) ? $admin_json_data->ip : '---';
        $wname = isset($admin_json_data->wname) ? $admin_json_data->wname : '---';
        $action_date = isset($staff->approved_date) ? date('d M Y, h:i A', strtotime($staff->approved_date)) : '---';

        $innerTH1 .= '
                <th>ADMIN Name</th>
                <th>Admin Email</th>
                <th>IP</th>
                <th>Device</th>
                <th>Action Date</th>';
        $innerTD1 .= '
                <td>'.$admin_name.'</td>
                <td>'.$admin_email.'</td>
                <td>'.$ip.'</td>
                <td>'.$wname.'</td>
                <td>'.$action_date.'</td>';
        // }
        //===========================Admin Information condition End=================================////

        $description = '
        <tr class="description" style="display:none">
            <td colspan="8">
                <div class="details-section-dark border-start-3 border-start-primary p-2 " style="display: flow-root;">
                    <span class="details-text">
                          $approved_by
                    </span>
                    <table id="deposit-details' . $request->id . '" class="deposit-details table dt-inner-table-dark">
                        <thead>
                            <tr>
                             '.$innerTH1.' 
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                '.$innerTD1.' 
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>';

        $data = [
            'status' => true,
            'description' => $description,
        ];
        return Response::json($data);
    }
}
