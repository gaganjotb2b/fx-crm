<?php

namespace App\Http\Controllers\Traders;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;

class InternalTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('internal_transfer_report', 'trader'));
        $this->middleware(AllFunctionService::access('reports', 'trader'));
    }
    public function internalReport(Request $request)
    {
        $op = $request->input('op');
        if ($op == "data_table") {
            return $this->internalReportDT($request);
        }
        return view('traders.reports.user_internal_report');
    }

    public function internalReportDT($request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $_GET['order'][0]["column"];
        $orderDir = $_GET["order"][0]["dir"];

        $columns = [ 'internal_transfers.created_at','users.email', 'users.name','trading_accounts.account_number', 'trading_accounts.platform', 'internal_transfers.type', 'internal_transfers.status', 'amount'];
        $orderby = $columns[$order];
        $result = InternalTransfer::select(
            'internal_transfers.id',
            'internal_transfers.user_id',
            'internal_transfers.type',
            'internal_transfers.status',
            'internal_transfers.approved_date',
            'internal_transfers.created_at',
            'internal_transfers.amount',
            'users.name',
            'users.email',
            'trading_accounts.account_number',
            'client_groups.server'
        )
            ->join('users', 'internal_transfers.user_id', '=', 'users.id')
            ->leftJoin('trading_accounts', 'internal_transfers.account_id', '=', 'trading_accounts.id')
            ->leftJoin('client_groups', 'trading_accounts.group_id', '=', 'client_groups.id')
            ->where('users.id', auth()->user()->id);

        $total_amount = $result->sum('amount');

        /*<-------filter search script start here------------->*/

        if ($request->type != "") {
            $result = $result->where('internal_transfers.type', '=', $request->type);
        }

        if ($request->approved_status != "") {
            $result = $result->where('status', '=', $request->approved_status);
        }

        if ($request->from != "") {

            $result = $result->whereDate("approved_date", '>=', $request->from);
        }
        if ($request->to != "") {

            $result = $result->whereDate("approved_date", '<=', $request->to);
        }

        if ($request->min != "") {
            $result = $result->where("amount", '>=', $request->min);
        }
        if ($request->max != "") {
            $result = $result->where("amount", '<=', $request->max);
        }

        if ($request->account_number != "") {
            $result = $result->where('account_number', 'LIKE', '%' . $request->account_number . '%');
        }

        if ($request->info != "") {
            $result = $result->where('name', 'LIKE', '%' . $request->info . '%')->orwhere('email', 'LIKE', '%' . $request->info . '%');
        }

        $count = $result->count();
        $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
        $data = array();
        $i = 0;
        $amount=0;
        foreach ($result as $user) {

            if ($user->status == 'P') {
                $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
            } elseif ($user->status == 'A') {
                $status = '<span class="bg-light-success badge badge-success">Approved</span>';
            } elseif ($user->status == 'D') {
                $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
            }

            if ($user->type == "wta") {
                $trans_type = "Wallet To Account";
            }
            if ($user->type == "atw") {
                $trans_type = "Account To Wallet";
            }

            $data[$i]['name'] = $user->name;
            $data[$i]['email'] = $user->email;
            $data[$i]['account_number'] = $user->account_number;
            $data[$i]['platform'] = $user->server;
            $data[$i]['method'] = $trans_type;
            $data[$i]['date'] = date('d F y, h:i A', strtotime($user->created_at));
            $data[$i]['status'] = $status;
            $data[$i]['amount'] = '$' . $user->amount;
            $amount += $user->amount;
            $i++;
        }

        $res['draw'] = $draw;
        $res['recordsTotal'] = $count;
        $res['recordsFiltered'] = $count;
        $res['data'] = $data;
        $res['total_amount'] = round($amount, 2);

        return json_encode($res);
    }
}
