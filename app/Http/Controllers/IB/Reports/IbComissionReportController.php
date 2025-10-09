<?php

namespace App\Http\Controllers\IB\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\ClientGroup;
use App\Models\IB;
use App\Models\IbGroup;
use App\Models\IbIncome;
use App\Models\Mt5Trade;
use App\Models\Symbol;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\DataTableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IbComissionReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('trade_commission', 'ib'));
        $this->middleware(AllFunctionService::access('reports', 'ib'));
        $this->middleware('is_ib');
    }
    public function ibComissionReports(Request $request)
    {
        if ($request->action == 'table') {
            return $this->ibComissionReportDT($request);
        }

        $ib_groups = IbGroup::all();
        $symbols = Mt5Trade::select('SYMBOL')->distinct()->get();
        return view('ibs.reports.ib_comission_report', compact('ib_groups', 'symbols'));
    }

    public function ibComissionReportDT(Request $request)
    {
        try {
            // Set default pagination values
            $start = $request->start ?? 0;
            $length = $request->length ?? 10; // Default to 10 rows per page
    
            $query = IbIncome::with(['trade', 'ibUser.ibGroup'])
                ->select(
                    'order_num',
                    'trader_id',
                    'ib_id',
                    'trading_account',
                    'amount',
                    'volume',
                    'com_level'
                )
                ->where('ib_id', auth()->user()->id); // ✅ Filter by logged-in IB
    
            // Apply filters dynamically
            $query->when($request->ibg, fn($q, $ibg) => $q->whereHas('ibUser', fn($q) => $q->where('ib_group_id', $ibg)));
            $query->when($request->trading_account, fn($q, $account) => $q->where('trading_account', $account));
            $query->when($request->symbol, fn($q, $symbol) => $q->whereHas('trade', fn($q) => $q->where('SYMBOL', $symbol)));
            $query->when($request->min, fn($q, $min) => $q->where('amount', '>=', $min));
            $query->when($request->max, fn($q, $max) => $q->where('amount', '<=', $max));
    
            if ($request->from && $request->to) {
                $query->whereBetween('trade.OPEN_TIME', [
                    Carbon::parse($request->from)->format('Y-m-d'),
                    Carbon::parse($request->to)->format('Y-m-d')
                ]);
            }
    
            // Clone query before counting
            $filteredQuery = clone $query;
            $count = $filteredQuery->count(); // ✅ Get total filtered records
            $totalAmount = $filteredQuery->sum('amount');
            // **Use skip() & take() for proper pagination with DataTables**
            $results = $query->orderBy('id', 'DESC')
                ->skip($start)
                ->take($length)
                ->get(); // ✅ Fetch only paginated data
    
            $data = $results->map(function ($row) {
                return [
                    "id" => $row->id,
                    "order_num" => $row->order_num,
                    "trading_account" => $row->trading_account,
                    "ib_group" => $row->ibUser->ibGroup->group_name ?? 'N/A',
                    "open_time" => Carbon::parse($row->trade->OPEN_TIME)->format('d-m-Y h:i:s'),
                    "close_time" => Carbon::parse($row->trade->CLOSE_TIME)->format('d-m-Y h:i:s'),
                    "cmd" => ($row->trade->cmd == 0) ? 'BUY' : 'SELL',
                    "symbol" => $row->trade->SYMBOL,
                    "com_level" => $row->com_level ?? 'N/A',
                    "volume" => round(($row->volume / 100), 2),
                    "amount" => "$" . $row->amount
                ];
            });
    
            return Response::json([
                'draw' => intval($request->draw), // Keep track of DataTables request
                'recordsTotal' => $count, // ✅ Total records after filtering
                'recordsFiltered' => $count, // ✅ Matches filtered count
                'total' => ["$".$totalAmount],
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return Response::json(['data' => [], 'recordsTotal' => 0, 'recordsFiltered' => 0, 'total' => [0]]);
        }
    }



    //  public function ibComissionReportDT(Request $request)
    // {

    //     try {
    //         // $result = IbIncome::select(
    //         //     'order_num',
    //         //     'ib_incomes.trader_id as com_trader_id',
    //         //     'ib_incomes.ib_id as com_ib_id',
    //         //     'ib_incomes.trading_account as com_trading_account',
    //         //     'amount',
    //         //     'mt5_trades.SYMBOL as com_symbol',
    //         //     'com_level',
    //         //     'ib_incomes.volume as com_volume',
    //         //     'mt5_trades.OPEN_TIME',
    //         //     'mt5_trades.CLOSE_TIME'
    //         // )->leftJoin('mt5_trades', 'ib_incomes.order_num', 'mt5_trades.TICKET')
    //         //     ->join('users', 'ib_incomes.ib_id', 'users.id')
    //         //     ->where('ib_incomes.ib_id', auth()->user()->id);
    //         $result = IbIncome::select(
    //             'order_num',
    //             'ib_incomes.trader_id as com_trader_id',
    //             'ib_incomes.ib_id as com_ib_id',
    //             'ib_incomes.trading_account as com_trading_account',
    //             'amount',
    //             'mt5_trades.SYMBOL as com_symbol',
    //             'com_level',
    //             'ib_incomes.volume as com_volume',
    //             'mt5_trades.OPEN_TIME',
    //             'mt5_trades.CLOSE_TIME'
    //         )->leftJoin('mt5_trades', 'ib_incomes.order_num', 'mt5_trades.TICKET')
    //             ->join('users', 'ib_incomes.ib_id', 'users.id')
    //             ->where('ib_incomes.ib_id', auth()->user()->id);

    //         // impliment filter conditions
    //         // filter by ib level
    //         if ($request->level == 'mdt') {
    //             $result = $result->where('ib_incomes.ib_id', auth()->user()->id);
    //         } else if ($request->level == 'msib') {
    //             $subibs = AllFunctionService::my_sub_ib_id(auth()->user()->id);
    //             // my sub ib traders
    //             $result = $result->whereIn('ib_incomes.ib_id', $subibs);
    //         } else {
    //             $subibs = AllFunctionService::my_sub_ib_id(auth()->user()->id);
    //             array_push($subibs, auth()->user()->id);
    //             $result = $result->whereIn('ib_incomes.ib_id', $subibs);
    //         }

    //         if ($request->ibg != '') {
    //             $result = $result->where('users.ib_group_id', $request->ibg);
    //         }
    //         if ($request->trading_account != "") {
    //             $result = $result->where('ib_incomes.trading_account', '=', $request->trading_account);
    //         }
    //         if ($request->order_number != "") {
    //             $result = $result->where('ib_incomes.order_num', '=', $request->order_number);
    //         }

    //         if ($request->symbol != '') {
    //             $result = $result->where('mt5_trades.SYMBOL', '=', $request->symbol);
    //         }
    //         if ($request->min != "") {
    //             $result = $result->where("ib_incomes.amount", '>=', $request->min);
    //         }
    //         if ($request->max != "") {
    //             $result = $result->where("ib_incomes.amount", '<=', $request->max);
    //         }
    //         if ($request->from != "") {
    //             $result = $result->whereDate('mt5_trades.OPEN_TIME', '>=', Carbon::parse($request->from)->format('Y-m-d'));
    //         }
    //         if ($request->to != "") {
    //             $result = $result->whereDate('mt5_trades.OPEN_TIME', '<=', Carbon::parse($request->to)->format('Y-m-d'));
    //         }

    //         $count = $result->count();

    //         $totalAmount = $result->sum('amount');
    //         $totalVolume = $result->sum('ib_incomes.volume');

    //         $result = $result->orderBy('ib_incomes.id', 'DESC')->skip($request->start)->take($request->length)->get();
    //         // return $result;
    //         // return $result;
    //         $data = [];
    //         $i = 0;

    //         foreach ($result as $row) {
    //             // get ib group
    //             $user = User::select('ib_group_id')->where('id', $row->com_ib_id)->first();
    //             $ib_groups = IbGroup::find($user->ib_group_id);

    //             $ib_mail = User::select('email')->where('id', $row->com_ib_id)->first();
    //             $trader_mail = User::select('email')->where('id', $row->com_trader_id)->first();

    //             $data[$i]["id"] = $row->id;
    //             $data[$i]["order_num"] = $row->order_num;
    //             $data[$i]["trading_account"] = $row->com_trading_account;
    //             $data[$i]["ib_group"] = $ib_groups->group_name;
    //             $data[$i]["open_time"] = '<span class="text-truncate">' . Carbon::parse($row->OPEN_TIME)->format('d-m-Y h:i:s') . '</span>';
    //             $data[$i]["close_time"] = '<span class="text-truncate">' . Carbon::parse($row->CLOSE_TIME)->format('d-m-Y h:i:s') . '</span>';
    //             $data[$i]["cmd"] = ($row->cmd == 0) ? 'BUY' : 'SELL';
    //             $data[$i]["symbol"] = $row->com_symbol;
    //             $data[$i]["com_level"] = $row->com_level;
    //             $data[$i]["volume"] = round(($row->com_volume / 100), 2);
    //             $data[$i]["amount"] = "$" . $row->amount;
    //             $i++;
    //         }

    //         return Response::json([
    //             'draw' => $request->draw,
    //             'recordsTotal' => $count,
    //             'recordsFiltered' => $count,
    //             'totalAmount' => $totalAmount,
    //             'totalVolume' => round(($totalVolume / 100), 2),
    //             'data' => $data
    //         ]);
    //     } catch (\Throwable $th) {
    //         throw $th;

    //         return Response::json([
    //             'draw' => $request->draw,
    //             'recordsTotal' => 0,
    //             'recordsFiltered' => 0,
    //             'totalAmount' => 0,
    //             'totalVolume' => 0,
    //             'data' => []
    //         ]);
    //     }
    // }
}
