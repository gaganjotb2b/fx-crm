<?php

namespace App\Http\Controllers\Traders\socialtrade;

use App\Http\Controllers\Controller;
use App\Models\Traders\MasterProfit;
use App\Models\TransactionSetting;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MasterProfitShareReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('deposit_report', 'trader'));
        $this->middleware(AllFunctionService::access('reports', 'trader'));
    }
    public function profitShareReport(Request $request)
    {
        $op = $request->input('op');
        if ($op == "data_table") {
            return $this->masterProfitShareReportDT($request);
        }
        return view('traders.pamm.master-profit-share-report');
    }

    public function masterProfitShareReportDT($request)
    {
        // return $request->all();
        $userId = auth()->user()->id;
        $columns = [
            'master_profits.slave_order', 
            'master_profits.slave', 
            'master_profits.slave_profit', 
            'master_profits.master', 
            'master_profits.profit_percent', 
            'master_profits.created_at', 
            'master_profits.status', 
            'master_profits.amount'
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
        ->join('users', 'trading_accounts.user_id', 'users.id')
        ->where('users.id', $userId);
    
    
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
    
        // Global search
        if (!empty($request->input('search.value'))) {
            $searchValue = $request->input('search.value');
            $result = $result->where(function ($query) use ($searchValue) {
                $query->where('master_profits.slave_order', 'like', "%{$searchValue}%")
                      ->orWhere('master_profits.slave', 'like', "%{$searchValue}%")
                      ->orWhere('master_profits.master', 'like', "%{$searchValue}%")
                      ->orWhere('master_profits.status', 'like', "%{$searchValue}%");
            });
        }
    
        // Get filtered count
        $count = $result->count();
    
        // Ordering and pagination
        $result = $result->orderBy($columns[$request->order[0]["column"]], $request->order[0]["dir"])
             ->skip($request->start)
             ->take($request->length)
             ->get();
    
        $data = [];
        $amount = 0;
        foreach ($result as $i => $value) {
            if ($value->status == 'pending') {
                $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
            } elseif ($value->status == 'credited') {
                $status = '<span class="bg-light-success badge badge-success">Credited</span>';
            } else {
                $status = '<span class="bg-light-danger badge badge-danger">'.ucwords($value->status).'</span>';
            }
    
            $data[$i]['slave_order'] = ucfirst($value->slave_order);
            $data[$i]['slave_login'] = ucfirst($value->slave);
            $data[$i]['slave_profit'] = ucfirst($value->slave_profit);
            $data[$i]['master_login'] = ucfirst($value->master);
            $data[$i]['profit_percent'] = ucfirst($value->profit_percent);
            $data[$i]['share_time'] = date('d M y, h:i A', strtotime($value->created_at)) ?? "---";
            $data[$i]['status'] = $status;
            $data[$i]['amount'] = '$' . $value->amount;
            $amount += $value->amount;
        }

        return Response::json([
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data,
            'total_amount' => round($amount, 2)

        ]);
    }

}
