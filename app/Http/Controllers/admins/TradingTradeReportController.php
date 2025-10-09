<?php

namespace App\Http\Controllers\admins;

// set_time_limit(120);

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\IB;
use App\Models\ComTrade;
use App\Models\CopySymbol;
use App\Models\ManagerUser;
use App\Models\Mt5Trade;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\export\ExportService;
use App\Services\IbService;
use App\Services\systems\PlatformService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TradingTradeReportController extends Controller
{
    private $prefix;
    public function __construct()
    {
        $this->middleware(["role:trading trade report"]);
        $this->middleware(["role:manage trade"]);
        $this->prefix = DB::getTablePrefix();
    }
    // basic view--------------------------------------
    public function trade_reports(Request $request)
    {
        $groups = ClientGroup::all();
        $symbol = CopySymbol::all();
        return view('admins.manage-trade.trading-trade-report', ['groups' => $groups], ['symbol' => $symbol]);
    }

    // fetch datatable data------------------------------
    public function trade_reports_dt(Request $request)
    {
        try {
            // LAST MODIFIED BY REZA | 21-06-2023
            // DONT MODIY WITHOUT PERMISSION
            $result = "";
            if (strtolower(PlatformService::get_platform()) == "mt4") {
                // for mt4 trades
                $columns = ['TICKET', 'LOGIN', 'user_id', 'user_id', 'SYMBOL', 'PROFIT', 'OPEN_TIME', 'CLOSE_TIME', 'VOLUME'];
                $orderby = $columns[isset($request->order[0]['column']) ? $request->order[0]['column'] : 6];
                $result = DB::connection('alternate')->table('MT4_TRADES')
                    ->join($this->prefix . 'trading_accounts', 'MT4_TRADES.LOGIN', '=', $this->prefix . 'trading_accounts.account_number');
            } else {
                // for mt5 trades
                $columns = ['TICKET', 'LOGIN', 'user_id', 'user_id', 'SYMBOL', 'PROFIT', 'OPEN_TIME', 'CLOSE_TIME', 'VOLUME'];
                $orderby = $columns[isset($request->order[0]['column']) ? $request->order[0]['column'] : 6];
                $result = Mt5Trade::select()
                    ->join('trading_accounts', 'mt5_trades.LOGIN', '=', 'trading_accounts.account_number')
                    ->join('users', 'trading_accounts.user_id', '=', 'users.id');
            }
            $result = $result->select(
                'TICKET',
                'LOGIN',
                'email',
                'SYMBOL',
                'PROFIT',
                'OPEN_TIME',
                'CLOSE_TIME',
                'VOLUME',
                'user_id',
                'account_number'
            );
            // filter by login manager
            if (strtolower(auth()->user()->type) === 'manager') {
                $manager_client = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $result = $result->whereIn('trading_accounts.user_id', $manager_client);
            }
            //trading_account filter
            if ($request->trade_number != "") {
                $result = $result->where('TICKET', $request->trade_number);
            }

            // Filter by open time / close time
            if ($request->open_close_time != "") {
                if ($request->open_close_time === "open_time") {
                    $result = $result->where('CLOSE_TIME', '=', '1970-01-01 00:00:00');
                } elseif ($request->open_close_time === "close_time") {
                    $result = $result->where('CLOSE_TIME', '!=', '1970-01-01 00:00:00');
                }

                // Apply date filters if date_from or date_to is provided
                if (!empty($request->from_date)) {
                    $formatted_start = Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay()->format('Y-m-d');
                    $result = $result->where('CLOSE_TIME', '>=', $formatted_start);
                }
                if (!empty($request->to_date)) {
                    $formatted_end = Carbon::createFromFormat('Y-m-d', $request->to_date)->startOfDay()->format('Y-m-d');
                    $result = $result->where('CLOSE_TIME', '<=', $formatted_end);
                }
            } else {
                $result = $result->where('CLOSE_TIME', '!=', '1970-01-01 00:00:00');

                if (!empty($request->from_date)) {
                    $formatted_start = Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay()->format('Y-m-d');
                    $result = $result->where('CLOSE_TIME', '>=', $formatted_start);
                }
                if (!empty($request->to_date)) {
                    $formatted_end = Carbon::createFromFormat('Y-m-d', $request->to_date)->startOfDay()->format('Y-m-d');
                    $result = $result->where('CLOSE_TIME', '<=', $formatted_end);
                }
            }
            // filter by trading account-------------------------
            if ($request->trading_account != "") {
                $result = $result->where('LOGIN', $request->trading_account);
            }
            // filter by ib email
            if ($request->ib_email != "") {
                if (get_platform() == "mt4") {
                    $ibs = IB::where('users.email', $request->ib_email)
                        ->join('users', 'ib.ib_id', '=', 'users.id')->first();
                    if ($ibs) {
                        $result = $result->whereIn($this->prefix . 'users.id', $ibs->traders()->select('reference_id'));
                    } else {
                        $result = $result->where($this->prefix . 'users.id', null);
                    }
                } else {
                    $ibs = IB::where('users.email', $request->ib_email)
                        ->join('users', 'ib.ib_id', '=', 'users.id')->first();
                    if ($ibs) {
                        $result = $result->whereIn('users.id', $ibs->traders()->select('reference_id'));
                    } else {
                        $result = $result->where('users.id', null);
                    }
                }
            }
            // filter by trader email
            if ($request->trader_email != "") {
                // get user ID
                $client_id = User::where('email', 'like', '%' . $request->trader_email . '%')->select('id')->first();
                $client_id = $client_id->id;
                $result = $result->where($this->prefix . 'trading_accounts.user_id', $client_id);
            }
            //trading_account filter
            if ($request->trade_number != "") {
                $result = $result->where('TICKET', $request->trade_number);
            }
            // filter by group----------------
            if ($request->client_group != "") {
                if (get_platform() == "mt4") {
                    $result = $result->where('group_id', $request->client_group);
                } else {
                    $result = $result->where('group_id', $request->client_group);
                }
            }
            // filter by symbol----------------
            if ($request->copy_symbol != "") {
                if (get_platform() == "mt4") {
                    $result = $result->where('SYMBOL', $request->copy_symbol);
                } else {
                    $result = $result->where('SYMBOL', $request->copy_symbol);
                }
            }
            // filter by status----------
            if ($request->status != "") {
                if ($request->status == 'running') {
                    $result = $result->where('CLOSE_TIME', '=', '1970-01-01 00:00:00');
                } else if ($request->status == 'closed') {
                    $result = $result->where('CLOSE_TIME', '!=', '1970-01-01 00:00:00');
                }
            }
            // datatble return-----------------------------------

            // export operation
            if ($request->op === 'export') {
                $exp = new ExportService();
                $file_path = $exp->export_all([
                    'chunkSize' => 100,
                    'sql' => $result,
                    'file_name' => 'trade-report',
                    'file_type' => $request->file_type,
                    'offset' => $request->input('offset', 0),
                    'total_import' => $request->input('total_import', 0),
                ], ["$orderby", 'DESC'], ['TICKET', 'LOGIN', 'email']);
                return $file_path;
            }
            // <------count total rows
            $total_volume = $result->sum('VOLUME') / 100;
            // // Clone for total_volume
            // $totalVolumeQuery = clone $result;
            // $total_volume = $totalVolumeQuery->whereNot('group_id', 15)->sum('VOLUME') / 100;

            $total_closed   = $result->where('CLOSE_TIME', '!=', '1970-01-01 00:00:00')->where('CLOSE_TIME', '!=', '1970-01-01 12:00:00')->count();
            $count          = $result->count();
            // get all data 
            $result         = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            foreach ($result as $key => $value) {
                $profit = $value->PROFIT;
                $close_time = date('Y-m-d', strtotime($value->CLOSE_TIME));
                if ($close_time === "1970-01-01") {
                    $close_time = "Trade Running";
                    $profit = "---";
                } else {
                    $close_time = date('d M Y h:i:s A', strtotime($value->CLOSE_TIME));
                }
                $data[] = [
                    "trade"         => $value->TICKET,
                    "login"         => $value->LOGIN,
                    "trader_email"  => AllFunctionService::user_email($value->user_id),
                    "ib_email"      => IbService::instant_ib_email($value->user_id),
                    "symbol"        => $value->SYMBOL,
                    "profit"        => $profit,
                    "open_time"     => date('d M Y h:i:s A', strtotime($value->OPEN_TIME)),
                    "close_time"    => $close_time,
                    "volume"        => round(($value->VOLUME / 100), 2),
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
                'total' => [$count, $total_closed, $total_volume]
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'total' => [0, 0, 0]
            ]);
        }
    }
    // export csv file
    public function export(Request $request)
    {
        return Response::download($request->file_path, 'export.csv')->deleteFileAfterSend(true);
    }
}
