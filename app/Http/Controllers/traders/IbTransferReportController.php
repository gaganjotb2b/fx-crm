<?php

namespace App\Http\Controllers\Traders;

use App\Http\Controllers\Controller;
use App\Models\IbTransfer;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IbTransferReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('ib_transfer_report', 'trader'));
        $this->middleware(AllFunctionService::access('reports', 'trader'));
    }
    public function ibReport(Request $request)
    {
        $op = $request->input('op');
        if ($op == "data_table") {
            return $this->ibReportDT($request);
        }
        return view("traders.reports.user_ib_transfer_report");
    }

    public function ibReportDT($request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $_GET['order'][0]["column"];
        $orderDir = $_GET["order"][0]["dir"];

        $columns = ['name', 'email', 'amount', 'charge', 'status', 'created_at', 'amount'];
        $orderby = $columns[$order];
        $result = IbTransfer::select(
            'ib_transfers.trader_id',
            'ib_transfers.ib_id',
            'users.name',
            'users.email',
            'ib_transfers.status',
            'ib_transfers.amount',
            'ib_transfers.charge',
            'ib_transfers.created_at'
        )
            ->where('ib_transfers.trader_id', auth()->user()->id)
            ->join('users', 'ib_transfers.ib_id', '=', 'users.id');

        $total_amount = $result->sum('amount');

        // /*<-------filter search script start here------------->*/

        if ($request->status != "") {
            $result = $result->where('status', '=', $request->status);
        }

        if ($request->min != "") {
            $result = $result->where('amount', '>=', $request->min);
        }

        if ($request->max != "") {
            $result = $result->where('amount', '<=', $request->max);
        }

        if ($request->from != "") {
            $result = $result->whereDate('ib_transfers.created_at', '>=', $request->from);
        }

        if ($request->to != "") {
            $result = $result->whereDate('ib_transfers.created_at', '<=', $request->to);
        }

        // /*<-------filter search script end here------------->*/f      

        $count = $result->count();
        $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
        $data = array();
        $i = 0;

        foreach ($result as $ib) {
            if ($ib->status == 'A') {
                $status = '<span class="bg-light-success badge badge-success">Approved</span>';
            }
            if ($ib->status == 'P') {
                $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
            }
            if ($ib->status == 'D') {
                $status = '<span class="bg-light-danger badge badge-danger">Decline</span>';
            }

            $data[] = [
                'ib_name' => $ib->name,
                'ib_email' => $ib->email,
                'type' => "Wallet To Wallet",
                'status' => $status,
                'charge' => '$ ' . $ib->charge,
                'amount' => '$ ' . $ib->amount,
                'date' => date('d F y, h:i A', strtotime($ib->created_at)),
            ];
            $data[$i]['ib_name'] = $ib->name;
            $data[$i]['ib_email'] = $ib->email;
            $data[$i]['type'] = "Wallet To Wallet";
            $data[$i]['status'] = $status;
            $data[$i]['charge'] = '$ ' . $ib->charge;
            $data[$i]['amount'] = '$ ' . $ib->amount;
            $data[$i]['date'] = date('d F y, h:i A', strtotime($ib->created_at));
            $i++;
        }
        
        return Response::json([
            'draw' => $request->$draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data,
            'total' => round($total_amount, 2),
        ]);
    }
}
