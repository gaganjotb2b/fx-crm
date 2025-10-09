<?php

namespace App\Http\Controllers;

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
use App\Services\balance\BalanceSheetService;
use App\Services\EmailService;
use App\Services\Mt5WebApi;
use App\Services\OtpService;
use App\Services\TransactionService;
use App\Services\Transfer\WtaTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class WtaTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('wallet_to_account', 'trader'));
        $this->middleware(AllFunctionService::access('transfer', 'trader'));
    }
    //basic view------------
    public function wta_transfer_view(Request $request)
    {
        // get last transaction----------------

        $last_transaction = InternalTransfer::where('user_id', auth()->user()->id)->where('type', 'wta')->latest()->first();
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();

        $trading_account = TradingAccount::where('user_id', auth()->user()->id)->where('client_type', 'live')->whereNotNull('account_number')->where('approve_status', 1)->get();
        $option = '';
        if ($trading_account) {
            foreach ($trading_account as $key => $value) {
                $client_group = ClientGroup::where('id', $value->group_id)->first();
                $option .= '<option value="' . encrypt($value->id) . '" data-group-name="'.$client_group->group_id.'">' . $value->account_number . ' (' . $client_group->group_id . ')</option>';
            }
        }
        return view(
            'traders.transfer.wallet-to-account',
            [
                'accounts' => $option,
                'last_transaction' => $last_transaction,
                'otp_settings' => $otp_settings,
                'user_otp_settings' => $user_otp_settings,
            ]
        );
    }
    // get meta logo
    public function meta_logo(Request $request)
    {
        $trading_account = TradingAccount::where('user_id', auth()->user()->id)->where('id', decrypt($request->account))->whereNotNull('account_number')->where('approve_status', 1)->first();
        // start equity check
        $mt5_api = new Mt5WebApi();
        $action = 'AccountGetMargin';

        $data = array(
            "Login" => $trading_account->account_number
        );
        $result = $mt5_api->execute($action, $data);
        $mt5_api->Disconnect();

        $credit = $equity = $balance = $free_margin = 0;
        if (isset($result['success'])) {
            if ($result['success']) {
                $credit = $result['data']['Credit'];
                $equity = $result['data']['Equity'];
                $balance = $result['data']['Balance'];
                $free_margin = isset($result['data']['MarginFree']) ? $result['data']['MarginFree'] : 0;
            }
        }
        // end equity check
        
        // get meta logo
        if (strtolower($trading_account->platform) === 'mt4') {
            $platform_logo = asset('trader-assets/assets/img/logos/platform-logo/mt4.png');
        } else {
            $platform_logo = asset('trader-assets/assets/img/logos/platform-logo/mt5.png');
        }
        return Response::json([
            'platform_logo' => $platform_logo,
            'credit' => $credit,
            'equity' => $equity,
            'balance' => $balance,
            'free_margin' => $free_margin,
        ]);
    }

    // wallet to account transfer---------------
    public function wta_transfer(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            // start session of form submit
            $multiple_submission = has_multi_submit('wta-transfer', 30);
            multi_submit('wta-transfer', 30);

            $otp_settings = OtpSetting::first();
            $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
            // get trading account------
            $trading_account = TradingAccount::find(decrypt($request->account));
            //charge applied here
            $charge = TransactionService::charge('w_to_a', $request->amount, null);
            $data = [];
            $validation_rules = [
                // 'platform' => 'required',
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
            // check authenticated request
            if ($trading_account->user_id != auth()->user()->id) {
                return Response::json([
                    'valid_status' => false,
                    'message' => 'You try with invalid account number!',
                ]);
            }
            // get user balance----------------
            $fn = new AllFunctionService();
            $balance = BalanceSheetService::trader_wallet_balance(auth()->user()->id);

            // check available balance---------

            if ($balance <= 0 || ($request->amount <= 0) || (($request->amount + $charge) > $balance)) {
                $data['valid_status'] = false;
                if ($request->amount <= 0) {
                    $data['errors'] = ['amount' => 'Requested amount must be greater than 0'];
                } else {
                    $data['errors'] = ['amount' => "You don't have enough balance!"];
                }
                $data['message'] = 'Please fix the following errors';
                return Response::json($data);
            }
            // // check transaction password
            // if (!Hash::check($request->transaction_password, auth()->user()->transaction_password)) {
            //     return Response::json([
            //         'valid_status' => false,
            //         'message' => 'Please fix the following errors.',
            //         'errors' => ['transaction_password' => 'Transaction Password Not match!']
            //     ]);
            // }
            // check live account
            if ($trading_account->client_type == 'demo') {
                return Response::json([
                    'valid_status' => false,
                    'message' => 'You Can not use demo account',
                    'errors' => ['account' => 'You can not use demo account to transfer balance']
                ]);
            }
            if (OtpService::has_otp('transfer')) {
                // otp sending to user email
                if ($request->op === 'step-1' || $request->op === 'resend') {
                    // create otp and send otp
                    $otp_status = OtpService::send_otp();
                    return Response::json(['otp_send' => $otp_status]);
                }
            }
            // if otp is off
            else {
                if ($client_group->group_id == "Cent Account" ){
                    $is_cent_acc = true;
                }else{
                    $is_cent_acc = false;
                }
                $data = WtaTransferService::balance_update($trading_account->account_number, $request->amount, $is_cent_acc);
                return Response::json($data);
            }
            // insert data and otp verify
            // if otp is one
            if ($request->op === 'step-2') {
                $request_otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4 . $request->otp_5 . $request->otp_6;
                if ($request->session()->get('wta-transfer-otp') == $request_otp) {
                    $time = session('otp_set_time');
                    $minutesBeforeSessionExpire = 5;
                    if (isset($request_otp) && (time() - $time < ($minutesBeforeSessionExpire * 60))) {
                        // wta service process
                        if ($client_group->group_id == "Cent Account" ){
                            $is_cent_acc = true;
                        }else{
                            $is_cent_acc = false;
                        }
                        $data = WtaTransferService::balance_update($trading_account->account_number, $request->amount, $is_cent_acc);
                        if ($data['status'] === true) {
                            $request->session()->forget('wta-transfer-otp');
                            $request->session()->forget('otp_set_time');
                        }
                        return Response::json($data);
                    } else {
                        $data['otp_status'] = false;
                        $data['message'] = 'OTP Time Out!';
                        return Response::json($data);
                    }
                } else {
                    $data['otp_status'] = false;
                    $data['message'] = 'OTP not matched!';
                    return Response::json($data);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
}
