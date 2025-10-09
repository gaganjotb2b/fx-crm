<?php

namespace App\Http\Controllers\traders\deposit;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\UserDescription;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Ramsey\Uuid\Uuid;

class KosmosDepositController extends Controller
{
    private $api_key;
    private $merchant_key;
    private $merchant_passphrase;
    public function __construct()
    {
        $this->api_key = "63E6D83DBB29464D939979977869C514";
        $this->merchant_key = "659d07d012e8480b279c27a2";
        $this->merchant_passphrase = "720FD9F8B29A45BAA70CD6858344805D";
    }
    public function index(Request $request)
    {
        try {
            $file_path = storage_path('app/json/currency-code.json');
            $json_data = json_decode(File::get($file_path), true);
            $currencies = collect($json_data)->pluck('currency_code');
            $countries = Country::get();
            // return ($invoice_id);
            return view('traders.deposit.kosmos-deposit', [
                'currencies' => $currencies,
                'countries' => $countries,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // make authorization hash
    public function auth_hash()
    {
        return base64_encode($this->merchant_key . ':' . $this->merchant_passphrase);
    }
    // make signature
    public function signature($data = [])
    {
        $json_string = json_encode($data);
        $combined_string = $json_string . $this->api_key;
        $sha256Hash = hash('sha256', $combined_string);
        return $sha256Hash;
    }

    // make invoice id
    public function invoic_id()
    {
        $user_id = auth()->user()->id;
        // $microtime = round(microtime(true) * 1000);
        // $randomNumber = mt_rand(10000, 99999);

        $uuid = Uuid::uuid4();
        $invoice_id = $user_id . '-' . $uuid->toString();
        return $invoice_id;
    }
    // make transaction request
    public function deposit_request(Request $request)
    {
        try {
            $invoice_id = $this->invoic_id();
            $amount = $request->input('local_currency');
            $usd_amount = $request->input('amount');
            $country_code = Country::where('name', $request->input('country'))->first()->iso;
            $user_description = UserDescription::where('user_id', auth()->user()->id)->first();
            $data = [
                'invoice_id' => $invoice_id,
                // 'amount' => (float)$amount,
                "amount" => (float)100.00,
                // 'country' => $country_code,
                'country' => 'IN',
                // 'currency' => $request->input('currency'),
                'currency' => "INR",
                'payer' => [
                    'id' => $invoice_id,
                    'first_name' => auth()->user()->name,
                    'last_name' => 'na',
                    'phone' => auth()->user()->phone,
                    'email' => auth()->user()->email,
                    'address' => [
                        "street" => 'na',
                        'city' => isset($user_description->city) ? $user_description->city : 'na',
                        'state' => isset($user_description->state) ? $user_description->state : 'na',
                        'zip_code' => isset($user_description->zip_code) ? $user_description->zip_code : 'na'
                    ]
                ],
                'payment_method' => "UPI",
                'description' => 'Make a deposit from ip-' . request()->ip(),
                'client_ip' => request()->ip(),
                'url' => [
                    'back_url' => route('user.deposit.kosmos.back'), //deposit cancel
                    'success_url' => route('user.deposit.kosmos.success'), // deposit completed
                    'pending_url' => route('user.deposit.kosmos.pending'), //deposit pending,
                    'callback_url' => route('user.deposit.kosmos.callback') //callback,
                ],
                'logo' => get_user_logo(),
                'test' => 1,
                'language' => 'en'
            ];
            // return $data;
            // signature
            $signature = $this->signature($data);
            $auth_hash =  $this->auth_hash();
            $client = new Client([
                'verify' => false,  // Disable SSL verification for testing purposes; remove in production
                'timeout' => 60,    // Set the timeout to 60 seconds (adjust as needed)
            ]);
            $headers = [
                'Authorization' => "Basic $auth_hash",
                'Signature' => "$signature",
                'Content-Type' => 'application/json',
            ];
            // return $headers;
            $body = json_encode($data);
            $request_client = new Psr7Request('POST', 'https://api.kosmossolution.com/transaction/deposit', $headers, $body);
            
            $res = $client->sendAsync($request_client)->wait();
            $result = $res->getBody();
            return $result;
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for supports'
            ]);
        }
    }
}
