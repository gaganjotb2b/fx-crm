<?php

namespace App\Http\Controllers\Api\banking;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class BankingController extends Controller
{
    //add new bank
    public function add_bank(Request $request)
    {
        try {
            $vlaidation_rules = [
                'bank_name' => 'required|max:191|string',
                'bank_account_name' => 'required|max:191|string',
                'bank_account_number' => 'required|max:191|string',
                'bank_swift_code' => 'nullable',
                'bank_iban' => 'nullable',
                'bank_address' => 'required|max:191',
                'bank_country' => 'required|exists:countries,id',
                'currency_id' => 'nullable|exists:currency_setups,id',
            ];
            $vlaidator = Validator::make($request->all(), $vlaidation_rules);
            if ($vlaidator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => 'Validation Error',
                    'message' => "The request data is invalid.",
                    'errors' => $vlaidator->errors(),
                ], 400);
            }
            // check bank account exists for account
            if (BankAccount::where('bank_ac_number', $request->bank_account_number)->where('user_id', auth()->user()->id)->exists()) {
                return Response::json([
                    'status' => false,
                    'error' => 'Validation Error',
                    'message' => 'The rquest data is invlaid.',
                    'errors' => ['account_number' => 'This account already taken for this user'],
                ], 400);
            }
            $create = BankAccount::create([
                'user_id'=>auth()->user()->id,
                'bank_name' => $request->bank_name,
                'bank_ac_name' => $request->bank_account_name,
                'bank_ac_number' => $request->bank_account_number,
                'bank_swift_code' => $request->bank_swift_code,
                'bank_iban' => $request->bank_iban,
                'bank_address' => $request->bank_address,
                'bank_country' => $request->bank_country,
                'currency_id' => $request->currency_id,
                'approve_status' => 'p',
                'status' => '1',
            ]);
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Bank account successfully added'
                ], 200);
            }
            return Response::json([
                'status' => false,
                'error' => "Account Add Failed",
                'message' => "An error occurred while add the bank account. Please try again later.",
            ], 500);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
}
