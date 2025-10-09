<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use App\Models\IbIncome;
use App\Models\IbSetup;
use App\Models\User;
use App\Models\IbCommissionStructure;
use DateTime;

class IBCommissionCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ibcommission:count';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is ib commission';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->comcount();
        //return 0;
    }


    public function comcount()
    {
        $prefix = DB::getTablePrefix();
        $trades = DB::connection('alternate')->table('MT4_TRADES')
            ->select('MT4_TRADES.*', $prefix . 'commission_status.status', $prefix . '_accounts.user_id', $prefix . 'trading_accounts.group_id', $prefix . 'ib.ib_id', $prefix . 'ib.ib_group_id')
            ->leftJoin($prefix . 'commission_status', 'MT4_TRADES.TICKET', '=', $prefix . 'commission_status.ticket')
            ->join($prefix . 'trading_accounts', 'MT4_TRADES.LOGIN', '=', $prefix . 'trading_accounts.account_number')
            ->leftJoin('vipglobalfx_ib', $prefix . 'accounts.user_id', '=', $prefix . 'ib.reference_id')
            ->where(function ($query) {
                return $query->where('MT4_TRADES.CMD', '=', 0)->orWhere('MT4_TRADES.CMD', '=', 1);
            })->where('MT4_TRADES.TICKET', '!=', 0)
            ->where('MT4_TRADES.SYMBOL', '!=', '')
            ->where('MT4_TRADES.OPEN_TIME', '!=', '1970-01-01 00:00:00')
            ->where('MT4_TRADES.CLOSE_TIME', '!=', '1970-01-01 00:00:00')
            ->whereDate('MT4_TRADES.OPEN_TIME', '>=', '2022-01-01')
            ->whereNull($prefix . 'commission_status.ticket')
            ->orderBy('MT4_TRADES.TICKET', 'ASC')->limit(100)
            ->get();

        $ibsc_sql = array();

        foreach ($trades as $trade) {

            $res['success'] = true;

            $status = 'UNKNOWN';
            $log = [];

            $order_num       = $trade->TICKET;
            $trading_account = $trade->LOGIN; // login
            $ib_id           = $trade->ib_id;
            $trader_id       = $trade->user_id;
            $symbol          = $trade->SYMBOL;
            $volume          = $trade->VOLUME;
            $profit          = $trade->PROFIT;
            $comment         = $trade->COMMENT;
            $cmd             = $trade->CMD;
            $group_id        = $trade->group_id;

            if ($ib_id) {

                // calculation trade duration
                $open_time = strtotime($trade->OPEN_TIME);
                $close_time =  strtotime($trade->CLOSE_TIME);
                $trade_duration = abs(round($close_time - $open_time));

                // Test if string contains the word 
                if ($comment == 'deleted [no money]') {
                    $status = 'CANCELED';
                } else if (strpos($comment, 'from #') !== false) {
                    $status = 'PARTIALLY CLOSED';
                } else if (strpos($comment, 'to #') !== false) {
                    $status = 'PARTIALLY CLOSED';
                } else {

                    //FIND Related IB
                    //Commissions IBs
                    $ibs = $this->getSubIBcom($ib_id);

                    //Total IBs
                    $total_ibs = count($ibs);

                    if ($total_ibs) {

                        $inner_status = "";

                        for ($i = 0; $i < $total_ibs; $i++) {
                            $com_level = $i + 1;
                            $next_ib = $ibs[$i]['subcode'];

                            // find ib group
                            $user = User::where('id', $next_ib)->select('ib_group_id')->first();
                            $ib_group = $user->ib_group_id;

                            // find ib commission structure 
                            $ib_commission_structure = IbCommissionStructure::where([
                                ['symbol', $symbol],
                                ['client_group_id', $group_id],
                                ['ib_group_id', $ib_group]
                            ])->first();

                            //var_dump($ib_commission_structure);

                            if ($ib_commission_structure) {

                                if ($this->checkTradeTime($ib_commission_structure->timing, $trade->OPEN_TIME, $trade->CLOSE_TIME)) {

                                    $commissions = json_decode($ib_commission_structure->commission);

                                    $level_com = (isset($commissions[$i])) ? (float) $commissions[$i] : 0;

                                    if ($level_com < 1) {
                                        if ($com_level == 1) {
                                            $status = 'ZERO_COMMISSION';
                                        }
                                        $log[$next_ib] = ["ZERO_COMMISSION"];
                                    } else {
                                        $amount = ($volume / 100) * $level_com;
                                        $ibsc_sql[$i]['trading_account'] = $trading_account;
                                        $ibsc_sql[$i]['symbol'] = $symbol;
                                        $ibsc_sql[$i]['volume'] = $volume;
                                        $ibsc_sql[$i]['amount'] =  $amount;
                                        $ibsc_sql[$i]['com_level'] = $com_level;
                                        $ibsc_sql[$i]['level_com'] = $level_com;
                                        $ibsc_sql[$i]['ib_group'] = $ib_group;
                                        $ibsc_sql[$i]['created_at'] =  $trade->CLOSE_TIME;

                                        $log[$next_ib] = ["CREDITED"];


                                        IbIncome::updateOrCreate([
                                            'ib_id' => $next_ib,
                                            'trader_id' => $trader_id,
                                            'order_num' => $order_num,
                                        ], $ibsc_sql[$i]);

                                        $status = 'CREDITED';
                                    }
                                } else {
                                    $status = "TIME_IGNORE";
                                    break;
                                }
                            } else {
                                if ($com_level == 1) {
                                    $status = 'COMMISSION_NOTFOUND';
                                }
                                $log[$next_ib] = ["COMMISSION_NOTFOUND"];
                            }
                        }
                    } else {
                        $status = "IB_NOTFOUND";
                    }
                }
            } else {
                $status = "NO_IB";
            }


            DB::table('commission_status')->insert([
                'ticket' => $order_num,
                'ib' => $ib_id,
                'trader' => $trader_id,
                'login' => $trading_account,
                'log' => json_encode($log),
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s')
            ]);


            // echo 'cmd: '.$cmd.'<br/>';
            // echo 'Login: '.$trading_account.'<br/>';
            // echo 'Group: '.$group_id.'<br/>';
            // echo 'IB: '.$ib_id.'<br/>';
            // echo 'Trader: '.$trader_id.'<br/>';
            // echo 'Ticket: '.$order_num.'<br/>';
            // echo 'Volume: '.$volume.'<br/>';
            // echo 'Symbol: '.$symbol.'<br/>';
            // echo 'Open Time: '.$trade->OPEN_TIME.'<br/>';
            // echo 'Close Time: '.$trade->CLOSE_TIME.'<br/>';
            // print_r($log);
            // if($status == 'CREDITED'){
            //     echo 'Status: <b><font color="green">'.$status.'</font></b><br/>';
            // }
            // else{
            //     echo 'Status: <b><font color="red">'.$status.'</font></b><br/>';
            // }
            // echo '<hr/>';
        }
    }

    public function getSubIBcom($sp, $level = null)
    {
        if (!$level) {
            $level = $this->getLevel();
        }
        $spc = $sp;
        $ibsub = array();

        $res = DB::table('ib')->where('reference_id', $spc)->select('ib_id', 'ib_group_id as gpr')->first();

        if ($res) {
            $ibsub[0]['subcode'] = $sp;

            $grs = $res->ib_id;
            if ($grs > 0) {
                $i = 1;
                while ($i <= $level) {
                    $ibsub[$i]['subcode'] = $grs;
                    $res2 = DB::table('ib')->select('ib_id', 'ib_group_id as gpr')->where('reference_id', $grs)->first();

                    if ($res2) {
                        $grs = $res2->ib_id;
                    } else {
                        break;
                    }

                    $i++;
                }
            }
        }

        return $ibsub;
    }

    public function getLevel()
    {
        $ib_setup = IbSetup::first();
        return isset($ib_setup->ib_level) ? ($ib_setup->ib_level - 1) : 0;
    }

    public function checkTradeTime($COM_TIME, $OPEN_TIME, $CLOSE_TIME)
    {
        $time_input = $COM_TIME;
        $timing = explode(":", $time_input);

        $in_hou = $timing[0] * 60;
        $in_min = $timing[1] * 60;
        $in_sec = $timing[1];

        $sec = $in_hou + $in_min + $in_sec;

        $open_time_ob = new DateTime($OPEN_TIME);
        $close_time_ob = new DateTime($CLOSE_TIME);
        $diff_in_sencond = $close_time_ob->getTimestamp() - $open_time_ob->getTimestamp();

        if ($diff_in_sencond >= $sec) {
            return true;
        }

        return false;
    }
}
