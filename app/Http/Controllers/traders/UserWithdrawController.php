<?php

namespace App\Http\Controllers\Traders;

use App\Http\Controllers\Controller;
use App\Models\TransactionSetting;
use App\Models\WalletUpDown;
use App\Models\Withdraw;
use App\Models\User;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserWithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('withdraw_reports', 'trader'));
        $this->middleware(AllFunctionService::access('reports', 'trader'));
    }
    public function withdrawReport(Request $request)
    {
        $op = $request->input('op');
        if ($op == "data_table") {
            return $this->withdrawReportDT($request);
        }
        return view('traders.reports.user_withdraw_report');
    }
    // public function withdrawReportDT($request)
    // {
    //     $userId = auth()->user()->id;
    //     $columns = ['deposits.transaction_type', 'cted', 'deposits.approved_date', 'deposits.approved_status', 'deposits.charge', 'deposits.amount'];

    //     $result = Deposit::select(
    //         'deposits.transaction_type',
    //         'deposits.charge',
    //         'deposits.approved_status',
    //         'deposits.created_at as cted',
    //         'deposits.approved_date',
    //         'deposits.amount'
    //     )
    //         ->join('users', 'deposits.user_id', '=', 'users.id')
    //         ->where('wallet_type', 'trader')
    //         ->where('users.id', $userId);

    //     $total_amount = $result->sum('deposits.amount');

    //     $type = $request->input('type');
    //     $approved_status = $request->input('approved_status');
    //     $from = $request->input('from');
    //     $to = $request->input('to');
    //     $min = $request->input('min');
    //     $max = $request->input('max');

    //     if ($type != "") {
    //         $result = $result->where('deposits.transaction_type', '=', $type);
    //         $total_amount = $result->where('deposits.transaction_type', '=', $type)->sum('deposits.amount');
    //     }
    //     if ($approved_status != "") {
    //         $result = $result->where('deposits.approved_status', '=', $approved_status);
    //         $total_amount = $result->where('deposits.approved_status', '=', $approved_status)->sum('deposits.amount');
    //     }

    //     if ($min != "") {
    //         $result = $result->where('deposits.amount', '>=', $min);
    //         $total_amount = $result->where('deposits.amount', '>=', $min)->sum('deposits.amount');
    //     }

    //     if ($max != "") {
    //         $result = $result->where('deposits.amount', '<=', $max);
    //         $total_amount = $result->where('deposits.amount', '<=', $max)->sum('deposits.amount');
    //     }
    //     if ($from != "") {
    //         $result = $result->whereDate('deposits.created_at', '>=', $from);
    //         $total_amount = $result->whereDate('deposits.created_at', '>=', $from)->sum('deposits.amount');
    //     }

    //     if ($to != "") {
    //         $result = $result->whereDate('deposits.created_at', '<=', $to);
    //         $total_amount = $result->whereDate('deposits.created_at', '<=', $to)->sum('deposits.amount');
    //     }
    //     $count = $result->count();
    //     $result = $result->orderby($columns[$request->order[0]["column"]], $request->order[0]["dir"])
    //         ->skip($request->start)->take($request->length)->get();
    //     $data = array();
    //     $i = 0;
    //     $amount = 0;
    //     foreach ($result as $value) {
    //         $approved_date = "";
    //         if ($value->approved_status == 'P') {
    //             $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
    //         } elseif ($value->approved_status == 'A') {
    //             $status = '<span class="bg-light-success badge badge-success">Approved</span>';
    //         } elseif ($value->approved_status == 'D') {
    //             $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
    //         }

    //         if ($value->approved_date != null) {
    //             $approved_date = date('d M y, h:i A', strtotime($value->approved_date));
    //         } else {
    //             $approved_date = '--';
    //         }

    //         $data[$i]['transaction_type'] = ucfirst($value->transaction_type);
    //         $data[$i]['request_date'] = date('d M Y, h:i:s', strtotime($value->cted));
    //         $data[$i]['approved_date'] = $approved_date;
    //         $data[$i]['status'] = $status;
    //         $data[$i]['charge'] = '$' . $value->charge;
    //         $data[$i]['amount'] = '$' . $value->amount;
    //         $amount += $value->amount;
    //         $i++;
    //     }
    //     $total = ["$" . round($amount, 2)];
    //     return Response::json([
    //         'draw' => $request->draw,
    //         'recordsTotal' => $count,
    //         'recordsFiltered' => $count,
    //         'data' => $data,
    //         'total' => $total
    //     ]);
    // }

    public function withdrawReportDT($request)
{
    try {
        $userId = auth()->user()->id;

        $columns = [
            'withdraws.transaction_type',
            'withdraws.created_at',
            'withdraws.approved_date',
            'withdraws.approved_status',
            'withdraws.charge',
            'withdraws.amount'
        ];

        $orderby = $columns[$request->order[0]['column']] ?? 'withdraws.created_at';

        $query = Withdraw::select(
                'withdraws.transaction_type',
                'withdraws.charge',
                'withdraws.approved_status',
                'withdraws.created_at',
                'withdraws.approved_date',
                'withdraws.amount',
                'withdraws.id'
            )
            ->join('users', 'withdraws.user_id', '=', 'users.id')
            ->where('wallet_type', 'trader')
            ->where('users.id', $userId);

        // Apply filters
        if (!empty($request->type)) {
            $query->where('withdraws.transaction_type', $request->type);
        }

        if (!empty($request->approved_status)) {
            $query->where('withdraws.approved_status', $request->approved_status);
        }

        if (!empty($request->min)) {
            $query->where('withdraws.amount', '>=', $request->min);
        }

        if (!empty($request->max)) {
            $query->where('withdraws.amount', '<=', $request->max);
        }

        if (!empty($request->from)) {
            $query->whereDate('withdraws.created_at', '>=', $request->from);
        }

        if (!empty($request->to)) {
            $query->whereDate('withdraws.created_at', '<=', $request->to);
        }

        // Total before pagination
        $total_amount = $query->clone()->sum('withdraws.amount');
        $count = $query->count();

        $results = $query->orderBy($orderby, $request->order[0]['dir'])
            ->skip($request->start)
            ->take($request->length)
            ->get();

        $data = [];
        $amount = 0;

        foreach ($results as $withdraw) {
            $approved_date = $withdraw->approved_date
                ? date('d F y, h:i A', strtotime($withdraw->approved_date))
                : '--';

            if ($withdraw->approved_status == 'P') {
                $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
            } elseif ($withdraw->approved_status == 'A') {
                $status = '<span class="bg-light-success badge badge-success">Approved</span>';
            } elseif ($withdraw->approved_status == 'D') {
                $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
            } else {
                $status = '<span class="badge badge-secondary">Unknown</span>';
            }

            $data[] = [
                'transaction_type' => ucfirst($withdraw->transaction_type),
                'created_at' => date('d F y, h:i A', strtotime($withdraw->created_at)),
                'approved_date' => $approved_date,
                'status' => $status,
                'charge' => '$ ' . ($withdraw->charge ?? 0),
                'amount' => '$' . $withdraw->amount,
            ];

            $amount += $withdraw->amount;
        }

        return Response::json([
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data,
            'total' => '$'.round($amount, 2)
        ]);
    } catch (\Throwable $th) {
        
        Throw $th;
        return Response::json([
            'draw' => $request->draw,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'total' => 0
        ]);
    }
}

    // public function userWithdrawDecline(Request $request, $withdraw_id){
    //     $id = $request->id;
    //     $withdraws = Withdraw::where('id', $withdraw_id)->first();
    //     // update withdraw table

    //     $update = Withdraw::where('id', $withdraw_id)->update([
    //         'approved_status' => 'D',
    //         'note' => $request->note,
    //         'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
    //         'approved_by' => auth()->user()->id,
    //     ]);

    //     if ($update) {
    //         // insert activity-----------------
    //         $user = User::find($withdraws->user_id); //<---client email as user id
    //         activity($withdraws->wallet_type . " withdraw declined")
    //             ->causedBy(auth()->user()->id)
    //             ->withProperties($withdraws)
    //             ->event($withdraws->wallet_type . " withdraw declined")
    //             ->performedOn($user)
    //             ->log("The IP address " . request()->ip() . " has been declined withdraw request");
    //         // end activity log-----------------
            
    //         return Response::json([
    //             'status' => true,
    //             'message' => 'Withdraw request is successfully declined.',
    //         ]);
    //     }
    //     return Response::json([
    //         'status' => false,
    //         'message' => 'Something went wrong, Please try again later!',
    //     ]);
    // }
}
