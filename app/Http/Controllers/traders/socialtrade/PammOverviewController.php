<?php

namespace App\Http\Controllers\traders\socialtrade;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\CopySlave;
use App\Models\CopyTrade;
use App\Models\CopyUser;
use App\Models\Traders\PammSetting;
use App\Models\TradingAccount;
use App\Services\CopyApiService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\UTCDateTime;

class PammOverviewController extends Controller
{
    private $copy_api = "";
    public function __construct()
    {
        $this->copy_api = new CopyApiService();
    }
    //upgrate vision of copy with mongodb
    public function index(Request $request)
    {

        try {
            if ($request->ac == "") {
                return redirect()->route('user.pamm.profile');
            }

            $trading_account = TradingAccount::where('user_id', auth()->user()->id)->get();
            return view(
                'traders/pamm.pamm-overview',
                [
                    'accounts' => $trading_account,
                ]
            );
        } catch (\Throwable $th) {
            // throw $th;
            return redirect()->route('user.pamm.profile');
        }
    }
    public function account_details(Request $request)
    {
        try {
            $copy_trades = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "SELECT *FROM copy_users WHERE account = $request->ac",
                ]
            ]));
            $data = isset($copy_trades->data) ? $copy_trades->data : [];
            return Response::json([
                'status' => true,
                'data' => [
                    'username' => isset($data[0]->username) ? $data[0]->username : '---',
                    'email' => isset($data[0]->email) ? $data[0]->email : '---'
                ]
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'data' => [
                    'username' => '---',
                    'email' => '---'
                ]
            ]);
        }
    }
    // open order upadate version
    public function open_order(Request $request)
    {
        try {
            // only for fxcrm demo account filter
            if (($request->input('account') == 97900159 || $request->input('account') == 98831808 || $request->input('account') == 98832171) && strtolower(config('app.name')) === 'fxcrm') {
                return $this->demo_open_order($request);
            }

            // -------------------------------------------------
            $columns = ['Order', 'Login', 'OpenTime', ['CloseTime', 'Symbol', 'Volume', 'OpenPrice', 'Profit']];

            $order = $columns[$request->order[0]['column']];
            if (strtolower(config('app.name')) === 'fxcrm') {
                $query = CopyTrade::where('Login', $request->account);
            } else {
                $query = CopyTrade::where('CloseTime', '1970-01-01 00:00:00')->where('Login', $request->account);
            }
            // Start search
            if (isset($request->search['value'])) {
                $search =  $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('Order', $search)
                        ->orWhere('Login', $search)
                        ->orWhere('OpenTime', 'LIKE', '%' . $search . '%')
                        ->orWhere('Symbol', 'LIKE', '%' . $search . '%')
                        ->orWhere('Volume', 'LIKE', '%' . (float)$search . '%')
                        ->orWhere('OpenPrice', 'LIKE', '%' . (float)$search . '%');
                });
            }

            $countQuery = clone $query;
            $query->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length);

            // Convert to select SQL
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            $selectQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $sql), array_map('addslashes', $bindings));

            // Convert count SQL
            $countSql = $countQuery->selectRaw('COUNT(*) as total')->toSql();
            $countBindings = $countQuery->getBindings();
            $countQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $countSql), array_map('addslashes', $countBindings));


            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$selectQuery",
                ]
            ]));
            $result = isset($result->data) ? $result->data : [];
            // count total trades
            $count = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$countQuery",
                ]
            ]));

            $count = isset($count->data[0]->total) ? $count->data[0]->total : 0;
            $data = [];
            foreach ($result as $value) {

                $data[] = [
                    'ticket' => $value->Order,
                    'account' => $value->Login,
                    'open_time' => $value->OpenTime,
                    'symbol' => $value->Symbol,
                    'volume' => round($value->Volume/10000, 2),
                    'open_price' => $value->OpenPrice,
                    'status' => '<span style="color:#0f7a0b">Trade Running...</span>'
                ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }
    // open order display for demo accounts
    // --------------------------------------------
    public function demo_open_order(Request $request)
    {
        try {
            $columns = ['Order', 'Login', 'OpenTime', ['CloseTime', 'Symbol', 'Volume', 'OpenPrice', 'Profit']];

            $order = $columns[$request->order[0]['column']];
            if (strtolower(config('app.name')) === 'fxcrm') {
                $query = CopyTrade::where('Login', $request->account);
            } else {
                $query = CopyTrade::where('CloseTime', '1970-01-01 00:00:00')->where('Login', $request->account);
            }


            // Start search
            if (isset($request->search['value'])) {
                $search =  $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('Order', $search)
                        ->orWhere('Login', $search)
                        ->orWhere('OpentTime', 'LIKE', $search . '%')
                        ->orWhere('Symbol', 'LIKE', '%' . $search . '%')
                        ->orWhere('Volume', (float)$search)
                        ->orWhere('OpenPrice', 'LIKE', '%' . $search . '%');
                });
            }

            $countQuery = clone $query;
            $result = $query->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();

            $count = $countQuery->count();
            $data = [];
            foreach ($result as $value) {

                $data[] = [
                    'ticket' => $value->Order,
                    'account' => $value->Login,
                    'open_time' => $value->OpenTime,
                    'symbol' => $value->Symbol,
                    'volume' => round($value->volume/100, 2),
                    'open_price' => $value->OpenPrice,
                    'status' => '<span style="color:#0f7a0b">Trade Running...</span>'
                ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }
    // closed order
    public function close_order(Request $request)
    {
        try {
            if (($request->input('account') == 97900159 || $request->input('account') == 98831808 || $request->input('account') == 98832171) && strtolower(config('app.name')) === 'fxcrm') {
                return $this->close_trade_demo($request);
            }
            $columns = ['Order', 'Login', 'OpenTime', ['CloseTime', 'Symbol', 'Volume', 'OpenPrice', 'Profit']];
            $order = $columns[$request->order[0]['column']];
            $query = CopyTrade::whereNot('OpenTime', '1970-01-01 00:00:00')->where('Login', $request->account);

            // Start search
            if (isset($request->search['value'])) {
                $search =  $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('Order', $search)
                        ->orWhere('Login', $search)
                        ->orWhere('OpenTime', 'LIKE', '%' . $search . '%')
                        ->orWhere('Symbol', 'LIKE', '%' . $search . '%')
                        ->orWhere('Volume', 'LIKE', '%' . (float)$search . '%')
                        ->orWhere('OpenPrice', 'LIKE', '%' . (float)$search . '%');
                });
            }

            $countQuery = clone $query;
            $query->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length);

            // Convert to select SQL
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            $selectQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $sql), array_map('addslashes', $bindings));

            // Convert count SQL
            $countSql = $countQuery->selectRaw('COUNT(*) as total')->toSql();
            $countBindings = $countQuery->getBindings();
            $countQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $countSql), array_map('addslashes', $countBindings));


            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$selectQuery",
                ]
            ]));

            $result = isset($result->data) ? $result->data : [];
            // count total trades
            $count_result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$countQuery",
                ]
            ]));

            $count = isset($count_result->data[0]->total) ? $count_result->data[0]->total : 0;
            $data = [];
            foreach ($result as $value) {
                // loss|profit arrow
                $ticket = '';
                $profit = '';
                if ($value->Profit > 0) {
                    $arrow = asset('trader-assets/assets/img/pamm/logo/arro-circle-up.png');
                    $ticket = '<span class="d-flex justify-content-between">
                                <span><img class="" src="' . $arrow . '"></span>
                                <span style="color:#0f7a0b">' . $value->Order . '</span>
                            </span>';
                    $profit = '<span style="color:#0f7a0b">' . $value->Profit . '</span>';
                } else {
                    $arrow = asset('trader-assets/assets/img/pamm/logo/arro-circle-down.png');
                    $ticket = '<span class="d-flex justify-content-between">
                                <span><img class="" src="' . $arrow . '"></span>
                                <span style="color:#ff8e31">' . $value->Order . '</span>
                            </span>';
                    $profit = '<span style="color:#ff8e31">' . $value->Profit . '</span>';
                }
                $data[] = [
                    'ticket' => $ticket,
                    'account' => $value->Login,
                    'open_time' => $value->OpenTime,
                    'close_time' => $value->CloseTime,
                    'symbol' => $value->Symbol,
                    'volume' => round($value->Volume/10000, 2),
                    'open_price' => $value->OpenPrice,
                    'profit' => $profit
                ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }

    // ----------------------------------
    // close trade for demo only--
    // ------------------------------------
    public function close_trade_demo(Request $request)
    {
        try {
            $columns = ['Order', 'Login', 'OpenTime', ['CloseTime', 'Symbol', 'Volume', 'OpenPrice', 'Profit']];
            $order = $columns[$request->order[0]['column']];
            $query = CopyTrade::whereNot('CloseTime', '1970-01-01 00:00:00')->where('Login', $request->account);

            // Start search
            if (isset($request->search['value'])) {
                $search =  $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('Order', $search)
                        ->orWhere('Login', $search)
                        ->orWhere('OpenTime', 'LIKE', '%' . $search . '%')
                        ->orWhere('Symbol', 'LIKE', '%' . $search . '%')
                        ->orWhere('Volume', 'LIKE', '%' . (float)$search . '%')
                        ->orWhere('OpenPrice', 'LIKE', '%' . (float)$search . '%');
                });
            }

            $countQuery = clone $query;
            $result = $query->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();

            // Convert to select SQL


            $count = $countQuery->count();
            $data = [];
            foreach ($result as $value) {
                // loss|profit arrow
                $ticket = '';
                $profit = '';
                if ($value->Profit > 0) {
                    $arrow = asset('trader-assets/assets/img/pamm/logo/arro-circle-up.png');
                    $ticket = '<span class="d-flex justify-content-between">
                                <span><img class="" src="' . $arrow . '"></span>
                                <span style="color:#0f7a0b">' . $value->Order . '</span>
                            </span>';
                    $profit = '<span style="color:#0f7a0b">' . $value->Profit . '</span>';
                } else {
                    $arrow = asset('trader-assets/assets/img/pamm/logo/arro-circle-down.png');
                    $ticket = '<span class="d-flex justify-content-between">
                                <span><img class="" src="' . $arrow . '"></span>
                                <span style="color:#ff8e31">' . $value->Order . '</span>
                            </span>';
                    $profit = '<span style="color:#ff8e31">' . $value->Profit . '</span>';
                }
                $data[] = [
                    'ticket' => $ticket,
                    'account' => $value->Login,
                    'open_time' => $value->OpenTime,
                    'close_time' => $value->CloseTime,
                    'symbol' => $value->Symbol,
                    'volume' => round($value->volume/100, 2),
                    'open_price' => $value->OpenPrice,
                    'profit' => $profit
                ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }
    // get trade state data
    public function trade_state(Request $request)
    {
        try {
            // condition for local demo accounts
            // --------------------------------------------------------
            if (($request->input('account') == 97900159 || $request->input('account') == 98831808 || $request->input('account') == 98832171) && strtolower(config('app.name')) === 'fxcrm') {
                return Response::json($this->demo_copy_trade($request));
            }
            // count total trades
            $total_trades = CopyTrade::where('Login', (int)$request->account)->selectRaw('COUNT(*) as total');
            $count_sql = $total_trades->toSql();
            $countBindings = $total_trades->getBindings();
            $countQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $count_sql), array_map('addslashes', $countBindings));
            $result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$countQuery",
                ]
            ]));
            $total_trades = isset($result->data[0]->total) ? $result->data[0]->total : 0;

            // count total profit

            $total_profit = CopyTrade::where('Login', (int)$request->account)->where('Profit', '>', 0)->selectRaw('SUM(Profit) as total_profit, MAX(Profit) as max_profit, AVG(Profit) as average_profit');
            $total_profit_sql = $total_profit->toSql();
            $totalProfitBinding = $total_profit->getBindings();
            $totalProfitQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $total_profit_sql), array_map('addslashes', $totalProfitBinding));
            $total_profit_result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$totalProfitQuery",
                ]
            ]));
            $total_profit = (isset($total_profit_result->data[0]->total_profit) && $total_profit_result->data[0]->total_profit != null) ? $total_profit_result->data[0]->total_profit : 0;
            $max_profit = (isset($total_profit_result->data[0]->max_profit) && $total_profit_result->data[0]->max_profit != null) ? $total_profit_result->data[0]->max_profit : 0;
            $avg_profit = (isset($total_profit_result->data[0]->average_profit) && $total_profit_result->data[0]->average_profit != null) ? $total_profit_result->data[0]->average_profit : 0;

            // count total loss
            $total_loss = CopyTrade::where('Login', (int)$request->account)->where('Profit', '<', 0)->selectRaw('SUM(Profit) as total_loss, MAX(Profit) as max_loss, AVG(Profit) as average_loss');
            $total_loss_sql = $total_loss->toSql();
            $totalLossBinding = $total_loss->getBindings();
            $totalLossQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $total_loss_sql), array_map('addslashes', $totalLossBinding));
            $total_loss_result = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$totalLossQuery",
                ]
            ]));

            $total_loss = (isset($total_loss_result->data[0]->total_loss) && $total_loss_result->data[0]->total_loss != null) ? $total_loss_result->data[0]->total_loss : 0;
            $max_loss = (isset($total_loss_result->data[0]->max_loss) && $total_loss_result->data[0]->max_loss != null) ? $total_loss_result->data[0]->max_loss : 0;
            $avg_loss = (isset($total_loss_result->data[0]->average_loss) && $total_loss_result->data[0]->average_loss != null) ? $total_loss_result->data[0]->average_loss : 0;

            // count trade volume
            $total_volume = CopyTrade::where('Login', (int)$request->account)->selectRaw('SUM(Volume) as total_volume');
            $total_volume_sql = $total_volume->toSql();
            $totalVolulmeBinding = $total_volume->getBindings();
            $totalVolulmeQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $total_volume_sql), array_map('addslashes', $totalVolulmeBinding));
            $total_volume = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$totalVolulmeQuery",
                ]
            ]));
            $total_volume = (isset($total_volume->data[0]->total_volume) && $total_volume->data[0]->total_volume != null) ? $total_volume->data[0]->total_volume : 0;

            // get copy master / copy user
            $copy_user = CopyUser::where('account', (int)$request->account);
            $copy_user_sql = $copy_user->toSql();
            $copyUserBinding = $copy_user->getBindings();
            $copyUserQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $copy_user_sql), array_map('addslashes', $copyUserBinding));
            $copy_user = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$copyUserQuery",
                ]
            ]));
            $copy_user = isset($copy_user->data) ? $copy_user->data : [];

            // best trades
            $profitable_trade = CopyTrade::where('Login', (int)$request->account)->where('Profit', '>', 0)->selectRaw('COUNT(*) as total');
            $profitable_trade_sql = $profitable_trade->toSql();
            $profitableTradeBinding = $profitable_trade->getBindings();
            $profitableTradeQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $profitable_trade_sql), array_map('addslashes', $profitableTradeBinding));
            $profitable_trade = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$profitableTradeQuery",
                ]
            ]));
            $profitable_trade = (isset($profitable_trade->data[0]->total) && $profitable_trade->data[0]->total != null) ? $profitable_trade->data[0]->total : 0;
            // Calculate profit percentage and loss percentage
            if ($total_trades) {
                $best_trade_percent = ($profitable_trade / $total_trades) * 100;
                $profit_percentage = ($total_profit / $total_trades) * 100;
                $loss_percentage = ($total_loss / $total_trades) * 100;
            } else {
                $best_trade_percent = 0;
                $profit_percentage = 0;
                $loss_percentage = 0;
            }




            // count total copier
            $total_copier = CopySlave::where('master', (int)$request->account)->selectRaw('COUNT(*) as total');
            $total_copier_sql = $total_copier->toSql();
            $totalCopierBinding = $total_copier->getBindings();
            $totalCopierQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $total_copier_sql), array_map('addslashes', $totalCopierBinding));
            $total_copier = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$totalCopierQuery",
                ]
            ]));
            $total_copier = (isset($total_copier->data[0]->total) && $total_copier->data[0]->total != null) ? $total_copier->data[0]->total : 0;

            // get account leverage
            $trading_account = TradingAccount::where('account_number', $request->account)->first();
            // calculate with us
            $current_time_stamp = \Carbon\Carbon::now();
            $with_us = $current_time_stamp->diffInDays($copy_user[0]->created_at);
            // ----------------------------------------------------------------------
            // set data for demo account of fxcrm only
            // bellow condition only for fxcrm
            // if crm is live , bellow code not working , working actual code automatically
            // ---------------------------------------------------------------------
            if ($request->input('account') == 97900159 && strtolower(config('app.name')) === 'fxcrm') {
                $total_trades = 1000;
                $total_profit = 1500.45;
            }
            return Response::json([
                'total_trade' => $total_trades,
                'profit_percent' => round($profit_percentage, 4),
                'loss_percent' => round($loss_percentage, 4),
                'total_profit' => $total_profit,
                'total_loss' => $total_loss,
                'total_volume' => $total_volume,
                'commission' => isset($copy_user[0]) ? $copy_user[0]->share_profit : 0,
                'max_profit' => $max_profit,
                'max_loss' => $max_loss,
                'avg_profit' => round($avg_profit, 4),
                'avg_loss' => round($avg_loss, 4),
                'best_trade' => round($best_trade_percent, 4),
                'total_copier' => $total_copier,
                'min_deposit' => isset($copy_user[0]) ? $copy_user[0]->min_deposit : 0.00,
                'max_deposit' => isset($copy_user[0]) ? $copy_user[0]->max_deposit : 0.00,
                'leverage' => ($trading_account) ? '1:' . $trading_account->leverage : '0:0',
                'with_us' => $with_us,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // demo data for trade state-----------------------
    // --------------------------------------------------------
    public function demo_copy_trade(Request $request)
    {
        $account = $request->input('account');
        $total_trades = CopyTrade::where('Login', $account)->count('id');
        $total_profit = CopyTrade::where('Login', $account)->where('Profit', '>', 0)->sum('Profit');

        $max_profit = CopyTrade::where('Login', $account)->max('Profit');
        $avg_profit = ($total_profit / $total_trades);

        // count total loss
        $total_loss = CopyTrade::where('Login', (int)$account)->where('Profit', '<', 0)->sum('Profit');
        $max_loss = CopyTrade::where('Login', $account)->min('Profit');
        $avg_loss = ($total_loss / $total_trades);

        // count trade volume
        $total_volume = CopyTrade::where('Login', (int)$account)->sum('Volume');
        $total_volume = ($total_volume / 1000);

        // get copy master / copy user
        $copy_user = CopyUser::where('account', (int)$request->account);
        $copy_user_sql = $copy_user->toSql();
        $copyUserBinding = $copy_user->getBindings();
        $copyUserQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $copy_user_sql), array_map('addslashes', $copyUserBinding));
        $copy_user = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => [
                'sql' => "$copyUserQuery",
            ]
        ]));
        $copy_user = isset($copy_user->data) ? $copy_user->data : [];

        // best trades
        $profitable_trade = CopyTrade::where('Login', (int)$request->account)->where('Profit', '>', 0)->selectRaw('COUNT(*) as total');
        $profitable_trade_sql = $profitable_trade->toSql();
        $profitableTradeBinding = $profitable_trade->getBindings();
        $profitableTradeQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $profitable_trade_sql), array_map('addslashes', $profitableTradeBinding));
        $profitable_trade = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => [
                'sql' => "$profitableTradeQuery",
            ]
        ]));
        $profitable_trade = (isset($profitable_trade->data[0]->total) && $profitable_trade->data[0]->total != null) ? $profitable_trade->data[0]->total : 0;
        // Calculate profit percentage and loss percentage
        if ($total_trades) {
            $best_trade_percent = $max_profit;
            $profit_percentage = ($total_profit / $total_trades) * 100;
            $loss_percentage = ($total_loss / $total_trades) * 100;
        } else {
            $best_trade_percent = 0;
            $profit_percentage = 0;
            $loss_percentage = 0;
        }

        // count total copier
        $total_copier = CopySlave::where('master', (int)$request->account)->selectRaw('COUNT(*) as total');
        $total_copier_sql = $total_copier->toSql();
        $totalCopierBinding = $total_copier->getBindings();
        $totalCopierQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $total_copier_sql), array_map('addslashes', $totalCopierBinding));
        $total_copier = json_decode($this->copy_api->apiCall([
            'command' => 'Custom',
            'data' => [
                'sql' => "$totalCopierQuery",
            ]
        ]));
        $total_copier = (isset($total_copier->data[0]->total) && $total_copier->data[0]->total != null) ? $total_copier->data[0]->total : 0;

        // get account leverage
        $trading_account = TradingAccount::where('account_number', $request->account)->first();
        // calculate with us
        $current_time_stamp = \Carbon\Carbon::now();
        $with_us = $current_time_stamp->diffInDays($copy_user[0]->created_at);

        return ([
            'total_trade' => $total_trades,
            'profit_percent' => round($profit_percentage, 4),
            'loss_percent' => round($loss_percentage, 4),
            'total_profit' => $total_profit,
            'total_loss' => $total_loss,
            'total_volume' => $total_volume,
            'commission' => isset($copy_user[0]) ? $copy_user[0]->share_profit : 0,
            'max_profit' => $max_profit,
            'max_loss' => $max_loss,
            'avg_profit' => round($avg_profit, 4),
            'avg_loss' => round($avg_loss, 4),
            'best_trade' => round($best_trade_percent, 4),
            'total_copier' => $total_copier,
            'min_deposit' => isset($copy_user[0]) ? $copy_user[0]->min_deposit : 0.00,
            'max_deposit' => isset($copy_user[0]) ? $copy_user[0]->max_deposit : 0.00,
            'leverage' => ($trading_account) ? '1:' . $trading_account->leverage : '0:0',
            'with_us' => $with_us,
        ]);
    }
    // get monthly doughnut chart
    public function monthly_doughnut(Request $request)
    {
        try {
            // filter only for fxcrm demo account
            // -------------------------------------------

            if (($request->input('account') == 97900159 || $request->input('account') == 98831808 || $request->input('account') == 98832171) && strtolower(config('app.name')) === 'fxcrm') {
                return $this->demo_monthly_doughnut($request);
            }
            // -----------------------------------------------------------
            // last month trades bgoup by created at and symbol
            $copy_trade = CopyTrade::where('Login', $request->account)->groupBy('Symbol')->selectRaw('SUM(Profit) as profit, Symbol');
            $copy_trade_sql = $copy_trade->toSql();
            $copyTradeBinding = $copy_trade->getBindings();
            $copyTradeQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $copy_trade_sql), array_map('addslashes', $copyTradeBinding));
            $copy_trade = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$copyTradeQuery",
                ]
            ]));

            $copy_trade = (isset($copy_trade->data)) ? $copy_trade->data : [];
            $labels = $values = [];
            foreach ($copy_trade as $value) {
                $labels[] = $value->Symbol;
                $values[] = $value->profit;
            }

            if (empty($copy_trade)) {
                return response()->json([
                    'labels' => ['No Trade'],
                    'chartData' => [100],
                    'backgroundColor' => ['#3A416F', '#2152ff',  '#f53939', '#cb0c9f', '#a8b8d8']
                ]);
            }

            return response()->json([
                'labels' => $labels,
                'chartData' => $values,
                'backgroundColor' => ['#2152ff', '#3A416F', '#f53939', '#a8b8d8', '#cb0c9f']
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'labels' => ['No Trade'],
                'chartData' => [100],
                'backgroundColor' => ['#3A416F', '#2152ff',  '#f53939', '#cb0c9f', '#a8b8d8']
            ]);
        }
    }
    // demo monthly donought for fxcrm
    // ------------------------------------------------------------------
    public function demo_monthly_doughnut(Request $request)
    {
        try {
            $copy_trade = CopyTrade::where('Login', $request->account)->groupBy('Symbol')->selectRaw('SUM(Profit) as profit, Symbol')->get();

            $labels = $values = [];
            foreach ($copy_trade as $value) {
                $labels[] = $value->Symbol;
                $values[] = $value->profit;
            }

            if (empty($copy_trade)) {
                return response()->json([
                    'labels' => ['No Trade'],
                    'chartData' => [100],
                    'backgroundColor' => ['#3A416F', '#2152ff',  '#f53939', '#cb0c9f', '#a8b8d8']
                ]);
            }

            return response()->json([
                'labels' => $labels,
                'chartData' => $values,
                'backgroundColor' => ['#2152ff', '#3A416F', '#f53939', '#a8b8d8', '#cb0c9f']
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'labels' => ['No Trade'],
                'chartData' => [100],
                'backgroundColor' => ['#3A416F', '#2152ff',  '#f53939', '#cb0c9f', '#a8b8d8']
            ]);
        }
    }
    // daily doughnut charts
    public function daily_doughnut(Request $request)
    {
        try {
            $last_months = Carbon::now()->subDays(30);
            // last month trades bgoup by created at and symbol
            // where('created_at', '>=', $last_months)
            $copy_trade = CopyTrade::where('Login', $request->account)->groupBy('Symbol')->selectRaw('SUM(Profit) as profit, Symbol');
            $copy_trade_sql = $copy_trade->toSql();
            $copyTradeBinding = $copy_trade->getBindings();
            $copyTradeQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $copy_trade_sql), array_map('addslashes', $copyTradeBinding));
            $copy_trade = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$copyTradeQuery",
                ]
            ]));

            $copy_trade = (isset($copy_trade->data)) ? $copy_trade->data : [];
            $labels = $values = [];
            foreach ($copy_trade as $value) {
                $labels[] = $value->Symbol;
                $values[] = $value->profit;
            }

            if (empty($copy_trade)) {
                return response()->json([
                    'labels' => ['No Trade'],
                    'chartData' => [100],
                    'backgroundColor' => ['#3A416F', '#2152ff',  '#f53939', '#cb0c9f', '#a8b8d8']
                ]);
            }

            return response()->json([
                'labels' => $labels,
                'chartData' => $values,
                'backgroundColor' => ['#2152ff', '#3A416F', '#f53939', '#a8b8d8', '#cb0c9f']
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'labels' => ['No Trade'],
                'chartData' => [100],
                'backgroundColor' => ['#3A416F', '#2152ff',  '#f53939', '#cb0c9f', '#a8b8d8']
            ]);
        }
    }
    // hourly_doughnut
    public function hourly_doughnut(Request $request)
    {
        $current_date = Carbon::now()->subDay();
        try {
            $last_months = Carbon::now()->subMonths(1);

            // last month trades bgoup by created at and symbol
            // where('created_at', '>=', $last_months)
            $copy_trade = CopyTrade::where('Login', $request->account)->groupBy('Symbol')->selectRaw('SUM(Profit) as profit, Symbol');
            $copy_trade_sql = $copy_trade->toSql();
            $copyTradeBinding = $copy_trade->getBindings();
            $copyTradeQuery = vsprintf(str_replace('?', "'" . '%s' . "'", $copy_trade_sql), array_map('addslashes', $copyTradeBinding));
            $copy_trade = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "$copyTradeQuery",
                ]
            ]));

            $copy_trade = (isset($copy_trade->data)) ? $copy_trade->data : [];
            $labels = $values = [];
            foreach ($copy_trade as $value) {
                $labels[] = $value->Symbol;
                $values[] = $value->profit;
            }

            if (empty($copy_trade)) {
                return response()->json([
                    'labels' => ['No Trade'],
                    'chartData' => [100],
                    'backgroundColor' => ['#3A416F', '#2152ff',  '#f53939', '#cb0c9f', '#a8b8d8']
                ]);
            }

            return response()->json([
                'labels' => $labels,
                'chartData' => $values,
                'backgroundColor' => ['#2152ff', '#3A416F', '#f53939', '#a8b8d8', '#cb0c9f']
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'labels' => ['No Trade'],
                'chartData' => [100],
                'backgroundColor' => ['#3A416F', '#2152ff',  '#f53939', '#cb0c9f', '#a8b8d8']
            ]);
        }
    }
    // get data for monthly line chart
    public function monthly_line_chart(Request $request)
    {
        try {
            $account = (int)$request->account;
            $month = [];
            $copy_trades = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "
                        SELECT
                            DATE_FORMAT(reference_months.month, '%b %Y') AS month,
                            COUNT(copy_trades.id) AS total_trades,
                            SUM(CASE WHEN copy_trades.Profit > 0 THEN copy_trades.Profit ELSE 0 END) AS profit,
                            SUM(CASE WHEN copy_trades.Profit < 0 THEN copy_trades.Profit ELSE 0 END) AS loss
                        FROM
                            (
                                SELECT DATE_SUB(NOW(), INTERVAL n MONTH) AS month
                                FROM (
                                    SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
                                    UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                    UNION SELECT 10 UNION SELECT 11
                                ) AS numbers
                            ) AS reference_months
                        LEFT JOIN copy_trades ON DATE_FORMAT(reference_months.month, '%Y-%m') = DATE_FORMAT(copy_trades.created_at, '%Y-%m') AND copy_trades.Login = $account
                        GROUP BY DATE_FORMAT(reference_months.month, '%b %Y')
                        ORDER BY DATE_FORMAT(reference_months.month, '%Y-%m')
                    "
                ]
            ]));
            $copy_trade = isset($copy_trades->data) ? $copy_trades->data : [];

            // last 12 months slave
            $copy_slaves = json_decode($this->copy_api->apiCall([
                'command' => 'Custom',
                'data' => [
                    'sql' => "
                        SELECT
                            DATE_FORMAT(reference_months.month, '%b %Y') AS month,
                            COUNT(copy_slaves.id) AS total_slaves
                        FROM
                            (
                                SELECT DATE_SUB(NOW(), INTERVAL n MONTH) AS month
                                FROM (
                                    SELECT 0 AS n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
                                    UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9
                                    UNION SELECT 10 UNION SELECT 11
                                ) AS numbers
                            ) AS reference_months
                        LEFT JOIN copy_slaves ON DATE_FORMAT(reference_months.month, '%Y-%m') = DATE_FORMAT(copy_slaves.created_at, '%Y-%m') AND copy_slaves.master = $account
                        GROUP BY DATE_FORMAT(reference_months.month, '%b %Y')
                        ORDER BY DATE_FORMAT(reference_months.month, '%Y-%m')
                    "
                ]
            ]));

            $copy_slave = isset($copy_slaves->data) ? $copy_slaves->data : [];
            $trades = $slaves = [];
            // seperate trade array
            foreach ($copy_trade as $value) {
                array_push($trades, $value->total_trades);
            }
            // seperate slave array
            foreach ($copy_slave as $value) {
                array_push($month, $value->month);
                array_push($slaves, $value->total_slaves);
            }
            // for excrm demo
            if (($account === 97900159 || $account == 98831808 || $account == 98832171) && strtolower(config('app.name')) === 'fxcrm') {
                $trades = [20, 35, 50, 40, 300, 220, 500, 250, 400, 230, 500, 400];
                $slaves = [20, 25, 30, 90, 40, 140, 290, 290, 340, 230, 400, 300];
            }
            return response()->json([
                'trade_per_month' => $trades,
                'copier_per_month' => $slaves,
                'months' => $month
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'labels' => ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                'chartData' => [20, 35, 50, 40, 300, 220, 500, 250, 400, 230, 500],
                'backgroundColor' => ['#2152ff', '#3A416F', '#f53939', '#a8b8d8', '#cb0c9f']
            ]);
        }
    }
    // add a slave account to master
    // copy master as slave
    public function copy_master(Request $request)
    {
        try {
            $ruls = [
                'account' => 'required|numeric',
                'master_account' => 'required|numeric',
                'symbol' => 'required',
                'max_trade' => 'required|numeric',
                'max_volume' => 'required|numeric',
                'min_volume' => 'required|numeric',
                'allocation' => 'required|numeric',
            ];
            $validator = Validator::make($request->all(), $ruls);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            $pammService = new CopyApiService();
            
            $cent_account = TradingAccount::select()->where('account_number', $request->account)->where('group_id', 10)->first();
            if (isset($cent_account)) {
                return Response::json(['success' => false, 'message' => 'Cent account not allowed!']);
            }
            
            // check account available or not
            $trading_account = TradingAccount::where('account_number', $request->account)->first();
            if ($trading_account) {
                //GET master settings
                $master_settings = json_decode($this->copy_api->apiCall([
                    'command' => 'Custom',
                    'data' => [
                        'sql' => "SELECT * FROM copy_users WHERE account = '$request->master_account'",
                    ]
                ]));
                $master_settings = isset($master_settings->data) ? $master_settings->data : [];
                if (empty($master_settings)) {
                    return Response::json([
                        'status' => false,
                        'message' => 'Settings not loaded currntly, plase try again later'
                    ]);
                }
                $master_settings = $master_settings[0];
                // var_dump($master_settings);
                // return $master_settings->min_deposit;
                // check min deposit
                $total_deposit = InternalTransfer::where('account_id', $trading_account->id)->sum('amount');
                if ($master_settings->min_deposit != 0 && $master_settings->min_deposit > $total_deposit) {
                    return Response::json([
                        'status' => false,
                        'message' => 'You can not copy this master account, for copy this account you need minimum ' . $master_settings->min_deposit . ' $ deposit',
                    ]);
                }
                // check max deposit
                if ($master_settings->max_deposit != 0 && $total_deposit > $master_settings->max_deposit) {
                    return Response::json([
                        'status' => false,
                        'message' => 'You can not copy this master account, your total deposit should less than ' . $master_settings->max_deposit . ' $ ',
                    ]);
                }
                // // check master from copy slave
                // return $master_count = json_decode($this->copy_api->apiCall([
                //     'command' => 'Custom',
                //     'data' => [
                //         'sql' => "SELECT * FROM copy_slaves WHERE master = '$request->master_account'",
                //     ]
                // ]));

                
                // $pamm_settings = PammSetting::select()->first();
                // if (isset($pamm_settings->pamm_requirement_status) && $pamm_settings->pamm_requirement_status == 1) {
                //     $master_count = json_decode($this->copy_api->apiCall([
                //         'command' => 'CountMaster',
                //         'data' => [
                //             'master' => $request->master_account,
                //             'slave' => $request->account
                //         ]
                //     ]));
                //     if ($pamm_settings->master_limit != 0 && $master_count->master >= $pamm_settings->master_limit) {
                //         return Response::json([
                //             'success' => false,
                //             'message' => 'Master Limit Exceeded!'
                //         ]);
                //     }
                //     if ($pamm_settings->slave_limit != 0) {
                //         if ($master_count->copy_slave >= $pamm_settings->slave_limit) {
                //             return Response::json([
                //                 'success' => false,
                //                 'message' => 'Slave Limit Exceeded!'
                //             ]);
                //         }
                //     }
                // }
                $result = $pammService->apiCall('add/slave', [
                    'master' => $request->master_account,
                    'slave' => $request->account,
                    'allocation' => $request->allocation,
                    'type' => 'pamm',
                    'max_number_of_trade' => $request->max_trade,
                    'max_trade_volume' => $request->max_volume,
                    'min_trade_volume' => $request->min_volume,
                    'ts_loss' => 0,
                    'symbols' => $request->symbol
                ]);
                // Decode the result if it's a JSON string
                if (is_string($result)) {
                    $result = json_decode($result);
                }

                // Check if the result is a valid object and has the 'status' property
                if (is_object($result) && isset($result->status)) {
                    if ($result->status) {
                        return Response::json([
                            'status' => true,
                            'message' => "Congratulations! You successfully copied this trade.",
                        ]);
                    } else {
                        return Response::json([
                            'status' => false,
                            'message' => $result->message ?? "An error occurred.",
                        ]);
                    }
                }

                // Handle cases where the response is not as expected
                return Response::json([
                    'status' => false,
                    'message' => "Invalid response from the API.",
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Account not in our system'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, please contact for support'
            ]);
        }
    }
    // ------------------------------------------------------------
    // month growth chart------------------------
    public function monthly_growth(Request $request)
    {
        if ($request->input('account') == 97900159 && strtolower(config('app.name')) === 'fxcrm') {
            return Response::json($this->demo_monthly_growth($request));
        }
        $monthsWithYear = [];

        // Get the current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Loop through the previous 12 months
        for ($i = 0; $i < 12; $i++) {
            // Calculate the month and year for the current iteration
            $month = $currentMonth - $i;
            $year = $currentYear;

            // If the month becomes less than 1, adjust the month and year accordingly
            if ($month < 1) {
                $month += 12;
                $year--;
            }

            // Add the month and year to the array
            $monthsWithYear[] = date('M Y', strtotime("$year-$month-01"));
        }
        return Response::json(
            [
                'total_profits' => [20, 35, 50, 40, 300, 220, 500, 250, 400, 230, 500, 300, 256, 365],
                'months' => array_reverse($monthsWithYear),
                'total_volumes' => [50, 40, 300, 220, 500, 250, 400, 230, 500, 200, 300, 50, 180]
            ]
        );
    }
    // -----------------------------------------------------------
    // monthly growth chart only for fxrm demo account
    public function demo_monthly_growth(Request $request)
    {
        try {
            $account = (int)$request->account;

            $last12Months = [];
            $currentDate = Carbon::now();
            $endDate = $currentDate->copy()->endOfMonth();
            $startDate = $currentDate->subMonths(11)->startOfMonth();

            // Fetch the data and calculate the sum of profit
            $results = CopyTrade::select(
                DB::raw('SUM(Profit) as total_profit'),
                DB::raw('SUM(Volume) as total_volume'),
                DB::raw('DATE_FORMAT(OpenTime, "%Y-%m") as month')
            )->where('Login', $account)
                ->whereBetween('OpenTime', [$startDate, $endDate])
                ->groupBy('month')
                ->get('total_profit', 'month', 'volume');
            // ->toArray();
            foreach ($results as $value) {
                $last12Months[$value->month]['total_profit'] = $value->total_profit;
                $last12Months[$value->month]['total_volume'] = ($value->total_volume / 1000);
            }

            // Prepare separate arrays for total profit, month, and volume
            $totalProfits = array_column($last12Months, 'total_profit');
            $months = array_keys($last12Months);
            $totalVolumes = array_column($last12Months, 'total_volume');

            return [
                'total_profits' => $totalProfits,
                'months' => $months,
                'total_volumes' => $totalVolumes
            ];
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'labels' => ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                'chartData' => [20, 35, 50, 40, 300, 220, 500, 250, 400, 230, 500],
                'backgroundColor' => ['#2152ff', '#3A416F', '#f53939', '#a8b8d8', '#cb0c9f']
            ]);
        }
    }
}
