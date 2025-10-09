<?php

namespace App\Services;

use App\Models\IB;
use App\Models\User;

class UserPammService
{
    public static function  get_duration($open_time, $close_time)
    {
        // $time1 = "23:58";
        // $time2 = "01:00";
        $time1 = date("h:i", strtotime($open_time));
        $time2 = date("h:i", strtotime($close_time));
        $time1 = explode(':', $time1);
        $time2 = explode(':', $time2);
        $hours1 = $time1[0];
        $hours2 = $time2[0];
        $mins1 = $time1[1];
        $mins2 = $time2[1];
        $hours = $hours2 - $hours1;
        $mins = 0;
        if ($hours < 0) {
            $hours = 24 + $hours;
        }

        if ($mins2 >= $mins1) {
            $mins = $mins2 - $mins1;
        } else {
            $mins = ($mins2 + 60) - $mins1;
            $hours--;
        }

        if ($mins < 9) {
            $mins = str_pad($mins, 2, '0', STR_PAD_LEFT);
        }

        if ($hours < 9) {
            $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
        }

        return ($hours . ' h : ' . $mins . ' m');
    }
    // get total gail for pumm dashboard
    public static function get_gain($login, $duration = null,)
    {
        $cpy = new CopyApiService();
        $profit_sql = "";
        $loss_sql = "";
        if ($duration == null) {
            $profit_sql = 'SELECT SUM(Profit)as total_profit FROM `copy_trades` WHERE Profit > 0 AND Login =' . $login;
            $loss_sql = 'SELECT SUM(Profit)as total_loss FROM `copy_trades` WHERE Profit < 0 AND Login = ' . $login;
        } else {
            $profit_sql = 'SELECT SUM(Profit)as total_profit FROM `copy_trades` WHERE Profit > 0 AND Login =' . $login . 'AND (DATE(copy_trades.created_at)> now() - INTERVAL ' . $duration . ' DAY)';
            $loss_sql = 'SELECT SUM(Profit)as total_loss FROM `copy_trades` WHERE Profit < 0 AND Login = ' . $login . 'AND (DATE(copy_trades.created_at)> now() - INTERVAL ' . $duration . ' DAY)';
        }

        $data = [
            'command' => 'Custom',
            'data' => [
                'sql' => $profit_sql,
            ]
        ];
        $result = json_decode($cpy->apiCall($data));
        (!is_null($result)) ? $total_prfit = $result->data : $total_prfit = [];
        $total_prfit = isset($total_prfit[0]->total_profit) ? $total_prfit[0]->total_profit : 0;

        $data = [
            'command' => 'Custom',
            'data' => [
                'sql' => $loss_sql,
            ]
        ];
        $result = json_decode($cpy->apiCall($data));
        (!is_null($result)) ? $total_loss = $result->data : $total_loss = [];
        $total_loss = isset($total_loss[0]->total_loss) ? $total_loss[0]->total_loss : 0;

        $total_gain = ($total_prfit + $total_loss + (($total_prfit * $total_loss) / 100));

        $data = [
            'profit' => $total_prfit,
            'loss' => $total_loss,
            'gain' => round($total_gain, 2),
            'total' => ($total_prfit + abs($total_loss))
        ];
        return $data;
    }
    // get account details chart data
    public static function account_details_chart($ac, $duration)
    {
        $cpy = new CopyApiService();
        $allfunction = new AllFunctionService();
        // sql for filter by day, month, all
        $sql = "select date(created_at) AS x,count(id) AS y from copy_followers WHERE (master = " . $ac;
        if ($duration <= 30 && $duration != "") {

            $sql .=  " AND created_at> now() - INTERVAL $duration day";
        }

        if ($duration > 30) {
            $sql = "select DATE_FORMAT(created_at,'%Y-%m') AS x,count(id) AS y from copy_followers WHERE (master = " . $ac;
            if ($duration == "") {
                $m = ($duration / 30);
            } else {
                $m = 12;
            }
            $sql .=  " AND created_at > now() - INTERVAL $m months";
        }
        if ($duration == "") {
            $sql = "select DATE_FORMAT(created_at,'%Y-%m') AS x,count(id) AS y from copy_followers WHERE (master = " . $ac;
            $sql .=  "  ";
        }

        $sql .= ")";
        // return $sql;
        $data = [
            'command' => 'Custom',
            'data' => [
                'sql' => $sql,
            ]
        ];

        $result = json_decode($cpy->apiCall($data));
        (!is_null($result)) ? $has_follower = $result->data : $has_follower = [];
        $days = [];
        $dates = [];
        $followers = [];
        // chart data for 2week or 1 month
        if ($duration == 30 || $duration == 14) {
            if ($duration == 14) {
                $day = 14;
            } else {
                $day = 30;
            }

            for ($i = $day; $i >= 0; $i--) {
                $dates[] = date('d M y', strtotime("-$i days"));
                if (isset($has_follower[$i]) && ($has_follower[$i]->x === date('Y-m-d', strtotime("-$i days")))) {
                    $followers[] = $has_follower[$i]->y;
                } else {
                    $followers[] = 0;
                }
            }
        }
        // chart data for 3 months
        $db_date = [];
        foreach ($has_follower as $key => $value) {
            $db_date[$value->x] = $value->y;
        }
        
        if ($duration > 30 || $duration == "") {
            if ($duration == "") {
                $m = 12;
                if (count($db_date) > 12) {
                    $m = count($db_date);
                }
            } else {
                $m = (($duration / 30) - 1);
            }
            $z = date('m') - $m;
            $months = [];
            for ($z; $z < date('m') + 1; $z++) {
                if ($z !== 0) {
                    if ($z < 0) {
                        $month = $m + ($z + 1);
                    } else {
                        $month = $z;
                    }
                    $dates[] = date('M Y', strtotime('2022-' . $month . '-05'));
                    $days[] = date('Y-m', strtotime('2022-' . $month . '-05'));
                }
            }

            // return $days;
            for ($i = 0; $i < count($days); $i++) {
                if (array_key_exists($days[$i], $db_date)) {
                    $followers[] = $db_date[$days[$i]];
                } else {
                    $followers[] = 0;
                }
            }
        }
        return $data = [
            'dates' => $dates,
            'followers' => $followers
        ];
    }
}
