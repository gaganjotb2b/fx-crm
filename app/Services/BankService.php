<?php

namespace App\Services;

use App\Models\CurrencySetup;
use App\Models\SoftwareSetting;

class BankService
{
    public static function  __callStatic($name, $data)
    {
        if ($name === 'swift_code_label') {
            return (new self)->get_swift_code_label($data[0]);
        }
    }
    private function get_swift_code_label($country)
    {
        if (strtolower($country) === 'india') {
            return __('page.ifsc-code');
        } else {
            return __('page.swift-code');
        }
    }
    // get currency setup
    public static function get_currency_setup($type = null, $currency = null)
    {
        $currency_setup = CurrencySetup::where('transaction_type', $type)->where('currency', $currency)->first();
        if ($currency_setup) {
            return ($currency_setup->currency_rate);
        } else {
            return (false);
        }
    }
    public static function is_multiCurrency($type = null)
    {
        switch ($type) {
            case 'deposit':
                $currency_setup = CurrencySetup::select()->where('transaction_type', 'deposit')->exists();
                if ($currency_setup) {
                    return (true);
                }
                return (false);
                break;
            case 'all':
                $software_settings = SoftwareSetting::select('is_multicurrency')->first();
                if ($software_settings) {
                    if ($software_settings->is_multicurrency == 1) {
                        return true;
                    }
                }
                return (false);
                break;
            default:
                $currency_setup = CurrencySetup::select()->where('transaction_type', 'withdraw')->exists();
                if ($currency_setup) {
                    return (true);
                }
                return (false);
                break;
        }
    }
}
