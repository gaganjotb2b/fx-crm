<?php

namespace App\Services\commission;

use App\Models\CommissionStatus;
use App\Models\CustomCommission;
use App\Models\IB;
use App\Models\IbCommissionStructure;
use App\Models\IbIncome;
use App\Models\IbSetup;
use App\Models\RemainingComSetup;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\IbService;
use App\Services\systems\PlatformService;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class IbCommissionService
{
    private $prefix;
    public function __construct()
    {
        $this->prefix = DB::getTablePrefix();
    }
    public static function remaining_setup()
    {
        try {
            $result = RemainingComSetup::first();
            if ($result->remaining === 'true') {
                return true;
            } elseif ($result->remaining === 'false') {
                return false;
            }
            return false;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
    public static function client_remaining_com($ib_id)
    {
        try {
            if (self::remaining_setup()) {
                $result = User::where('id', $ib_id)->select('remaining_com')->first();
                if ($result->remaining_com == 1) {
                    return true;
                }
                return false;
            }
            return false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public static function commission_count()
    {
        // try {
        // return 'true';
        $log = $result = [];
        $status = '';
        // check platform is mt4/mt5
        if (strtolower(PlatformService::get_platform()) === 'mt4') {
            $result = (new self)->get_mt4_trade();
        } else {
            $result = (new self)->get_mt5_trade();
        }
        return $result;
        foreach ($result as $value) {

            if ($value->ib_id) {

                // Test if string contains the word 
                if ($value->COMMENT == 'deleted [no money]') {
                    $status = 'CANCELED';
                } else if (strpos($value->COMMENT, 'from #') !== false) {
                    $status = 'PARTIALLY CLOSED';
                } else if (strpos($value->COMMENT, 'to #') !== false) {
                    $status = 'PARTIALLY CLOSED';
                } else {
                    //FIND Related IB
                    //Commissions IBs

                    $all_parent_ib = IbService::all_parents($value->ib_id);
                    array_push($all_parent_ib, $value->ib_id);
                    if (count($all_parent_ib)) {

                        foreach ($all_parent_ib as $key => $parent_ib) {
                            $response_commission = self::create_commission([
                                'ib_id' => $parent_ib,
                                'group_id' => $value->group_id,
                                'symbol' => $value->SYMBOL,
                                'open_time' => $value->OPEN_TIME,
                                'close_time' => $value->CLOSE_TIME,
                                'volume' => $value->VOLUME,
                                'user_id' => $value->trader_id,
                                'ticket' => $value->TICKET,
                                'login' => $value->LOGIN,
                            ]);
                            $status = $response_commission['status'];
                        }
                    } else {
                        $status = "IB_NOTFOUND";
                    }
                }
            } else {
                $status = "NO_IB";
            }

            // create commission status
            $create_status = CommissionStatus::create([
                'ticket' => $value->TICKET,
                'ib' => $value->ib_id,
                'trader' => $value->trader_id,
                'login' => $value->LOGIN,
                'log' => json_encode($log),
                'status' => $status,
            ]);
        }
        return $status;
        // } catch (\Throwable $th) {
        //     throw $th;
        // }
    }
    public static function create_commission($data = [])
    {
        try {
            $log = [];
            $ib_level = LevelService::get_level($data['ib_id'], $data['user_id']);
            // check for lite crm, only generate 5 level commission
            if($ib_level>5){
                return [
                    'status' => 'IB_LEVEL_EXITED',
                    'log' => $data,
                ];
            }
            // find ib commission structure 
            $ib_commission_structure = IbCommissionStructure::where([
                ['symbol', $data['symbol']],
                ['client_group_id', $data['group_id']],
                ['ib_group_id', self::get_ib_group_id($data['ib_id'])]
            ])->first();
            if ($ib_commission_structure) {
                if ((new self)->checkTradeTime($ib_commission_structure->timing, $data['open_time'], $data['close_time'])) {
                    $level_com = self::level_commission($ib_commission_structure->id, $data['user_id'], $data['ib_id'], $ib_level);
                    if ($level_com <= 0) {
                        $status = 'ZERO_COMMISSION';
                        $log[$data['ib_id']] = ["ZERO_COMMISSION"];
                    } else {
                        $amount = ($data['volume'] / 100) * $level_com;
                        $log[$data['ib_id']] = ["CREDITED"];
                        IbIncome::updateOrCreate(
                            [
                                'ib_id' => $data['ib_id'],
                                'trader_id' => $data['user_id'],
                                'order_num' => $data['ticket'],
                            ],
                            [
                                'trading_account' => $data['login'],
                                'symbol' => $data['symbol'],
                                'volume' => $data['volume'],
                                'amount' => $amount,
                                'com_level' => $ib_level,
                                'level_com' => $level_com,
                                'ib_group' => self::get_ib_group_id($data['ib_id']),
                                'created_at' => $data['close_time'],
                            ]
                        );
                        // update total ib income

                        $status = 'CREDITED';
                    }
                } else {
                    $status = "TIME_IGNORE";
                }
            } else {
                $status = 'COMMISSION_NOTFOUND';
                $log[$data['ib_id']] = ["COMMISSION_NOTFOUND"];
            }
            return [
                'status' => $status,
                'log' => $log,
            ];
        } catch (\Throwable $th) {
            throw $th;
            // return 0;
        }
    }
    // get level commission
    public static function level_commission($commission_structure, $trader_id, $ib_id, $ib_level)
    {
        try {
            $result = IbCommissionStructure::find($commission_structure);
            $commissions = json_decode($result->commission);
            // check custom commission is on/off
            if (self::client_remaining_com($ib_id)) {
                $custom_com = CustomCommission::where('commission_id', $result->id)->get();
                $custom_com_array = [];
                foreach ($custom_com as $value) {
                    $sub_array = json_decode($value->custom_commission);
                    $sub_array = array_filter($sub_array, 'is_numeric');
                    $custom_com_array[] = $sub_array;
                }
                $level_com = 0;
                $total_ib = count(IbService::all_parents($trader_id)); // Number of elements in the subarray
                $custom_com = array_filter($custom_com_array, function ($subArray) use ($total_ib) {
                    return count($subArray) === $total_ib;
                });
                $desiredSubarray = null;

                foreach ($custom_com as $subArray) {
                    if (count($subArray) === $total_ib) {
                        $desiredSubarray = $subArray;
                        break;
                    }
                }
                $level_com = $desiredSubarray[$ib_level - 1];
                return $level_com;
            }
            return $commissions[$ib_level - 1];
        } catch (\Throwable $th) {
            throw $th;
            return 0;
        }
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
    // get mt4 trade
    public function get_mt4_trade()
    {
        try {
            $trades = DB::connection('alternate')->table('MT4_TRADES')
                ->select(
                    'MT4_TRADES.COMMENT',
                    'MT4_TRADES.SYMBOL',
                    'MT4_TRADES.OPEN_TIME',
                    'MT4_TRADES.CLOSE_TIME',
                    'MT4_TRADES.VOLUME',
                    'MT4_TRADES.TICKET',
                    'MT4_TRADES.LOGIN',
                    $this->prefix . 'trading_accounts.user_id as trader_id',
                    $this->prefix . 'trading_accounts.group_id',
                    $this->prefix . 'ib.ib_id'
                )
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from($this->prefix . 'commission_status')
                        ->whereColumn('MT4_TRADES.TICKET', '=', $this->prefix . 'commission_status.ticket');
                })
                ->where(function ($query) {
                    return $query->where('MT4_TRADES.CMD', 0)
                        ->orWhere('MT4_TRADES.CMD', 1);
                })->where('MT4_TRADES.TICKET', '!=', 0)
                ->where('MT4_TRADES.SYMBOL', '!=', '')
                ->where('MT4_TRADES.OPEN_TIME', '!=', '1970-01-01 00:00:00')
                ->where('MT4_TRADES.CLOSE_TIME', '!=', '1970-01-01 00:00:00')
                ->whereDate('MT4_TRADES.OPEN_TIME', '>=', '2023-02-27')
                ->join($this->prefix . 'trading_accounts', 'MT4_TRADES.LOGIN', '=', $this->prefix . 'trading_accounts.account_number')
                ->leftJoin($this->prefix . 'ib', $this->prefix . 'trading_accounts.user_id', '=', $this->prefix . 'ib.reference_id')
                ->orderBy('MT4_TRADES.TICKET', 'ASC')
                ->limit(20)
                ->get();
            return $trades;
        } catch (\Throwable $th) {
            throw $th;
            return ([]);
        }
    }
    // get ib group id
    public static function get_ib_group_id($ib_id)
    {
        try {
            $user = User::where('id', $ib_id)->select('ib_group_id')->first();
            $ib_group = $user->ib_group_id;
            return $ib_group;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // function for get mt5 trades
    public function get_mt5_trade()
    {
        try {
            $prefix = $this->prefix;
            $trades = DB::connection('alternate')->table($this->prefix . 'mt5_trades')
                ->select(
                    $this->prefix . 'mt5_trades.COMMENT',
                    $this->prefix . 'mt5_trades.SYMBOL',
                    $this->prefix . 'mt5_trades.OPEN_TIME',
                    $this->prefix . 'mt5_trades.CLOSE_TIME',
                    $this->prefix . 'mt5_trades.VOLUME',
                    $this->prefix . 'mt5_trades.TICKET',
                    $this->prefix . 'mt5_trades.LOGIN',
                    $this->prefix . 'trading_accounts.user_id as trader_id',
                    $this->prefix . 'trading_accounts.group_id',
                    $this->prefix . 'ib.ib_id'
                )
                ->whereNotExists(function ($query) use ($prefix) {
                    $query->select(DB::raw(1))
                        ->from($prefix . 'commission_status')
                        ->whereColumn($prefix . 'mt5_trades.TICKET', '=', $prefix . 'commission_status.ticket');
                })
                ->where(function ($query) {
                    return $query->where($this->prefix . 'mt5_trades.CMD', 0)
                        ->orWhere($this->prefix . 'mt5_trades.CMD', 1);
                })->where($this->prefix . 'mt5_trades.TICKET', '!=', 0)
                ->where($this->prefix . 'mt5_trades.SYMBOL', '!=', '')
                ->where($this->prefix . 'mt5_trades.OPEN_TIME', '!=', '1970-01-01 00:00:00')
                ->where($this->prefix . 'mt5_trades.CLOSE_TIME', '!=', '1970-01-01 00:00:00')
                ->whereDate($this->prefix . 'mt5_trades.OPEN_TIME', '>=', '2022-02-27')
                ->join($this->prefix . 'trading_accounts', $this->prefix . 'mt5_trades.LOGIN', '=', $this->prefix . 'trading_accounts.account_number')
                ->leftJoin($this->prefix . 'ib', $this->prefix . 'trading_accounts.user_id', '=', $this->prefix . 'ib.reference_id')
                ->orderBy($this->prefix . 'mt5_trades.TICKET', 'ASC')
                ->limit(100)
                ->get();
            return $trades;
        } catch (\Throwable $th) {
            throw $th;
            return ([]);
        }
    }
}
