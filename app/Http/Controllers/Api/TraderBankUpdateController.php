<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TraderBankUpdateController extends Controller
{
    public function bank_update(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $validator = Validator::make($request->all(), [
                'bank_id' => 'required|exists:bank_accounts,id',
                'bank_name' => 'nullable|max:191|string',
                'bank_account_name' => 'nullable|max:191|string',
                'bank_account_number' => 'nullable|max:191|string',
                'bank_swift_code' => 'nullable',
                'bank_iban' => 'nullable',
                'bank_address' => 'required|max:191',
                'bank_country' => 'required|exists:countries,id',
                'currency_id' => 'nullable|exists:currency_setups,id',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => 'Validation Error',
                    'message' => "The request data is invalid.",
                    'errors' => $validator->errors(),
                ]);
            }
            if (!$trader_user) {
                return Response::json([
                    'status' => false,
                    'message' => 'You dont have a trader account'
                ]);
            }
            $bank = BankAccount::where('id', $request->input('bank_id'))->where('user_id', auth()->user()->id)->first();
            if (!$bank) {
                return Response::json([
                    'status' => false,
                    'message' => 'Bank account not found'
                ]);
            }
            if ($request->input('bank_name')) {
                $bank->bank_name = $request->input('bank_name');
            }
            if ($request->input('bank_account_name')) {
                $bank->bank_ac_name = $request->input('bank_account_name');
            }
            if ($request->input('bank_account_number')) {
                $bank->bank_ac_number = $request->input('bank_account_number');
            }
            if ($request->input('bank_swift_code')) {
                $bank->bank_swift_code = $request->input('bank_swift_code');
            }
            if ($request->input('bank_iban')) {
                $bank->bank_iban = $request->input('bank_iban');
            }
            if ($request->input('bank_address')) {
                $bank->bank_address = $request->input('bank_address');
            }
            if ($request->input('bank_country')) {
                $bank->bank_country = $request->input('bank_country');
            }
            if ($request->input('currency_id')) {
                $bank->currency_id = $request->input('currency_id');
            }
            $update = $bank->save();
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'Bank successfully updated',
                    'data'=>$bank->refresh()
                ]);
            }
            return Response::json([
                'status' => false,
                'error' => "Account Add Failed",
                'message' => "An error occurred while add the bank account. Please try again later.",
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ]);
        }
    }
}
