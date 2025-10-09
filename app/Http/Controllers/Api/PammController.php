<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\Country;
use App\Models\Traders\PammSetting;
use App\Models\TradingAccount;
use App\Services\api\CrmApiService;
use App\Services\CopyApiService;
use App\Services\UserPammService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PammController extends Controller
{
    protected $copy_api;
    public function __construct()
    {
        $this->copy_api = new CopyApiService('mt5');
    }
    //get pamm profile list
    public function pamm_profile_list(Request $request)
    {
        // authorization check
        if ($request->header('api_key')) {
            $page = 1;
            $rpp = $request->rpp;
            $orderBy = "cu.id ASC";

            if (!empty($request->page)) {
                $page = $request->page;
            }

            $start = ($page - 1) * $rpp;
            if ($start < 0) $start = 0;

            $sql = "SELECT cu.*, SUM(ct.Profit) AS total_filter_profit,";
            $sql .= "(SELECT SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) AS total_profit FROM copy_trades WHERE Login =cu.account AND Deal != 0) AS total_profit, ";
            $sql .= "(SELECT SUM(CASE WHEN Profit < 0 THEN Profit ELSE 0 END) AS total_lose FROM copy_trades WHERE Login = cu.account AND Deal != 0)AS total_lose, ";
            $sql .= "(SELECT COUNT(*) AS total_slaves FROM copy_slaves WHERE master = cu.account)AS total_slaves, ";
            $sql .= "(SELECT COUNT(*) AS all_copiers FROM copy_activities WHERE master = cu.account AND action != 'uncopy')AS all_copiers ";
            $sql .= " FROM copy_users cu";
            $sql_join = " LEFT JOIN copy_trades ct ON cu.account = ct.Login ";
            $sql_where = "";

            // filter by deposit
            if ($request->filter_deposit != "") {
                $sql_where .= " cu.min_deposit <=  $request->filter_deposit ";
            }
            // filter by user name
            if ($request->filter_text != "") {
                $sql_where .= " (cu.name LIKE '%$request->filter_text%' OR cu.username LIKE '%$request->filter_text%' OR cu.email LIKE '%$request->filter_text%') ";
            }
            // filter by duration
            if ($request->filter_duration != "") {
                $sql_where .= ($sql_where != "") ? " AND " : "";
                $sql_where .= " DATE(ct.OpenTime) >= CURDATE() - INTERVAL $request->filter_duration DAY ";
            }
            // filter by expert position
            if ($request->filter_expert != "") {
                if ($request->filter_duration != "") {
                    $sql_where .= " OR ";
                } else if ($sql_where != "") {
                    $sql_where .= " AND ";
                }
                $sql_where .= " DATE(ct.OpenTime) >= CURDATE() - INTERVAL $request->filter_expert DAY ";
            }
            // filter by gainer
            if ($request->filter_gainer != "") {
                $orderBy = "total_filter_profit $request->filter_gainer";
            }
            // sql limit
            $limit_sql = " GROUP BY cu.account ORDER BY $orderBy LIMIT $start, $rpp";

            if ($sql_where != "") {
                $sql_where = " WHERE " . $sql_where;
            }

            // count total
            $count_sql = "SELECT COUNT(*) AS total FROM (" . $sql;
            $count_sql .= $sql_join . $sql_where . " GROUP BY cu.account) t";

            $count_result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => $count_sql
                ]
            ]));
            // get result
            $sql .= $sql_join . $sql_where . $limit_sql;
            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => $sql
                ]
            ]));
            // check data exist or not
            $result = ($result) ? $result->data : $result = [];

            $total = isset($count_result->data) ? $count_result->data[0]->total : 0;
            $data = array();

            foreach ($result as $key => $row) {
                //Get User Country
                $account_info = TradingAccount::where('account_number', $row->account)
                    ->join('user_descriptions', 'trading_accounts.user_id', '=', 'user_descriptions.user_id')
                    ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->first();

                $u_flag = trim(strtolower((isset($account_info->iso)) ? $account_info->iso : 'in'));
                $data[$key]['id'] = $row->id;
                $data[$key]['flag'] = $u_flag;
                $data[$key]['name'] = $row->name;
                $data[$key]['account'] = $row->account;
                $data[$key]['share_profit'] = $row->share_profit;
                $data[$key]['total_slaves'] = $row->total_slaves + 0;
                $data[$key]['total_filter_profit'] = $row->total_filter_profit;
                $data[$key]['total_profit'] = $row->total_profit + 0;
                $data[$key]['total_lose'] = $row->total_lose + 0;
                $data[$key]['created_at'] = $row->created_at;
                $data[$key]['all_copiers'] = $row->all_copiers + 0;
            }

            return ([
                'status' => true,
                'data' => $data,
                'page' => $page,
                'total' => $total,
                'code' => '001'
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // get pamm profile details
    // pamm account details
    public function pamm_account_details(Request $request)
    {
        // check authorization
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // check account validation
            $validation_rules = [
                'account' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first!',
                    'errors' => $validator->errors(),
                ]);
            }
            // get account details
            $has_follower = UserPammService::account_details_chart($request->account, $request->day);
            return ([
                'status' => true,
                'data' => $has_follower,
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002',
        ]);
    }
    // bar chart
    public function bar_chart(Request $request)
    {
        // check authorization
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // account validation check
            $validation_rules = [
                'account' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first',
                    'errors' => $validator->errors(),
                ]);
            }
            // create bar chart data
            $months = [];
            $month = time();
            for ($i = 1; $i <= 12; $i++) {
                $month = strtotime('last month', $month);
                $months[] = [
                    'month' => date("F", $month),
                    'year' => date("Y", $month),
                    'value' => 0,
                ];
            }
            $monthsWithValue = $months;
            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT COUNT(slave) AS traders, date_format(created_at,"%M") as monthName, date_format(created_at, "%Y") as yearName FROM copy_slaves WHERE master =' . $request->account . ' AND date(created_at) > now() - INTERVAL 12 month group by month(created_at) order BY(created_at)',
                ]
            ];

            $result = json_decode($this->copy_api->apiCall($data));
            (!is_null($result)) ? $perMonthTraders = $result->data :  $perMonthTraders = [];

            foreach ($monthsWithValue as $key => $item) {
                foreach ($perMonthTraders as $perMonthTrader) {
                    if (strtolower($perMonthTrader->monthName) == strtolower($item['month'])  && strtolower($perMonthTrader->yearName) == strtolower($item['year'])) {
                        $monthsWithValue[$key]['value'] = $perMonthTrader->traders;
                    }
                }
            }
            $months = [];
            $years = [];
            $traders = [];
            foreach ($monthsWithValue as $key => $value) {
                array_push($months, $monthsWithValue[$key]['month'] . "-" . $monthsWithValue[$key]['year']);
                array_push($traders, $monthsWithValue[$key]['value']);
            }
            $output_data = [
                'months' => $months,
                'traders' => $traders
            ];
            return ([
                'status' => true,
                'months' => $months,
                'traders' => $traders,
                'code' => '001'
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // pamm profile details piechart
    public function get_piechart(Request $request)
    {
        // check authorizations
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // account validation check
            $validation_rules = [
                'account' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first',
                    'errors' => $validator->errors(),
                ]);
            }
            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT Symbol , SUM(Profit) as profit FROM copy_trades WHERE Login=' . $request->account . ' GROUP BY Symbol',
                ]
            ]));
            (!is_null($result)) ? $instruments_tradeds = $result->data : $instruments_tradeds = [];


            /******* get the consecutive wins and losses **********/

            $symbols = [];
            $profits = [];
            foreach ($instruments_tradeds as $instruments_traded) {
                array_push($symbols, $instruments_traded->Symbol);
                array_push($profits, ($instruments_traded->profit));
            }
            // /instruments traded
            $symbols = [];
            $profits = [];
            foreach ($instruments_tradeds as $instruments_traded) {
                array_push($symbols, $instruments_traded->Symbol);
                array_push($profits, ($instruments_traded->profit));
                // $profits .= $instruments_traded->Symbol.',';
            }

            return ([
                'status' => true,
                'symbols' => $symbols,
                'profits' => $profits,
                'status' => '001'
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // account details / more details
    public function get_more_details(Request $request)
    {
        // check api authorization
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // check account validation
            $validation_rules = [
                'account' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first',
                    'errors' => $validator->errors(),
                ]);
            }
            // if everything good
            $ac = $request->account;
            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT COUNT("id") AS total_trade  FROM copy_trades where  Login=' . $ac,
                ]
            ];

            $result = json_decode($this->copy_api->apiCall($data));
            (!is_null($result)) ? $total_trade = $result->data[0]->total_trade : $total_trade = 0;

            /******* Average Daily trades **********/
            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT  DATEDIFF((SELECT created_at FROM copy_trades where Login = ' . $ac . ' order by created_at DESC LIMIT 1), (SELECT created_at FROM copy_trades where Login = ' . $ac . ' order by created_at ASC LIMIT 1)) as days FROM `copy_trades` WHERE Login = ' . $ac . ' LIMIT 1',

                ]
            ];

            $result = json_decode($this->copy_api->apiCall($data));
            // return $result->data;
            //fixing when result variable is null
            // (!is_null($result->data)) ? $days =  $result->data[0]->days : $days = 1;
            if ($result->data != "" && (isset($result->data[0]) && $result->data[0]->days != 0)) {
                $days = $result->data[0]->days;
                $averageDailyTrades = $total_trade / $days;
            } else {
                $days = 0;
                $averageDailyTrades = 0;
            }

            /******* deals with stop loss and deals with take profit **********/
            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT SUM(PriceSL) + SUM(PriceTP) as sum_price_sl_tp, SUM(PriceSL) as sum_price_sl, SUM(PriceTP) as sum_price_tp  FROM copy_trades where PriceSL <> 0 and PriceTP <> 0  and Login=' . $ac,
                ]
            ];

            $result = json_decode($this->copy_api->apiCall($data));

            (!is_null($result)) ? $stop_loss = $result->data[0]->sum_price_sl_tp : $stop_loss = 0;
            (!is_null($result)) ? $priceSL = $result->data[0]->sum_price_sl : $priceSL = 0;
            (!is_null($result)) ? $priceTP = $result->data[0]->sum_price_tp : $priceTP = 0;

            $d_stop_loss = round(($total_trade * $priceSL) / 100, 2);

            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT Profit from copy_trades where Login=' . $ac,
                ]
            ];

            $result = json_decode($this->copy_api->apiCall($data));
            (!is_null($result) ? $profits = $result->data : $profits = []);


            $curr_win = 0;
            $max_win = 0;
            $curr_loss = 0;
            $max_loss = 0;
            foreach ($profits as $item) {

                if ($item->Profit > 0) {
                    $curr_win++;
                    $curr_loss = 0;
                    if ($curr_win > $max_win) {
                        $max_win = $curr_win;
                    }
                } else {
                    $curr_win = 0;
                    $curr_loss++;
                    if ($curr_loss > $max_loss) {
                        $max_loss = $curr_loss;
                    }
                }
            }
            /******* get Profitable trade**********/
            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT Count("Profit") as profitable_trade, AVG(Profit) as averageDailyProfit from copy_trades  where Profit > 0 and profit <> 0 and  Login=' . $ac,
                ]
            ];
            $result = json_decode($this->copy_api->apiCall($data));

            //fixing when result variable is null
            (!is_null($result)) ? $profitable_trade =  $result->data[0]->profitable_trade : $profitable_trade = 0;
            (!is_null($result)) ? $averageDailyProfit =  $result->data[0]->averageDailyProfit : $averageDailyProfit = 0;
            /******* get unProfitable trade**********/
            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT Count("Profit") as unProfitable_trade from copy_trades  where Profit <= 0 and  Login=' . $ac,
                ]
            ];
            $result = json_decode($this->copy_api->apiCall($data));
            //fixing when result variable is null
            (!is_null($result)) ? $unProfitable_trade =  $result->data[0]->unProfitable_trade : $unProfitable_trade = 0;

            $fx_profitable = ($total_trade != 0) ? round(($profitable_trade / $total_trade) * 100) : 0;
            $fx_take_profit = round(($total_trade * $priceTP) / 100, 2);
            $fx_max_loss = round($max_loss, 2);
            $fx_unprofit = ($total_trade != 0) ? round(($unProfitable_trade / $total_trade) * 100, 2) : 0;

            /******* get sell trade**********/
            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT Count("type") as sell from copy_trades  where type = 1 and  Login=' . $ac,
                ]
            ];
            $result = json_decode($this->copy_api->apiCall($data));

            //fixing when result variable is null
            (!is_null($result)) ? $sell =  $result->data[0]->sell : $sell = 0;

            /******* get buy trade**********/
            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT Count("type") as buy from copy_trades  where type = 0 and  Login=' . $ac,
                ]
            ];
            $result = json_decode($this->copy_api->apiCall($data));
            //fixing when result variable is null
            (!is_null($result)) ? $buy =  $result->data[0]->buy : $buy = 0;

            $fx_sell_percenct = (($sell + $buy) != 0) ? round(($sell / ($sell + $buy) * 100), 2) : 0;

            /******* get greatest win and loss **********/
            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT MAX(Profit) as greatest_win, MIN(Profit) as greatest_loss from copy_trades  where Login=' . $ac,
                ]
            ];
            $result = json_decode($this->copy_api->apiCall($data));
            //fixing when result variable is null
            (!is_null($result)) ? $greatest_win =  $result->data[0]->greatest_win : $greatest_win = 0;
            (!is_null($result)) ? $greatest_loss =  $result->data[0]->greatest_loss : $greatest_loss = 0;
            $fx_greatest_win = round($greatest_win, 2);

            /******* Average Trade length **********/
            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT  AVG(TIMESTAMPDIFF( second, OpenTime, CloseTime)) AS averageTradeTime from copy_trades  where Login=' . $ac,
                ]
            ];

            $result = json_decode($this->copy_api->apiCall($data));
            //fixing when result variable is null
            (!is_null($result)) ? $averageTradeTime = date(' H:i:s', $result->data[0]->averageTradeTime) : $averageTradeTime = '00:00:00';
            $fx_buy = (($sell + $buy) != 0) ? round($buy / ($sell + $buy) * 100, 2) : 0;

            $fx_greatest_loss = round($greatest_loss, 2);
            $fx_avg_dprofit = round($averageDailyProfit, 2);

            /******* max simultaneously open  trades **********/
            $data = [
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT COUNT(id) as trades FROM copy_trades WHERE Login = ' . $ac . ' GROUP BY (created_at)',
                ]
            ];

            $result = json_decode($this->copy_api->apiCall($data));
            (!is_null($result)) ? $maxOpenTradesArray =  $result->data : $maxOpenTradesArray = [];
            $maxOpenTrades = (!empty($maxOpenTradesArray) ? max($maxOpenTradesArray) : 0);
            $maxOpenTrades = isset($maxOpenTrades->trades) ? $maxOpenTrades->trades : 0;
            $fx_open_trades = round($maxOpenTrades, 2);
            $fx_avg_trade = round($averageDailyTrades, 2);
            $data = [
                'total_trade' => $total_trade,
                'stop_loss' => $stop_loss,
                'd_stop_loss' => $d_stop_loss,
                'consicutive_wins' => $max_win,
                'profitable_trade' => $profitable_trade,
                'profitable' => $fx_profitable,
                'take_profit' => $fx_take_profit,
                'consicutive_loss' => $fx_max_loss,
                'unProfitable_trade' => $unProfitable_trade,
                'unProfitable' => $fx_unprofit,
                'sell' => $sell,
                'sell_percent' => $fx_sell_percenct,
                'greatest_win' => $fx_greatest_win,
                'trade_lenth' => $averageTradeTime,
                'buy' => $fx_buy,
                'greatest_loss' => $fx_greatest_loss,
                'daily_profit' => $fx_avg_dprofit,
                'open_trade' => $fx_open_trades,
                'daily_trade' => $fx_avg_trade
            ];
            return ([
                'status' => true,
                'data' => $data,
                'code' => '001'
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // pamm profile details
    // statistic chart
    public function pamm_statistic_chart(Request $request)
    {
        // check api authorization
        // return CrmApiService::api_key();
        if (trim($request->header('api_key')) === trim(CrmApiService::api_key())) {
            // check account validation
            $validation_rules = [
                'account' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first',
                    'errors' => $validator->errors(),
                ]);
            }


            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT Symbol , SUM(Profit) as profit FROM copy_trades WHERE Login=' . $request->account . ' GROUP BY Symbol',
                ]
            ]));
            (!is_null($result)) ? $instruments_tradeds = $result->data : $instruments_tradeds = [];


            /******* get the consecutive wins and losses **********/

            $symbols = [];
            $profits = [];
            foreach ($instruments_tradeds as $instruments_traded) {
                array_push($symbols, $instruments_traded->Symbol);
                array_push($profits, ($instruments_traded->profit));
            }
            // /instruments traded
            $symbols = [];
            $profits = [];
            foreach ($instruments_tradeds as $instruments_traded) {
                array_push($symbols, $instruments_traded->Symbol);
                array_push($profits, ($instruments_traded->profit));
            }

            return ([
                'status' => false,
                'symbols' => $symbols,
                'profits' => $profits
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // trade per hour
    // pamm profie details
    public function trade_per_hour(Request $request)
    {
        // check api authorization
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // check account validation
            $validation_rules = [
                'account' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first',
                    'errors' => $validator->errors(),
                ]);
            }

            $hours = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 0, 21 => 0, 22 => 0, 23 => 0];

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT sum(Profit) as profit, hour(created_at) as hour  FROM copy_trades WHERE Login =' . $request->ac . ' and Profit > 0 and created_at > now() - INTERVAL 24 hour GROUP by hour(created_at) Order by  hour(created_at)',

                ]
            ]));
            (!is_null($result)) ?  $tradesByHoursProfit = $result->data : $tradesByHoursProfit = [];

            foreach ($hours as $key => $value) {
                foreach ($tradesByHoursProfit as $tradesByHourProfit) {
                    if ($tradesByHourProfit->hour == $key) {
                        $hours[$key] = $tradesByHourProfit->profit;
                    }
                }
            }

            $tradesByHourProfitValue = $hours;
            foreach ($hours as $key => $value) {
                $hours[$key] = 0;
            }
            //get trades per hour loss

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT sum(Profit) as profit, hour(created_at) as hour  FROM copy_trades WHERE Login =' . $request->ac . ' and Profit < 0 and created_at > now() - INTERVAL 24 hour GROUP by hour(created_at) Order by  hour(created_at)',
                ]
            ]));
            (!is_null($result)) ?  $tradesByHoursLoss = $result->data : $tradesByHoursLoss = [];

            foreach ($hours as $key => $value) {
                foreach ($tradesByHoursLoss as $tradesByHourLoss) {

                    if ($tradesByHourLoss->hour == $key) {
                        $hours[$key] = $tradesByHourLoss->profit;
                    }
                }
            }

            $tradesByHourLossValue = $hours;
            return ([
                'status' => true,
                'loss_value' => $tradesByHourLossValue,
                'profit_value' => $tradesByHourProfitValue
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // get statistic chart 2
    // pamm profile details
    public function pamm_statistic_chart2(Request $request)
    {
        // check authorizaiton
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // check account validation
            $validation_rules = [
                'account' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first',
                    'errors' => $validator->errors(),
                ]);
            }

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT distinct Symbol FROM `copy_trades` where Login = ' . $request->account . ' order by id',

                ]
            ]));
            (!is_null($result)) ? $all_symbols = $result->data : $all_symbols = [];
            $all_symbol_names = [];
            $all_symbol_value = [];
            foreach ($all_symbols as $all_symbol) {
                array_push($all_symbol_names, $all_symbol->Symbol);
                $all_symbol_value[$all_symbol->Symbol] = 0;
            }
            //get the profitable symbol
            $data = [];

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT Symbol, Profit as profit FROM `copy_trades` where Profit>0 and Login = ' . $request->account . ' GROUP by Symbol order by id',

                ]
            ]));

            (!is_null($result)) ?  $symbol_profits = $result->data : $symbol_profits = [];
            $profit_symbol_value = [];

            foreach ($all_symbol_names as $all_symbol_name) {
                foreach ($symbol_profits as $symbol_profit) {
                    if ($all_symbol_name == $symbol_profit->Symbol) {
                        $all_symbol_value[$all_symbol_name] = $symbol_profit->profit;
                        break;
                    }
                }
            }

            foreach ($all_symbol_value as $key => $value) {
                array_push($profit_symbol_value, $value);
            }

            //again set 0 in all symbols value
            foreach ($all_symbols as $all_symbol) {
                $all_symbol_value[$all_symbol->Symbol] = 0;
            }
            //get the loss symbol

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT Symbol, Profit as loss FROM `copy_trades` where Profit<0 and Login = ' . $request->account . ' GROUP by Symbol order by id',

                ]
            ]));
            (!is_null($result)) ?    $symbol_losses = $result->data : $symbol_losses = [];
            $loss_symbol_value = [];
            foreach ($all_symbol_names as $all_symbol_name) {
                foreach ($symbol_losses as $symbol_loss) {

                    if ($all_symbol_name == $symbol_loss->Symbol) {
                        $all_symbol_value[$all_symbol_name] = $symbol_loss->loss;
                        break;
                    }
                }
            }

            foreach ($all_symbol_value as $key => $value) {
                array_push($loss_symbol_value, $value);
            }

            return ([
                'status' => true,
                'symbol' => $all_symbol_names,
                'value' => $loss_symbol_value,
                'profit' => $profit_symbol_value
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // get trade per day
    public function trade_per_day(Request $request)
    {
        // check api authorization
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // check account validation
            $validation_rules = [
                'account' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first',
                    'errors' => $validator->errors(),
                ]);
            }

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT  dayname(created_at) as day_name FROM copy_trades WHERE Login = ' . $request->account . '  and created_at > now() - INTERVAL 7 day GROUP by dayname(created_at) Order by (created_at) DESC',

                ]
            ]));
            (!is_null($result)) ?  $days = $result->data : $days = [];

            $daysValue = [];
            $weekDaysName = [];
            $weekDaysValue = [];
            foreach ($days as $day) {
                array_push($weekDaysName, $day->day_name);

                //initially set zero
                $weekDaysValue[$day->day_name] = 0;
            }

            //get the days profit

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT sum(Profit) as profit, dayname(created_at) as day_name FROM copy_trades WHERE Login = ' . $request->account . ' and Profit >0 and created_at > now() - INTERVAL 7 day GROUP by dayname(created_at) Order by (created_at) DESC',

                ]
            ]));
            (!is_null($result)) ?  $weekDaysProfit = $result->data : $weekDaysProfit = [];

            foreach ($weekDaysValue as $key => $value) {

                foreach ($weekDaysProfit as $weekDayProfit) {

                    if ($key == $weekDayProfit->day_name) {
                        $weekDaysValue[$key] = $weekDayProfit->profit;
                        break;
                    }
                }
            }


            $dayPerProfit = [];
            foreach ($weekDaysValue as $key => $value) {
                array_push($dayPerProfit, $value);
            }
            //again set zero in all days value
            foreach ($weekDaysValue as $key => $value) {
                $weekDaysValue[$key] = 0;
            }


            //get the days loss
            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT sum(Profit) as loss, dayname(created_at) as day_name FROM copy_trades WHERE Login = ' . $request->account . ' and Profit < 0 and created_at > now() - INTERVAL 7 day GROUP by dayname(created_at) Order by (created_at) DESC',
                ]
            ]));
            (!is_null($result)) ?  $weekDaysLoss = $result->data : $weekDaysLoss = [];


            foreach ($weekDaysValue as $key => $value) {

                foreach ($weekDaysLoss as $weekDayLoss) {

                    if ($key == $weekDayLoss->day_name) {
                        $weekDaysValue[$key] = $weekDayLoss->loss;
                        break;
                    }
                }
            }

            $dayPerLoss = [];
            foreach ($weekDaysValue as $key => $value) {
                array_push($dayPerLoss, $value);
            }

            return ([
                'days' => $weekDaysName,
                'value' => $weekDaysValue,
                'loss' => $dayPerLoss,
                'profit' => $dayPerProfit
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // get open orders
    // pamm profile details
    public function open_orders(Request $request)
    {
        // check api authorizaion
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // check account validation
            $validation_rules = [
                'account' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first',
                    'errors' => $validator->errors(),
                ]);
            }

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT DISTINCT (DATE(OpenTime)) OpenTime FROM copy_trades Where CloseTime = "1970-01-01" AND login=' . $request->account . ' ORDER BY CloseTime DESC',
                ]
            ]));
            (!is_null($result)) ? $copy_trades = $result->data : $copy_trades = [];

            $data = array();
            $details = array();
            $i = 0;
            foreach ($copy_trades as $trade) {
                $openTime = $trade->OpenTime;
                $api_data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => "SELECT Volume, Symbol, CloseTime, Profit FROM copy_trades WHERE (CloseTime = '1970-01-01' AND DATE(OpenTime)= '" . $openTime . "') AND login=" . $request->account . "",
                    ]
                ];
                $result = json_decode($this->copy_api->apiCall($api_data));
                (!is_null($result)) ? $openTimeDetails = $result->data : $openTimeDetails = [];

                $open_time = "<div class='fx-opn-tm'><p class='mb-2' style='color:#4f5256; font-size:1.2rem;font-weight:bold;padding-left:.5rem; margin-left:0;'>" . $trade->OpenTime . "</p></div>";
                $close_time = "<div class='fx-nbsp p-1 mb-1'>&nbsp;</div>";
                $duretion = "<div class='fx-nbsp p-1 mb-1'>&nbsp;</div>";
                $fx_profilt = "<div class='fx-nbsp p-1 mb-1'>&nbsp;</div>";
                foreach ($openTimeDetails as $timeDetails) {
                    $open_time .= "<div class='trd-row-height p-1 ps-3 pe-3 mb-3 d-flex justify-content-between align-items-center fx-open-time-con'><div class='d-flex'><img src='" . asset('assets/img/pamm/logo/arrow.svg') . "' alt='arrow'>" . "<p class='table-dgt ms-3 bg-dark'>" . ($timeDetails->Volume / 10000) . "</p></div><p class='table-currency mb-0'>" . $timeDetails->Symbol . "</p></div>";
                    $close_time .= "<div class='trd-row-height d-flex align-items-center p-1 ps-3 mb-3 close-time-fx' style='font-size:12px'>$timeDetails->CloseTime</div>";
                    $duretion .= "<div class='duration-fx d-flex align-items-center p-1 ps-3 mb-3 trd-row-height' style='font-size:12px'>$timeDetails->Profit </div>";
                    $fx_profilt .= "<div class='trd-row-height d-flex align-items-center p-1 ps-3 mb-3 fx-profit-row' style='font-size:12px'>&dollar;$timeDetails->Profit</div>";
                }

                $data[$i]['OpenTime'] = $open_time;
                $data[$i]['space1'] = $close_time;
                $data[$i]['space2'] = $duretion;
                $data[$i]['space3'] = $fx_profilt;

                $open_time = "";
                $close_time = "";
                $duretion = "";
                $fx_profilt = "";
                $i++;
            }

            return ([
                'status' => true,
                'data' => $data,
                'details' => $details
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // close order
    // pamm profile details
    public function close_orders(Request $request)
    {
        // check api authorizations
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // check account validation
            $validation_rules = [
                'account' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first',
                    'errors' => $validator->errors(),
                ]);
            }
            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => 'SELECT DISTINCT (DATE(OpenTime)) OpenTime FROM copy_trades Where CloseTime != "1970-01-01" AND login=' . $request->account . ' ORDER BY CloseTime DESC',
                ]
            ]));
            (!is_null($result)) ? $copy_trades = $result->data : $copy_trades = [];

            $data = array();
            $details = array();
            $i = 0;
            foreach ($copy_trades as $trade) {
                // datatables tr details
                $openTime = $trade->OpenTime;
                $api_data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => "SELECT Volume, Symbol, CloseTime, Profit FROM copy_trades WHERE (CloseTime != '1970-01-01' AND DATE(OpenTime)= '" . $openTime . "') AND login=" . $request->account,
                    ]
                ];
                $result = json_decode($this->copy_api->apiCall($api_data));
                (!is_null($result)) ? $openTimeDetails = $result->data : $openTimeDetails = [];
                $open_time = "<div class='fx-opn-tm'><p class='mb-2' style='color:#4f5256; font-size:1.2rem;font-weight:bold;padding-left:.5rem; margin-left:0;'>" . $trade->OpenTime . "</p></div>";
                $close_time = "<div class='fx-nbsp close-time-fx p-1 mb-2'>&nbsp;</div>";
                $duretion = "<div class='fx-nbsp p-1 mb-2'>&nbsp;</div>";
                $fx_profilt = "<div class='fx-nbsp p-1 mb-2'>&nbsp;</div>";
                foreach ($openTimeDetails as $timeDetails) {
                    $open_time .= "<div class='trd-row-height ps-3 pe-3 fx-open-time-con d-flex justify-content-between align-items-center bg-gradient-faded-light-vertical mb-3 p-1'>
                                    <div class='arrow-con d-flex'><img src='" . asset('assets/img/pamm/logo/arrow.svg') . "' alt='arrow'>" . "<p class='table-dgt ms-3'>" . ($timeDetails->Volume / 10000) . "</p></div><p class='table-currency m-0'>" . $timeDetails->Symbol . "</p></div>";
                    $close_time .= '<div class="trd-row-height ps-3 align-items-center d-flex close-time-fx close-time-fx bg-gradient-faded-light-vertical p-1 mb-3" style="font-size: 12px;">' . date("h:i A", strtotime($timeDetails->CloseTime)) . '</div>';

                    $duretion .= "<div class='trd-row-height ps-3 align-items-center d-flex duration-fx bg-gradient-faded-light-vertical p-1 mb-3' style='font-size: 12px;'>" . UserPammService::get_duration($openTime, $timeDetails->CloseTime) . "</div>";
                    $fx_profilt .= "<div class='trd-row-height ps-3 align-items-center d-flex fx-profit-row bg-gradient-faded-light-vertical p-1 mb-3' style='font-size: 12px;'>&dollar;$timeDetails->Profit</div>";
                }

                $data[$i]['CloseTime'] = $open_time;
                $data[$i]['space1'] = $close_time;
                $data[$i]['space2'] = $duretion;
                $data[$i]['space3'] = $fx_profilt;
                $open_time = $close_time = $duretion = $fx_profilt = "";
                $i++;
            }
            return ([
                'status' => true,
                'data' => $data,
                'details' => $details
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // get performance data
    // pamm profile details
    public function get_performance(Request $request)
    {
        // check api authorization
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // check account validation
            $validation_rules = [
                'account' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first',
                    'errors' => $validator->errors(),
                ]);
            }
            // get data
            if ($request->duration == 0) {
                $profit_loss = UserPammService::get_gain($request->account);
                return ([
                    'status' => true,
                    'profitSum' => ($profit_loss['profit'] != null) ? $profit_loss['profit'] : 0,
                    'lossSum' => $profit_loss['loss'],
                    'total' => $profit_loss['total'],
                ]);
            }
            $profit_loss = UserPammService::get_gain($request->account, $request->duration);
            return ([
                'status' => true,
                'profitSum' => ($profit_loss['profit'] != null) ? $profit_loss['profit'] : 0,
                'lossSum' => $profit_loss['loss'],
                'total' => $profit_loss['total'],
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // get pamm profile user details
    // pamm profile details
    public function user_profile(Request $request)
    {
        if ($request->header('api_key') === CrmApiService::api_key()) {
            // check account validation
            $validation_rules = [
                'account' => 'required',
                'user_id' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation errors, Please check first',
                    'errors' => $validator->errors(),
                ]);
            }
            // get data

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "SELECT copy_users.id,copy_users.account AS master_account, (SELECT DATEDIFF(NOW(),created_at ) as with_us from copy_users WHERE account =$request->account)AS with_us,copy_users.name,copy_users.email,copy_users.username,copy_users.account,copy_users.min_deposit,copy_users.max_deposit,copy_users.share_profit,copy_users.created_at,(SELECT COUNT(id) FROM copy_activities WHERE master=master_account AND action='copy')AS total_copy, (SELECT COUNT(id) FROM copy_activities WHERE master=master_account AND action='uncopy')AS total_uncopy, (SELECT COUNT('id')AS copy FROM `copy_activities` WHERE (action ='copy'AND type='pamm') AND master = master_account AND DATE(created_at) = DATE(NOW()))AS today_copy FROM copy_users LEFT JOIN copy_activities ON copy_users.account = copy_activities.master where copy_users.account =" . $request->account,
                ]
            ]));

            (!is_null($result)) ? $copy_user = isset($result->data[0]) ? $result->data[0] : [] : $copy_user = [];

            $profit_loss = UserPammService::get_gain($copy_user->account, 14);
            $achiever = "";
            if ($profit_loss["profit"] > abs($profit_loss["loss"])) {
                $achiever = "High achiever";
            } elseif ($profit_loss["profit"] == abs($profit_loss["loss"])) {
                $achiever = "Mid achiever";
            } else {
                $achiever = "Low achiver";
            }

            $pr_gain = ($profit_loss['gain'] > 0) ? '+' . $profit_loss['gain'] : $profit_loss['gain'];
            if (abs($profit_loss['total']) != 0) {
                $positive_width = (100 * abs($profit_loss['profit'])) / (abs($profit_loss['total']));
                $negative_width = (100 * abs($profit_loss['loss'])) / (abs($profit_loss['total']));
            } else {
                $positive_width = 0;
                $negative_width = 0;
            }

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "SELECT copy_users.id,copy_users.account AS master_account,  copy_users.name,copy_users.email,copy_users.username,copy_users.account,copy_users.min_deposit,copy_users.max_deposit,copy_users.share_profit,copy_users.created_at,(SELECT COUNT(id) FROM copy_activities WHERE master=master_account AND action='copy' AND (DATE(copy_users.created_at)> now() - INTERVAL 14 DAY))AS total_copy, (SELECT COUNT(id) FROM copy_activities WHERE master=master_account AND action='uncopy' AND (DATE(copy_users.created_at)> now() - INTERVAL 14 DAY))AS total_uncopy, (SELECT COUNT('id')AS copy FROM `copy_activities` WHERE (action ='copy'AND type='pamm') AND master = master_account AND DATE(created_at) = DATE(NOW()) AND (DATE(copy_users.created_at)> now() - INTERVAL 14 DAY))AS today_copy FROM copy_users LEFT JOIN copy_activities ON copy_users.account = copy_activities.master where copy_users.account =$request->account AND (DATE(copy_users.created_at)> now() - INTERVAL 14 DAY)",
                ]
            ]));
            (!is_null($result)) ? $partial_copy_user = $result->data : $copy_user = [];

            // get flag from country
            $country = (isset($copy_user->country)) ? $copy_user->country : 'india';
            $iso = Country::where('name', $country)->select()->first();
            $flag = (isset($iso->iso) && $iso->iso != null) ? strtolower($iso->iso) : 'in';
            $flag .= '.svg';

            /*********************************************
             * get data from hb_ac table/re
             ********************************************/
            $trading_account = TradingAccount::where('account_number', $request->account)->select()->first();
            $my_trd_account = TradingAccount::where('user_id', $request->user_id)->where('client_type', 'live')->get();
            return ([
                'status' => true,
                'partial_copy_user' => $partial_copy_user,
                'copy_user' => $copy_user,
                'achiever' => $achiever,
                'flag' => $flag,
                'profit_loss' => $profit_loss,
                'gain' => $profit_loss['gain'],
                'trading_account' => $trading_account,
                'my_trading_account' => $my_trd_account
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
    // add slave account/ copy trades
    public function add_slave_account(Request $request)
    {
        // check api authorization
        if ($request->header('api_key') === CrmApiService::api_key()) {
            $validation_rules = [
                'max_trade' => 'required',
                'max_volume' => 'required|numeric',
                'min_volume' => 'required',
                'account' => 'required',
                'slave_account' => 'required',
                'allocation' => 'required',
                'symbol' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return ([
                    'status' => false,
                    'message' => 'Validation error, Please fix following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            // if validate 
            $check_ac = TradingAccount::where('account_number', $request->slave_account)->first();
            if ($check_ac) {
                $copy_api = new CopyApiService($check_ac->server);
                //GET master settings
                $result_settings = json_decode($copy_api->apiCall([
                    'command' => 'Custom',
                    'data' => [
                        'sql' => "SELECT * FROM copy_users WHERE account = '$request->account'"
                    ]
                ]));
                $fx_settings = $result_settings->data;

                if (isset($result_settings->status)) {
                    if ($result_settings->status) {
                        $check_setting = true;
                        $min_deposit = $fx_settings[0]->min_deposit;
                        $max_deposit = $fx_settings[0]->max_deposit;
                    }
                }
                if ($check_setting) {
                    //Get slave account total deposit
                    $slave_account = TradingAccount::where('account_number', $request->slave_account)->first();
                    $total_deposit = InternalTransfer::where('account_id', $slave_account->id)->sum('amount');

                    if ($total_deposit < $min_deposit) {
                        return ([
                            'status' => false,
                            'message' => "Minimum $" . $min_deposit . " is required!"
                        ]);
                    } else if ($total_deposit > $max_deposit && $max_deposit != 0) {

                        return ([
                            'status' => false,
                            'message' => "Maximum deposit limit is $" . $max_deposit
                        ]);
                    }
                } else {
                    $internal_err = 1;
                    $data = [
                        'success' => false,
                        'message' => "Master account setting not found!"
                    ];
                }
            } else {
                $internal_err = 1;
                $data['message'] = $request->slave_account . ' does not exit!';
            }

            //=========check master from copy slave=============
            $pamm = PammSetting::select()->first();
            $mampamm = new CopyApiService('mt5');
            if ($pamm->pamm_requirement_status == 1) {
                $master_count = [
                    'command' => 'CountMaster',
                    'data' => [
                        'master' => $request->account,
                        'slave' => $request->slave_account
                    ]
                ];
                $result = json_decode($mampamm->apiCall($master_count));
                if ($pamm->master_limit != 0) {
                    if ($result->master >= $pamm->master_limit) {
                        return Response::json([
                            'success' => false,
                            'message' => 'Master Limit Exceeded'
                        ]);
                    }
                }
                if ($pamm->slave_limit != 0) {
                    if ($result->copy_slave >= $pamm->slave_limit) {
                        return Response::json([
                            'success' => false,
                            'message' => 'Slave Limit Exceeded'
                        ]);
                    }
                }
            }
            //======end check master script===========

            if ($internal_err == 0) {
                // $slave_sql = "INSERT INTO copy_slaves(master,slave,type,allocation,";
                // $slave_sql .=")"
                $api_data = [
                    'command' => 'addSlave',
                    'data' => [
                        'master' => $request->account,
                        'slave' => $request->slave_account,
                        'type' => 'pamm',
                        'allocation' => $request->allocation,
                        'max_number_of_trade' => $request->max_trade,
                        'max_trade_volume' => $request->max_volume,
                        'min_trade_volume' => $request->min_volume,
                        'symbols' => $request->symbol
                    ]
                ];
                // $result = json_decode(CopyApiService2::apiCall($api_data));
                $result = json_decode($mampamm->apiCall($api_data));
                if ($result->status === true) {
                    $data = [
                        'success' => true,
                        'message' => "Congratulations! You successfully copy this trade"
                    ];
                } else {
                    $data = [
                        'success' => false,
                        'message' => $result->message
                    ];
                }
            }
            return Response::json($data);
        }
        return ([
            'status' => false,
            'message' => 'Un-authorize request found!',
            'code' => '002'
        ]);
    }
}
