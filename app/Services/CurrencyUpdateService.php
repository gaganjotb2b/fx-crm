<?php

namespace App\Services;

use App\Models\CurrencySetup;
use Exception;

class CurrencyUpdateService
{
    public function updateCurrency()
    {
        $currency_setups = CurrencySetup::all();
        foreach ($currency_setups as $currency_setup) {
            $req_url = 'https://api.exchangerate.host/convert?from=USD&to=' . $currency_setup->currency;
            
            $response_json = file_get_contents($req_url);
            return $response_json;
            if (false !== $response_json) {
                try {
                    $response = json_decode($response_json);
                    if ($response->success === true) {
                        CurrencySetup::where('currency', $currency_setup->currency)->update([
                            'currency_rate' => round($response->info->rate, 2)
                        ]);
                    }
                } catch (Exception $e) {
                    // Handle JSON parse error...
                }
            }
        }
    }
}
