<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\BonusFor;
use App\Models\BonusUser;
use App\Models\Credit;
use App\Models\ManagerUser;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserBonusController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:bonus report"]);
        $this->middleware(["role:reports"]);
        // system module control
        $this->middleware(AllFunctionService::access('reports', 'admin'));
        $this->middleware(AllFunctionService::access('bonus_report', 'admin'));
    }
    public function bonusReport(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->bonusReportDT($request);
        }
        return view('admins.reports.bonus-report');
    }

    public function bonusReportDT($request)
    {
        try {
            // data from credit table
            $columns = ['users.email', 'credits.type', 'credits.trading_account', 'credits.expire_date', 'credits.created_at', 'credits.credited_by', 'credits.amount'];
            $result = Credit::select(
                'credits.amount',
                'credits.type',
                'credits.expire_date',
                'trading_accounts.account_number',
                'users.email',
                'credits.created_at',
                'credited_by'
            )->join('trading_accounts', 'credits.trading_account', '=', 'trading_accounts.account_number')
                ->join('users', 'trading_accounts.user_id', '=', 'users.id');
            // checking login is manger
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
                $result = $result->whereIn('users.id', $users_id);
            }

            //----------------------------------------------------------------------------------
            //Filter Start
            //----------------------------------------------------------------------------------
            // filter by type
            if ($request->type != "") {
                $result = $result->where('credits.type', $request->type);
            }
            //Filter By Trader Name / Email /Phone /Country
            if ($request->info != "") {
                $trader_info = $request->info;
                $user_id = User::select('countries.name')->where(function ($query) use ($trader_info) {
                    $query->where('users.name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $trader_info . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }
            //Filter By IB Name / Email /Phone /Country
            if ($request->ib_info != "") {
                $ib = $request->ib_info;
                $user_id = User::select('countries.name')->where('users.type', 4)->where(function ($query) use ($ib) {
                    $query->where('users.name', 'LIKE', '%' . $ib . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $ib . '%')
                        ->orWhere('phone', 'LIKE', '%' . $ib . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $ib . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }
            // filter by min amount
            if ($request->min != "") {
                $result = $result->where("amount", '>=', $request->min);
            }
            // filter by max amount
            if ($request->max != "") {
                $result = $result->where("amount", '<=', $request->max);
            }
            // filter by credit date from
            if ($request->from != "") {
                $result = $result->whereDate('credits.created_at', '>=', $request->from);
            }
            // filter by credit date to
            if ($request->to != "") {
                $result = $result->whereDate('credits.created_at', '<=', $request->to);
            }
            //Filter By Account Number
            if ($request->account_number != "") {
                $result = $result->where('trading_accounts.account_number', '=', $request->account_number);
            }
            //Filter by account manager desk manager
            if ($request->manager_info != "") {
                $manager = $request->manager_info;
                $manager_id = User::select('id')
                    ->where(function ($query) use ($manager) {
                        $query->where('name', 'LIKE', '%' . $manager . '%')
                            ->orWhere('email', 'LIKE', '%' . $manager . '%')
                            ->orWhere('phone', 'LIKE', '%' . $manager . '%');
                    })->get()->pluck('id');
                $users_id = ManagerUser::select('user_id')->where('manager_id', $manager_id)->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $users_id);
            }
            /*<-------filter search script End here------------->*/

            $count = $result->count();
            $total_amount = $result->sum('amount');
            $result = $result->orderBy($columns[$request->order[0]['column']], $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();

            $data = array();

            foreach ($result as $value) {
                $type = '';
                if (strtolower($value->type) === 'add') {
                    $type = '<span class="badge badge-success bg-light-success">Add</span>';
                } elseif (strtolower($value->type) === 'deduct') {
                    $type = '<span class="badge badge-warning bg-light-warning">Deduct</span>';
                }
                $data[] = [
                    'email' => $value->email,
                    'type' => $type,
                    'account' => $value->account_number,
                    'credit_expire' => date('d F y, h:i A', strtotime($value->expire_date)),
                    'credit_date' => date('d F y, h:i A', strtotime($value->created_at)),
                    'created_by' => AllFunctionService::user_email($value->credited_by),
                    'amount' =>$value->amount,
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
