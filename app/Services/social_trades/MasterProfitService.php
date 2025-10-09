<?php

namespace App\Services\social_trades;

use App\Models\TradingAccount;
use App\Models\admin\InternalTransfer;
use App\Models\User;
use App\Services\Mt5WebApi;
use App\Services\CopyApiService;
use App\Models\Traders\PammSetting;
use App\Models\Traders\MasterProfit;
use DateTime;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterProfitServiceTest
{
    public function getMasterProfit()
    {
        try {
            $pammService = new CopyApiService();
            $req_data = [
                'command' => 'Custom',
                'data' => [
                    "sql" => "SELECT * FROM copy_master_profits"
                ]
            ];
    
            $result = json_decode($pammService->apiCall($req_data));
            if (is_string($result)) {
                $result = json_decode($result);
            }
    
            $pammSetting = PammSetting::select()->first();
            $status = "credited";
            // $status = ($pammSetting->profit_duration == "daily")?"credited":"pending";
    
            if ($result && isset($result->data)) {
                foreach ($result->data as $row) {
                    // Check if the record already exists in the local database
                    $existing = MasterProfit::where('id', $row->id)->exists();
                    if (!$existing) {
                        if($pammSetting->profit_duration != "daily"){
                            try {
                                $mt5_api = new Mt5WebApi();
                                $action = 'BalanceUpdate';
                                $data = array(
                                    "Login" => (int)$row->master,
                                    // "Balance" => $is_cen_acc? -(float)$amount * 100 : -(float)$amount,
                                    "Balance" => -(float)$row->amount,
                                    "Comment" => "Master profit is blocked"
                                );
                                $result = $mt5_api->execute($action, $data);
                                if(isset($result['success'])){
                                    $status = "pending";
                                }
                            } catch (\Throwable $th) {
                                // throw $th;
                            }
                        }
                        // Insert the new record
                        DB::table('master_profits')->insert([
                            'id' => $row->id,
                            'master_order' => $row->master_order,
                            'master' => $row->master,
                            'slave_order' => $row->slave_order,
                            'slave' => $row->slave,
                            'profit_percent' => $row->profit_percent,
                            'slave_profit' => $row->slave_profit,
                            'broker_profit_rate' => $row->broker_profit_rate,
                            'broker_profit' => $row->broker_profit,
                            'amount' => $row->amount,
                            'slave_deal_id' => $row->slave_deal_id,
                            'master_deal_id' => $row->master_deal_id,
                            'status' => $status,
                            'created_at' => $row->created_at ?? now(),
                            'updated_at' => $row->updated_at ?? now(),
                        ]);
                    }
                }
                return "Data synced successfully.";
            }
            return "No data to sync.";
        } catch(Exception $e) {
            // throw $th;
        }
    }
    
    // release master profit share
    public function releaseMasterProfit()
    {
        try {
            $pammSetting = PammSetting::select()->first();
            $master_profit = MasterProfit::select()->where('status', 'pending')->first();
            // $master_profit->slave;
            $trading_account = TradingAccount::where('account_number', $master_profit->slave)->select('id', 'account_number')->first();
            $account_deposit = InternalTransfer::where('account_id', $trading_account->id)->where('type', 'wta')->sum('amount');
            $account_withdraw = InternalTransfer::where('account_id', $trading_account->id)->where('type', 'atw')->sum('amount');
            $account_balance = 0;
            try{
                $mt5_api = new Mt5WebApi();
                $data = array(
                    "Login" => (int)2100604
                );
                $result = $mt5_api->execute('AccountGetMargin', $data);
                $account_balance = $result['data']['Balance'];
            } catch(Exception $e) {
                // throw $e;
            }
            if((($account_withdraw + $account_balance) - $account_deposit) < 0){
                try {
                    $mt5_api = new Mt5WebApi();
                    $action = 'BalanceUpdate';
                    $data = array(
                        "Login" => (int)$master_profit->slave,
                        "Balance" => (float)$master_profit->amount,
                        "Comment" => "Return to trader"
                    );
                    $result = $mt5_api->execute($action, $data);
                    if(isset($result['success'])){
                        $status = "pending";
                    }
                } catch (\Throwable $th) {
                    // throw $th;
                }
                // update 
                MasterProfit::where('id', $master_profit->id)->update([
                    'status' => 'return-back'
                ]);
                return "Profit updated successfully.";
            }else{
                $create_time = Carbon::parse($master_profit->created_at);
                $now = Carbon::now();
                $daysPassed = $create_time->diffInDays($now);
                // return $daysPassed;
                $release = false;
                if($pammSetting->profit_duration != "weekly"){
                    if($daysPassed>=7){
                        $release = true;
                    }
                } elseif($pammSetting->profit_duration != "biweekly"){
                    if($daysPassed>=14){
                        $release = true;
                    }
                } elseif($pammSetting->profit_duration != "monthly"){
                    if($daysPassed>=30){
                        $release = true;
                    }
                }
                // $status = ($pammSetting->profit_duration == "daily")?"credited":"pending";
        
                if ($release) {
                    try {
                        $mt5_api = new Mt5WebApi();
                        $action = 'BalanceUpdate';
                        $data = array(
                            "Login" => (int)$master_profit->master,
                            "Balance" => (float)$master_profit->amount,
                            "Comment" => "Master profit is released"
                        );
                        $result = $mt5_api->execute($action, $data);
                        if(isset($result['success'])){
                            $status = "pending";
                        }
                    } catch (\Throwable $th) {
                        // throw $th;
                    }
                    // update 
                    MasterProfit::where('id', $master_profit->id)->update([
                        'status' => 'credited'
                    ]);
                    return "Profit updated successfully.";
                }
                return "No data to sync.";
            }
        } catch(Exception $e) {
            // throw $e;
        }
    }
}
