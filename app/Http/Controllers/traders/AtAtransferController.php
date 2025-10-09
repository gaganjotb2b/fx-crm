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
use App\Services\Transfer\WtaTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AtAtransferController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(AllFunctionService::access('account_to_wallet', 'trader'));
    //     $this->middleware(AllFunctionService::access('transfer', 'trader'));
    // }
    // basic view------------
    public function ata_transfer_view(Request $request)
    {
        $trading_account = TradingAccount::where('user_id', auth()->user()->id)->where('client_type', 'live')->whereNotNull('account_number')->where('approve_status', 1)->get();
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();

        $option = '';
        if ($trading_account) {
            foreach ($trading_account as $key => $value) {
                $client_group = ClientGroup::where('id', $value->group_id)->first();
                if($client_group->group_id != "Cent Account"){
                    $option .= '<option value="' . encrypt($value->id) . '" data-group-name="'.$client_group->group_id.'">' . $value->account_number . ' (' . $client_group->group_id . ')</option>';
                }
            }
        }
        // get last transaction----------------
        $last_transaction = InternalTransfer::where('user_id', auth()->user()->id)->where('type', 'atw')->latest()->first();

        return view('traders.transfer.account-to-account', [
            'accounts' => $option,
            'last_transaction' => $last_transaction,
            'otp_settings' => $otp_settings,
            'user_otp_settings' => $user_otp_settings,
        ]);
    }
    // ****************************************************************
    // account to account transfer
    public function ata_transfer(Request $request)
    {
        try {
            if (decrypt($request->account_send) == decrypt($request->account_receive)) {
                return Response::json([
                    'valid_status' => false,
                    'message' => 'You have been choosen same accounts!',
                ]);
            }
            $user = User::find(auth()->user()->id);
            // start session of form submit
            $multiple_submission = has_multi_submit('ata-transfer', 30);
            multi_submit('ata-transfer', 60);

            $otp_settings = OtpSetting::first();
            $user_otp_settings = UserOtpSetting::where('user_id', auth()->user()->id)->first();
            $validation_rules = [
                'account_send' => 'required',
                'account_receive' => 'required',
                'amount' => 'required|numeric'
            ];
            // get trading account------
            $trading_account = TradingAccount::find(decrypt($request->account_send));
            $client_group = ClientGroup::where("id", $trading_account->group_id)->first();
            // return validation status
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                $data['message'] = 'Please fix the following errors!';
                $data['errors'] = $validator->errors();
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
            if ($trading_account->client_type == 'demo') {
                return Response::json([
                    'valid_status' => false,
                    'message' => 'You can not use demo account',
                    'errors' => ['account' => 'You can not use demo account to transfer balance']
                ]);
            }

            if ($client_group->group_id == "Cent Account" ){
                $is_cent_acc = true;
            }else{
                $is_cent_acc = false;
            }
            // withdraw from sender
            $data = AtwTransferService::balance_update($trading_account->account_number, $request->amount, $is_cent_acc);
            if($data['status'] == true){
                // get trading account------
                $trading_account2 = TradingAccount::find(decrypt($request->account_receive));
                if ($trading_account2->user_id != auth()->user()->id) {
                    return Response::json([
                        'valid_status' => false,
                        'message' => 'You try with invalid account number!',
                    ]);
                }
                $data = WtaTransferService::balance_update($trading_account2->account_number, $request->amount, $is_cent_acc);
                if($data['status'] == true){
                    return Response::json($data);
                }else{
                    return Response::json([
                        'status' => true,
                        'message' => 'Failed to transfer in '. decrypt($request->account_receive),
                    ]);
                }
            }
            // if ($data['status'] == true) {
            //     $request->session()->forget('ata-transfer-otp');
            //     $request->session()->forget('otp_set_time');
            // }
            // return Response::json($data);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
}
