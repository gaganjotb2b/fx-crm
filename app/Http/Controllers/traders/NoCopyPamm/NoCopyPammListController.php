<?php

namespace App\Http\Controllers\traders\NoCopyPamm;

use App\Http\Controllers\Controller;
use App\Models\PammTrade;
use App\Models\PammUser;
use Illuminate\Http\Request;

class NoCopyPammListController extends Controller
{
    public function index(Request $request)
    {
        try {
            return view('traders.pamm.non-copy-pamm.non-copy-pamm-list');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // pamm profile list
    public function pamm_list(Request $request)
    {
        try {
            $result = PammUser::where('status', 'active')
                ->where('request_status', 'approved')
                ->with([
                    'tradingAccount',
                    'investors',
                    'tradingAccount.user.description.country',
                    'trades'
                ])->withCount('investors as total_investors')
                ->withSum(['trades as total_profit' => function ($query) {
                    $query->whereNotNull('symbol');
                }], 'profit')
                ->withSum(['trades as net_profit' => function ($query) {
                    $query->where('profit', '>', 0);
                    $query->whereNotNull('symbol');
                }], 'profit')
                ->withSum(['trades as net_loss' => function ($query) {
                    $query->where('profit', '<', 0);
                    $query->whereNotNull('symbol');
                }], 'profit');

            // return $result->get();
            // Shorting by whom show first
            if ($request->input('show_first')){
                $shorting = $request->input('show_first');
                if ($shorting == 'popular') {
                    $result = $result->orderBy('total_investors', 'desc');
                } else if ($shorting == 'Gainer') {
                    $result = $result->orderBy('total_profit', 'desc');
                }
            }

            // Filter by duration
            if ($request->input('duration')) {
                $duration = $request->input('duration');
                
                switch ($duration) {
                    case '2weeks':
                        $startDate = now()->subWeeks(2);
                        break;
                    case '1month':
                        $startDate = now()->subMonths(1);
                        break;
                    case '3months':
                        $startDate = now()->subMonths(3);
                        break;
                    case '6months':
                        $startDate = now()->subMonths(6);
                        break;
                    default:
                        $startDate = null;
                }
            
                if ($startDate) {
                    $result->whereHas('trades', function ($query) use ($startDate) {
                        $query->where('created_at', '>=', $startDate);
                    });
                }
            }
            // filter by min deposit
            if ($request->input('min_investment')) {
                $result = $result->where('min_deposit', '<=', $request->input('min_investment'));
            }
            // filter by user name
            if ($request->input('trader_info')) {
                $trader_info = $request->input('trader_info');
                $result = $result->where(function ($query) use ($trader_info) {
                    $query->where('name', 'like', '%' . $trader_info . '%')
                        ->orWhere('email', 'like', '%' . $trader_info . '%')
                        ->orWhere('username', 'like', '%' . $trader_info . '%')
                        ->orWhere('account', 'like', '%' . $trader_info . '%');
                });
            }
            // filter by duration
            if ($request->input('duration')) {
                $duration = (int) $request->input('duration');
                $startDate = now()->subDays($duration)->startOfDay();
                $result = $result->whereDate('created_at', '>=', $startDate);
            }
            // filter by gainer
            if ($request->input('show_first')) {
                // need to apply logic here
            }
            $count = $result->count();
            $result = $result->orderBy('id', 'desc')->skip($request->input('start'))->take($request->input('length'))->get();
            $data = [];
            foreach ($result as $value) {
                $current_time_stamp = \Carbon\Carbon::now();
                $with_us = $current_time_stamp->diffInDays($value->created_at);
                $country = $value->tradingAccount?->user?->description?->country ?? null;
                $iso = isset($country->iso) && $country->iso !== "" ? $country->iso : 'in';
                $flag = trim(strtolower($iso));

                $chart = $this->growth_chart($value->account);
                $data[] = [
                    "id" => $value->id,
                    "name" => $value->username,
                    "flag" => "https://flagcdn.com/32x24/$flag.png",
                    // "flag" => asset("trader-assets/assets/img/pamm/$flag.svg"),
                    "gain" => round($chart['compound_growth'], 4),
                    "follower" => $value->net_profit ?? 0.00,
                    "unfollow" => $value->net_loss ?? 0.00,
                    "commission" => $value->share_profit,
                    "with_us" => $with_us,
                    "overview_url" => route('trader.pamm.overview') . "?ac=$value->account&id=$value->id",
                    'months' => $chart['label'],
                    'growth' => $chart['data'],
                    'equity' => $chart['equity'],
                    'risk_icon' => ($value->total_profit > 0) ? asset('trader-assets/assets/img/pamm/logo/arro-circle-up.png') : asset('trader-assets/assets/img/pamm/logo/arro-circle-down.png')
                ];
            }
            return response()->json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
                // 'growth_chart' => $this->growth_chart(10025),
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                // 'growth_chart' => $this->growth_chart(10025),
            ]);
        }
    }

    public function growth_chart($login)
    {
        try {
            $initialBalance = 0;
            $currentBalance = $initialBalance;
            $totalGrowthFactor = 1;

            // Fetch trade history ordered by date with pagination
            $batchSize = 1000; // Define batch size for pagination
            $skip = 0;
            $label = [];
            $data = [];
            $equity = [];

            do {
                $trades = PammTrade::orderBy('close_time', 'asc')
                    ->where('login', $login)
                    ->skip($skip)
                    ->take($batchSize)
                    ->get();
                if ($trades->isEmpty()) {
                    break;
                }

                foreach ($trades as $trade) {

                    // Prepare trade data
                    $amount = $trade->profit;
                    $type = $trade->cmd;

                    if ($type == 9) {
                        // Deposit or Withdrawal (Type 6)
                        if ($amount > 0) {
                            // Deposit: Add to balance
                            $currentBalance += $amount;
                        } else {
                            // Withdrawal: Subtract from balance
                            $currentBalance += $amount; // amount is negative for withdrawals
                        }
                    } else {
                        // Profit or Loss (Other types)
                        $previousBalance = $currentBalance;
                        $currentBalance += $amount;

                        // Calculate growth factor for this period
                        if ($previousBalance > 0) {
                            $growthFactor = $currentBalance / $previousBalance;
                            $totalGrowthFactor *= $growthFactor;
                        }
                    }
                    $label[] = date('Y-m-d H:i:s', strtotime($trade->close_time));
                    $data[] = round(($totalGrowthFactor - 1) * 100, 2);
                    $equity[] = round($currentBalance, 2);
                }
                // Move to the next batch
                $skip += $batchSize;
            } while (true);

            $finalCompoundGrowth = round(($totalGrowthFactor - 1) * 100, 2);
            $index_of_data = array_keys($data);

            return ([
                'currentBalance' => round($currentBalance, 2),
                'compound_growth' => $finalCompoundGrowth,
                'data' => array_values($data),
                // 'label' => array_values($label),
                'label' => $index_of_data,
                'equity' => $equity,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return ([
                'currentBalance' => 0,
                'compound_growth' => 0,
                'data' => [],
                'label' => [],
                'equity' => [],
            ]);
        }
    }
}
