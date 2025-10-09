<?php

namespace App\Http\Controllers\Traders;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\TransactionSetting;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('deposit_report', 'trader'));
        $this->middleware(AllFunctionService::access('reports', 'trader'));
    }
    public function depositReport(Request $request)
    {
        $op = $request->input('op');
        if ($op == "data_table") {
            return $this->depositReportDT($request);
        }
        return view('traders.reports.user_deposit_report');
    }

    public function depositReportDT($request)
    {
        $userId = auth()->user()->id;
        $columns = ['deposits.transaction_type', 'cted', 'deposits.approved_date', 'deposits.approved_status', 'deposits.charge', 'deposits.amount'];

        $result = Deposit::select(
            'deposits.transaction_type',
            'deposits.charge',
            'deposits.approved_status',
            'deposits.created_at as cted',
            'deposits.approved_date',
            'deposits.amount'
        )
            ->join('users', 'deposits.user_id', '=', 'users.id')
            ->where('wallet_type', 'trader')
            ->where('users.id', $userId);

        $total_amount = $result->sum('deposits.amount');

        $type = $request->input('type');
        $approved_status = $request->input('approved_status');
        $from = $request->input('from');
        $to = $request->input('to');
        $min = $request->input('min');
        $max = $request->input('max');

        if ($type != "") {
            $result = $result->where('deposits.transaction_type', '=', $type);
            $total_amount = $result->where('deposits.transaction_type', '=', $type)->sum('deposits.amount');
        }
        if ($approved_status != "") {
            $result = $result->where('deposits.approved_status', '=', $approved_status);
            $total_amount = $result->where('deposits.approved_status', '=', $approved_status)->sum('deposits.amount');
        }

        if ($min != "") {
            $result = $result->where('deposits.amount', '>=', $min);
            $total_amount = $result->where('deposits.amount', '>=', $min)->sum('deposits.amount');
        }

        if ($max != "") {
            $result = $result->where('deposits.amount', '<=', $max);
            $total_amount = $result->where('deposits.amount', '<=', $max)->sum('deposits.amount');
        }
        if ($from != "") {
            $result = $result->whereDate('deposits.created_at', '>=', $from);
            $total_amount = $result->whereDate('deposits.created_at', '>=', $from)->sum('deposits.amount');
        }

        if ($to != "") {
            $result = $result->whereDate('deposits.created_at', '<=', $to);
            $total_amount = $result->whereDate('deposits.created_at', '<=', $to)->sum('deposits.amount');
        }
        $count = $result->count();
        $result = $result->orderby($columns[$request->order[0]["column"]], $request->order[0]["dir"])
            ->skip($request->start)->take($request->length)->get();
        $data = array();
        $i = 0;
        $amount = 0;
        foreach ($result as $value) {
            $approved_date = "";
            if ($value->approved_status == 'P') {
                $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
            } elseif ($value->approved_status == 'A') {
                $status = '<span class="bg-light-success badge badge-success">Approved</span>';
            } elseif ($value->approved_status == 'D') {
                $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
            }

            if ($value->approved_date != null) {
                $approved_date = date('d M y, h:i A', strtotime($value->approved_date));
            } else {
                $approved_date = '--';
            }

            $data[$i]['transaction_type'] = ucfirst($value->transaction_type);
            $data[$i]['request_date'] = date('d M Y, h:i:s', strtotime($value->cted));
            $data[$i]['approved_date'] = $approved_date;
            $data[$i]['status'] = $status;
            $data[$i]['charge'] = '$' . $value->charge;
            $data[$i]['amount'] = '$' . $value->amount;
            $amount += $value->amount;
            $i++;
        }
        $total = ["$" . round($amount, 2)];
        return Response::json([
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data,
            'total' => $total
        ]);
    }
}
