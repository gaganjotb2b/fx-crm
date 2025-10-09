<?php

namespace App\Services;

use App\Models\User;
use App\Models\Deposit;
use App\Models\ExternalFundTransfers;
use App\Models\IbIncome;
use App\Models\IbSetup;
use App\Models\IbTransfer;
use App\Models\WalletUpDown;
use App\Models\Withdraw;

class BalanceService
{
    public function __call($name, $data)
    {
        if ($name == 'ib_balance') {
            return $this->get_ib_balance($data[0]);
        }
        if ($name == 'total_commission') {
            return $this->get_ib_commission($data[0]);
        }
        // get ib commission volume
        if ($name == 'ib_commission_volume') {
            return $this->get_ib_commission_volume($data[0]);
        }
    }
    public static function __callStatic($name, $data)
    {
        if ($name == 'ib_balance') {
            return (new self)->get_ib_balance($data[0]);
        }
        if ($name == 'total_commission') {
            return (new self)->get_ib_commission($data[0]);
        }
        // get ib commission volume
        if ($name == 'ib_commission_volume') {
            return (new self)->get_ib_commission_volume($data[0]);
        }
        // get ib todays earning
        if ($name == 'todays_ib_erning') {
            return (new self)->calculate_todays_ib_erning($data[0]);
        }
        // get ib todays earning
        if ($name == 'yesterday_ib_erning') {
            return (new self)->calculate_yesterday_ib_erning($data[0]);
        }
        // get total client deposit

        if ($name == 'client_deposit_balance') {
            return (new self)->total_client_deposit($data[0]);
        }
        if ($name == 'client_withdraw_balance') {
            return (new self)->total_client_withdraw($data[0]);
        }
        // ib minimum withdraw check
        if ($name == 'check_minimum_withdraw') {
            return (new self)->minimum_withdraw($data[0]);
        }
        // ib minimum withdraw check
        if ($name == 'check_max_withdraw') {
            return (new self)->max_withdraw($data[0]);
        }
        // ib minimum withdraw amount
        if ($name == 'min_withdraw_amount') {
            return (new self)->get_minimum_withdraw();
        }
        // ib max withdraw amount
        if ($name == 'max_withdraw_amount') {
            return (new self)->get_max_withdraw();
        }
    }
    // start admin sections
    private function get_today_deposit()
    {
        // here calculate totday deposit all 
    }
    // start ib section
    private function get_ib_balance($ib_id = null)
    {
        try {
            if ($ib_id == null) {
                $ib_id = auth()->user()->id;
            }
            $balance = (new self)->get_ib_balance_v2($ib_id);
            return round($balance, 2);
        } catch (\Throwable $th) {
            throw $th;
            return 0;
        }
    }

