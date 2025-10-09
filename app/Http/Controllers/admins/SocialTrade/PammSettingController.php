<?php

namespace App\Http\Controllers\admins\SocialTrade;

use App\Services\CopyApiService;
use App\Http\Controllers\Controller;
use App\Models\Traders\PammSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PammSettingController extends Controller
{
    public function PammSetting(Request $request)
    {
        $data = PammSetting::select()->first();
        return view('admins.socialTrade.pamm-settings', ['data' => $data]);
    }
    public function PammSettingProcess(Request $request)
    {
        $copy_api = new CopyApiService();

        $response['success'] = false;
        $response['message'] = 'Please fix the errors';
        $op = $_POST['op'];

        if ($op == 'switch-enable-disable') {
            $columnname = $_POST['columnname'];
            $valueis = $_POST['valueis'];

            $settingChange = PammSetting::updateOrCreate(
                ['id' => 1],
                [$columnname => $valueis]
            );

            if ($settingChange) {
                $response['success'] = true;
            }
            return $response;
        } elseif ($op == 'settings_pamm_values') {
            $pamm_requirement = $_POST['pamm_requirement'];
            // $pamm_global_deposit = $_POST['pamm_global_deposit'];
            $master_limit = $_POST['master_limit'];
            $slave_limit = $_POST['slave_limit'];
            $profit_share_value = $_POST['profit_share_value'];
            $minimum_deposit = $_POST['minimum_deposit'];
            $minimum_wallet_balance = $_POST['minimum_wallet_balance'];
            $minimum_account_balance = $_POST['minimum_account_balance'];
            $pamm_account_limit = $_POST['pamm_account_limit'];
            $profit_duration = $_POST['profit_duration'];


            $profit_share_commission_value = ($request->profit_share_commission_value) ? $request->profit_share_commission_value : 0;
            $minimum_profit_share_value = (($_POST['minimum_profit_share_value']) ? $_POST['minimum_profit_share_value'] : 0);
            $maximum_profit_share_value = (($_POST['maximum_profit_share_value']) ? $_POST['maximum_profit_share_value'] : 0);
            $profit_share_value = (($_POST['profit_share_value']) ? $_POST['profit_share_value'] : 0);
            $profit_share_margin_value = (($_POST['profit_share_margin_value']) ? $_POST['profit_share_margin_value'] : 0);
            $manual_approve_pamm_reg = (($_POST['manual_approve_pamm_reg']) ? $_POST['manual_approve_pamm_reg'] : 0);
            $validation_rules = [
                'pamm_requirement' => 'required',
                'pamm_global_deposit' => 'nullable',
                'master_limit' => 'required',
                'slave_limit' => 'required',
                'minimum_deposit' => 'required',
                'minimum_wallet_balance' => 'required',
                'minimum_account_balance' => 'required',
                'profit_share_value' => 'required',
                'pamm_account_limit' => 'required',
                'profit_duration' => 'required',

            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => '<span style="color:red;">Fix the following error</span>',
                    'errors' => $validator->errors()
                ]);
            } else {

                $settingChange = PammSetting::updateOrCreate(
                    ['id' => 1],
                    [
                        'pamm_requirement' => $pamm_requirement,
                        // 'pamm_global_deposit' => $pamm_global_deposit,
                        'master_limit' => $master_limit,
                        'slave_limit' => $slave_limit,
                        'minimum_deposit' => $minimum_deposit,
                        'minimum_wallet_balance' => $minimum_wallet_balance,
                        'minimum_account_balance' => $minimum_account_balance,
                        'minimum_profit_share_value' => $minimum_profit_share_value,
                        'maximum_profit_share_value' => $maximum_profit_share_value,
                        'profit_share_commission_value' => $profit_share_commission_value,
                        'profit_share_margin_value' => $profit_share_margin_value,
                        'profit_share_value' =>  $profit_share_value,
                        // 'manual_approve_pamm_reg' =>  $manual_approve_pamm_reg,
                        'pamm_account_limit' =>  $pamm_account_limit,
                        'profit_duration' =>  $profit_duration,
                    ]
                );

                if ($settingChange) {
                    $sql = "UPDATE apps SET commission = $profit_share_commission_value";

                    $req_data = [
                        'command' => 'Custom',
                        'data' => [
                            "sql" => $sql
                        ]
                    ];

                    $result = json_decode($copy_api->apiCall($req_data));


                    if ($result) {
                        $response['success'] = true;
                        $response['message'] = 'PAMM Settings Changed.';
                    }
                }
                return Response::json($response);
            }
        }
    }

    public function ReadyContent(Request $request)
    {
        $pageName = $_POST['page_name'];

        $response['success'] = false;
        $response['message'] = 'Something is wrong';
        if ($pageName == 'pamm_settings') {
            $getPammSettings = PammSetting::where('id', 1)->first();



            $response['pageContents'] = $getPammSettings;
            if ($getPammSettings) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
            }
        }
        return $response;
    }
}
