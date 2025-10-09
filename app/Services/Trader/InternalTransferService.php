<?php

declare(strict_types=1);

namespace App\Services\Trader;

use App\Models\admin\InternalTransfer;

final class InternalTransferService
{
    // all deposit
    public static function wta_deposit_approved($user_id = null, $data = [])
    {
        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            $deposit = InternalTransfer::where('user_id', $user_id)
                ->where('status', 'A')
                ->where('type', 'wta');
            // filter for offer
            if (array_key_exists('offer_date', $data) && $data['offer_date'] != '') {
                $deposit = $deposit->whereDate('created_at', $data['offer_date']);
            }
            $deposit = $deposit->sum('amount');
            return $deposit;
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
    // wallet to account deposit first deposit
    public static function wta_first_deposit($user_id = null, $data = [])
    {
        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            $deposit = InternalTransfer::where('user_id', $user_id)
                ->where('status', 'A')
                ->where('type', 'wta');
            // filter for offer
            if (array_key_exists('offer_date', $data) && $data['offer_date'] != '') {
                $deposit = $deposit->whereDate('created_at', $data['offer_date']);
            }
            $deposit = $deposit->orderBy('id', 'ASC')->first('amount');
            if ($deposit) {
                $deposit = $deposit->amount;
            } else {
                $deposit = 0;
            }
            return $deposit;
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
    // get wta deposit by date range
    public static function wta_range_deposit($user_id = null, $data = [])
    {
        try {
            $user_id = ($user_id != null) ? $user_id : auth()->user()->id;
            $deposit = InternalTransfer::where('user_id', $user_id)
                ->where('status', 'A')
                ->where('type', 'wta');
            // filter for offer
            if (array_key_exists('offer_date', $data) && $data['offer_date'] != '') {
                $deposit = $deposit->whereDate('created_at', $data['offer_date']);
            }
            // filter by start date
            if (array_key_exists('start_date', $data) && $data['start_date'] != "") {
                $deposit = $deposit->whereDate('created_at', '>=', $data['offer_date']);
            }
            // filter by date end
            if (array_key_exists('start_date', $data) && $data['start_date'] != "") {
                $deposit = $deposit->whereDate('created_at', '<=', $data['offer_date']);
            }
            $deposit = $deposit->orderBy('id', 'ASC')->sum('amount');
            return $deposit;
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
}
