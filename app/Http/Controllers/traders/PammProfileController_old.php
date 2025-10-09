<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\PammRequest;
use App\Models\Traders\PammSetting;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\CopyApiService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\UserPammService;

class PammProfileController extends Controller
{
    private $copy_api = "";
    public function __construct()
    {
        $this->copy_api = new CopyApiService();
        if (request()->is('user/user-pamm/user-pamm-registration')) {
            $this->middleware(AllFunctionService::access('pamm_registration', 'trader'));
        } elseif (request()->is('user/user-pamm/user-pamm-copy-traders-details/*')) {
            $this->middleware(AllFunctionService::access('pamm_profile', 'trader'));
        }

        $this->middleware(AllFunctionService::access('pamm', 'trader'));
    }
    public function userPammCopy(Request $request)
    {

        $ac = $request->ac;
        // get data for ajax requiest
        if ($request->ajax()) {
            // data for account details chart
            // get follower chart
            if ($request->op === 'account-details') {

                $has_follower = UserPammService::account_details_chart($ac, $request->day);
                return Response::json($has_follower);
            }
            // analytics section data
            // data for per month traders/slave
            if ($request->op === 'bar-chart') {
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
                        'sql' => 'SELECT COUNT(slave) AS traders, date_format(created_at,"%M") as monthName, date_format(created_at, "%Y") as yearName FROM copy_slaves WHERE master =' . $ac . ' AND date(created_at) > now() - INTERVAL 12 month group by month(created_at) order BY(created_at)',
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
                return Response::json($output_data);
            }

            // data for pie chart
            if ($request->op === 'piechart') {
                $data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT Symbol , SUM(Profit) as profit FROM copy_trades WHERE Login=' . $ac . ' GROUP BY Symbol',
                    ]
                ];

                $result = json_decode($this->copy_api->apiCall($data));
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
                $output_data = [
                    'symbols' => $symbols,
                    'profits' => $profits
                ];
                // echo json_encode($output_data);
                return Response::json($output_data);
            }
            // get more deatails data
            if ($request->op === 'more-details') {
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
                if ($result->data != "" && ($result->data[0]->days != 0)) {
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
                $maxOpenTrades = $maxOpenTrades->trades;
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
                return Response::json($data);
            }
            // get statistics chart 
            // statistics per instruments 
            if ($request->op === 'st-per-instrument') {
                $data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT Symbol , SUM(Profit) as profit FROM copy_trades WHERE Login=' . $ac . ' GROUP BY Symbol',
                    ]
                ];

