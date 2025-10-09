<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Mail\OTPverificationMail;
use App\Mail\withdraw\WithdrawRequest;
use App\Models\admin\SystemConfig;
use App\Models\OtherTransaction;
use App\Models\OtpSetting;
use App\Models\TransactionSetting;
use App\Models\User;
use App\Models\UserOtpSetting;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\EmailService;
use App\Services\TransactionService;
use App\Services\MailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SkrillWithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('skrill_withdraw', 'trader'));
        $this->middleware(AllFunctionService::access('withdraw', 'trader'));
    }
    //basic view----------------
    public function skrill_view(Request $request)
    {
        $last_transaction = Withdraw::where('user_id', auth()->user()->id)->where('transaction_type', 'skrill')->latest()->first();
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
        return view('traders.withdraw.skrill-withdraw', [
            'last_transaction' => $last_transaction,
            'otp_settings' => $otp_settings,
            'user_otp_settings' => $user_otp_settings,
        ]);
    }
    // submit skrill form
    public function skrill_withdraw(Request $request)
    {
        $user = User::find(auth()->user()->id);
        // start session of form submit
        $multiple_submission = has_multi_submit('skrill-withdraw', 60);
        $data = [];
        // insert data and otp verify
        $charge = TransactionService::charge('withdraw', $request->amount, null);
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);

        $company_info = SystemConfig::select()->first();
        $support_email = ($company_info->support_email) ? $company_info->support_email : default_support_email();

        // validation check
        $validation_rules = [
            'skrill_account_name' => 'required',
            'skrill_account_email' => 'required|email',
            'amount' => 'required|numeric',
            'transaction_password' => 'required',
        ];
        // validation check otp
        if ($request->op === 'step-2') {
            $validation_rules['otp_1'] = 'required|max:1';
            $validation_rules['otp_2'] = 'required|max:1';
            $validation_rules['otp_3'] = 'required|max:1';
            $validation_rules['otp_4'] = 'required|max:1';
            $validation_rules['otp_5'] = 'required|max:1';
            $validation_rules['otp_6'] = 'required|max:1';
        }
        // return validation status
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            $data['message'] = 'Please fix the following errors!';
            $data['errors'] = $validator->errors();
            // return status for otp validation
            if ($request->op === 'step-2') {
                $data['otp_status'] = false;
            }
            if ($request->op === 'step-1') {
                $data['valid_status'] = false;
            }
            return Response::json($data);
        }
        // balance check-------
        if ($request->op === 'step-1') {
            $all_fun = new AllFunctionService();
            $balance = $all_fun->get_self_balance(auth()->user()->id);
            // return $balance;
            if ($balance <= 0) {
                $data['valid_status'] = false;
                $data['errors'] = ['amount' => "You don't have available balance!"];
                $data['message'] = 'Please fix the following errors';
                return Response::json($data);
            }
        }
        // transaction password validation
        if (!Hash::check($request->transaction_password, $user->transaction_password)) {
            return Response::json([
                'valid_status' => false,
                'message' => 'Please fix the following errors.',
                'errors' => ['transaction_password' => 'Transaction Password Not match!']
            ]);
        }
        // otp generate if otp enable
        if ($otp_settings->withdraw == true && $user_otp_settings->withdraw == true) {
            // otp sending to user email
            if ($request->op === 'step-1' || $request->op === 'resend') {
                // create otp
                $otp = random_int(100000, 999999);
                $request->session()->put('skrill-withdraw-otp', $otp);
                $request->session()->put('otp_set_time', time());
                // send otp to mail
                EmailService::send_email('otp-verification', [
                    'user_id' => auth()->user()->id,
                    'otp' => $otp,
                    'clientWithdrawAmount'      => $request->amount,
                ]);
                return Response::json(['otp_send' => true]);
            }
        } else {
            $skrill = OtherTransaction::create([
                'transaction_type' => 'skrill',
                'account_name' => $request->skrill_account_name,
                'account_email' => $request->skrill_account_email,
            ])->id;
            $created = Withdraw::create([
                'user_id' => auth()->user()->id,
                'transaction_id' => $invoice,
                'other_transaction_id' => $skrill,
                'amount' => $request->amount,
                'charge' => $charge,
                'approved_status' => 'P',
                'transaction_type' => 'skrill'
            ]);

            if ($created) {
                //notification mail to admin
                MailNotificationService::notification('withdraw', 'trader', 1, $user->name, $request->amount);
                // sending mail to 
                EmailService::send_email('withdraw-request', [
                    'clientWithdrawAmount'      => $request->amount,
                    'user_id' => auth()->user()->id,
                ]);
                // insert activity-----------------
                //<---client email as user id
                activity("skrill withdraw")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("skrill withdraw")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been " . "withdraw");
                // end activity log----------------->>
                $last_transaction = Withdraw::where('user_id', auth()->user()->id)->where('id', $created->id)->latest()->first();
                $data['status'] = true;
                $data['message'] = 'Withdraw Request successfully submited.';
                $data['last_transaction'] = $last_transaction;
                return Response::json($data);
            }
            $data['status'] = false;
            $data['message'] = 'Somthing went wrong, please try agian later!.';
            return Response::json($data);
        }
        // when otp on by admin/client
        // withdraws with otp verification
        if ($request->op === 'step-2') {
            // $transaction_settinss = TransactionSetting::where('transaction_type', 'withdraw')->where('active_status', 1)->first();
            $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
            if ($request->session()->get('skrill-withdraw-otp') == $request_otp) {

                $time = session('otp_set_time');
                $minutesBeforeSessionExpire = 5;
                if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                    $skrill = OtherTransaction::create([
                        'transaction_type' => 'skrill',
                        'account_name' => $request->skrill_account_name,
                        'account_email' => $request->skrill_account_email,
                    ])->id;
                    $created = Withdraw::create([
                        'user_id' => auth()->user()->id,
                        'transaction_id' => $invoice,
                        'other_transaction_id' => $skrill,
                        'amount' => $request->amount,
                        'charge' => $charge,
                        'approved_status' => 'P',
                        'transaction_type' => 'skrill'
                    ]);
                    //mailer script
                    if ($created) {
                        //notification mail to admin
                        MailNotificationService::notification('withdraw', 'trader', 1, $user->name, $request->amount);
                        // sending mail to 
                        EmailService::send_email('withdraw-request', [
                            'clientWithdrawAmount'      => $request->usd_amount,
                            'user_id' => auth()->user()->id,
                        ]);
                        // insert activity-----------------
                        //<---client email as user id
                        activity("skrill withdraw")
                            ->causedBy(auth()->user()->id)
                            ->withProperties($request->all())
                            ->event("skrill withdraw")
                            ->performedOn($user)
                            ->log("The IP address " . request()->ip() . " has been " . "withdraw");
                        // end activity log----------------->>
                        $last_transaction = Withdraw::where('user_id', auth()->user()->id)->where('id', $created->id)->latest()->first();
                        $request->session()->forget('skrill-withdraw-otp');
                        $request->session()->forget('otp_set_time');
                        $data['status'] = true;
                        $data['message'] = 'Withdraw Request successfully submited.';
                        $data['last_transaction'] = $last_transaction;
                        return Response::json($data);
                    }
                    $data['status'] = false;
                    $data['message'] = 'Somthing went wrong, please try agian later!.';
                    return Response::json($data);
                } else {
                    $data['otp_status'] = false;
                    $data['message'] = 'OTP Time Out!';
                    return $data;
                }
            }
            $data['otp_status'] = false;
            $data['message'] = 'OTP not matched!';
            return $data;
        }
    }
}
