<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Mail\OTPverificationMail;
use App\Mail\transfer\BalanceTransfer;
use App\Models\admin\InternalTransfer;
use App\Models\admin\SystemConfig;
use App\Models\OtpSetting;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\ClientGroup;
use App\Models\UserOtpSetting;
use App\Services\AllFunctionService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\Mt5WebApi;
use App\Services\OtpService;
use App\Services\TransactionService;
use App\Services\Transfer\AtwTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AtwTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('account_to_wallet', 'trader'));
        $this->middleware(AllFunctionService::access('transfer', 'trader'));
    }
    // basic view------------
    public function atw_transfer_view(Request $request)
    {
        $trading_account = TradingAccount::where('user_id', auth()->user()->id)->where('client_type', 'live')->whereNotNull('account_number')->where('approve_status', 1)->get();
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();

        $option = '';
        if ($trading_account) {
            foreach ($trading_account as $key => $value) {
                $client_group = ClientGroup::where('id', $value->group_id)->first();
                $option .= '<option value="' . encrypt($value->id) . '" data-group-name="'.$client_group->group_id.'">' . $value->account_number . ' (' . $client_group->group_id . ')</option>';
            }
        }
        // get last transaction----------------
        $last_transaction = InternalTransfer::where('user_id', auth()->user()->id)->where('type', 'atw')->latest()->first();

        return view('traders.transfer.account-to-wallet', [
            'accounts' => $option,
            'last_transaction' => $last_transaction,
            'otp_settings' => $otp_settings,
            'user_otp_settings' => $user_otp_settings,
        ]);
    }
    // ****************************************************************
    // account to wallet transfer
    public function atw_transfer(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            // start session of form submit
            $multiple_submission = has_multi_submit('atw-transfer', 30);
            multi_submit('atw-transfer', 60);

            $otp_settings = OtpSetting::first();
            $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
            // get trading account------
            $trading_account = TradingAccount::find(decrypt($request->account));
            $validation_rules = [
                'account' => 'required',
                'amount' => 'required|numeric',
                // 'transaction_password' => 'required',
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
            $client_group = ClientGroup::where("id", $trading_account->group_id)->first();
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
            // check authenticate
            if ($trading_account->user_id != auth()->user()->id) {
                return Response::json([
                    'valid_status' => false,
                    'message' => 'You try with invalid account number!',
                ]);
            }
            // get user balance----------------
            $fn = new AllFunctionService();
            // // check transaction password
            // if (!Hash::check($request->transaction_password, auth()->user()->transaction_password)) {
            //     return Response::json([
            //         'valid_status' => false,
            //         'message' => 'Please fix the following errors.',
            //         'errors' => ['transaction_password' => 'Transaction Password Not match!']
            //     ]);
            // }
            // otp sending to user email

            if ($trading_account->client_type == 'demo') {
                return Response::json([
                    'valid_status' => false,
                    'message' => 'You Can not use demo account',
                    'errors' => ['account' => 'You can not use demo account to transfer balance']
                ]);
            }
            // if otp is on
            if (OtpService::has_otp('transfer')) {
                if ($request->op === 'step-1' || $request->op === 'resend') {
                    // create otp and send otp
                    $otp_status = OtpService::send_otp();
                    return Response::json(['otp_send' => $otp_status]);
                }
            }
            // if otp is stop 
            // insert data
            else {
                if ($client_group->group_id == "Cent Account" ){
                    $is_cent_acc = true;
                }else{
                    $is_cent_acc = false;
                }
                $data = AtwTransferService::balance_update($trading_account->account_number, $request->amount, $is_cent_acc);
                if ($data['status'] == true) {
                    $request->session()->forget('atw-transfer-otp');
                    $request->session()->forget('otp_set_time');
                }
                return Response::json($data);
            }
            // if otp is on
            // insert data and otp verify
            if ($request->op === 'step-2') {
                $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
                if ($request->session()->get('atw-transfer-otp') == $request_otp) {
                    $time = session('otp_set_time');
                    $minutesBeforeSessionExpire = 5;
                    if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                        if ($client_group->group_id == "Cent Account" ){
                            $is_cent_acc = true;
                        }else{
                            $is_cent_acc = false;
                        }
                        $data = AtwTransferService::balance_update($trading_account->account_number, $request->amount, $is_cent_acc);
                        if ($data['status'] == true) {
                            $request->session()->forget('atw-transfer-otp');
                            $request->session()->forget('otp_set_time');
                        }
                        return Response::json($data);
                    } else {
                        $data['otp_status'] = false;
                        $data['message'] = 'OTP time Out!';
                        return $data;
                    }
                }

                $data['otp_status'] = false;
                $data['message'] = 'OTP not matched!';
                return $data;
            }
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
}