                $result = json_decode($this->copy_api->apiCall($data));
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
                $output_data = [
                    'symbols' => $symbols,
                    'profits' => $profits
                ];
                return Response::json($output_data);
            }
            // trade per hour
            // chart for trade per hour
            if ($request->op === 'trade-per-hour') {
                $hours = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 0, 21 => 0, 22 => 0, 23 => 0];

                $data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT sum(Profit) as profit, hour(created_at) as hour  FROM copy_trades WHERE Login =' . $ac . ' and Profit > 0 and created_at > now() - INTERVAL 24 hour GROUP by hour(created_at) Order by  hour(created_at)',

                    ]
                ];

                $result = json_decode($this->copy_api->apiCall($data));
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
                $data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT sum(Profit) as profit, hour(created_at) as hour  FROM copy_trades WHERE Login =' . $ac . ' and Profit < 0 and created_at > now() - INTERVAL 24 hour GROUP by hour(created_at) Order by  hour(created_at)',

                    ]
                ];

                $result = json_decode($this->copy_api->apiCall($data));
                (!is_null($result)) ?  $tradesByHoursLoss = $result->data : $tradesByHoursLoss = [];

                foreach ($hours as $key => $value) {
                    foreach ($tradesByHoursLoss as $tradesByHourLoss) {

                        if ($tradesByHourLoss->hour == $key) {
                            $hours[$key] = $tradesByHourLoss->profit;
                        }
                    }
                }

                $tradesByHourLossValue = $hours;
                $data = [
                    'loss_value' => $tradesByHourLossValue,
                    'profit_value' => $tradesByHourProfitValue
                ];
                return Response::json($data);
            }
            // get statistic chart 2 data
            if ($request->op === 'st-chart-two') {
                /******  statistics per instrument  second chart script *********/
                $data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT distinct Symbol FROM `copy_trades` where Login = ' . $ac . ' order by id',

                    ]
                ];
                $result = json_decode($this->copy_api->apiCall($data));
                (!is_null($result)) ? $all_symbols = $result->data : $all_symbols = [];
                $all_symbol_names = [];
                $all_symbol_value = [];
                foreach ($all_symbols as $all_symbol) {
                    array_push($all_symbol_names, $all_symbol->Symbol);
                    $all_symbol_value[$all_symbol->Symbol] = 0;
                }
                //get the profitable symbol
                $data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT Symbol, Profit as profit FROM `copy_trades` where Profit>0 and Login = ' . $ac . ' GROUP by Symbol order by id',

                    ]
                ];

                $result = json_decode($this->copy_api->apiCall($data));

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
                $data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT Symbol, Profit as loss FROM `copy_trades` where Profit<0 and Login = ' . $ac . ' GROUP by Symbol order by id',

                    ]
                ];

                $result = json_decode($this->copy_api->apiCall($data));
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
                $data = [
                    'symbol' => $all_symbol_names,
                    'value' => $loss_symbol_value,
                    'profit' => $profit_symbol_value
                ];
                return Response::json($data);
            }
            // get trade per day
            // chart trade per day
            if ($request->op === 'trade-per-day') {
                $data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT  dayname(created_at) as day_name FROM copy_trades WHERE Login = ' . $ac . '  and created_at > now() - INTERVAL 7 day GROUP by dayname(created_at) Order by (created_at) DESC',

                    ]
                ];

                $result = json_decode($this->copy_api->apiCall($data));
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

                $data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT sum(Profit) as profit, dayname(created_at) as day_name FROM copy_trades WHERE Login = ' . $ac . ' and Profit >0 and created_at > now() - INTERVAL 7 day GROUP by dayname(created_at) Order by (created_at) DESC',

                    ]
                ];

                $result = json_decode($this->copy_api->apiCall($data));
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

                $data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT sum(Profit) as loss, dayname(created_at) as day_name FROM copy_trades WHERE Login = ' . $ac . ' and Profit < 0 and created_at > now() - INTERVAL 7 day GROUP by dayname(created_at) Order by (created_at) DESC',
                    ]
                ];

                $result = json_decode($this->copy_api->apiCall($data));
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

                $data = [
                    'days' => $weekDaysName,
                    'value' => $weekDaysValue,
                    'loss' => $dayPerLoss,
                    'profit' => $dayPerProfit
                ];

                return Response::json($data);
            }
            // get open oreder datatable data
            if ($request->op === 'dt-open-order') {
                $api_data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT DISTINCT (DATE(OpenTime)) OpenTime FROM copy_trades Where CloseTime = "1970-01-01" AND login=' . $ac . ' ORDER BY CloseTime DESC',
                    ]
                ];
                $result = json_decode($this->copy_api->apiCall($api_data));
                (!is_null($result)) ? $copy_trades = $result->data : $copy_trades = [];

                $data = array();
                $details = array();
                $i = 0;
                foreach ($copy_trades as $trade) {
                    $openTime = $trade->OpenTime;
                    $api_data = [
                        'command' => 'Custom',
                        'data' => [
                            'sql' => "SELECT Volume, Symbol, CloseTime, Profit FROM copy_trades WHERE (CloseTime = '1970-01-01' AND DATE(OpenTime)= '" . $openTime . "') AND login=" . $ac . "",
                        ]
                    ];
                    $result = json_decode($this->copy_api->apiCall($api_data));
                    (!is_null($result)) ? $openTimeDetails = $result->data : $openTimeDetails = [];

                    $open_time = "<div class='fx-opn-tm'><p class='mb-2' style='color:#4f5256; font-size:1.2rem;font-weight:bold;padding-left:.5rem; margin-left:0;'>" . $trade->OpenTime . "</p></div>";
                    $close_time = "<div class='fx-nbsp p-1 mb-2'>&nbsp;</div>";
                    $duretion = "<div class='fx-nbsp p-1 mb-2'>&nbsp;</div>";
                    $fx_profilt = "<div class='fx-nbsp p-1 mb-2'>&nbsp;</div>";
                    foreach ($openTimeDetails as $timeDetails) {
                        $open_time .= "<div class='trd-row-height p-1 ps-3 pe-3 mb-3 d-flex justify-content-between align-items-center fx-open-time-con bg-gradient-faded-light-vertical'><div class='d-flex'><img src='" . asset('trader-assets/assets/img/pamm/logo/arrow.svg') . "' alt='arrow'>" . "<p class='table-dgt ms-3 '>" . ($timeDetails->Volume / 10000) . "</p></div><p class='table-currency mb-0'>" . $timeDetails->Symbol . "</p></div>";
                        $close_time .= "<div class='trd-row-height d-flex align-items-center p-1 ps-3 mb-3 close-time-fx bg-gradient-faded-light-vertical'>$timeDetails->CloseTime</div>";
                        $duretion .= "<div class='duration-fx d-flex align-items-center p-1 ps-3 mb-3 trd-row-height bg-gradient-faded-light-vertical'>$timeDetails->Profit </div>";
                        $fx_profilt .= "<div class='trd-row-height d-flex align-items-center p-1 ps-3 mb-3 fx-profit-row bg-gradient-faded-light-vertical'>&dollar;$timeDetails->Profit</div>";
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
                $output = [
                    'data' => $data,
                    // 'details' => $details
                ];

                return Response::json($output);
            }
            // get closed order datatable data
            if ($request->op === 'dt-close-order') {
                $api_data = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => 'SELECT DISTINCT (DATE(OpenTime)) OpenTime FROM copy_trades Where CloseTime != "1970-01-01" AND login=' . $ac . ' ORDER BY CloseTime DESC',
                    ]
                ];
                $result = json_decode($this->copy_api->apiCall($api_data));
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
                            'sql' => "SELECT Volume, Symbol, CloseTime, Profit FROM copy_trades WHERE (CloseTime != '1970-01-01' AND DATE(OpenTime)= '" . $openTime . "') AND login=" . $ac,
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
                                        <div class='arrow-con d-flex'><img src='" . asset('trader-assets/assets/img/pamm/logo/arrow.svg') . "' alt='arrow'>" . "<p class='table-dgt ms-3'>" . ($timeDetails->Volume / 10000) . "</p></div><p class='table-currency m-0'>" . $timeDetails->Symbol . "</p></div>";
                        $close_time .= '<div class="trd-row-height ps-3 align-items-center d-flex close-time-fx close-time-fx bg-gradient-faded-light-vertical p-1 mb-3" style="font-size: 1.1rem;">' . date("h:i A", strtotime($timeDetails->CloseTime)) . '</div>';

                        $duretion .= "<div class='trd-row-height ps-3 align-items-center d-flex duration-fx bg-gradient-faded-light-vertical p-1 mb-3' style='font-size: 1.1rem;'>" . UserPammService::get_duration($openTime, $timeDetails->CloseTime) . "</div>";
                        $fx_profilt .= "<div class='trd-row-height ps-3 align-items-center d-flex fx-profit-row bg-gradient-faded-light-vertical p-1 mb-3' style='font-size: 1.1rem;'>&dollar;$timeDetails->Profit</div>";
                    }

                    $data[$i]['CloseTime'] = $open_time;
                    $data[$i]['space1'] = $close_time;
                    $data[$i]['space2'] = $duretion;
                    $data[$i]['space3'] = $fx_profilt;
                    $open_time = $close_time = $duretion = $fx_profilt = "";
                    $i++;
                }
                $output = [
                    'data' => $data,
                    'details' => $details
                ];
                return Response::json($output);
            }
            // get performance data
            if ($_POST['duration'] == 0) {
                $profit_loss = UserPammService::get_gain($ac);
                $data = [
                    'profitSum' => ($profit_loss['profit'] != null) ? $profit_loss['profit'] : 0,
                    'lossSum' => $profit_loss['loss'],
                    'total' => $profit_loss['total'],
                ];
            } else {
                $profit_loss = UserPammService::get_gain($ac, $request->duration);
                $data = [
                    'profitSum' => ($profit_loss['profit'] != null) ? $profit_loss['profit'] : 0,
                    'lossSum' => $profit_loss['loss'],
                    'total' => $profit_loss['total'],
                ];
            }

            return Response::json($data);
        }
        //find the users profile 
        if ($ac == "") {
            return redirect()->route('user.pamm.profile');
        }
        $copy_user_sql = "SELECT copy_users.id,copy_users.account AS master_account,";
        $copy_user_sql .= " (SELECT DATEDIFF(NOW(),created_at ) as with_us from copy_users WHERE account =$ac)AS with_us,";
        $copy_user_sql .= "copy_users.name,copy_users.email,copy_users.username,copy_users.account,copy_users.min_deposit,copy_users.max_deposit,copy_users.share_profit,copy_users.created_at, ";
        $copy_user_sql .= "(SELECT COUNT(id) FROM copy_activities WHERE master=master_account AND action='copy')AS total_copy,";
        $copy_user_sql .= " (SELECT COUNT(id) FROM copy_activities WHERE master=master_account AND action='uncopy')AS total_uncopy,";
        $copy_user_sql .= " (SELECT COUNT('id')AS copy FROM `copy_activities` WHERE (action ='copy'AND type='pamm') AND master = master_account AND DATE(created_at) = DATE(NOW()))AS today_copy ";
        $copy_user_sql .= " FROM copy_users LEFT JOIN copy_activities ON copy_users.account = copy_activities.master where copy_users.account =" . $ac;
        $result = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => [
                'sql' => $copy_user_sql,
            ]
        ]));

        (!is_null($result)) ? $copy_user = $result->data[0] : $copy_user = [];

        // get flag from country
        $country = (isset($copy_user->country)) ? $copy_user->country : 'india';
        $iso = Country::where('name', $country)->select()->first();
        $flag = (isset($iso->iso) && $iso->iso != null) ? strtolower($iso->iso) : 'in';
        $flag .= '.svg';

        /*********************************************
         * get data from hb_ac table/re
         ********************************************/
        $trading_account = TradingAccount::where('account_number', $ac)->select()->first();
        $my_trd_account = TradingAccount::where('user_id', auth()->user()->id)->where('client_type', 'live')->get();
        return view('traders.pamm.pamm-profile-details', [
            'partial_copy_user' => '',
            'copy_user' => $copy_user,
            'achiever' => '',
            'flag' => $flag,
            'profit_loss' => '',
            'gain' => '',
            'trading_account' => $trading_account,
            'my_trading_account' => $my_trd_account
        ]);
    }

    public function copyTraderReport()
    {
        return view('traders.copy.copy-traders-report');
    }
    public function copyTradersActivitiesReport()
    {
        return view('traders.copy.copy-traders-activities-report');
    }

    public function userPammRegistration()
    {
        $user = auth()->user()->id;
        $trading_account = TradingAccount::select('account_number')->where('user_id', $user)->get();
        return view('traders.pamm.pamm-registration', ['trading_account' => $trading_account]);
    }

    public function PammRegAndUpdate(Request $request)
    {
        $validation_rules = [
            'username' => 'required',
            'trading_account' => 'required',
            'min_deposit' => 'required',
            'share_profit' => 'required',

        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => '<span class="error">Fix the following error</span>',
                'errors' => $validator->errors()
            ]);
        } else {
            $id = auth()->user()->id;
            $name = auth()->user()->name;
            $email = auth()->user()->email;
            $username = strtolower($request->username);
            $account = $request->trading_account;
            $min_deposit = $request->min_deposit == "" ? 0 : $request->min_deposit;
            $max_deposit = $request->max_deposit == "" ? 0 : $request->max_deposit;
            $share_profit = $request->share_profit;
            $date = date('Y-m-d h:i:s');

            $mtac = TradingAccount::select()->where('account_number', $account)->first();

            if ($mtac) {
                $server = $mtac->platform;

                $copy_mt = new CopyApiService($server);


                $req_data = [
                    'command' => 'Custom',
                    'data' => [
                        "sql" => "SELECT COUNT(*) AS check_account FROM copy_users WHERE account = '$account' AND id <> '$id'"
                    ]
                ];
                $result = json_decode($copy_mt->apiCall($req_data));

                if ($result->data[0]->check_account) {
                    return Response::json(['success' => false, 'message' => 'Trading account already exists!']);
                }

                //Check username ability
                $req_data = [
                    'command' => 'Custom',
                    'data' => [
                        "sql" => "SELECT COUNT(*) AS check_un FROM copy_users WHERE username = '$username' AND id <> '$id'"
                    ]
                ];
                $result = json_decode($copy_mt->apiCall($req_data));
                if ($result->data[0]->check_un) {
                    return Response::json(['success' => false, 'message' => 'Username already exits!']);
                }

                //check account in slave
                $req_data2 = [
                    'command' => 'Custom',
                    'data' => [
                        "sql" => "SELECT COUNT(*) AS check_slave FROM copy_slaves WHERE slave = '$account'"
                    ]
                ];

                $result2 = json_decode($copy_mt->apiCall($req_data2));

                if ($result2->data[0]->check_slave) {
                    return Response::json(['success' => false, 'message' => 'Account already exits in slave!']);
                }

                //=========pam setting script start here===================
                $pamm = PammSetting::select()->first();

                // if ($pamm->pamm_requirement_status == 1) {
                //     $total_deposit = AllFunctionService::trader_total_deposit(auth()->user()->id);
                //     if ($pamm->minimum_deposit > $min_deposit || $pamm->minimum_deposit > $total_deposit) {
                //         return Response::json(['success' => false, 'message' => '<span style="color:red;">Minimum deposit should be $' . $pamm->minimum_deposit . '</span>']);
                //     }

                //     //pamm account limit check
                //     $req_data = [
                //         'command' => 'pammLimit',
                //     ];
                //     $count = json_decode($copy_mt->apiCall($req_data));

                //     if ($pamm->pamm_account_limit != 0) {
                //         if ($count > $pamm->pamm_account_limit) {
                //             return Response::json(['success' => false, 'message' => '<span style="white;">PAMM Limit Exceeded']);
                //         }
                //     }
                // }

                //profit share status
                if ($pamm->profit_share_status == 1) {
                    if ($share_profit != $pamm->profit_share_value) {
                        return Response::json(['success' => false, 'message' => '<span style="color:red;">Profit share value should be equal to ' . $pamm->profit_share_value . '%']);
                    }
                }
                // flexiable profit share status
                if ($pamm->flexible_profit_share_status == 1) {

                    if ($pamm->minimum_profit_share_value != 0 || $pamm->maximum_profit_share_value != 0) {
                        if ($share_profit < $pamm->minimum_profit_share_value || $share_profit > $pamm->maximum_profit_share_value) {
                            return Response::json(['success' => false, 'message' => '<span style="color:red;">Profit share value should be between ' . $pamm->minimum_profit_share_value . '%   to ' . $pamm->maximum_profit_share_value . '%']);
                        }
                    }
                }

                // ==============balance equity check==============
                $trading_account = TradingAccount::where('account_number', $account)->first();
                $response['success'] = false;
                if (strtolower($trading_account->platform) == 'mt4') {
                    $mt4api = new MT4API();
                    $data = array(
                        'command' => 'user_data_get',
                        'data' => array('account_id' => $trading_account->account_number),
                    );

                    $result = $mt4api->execute($data, $trading_account->client_type);

                    if ($result["success"]) {
                        $result1 = $result['data'];
                        $response['success'] = true;
                        $response['credit'] = 0;
                        $response['balance'] = $result1['balance'];
                        // $response['amount']  = ($request->search === 'balance') ? $result1['balance'] : $result1['equity'];
                        if ($response['balance'] < $pamm->minimum_account_balance) {
                            return Response::json(['success' => false, 'message' => "<span style='color:red;'>You don't have enough account balance!"]);
                        }
                    } else {
                        try {
                            return Response::json([
                                'success' => false,
                                'message' => $result['info']['message']
                            ]);
                        } catch (\Throwable $th) {
                            return Response::json([
                                'success' => false,
                                'message' => "Failed to check wallet balance!"
                            ]);
                        }
                    }
                } else {
                    $mt5_api = new Mt5WebApi();
                    $action = 'AccountGetMargin';

                    $data = array(
                        "Login" => $trading_account->account_number
                    );
                    $result = $mt5_api->execute($action, $data);
                    $mt5_api->Disconnect();

                    if (isset($result['success'])) {
                        if ($result['success']) {
                            $response['success'] = true;
                            $response['balance'] = $result['data']['Balance'];

                            // $response['amount']  = ($request->search === 'balance') ? $result['data']['Balance'] : $result['data']['Equity'];
                            if ($response['balance'] < $pamm->minimum_account_balance) {
                                return Response::json(['success' => false, 'message' => '<span style="color:red;">You dont have enough Trading account balance!']);
                            }
                        } else if (isset($result['error'])) {
                            $response['message'] = $result['error']['Description'];
                        } else {
                            $response = [
                                'success' => false,
                                'message' => $result['message']
                            ];
                        }
                    }
                }
                // ==============balance equity check==============


                //when manual pamm approved system active
                $check_pamm = PammRequest::select('account')->where('account', $account)->first();
                if (isset($check_pamm->account)) {
                    return Response::json(['success' => false, 'message' => '<span style="color:red;">Trading account already exists!']);
                }
                if ($pamm->manual_approve_pamm_reg == 1) {
                    $create = PammRequest::create([
                        'user_id' => auth()->user()->id,
                        'name' => $name,
                        'email' => $email,
                        'account' => $account,
                        'username' => $username,
                        'min_deposit' => $min_deposit,
                        'max_deposit' => $max_deposit,
                        'share_profit' => $share_profit,
                        'status' => 'P',
                    ]);
                    if ($create) {
                        return Response::json(['success' => true, 'message' => '<span style="color:green;">Your PAMM profile has been created successfully!']);
                    }
                }
                //============Script end here========================
                //===========balance equity check end============
                if (Response::json(['success' => true])) {
                    if ($id) {
                        $req_data = [
                            'command' => 'Custom',
                            'data' => [
                                "sql" => "INSERT INTO copy_users (name, email, username, account, min_deposit, max_deposit, share_profit, created_at) VALUES('$name', '$email', '$username', '$account', '$min_deposit', '$max_deposit', '$share_profit', '$date')"
                            ]
                        ];
                        $result = json_decode($copy_mt->apiCall($req_data));
                        //Check master exits
                        $req_data_x = [
                            'command' => 'Custom',
                            'data' => [
                                "sql" => "SELECT COUNT(*) AS check_master FROM copy_masters WHERE master = '$account'"
                            ]
                        ];

                        $result_me = json_decode($copy_mt->apiCall($req_data_x));

                        if ($result_me->data[0]->check_master < 1) {
                            $req_data2 = [
                                'command' => 'Custom',
                                'data' => [
                                    "sql" => "INSERT INTO copy_masters (master , created_at) VALUES('$account', '$date')"
                                ]
                            ];
                            $result_master = json_decode($copy_mt->apiCall($req_data2));
                        }

                        return Response::json(['success' => true, 'message' => '<span style="color:green;">Your PAMM profile has been created successfully!']);
                    } else {
                        $req_data = [
                            'command' => 'Custom',
                            'data' => [
                                "sql" => "UPDATE copy_users SET min_deposit = '$min_deposit', max_deposit = '$max_deposit', share_profit = '$share_profit' WHERE id = '$id'"
                            ]
                        ];
                        return Response::json(['success' => true, 'message' => '<span style="color:green;">Your PAMM profile has been updated successfully!']);
                    }


                    if ($result) {
                        return Response::json(['success' => true]);
                    } else {
                        return Response::json(['success' => false, 'message' => '<span style="color:green;">Unkown Error!']);
                    }
                }
            } else {
                return Response::json(['success' => false, 'message' => 'Account does not exit!']);
            }
        }
    }
    // get partial / some days of data
    public function partial_data(Request $request)
    {
        $sql = "SELECT copy_users.id,copy_users.account AS master_account, ";
        $sql .= " copy_users.name,copy_users.email,copy_users.username,copy_users.account,copy_users.min_deposit,copy_users.max_deposit,copy_users.share_profit,copy_users.created_at,";
        $sql .= "(SELECT COUNT(id) FROM copy_activities WHERE master=master_account AND action='copy' AND (DATE(copy_users.created_at)> now() - INTERVAL 14 DAY))AS total_copy, ";
        $sql .= "(SELECT COUNT(id) FROM copy_activities WHERE master=master_account AND action='uncopy' AND (DATE(copy_users.created_at)> now() - INTERVAL 14 DAY))AS total_uncopy,";
        $sql .= " (SELECT COUNT('id')AS copy FROM `copy_activities` WHERE (action ='copy'AND type='pamm') AND master = master_account AND DATE(created_at) = DATE(NOW()) AND (DATE(copy_users.created_at)> now() - INTERVAL 14 DAY))AS today_copy ";
        $sql .= " FROM copy_users LEFT JOIN copy_activities ON copy_users.account = copy_activities.master where copy_users.account =$request->ac AND (DATE(copy_users.created_at)> now() - INTERVAL 14 DAY)";

        $result = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => $sql
        ]));
        $result = isset($result->data) ? $result->data : [];
        return Response::json($result);
    }
    // get profit/loss gain/aciever
    public function profit_loss(Request $request)
    {
        $profit_loss = UserPammService::get_gain($request->ac, 14);

        $achiever = "";
        if ($profit_loss["profit"] > abs($profit_loss["loss"])) {
            $achiever = "High achiever";
        } elseif ($profit_loss["profit"] == abs($profit_loss["loss"])) {
            $achiever = "Mid achiever";
        } else {
            $achiever = "Low achiver";
        }
        $html_class = ($profit_loss['profit'] - $profit_loss['loss'] > 0) ? 'text-success' : 'text-danger';
        return Response::json([
            'achiever' => $achiever,
            'profit_loss' => $profit_loss,
            'gain' => $profit_loss['gain'],
            'html_class' => $html_class
        ]);
    }
}
