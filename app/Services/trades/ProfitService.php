<?php

namespace App\Services\trades;

use Illuminate\Support\Facades\DB;

class ProfitService
{
    private static $prefix;
    public function __construct()
    {
        $this->prefix = DB::getTablePrefix();
    }
    public static function get_profit($user_id)
    {
        try {
            $result = DB::connection('alternate')->table('MT4_TRADES')
                ->where('users.id', $user_id)
                ->join(self::$prefix . 'trading_accounts', 'MT4_TRADES.LOGIN', '=', self::$prefix . 'trading_accounts.account_number')
                ->join(self::$prefix . 'users', self::$prefix . 'trading_accounts.user_id', '=', self::$prefix . 'users.id')->sum('PROFIT');
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get profit for contest
    public  static function profit_contest($account_number, $start_date, $end_date)
    {
        try {
            $result = DB::connection('alternate')->table('MT4_TRADES')
                ->where('LOGIN', $account_number)
                ->whereDate('CLOSE_TIME', '>=', $start_date)
                ->whereDate('CLOSE_TIME', '<=', $end_date)
                ->sum('PROFIT');
            return $result;
        } catch (\Throwable $th) {
            throw $th;
            return 0;
        }
    }
    // get total lot
    public  static function get_total_lot($account_number, $start_date, $end_date)
    {
        try {
            $result = DB::connection('alternate')->table('MT4_TRADES')
                ->where('LOGIN', $account_number)
                ->whereDate('CLOSE_TIME', '>=', $start_date)
                ->whereDate('CLOSE_TIME', '<=', $end_date)
                ->sum('VOLUME');
            return $result;
        } catch (\Throwable $th) {
            throw $th;
            return 0;
        }
    }
}
