<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\CurrencySetup;
use App\Models\SoftwareSetting;
use App\Services\AllFunctionService;
use App\Services\BankService;
// use App\Services\CurrencyUpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CurrencySetupController extends Controller
{
    public function __construct()
    {
        // $this->middleware(["role:support"]);
        // $this->middleware(["role:client ticket"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('currency_setup', 'admin'));
    }
    public function index(Request $request)
    {
        $software_settings = SoftwareSetting::first();
        return view('admins.settings.currency-setup', ['software_settings' => $software_settings]);
    }
    // get currency setup
    public function getCurrencySetup(Request $request)
    {
        $currency_setup = CurrencySetup::where('currency', $request->currency)->where('transaction_type', $request->transaction_type)->first();
        if ($currency_setup) {
            return Response::json([
                'status' => true,
                'currency_id' => $currency_setup->id,
                'transaction_type' => $currency_setup->transaction_type,
                'currency_rate' => $currency_setup->currency_rate,
            ]);
        } else {
            return Response::json([
                'status' => false,
                'currency_id' => "",
                'transaction_type' => "",
                'currency_rate' => "",
            ]);
        }
    }
    // set currency setup
    public function store(Request $request)
    {
        $validation_rules = [
            'currency' => 'required',
            'currency_rate' => 'required|numeric|min:0.01',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return ([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors'
            ]);
        }
        $currency_exists = CurrencySetup::where('currency', $request->currency)->where('transaction_type', $request->transaction_type)->first();
        if ($currency_exists) {
            $update = CurrencySetup::where('id', $currency_exists->id)->update(
                [
                    'currency' => $request->currency,
                    'currency_rate' => $request->currency_rate,
                    'transaction_type' => $request->transaction_type,
                    'created_by' => auth()->user()->id,
                    'ip' => request()->ip()
                ]
            );
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'Successfully Updated'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Failed To Update!'
                ]);
            }
        } else {
            $create = CurrencySetup::create([
                'currency' => $request->currency,
                'currency_rate' => $request->currency_rate,
                'transaction_type' => $request->transaction_type,
                'created_by' => auth()->user()->id,
                'ip' => request()->ip()
            ]);
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Successfully Inserted'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Failed To Insert!'
                ]);
            }
        }
    }
    // convert currency
    public function convert(Request $request)
    {
        if ($request->from_currency == "---") {
            return 0;
        }
        switch ($request->type) {
            case 'deposit':
                switch ($request->to_currency) {
                    case 'USD':
                        $local_currency = ($request->amount / BankService::get_currency_setup($request->type, $request->from_currency));
                        return round($local_currency, 2);
                        break;
                    default:
                        $local_currency = ($request->amount * BankService::get_currency_setup($request->type, $request->to_currency));
                        return round($local_currency, 2);
                        break;
                }
                break;
            case 'withdraw':
                switch ($request->to_currency) {
                    case 'USD':
                        $local_currency = ($request->amount / BankService::get_currency_setup($request->type, $request->from_currency));
                        return round($local_currency, 2);
                        break;

                    default:
                        $local_currency = ($request->amount * BankService::get_currency_setup($request->type, $request->to_currency));
                        return round($local_currency, 2);
                        break;
                }
                break;
            default:
                switch ($request->to_currency) {
                    case 'USD':
                        $local_currency = ($request->amount / BankService::get_currency_setup($request->type, $request->from_currency));
                        return round($local_currency, 2);
                        break;

                    default:
                        $local_currency = ($request->amount * BankService::get_currency_setup($request->type, $request->to_currency));
                        return round($local_currency, 2);
                        break;
                }
                break;
        }
    }

    public function multiCurrency(Request $request, $is_multicurrency)
    {
        $software_settings = SoftwareSetting::first();
        $is_multicurrency = ($is_multicurrency == 1) ? 1 : 0;
        SoftwareSetting::where('id', $software_settings->id)->update([
            'is_multicurrency' => $is_multicurrency
        ]);
        $message = ($is_multicurrency == 1) ? "Successfully Enabled" : "Successfully Disabled!";
        return Response::json(['success' => true, 'is_multicurrency' => $is_multicurrency, 'message' => $message]);
    }

    public function autoCurrencyRate(Request $request, $auto_c_rate)
    {
        $software_settings = SoftwareSetting::first();
        $auto_c_rate = ($auto_c_rate == 1) ? 1 : 0;
        SoftwareSetting::where('id', $software_settings->id)->update([
            'auto_c_rate' => $auto_c_rate
        ]);
        $message = ($auto_c_rate == 1) ? "Successfully Enabled" : "Successfully Disabled!";
        return Response::json(['success' => true, 'auto_c_rate' => $auto_c_rate, 'message' => $message]);
    }
}
