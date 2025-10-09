<?php

namespace App\Http\Controllers\IB\Reports;

use App\Http\Controllers\Controller;
use App\Models\ExternalFundTransfers;
use App\Services\AllFunctionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IbBalanceRecivedController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('ib_balance_receive', 'ib'));
        $this->middleware(AllFunctionService::access('reports', 'ib'));
        $this->middleware('is_ib');
    }
    public function BalanceRecivingReports(Request $request)
    {
        if ($request->action === 'table') {
            return $this->balanceTransferReportDT($request);
        }
        return view('ibs.reports.balance_recived_report');
    }

    public function balanceTransferReportDT(Request $request)
    {
        try {
            // return $request->all();
            $columns = [
                'users.name',
                'users.email',
                'external_fund_transfers.status',
                'external_fund_transfers.receiver_wallet_type',
                'external_fund_transfers.created_at',
                'external_fund_transfers.charge',
                'external_fund_transfers.amount'
            ];
            $orderby = $columns[$request->order[0]['column']];

            $result = ExternalFundTransfers::select(
                'external_fund_transfers.*',
                'users.name',
                'users.email'
            )->where('receiver_id', auth()->user()->id)
                ->where('receiver_wallet_type', 'ib')
                ->join('users', 'external_fund_transfers.sender_id', '=', 'users.id')
                ->where(function ($query) {
                    $query->where('external_fund_transfers.type', 'trader_to_ib')
                        ->orWhere('external_fund_transfers.type', 'ib_to_ib');
                });
            // return $result->get();
            //Search if columns field has search data
            if ($request->search != "") {
                $search = $request->search;
                $result = $result->where(function ($query) use ($search) {
                    $query->orWhere('users.name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('external_fund_transfers.type', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('external_fund_transfers.status', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('external_fund_transfers.created_at', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('external_fund_transfers.charge', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('external_fund_transfers.amount', 'LIKE', '%' . $search['value'] . '%');
                });
            }
            // impliment filter conditions
            if ($request->sender_info) {
                $email = $request->sender_info;
                $result = $result->where(function ($query) use ($email) {
                    $query->where('name', 'LIKE', '%' . $email . '%')
                        ->orWhere('email', 'LIKE', '%' . $email . '%');
                });
            }
            if ($request->status != "") {
                $result = $result->where("external_fund_transfers.status", $request->status);
            }
            if ($request->transaction_type != "") {
                $result = $result->where("external_fund_transfers.type", $request->transaction_type);
            }
            if ($request->min != "") {
                $result = $result->where("amount", '>=', $request->min);
            }
            if ($request->max != "") {
                $result = $result->where("amount", '<=', $request->max);
            }
            if ($request->from != "") {
                $result = $result->whereDate('external_fund_transfers.created_at', '>=', Carbon::parse($request->from)->format('Y-m-d'));
            }
            if ($request->to != "") {
                $result = $result->whereDate('external_fund_transfers.created_at', '<=', Carbon::parse($request->to)->format('Y-m-d'));
            }

            $count = $result->count();
            $totalAmount = $result->sum('amount');
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = [];
            $i = 0;
            foreach ($result as $value) {
                $status = $value->status;
                if ($status == 'A') {
                    $status = '<span class="badge badge-success badge-sm">Approved</span>';
                } else if ($status == 'P') {
                    $status = '<span class="badge badge-warning badge-sm">Pending</span>';
                } else {
                    $status = '<span class="badge badge-danger badge-sm">Declined</span>';
                }
                $sender_type = '';
                if ($value->sender_wallet_type === 'ib') {
                    $sender_type = '<span class="badge badge-warning badge-sm">IB</span>';
                } else {
                    $sender_type = '<span class="badge badge-success badge-sm">Trader</span>';
                }
                $data[$i]["name"] = ucwords($value->name);
                $data[$i]["email"] = $value->email;
                $data[$i]["status"] = $status;
                $data[$i]["sender_type"] = $sender_type;
                $data[$i]["created_at"] = Carbon::parse($value->created_at)->format('d-m-Y');
                $data[$i]["charge"] = '$' . $value->charge;
                $data[$i]["amount"] = '$' . $value->amount;
                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total' => ["$".$totalAmount],
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'total' => [0],
            ]);
        }
    }
}
