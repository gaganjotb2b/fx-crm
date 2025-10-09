<?php

namespace App\Http\Controllers\IB\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\IB;
use App\Models\IbCommissionStructure;
use App\Models\IbSetup;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\DataTableService;
use App\Services\IbService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class IbClientWithdrawReport extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('withdraw_reports', 'ib'));
        $this->middleware(AllFunctionService::access('affiliate', 'ib'));
        $this->middleware('is_ib'); // check the combined user is an IB
    }
    public function withdrawReports(Request $request)
    {
        if ($request->action == 'table') {
            return $this->withdrawReportDT($request);
        }
        return view('ibs.affiliate.client_withdraw_report');
    }

    public function withdrawReportDT(Request $request)
    {
        try {
            $dts = new DataTableService($request);
            $columns = $dts->get_columns();
            $traders = [];
            if ($request->fiGroup == 'my_direct') {
                // my direct 
                $mytraders = IB::where('users.type', 0)
                    ->join('users', 'ib.reference_id', '=', 'users.id')
                    ->where('ib_id', auth()->user()->id)->get();

                if ($mytraders) {
                    foreach ($mytraders as $key => $value) {
                        array_push($traders, $value->reference_id);
                    }
                }
            } else if ($request->fiGroup == 'my_team') {
                // my team
                $traders = AllFunctionService::sub_ib_traders_id(auth()->user()->id);
            } else {
                // full time
                $traders = AllFunctionService::sub_ib_traders_id(auth()->user()->id, 'all');
            }

            $result = Withdraw::select('withdraws.*', 'users.name', 'users.email')
                ->join('users', 'withdraws.user_id', '=', 'users.id')
                ->whereIn('withdraws.user_id', $traders);

            //Search if columns field has search data
            $result = $result->where(function ($q) use ($dts, $columns) {
                if ($dts->search) {
                    foreach ($columns as $col) {
                        if ($col['data'] != 'id' && $col['data'] != 'ib' && !empty($col['data'])) {
                            $tf = $col['data'] == 'created_at' ? 'withdraws.created_at' : $col['data'];
                            $st = $dts->search;
                            $q->orWhere($tf, 'LIKE', '%' . $st . '%');
                        }
                    }
                }
            });

            // impliment filter conditions
            if ($request->status != "") {
                $result = $result->where('withdraws.approved_status', $request->status);
            }
            //Filter By Ib
            if ($request->ib_info != "") {
                $filter_client = [];
                $users = User::where('email', 'LIKE', '%'.$request->ib_info.'%')
                            ->orWhere('name', 'LIKE', '%'.$request->ib_info.'%')
                            ->orWhere('phone', 'LIKE', '%'.$request->ib_info.'%')
                            ->first();

                $ref_id = IB::where('ib_id', $users->id)
                    ->where('users.type', 0)->select('reference_id')
                    ->join('users', 'ib.reference_id', '=', 'users.id')->get();
                foreach ($ref_id as $key => $value) {
                    array_push($filter_client, $value->reference_id);
                }
                $result = $result->whereIn('withdraws.user_id', $filter_client);
            }
            // filter by trader name/email/phone
            if ($request->trader_info != "") {
                $result = $result->where(function ($query) use ($request) {
                    $query->where('email', 'LIKE', '%'.$request->trader_info.'%')
                          ->orWhere('name', 'LIKE', '%'.$request->trader_info.'%')
                          ->orWhere('phone', 'LIKE', '%'.$request->trader_info.'%');
                });
            }
            //Filter By Account Number
            if ($request->account_number != "") {
                $account = TradingAccount::where('account_number', $request->account_number)->select('user_id')->first();

                if (isset($account)) {
                    // $user_id = IB::where('reference_id', $account->user_id)->pluck('ib_id')->toArray();
                    $user_id = Withdraw::where('user_id', $account->user_id)->get()->pluck('user_id');
                    $result = $result->whereIn('users.id', $user_id);
                } else {
                    $result = $result->where('users.id', null);
                }
            }
            // filter by withdraw method
            if ($request->withdraw_method != "") {
                $result = $result->where('transaction_type', $request->withdraw_method);
            }
            // filter by minimum deposit
            if ($request->min != "") {
                $result = $result->where("amount", '>=', $request->min);
            }
            // filter by maximum deposit
            if ($request->max != "") {
                $result = $result->where("amount", '<=', $request->max);
            }
            // filter by date from
            if ($request->from != "") {
                $result = $result->whereDate('withdraws.created_at', '>=', Carbon::parse($request->from)->format('Y-m-d'));
            }
            // filter by date to
            if ($request->to != "") {
                $result = $result->whereDate('withdraws.created_at', '<=', Carbon::parse($request->to)->format('Y-m-d'));
            }

            $count = $result->count();
            $totalAmount = $result->sum('amount');
            $result = $result->orderBy($dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();

            $data = [];
            $i = 0;
            foreach ($result as $row) {
                $status = $row->approved_status;
                if ($status == 'A') $status = 'Approved';
                else if ($status == 'P') $status = 'Pending';
                else $status = 'Declined';
                $data[$i]["id"] = $row->id;
                $data[$i]["name"] = $row->name;
                $data[$i]["email"] = $row->email;
                $data[$i]["ib"] = AllFunctionService::user_email(IbService::instant_parent($row->user_id));
                $data[$i]["transaction_type"] = $row->transaction_type;
                $data[$i]["approved_status"] = $status;
                $data[$i]["created_at"] = Carbon::parse($row->created_at)->format('d-m-Y');
                $data[$i]["amount"] = $row->amount;
                $i++;
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'totalAmount' => round($totalAmount, 2),
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'totalAmount' => 0,
                'data' => []
            ]);
        }
    }
}
