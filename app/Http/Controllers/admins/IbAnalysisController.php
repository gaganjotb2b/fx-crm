<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\IB;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IbAnalysisController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:ib analysis"]);
        $this->middleware(["role:ib management"]);
        // system module control
        $this->middleware(AllFunctionService::access('ib_management', 'admin'));
        $this->middleware(AllFunctionService::access('ib_analysis', 'admin'));
    }
    //START: IB analysis view--------------------------------
    public function ib_analysis(Request $request)
    {
        return view('admins.ib-management.ib-analysis');
    }
    // END: IB analysis basic view---------------------------
    // FILTER: IB analysis-----------------------------------
    public function filter(Request $request)
    {
        try {
            $result = User::where('users.id', $request->search_email)
                ->where('users.type', CombinedService::type());
            // check chrm is combined
            if (CombinedService::is_combined()) {
                $result = $result->where('users.combine_access', 1);
            }
            $result = $result->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                ->select('users.name', 'countries.name as country', 'users.email', 'users.id')
                ->first();
            if ($result != null) {
                // total trader
                $total_client = IB::where('ib_id', $result->id)
                    ->join('users', 'ib.ib_id', 'users.id')
                    ->where('users.type',  0);

                if ($request->start_date != '') {
                    $total_client = $total_client->whereDate('ib.created_at', '>=', $request->start_date);
                    $total_client = $total_client->whereDate('ib.created_at', '<=', $request->end_date);
                }

                // Total IB
                $total_ib = IB::where('ib_id', $result->id)
                    ->join('users', 'ib.ib_id', 'users.id')
                    ->where('users.type',  CombinedService::type());
                // check crm is combined
                if (CombinedService::is_combined()) {
                    $total_ib = $total_ib->where('users.combine_access', 1);
                }
                if ($request->start_date != '') {
                    $total_ib = $total_ib->whereDate('ib.created_at', '>=', $request->start_date);
                    $total_ib = $total_ib->whereDate('ib.created_at', '<=', $request->end_date);
                }
                // Trade Volume (Total)
                $total_trade_volume = IB::where("ib.ib_id", $result->id)
                    ->join('trading_accounts', 'ib.reference_id', '=', 'trading_accounts.user_id')
                    ->join('trades', 'trading_accounts.id', '=', 'trades.trading_account')
                    ->where(function ($q) {
                        $q->orWhere('trades.cmd', '=', 0)
                            ->orWhere('trades.cmd', '=', 1);
                    });
                if ($request->start_date != '') {
                    $total_trade_volume = $total_trade_volume->whereDate('trades.created_at', '>=', $request->start_date);
                    $total_trade_volume = $total_trade_volume->whereDate('trades.created_at', '<=', $request->end_date);
                }

                $total_trade_volume = $total_trade_volume->sum('trades.volume');

                // IB Commission (Total)
                $total_ib_commission = IB::where("ib.ib_id", $result->id)
                    ->where('users.type', 4)
                    ->join('users', 'ib.ib_id', '=', 'users.id')
                    ->join('ib_incomes', 'users.id', '=', 'ib_incomes.ib_id');

                if ($request->start_date != '') {
                    $total_ib_commission = $total_ib_commission->whereDate('ib.created_at', '>=', $request->start_date);
                    $total_ib_commission = $total_ib_commission->whereDate('ib.created_at', '<=', $request->end_date);
                }
                // IB Commission (Lot)
                $ib_commission_lot = $total_ib_commission->sum('ib_incomes.volume');
                $total_ib_commission = $total_ib_commission->sum('ib_incomes.amount');

                // Trading Accounts
                $trading_accounts = IB::where("ib.ib_id", $result->id)
                    ->join('trading_accounts', 'ib.reference_id', '=', 'trading_accounts.user_id')
                    ->join('users', 'trading_accounts.user_id', '=', 'users.id')
                    ->where('users.type', 0);
                if ($request->start_date != '') {
                    $trading_accounts = $trading_accounts->whereDate('trading_accounts.created_at', '>=', $request->start_date);
                    $trading_accounts = $trading_accounts->whereDate('trading_accounts.created_at', '<=', $request->end_date);
                }

                // Trade Volume (From IB)
                $trade_volume_ib = IB::where("ib.ib_id", $result->id)
                    ->where('users.type', CombinedService::type());
                // check crm is combined
                if (CombinedService::is_combined()) {
                    $trade_volume_ib = $trade_volume_ib->where('users.combine_access', 1);
                }
                $trade_volume_ib = $trade_volume_ib->join('users', 'ib.reference_id', '=', 'users.id')
                    ->join('trading_accounts', 'ib.reference_id', '=', 'trading_accounts.user_id')
                    ->join('trades', 'trading_accounts.id', '=', 'trades.trading_account');
                if ($request->start_date != '') {
                    $trade_volume_ib = $trade_volume_ib->whereDate('ib.created_at', '>=', $request->start_date);
                    $trade_volume_ib = $trade_volume_ib->whereDate('ib.created_at', '<=', $request->end_date);
                }
                // Trade Volume (Trader)
                $trade_volume_trader = IB::where("ib.ib_id", $result->id)
                    ->where('users.type', 0)
                    ->join('users', 'ib.reference_id', '=', 'users.id')
                    ->join('trading_accounts', 'ib.reference_id', '=', 'trading_accounts.user_id')
                    ->join('trades', 'trading_accounts.id', '=', 'trades.trading_account');
                if ($request->start_date != '') {
                    $trade_volume_trader = $trade_volume_trader->whereDate('ib.created_at', '>=', $request->start_date);
                    $trade_volume_trader = $trade_volume_trader->whereDate('ib.created_at', '<=', $request->end_date);
                }

                // Total Bonus
                $total_bonus = IB::where("ib.ib_id", $result->id)
                    ->join('bonus_users', 'ib.reference_id', '=', 'bonus_users.user_id');
                if ($request->start_date != '') {
                    $total_bonus = $total_bonus->whereDate('bonus_users.created_at', '>=', $request->start_date);
                    $total_bonus = $total_bonus->whereDate('bonus_users.created_at', '<=', $request->end_date);
                }

                // trader deposit
                $trader_deposit = IB::where("ib.ib_id", $result->id)
                    ->where('users.type', 0)
                    ->join('users', 'ib.reference_id', '=', 'users.id')
                    ->join('deposits', 'ib.reference_id', '=', 'deposits.user_id');
                if ($request->start_date != '') {
                    $trader_deposit = $trader_deposit->whereDate('ib.created_at', '>=', $request->start_date);
                    $trader_deposit = $trader_deposit->whereDate('ib.created_at', '<=', $request->end_date);
                }

                // trader withdraw
                $trader_withdraw = IB::where("ib.ib_id", $result->id)
                    ->where('users.type', 0)
                    ->join('users', 'ib.reference_id', '=', 'users.id')
                    ->join('withdraws', 'ib.reference_id', '=', 'withdraws.user_id');
                if ($request->start_date != '') {
                    $trader_withdraw = $trader_withdraw->whereDate('ib.created_at', '>=', $request->start_date);
                    $trader_withdraw = $trader_withdraw->whereDate('ib.created_at', '<=', $request->end_date);
                }

                $data = [
                    'status' => true,
                    'user_info' => $result,
                    'total_trader' => $total_client->count(),
                    'total_ib' => $total_ib->count(),
                    'total_trade_volume' => ($total_trade_volume / 100),
                    'total_ib_commission' => $total_ib_commission,
                    'ib_commission_lot' => ($ib_commission_lot / 100),
                    'trading_accounts' => $trading_accounts->count(),
                    'trade_volume_from_ib' => $trade_volume_ib->sum('trades.volume'),
                    'trade_volume_trader' => $trade_volume_trader->sum('trades.volume'),
                    'total_bonus' => $total_bonus->count(),
                    'total_deposit' => $trader_deposit->sum('deposits.amount'),
                    'total_withdraw' => $trader_withdraw->sum('withdraws.amount'),
                    'message' => 'Data successfully displayed'
                ];
            } else {
                $data = [
                    'status' => false,
                    'message' => 'Data not Found!'
                ];
            }
            return Response::json($data);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
}
