<?php

namespace App\Http\Controllers\Api\pamm;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\CopySlave;
use App\Models\CopySymbol;
use App\Models\CopyTrade;
use App\Models\CopyUser;
use App\Models\Traders\PammSetting;
use App\Models\TradingAccount;
use App\Services\CopyApiService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\UTCDateTime;

class PammOverviewController extends Controller
{
    private $copy_api = "";
    public function __construct()
    {
        $this->copy_api = new CopyApiService();
    }
    // pamm account details
    public function pammAccountDetails(Request $request)
    {
        try {
            $account_info = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "SELECT *FROM copy_users WHERE account = $request->ac",
                ]
            ]));
            // return $account_info;
            $copy_trades = CopyTrade::where('Login', $request->ac);

            // Calculate total trades
            $total_trades = $copy_trades->count();

            // Calculate total volume
            $volume = 0;
            if ($total_trades != 0) {
                $volume = $copy_trades->sum('Volume') / 100;
            }

            $gain = $copy_trades->sum('Profit');

            // Calculate total profit (only positive profits)
            $profit = CopyTrade::where('Login', $request->ac)->where('Profit', '>', 0)->sum('Profit');

            // Calculate total loss (only negative profits)
            $loss = CopyTrade::where('Login', $request->ac)->where('Profit', '<', 0)->sum('Profit');

            // Get maximum and minimum profit
            $greatest_profit = $copy_trades->max('Profit');
            $greatest_loss = $copy_trades->min('Profit');
            $average_profit = $average_loss = 0;

            if ($total_trades != 0) {
                // Calculate average profit
                $average_profit = round(($profit / $total_trades), 2);
                // Calculate average loss (only negative profits)
                $average_loss = round(($loss / $total_trades), 2);
            }



            // Get best trade (highest profit)
            $best_trade = $copy_trades->orderBy('Profit', 'desc')->first();
            // get account leverage
            $trading_account = TradingAccount::where('account_number', $request->account)->first();
            // Calculate profit percentage
            $profit_percentage = ($profit / ($volume == 0 ? 1 : $volume)) * 100;

            // Calculate loss percentage
            $loss_percentage = (abs($loss) / ($volume == 0 ? 1 : $volume)) * 100;

            // Loss Percentage=( 
            //     Volume
            //     ∣Loss∣
            //     ​
            //      )×100

            // Profit Percentage=( 
            //     Volume
            //     Profit
            //     ​
            //      )×100
            $gain_percentage = 0;
            if ($profit + abs($loss) != 0) {
                $gain_percentage = round(($profit / ($profit + abs($loss))) / 100, 2);
            }

            return Response::json([
                'status' => true,
                'data' => [
                    'account_info' => $account_info ?? [],
                    'total_trades' => $total_trades,
                    'volume' => $volume,
                    'profit' => $profit,
                    'loss' => $loss,
                    'gain_percentage' => $gain_percentage,
                    'greatest_profit' => $greatest_profit,
                    'greatest_loss' => $greatest_loss,
                    'average_profit' => $average_profit,
                    'average_loss' => $average_loss,
                    'best_trade' => $best_trade,
                    'profit_percentage' => $profit_percentage,
                    'loss_percentage' => $loss_percentage,
                    'leverage' => ($trading_account) ? '1:' . $trading_account->leverage : '0:0',
                ]
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'data' => [
                    'account_info' => [],
                    'total_trades' => "",
                    'volume' => "",
                    'profit' => "",
                    'loss' => "",
                    'greatest_profit' => "",
                    'greatest_loss' => "",
                    'average_profit' => "",
                    'average_loss' => "",
                    'best_trade' => "",
                    'leverage' => ""
                ]
            ]);
        }
    }
    // open order update version
    public function openOrderReport(Request $request)
    {
        try {
            // $result = CopyTrade::where('Login', $request->ac);
            $result = CopyTrade::where('CloseTime', '1970-01-01 00:00:00')->where('Login', $request->ac);
            // Start search
            if (isset($request->search['value'])) {
                $search =  $request->search['value'];
                $result->where(function ($q) use ($search) {
                    $q->where('Order', $search)
                        ->orWhere('Login', $search)
                        ->orWhere('OpentTime', 'LIKE', $search . '%')
                        ->orWhere('Symbol', 'LIKE', '%' . $search . '%')
                        ->orWhere('Volume', (float)$search)
                        ->orWhere('OpenPrice', 'LIKE', '%' . $search . '%');
                });
            }


            $result = $result->paginate($request->input('per_page', 10));
            return Response::json([
                'status'    => true,
                'data'   => $result
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return 'Deails loading failed';
        }
    }
    // close order upadate version
    public function closeOrderReport(Request $request)
    {
        try {
            // $result = CopyTrade::where('Login', $request->ac);
            $result = CopyTrade::whereNot('CloseTime', '1970-01-01 00:00:00')->where('Login', $request->ac);

            // Start search
            if (isset($request->search['value'])) {
                $search =  $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('Order', $search)
                        ->orWhere('Login', $search)
                        ->orWhere('OpentTime', 'LIKE', $search . '%')
                        ->orWhere('Symbol', 'LIKE', '%' . $search . '%')
                        ->orWhere('Volume', (float)$search)
                        ->orWhere('OpenPrice', 'LIKE', '%' . $search . '%');
                });
            }


            $result = $result->paginate($request->input('per_page', 10));
            return Response::json([
                'status'    => true,
                'data'   => $result
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return 'Deails loading failed';
        }
    }

    // get data for monthly line chart
    public function monthlyLineChart(Request $request)
    {
        try {
            $account = (int)$request->ac;
            $month = [];
            $copy_trades = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "
                        SELECT
                            DATE_FORMAT(reference_months.month, '%b %Y') AS month,
                            COUNT(copy_trades.id) AS total_trades,
                            SUM(CASE WHEN copy_trades.Profit > 0 THEN copy_trades.Profit ELSE 0 END) AS profit,
                            SUM(CASE WHEN copy_trades.Profit < 0 THEN copy_trades.Profit ELSE 0 END) AS loss
                        FROM
                            (
                                SELECT DATE_SUB(NOW(), INTERVAL n MONTH) AS month
                                FROM (
                                    SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
                                    UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                    UNION SELECT 10 UNION SELECT 11
                                ) AS numbers
                            ) AS reference_months
                        LEFT JOIN copy_trades ON DATE_FORMAT(reference_months.month, '%Y-%m') = DATE_FORMAT(copy_trades.created_at, '%Y-%m') AND copy_trades.Login = $account
                        GROUP BY DATE_FORMAT(reference_months.month, '%b %Y')
                        ORDER BY DATE_FORMAT(reference_months.month, '%Y-%m')
                    "
                ]
            ]));
            $copy_trade = isset($copy_trades->data) ? $copy_trades->data : [];

            // last 12 months slave
            $copy_slaves = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "
                        SELECT
                            DATE_FORMAT(reference_months.month, '%b %Y') AS month,
                            COUNT(copy_slaves.id) AS total_slaves
                        FROM
                            (
                                SELECT DATE_SUB(NOW(), INTERVAL n MONTH) AS month
                                FROM (
                                    SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
                                    UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                    UNION SELECT 10 UNION SELECT 11
                                ) AS numbers
                            ) AS reference_months
                        LEFT JOIN copy_slaves ON DATE_FORMAT(reference_months.month, '%Y-%m') = DATE_FORMAT(copy_slaves.created_at, '%Y-%m') AND copy_slaves.master = $account
                        GROUP BY DATE_FORMAT(reference_months.month, '%b %Y')
                        ORDER BY DATE_FORMAT(reference_months.month, '%Y-%m')
                    "
                ]
            ]));

            $copy_slave = isset($copy_slaves->data) ? $copy_slaves->data : [];
            $trades = $slaves = [];
            // seperate trade array
            foreach ($copy_trade as $value) {
                array_push($trades, $value->total_trades);
            }
            // seperate slave array
            foreach ($copy_slave as $value) {
                array_push($month, $value->month);
                array_push($slaves, $value->total_slaves);
            }
            // for excrm demo
            if (($account === 97900159 || $account == 98831808 || $account == 98832171) && strtolower(config('app.name')) === 'fxcrm') {
                $trades = [20, 35, 50, 40, 300, 220, 500, 250, 400, 230, 500, 400];
                $slaves = [20, 25, 30, 90, 40, 140, 290, 290, 340, 230, 400, 300];
            }
            return response()->json([
                'status' => true,
                'data' => [
                    'trade_per_month' => $trades,
                    'copier_per_month' => $slaves,
                    'months' => $month
                ]
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'labels' => ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                'chartData' => [20, 35, 50, 40, 300, 220, 500, 250, 400, 230, 500],
                'backgroundColor' => ['#2152ff', '#3A416F', '#f53939', '#a8b8d8', '#cb0c9f']
            ]);
        }
    }
    // get data for daily line chart
    public function dailyLineChart(Request $request)
    {
        try {
            $account = (int) $request->ac;
            $days = [];
            $trades = [];
            $slaves = [];

            // Fetch trade data for the last 30 days
            $copy_trades = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "
                    SELECT
                        DATE_FORMAT(reference_days.day, '%d %b %Y') AS day,
                        COUNT(copy_trades.id) AS total_trades,
                        SUM(CASE WHEN copy_trades.Profit > 0 THEN copy_trades.Profit ELSE 0 END) AS profit,
                        SUM(CASE WHEN copy_trades.Profit < 0 THEN copy_trades.Profit ELSE 0 END) AS loss
                    FROM
                        (
                            SELECT CURDATE() - INTERVAL n DAY AS day
                            FROM (
                                SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
                                UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14
                                UNION SELECT 15 UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19
                                UNION SELECT 20 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24
                                UNION SELECT 25 UNION SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29
                            ) AS numbers
                        ) AS reference_days
                    LEFT JOIN copy_trades ON DATE_FORMAT(reference_days.day, '%Y-%m-%d') = DATE_FORMAT(copy_trades.created_at, '%Y-%m-%d') AND copy_trades.Login = $account
                    GROUP BY DATE_FORMAT(reference_days.day, '%d %b %Y')
                    ORDER BY DATE_FORMAT(reference_days.day, '%Y-%m-%d')
                "
                ]
            ]));

            $copy_trade_data = isset($copy_trades->data) ? $copy_trades->data : [];

            // Fetch slave data for the last 30 days
            $copy_slaves = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "
                    SELECT
                        DATE_FORMAT(reference_days.day, '%d %b %Y') AS day,
                        COUNT(copy_slaves.id) AS total_slaves
                    FROM
                        (
                            SELECT CURDATE() - INTERVAL n DAY AS day
                            FROM (
                                SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
                                UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14
                                UNION SELECT 15 UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19
                                UNION SELECT 20 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24
                                UNION SELECT 25 UNION SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29
                            ) AS numbers
                        ) AS reference_days
                    LEFT JOIN copy_slaves ON DATE_FORMAT(reference_days.day, '%Y-%m-%d') = DATE_FORMAT(copy_slaves.created_at, '%Y-%m-%d') AND copy_slaves.master = $account
                    GROUP BY DATE_FORMAT(reference_days.day, '%d %b %Y')
                    ORDER BY DATE_FORMAT(reference_days.day, '%Y-%m-%d')
                "
                ]
            ]));

            $copy_slave_data = isset($copy_slaves->data) ? $copy_slaves->data : [];

            // Separate trade and slave data
            foreach ($copy_trade_data as $value) {
                array_push($trades, $value->total_trades);
                array_push($days, $value->day);
            }

            foreach ($copy_slave_data as $value) {
                array_push($slaves, $value->total_slaves);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'trade_per_day' => $trades,
                    'copier_per_day' => $slaves,
                    'days' => $days
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while generating the chart data.',
                'error' => $th->getMessage()
            ]);
        }
    }
    // get data for hourly line chart
    public function hourlyLineChart(Request $request)
    {
        try {
            $account = (int) $request->ac;
            $hours = [];
            $trades = [];
            $slaves = [];
            $profits = [];
            $losses = [];

            // Fetch trade data for the last 24 hours
            $copy_trades = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "
                        SELECT
                            DATE_FORMAT(reference_hours.hour, '%H:00') AS hour,
                            COUNT(copy_trades.id) AS total_trades,
                            SUM(CASE WHEN copy_trades.Profit > 0 THEN copy_trades.Profit ELSE 0 END) AS profit,
                            SUM(CASE WHEN copy_trades.Profit < 0 THEN copy_trades.Profit ELSE 0 END) AS loss
                        FROM
                            (
                                SELECT DATE_SUB(NOW(), INTERVAL n HOUR) AS hour
                                FROM (
                                    SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
                                    UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                    UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14
                                    UNION SELECT 15 UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19
                                    UNION SELECT 20 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23
                                ) AS numbers
                            ) AS reference_hours
                        LEFT JOIN copy_trades ON DATE_FORMAT(reference_hours.hour, '%Y-%m-%d %H') = DATE_FORMAT(copy_trades.created_at, '%Y-%m-%d %H') AND copy_trades.Login = $account
                        GROUP BY DATE_FORMAT(reference_hours.hour, '%Y-%m-%d %H')
                        ORDER BY DATE_FORMAT(reference_hours.hour, '%Y-%m-%d %H')
                    "
                ]
            ]));

            $copy_trade_data = isset($copy_trades->data) ? $copy_trades->data : [];

            // Fetch slave data for the last 24 hours
            $copy_slaves = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "
                        SELECT
                            DATE_FORMAT(reference_hours.hour, '%H:00') AS hour,
                            COUNT(copy_slaves.id) AS total_slaves
                        FROM
                            (
                                SELECT DATE_SUB(NOW(), INTERVAL n HOUR) AS hour
                                FROM (
                                    SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
                                    UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                    UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14
                                    UNION SELECT 15 UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19
                                    UNION SELECT 20 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23
                                ) AS numbers
                            ) AS reference_hours
                        LEFT JOIN copy_slaves ON DATE_FORMAT(reference_hours.hour, '%Y-%m-%d %H') = DATE_FORMAT(copy_slaves.created_at, '%Y-%m-%d %H') AND copy_slaves.master = $account
                        GROUP BY DATE_FORMAT(reference_hours.hour, '%Y-%m-%d %H')
                        ORDER BY DATE_FORMAT(reference_hours.hour, '%Y-%m-%d %H')
                    "
                ]
            ]));

            $copy_slave_data = isset($copy_slaves->data) ? $copy_slaves->data : [];

            // Separate trade and slave data
            foreach ($copy_trade_data as $value) {
                array_push($trades, $value->total_trades);
                array_push($profits, $value->profit);
                array_push($losses, $value->loss);
                array_push($hours, $value->hour);
            }

            foreach ($copy_slave_data as $value) {
                array_push($slaves, $value->total_slaves);
            }

            // Calculate the total profit and total volume
            $total_profit = array_sum($profits);
            $total_loss = array_sum($losses);
            $total_volume = array_sum($trades); // Assuming each trade contributes to the volume

            // Calculate the gain percentage
            $gain_percentage = ($total_volume > 0) ? ($total_profit / $total_volume) * 100 : 0;

            // Calculate the loss percentage
            $loss_percentage = ($total_volume > 0) ? (abs($total_loss) / $total_volume) * 100 : 0;

            return response()->json([
                'status' => true,
                'data' => [
                    'trade_per_hour' => $trades,
                    'copier_per_hour' => $slaves,
                    'hours' => $hours,
                    'gain_percentage' => $gain_percentage,
                    'loss_percentage' => $loss_percentage
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while generating the chart data.',
                'error' => $th->getMessage()
            ]);
        }
    }

    // get symbols
    public function getCopySymbols(Request $request)
    {
        // return CopySymbol::all();
        try {
            $result = CopySymbol::where('visible', 'visible')->select('symbol')->get();
            return Response::json([
                'status'    => true,
                'data'   => $result
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status'    => false,
                'data'   => []
            ]);
        }
    }

    // add a slave account to master
    // copy master as slave
    public function copyMaster(Request $request)
    {
        try {
            $ruls = [
                'account' => 'required|numeric',
                'master_account' => 'required|numeric',
                'symbol' => 'required',
                'max_trade' => 'required|numeric',
                'max_volume' => 'required|numeric',
                'min_volume' => 'required|numeric',
                'allocation' => 'required|numeric',
            ];
            $validator = Validator::make($request->all(), $ruls);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // check account available or not
            $trading_account = TradingAccount::where('account_number', $request->account)->first();
            if ($trading_account) {
                //GET master settings
                $master_settings = json_decode($this->copy_api->apiCall([
                    'command' => 'Custom',
                    'data' => [
                        'sql' => "SELECT * FROM copy_users WHERE account = '$request->master_account'",
                    ]
                ]));
                $master_settings = isset($master_settings->data) ? $master_settings->data : [];
                if (empty($master_settings)) {
                    return Response::json([
                        'status' => false,
                        'message' => 'Settings not loaded currntly, plase try again later'
                    ]);
                }
                // check min deposit
                $total_deposit = InternalTransfer::where('account_id', $trading_account->id)->sum('amount');
                if ($master_settings[0]->min_deposit != 0 && $master_settings[0]->min_deposit < $total_deposit) {
                    return Response::json([
                        'status' => false,
                        'message' => 'You can not copy this master account, for copy this account you need minimum ' . $master_settings[0]->min_deposit . ' $ deposit',
                    ]);
                }
                // check max deposit
                if ($master_settings[0]->max_deposit != 0 && $total_deposit > $master_settings[0]->max_deposit) {
                    return Response::json([
                        'status' => false,
                        'message' => 'You can not copy this master account, your total deposit should less than ' . $master_settings[0]->max_deposit . ' $ ',
                    ]);
                }
                // check master from copy slave
                $pamm_settings = PammSetting::select()->first();
                if (isset($pamm_settings[0]->pamm_requirement_status) && $pamm_settings[0]->pamm_requirement_status == 1) {
                    $master_count = json_decode($this->copy_api->apiCall([
                        'command' => 'CountMaster',
                        'data' => [
                            'master' => $request->master_account,
                            'slave' => $request->account
                        ]
                    ]));
                    if ($pamm_settings[0]->master_limit != 0 && $master_count->master >= $pamm_settings[0]->master_limit) {
                        return Response::json([
                            'success' => false,
                            'message' => 'Master Limit Exceeded'
                        ]);
                    }
                    if ($pamm_settings[0]->slave_limit != 0) {
                        if ($master_count->copy_slave >= $pamm_settings[0]->slave_limit) {
                            return Response::json([
                                'success' => false,
                                'message' => 'Slave Limit Exceeded'
                            ]);
                        }
                    }
                }
                // sending request to API
                $result = json_decode($this->copy_api->apiCall([
                    'command' => 'addSlave',
                    'data' => [
                        'master' => $request->master_account,
                        'slave' => $request->account,
                        'type' => 'pamm',
                        'allocation' => $request->allocation,
                        'max_number_of_trade' => $request->max_trade,
                        'max_trade_volume' => $request->max_volume,
                        'min_trade_volume' => $request->min_volume,
                        'symbols' => $request->symbol
                    ]
                ]));
                if ($result->status === true) {
                    return Response::json([
                        'status' => true,
                        'message' => "Congratulations! You successfully copy this trade",
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'messege' => $result->message,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Account not in our system'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
}
