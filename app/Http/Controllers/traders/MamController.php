<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\CommonController;
use App\Services\Mt5WebApi;
use App\Services\MT4API;
use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\Traders\PammSetting;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use App\Services\CopyApiService;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MamController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('manage_slave_account', 'trader'));
        $this->middleware(AllFunctionService::access('mamm', 'trader'));
    }
    public function manageSlaveAccount()
    {
        $user = auth()->user()->id;
        $trading_account = TradingAccount::select('account_number')->where('user_id', $user)->get();
        return view('traders.mam.manage-slave-account', ['trading_account' => $trading_account]);
    }


    public function SlaveAccountList(Request $request)
    {
        $mampamm = new CopyApiService();
        $draw = $request->input('draw');
        $LOGIN = $request->login;
        // $page = 0;
        $page = 1;
        $total = 10;
        $result = $mampamm->apiCall('get/slaves_of_master', [
            'master' => $LOGIN,
            'page' => $page,
            'total' => $total
        ]);
        if (is_string($result)) {
            $result = json_decode($result);
        }
        $recordsTotal = 0;
        $recordsFiltered = 0;
        if (!$result || $result->status == false) {
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        if (isset($result->status)) {
            if ($result->status) {

                $recordsTotal = $result->total;
                $recordsFiltered = $result->total;
                $data = array();
                $i = 0;
                $result_data = $result->data;
                foreach ($result_data as $key => $row) {

                    $mt_account = TradingAccount::where('account_number', $row->slave)->first();

                    // $mtg = ClientGroup::where('id', $mt_account->group_id)->first();
                    if (isset($mt_account)) {
                        $leverage = $mt_account->leverage;
                        $platform = $mt_account->platform;
                        $status = ($mt_account->account_status) ? 'Active' : 'Disabled';
                        $mtg = ClientGroup::where('id', $mt_account->group_id)->first();
                        if (isset($mtg)) {
                            $mtgroup = $mtg->group_id;
                        }
                    } else {
                        $mtgroup = null;
                    }

                    $data[$i]["DT_RowId"]         = $row->id;
                    $data[$i]["slave_account"]     = $row->slave;
                    $data[$i]["allocation"]     = $row->allocation;
                    $data[$i]["max_number_of_trade"] = $row->max_number_of_trade;
                    $data[$i]["min_trade_volume"] = $row->min_trade_volume / 10000;
                    $data[$i]["max_trade_volume"] = $row->max_trade_volume / 10000;
                    $data[$i]["platform"]         = isset($platform) ? $platform : '---';
                    $data[$i]["group"]             = $mtgroup ? $mtgroup : '---';
                    $data[$i]["leverage"]         = isset($leverage) ? $leverage : '---';
                    $data[$i]["status"]         = isset($status) ? $status : '---';
                    $data[$i]["action"]         = '<i class="fa fa-trash" onclick="openDeleteModal(' . $row->id . ',' . $row->master . ',\'' . $row->slave . '\',' . $row->allocation . ', \'' . $row->id . '\')"></i>';
                    // GET Symbol information
                    $slaveSymbols = $row->symbols;


                    $table = '<button class="mb-xs mt-xs mr-xs btn btn-primary pull-left" onclick="addSymbolReady(' . $row->slave . ')"><i title="Add New Symbol" class="fa fa-plus"></i>Add Symbol</button><br/>
                    <div class="table-responsive">
                        <table class="table w-100 table-description" style="width: 100% !important;">
                            <thead>
                                <tr>
                                <th>Symbol</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>';

                    foreach ($slaveSymbols as $key => $slaveSymbol) {
                        $table .= '<tr data-id="' . $slaveSymbol->id . '" data-slave="' . $row->slave . '" data-master="' . $LOGIN . '" data-role="' . $row->id . '" data-symbolname="' . $slaveSymbol->symbol . '">
                                                    <td data-name="symbol">' . $slaveSymbol->symbol . '</td>
                                                    <td data-name="status">' . ucfirst($slaveSymbol->status) . '</td>
                                                    <td class="text-center">
                                                        <i class="fas fa-edit" onclick="editSymbol(this)"></i>
                                                        <i class="fa fa-save" onclick="editSymbolUpdate(this)" style="display: none;"></i>
                                                        <i class="fa fa-times" onclick="editSymbolCancel(this)" style="display: none;"></i>
                                                        <i class="fa fa-trash" onclick="deleteSymbol(this)"></i>
                                                    </td>
                                                </tr>';
                    }

                    $table .= '</tbody>
                        </table>
                    </div>';
                    $data[$i]["extra"]  = $table;
                    $i++;
                }

                $output = array('draw' => $draw, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered);
                $output['data'] = $data;
                return Response::json($output);
            }
        }
    }
    //slave account delete
    public function SlaveAccountDelete(Request $request)
    {
        $mampamm = new CopyApiService();
        $result = $mampamm->apiCall('delete/slave', [
            'slave' => $request->sa
        ]);
        if (is_string($result)) {
            $result = json_decode($result);
        }
        // $data = [
        //     'command' => 'DeleteSlave',
        //     'data' => [
        //         'slave' => $request->sa
        //     ]
        // ];

        // $result = json_decode($mampamm->apiCall($data));

        return Response::json($result);
    }

    //symbol delete
    public function SymbolDelete(Request $request)
    {
        $mampamm = new CopyApiService('mt5');
        $itemid = $request->id;
        $slaveid = $request->slave;
        $symbol = $request->symbol;
        $result = $mampamm->apiCall('delete/symbol', [
            'slave' => $slaveid,
            'symbol' => $symbol
        ]);
        if (is_string($result)) {
            $result = json_decode($result);
        }
        // $data = [
        //     'command' => 'DeleteSymbol',
        //     'data' => [
        //         'slave' => $slaveid,
        //         'symbol' => $symbol
        //     ]
        // ];

        // $result = json_decode($mampamm->apiCall($data));

        return Response::json($result);
    }

    //symbol add
    public function AddSymbol(Request $request)
    {
        $mampamm = new CopyApiService('mt5');
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
            // $data = [
            //     'command' => 'AddSymbol',
            //     'data' => [
            //         'slave' => $symbol_slave,
            //         'symbol' => $add_new_symbol,
            //     ]
            // ];
            // $result = json_decode($mampamm->apiCall($data));
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

    //symbol edit/udpate
    public function UpdateSymbolStatus(Request $request)
    {
        $mampamm = new CopyApiService('mt5');
        $data = [
            'command' => 'UpdateSymbol',
            'data' => [
                'slave' => $_POST['slave'],
                'symbol' => $_POST['symbol'],
                'status' => $_POST['status']
            ]
        ];

        $result = json_decode($mampamm->apiCall($data));

        return Response::json($result);
    }


    public function addSlaveAccount(Request $request)
    {

        $mampamm = new CopyApiService();

        $master_ac = $request->master_account;
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
            //=========check master from copy slave
            $pamm = PammSetting::select()->first();

            if ($pamm->pamm_requirement_status == 1) {
                // $master_count = [
                //     'command' => 'CountMaster',
                //     'data' => [
                //         'master' => $master_ac,
                //         'slave' => $add_an_num
                //     ]
                // ];
                // $result = json_decode($mampamm->apiCall($master_count));
                $master_count = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => "SELECT (SELECT COUNT(*) FROM copy_slaves WHERE master = $master_ac) AS copy_slave,
                            (SELECT COUNT(*) FROM copy_slaves WHERE slave = $add_an_num) AS master_count;
                        ",
                        // 'sql' => 'SELECT COUNT("*") as copy_slave FROM copy_slaves WHERE master = ' . $master_ac,
                    ]
                ];
                // $result = json_decode($mampamm->apiCall($master_count));
                // if ($pamm->master_limt != 0) {
                //     if ($result->data->master_count >= $pamm->master_limit) {
                //         return Response::json([
                //             'status' => false,
                //             'message' => 'Master Limit Exceeded'
                //         ]);
                //     }
                // }
                // return false;
                // if ($pamm->slave_limit != 0) {
                //     if ($result->data->copy_slave >= $pamm->slave_limit) {
                //         return Response::json([
                //             'status' => false,
                //             'message' => 'Slave Limit Exceeded'
                //         ]);
                //     }
                // }
            }
            //======end check master script
            // $data = [
            //     'command' => 'addSlave',
            //     'data' => [
            //         'master' => $master_ac,
            //         'slave' => $add_an_num,
            //         'type' => "pamm",
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
                'type' => "pamm",
                'allocation' => $add_allocation,
                'symbols' => $request->symbol,
                'max_number_of_trade' => $max_trade,
                'max_trade_volume' => $max_volume,
                'min_trade_volume' => $min_volume,
                'ts_loss' => 0
            ]);
            if (is_string($result)) {
                $result = json_decode($result);
            }
            return Response::json($result);
        }
    }
    //trading account balance show
    public function showTradingAccountBl(Request $request)
    {
        $test_user = $request->login;
        //Get account server
        $mtac = TradingAccount::select()->where('account_number', $test_user)->first();
        $server = $mtac->platform;
        $type = strtolower($mtac->client_type);

        $response['equity'] = 0;
        $response['balance'] = 0;
        $response['total_volume'] = 0;
        $response['total_trade'] = 0;
        $response['success'] = true;


        if (strtolower($server) == 'mt4') {
            // $data = array(
            //     'command' => 'user_data_get',
            //     'data' => array('account_id' => $test_user),
            // );
            // $mt4api = new MT4API();
            // $var = $mt4api->execute($data);
            
            $mt4api = new MT4API();
            $data = array(
                'command' => 'UserDataGet',
                'data' => array('Login' => (int)$test_user),
            );
            $var = $mt4api->execute($data);
            $response['success'] = true;
            $response['equity'] = 0;
            $response['balance'] = 0;
            if (isset($var["status"])) {
                if ($var["status"]) {
                    $var1 = $var['data'];
                    $response['success'] = true;
                    $response['credit'] = 0;
                    $response['equity'] = $var1['Equity'];
                    $response['balance'] = $var1['Balance'];
                    $response['free_margin'] = 0;
                }
            }
            return Response::json($response);
        } else {
            $mt5_api = new Mt5WebApi(null, $type);

            $action = 'AccountGetMargin';

            $data = array(
                "Login" => (int)$test_user
            );
            $result = $mt5_api->execute($action, $data);
            if (isset($result['success'])) {
                if ($result['success']) {
                    $response['success'] = true;
                    $response['credit'] = $result['data']['Credit'];
                    $response['equity'] = $result['data']['Equity'];
                    $response['balance'] = $result['data']['Balance'];
                    $response['free_margin'] = isset($result['data']['MarginFree']) ? $result['data']['MarginFree'] : 0;
                }
            }

            $mt5_api->Disconnect();
            return Response::json($response);
        }
    }

    // public function showTradingAccountBl(Request $request){
    //     $test_user=$request->login;
    //     $a=0;
    //     $result = (new CommonController)->balance_equity($request->login);
    //     dd($result);
    // }
}
