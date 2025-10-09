<?php

namespace App\Http\Controllers;

use App\Http\Controllers\admins\IBcommisionStructureController;
use App\Models\ComTrade;
use App\Models\IB;
use App\Models\IbCommissionStructure;
use App\Models\IbIncome;
use App\Models\Traders\Trade;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

class CopyTradeGetController extends Controller
{
    //get copy trade
    public function get_trades(Request $request)
    {
        // check if order already exist in datatabase
        // check if trade not colosed
        $order_exist = Trade::where('ticket', $request->Order)->exists();
        if ($order_exist || date('Y-m-d', strtotime($request->CloseTime)) == '1970-01-01') {
            // return false;
        }
        // insert trade data and generate commission
        $trading_account = TradingAccount::where('account_number', $request->Login)->first();
        if ($trading_account) {
            $ib = IB::where('reference_id', $trading_account->user_id)
                ->join('users', 'users.id', '=', 'ib.ib_id')
                ->first();
            $ib_id  = ($ib) ? $ib->id : null;
            if ($ib) {
                $commission_structure = IbCommissionStructure::where('symbol', $request->Symbol)
                    ->where('client_group_id', $trading_account->group_id)
                    ->where('ib_group_id', $ib->ib_group_id)
                    ->where('status', 1)->first();
                if ($commission_structure) {
                    // check minimum trade duration
                    $open_time = strtotime($request->OpenTime);
                    $close_time = strtotime($request->closeTime);
                    $trade_duration = abs(round($close_time - $open_time));
                    // if minimum trade duration not exists
                    if ($trade_duration < (60 * 60)) {
                        $commission = 0;
                        $status = 'TimeEgnore';
                    } else {
                        $level_commission = AllFunctionService::commission_level(AllFunctionService::get_node_level($ib->ib_id), $commission_structure->id);
                        $commission  = ($level_commission > 0) ? ((($request->Volume) / 100)) / $level_commission : '';
                        //    crete ib income
                        $income_create = IbIncome::create([
                            'ib_id' => $ib_id,
                            'order_num' => $request->Order,
                            'trading_account' => $trading_account->id,
                            'symbol' => $request->Symbol,
                            'cmd' => null,
                            'volume' => $request->Volume,
                            'profit' => $request->Profit,
                            'open_time' => date('Y-m-d h:i:s', strtotime($request->OpenTime)),
                            'close_time' => date('Y-m-d h:i:s', strtotime($request->CloseTime)),
                            'comment' => $request->Comment,
                            'amount' => $commission,
                            'com_level' => AllFunctionService::get_node_level($ib->ib_id),
                            'level_com' => $level_commission,
                            'total_ibs' => null,
                            'account_group' => null,
                        ]);
                    }
                } else {
                    $status = 'Commission Not found';
                    $commission = '';
                }
            } else {
                $status = 'self';
                $commission = '';
            }

            // insert trade data
            $create = Trade::create([
                'ticket' => $request->Order,
                'trading_account' => $trading_account->id,
                'account_no' => $request->Login,
                'symbol' => $request->Symbol,
                'valume' => $request->Volume,
                'open_price' => $request->OpenPrice,
                'close_price' => $request->ClosePrice,
                'cmd' => null,
                'profit' => $request->Profit,
                'comment' => $request->Comment,
                'open_time' => date('Y-m-d h:i:s', strtotime($request->OpenTime)),
                'close_time' => date('Y-m-d h:i:s', strtotime($request->CloseTime)),
                'commission' => $commission,
                'state' => $request->State,
                'expert_position_id' => $request->PositionByID,
                'ib' => $ib_id,
                'type' => $request->Type,
                'status' => $status,
            ]);
        }
        return $create;
    }
}
