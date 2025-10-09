<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class IBBankUpdateController extends Controller
{
    public function bank_update(Request $request)
    {
        try {
            $user = User::find(auth()->guard('api')->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $validator = Validator::make($request->all(), [
                'bank_id' => 'required|exists:bank_accounts,id',
                'bank_name' => 'nullable|max:191|string',
                'bank_account_name' => 'nullable|max:191|string',
                'bank_account_number' => 'nullable|max:191|string',
                'bank_swift_code' => 'nullable',
                'bank_iban' => 'nullable',
                'bank_address' => 'nullable|max:191',
                'bank_country' => 'nullable|exists:countries,id',
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
            if (!$ib_user) {
                return Response::json([
                    'status' => false,
                    'message' => 'You dont have a IB account'
                ]);
            }
            $bank = BankAccount::where('user_id', $ib_user->id)->where('id', $request->input('bank_id'))->first();
            if (!$bank) {
                return Response::json([
                    'status' => false,
                    'message' => 'Bank account not found',
                ]);
            }
            $request_updated = false;
            if ($request->input('bank_name')) {
                $bank->bank_name = $request->input('bank_name');
                $request_updated = true;
            }
            if ($request->input('bank_account_name')) {
                $bank->bank_ac_name = $request->bank_account_name;
                $request_updated = true;
            }
            if ($request->input('bank_account_number')) {
                $bank->bank_ac_number = $request->input('bank_account_number');
                $request_updated = true;
            }
            if ($request->input('bank_iban')) {
                $bank->bank_iban = $request->input('bank_iban');
                $request_updated = true;
            }
            if ($request->input('bank_address')) {
                $bank->bank_address = $request->input('bank_address');
                $request_updated = true;
            }
            if ($request->input('bank_country')) {
                $bank->bank_country = $request->input('bank_country');
                $request_updated = true;
            }
            if ($request->input('currency_id')) {
                $bank->currency_id = $request->input('currency_id');
                $request_updated = true;
            }
            if ($request_updated == false) {
                return Response::json([
                    'status' => false,
                    'message' => 'Nothing found for update'
                ]);
            }
            $update = $bank->save();
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'IB Bank successfully updated',
                    'data'=>$bank->refresh()
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => "Something went wrong. Please try again later.",
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
}
