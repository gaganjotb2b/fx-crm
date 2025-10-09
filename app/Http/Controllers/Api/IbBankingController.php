<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\User;
use App\Services\MailNotificationService;
use App\Services\systems\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class IbBankingController extends Controller
{
    public function bank_add(Request $request)
    {
        try {
            $user = User::find(auth()->guard('api')->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            $validator = Validator::make($request->all(), [
                'bank_name' => 'required|max:191|string',
                'bank_account_name' => 'required|max:191|string',
                'bank_account_number' => 'required|max:191|string',
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
            // check bank account exists for account
            if (BankAccount::where('bank_ac_number', $request->input('bank_account_number'))
                ->where('user_id', $ib_user->id)->exists()
            ) {
                return Response::json([
                    'status' => false,
                    'message' => 'The bank account already exist, please try with another',
                    'errors' => ['account_number' => 'This account already taken for this user'],
                ]);
            }
            $create = BankAccount::create([
                'user_id' => $ib_user->id,
                'bank_name' => $request->input('bank_name'),
                'bank_ac_name' => $request->input('bank_account_name'),
                'bank_ac_number' => $request->input('bank_account_number'),
                'bank_swift_code' => $request->input('bank_swift_code'),
                'bank_iban' => $request->input('bank_iban'),
                'bank_address' => $request->input('bank_address'),
                'bank_country' => $request->input('bank_country'),
                'currency_id' => $request->input('currency_id'),
                'approve_status' => 'p',
                'status' => '1',
            ]);
            if ($create) {
                MailNotificationService::admin_notification([
                    'name' => $ib_user->name,
                    'email' => $ib_user->email,
                    'type' => 'bank add',
                    'client_type' => 'ib'
                ]);
                NotificationService::system_notification([
                    'type' => 'client_bank_add',
                    'user_id' => $ib_user->id,
                    'user_type' => 'ib',
                    'table_id' => $create->id,
                    'category' => 'client',
                ]);
                return Response::json([
                    'status' => true,
                    'message' => 'Bank account successfully added'
                ], 201);
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
