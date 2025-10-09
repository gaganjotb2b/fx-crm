<?php

namespace App\Http\Controllers\traders\NoCopyPamm;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\PammInvestor;
use App\Models\PammTrade;
use App\Models\PammUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\balance\BalanceSheetService;
use App\Services\ctrader\CtraderApi;
use App\Services\EmailService;
use App\Services\MailNotificationService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use App\Services\TransactionService;
use App\Services\VertexApiCall;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoCopyPammOverviewController extends Controller
{
    public function index(Request $request)
    {
        try {
            $pamm_profile = PammUser::with('tradingAccount')->where('account', $request->ac)->where('id', $request->id)->first();
            $with_us = $pamm_profile ? str_replace([' ago', ' from now'], '', $pamm_profile->created_at->diffForHumans()) : 'N/A';
            $total_investor = PammInvestor::where('pamm_id', $pamm_profile->id)->count();
            $total_investment = InternalTransfer::where('account_type', 'pamm')
                ->where('type', 'wta')
                ->where('account_id', $pamm_profile->tradingAccount?->id)
                ->sum('amount');
            return view('traders.pamm.non-copy-pamm.non-copy-pamm-overview', [
                'user_name' => $pamm_profile->username ?? '---',
                'user_email' => $pamm_profile->email ?? '---',
                'commission' => $pamm_profile->share_profit ?? '---',
                'min_deposit' => $pamm_profile->min_deposit ?? '---',
                'max_deposit' => $pamm_profile->max_deposit ?? '---',
                'account' => $pamm_profile->account ?? '---',
                'leverage' => $pamm_profile?->tradingAccount?->leverage ?? '---',
                'with_us' => $with_us,
                'total_investor' => $total_investor ?? 0,
                'share_profit' => $pamm_profile->share_profit ?? 0,
                'total_invested' => $total_investment,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back();
        }
    }
    public function trade_details(Request $request)
    {
        try {
            // profit count
            $total_profit = PammTrade::where('pamm_id', $request->input('id'))
                ->where('cmd', '!=', 9)
                ->where('profit', '>', 0)
                ->sum('profit');
            $total_loss = PammTrade::where('pamm_id', $request->input('id'))
                ->where('cmd', '!=', 9)
                ->where('profit', '<', 0)
                ->sum('profit');
            $gain = $this->calculateGainPercentage($total_profit, $total_loss);
            // trade count
            $total_profitable_trade = PammTrade::where('pamm_id', $request->input('id'))
                ->where('cmd', '!=', 9)
                ->where('profit', '>', 0)
                ->count();
            $total_losable_trade = PammTrade::where('pamm_id', $request->input('id'))
                ->where('cmd', '!=', 9)
                ->where('profit', '<', 0)
                ->count();
            $profit_loss_percent = $this->calculateProfitLossPercentage($total_profitable_trade, $total_losable_trade);
            // volume count
            $total_volume  = PammTrade::where('pamm_id', $request->input('id'))
                ->where('cmd', '!=', 9)
                ->sum('volume');
            // greatest profit
            $greatest_profit = PammTrade::where('pamm_id', $request->input('id'))
                ->where('cmd', '!=', 9)
                ->where('profit', '>', 0)
                ->max('profit');

            $greatest_loss = PammTrade::where('pamm_id', $request->input('id'))
                ->where('cmd', '!=', 9)
                ->where('profit', '<', 0)
                ->min('profit');
            // average profit
            $average_profit = PammTrade::where('pamm_id', $request->input('id'))
                ->where('cmd', '!=', 9)
                ->where('profit', '>', 0)
                ->avg('profit');
            $average_loss = PammTrade::where('pamm_id', $request->input('id'))
                ->where('cmd', '!=', 9)
                ->where('profit', '<', 0)
                ->avg('profit');
            // best trade
            // $best_trade_percentage = PammTrade::where('pamm_id', $request->input('id'))
            //     ->where('cmd', '!=', 9)
            //     ->where('profit', '>', 0)
            //     ->selectRaw('MAX((profit / volume) * 100) as best_trade_percentage')
            //     ->value('best_trade_percentage');

            $greatestTradeSelect = PammTrade::where('pamm_id', $request->input('id'))
                ->where('cmd', '!=', 9)
                ->where('profit', '>', 0)
                ->orderByDesc('profit')
                ->first();
            $balance = PammTrade::where('pamm_id', $request->input('id'))
                ->where('created_at', '<', $greatestTradeSelect->created_at)
                ->sum('profit');
            $best_trade_percentage = ($greatestTradeSelect->profit / $balance) * 100;

            return response()->json([
                'total_profit' => round($total_profit, 2),
                'total_loss' => round($total_loss, 2),
                'gain' => number_format($gain, 2),
                'trade_percent' => $profit_loss_percent,
                'volume' => number_format($total_volume, 2),
                'greatest_profit' => number_format($greatest_profit, 2),
                'greatest_loss' => number_format($greatest_loss, 2),
                'average_profit' => number_format($average_profit, 2),
                'average_loss' => number_format($average_loss, 2),
                'best_trade_percentage' => number_format($best_trade_percentage, 2)
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'total_profit' => 0,
                'total_loss' => 0
            ]);
        }
    }
    // render top growth chart
    public function render_growth_chart(Request $request)
    {
        try {
            $pamm_id = $request->input('id');
            $account = $request->input('account');

            $initialBalance = 0;
            $currentBalance = $initialBalance;
            $totalGrowthFactor = 1;

            // Fetch trade history ordered by date with pagination
            $batchSize = 1000; // Define batch size for pagination
            $skip = 0;
            $label = [];
            $data = [];
            $equity = [];

            do {
                $trades = PammTrade::orderBy('close_time', 'asc')
                    ->where('login', $account)
                    ->skip($skip)
                    ->take($batchSize)
                    ->get();

                if ($trades->isEmpty()) {
                    break;
                }
                foreach ($trades as $trade) {
                    // Prepare trade data
                    $amount = $trade->profit;
                    $type = $trade->cmd;

                    if ($type == 9) {
                        // Deposit or Withdrawal (Type 6)
                        if ($amount > 0) {
                            // Deposit: Add to balance
                            $currentBalance += $amount;
                        } else {
                            // Withdrawal: Subtract from balance
                            $currentBalance += $amount; // amount is negative for withdrawals
                        }
                    } else {
                        // Profit or Loss (Other types)
                        $previousBalance = $currentBalance;
                        $currentBalance += $amount;

                        // Calculate growth factor for this period
                        if ($previousBalance > 0) {
                            $growthFactor = $currentBalance / $previousBalance;
                            $totalGrowthFactor *= $growthFactor;
                        }
                    }
                    $label[] = date('Y-m-d', strtotime($trade->close_time));
                    $data[] = round(($totalGrowthFactor - 1) * 100, 2);
                    $equity[] = round($currentBalance, 2);
                }
                // Move to the next batch
                $skip += $batchSize;
            } while (true);
            // return $data;
            $finalCompoundGrowth = round(($totalGrowthFactor - 1) * 100, 2);
            $index_of_data = array_keys($data);

            // Return JSON response
            return ([
                'currentBalance' => round($currentBalance, 2),
                'compound_growth' => $finalCompoundGrowth,
                'data' => array_values($data),
                'label' => array_values($label),
                // 'label' => $index_of_data,
                'equity' => $equity,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return ([
                'currentBalance' => 0,
                'compound_growth' => 0,
                'data' => [],
                'label' => []
            ]);
        }
    }
    function calculateGainPercentage($totalProfit, $totalLoss)
    {
        if ($totalLoss == 0) {
            $result = $totalProfit > 0 ? 100 : 0; // If no loss, assume 100% gain if profit exists.
        } elseif ($totalProfit == 0) {
            $result = 0;
        } else {
            $result =  (($totalProfit - abs($totalLoss)) / abs($totalLoss)) * 100;
        }
        return  number_format($result, 2);
    }
    // load balance equity
    // -----------------------
    public function balance_equity(Request $request)
    {
        try {
            $login = $request->input('account');
            $pamm_user = PammUser::with('tradingAccount')->where('id', $request->input('id'))->where('account', $login)->first();
            $account = $pamm_user->tradingAccount;
            // get balance from platform
            // -------------------------
            $platform = strtolower($account->platform);
            $balance = $equity = $result = 0;
            switch ($platform) {
                case 'mt4':
                    return $result = $this->balance_equity_mt4($account);
                    break;
                default:
                    return $result = $this->balance_equity_mt5($account);
                    break;
            }

            // return response()->json($result);
        } catch (\Throwable $th) {
            // throw $th;
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
                    'equity' => round($equity, 2),
                    'balance' => round($balance, 2),
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

    function calculateProfitLossPercentage($totalProfit, $totalLoss)
    {
        $totalTrade = $totalProfit + abs($totalLoss);

        if ($totalTrade == 0) {
            return [
                'profit_percentage' => 0,
                'loss_percentage' => 0
            ];
        }

        $profitPercentage = ($totalProfit / $totalTrade) * 100;
        $lossPercentage = (abs($totalLoss) / $totalTrade) * 100;

        return [
            'profit_percentage' => number_format($profitPercentage, 2),
            'loss_percentage' => number_format($lossPercentage, 2),
            'total_trade' => $totalTrade
        ];
    }
    // pamm investment
    // ----------------
    public function investment(Request $request)
    {
        try {
            $user_id = auth()->id();
            $validator = Validator::make($request->all(), [
                'account' => 'required|integer',
                'amount' => 'required|numeric|gt:0',
                'transaction_password' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ]);
            }
            $pamm_user = PammUser::with('tradingAccount')->find($request->input('account'));
            if (!$pamm_user) {
                return response()->json([
                    'status' => false,
                    'message' => 'PAMM Account not found, please reload the page',
                ]);
            }
            $amount = $request->input('amount');
            // check min deposit
            $min_deposit = $pamm_user->min_deposit;
            if ($min_deposit != 0 && $amount < $min_deposit) {
                return response()->json([
                    'status' => false,
                    'message' => "Minimum deposit required $$min_deposit",
                ]);
            }
            // check maximum 
            $max_deposit = $pamm_user->max_deposit;
            if ($max_deposit != 0 && $amount > $max_deposit) {
                return response()->json([
                    'status' => false,
                    'message' => "Maximum deposit required $$max_deposit",
                ]);
            }

            $account = $pamm_user->tradingAccount;
            if ($account->user_id === $user_id) {
                return response()->json([
                    'status' => false,
                    'message' => "You can't PAMM invest in your own PAMM profile"
                ]);
            }
            $platform = strtolower($account->platform);
            switch ($platform) {
                case 'mt5':
                    $result = $this->balance_update_mt5($account, $amount);
                    break;
                case 'mt4':
                    $result =  $this->balance_update_mt4($account, $amount);
                    break;
                default:
                    # code...
                    break;
            }
            if (!isset($result) || $result['status'] === false) {
                return response()->json([
                    'status' => false,
                    'message' => 'Transaction failed, please try again later'
                ]);
            }
            $create = PammInvestor::UpdateOrCreate([
                'user_id' => $user_id,
                'pamm_id' => $pamm_user->id,
            ], [
                'status' => 'active',
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Investment successfully done, please check your email and report.'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
                'error' => $th->getMessage(),
            ]);
        }
    }

    // balance update for mt5 account
    public function balance_update_mt5($account, $amount)
    {
        try {
            $user_id = auth()->id();
            $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
            $charge = TransactionService::charge('w_to_a', $amount, null);
            $mt5_api = new Mt5WebApi();

            $action = 'BalanceUpdate';
            $data = array(
                "Login" => (int)$account->account_number,
                "Balance" => (float)$amount,
                "Comment" => "Wallet Deposit #" . $invoice
            );
            $result = $mt5_api->execute($action, $data);

            if (isset($result['success']) && $result['success']) {
                $trans_data = [
                    'user_id' => $user_id,
                    'invoice_code' => $invoice,
                    'platform' => $account->platform,
                    'account_id' => $account->id,
                    'charge' => $charge,
                    'amount' => $amount,
                    'type' => 'wta',
                    'order_id' => $result['data']['order'],
                    'status' => 'A',
                    'account_type' => 'pamm'
                ];
                $internal_transfer = InternalTransfer::create($trans_data);
                //mailer script
                if ($internal_transfer) {
                    // get last transaction
                    $last_transaction = InternalTransfer::where('user_id', $user_id)->where('type', 'wta')->latest()->first();
                    // admin notification
                    $client = User::find($user_id);
                    MailNotificationService::admin_notification([
                        'amount' => $amount,
                        'name' => $client->name,
                        'email' => $client->email,
                        'type' => 'wallet to account transfer',
                        'client_type' => 'trader'
                    ]);
                    EmailService::user_notification('wallet-to-account-transfer', 'trader', [
                        'user_id' => $user_id,
                        'clientDepositAmount' => $amount,
                        'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                        'previous_balance' => ((BalanceSheetService::trader_wallet_balance($user_id)) + ($last_transaction->amount)),
                        'transfer_amount' => $last_transaction->amount,
                        'total_balance' => BalanceSheetService::trader_wallet_balance($user_id)
                    ]);
                    // insert activity-----------------
                    $user = User::find(auth()->user()->id);
                    //<---client email as user id
                    activity("wallet to account")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($trans_data)
                        ->event("wallet to account")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been wallet to account transfer");
                    // end activity log----------------->>
                    return ([
                        'status' => true,
                        'last_transaction' => $last_transaction,
                        'submit_wait' => submit_wait('wta-transfer', 60),
                        'message' => 'Transaction successfully done!'
                    ]);
                }
                return ([
                    'status' => false,
                    'submit_wait' => submit_wait('wta-transfer', 60),
                    'message' => 'Something went wrong, please try again later',
                ]);
            }
            return ([
                'status' => false,
                'submit_wait' => submit_wait('wta-transfer', 60),
                'message' => (array_key_exists('data', $result)) ? $result['data']['message'] : $result['error']['Description'],

            ]);
        } catch (\Throwable $th) {
            throw $th;
            return ([
                'status' => false,
                'submit_wait' => submit_wait('wta-transfer', 60),
                'message' => 'Got a server error, please contact for support',
            ]);
        }
    }

    // mt4 account balance transfer
    public function balance_update_mt4($amount, $account)
    {
        try {
            $user_id = auth()->id();
            $mt4_api = new MT4API();
            $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
            $charge = TransactionService::charge('w_to_a', $amount, null);
            $data = array(
                'command' => 'BalanceUpdate',
                'data' => array(
                    'Login' => $account->account_number,
                    'Balance' => (float)$amount,
                    'Comment' => "PAMM Investment #" . $invoice
                ),
            );
            $result = $mt4_api->execute($data, 'live');
            if (isset($result['status']) && $result['status']) {
                $internal_transfer = InternalTransfer::create([
                    'user_id' => $user_id,
                    'invoice_code' => $invoice,
                    'platform' => $account->platform,
                    'account_id' => $account->id,
                    'charge' => $charge,
                    'amount' => $amount,
                    'type' => 'wta',
                    'order_id' => $result['data']['order'],
                    'status' => 'A',
                    'account_type' => 'pamm',
                    'note' => 'Investor investment'
                ]);
                //mailer script
                if ($internal_transfer) {
                    // get last transaction
                    $last_transaction = InternalTransfer::where('user_id', $user_id)->where('type', 'wta')->latest()->first();
                    // admin notification
                    $client = User::find($user_id);
                    MailNotificationService::admin_notification([
                        'amount' => $amount,
                        'name' => $client->name,
                        'email' => $client->email,
                        'type' => 'wallet to account transfer',
                        'client_type' => 'trader'
                    ]);
                    EmailService::user_notification('wallet-to-account-transfer', 'trader', [
                        'user_id' => $user_id,
                        'clientDepositAmount' => $amount,
                        'amount' => $amount,
                        'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                        'previous_balance' => ((BalanceSheetService::trader_wallet_balance($user_id)) + ($last_transaction->amount)),
                        'transfer_amount' => $last_transaction->amount,
                        'total_balance' => BalanceSheetService::trader_wallet_balance($user_id),
                        'account_number' => $account->account_number,
                    ]);
                    // insert activity-----------------
                    $user = User::find(auth()->user()->id);
                    //<---client email as user id
                    activity("PAMM Investment")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($internal_transfer)
                        ->event("create")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been invest fund to a PAMM");
                    // end activity log----------------->>
                    return ([
                        'status' => true,
                        'last_transaction' => $last_transaction,
                        'message' => 'Transaction successfully done!'
                    ]);
                } else {
                    return ([
                        'status' => false,
                        'message' => (array_key_exists('data', $result)) ? $result['data']['message'] : $result['error']['Description'],
                    ]);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
            return ([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
            ]);
        }
    }
    // cTrader balance update
    public function balance_update_cTrader($account, $amount)
    {
        try {
            $cTrader = new CtraderApi();
            $invoice = substr(hash('sha256', mt_rand() . microtime()), 0, 16);
            $charge = TransactionService::charge('w_to_a', $amount, null);
            $result = $cTrader->call('TraderBalanceUpdate', [
                'comment' => 'Deposit balance',
                'login' => $account->account_number,
                'preciseAmount' => $amount,
                'type' => 'DEPOSIT'
            ], $account->account_number);
            if ($result->status === true && $result->code === 200) {
                $orderID = $result->data['balanceHistoryId'];
                $trans_data = [
                    'user_id' => $account->user_id,
                    'invoice_code' => $invoice,
                    'platform' => $account->platform,
                    'account_id' => $account->id,
                    'charge' => $charge,
                    'amount' => $amount,
                    'type' => 'wta',
                    'order_id' => $orderID,
                    'status' => 'A'
                ];
                $internal_transfer = InternalTransfer::create($trans_data);
                //mailer script
                if ($internal_transfer) {
                    // get last transaction
                    $last_transaction = InternalTransfer::where('user_id', $account->user_id)->where('type', 'wta')->latest()->first();
                    // admin notification
                    $client = User::find($account->user_id);
                    MailNotificationService::admin_notification([
                        'amount' => $amount,
                        'name' => $client->name,
                        'email' => $client->email,
                        'type' => 'wallet to account transfer',
                        'client_type' => 'trader'
                    ]);
                    EmailService::user_notification('wallet-to-account-transfer', 'trader', [
                        'user_id' => $account->user_id,
                        'clientDepositAmount' => $amount,
                        'transfer_date' => ($last_transaction) ? ucwords($last_transaction->created_at) : '',
                        'previous_balance' => ((BalanceSheetService::trader_wallet_balance($account->user_id)) + ($last_transaction->amount)),
                        'transfer_amount' => $last_transaction->amount,
                        'total_balance' => BalanceSheetService::trader_wallet_balance($account->user_id)
                    ]);
                    // insert activity-----------------
                    $user = User::find(auth()->user()->id);
                    //<---client email as user id
                    activity("wallet to account")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($trans_data)
                        ->event("wallet to account")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been wallet to account transfer");
                    // end activity log----------------->>
                    return ([
                        'status' => true,
                        'last_transaction' => $last_transaction,
                        'submit_wait' => submit_wait('wta-transfer', 60),
                        'message' => 'Transaction successfully done!'
                    ]);
                }
                return ([
                    'status' => false,
                    'submit_wait' => submit_wait('wta-transfer', 60),
                    'message' => 'Something went wrong, please try again later',
                ]);
            }
            return ([
                'status' => false,
                'submit_wait' => submit_wait('wta-transfer', 60),
                'message' => 'API Response failed, please contact for support',
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
