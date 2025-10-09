<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\CommissionStatus;
use App\Models\ComTrade;
use App\Models\IB;
use App\Models\Mt5Trade;
use App\Models\Trade;
use App\Services\AllFunctionService;
use App\Services\commission\AllCommissionService;
use App\Services\IbService;
use App\Services\systems\PlatformService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TradeCommissionStatusController extends Controller
{
    private $prefix;
    public function __construct()
    {
        $this->middleware(["role:trade commission status"]);
        $this->middleware(["role:manage trade"]);
        $this->prefix = DB::getTablePrefix();
    }
    // basic view
    public function commission_status(Request $request)
    {
        $groups = ClientGroup::all();
        return view('admins.manage-trade.trade-commission-status', ['groups' => $groups]);
    }
    // commission status datatable-------------------
    public function commission_status_dt(Request $request)
    {
        try {
            if (strtolower(PlatformService::get_platform()) === 'mt4') {
                // mt4 trades
                // redirect to mt4 report function
                return $this->commission_status_dt_mt4($request);
            }
            // mt5 default function
            // -----------------------------------------
            $columns = [
                'ticket',
                'login',
                'ib',
                'trader',
                'created_at',
                'created_at',
                'open_time',
                'close_time',
                'created_at',
                'status'
            ];
            $orderby = $columns[$request->order[0]['column']];
            $result = CommissionStatus::with(['trades', 'user', 'directIb', 'account']);
            $result_closed = Mt5Trade::whereDate('CLOSE_TIME', '!=', '1970-01-01 00:00:00');
            $result_total = Mt5Trade::select('id');

            // Filter by open time / close time
            if ($request->open_close_time != "") {
                if ($request->open_close_time === "open_time") {
                    $result = $result->where('CLOSE_TIME', '=', '1970-01-01 00:00:00');
                    $result_total = $result_total->where('CLOSE_TIME', '=', '1970-01-01 00:00:00');
                    $result_closed = $result_closed->where('CLOSE_TIME', '=', '1970-01-01 00:00:00');
                } elseif ($request->open_close_time === "close_time") {
                    $result = $result->where('CLOSE_TIME', '!=', '1970-01-01 00:00:00');
                    $result_total = $result_total->where('CLOSE_TIME', '!=', '1970-01-01 00:00:00');
                    $result_closed = $result_closed->where('CLOSE_TIME', '!=', '1970-01-01 00:00:00');
                }

                // Apply date filters if date_from or date_to is provided
                if (!empty($request->value_from_start_date)) {
                    $formatted_start = Carbon::createFromFormat('Y-m-d', $request->value_from_start_date)->startOfDay()->format('Y-m-d');
                    $result = $result->where('CLOSE_TIME', '>=', $formatted_start);
                }
                if (!empty($request->value_from_end_date)) {
                    $formatted_end = Carbon::createFromFormat('Y-m-d', $request->value_from_end_date)->startOfDay()->format('Y-m-d');
                    $result = $result->where('CLOSE_TIME', '<=', $formatted_end);
                }
            } else {
                $result = $result->where('CLOSE_TIME', '!=', '1970-01-01 00:00:00');

                if (!empty($request->value_from_start_date)) {
                    $formatted_start = Carbon::createFromFormat('Y-m-d', $request->value_from_start_date)->startOfDay()->format('Y-m-d');
                    $result = $result->where('CLOSE_TIME', '>=', $formatted_start);
                    $result_total = $result_total->where('CLOSE_TIME', $formatted_start);
                    $result_closed = $result_closed->where('CLOSE_TIME', $formatted_start);
                }
                if (!empty($request->value_from_end_date)) {
                    $formatted_end = Carbon::createFromFormat('Y-m-d', $request->value_from_end_date)->startOfDay()->format('Y-m-d');
                    $result = $result->where('CLOSE_TIME', '<=', $formatted_end);
                    $result_total = $result_total->where('CLOSE_TIME', $formatted_end);
                    $result_closed = $result_closed->where('CLOSE_TIME', $formatted_end);
                }
            }
            // filter by trading account-------------------------
            if ($request->input('trading_account') != "") {
                $result = $result->where('login', $request->input('trading_account'));
                // counter
                $result_total = $result_total->where('LOGIN', $request->input('trading_account'));
                $result_closed = $result_closed->where('LOGIN', $request->input('trading_account'));
            }
            // filter by trader number/ ticket / order
            if ($request->filled('trade_number')) {
                $result = $result->where('ticket', $request->input('trade_number'));
                $result_total = $result_total->where('TICKET', $request->input('trade_number'));
                $result_closed = $result_closed->where('TICKET', $request->input('trade_number'));
            }
            // filter by ib email-------------
            if ($request->filled('ib_email')) {
                $result = $result->whereHas('directIb', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->input('ib_email'))
                        ->orWhere('email', 'LIKE', '%' . $request->input('ib_email'))
                        ->orWhere('phone', 'LIKE', '%' . $request->input('ib_email'));
                });
                // filter counter data
                $result_total = $result_total->whereHas('account.user.parentIb', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->input('ib_email'))
                        ->orWhere('email', 'LIKE', '%' . $request->input('ib_email'))
                        ->orWhere('phone', 'LIKE', '%' . $request->input('ib_email'));
                });
                $result_closed = $result_closed->whereHas('account.user.parentIb', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->input('ib_email'))
                        ->orWhere('email', 'LIKE', '%' . $request->input('ib_email'))
                        ->orWhere('phone', 'LIKE', '%' . $request->input('ib_email'));
                });
            }
            // filter by trader email
            if ($request->filled('trader_email')) {
                $result = $result->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->input('trader_email'))
                        ->orWhere('email', 'LIKE', '%' . $request->input('trader_email'))
                        ->orWhere('phone', 'LIKE', '%' . $request->input('trader_email'));
                });
                // filter counter data
                $result_total = $result_total->whereHas('account.user', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->input('trader_email'))
                        ->orWhere('email', 'LIKE', '%' . $request->input('trader_email'))
                        ->orWhere('phone', 'LIKE', '%' . $request->input('trader_email'));
                });
                $result_closed = $result_closed->whereHas('account.user', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->input('trader_email'))
                        ->orWhere('email', 'LIKE', '%' . $request->input('trader_email'))
                        ->orWhere('phone', 'LIKE', '%' . $request->input('trader_email'));
                });
            }
            // filter by group----------------
            if ($request->filled('client_group')) {
                $result = $result->whereHas('account', function ($query) use ($request) {
                    $query->where('group_id', $request->input('client_group'));
                });
                // counter
                $result_closed = $result_closed->whereHas('account', function ($query) use ($request) {
                    $query->where('group_id', $request->input('client_group'));
                });
                $result_total = $result_total->whereHas('account', function ($query) use ($request) {
                    $query->where('group_id', $request->input('client_group'));
                });
            }
            // filter by status----------
            if ($request->filled('status')) {
                $result = $result->where('status', $request->input('status'));
            }
            // datatable return-----------------------------------
            $count = $result->count(); // <------count total rows
            $total_closed = $result_closed->count();
            $total_trade = $result_total->count();
            $total_volume = $result_total->sum('VOLUME');
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $total_profit = $result_total->sum('PROFIT');
            // return $result;
            $data = array();
            foreach ($result as $value) {
                $trader_email = $value->user->email ?? '---';
                $ib_email = $value->directIb->email ?? '---';
                if (strtolower($value->account->platform) === 'edgetrader') {
                    $symbol = $value->trades->symbol ?? '---';
                    $volume = $value->trades->volume ?? '---';
                    $volume = is_numeric($volume) ? number_format(($volume / 100), 2) : '---';
                    $profit = $value->trades->profit ?? '---';
                    $profit = is_numeric($profit) ? number_format($profit, 2) : '---';
                } else {
                    $symbol = $value->trades->SYMBOL ?? '---';
                    $volume = $value->trades->VOLUME ?? '---';
                    $volume = is_numeric($volume) ? number_format(($volume / 100), 2) : '---';
                    $profit = $value->trades->PROFIT ?? '---';
                    $profit = is_numeric($profit) ? number_format($profit, 2) : '---';
                }

                $com_status = str_replace('_', ' ', $value->status);

                $data[] = [
                    "trade"         => $value->ticket,
                    "login"         => $value->login,
                    "trader_email"  => $trader_email,
                    "ib_email"      => $ib_email,
                    "symbol"        => $symbol,

                    "open_time"     => date('d M Y h:i:s A', strtotime($value->open_time)),
                    "close_time"    => date('d M Y h:i:s A', strtotime($value->close_time)),
                    "volume"        => $volume,
                    "profit"        => $profit,
                    "commission"    => ucwords($com_status),
                ];
            }
            // return Response::json([
            //     'draw' => $request->draw,
            //     'recordsTotal' => $count,
            //     'recordsFiltered' => $count,
            //     'data' => $data,
            //     'total' => [round(($total_volume / 100), 2), $count, $total_closed],
            // ]);
            $volume = round(($total_volume / 100), 2);
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
                'total' => [$total_trade, $total_closed, $volume, $volume, $total_profit],
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'total' => [],
                'errors' => $th->getMessage()
            ]);
        }
    }
}
