<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CryptoMailForITCorner;
use App\Models\Admin;
use App\Models\admin\SystemConfig;
use App\Models\OtherTransaction;
use App\Models\OtpSetting;
use App\Models\User;
use App\Models\UserOtpSetting;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\BalanceService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\OtpService;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class WithdrawController extends Controller
{
    //api bank withdraw
    public function bank_withdraw(Request $request)
    {
        $charge = TransactionService::charge('withdraw', $request->amount, $request->user_id);
        $has_otp = OtpService::has_otp('withdraw', $request->user_id);
        // check otp enable or not
        if ($has_otp) {
            if ($request->step_otp === true) {
                return OtpService::send_otp();
            }
        }

        // check validation
        $validation_rule = [
            'bank_id' => 'required|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:1',
            'transaction_password' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rule);
        if ($validator->fails()) {
            return ([
                'status' => false,
                'message' => 'Error Found!',
                'errors' => $validator->errors(),
            ]);
        }
        // check balance available or not
        if (BalanceService::check_balance($request->amount, $charge, $request->user_id) == false) {
            return ([
                'status' => false,
                'message' => "Your don't have available balance!"
            ]);
        }
        // check transaction password
        if (!Hash::check($request->transaction_password)) {
            return ([
                'valid_status' => false,
                'message' => 'Please fix the following errors.',
                'errors' => ['transaction_password' => 'Transaction Password Not match!']
            ]);
        }
        // if validation success
        // check otp matched or not
        if ($has_otp == true && ($request->otp_code === $request->session()->get('bank-withdraw-otp'))) {
            // check otp session expired or not
            if (OtpService::otp_expire($request->otp_code)) {
                // create transaction
                return $this->create_bank_withdraw([
                    'bank_account' => $request->bank_id,
                    'amount' => $request->amount,
                    'charge' => $charge,
                    'currency_name' => $request->currency_name,
                    'amount_local' => $request->amount_local,
                    'all' => $request->all(),
                    'user_id' => $request->user_id,
                ]);
            }
            return ([
                'status' => false,
                'message' => 'OTP Time Out!'
            ]);
        } else if (strtolower($request->step_otp) === 'success') {
            // create transaction
            return $this->create_bank_withdraw([
                'bank_account' => $request->bank_id,
                'amount' => $request->amount,
                'charge' => $charge,
                'currency_name' => $request->currency_name,
                'amount_local' => $request->amount_local,
                'all' => $request->all(),
                'user_id' => $request->user_id,
            ]);
        }
        return ([
            'status' => false,
            'message' => 'OTP not matched!'
        ]);
    }
    // bank withdraw create
    private function create_bank_withdraw($data)
    {
        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
        $user = User::find($data['user_id']);
        $created = Withdraw::create([
            'user_id' => $data['user_id'],
            'transaction_id' => $invoice,
            'bank_account_id' => $data['bank_account'],
            'amount' => $data['amount'],
            'charge' => $data['charge'],
            'approved_status' => 'P',
            'transaction_type' => 'bank',
            'currency' => $data['currency_name'] ?? "",
            'local_currency' => $data['amount_local'] ?? 0
        ]);
        if ($created) {
            //notification mail to admin
            MailNotificationService::notification('withdraw', 'trader', 1, $user->name, $data['amount']);
            // sending mail to 
            $last_transaction = Withdraw::find($created->id);
            EmailService::send_email('withdraw-request', [
                'clientWithdrawAmount'      => $data['amount'],
                'user_id'                   => $data['user_id'],
                'deposit_method'            => ($last_transaction) ? ucwords($last_transaction->transaction_type) : '',
                'deposit_date'              => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                'previous_balance'          => ((AllFunctionService::trader_total_balance($data['user_id'])) + ($last_transaction->amount)),
                'approved_amount'           => $last_transaction->amount,
                'total_balance'             => AllFunctionService::trader_total_balance($data['user_id'])
            ]);

            request()->session()->forget('bank-withdraw-otp');
            request()->session()->forget('otp_set_time');
            // insert activity-----------------
            //<---client email as user id

            activity("bank withdraw")
                ->causedBy($data['user_id'])
                ->withProperties($data['all'])
                ->event("bank withdraw")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been " . "withdraw");
            // end activity log----------------->>
            return ([
                'status' => true,
                'message' => 'Withdraw Request successfully submited.',
                'last_transaction' => $last_transaction
            ]);
        }
        return ([
            'status' => false,
            'message' => 'Somthing went wrong, please try agian later!.'
        ]);
    }
    //crypto deposit 
    public function crypto_withdraw(Request $request)
    {
        $user_id = $request->user_id ?? auth()->user()->id;
        $user_transaction_pass = $request->user_transaction_pass ?? auth()->user()->transaction_password;
        $user = User::find($user_id);
        $otp_settings = OtpSetting::first();
        $user_otp_settings = UserOtpSetting::where('user_id', $user_id)->first();
        $request_otp = isset($request->otp) ? $request->otp : "";
        $data = [];
        if ($request_otp != "") {
            $user_otp = User::where('id', $user_id)->first();
            // difference of time start
            $currentDateTime = date('Y-m-d H:i:s');
            $keyUpdatedAt = $user_otp->updated_at;
            $carbonUpdatedAt = \Carbon\Carbon::parse($keyUpdatedAt);
            $diffInSeconds = $carbonUpdatedAt->diffInSeconds($currentDateTime);
            // difference of time end
            if ($user_otp->secret_key !== $request_otp) {
                // if ($user_otp->secret_key !== $request->otp && $diffInSeconds > 150) {
                return ([
                    'status' => false,
                    'message' => 'Invalid OTP Code!',
                    'withdraw_status' => false,
                    'otp_verify' => false,
                ]);
            } else {
                $otp_settings->withdraw == false;
            }
        } else if ($otp_settings->withdraw == true) {
            $data['otp_status'] = true;
            $data['block_chain'] = $request->block_chain;
            $data['instrument'] = $request->instrument;
            $data['crypto_address'] = $request->crypto_address;
            $data['usd_amount'] = $request->usd_amount;
            $data['crypto_amount'] = $request->crypto_amount;
            $data['transaction_password'] = $request->transaction_password;
            $data['method'] = $request->method;
            // create otp
            $otp = random_int(100000, 999999);
            User::where('id', $user_id)->update([
                'secret_key' => $otp
            ]);
            $mail_status = EmailService::send_email('otp-verification', [
                'user_id'  => $user_id,
                'otp'      => $otp,
            ]);
            if ($mail_status) {
                return ([
                    'status' => true,
                    'message' => 'OTP code successfully sent to your mail!',
                    'withdraw_data' => $data,
                    'withdraw_status' => false,
                    'otp_verify' => true,
                ]);
            } else {
                return ([
                    'status' => false,
                    'message' => 'Failed to send OTP code!',
                    'withdraw_status' => false,
                    'otp_verify' => false,
                ]);
            }
        }
        //charge applied here
        $charge = TransactionService::charge('withdraw', $request->usd_amount, $user_id);

        $all_fun = new AllFunctionService();
        $balance = $all_fun->get_self_balance($user_id);
        if ($balance <= 0 || (($request->usd_amount + $charge) > $balance)) {
            $data['valid_status'] = false;
            $data['errors'] = ['usd_amount' => "You don't have available balance"];
            $data['message'] = "You don't have available balance";
            return Response::json($data);
        }

        if (!Hash::check($request->transaction_password, $user_transaction_pass)) {
            $data['valid_status'] = false;
            $data['errors'] = ['tpassword' => 'Transaction password not matched!'];
            $data['message'] = 'Transaction password not matched!';
            return Response::json($data);
        }

        $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);

        // crypto transaction
        $crypto_txn = OtherTransaction::create([
            'transaction_type' => 'crypto',
            'crypto_type' => $request->block_chain,
            'crypto_instrument' => $request->instrument,
            'crypto_address' => $request->crypto_address,
            'crypto_amount' => $request->crypto_amount,
        ])->id;

        $withdraw = Withdraw::create([
            'user_id' => $user_id,
            'transaction_id' => $invoice,
            'transaction_type' => 'crypto',
            'other_transaction_id' => $crypto_txn,
            'amount' => $request->usd_amount,
            'charge' => $charge
        ]);

        //mailer script
        if ($crypto_txn && $withdraw) {
            $last_transaction = Withdraw::find($withdraw->id);
            // sending mail to user
            EmailService::send_email('crypto-withdraw-request', [
                'cryptoAddress' => $request->crypto_address,
                'currency' => $request->block_chain,
                'blockchain' => $request->instrument,
                'amount' => $request->usd_amount,
                'cryptoAmount' => $request->crypto_amount,
                'status' => "Pending",
                'user_id' => $user_id,
            ]);

            //start: mail for itcorner
            $message_to_itcorner = '<p> A crypto withdraw request to your software from <strong>' . $request->user_email . '.</strong> </p>
                <table style="text-align:left; border-collapse:collapse; margin-top:2rem">
                    <tbody>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Address</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $request->crypto_address . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Currency</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $request->block_chain . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Blockchain</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $request->instrument . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Amount</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> $' . $request->usd_amount . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Crypto Amount</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px"> ' . $request->crypto_amount . ' </td>
                        </tr>
                        <tr>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#5a1515;padding:15px">Status</td>
                            <td style="text-align:center;border:solid 1px #cbcbb8;color:#ffa442;padding:15px"> Pending </td>
                        </tr>
                    </tbody>
                </table>';
            $to_itcorner = 'gainxplus1@gmail.com';
            $support_email = SystemConfig::select('support_email')->first();
            $support_email = ($support_email) ? $support_email->support_email : default_support_email();



            $it_corner_data = [
                'name'                  => 'Author',
                'master-admin'          => $to_itcorner,
                'it_corner_message'     => $message_to_itcorner,
                'transaction'           => "crypto_withdraw",
            ];

            Mail::to($to_itcorner)->send(new CryptoMailForITCorner($it_corner_data));

            //end: mail for itcorner

            // admin notification after crypto withdraw 
            $super_admin = Admin::select('user_id')->first();
            $it_corner_data = [
                'name'                  => 'Super Admin',
                'master-admin'          => ($super_admin) ? $super_admin->user_id : 1,
                'it_corner_message'     => $message_to_itcorner,
                'transaction'           => "crypto_withdraw",
            ];

            Mail::to($to_itcorner)->send(new CryptoMailForITCorner($it_corner_data));
            // return if withdraw created
            return ([
                'status' => true,
                'message' => 'Withdraw request successfully submited',
                'withdraw_status' => 'success',
                'otp_verify' => false
            ]);
        }
        // return if withdraw creation faild
        return ([
            'status' => false,
            'message' => 'Something went wrong please try again later!',
        ]);
    }
    // get withdraw
    public function get_client_withdraw(Request $request)
    {
        try {
            $validation_rules = [
                'status' => 'nullable|in:approved,pending,declined',
                'method' => 'nullable|in:bank,cash,prexis,help2pay,m2pay',
                'min_amount' => 'nullable|min:0|numeric',
                'max_amount' => 'nullable|min:0|numeric',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'error' => "Validation Error",
                    'message' => "The request data is invalid.",
                    'errors' => $validator->errors(),
                ], 400);
            }
            $user = User::find(auth()->guard('api')->user()->id);
            $trader_user = $user;
            
            if ($user->type === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            $withdraw = Withdraw::where('user_id', isset($trader_user->id)?$trader_user->id:'')->where('wallet_type', 'trader');
            // filter by status
            if (isset($request->status) && $request->status != "") {
                $status = '';
                if (strtolower($request->status) === 'approved') {
                    $status = 'A';
                } elseif (strtolower($request->status) === 'pending') {
                    $status = 'P';
                } elseif (strtolower($request->status) === 'declined') {
                    $status = 'D';
                }
                $withdraw = $withdraw->where('approved_status', $status);
            }
            // filter by method
            if (isset($request->method) && $request->method != "") {
                $withdraw = $withdraw->where('transaction_type', strtolower($request->method));
            }
            // filter by min amount
            if ($request->min_amount) {
                $withdraw = $withdraw->where('amount', '>=', $request->min_amount);
            }
            if ($request->max_amount) {
                $withdraw = $withdraw->where('amount', '<=', $request->max_amount);
            }
             // filter by date to
             if ($request->input('date_to')) {
                $to  = Carbon::parse($request->input('date_to'));
                $withdraw = $withdraw->whereDate('created_at', '<=', $to);
            }
            // filter by date from
            if ($request->input('date_from')) {
                $date_from  = Carbon::parse($request->input('date_from'));
                $withdraw = $withdraw->whereDate('created_at', '>=', $date_from);
            }
            $total_amount = $withdraw->sum('amount');
            $withdraw = $withdraw->orderBy('withdraws.created_at', 'DESC')
                ->with(['bankAccount', 'tradingAccount', 'accountTransfer'])
                ->paginate($request->input('per_page', 5));
            if ($withdraw) {
                return ([
                    'status' => true,
                    'withdraw' => $withdraw,
                    'total_amount' => $total_amount,
                ]);
            }
            return ([
                'status' => false,
                'message' => 'Data not found'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                "error" => $th->getMessage(),
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
}
