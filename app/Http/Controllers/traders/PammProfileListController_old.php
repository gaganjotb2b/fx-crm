<?php

namespace App\Http\Controllers\traders;

use App\Services\CopyApiService;
use App\Http\Controllers\Controller;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
        return view('traders.pamm.pamm-profile');
    }

    public function PammProfileList(Request $request)
    {
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

        return Response::json([
            'draw' => $request->draw,
            'data' => $data,
            'page' => $page,
            'total' => $total
        ]);
    }
}
