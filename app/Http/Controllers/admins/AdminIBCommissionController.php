<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\TradingAccount;
use App\Models\IB;
use App\Models\ComTrade;
use App\Models\IbGroup;
use App\Models\IbIncome;
use App\Models\CommissionStatus;
use App\Models\IbSetup;
use App\Models\ManagerUser;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\systems\PlatformService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PhpParser\Node\Stmt\Return_;

class AdminIBCommissionController extends Controller
{
    private $prefix;
    public function __construct()
    {
        $this->prefix = DB::getTablePrefix();
    }
    public function ibCommission()
    {
        $ib_group = IbGroup::select('group_name', 'id')->where('status', 1)->get();
        $ib_level = IbSetup::select('ib_level')->first();
        $ib_level = $ib_level->ib_level;
        return view('admins.reports.admin-ib-commisson-report', compact('ib_group', 'ib_level'));
    }

    public function ibCommissionRP(Request $request)
    {
        try {
            // DONT MODIFY THIS CODE WITHDOUT PERMISSION
            // LAST MODIFIED | 22-06-2023
            // THIS CODE FOR BOTH MT4 | MT4
            $trade_table = '';
            $columns = [
                $this->prefix . 'ib_incomes.trader_id',
                $this->prefix . 'ib_incomes.ib_id',
                $this->prefix . 'ib_incomes.trading_account',
                $this->prefix . 'ib_incomes.order_num',
                // 'com_ticket',
                $this->prefix . 'ib_incomes.symbol',
                'OPEN_TIME',
                'CLOSE_TIME',
                $this->prefix . 'ib_incomes.com_level',
                $this->prefix . 'ib_incomes.com_level',
                $this->prefix . 'ib_incomes.volume',
                $this->prefix . 'ib_incomes.amount'
            ];
            $orderby = $columns[$request->order[0]['column']];
            $trade_table = $this->prefix . 'mt5_trades';
            if (strtolower(PlatformService::get_platform()) === 'mt4') {
                $trade_table = 'MT4_TRADES';
                $result = DB::connection('alternate')->table($this->prefix . 'ib_incomes')->select(
                    $this->prefix . 'ib_incomes.trader_id',
                    $this->prefix . 'ib_incomes.ib_id',
                    $this->prefix . 'ib_incomes.trading_account',
                    $this->prefix . 'ib_incomes.order_num',
                    // $this->prefix . 'ib_incomes.order_num as com_ticket',
                    $this->prefix . 'ib_incomes.symbol',
                    $this->prefix . 'ib_incomes.volume',
                    $this->prefix . 'ib_incomes.amount',
                    $this->prefix . 'ib_incomes.com_level',
                    $trade_table . '.OPEN_TIME',
                    $trade_table . '.CLOSE_TIME'
                )
                    ->join("$trade_table", $this->prefix . 'ib_incomes.order_num', '=', "$trade_table" . '.TICKET');
            } else {
                $trade_table = $this->prefix . 'mt5_trades';
                $result = DB::connection('alternate')->table($this->prefix . 'ib_incomes')->select(
                    $this->prefix . 'ib_incomes.trader_id',
                    $this->prefix . 'ib_incomes.ib_id',
                    $this->prefix . 'ib_incomes.trading_account',
                    // $this->prefix . 'ib_incomes.order_num as com_ticket',
                    $this->prefix . 'ib_incomes.order_num',
                    $this->prefix . 'ib_incomes.symbol',
                    $this->prefix . 'ib_incomes.volume',
                    $this->prefix . 'ib_incomes.amount',
                    $this->prefix . 'ib_incomes.com_level',
                    $trade_table . '.OPEN_TIME',
                    $trade_table . '.CLOSE_TIME'
                )
                    ->join("$trade_table", $this->prefix . 'ib_incomes.order_num', '=', "$trade_table" . '.TICKET');
            }

            // check login is manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $result = $result->whereIn('ib_incomes.ib_id', $users_id);
            }
            /*<-------filter search script start here------------->*/

            // filter by trader name | email
            if (trim($request->trader_info) != "") {
                $trader_info = $request->trader_info;
                $trader = User::select('id')->where(function ($query) use ($trader_info) {
                    $query->where('name', 'LIKE', '%' . trim($trader_info) . '%')
                        ->orWhere('email', 'LIKE', '%' . trim($trader_info) . '%')
                        ->orWhere('phone', 'LIKE', '%' . trim($trader_info) . '%');
                })->get()->pluck('id');
                $result = $result->whereIn($this->prefix . 'ib_incomes.trader_id', $trader);
            }

            // filter by ib name | email
            if (trim($request->ib_info) != "") {
                $ib_info = $request->ib_info;
                $ib = User::select('id')
                    ->where(function ($query) use ($ib_info) {
                        $query->where('name', 'LIKE', '%' . trim($ib_info) . '%')
                            ->orWhere('email', 'LIKE', '%' . trim($ib_info) . '%')
                            ->orWhere('phone', 'LIKE', '%' . trim($ib_info) . '%');
                    })
                    ->get()->pluck('id');
                if ($ib) {
                    $result = $result->whereIn($this->prefix . 'ib_incomes.ib_id', $ib);
                }
            }
            // filter by account number
            if ($request->trading_account != "") {
                $result = $result->where($this->prefix . 'ib_incomes.trading_account', $request->trading_account);
            }
            // filter by account number
            if ($request->ticket != "") {
                $result = $result->where($this->prefix . 'ib_incomes.order_num', $request->ticket);
            }

            // // filter by ticket
            // if ($request->ticket != '') {
            //     $result = $result->where("ib_incomes.order_num", $request->ticket);
            // }

            // filter by ib group
            if ($request->ib_group != "") {
                // find ib by ibgroup
                $ib_ids = User::where('ib_group_id', $request->ib_group)->select('id')->get()->pluck('id');
                $result = $result->whereIn($this->prefix . 'ib_incomes.ib_id', $ib_ids);
            }

            // filter by date from
            if ($request->from != '') {
                $time_method = $request->open_close;
                $fromDate = Carbon::parse($request->from)->toDateString();
                // FILTER BY OPEN OR CLOSE TIME
                if ($time_method != '') {
                    $result = $result->whereDate("$trade_table.$time_method", '>=', $fromDate);
                } else {
                    $result = $result->where(function ($q) use ($fromDate, $trade_table) {
                        $q->whereDate("$trade_table.OPEN_TIME", '>=', $fromDate)
                            ->orWhereDate("$trade_table.CLOSE_TIME", '>=', $fromDate);
                    });
                }
            }

            // filter by date to
            if ($request->to != '') {
                $by_time = $request->open_close;
                $date_to = $request->to;
                // FILTER BY OPEN OR CLOSE TIME
                if ($by_time != '') {
                    $result = $result->whereDate("$trade_table.$by_time", '<=', Carbon::parse($date_to));
                } else {
                    $result = $result->where(function ($q) use ($date_to, $trade_table) {
                        $q->whereDate("$trade_table.OPEN_TIME", '<=', Carbon::parse($date_to))
                            ->orWhereDate("$trade_table.CLOSE_TIME", '<=', Carbon::parse($date_to));
                    });
                }
            }

            // filter by ib level
            if ($request->ib_level != "") {
                $result = $result->where($this->prefix . 'ib_incomes.com_level', '=', $request->ib_level);
            }
            

            /*<-------filter search script End here------------->*/
            $count = $result->count();
            $allmost_total_commission = $result->sum($this->prefix . 'ib_incomes.amount');
            $total_volume = $result->sum($this->prefix . 'ib_incomes.volume');
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            // return $result;
            $data = array();
            foreach ($result as $row) {
                $com_status = CommissionStatus::where('ticket', $row->order_num)->select('status')->first();
                $ib_mail = User::select('email')->where('id', $row->ib_id)->first();

                if ($com_status->status == "CREDITED") {
                    $status = '<span class="bg-light-success badge badge-success">CREDITED</span>';
                } else {
                    $status = $com_status->status;
                }

                $data[] = [
                    'trader_mail'   => AllFunctionService::user_email($row->trader_id),
                    'ib_mail'       => ($ib_mail) ? $ib_mail->email : "No IB",
                    'trade_acc'     => $row->trading_account,
                    'ticket'        => $row->order_num,
                    'symbol'        =>  $row->symbol,
                    'open_time'     => '<span class="text-truncate">' . date('d-m-Y H:i:s', strtotime($row->OPEN_TIME)) . '</span>',
                    'close_time'    => '<span class="text-truncate">' . date('d-m-Y H:i:s', strtotime($row->CLOSE_TIME)) . '</span>',
                    'com_level'     => $row->com_level,
                    'status'        => $status,
                    'volume'        =>  round(($row->volume / 100), 2),
                    'amount'        =>  round(($row->amount), 3),
                ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total' => [
                    round(($total_volume / 100), 2), //=>total volume
                    $count, //=>total trades
                    round(($total_volume / 100), 2), //=>total commission volume
                    round($allmost_total_commission, 2), //=>total commission
                    round($allmost_total_commission, 2), //=>total commission/amount
                ],
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'total' => [
                    0, //=>total volume
                    0, //=>total trades
                    0, //=>total commission volume
                    0, //=>total commission
                    0, //=>total commission/amount
                ],
                'data' => []
            ]);
        }
    }
}
