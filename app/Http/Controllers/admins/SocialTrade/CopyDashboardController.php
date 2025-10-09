<?php

namespace App\Http\Controllers\admins\SocialTrade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CopyApiService;
use Illuminate\Support\Facades\Response;

class CopyDashboardController extends Controller
{


    public function copyDashboard(Request $request)
    {
        //total trades
        $copy_mt5 = new CopyApiService();
        $req_data = [
            'command' => 'Custom',
            'data' => ['sql' => "SELECT COUNT(*) AS total_trades FROM copy_trades WHERE copy_of <> 0"]
        ];

        $ttc = json_decode($copy_mt5->apiCall($req_data));

        $req_data = [
            'command' => 'Custom',
            'data' => ['sql' => "SELECT COUNT(*) AS total_trades FROM copy_trades WHERE copy_of <> 0"]
        ];

        $ttcc = json_decode($copy_mt5->apiCall($req_data));

        //total profit
        $req_data = [
            'command' => 'Custom',
            'data' => ['sql' => "SELECT SUM(Profit) AS total_profit FROM copy_trades WHERE copy_of <> 0"]
        ];

        $tpc = json_decode($copy_mt5->apiCall($req_data));

        //total master
        $req_data = [
            'command' => 'Custom',
            'data' => ['sql' => "SELECT COUNT(*) AS total_masters FROM copy_masters"]
        ];

        $tmc = json_decode($copy_mt5->apiCall($req_data));

        //total slaves
        $req_data = [
            'command' => 'Custom',
            'data' => ['sql' => "SELECT COUNT(*) AS total_slaves FROM copy_slaves"]
        ];

        $tsc = json_decode($copy_mt5->apiCall($req_data));

        //chart code here
        $chart_others_sql = "
            SELECT 
              SUM(CASE MONTH(ct.OpenTime) WHEN 1 THEN ct.Volume ELSE 0 END) AS 'Jan',
              SUM(CASE MONTH(ct.OpenTime) WHEN 2 THEN ct.Volume ELSE 0 END) AS 'Feb',
              SUM(CASE MONTH(ct.OpenTime) WHEN 3 THEN ct.Volume ELSE 0 END) AS 'Mar',
              SUM(CASE MONTH(ct.OpenTime) WHEN 4 THEN ct.Volume ELSE 0 END) AS 'Apr',
              SUM(CASE MONTH(ct.OpenTime) WHEN 5 THEN ct.Volume ELSE 0 END) AS 'May',
              SUM(CASE MONTH(ct.OpenTime) WHEN 6 THEN ct.Volume ELSE 0 END) AS 'Jun',
              SUM(CASE MONTH(ct.OpenTime) WHEN 7 THEN ct.Volume ELSE 0 END) AS 'Jul',
              SUM(CASE MONTH(ct.OpenTime) WHEN 8 THEN ct.Volume ELSE 0 END) AS 'Aug',
              SUM(CASE MONTH(ct.OpenTime) WHEN 9 THEN ct.Volume ELSE 0 END) AS 'Sep',
              SUM(CASE MONTH(ct.OpenTime) WHEN 10 THEN ct.Volume ELSE 0 END) AS 'Oct',
              SUM(CASE MONTH(ct.OpenTime) WHEN 11 THEN ct.Volume ELSE 0 END) AS 'Nov',
              SUM(CASE MONTH(ct.OpenTime) WHEN 12 THEN ct.Volume ELSE 0 END) AS 'Dec'
            FROM copy_trades ct
            LEFT OUTER JOIN copy_masters cm ON ct.Login = cm.master
            LEFT OUTER JOIN copy_slaves cs ON ct.Login = cs.slave
            WHERE (ct.Type = '0' OR ct.Type = '1') AND 
                cm.id is null AND cs.id is null AND
                ct.OpenTime BETWEEN date_sub(now(),INTERVAL 1 YEAR) and now()
        ";

        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $chart_others_sql
            ]
        ];

        $chart_others_res = json_decode($copy_mt5->apiCall($req_data));


        $cart_slave_sql = "
            SELECT 
              SUM(CASE MONTH(ct.OpenTime) WHEN 1 THEN ct.Volume ELSE 0 END) AS 'Jan',
              SUM(CASE MONTH(ct.OpenTime) WHEN 2 THEN ct.Volume ELSE 0 END) AS 'Feb',
              SUM(CASE MONTH(ct.OpenTime) WHEN 3 THEN ct.Volume ELSE 0 END) AS 'Mar',
              SUM(CASE MONTH(ct.OpenTime) WHEN 4 THEN ct.Volume ELSE 0 END) AS 'Apr',
              SUM(CASE MONTH(ct.OpenTime) WHEN 5 THEN ct.Volume ELSE 0 END) AS 'May',
              SUM(CASE MONTH(ct.OpenTime) WHEN 6 THEN ct.Volume ELSE 0 END) AS 'Jun',
              SUM(CASE MONTH(ct.OpenTime) WHEN 7 THEN ct.Volume ELSE 0 END) AS 'Jul',
              SUM(CASE MONTH(ct.OpenTime) WHEN 8 THEN ct.Volume ELSE 0 END) AS 'Aug',
              SUM(CASE MONTH(ct.OpenTime) WHEN 9 THEN ct.Volume ELSE 0 END) AS 'Sep',
              SUM(CASE MONTH(ct.OpenTime) WHEN 10 THEN ct.Volume ELSE 0 END) AS 'Oct',
              SUM(CASE MONTH(ct.OpenTime) WHEN 11 THEN ct.Volume ELSE 0 END) AS 'Nov',
              SUM(CASE MONTH(ct.OpenTime) WHEN 12 THEN ct.Volume ELSE 0 END) AS 'Dec'
            FROM copy_trades ct
            JOIN copy_slaves cs ON ct.Login = cs.slave
            WHERE (ct.Type = '0' OR ct.Type = '1') AND ct.copy_of != 0 AND
              ct.OpenTime BETWEEN date_sub(now(),INTERVAL 1 YEAR) and now()
        ";

        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $cart_slave_sql
            ]
        ];

        $chart_slave_res = json_decode($copy_mt5->apiCall($req_data));


        $chart_master_sql = "
            SELECT 
              SUM(CASE MONTH(ct.OpenTime) WHEN 1 THEN ct.Volume ELSE 0 END) AS 'Jan',
              SUM(CASE MONTH(ct.OpenTime) WHEN 2 THEN ct.Volume ELSE 0 END) AS 'Feb',
              SUM(CASE MONTH(ct.OpenTime) WHEN 3 THEN ct.Volume ELSE 0 END) AS 'Mar',
              SUM(CASE MONTH(ct.OpenTime) WHEN 4 THEN ct.Volume ELSE 0 END) AS 'Apr',
              SUM(CASE MONTH(ct.OpenTime) WHEN 5 THEN ct.Volume ELSE 0 END) AS 'May',
              SUM(CASE MONTH(ct.OpenTime) WHEN 6 THEN ct.Volume ELSE 0 END) AS 'Jun',
              SUM(CASE MONTH(ct.OpenTime) WHEN 7 THEN ct.Volume ELSE 0 END) AS 'Jul',
              SUM(CASE MONTH(ct.OpenTime) WHEN 8 THEN ct.Volume ELSE 0 END) AS 'Aug',
              SUM(CASE MONTH(ct.OpenTime) WHEN 9 THEN ct.Volume ELSE 0 END) AS 'Sep',
              SUM(CASE MONTH(ct.OpenTime) WHEN 10 THEN ct.Volume ELSE 0 END) AS 'Oct',
              SUM(CASE MONTH(ct.OpenTime) WHEN 11 THEN ct.Volume ELSE 0 END) AS 'Nov',
              SUM(CASE MONTH(ct.OpenTime) WHEN 12 THEN ct.Volume ELSE 0 END) AS 'Dec'
            FROM copy_trades ct
            JOIN copy_masters cm ON ct.Login = cm.master
            WHERE (ct.Type = '0' OR ct.Type = '1') AND
              ct.OpenTime BETWEEN date_sub(now(),INTERVAL 1 YEAR) and now()
        ";

        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $chart_master_sql
            ]
        ];

        $chart_master_res = json_decode($copy_mt5->apiCall($req_data));

        $curdate =  date('M');
        $new_data_ot = [];
        $old_data_ot = [];
        $new_data_st = [];
        $old_data_st = [];
        $new_data_mt = [];
        $old_data_mt = [];
        $all_val = [];
        $found = 0;
        $ci = 0;
        
        // if (is_string($chart_others_res)) {
        //     $chart_others_res = json_decode($chart_others_res);
        // }
        // var_dump($chart_others_res);die;
        //code modify when api not connected
        if ($chart_others_res && isset($chart_others_res->data[0])) {
            foreach ($chart_others_res->data[0] as $key => $ot) {
                $ot = (int) $ot; // Convert string to integer
        
                $st = isset($chart_slave_res->data[0]->$key) ? (int) $chart_slave_res->data[0]->$key : 0;
                $mt = isset($chart_master_res->data[0]->$key) ? (int) $chart_master_res->data[0]->$key : 0;
        
                $all_val[] = $ot / 10000;
                $all_val[] = $st / 10000;
                $all_val[] = $mt / 10000;
        
                if ($found == 0) {
                    $new_data_ot[] = ["$key", $ot / 10000];
                    $new_data_st[] = ["$key", $st / 10000];
                    $new_data_mt[] = ["$key", $mt / 10000];
                    if ($curdate == $key) {
                        $found = 1;
                    }
                } else {
                    $old_data_ot[] = ["$key", $ot / 10000];
                    $old_data_st[] = ["$key", $st / 10000];
                    $old_data_mt[] = ["$key", $mt / 10000];
                }
            }
        } else {
            $all_val[] = 0;
        }


        $chart_data_ot = array_merge($old_data_ot, $new_data_ot);
        $chart_data_st = array_merge($old_data_st, $new_data_st);
        $chart_data_mt = array_merge($old_data_mt, $new_data_mt);
        // dd($chart_data_ot);
        $max_val = max($all_val);

        $chart_slave_sql = "
            SELECT 
                SUM(ct.Volume) as total_slave_trade_vol
            FROM copy_trades ct
            WHERE (ct.Type = '0' OR ct.Type = '1') AND ct.copy_of != 0
        ";

        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $chart_slave_sql
            ]
        ];

        $tt_slave_res = json_decode($copy_mt5->apiCall($req_data));

        if (isset($tt_slave_res) && isset($tt_slave_res->data[0])) {
            $total_slave_trade_vol = $tt_slave_res->data[0]->total_slave_trade_vol;
        } else {
            $total_slave_trade_vol = 0;
        }


        $chart_master_sql = "
            SELECT 
                SUM(ct.Volume) as total_master_trade_vol
            FROM copy_trades ct
            JOIN copy_masters cm ON ct.Login = cm.master
            WHERE (ct.Type = '0' OR ct.Type = '1')
        ";

        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $chart_master_sql
            ]
        ];

        $tt_master_res = json_decode($copy_mt5->apiCall($req_data));

        if (isset($tt_master_res) && isset($tt_master_res->data[0])) {
            $total_master_trade_vol = $tt_master_res->data[0]->total_master_trade_vol;
        } else {
            $total_master_trade_vol = 0;
        }

        $total_pie_vol = $total_slave_trade_vol + $total_master_trade_vol;
        //$pie_ot = round(($total_other_trade_vol / $total_pie_vol) * 100);
        $pie_st = ($total_slave_trade_vol > 0) ? round(($total_slave_trade_vol / $total_pie_vol) * 10000) : 0;
        $pie_mt = ($total_master_trade_vol > 0) ? round(($total_master_trade_vol / $total_pie_vol) * 10000) : 0;


        return view('admins.socialTrade.copy-dashboard', [
            'ttc' => $ttc, 'ttcc' => $ttcc, 'tpc' => $tpc, 'tmc' => $tmc, 'tsc' => $tsc,

            'chart_data_ot' => json_encode($chart_data_ot),
            'chart_data_st' => json_encode($chart_data_st),
            'chart_data_mt' => json_encode($chart_data_mt),
            'max_val' => $max_val,
            'pie_st' => $pie_st,
            'pie_mt' => $pie_mt
        ]);
    }

    public function copyDashboardProcess(Request $request)
    {
        $copy_mt5 = new CopyApiService('mt5');
        $req_data = [
            'command' => 'Custom',
            'data' => ['sql' => "SELECT * FROM copy_trades WHERE copy_of <> 0 AND (Type = 0 OR Type = 1) ORDER BY copy_trades.Order DESC LIMIT 10"]
        ];
        $live_trades = json_decode($copy_mt5->apiCall($req_data));
        if (is_string($live_trades)) {
            $live_trades = json_decode($live_trades);
        }
        if ($live_trades === null) {
            $data = array();
            $output['data'] = $data;
            return $output;
        }
        $data = array();
        $i = 0;
        foreach ($live_trades->data as  $value) {
            $data[$i]["ticket"]         = $value->Order;
            $data[$i]["login"]          = $value->Login;
            $data[$i]["type"]           = ($value->Type == 0) ? 'BUY' : 'SELL';
            $data[$i]["symbol"]         = $value->Symbol;
            $data[$i]["volume"]         = $value->Volume / 10000;
            $data[$i]["open_time"]      = $value->OpenTime;
            $data[$i]["close_time"]     = isset($value->CloseTime) ? $value->CloseTime:'---';
            $data[$i]["comment"]        = $value->Comment;
            $i++;
        }
        $output['data'] = $data;
        return $output;
    }
}
