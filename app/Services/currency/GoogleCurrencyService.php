<?php

namespace App\Services\currency;

use App\Models\CurrencyRate;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class GoogleCurrencyService
{
    public static function currency_rate()
    {
        $client = new Client();
        $headers = [
            'apikey' => 'YfO69HYv16aO8X23yphQX06FrHSdOuCT' // api login fxcrm03@gmail.com
        ];

        // Set the SSL verification to false
        $options = [
            'headers' => $headers,
            'verify' => false,
        ];

        try {
            $response = $client->request('GET', 'https://api.apilayer.com/fixer/latest?symbols=IDR,BDT,INR,MYR,PHP,THB,VND,&BASE=USD', $options);

            $data = json_decode($response->getBody(), true);

            if ($data['success']) {
                $rates = $data['rates'];
                foreach ($rates as $key => $value) {
                    CurrencyRate::updateOrCreate(
                        ['currency' => $key],
                        ['currency' => $key, 'rate' => $value]
                    );
                }
            }

            return $data['rates'];
        } catch (RequestException $e) {
            return 'Request failed: ' . $e->getMessage();
        }
    }
    // get currency rate from db
    public static function get_rate($currency)
    {
        $currency_data = CurrencyRate::where('currency', $currency)->first();
        return ($currency_data->rate);
    }
}
