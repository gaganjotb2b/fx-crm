<?php

namespace App\Http\Controllers\traders\praxis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\Deposit;
use App\Models\UserDescription;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PraxisPaymentController extends Controller
{
    //Action to be taken within the cashier
    const INTENT_PAYMENT = 'payment';
    // Your Merchant Account ID
    const MERCHANT_ID = 'API-xflowmarkets';
    // Your Merchant Secret
    const MERCHANT_SECRET = 'XyVE7pWe1wDeMd45cRWubD5Ym73oHi17';
    // Your Application Key
    const APPLICATION_KEY = 'xflowmarkets.com';
    // Your API Version
    const API_VERSION = '1.3';
    // view indexing
    public  static function index(Request $request)
    {
        return view('traders/deposit/praxis-deposit');
    }
    public static function _GET_SAMPLE_DATA_TO_SEND_REQUEST_($cid, $locale): array
    {
        $request = [
            'cid'                 => $cid,
            'application_key'     => self::APPLICATION_KEY,
            'merchant_id'         => self::MERCHANT_ID,
            'intent'              => self::INTENT_PAYMENT,
            'order_id'            => self::getOrderID(),
            '_token' => csrf_token(),
            'your_variable_key_2' => 12345,
            'your_variable_key_3' => null,
            'your_variable_key_4' => true,
            'timestamp'           => self::getCurrentTimestamp(),
            'version'             => self::API_VERSION,
            'notification_url'    => "https://crm.xflowmarkets.com/api/praxis/payment/notification/" . auth()->user()->id,
            'return_url'          => "https://crm.xflowmarkets.com/user/deposit/praxis/return?status={{transaction.transaction_status}} & amount={{transaction.amount}} & currency={{transaction.currency}}",
            'locale'              => $locale,
        ];
        return $request;
    }
    //Cashier API 1.3
    private static function getRequestSignatureList()
    {
        return [
            'merchant_id',
            'application_key',
            'timestamp',
            'intent',
            'cid',
            'order_id'
        ];
    }
    private static function getConcatenatedString(array $data): string
    {
        $concatenated_string = "";
        foreach (PraxisPaymentController::getRequestSignatureList() as $key) {
            if (array_key_exists($key, $data) && !is_null($data[$key])) {
                $concatenated_string .= $data[$key];
            }
        }
        return $concatenated_string;
    }

    private static function getGtAuthenticationHeader(array $request): string
    {
        // Sort request array by keys ASC
        $concatenated_string = self::getConcatenatedString($request);

        // Concatenate Merchant Secret Key with response params
        $concatenated_string .= self::MERCHANT_SECRET;

        // Generate HASH of concatenated string
        $signature = self::generateSignature($concatenated_string);
        return $signature;
    }

    private static function generateSignature(string $input): string
    {
        $hashtext = hash('sha384', $input);

        return $hashtext;
    }

    private static function exportArrayToJSON(array $input): string
    {
        $json_string = json_encode($input);

        return $json_string;
    }

    private static function getOrderID(): string
    {
        // return 'order_' . rand(100, 100000);
        $order_id = microtime(true) . mt_rand();
        $order_id = str_replace('.','',$order_id);
        return auth()->user()->id . $order_id;
    }

    private static function getCurrentTimestamp(): int
    {
        // return time();
        // Get the current local date and time
        // $localDateTime = Carbon::now();

        // Convert to UTC
        // $utcDateTime = $localDateTime->utc();

        // Get the Unix timestamp from the UTC time
        // $unixTimestamp = $utcDateTime->getTimestamp();
        $localDateTime = time(); // Replace this with your local date and time
        $localDateTime = Carbon::parse($localDateTime);

        $gmtDateTime = $localDateTime->utc();
        // $formattedGMTDateTime = $gmtDateTime->format('Y-m-d H:i:s');
        // $unixTimestamp=strtotime($formattedGMTDateTime);
        return $gmtDateTime->getTimestamp();
    }


    // submit request to api
    public function submit_request(Request $request)
    {
        $validation_rule = [
            'locale' => 'required',
            'currency' => 'required',
            'amount_local' => 'required',
            'amount' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rule);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Please fix the following errors',
                'error' => $validator->errors(),
            ]);
        }
        $cid = (string)auth()->user()->id;
        $currency = $request->currency;
        $request_to_send             = self::_GET_SAMPLE_DATA_TO_SEND_REQUEST_($cid, $request->locale);
        $gt_authentication_header    = self::getGtAuthenticationHeader($request_to_send);
        $request_to_send_json_string = self::exportArrayToJSON($request_to_send);

        $json_data = (array) json_decode($request_to_send_json_string);
        $json_data['signature'] = $gt_authentication_header;
        $json_data['currency'] = $currency;
        $json_data['amount'] = (int)$request->amount_local * 100;
        // user data
        $user_data = UserDescription::select(
            'user_descriptions.country_id',
            'countries.iso'
        )->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->where('user_id', auth()->user()->id)->first();
        $json_data["customer_data"] = (object)[
            "country" => ($user_data && isset($user_data->iso)) ? strtoupper($user_data->iso) : "GB",
            "first_name" => (string)substr(auth()->user()->name, 0, 25),
            "last_name" => "N/A",
            "dob" => null,
            "email" => auth()->user()->email,
            "phone" => (isset(auth()->user()->phone)) ? auth()->user()->phone : null,
            "zip" => null,
            "state" => null,
            "city" => null,
            "address" => null
        ];

        $client = new Client();

        $headers = [
            'Content-Type' => 'application/json',
            "gt-authentication" => $gt_authentication_header,
            "user-agent" => "Praxis GT API Agent v1.3",
        ];
        $body = json_encode($json_data);
        $request_guzz = new Psr7Request('POST', 'https://gw.praxisgate.com/cashier/cashier', $headers, $body);
        $res = $client->sendAsync($request_guzz)->wait();
        $response = json_decode($res->getBody());

        if ($response->status == 0 && strtolower($response->description) === 'success') {
            $create = Deposit::create([
                'user_id' => auth()->user()->id,
                'invoice_id' => $response->session->auth_token,
                'amount' => round(($response->session->amount / 100), 2),
                'transaction_type' => 'praxis',
                'transaction_id' => $response->customer->customer_token,
                'order_id' => $response->session->order_id,
                'ip_address' => $request->ip(),
                'approved_status' => 'P',
                'currency' => $response->session->currency,
                'local_currency' => $response->session->amount,
                'wallet_type' => 'trader',

            ]);
            // insert activity-----------------
            $user = User::find(auth()->user()->id);
            // $requested_data = json_decode($request->all());
            activity('praxis deposit request from ' . $request->ip())
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event('praxis deposit')
                ->performedOn($user)
                ->log('Praxis deposit request');
            // end activity log-----------------
            return Response::json([
                'status' => true,
                'message' => 'Please wait while we redirect to payment page',
                'redirect_url' => $response->redirect_url,

            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Session expired, try again !'

        ]);
        // return $response;
    }

    // make signature
    public function make_signature($customer_data = [])
    {
        $signatur = '{
            "merchant_id":"Test-Integration-Merchant",
            "application_key":"Sandbox","intent":"payment",
            "currency":"USD","amount":100,"cid":"1",
            "locale":"en-GB",
            "gateway":null,
            "notification_url":"https://crm.xflowmarkets.com/api/praxis/payment/notification/".auth()->user()->id,
            "return_url":"https://crm.xflowmarkets.com/user/deposit/praxis/return",
            "order_id":112233,
            "version":"1.2",
            "timestamp":1689848910
        }';
        $signatur = hash('sha384', $signatur);
        return $signatur;
    }

    // notification url action
    public  static function notification(Request $request)
    {
        try {
            // return $request->all();
            // insert activity-----------------
            $user = User::find($request->id);
            activity('praxis deposit')
                ->causedBy($request->id)
                ->withProperties($request->all())
                ->event('praxis deposit')
                ->performedOn($user)
                ->log('The IP address ' . request()->ip() . ' has been make praxis deposit');
            // end activity log-----------------

            // return $request->transaction['transaction_status'];
            $approved_status = 'P';
            if (strtolower($request->transaction['transaction_status']) === 'approved') {
                $response_request = '{
                    "status": 0,
                    "description": "successfully approved and recieved by crm",
                    "version": "1.3",
                    "timestamp": 1590611635
                }';
                $approved_status = 'A';
            } elseif (strtolower($request->transaction['transaction_status']) === 'initialized') {
                $approved_status = 'P';
            } else {
                $approved_status = 'D';
            }
            $cur_date = time();
            $create = Deposit::updateOrCreate(
                [
                    'user_id' => $request->session['cid'],
                    'order_id' => $request->session['order_id'],
                    'currency' => $request->transaction['currency'],
                    // 'local_currency'=>$request->session['amount'],
                ],
                [
                    'note' => $request->transaction['status_details'],
                    'approved_status' => $approved_status,
                    'approved_date' => date('Y-m-d h:i:s', strtotime($cur_date)),
                    'amount' => round($request->transaction['amount'] / 100, 2),
                    'local_currency' => $request->transaction['amount'],
                    'transaction_id' => $request->transaction['transaction_id'],
                    'incode' => $request->transaction['tid'],
                    'approved_date' => date('Y-m-d h:i:s', strtotime('now')),
                ]
            );

            // submit api response
            if (strtolower($request->transaction['transaction_status']) === 'approved') {
                return Response::json([
                    "status" => 0,
                    "description" => "successfully approved and recieved by crm",
                    "version" => "1.3",
                    "timestamp" => self::getCurrentTimestamp()
                ]);
            } elseif (strtolower($request->transaction['transaction_status']) === 'initialized') {
                // $approved_status = 'P';
            } else {
                return Response::json([
                    "status" => 1,
                    "description" => "successfully canceled and recieved by crm",
                    "version" => "1.3",
                    "timestamp" => self::getCurrentTimestamp()
                ]);
            }

            // return $user;
        } catch (\Throwable $th) {
            $user = User::find($request->id);
            $fail_log = json_encode([
                'message' => $th->getMessage(),
                'request' => $request->all()
            ]);
            activity('praxis deposit')
                ->causedBy($request->id)
                ->withProperties($fail_log)
                ->event('praxis deposit failed')
                ->performedOn($user)
                ->log('The IP address ' . request()->ip() . ' has been failed praxis deposit');
            throw $th;
        }
    }
    // praxis payment return url action
    public  static function return_api(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            activity('praxis deposit success')
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event('praxis deposit')
                ->performedOn($user)
                ->log('The IP address ' . request()->ip() . ' has been make praxis deposit success');
            return view('traders.deposit.praxis-success');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
