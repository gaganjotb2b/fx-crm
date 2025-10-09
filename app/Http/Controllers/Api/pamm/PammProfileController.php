<?php

namespace App\Http\Controllers\Api\pamm;

use App\Http\Controllers\Controller;
use App\Models\PammRequest;
use App\Models\Traders\PammSetting;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\CopyApiService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;

class PammProfileController extends Controller
{
    public function pammRegistration(Request $request)
    {
        $validation_rules = [
            'username' => 'required',
            'trading_account' => 'required',
            'min_deposit' => 'required',
            'share_profit' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => '<span class="error">Fix the following error</span>',
                'errors' => $validator->errors()
            ]);
        } else {
            $id = auth()->user()->id;
            $name = auth()->user()->name;
            $email = auth()->user()->email;
            $username = strtolower($request->username);
            $account = $request->trading_account;
            $min_deposit = $request->min_deposit == "" ? 0 : $request->min_deposit;
            $max_deposit = $request->max_deposit == "" ? 0 : $request->max_deposit;
            $share_profit = $request->share_profit;
            $date = date('Y-m-d h:i:s');

            $mtac = TradingAccount::select()->where('account_number', $account)->first();

            if ($mtac) {
                $server = $mtac->platform;

                $copy_mt = new CopyApiService($server);


                $req_data = [
                    'command' => 'Custom',
                    'data' => [
                        "sql" => "SELECT COUNT(*) AS check_account FROM copy_users WHERE account = '$account' AND id <> '$id'"
                    ]
                ];
                $result = json_decode($copy_mt->apiCall($req_data));

                if ($result->data[0]->check_account != 0) {
                    return Response::json(['success' => false, 'message' => 'Trading account already exists!']);
                }
                //Check username ability
                $req_data = [
                    'command' => 'Custom',
                    'data' => [
                        "sql" => "SELECT COUNT(*) AS check_un FROM copy_users WHERE username = '$username' AND id <> '$id'"
                    ]
                ];
                $result = json_decode($copy_mt->apiCall($req_data));
                if ($result->data[0]->check_un) {
                    return Response::json(['success' => false, 'message' => 'Username already exists!']);
                }

                //check account in slave
                $req_data2 = [
                    'command' => 'Custom',
                    'data' => [
                        "sql" => "SELECT COUNT(*) AS check_slave FROM copy_slaves WHERE slave = '$account'"
                    ]
                ];

                $result2 = json_decode($copy_mt->apiCall($req_data2));

                if ($result2->data[0]->check_slave) {
                    return Response::json(['success' => false, 'message' => 'Account already exists in slave!']);
                }

                //=========pam setting script start here===================
                $pamm = PammSetting::select()->first();

                if ($pamm->pamm_requirement_status == 1) {
                    $total_deposit = AllFunctionService::trader_total_deposit(auth()->user()->id);
                    // if ($pamm->minimum_deposit > $min_deposit || $pamm->minimum_deposit > $total_deposit) {
                    //     return Response::json(['success' => false, 'message' => '<span style="color:red;">Minimum deposit should be $' . $pamm->minimum_deposit . '</span>']);
                    // }

                    //pamm account limit check
                    $req_data = [
                        'command' => 'pammLimit',
                    ];
                    $count = json_decode($copy_mt->apiCall($req_data));
                    // if ($pamm->pamm_account_limit != 0) {
                    //     if ($count > $pamm->pamm_account_limit) {
                    //         return Response::json(['success' => false, 'message' => '<span style="white;">PAMM Limit Exceeded']);
                    //     }
                    // }
                }

                //profit share status
                if ($pamm->profit_share_status == 1 && $pamm->flexible_profit_share_status == 0) {
                    if ($share_profit != $pamm->profit_share_value) {
                        return Response::json(['success' => false, 'message' => '<span style="color:red;">Profit share value should be equal to ' . $pamm->profit_share_value . '%']);
                    }
                }
                // flexiable profit share status
                // if ($pamm->flexible_profit_share_status == 1) {
                //     if ($pamm->minimum_profit_share_value != 0 || $pamm->maximum_profit_share_value != 0) {
                //         if ($share_profit < $pamm->minimum_profit_share_value || $share_profit > $pamm->maximum_profit_share_value) {
                //             return Response::json(['success' => false, 'message' => '<span style="color:red;">Profit share value should be between ' . $pamm->minimum_profit_share_value . '%   to ' . $pamm->maximum_profit_share_value . '%']);
                //         }
                //     }
                // }
                // //wallet balance check
                // $all_fun = new AllFunctionService();
                // $balance = $all_fun->get_self_balance(auth()->user()->id);
                // if ($balance <= $pamm->minimum_wallet_balance || $balance <= 0) {
                //     return Response::json(['success' => false, 'message' => "<span style='color:red;'>You don't have enough account balance!"]);
                // }

                // ==============balance equity check==============
                $trading_account = TradingAccount::where('account_number', $account)->first();
                $response['success'] = false;
                if (strtolower($trading_account->platform) == 'mt4') {
                    $mt4api = new MT4API();
                    $data = array(
                        'command' => 'user_data_get',
                        'data' => array('account_id' => $trading_account->account_number),
                    );

                    $result = $mt4api->execute($data, $trading_account->client_type);
                    if ($result["success"]) {
                        $result1 = $result['data'];
                        $response['success'] = true;
                        $response['credit'] = 0;
                        $response['balance'] = $result1['balance'];
                        // $response['amount']  = ($request->search === 'balance') ? $result1['balance'] : $result1['equity'];
                        if ($response['balance'] < $pamm->minimum_account_balance) {
                                return Response::json(['success' => false, 'message' => "<span style='color:red;'>You don't have enough Trading account balance!"]);
                            }
                    } else {
                        try {
                            return Response::json([
                                'success' => false,
                                'message' => $result['info']['message']
                            ]);
                        } catch (\Throwable $th) {
                            return Response::json([
                                'success' => false,
                                'message' => "Failed to check wallet balance!"
                            ]);
                        }
                    }
                } else {
                    $mt5_api = new Mt5WebApi();
                    $action = 'AccountGetMargin';

                    $data = array(
                        "Login" => $trading_account->account_number
                    );
                    $result = $mt5_api->execute($action, $data);
                    $mt5_api->Disconnect();

                    if (isset($result['success'])) {
                        if ($result['success']) {
                            $response['success'] = true;
                            $response['balance'] = $result['data']['Balance'];

                            // $response['amount']  = ($request->search === 'balance') ? $result['data']['Balance'] : $result['data']['Equity'];
                            if ($response['balance'] < $pamm->minimum_account_balance) {
                                return Response::json(['success' => false, 'message' => "<span style='color:red;'>You don't have enough Trading account balance!"]);
                            }
                        } else if (isset($result['error'])) {
                            $response['message'] = $result['error']['Description'];
                        } else {
                            $response = [
                                'success' => false,
                                'message' => $result['message']
                            ];
                        }
                    }
                }
                // ==============balance equity check==============


                //when manual pamm approved system active
                $check_pamm = PammRequest::select('account')->where('account', $account)->first();
                if (isset($check_pamm->account)) {
                    return Response::json(['success' => false, 'message' => '<span style="color:red;">Trading account already exists!']);
                }
                if ($pamm->manual_approve_pamm_reg == 1) {
                    $create = PammRequest::create([
                        'user_id' => auth()->user()->id,
                        'name' => $name,
                        'email' => $email,
                        'account' => $account,
                        'username' => $username,
                        'min_deposit' => $min_deposit,
                        'max_deposit' => $max_deposit,
                        'share_profit' => $share_profit,
                        'status' => 'P',
                    ]);
                    if ($create) {
                        return Response::json(['success' => true, 'message' => '<span style="color:green;">Your PAMM profile has been created successfully!']);
                    }
                }
                //============Script end here========================
                //===========balance equity check end============
                if (Response::json(['success' => true])) {
                    if ($id) {

                        $req_data = [
                            'command' => 'Custom',
                            'data' => [
                                "sql" => "INSERT INTO copy_users (name, email, username, account, min_deposit, max_deposit, share_profit, created_at) VALUES('$name', '$email', '$username', '$account', '$min_deposit', '$max_deposit', '$share_profit', '$date')"
                            ]
                        ];
                        $result = json_decode($copy_mt->apiCall($req_data));

                        //Check master exists
                        $req_data_x = [
                            'command' => 'Custom',
                            'data' => [
                                "sql" => "SELECT COUNT(*) AS check_master FROM copy_masters WHERE master = '$account'"
                            ]
                        ];

                        $result_me = json_decode($copy_mt->apiCall($req_data_x));

                        if ($result_me->data[0]->check_master < 1) {
                            $req_data2 = [
                                'command' => 'Custom',
                                'data' => [
                                    "sql" => "INSERT INTO copy_masters (master , created_at) VALUES('$account', '$date')"
                                ]
                            ];
                            $result_master = json_decode($copy_mt->apiCall($req_data2));
                        }

                        return Response::json(['success' => true, 'message' => '<span style="color:green;">Your PAMM profile has been created successfully!']);
                    } else {
                        $req_data = [
                            'command' => 'Custom',
                            'data' => [
                                "sql" => "UPDATE copy_users SET min_deposit = '$min_deposit', max_deposit = '$max_deposit', share_profit = '$share_profit' WHERE id = '$id'"
                            ]
                        ];
                        return Response::json(['success' => true, 'message' => '<span style="color:green;">Your PAMM profile has been updated successfully!']);
                    }


                    if ($result) {
                        return Response::json(['success' => true]);
                    } else {
                        return Response::json(['success' => false, 'message' => '<span style="color:green;">Unkown Error!']);
                    }
                }
            } else {
                return Response::json(['success' => false, 'message' => 'Account does not exists!']);
            }
        }
    }
}
