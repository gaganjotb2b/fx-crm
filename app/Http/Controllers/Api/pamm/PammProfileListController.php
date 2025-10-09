<?php

namespace App\Http\Controllers\Api\pamm;

use App\Services\CopyApiService;
use App\Http\Controllers\Controller;
use App\Models\TradingAccount;
use App\Models\Country;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class PammProfileListController extends Controller
{
    private $copy_api = "";
    public function __construct()
    {
        $this->copy_api = new CopyApiService();
    }

    public function PammProfileChartData($account)
    {
        // created_at >= CURDATE()-INTERVAL 30 DAY AND
        $sql_trades = "SELECT DATE(created_at)as trading_date, SUM(Profit) as Profit, SUM(Volume) as Volume FROM copy_trades WHERE (Login=$account)  GROUP BY DATE(created_at)";
        // $copy_trader_sql = "SELECT ct.Login as login ,ct.Volume as volume, ct.Profit as profit,ct.created_at as created_at, cm.master AS account FROM copy_trades AS ct JOIN copy_masters AS cm ON cm.master = ct.Login where cm.master = $account AND DATE_FORMAT(ct.created_at,'%Y-%m') = DATE_FORMAT(CURRENT_DATE,'%Y-%m')";
        $copy_req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $sql_trades
            ]
        ];

        $res = json_decode($this->copy_api->apiCall($copy_req_data));
        return $res->data;
    }


    public function checkCopyOrUncopy($ac)
    {
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

        $user_id = auth()->user()->id;

        $user_account = DB::table('trading_accounts')->select('account_number')->where('user_id', $user_id)->get();
        $all_accounts = [];
        foreach ($user_account as $value) {
            $all_accounts[] = $value;
        }

        $master_account = $copy_user->account;

        $req_data = [
            'command' => 'IsCopied',
            'data' => [
                "master" => $master_account,
                "slaves" => $all_accounts
            ]
        ];

        $result = json_decode($this->copy_api->apiCall($req_data));

        $data = $result->data;
        foreach ($data as $values) {
            $master =  $values->master;
            $slave =  $values->slave;
        }

        return (isset($slave)) ? $slave : '0';
    }

    // pamm profile list with update version
    public function pammProfileList(Request $request)
    {
        try {
            $sql = "SELECT cu.*, cm.*, ";
            $sql .= "(SELECT COUNT(*) FROM copy_slaves WHERE master = cu.account) AS total_slaves, ";
            $sql .= "(SELECT COUNT(*) FROM copy_activities WHERE master = cu.account AND action != 'uncopy') AS all_copiers, ";
            $sql .= "SUM(ct.Profit) AS gain ";
            $sql .= "FROM copy_users cu ";
            $sql .= "JOIN copy_masters cm ON cu.account = cm.master ";
            $sql .= "LEFT JOIN copy_trades ct ON cu.account = ct.Login ";
            $sql .= "GROUP BY cu.account, cm.master";

            $totalCountSql = "SELECT COUNT(*) AS total_count FROM copy_users cu JOIN copy_masters cm ON cu.account = cm.master";

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => $totalCountSql,
                ]
            ]));
            $totalCount = ($result->status && !empty($result->data)) ? $result->data[0]->total_count : 0;

            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => $sql
                ]
            ]));
            return Response::json([
                'status' => true,
                'total' => $totalCount,
                'data' => $result,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => true,
                'total' => $totalCount,
                'data' => $result,
            ]);
        }
    }
    // get last 12 months
    private function profits($account)
    {

        $copy_trades = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => [
                'sql' => "
                    SELECT
                        DATE_FORMAT(reference_months.month, '%b %Y') AS month,
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



        $data = isset($copy_trades->data) ? $copy_trades->data : [];

        $months = [];
        $profits = [];
        $losses = [];

        foreach ($data as $entry) {
            $months[] = $entry->month;
            $profits[] = $entry->profit;
            $losses[] = abs($entry->loss); // Taking the absolute value of losses
        }
        // <--------------data ready for dumy chart/ its will be remove in live-------------->
        if (strtolower(config('app.name')) === 'fxcrm') {
            $random_profit = $random_loss = [];
            $dumy_months = ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            for ($i = 0; $i < 11; $i++) {
                $random_profit[] = mt_rand() / mt_getrandmax();
                $random_loss[] = mt_rand() / mt_getrandmax();
            }
            return [
                'months' => $dumy_months,
                'profits' => $random_profit,
                'losses' => $random_loss,
            ];
        }
        return [
            'months' => $months,
            'profits' => $profits,
            'losses' => $losses,
        ];
    }
}
