<?php

declare(strict_types=1);

namespace App\Services\manager;

use App\Models\ManagerUser;
use App\Models\User;
use App\Services\CombinedService;
use App\Services\Trader\TraderAffiliatService;

final class ManagerAnalysisService
{
    public static function manager_analysis($data)
    {
        try {
            $result = User::where('users.email', $data['search_email'])->orWhere('users.name', $data['search_email'])->where('type', 5)
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                ->select('users.name', 'countries.name as country', 'users.email', 'users.id')
                ->first();


            if ($result != null) {
                // total trader
                $total_client = ManagerUser::where('manager_id', $result->id)
                    ->join('users', 'manager_users.user_id', 'users.id')
                    ->where('type',  CombinedService::type());
                if (CombinedService::is_combined()) {
                    $total_client = $total_client->where('combine_access', 0);
                }

                if ($data['start_date'] != '') {
                    $total_client = $total_client->whereDate('manager_users.created_at', '>=', $data['start_date']);
                    if ($data['end_date'] != '') {
                        $total_client = $total_client->whereDate('manager_users.created_at', '<=', trim($data['end_date']));
                    }
                }

                // Total IB
                $total_ib = ManagerUser::where('manager_id', $result->id)
                    ->join('users', 'manager_users.user_id', 'users.id')
                    ->where('type',  CombinedService::type());
                if (CombinedService::is_combined()) {
                    $total_ib = $total_ib->where('combine_access', 1);
                }
                // total ib filter by date
                if ($data['start_date'] != '') {
                    $total_ib = $total_ib->whereDate('manager_users.created_at', '>=', $data['start_date']);
                    if ($data['end_date'] != "") {
                        $total_ib = $total_ib->whereDate('manager_users.created_at', '<=', $data['end_date']);
                    }
                }
                // Trade Volume (Total)
                $total_trade_volume = ManagerUser::where("manager_users.manager_id", $result->id)
                    ->join('trading_accounts', 'manager_users.user_id', '=', 'trading_accounts.user_id')
                    ->join('trades', 'trading_accounts.id', '=', 'trades.trading_account')
                    ->where(function ($q) {
                        $q->orWhere('trades.cmd', '=', 0)->orWhere('trades.cmd', '=', 1);
                    });
                // total volume filter by date
                if ($data['start_date'] != '') {
                    $total_trade_volume = $total_trade_volume->whereDate('trades.created_at', '>=', $data['start_date']);
                    if ($data['end_date'] != "") {
                        $total_trade_volume = $total_trade_volume->whereDate('trades.created_at', '<=', $data['end_date']);
                    }
                }

                $total_trade_volume = $total_trade_volume->sum('trades.volume');
                // IB Commission (Total)
                $total_ib_commission = ManagerUser::where("manager_users.manager_id", $result->id)
                    ->where('users.type', CombinedService::type())
                    ->join('users', 'manager_users.user_id', '=', 'users.id')
                    ->join('ib_incomes', 'users.id', '=', 'ib_incomes.ib_id');
                if (CombinedService::is_combined()) {
                    $total_ib_commission = $total_ib_commission->where('combine_access', 1);
                }
                // filter by date/ ib commisison
                if ($data['start_date'] != '') {
                    $total_ib_commission = $total_ib_commission->whereDate('manager_users.created_at', '>=', $data['start_date']);
                    if ($data['end_date'] != "") {
                        $total_ib_commission = $total_ib_commission->whereDate('manager_users.created_at', '<=', $data['end_date']);
                    }
                }
                // IB Commission (Lot)
                $ib_commission_lot = $total_ib_commission->sum('ib_incomes.volume');
                $total_ib_commission = $total_ib_commission->sum('ib_incomes.amount');

                // Trading Accounts
                $trading_accounts = ManagerUser::where("manager_users.manager_id", $result->id)
                    ->join('trading_accounts', 'manager_users.user_id', '=', 'trading_accounts.user_id');
                // trading account filter by date
                if ($data['start_date'] != '') {
                    $trading_accounts = $trading_accounts->whereDate('trading_accounts.created_at', '>=', $data['start_date']);
                    if ($data['end_date'] != "") {
                        $trading_accounts = $trading_accounts->whereDate('trading_accounts.created_at', '<=', $data['end_date']);
                    }
                }

                // Trade Volume (From IB)
                $trade_volume_ib = ManagerUser::where("manager_users.manager_id", $result->id)
                    ->where('users.type', CombinedService::type())
                    ->join('users', 'manager_users.user_id', '=', 'users.id')
                    ->join('trading_accounts', 'manager_users.user_id', '=', 'trading_accounts.user_id')
                    ->join('trades', 'trading_accounts.id', '=', 'trades.trading_account');
                if (CombinedService::is_combined()) {
                    $trade_volume_ib = $trade_volume_ib->where('combine_access', 1);
                }
                // trader volume filter by date
                if ($data['start_date'] != '') {
                    $trade_volume_ib = $trade_volume_ib->whereDate('manager_users.created_at', '>=', $data['start_date']);
                    if ($data['end_date'] != "") {
                        $trade_volume_ib = $trade_volume_ib->whereDate('manager_users.created_at', '<=', $data['end_date']);
                    }
                }
                // Trade Volume (Trader)
                $trade_volume_trader = ManagerUser::where("manager_users.manager_id", $result->id)
                    ->where('users.type', CombinedService::type())
                    ->join('users', 'manager_users.user_id', '=', 'users.id')
                    ->join('trading_accounts', 'manager_users.user_id', '=', 'trading_accounts.user_id')
                    ->join('trades', 'trading_accounts.id', '=', 'trades.trading_account');
                if (CombinedService::is_combined()) {
                    $trade_volume_trader = $trade_volume_trader->where('combine_access', 0);
                }
                // total volume filter by trade
                if ($data['start_date'] != '') {
                    $trade_volume_trader = $trade_volume_trader->whereDate('manager_users.created_at', '>=', $data['start_date']);
                    if ($data['end_date']) {
                        $trade_volume_trader = $trade_volume_trader->whereDate('manager_users.created_at', '<=', $data['end_date']);
                    }
                }

                // Total Bonus
                $total_bonus = ManagerUser::where("manager_users.manager_id", $result->id)
                    ->join('bonus_users', 'manager_users.user_id', '=', 'bonus_users.user_id');
                // total bonus filter by date
                if ($data['start_date'] != '') {
                    $total_bonus = $total_bonus->whereDate('bonus_users.created_at', '>=', $data['start_date']);
                    if ($data['end_date'] != "") {
                        $total_bonus = $total_bonus->whereDate('bonus_users.created_at', '<=', $data['end_date']);
                    }
                }

                // trader deposit
                $trader_deposit = ManagerUser::where("manager_users.manager_id", $result->id)
                    ->where('users.type', CombinedService::type())
                    ->join('users', 'manager_users.user_id', '=', 'users.id')
                    ->join('deposits', 'manager_users.user_id', '=', 'deposits.user_id');
                // trader deposit filter by date
                if ($data['start_date'] != '') {
                    $trader_deposit = $trader_deposit->whereDate('manager_users.created_at', '>=', $data['start_date']);
                    if ($data['end_date'] != "") {
                        $trader_deposit = $trader_deposit->whereDate('manager_users.created_at', '<=', $data['end_date']);
                    }
                }

                // trader withdraw
                $trader_withdraw = ManagerUser::where("manager_users.manager_id", $result->id)
                    ->where('users.type', CombinedService::type())
                    ->join('users', 'manager_users.user_id', '=', 'users.id')
                    ->join('withdraws', 'manager_users.user_id', '=', 'withdraws.user_id');
                // trader withdraw filter by date
                if ($data['start_date'] != '') {
                    $trader_withdraw = $trader_withdraw->whereDate('manager_users.created_at', '>=', $data['start_date']);
                    if ($data['end_date'] != "") {
                        $trader_withdraw = $trader_withdraw->whereDate('manager_users.created_at', '<=', $data['end_date']);
                    }
                }
                // controll empty pie chart
                $ib_commission_all = $total_ib_commission;
                if ($total_ib_commission == 0 && $trader_deposit->sum('deposits.amount') == 0 && $trader_withdraw->sum('withdraws.amount') == 0) {
                    $total_ib_commission = 1;
                    $deposit = 1;
                    $withdraw = 1;
                } else {
                    $deposit = $trader_deposit->sum('deposits.amount');
                    $withdraw = $trader_withdraw->sum('withdraws.amount');
                }
                return ([
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
                    'total_deposit' => $deposit,
                    'total_withdraw' => $withdraw,
                    'ib_commission_all' => $ib_commission_all,
                ]);
            }
            return ([
                'status' => false,
                'message' => 'Data not match!'
            ]);
        } catch (\Throwable $th) {
            return $th->getMessage();
            return ([
                'status' => false,
                'message' => 'Something went wrong, Please try again later'
            ]);
        }
    }
    // get client deposti
    public static function deposit($data = [])
    {
        try {
            $trader_deposit = ManagerUser::where("manager_users.manager_id", $data['manager_id'])
                ->where('users.type', CombinedService::type())
                ->where('deposits.approved_status', $data['approved_status']);
            // filter by non affiliat trader
            if (array_key_exists('direct', $data) && $data['direct'] == true) {
                $trader_deposit = $trader_deposit->whereIn('deposits.user_id', TraderAffiliatService::non_affiliat_trader_id());
            }
            // filter by affiliat trader
            if (array_key_exists('affiliated', $data) && $data['affiliated'] == true) {
                $trader_deposit = $trader_deposit->whereIn('deposits.user_id', TraderAffiliatService::affiliated_trader_id());
            }
            $trader_deposit = $trader_deposit->join('users', 'manager_users.user_id', '=', 'users.id')
                ->join('deposits', 'manager_users.user_id', '=', 'deposits.user_id');
            return $trader_deposit->sum('deposits.amount');
        } catch (\Throwable $th) {
            return 0;
        }
    }
    // get client withdraw
    public static function withdraw($data = [])
    {
        try {
            $trader_deposit = ManagerUser::where("manager_users.manager_id", $data['manager_id'])
                ->where('users.type', CombinedService::type())
                ->where('withdraws.approved_status', $data['approved_status']);
            // filter by non affiliat trader
            if (array_key_exists('direct', $data) && $data['direct'] == true) {
                $trader_deposit = $trader_deposit->whereIn('withdraws.user_id', TraderAffiliatService::non_affiliat_trader_id());
            }
            // filter by affiliat trader
            if (array_key_exists('affiliated', $data) && $data['affiliated'] == true) {
                $trader_deposit = $trader_deposit->whereIn('withdraws.user_id', TraderAffiliatService::affiliated_trader_id());
            }
            $trader_deposit = $trader_deposit->join('users', 'manager_users.user_id', '=', 'users.id')
                ->join('withdraws', 'manager_users.user_id', '=', 'withdraws.user_id');
            return $trader_deposit->sum('withdraws.amount');
        } catch (\Throwable $th) {
            return 0;
        }
    }
    // get total clients by active status
    public static function count_clients_by_status($data = [])
    {
        try {
            $clients = ManagerUser::where("manager_users.manager_id", $data['manager_id']);

            // filter by use type
            if ($data['user_type'] != "") {
                $clients = $clients->where('users.type', $data['user_type']);
                if (array_key_exists('combine_access', $data) && CombinedService::is_combined()) {
                    $clients = $clients->where('combine_access', $data['combine_access']);
                }
            }
            // filter by status
            // active status
            if ($data['status'] === 'active') {
                $clients = $clients->where('users.active_status', 1);
            }
            // disabled status
            if ($data['status'] === 'disabled') {
                $clients = $clients->where('users.active_status', 0);
            }
            // filter by live status
            if ($data['status'] === 'live') {
                $clients = $clients->where('users.live_status', 'live');
            }
            if ($data['status'] === 'demo') {
                $clients = $clients->where('users.live_status', 'demo');
            }
            // filter by non affiliat trader
            if (array_key_exists('direct', $data) && $data['direct'] == true) {
                $clients = $clients->whereIn('users.id', TraderAffiliatService::non_affiliat_trader_id());
            }
            // filter by affiliat trader
            if (array_key_exists('affiliated', $data) && $data['affiliated'] == true) {
                $clients = $clients->whereIn('users.id', TraderAffiliatService::affiliated_trader_id());
            }
            $clients = $clients->join('users', 'manager_users.user_id', '=', 'users.id');
            return $clients->count();
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
