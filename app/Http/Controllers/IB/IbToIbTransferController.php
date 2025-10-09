<?php

namespace App\Http\Controllers\IB;

use App\Http\Controllers\Controller;
use App\Models\ExternalFundTransfers;
use App\Models\OtpSetting;
use App\Models\User;
use App\Models\UserOtpSetting;
use App\Services\BalanceService;
use App\Services\CombinedService;
use App\Services\EmailService;
use App\Services\GetPhotosService;
use App\Services\MailNotificationService;
use App\Services\OtpService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class IbToIbTransferController extends Controller
{
    public function index(Request $request)
    {
        $avatar = GetPhotosService::avatar();
        $last_transaction = TransactionService::last_transaction(1, 'send', 'recieve');
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
        return view(
            'ibs.transfer.ib-to-ib-transfer',
            [
                'avatar' => $avatar,
                'last_transaction' => $last_transaction,
                'otp_settings' => $otp_settings,
                'user_otp_settings' => $user_otp_settings,
            ]
        );
    }
    public function make_request(Request $request)
    {
        try {
            // submit form
            $otp_settings = OtpSetting::first();
            $charge = TransactionService::charge('w_to_w', $request->amount, null);

            $user = User::find(auth()->user()->id);
            $validation_rules = [
                'recipient' => 'required',
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
            // start session of form submit

            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                // return status for otp validation
                if ($request->op === 'step-2') {
                    if ($otp_settings->transfer == true) {
                        return Response::json([
                            'otp_status' => false,
                            'message' => 'Please fix the following errors!',
                            'errors' => $validator->errors()
                        ]);
                    }
                    return Response::json([
                        'otp_status' => true,
                        'message' => 'Please fix the following errors!',
                        'errors' => $validator->errors()
                    ]);
                }
                // return status for validation check
                if ($request->op === 'step-1') {
                    return Response::json([
                        'valid_status' => false,
                        'message' => 'Please fix the following errors!',
                        'errors' => $validator->errors()
                    ]);
                }
            }
            // get user balance----------------

            $balance = BalanceService::get_ib_balance_v2(auth()->user()->id);
            // return $balance;
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
                    'errors' => ['transaction_password' => 'Transaction Password Not match!']
                ]);
            }
            // otp sending to user email
            switch (OtpService::has_otp('transfer')) {
                case true:
                    if ($request->op === 'step-1' || $request->op === 'resend') {
                        // create otp
                        $otp_status = OtpService::send_otp();
                        return Response::json(['otp_send' => $otp_status]);
                    }
                    // make transaction with otp
                    if ($request->op === 'step-2') {
                        $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
                        if ($request->session()->get('trader-transfer-otp') == $request_otp) {
                            $time = session('otp_set_time');
                            $minutesBeforeSessionExpire = 5;
                            if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                                $response = $this->make_transaction([
                                    'recipient' => $request->recipient,
                                    'amount' => $request->amount,
                                    'charge' => $charge,
                                    'name' => $user->name,
                                    'request_all' => $request->all(),
                                    'user' => $user,
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
                        'request_all' => $request->all(),
                        'user' => $user,
                    ]);
                    return Response::json($response);
                    break;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // make ib to ib trnasfer 
    private function make_transaction($data)
    {
        $balance = BalanceService::get_ib_balance_v2(auth()->user()->id);
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
        $receiver = User::where('id', $data['recipient'])->select('id', 'type', 'name', 'email', 'combine_access')->first();
        // check receiver type
        // check crm type
        if (strtolower($receiver->type) !== 'ib' && CombinedService::is_combined() == false) {
            return ([
                'status'    => false,
                'message'   => 'The Receiver is not a Trader!'
            ]);
        }
        $created = ExternalFundTransfers::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $receiver->id,
            'amount' => $data['amount'],
            'charge' => $data['charge'],
            'type' => 'ib_to_ib',
            'status' => 'A',
            'txnid' => $invoice,
            'sender_wallet_type' => 'ib',
            'receiver_wallet_type' => 'ib',
            'ip_address' => request()->ip,

        ]);
        //mail script
        if ($created) {
            //notification mail to admin
            // MailNotificationService::notification('balance transfer', 'ib', 1, $data['name'], $data['amount']);
            MailNotificationService::admin_notification([
                'amount'=>$data['amount'],
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'type' => 'balance transfer',
                'client_type' => 'ib'
            ]);
            // get last transaction----------------
            $last_transaction = TransactionService::last_transaction(null, 'ib_to_ib');
            // send mail to client
            EmailService::send_email('ib-to-ib-transfer', [
                'clientWithdrawAmount'      => $data['amount'],
                'user_id' => auth()->user()->id,
                'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                'previous_balance' => (($balance) + ($last_transaction->amount)),
                'transfer_amount' => $last_transaction->amount,
                'total_balance' => $balance,
                'reciever_name' => ucwords($receiver->name),
                'reciever_email' => $receiver->email,
            ]);
            // insert activity-----------------
            //<---client email as user id
            activity("IB to IB transfer")
                ->causedBy(auth()->user()->id)
                ->withProperties($data['request_all'])
                ->event("IB to IB transfer")
                ->performedOn($data['user'])
                ->log("The IP address " . request()->ip() . " has been " .  "IB to IB transfer");
            // end activity log----------------->>
            request()->session()->forget('trader-transfer-otp');
            request()->session()->forget('otp_set_time');

            return ([
                'status' => true,
                'message' => 'Transaction successfully done!',
                'last_transaction' => $last_transaction
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Somthing went wrong, please try again later!',
        ]);
    }
}
