<?php

namespace App\Services\balance;

use App\Models\BalanceSheet;
use App\Models\Deposit;
use App\Models\ExternalFundTransfers;
use App\Models\IbIncome;
use App\Models\User;
use App\Models\WalletUpDown;
use App\Models\Withdraw;
use App\Models\admin\InternalTransfer;
use App\Models\InvestorLossTrade;
use App\Services\AllFunctionService;
use App\Services\BalanceService;

class BalanceSheetService
{
    // get trader wallet balance
    public  static function trader_wallet_balance($user_id)
    {
        try {
            $balance = AllFunctionService::trader_total_balance($user_id);
            return round($balance, 2);
        } catch (\Throwable $th) {
            throw $th;
            return 0;
        }
    }
    
    // get trader wallet balance
    public  static function ib_wallet_balance($user_id)
    {
        try {
            $balance = BalanceService::get_ib_balance_v2($user_id);
            return round($balance, 2);
        } catch (\Throwable $th) {
            throw $th;
            return 0;
        }
    }
    
    // investor pamm balance
    public static function PammInvestorBalance($investor_id, Model $account)
    {
        // Calculate total investment
        $totalInvestment = InternalTransfer::where('user_id', $investor_id)
            ->where('account_id', $account->id)
            ->where('type', 'wta')
            ->where('status', 'A')
            ->sum('amount');

        // total loss
        $totalLoss = InvestorLossTrade::where('account_id', $account->id)
            ->where('user_id', $investor_id)
            ->sum('distributed_loss');

        // Final balance
        $balance = max(0, $totalInvestment + $totalLoss); // Adjust balance with proportional loss
        return round($balance, 2);
    }
}
