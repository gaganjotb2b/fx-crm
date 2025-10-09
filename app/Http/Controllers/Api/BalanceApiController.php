<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\ExternalFundTransfers;
use App\Models\IbIncome;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\Transfer\ExternalTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BalanceApiController extends Controller
{
    private $balance = 0;
    private $trader_deposit_pending = 0;
    private $trader_deposit_approved = 0;
    private $trader_withdraw_pending = 0;
    private $trader_withdraw_approved = 0;
    private $trader_balance_send_approved = 0;
    private $trader_balance_send_pending = 0;
    private $trader_balance_receive_approved = 0;
    private $trader_balance_receive_pending = 0;
    private $trader_internal_wta = 0;
    private $trader_internal_atw = 0;
    private $ib_balance = 0;
    private $ib_balance_send_pending = 0;
    private $ib_balance_send_approved = 0;
    private $ib_balance_receive_pending = 0;
    private $ib_balance_receive_approved = 0;
    private $ib_commission = 0;
    public function get_balance(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $balance = BalanceSheetService::trader_wallet_balance($trader_user->id);
            $this->balance = $balance;
            return Response::json([
                'status' => true,
                'balance' => $balance,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                'balance' => 0,
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
    // get deposit amount
    public function deposit_amount(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            // get approved  deposit
            $approved_amount = AllFunctionService::trader_total_deposit($trader_user->id, 'approved');
            $pending_amount = AllFunctionService::trader_total_deposit($trader_user->id, 'pending');
            $this->trader_deposit_pending = $pending_amount;
            $this->trader_deposit_approved = $approved_amount;
            return Response::json([
                'status' => true,
                'deposit' => [
                    'approved_amount' => $approved_amount,
                    'pending_amount' => $pending_amount,
                ],
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
    // get withdraw amount
    public function withdraw_amount(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $approved_amount = BalanceService::trader_total_withdraw($trader_user->id);
            $pending_amount = BalanceService::trader_total_pending_withdraw($trader_user->id);
            $this->trader_withdraw_approved = $approved_amount;
            $this->trader_withdraw_pending = $pending_amount;
            return Response::json([
                'status' => true,
                'withdraw' => [
                    'approved_amount' => $approved_amount,
                    'pending_amount' => $pending_amount,
                ],
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
    // get external transfer amount
    public function external_transfer_amount(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            // external transfer send
            $send_approved_amount = ExternalFundTransfers::where('sender_id', $trader_user->id)
                ->where('sender_wallet_type', 'trader')
                ->where('status', 'A')->sum('amount');
            $this->trader_balance_send_approved = $send_approved_amount;
            $send_pending_amount = ExternalFundTransfers::where('sender_id', $trader_user->id)
                ->where('sender_wallet_type', 'trader')
                ->where('status', 'P')->sum('amount');
            $this->trader_balance_send_pending = $send_pending_amount;
            // external transfer receive
            $receive_approved_amount = ExternalFundTransfers::where('receiver_id', $trader_user->id)
                ->where('receiver_wallet_type', 'trader')
                ->where('status', 'A')->sum('amount');
            $this->trader_balance_receive_approved = $receive_approved_amount;
            $receive_pending_amount = ExternalFundTransfers::where('receiver_id', $trader_user->id)
                ->where('receiver_wallet_type', 'trader')
                ->where('status', 'P')->sum('amount');
            $this->trader_balance_receive_pending = $receive_pending_amount;
            return Response::json([
                'status' => true,
                'external_transfer' => [
                    'send' => [
                        'approved_amount' => $send_approved_amount,
                        'pending_amunt' => $send_pending_amount,
                    ],
                    'receive' => [
                        'approved_amount' => $receive_approved_amount,
                        'pending_amount' => $receive_pending_amount
                    ]
                ]
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
    // internal transfer amount
    public function internal_transfer_amount(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            // wallet to account transfer amount
            $wallet_to_account = InternalTransfer::where('user_id', $trader_user->id)
                ->where('status', 'A')->where('type', 'wta')->sum('amount');
            $this->trader_internal_wta = $wallet_to_account;
            // account to wallet
            $account_to_wallet = InternalTransfer::where('user_id', $trader_user->id)
                ->where('status', 'A')->where('type', 'atw')->sum('amount');
            $this->trader_internal_atw = $account_to_wallet;
            return Response::json([
                'status' => true,
                'internal_transfer' => [
                    'wallet_to_account' => $wallet_to_account,
                    'account_to_wallet' => $account_to_wallet
                ]
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
    // start finance for IB accounts
    public function get_ib_balance(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $balance = BalanceSheetService::ib_wallet_balance($ib_user->id);
            $this->ib_balance = $balance;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // ib balance send to other user
    public function ib_balance_send(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            // pending balance send
            $send_pending_amount = ExternalFundTransfers::where('sender_id', $ib_user->id)
                ->where('sender_wallet_type', 'ib')
                ->where('status', 'P')->sum('amount');
            $this->ib_balance_send_pending = $send_pending_amount;
            $send_approved_amount = ExternalFundTransfers::where('sender_id', $ib_user->id)
                ->where('sender_wallet_type', 'ib')
                ->where('status', 'A')->sum('amount');
            $this->ib_balance_send_approved = $send_approved_amount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // ib balance received
    public function ib_balance_received(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            // pending balance send
            $receive_pending_amount = ExternalFundTransfers::where('receiver_id', $ib_user->id)
                ->where('receiver_wallet_type', 'ib')
                ->where('status', 'P')->sum('amount');
            $this->ib_balance_receive_pending = $receive_pending_amount;
            $receive_approved_amount = ExternalFundTransfers::where('receiver_id', $ib_user->id)
                ->where('receiver_wallet_type', 'ib')
                ->where('status', 'A')->sum('amount');
            $this->ib_balance_receive_approved = $receive_approved_amount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function get_ib_commission(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $ib_commission = IbIncome::where('ib_id', $ib_user->id)->sum('amount');
            $this->ib_commission = $ib_commission;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // get finance all for trader and IB
    public function all_finance(Request $request)
    {
        try {
            $this->get_balance($request);
            $this->deposit_amount($request);
            $this->withdraw_amount($request);
            $this->external_transfer_amount($request);
            $this->internal_transfer_amount($request);
            $this->get_ib_balance($request);
            $this->ib_balance_send($request);
            $this->ib_balance_received($request);
            $this->get_ib_commission($request);
            return Response::json([
                'status' => true,
                'trader' => [
                    'balance' => $this->balance,
                    'deposit' => [
                        'amount_approved' => $this->trader_deposit_approved,
                        'amount_pending' => $this->trader_deposit_pending,
                    ],
                    'withdraw' => [
                        'amount_pending' => $this->trader_withdraw_pending,
                        'amount_approved' => $this->trader_withdraw_approved,
                    ],
                    'external_transfer' => [
                        'send' => [
                            'amount_pending' => $this->trader_balance_send_pending,
                            'amount_approved' => $this->trader_balance_send_approved,
                        ],
                        'receive' => [
                            'amount_pending' => $this->trader_balance_receive_pending,
                            'amount_approved' => $this->trader_balance_receive_approved,
                        ],
                    ],
                    'internal_transfer' => [
                        'wallet_to_account' => $this->trader_internal_wta,
                        'account_to_wallet' => $this->trader_internal_atw,
                    ]
                ],
                'ib' => [
                    'balance' => $this->ib_balance,
                    'send' => [
                        'amount_pending' => $this->ib_balance_send_pending,
                        'amount_approved' => $this->ib_balance_send_approved,
                    ],
                    'receive' => [
                        'amount_pending' => $this->ib_balance_receive_pending,
                        'amount_approved' => $this->ib_balance_receive_approved,
                    ],
                    'commission' => $this->ib_commission
                ]
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
