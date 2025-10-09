<?php

namespace App\Http\Controllers\Api\mam;

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
    public function SlaveAccountList(Request $request)
    {
        try {
            $mam_api = new CopyApiService('mt5');

            $LOGIN = $request->ac;
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);

            $req_data = [
                'command' => 'GetMasterSlaves',
                'data' => [
                    'master' => $LOGIN,
                    'page' => $page - 1, // Assuming your API uses zero-based page index
                    'total' => $perPage
                ]
            ];

            $result = json_decode($mam_api->apiCall($req_data));

            if (isset($result->data)) {
                $collection = collect($result->data);
                $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
                    $collection->forPage($page, $perPage),
                    $result->total_count ?? $collection->count(), // Total count of items
                    $perPage,
                    $page,
                    ['path' => $request->url(), 'query' => $request->query()]
                );

                return response()->json([
                    'status' => true,
                    'data' => $paginatedData
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No data found'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Details loading failed',
                'error' => $th->getMessage()
            ]);
        }
    }

    //slave account delete
    public function deleteSlaveAccount(Request $request)
    {
        $mam_api = new CopyApiService('mt5');
        $data = [
            'command' => 'DeleteSlave',
            'data' => [
                'slave' => $request->slave_account
            ]
        ];

        $result = json_decode($mam_api->apiCall($data));

        return Response::json([
            'status' => true,
            'data' => $result,
            'slave_account' => $request->slave_account
        ]);
    }

    //symbol delete
    public function deleteSymbol(Request $request)
    {
        $mam_api = new CopyApiService('mt5');
        $slave = $request->slave;
        $symbol = $request->symbol;
        $data = [
            'command' => 'DeleteSymbol',
            'data' => [
                'slave' => $slave,
                'symbol' => $symbol
            ]
        ];

        $result = json_decode($mam_api->apiCall($data));

        return Response::json([
            'status' => true,
            'data' => $result,
            'slave_account' => $request->slave
        ]);
    }

    //symbol add
    public function addSymbol(Request $request)
    {
        $mam_api = new CopyApiService('mt5');
        $slave = $request->slave;
        $symbol = $request->symbol;

        $validation_rules = [
            'symbol' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => '<span style="color:red;">Add Symbol</span>',
            ]);
        } else {
            $data = [
                'command' => 'AddSymbol',
                'data' => [
                    'slave' => $slave,
                    'symbol' => $symbol,
                ]
            ];
            $result = json_decode($mam_api->apiCall($data));
            return Response::json([
                'status' => true,
                'data' => $result,
                'slave_account' => $request->slave
            ]);
        }
    }

    //symbol edit/udpate
    public function updateSymbolStatus(Request $request)
    {
        $mam_api = new CopyApiService('mt5');
        $data = [
            'command' => 'UpdateSymbol',
            'data' => [
                'slave' => $request->slave,
                'symbol' => $request->symbol,
                'status' => $request->status
            ]
        ];

        $result = json_decode($mam_api->apiCall($data));

        return Response::json([
            'status' => true,
            'data' => $result,
            'slave_account' => $request->slave
        ]);
    }


    public function addSlaveAccount(Request $request)
    {

        $mam_api = new CopyApiService();

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
                // $result = json_decode($mam_api->apiCall($master_count));
                $master_count = [
                    'command' => 'Custom',
                    'data' => [
                        'sql' => "SELECT (SELECT COUNT(*) FROM copy_slaves WHERE master = $master_ac) AS copy_slave,
                            (SELECT COUNT(*) FROM copy_slaves WHERE slave = $add_an_num) AS master_count;
                        ",
                        // 'sql' => 'SELECT COUNT("*") as copy_slave FROM copy_slaves WHERE master = ' . $master_ac,
                    ]
                ];
                // $result = json_decode($mam_api->apiCall($master_count));
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
            $data = [
                'command' => 'addSlave',
                'data' => [
                    'master' => $master_ac,
                    'slave' => $add_an_num,
                    'type' => "pamm",
                    'allocation' => $add_allocation,
                    'symbols' => $request->symbol,
                    'max_number_of_trade' => $max_trade,
                    'max_trade_volume' => $max_volume,
                    'min_trade_volume' => $min_volume
                ]
            ];

            $result = json_decode($mam_api->apiCall($data));
            return Response::json($result);
        }
    }
}
