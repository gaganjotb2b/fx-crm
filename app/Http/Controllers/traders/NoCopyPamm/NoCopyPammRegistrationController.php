<?php

namespace App\Http\Controllers\traders\NoCopyPamm;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\NonCopyPammSetting;
use App\Models\PammUser;
use App\Models\TradingAccount;
use App\Services\balance\BalanceSheetService;
use App\Services\ctrader\CtraderApi;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\VertexApiCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\returnSelf;

class NoCopyPammRegistrationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $accounts = TradingAccount::where('user_id', auth()->id())->get();
            return view('traders.pamm.non-copy-pamm.non-copy-pamm-registration', [
                'accounts' => $accounts
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // balance equity with existing data
    public function balance_equity(Request $request)
    {
        try {
            $account_id = $request->input('account');
            $account = TradingAccount::where('user_id', auth()->id())->where('id', $account_id)->first();
            $pamm_user = PammUser::where('user_id', auth()->id())->where('account', $account->account_number)->first();
            // get balance from platform
            // -------------------------
            $platform = strtolower($account->platform);
            $balance = $equity = $result = 0;
            switch ($platform) {
                case 'mt4':
                    $result = $this->balance_equity_mt4($account);
                    break;
                default:
                    $result = $this->balance_equity_mt5($account);
                    break;
            }
            $balance = $result['balance'];
            $equity = $result['equity'];
            return response()->json([
                'status' => true,
                'data' => [
                    'balance' => $balance,
                    'equity' => $equity,
                ],
                'pamm' => $pamm_user
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function balance_equity_mt4($account)
    {
        try {
            $mt4api = new MT4API();
            $result = $mt4api->execute([
                'command' => 'UserDataGet',
                'data' => array('Login' => $account->account_number),
            ], $account->client_type);
            if ($result["status"]) {
                $balance = $result['data']['Balance'];
                $equity = $result['data']['Equity'];
                return ([
                    'success' => true,
                    'credit' => 0,
                    'equity' => $equity,
                    'balance' => $balance,
                    'free_margin' => 0,
                ]);
            }
            return ([
                'message' => $result['error']['Description'],
                'success' => false,
                'credit' => 0,
                'equity' => 0,
                'balance' => 0,
                'free_margin' => 0,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return ([
                'message' => 'Got a server error, please contact for support',
                'success' => false,
                'credit' => 0,
                'equity' => 0,
                'balance' => 0,
                'free_margin' => 0,
            ]);
        }
    }
    // balance equity for vertex
    public function balance_equity_vertex($account)
    {
        try {
            $vertex = new VertexApiCall();
            $vertex->execute('BackofficeLogin');
            $result = $vertex->execute('GetAccountSummary', [
                'AccountId' => $account->account_number
            ]);
            if ($result['success'] == true) {
                return ([
                    'credit' => $result['data']->Credit,
                    'equity' => $result['data']->Equity,
                    'balance' => $result['data']->Balance,
                    'free_margin' => isset($result['data']->FreeMargin) ? $result['data']->FreeMargin : 0,
                ]);
            }
            return ([
                'success' => false,
                'message' => 'API Response failed, please contact for support',
                'balance' => 0,
                'equity' => 0,
                'free_margin' => 0,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return ([
                'success' => false,
                'message' => 'Got a server error, please contact for support',
                'balance' => 0,
                'equity' => 0,
                'free_margin' => 0,
            ]);
        }
    }
    // for mt5 account
    public function balance_equity_mt5($account)
    {
        try {
            $mt5_api = new Mt5WebApi();
            $result = $mt5_api->execute('AccountGetMargin', [
                "Login" => $account->account_number
            ]);
            if (isset($result['success'])) {
                if ($result['success']) {
                    return ([
                        'success' => true,
                        'credit' => $result['data']['Credit'],
                        'equity' => $result['data']['Equity'],
                        'balance' => $result['data']['Balance'],
                        'free_margin' => isset($result['data']['MarginFree']) ? $result['data']['MarginFree'] : 0,
                    ]);
                }
                return ([
                    'message' => $result['error']['Description'],
                    'credit' => 0,
                    'equity' => 0,
                    'balance' => 0,
                    'free_margin' => 0,
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ([
                'message' => 'Got a server error please contact for support',
                'credit' => 0,
                'equity' => 0,
                'balance' => 0,
                'free_margin' => 0,
            ]);
        }
    }
    // balance equity for cTrader
    public function balance_equity_cTrader($account)
    {
        try {
            $cTraderApi = new CtraderApi();
            $result = $cTraderApi->call('TraderGetData', [],  $account->account_number);
            if ($result->status === true && $result->code === 200) {
                $equity = $result->data['equity'];
                $balance = $result->data['balance'];
                return ([
                    'credit' => $result->data['bonus'],
                    'equity' => $result->data['equity'],
                    'balance' => $result->data['balance'],
                    'free_margin' => $result->data['freeMargin'],
                ]);
            }
            return ([
                'credit' => 0.00,
                'equity' => 0.00,
                'balance' => 0.00,
                'free_margin' => 0.00,
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return ([
                'credit' => 0.00,
                'equity' => 0.00,
                'balance' => 0.00,
                'free_margin' => 0.00,
            ]);
        }
    }

    // pamm registration & update 
    // ------------------------------
    public function pamm_registration(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'account' => 'required|integer',
                'username' => 'required|string|max:60',
                'minimum_deposit' => 'required|numeric|gte:0',
                'maximum_deposit' => 'required|numeric|gte:0',
                'share_profit' => 'required|numeric|gte:0',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ]);
            }
            // pamm settings
            $pamm_settings = NonCopyPammSetting::first();
            $account = TradingAccount::find($request->input('account'));
            $pamm_user_exists = PammUser::where('user_id', auth()->id())->where('account', $account->account_number)->exists();
            if (!$pamm_settings) {
                return response()->json([
                    'status' => false,
                    'message' => 'PAMM settings not found, please contact for support'
                ]);
            }
            // check requirement status
            // requirement check only when register as new
            if ($pamm_settings->requirement_status === 'active' && !$pamm_user_exists) {
                // check the unique username when register
                // ------------------------
                $pamm_user_name = PammUser::where('username', $request->input('username'))->exists();
                if ($pamm_user_name) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Username already taken, please input another username',
                        'errors' => ['username' => 'Username already taken, please input another username']
                    ]);
                }
                // check master limit
                $total_pamm_account = PammUser::where('user_id', auth()->id())->count();
                $master_limit = $pamm_settings->master_limit;
                if ($master_limit != 0 && $total_pamm_account >= $master_limit) {
                    return response()->json([
                        'status' => false,
                        'message' => 'You pamm profile limit existed, please contact for support'
                    ]);
                }
                // check minimum account deposit
                $min_account_deposit = $pamm_settings->min_account_deposit;
                $total_account_deposit = InternalTransfer::where('type', 'wta')->where('account_id', $account->id)->sum('amount');
                if ($min_account_deposit != 0 && $total_account_deposit < $min_account_deposit) {
                    return response()->json([
                        'status' => false,
                        'message' => "Minimum account deposit required $$min_account_deposit",
                    ]);
                }
                // check minimum account balance
                $min_account_balance = $pamm_settings->min_account_balance;
                if ($min_account_balance != 0) {
                    // check account balance from platform
                    $platform = strtolower($account->platform);
                    $balance = $equity = $result = 0;
                    switch ($platform) {
                        case 'mt4':
                            $result = $this->balance_equity_mt4($account);
                            break;
                        case 'vertex':
                            $result = response()->json($this->balance_equity_vertex($account));

                            break;
                        case 'ctrader':
                            $result = response()->json($this->balance_equity_cTrader($account));
                            break;
                        default:
                            $result = response()->json($this->balance_equity_mt5($account));
                            break;
                    }
                    $balance = $result['balance'];
                    if ($balance < $min_account_balance) {
                        return response()->json([
                            'status' => false,
                            'message' => "Minimum account balance required $$min_account_balance",
                        ]);
                    }
                }
                // check minimum wallet balance
                $min_wallet_balance = $pamm_settings->min_wallet_balance;
                $wallet_balance = BalanceSheetService::trader_wallet_balance(auth()->id());
                if ($min_wallet_balance != 0 && $wallet_balance < $min_wallet_balance) {
                    return response()->json([
                        'status' => false,
                        'message' => "Minimum wallet balance required $$min_wallet_balance",
                    ]);
                }
            }
            // profit share check
            // check for both new and update
            // -----------------------------
            $profit_share_is = $pamm_settings->profit_share_is;
            $profit_share = $request->input('share_profit');
            $settings_profit_share = $pamm_settings->profit_share;
            if ($profit_share_is === 'fixed') {
                // check profit share                
                if ($profit_share != $settings_profit_share) {
                    return response()->json([
                        'status' => false,
                        'message' => "Profit share should equal to $$settings_profit_share"
                    ]);
                }
            } elseif ($profit_share_is === 'flexible') {
                // check flexible profit share
                $min_profit_share = $pamm_settings->min_profit_share;
                $max_profit_share = $pamm_settings->max_profit_share;
                if ($profit_share < $min_profit_share || $profit_share > $max_profit_share) {
                    return response()->json([
                        'status' => false,
                        'message' => "Profit share should between $ $min_profit_share to $ $max_profit_share"
                    ]);
                }
            }

            // finally create of update
            // set status
            if ($pamm_user_exists) {
                $pamm_user = PammUser::where('user_id', auth()->id())->where('account', $account->account_number)->first();
                $status = $pamm_user->request_status;
            } elseif ($pamm_settings->approval_type === 'auto') {
                $status = 'approved';
            } else {
                $status = 'pending';
            }
            $create = PammUser::updateOrCreate([
                'user_id' => auth()->id(),
                'account' => $account->account_number,
            ], [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'username' => $request->input('username'),
                'min_deposit' => $request->input('minimum_deposit'),
                'max_deposit' => $request->input('maximum_deposit'),
                'share_profit' => $request->input('share_profit'),
                'status' => 'active',
                'request_status' => $status,
            ]);
            if ($create) {
                if ($pamm_user_exists) {
                    $message = 'PAMM profile successfully updated';
                } else {
                    $message = 'PAMM profile successfully created';
                }
                return response()->json([
                    'status' => true,
                    'message' => $message,
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
                'error' => $th->getMessage(),
            ]);
        }
    }
}
