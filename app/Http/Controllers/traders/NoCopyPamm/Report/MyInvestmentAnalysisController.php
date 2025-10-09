<?php

namespace App\Http\Controllers\traders\NoCopyPamm\Report;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;
use App\Models\InvestorLossTrade;
use App\Models\PammInvestor;
use App\Models\TradingAccount;
use App\Services\balance\BalanceSheetService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MyInvestmentAnalysisController extends Controller
{
    public function index(Request $request)
    {
        try {
            $pamm_investor = PammInvestor::with('pammProfile')->where('user_id', auth()->id())->get();
            return view('traders.pamm.non-copy-pamm.reports.my-investment-analysis', [
                'pamm_accounts' => $pamm_investor,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function investment_analysis(Request $request)
    {
        try {
            $account = TradingAccount::where('account_number', $request->input('account'))->first();
            $total_investment = InternalTransfer::where('account_id', $account->id)
                ->where('type', 'wta')
                ->where('account_type', 'pamm')
                ->where('user_id', auth()->id())
                ->sum('amount');

            // $lastInvestmentDate = InternalTransfer::where('user_id', auth()->id())
            //     ->where('type', 'wta')
            //     ->where('status', 'A')
            //     ->where('account_id', $account->id)
            //     ->latest('created_at')
            //     ->value('created_at');
            $total_loss = InvestorLossTrade::where('account_id', $account->id)
                ->where('user_id', auth()->id())
                ->sum('distributed_loss');
            $total_profit = InternalTransfer::where('account_id', $account->id)
                ->where('type', 'atw')
                ->where('account_type', 'pamm')
                ->where('user_id', auth()->id())
                ->sum('amount');
            $todays_profit = InternalTransfer::where('account_id', $account->id)
                ->where('type', 'atw')
                ->where('account_type', 'pamm')
                ->where('user_id', auth()->id())
                ->whereDate('created_at', Carbon::today())
                ->sum('amount');
            $my_profit_orders = InternalTransfer::where('account_id', $account->id)
                ->where('type', 'atw')
                ->where('account_type', 'pamm')
                ->where('user_id', auth()->id())
                ->select('order_id')
                ->get()->pluck('order_id')->toArray();
            $total_profit_share = InternalTransfer::where('account_id', $account->id)
                ->whereNot('user_id', auth()->id())
                ->whereIn('order_id', $my_profit_orders)->sum('amount');

            $balance = BalanceSheetService::PammInvestorBalance(auth()->id(), $account);

            return response()->json([
                'status' => true,
                'data' => [
                    'total_investment' => round($total_investment, 2),
                    'total_profit' => round($total_profit, 2),
                    'total_loss' => round($total_loss, 2),
                    'remaining_investment' => round($balance, 2),
                    'todays_profit' => round($todays_profit, 2),
                    'profit_share' => round($total_profit_share, 2)
                ]
            ]);
        } catch (\Throwable $th) {
            // throw $th;
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
