<?php

namespace App\Services\pamm;

use App\Models\CommissionStatus;
use App\Models\CustomCommission;
use App\Models\IbCommissionStructure;
use App\Models\IbIncome;
use App\Models\Mt5Trade;
use App\Models\PammUser;
use App\Models\admin\InternalTransfer;
use App\Models\PammProfitShare;
use App\Services\Mt5WebApi;
use App\Models\User;
use App\Services\IbService;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class ProfitShareService
{
    public static function profit_share()
    {
        try {
            $log = [];
            $result = (new self)->get_mt5_trades();
            foreach ($result as $value) {
                $profit = $value->PROFIT;
                if($profit>0){
                    $result = (new self)->balance_update_mt5($value->LOGIN, $profit);
                    if (!isset($result) || $result['status'] === false) {
                        return true;
                    }
                }
                
                // share master profit
                $pamm_user = PammUser::where('user_id', $value->user_id)->first();
                $pamm_profit = (($profit * $pamm_user->share_profit)/100); // master profit percentage 
                PammProfitShare::create([
                    'login' => $value->LOGIN,
                    'ticket' => $value->TICKET,
                    'pamm_id' => $value->user_id,
                    'open_time' => $value->OPEN_TIME,
                    'close_time' => $value->CLOSE_TIME,
                    'profit' => $value->PROFIT,
                    'share_type' => "pamm",
                    'shared_amount' => round($pamm_profit, 3),
                ]);
                
                // share investor profit
                $remaining_profit = $profit-$pamm_profit;
                $all_investors = InternalTransfer::select(
                        'internal_transfers.user_id',
                        'trading_accounts.account_number',
                        'internal_transfers.account_id'
                    )
                    ->join('trading_accounts', 'internal_transfers.account_id', '=', 'trading_accounts.id')
                    ->where('trading_accounts.account_number', $value->LOGIN)
                    ->distinct('internal_transfers.user_id');
                
                $total_investor = $all_investors->count();
                $all_investors = $all_investors->get();
                
                $investor_info = []; // ← store investor data here
                $total_investment = 0;
                $total_shared_amount = 0;
                
                foreach ($all_investors as $investor) {
                    $total_deposit = 0; 
                    $total_withdraw = 0; 
                
                    if ($investor->user_id == $value->user_id) {
                        $total_deposit = InternalTransfer::where('user_id', $value->user_id)
                            ->where('type', 'wta')
                            ->where('internal_transfers.account_id', $investor->account_id)
                            ->sum('amount');
                        
                        $total_withdraw = InternalTransfer::where('user_id', $value->user_id)
                            ->where('type', 'atw')
                            ->where('internal_transfers.account_id', $investor->account_id)
                            ->sum('amount');
                    } else {
                        $total_deposit = InternalTransfer::where('user_id', $investor->user_id)
                            ->where('type', 'wta')
                            ->where('internal_transfers.account_id', $investor->account_id)
                            ->where('account_type', 'pamm')
                            ->sum('amount');
                    }
                
                    $self_invest = $total_deposit - $total_withdraw;
                    $total_investment += $self_invest;
                    // Add to investor_info array
                    $investor_info[] = [
                        'user_id' => $investor->user_id,
                        'self_invest' => $self_invest,
                    ];
                }
                // Print investor info
                // dd($investor_info); // or use print_r($investor_info);
                foreach ($investor_info as $investor) {
                    // $userId = $investor['user_id'];
                    // $amount = $investor['self_invest'];
                    
                    $profit_share = ($investor['self_invest'] / $total_investment) * $remaining_profit;
                    // echo round($profit_share, 3) ."  ";
                
                    PammProfitShare::create([
                        'login' => $value->LOGIN,
                        'ticket' => $value->TICKET,
                        'pamm_id' => $value->user_id,
                        'investor_id' => $investor['user_id'],
                        'open_time' => $value->OPEN_TIME,
                        'close_time' => $value->CLOSE_TIME,
                        'profit' => $value->PROFIT,
                        'share_type' => "investor",
                        'shared_amount' => round($profit_share, 3),
                    ]);
                }
            }
            echo "success";
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
    public function get_mt5_trades()
    {
        try {
            $trades = Mt5Trade::select(
                'mt5_trades.OPEN_TIME',
                'mt5_trades.CLOSE_TIME',
                'mt5_trades.TICKET',
                'mt5_trades.LOGIN',
                'mt5_trades.SYMBOL',
                'mt5_trades.PROFIT',
                'mt5_trades.CMD',
                'pamm_users.user_id'
            )
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('pamm_profit_shares')
                        ->whereColumn('mt5_trades.TICKET', 'pamm_profit_shares.ticket');
                })
                // ->where(function ($query) {
                //     $query->where('mt5_trades.CMD', 0)
                //           ->orWhere('mt5_trades.CMD', 1);
                // })
                ->where('mt5_trades.TICKET', '!=', 0)
                // ->where('mt5_trades.TICKET', '=', 1767207)
                ->where('mt5_trades.SYMBOL', '!=', '')
                ->where('mt5_trades.OPEN_TIME', '!=', '1970-01-01 00:00:00')
                ->where('mt5_trades.CLOSE_TIME', '!=', '1970-01-01 00:00:00')
                ->whereDate('mt5_trades.OPEN_TIME', '>=', '2025-04-11')
                ->join('pamm_users', 'mt5_trades.LOGIN', 'pamm_users.account')
                ->orderBy('mt5_trades.TICKET', 'ASC')
                ->limit(1) // Changed from 10 to 100 after testing
                ->get();

            return $trades;
        } catch (\Throwable $th) {
            // throw $th;
            return ([]);
        }
    }
    // balance update for mt5 account
    public function balance_update_mt5($login, $profit)
    {
        try {
            $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
            $mt5_api = new Mt5WebApi();

            $action = 'BalanceUpdate';
            $data = array(
                "Login" => (int)$login,
                "Balance" => -(float)$profit,
                "Comment" => "PAMM profit deduct #" . $invoice
            );
            $result = $mt5_api->execute($action, $data);

            if (isset($result['success']) && $result['success']) {
                $order = $result['data']['order'];
                return ([
                    'status' => true,
                    'order' => $order,
                    'invoice' => $invoice,
                ]);
            }
            return ([
                'status' => false,
                'message' => (array_key_exists('data', $result)) ? $result['data']['message'] : $result['error']['Description'],

            ]);
        } catch (\Throwable $th) {
            throw $th;
            return ([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
                'error' => $th->getMessage(),
            ]);
        }
    }
}
