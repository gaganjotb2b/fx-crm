<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommissionStatus;
use App\Models\IB;
use App\Models\IbCommissionStructure;
use App\Models\IbIncome;
use App\Models\IbIndividualCommissionStructure;
use App\Models\LogData;
use App\Models\Mt5Trade;
use App\Models\RemainingComSetup;
use App\Models\User;
use App\Services\commission\LevelService;
use App\Services\IbService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TradeCommisionController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            // return $request->all();
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $incomes = IbIncome::where('ib_id', $ib_user->id)->with(['traderInfo' => function ($query) {
                $query->select('id', 'name', 'email');
            }]);
            // filter by order
            if ($request->input('order')) {
                $incomes = $incomes->where('order_num', $request->input('order'));
            }
            // filter by account number
            if ($request->input('account_number')) {
                $incomes = $incomes->where('trading_account', $request->input('account_number'));
            }
            // filter by symbol
            if ($request->input('symbol')) {
                $incomes = $incomes->where('symbol', $request->input('symbol'));
            }
            // filter by open time
            if ($request->input('open_time')) {
                $to  = Carbon::parse($request->input('open_time'));
                $incomes = $incomes->whereDate('open_time', '>=', $to);
            }
            // filter by close time
            if ($request->input('close_time')) {
                $to  = Carbon::parse($request->input('close_time'));
                $incomes = $incomes->whereDate('close_time', '>=', $to);
            }
            // total volume
            $sum = clone $incomes;
            $total_commission = $sum->sum('amount');
            $total_volume = $sum->sum('volume');

            $incomes = $incomes->paginate(6);
            if ($incomes) {
                return Response::json([
                    'status' => true,
                    'total_volume' => (float)$total_volume,
                    'total_commission' => '$ ' . $total_commission,
                    'commissions' => $incomes
                ]);
            }
            return Response::json([
                'status' => false,
                'commissions' => [],
                'message' => 'Data not available'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store_trade(Request $request)
    {
        $apiKey = $request->header('x-api-key');
        // if ($apiKey !== "api-key") {
        //     return Response::json([
        //         'status'=>false,
        //         'message'=>'Invalid api key'
        //     ]);
        // }
        $create = Mt5Trade::create([
            'SYMBOL' => $request->Symbol,
            'DIGITS' => (int)$request->Digits,
            'CMD' => $request->Action,
            'LOGIN' => $request->Login,
            'DEAL' => (int)$request->Dealer,
            'OPEN_TIME' => date("Y-m-d H:i:s", $request->TimeSetup),
            'OPEN_PRICE' => (float)$request->PriceOrder,
            'SL' => (float)$request->PriceSL,
            'TP' => (float)$request->PriceTP,
            'CLOSE_TIME' => date('Y-m-d H:i:s', strtotime("1970-01-01 00:00:00")),
            'EXPIRATION' => (int)$request->ExpertID,
            'REASON' => (int)$request->Reason,
            'COMMENT' => (string)$request->Comment,
            'TIMESTAMP' => (int)$request->TimeSetup,
            // 'MODIFY_TIME' => $request->ActivationTime,
            'TICKET' => $request->Order,
            'VOLUME' => $request->Volume / 100,
            'PROFIT' => $request->Profit,
            // 'all_data' => json_encode(['status' => 'position add'])
        ]);
        return Response::json([
            'status' => true,
            'message' => 'Data successfully stored'
        ]);
    }
    public function store_closed_trade(Request $request)
    {
        try {
            $trade = Mt5Trade::where([
                'TICKET' => (int)$request->Order,
                'LOGIN' => (int)$request->Login,
            ])->with('account', 'account.user', 'account.user.parentIb')->first();
            if ($trade) {
                $trade->DEAL = $request->Deal;
                $trade->PROFIT = $request->Profit;
                $trade->CLOSE_TIME = date('Y-m-d H:i:s', $request->Time);
                $trade->CLOSE_PRICE = $request->Price;
                // $trade->all_data = json_encode($request->all());
                $update = $trade->save();
            }
            // LogData::create(['log' => json_encode($request->all()), 'type' => 'make commission']);
            // return $trade;
            // make commission
            // ------------------------------------
            $account = $trade->account;
            if ($account != "") {

                $trader = $trade->account->user;
                $instant_ib = $trade->account->user->parentIb ?? "";
                // 
                // check has instant IB
                if ($instant_ib == "") {
                    // LogData::create(['log' => json_encode($instant_ib), 'type' => 'no ib']);
                    CommissionStatus::create([
                        'ticket' => $trade->TICKET,
                        'trader' => $trader->id,
                        'login' => $trade->LOGIN,
                        'status' => 'NO IB',
                        'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
                        'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
                        'tradeable_type' => 'App\Models\Mt5Trade'
                    ]);
                    return Response::json([
                        'status' => true,
                        'message' => 'NO IB'
                    ]);
                }
                // check trade is deleted
                if ($trade->COMMENT == 'deleted [no money]') {
                    CommissionStatus::create([
                        'ticket' => $trade->TICKET,
                        'trader' => $trader->id,
                        'login' => $trade->LOGIN,
                        'status' => 'CANCELED',
                        'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
                        'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
                        'tradeable_type' => 'App\Models\Mt5Trade'
                    ]);
                    return Response::json([
                        'status' => true,
                        'message' => 'CANCELED'
                    ]);
                }
                if ($trade->account?->commission_status==0) {
                    CommissionStatus::create([
                        'ticket' => $trade->TICKET,
                        'trader' => $trader->id,
                        'login' => $trade->LOGIN,
                        'status' => 'SUSPENDED',
                        'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
                        'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
                        'tradeable_type' => 'App\Models\Mt5Trade'
                    ]);
                    return Response::json([
                        'status' => true,
                        'message' => 'SUSPENDED'
                    ]);
                }
                //FIND Related IB
                //Commissions IBs
                $all_parent_ib_with_master = IbService::all_parents_with_master($instant_ib->id);
                $master = array_filter($all_parent_ib_with_master, fn ($item) => $item['is_master'] === true);
                if (count($all_parent_ib_with_master)) {
                    $master_id = !empty($master) ? reset($master)['id'] : null;
                    $masterCommissionStatus  = !empty($master) ? reset($master)['individual_commission'] : null;
                } else {
                    $master_id = $instant_ib->id;
                    $masterCommissionStatus = $instant_ib->individual_commission ?? null;
                }

                $all_parent_ib = array_map(fn ($item) => $item['id'], $all_parent_ib_with_master);
                LogData::create(['log' => json_encode($all_parent_ib_with_master), 'type' => 'make commission master status']);


                array_push($all_parent_ib, $instant_ib->id);
                $total_ib = count($all_parent_ib);
                // return $all_parent_ib;
                // return $total_ib;
                $structure = [];
                foreach ($all_parent_ib as $value) {
                    $ib_level = LevelService::get_level($value, $trader->id);
                    $ib_group_id = $this->get_ib_group_id($value);
                    $ib_commission_structure = IbCommissionStructure::where([
                        ['symbol', $trade->SYMBOL],
                        ['client_group_id', $account->group_id],
                        // ['ib_group_id', $ib_group_id],
                    ])->with('customCommission')->whereNot('status', 2)->first();
                    // $admin_structure = IbCommissionStructure::where([
                    //     ['symbol', $trade->SYMBOL],
                    //     ['client_group_id', $account->group_id],
                    //     ['ib_group_id', $ib_group_id],
                    // ])->with('customCommission')->whereNot('status', 2)->first();
                    if (!$ib_commission_structure) {
                        CommissionStatus::create([
                            'ticket' => $trade->TICKET,
                            'ib' => $value,
                            'trader' => $trader->id,
                            'login' => $trade->LOGIN,
                            'status' => 'COMMISSION NOT FOUND',
                            'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
                            'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
                            'tradeable_type' => 'App\Models\Mt5Trade'
                        ]);
                        continue;
                    }
                    // check 
                    if ($ib_commission_structure) {
                        // finding the level commission
                        // -----------------------------------------
                        $commissions = json_decode($ib_commission_structure->commission);
                        $level_com = 0;
                        if ($total_ib >= count($commissions)) {
                            $desiredSubarray = $commissions;
                        } else {
                            // -------------------------------
                            // custom commission
                            // -------------------------------
                            $custom_com = $ib_commission_structure->customCommission;
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

                        if (!$this->checkTradeTime($ib_commission_structure->timing, $trade->OPEN_TIME, $trade->CLOSE_TIME)) {
                            CommissionStatus::create([
                                'ticket' => $trade->TICKET,
                                'ib' => $value,
                                'trader' => $trader->id,
                                'login' => $trade->LOGIN,
                                'status' => 'TIME IGNORE',
                                'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
                                'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
                            ]);
                            continue;
                        }
                        // ending the level commission
                        // ----------------------------------------------

                        if ($level_com <= 0) {
                            CommissionStatus::create([
                                'ticket' => $trade->TICKET,
                                'ib' => $value,
                                'trader' => $trader->id,
                                'login' => $trade->LOGIN,
                                'status' => 'ZERO COMMISSION',
                                'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
                                'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
                                'tradeable_type' => 'App\Models\Mt5Trade'
                            ]);
                            continue;
                        } else {
                            $amount = ($trade->VOLUME / 100) * $level_com;
                            IbIncome::updateOrCreate(
                                [
                                    'ib_id' => $value,
                                    'trader_id' => $trader->id,
                                    'order_num' => $trade->TICKET,
                                ],
                                [
                                    'trading_account' => $trade->LOGIN,
                                    'symbol' => $trade->SYMBOL,
                                    'cmd' => $trade->CMD,
                                    'volume' => $trade->VOLUME,
                                    'profit' => $trade->PROFIT,
                                    'open_time' => $trade->OPEN_TIME,
                                    'close_time' => $trade->CLOSE_TIME,
                                    'comment' => $trade->COMMENT,
                                    'amount' => $amount,
                                    'com_level' => $ib_level,
                                    'level_com' => $level_com,
                                    'total_ibs' => $total_ib,
                                    'account_group' => $account->group_id,
                                    'ip' => request()->ip(),
                                    'ib_group' => $ib_group_id
                                ]
                            );
                            CommissionStatus::create([
                                'ticket' => $trade->TICKET,
                                'ib' => $value,
                                'trader' => $trader->id,
                                'login' => $trade->LOGIN,
                                'status' => 'CREDITED',
                                'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
                                'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
                                'tradeable_type' => 'App\Models\Mt5Trade'
                            ]);
                            continue;
                        }
                    } else {
                        CommissionStatus::create([
                            'ticket' => $trade->TICKET,
                            'ib' => $value,
                            'trader' => $trader->id,
                            'login' => $trade->LOGIN,
                            'status' => 'COMMISSION NOT FOUND',
                            'open_time' => date('Y-m-d H:i:s', strtotime($trade->OPEN_TIME)),
                            'close_time' => date('Y-m-d H:i:s', strtotime($trade->CLOSE_TIME)),
                            'tradeable_type' => 'App\Models\Mt5Trade'
                        ]);
                        continue;
                    }
                }
            }

            return Response::json([
                'status' => true,
                'message' => 'Deal successfully stored',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            // return $th->getMessage();
            LogData::create(['log' => json_encode($th->getMessage()), 'type' => 'make commission err']);
        }
    }
    public function checkTradeTime($COM_TIME, $OPEN_TIME, $CLOSE_TIME)
    {
        $time_input = $COM_TIME;
        $timing = explode(":", $time_input);
        // $in_hou = $timing[0] * 60;
        $in_min = $timing[0] * 60;
        $in_sec = $timing[1];

        $sec = $in_min + $in_sec;

        $open_time_ob = new DateTime($OPEN_TIME);
        $close_time_ob = new DateTime($CLOSE_TIME);
        $diff_in_sencond = $close_time_ob->getTimestamp() - $open_time_ob->getTimestamp();

        if ($diff_in_sencond >= $sec) {
            return true;
        }

        return false;
    }
    public function get_ib_group_id($ib_id)
    {
        try {
            $user = User::where('id', $ib_id)->select('ib_group_id')->first();
            $ib_group = $user->ib_group_id;
            return $ib_group;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function remaining_setup()
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
    public function client_remaining_com($ib_id)
    {
        try {
            if ($this->remaining_setup()) {
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


    public function get_level($ib_id, $reference_id, $level = 0)
    {
        try {
            if ($ib_id == $reference_id) {
                $ib = IB::where('ib_id', $ib_id)->with('ibDetails')->first();
                return [
                    'level' => $level,
                    'ib_id' => $ib_id
                ];
            }
            $ib = IB::where('ib_id', $ib_id)->with('ibDetails')->get();
            if ($ib) {
                foreach ($ib as $value) {
                    $result = $this->get_level($value->reference_id, $reference_id, $level + 1);
                    if ($result !== false) {
                        return $result;
                    }
                }
            }
            return false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
