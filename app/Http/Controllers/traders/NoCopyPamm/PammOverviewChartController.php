<?php

namespace App\Http\Controllers\traders\NoCopyPamm;

use App\Http\Controllers\Controller;
use App\Models\PammTrade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PammOverviewChartController extends Controller
{
    public function monthly_mix_chart(Request $request)
    {
        try {
            $login = $request->input('account');

            // Initialize variables
            $initialBalance = 0;
            $currentBalance = $initialBalance;
            $totalGrowthFactor = 1;
            $currentMonth = null;
            $monthlyProfit = 0;

            // Batch processing for pagination
            $batchSize = 1000;
            $skip = 0;
            $label = [];
            $data = [];
            $equity = [];

            do {
                // Fetch trades from the database
                $trades = PammTrade::orderBy('close_time', 'asc')
                    ->where('login', $login)
                    ->skip($skip)
                    ->take($batchSize)
                    ->get();

                if ($trades->isEmpty()) {
                    break;
                }

                foreach ($trades as $trade) {
                    $carbonDate = Carbon::parse($trade->close_time);
                    $tradeMonth = $carbonDate->format('Y-m');

                    if ($currentMonth === null) {
                        $currentMonth = $tradeMonth;
                    }

                    // Process end-of-month growth calculation
                    if ($currentMonth !== $tradeMonth) {
                        // Record growth for the month
                        $label[] = $currentMonth;
                        $data[] = round(($totalGrowthFactor - 1) * 100, 2);
                        $equity[] = $currentBalance;

                        // Reset for the new month
                        $currentMonth = $tradeMonth;
                    }

                    // Calculate profit/loss for the current trade
                    $amount = $trade->profit;
                    $type = $trade->cmd;

                    // Handle deposits/withdrawals (type 9)
                    if ($type == 9) {
                        $currentBalance += $amount;
                    } else {
                        // Update balance and calculate growth factor
                        $previousBalance = $currentBalance;
                        $currentBalance += $amount;

                        if ($previousBalance > 0) {
                            $growthFactor = $currentBalance / $previousBalance;
                            $totalGrowthFactor *= $growthFactor;
                        }
                    }
                }

                // Move to the next batch for pagination
                $skip += $batchSize;
            } while (true);

            // Ensure the final month's data is processed
            if ($currentMonth !== null) {
                $label[] = $currentMonth;
                $data[] = round(($totalGrowthFactor - 1) * 100, 2);
                $equity[] = $currentBalance;
            }

            // Final compound growth calculation
            $finalCompoundGrowth = round(($totalGrowthFactor - 1) * 100, 2);

            // Return the chart data
            return response()->json([
                'currentBalance' => round($currentBalance, 2),
                'compound_growth' => $finalCompoundGrowth,
                'data' => array_values($data),
                'label' => array_values($label),
                'equity' => array_values($equity)
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'currentBalance' => 0,
                'compound_growth' => 0,
                'data' => [],
                'label' => [],
                'equity' => []
            ], 200);
        }
    }

    // monthly doughnut chart
    public function monthly_doughnut_chart(Request $request)
    {
        try {
            $last_months = Carbon::now()->subMonths(1);
            // last month trades group by created at and symbol
            // where('created_at', '>=', $last_months)
            $copy_trade = PammTrade::where('login', $request->input('account'))->groupBy('symbol')->where('cmd', '!=', 9)->selectRaw('SUM(profit) as profit, symbol')->get();

            $labels = $values = [];
            foreach ($copy_trade as $value) {
                $labels[] = $value->symbol;
                $values[] = $value->profit;
            }
            if (empty($copy_trade)) {
                return response()->json([
                    'labels' => ['No Trade'],
                    'chartData' => [100],
                    'backgroundColor' => ['#3A416F', '#2152ff',  '#f53939', '#cb0c9f', '#a8b8d8']
                ]);
            }

            return response()->json([
                'labels' => $labels,
                'chartData' => $values,
                'backgroundColor' => ['#2152ff', '#3A416F', '#f53939', '#a8b8d8', '#cb0c9f']
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'labels' => ['No Trade'],
                'chartData' => [100],
                'backgroundColor' => ['#3A416F', '#2152ff',  '#f53939', '#cb0c9f', '#a8b8d8']
            ]);
        }
    }
    // daily mix chart
    // -------------------------------
    public function daily_mix_chart(Request $request)
    {
        try {
            $login = $request->input('account');

            // Initialize variables
            $initialBalance = 0;
            $currentBalance = $initialBalance;
            $totalGrowthFactor = 1;
            $currentDay = null;
            $dailyProfit = 0;

            // Batch processing for pagination
            $batchSize = 1000;
            $skip = 0;
            $label = [];
            $data = [];
            $equity = [];

            do {
                // Fetch trades from the database
                $trades = PammTrade::orderBy('close_time', 'asc')
                    ->where('login', $login)
                    ->skip($skip)
                    ->take($batchSize)
                    ->get();

                if ($trades->isEmpty()) {
                    break;
                }

                foreach ($trades as $trade) {
                    $carbonDate = Carbon::parse($trade->close_time);
                    $tradeDay = $carbonDate->format('Y-m-d'); // Group by day

                    if ($currentDay === null) {
                        $currentDay = $tradeDay;
                    }

                    // Process end-of-day growth calculation
                    if ($currentDay !== $tradeDay) {
                        if ($currentBalance > 0) {
                            // Calculate growth factor for the day
                            $growthFactor = ($currentBalance + $dailyProfit) / $currentBalance;
                            $totalGrowthFactor *= $growthFactor;
                        }

                        // Record growth for the day
                        $label[] = $currentDay;
                        $data[] = round(($totalGrowthFactor - 1) * 100, 2);
                        $equity[] = $currentBalance;

                        // Update balance and reset for the new day
                        $currentBalance += $dailyProfit;
                        $dailyProfit = 0;
                        $currentDay = $tradeDay;
                    }

                    // Calculate profit/loss for the current trade
                    $amount = $trade->profit;
                    $type = $trade->cmd;

                    // Handle deposits/withdrawals (type 9)
                    if ($type == 9) {
                        $currentBalance += $amount;
                    } else {
                        $dailyProfit += $amount;
                    }
                }

                // Move to the next batch for pagination
                $skip += $batchSize;
            } while (true);

            // Ensure the final day's data is processed
            if ($dailyProfit != 0 && $currentBalance > 0) {
                $growthFactor = ($currentBalance + $dailyProfit) / $currentBalance;
                $totalGrowthFactor *= $growthFactor;
                $currentBalance += $dailyProfit;
                $label[] = $currentDay;
                $data[] = round(($totalGrowthFactor - 1) * 100, 2);
                $equity[] = $currentBalance;
            }

            // Final compound growth calculation
            $finalCompoundGrowth = round(($totalGrowthFactor - 1) * 100, 2);

            // Return the chart data
            return response()->json([
                'currentBalance' => round($currentBalance, 2),
                'compound_growth' => $finalCompoundGrowth,
                'data' => array_values($data),
                'label' => array_values($label),
                'equity' => array_values($equity)
            ], 200);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'currentBalance' => 0,
                'compound_growth' => 0,
                'data' => [],
                'label' => [],
                'equity' => []
            ], 200);
        }
    }

    // doughnut chart daily
    public function daily_doughnut_chart(Request $request)
    {
        try {
            $login = $request->input('account');

            // Fetch all trades for the account, grouped by symbol and date
            $all_trades = PammTrade::where('login', $login)
                ->where('cmd', '!=', 9) // Exclude deposits/withdrawals
                ->selectRaw('symbol, DATE(close_time) as trade_date, SUM(profit) as daily_profit')
                ->groupBy('symbol', 'trade_date')
                ->orderBy('trade_date', 'asc')
                ->get();

            // Prepare data for the chart
            $labels = []; // Symbols (labels)
            $chartData = []; // Average daily profit for each symbol (chartData)
            $backgroundColors = []; // Random colors for each symbol

            // Group data by symbol and calculate average daily profit
            $symbolData = [];
            foreach ($all_trades as $trade) {
                if (!isset($symbolData[$trade->symbol])) {
                    $symbolData[$trade->symbol] = [
                        'total_profit' => 0,
                        'trade_count' => 0,
                    ];
                }
                $symbolData[$trade->symbol]['total_profit'] += $trade->daily_profit;
                $symbolData[$trade->symbol]['trade_count'] += 1;
            }

            // Calculate average daily profit for each symbol
            foreach ($symbolData as $symbol => $data) {
                $labels[] = $symbol; // Use the symbol as the label
                $chartData[] = $data['total_profit'] / $data['trade_count']; // Calculate average daily profit
                $backgroundColors[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT); // Generate random color
            }

            // If no trades are found, return a default response
            if (empty($all_trades)) {
                return response()->json([
                    'labels' => ['No Trade'],
                    'chartData' => [100],
                    'backgroundColor' => ['#3A416F'] // Default color
                ]);
            }

            // Return the chart data
            return response()->json([
                'labels' => $labels,
                'chartData' => $chartData,
                'backgroundColor' => $backgroundColors
            ]);
        } catch (\Throwable $th) {
            // Handle errors
            return response()->json([
                'labels' => ['No Trade'],
                'chartData' => [100],
                'backgroundColor' => ['#3A416F'] // Default color
            ]);
        }
    }
    // hourly mix chart
    // -------------------------------
    public function hourly_mix_chart(Request $request)
    {
        try {
            $login = $request->input('account');

            // Initialize variables
            $initialBalance = 0;
            $currentBalance = $initialBalance;
            $totalGrowthFactor = 1;
            $currentHour = null;
            $hourlyProfit = 0;

            // Batch processing for pagination
            $batchSize = 1000;
            $skip = 0;
            $label = [];
            $data = [];
            $equity = [];

            do {
                // Fetch trades from the database
                $trades = PammTrade::orderBy('close_time', 'asc')
                    ->where('login', $login)
                    ->skip($skip)
                    ->take($batchSize)
                    ->get();

                if ($trades->isEmpty()) {
                    break;
                }

                foreach ($trades as $trade) {
                    $carbonDate = Carbon::parse($trade->close_time);
                    $tradeHour = $carbonDate->format('Y-m-d H:00'); // Group by hour

                    if ($currentHour === null) {
                        $currentHour = $tradeHour;
                    }

                    // Process end-of-hour growth calculation
                    if ($currentHour !== $tradeHour) {
                        if ($currentBalance > 0) {
                            // Calculate growth factor for the hour
                            $growthFactor = ($currentBalance + $hourlyProfit) / $currentBalance;
                            $totalGrowthFactor *= $growthFactor;
                        }

                        // Record growth for the hour
                        $label[] = $currentHour;
                        $data[] = round(($totalGrowthFactor - 1) * 100, 2);
                        $equity[] = $currentBalance;

                        // Update balance and reset for the new hour
                        $currentBalance += $hourlyProfit;
                        $hourlyProfit = 0;
                        $currentHour = $tradeHour;
                    }

                    // Calculate profit/loss for the current trade
                    $amount = $trade->profit;
                    $type = $trade->cmd;

                    // Handle deposits/withdrawals (type 9)
                    if ($type == 9) {
                        $currentBalance += $amount;
                    } else {
                        $hourlyProfit += $amount;
                    }
                }

                // Move to the next batch for pagination
                $skip += $batchSize;
            } while (true);

            // Ensure the final hour's data is processed
            if ($hourlyProfit != 0 && $currentBalance > 0) {
                $growthFactor = ($currentBalance + $hourlyProfit) / $currentBalance;
                $totalGrowthFactor *= $growthFactor;
                $currentBalance += $hourlyProfit;
                $label[] = $currentHour;
                $data[] = round(($totalGrowthFactor - 1) * 100, 2);
                $equity[] = $currentBalance;
            }

            // Final compound growth calculation
            $finalCompoundGrowth = round(($totalGrowthFactor - 1) * 100, 2);

            // Return the chart data
            return response()->json([
                'currentBalance' => round($currentBalance, 2),
                'compound_growth' => $finalCompoundGrowth,
                'data' => array_values($data),
                'label' => array_values($label),
                'equity' => array_values($equity)
            ], 200);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'currentBalance' => 0,
                'compound_growth' => 0,
                'data' => [],
                'label' => [],
                'equity' => []
            ], 200);
        }
    }

    public function hourly_doughnut_chart(Request $request)
    {
        try {
            $login = $request->input('account');

            // Get the current date
            $currentDate = Carbon::now()->toDateString(); // Format: YYYY-MM-DD

            // Fetch trades grouped by symbol and hour, and calculate average profit per hour
            $hourly_trades = PammTrade::where('login', $login)
                ->where('cmd', '!=', 9) // Exclude deposits/withdrawals
                ->whereDate('close_time', $currentDate) // Filter by current date
                ->selectRaw('symbol, HOUR(close_time) as hour, AVG(profit) as avg_profit')
                ->groupBy('symbol', 'hour')
                ->get();

            $labels = [];
            $values = [];
            $backgroundColors = [];

            // Process the data to calculate average profit per symbol across all hours
            $symbolData = [];
            foreach ($hourly_trades as $trade) {
                if (!isset($symbolData[$trade->symbol])) {
                    $symbolData[$trade->symbol] = [
                        'total_profit' => 0,
                        'count' => 0,
                    ];
                }
                $symbolData[$trade->symbol]['total_profit'] += $trade->avg_profit;
                $symbolData[$trade->symbol]['count'] += 1;
            }

            // Calculate the average profit for each symbol
            foreach ($symbolData as $symbol => $data) {
                $labels[] = $symbol; // Use the symbol as the label
                $values[] = $data['total_profit'] / $data['count']; // Calculate average profit
                $backgroundColors[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT); // Generate random color
            }

            // If no trades are found for the current day, return a default response
            if (empty($hourly_trades)) {
                return response()->json([
                    'labels' => ['No Trade'],
                    'chartData' => [100],
                    'backgroundColor' => ['#3A416F'] // Default color
                ]);
            }

            // Return the hourly average data with random colors
            return response()->json([
                'labels' => $labels,
                'chartData' => $values,
                'backgroundColor' => $backgroundColors
            ]);
        } catch (\Throwable $th) {
            // Handle errors
            return response()->json([
                'labels' => ['No Trade'],
                'chartData' => [100],
                'backgroundColor' => ['#3A416F'] // Default color
            ]);
        }
    }
}
