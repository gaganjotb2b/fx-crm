<?php

namespace App\Services\trades;

use App\Models\Mt5Trade;
use App\Models\TradingAccount;
use App\Services\Mt5WebApi;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class Mt5TradeRecountService
{
    protected $property1;

    public function __construct($property1 = null)
    {
        $this->property1 = $property1;
    }

    public  static function trade_recount()
    {
        try {
            //Get data from auto helper
            //Get data from auto helper
            $auto_helper = DB::table('auto_helpers')->where('relation', 'pulling_trades')->first();

            $next_id = $auto_helper->next;
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

                // $to = date("Y-m-d");
                $to = "2022-07-01";
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
                // $result = json_decode($result);
                // $result = (array) $result;
                // echo json_encode($result);
                $total_result = 0;
                if ($result) {
                    if (isset($result['success'])) {
                        if ($result['success']) {
                            $orders = (array) $result['data']['Deals'];
                            $total_result = $result['data']['Total'];
                            echo "success|$login|$next_id|$total_result";
                            echo json_encode($orders);
                        } else {
                            if (!isset($result['error'])) {
                                echo "faild";
                            } else {
                                echo "false|$login|$next_id";
                            }
                        }
                    } else {
                        echo "faild";
                    }
                } else {
                    echo "faild";
                }
                if ($orders) {
                    print_r($orders);
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
                            echo 'first condition';
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
                            echo 'second condition';
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


                    $meta_account->page = ($meta_account->page + $total_result);

                    $meta_account->save();

                    if ($total_result < $rpp) {
                        $next_id++;
                    }
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
            return $update_id;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
