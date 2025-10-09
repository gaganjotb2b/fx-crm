<?php

namespace App\Http\Controllers\traders;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\BankAccount;
use App\Models\Deposit;
use App\Models\OtherTransaction;
use App\Models\PaymentGatewayConfig;
use App\Models\TradingAccount;
use App\Services\deposit\B2bDepositService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;
use Illuminate\Support\Facades\Validator;

class B2bDepositController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('b2binpay_deposit', 'trader'));
        $this->middleware(AllFunctionService::access('deposit', 'trader'));
    }
    public function index(Request $request)
    {
        $trading_accounts = TradingAccount::where('user_id', auth()->user()->id)->get();
        return view('traders.deposit.b2b-deposit', [
            'trading_accounts'   => $trading_accounts
        ]);
    }
    public function create_deposit(Request $request)
    {
        try {
            $validtion_ruls = [
                'trading_account' => (strtolower($request->deposit_option) === 'account') ? 'required' : 'nullable',
                'crypto_currency' => 'required',
                'amount_usd' => 'required',
                'amount_crypto' => 'required',
            ];
            $validator = Validator::make($request->all(), $validtion_ruls);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            // check b2binpay configured or not
            $configuration = PaymentGatewayConfig::where('gateway_name', 'b2binpay')->first();
            if (!$configuration || empty($configuration->merchent_code)) {
                return Response::json([
                    'status' => false,
                    'message' => 'B2BinPay not configured please try another way to deposit'
                ]);
            }
            $response = B2bDepositService::b2b_post([
                'user' => auth()->user(),
                'amount' => $request->amount_crypto,
                'currency' => $request->currency
            ]);
            if ($response['status'] == false) {
                return Response::json($response);
            }
            // activity log------------------------------->
            $user = User::find(auth()->user()->id);
            activity('b2binpay deposit request')
                ->causedBy($user->id)
                ->withProperties($response)
                ->event('b2binpay deposit')
                ->performedOn($user)
                ->log('The IP address ' . request()->ip() . ' has been make b2binpay deposit request');
            // end activity log---------------------------->
            Session::put('currency', $request->crypto_currency);
            Session::put('amount', $request->amount_usd);
            // return $response;

            $attribute = $response->data->attributes;
            $relationships = $response->data->relationships;
            $create_internal = false;
            // if direct account deposit
            if (strtolower($request->deposit_option) === 'account') {
                $trading_accounts = TradingAccount::where('id', decrypt($request->trading_account))->first();
                if (!$trading_accounts) {
                    return Response::json([
                        'status' => false,
                        'message' => 'Trading account not found! Invalid trading account'
                    ]);
                }
                $create_internal = InternalTransfer::create([
                    'user_id' => auth()->user()->id,
                    'platform' => $trading_accounts->platform,
                    'account_id' => $trading_accounts->id,
                    'invoice_code' => $attribute->address,
                    'amount' => $request->usd_amount,
                    'charge' => 0,
                    // 'order_id'=>
                    'type' => 'wta',
                    'status' => 'P',
                    'client_log' => AdminLogService::admin_log(),
                ]);
            }
            // create othr transaction
            $others = OtherTransaction::create([
                'transaction_type' => 'b2bin',
                'crypto_type' => $request->crypto_currency,
                'crypto_instrument' => $relationships->currency->data->id,
                'crypto_address' => $attribute->address,
                'crypto_amount' => $request->local_currency,
                'b2b_details' => json_encode($attribute->destination)
            ]);
            // create deposit
            $create = Deposit::create([
                'user_id' => auth()->user()->id,
                'invoice_id' => $attribute->address,
                'transaction_type' => 'b2bin',
                'transaction_id' => $response->data->id, // transaction id as id
                'incode' => $attribute->tracking_id, // icode as tracking id
                'amount' => $request->amount_usd,
                'ip_address' => request()->ip(),
                'approved_status' => 'P',
                'local_currency' => $request->amount_crypto,
                'currency' => $request->crypto_currency,
                'other_transaction_id' => $others->id,
                'wallet_type' => 'trader',
                'created_by' => 'system',
                'account' => (strtolower($request->deposit_option) === 'account') ? $request->trading_account : null,
                'deposit_options' => $request->deposit_option,
                'internal_transfer' => ($create_internal) ? $create_internal->id : null,
            ]);
            if (!$create) {
                return Response::json([
                    'status' => false,
                    'message' => 'Somthing went wrong, please try again later!'
                ]);
            }
            // sending mail to admin and account manager
            MailNotificationService::admin_notification([
                'amount' => $request->amount_usd,
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'type' => 'deposit',
                'client_type' => 'trader'
            ]);
            return Response::json([
                'status' => true,
                'message' => 'Please wait while we redirect you',
                'redirect_to' => $response->data->attributes->payment_page
            ]);
        } catch (\Throwable $th) {
            throw $th;
            // return $response = B2bDepositService::b2b_post([
            //     'user' => auth()->user(),
            //     'amount' => $request->amount_crypto,
            //     'currency' => $request->currency
            // ]);
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    public function call_back(Request $request)
    {
        // this function not needed
        // alternative function notification
        // function notification call by api
        return $request->all();
    }

    public function notification(Request $request)
    {
        // this function call by api
        try {

            $login = 'X2sKdyb0t4RnNyuh';
            $password = 'TDFJTXhvVixOxe';

            // $log_data = DB::table('activity_log')->where('id', 55541)->select('properties')->first();
            // $log_data = json_decode($log_data->properties);
            $callback_payload = ($request->all());
            if (array_key_exists('meta', $callback_payload)) {
                $callback_sign = $callback_payload['meta']['sign'];
                $callback_time = $callback_payload['meta']['time'];

                # retrieve transfer and deposit attributes
                $included_transfer = array_filter(
                    $callback_payload['included'],
                    function ($item) {
                        return $item['type'] === 'transfer';
                    }
                );

                $included_transfer = array_pop($included_transfer)['attributes'];
                $deposit = $callback_payload['data']['attributes'];

                $status = $included_transfer['status'];
                $amount = $included_transfer['amount'];
                $tracking_id = $deposit['tracking_id'];
                $txid = $included_transfer['txid'];
                # prepare data for hash check
                $message = $status . $amount . $tracking_id . $callback_time;
                $hash_secret = hash('sha256', $login . $password, true);
                $hash_hmac_result = hash_hmac('sha256', $message, $hash_secret);
                // activity log store
                $array_tracking_id = explode('-', $tracking_id);
                $user_id = $array_tracking_id[0];
                $user = User::find($user_id);
                activity('b2binpay deposit')
                    ->causedBy($user->id)
                    ->withProperties($request->all())
                    ->event('b2binpay deposit')
                    ->performedOn($user)
                    ->log('The IP address ' . request()->ip() . ' has been make b2binpay deposit');
                // end log store
                # print result
                if ($hash_hmac_result === $callback_sign) {
                    // return 'Verified';
                    $deposit = Deposit::where('incode', $tracking_id)->update([
                        'approved_status' => 'A',
                        'note' => 'automatic approved by ip ' . request()->ip(),
                        'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                        'invoice_id' => $txid
                    ]);
                    return response('', HttpResponse::HTTP_OK);
                } else {
                    // return 'Invalid sign';
                    $deposit = Deposit::where('incode', $tracking_id)->update([
                        'approved_status' => 'D',
                        'note' => 'automatic declined by ip ' . request()->ip() . ' for invalid sign',
                        'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                    ]);
                }
            }
        } catch (\Throwable $th) {
            // throw $th;
            // return $request->all();
            $user = User::find(4678);
            activity('b2binpay deposit failed')
                ->causedBy($request->id)
                ->withProperties($request->all())
                ->event('b2binpay deposit')
                ->performedOn($user)
                ->log(substr($th->getMessage(), 0, 190));
            return response('', HttpResponse::HTTP_OK);
        }
    }
}
