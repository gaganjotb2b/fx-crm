<?php

namespace App\Http\Controllers\traders;

use App\Services\CopyApiService;
use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\Traders\PammSetting;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CopyTradesController extends Controller
{
    public function __construct()
    {
        if (request()->is('/user/user-copy/social-traders-report')) {
            $this->middleware(AllFunctionService::access('social_traders_report', 'trader'));
        } elseif (request()->is('/user/user-copy/traders-activities-report*/')) {
            $this->middleware(AllFunctionService::access('social_activities_report', 'trader'));
        }
        $this->middleware(AllFunctionService::access('copy_trading', 'trader'));
    }
    public function copyTraderReport()
    {
        $user = auth()->user()->id;
        $trading_account = TradingAccount::select('account_number')->where('user_id', $user)->get();
        return view('traders.copy.copy-traders-report', ['trading_account' => $trading_account]);
    }

    // public function SocialReport(Request $request)
    // {

    //     $data = $request->all();
    //     $mampamm = new CopyApiService();
    //     $result = $mampamm->apiCall('get/slaves_copy_trades', [
    //         // 'ticket' =>  $request->ticket,
    //         'start' =>  $request->start,
    //         'length' =>  $request->length,
    //         'isnew' =>  ($request->isnew==true)?1:0,
    //         'order_by' =>  $request->order,
    //         'dir' =>  $request->dir
    //     ]);
    //     if (is_string($result)) {
    //         $result = json_decode($result);
    //     }
    //     if ($result === null) {
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Data not found!',
    //             'data' => [],
    //             'counter' => [
    //                 'total_volume' => 0,
    //                 'total_trades' => 0,
    //                 'total_closed' => 0
    //             ]
    //         ]);
    //     }

    //     return Response::json($result);
    // }

    public function SocialReport(Request $request)
    {
        $mampamm = new CopyApiService();
        $result = $mampamm->apiCall('get/slaves_copy_trades', [
            'ticket' =>  $request->ticket,
            'trade_account' =>  $request->trade_account,
            'start' => $request->start ?? 0,
            'length' => $request->length ?? 10,
            'isnew' => $request->isnew == true ? 1 : 0,
            'order_by' => $request->order ?? 'order',
            'dir' => $request->dir ?? 'desc',
        ]);

        if (is_string($result)) {
            $result = json_decode($result);
        }

        if ($result === null || empty($result->data)) {
            return response()->json([
                'status' => false,
                'message' => 'Data not found!',
                'data' => [],
                'counter' => [
                    'total_volume' => 0,
                    'total_trades' => 0,
                    'total_closed' => 0
                ]
            ]);
        }

        return response()->json($result);
    }




    public function copyTradersActivitiesReport()
    {
        $user = auth()->user()->id;
        $trading_account = TradingAccount::select('account_number')->where('user_id', $user)->get();
        return view('traders.copy.copy-traders-activities-report', ['trading_account' => $trading_account]);
    }

    public function copyTradersActivitiesProcess(Request $request)
    {
        $mampamm = new CopyApiService();
        $request = $request->all();

        $start = intval($request['start']);
        $take = intval($request['length']);
        $type = $request['type'];

        $master_account = $request['master_account'];
        $trade_account = $request['trade_account'];
        $date_from = $request['date_from'];
        $date_to = $request['date_to'];
        $status = $request['status'];


        // $filter = $request['filter'];
        //Order Settings
        $orderBy = $request['order'];
        $orderDir = $request['dir'];


        $sql = "SELECT * FROM copy_activities";


        // if ($type != "") {
        //     $sql = "SELECT * FROM copy_activities WHERE type = '$type' AND slave = '$trade_account'";
        // }

        // if ($master_account != "") {
        //     $sql = "SELECT * FROM copy_activities WHERE master = '$master_account' AND slave = '$trade_account'";
        // }


        // if ($status != "") {
        //     $sql = "SELECT * FROM copy_activities WHERE action = '$status' AND slave = '$trade_account'";
        // }

        // if ($date_from != "") {
        //     $sql = "SELECT * FROM copy_activities WHERE created_at >= '$date_from' AND slave = '$trade_account'";
        // }

        // if ($date_to != "") {
        //     $sql = "SELECT * FROM copy_activities WHERE created_at <= '$date_to' AND slave = '$trade_account'";
        // }

        // $req_data = [
        //     'command' => 'Custom',
        //     'data' => [
        //         "sql" => $sql
        //     ]
        // ];

        // $tr_res = json_decode($mampamm->apiCall($req_data));

        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $sql
            ]
        ];
        
        $tr_res = json_decode($mampamm->apiCall($req_data));

        // $count = count ($tr_res->data);
        if (isset($tr_res->data)) {
            $count = count($tr_res->data);
        } else {
            $count = 0;
        }


        $recordsTotal = $count;

        $recordsFiltered = $count;


        $limit_sql = " ORDER BY copy_activities.$orderBy $orderDir LIMIT $start, $take";

        $sql .= $limit_sql;

        // $req_data = [
        //     'command' => 'Custom',
        //     'data' => [
        //         "sql" => $sql
        //     ]
        // ];
        $req_data = [
            'command' => 'Custom',
            'data' => [
                "sql" => $sql
            ]
        ];

        $result = json_decode($mampamm->apiCall($req_data));

        if (!$result) {
            return response()->json(['draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
        }

        //COUNTING HERE

        $data = array();
        $i = 0;

        foreach ($result->data as $row) {

            $data[$i][] = $row->master;
            $data[$i][] = $row->slave;
            $data[$i][] = $row->action;
            // $data[$i][] = $row->type;
            $data[$i][] = "Social Trade";
            $data[$i][] = $row->created_at;

            $i++;
        }
        return Response::json((['data' => $data, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered]));
        //  json_encode(['data' => $data, 'recordsTotal' => $recordsTotal , 'recordsFiltered' => $recordsFiltered]);
    }

    // copy trade from pamm profile detailes
    public function copy_trades(Request $request)
    {
        $validation_rules = [
            'max_trade' => 'required',
            'max_volume' => 'required|numeric',
            'min_volume' => 'required',
            'account' => 'required',
            'slave_account' => 'required',
            'allocation' => 'required',
            'symbol' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            $data['message'] = 'Please fix the following errors!';
            $data['errors'] = $validator->errors();
            $data['success'] = false;
            return Response::json($data);
        }
        // account availablity
        // $check_ac = DB::table('hb_ac')->where('cusername', $slave_ac)->first();
        $check_ac = TradingAccount::where('account_number', $request->slave_account)->first();
        if ($check_ac) {
            $server = $check_ac->server;
            $mampamm = new CopyApiService($server);

            //GET master settings
            $sdata = [
                'command' => 'Custom',
                'data' => [
                    'sql' => "SELECT * FROM copy_users WHERE account = '$request->account'"
                ]
            ];

            $result_settings = json_decode($mampamm->apiCall($sdata));
            $fx_settings = $result_settings->data;

            $check_setting = false;
            $min_deposit = 0;
            $max_deposit = 0;
            $internal_err = 0;

            if (isset($result_settings->status)) {
                if ($result_settings->status) {
                    $check_setting = true;
                    $min_deposit = $fx_settings[0]->min_deposit;
                    $max_deposit = $fx_settings[0]->max_deposit;
                }
            }
            // if ($check_setting) {
            //     //Get slave account total deposit
            //     $slave_account = TradingAccount::where('account_number', $request->slave_account)->first();
            //     $total_deposit = InternalTransfer::where('account_id', $slave_account->id)->sum('amount');

            //     // if ($total_deposit < $min_deposit) {
            //     //     $internal_err = 1;
            //     //     $data = [
            //     //         'success' => false,
            //     //         'message' => "Minimum $" . $min_deposit . " is required!"
            //     //     ];
            //     // } else 
            //     // if ($total_deposit > $max_deposit && $max_deposit != 0) {
            //     //     $internal_err = 1;
            //     //     $data = [
            //     //         'success' => false,
            //     //         'message' => "Maximum deposit limit is $" . $max_deposit
            //     //     ];
            //     // }
            // } else {
            //     $internal_err = 1;
            //     $data = [
            //         'success' => false,
            //         'message' => "Master account setting not found!"
            //     ];
            // }
        } else {
            $internal_err = 1;
            $data['message'] = $request->slave_account . ' does not exit!';
        }

        //=========check master from copy slave=============
        $pamm = PammSetting::select()->first();
        if ($pamm->pamm_requirement_status == 1) {
            $master_count = [
                'command' => 'CountMaster',
                'data' => [
                    'master' => $request->account,
                    'slave' => $request->slave_account
                ]
            ];
            $result = json_decode($mampamm->apiCall($master_count));
            // if ($pamm->master_limit != 0) {
            //     if ($result->master >= $pamm->master_limit) {
            //         return Response::json([
            //             'success' => false,
            //             'message' => 'Master Limit Exceeded'
            //         ]);
            //     }
            // }
            // if ($pamm->slave_limit != 0) {
            //     if ($result->copy_slave >= $pamm->slave_limit) {
            //         return Response::json([
            //             'success' => false,
            //             'message' => 'Slave Limit Exceeded'
            //         ]);
            //     }
            // }
        }
        //======end check master script===========
        if ($internal_err == 0) {
            // $slave_sql = "INSERT INTO copy_slaves(master,slave,type,allocation,";
            // $slave_sql .=")"
            $api_data = [
                'command' => 'addSlave',
                'data' => [
                    'master' => $request->account,
                    'slave' => $request->slave_account,
                    'type' => 'pamm',
                    'allocation' => $request->allocation,
                    'max_number_of_trade' => $request->max_trade,
                    'max_trade_volume' => $request->max_volume,
                    'min_trade_volume' => $request->min_volume,
                    'symbols' => $request->symbol
                ]
            ];
            // $result = json_decode(CopyApiService2::apiCall($api_data));
            $result = json_decode($mampamm->apiCall($api_data));
            if ($result->status === true) {
                $data = [
                    'success' => true,
                    'message' => "Congratulations! You successfully copy this trade"
                ];
            } else {
                $data = [
                    'success' => false,
                    'message' => $result->message
                ];
            }
        }
        return Response::json($data);
    }
}