    private function get_ib_commission($user_id = null)
    {
        if ($user_id == null) {
            $user_id = auth()->user()->id;
        }
        $total_commission = IbIncome::where('ib_id', $user_id)->sum('amount');
        return $total_commission;
    }
    // get ib commission volume
    public function get_ib_commission_volume($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $valume = IbIncome::where('ib_id', $ib_id)->sum('volume');
        return $valume;
    }
    // get ib todays earning
    private function calculate_todays_ib_erning($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $todays_earning = IbIncome::where('ib_id', $ib_id)->whereDate('created_at', now())->sum('amount');
        return $todays_earning;
    }
    // get ib yesterday earning
    private function calculate_yesterday_ib_erning($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $yesterday_earning = IbIncome::where('ib_id', $ib_id)->whereDate('created_at', date('Y-m-d', strtotime('-1 days')))->sum('amount');
        return $yesterday_earning;
    }
    // get total client(trader) deposit 
    // for ib reference client
    // like as IB dashboard
    private function total_client_deposit($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $all_clients = AllFunctionService::sub_ib_traders_id($ib_id, 'all');
        $deposit = Deposit::whereIn('user_id', $all_clients)
            ->where('approved_status', 'A')->sum('amount');
        return $deposit;
    }
    // get total client(trader) withdraw 
    private function total_client_withdraw($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $all_clients = AllFunctionService::sub_ib_traders_id($ib_id, 'all');
        $withdraw = Withdraw::whereIn('user_id', $all_clients)
            ->where('approved_status', 'A')->sum('amount');
        return $withdraw;
    }
    // check minimum withdraw
    private function minimum_withdraw($amount)
    {
        $minimum_withdraw = IbSetup::select()->first();
        if ($minimum_withdraw) {
            if ($amount < $minimum_withdraw->min_withdraw) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    // get minimum withdraw amount
    private function get_minimum_withdraw()
    {
        $minimum_withdraw = IbSetup::select()->first();
        if ($minimum_withdraw) {
            return ($minimum_withdraw->min_withdraw);
        } else {
            return 0;
        }
    }
    // check max withdraw
    private function max_withdraw($amount)
    {
        $max_withdraw = IbSetup::select()->first();
        if ($max_withdraw) {
            if ($amount > $max_withdraw->max_withdraw && $max_withdraw->max_withdraw != 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    // get minimum withdraw amount
    private function get_max_withdraw()
    {
        $max_withdraw = IbSetup::select()->first();
        if ($max_withdraw) {
            return ($max_withdraw->max_withdraw);
        } else {
            return 0;
        }
    }

    // check balance availabe or not
    public static function check_balance($request_amount, $charge = 0, $user_id = null)
    {
        $user_id = ($user_id == null) ? auth()->user()->id : $user_id;
        $all_fun = new AllFunctionService();
        $balance = $all_fun->get_self_balance($user_id);
        if ($balance <= 0 || ($request_amount + $charge) > $balance) {
            return false;
        }
        return true;
    }

    // get ib balance
    public static function get_ib_balance_v2($ib_id)
    {
        try {
            // outgoing blance
            //**********************************************************************
            $total_ib_withdraw = Withdraw::where(function ($query) {
                $query->where('approved_status', 'A')
                    ->orWhere('approved_status', 'P');
            })->where('user_id', $ib_id)
                ->where('wallet_type', 'ib')->sum('amount');
            // fexternal fund send
            $external_fund_send = ExternalFundTransfers::where('sender_id', $ib_id)
            ->where('sender_wallet_type', 'ib')    
            ->where(function ($query) {
                    $query->where('type', 'ib_to_trader')
                        ->orWhere('type', 'ib_to_ib');
                })->where(function ($query) {
                    $query->where('status', 'A')
                        ->orWhere('status', 'P');
                })->sum('amount');
            //********************************************************************
            $deposit = Deposit::where('approved_status', 'A')
                ->where('wallet_type', 'ib')->where('user_id', $ib_id)->sum('amount');
            // external fund receive
            $external_fund_rec = ExternalFundTransfers::where('receiver_id', $ib_id)
                ->where(function ($query) {
                    $query->where('type', 'ib_to_ib')
                        ->orWhere('type', 'trader_to_ib');
                })
                ->where('status', 'A')
                ->where('receiver_wallet_type', 'ib')
                ->sum('amount');
            $ib_income = IbIncome::where('ib_id', $ib_id)->sum('amount');
            $balance = ($deposit + $external_fund_rec + $ib_income) - ($total_ib_withdraw + $external_fund_send);
            return round($balance, 2);
        } catch (\Throwable $th) {
            throw $th;
            return 0;
        }
    }
    // get trader total pending withdraw
    public  static function trader_total_pending_withdraw($trader_id)
    {
        try {
            $withdraw = Withdraw::where('user_id', $trader_id)
                ->where('approved_status', 'P')
                ->where('wallet_type', 'trader')
                ->sum('amount');
            return round($withdraw, 2);
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get ib total pending withdraw
    public  static function get_ib_pending_withdraw($ib_id)
    {
        try {
            $withdraw = Withdraw::where('user_id', $ib_id)
                ->where('approved_status', 'P')
                ->where('wallet_type', 'ib')
                ->sum('amount');
            return round($withdraw, 2);
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get trader total withdraw
    public  static function trader_total_withdraw($trader_id)
    {
        try {
            $withdraw = Withdraw::where('wallet_type', 'trader')
                ->where('user_id', $trader_id)
                ->where('approved_status', 'A')
                ->sum('amount');

            return round($withdraw, 2);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // get ib last withdraw
    public static function ib_last_withdraw($ib_id)
    {
        try {
            $withdraw = Withdraw::where('wallet_type', 'ib')
                ->select('amount', 'approved_status')
                ->where('user_id', $ib_id)
                ->latest('created_at')->first();
            return [
                'amount' => $withdraw->amount,
                'status' => $withdraw->approved_status
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'amount' => 0,
                'status' => ''
            ];
        }
    }
    // trader last withdraw
    public  static function trader_last_withdraw($trader_id)
    {
        try {
            $withdraw = Withdraw::where('wallet_type', 'trader')
                ->select('amount', 'approved_status')
                ->where('user_id', $trader_id)
                ->latest('created_at')->first();
            return [
                'amount' => $withdraw->amount,
                'status' => $withdraw->approved_status
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'amount' => 0,
                'status' => ''
            ];
        }
    }
    // trader last deposit
    public  static function trader_last_deposit($trader_id)
    {
        try {
            $deposit = Deposit::where('wallet_type', 'trader')
                ->select('amount', 'approved_status')
                ->where('user_id', $trader_id)
                ->latest('created_at')->first();
            return [
                'amount' => $deposit->amount,
                'status' => $deposit->approved_status
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'amount' => 0,
                'status' => ''
            ];
        }
    }
}
