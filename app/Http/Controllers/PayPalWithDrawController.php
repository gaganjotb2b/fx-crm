<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\OtherTransaction;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PayPalWithDrawController extends Controller
{
    public function index(Request $request)
    {
        $email = auth()->user()->email;
        $bank_accounts = BankAccount::where('user_id',auth()->user()->id)->select('bank_ac_number')->get();
        return view('traders.withdraw.paypal-withdraw', compact('email','bank_accounts'));
    }

    public function withdrawPayPal(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $validation_rules = [
            'user_amount' => 'required',
            // 'user_email' => 'required|email',
        ];
        $email = $request->user_email;
        $amount = $request->user_amount;
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            $data['message'] = 'Please fix the following errors!';
            $data['errors'] = $validator->errors();
            return Response::json($data);
        }
        $charge = TransactionService::charge('withdraw', $request->amount, null);
        $all_fun = new AllFunctionService();
        $balance = $all_fun->get_self_balance(auth()->user()->id);
        // return $balance;
        if ($balance <= 0 || (($amount + $charge) > $balance)) {
            $data['status'] = false;
            $data['errors'] = ['user_amount' => "You don't have available balance!"];
            $data['message'] = 'Please fix the following errors';
            return Response::json($data);
        } else {
            $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
            $paypal = OtherTransaction::create([
                'transaction_type' => 'PayPal',
                'account_email' => auth()->user()->email,
            ])->id;
            $created = Withdraw::create([
                'user_id' => auth()->user()->id,
                'transaction_id' => $invoice,
                'other_transaction_id' => $paypal,
                'amount' => $amount,
                'charge' => $charge,
                'approved_status' => 'P',
                'transaction_type' => 'PayPal'
            ]);
            if ($created) {
                activity("PayPal withdraw")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("PayPal withdraw")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been " . "withdraw");
                $data['status'] = true;
                $data['message'] = 'Withdraw Request successfully submited.';
            }
            return Response::json($data);
        }
    }
}
