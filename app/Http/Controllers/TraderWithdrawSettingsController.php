<?php

namespace App\Http\Controllers;

use App\Models\WithdrawSetting;
use App\Services\systems\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TraderWithdrawSettingsController extends Controller
{
    public function manage_withdraw_settings(Request $request)
    {
        //Local Bank withdraw settings
        if ($request->bank_min_withdraw !== "" && $request->bank_max_withdraw !== "") {
            $update = WithdrawSetting::where('withdraw_method', 'bank')->update([
                'min_amount' => $request->bank_min_withdraw,
                'max_amount' => $request->bank_max_withdraw,
                'created_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log(),
            ]);
        }
        //Crypto withdraw settings
        if ($request->crypto_min_withdraw !== "" && $request->crypto_max_withdraw !== "") {
            $update = WithdrawSetting::where('withdraw_method', 'crypto')->update([
                'min_amount' => $request->crypto_min_withdraw,
                'max_amount' => $request->crypto_max_withdraw,
                'created_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log(),
            ]);
        }
        //Paypal withdraw settings
        if ($request->paypal_min_withdraw !== "" && $request->paypal_max_withdraw !== "") {
            $update = WithdrawSetting::where('withdraw_method', 'paypal')->update([
                'min_amount' => $request->paypal_min_withdraw,
                'max_amount' => $request->paypal_max_withdraw,
                'created_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log(),
            ]);
        }
        //GCash withdraw settings
        if ($request->gcash_min_withdraw !== "" && $request->gcash_max_withdraw !== "") {
            $update = WithdrawSetting::where('withdraw_method', 'gcash')->update([
                'min_amount' => $request->gcash_min_withdraw,
                'max_amount' => $request->gcash_max_withdraw,
                'created_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log(),
            ]);
        }
        if ($update) {
            return Response::json([
                'status'    => true,
                'message'   => 'Trader Withdraw settings successfully updated'
            ]);
        }
        return Response::json([
            'status'    => false,
            'message'   => 'Something went wrong, Please try again later!'
        ]);
    }
}
