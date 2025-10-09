<?php

namespace App\Http\Controllers\traders\deposit\nowpay;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use App\Services\deposit\NowPaymentsAPI2;
use App\Services\systems\AdminLogService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class NowPayController extends Controller
{
    private $user_name = "marvelapi@orbinoxprime.com";
    private $password = "ggMvb2BorbxpwDxx";
    private $token;
    private $api_key;
    public function __construct()
    {

        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $body = '{
                "email": "marvelapi@orbinoxprime.com",
                "password": "ggMvb2BorbxpwDxx"
            }';
        $request = new Psr7Request('POST', 'https://api.nowpayments.io/v1/auth', $headers, $body);
        $res = $client->sendAsync($request)->wait();
        $result =  json_decode($res->getBody());
        $this->token = $result->token;
        // $this->api_key = "A4EDJB0-SNH42FH-QQ0GY0N-WFRG2TQ";
        $this->api_key = "HJHVRZG-9H1MJGC-QKKYEP3-6CYF5G7";
    }
    public function __invoke(Request $request)
    {
        // $now_object = new NowPaymentsAPI2($this->api_key);
        // return json_decode($now_object->getEstimatePrice([
        //     'amount'=>(float)10.00,
        //     'currency_from'=>'USD',
        //     'currency_to'=>'USDTTRC20',
        // ]));
        return view('traders.deposit.now-pay-deposit');
    }
    // get estimate price
    public function estimate_price(Request $request)
    {
        $now_object = new NowPaymentsAPI2($this->api_key);
        $currency_from = $currency_to = '';
        if ($request->convart_from === 'crypto') {
            $currency_from = $request->currency;
            $currency_to = 'USD';
        } elseif ($request->convart_from === 'usd') {
            $currency_to = $request->currency;
            $currency_from = 'USD';
        }
        $result = json_decode($now_object->getEstimatePrice([
            'amount' => (float)$request->input('amount'),
            'currency_from' => $currency_from,
            'currency_to' => $currency_to,
        ]));
        return Response::json([
            'status' => true,
            'result' => $result,
        ]);
    }
    public function invoic_id()
    {
        $user_id = auth()->user()->id;
        $uuid = Uuid::uuid4();
        $invoice_id = $user_id . '-' . $uuid->toString();
        return $invoice_id;
    }
    // submit payment request
    public function request_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount_usd' => 'required|numeric',
            'pay_currency' => 'required|string',
            'amount_crypto' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Validation error, please fix the following errors',
                'errors' => $validator->errors(),
            ]);
        }
        try {
            $now_object = new NowPaymentsAPI2($this->api_key);
            $order_id = $this->invoic_id();
            $result = $now_object->createInvoice([
                'price_amount' => $request->input('amount_usd'),
                'price_currency' => $request->input('price_currency', 'USD'),
                'pay_currency' => $request->input('pay_currency'),
                'ipn_callback_url' => 'https://social.fxcrm.net/api/nowpay/payment/notification',
                'success_url' => route('user.deposit.nowpayments.success'),
                'order_id' => $order_id,
                'order_description' => 'CRM deposit',
            ]);
            $result = json_decode($result);
            $deposit = Deposit::create([
                'user_id'=>auth()->user()->id,
                'invoice_id'=>$result->id,
                'transaction_type'=>'nowpayments',
                'transaction_id'=>$result->token_id,
                'incode'=>$result->order_id,
                'amount'=>$request->input('amount_usd'),
                'order_id'=>$result->order_id,
                'status'=>'P',
                'wallet_type'=>'trader',
                'created_by'=>'system',
                'deposit_option'=>'wallet',
                'client_log'=>AdminLogService::admin_log('create deposit by nowpay'),
            ]);
            return Response::json([
                'status' => true,
                'message' => 'Please wait, while we redirect you',
                'redirect_to' => $result->invoice_url,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a internal server error, please try again',
                // 'redirect_to' => $result->invoice_url,
            ]);
        }
    }
    public function callback(Request $request)
    {
        return $request->all();
    }
    public function success(Request $request)
    {
        // return $request->all();
        try {
            return view('traders.deposit.now-pay-success');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function notification(Request $request)
    {
        try {
            // activity log------------------------
            $user = User::find(1);
            activity('NOWPay deposit')
                ->causedBy(1)
                ->withProperties($request->all())
                ->event('NOWPay deposit')
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been make a request NOWPay deposit");
            // <----------------------
        } catch (\Throwable $th) {
            //throw $th;
            // activity log------------------------
            $user = User::find(1);
            activity('NOWPay deposit')
                ->causedBy(1)
                ->withProperties($th->getMessage())
                ->event('NOWPay deposit')
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been make a request NOWPay deposit");
            // <----------------------
            // <----------------------
        }
    }
}
