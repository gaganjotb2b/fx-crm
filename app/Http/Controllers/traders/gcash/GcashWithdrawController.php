<?php

namespace App\Http\Controllers\traders\gcash;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\OtpSetting;
use App\Models\User;
use App\Models\UserOtpSetting;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\OtpService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class GcashWithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('gcash_withdraw', 'trader'));
        $this->middleware(AllFunctionService::access('withdraw', 'trader'));
    }
    //form view
    public function index(Request $request)
    {
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
        $bank_accounts = BankAccount::where('user_id',auth()->user()->id)->select('bank_ac_number')->get();
        return view('traders.withdraw.gcash-withdraw', [
            'otp_settings' => $otp_settings,
            'user_otp_settings' => $user_otp_settings,
            'bank_accounts'     => $bank_accounts
        ]);
    }
    public function gcash_withdraw(Request $request)
    {
        try {
            $validation_ruls = [
                'amount' => "required|numeric|min:1",
                'gcash_ID' => 'required|max:191',
                'transaction_password' => 'required'
            ];
            $message = [
                'gcash_ID.required' => 'The Gcash ID field is required',
            ];
            $validator = Validator::make($request->all(), $validation_ruls, $message);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following error!',
                    'errors' => $validator->errors(),
                    'next_step' => 'request'
                ]);
            }
            $balance = BalanceSheetService::trader_wallet_balance(auth()->user()->id);
            $charge = TransactionService::charge('withdraw', $request->amount, null);
            if ($charge + $request->amount > $balance || $balance <= 0) {
                return Response::json([
                    'status' => false,
                    'message' => 'You dont have availble balance!',
                    'errors' => ['amount' => 'You dont have available balance'],
                    'next_step' => 'request'
                ]);
            }
            // transaction pin match
            $user = User::find(auth()->user()->id);
            if (!Hash::check($request->transaction_password, $user->transaction_password)) {
                return Response::json([
                    'valid_status' => false,
                    'message' => 'Please fix the following errors.',
                    'errors' => ['transaction_password' => 'Transaction Password Not match!']
                ]);
            }

            // check otp enable or disable
            if (OtpService::has_otp('withdraw') && $request->op !== 'otp') {
                $otp_status = OtpService::send_otp(null, 'bank-withdraw-otp');
                return Response::json([
                    'status' => true,
                    'next_step' => 'otp',
                ]);
            }
            // check otp code
            if ($request->op === 'otp' || OtpService::has_otp('withdraw')) {
                $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
                if ($request->session()->get('bank-withdraw-otp') == $request_otp) {
                    $time = session('otp_set_time');
                    $minutesBeforeSessionExpire = 5;
                    if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                        // create withdraw
                        // trader
                        $response = $this->store_withdraw($request);

                        return Response::json($response);
                    }
                    return Response::json([
                        'status' => false,
                        'message' => 'OTP Time Out!',
                        'next_step' => 'otp'
                    ]);
                }
                // return if otp not matched
                return Response::json([
                    'status' => false,
                    'message' => 'OTP not matched!',
                    'next_step' => 'otp'
                ]);
            }
            // submit request without otp service
            elseif (OtpService::has_otp('withdraw') != true) {
                $response = $this->store_withdraw($request);
                return Response::json($response);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // store withdraw data
    public function store_withdraw($request)
    {
        $charge = TransactionService::charge('withdraw', $request->amount, null);
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
        $create = Withdraw::create([
            'user_id' => auth()->user()->id,
            'transaction_id' => $invoice,
            'amount' => $request->amount,
            'charge' => $charge,
            'approved_status' => 'P',
            'transaction_type' => 'gcash',
            'currency' => 'USD',
            'wallet_type' => 'trader',
        ]);
        if ($create) {
            //notification mail to admin
            MailNotificationService::notification('withdraw', 'trader', 1, auth()->user()->name, $request->amount);
            // sending mail to 
            $last_transaction = Withdraw::find($create->id);
            EmailService::send_email('withdraw-request', [
                'clientWithdrawAmount'      => $request->amount,
                'user_id'                   => auth()->user()->id,
                'deposit_method'            => ($last_transaction) ? ucwords($last_transaction->transaction_type) : '',
                'deposit_date'              => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                'previous_balance'          => ((AllFunctionService::trader_total_balance(auth()->user()->id)) + ($last_transaction->amount)),
                'approved_amount'           => $last_transaction->amount,
                'total_balance'             => AllFunctionService::trader_total_balance(auth()->user()->id)
            ]);

            request()->session()->forget('bank-withdraw-otp');
            request()->session()->forget('otp_set_time');
            // insert activity-----------------
            //<---client email as user id
            $user = User::find(auth()->user()->id);
            activity("gcash withdraw")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event("gcash withdraw")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been " . "withdraw from gcash");
            // end activity log----------------->>
            return ([
                'status' => true,
                'message' => 'Withdraw Request successfully submited.',
                'last_transaction' => $create,
                'next_step' => 'preview'
            ]);
        }
        return Response::json([
            'status' => false,
            'Got a server error, Please try again later!',
            'mext_step' => 'request',
        ]);
    }
}
