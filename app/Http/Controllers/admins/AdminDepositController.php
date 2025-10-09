<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\DepositSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;

class AdminDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:deposit request report"]);
        $this->middleware(["role:reports"]);
        // system module control
        $this->middleware(AllFunctionService::access('reports', 'admin'));
        $this->middleware(AllFunctionService::access('deposit_request', 'admin'));
    }
    public function deposit_report()
    {
        return view('admins.reports.deposit-report');
    }

    // deposit datatable ajax process
    public function deposit_dt_proccess(Request $request)
    {
        $transection_type = $request->transaction_type;
        $approved_status = $request->approved_status;
        $name = $request->name;
        $from = $request->input('from');
        $to = $request->input('to');
        $min = $request->input('min');
        $max = $request->input('max');


        $start = $request->input('start');
        $length = $request->input('length');
        $order = $_GET['order'][0]["column"];
        $orderDir = $_GET["order"][0]["dir"];

        $columns = ['name', 'email', 'account', 'transaction_type', 'approved_status', 'deposits.created_at'];
        $orderby = $columns[$order];

        $result = User::select()->join('deposits', 'users.id', '=', 'deposits.user_id');

        //Filter script

        if ($transection_type != "") {
            $result = $result->where('transaction_type', $transection_type);
        }
        if ($approved_status != "") {
            $result = $result->where('approved_status', $approved_status);
        }
        if ($name != "") {
            $result = $result->where('name', 'LIKE', '%' . $name . '%')->orwhere('email', 'LIKE', '%' . $name . '%');
        }

        if ($min != "") {
            $result = $result->where("amount", '>=', $min);
        }
        if ($max != "") {
            $result = $result->where("amount", '<=', $max);
        }

        if ($from != "") {
            $result = $result->whereDate("deposits.created_at", '>=', $from);
        }

        if ($to != "") {
            $result = $result->whereDate("deposits.created_at", '<=', $to);
        }



        $count = $result->count();
        $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
        $data = array();
        $i = 0;





        foreach ($result as $key => $value) {
            if ($value->approved_status == 'A') {
                $status = 'Approved';
            }
            if ($value->approved_status == 'P') {
                $status = 'Pending';
            }
            if ($value->approved_status == 'D') {
                $status = 'Declined';
            }

            // if(isset($value->account)){
            //  $trading_account = TradingAccount::select('account_number')->where('user_id', $value->user_id)->first();
            //  $accountNo = $trading_account->account_number;
            // }else{
            //     $accountNo  = '---';
            // }

            $data[$i]["name"]               = '<a href="#" data-id="' . $value->id . '" class="dt-description justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' . $value->name . '</span></a>';
            $data[$i]["email"]              = $value->email;
            // $data[$i]["account"]            = $accountNo;
            $data[$i]["transaction_type"]   = ucwords($value->transaction_type);
            $data[$i]["approved_status"]    = $status;
            $data[$i]["created_at"]         = date('d F y, h:i A', strtotime($value->created_at));
            $i++;
        }

        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $count, 'recordsFiltered' => $count);
        $output['data'] = $data;
        return Response::json($output);
    }

    // deposit datatable descriptions
    public function deposit_dt_description()
    {
        //     $description = '<tr class="description" style="display:none">
        //     <td colspan="12">
        //         <div class="details-section-dark border-start-3 border-start-primary p-2" style="display: flow-root;">
        //             <span class="details-text">
        //                 Details
        //             </span>
        //             <table class="datatable-inner table table-responsive dt-inner-table-dark">
        //                 <thead>
        //                     <tr>
        //                         <th>Amount Request</th>
        //                         <th>Invoice</th>
        //                         <th>Note</th>
        //                     </tr>
        //                 </thead>
        //             </table>
        //         </div>
        //     </td>
        // </tr>';
        //     $data = [
        //         'status' => true,
        //         'description' => $description
        //     ];
        //     return Response::json($data);


        $description = '<tr class="description" style="display:none">
            <td colspan="7">
                <div class="details-section-dark border-start-3 border-start-primary p-2 " style="display: flow-root;">
                    <span class="details-text">
                        Details
                    </span>
                    <table id="datatable-inner" class="datatable-inner table dt-inner-table-dark">
                        <thead>
                            <tr>
                                <th>Amount Request</th>
                                <th>Invoice</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </td>
            
        </tr>';
        $data = [
            'status' => true,
            'description' => $description
        ];
        return Response::json($data);
    }





    // deposit dtatable description, inner table
    public function deposit_dt_inner(Request $request, $id)
    {

        $start = $request->input('start');
        $length = $request->input('length');
        $order = $_GET['order'][0]["column"];
        $orderDir = $_GET["order"][0]["dir"];

        $columns = ['amount', 'invoice_id', 'note'];
        $orderby = $columns[$order];
        // $result = Deposit::select()->join('users', 'deposit.id', '=', 'deposits.user_id');
        $result = Deposit::select()->where('id', $id);


        $count = $result->count();
        $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
        $data = array();
        $i = 0;

        foreach ($result as $key => $value) {
            $data[$i]["amount_request"]     = '$ ' . $value->amount;
            $data[$i]["invoice"]            = $value->invoice_id;
            $data[$i]["note"]               = ($value->note != '') ? $value->note : '---';
            $i++;
        }

        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $count, 'recordsFiltered' => $count);
        $output['data'] = $data;
        return Response::json($output);
    }
}
