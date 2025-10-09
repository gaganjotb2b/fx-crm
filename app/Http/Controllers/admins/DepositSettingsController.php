<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DepositSetting;
use App\Services\systems\AdminLogService;
use Illuminate\Support\Facades\Response;

class DepositSettingsController extends Controller
{
    public function manage_deposit_settings(Request $request)
    {
        //Local Bank transaction settings
        if ($request->bank_min_transaction !== "" && $request->bank_max_transaction !== "") {
            $update = DepositSetting::where('deposit_method', 'bank')->update([
                'min_amount' => $request->bank_min_transaction,
                'max_amount' => $request->bank_max_transaction,
                'created_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log(),
            ]);
        }

        //crypto deposit settings
        if ($request->crypto_min_transaction !== "" && $request->crypto_max_transaction !== "") {
            $update = DepositSetting::where('deposit_method', 'crypto')->update([
                'min_amount' => $request->crypto_min_transaction,
                'max_amount' => $request->crypto_max_transaction,
                'created_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log(),
            ]);
        }
        //Perfect money deposit settings
        if ($request->perfect_money_min_transaction !== "" && $request->perfect_money_max_transaction !== "") {
            $update = DepositSetting::where('deposit_method', 'perfect_money')->update([
                'min_amount' => $request->perfect_money_min_transaction,
                'max_amount' => $request->perfect_money_max_transaction,
                'created_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log(),
            ]);
        }
        //Help2Pay deposit settings
        if ($request->help2pay_min_transaction !== "" && $request->help2pay_max_transaction !== "") {
            $update = DepositSetting::where('deposit_method', 'help2pay')->update([
                'min_amount' => $request->help2pay_min_transaction,
                'max_amount' => $request->help2pay_max_transaction,
                'created_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log(),
            ]);
        }
        //b2b deposit settings
        if ($request->b2b_min_transaction !== "" && $request->b2b_max_transaction !== "") {
            $update = DepositSetting::where('deposit_method', 'b2b')->update([
                'min_amount' => $request->b2b_min_transaction,
                'max_amount' => $request->b2b_max_transaction,
                'created_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log(),
            ]);
        }
        //Match2pay deposit settings
        if ($request->m2pay_min_transaction !== "" && $request->m2pay_max_transaction !== "") {
            $update = DepositSetting::where('deposit_method', 'm2pay')->update([
                'min_amount' => $request->m2pay_min_transaction,
                'max_amount' => $request->m2pay_max_transaction,
                'created_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log(),
            ]);
        }

        if ($update) {
            return Response::json([
                'status'    => true,
                'message'   => 'Deposit settings successfully updated'
            ]);
        }
        return Response::json([
            'status'    => false,
            'message'   => 'Something went wrong, Please try again later!'
        ]);
    }
}
