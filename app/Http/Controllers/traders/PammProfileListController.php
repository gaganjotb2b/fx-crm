<?php

namespace App\Http\Controllers\traders;

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
        $this->middleware(AllFunctionService::access('pamm_profile', 'trader'));
        $this->middleware(AllFunctionService::access('pamm', 'trader'));
    }
    public function userPammProfile(Request $request)
    {
        // return view('traders.pamm.pamm-profile');
        return view('traders.pamm.pamm-list-sql-update');
    }

    public function PammProfileList(Request $request)
    {
        // return $this->PammProfileChartData(91899620);
        $page = 1;
        $rpp = $request->rpp;
        $orderBy = "cu.id ASC";

        if (!empty($request->page)) {
            $page = $request->page;
        }

        $start = ($page - 1) * $rpp;
        if ($start < 0) $start = 0;

        $sql = "SELECT cu.*, SUM(ct.Profit) AS total_filter_profit,ct.*,";
        $sql .= "(SELECT SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) AS total_profit FROM copy_trades WHERE Login = cu.account AND Deal != 0) AS total_profit, ";
        $sql .= "(SELECT SUM(CASE WHEN Profit < 0 THEN Profit ELSE 0 END) AS total_lose FROM copy_trades WHERE Login = cu.account AND Deal != 0) AS total_lose, ";
        $sql .= "(SELECT COUNT(*) AS total_slaves FROM copy_slaves WHERE master = cu.account) AS total_slaves, ";
        $sql .= "(SELECT COUNT(*) AS all_copiers FROM copy_activities WHERE master = cu.account AND action != 'uncopy') AS all_copiers ";
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

            // check copy or uncopy 
            $data[$key]['copy_uncopy'] = $this->checkCopyOrUncopy($row->account);

            // chart data
            $fetchChartData = $this->PammProfileChartData($row->account);
            $chartProfit = array();
            $chartVolume = array();
            $chartDate = array();
            // foreach ($fetchChartData as $index => $row) {
            //     $chartVolume[$index] = $row->volume;
            //     $chartProfit[$index] = $row->profit;
            //     $chartDate[$index] = $row->created_at;
            // }
            $data[$key]['chart_volume'] = $chartVolume;
            $data[$key]['chart_profit'] = $chartProfit;
            $data[$key]['chart_date'] = $chartDate;
        }

        return Response::json([
            'draw' => $request->draw,
            'data' => $data,
            'page' => $page,
            'total' => $total,
            'chartdata' => $this->PammProfileChartData(97900158)
        ]);
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
    public function pamm_list_version2(Request $request)
    {
        try {
            $orderBy = "cu.id DESC";
            $sql = "SELECT cu.id, cu.name, cu.username, cu.email, cu.account, cu.min_deposit, cu.created_at, 
                        SUM(ct.Profit) AS gain, 
                        (SELECT COUNT(*) FROM copy_slaves WHERE master = cu.account) AS total_slaves, 
                        (SELECT COUNT(*) FROM copy_activities WHERE master = cu.account AND action != 'uncopy') AS all_copiers 
                    FROM copy_users cu";
            $sql_join = " LEFT JOIN copy_trades ct ON cu.account = ct.Login ";
            $sql_where = "";

            // Filter by deposit
            if ($request->min_investment != "") {
                $sql_where .= " cu.min_deposit <= $request->min_investment ";
            }

            // Filter by trader info (name, username, email)
            if ($request->trader_info != "") {
                $sql_where .= ($sql_where != "") ? " AND " : "";
                $sql_where .= " (cu.name LIKE '%$request->trader_info%' OR cu.username LIKE '%$request->trader_info%' OR cu.email LIKE '%$request->trader_info%') ";
            }

            // Filter by duration
            if ($request->duration != "") {
                $sql_where .= ($sql_where != "") ? " AND " : "";
                $sql_where .= " DATE(ct.OpenTime) >= CURDATE() - INTERVAL $request->duration DAY ";
            }

            // Filter by gainer or total_slaves
            if ($request->show_first != "") {
                if (strtolower($request->show_first) === 'gainer') {
                    $orderBy = "gain DESC";
                } else {
                    $orderBy = "total_slaves DESC";
                }
            }

            // SQL limit and grouping
            $limit_sql = " GROUP BY cu.id, cu.name, cu.username, cu.email, cu.account, cu.min_deposit, cu.created_at 
                        ORDER BY $orderBy 
                        LIMIT $request->start, $request->length";

            if ($sql_where != "") {
                $sql_where = " WHERE " . $sql_where;
            }

            // Count total records
            $count_sql = "SELECT COUNT(*) AS total FROM (
                            $sql $sql_join $sql_where 
                            GROUP BY cu.id, cu.name, cu.username, cu.email, cu.account, cu.min_deposit, cu.created_at
                        ) t";

            $count_result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => $count_sql
                ]
            ]));

            // Execute main query
            $sql .= $sql_join . $sql_where . $limit_sql;
            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => $sql
                ]
            ]));

            // Check if data exists
            $result = ($result) ? $result->data : [];
            $count = isset($count_result->data) ? $count_result->data[0]->total : 0;
            $data = [];
            $i = 0;

            foreach ($result as $value) {
                // Calculate days with us
                $current_time_stamp = \Carbon\Carbon::now();
                $with_us = isset($value->created_at) ? $current_time_stamp->diffInDays($value->created_at) : 0;

                // Get user country information
                $account_info = TradingAccount::where('account_number', $value->account)
                    ->select('countries.iso')
                    ->join('user_descriptions', 'trading_accounts.user_id', '=', 'user_descriptions.user_id')
                    ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->first();
                $u_flag = trim(strtolower((isset($account_info->iso)) ? $account_info->iso : 'pk'));

                // Generate chart data for the last 12 months
                $chart = $this->profits($value->account);
                $data[] = [
                    "name" => ucwords($value->username),
                    "flug" => "https://flagcdn.com/32x24/in.png",
                    // "flug" => asset("trader-assets/assets/img/pamm/$u_flag.svg"),
                    "gain" => round($value->gain, 4),
                    "follower" => $value->total_slaves,
                    "unfllower" => "$i",  // This seems static; you may want to change it.
                    "commission" => $value->share_profit ?? 0,
                    "with_us" => $with_us,
                    "overview_url" => route('user.pamm.trader.overview') . "?ac=$value->account",
                    'months' => $chart['months'],
                    'profits' => $chart['profits'],
                    'losses' => $chart['losses'],
                    'risk_icon' => ($value->gain > 0) ? asset('trader-assets/assets/img/pamm/logo/arro-circle-up.png') : asset('trader-assets/assets/img/pamm/logo/arro-circle-down.png')
                ];
                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $th->getMessage(),
            ]);
        }
    }



    // public function pamm_list_version2(Request $request)
    // {
    //     try {
    //         $orderBy = "cu.id DESC";
    //         $sql = "SELECT cu.*, SUM(ct.Profit) AS gain,";
    //         $sql .= "(SELECT COUNT(*) FROM copy_slaves WHERE master = cu.account) AS total_slaves, ";
    //         $sql .= "(SELECT COUNT(*) FROM copy_activities WHERE master = cu.account AND action != 'uncopy') AS all_copiers ";
    //         $sql .= " FROM copy_users cu";
    //         $sql_join = " LEFT JOIN copy_trades ct ON cu.account = ct.Login ";
    //         $sql_where = "";

    //         // filter by deposit
    //         if (!empty($request->min_investment)) {
    //             $sql_where .= " cu.min_deposit <=  " . (int) $request->min_investment;
    //         }
    //         // filter by user name
    //         if (!empty($request->trader_info)) {
    //             $sql_where .= ($sql_where != "") ? " AND " : "";
    //             $searchTerm = addslashes($request->trader_info);
    //             $sql_where .= " (cu.name LIKE '%$searchTerm%' OR cu.username LIKE '%$searchTerm%' OR cu.email LIKE '%$searchTerm%') ";
    //         }
    //         // filter by duration
    //         if (!empty($request->duration)) {
    //             $sql_where .= ($sql_where != "") ? " AND " : "";
    //             $sql_where .= " DATE(ct.OpenTime) >= CURDATE() - INTERVAL " . (int) $request->duration . " DAY ";
    //         }
    //         // filter by gainer
    //         if (!empty($request->show_first)) {
    //             $orderBy = (strtolower($request->show_first) === 'gainer') ? "gain ASC" : "total_slaves DESC";
    //         }
    //         // sql limit
    //         $limit_sql = " GROUP BY cu.account ORDER BY $orderBy LIMIT " . (int) $request->start . ", " . (int) $request->length;

    //         if (!empty($sql_where)) {
    //             $sql_where = " WHERE " . $sql_where;
    //         }

    //         // count total
    //         $count_sql = "SELECT COUNT(*) AS total FROM (" . $sql . $sql_join . $sql_where . " GROUP BY cu.account) t";

    //         $count_result = json_decode($this->copy_api->apiCall([
    //             'command' => 'Custom',
    //             'data' => [
    //                 'sql' => $count_sql
    //             ]
    //         ]));

    //         // Ensure `count_result` is valid before accessing data
    //         $count = (!empty($count_result) && isset($count_result->data[0]->total)) ? $count_result->data[0]->total : 0;

    //         // get result
    //         $sql .= $sql_join . $sql_where . $limit_sql;
    //         $result = json_decode($this->copy_api->apiCall([
    //             'command' => 'Custom',
    //             'data' => [
    //                 'sql' => $sql
    //             ]
    //         ]));

    //         // Ensure `result` is valid before accessing data
    //         $resultData = (!empty($result) && isset($result->data)) ? $result->data : [];

    //         $data = [];
    //         $i = 0;

    //         foreach ($resultData as $value) {
    //             // calculate with us
    //             $current_time_stamp = \Carbon\Carbon::now();
    //             $with_us = $current_time_stamp->diffInDays($value->created_at ?? now());

    //             // Get User Country
    //             $account_info = TradingAccount::where('account_number', $value->account)
    //                 ->select('name', 'iso')
    //                 ->join('user_descriptions', 'trading_accounts.user_id', '=', 'user_descriptions.user_id')
    //                 ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
    //                 ->first();
    //             $u_flag = trim(strtolower($account_info->iso ?? 'in'));

    //             // chart data for profit last 12 months
    //             $chart = $this->profits($value->account);

    //             $data[] = [
    //                 "name" => $value->username ?? '',
    //                 "flug" => asset("trader-assets/assets/img/pamm/$u_flag.svg"),
    //                 "gain" => round($value->gain ?? 0, 4),
    //                 "follower" => $value->total_slaves ?? 0,
    //                 "unfllower" => "$i",
    //                 "commission" => $value->share_profit ?? 0,
    //                 "with_us" => $with_us,
    //                 "overview_url" => route('user.pamm.trader.overview') . "?ac=" . ($value->account ?? ''),
    //                 'months' => $chart['months'] ?? [],
    //                 'profits' => $chart['profits'] ?? [],
    //                 'losses' => $chart['losses'] ?? [],
    //                 'risk_icon' => ($value->gain > 0)
    //                     ? asset('trader-assets/assets/img/pamm/logo/arro-circle-up.png')
    //                     : asset('trader-assets/assets/img/pamm/logo/arro-circle-down.png')
    //             ];
    //             $i++;
    //         }

    //         return Response::json([
    //             'draw' => (int) $request->draw,
    //             'recordsTotal' => $count,
    //             'recordsFiltered' => $count,
    //             'data' => $data,
    //         ]);
    //     } catch (\Throwable $th) {
    //         return Response::json([
    //             'draw' => (int) $request->draw,
    //             'recordsTotal' => 0,
    //             'recordsFiltered' => 0,
    //             'data' => [],
    //             'error' => $th->getMessage(),
    //         ]);
    //     }
    // }


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
