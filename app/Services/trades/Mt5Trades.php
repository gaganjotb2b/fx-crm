<?php

namespace App\Services\trades;

use App\Models\AutoHelper;
use App\Models\IbCommissionStructure;
use App\Models\IbIncome;
use App\Models\IbSetup;
use App\Models\Mt5Trade;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\Mt5WebApi;
use DateTime;
use Illuminate\Support\Facades\DB;

class Mt5Trades
{

    protected $count = 0;
    public function margeTrades()
    {
        $this->count++;
    
        // Get active temporary account
        $temp = DB::table('temporary_accounts')->where('status', 1)->first();
        if (!$temp) {
            return 'No active temporary account found';
        }
    
        $total_mt_page = 0;
        $total_result = 0;
        $orders = [];
    
        $mt5api = new Mt5WebApi();
    
        // Load account information
        $meta_account = TradingAccount::where('account_number', $temp->account_no)->first();
        if (!$meta_account) {
            // No account found, mark temp as processed
            DB::table('temporary_accounts')->where('id', $temp->id)->update(['status' => 0]);
            return 'No matching trading account found';
        }
    
        $login = $meta_account->account_number;
    
        // Date range (from Aug 4, 2023 to tomorrow)
        $from = "2023-08-04";
        $from_timestamp = (new DateTime($from))->getTimestamp();
    
        $to = "2023-08-07";
        // $to = date('Y-m-d', strtotime('+1 day'));
        $to_timestamp = (new DateTime($to))->getTimestamp();
    
        $rpp = 100;
        $index = $meta_account->page;
    
        // API request data
        $data = [
            "Login" => (int) $login,
            'From'  => $from_timestamp,
            'To'    => $to_timestamp,
            'Offset'=> $index,
            'Total' => $rpp
        ];
    
        // Fetch trades from MT5 API
        return $result = $mt5api->execute('DealGetPage', $data);
    
        if ($result && isset($result['success']) && $result['success']) {
            $orders = (array) $result['data']['Deals'];
            $total_result = $result['data']['Total'];
        }
    
        if (!empty($orders)) {
            foreach ($orders as $value) {
                $open_time = date("Y-m-d H:i:s", $value->Time);
                $close_time = date("Y-m-d H:i:s", $value->Time);
    
                $order_num   = $value->Order;
                $login       = $value->Login;
                $symbol      = $value->Symbol;
                $volume      = $value->Volume / 100;
                $open_price  = $value->Price;
                $close_price = $value->Price;
                $profit      = $value->Profit;
                $comment     = $value->Comment;
                $cmd         = $value->Action;
                $PositionID  = $value->PositionID;
    
                // Update trade by reference if needed
                if ($PositionID != $order_num) {
                    $actual_trade = Mt5Trade::where('TICKET', $PositionID)->first();
                    if ($actual_trade) {
                        $actual_trade->CLOSE_TIME  = $close_time;
                        $actual_trade->CLOSE_PRICE = $close_price;
                        $actual_trade->PROFIT      = $profit;
                        $actual_trade->DEAL        = $value->Deal;
                        $actual_trade->save();
                    }
                }
    
                // Create or update if main ticket
                if ($PositionID == $order_num && ($cmd == 0 || $cmd == 1)) {
                    Mt5Trade::updateOrCreate(
                        ['TICKET' => $order_num, 'LOGIN' => $login],
                        [
                            "SYMBOL"         => $symbol,
                            "DIGITS"         => $value->Digits,
                            "CMD"            => $cmd,
                            "VOLUME"         => $volume,
                            "OPEN_TIME"      => $open_time,
                            "OPEN_PRICE"     => $open_price,
                            "SL"             => $value->PriceSL,
                            "TP"             => $value->PriceTP,
                            "CLOSE_TIME"     => '1970-01-01 00:00:00',
                            "EXPIRATION"     => 0,
                            "REASON"         => $value->Reason,
                            "DEAL"           => $value->Deal,
                            "CONV_RATE1"     => 0,
                            "CONV_RATE2"     => 0,
                            "COMMISSION"     => 0,
                            "COMMISSION_AGENT"=> 0,
                            "SWAPS"          => 0,
                            "CLOSE_PRICE"    => $close_price,
                            "PROFIT"         => $profit,
                            "TAXES"          => 0,
                            "COMMENT"        => $comment,
                            "INTERNAL_ID"    => $PositionID,
                            "MARGIN_RATE"    => 0,
                            "TIMESTAMP"      => $value->Time,
                            "MAGIC"          => 0,
                            "GW_VOLUME"      => $value->Volume,
                            "GW_OPEN_PRICE"  => $value->Price,
                            "GW_CLOSE_PRICE" => $value->PriceGateway,
                            "MODIFY_TIME"    => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }
    
            // Update page progress
            $total_mt_page = $meta_account->page + count($orders);
            $meta_account->page = $total_mt_page;
            $meta_account->save();
    
        } else {
            // No trades found for this page
            // (If you don't want to log this, just skip)
                
        }
    

        // // Mark temporary account as processed
        DB::table('temporary_accounts')->where('id', $temp->id)->update(['status' => 0]);
        return [
            'account_id'    => $meta_account->id,
            'total_result'  => $total_result,
            'page_processed'=> $meta_account->page
        ];
    }


    public function pull_trade()
    {
        //Get data from auto helper
        $auto_helper = DB::table('auto_helpers')->where('relation', 'pulling_trades')->first();

        // $next_id = 38;
        $next_id = $auto_helper->next;

        $table = "";
        $table_data = "";
        $table_end = "";

        $mt5api = new Mt5WebApi();

        //Account Information
        $meta_account = TradingAccount::find($next_id);

        if ($meta_account) {
            $login = $meta_account->account_number;

            //$from_timestamp = strtotime("- 1 years");
            //$to_timestamp = time();

            $from = "2023-07-01";
            if ($from != "") {
                $date = new DateTime($from);
                $from_timestamp = $date->getTimestamp();
            }

            $to = date("Y-m-d");
            //$to = "2022-06-02";
            $to = date('Y-m-d', strtotime($to . ' + 1 days'));
            if ($to != "") {
                $date = new DateTime($to);
                $to_timestamp = $date->getTimestamp();
            }

            $rpp = 100;
            $index = $meta_account->page;

            //PREPARATION FOR MAKE NEW FORMAT DATA
            $data = array();
            $i = 1;
            $orders = [];
            $action = 'DealGetPage';
            $data = array(
                "Login" => (int) $login,
                'From' => $from_timestamp,
                'To' => $to_timestamp,
                'Offset' => $index,
                'Total' => $rpp
            );

            $result = $mt5api->execute($action, $data);
            // return $result;
            $total_result = 0;
            if ($result) {
                if (isset($result['success'])) {
                    if ($result['success']) {
                        $orders = (array) $result['data']['Deals'];
                        $total_result = $result['data']['Total'];
                        // echo "success|$login|$next_id|$total_result";
                        // echo json_encode($orders);
                    } else {
                        if (!isset($result['error'])) {
                            // echo "faild";
                        } else {
                            // echo "false|$login|$next_id";
                        }
                    }
                } else {
                    // echo "faild";
                }
            } else {
                // echo "faild";
            }
            if ($orders) {
                // print_r($orders);
                foreach ($orders as $key => $value) {


                    $open_time = date("Y-m-d H:i:s", $value->Time);
                    $close_time = date("Y-m-d H:i:s", $value->Time);

                    $order_num      = $value->Order;
                    $login          = $value->Login;
                    $t_open_time    = $open_time;
                    $t_close_time   = $close_time;
                    $symbol         = $value->Symbol;
                    $volume         = $value->Volume / 100;
                    $open_price     = $value->Price;
                    $close_price    = $value->Price;
                    $profit         = $value->Profit;
                    $comment        = $value->Comment;
                    $cmd            = $value->Action;
                    $state          = $value->Entry;
                    $PositionID     = $value->PositionID;

                    //Find the Get actual trade by reference
                    $atf = "Yes";
                    if ($PositionID != $order_num) {
                        // echo 'first condition';
                        $actual_trade = Mt5Trade::where('TICKET', $PositionID)->first();
                        if ($actual_trade) {
                            $atf = "NO | Found #" . $PositionID . " !Updated By Reference #" . $order_num;
                            $actual_trade->CLOSE_TIME = $t_close_time;
                            $actual_trade->CLOSE_PRICE = $close_price;
                            $actual_trade->PROFIT = $profit;
                            $actual_trade->DEAL = $value->Deal;
                            $actual_trade->save();
                        }
                    }

                    if ($PositionID == $order_num && ($cmd == 0 || $cmd == 1)) {
                        // echo 'second condition';
                        Mt5Trade::updateOrCreate(['TICKET' => $order_num, 'LOGIN' => $login], [
                            "SYMBOL" => $symbol,
                            "DIGITS" => $value->Digits,
                            "CMD" => $cmd,
                            "VOLUME" => $volume,
                            "OPEN_TIME" => $t_open_time,
                            "OPEN_PRICE" => $open_price,
                            "SL" => $value->PriceSL,
                            "TP" => $value->PriceTP,
                            "CLOSE_TIME" => '1970-01-01 00:00:00',
                            "EXPIRATION" => 0,
                            "REASON" => $value->Reason,
                            "DEAL" => $value->Deal,
                            "CONV_RATE1" => 0,
                            "CONV_RATE2" => 0,
                            "COMMISSION" => 0,
                            "COMMISSION_AGENT" => 0,
                            "SWAPS" => 0,
                            "CLOSE_PRICE" => $close_price,
                            "PROFIT" => $profit,
                            "TAXES" => 0,
                            "COMMENT" => $comment,
                            "INTERNAL_ID" => $PositionID,
                            "MARGIN_RATE" => 0,
                            "TIMESTAMP" => $value->Time,
                            "MAGIC" => 0,
                            "GW_VOLUME" => $value->Volume,
                            "GW_OPEN_PRICE" => $value->Price,
                            "GW_CLOSE_PRICE" => $value->PriceGateway,
                            "MODIFY_TIME" => date("Y-m-d h:i:s")
                        ]);
                    }
                }

                if (($meta_account->page + count($orders)) == $total_result) {
                    $next_id++;
                }
                $meta_account->page = ($meta_account->page + count($orders));
                $meta_account->save();

                // if ($total_result < $rpp) {
                //     $next_id++;
                // }
            } else {
                //Update next id
                $next_id++;
            }
        } else {
            $next_id++;
        }

        //Update auto helper
        $count_account = TradingAccount::latest()->first();
        $update_id = $next_id;

        if ($next_id >= $count_account->id) {
            $first_account = TradingAccount::select('id')->first();
            $update_id = $first_account->id;
        }
        $update = DB::table('auto_helpers')->where('relation', 'pulling_trades')->update([
            'next' => $update_id
        ]);
        // return $orders;
        return ($next_id . ' <br>' . $meta_account->page + count($orders) . ' <br>' . $total_result . '<br>');
    }
}
