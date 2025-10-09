<?php

namespace App\Services\deposit;

use App\Models\PaymentGatewayConfig;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class B2bDepositService
{
    private static $access_token = "";
    public static function b2b_post($data)
    {
        // $tracking_id = auth()->user()->id.'-'.mt_rand(100000, 999999) . '-' . mt_rand(111111, 999999);
        $microtime = str_replace([' ', '.'], '', microtime()); // Get the current microtime and remove spaces and dots
        $tracking_id = auth()->user()->id . '-' . $microtime . '-' . mt_rand(111111, 999999);

        $client = new Client();
        try {
            $auth = self::api_authentication('access');
            if ($auth['status']) {
                $configuration = PaymentGatewayConfig::where('gateway_name', 'b2binpay')->first();
                $wallet_id = $configuration->merchent_code;
                $res = $client->post('https://api.b2binpay.com/deposit/', [
                    // $res = $client->post('https://api-sandbox.b2binpay.com/deposit/', [
                    'json' => [
                        'data' => [
                            'type' => 'deposit',
                            'attributes' => [
                                "target_paid" => $data['amount'],
                                'label' => 'deposit from ' . request()->ip(),
                                'tracking_id' => "$tracking_id",
                                'confirmations_needed' => 3,
                                'callback_url' => url("/api/b2binpay/payment/notification"),
                            ],
                            'relationships' => [
                                'wallet' => [
                                    'data' => [
                                        'type' => 'wallet',
                                        'id' => "$wallet_id",
                                        // 'id' => ($data['currency'] === '1000') ? 1552 : 1374,
                                    ],
                                ],
                                'currency' => [
                                    'data' => [
                                        'type' => 'currency',
                                        'id' => $data['currency'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'headers' => [
                        'Authorization' => 'Bearer ' . $auth['access_token'],
                        'Content-Type' => 'application/vnd.api+json',
                    ],
                ]);
                return json_decode($res->getBody());
            }
            return ([
                'status' => false,
                'message' => 'Connection failed, Please try again later!'
            ]);
        } catch (RequestException $e) {
            return ([
                'status' => false,
                'message' => 'Message: ' . $e->getMessage()
            ]);
        }
    }
    // api authentication
    public static function api_authentication($data)
    {
        $client = new Client();
        try {
            // get configuration
            $configuration = PaymentGatewayConfig::where('gateway_name', 'b2binpay')->first();
            $login = $configuration->user_name;
            $password = $configuration->password;
            $res = $client->post('https://api.b2binpay.com/token/', [
                // $res = $client->post('https://api-sandbox.b2binpay.com/token/', [
                'json' => [
                    'data' => [
                        'type' => 'auth-token',
                        'attributes' => [
                            // "login" => "X2sKdyb0t4RnNyuh", //real 
                            "login" => "$login", //real 
                            // "login" => "reza7nov@gmail.com", //sandbox
                            // "password" => "TDFJTXhvVixOxe" //real
                            "password" => "$password" //real
                            // "password" => "reza@1995" //sandbox
                        ],
                    ],
                ],
                'headers' => [
                    'Content-Type' => 'application/vnd.api+json',
                ],
            ]);
            switch ($data) {
                case 'access':
                    $response = json_decode($res->getBody());
                    $response = $response->data->attributes->access;
                    self::$access_token = $response;

                    return ([
                        'status' => true,
                        'access_token' => $response
                    ]);
                    break;

                default:
                    $response = json_decode($res->getBody());
                    break;
            }
            return $response;
        } catch (RequestException $e) {
            return ([
                'status' => false,
                'message' => 'Message: ' . $e->getMessage()
            ]);
        }
    }
    // get wallet
    public static function get_wallet()
    {
        $access_token = self::api_authentication('access');
        $client = new Client();
        try {
            // $res = $client->get('https://api.b2binpay.com/wallet/', [
            $res = $client->get('https://api.b2binpay.com/wallet/?page=2', [
                // $res = $client->get('https://api-sandbox.b2binpay.com/wallet/', [
                'headers' => [
                    'Authorization' => 'Bearer ' . self::$access_token,
                    'Content-Type' => 'application/vnd.api+json',
                ],
            ]);
            // return $res->getBody();
            return ([
                'status' => true,
                'wallet' => json_decode($res->getBody())
            ]);
        } catch (RequestException $e) {
            return ([
                'status' => false,
                'message' => 'Message: ' . $e->getMessage()
            ]);
        }
    }
    // get deposit
    public static function get_deposit()
    {
        $access_token = self::api_authentication('access');
        $client = new Client();
        try {
            $res = $client->get('https://api.b2binpay.com/deposit/', [
                // $res = $client->get('https://api-sandbox.b2binpay.com/deposit/', [
                'headers' => [
                    'Authorization' => 'Bearer ' . self::$access_token,
                    'Content-Type' => 'application/vnd.api+json',
                ],
            ]);
            return ($res->getBody());
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }
    public function callback()
    {
        //  return ();
        // this function not needed remove after check
    }
}
