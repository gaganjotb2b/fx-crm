<?php

namespace App\Services\Trader;

use App\Models\FinanceOp;

class FinanceService
{
    public static function check_op($op, $user_id = null)
    {
        $user_id = ($user_id == null) ? auth()->user()->id : $user_id;
        $finance_op = FinanceOp::where('user_id', $user_id);
        switch ($op) {
                // deposit operation
            case 'deposit':
                $finance_op = $finance_op->select('deposit_operation')->first();
                return ($finance_op) ? $finance_op->deposit_operation : 0;
                break;
                // withdraw operation
            case 'withdraw':
                $finance_op = $finance_op->select('withdraw_operation')->first();
                return ($finance_op) ? $finance_op->withdraw_operation : 0;
                break;
                // internal transfer/atw(account to wallet)
            case 'atw':
                $finance_op = $finance_op->select('internal_transfer')->first();
                return ($finance_op) ? $finance_op->internal_transfer : 0;
                break;
                // wta(wallet to account)
            case 'wta':
                $finance_op = $finance_op->select('wta_transfer')->first();
                return ($finance_op) ? $finance_op->wta_transfer : 0;
                break;
                // trader to trader transfer
            case 'trader_to_trader':
                $finance_op = $finance_op->select('trader_to_trader')->first();
                return ($finance_op) ? $finance_op->trader_to_trader : 0;
                break;
                // trader to ib
            case 'trader_to_ib':
                $finance_op = $finance_op->select('trader_to_ib')->first();
                return ($finance_op) ? $finance_op->trader_to_ib : 0;
                break;
                // ib to ib transfer
            case 'ib_to_ib':
                $finance_op = $finance_op->select('ib_to_ib')->first();
                return ($finance_op) ? $finance_op->ib_to_ib : 0;
                break;
                // ib to trader transfer
            case 'ib_to_trader':
                $finance_op = $finance_op->select('ib_to_trader')->first();
                return ($finance_op) ? $finance_op->ib_to_trader : 0;
                break;

            default:
                return false;
                break;
        }
    }
}
