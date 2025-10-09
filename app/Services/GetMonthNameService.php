<?php

namespace App\Services;

use App\Models\admin\InternalTransfer;
use App\Models\Deposit;
use App\Models\ExternalFundTransfers;
use App\Models\WalletUpDown;
use App\Models\Withdraw;
use Illuminate\Support\Facades\DB;

class GetMonthNameService
{
    public function __construct()
    {
        // call store procedure
        // $calendar = DB::select(
        //     DB::raw("CALL Calendar()")
        // );
    }
    public function get_month_name($month_num = [])
    {
        $months = [];
        for ($i = 0; $i <= 11; $i++) {
            $month = explode(" ", $month_num[$i]);
            $year = $month[1];
            $month = $month[0];
            if ($month == 1) {
                array_push($months, 'Jan ' . $year);
            } elseif ($month == 2) {
                array_push($months, 'Feb ' . $year);
            } elseif ($month == 3) {
                array_push($months, 'Mar ' . $year);
            } elseif ($month == 4) {
                array_push($months, 'Apr ' . $year);
            } elseif ($month == 5) {
                array_push($months, 'May ' . $year);
            } elseif ($month == 6) {
                array_push($months, 'Jun ' . $year);
            } elseif ($month == 7) {
                array_push($months, 'Jul ' . $year);
            } elseif ($month == 8) {
                array_push($months, 'Aug ' . $year);
            } elseif ($month == 9) {
                array_push($months, 'Sep ' . $year);
            } elseif ($month == 10) {
                array_push($months, 'Oct ' . $year);
            } elseif ($month == 11) {
                array_push($months, 'Nov ' . $year);
            } elseif ($month == 12) {
                array_push($months, 'Dec ' . $year);
            }
        }
        return $months;
    }
    // last 12 month deposit
    public function get_12_month_deposit()
    {
        $z = date('m') - 12;
        $celender = [];

        for ($z; $z < date('m') + 1; $z++) {

            if ($z !== 0) {
                $year = (int)date("Y");
                if ($z < 0) {
                    $month = 12 + ($z + 1);
                    $year = date("Y") - 1;
                } else {
                    $month = $z;
                }
                $user_id = auth()->user()->id;
                $total_deposit = Deposit::where('user_id', $user_id)
                    ->whereYear('created_at', '=', $year)->whereMonth('created_at', $month)->where('approved_status', 'A')->sum('amount');


                $total = $total_deposit;
                $std_array = (object) [
                    'deposit'  => $total
                ];

                array_push($celender, $std_array);
            }
        }
        return $celender;
    }
    // get 12 month withdraw
    public function get_12_month_withdraw()
    {
        $z = date('m') - 12;
        $celender = [];

        for ($z; $z < date('m') + 1; $z++) {

            if ($z !== 0) {
                $year = (int)date("Y");
                if ($z < 0) {
                    $month = 12 + ($z + 1);
                    $year = date("Y") - 1;
                } else {
                    $month = $z;
                }

                $user_id = auth()->user()->id;
                $total_withdraw2 = Withdraw::where('user_id', $user_id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', '=', $year)
                    ->where(function ($query) {
                        $query->where('approved_status', 'A')
                            ->orWhere('approved_status', 'P');
                    })->sum('amount');
                $wta_internal = InternalTransfer::where('user_id', $user_id)
                    ->where('type', 'wta')
                    ->whereYear('created_at', '=', $year)->whereMonth('created_at', $month)->where('status', 'A')->sum('amount');
                $external_fund_send = ExternalFundTransfers::where('sender_id', $user_id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', '=', $year)
                    ->where(function ($query) {
                        $query->where('status', 'A')
                            ->orWhere('status', 'P');
                    })->sum('amount');


                $total = $total_withdraw2 + $wta_internal + $external_fund_send;

                $std_array = (object) [
                    'Month'  => $month,
                    'withdraw'  => $total,
                    'Year'  => $year,
                ];

                array_push($celender, $std_array);
            }
        }
        return $celender;
    }
    // get 12 month total
    public function get_12_month_total($user_id, $month)
    {
        if ($month < 1) {
            $year = date("Y") - 1;
            $month = 12 + $month;
        }
        $year = date("Y");
        $total_withdraw = Withdraw::where('user_id', $user_id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', '=', $year)
            ->where(function ($query) {
                $query->where('approved_status', 'A')
                    ->orWhere('approved_status', 'P');
            })->sum('amount');
        $total_deposit = Deposit::where('user_id', $user_id)
            ->whereYear('created_at', '=', $year)
            ->whereMonth('created_at', $month)->where('approved_status', 'A')->sum('amount');

        $external_fund_send = ExternalFundTransfers::where('sender_id', $user_id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', '=', $year)
            ->where(function ($query) {
                $query->where('status', 'A')
                    ->orWhere('status', 'P');
            })->sum('amount');
        $external_fund_rec = ExternalFundTransfers::where('receiver_id', $user_id)
            ->whereYear('created_at', '=', $year)
            ->whereMonth('created_at', $month)
            ->where('status', 'A')->sum('amount');

        $atw_internal = InternalTransfer::where('user_id', $user_id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', '=', $year)
            ->where('type', 'atw')
            ->where(function ($query) {
                $query->where('status', 'A')
                    ->orWhere('status', 'P');
            })->sum('amount');
        $wta_internal = InternalTransfer::where('user_id', $user_id)
            ->where('type', 'wta')
            ->whereYear('created_at', '=', $year)->whereMonth('created_at', $month)->where('status', 'A')->sum('amount');



        $balance = round(($total_deposit + $atw_internal + $external_fund_rec), 2) - round(($total_withdraw + $wta_internal + $external_fund_send), 2);

        return round($balance, 2);
    }
}
