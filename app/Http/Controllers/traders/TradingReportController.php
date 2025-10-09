<?php

namespace App\Http\Controllers\Traders;

use App\Http\Controllers\Controller;
use App\Models\Mt5Trade;
use App\Models\Symbol;
use App\Models\Traders\Trade;
use App\Services\AllFunctionService;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response as FacadesResponse;

class TradingReportController extends Controller
{
    private $prefix;
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('trading_report', 'trader'));
        $this->middleware(AllFunctionService::access('reports', 'trader'));
        $this->prefix = DB::getTablePrefix();
    }
    public function tradingReport(Request $request)
    {
        $op = $request->input('op');
        if ($op == "data_table") {
            return $this->tradingReportDT($request);
        }
        $symbols = [];
        try {
            if (get_platform() == "mt4") {
                $symbols = DB::connection('alternate')->table('MT4_TRADES')->select('SYMBOL')->distinct()->get();
            } else {
                $symbols = DB::table('mt5_trades')->select('SYMBOL')->distinct()->get();
            }
        } catch (\Throwable $th) {
            //throw $th;
            $symbol = [];
        }

        return view("traders.reports.user_trading_report", ['symbols' => $symbols]);
    }

    public function tradingReportDT($request)
    {
        try {
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $_GET['order'][0]["column"];
            $orderDir = $_GET["order"][0]["dir"];

            $columns = ['TICKET', 'LOGIN', 'OPEN_TIME', 'CLOSE_TIME', 'SYMBOL', 'OPEN_PRICE', 'CLOSE_PRICE', 'PROFIT', 'VOLUME'];
            $orderby = $columns[$order];
            $result = "";
            if (get_platform() == "mt4") {
                $result = DB::connection('alternate')->table('MT4_TRADES')
                    ->join($this->prefix . 'trading_accounts', 'MT4_TRADES.LOGIN', $this->prefix . 'trading_accounts.account_number')
                    ->where($this->prefix . 'trading_accounts.user_id', auth()->user()->id);
            } else {
                $result = Mt5Trade::Join('trading_accounts', 'mt5_trades.LOGIN', 'trading_accounts.account_number')
                    ->where('trading_accounts.account_status', 1)
                    ->where('trading_accounts.user_id', auth()->user()->id);
            }

            $total_profit = $result->sum('PROFIT');
            $total_volume = $result->sum('VOLUME');

            $symbol = $request->input('symbol');
            $ticket = $request->input('ticket');
            $trade_account = $request->input('trade_account');
            $from = $request->input('from');
            $to = $request->input('to');
            $min = $request->input('min');

            $max = $request->input('max');

            // /*<-------filter search script start here------------->*/

            if ($symbol != "") {
                $result = $result->where('SYMBOL', '=', $symbol);
                $total_volume = $result->where('SYMBOL', '=', $symbol)->sum('VOLUME');
                $total_profit = $result->where('SYMBOL', '=', $symbol)->sum('VOLUME');
            }

            if ($ticket != "") {
                $result = $result->where('TICKET', '=', $ticket);
                $total_volume = $result->where('TICKET', '=', $ticket)->sum('VOLUME');
                $total_profit = $result->where('TICKET', '=', $ticket)->sum('PROFIT');
            }

            if ($trade_account != "") {
                $result = $result->where('LOGIN', '=', $trade_account);
                $total_volume = $result->where('LOGIN', '=', $trade_account)->sum('VOLUME');
                $total_profit = $result->where('LOGIN', '=', $trade_account)->sum('PROFIT');
            }

            if ($min != "") {
                $result = $result->where('PROFIT', '>=', $min);
                $total_volume = $result->where('PROFIT', '>=', $min)->sum('VOLUME');
                $total_profit = $result->where('PROFIT', '>=', $min)->sum('PROFIT');
            }

            if ($max != "") {
                $result = $result->where('PROFIT', '<=', $max);
                $total_volume = $result->where('PROFIT', '<=', $max)->sum('VOLUME');
                $total_profit = $result->where('PROFIT', '<=', $max)->sum('PROFIT');
            }

            if ($from != "") {
                $result = $result->whereDate('OPEN_TIME', '>=', $from);
                $total_volume = $result->whereDate('OPEN_TIME', '>=', $from)->sum('VOLUME');
                $total_profit = $result->whereDate('OPEN_TIME', '>=', $from)->sum('PROFIT');
            }

            if ($to != "") {
                $result = $result->whereDate('OPEN_TIME', '<=', $to);
                $total_volume = $result->whereDate('OPEN_TIME', '<=', $to)->sum('VOLUME');
                $total_profit = $result->whereDate('OPEN_TIME', '<=', $to)->sum('PROFIT');
            }

            // /*<-------filter search script end here------------->*/f      

            $count = $result->count();
            $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $trader) {

                if ($trader->PROFIT < 0) {
                    $profit = "-$" . abs($trader->PROFIT);
                } else {
                    $profit = "$" . abs($trader->PROFIT);
                }

                $data[$i]['ticket'] = $trader->TICKET;
                $data[$i]['account'] = $trader->LOGIN;
                $data[$i]['open_time'] = date('d M Y h:i:s A', strtotime($trader->OPEN_TIME));
                $data[$i]['close_time'] = date('d M Y h:i:s A', strtotime($trader->CLOSE_TIME));
                $data[$i]['symbol'] = $trader->SYMBOL;
                $data[$i]['open_price'] = '$ ' . $trader->OPEN_PRICE;
                $data[$i]['close_price'] = '$ ' . $trader->CLOSE_PRICE;
                $data[$i]['profit'] = $profit;
                $data[$i]['volume'] = round(($trader->VOLUME / 100), 2);
                $i++;
            }

            $res['draw'] = $draw;
            $res['recordsTotal'] = $count;
            $res['recordsFiltered'] = $count;
            $res['data'] = $data;

            if ($total_profit < 0) {
                $total_profit = "-$" . abs(round($total_profit, 2));
            } else {
                $total_profit = "$" . abs(round($total_profit, 2));
            }

            $total = [
                $total_profit,
                round(($total_volume / 100), 2)
            ];
            $res['total'] = $total;
            return json_encode($res);
        } catch (\Throwable $th) {
            //throw $th;
            return FacadesResponse::json([
                'draw'=>$request->draw,
                'recordsTotal'=>0,
                'recordsFiltered',
                'data'=>[],
                'total'=>0
            ]);
        }
    }
}
