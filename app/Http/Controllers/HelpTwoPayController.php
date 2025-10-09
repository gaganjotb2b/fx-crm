<?php

namespace App\Http\Controllers;

use App\Models\admin\InternalTransfer;
use App\Models\BankAccount;
use App\Models\Deposit;
use App\Models\PaymentGatewayConfig;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\currency\GoogleCurrencyService;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\systems\AdminLogService;
use Carbon\Carbon;
use Database\Seeders\PaymentGateway;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HelpTwoPayController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('help2pay_deposit', 'trader'));
        $this->middleware(AllFunctionService::access('deposit', 'trader'));
    }
    //
    public function index(Request $request)
    {

        $trading_accounts = TradingAccount::where('user_id', auth()->user()->id)->get();
        $help2pay = PaymentGatewayConfig::where('gateway_name', 'help2pay')->first();
        return view('traders.deposit.help2pay', [
            'trading_accounts' => $trading_accounts,
            'help2pay' => $help2pay,
        ]);
    }
    public function set_form_value(Request $request)
    {
        try {
            // check amount validation
            $validation_rules = [
                'amount' => 'required|numeric',
                'local_currency' => 'required|numeric',
                'trading_account' => (strtolower($request->deposit_option) === 'account') ? 'required' : 'nullable',
            ];
            $validation = Validator::make($request->all(), $validation_rules);
            if ($validation->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => "Please fix the following errors",
                    'errors' => $validation->errors()
                ]);
            }
            // check floating point value
            $amount_local = $request->local_currency;
            $amount_local = explode('.', $amount_local);
            if (is_array($amount_local)) {
                if (array_key_exists(1, $amount_local)) {
                    if ($amount_local[1] > 0) {
                        return Response::json([
                            'status' => false,
                            'message' => 'Please fix the following errors',
                            'errors' => ['local_currency' => 'Floating point value not accepted'],
                        ]);
                    }
                }
            }
            // currency convert
            switch ($request->currency) {
                case 'IDR':
                    // INDONESIA RUPIA
                    $rate = GoogleCurrencyService::get_rate('IDR');
                    $idr_amount = round((($request->amount) * $rate));
                    // check min deposit
                    $min_check = $this->min_check($request->local_currency, 50000, $rate, $request->currency);
                    if ($min_check['status'] == false) {
                        return Response::json($min_check);
                    }
                    // check max deposit
                    $max_check = $this->max_check($request->local_currency, 200000000, $rate, $request->currency);
                    if ($max_check['status'] == false) {
                        return Response::json($max_check);
                    }
                    break;
                case 'INR':
                    // INDIAN RUPIA
                    $rate = GoogleCurrencyService::get_rate('INR');
                    $idr_amount = round((($request->amount) * $rate));
                    // check minimum deposit
                    $min_check = $this->min_check($request->local_currency, 1000, $rate, $request->currency);
                    if ($min_check['status'] == false) {
                        return Response::json($min_check);
                    }
                    // check max deposit
                    $max_check = $this->max_check($request->local_currency, 500000, $rate, $request->currency);
                    if ($max_check['status'] == false) {
                        return Response::json($max_check);
                    }
                    break;
                case 'MYR':
                    // Malaysian Ringgits
                    $rate = GoogleCurrencyService::get_rate('MYR');
                    $idr_amount = round((($request->amount) * $rate));
                    // check minimum deposit
                    $min_check = $this->min_check($request->local_currency, 10, $rate, $request->currency);
                    if ($min_check['status'] == false) {
                        return Response::json($min_check);
                    }
                    // check max deposit
                    $max_check = $this->max_check($request->local_currency, 50000, $rate, $request->currency);
                    if ($max_check['status'] == false) {
                        return Response::json($max_check);
                    }
                    break;
                case 'PHP':
                    //Philippine Pesos
                    $rate = GoogleCurrencyService::get_rate('PHP');
                    $idr_amount = round((($request->amount) * $rate));
                    // check minimum deposit
                    $min_check = $this->min_check($request->local_currency, 100, $rate, $request->currency);
                    if ($min_check['status'] == false) {
                        return Response::json($min_check);
                    }
                    // check max deposit
                    $max_check = $this->max_check($request->local_currency, 1000000, $rate, $request->currency);
                    if ($max_check['status'] == false) {
                        return Response::json($max_check);
                    }
                    break;
                case 'THB':
                    //Thai Baht
                    $rate = GoogleCurrencyService::get_rate('THB');
                    $idr_amount = round((($request->amount) * $rate));
                    // check minimum deposit
                    $min_check = $this->min_check($request->local_currency, 100, $rate, $request->currency);
                    if ($min_check['status'] == false) {
                        return Response::json($min_check);
                    }
                    // check max deposit
                    $max_check = $this->max_check($request->local_currency, 500000, $rate, $request->currency);
                    if ($max_check['status'] == false) {
                        return Response::json($max_check);
                    }
                    break;
                case 'VND':
                    //Vietnamese Dongs
                    $rate = GoogleCurrencyService::get_rate('VND');
                    $idr_amount = round((($request->amount) * $rate));
                    // check minimum deposit
                    $min_check = $this->min_check($request->local_currency, 50000, $rate, $request->currency);
                    if ($min_check['status'] == false) {
                        return Response::json($min_check);
                    }
                    // check max deposit
                    $max_check = $this->max_check($request->local_currency, 300000000, $rate, $request->currency);
                    if ($max_check['status'] == false) {
                        return Response::json($max_check);
                    }
                    break;
                default:
                    # code...
                    break;
            }

            // Get configuration
            $configuration = PaymentGatewayConfig::where('gateway_name', 'help2pay')->first();
            
            if (!$configuration || empty($configuration->api_secret)) {
                return Response::json([
                    'status' => false,
                    'message' => 'Currently not configured help2pay, please try another way to deposit',
                ]);
            }
            
            // Now you can safely use the $configuration object
            $secret_key = $configuration->api_secret;
            $merchant_code = $configuration->merchent_code;

            // make transaction reference
            $id = mt_rand(10000, 99999);
            $user_id = $id; //user id       
            $id_lenth = strlen($id);
            $stamp = mt_rand(2, 100);
            $random_id_length = 6 - $id_lenth;
            $paymentreferenceno = hexdec(uniqid(rand(), 1));
            $paymentreferenceno = strip_tags(stripslashes($paymentreferenceno));
            $paymentreferenceno = str_replace(".", "", $paymentreferenceno);
            $paymentreferenceno = str_replace("E", "$stamp", $paymentreferenceno);
            $paymentreferenceno = str_replace("+", "9", $paymentreferenceno);
            $paymentreferenceno = strrev(str_replace("/", "", $paymentreferenceno));
            $paymentreferenceno = substr($paymentreferenceno, 0, $random_id_length);
            $paymentreference_no = $paymentreferenceno . $id; //payment reference no
            // actual IDR amount
            $actual_amount = strval(number_format($request->local_currency, 2, '.', ''));

            $values = "$merchant_code" . $paymentreference_no . auth()->user()->email . $actual_amount . $request->currency . Carbon::now('Asia/Brunei')->format('YmdHis') . "$secret_key" . request()->ip();
            $key = md5("$values");
            $data = [
                'status' => true,
                "reference" => $paymentreference_no,
                "datetime" => Carbon::now('Asia/Brunei')->format('Y-m-d h:i:sA'),
                'key' => $key,
                'amount' => $actual_amount,
                'message' => 'Please wait, while we redirecting you'
            ];
            // if check deposit is direct account deposit
            $create_internal = false;
            if (strtolower($request->deposit_option) === 'account') {
                $trading_account = TradingAccount::where('id', decrypt($request->trading_account))->first();
                $create_internal = InternalTransfer::create([
                    'user_id' => auth()->user()->id,
                    'platform' => $trading_account->platform,
                    'account_id' => $trading_account->id,
                    'invoice_code' => $key,
                    'amount' => $request->amount,
                    'charge' => 0,
                    'type' => 'wta',
                    'status' => 'P',
                    'client_log' => AdminLogService::admin_log(),

                ]);
            }
            // create deposit
            $create = Deposit::create([
                'user_id' => auth()->user()->id,
                'invoice_id' => $request->Key,
                'transaction_type' => 'help2pay',
                'amount' => $request->amount,
                'ip_address' => $request->ip(),
                'approved_status' => 'P',
                'wallet_type' => 'trader',
                'created_by' => 'system',
                'deposit_option' => ($request->deposit_option)?$request->deposit_option:'wallet',
                'account' => (strtolower($request->deposit_option) === 'account') ? $request->trading_account : null,
                'internal_transfer' => ($create_internal) ? $create_internal->id : null,
            ]);
            // activity log------------------------
            $user = User::find(auth()->user()->id);
            activity('help2pay deposit request')
                ->causedBy(auth()->user()->id)
                ->withProperties($create)
                ->event('help2pay deposit')
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been make a request help2pay deposit");
            // <----------------------
            Session::put('bank_account', $request->bank_account);
            return Response::json($data);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    public function min_check($idr_amount, $min, $rate, $currency)
    {
        if ($idr_amount < $min) {
            $usd_amount = round(($min / $rate), 3);
            return ([
                'status' => false,
                'errors' => [
                    'amount' => 'Minimum deposit with Help2Pay &dollar; ' . $usd_amount,
                    'local_currency' => 'Minimum deposit with Help2Pay ' . $min . " ($currency)",
                ],
                'message' => "Please fix the following errors!",
                'usd_amount' => $usd_amount,
            ]);
        }
        return ([
            'status' => true,
            'message' => ''
        ]);
    }
    public function max_check($idr_amount, $max, $rate, $currency)
    {
        if ($idr_amount > $max) {
            $usd_amount = round(($max / $rate), 3);
            return ([
                'status' => false,
                'errors' => [
                    'amount' => 'Maximum deposit with Help2Pay &dollar; ' . $usd_amount,
                    'local_currency' => 'Maximum deposit with Help2Pay ' . $max . " ($currency)",
                ],
                'message' => "Please fix the following errors!",
            ]);
        }
        return ([
            'status' => true,
            'message' => ''
        ]);
    }
    // convert with exchange rate
    public function convert(Request $request, $rquest_amount = null, $rquest_currency)
    {
        // request currency
        if (isset($request->currency)) {
            $currency = $request->currency;
        } else {
            $currency = $rquest_currency;
        }
        // request amount
        if (isset($request->amount)) {
            $amount = $request->amount;
        } else {
            $amount = $rquest_amount;
        }
        switch ($currency) {
            case 'IDR':
                // INDONESIA RUPIA
                $rate = GoogleCurrencyService::get_rate('IDR');
                $usd_amount = round(($amount / $rate), 3);
                return $usd_amount;
                break;
            case 'INR':
                // INDIAN RUPIA
                $rate = GoogleCurrencyService::get_rate('INR');
                $usd_amount = round(($amount / $rate), 3);
                return $usd_amount;
                break;
            case 'MYR':
                // Malaysian Ringgits
                $rate = GoogleCurrencyService::get_rate('MYR');
                $usd_amount = round(($amount / $rate), 3);
                return $usd_amount;
                break;
            case 'PHP':
                //Philippine Pesos
                $rate = GoogleCurrencyService::get_rate('PHP');
                $usd_amount = round(($amount / $rate), 3);
                return $usd_amount;
                break;
            case 'THB':
                //Thai Baht
                $rate = GoogleCurrencyService::get_rate('THB');
                $usd_amount = round(($amount / $rate), 3);
                return $usd_amount;
                break;
            case 'VND':
                //Vietnamese Dongs
                $rate = GoogleCurrencyService::get_rate('VND');
                $usd_amount = round(($amount / $rate), 3);
                return $usd_amount;
                break;
            default:

                break;
        }
    }

    public function round_off($amount)
    {
        $value = (float) $amount;

        $integerPart = floor($value);
        $decimalPart = $value - $integerPart;

        if ($decimalPart >= 0.5) {
            $roundedValue = ceil($value);
        } else {
            $roundedValue = floor($value);
        }
        return $roundedValue;
    }
    // conver reverse
    public function convert_reverse(Request $request, $rquest_amount = null, $rquest_currency)
    {
        // request currency
        if (isset($request->currency)) {
            $currency = $request->currency;
        } else {
            $currency = $rquest_currency;
        }
        // request amount
        if (isset($request->amount)) {
            $amount = $request->amount;
        } else {
            $amount = $rquest_amount;
        }
        if ($amount == 0 || $amount == "") {
            return (0);
        } else {
            switch ($currency) {
                case 'IDR':
                    // INDONESIA RUPIA
                    $rate = GoogleCurrencyService::get_rate('IDR');
                    $usd_amount = round(($amount * $rate), 3);
                    return $this->round_off($usd_amount);
                    break;
                case 'INR':
                    // INDIAN RUPIA
                    $rate = GoogleCurrencyService::get_rate('INR');
                    $usd_amount = round(($amount * $rate), 3);
                    return $this->round_off($usd_amount);
                    break;
                case 'MYR':
                    // Malaysian Ringgits
                    $rate = GoogleCurrencyService::get_rate('MYR');
                    $usd_amount = round(($amount * $rate), 3);
                    return $this->round_off($usd_amount);
                    break;
                case 'PHP':
                    //Philippine Pesos
                    $rate = GoogleCurrencyService::get_rate('PHP');
                    $usd_amount = round(($amount * $rate), 3);
                    return $this->round_off($usd_amount);
                    break;
                case 'THB':
                    //Thai Baht
                    $rate = GoogleCurrencyService::get_rate('THB');
                    $usd_amount = round(($amount * $rate), 3);
                    return $this->round_off($usd_amount);
                    break;
                case 'VND':
                    //Vietnamese Dongs
                    $rate = GoogleCurrencyService::get_rate('VND');
                    $usd_amount = round(($amount * $rate), 3);
                    return $this->round_off($usd_amount);
                    break;
                default:

                    break;
            }
        }
    }

    public function success(Request $request)
    {
        // return redirect()->route('user.deposit.help2pay');
        // return($request->all());
        $idr_amount = $request->Amount;

        $usd_amount = $this->convert($request, $request->Amount, $request->Currency);
        // store activity log---------------------
        $ip_address = request()->ip();
        $description = "The IP address $ip_address has been make a help2pay deposit";
        $user = User::find(auth()->user()->id);
        activity('help2pay deposit')
            ->causedBy(auth()->user()->id)
            ->withProperties($request->all())
            ->event('help2pay deposit')
            ->performedOn($user)
            ->log($description);
        // <----------------------
        if ($request->Status == "000") {
            $client_key = md5("T0306" . $request->Reference . $request->Customer . $request->Amount . $request->Currency . "000" . "WQEQU8LhzsDR1bKB72rK");

            if (strtolower($client_key) === strtolower($request->Key)) {
                // check created deposit is direct account deposit
                $deposit = Deposit::where('invoice_id', $request->Key)->first();
                $account_deposit = false;
                if ($deposit && (isset($deposit->deposit_option) && strtolower($deposit->deposit_option) === 'account')) {
                    // deposit to trading account
                    $trading_account = TradingAccount::where('trading_accounts.id', $deposit->account)->first();
                    if ($trading_account) {
                        $result['success'] = false;
                        // mt4 account deposit
                        if (strtolower($trading_account->platform) === 'mt4') {
                            $mt4_api = new MT4API();
                            $result = $mt4_api->execute([
                                'command' => 'deposit_funds',
                                'data' => [
                                    'account_id' => $trading_account->account_number,
                                    'amount' => (float)$deposit->amount,
                                    'comment' => "Wallet Deposit from direct deposit approved #" . request()->ip()
                                ]
                            ], 'live');
                        }
                        // mt5 account deposit
                        if (strtolower($trading_account->platform) === 'mt5') {
                            $mt5_api = new Mt5WebApi();
                            $result = $mt5_api->execute('BalanceUpdate', [
                                "Login" => (int)$trading_account->account_number,
                                "Balance" => (float)$deposit->amount,
                                "Comment" => "Wallet Deposit from direct deposit approved #" . request()->ip(),
                            ]);
                        }
                        if (isset($result['success']) && $result['success']) {
                            $account_deposit = true;
                            // update internal transfer
                            InternalTransfer::where('id', $deposit->internal_transfer)->update([
                                'status' => 'A',
                                'admin_log' => AdminLogService::admin_log(),
                                'approved_by' => auth()->user()->id,
                                'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                            ]);
                        }
                    }
                }
                $update_data = [
                    'user_id' => auth()->user()->id,
                    'invoice_id' => $request->Key,
                    'transaction_type' => 'help2pay',
                    'transaction_id' => $client_key,
                    'amount' => $usd_amount,
                    'order_id' => $request->ID,
                    'ip_address' => $request->ClientIP,
                    'merchant' => $request->Merchant,
                    'approved_status' => 'A',
                    'local_currency' => $idr_amount,
                    'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                    'currency' => $request->Currency,
                    'approved_by' => auth()->user()->id,
                    'admin_log' => AdminLogService::admin_log()
                ];
                if (!$account_deposit) {
                    $update_data['deposti_options'] = 'walelt';
                }
                $update = Deposit::updateOrCreate(
                    [
                        'user_id' => auth()->user()->id,
                        'invoice_id' => $request->Key,
                        'transaction_type' => 'help2pay',
                    ],
                    $update_data,
                );
                if ($update) {
                    // sending mail to client
                    EmailService::send_email('help2pay-deposit', [
                        'user_id' => auth()->user()->id,
                        'clientWithdrawAmount' => $usd_amount
                    ]);
                    // MailNotificationService::notification('deposit', 'trader', 1, $user->name, $usd_amount);
                    MailNotificationService::admin_notification([
                        'amount' => $usd_amount,
                        'name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                        'type' => 'deposit',
                        'client_type' => 'trader'
                    ]);
                    return view('traders.deposit.help2pay-success', [
                        'status' => true,
                        'message' => "Deposit process is successful",
                        'key' => $request->key,
                        'usd_amount' => $usd_amount,
                        'idr_amount' => $idr_amount,
                        'currency' => $request->Currency,
                        'invoice_id' => $request->Key,
                        'date' => date('Y/m/d', strtotime(now())),
                    ]);
                } else {
                    return view('traders.deposit.help2pay-success', [
                        'status' => false,
                        'message' => "Deposit process is faild, Please reload this page and continue",
                        'key' => $request->key,
                        'usd_amount' => $usd_amount,
                        'idr_amount' => $idr_amount,
                        'currency' => $request->Currency,
                        'invoice_id' => $request->Key,
                        'date' => date('Y/m/d', strtotime(now())),
                    ]);
                }
            } else {
                return view('traders.deposit.help2pay-success', [
                    'status' => false,
                    'message' => "Deposit process is faild, Because we got invalid key, That not matched our credental.",
                    'key' => $request->key,
                    'usd_amount' => $usd_amount,
                    'idr_amount' => $idr_amount,
                    'currency' => $request->Currency,
                    'invoice_id' => $request->Key,
                    'date' => date('Y/m/d', strtotime(now())),
                ]);
            }
        } else {
            return view('traders.deposit.help2pay-success', [
                'status' => false,
                'message' => "Deposit process is faild, Because your bank info is incorrect, That not matched our credental.",
                'key' => $request->key,
                'usd_amount' => $usd_amount,
                'idr_amount' => $idr_amount,
                'currency' => $request->Currency,
                'invoice_id' => $request->Key,
                'date' => date('Y/m/d', strtotime(now())),
            ]);
        }
        return view('traders.deposit.help2pay-success', [
            'status' => false,
            'message' => "Deposit process is faild, Because your bank info is incorrect, That not matched our credental.",
            'key' => $request->key,
            'usd_amount' => $usd_amount,
            'idr_amount' => $idr_amount,
            'currency' => $request->Currency,
            'invoice_id' => $request->Key,
            'date' => date('Y/m/d', strtotime(now())),
        ]);
    }
    public function help2deposit(Request $request)
    {
        return $request->all();
    }
}
