<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Mail\OTPverificationMail;
use App\Mail\transfer\BalanceTransfer;
use App\Models\admin\SystemConfig;
use App\Models\ExternalFundTransfers;
use App\Models\Log;
use App\Models\OtpSetting;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\UserOtpSetting;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use App\Services\EmailService;
use App\Services\GetPhotosService;
use App\Services\MailNotificationService;
use App\Services\OtpService;
use App\Services\TransactionService;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class TraderToIbTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('trader_to_ib', 'trader'));
        $this->middleware(AllFunctionService::access('transfer', 'trader'));
    }
    // basic view------------
    public function trader_ib_transfer_view(Request $request)
    {

        // get last transaction----------------
        $last_transaction = TransactionService::last_transaction(auth()->user()->id, 'send');
        $avatar = GetPhotosService::avatar();
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
        return view(
            'traders.transfer.trader-to-ib',
            [
                'last_transaction' => $last_transaction,
                'avatar' => $avatar,
                'otp_settings' => $otp_settings,
                'user_otp_settings' => $user_otp_settings,
            ]
        );
    }
    // trader to ib 
    public function trader_ib_transfer(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $multiple_submission = has_multi_submit('trader-transfer', wait_second());
        // insert data and otp verify
        $charge = TransactionService::charge('w_to_w', $request->amount, null);

        $validation_rules = [
            'recipient' => 'required',
            'amount' => 'required|numeric|min:1',
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
        // start session of form submit

        $validator = FacadesValidator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            // return status for otp validation
            if ($request->op === 'step-2') {
                return Response::json([
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                    'otp_status' => false,
                ]);
            }
            if ($request->op === 'step-1') {
                return Response::json([
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                    'otp_status' => false
                ]);
            }
        }
        // get user balance----------------
        $fn = new AllFunctionService();
        $balance = $fn->get_self_balance(auth()->user()->id);
        // check available balance---------
        if ($balance <= 0 || $request->amount > $balance) {
            return Response::json([
                'valid_status' => false,
                'errors' => ['amount' => "You don't have available balance!"],
                'message' => 'Please fix the following errors'
            ]);
        }
        // check transaction password
        if (!Hash::check($request->transaction_password, auth()->user()->transaction_password)) {
            return Response::json([
                'valid_status' => false,
                'message' => 'Please fix the following errors.',
                'errors' => ['transaction_password' => 'Transaction Password Not matched!']
            ]);
        }
        // otp sending to user email
        switch (OtpService::has_otp('transfer')) {
            case true:
                if ($request->op === 'step-1' || $request->op === 'resend') {
                    $otp_status = OtpService::send_otp();
                    return Response::json(['otp_send' => $otp_status]);
                }
                // make transaction with otp
                if ($request->op === 'step-2') {
                    $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
                    if ($request->session()->get('trader-transfer-otp') == $request_otp) {
                        $time = session('otp_set_time');
                        $minutesBeforeSessionExpire = 1;
                        if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {

                            $response = $this->make_transaction([
                                'recipient' => $request->recipient,
                                'amount' => $request->amount,
                                'charge' => $charge,
                                'name' => $user->name,
                                'user_id' => $user->id,
                                'request_all' => $request->all(),
                            ]);
                            return Response::json($response);
                        }
                        return Response::json([
                            'otp_status' => false,
                            'message' => 'OTP Time Out!',
                        ]);
                    }
                    return Response::json([
                        'otp_status' => false,
                        'message' => 'OTP not matched!'
                    ]);
                }
                break;

            default:
                // make transaction without otp
                $response = $this->make_transaction([
                    'recipient' => $request->recipient,
                    'amount' => $request->amount,
                    'charge' => $charge,
                    'name' => $user->name,
                    'user_id' => $user->id,
                    'request_all' => $request->all(),
                ]);
                return Response::json($response);
                break;
        }
    }
    // make transfer
    private function make_transaction($data)
    {

        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
        $receiver = User::where('id', $data['recipient'])->select('id', 'type', 'name', 'email', 'combine_access')->first();
        // check crm is combined
        if (CombinedService::is_combined()) {
            // check user is ib or not
            if ($receiver->combine_access != 1) {
                return ([
                    'status' => false,
                    'message' => 'The Receiver is not a IB!'
                ]);
            }
        } else {
            // check user is ib or not
            if ($receiver->type !== 'ib') {
                return ([
                    'status' => false,
                    'message' => 'The Receiver is not a IB!'
                ]);
            }
        }
        $created = ExternalFundTransfers::create([
            'txnid' => $invoice,
            'sender_id' => auth()->user()->id,
            'receiver_id' => $receiver->id,
            'amount' => $data['amount'],
            'charge' => $data['charge'],
            'type' => 'trader_to_ib',
            'status' => 'A',
            'sender_wallet_type' => 'trader',
            'receiver_wallet_type' => 'ib',
        ]);
        //mail script
        if ($created) {
            //notification mail to admin
            MailNotificationService::admin_notification([
                'name'=>auth()->user()->name,
                'email'=>auth()->user()->email,
                'amount'=>$data['amount'],
                'type'=>'balance transfer',
                'client_type'=>'trader'
            ]);
            // get last transaction----------------
            $last_transaction = ExternalFundTransfers::where('sender_id', auth()->user()->id)->where('type', 'trader_to_ib')->latest()->first();
            EmailService::send_email('trader-to-ib-transfer', [
                'user_id' => auth()->user()->id,
                'clientDepositAmount' => $data['amount'],
                'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                'previous_balance' => ((AllFunctionService::trader_total_balance($data['user_id'])) + ($last_transaction->amount)),
                'transfer_amount' => $last_transaction->amount,
                'total_balance' => AllFunctionService::trader_total_balance($data['user_id']),
                'reciever_name' => ucwords($receiver->name),
                'reciever_email' => $receiver->email,
            ]);
            // get last transaction
            // insert activity-----------------
            $user = User::find(auth()->user()->id);
            //<---client email as user id
            activity("trader to IB balance transfer")
                ->causedBy(auth()->user()->id)
                ->withProperties($data['request_all'])
                ->event("trader to IB")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been trader to IB transfer");
            // end activity log----------------->>
            return ([
                'status' => true,
                'message' => 'Transaction successfully done!',
                'last_transaction' => $last_transaction,
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Somthing went wrong, please try again later!'
        ]);
    } //ending function
}//ending class
