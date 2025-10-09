<?php

namespace App\Http\Controllers\Traders\tournaments;

use App\Http\Controllers\Controller;
use App\Models\Mt5Trade;
use App\Models\Symbol;
use App\Models\Traders\Trade;
use App\Services\AllFunctionService;
use App\Models\tournaments\TourGroup;
use App\Models\tournaments\TourSetting;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Carbon\Carbon;

class TradingAccountHistoryController extends Controller
{
    private $prefix;
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('trading_report', 'trader'));
        $this->middleware(AllFunctionService::access('reports', 'trader'));
        $this->prefix = DB::getTablePrefix();
    }
    public function tradingAccountHistory(Request $request)
    {
        $op = $request->input('op');
        if ($op == "data_table") {
            return $this->tradingAccountHistoryDT($request);
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

        return view("traders.tournaments.trading-account-history", ['symbols' => $symbols]);
    }

    public function tradingAccountHistoryDT($request)
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
                    // ->where('trading_accounts.user_id', auth()->user()->id);
                    ->where('trading_accounts.account_number', $request->account_number);
            }

            $total_profit = $result->sum('PROFIT');
            $total_volume = $result->sum('VOLUME');
            $tour_settings = TourSetting::select()->first();
            $tour_group = TourGroup::where('id', $request->group_id)->first();
            
            $from = Carbon::parse($tour_group->start_trading);
            $to = $from->copy()->addDays($tour_settings->group_trading_duration);
            $result = $result->whereBetween('OPEN_TIME', [$from, $to]);

            $total_volume = $result->sum('VOLUME');
            $total_profit = $result->sum('PROFIT');

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
            // throw $th;
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
