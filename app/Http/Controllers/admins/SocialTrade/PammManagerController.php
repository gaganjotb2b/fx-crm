<?php

namespace App\Http\Controllers\admins\SocialTrade;

use App\Http\Controllers\Controller;
use App\Models\TradingAccount;
use App\Services\CopyApiService;
use App\Services\Mt5WebApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\MT4API;
use MTAccount;


class PammManagerController extends Controller
{
    protected $copy_api = '';
    // constructor
    public function __construct()
    {
        $this->copy_api = new CopyApiService('mt5');
    }
    // basic view
    public function PammManager(Request $request)
    {
        return view('admins.socialTrade.pamm-manager');
    }
    // datatable fetch data
    public function datatable(Request $request)
    {
        // datatable description
        if ($request->op === 'description') {
            return $this->dt_description($request->id);
        }
        // inner datatable
        if ($request->op === 'description_table') {
            return $this->inner_dt($request->id);
        }
        // start datatable root
        $count = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => [
                'sql' => "SELECT COUNT(*)AS total_row FROM copy_users"
            ]
        ]));
        $recordsTotal = isset($count->data[0]->total_row) ? $count->data[0]->total_row : 0;
        /********--------------------------------
         * START Filter Area 
         *********----------------------------*/
        $sqlContainer = '';
        $joinQuery = '';
        $s = " copy_users.id <> 0";

        // Finance Filter
        $volumeField = '';
        if ($request->finance_filter != "") {
            if ($request->finance_filter == 'profit_loss') {
                $volumeField = 'Profit';
            } elseif ($request->finance_filter == 'volume') {
                $volumeField = 'Volume';
            } else {
                $volumeField = 'Profit';
            }
        }
        if ($request->min != "" || $request->max != "" || $request->date_from != '' || $request->date_to != '') {
            $joinQuery = " JOIN copy_trades ON copy_users.account = copy_trades.Login ";
        }
        // Min & Max Filter
        if ($request->min != "") {
            $s .= " AND  copy_trades.$volumeField >= '$request->min'";
        }
        // filter by max 
        if ($request->max != "") {
            $s .= " AND  copy_trades.$volumeField <= '$request->max'";
        }
        // Trade Duration Filter
        if ($request->date_from != "") {
            $s .= " AND CAST(copy_trades.OpenTime AS Date) >= '$request->date_from'";
        }
        if ($request->date_to != "") {
            $s .= " AND CAST(copy_trades.OpenTime AS Date) <= '$request->date_to'";
        }

        // Ratting Filter
        // if ($request->ratting != "") {
        //     $s='';
        //     // $s .= " AND copy_users.ratting >= '$request->ratting'";
        // }

        // Account Number Email Name Filter
        if ($request->account_filter != "") {
            $s .= " AND (copy_users.name LIKE '%$request->account_filter%' OR copy_users.email LIKE '%$request->account_filter%' OR copy_users.account LIKE '%$request->account_filter%')";
        }

        // Slave accountNumber/Email/Name Filter
        if ($request->slave_filter != "") {
            $joinQuery .= " JOIN copy_slaves ON copy_users.account = copy_slaves.master ";
            $s .= " AND copy_slaves.slave = '$request->slave_filter'";
        }

        // Joining Date Filter
        if ($request->join_date_from != "") {
            $s .= " AND CAST(copy_users.created_at AS joinDate) >= '$request->join_date_from'";
        }
        if ($request->join_date_to != "") {
            $s .= " AND CAST(copy_users.created_at AS joinDate) <= '$request->join_date_to'";
        }

        // Status Filter
        if ($request->active_inactive_filter != "") {
            $s .= " AND copy_users.status = '$request->active_inactive_filter'";
        }
        /********   END Filter Area  ********/
        // Search Filter
        if ($_REQUEST["search"]["value"] != "") {
            $searchField = ['name', 'email', 'username', 'account', 'country', 'share_profit'];

            $s .= " AND (";
            for ($i = 0; $i < count($searchField); $i++) {

                if ($i != 0) {
                    $s .= " OR ";
                }

                $s .= "copy_users." . $searchField[$i] . " LIKE '%" . $_REQUEST["search"]["value"] . "%'";
            }
            $s .= ")";
        }

        $sqlContainer = $s;
        // Drop Down Order
        $limit = '';
        $sortBy = $_REQUEST['order'][0]['dir'];
        if (isset($_REQUEST['start']) && $_REQUEST['length'] != -1) {
            $limit = " ORDER BY copy_users.id $sortBy LIMIT " . intval($_REQUEST['start']) . ", " . intval($_REQUEST['length']);
        }

        $sqlContainer .= $limit;
        $copy_user_sql = "SELECT *, (SELECT COUNT(copy_users.id) FROM copy_users) AS total_row_filtered ,";
        $copy_user_sql .= " (SELECT COUNT(*) AS total_copied FROM copy_slaves WHERE copy_slaves.master = copy_users.account) AS total_copied,";
        $copy_user_sql .= " (SELECT COUNT(id) AS total_follow FROM copy_followers WHERE master = copy_users.account) AS copy_followers,";
        $copy_user_sql .= " (SELECT COALESCE(SUM(Volume), 0) AS total_volume FROM copy_trades WHERE Login = copy_users.account)AS total_volume,";
        $copy_user_sql .= "(SELECT SUM(Profit) AS total_profit FROM copy_trades WHERE Login = copy_users.account AND Profit > 0)AS total_profit,";
        $copy_user_sql .= " (SELECT COUNT(Profit) AS total_profit_number FROM copy_trades WHERE Login = copy_users.account AND Profit > 0)AS total_profit_number,";
        $copy_user_sql .= " (SELECT SUM(Profit) AS total_loss FROM copy_trades WHERE Login = copy_users.account AND Profit < 0)AS total_loss,";
        $copy_user_sql .= " (SELECT COUNT(Profit) AS total_loss_number FROM copy_trades WHERE Login = copy_users.account AND Profit < 0)AS total_loss_number,";
        $copy_user_sql .= " (SELECT COUNT(Profit) AS total_trade_number FROM copy_trades WHERE Login = copy_users.account)AS total_trade_number";
        $copy_user_sql .= " FROM copy_users $joinQuery WHERE $sqlContainer";
        // All Copy Data Retrieve
        $result = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => [
                'sql' => $copy_user_sql
            ]
        ]));
        $recordsFiltered = ((isset($result->data[0]->total_row_filtered)) ? $result->data[0]->total_row_filtered : 0);

        $data = array();
        $i = 0;
        if (isset($result->data)) {
            foreach ($result->data as $row) {
                /******** Profit & Loss Area  **********/

                $totalTradeNumberIs = $row->total_trade_number;

                $totalProfitTradeNumberIs = $row->total_profit_number;
                $totalLossTradeNumberIs = $row->total_loss_number;

                // Getting Loss & Profit Percentage
                $profitPercentage = (($totalTradeNumberIs) ? (100 / $totalTradeNumberIs) * $totalProfitTradeNumberIs : 0);
                $lossPercentage =   (($totalTradeNumberIs) ? (100 / $totalTradeNumberIs) * $totalLossTradeNumberIs : 0);

                if ($row->total_profit > $row->total_loss) {
                    // Profit
                    $tradeValue = "+" . $row->total_profit;
                    $displayClasses = 'text-success';
                    $displayType = $profitPercentage;
                    $barBgColor1 = '#47a447';
                    $barBgColor2 = 'red';
                } elseif ($row->total_profit == 0 and 0 == $row->total_loss) {
                    // No Trade
                    $tradeValue = 0;
                    $displayClasses = '';
                    $displayType = 0;
                    $barBgColor1 = 'transparent';
                    $barBgColor2 = 'transparent';
                } else {
                    // Loss
                    $tradeValue = "-" . $row->total_loss;
                    $displayClasses = 'text-danger';
                    $displayType = $lossPercentage;
                    $barBgColor1 = 'red';
                    $barBgColor2 = '#47a447';
                }

                // $data[$i]["profile_pic"]    = '<img src="https://www.atomix.com.au/media/2015/06/atomix_user31.png" width="40px"/>';
                $data[$i]["account"]    = '<a data-id="' . $row->account . '" href="#" class="dt-description justify-content-between"><span class="w"> <i class="plus-minus text-dark" data-feather="plus"></i> </span><span>' . $row->account . '</span></a>';
                $data[$i]["name"]         = $row->name;
                $data[$i]["username"]  = $row->username;
                $data[$i]["share_profit"] = $row->share_profit . '%';
                $data[$i]["total_slave"]     = $row->total_copied;
                $data[$i]["volume"]     = $row->total_volume / 100;
                $data[$i]["profit_loss"] = '<span class="' . $displayClasses . '">' . $tradeValue . '$</span>
                                    <div class="progress" style="height: 5px; margin-top: 10px; margin-bottom: 0;' . (($displayType <= 0) ? 'background-color:transparent;' : 'background-color:' . $barBgColor2) . '">
                                        <div class="progress-bar progress-bar-success progress-without-number" role="progressbar" aria-valuenow="' . $displayType . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $displayType . '%; background-color: ' . $barBgColor1 . '">
                                            <span class="sr-only"></span>
                                        </div>
                                    </div>';
                $data[$i]["status"]         = ucfirst($row->status);
                // $data[$i]["action"]         = '<a href="pamm_edit_admin.php?account=' . $row->account . '"><center><i class="icons icon-settings io"></i></center></a>';
                $i++;
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        }
        return Response::json([
            'draw' => $request->draw,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
        ]);
    }

    private function dt_description($account)
    {

        $copy_user_sql = "SELECT *, (SELECT COUNT(*) AS total_copied FROM copy_slaves WHERE master = copy_users.account)AS total_copied, ";
        $copy_user_sql .= "(SELECT MAX(Profit) as greatest  FROM copy_trades WHERE Login = copy_users.account AND Profit > 0) AS greatest, ";
        $copy_user_sql .= "(SELECT MAX(Profit) as greatest_loss FROM copy_trades WHERE Login = copy_users.account AND Profit < 0)AS greatest_loss, ";
        $copy_user_sql .= "(SELECT AVG(Profit) as avg_daily_profit_loss FROM copy_trades WHERE Login = copy_users.account AND Profit < 0)AS avg_daily_profit_loss, ";
        $copy_user_sql .= "(SELECT COUNT(Volume) as total_trade FROM copy_trades WHERE Login = copy_users.account)AS total_trade, ";
        $copy_user_sql .= "(SELECT SUM(Volume) as total_volume FROM copy_trades WHERE Login = copy_users.account) AS total_volume";
        $copy_user_sql .= " FROM copy_users WHERE account = $account";
        // return $copy_user_sql;
        $result = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => [
                'sql' => $copy_user_sql
            ]
        ]));
       

        $getServerType = TradingAccount::where('account_number', $account);
        $getServerType = $getServerType->first();

        $total_volume = ($result->data[0]->total_volume != null) ? round(($result->data[0]->total_volume/100), 2) : 0;
        $total_trade = ($result->data[0]->total_volume != null) ? $result->data[0]->total_trade : 0;
        $avg_daily_pl = (($result->data[0]->avg_daily_profit_loss) ? $result->data[0]->avg_daily_profit_loss : 0);

        $accountBalance = 0;
        $accountEquity = 0;
        $platform = isset($getServerType->platform) ? $getServerType->platform : get_platform();

        /** Retrieving Data From API **/
        if (strtolower($platform) == 'mt4') {
            // $mt4api = new MT4API();
            // $data = array(
            //     'command' => 'user_data_get',
            //     'data' => array('account_id' => (int)$account),
            // );
            $mt4api = new MT4API();
            $data = array(
                'command' => 'UserDataGet',
                'data' => array('Login' => (int)$account),
            );
            // $result = $mt4api->execute($data);

            $var = $mt4api->execute($data);

            if ($var["status"]) {
                $var1 = $var['data'];
                $respose['success'] = true;
                $respose['credit'] = 0;
                $accountEquity = $var1['Equity'];
                $accountBalance = $var1['Balance'];
                $respose['free_margin'] = array_key_exists('FreeMargin', $var) ? $var1['FreeMargin'] : '';
            }
            $data = [
                'status' => false,
                'message' => 'MT4 API not found'
            ];
            // return Response::json($data);
        } else if (strtolower($platform) == 'mt5') {
            $mt5_api = new Mt5WebApi();
            $action = 'AccountGetMargin';

            $data = array(
                "Login" => (int)$account
            );
            $results = $mt5_api->execute($action, $data);
        
            $mt5_api->Disconnect();
            if (isset($results['success'])) {
                if ($results['success']) {
                    $respose['success'] = true;
                    $respose['credit'] = 0;
                    $accountEquity = $results['data']['Equity'];
                    $accountBalance = $results['data']['Balance'];
                    // $respose['free_margin'] = $results['data']['MarginFree'];
                    
                }
            }
        }

        // active status
       if ($result->data[0]->status === 'active') {
            $statu_button = '<button type="button" class="btn btn-danger btn-block btn-active w-100" data-account="' . $account . '" data-op="inactive" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Inactive Master Account" data-bs-original-title="Inactive Master Account" aria-label="Inactive Master Account" >Inactive Account</button>';
        } else {
            $statu_button = '<button type="button" class="btn btn-success btn-block btn-active w-100" data-account="' . $account . '" data-op="active" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Active Master Account" data-bs-original-title="Active Master Account" aria-label="Active Master Account"  >Active Account</button>';
        }
        // descriptions
        $description = '<tr class="description" style="display:none">
            <td colspan="8">
                <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="rounded-0 w-75">
                                <table class="table table-responsive tbl-balance">
                                    <tr>
                                        <th>' . __('page.balance') . '</th>
                                        <td class="btn-load-balance">
                                            <span>&dollar;<span class="balance-value amount"> ' . $accountBalance . '</span></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.equity') . '</th>
                                        <td class="btn-load-equity">
                                            <span>&dollar;<span class="balance-value amount"> ' . $accountEquity . '</span></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.volume') . '</th>
                                        <td class="btn-load-equity">
                                            <span><span class="balance-value amount"> ' . $total_volume . '</span></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.total-trade') . '</th>
                                        <td class="btn-load-equity">
                                            <span><span class="balance-value amount"> ' . $total_trade . '</span></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.copied') . '</th>
                                        <td class="btn-load-equity">
                                            <span><span class="balance-value amount"> ' . $result->data[0]->total_copied . '</span></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justfy-content-between">
                            <div class="rounded-0 w-100">
                                <table class="table table-responsive tbl-trader-details">
                                    <tr>
                                        <th>' . __('page.name') . '</th>
                                        <td>' . $result->data[0]->name . '</td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.email') . '</th>
                                        <td>' . $result->data[0]->email . '</td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.broker') . '</th>
                                        <td>local</td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.ratings') . '</th>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.average-daily-pl') . '</th>
                                        <td>' . $avg_daily_pl . '</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="rounded ms-1 dt-trader-img">
                                <div class="h-100">
                                    <img class="img img-fluid bg-light-primary img-trader-admin" src="' . asset(avatar()) . ' "alt="avatar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="row">
                            <!-- Filled Tabs starts -->
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <div class=" p-0">
                                    <div class=" p-0">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="d-flex align-items-center">
                                                <div class="w-position-label p-2 bg-gradient-danger">Worst Position</div>
                                                    <div class="w-position mx-2">
                                                        &dollar; ' . ($result->data[0]->greatest_loss ? $result->data[0]->greatest_loss : 0) . '
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <div class=" p-0">
                                    <div class=" p-0">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                <div class="d-flex align-items-center">
                                                <div class="w-position-label p-2 bg-gradient-success">Best Position</div>
                                                    <div class="w-position mx-2">
                                                        &dollar; ' . ($result->data[0]->greatest ? $result->data[0]->greatest : 0) . '
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <div class=" p-0">
                                    <div class=" p-0">
                                        <div class="card">
                                            <div class="card-body p-1">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        ' . $statu_button . '
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button" class="btn btn-success text-truncate btn-block w-100 btn-add-slave" data-account="' . $account . '">Add Slave Account</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="row">
                            <!-- Filled Tabs starts -->
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class=" p-0">
                                    <div class=" p-0">
                                        <h4>Slave accounts</h4>
                                        <div class="table-responsive">
                                            <table class="datatable-inner trading_account table dt-inner-table-dark ' . table_color() . ' m-0"  style="margin:0px !important;">
                                                <thead>
                                                    <tr>
                                                        <th style="text-truncate">' . __('page.account-number') . '</th>
                                                        <th style="text-truncate">' . __('page.allocation') . '</th>
                                                        <th style="text-truncate">' . __('page.max-trade') . '</th>
                                                        <th style="text-truncate">' . __('page.platform') . '</th>
                                                        <th style="text-truncate">' . __('page.profit/loss') . '</th>
                                                        <th style="text-truncate">' . __('page.status') . '</th>
                                                        <th style="text-truncate">' . __('page.action') . '</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="demo-inline-spacing">
                        <buttom class="btn btn-primary btn-update-pamm" data-account="' . $account . '" type="button">Update PAMM Profile</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
        </tr>';
        $data = [
            'status' => true,
            'description' => $description
        ];
        return $data;
    }
    private function inner_dt($master_account)
    {

        $result = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => [
                'sql' => "SELECT COUNT(*) AS total_user FROM copy_slaves WHERE master='$master_account'"
            ]
        ]));
        // Getting Recordes For Each Page
        $recordsTotal = $result->data[0]->total_user;
        /******** START Filter Area  ********/
        $sqlContainer = '';
        $joinQuery = '';
        $s = '';
        // Search Filter
        if ($_REQUEST["search"]["value"] != "") {

            $searchField = ['master', 'slave', 'allocation'];

            $s .= " AND (";
            for ($i = 0; $i < count($searchField); $i++) {

                if ($i != 0) {
                    $s .= " OR ";
                }

                $s .= "copy_slaves." . $searchField[$i] . " LIKE '%" . $_REQUEST["search"]["value"] . "%'";
            }
            $s .= ")";
        }
        $sqlContainer = $s;
        // Drop Down Order
        $limit = '';
        $sortBy = $_REQUEST['order'][0]['dir'];
        if (isset($_REQUEST['start']) && $_REQUEST['length'] != -1) {
            $limit = " ORDER BY copy_slaves.id $sortBy LIMIT " . intval($_REQUEST['start']) . ", " . intval($_REQUEST['length']);
        }
        $sqlContainer .= $limit;
        $copy_slave_sql = "SELECT *, (SELECT COUNT(copy_slaves.id) FROM copy_slaves WHERE master='$master_account') AS total_row_filtered,";
        $copy_slave_sql .= " (SELECT COALESCE(SUM(Volume), 0) AS total_volume FROM copy_trades WHERE Login = copy_slaves.slave)AS total_volume, ";
        $copy_slave_sql .= " (SELECT SUM(Profit) AS total_profit FROM copy_trades WHERE Login = copy_slaves.slave AND Profit > 0)AS total_profit, ";
        $copy_slave_sql .= " (SELECT COUNT(Profit) AS total_profit_number FROM copy_trades WHERE Login = copy_slaves.slave AND Profit > 0)AS total_profit_number, ";
        $copy_slave_sql .= " (SELECT COUNT(Profit) AS total_trade_number FROM copy_trades WHERE Login = copy_slaves.slave)AS total_trade_number, ";
        $copy_slave_sql .= " (SELECT SUM(Profit) AS total_loss FROM copy_trades WHERE Login = copy_slaves.slave AND Profit < 0)AS total_loss, ";
        $copy_slave_sql .= " (SELECT COUNT(profit) AS total_loss_number FROM copy_trades WHERE Login = copy_slaves.slave AND Profit < 0)AS total_loss_number  ";
        $copy_slave_sql .= " FROM copy_slaves $joinQuery WHERE master='$master_account'" . $sqlContainer;
        $result = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => [
                'sql' => $copy_slave_sql
            ]
        ]));
        $recordsFiltered = ((isset($result->data[0]->total_row_filtered)) ? $result->data[0]->total_row_filtered : 0);

        $data = array();
        $i = 0;
        // return $result;
        foreach ($result->data as $row) {

            /******** Profit & Loss Area  **********/
            // Total Loss
            if ($row->total_profit > $row->total_loss) {
                // Profit
                $tradeValue = "+" . $row->total_profit;
                $displayClasses = 'text-success';
            } elseif ($row->total_profit == 0 and 0 == $row->total_loss) {
                // No Trade
                $tradeValue = 0;
                $displayClasses = '';
            } else {
                // Loss
                $tradeValue = "-" . $row->total_loss;
                $displayClasses = 'text-danger';
            }

            $accountDetails = TradingAccount::where('account_number', $row->slave)
                ->join('client_groups', 'trading_accounts.group_id', '=', 'client_groups.id')
                ->select('trading_accounts.*', 'group_name')->first();

            $min_volume = $row->min_trade_volume / 10000;
            $max_volume = $row->max_trade_volume / 10000;
            $group      = isset($accountDetails->group_name) ? $accountDetails->group_name : '';
            $leverage   = isset($accountDetails->leverage) ? '1:' . $accountDetails->leverage : '';
            $volume     = $row->total_volume / 1000;

            $data[$i]["account"]    = $row->slave;
            $data[$i]["allocation"] = $row->allocation;
            $data[$i]["max_number_of_trade"] = $row->max_number_of_trade;
            $data[$i]["platform"]   = strtoupper(isset($accountDetails->platform) ? $accountDetails->platform : get_platform());

            $data[$i]["profit_loss"] = '<span class="' . $displayClasses . '">$' . $tradeValue . '</span>';
            $data[$i]["status"]  = ucwords($row->status);
            $data[$i]["action"]  = '<div class="d-flex justify-content-between">
                                        <a href="#" class="more-actions dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <span class="dropdown-item btn-block btn-more-details" data-id="' . $row->slave . '" data-minvolume="' . $min_volume . '" data-maxvolume="' . $max_volume . '" data-group="' . $group . '" data-leverage="' . $leverage . '" data-volume="' . $volume . '">More Details</span>
                                            <span class="dropdown-item btn-block btn-edit" data-masteraccount="' . $master_account . '" data-account="' . $row->slave . '" data-symbol="" data-allocation="' . $row->allocation . '" data-maxtrade="' . $row->max_number_of_trade . '" data-maxvolume="' . $max_volume . '" data-minvolume="' . $min_volume . '">Edit</span>
                                            <span class="dropdown-item btn-block btn-delete" data-account="' . $row->slave . '">Delete</span>
                                        </div>
                                    </div>';
            $i++;
        }
        $output = array(
            'draw' => $_REQUEST['draw'],
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
        );
        $output['data'] = $data;
        return Response::json($output);
    }

    // master account active inactive
    public function active_inactive(Request $request)
    {
        $post_obj = new CopyApiService('mt5');
        $response['status'] = false;
        $response['message'] = 'Failed To Changed  Status';

        $actionType = $request->op;
        $master = $request->master_ac;
        $data = [
            'command' => 'Custom',
            'data' => [
                'sql' => "UPDATE copy_users SET status='$actionType' WHERE account = $master"
            ]
        ];
        // return $data;
        $result = json_decode($post_obj->apiCall($data));
        $data2 = [
            'command' => 'Custom',
            'data' => [
                'sql' => "UPDATE copy_masters SET status='$actionType' WHERE master = $master"
            ]
        ];
        $result2 = json_decode($post_obj->apiCall($data2));
        $response['status'] = true;
        $response['actionType'] = $actionType;
        $response['message'] = 'Account Status Changed Successfully';
        return Response::json($result2);
    }

    // add new slave account to master
    public function add_slave(Request $request)
    {
        $post_obj = new CopyApiService();

        $validation_rules = [
            'account_number' => 'required',
            'password' => 'required',
            'symbol' => 'required',
            'allocation' => 'required',
            'max_trade' => 'required',
            'max_volume' => 'required',
            'min_volume' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Please fill out all of required field.',
                'errors' => $validator->errors()
            ];
            return $response;
        }

        if ($request->account == $request->account_number) {
            $response = [
                'message' => 'Master and slave is same!',
                'status' => false,
            ];
            return Response::json($response);
        } elseif (!isset($request->symbol)) {
            $response = [
                'message' => 'Please select symbols!',
                'status' => false,
            ];
            return Response::json($response);
        } else {

            //Match acount password
            $check_ac = TradingAccount::where('account_number', $request->account_number)->where('master_password', $request->password)->first();

            if (!$check_ac) {
                $response = [
                    'message' => $request->account_number . ' Account Or Password Is invalid. Please try again',
                    'status' => false
                ];
                return Response::json($response);
            }
            $result = $post_obj->apiCall('add/slave', [
                'master' => $request->account,
                'slave' => $request->account_number,
                'allocation' => $request->allocation,
                'type' => 'pamm',
                'max_number_of_trade' => $request->max_trade,
                'max_trade_volume' => $request->max_volume,
                'min_trade_volume' => $request->min_volume,
                'ts_loss' => 0,
                'symbols' => $request->symbol,
            ]);
            // Decode the result if it's a JSON string
            if (is_string($result)) {
                $result = json_decode($result);
            }
            return Response::json($result);
        }
    }

    // edit slave account
    public function edit_slave(Request $request)
    {
        $post_obj = new CopyApiService();
        $validation_rules = [
            'master_account' => 'required',
            'account_number' => 'required',
            // 'password' => 'required',
            'symbol' => 'required',
            'allocation' => 'required',
            'max_trade' => 'required',
            'max_volume' => 'required',
            'min_volume' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Please fill out all of required field.',
                'errors' => $validator->errors()
            ];
            return Response::json($response);
        } elseif ($request->master_account == $request->account_number) {
            $response = [
                'message' => 'Master and slave is same!',
                'status' => false,
            ];
            return Response::json($response);
        } else {

            // //Match acount password
            // $check_ac = TradingAccount::where('account_number', $request->account_number)->where('master_password', '=', $request->password)->first();

            // if (!$check_ac) {
            //     $response = [
            //         'message' => $request->account_number . ' Account Or Password Is invalid. Please try again',
            //         'status' => false,
            //     ];
            //     return Response::json($response);
            // }
            // Update Copy Slave Informations
            $slave_update_sql = "UPDATE copy_slaves SET allocation=$request->allocation, max_number_of_trade=$request->max_trade, max_trade_volume=$request->max_volume, min_trade_volume=$request->min_volume WHERE master = $request->master_account AND slave=$request->account_number";
            $data = [
                'command' => 'Custom',
                'data' => [
                    "sql" => $slave_update_sql
                ]
            ];


            $result = json_decode($post_obj->apiCall($data));


            // Update Slave Symbols
            $data2 = [
                'command' => 'Custom',
                'data' => [
                    'sql' => "DELETE FROM copy_slave_symbols WHERE slave = '$request->account_number'"
                ]
            ];

            $result2 = json_decode($post_obj->apiCall($data2));


            // $symbol_add_sql = "INSERT INTO copy_slave_symbols (slave, symbol, symbol_fix, created_at) VALUES ";

            // foreach ($request->symbol as $key => $symbol) {
            //     $symbol_cut = explode(".", $symbol);
            //     $symbol_org = $symbol;
            //     if (count($symbol_cut) > 1) {
            //         $symbol_org = $symbol_cut[0];
            //     }


            //     // Find Existing Slave Symbols
            //     $data10 = [
            //         'command' => 'Custom',
            //         'data' => [
            //             'sql' => "SELECT symbol from copy_slave_symbol where symbol = '$symbol'"
            //         ]
            //     ];

            //     $symbol_exists = json_decode($post_obj->apiCall($data10));

            //     if (!$symbol_exists) {
            //         $symbol_add_sql .= "('$request->account_number', '$symbol_org', '$symbol', '" . date('Y-m-d h:i:s') . "')";
            //     }
            //     if ($key !== array_key_last($request->symbol)) {
            //         $symbol_add_sql .= ",";
            //     }
            // }

            // $data3 = [
            //     'command' => 'Custom',
            //     'data' => [
            //         'sql' => $symbol_add_sql
            //     ]
            // ];

            // $result3 = json_decode($post_obj->apiCall($data3));
            // return Response::json($result3);

            $symbol_add_sql = "INSERT INTO copy_slave_symbols (slave, symbol, symbol_fix, created_at) VALUES ";
            $values = []; // Array to hold values for bulk insertion

            foreach ($request->symbol as $symbol) {
                $symbol_cut = explode(".", $symbol);
                $symbol_org = count($symbol_cut) > 1 ? $symbol_cut[0] : $symbol;

                // Find Existing Slave Symbols
                $data10 = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => "SELECT symbol FROM copy_slave_symbols WHERE symbol = :symbol"
                    ]
                ];

                // Parameterized query execution
                $params = ['symbol' => $symbol];
                $symbol_exists = json_decode($post_obj->apiCall($data10, $params));

                // If the symbol doesn't exist, add it to the insert query
                if (empty($symbol_exists->data)) {
                    $values[] = "('" . addslashes($request->account_number) . "', '" . addslashes($symbol_org) . "', '" . addslashes($symbol) . "', '" . date('Y-m-d H:i:s') . "')";
                }
            }

            // Execute insert only if there are new symbols
            if (!empty($values)) {
                $symbol_add_sql .= implode(",", $values);

                $data3 = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => $symbol_add_sql
                    ]
                ];

                $result3 = json_decode($post_obj->apiCall($data3));
                // return response()->json($result3);
                return response()->json(['status' => true, 'message' => 'Updated Successfully.']);
            } else {
                return response()->json(['status' => false, 'message' => 'No new symbols to add!']);
            }

        }
    }
    public function delete_slave(Request $request)
    {
        $post_obj = new CopyApiService();
        return $result = $post_obj->apiCall('delete/slave', [
            'slave' => (int)$request->account
        ]);
        return $result = json_decode($post_obj->apiCall($data));
        if ($result->status) {
            $response = [
                'status' => true,
                'message' => 'Slave account deleted successfully.'
            ];
            return Response::json($response);
        } else {
            $response = [
                'status' => false,
                'message' => 'Failed to delete! Please try again later.'
            ];
            return Response::json($response);
        }
    }
}
