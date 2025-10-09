<?php

namespace App\Http\Controllers\admins\socialTrade;

use App\Http\Controllers\Controller;
use App\Models\BonusFor;
use App\Models\BonusUser;
use App\Models\Credit;
use App\Models\ManagerUser;
use App\Models\User;
use App\Models\Traders\MasterProfit;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MasterProfitShareController extends Controller
{
    public function masterProfitShareView(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->masterProfitShareReportDT($request);
        }
        return view('admins.socialTrade.master-profit-share-report');
    }

    public function masterProfitShareReportDT($request)
    {
        try {
            // data from credit table
            $columns = [
                'slave_order', 
                'slave', 
                'slave_profit', 
                'master', 
                'profit_percent', 
                'created_at', 
                'status', 
                'amount'
            ];
            $result = MasterProfit::select(
                'master_profits.slave_order',
                'master_profits.slave',
                'master_profits.slave_profit',
                'master_profits.master',
                'master_profits.profit_percent',
                'master_profits.amount',
                'master_profits.created_at',
                'master_profits.status'
            )
            ->join('trading_accounts', 'master_profits.master', 'trading_accounts.account_number')
            ->join('users', 'trading_accounts.user_id', 'users.id');
        
        
            // Apply filters
            if ($request->filled('slave_order')) {
                $result = $result->where('master_profits.slave_order', $request->input('slave_order'));
            }
            if ($request->filled('slave')) {
                $result = $result->where('master_profits.slave', $request->input('slave'));
            }
            if ($request->filled('master')) {
                $result = $result->where('master_profits.master', $request->input('master'));
            }
            if ($request->filled('slave_profit')) {
                $result = $result->where('master_profits.slave_profit', $request->input('slave_profit'));
            }
            if ($request->filled('from')) {
                $result = $result->whereDate('master_profits.created_at', '>=', $request->input('from'));
            }
            if ($request->filled('to')) {
                $result = $result->whereDate('master_profits.created_at', '<=', $request->input('to'));
            }
            if ($request->filled('min_profit_percent')) {
                $result = $result->where('master_profits.profit_percent', '>=', $request->input('min_profit_percent'));
            }
            if ($request->filled('max_profit_percent')) {
                $result = $result->where('master_profits.profit_percent', '<=', $request->input('max_profit_percent'));
            }
            if ($request->filled('min')) {
                $result = $result->where('master_profits.amount', '>=', $request->input('min'));
            }
            if ($request->filled('max')) {
                $result = $result->where('master_profits.amount', '<=', $request->input('max'));
            }
            if ($request->filled('status')) {
                $result = $result->where('master_profits.status', $request->input('status'));
            }
            
            // $columns = ['users.email', 'credits.type', 'credits.trading_account', 'credits.expire_date', 'credits.created_at', 'credits.credited_by', 'credits.amount'];
            // $result = Credit::select(
            //     'credits.amount',
            //     'credits.type',
            //     'credits.expire_date',
            //     'trading_accounts.account_number',
            //     'users.email',
            //     'credits.created_at',
            //     'credited_by'
            // )->join('trading_accounts', 'credits.trading_account', '=', 'trading_accounts.account_number')
            //     ->join('users', 'trading_accounts.user_id', '=', 'users.id');
            // // checking login is manger
            // if (auth()->user()->type === 'manager') {
            //     $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
            //     $result = $result->whereIn('users.id', $users_id);
            // }

            // //----------------------------------------------------------------------------------
            // //Filter Start
            // //----------------------------------------------------------------------------------
            // // filter by type
            // if ($request->type != "") {
            //     $result = $result->where('credits.type', $request->type);
            // }
            // //Filter By Trader Name / Email /Phone /Country
            // if ($request->info != "") {
            //     $trader_info = $request->info;
            //     $user_id = User::select('countries.name')->where(function ($query) use ($trader_info) {
            //         $query->where('users.name', 'LIKE', '%' . $trader_info . '%')
            //             ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
            //             ->orWhere('phone', 'LIKE', '%' . $trader_info . '%')
            //             ->orWhere('countries.name', 'LIKE', '%' . $trader_info . '%');
            //     })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            //         ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
            //         ->select('users.id as user_id')->get()->pluck('user_id');
            //     $result = $result->whereIn('users.id', $user_id);
            // }
            // //Filter By IB Name / Email /Phone /Country
            // if ($request->ib_info != "") {
            //     $ib = $request->ib_info;
            //     $user_id = User::select('countries.name')->where('users.type', 4)->where(function ($query) use ($ib) {
            //         $query->where('users.name', 'LIKE', '%' . $ib . '%')
            //             ->orWhere('users.email', 'LIKE', '%' . $ib . '%')
            //             ->orWhere('phone', 'LIKE', '%' . $ib . '%')
            //             ->orWhere('countries.name', 'LIKE', '%' . $ib . '%');
            //     })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            //         ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
            //         ->select('users.id as user_id')->get()->pluck('user_id');
            //     $result = $result->whereIn('users.id', $user_id);
            // }
            // // filter by min amount
            // if ($request->min != "") {
            //     $result = $result->where("amount", '>=', $request->min);
            // }
            // // filter by max amount
            // if ($request->max != "") {
            //     $result = $result->where("amount", '<=', $request->max);
            // }
            // // filter by credit date from
            // if ($request->from != "") {
            //     $result = $result->whereDate('credits.created_at', '>=', $request->from);
            // }
            // // filter by credit date to
            // if ($request->to != "") {
            //     $result = $result->whereDate('credits.created_at', '<=', $request->to);
            // }
            // //Filter By Account Number
            // if ($request->account_number != "") {
            //     $result = $result->where('trading_accounts.account_number', '=', $request->account_number);
            // }
            // //Filter by account manager desk manager
            // if ($request->manager_info != "") {
            //     $manager = $request->manager_info;
            //     $manager_id = User::select('id')
            //         ->where(function ($query) use ($manager) {
            //             $query->where('name', 'LIKE', '%' . $manager . '%')
            //                 ->orWhere('email', 'LIKE', '%' . $manager . '%')
            //                 ->orWhere('phone', 'LIKE', '%' . $manager . '%');
            //         })->get()->pluck('id');
            //     $users_id = ManagerUser::select('user_id')->where('manager_id', $manager_id)->get()->pluck('user_id');
            //     $result = $result->whereIn('users.id', $users_id);
            // }
            /*<-------filter search script End here------------->*/

            $count = $result->count();
            $total_amount = $result->sum('amount');
            $result = $result->orderBy($columns[$request->order[0]['column']], $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();

            $data = array();

            foreach ($result as $value) {
                if ($value->status == 'pending') {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                } elseif ($value->status == 'credited') {
                    $status = '<span class="bg-light-success badge badge-success">Credited</span>';
                } else {
                    $status = '<span class="bg-light-danger badge badge-danger">'.ucwords($value->status).'</span>';
                }
                $data[] = [
                    'slave_order' => ucfirst($value->slave_order),
                    'slave_login' => ucfirst($value->slave),
                    'slave_profit' => ucfirst($value->slave_profit),
                    'master_login' => ucfirst($value->master),
                    'profit_percent' => ucfirst($value->profit_percent),
                    'share_time' => date('d M y, h:i A', strtotime($value->created_at)) ?? "---",
                    'status' => $status,
                    'amount' => '$' . $value->amount
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total_amount' => round($total_amount, 2),
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'total_amount' => 0,
                'data' => []
            ]);
        }
    }
}
