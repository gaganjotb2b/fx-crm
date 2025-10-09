<?php

namespace App\Http\Controllers\admins\SocialTrade;

use App\Services\Mt5WebApi;
use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\TradingAccount;
use App\Services\MT4API;
use Illuminate\Http\Request;
use App\Services\CopyApiService;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AdminManageMammController extends Controller
{
    public function manageMamm()
    {
        return view('admins.socialTrade.manage-mam-report');
    }

    public function SlaveAccount(Request $request)
    {
        try {
            $mampamm = new CopyApiService();
            $draw = $request->input('draw', 0); // Default to 0 if not provided
            $page = 1;
            $total = 10;

            // API Request
            $result = $mampamm->apiCall('get/slaves_of_master', [
                'master' => $request->login,
                'page' => $page,
                'total' => $total
            ]);

            // Decode API response
            if (is_string($result)) {
                $result = json_decode($result);
            }

            if (!$result || !isset($result->status) || !$result->status) {
                return response()->json([
                    'draw' => $draw, 
                    'recordsTotal' => 0, 
                    'recordsFiltered' => 0, 
                    'data' => []
                ]);
            }

            $recordsTotal = $result->total ?? 0;
            $recordsFiltered = $recordsTotal;

            $data = [];
            foreach ($result->data as $row) {
                $mt_account = TradingAccount::where('account_number', $row->slave)->first();

                // Initialize values
                $leverage = $platform = $status = $mtgroup = '---';

                if ($mt_account) {
                    $leverage = $mt_account->leverage ?? '---';
                    $platform = $mt_account->platform ?? '---';
                    $status = $mt_account->status ?? '---';

                    $mtg = ClientGroup::where('id', $mt_account->group_id)->first();
                    if ($mtg) {
                        $mtgroup = isset($mtg->group_id) ? $mtg->group_id : '---';
                    }
                }

                // Action button
                $data[] = [
                    "DT_RowId" => $row->id,
                    "slave_account" => $row->slave,
                    "allocation" => $row->allocation,
                    "platform" => $platform,
                    "group" => $mtgroup,
                    "leverage" => $leverage,
                    "status" => $status,
                    "action" => '<i class="fa fa-trash" style="font-size:24px" onclick="openDeleteModal(' 
                                . $row->id . ',' . $row->master . ',\'' . $row->slave . '\',' . $row->allocation . ', \'' . $row->id . '\')"></i>',
                    "extra" => $this->generateSymbolsTable($row->symbols, $row->slave, $row->id)
                ];
            }

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]);

        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'draw' => $draw, 
                'recordsTotal' => 0, 
                'recordsFiltered' => 0, 
                'data' => [],
                'error' => $th->getMessage()
            ]);
        }
    }

    /**
     * Generates the HTML table for symbols.
     */
    private function generateSymbolsTable($symbols, $slave, $role)
    {
        $table = '<button class="mb-xs mt-xs mr-xs btn btn-primary pull-left" onclick="addSymbolReady(' . $slave . ')">
                    <i title="Add New Symbol" class="fa fa-plus"></i> Add Symbol
                </button>
                <br/>
                <table class="table table-bordered">
                    <th>Symbol</th><th>Status</th><th class="text-center">Action</th>';

        foreach ($symbols as $symbol) {
            $table .= '<tr data-id="' . $symbol->id . '" data-slave="' . $slave . '" data-role="' . $role . '" data-symbolname="' . $symbol->symbol . '">
                            <td data-name="symbol">' . htmlspecialchars($symbol->symbol) . '</td>
                            <td data-name="status">' . ucfirst(htmlspecialchars($symbol->status)) . '</td>
                            <td class="text-center">
                                <i class="fa fa-edit" onclick="editSymbol(this)"></i>
                                <i class="fa fa-save" onclick="editSymbolUpdate(this)" style="display: none;"></i>
                                <i class="fa fa-times" onclick="editSymbolCancel(this)" style="display: none;"></i>
                                <i class="fa fa-trash" onclick="deleteSymbol(this)"></i>
                            </td>
                    </tr>';
        }

        $table .= '</table>';
        return $table;
    }


    public function AddNewSymbol(Request $request)
    {
        $mampamm = new CopyApiService();
        $add_new_symbol = $_POST['add_new_symbol'];
        $symbol_slave = $_POST['slave'];

        $validation_rules = [
            'add_new_symbol' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => '<span style="color:red;">Add Symbol</span>',
                'errors' => $validator->errors()
            ]);
        } else {
            $result = $mampamm->apiCall('add/symbol', [
                'slave' => $symbol_slave,
                'symbol' => $add_new_symbol,
            ]);
            if (is_string($result)) {
                $result = json_decode($result);
            }
            return Response::json($result);
        }
    }

    //symbol delete
    public function SymbolDelete(Request $request)
    {
        $mampamm = new CopyApiService();
        $itemid = $request->id;
        $slaveid = $request->slave;
        $symbol = $request->symbol;
        // $data = [
        //     'command' => 'DeleteSymbol',
        //     'data' => [
        //         'slave' => $slaveid,
        //         'symbol' => $symbol
        //     ]
        // ];

        // $result = json_decode($mampamm->apiCall($data));
        $result = $mampamm->apiCall('delete/symbol', [
            'slave' => $slaveid,
            'symbol' => $symbol
        ]);
        if (is_string($result)) {
            $result = json_decode($result);
        }

        return Response::json($result);
    }

    //symbol edit/udpate
    public function UpdateSymbolStatus(Request $request)
    {
        $mampamm = new CopyApiService();
        // $data = [
        //     'command' => 'UpdateSymbol',
        //     'data' => [
        //         'slave' => $_POST['slave'],
        //         'symbol' => $_POST['symbol'],
        //         'status' => $_POST['status']
        //     ]
        // ];

        // $result = json_decode($mampamm->apiCall($data));

        $result = $mampamm->apiCall('update/symbol', [
            'slave' => $_POST['slave'],
            'symbol' => $_POST['symbol'],
            'status' => $_POST['status']
        ]);
        if (is_string($result)) {
            $result = json_decode($result);
        }
        return Response::json($result);
    }

    //trading account balance show
    public function showTradingAccountBl(Request $request)
    {
        $mampamm = new CopyApiService('mt5');

        $test_user = $request->login;

        //Get account server
        $mtac = TradingAccount::select()->where('account_number', $test_user)->first();


        //Get total master trades info
        $trcc_sql = "SELECT COUNT(copy_trades.Order) AS total_trades, SUM(copy_trades.Profit) AS total_profit, SUM(copy_trades.Volume) AS total_volume FROM copy_trades WHERE copy_trades.Login = '$test_user'";

        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $trcc_sql
            ]
        ];

        $trcc_res = json_decode($mampamm->apiCall($req_data));

        //Get total slave account
        $trsc_sql = "SELECT COUNT(*) AS total_slaves FROM copy_slaves WHERE copy_slaves.master = '$test_user'";

        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $trsc_sql
            ]
        ];

        $trsc_res = json_decode($mampamm->apiCall($req_data));


        //Get total slave trades info copied and volume
        $trs_sql = "SELECT COUNT(copy_trades.Order) AS total_copied, SUM(copy_trades.Volume) AS total_copied_volume FROM copy_slaves LEFT JOIN copy_trades ON copy_slaves.slave = copy_trades.Login WHERE copy_slaves.master = '$test_user'";

        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $trs_sql
            ]
        ];

        $trs_res = json_decode($mampamm->apiCall($req_data));
        if (is_string($trs_res)) {
            $trs_res = json_decode($trs_res);
        }
        //Get total master trades chart
        $cart_master_sql = "
                SELECT 
                SUM(CASE MONTH(OpenTime) WHEN 1 THEN volume ELSE 0 END) AS 'Jan',
                SUM(CASE MONTH(OpenTime) WHEN 2 THEN volume ELSE 0 END) AS 'Feb',
                SUM(CASE MONTH(OpenTime) WHEN 3 THEN volume ELSE 0 END) AS 'Mar',
                SUM(CASE MONTH(OpenTime) WHEN 4 THEN volume ELSE 0 END) AS 'Apr',
                SUM(CASE MONTH(OpenTime) WHEN 5 THEN volume ELSE 0 END) AS 'May',
                SUM(CASE MONTH(OpenTime) WHEN 6 THEN volume ELSE 0 END) AS 'Jun',
                SUM(CASE MONTH(OpenTime) WHEN 7 THEN volume ELSE 0 END) AS 'Jul',
                SUM(CASE MONTH(OpenTime) WHEN 8 THEN volume ELSE 0 END) AS 'Aug',
                SUM(CASE MONTH(OpenTime) WHEN 9 THEN volume ELSE 0 END) AS 'Sep',
                SUM(CASE MONTH(OpenTime) WHEN 10 THEN volume ELSE 0 END) AS 'Oct',
                SUM(CASE MONTH(OpenTime) WHEN 11 THEN volume ELSE 0 END) AS 'Nov',
                SUM(CASE MONTH(OpenTime) WHEN 12 THEN volume ELSE 0 END) AS 'Dec'
                FROM
                    copy_trades
                WHERE (Type = '0' OR Type = '1') AND login = '$test_user' AND
                OpenTime BETWEEN date_sub(now(),INTERVAL 1 YEAR) and now()
            ";

        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $cart_master_sql
            ]
        ];

        $chart_master_res = json_decode($mampamm->apiCall($req_data));
        if (is_string($chart_master_res)) {
            $chart_master_res = json_decode($chart_master_res);
        }
        //Get total slaves trades chart
        $cart_slave_sql = "
                SELECT 
                SUM(CASE MONTH(OpenTime) WHEN 1 THEN volume ELSE 0 END) AS 'Jan',
                SUM(CASE MONTH(OpenTime) WHEN 2 THEN volume ELSE 0 END) AS 'Feb',
                SUM(CASE MONTH(OpenTime) WHEN 3 THEN volume ELSE 0 END) AS 'Mar',
                SUM(CASE MONTH(OpenTime) WHEN 4 THEN volume ELSE 0 END) AS 'Apr',
                SUM(CASE MONTH(OpenTime) WHEN 5 THEN volume ELSE 0 END) AS 'May',
                SUM(CASE MONTH(OpenTime) WHEN 6 THEN volume ELSE 0 END) AS 'Jun',
                SUM(CASE MONTH(OpenTime) WHEN 7 THEN volume ELSE 0 END) AS 'Jul',
                SUM(CASE MONTH(OpenTime) WHEN 8 THEN volume ELSE 0 END) AS 'Aug',
                SUM(CASE MONTH(OpenTime) WHEN 9 THEN volume ELSE 0 END) AS 'Sep',
                SUM(CASE MONTH(OpenTime) WHEN 10 THEN volume ELSE 0 END) AS 'Oct',
                SUM(CASE MONTH(OpenTime) WHEN 11 THEN volume ELSE 0 END) AS 'Nov',
                SUM(CASE MONTH(OpenTime) WHEN 12 THEN volume ELSE 0 END) AS 'Dec'
                FROM copy_slaves
                LEFT JOIN copy_trades ON copy_slaves.slave = copy_trades.login
                WHERE (copy_trades.Type = '0' OR copy_trades.Type = '1') AND copy_trades.copy_of != 0 AND copy_slaves.master = '$test_user' AND
                copy_trades.OpenTime BETWEEN date_sub(now(),INTERVAL 1 YEAR) and now()
            ";

        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $cart_slave_sql
            ]
        ];

        $chart_slave_res = json_decode($mampamm->apiCall($req_data));
        $curdate =  date('M');
        $new_data = [];
        $old_data = [];
        $found = 0;
        $ci = 0;
        foreach ($chart_master_res->data[0] as $key => $a) {

            if ($a == NULL) {
                $a = 0;
            }
            $b = $chart_slave_res->data[0]->$key;
            if ($b == NULL) {
                $b = 0;
            }
            if ($found == 0) {
                $new_data[] = ["y" => $key, "a" => $a / 10000, "b" => $b / 10000];
                if ($curdate == $key) {
                    $found = 1;
                }
            } else {
                $old_data[] = ["y" => $key, "a" => $a / 10000, "b" => $b / 10000];
            }
            $ci++;
        }

        $chart_data = array_merge($old_data, $new_data);


        $respose['equity'] = 0;
        $respose['balance'] = 0;
        $respose['chart'] = $chart_data;
        $respose['total_slave'] = $trsc_res->data[0]->total_slaves;;
        $respose['total_copied'] = $trs_res->data[0]->total_copied . "/" . number_format(($trs_res->data[0]->total_copied_volume / 10000), 2);
        $respose['total_profit'] = number_format($trcc_res->data[0]->total_profit, 2);
        $respose['total_trade'] = $trcc_res->data[0]->total_trades . "/" . number_format(($trcc_res->data[0]->total_volume / 10000), 2);
        $respose['success'] = true;

        if ($mtac) {
            $server = $mtac->platform;
            $type = strtolower($mtac->client_type);
            
            $mt5_api = new Mt5WebApi();
            $action = 'AccountGetMargin';

            $data = array(
                "Login" => (int)$test_user
            );
            $result = $mt5_api->execute($action, $data);
            
            // $mt4api = new MT4API();
            // $data = array(
            //     'command' => 'UserDataGet',
            //     'data' => array('Login' => (int)$test_user),
            // );
            // $result = $mt4api->execute($data);
            if ($result["success"]) {
                $result1 = $result['data'];
                $respose['success'] = true;
                $respose['equity'] = $result1['Equity'];
                $respose['balance'] = $result1['Balance'];
            }
        }

        return Response::json($respose);
    }



    //slave account delete
    public function SlaveAccountDelete(Request $request)
    {
        $mampamm = new CopyApiService();
        // $data = [
        //     'command' => 'DeleteSlave',
        //     'data' => [
        //         'slave' => $request->sa
        //     ]
        // ];

        // $result = json_decode($mampamm->apiCall($data));
        $result = $mampamm->apiCall('delete/slave', [
            'slave' => $request->sa
        ]);
        if (is_string($result)) {
            $result = json_decode($result);
        }

        return Response::json($result);
    }

    public function addSlaveAccount(Request $request)
    {
        $mampamm = new CopyApiService();
        $master_ac = $request->master_ac_hide;
        $server = $request->platform;
        $add_an_num = $request->slave_account;
        $add_pass = $request->password;
        $add_allocation = $request->allocation;
        $date = date("Y-m-d h:i:s");

        $max_trade = $request->max_trade_number;
        $max_volume = $request->max_trade_vol;
        $min_volume = $request->min_trade_vol;

        $validation_rules = [
            'slave_account' => 'required',
            'password' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        } else if ($master_ac == $add_an_num) {
            return Response::json([
                'status' => false,
                'message' => 'Master and slave is same!'
            ]);
        } else {
            //Match acount 
            $check_ac = TradingAccount::where('account_number', $add_an_num)->count();

            if ($check_ac < 1) {
                return Response::json([
                    'status' => false,
                    'message' => $add_an_num . ' does not exit!'
                ]);
            }

            //Match Account Password 
            $check_ac_pass = TradingAccount::where('master_password', $add_pass)->count();

            if ($check_ac_pass < 1) {
                return Response::json([
                    'status' => false,
                    'message' => 'Account Password Is Wrong'
                ]);
            }


            // $data = [
            //     'command' => 'addSlave',
            //     'data' => [
            //         'master' => $master_ac,
            //         'slave' => $add_an_num,
            //         'type' => "mamm",
            //         'allocation' => $add_allocation,
            //         'symbols' => $request->symbol,
            //         'max_number_of_trade' => $max_trade,
            //         'max_trade_volume' => $max_volume,
            //         'min_trade_volume' => $min_volume
            //     ]
            // ];

            // $result = json_decode($mampamm->apiCall($data));

            $result = $mampamm->apiCall('add/slave', [
                'master' => $master_ac,
                'slave' => $add_an_num,
                'type' => "mamm",
                'allocation' => $add_allocation,
                'symbols' => $request->symbol,
                'ts_loss' => '0',
                'max_number_of_trade' => $max_trade,
                'max_trade_volume' => $max_volume,
                'min_trade_volume' => $min_volume
            ]);
            if (is_string($result)) {
                $result = json_decode($result);
            }
            return Response::json($result);
        }
    }
}
