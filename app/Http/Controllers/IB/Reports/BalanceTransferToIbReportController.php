<?php

namespace App\Http\Controllers\IB\Reports;

use App\Http\Controllers\Controller;
use App\Models\ExternalFundTransfers;
use App\Services\AllFunctionService;
use App\Services\DataTableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class BalanceTransferToIbReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('trader_to_ib_balance_transfer', 'ib'));
        $this->middleware(AllFunctionService::access('reports', 'ib'));
        $this->middleware('is_ib');
    }
    public function balanceTransferReports(Request $request)
    {
        if ($request->action == 'table') {
            return $this->balanceTransferReportDT($request);
        }
        return view('ibs.reports.balance_transfer_trader_to_ib_report');
    }

    public function balanceTransferReportDT(Request $request)
    {
        try {
            $dts = new DataTableService($request);
            $columns = $dts->get_columns();
            $result = ExternalFundTransfers::select(
                'external_fund_transfers.*',
                'users.name',
                'users.email'
            )
                ->join('users', 'external_fund_transfers.sender_id', '=', 'users.id')
                ->where('external_fund_transfers.type', 'trader_to_ib')
                ->where('receiver_id', Auth::id());

            //Search if columns field has search data
            $result = $result->where(function ($q) use ($dts, $columns) {
                if ($dts->search) {
                    foreach ($columns as $col) {
                        if ($col['data'] != 'id' && !empty($col['data'])) {
                            $tf = ($col['data'] == 'created_at' || $col['data'] == 'type') ? "external_fund_transfers.{$col['data']}" : $col['data'];
                            $st = $dts->search;
                            $q->orWhere($tf, 'LIKE', '%' . $st . '%');
                        }
                    }
                }
            });

            // impliment filter conditions
            if ($request->email != "") {
                $result = $result->where('email', '=', $request->email);
            }
            if ($request->status != "") {
                $result = $result->where("status", '=', $request->status);
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
            $result = $result->orderBy($dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();

            $data = [];
            $i = 0;
            foreach ($result as $row) {
                $status = $row->status;
                if ($status == 'A') $status = 'Approved';
                else if ($status == 'P') $status = 'Pending';
                else $status = 'Declined';
                $data[$i]["id"] = $row->id;
                $data[$i]["name"] = $row->name;
                $data[$i]["email"] = $row->email;
                $data[$i]["type"] = "<span class='badge badge-info badge-sm'>receive</span>";
                $data[$i]["status"] = $status;
                $data[$i]["created_at"] = Carbon::parse($row->created_at)->format('d-m-Y');
                $data[$i]["charge"] = '$' . $row->charge;
                $data[$i]["amount"] = '$' . $row->amount;
                $i++;
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'totalAmount' => $totalAmount,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'totalAmount' => 0,
                'data' => []
            ]);
        }
    }
}
