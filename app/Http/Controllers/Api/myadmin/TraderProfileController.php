<?php

namespace App\Http\Controllers\Api\myadmin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\IB;
use App\Models\KycVerification;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\api\FileApiService;
use App\Services\balance\BalanceSheetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TraderProfileController extends Controller
{

    // get trader fprofile data
    public function trader_data_get(Request $request)
    {
        // return 'this is from api';
        try {
            $requested_user = User::where('id', auth()->user()->id);
            if (strtolower($requested_user->first()->type) === 'trader') {
                $client = $requested_user->select(
                    'id',
                    'name',
                    'phone',
                    'email',
                    'type',
                    'live_status',
                    'transaction_password as pin',
                    'active_status',
                    'login_status',
                    'email_verified_at',
                    'trading_ac_limit as trading_account_limit',
                    'g_auth as google_2step',
                    'email_auth as email_2step',
                    'kyc_status',
                    'created_at'
                )->with([
                    'user_description' => function ($query) {
                        $query->select('user_id', 'state', 'city', 'country_id', 'address', 'zip_code', 'gender', 'date_of_birth', 'profile_avater');
                    },
                    'user_description.country' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'otpOptions' => function ($query) {
                        $query->select('user_id', 'account_create', 'deposit', 'withdraw', 'transfer');
                    },
                    'financeOptions' => function ($query) {
                        $query->select('user_id', 'deposit_operation', 'withdraw_operation', 'internal_transfer', 'wta_transfer', 'trader_to_trader', 'trader_to_ib', 'ib_to_ib', 'ib_to_trader', 'kyc_verify');
                    },
                    'tradingAccount',
                    'tradingAccount.group' => function ($query) {
                        $query->select('id', 'group_id as group_display_name', 'account_category');
                    },
                    'bankAccount' => function ($query) {
                        $query->where(function ($inquery) {
                            $inquery->where('status', 1)
                                ->orWhere('status', 0);
                        })->where(function ($inquery) {
                            $inquery->where('approve_status', 'a');
                        });
                    },
                    'IbAccount' => function ($query) {
                        $query->select(
                            'id',
                            'name',
                            'email',
                            'phone',
                            'type',
                            'live_status',
                            'transaction_password as pin',
                            'active_status',
                            'email_verified_at',
                            'kyc_status',
                            'created_at',
                            'g_auth as google_2step',
                            'email_auth as email_2step'
                        );
                    },
                    'IbAccount.otpOptions' => function ($query) {
                        $query->select('user_id', 'account_create', 'deposit', 'withdraw', 'transfer');
                    },
                    'IbAccount.financeOptions' => function ($query) {
                        $query->select('user_id', 'deposit_operation', 'withdraw_operation', 'internal_transfer', 'wta_transfer', 'trader_to_trader', 'trader_to_ib', 'ib_to_ib', 'ib_to_trader', 'kyc_verify');
                    },
                    'IbAccount.bankAccount' => function ($query) {
                        $query->where(function ($inquery) {
                            $inquery->where('status', 1)
                                ->orWhere('status', 0);
                        })->where(function ($inquery) {
                            $inquery->where('approve_status', 'a');
                        });
                    },
                ])->first();
            } else {
                $client = $requested_user->select(
                    'id',
                    'name',
                    'email',
                    'phone',
                    'type',
                    'live_status',
                    'transaction_password as pin',
                    'active_status',
                    'login_status',
                    'email_verified_at',
                    'kyc_status',
                    'created_at',
                    'g_auth as google_2step',
                    'email_auth as email_2step'
                )
                    ->with([
                        'user_description' => function ($query) {
                            $query->select('user_id', 'state', 'city', 'country_id', 'address', 'zip_code', 'gender', 'date_of_birth');
                        },
                        'user_description.country' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'otpOptions' => function ($query) {
                            $query->select('user_id', 'account_create', 'deposit', 'withdraw', 'transfer');
                        },
                        'financeOptions' => function ($query) {
                            $query->select('user_id', 'deposit_operation', 'withdraw_operation', 'internal_transfer', 'wta_transfer', 'trader_to_trader', 'trader_to_ib', 'ib_to_ib', 'ib_to_trader', 'kyc_verify');
                        },
                        'bankAccount' => function ($query) {
                            $query->where(function ($inquery) {
                                $inquery->where('status', 1)
                                    ->orWhere('status', 0);
                            })->where(function ($inquery) {
                                $inquery->where('approve_status', 'a');
                            });
                        },
                        'TraderAccount' => function ($query) {
                            $query->select(
                                'id',
                                'name',
                                'email',
                                'phone',
                                'type',
                                'live_status',
                                'trading_ac_limit as trading_account_limit',
                                'transaction_password as pin',
                                'active_status',
                                'email_verified_at',
                                'kyc_status',
                                'created_at',
                                'g_auth as google_2step',
                                'email_auth as email_2step',
                            );
                        },
                        'TraderAccount.otpOptions' => function ($query) {
                            $query->select('user_id', 'account_create', 'deposit', 'withdraw', 'transfer');
                        },
                        'TraderAccount.financeOptions' => function ($query) {
                            $query->select('user_id', 'deposit_operation', 'withdraw_operation', 'internal_transfer', 'wta_transfer', 'trader_to_trader', 'trader_to_ib', 'ib_to_ib', 'ib_to_trader', 'kyc_verify');
                        },
                        'TraderAccount.tradingAccount',
                        'TraderAccount.tradingAccount.group' => function ($query) {
                            $query->select('id', 'group_id as group_display_name', 'account_category');
                        },
                        'TraderAccount.bankAccount' => function ($query) {
                            $query->where(function ($inquery) {
                                $inquery->where('status', 1)
                                    ->orWhere('status', 0);
                            })->where(function ($inquery) {
                                $inquery->where('approve_status', 'a');
                            });
                        },
                    ])->first();
            }
            if ($client) {
                if (!empty($client->IbAccount)) {
                    $client->IbAccount->refere_links = $client->referLinks($client->IbAccount->id);
                }
                return Response::json([
                    'status' => true,
                    'data' => $client, // array of data
                ], 200);
            }
            return Response::json([
                'status' => false,
                'message' => 'Invalid request found'
            ], 403);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for supports'
            ], 401);
        }
    }
}
