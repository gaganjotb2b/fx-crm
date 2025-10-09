<?php

namespace App\Services\commission;

use App\Models\CommissionStatus;
use App\Models\CustomCommission;
use App\Models\IbCommissionStructure;
use App\Models\IbIncome;
use App\Models\User;
use App\Services\IbService;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class IbCommissionVersionTwo
{
    protected $prefix;

    public function __construct()
    {
        $this->prefix = DB::getTablePrefix();
    }

    public static function create_commission()
    {
        try {
            $log = [];
            $result = (new self)->get_mt5_trades();
            $status = 'UNKNOWN';
            $commission_status = [];
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

                            foreach ($all_parent_ib as $parent_ib) {
                                // get IB level for each IB
                                $ib_level = LevelService::get_level($parent_ib, $value->trader_id);
                                if ($ib_level <= 5) {
                                    // -------------------------------
                                    // ib commission strtucture 
                                    // -------------------------------
                                    $ib_commission_structure = IbCommissionStructure::where([
                                        ['symbol', $value->SYMBOL],
                                        ['client_group_id', $value->group_id],
                                        ['ib_group_id', self::get_ib_group_id($parent_ib)]
                                    ])->whereNot('status', 2)->first();

                                    if ($ib_commission_structure) {
                                        $commissions = json_decode($ib_commission_structure->commission);
                                        $level_com = 0;
                                        $total_ib = count(IbService::all_parents($value->trader_id)); // Number of elements in the subarray
                                        if ($total_ib >= count($commissions)) {
                                            $desiredSubarray = $commissions;
                                        } else {
                                            // -------------------------------
                                            // custom commission
                                            // -------------------------------
                                            $custom_com = CustomCommission::where('commission_id', $ib_commission_structure->id)->get();
                                            $custom_com_array = [];
                                            foreach ($custom_com as $com_value) {
                                                $sub_array = json_decode($com_value->custom_commission);
                                                $sub_array = array_filter($sub_array, 'is_numeric');
                                                $custom_com_array[] = $sub_array;
                                            }
                                            $custom_com = array_filter($custom_com_array, function ($subArray) use ($total_ib) {
                                                return count($subArray) === $total_ib;
                                            });
                                            $desiredSubarray = null;

                                            foreach ($custom_com as $subArray) {
                                                if (count($subArray) == $total_ib) {
                                                    $desiredSubarray = $subArray;
                                                    break;
                                                }
                                            }
                                        }

                                        if (isset($desiredSubarray) && is_array($desiredSubarray) && isset($desiredSubarray[$ib_level - 1])) {
                                            $level_com = $desiredSubarray[$ib_level - 1]; // resulted commission of level
                                        }
                                        // ----------------------------------
                                        // check time ignore
                                        // ----------------------------------
                                        if ((new self)->checkTradeTime($ib_commission_structure->timing, $value->OPEN_TIME, $value->CLOSE_TIME)) {
                                            // ----------------------------------
                                            // check zero commission
                                            // ----------------------------------
                                            if ($level_com <= 0) {
                                                $status = 'ZERO COMMISSION';
                                                $log[$parent_ib] = ["ZERO COMMISSION"];
                                            } else {
                                                $amount = ($value->VOLUME / 100) * $level_com;
                                                $log[$parent_ib] = ["CREDITED"];
                                                IbIncome::updateOrCreate(
                                                    [
                                                        'ib_id' => $parent_ib,
                                                        'trader_id' => $value->trader_id,
                                                        'order_num' => $value->TICKET,
                                                    ],
                                                    [
                                                        'trading_account' => $value->LOGIN,
                                                        'symbol' => $value->SYMBOL,
                                                        'cmd' => $value->CMD,
                                                        'volume' => $value->VOLUME,
                                                        'profit' => $value->PROFIT,
                                                        'open_time' => $value->OPEN_TIME,
                                                        'close_time' => $value->CLOSE_TIME,
                                                        'comment' => $value->COMMENT,
                                                        'amount' => $amount,
                                                        'com_level' => $ib_level,
                                                        'level_com' => $level_com,
                                                        'total_ibs' => $total_ib,
                                                        'account_group' => $value->group_id,
                                                        'ip' => request()->ip(),
                                                        'ib_group' => self::get_ib_group_id($parent_ib)
                                                    ]
                                                );
                                                $status = 'CREDITED';
                                                $commission_status[] = [
                                                    'ticket' => $value->TICKET,
                                                    'ib' => $parent_ib,
                                                    'trader' => $value->trader_id,
                                                    'login' => $value->LOGIN,
                                                    'log' => json_encode($log),
                                                    'status' => $status,
                                                    'open_time' => $value->OPEN_TIME,
                                                    'close_time' => $value->CLOSE_TIME,
                                                    'created_at' => date('Y-m-d h:i:s', strtotime(now())),
                                                    'updated_at' => date('Y-m-d h:i:s', strtotime(now()))
                                                ];
                                            }
                                        } else {
                                            $status = "TIME_IGNORE";
                                            $commission_status[] = [
                                                'ticket' => $value->TICKET,
                                                'ib' => $parent_ib,
                                                'trader' => $value->trader_id,
                                                'login' => $value->LOGIN,
                                                'log' => json_encode($log),
                                                'status' => $status,
                                                'open_time' => $value->OPEN_TIME,
                                                'close_time' => $value->CLOSE_TIME,
                                                'created_at' => date('Y-m-d h:i:s', strtotime(now())),
                                                'updated_at' => date('Y-m-d h:i:s', strtotime(now()))
                                            ];
                                        }
                                    } else {
                                        $status = 'COMMISSION NOT FOUND';
                                        $log[$parent_ib] = ["COMMISSION NOT FOUND"];
                                        $commission_status[] = [
                                            'ticket' => $value->TICKET,
                                            'ib' => $parent_ib,
                                            'trader' => $value->trader_id,
                                            'login' => $value->LOGIN,
                                            'log' => json_encode($log),
                                            'status' => $status,
                                            'open_time' => $value->OPEN_TIME,
                                            'close_time' => $value->CLOSE_TIME,
                                            'created_at' => date('Y-m-d h:i:s', strtotime(now())),
                                            'updated_at' => date('Y-m-d h:i:s', strtotime(now()))
                                        ];
                                    }
                                } else {
                                    $status = 'LEVEL EXITED';
                                    $log[$parent_ib] = ["LEVEL EXITED"];
                                    $commission_status[] = [
                                        'ticket' => $value->TICKET,
                                        'ib' => $parent_ib,
                                        'trader' => $value->trader_id,
                                        'login' => $value->LOGIN,
                                        'log' => json_encode($log),
                                        'status' => $status,
                                        'open_time' => $value->OPEN_TIME,
                                        'close_time' => $value->CLOSE_TIME,
                                        'created_at' => date('Y-m-d h:i:s', strtotime(now())),
                                        'updated_at' => date('Y-m-d h:i:s', strtotime(now()))
                                    ];
                                }
                            }
                        } else {
                            $status = "IB NOT FOUND";
                            $commission_status[] = [
                                'ticket' => $value->TICKET,
                                'ib' => $value->ib_id,
                                'trader' => $value->trader_id,
                                'login' => $value->LOGIN,
                                'log' => json_encode($log),
                                'status' => $status,
                                'open_time' => $value->OPEN_TIME,
                                'close_time' => $value->CLOSE_TIME,
                                'created_at' => date('Y-m-d h:i:s', strtotime(now())),
                                'updated_at' => date('Y-m-d h:i:s', strtotime(now()))
                            ];
                        }
                    }
                } else {
                    $status = "NO_IB";
                    $commission_status[] = [
                        'ticket' => $value->TICKET,
                        'ib' => $value->ib_id,
                        'trader' => $value->trader_id,
                        'login' => $value->LOGIN,
                        'log' => json_encode($log),
                        'status' => $status,
                        'open_time' => $value->OPEN_TIME,
                        'close_time' => $value->CLOSE_TIME,
                        'created_at' => date('Y-m-d h:i:s', strtotime(now())),
                        'updated_at' => date('Y-m-d h:i:s', strtotime(now()))
                    ];
                }
                // create commission status

                CommissionStatus::insert($commission_status);
                $commission_status = []; // empty the commission status 
            }
            return $status;
        } catch (\Throwable $th) {
            throw $th;
            // return 0;
        }
    }
    public static function get_ib_group_id($ib_id)
    {
        $user = User::where('id', $ib_id)->select('ib_group_id')->first();
        $ib_group = $user->ib_group_id;
        return $ib_group;
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
    public static function get_trades()
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
                    (new self)->prefix . 'trading_accounts.user_id as trader_id',
                    (new self)->prefix . 'trading_accounts.group_id',
                    (new self)->prefix . 'ib.ib_id'
                )
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('xf_commission_status')
                        ->whereColumn('MT4_TRADES.TICKET', '=', 'xf_commission_status.ticket');
                })
                ->where(function ($query) {
                    return $query->where('MT4_TRADES.CMD', 0)
                        ->orWhere('MT4_TRADES.CMD', 1);
                })->where('MT4_TRADES.TICKET', '!=', 0)
                ->where('MT4_TRADES.SYMBOL', '!=', '')
                ->where('MT4_TRADES.OPEN_TIME', '!=', '1970-01-01 00:00:00')
                ->where('MT4_TRADES.CLOSE_TIME', '!=', '1970-01-01 00:00:00')
                ->whereDate('MT4_TRADES.OPEN_TIME', '>=', '2023-12-09')
                ->join((new self)->prefix . 'trading_accounts', 'MT4_TRADES.LOGIN', '=', (new self)->prefix . 'trading_accounts.account_number')
                ->join((new self)->prefix . 'ib', (new self)->prefix . 'trading_accounts.user_id', '=', (new self)->prefix . 'ib.reference_id')
                ->orderBy('MT4_TRADES.TICKET', 'ASC')
                ->limit(200)
                ->get();
            return $trades;
        } catch (\Throwable $th) {
            // throw $th;
            return ([]);
        }
    }
    // ------------------------------
    // get trade from mt5 tables
    // ------------------------------
    public function get_mt5_trades()
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
                    $this->prefix . 'mt5_trades.PROFIT',
                    $this->prefix . 'mt5_trades.CMD',
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
                // ->where($this->prefix . 'mt5_trades.TICKET', '1135531') //need to remove this line after testing
                ->whereDate($this->prefix . 'mt5_trades.OPEN_TIME', '>=', '2023-12-09')
                ->join($this->prefix . 'trading_accounts', $this->prefix . 'mt5_trades.LOGIN', '=', $this->prefix . 'trading_accounts.account_number')
                ->leftJoin($this->prefix . 'ib', $this->prefix . 'trading_accounts.user_id', '=', $this->prefix . 'ib.reference_id')
                ->orderBy($this->prefix . 'mt5_trades.TICKET', 'ASC')
                ->limit(100) // need modify after testing 1 to 100
                ->get();
            return $trades;
        } catch (\Throwable $th) {
            // throw $th;
            return ([]);
        }
    }
}
