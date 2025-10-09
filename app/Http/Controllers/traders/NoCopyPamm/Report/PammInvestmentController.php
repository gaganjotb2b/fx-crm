<?php

namespace App\Http\Controllers\traders\NoCopyPamm\Report;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PammInvestmentController extends Controller
{
    public function index(Request $request)
    {
        try {
            return view('traders.pamm.non-copy-pamm.reports.investment-reports');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function investment_report(Request $request)
    {
        try {
            $columns = ['account_id', 'id', 'created_at', 'status', 'charge', 'amount'];
            $order = $columns[$request->order[0]['column']];
            $result = InternalTransfer::with(['account', 'user'])
                ->where('account_type', 'pamm')
                ->where('type', 'wta')
                ->whereHas('account', function ($query) {
                    $query->where('user_id', auth()->id());
                });
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

            // filter by name investor name email 
            if ($request->input('investor_info')) {
                $investor_info = $request->input('investor_info');
                $result = $result->whereHas('user', function ($query) use ($investor_info) {
                    $query->where('name', 'LIKE', "%$investor_info%")
                        ->orWhere('email', 'LIKE', "%$investor_info%")
                        ->orWhere('phone', 'LIKE', "%$investor_info%");
                });
            }
            // filter by status
            if ($request->input('status')) {
                $result = $result->where('status', $request->input('status'));
            }
            // filter by min amount
            if ($request->input('min')) {
                $result = $result->where('amount', '>=', $request->input('min'));
            }
            // filter by max amount
            if ($request->input('max')) {
                $result = $result->where('amount', '<=', $request->input('max'));
            }
            // filter by date from
            if ($request->input('from')) {
                $from = $request->input('from');
                $result = $result->whereDate('created_at', '>=', $from);
            }
            // filter by date to
            if ($request->input('to')) {
                $to = $request->input('to');
                $result = $result->whereDate('created_at', '<=', $to);
            }

            $clone_result = clone $result;
            $result = $result->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $sum = $clone_result->orderBy($order, $request->order[0]['dir'])->skip($request->start)->take($request->length)->sum('amount');
            $count = $clone_result->count();
            $data = [];
            foreach ($result as $value) {
                $status = '';
                if ($value->status === 'A') {
                    $status = 'Approved';
                } elseif ($value->status === 'P') {
                    $status = 'Pending';
                } else {
                    $status = 'Declined';
                }
                $data[] = [
                    'account' => $value->account?->account_number,
                    'investor_email' => $value->user?->email,
                    'date' => Carbon::parse($value->created_at)->format('d-M-Y h:i'),
                    'status' => $status,
                    'charge' => $value->charge,
                    'amount' => $value->amount,
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
            ]);
        }
    }
}
