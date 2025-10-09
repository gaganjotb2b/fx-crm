<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\Deposit;
use App\Models\ExternalFundTransfers;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class MyLastTransactionController extends Controller
{
    public function __invoke(Request $request)
    {
        // for trader data
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $deposit_array = $withdraw_array = $send_array = $received_array = $wta_array = $atw_array = [];
            $last_deposit = Deposit::where('user_id', $trader_user->id)
                ->where('wallet_type', 'trader')
                ->select('amount', 'created_at', 'approved_status', 'transaction_type as method')
                ->addSelect(DB::raw("'deposit' as type")) // Add this line to select 'deposit' as the type directly
                ->latest();

            $last_withdraw = Withdraw::where('user_id', $trader_user->id)
                ->where('wallet_type', 'trader')
                ->select('amount', 'created_at', 'approved_status', 'transaction_type as method')
                ->addSelect(DB::raw("'withdraw' as type"));
            $last_balance_send = ExternalFundTransfers::where('sender_id', $trader_user->id)
                ->where('sender_wallet_type', 'trader')
                ->select('amount', 'created_at', 'status')
                ->addSelect(DB::raw("'balance send' as type"));
            $last_balance_receive = ExternalFundTransfers::where('receiver_id', $trader_user->id)
                ->where('sender_wallet_type', 'trader')
                ->select('amount', 'created_at', 'status')
                ->addSelect(DB::raw("'balance receive' as type"));
            $last_wta = InternalTransfer::where('user_id', $trader_user->id)
                ->where('type', 'wta')
                ->select('amount', 'created_at', 'status')
                ->addSelect(DB::raw("'wallet to account' as type"));
            $last_atw = InternalTransfer::where('user_id', $trader_user->id)
                ->where('type', 'atw')
                ->select('amount', 'created_at', 'status')
                ->addSelect(DB::raw("'account to wallet' as type"));
            $all_data = [];
            if ($last_withdraw->exists()) {
                $deposit_array = $last_deposit->limit(2)->get()->toArray();
                $withdraw_array = $last_withdraw->latest()->limit(2)->get()->toArray();
            } else {
                $deposit_array = $last_deposit->limit(3)->get()->toArray();
            }
            if ($last_balance_send->exists()) {
                $send_array = $last_balance_send->latest()->limit(1)->get()->toArray();
            } else {
                $deposit_array = $last_deposit->limit(3)->get()->toArray();
            }
            if ($last_balance_receive->exists()) {
                $received_array = $last_balance_receive->latest()->limit(1)->get()->toArray();
            } else {
                $deposit_array = $last_deposit->limit(3)->get()->toArray();
            }
            if ($last_wta->exists()) {
                $wta_array = $last_wta->latest()->limit(1)->get()->toArray();
            } else {
                $deposit_array = $last_deposit->limit(3)->get()->toArray();
            }
            if ($last_atw->exists()) {
                $atw_array = $last_atw->latest()->limit(1)->get()->toArray();
            } else {
                $deposit_array = $last_deposit->limit(3)->get()->toArray();
            }
            $all_data = array_merge($all_data, $withdraw_array, $send_array, $received_array, $wta_array, $atw_array, $deposit_array);
            return Response::json([
                'status' => true,
                'trader_transactions' => $all_data,
                'ib_transactions' => []
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'data' => []
            ]);
        }
    }
}
