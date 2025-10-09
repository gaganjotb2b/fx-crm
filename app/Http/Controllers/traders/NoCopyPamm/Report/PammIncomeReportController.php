<?php

namespace App\Http\Controllers\traders\NoCopyPamm\Report;

use App\Http\Controllers\Controller;
// use App\Models\admin\InternalTransfer;
use App\Models\PammProfitShare;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PammIncomeReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            return view('traders.pamm.non-copy-pamm.reports.pamm-income-report');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function income_report(Request $request)
    {
        try {
            $columns = ['ticket', 'login', 'profit', 'pamm_id', 'shared_amount', 'created_at'];
            $order = $columns[$request->order[0]['column']];
            $result = PammProfitShare::select('ticket', 'login', 'profit', 'name', 'email', 'shared_amount', 'pamm_profit_shares.created_at', 'pamm_id', 'share_type')
                ->where('share_type', 'pamm')
                ->where('pamm_id', auth()->id())
                ->join('users', 'pamm_profit_shares.pamm_id', 'users.id');
            // return $result->get();
            // Start search
            // if (isset($request->search['value'])) {
            //     $search =  $request->search['value'];
            //     $result->where(function ($q) use ($search) {
            //         $q->where('order', $search)
            //             ->orWhere('login', $search)
            //             ->orWhere('open_time', 'LIKE', '%' . $search . '%')
            //             ->orWhere('symbol', 'LIKE', '%' . $search . '%')
            //             ->orWhere('volume', 'LIKE', '%' . (float)$search . '%')
            //             ->orWhere('open_price', 'LIKE', '%' . (float)$search . '%');
            //     });
            // }

            // // filter by name investor name email 
            // if ($request->input('investor_info')) {
            //     $investor_info = $request->input('investor_info');
            //     $result = $result->whereHas('account.pammUser', function ($query) use ($investor_info) {
            //         $query->where('name', 'LIKE', "%$investor_info%")
            //             ->orWhere('email', 'LIKE', "%$investor_info%")
            //             ->orWhere('username', 'LIKE', "%$investor_info%");
            //     });
            // }
            // // filter by status
            // if ($request->input('status')) {
            //     $result = $result->where('status', $request->input('status'));
            // }
            // // filter by min amount
            // if ($request->input('min')) {
            //     $result = $result->where('amount', '>=', $request->input('min'));
            // }
            // // filter by max amount
            // if ($request->input('max')) {
            //     $result = $result->where('amount', '<=', $request->input('max'));
            // }
            // // filter by date from
            // if ($request->input('from')) {
            //     $from = $request->input('from');
            //     $result = $result->whereDate('created_at', '>=', $from);
            // }
            // // filter by date to
            // if ($request->input('to')) {
            //     $to = $request->input('to');
            //     $result = $result->whereDate('created_at', '<=', $to);
            // }

            $clone_result = clone $result;
            $result = $result->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $sum = $clone_result->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length)->sum('shared_amount');
            $count = $clone_result->count();
            $data = [];
            foreach ($result as $value) {
                // $status = '';
                // if ($value->status === 'A') {
                //     $status = 'Approved';
                // } elseif ($value->status === 'P') {
                //     $status = 'Pending';
                // } else {
                //     $status = 'Declined';
                // }
                $data[] = [
                    'ticket' => $value->ticket,
                    'login' => $value->login ?? '---',
                    'profit' => $value->profit ?? '---',
                    'pamm_email' => $value->email ?? '---',
                    'created_at' => Carbon::parse($value->created_at)->format('d-M-Y h:i'),
                    'shared_amount' => $value->shared_amount,
                ];
            }

            return response()->json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
                'totalAmount' => number_format($sum, 2),
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'totalAmount' => 0.00,
                'error'=>$th->getMessage(),
            ]);
        }
    }
    // public function income_report(Request $request)
    // {
    //     try {
    //         $columns = ['account_id', 'id', 'created_at', 'status', 'charge', 'amount'];
    //         $order = $columns[$request->order[0]['column']];
    //         $result = InternalTransfer::with([
    //             'account',
    //             'user',
    //             'account.pammUser',
    //             'pammTrade'
    //         ])
    //             ->whereHas('account',function ($query) {
    //                 $query->where('user_id',auth()->id());
    //             })
    //             ->where('type', 'atw')
    //             ->where('account_type', 'pamm')
    //             ->where('user_id', auth()->id());
    //         // return $result->get();
    //         // Start search
    //         // if (isset($request->search['value'])) {
    //         //     $search =  $request->search['value'];
    //         //     $result->where(function ($q) use ($search) {
    //         //         $q->where('order', $search)
    //         //             ->orWhere('login', $search)
    //         //             ->orWhere('open_time', 'LIKE', '%' . $search . '%')
    //         //             ->orWhere('symbol', 'LIKE', '%' . $search . '%')
    //         //             ->orWhere('volume', 'LIKE', '%' . (float)$search . '%')
    //         //             ->orWhere('open_price', 'LIKE', '%' . (float)$search . '%');
    //         //     });
    //         // }

    //         // filter by name investor name email 
    //         if ($request->input('investor_info')) {
    //             $investor_info = $request->input('investor_info');
    //             $result = $result->whereHas('user', function ($query) use ($investor_info) {
    //                 $query->where('name', 'LIKE', "%$investor_info%")
    //                     ->orWhere('email', 'LIKE', "%$investor_info%")
    //                     ->orWhere('phone', 'LIKE', "%$investor_info%");
    //             });
    //         }
    //         // filter by status
    //         if ($request->input('status')) {
    //             $result = $result->where('status', $request->input('status'));
    //         }
    //         // filter by min amount
    //         if ($request->input('min')) {
    //             $result = $result->where('amount', '>=', $request->input('min'));
    //         }
    //         // filter by max amount
    //         if ($request->input('max')) {
    //             $result = $result->where('amount', '<=', $request->input('max'));
    //         }
    //         // filter by date from
    //         if ($request->input('from')) {
    //             $from = $request->input('from');
    //             $result = $result->whereDate('created_at', '>=', $from);
    //         }
    //         // filter by date to
    //         if ($request->input('to')) {
    //             $to = $request->input('to');
    //             $result = $result->whereDate('created_at', '<=', $to);
    //         }

    //         $clone_result = clone $result;
    //         $result = $result->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
    //         $sum = $clone_result->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length)->sum('amount');
    //         $count = $clone_result->count();
    //         $data = [];
    //         foreach ($result as $value) {
    //             $status = '';
    //             if ($value->status === 'A') {
    //                 $status = 'Approved';
    //             } elseif ($value->status === 'P') {
    //                 $status = 'Pending';
    //             } else {
    //                 $status = 'Declined';
    //             }
    //             $data[] = [
    //                 'account' => $value->account?->account_number,
    //                 'pamm_user' => $value->account?->pammUser?->username ?? '---',
    //                 'profit_share' => $value->account?->pammUser?->share_profit ?? '---',
    //                 'date' => Carbon::parse($value->created_at)->format('d-M-Y h:i'),
    //                 'trade_profit' => $value->pammTrade?->profit ?? '---',
    //                 'trade_order' => $value->pammTrade?->order ?? '---',
    //                 'my_profit' => $value->amount,
    //             ];
    //         }

    //         return response()->json([
    //             'draw' => $request->draw,
    //             'recordsTotal' => $count,
    //             'recordsFiltered' => $count,
    //             'data' => $data,
    //             'totalAmount' => number_format($sum, 2),
    //         ]);
    //     } catch (\Throwable $th) {
    //         // throw $th;
    //         return response()->json([
    //             'draw' => $request->draw,
    //             'recordsTotal' => 0,
    //             'recordsFiltered' => 0,
    //             'data' => [],
    //             'totalAmount' => 0.00,
    //         ]);
    //     }
    // }
}
