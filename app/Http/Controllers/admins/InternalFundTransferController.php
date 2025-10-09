<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\IB;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Response;

class InternalFundTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:internal fund transfer"]);
        $this->middleware(["role:fund transfer"]);
        // system module control
        $this->middleware(AllFunctionService::access('fund_transfer', 'admin'));
        $this->middleware(AllFunctionService::access('internal_fund_transfer', 'admin'));
    }
    public function internaFundTransfer(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->fundTransferReportDT($request);
        }
        return view('admins.reports.internal-fund-transfer');
    }



    public function fundTransferReportDT($request)
    {
        try {
            $columns = ['name', 'email', 'account_number', 'platform', 'type', 'status', 'created_at', 'amount'];
            $orderby = $columns[$request->order[0]['column']];
            $result = InternalTransfer::select(
                'internal_transfers.id',
                'internal_transfers.user_id',
                'internal_transfers.type',
                'internal_transfers.status',
                'internal_transfers.created_at',
                'internal_transfers.amount',
                'users.id',
                'users.name',
                'users.email',
                'users.kyc_status',
                'trading_accounts.account_number',
                'trading_accounts.platform',
                'client_groups.server'
            )
                ->join('users', 'internal_transfers.user_id', '=', 'users.id')
                ->join('trading_accounts', 'internal_transfers.account_id', '=', 'trading_accounts.id')
                ->leftJoin('client_groups', 'trading_accounts.group_id', '=', 'client_groups.group_id');

            // -----------------------------------------Filter Start---------------------------------->
             // Filter By Approved Status
             if ($request->approved_status != "") {
                $result = $result->where("status", $request->approved_status);
            }
            // Filter By Method
            if ($request->method != "") {
                $result = $result->where("internal_transfers.type", $request->method);
            }
            // Filter By KYC Verification Status
            if ($request->verify_status != "") {
                $result = $result->where("users.kyc_status", $request->verify_status);
            }
            //Filter By Trader Name / Email /Phone /Country
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $user_id = User::select('countries.name')->where(function ($query) use ($trader_info) {
                    $query->where('users.name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $trader_info . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                $result = $result->whereIn('internal_transfers.user_id', $user_id);
            }
            //Filter By IB Name / Email /Phone /Country
            if ($request->ib_info != "") {
                $ib = $request->ib_info;
                $user_id = User::select('countries.name')->where('users.type', 4)->where(function ($query) use ($ib) {
                    $query->where('users.name', 'LIKE', '%' . $ib . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $ib . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $ib . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $ib . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                $trader_id = IB::where('ib_id',$user_id)->get()->pluck('reference_id');
                $result = $result->whereIn('internal_transfers.user_id', $trader_id);
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
                $result = $result->whereIn('internal_transfers.id', $users_id);
            }
            //Filter by trading account number
            if ($request->trading_account != "") {
                $result = $result->where('trading_accounts.account_number',$request->trading_account);
            }
            //Filter By Amount
            if ($request->min != "") {
                $result = $result->where("internal_transfers.amount", '>=', $request->min);
            }
            if ($request->max != "") {
                $result = $result->where("internal_transfers.amount", '<=', $request->max);
            }
            //Filter By Request Date
            if ($request->from != "") {
                $result = $result->whereDate("internal_transfers.created_at", '>=', $request->from);
            }
            if ($request->to != "") {
                $result = $result->whereDate("internal_transfers.created_at", '<=', $request->to);
            }
            // -----------------------------------------Filter End---------------------------------->
            $total_amount = $result->sum('amount');
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $total_amount = $total_amount;
            $data = array();
            $i = 0;

            foreach ($result as $user) {
                if ($user->type == "atw") {
                    $trans_type = "Account To Wallet";
                }
                if ($user->type == "wta") {
                    $trans_type = "Wallet To Account";
                }
                
                 
                if ($user->status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                } elseif ($user->status == 'A') {
                    $status = '<span class="bg-light-success badge badge-success">Approved</span>';
                } elseif ($user->status == 'D') {
                    $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
                }
                
                $data [] = [
                    'responsive_id' => null,
                    'name' => $user->name,
                    'email' => $user->email,
                    'account_number' => $user->account_number,
                    'platform' => $user->platform,
                    'method' => $trans_type,
                    'date' => date('d M y', strtotime($user->created_at)),
                    'status' => $status,
                    'amount' => '$' .$user->amount,
                    ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total_amount' => round($total_amount,2),
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
}
