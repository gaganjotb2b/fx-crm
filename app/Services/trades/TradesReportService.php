<?php

declare(strict_types=1);

namespace App\Services\trades;

use App\Models\Mt5Trade;

final class TradesReportService
{
    public static function trade_reprots($data)
    {
        switch (strtolower(get_platform())) {
            case 'mt4':

                break;

            default:
                $result = Mt5Trade::where('users.id', $data['user_id'])->select()
                    ->join('trading_accounts', 'mt5_trades.LOGIN', '=', 'trading_accounts.account_number')
                    ->join('users', 'trading_accounts.user_id', '=', 'users.id');
                // filter by symbol
                if ($data['symbol'] != "") {
                    $result = $result->where('mt5_trades.SYMBOL', $data['symbol']);
                }
                // filter by account number
                if ($data['account_number']) {
                    $result = $result->where('mt5_trades.LOGIN', $data['account_number']);
                }
                // filter by ticket
                if ($data['ticket']) {
                    $result = $result->where('mt5_trades.TICKET', $data['ticket']);
                }
                // filter by min amount
                if ($data['min_volume']) {
                    $result = $result->where('mt5_trades.VOLUME', '>=', $data['min_volume']);
                }
                // filter by max amount
                if ($data['max_volume']) {
                    $result = $result->where('mt5_trades.volume', '<=', $data['max_volume']);
                }
                break;
        }
    }
}
