<?php

namespace App\Services;

use App\Models\admin\InternalTransfer;
use App\Models\Deposit;
use App\Models\ExternalFundTransfers;
use App\Models\TransactionSetting;
use App\Models\IbTransfer;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\Withdraw;
use App\Services\Transfer\ExternalTransfer;

class TransactionService
{
    public function __call($name, $data)
    {
        if ($name == 'last_transaction') {
            return $this->get_last_transaction($data['user_id'], $data['type']);
        }
        if ($name == 'charge') {
            return $this->get_charge($data[0], $data[1], $data[2]);
        }
    }
    public static function __callStatic($name, $data)
    {
        // print_r($data);
        if ($name == 'last_transaction') {
            return (new self)->get_last_transaction($data[0], $data[1]);
        }
        if ($name == 'charge') {
            return (new self)->get_charge($data[0], $data[1], $data[2]);
        }
    }
    private function get_last_transaction($user_id = null, $type)
    {
        if ($user_id == null) {
            $user_id = auth()->user()->id;
        }
        $user = User::find($user_id);
        // check crm is combine
        switch (CombinedService::is_combined()) {
            case true:
                if ($user->combine_access == 1) {
                    $last_transaction = IbTransfer::where('ib_id', $user_id)->latest()->first();
                } else {
                    $last_transaction = ExternalFundTransfers::where('sender_id', $user_id)->where('type', $type)->latest()->first();
                }
                break;

            default:
                if (strtolower($user->type) == 'ib') {
                    $last_transaction = ExternalFundTransfers::where('sender_id', $user_id)
                        ->where('type', $type)
                        ->latest()->first();
                } else {
                    $last_transaction = ExternalFundTransfers::where('sender_id', $user_id)
                        ->where('type', $type)
                        ->latest()->first();
                }
                break;
        }


        return $last_transaction;
    }

    // calculate charge
    private function get_charge($transaction_type, $transaction_amount, $user_id = null)
    {
        if ($user_id == null) {
            $user_id = auth()->user()->id;
        }
        $charge = $total_transaction = 0;
        $charge_type = TransactionSetting::where('transaction_type', strtolower($transaction_type))
            ->where(function ($query) {
                $query->where('permission', 'approved')
                    ->where('active_status', 1);
            })->get();
        if (strtolower($transaction_type) === 'deposit') {
            $total_transaction = Deposit::where('user_id', $user_id)->count('id');
        }
        if (strtolower($transaction_type) === 'withdraw') {
            $total_transaction = Withdraw::where('user_id', $user_id)->count('id');
        }
        if (strtolower($transaction_type) === 'wta') {
            $total_transaction = InternalTransfer::where('user_id', $user_id)->count('id');
        }
        if (strtolower($transaction_type) === 'atw') {
            $total_transaction = InternalTransfer::where('user_id', $user_id)->count('id');
        }
        if (strtolower($transaction_type) === 'wtw') {
            $total_transaction = ExternalFundTransfers::where('user_id', $user_id)->count('id');
        }

        foreach ($charge_type as $key => $value) {
            if ($transaction_amount >= $value->limit_start && $transaction_amount <= $value->limit_end) {
                if ($value->charge_type == 'fixed') {
                    $charge = $value->amount;
                } else {
                    if ($total_transaction >= $value->min_transaction && $total_transaction <= $value->max_transaction) {
                        $charge = $transaction_amount * ($value->amount / 100);
                    } else {
                        $charge = $transaction_amount * ($value->amount / 100);
                    }
                }
            }
        }
        return $charge;
    }
}
