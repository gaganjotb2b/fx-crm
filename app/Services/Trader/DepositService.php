<?php

namespace App\Services\Trader;

use App\Models\Deposit;

class DepositService
{
    public static function total_approved_deposit($user_id = null, $data = [])
    {
        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            $deposit = Deposit::where('user_id', $user_id)->where('approved_status', 'A');
            // check for offer
            if (array_key_exists('offer_date', $data)) {
                $deposit = $deposit->whereDate('created_at', '>=', $data['offer_date']);
            }
            $deposit = $deposit->sum('amount');
            return round($deposit, 3);
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
    // get first deposit
    public static function get_first_deposit($user_id = null, $data = [])
    {
        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            $deposit = Deposit::where('user_id', $user_id)->orderBy('id', 'ASC');
            // filter for offer
            if (array_key_exists('offer_date', $data) && $data['offer_date' != ""]) {
                $deposit = $deposit->where('created_at', '>=', $data['offer_date']);
            }
            $deposit = $deposit->first();
            if ($deposit) {
                return $deposit->amount;
            }
            return (0);
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
    // get range deposit
    public static function get_range_deposit($user_id = null, $min_amount = 0, $max_amount = null, $data = [])
    {
        $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            $deposit = Deposit::where('user_id', $user_id)->whereBetween('amount', $min_amount, $max_amount);
            if (array_key_exists('start_date', $data) && $data['start_date'] != "") {
                $deposit = $deposit->whereDate('created_at', '<=', $data['start_date']);
            }
            if (array_key_exists('end_date', $data) && $data['end_date'] != "") {
                $deposit = $deposit->whereDate('created_at', '>=', $data['end_date']);
            }
            // filter for bonus
            if (array_key_exists('offer_date', $data) && $data['offer_date']) {
                $deposit = $deposit->whereDate('created_at', '>=', $data['offer_date']);
            }
            $deposit = $deposit->sum('amount');
            return $deposit;
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
    // count range deposit
    public static function count_range_deposit($user_id = null, $min_amount = 0, $max_amount = null, $data = [])
    {

        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            $deposit = Deposit::where('user_id', $user_id)->whereBetween('amount', $min_amount, $max_amount);
            if (array_key_exists('start_date', $data)) {
                $deposit = $deposit->whereDate('created_at', '<=', $data['start_date']);
            }
            if (array_key_exists('end_date', $data)) {
                $deposit = $deposit->whereDate('created_at', '>=', $data['end_date']);
            }
            $deposit = $deposit->count();
            return $deposit;
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
}
