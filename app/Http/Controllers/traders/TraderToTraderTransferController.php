<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Mail\MailNotification;
use App\Mail\OTPverificationMail;
use App\Mail\transfer\BalanceTransfer;
use App\Models\admin\InternalTransfer;
use App\Models\admin\SystemConfig;
use App\Models\ExternalFundTransfers;
use App\Models\OtpSetting;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\UserOtpSetting;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\Mt5WebApi;
use App\Services\OtpService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TraderToTraderTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('trader_to_trader', 'trader'));
        $this->middleware(AllFunctionService::access('transfer', 'trader'));
    }
    // basic view------------
    public function trader_transfer_view(Request $request)
    {

        // get last transaction----------------
        $last_transaction = ExternalFundTransfers::where('sender_id', auth()->user()->id)->where('type', 'send')->latest()->first();
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)->first();

        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();

        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }

        return view(
            'traders.transfer.trader-to-trader',
            [
                'last_transaction' => $last_transaction,
                'avatar' => $avatar,
                'otp_settings' => $otp_settings,
                'user_otp_settings' => $user_otp_settings,
            ]
        );
    }
    // account to wallet transfer
    public function trader_transfer(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $multiple_submission = has_multi_submit('trader-transfer', 15);
            $data = [];

            $otp_settings = OtpSetting::first();
            $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
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

            // get user balance----------------
            $balance = BalanceSheetService::trader_wallet_balance(auth()->user()->id);
            // check available balance---------
            if ($balance <= 0 || $request->amount > $balance) {
                $data['valid_status'] = false;
                $data['errors'] = ['amount' => "You don't have available balance!"];
                $data['message'] = 'Please fix the following errors';
                return Response::json($data);
            }
            // check transaction password
            if (!Hash::check($request->transaction_password, auth()->user()->transaction_password)) {
                return Response::json([
                    'valid_status' => false,
                    'message' => 'Please fix the following errors.',
                    'errors' => ['transaction_password' => 'Transaction Password Not match!']
                ]);
            }
            // otp sending to user email
            if (OtpService::has_otp('transfer')) {
                if ($request->op === 'step-2' || $request->op === 'resend') {
                    // create otp and send otp
                    $otp_status = OtpService::send_otp();
                    return Response::json(['otp_send' => $otp_status]);
                }
            }
            // transfer without otp
            // when otp off by admin/client
            else {
                $response = $this->create_transaction([
                    'recipient' => $request->recipient,
                    'amount' => $request->amount,
                    'charge' => $charge,
                    'all' => $request->all(),
                ]);
                return Response::json($response);
            }
            // when otp on by admin/client
            // insert data and otp verify
            if ($request->op === 'step-2') {
                $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
                if ($request->session()->get('trader-transfer-otp') == $request_otp) {
                    $time = session('otp_set_time');
                    $minutesBeforeSessionExpire = 5;
                    if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 15))) {
                        $response = $this->create_transaction([
                            'recipient' => $request->recipient,
                            'amount' => $request->amount,
                            'charge' => $charge,
                            'all' => $request->all(),
                        ]);
                        return Response::json($response);
                    }
                    return Response::json([
                        'otp_status' => false,
                        'message' => 'OTP Time Out!'
                    ]);
                }
                return Response::json([
                    'otp_status' => false,
                    'message' => 'OTP not Matched!'
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // create transaction
    private function create_transaction($data)
    {
        // get trading account------
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
        $receiver = User::where('id', $data['recipient'])->select('id', 'email', 'name')->first();
        $user = User::find(auth()->user()->id);
        $created = ExternalFundTransfers::create([
            'txnid' => $invoice,
            'sender_id' => auth()->user()->id,
            'receiver_id' => $receiver->id,
            'amount' => $data['amount'],
            'charge' => $data['charge'],
            'type' => 'trader_to_trader',
            'status' => 'A',
            'sender_wallet_type' => 'trader', // sender wallet type
            'receiver_wallet_type' => 'trader' // receiver wallet type
        ]);
        //mailer script
        if ($created) {
            //notification mail to admin
            // MailNotificationService::notification('balance transfer', 'trader', 1, $user->name, $data['amount']);
            MailNotificationService::admin_notification([
                'amount'=>$data['amount'],
                'name'=>auth()->user()->name,
                'email'=>auth()->user()->email,
                'type'=>'balance transfer',
                'client_type'=>'trader'
            ]);
            // get last transaction----------------
            $last_transaction = ExternalFundTransfers::where('sender_id', auth()->user()->id)->where('type', 'trader_to_trader')->latest()->first();
            EmailService::send_email('trader-to-trader-transfer', [
                'user_id' => auth()->user()->id,
                'clientDepositAmount' => $data['amount'],
                'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                'previous_balance' => ((BalanceSheetService::trader_wallet_balance($user->id)) + ($last_transaction->amount)),
                'transfer_amount' => $last_transaction->amount,
                'total_balance' => BalanceSheetService::trader_wallet_balance($user->id),
                'reciever_name' => ucwords($receiver->name),
                'reciever_email' => $receiver->email,
            ]);
            // get last transaction
            // insert activity-----------------
            $user = User::find(auth()->user()->id);
            //<---client email as user id
            activity("trader to trader")
                ->causedBy(auth()->user()->id)
                ->withProperties($data['all'])
                ->event("trader to trader")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been trader to trader transfer");
            // end activity log----------------->>
            request()->session()->forget('trader-transfer-otp');
            request()->session()->forget('otp_set_time');
            return ([
                'status' => true,
                'message' => 'Transaction successfully done!',
                'last_transaction' => $last_transaction,
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Something went wrong, Please try again later!',
        ]);
    }
}
