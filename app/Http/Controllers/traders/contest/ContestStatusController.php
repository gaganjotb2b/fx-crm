<?php

namespace App\Http\Controllers\traders\contest;

use App\Http\Controllers\Controller;
use App\Models\ContestJoin;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\Mt5WebApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PDO;
use PDOException;
use App\Models\Mt5Trade;

class ContestStatusController extends Controller
{
    private $prefix;
    private $realTimeEquityCache = [];
    private $lastEquityUpdate = null;
    
    public function __construct()
    {
        $this->prefix = \DB::getTablePrefix();
        
        if (request()->is('user/contest/contest-status')) {
            $this->middleware(AllFunctionService::access('contest_status', 'trader'));
            $this->middleware(AllFunctionService::access('contest', 'trader'));
        }
        if (request()->is('user/contest/leaderboard')) {
            $this->middleware(AllFunctionService::access('contest_status', 'trader'));
            $this->middleware(AllFunctionService::access('contest', 'trader'));
        }
    }
    
    public static function index(Request $request)
    {
        // Get all contests (both active and closed)
        $contest = \App\Models\Contest::where('status', 'active')
            ->orWhere('status', 'closed')
            ->get();
        
        // Check if any contest is closed
        $hasClosedContest = $contest->where('status', 'closed')->count() > 0;
        
        return view('traders.contest.contest-status', [
            'contest' => $contest,
            'hasClosedContest' => $hasClosedContest
        ]);
    }

    public function getLeaderboard(Request $request)
    {
        try {
            $contest_id = $request->input('contest_id');
            
            if (!$contest_id) {
                return Response::json([
                    'draw' => $request->draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'message' => 'Contest ID is required'
                ]);
            }

            // Get contest details
            $contest = \App\Models\Contest::find($contest_id);
            if (!$contest) {
                return Response::json([
                    'draw' => $request->draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'message' => 'Contest not found'
                ]);
            }
            
            // Debug logging for contest status
            \Log::info("Contest details for ID {$contest_id}:", [
                'contest_name' => $contest->contest_name,
                'status' => $contest->status,
                'start_date' => $contest->start_date,
                'end_date' => $contest->end_date,
                'current_time' => now()
            ]);

            // Get all participants for this contest
            $participants = ContestJoin::with(['user', 'user.description', 'user.description.country'])
                ->where('contest_id', $contest_id)
                ->get();

            // Filter out demo participants if contest is closed
            $isContestClosed = $contest->status === 'closed' || Carbon::now()->gt($contest->end_date);
            $originalCount = $participants->count();
            
            // Temporary: Disable filtering for testing - remove this condition to re-enable filtering
            $disableFiltering = true; // Set to false to re-enable filtering
            
            if ($isContestClosed && !$disableFiltering) {
                $participants = $this->filterDemoParticipants($participants);
                $filteredCount = $participants->count();
                // \Log::info("Contest filtering: Contest ID {$contest_id}, Status: {$contest->status}, End Date: {$contest->end_date}, Is Closed: " . ($isContestClosed ? 'Yes' : 'No') . ", Original participants: {$originalCount}, After filtering: {$filteredCount}");
            } else {
                // \Log::info("Contest filtering: DISABLED - Contest ID {$contest_id}, Status: {$contest->status}, End Date: {$contest->end_date}, Is Closed: " . ($isContestClosed ? 'Yes' : 'No') . ", Participants: {$originalCount}");
            }

            // Use optimized bulk query approach for better performance
            $leaderboardData = $this->getOptimizedLeaderboardData($participants, $contest);

            // Sort by profit (descending) for profit-based contests
            if ($contest->contest_type === 'on_profit') {
                usort($leaderboardData, function($a, $b) {
                    return $b['profit'] <=> $a['profit'];
                });
            }
            // Sort by lot (descending) for lot-based contests
            elseif ($contest->contest_type === 'on_profit_lot') {
                usort($leaderboardData, function($a, $b) {
                    return $b['lot'] <=> $a['lot'];
                });
            }
            // Sort by equity (descending) for equity-based contests
            elseif ($contest->contest_type === 'on_equity') {
                usort($leaderboardData, function($a, $b) {
                    return $b['equity'] <=> $a['equity'];
                });
            }

            // Assign positions
            foreach ($leaderboardData as $index => $data) {
                $leaderboardData[$index]['position'] = $index + 1;
            }

            // Remove pagination - show all records
            $totalCount = count($leaderboardData);
            $allData = $leaderboardData;

            // Check if pagination parameters were sent
            $start = $request->input('start');
            $length = $request->input('length');
            
            if ($start !== null && $length !== null) {
                $allData = array_slice($leaderboardData, $start, $length);
            }

            // Format data for response
            $data = [];
            foreach ($allData as $item) {
                $data[] = [
                    'rank' => $item['position'],
                    'contestant' => [
                        'name' => $item['user_name'],
                        'country' => $item['country'],
                    ],
                    'account' => $item['account_number'],
                    'profit' => "$" . number_format($item['profit'], 2),
                    'lot' => number_format($item['lot'], 2),
                    'equity' => number_format($item['equity'], 2), // Show equity for all contest types (without % symbol)
                ];
            }

            return Response::json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalCount,
                'recordsFiltered' => $totalCount,
                'data' => $data,
                'contest_status' => $contest->status,
                'contest_end_date' => $contest->end_date,
                'is_closed' => $contest->status === 'closed' || Carbon::now()->gt($contest->end_date),
                'debug_info' => [
                    'contest_type' => $contest->contest_type,
                    'data_source' => 'real_time_with_fallback',
                    'participants_count' => $totalCount
                ]
            ]);
        } catch (\Throwable $th) {
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'message_error' => 'Error loading leaderboard data',
            ]);
        }
    }

    /**
     * Debug contest data and check if trades exist for contest period
     */
    public function debugContestData(Request $request)
    {
        try {
            // Get all contests and participants
            $allContests = \App\Models\Contest::all();
            $allContestJoins = ContestJoin::all();
            $currentUserContests = ContestJoin::where('user_id', auth()->user()->id)->get();
            
            // Get all users
            $allUsers = \App\Models\User::all();
            
            $contest_id = $request->input('contest_id');
            
            // \Log::info('Debug contest data');
            
            return Response::json([
                'status' => 'success',
                'data' => [
                    'all_contests_count' => $allContests->count(),
                    'all_contest_joins_count' => $allContestJoins->count(),
                    'current_user_contests_count' => $currentUserContests->count(),
                    'all_users_count' => $allUsers->count(),
                    'contest_id' => $contest_id
                ]
            ]);
        } catch (\Exception $e) {
            // \Log::error('Debug contest data error');
            return Response::json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test database connection
     */
    public function testDatabaseConnection(Request $request)
    {
        try {
            // \Log::info('Testing database connection');
            
            // Test different connections
            $connections = ['mysql', 'alternate'];
            $results = [];
            
            foreach ($connections as $connection) {
                try {
                    $testQuery = \DB::connection($connection)->table('pro_mt5_trades')->limit(1)->get();
                    $results[$connection] = [
                        'status' => 'success',
                        'count' => $testQuery->count()
                    ];
                } catch (\Exception $e) {
                    $results[$connection] = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            }
            
            return Response::json([
                'status' => 'success',
                'connections' => $results
            ]);
        } catch (\Exception $e) {
            // \Log::error('Database connection test error');
            return Response::json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get optimized leaderboard data
     */
    private function getOptimizedLeaderboardData($participants, $contest)
    {
        try {
            // \Log::info('Processing participants for leaderboard');
            
            $leaderboardData = [];
            $start_date = $contest->start_date;
            $end_date = $contest->end_date;
            
            // Get all account numbers for bulk query
            $accountNumbers = $participants->pluck('account_number')->toArray();
            
            // Bulk query for real-time data
            $realTimeData = $this->getBulkRealTimeData($accountNumbers, $start_date, $end_date);
            
            foreach ($participants as $participant) {
                $accountNumber = $participant->account_number;
                $user = $participant->user;
                
                // Get real-time data for this account
                $profit = $realTimeData['profit'][$accountNumber] ?? 0;
                $lot = $realTimeData['lot'][$accountNumber] ?? 0;
                
                // Calculate equity based on contest status
                if ($contest->status === 'active') {
                    // For active contests, use live MT5 equity
                    $equity = $realTimeData['equity'][$accountNumber] ?? 0;
                    
                                // Debug logging for equity calculation
            // \Log::info("Equity calculation for account {$accountNumber}:", [
            //     'contest_status' => $contest->status,
            //     'live_equity' => $realTimeData['equity'][$accountNumber] ?? 0,
            //     'final_equity' => $equity,
            //     'total_profit' => $participant->total_profit ?? 0
            // ]);
                } else {
                    // For closed contests, use frozen equity if available, otherwise fallback to total_profit
                    if ($participant->frozen_equity !== null && $participant->frozen_equity > 0) {
                        $equity = $participant->frozen_equity;
                        // \Log::info("Equity calculation for closed contest - using frozen equity - account {$accountNumber}:", [
                        //     'contest_status' => $contest->status,
                        //     'frozen_equity' => $participant->frozen_equity,
                        //     'final_equity' => $equity
                        // ]);
                    } else {
                        // Fallback to total_profit if frozen equity is not available
                        $equity = $participant->total_profit ?? 0;
                        // \Log::info("Equity calculation for closed contest - using total_profit fallback - account {$accountNumber}:", [
                        //     'contest_status' => $contest->status,
                        //     'equity' => $equity,
                        //     'total_profit' => $participant->total_profit ?? 0,
                        //     'frozen_equity_available' => $participant->frozen_equity !== null
                        // ]);
                    }
                }
                
                // Fallback to stored data if real-time data is not available
                if ($profit == 0 && $lot == 0) {
                    $profit = $participant->total_profit ?? 0;
                    $lot = $participant->total_lot ?? 0;
                }
                
                $leaderboardData[] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name ?? 'Unknown',
                    'country' => $user->description->country->name ?? 'Unknown',
                    'account_number' => $accountNumber,
                    'profit' => $profit,
                    'lot' => $lot,
                    'equity' => $equity,
                    'join_date' => $participant->created_at
                ];
            }
            
            // \Log::info('Real-time data used');
            
            return $leaderboardData;
        } catch (\Exception $e) {
            // \Log::error('Error in getOptimizedLeaderboardData');
            return [];
        }
    }

    /**
     * Get bulk real-time data for multiple accounts
     */
    private function getBulkRealTimeData($accountNumbers, $startDate, $endDate)
    {
        try {
            // Use the alternate connection which has no prefix
            // This allows us to access the pro_mt5_trades table directly

            // Bulk query for profit data - Get ALL trades for these accounts (not just contest period)
            $profitData = \DB::connection('alternate')->table('pro_mt5_trades')
                ->select('LOGIN', \DB::raw('SUM(PROFIT) as total_profit'))
                ->whereIn('LOGIN', $accountNumbers)
                ->where('CLOSE_TIME', '!=', '0000-00-00 00:00:00') // Only closed trades
                ->groupBy('LOGIN')
                ->get()
                ->keyBy('LOGIN');
            
            // Bulk query for lot data - Get ALL trades for these accounts (not just contest period)
            $lotData = \DB::connection('alternate')->table('pro_mt5_trades')
                ->select('LOGIN', \DB::raw('SUM(VOLUME) as total_lot'))
                ->whereIn('LOGIN', $accountNumbers)
                ->where('CLOSE_TIME', '!=', '0000-00-00 00:00:00') // Only closed trades
                ->groupBy('LOGIN')
                ->get()
                ->keyBy('LOGIN');
            
            // Get real-time equity from MT5 API (30 seconds cache)
            $realTimeEquity = $this->getRealTimeMT5Equity($accountNumbers);
            
            $profit = [];
            $lot = [];
            $equity = [];
            
            foreach ($accountNumbers as $account) {
                $profit[$account] = $profitData->get($account)->total_profit ?? 0;
                $lot[$account] = $lotData->get($account)->total_lot ?? 0;
                $equity[$account] = $realTimeEquity[$account] ?? 0;
            }
            
            // Debug logging for specific account
            if (in_array('2109028', $accountNumbers)) {
                \Log::info('Account 2109028 real-time data:', [
                    'profit' => $profit['2109028'] ?? 0,
                    'lot' => $lot['2109028'] ?? 0,
                    'equity' => $equity['2109028'] ?? 0
                ]);
            }
            
            return ['profit' => $profit, 'lot' => $lot, 'equity' => $equity];
        } catch (\Exception $e) {
            \Log::error('Error fetching bulk real-time data: ' . $e->getMessage());
            return ['profit' => [], 'lot' => [], 'equity' => []];
        }
    }

    /**
     * Get real-time profit from MT5 database
     */
    private function getRealTimeProfit($accountNumber, $startDate, $endDate)
    {
        try {
            // Check if we're in local development environment
            if ($this->isLocalDevelopment()) {
                return $this->getMockTradeData($accountNumber, $startDate, $endDate)['profit'];
            }

            // Use the alternate connection which has no prefix
            // This allows us to access the pro_mt5_trades table directly

            // Single optimized query to get profit
            $profit = \DB::connection('alternate')->table('pro_mt5_trades')
                ->where('LOGIN', $accountNumber)
                ->whereDate('CLOSE_TIME', '>=', $startDate)
                ->whereDate('CLOSE_TIME', '<=', $endDate)
                ->sum('PROFIT');
            
            return $profit ?? 0;
        } catch (\Exception $e) {
            // \Log::error('Error getting real-time profit from MT5');
            return 0;
        }
    }

    /**
     * Get real-time lot from MT5 database
     */
    private function getRealTimeLot($accountNumber, $startDate, $endDate)
    {
        try {
            // Check if we're in local development environment
            if ($this->isLocalDevelopment()) {
                return $this->getMockTradeData($accountNumber, $startDate, $endDate)['lot'];
            }

            // Use the alternate connection which has no prefix
            // This allows us to access the pro_mt5_trades table directly

            // Single optimized query to get lot
            $lot = \DB::connection('alternate')->table('pro_mt5_trades')
                ->where('LOGIN', $accountNumber)
                ->whereDate('CLOSE_TIME', '>=', $startDate)
                ->whereDate('CLOSE_TIME', '<=', $endDate)
                ->sum('VOLUME');
            
            return $lot ?? 0;
        } catch (\Exception $e) {
            // \Log::error('Error getting real-time lot from MT5');
            return 0;
        }
    }

    /**
     * Check if we're in local development environment
     */
    private function isLocalDevelopment()
    {
        $environment = app()->environment();
        $isLocal = in_array($environment, ['local', 'development']);
        
        // \Log::info('Environment detection');
        
        return $isLocal;
    }

    /**
     * Get mock trade data for local development
     */
    private function getMockTradeData($accountNumber, $startDate, $endDate)
    {
        // \Log::info('Using mock data for local development');
        
        return [
            'profit' => rand(100, 1000),
            'lot' => rand(1, 10)
        ];
    }

    /**
     * Get user statistics
     */
    public function getUserStats(Request $request)
    {
        try {
            // \Log::info('getUserStats method called');
            
            $account_number = $request->input('account_number');
            $contest_id = $request->input('contest_id');
            
            // \Log::info('getUserStats parameters');
            
            if (!$account_number || !$contest_id) {
                // \Log::warning('Missing required parameters');
                return Response::json([
                    'status' => 'error',
                    'message' => 'Account number and contest ID are required'
                ]);
            }

            // Get contest details
            $contest = \App\Models\Contest::find($contest_id);
            if (!$contest) {
                // \Log::warning('Contest not found');
                return Response::json([
                    'status' => 'error',
                    'message' => 'Contest not found'
                ]);
            }

            // Get participant details
            $participant = ContestJoin::where('contest_id', $contest_id)
                ->where('account_number', $account_number)
                ->first();

            if (!$participant) {
                // \Log::warning('Participant not found');
                return Response::json([
                    'status' => 'error',
                    'message' => 'Participant not found'
                ]);
            }

            // \Log::info('Getting stats from ContestJoin table');

            // Get real-time data
            $startDate = $contest->start_date;
            $endDate = $contest->end_date;
            
            // Get real-time profit and lot
            $realTimeProfit = $this->getRealTimeProfit($account_number, $startDate, $endDate);
            $realTimeLot = $this->getRealTimeLot($account_number, $startDate, $endDate);
            
            // Use real-time data if available, otherwise fallback to stored data
            $totalProfit = $realTimeProfit > 0 ? $realTimeProfit : ($participant->total_profit ?? 0);
            $totalLot = $realTimeLot > 0 ? $realTimeLot : ($participant->total_lot ?? 0);
            
            // Get recent trades
            $recentTrades = $this->getUserTrades($account_number, $startDate, $endDate, $participant->created_at);
            
            if ($recentTrades->isEmpty()) {
                // \Log::warning('Recent trades returned empty');
                $recentTrades = collect([]);
            }

            // \Log::info('Using real MT5 data for all stats');

            // Calculate statistics
            $stats = $this->calculateUserStats($recentTrades);
            
            // Generate charts
            $profitChart = $this->generateProfitChart($recentTrades, $startDate);
            $tradeDistribution = $this->generateTradeDistribution($recentTrades);
            $recentTradesList = $this->getRecentTrades($recentTrades);

            $response = [
                'status' => 'success',
                'data' => [
                    'profit_chart' => $profitChart,
                    'trade_distribution' => $tradeDistribution,
                    'recent_trades' => $recentTradesList,
                    'total_profit' => $totalProfit,
                    'total_lot' => $totalLot,
                    'best_trade' => $stats['best_trade'],
                    'worst_trade' => $stats['worst_trade'],
                    'total_trades' => $stats['total_trades']
                ]
            ];

            // \Log::info('Final response for getUserStats');

            return Response::json($response);
        } catch (\Exception $e) {
            // \Log::error('User stats error');
            
            return Response::json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Calculate user statistics from trades
     */
    private function calculateUserStats($trades)
    {
        if ($trades->isEmpty()) {
            // \Log::info('No trades available for stats calculation');
            return [
                'best_trade' => 0,
                'worst_trade' => 0,
                'total_trades' => 0
            ];
        }

        // \Log::info('Calculating stats from pro_mt5_trades data');

        $profits = $trades->pluck('PROFIT')->filter(function($profit) {
            return $profit != 0;
        });

        if ($profits->isEmpty()) {
            return [
                'best_trade' => 0,
                'worst_trade' => 0,
                'total_trades' => $trades->count()
            ];
        }

        // \Log::info('Best Trade Calculation from Real MT5 Data');

        return [
            'best_trade' => $profits->max(),
            'worst_trade' => $profits->min(),
            'total_trades' => $trades->count()
        ];
    }

    /**
     * Get user trades from MT5 database
     */
    private function getUserTrades($accountNumber, $startDate, $endDate, $joinDate = null)
    {
        try {
            // \Log::info('Fetching trades for account');
            
            // Use pro_mt5_trades table directly
            // \Log::info('Querying pro_mt5_trades table');
            
            // Get ALL trades for this account from pro_mt5_trades table
            $trades = \DB::table('pro_mt5_trades')
                ->where('LOGIN', $accountNumber)
                ->where('CMD', '<=', 2) // Only buy/sell trades
                ->orderBy('CLOSE_TIME', 'desc')
                ->get();

            // \Log::info('pro_mt5_trades query result');

            if ($trades->count() > 0) {
                // \Log::info('Real trades found');
                return $trades;
            }

            // \Log::info('No trades found');
            return collect([]);
            
        } catch (\Exception $e) {
            // \Log::error('Error fetching trades from pro_mt5_trades table');
            return collect([]);
        }
    }

    /**
     * Generate profit chart data
     */
    private function generateProfitChart($trades, $startDate)
    {
        if ($trades->isEmpty()) {
            // \Log::info('No trades available for profit chart');
            return [];
        }

        // \Log::info('Generating profit chart');

        $chartData = [];
        $cumulativeProfit = 0;

        foreach ($trades as $trade) {
            $cumulativeProfit += $trade->PROFIT;
            $chartData[] = [
                'date' => $trade->CLOSE_TIME,
                'profit' => $cumulativeProfit
            ];
        }

        // \Log::info('Generated profit chart data');

        return $chartData;
    }

    /**
     * Generate trade distribution data
     */
    private function generateTradeDistribution($trades)
    {
        if ($trades->isEmpty()) {
            // \Log::info('No trades available for trade distribution');
            return [];
        }

        // \Log::info('Generating trade distribution');

        $distribution = [
            'profitable' => $trades->where('PROFIT', '>', 0)->count(),
            'losing' => $trades->where('PROFIT', '<', 0)->count(),
            'break_even' => $trades->where('PROFIT', '=', 0)->count()
        ];

        // \Log::info('Generated trade distribution');

        return $distribution;
    }

    /**
     * Get recent trades for display
     */
    private function getRecentTrades($trades)
    {
        if ($trades->isEmpty()) {
            // \Log::info('No trades available for recent trades');
            return [];
        }

        // \Log::info('Processing recent trades');

        $recentTrades = $trades->take(10)->map(function($trade) {
            return [
                'symbol' => $trade->SYMBOL,
                'type' => $trade->CMD == 0 ? 'BUY' : 'SELL',
                'volume' => $trade->VOLUME,
                'profit' => $trade->PROFIT,
                'close_time' => $trade->CLOSE_TIME
            ];
        });

        // \Log::info('Generated recent trades');

        return $recentTrades->toArray();
    }

    // Disable all other methods to reduce logging
    public function manualUpdateContestData(Request $request) { 
        // \Log::info('Manual update disabled');
        return Response::json(['message' => 'Manual update disabled']); 
    }
    
    public function testContestData(Request $request) { 
        // \Log::info('Test data disabled');
        return Response::json(['message' => 'Test data disabled']); 
    }
    
    public function testMT5Connection(Request $request) { 
        // \Log::info('MT5 connection test disabled');
        return Response::json(['message' => 'MT5 connection test disabled']); 
    }
    
    public function testRealTrades(Request $request) { 
        // \Log::info('Real trades test disabled');
        return Response::json(['message' => 'Real trades test disabled']); 
    }
    
    public function checkMT5Data(Request $request) { 
        // \Log::info('MT5 data check disabled');
        return Response::json(['message' => 'MT5 data check disabled']); 
    }
    
    public function checkContestParticipant(Request $request) { 
        // \Log::info('Contest participant check disabled');
        return Response::json(['message' => 'Contest participant check disabled']); 
    }
    
    public function testSpecificAccount(Request $request) { 
        // \Log::info('Specific account test disabled');
        return Response::json(['message' => 'Specific account test disabled']); 
    }
    
    public function debugAvailableData(Request $request) { 
        // \Log::info('Debug available data disabled');
        return Response::json(['message' => 'Debug available data disabled']); 
    }
    
    public function testMT5DataForAccount(Request $request) { 
        // \Log::info('MT5 data for account test disabled');
        return Response::json(['message' => 'MT5 data for account test disabled']); 
    }
    
    public function checkContestDetails(Request $request) { 
        // \Log::info('Contest details check disabled');
        return Response::json(['message' => 'Contest details check disabled']); 
    }
    
    public function checkContestDates(Request $request) { 
        // \Log::info('Contest dates check disabled');
        return Response::json(['message' => 'Contest dates check disabled']); 
    }
    
    public function testEnvironment(Request $request) { 
        // \Log::info('Environment test disabled');
        return Response::json(['message' => 'Environment test disabled']); 
    }
    
    public function searchAccountInMT5(Request $request) { 
        // \Log::info('Account search in MT5 disabled');
        return Response::json(['message' => 'Account search in MT5 disabled']); 
    }

    /**
     * Filter out demo participants for closed contests
     */
    private function filterDemoParticipants($participants)
    {
        return $participants->filter(function($participant) {
            // Get the trading account
            $tradingAccount = \App\Models\TradingAccount::where('account_number', $participant->account_number)->first();
            
            // If no trading account found, keep the participant (don't remove)
            if (!$tradingAccount) {
                // \Log::info("Participant filtering: Account {$participant->account_number} - No trading account found, keeping");
                return true;
            }
            
            // Only remove if explicitly marked as demo
            if ($tradingAccount->client_type === 'demo') {
                // \Log::info("Participant filtering: Account {$participant->account_number} - Demo account (client_type: demo), removing");
                return false;
            }
            
            // Check group name only if group_id exists
            if ($tradingAccount->group_id) {
                $clientGroup = \App\Models\ClientGroup::find($tradingAccount->group_id);
                if ($clientGroup) {
                    $groupName = strtolower($clientGroup->group_id);
                    // Only remove if group name explicitly contains 'demo'
                    if (str_contains($groupName, 'demo')) {
                        // \Log::info("Participant filtering: Account {$participant->account_number} - Demo group account (group_id: {$clientGroup->group_id}), removing");
                        return false;
                    }
                }
            }
            
            // \Log::info("Participant filtering: Account {$participant->account_number} - Live account, keeping");
            return true; // Keep all other accounts
        });
    }

    /**
     * Check contest status and return if it's closed
     */
    public function checkContestStatus(Request $request)
    {
        try {
            $contest_id = $request->input('contest_id');
            
            if (!$contest_id) {
                return Response::json([
                    'status' => false,
                    'message' => 'Contest ID is required'
                ]);
            }

            $contest = \App\Models\Contest::find($contest_id);
            
            if (!$contest) {
                return Response::json([
                    'status' => false,
                    'message' => 'Contest not found'
                ]);
            }

            $isClosed = $contest->status === 'closed' || Carbon::now()->gt($contest->end_date);
            
            // Get participants for debugging
            $participants = ContestJoin::where('contest_id', $contest_id)->get();
            $demoParticipants = $participants->filter(function($participant) {
                $tradingAccount = \App\Models\TradingAccount::where('account_number', $participant->account_number)->first();
                if (!$tradingAccount) return false;
                return $tradingAccount->client_type === 'demo' || 
                       ($tradingAccount->group_id && \App\Models\ClientGroup::find($tradingAccount->group_id) && 
                        str_contains(strtolower(\App\Models\ClientGroup::find($tradingAccount->group_id)->group_id), 'demo'));
            });
            
            // Get detailed participant info for debugging
            $participantDetails = $participants->take(5)->map(function($participant) {
                $tradingAccount = \App\Models\TradingAccount::where('account_number', $participant->account_number)->first();
                return [
                    'account_number' => $participant->account_number,
                    'user_name' => $participant->user->name ?? 'Unknown',
                    'has_trading_account' => $tradingAccount ? 'Yes' : 'No',
                    'client_type' => $tradingAccount ? $tradingAccount->client_type : 'N/A',
                    'group_id' => $tradingAccount ? $tradingAccount->group_id : 'N/A',
                    'group_name' => $tradingAccount && $tradingAccount->group_id ? 
                        (\App\Models\ClientGroup::find($tradingAccount->group_id)->group_id ?? 'N/A') : 'N/A'
                ];
            });
            
            return Response::json([
                'status' => true,
                'is_closed' => $isClosed,
                'contest_status' => $contest->status,
                'end_date' => $contest->end_date,
                'current_time' => Carbon::now(),
                'debug_info' => [
                    'total_participants' => $participants->count(),
                    'demo_participants' => $demoParticipants->count(),
                    'contest_id' => $contest_id,
                    'should_filter' => $isClosed,
                    'sample_participants' => $participantDetails
                ]
            ]);
            
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Error checking contest status: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Calculate equity for a specific account
     * Formula: Starting Balance + Contest Period Profit
     */
    private function calculateEquityForAccount($accountNumber, $startDate, $endDate)
    {
        try {
            // Use the alternate connection which has no prefix
            // This allows us to access the pro_mt5_trades table directly
            
            // Get contest period trades (trades during contest period only)
            $contestPeriodTrades = \DB::connection('alternate')->table('pro_mt5_trades')
                ->where('LOGIN', $accountNumber)
                ->where('CLOSE_TIME', '!=', '0000-00-00 00:00:00') // Closed trades only
                ->where('CLOSE_TIME', '>=', $startDate)
                ->where('CLOSE_TIME', '<=', $endDate)
                ->sum('PROFIT');
            
            // Get floating P&L from open trades during contest period
            $floatingPnl = \DB::connection('alternate')->table('pro_mt5_trades')
                ->where('LOGIN', $accountNumber)
                ->where('CLOSE_TIME', '0000-00-00 00:00:00') // Open trades
                ->where('OPEN_TIME', '>=', $startDate)
                ->where('OPEN_TIME', '<=', $endDate)
                ->sum('PROFIT');
            
            // Total contest performance (contest period profit)
            $totalContestPerformance = $contestPeriodTrades + $floatingPnl;
            
            // Get starting balance from trades before contest
            $baseAmount = 10000; // $10,000 base amount if no trades before contest
            
            // Get trades before contest to determine starting balance
            $tradesBeforeContest = \DB::connection('alternate')->table('pro_mt5_trades')
                ->where('LOGIN', $accountNumber)
                ->where('CLOSE_TIME', '!=', '0000-00-00 00:00:00')
                ->where('CLOSE_TIME', '<', $startDate)
                ->count();
            
            if ($tradesBeforeContest > 0) {
                // Account had activity before contest, calculate starting balance
                $startingBalance = \DB::connection('alternate')->table('pro_mt5_trades')
                    ->where('LOGIN', $accountNumber)
                    ->where('CLOSE_TIME', '!=', '0000-00-00 00:00:00')
                    ->where('CLOSE_TIME', '<', $startDate)
                    ->sum('PROFIT');
                
                // Use starting balance if it's not zero, otherwise use base amount
                $startingBalance = $startingBalance != 0 ? $startingBalance : $baseAmount;
            } else {
                // No trades before contest, use base amount
                $startingBalance = $baseAmount;
            }
            
            // Calculate equity: Starting Balance + Contest Period Profit
            $equity = $startingBalance + $totalContestPerformance;
            
            // Debug logging for specific account
            if ($accountNumber == '2109028') {
                \Log::info('Account 2109028 equity calculation:', [
                    'starting_balance' => $startingBalance,
                    'contest_period_trades' => $contestPeriodTrades,
                    'floating_pnl' => $floatingPnl,
                    'total_contest_performance' => $totalContestPerformance,
                    'equity' => $equity
                ]);
            }
            
            return $equity;
            
        } catch (\Exception $e) {
            \Log::error('Error calculating equity for account', [
                'account_number' => $accountNumber,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Get real-time equity from MT5 API for multiple accounts
     */
    private function getRealTimeMT5Equity($accountNumbers)
    {
        try {
            // Check if we need to update (30 seconds cache)
            $now = time();
            if ($this->lastEquityUpdate && ($now - $this->lastEquityUpdate) < 30) {
                return $this->realTimeEquityCache;
            }

            // Check if we're on server environment
            $isServer = !$this->isLocalDevelopment();
            // \Log::info("Environment check - Is Server: " . ($isServer ? 'Yes' : 'No'));

            $mt5_api = new Mt5WebApi();
            $equityData = [];

            foreach ($accountNumbers as $accountNumber) {
                try {
                    // \Log::info("Attempting to get MT5 equity for account: {$accountNumber}");
                    
                    $result = $mt5_api->execute('AccountGetMargin', [
                        "Login" => (int)$accountNumber
                    ]);
                    
                    // \Log::info("MT5 API response for account {$accountNumber}:", $result);
                    
                    if (isset($result['success']) && $result['success']) {
                        $equity = $result['data']['Equity'] ?? 0;
                        $equityData[$accountNumber] = $equity;
                        
                        // \Log::info("Real-time MT5 equity for account {$accountNumber}: {$equity}");
                    } else {
                        // On server, if MT5 API fails, try alternative method
                        if ($isServer) {
                            // \Log::warning("MT5 API failed on server for account {$accountNumber}, trying alternative method");
                            $alternativeEquity = $this->getAlternativeEquityForAccount($accountNumber);
                            $equityData[$accountNumber] = $alternativeEquity;
                            // \Log::info("Alternative equity for account {$accountNumber}: {$alternativeEquity}");
                        } else {
                            $equityData[$accountNumber] = 0;
                            // \Log::warning("Failed to get MT5 equity for account {$accountNumber}. Response: " . json_encode($result));
                        }
                    }
                } catch (\Exception $e) {
                    // \Log::error("Error getting MT5 equity for account {$accountNumber}: " . $e->getMessage());
                    
                    // On server, if MT5 API fails, try alternative method
                    if ($isServer) {
                        // \Log::info("Trying alternative equity method for account {$accountNumber}");
                        $alternativeEquity = $this->getAlternativeEquityForAccount($accountNumber);
                        $equityData[$accountNumber] = $alternativeEquity;
                                                    // \Log::info("Alternative equity for account {$accountNumber}: {$alternativeEquity}");
                    } else {
                        $equityData[$accountNumber] = 0;
                    }
                }
            }

            $mt5_api->Disconnect();
            
            // Update cache
            $this->realTimeEquityCache = $equityData;
            $this->lastEquityUpdate = $now;
            
            return $equityData;
            
        } catch (\Exception $e) {
            // \Log::error('Error in getRealTimeMT5Equity: ' . $e->getMessage());
            
            // On server, if everything fails, return alternative equity
            if (!$this->isLocalDevelopment()) {
                // \Log::info("Using alternative equity method for all accounts");
                $equityData = [];
                foreach ($accountNumbers as $accountNumber) {
                    $equityData[$accountNumber] = $this->getAlternativeEquityForAccount($accountNumber);
                }
                return $equityData;
            }
            
            return [];
        }
    }

    /**
     * Get alternative equity calculation for server environment
     */
    public function getAlternativeEquityForAccount($accountNumber)
    {
        try {
            // Get total profit from all closed trades for this account
            $totalProfit = \DB::connection('alternate')->table('pro_mt5_trades')
                ->where('LOGIN', $accountNumber)
                ->where('CLOSE_TIME', '!=', '0000-00-00 00:00:00') // Only closed trades
                ->sum('PROFIT');
            
            // Get floating PnL from open trades
            $floatingPnl = \DB::connection('alternate')->table('pro_mt5_trades')
                ->where('LOGIN', $accountNumber)
                ->where('CLOSE_TIME', '=', '0000-00-00 00:00:00') // Open trades
                ->sum('PROFIT');
            
            // Base starting balance
            $startingBalance = 10000; // $10,000 base amount
            
            // Calculate equity as starting balance + total profit + floating PnL
            $equity = $startingBalance + $totalProfit + $floatingPnl;
            
            // \Log::info("Alternative equity calculation for account {$accountNumber}:", [
            //     'starting_balance' => $startingBalance,
            //     'total_profit' => $totalProfit,
            //     'floating_pnl' => $floatingPnl,
            //     'calculated_equity' => $equity
            // ]);
            
            return $equity;
            
        } catch (\Exception $e) {
            // \Log::error("Error in getAlternativeEquityForAccount for account {$accountNumber}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get real-time equity for a single account directly from MT5 API
     */
    public function getDirectMT5EquityForAccount($accountNumber)
    {
        try {
            // \Log::info("Getting direct MT5 equity for account: {$accountNumber}");
            
            $mt5_api = new Mt5WebApi();
            
            $result = $mt5_api->execute('AccountGetMargin', [
                "Login" => (int)$accountNumber
            ]);
            
            // \Log::info("Direct MT5 API response for account {$accountNumber}:", $result);
            
            if (isset($result['success']) && $result['success']) {
                $equity = $result['data']['Equity'] ?? 0;
                // \Log::info("Direct MT5 equity for account {$accountNumber}: {$equity}");
                return $equity;
            } else {
                // \Log::warning("Direct MT5 API failed for account {$accountNumber}. Response: " . json_encode($result));
                return 0;
            }
            
        } catch (\Exception $e) {
            // \Log::error("Error getting direct MT5 equity for account {$accountNumber}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get real-time equity for a single account
     */
    public function getRealTimeEquityForAccount($accountNumber)
    {
        $equityData = $this->getRealTimeMT5Equity([$accountNumber]);
        return $equityData[$accountNumber] ?? 0;
    }

    /**
     * Show individual contest leaderboard page
     */
    public function showContestLeaderboard($contestId)
    {
        try {
            // Get contest details
            $contest = \App\Models\Contest::find($contestId);
            
            if (!$contest) {
                return redirect()->back()->with('error', 'Contest not found');
            }
            
            return view('traders.contest.contest-leaderboard', [
                'contest' => $contest
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error showing contest leaderboard: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading contest leaderboard');
        }
    }

    /**
     * Get contest details for API
     */
    public function getContestDetails($contestId)
    {
        try {
            $contest = \App\Models\Contest::find($contestId);
            
            if (!$contest) {
                return Response::json([
                    'status' => false,
                    'message' => 'Contest not found'
                ]);
            }
            
            // Get participant count
            $participantCount = ContestJoin::where('contest_id', $contestId)->count();
            
            return Response::json([
                'status' => true,
                'contest' => [
                    'id' => $contest->id,
                    'contest_name' => $contest->contest_name,
                    'status' => $contest->status,
                    'start_date' => $contest->start_date,
                    'end_date' => $contest->end_date,
                    'total_join' => $participantCount
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting contest details: ' . $e->getMessage());
            return Response::json([
                'status' => false,
                'message' => 'Error getting contest details'
            ]);
        }
    }

    /**
     * Check for announced results
     */
    public function checkAnnouncedResults(Request $request)
    {
        try {
            $contest = \App\Models\Contest::find($request->contest_id);
            
            if (!$contest) {
                return Response::json([
                    'status' => false,
                    'message' => 'Contest not found'
                ]);
            }
            
            // Debug logging
            // \Log::info('Checking announced results for contest: ' . $contest->id . ', status: ' . $contest->status);
            
            // Check contest status and return appropriate response
            if ($contest->status === 'active') {
                // \Log::info('Contest is active, no modal needed');
                return Response::json([
                    'status' => false,
                    'message' => 'Contest is active'
                ]);
            } elseif ($contest->status === 'closed') {
                // Contest is closed, check if results are announced
                if ($contest->results_announced) {
                    // \Log::info('Contest is closed and results are announced');
                    // Continue to show winners
                } else {
                    // \Log::info('Contest is closed but results not yet announced');
                    return Response::json([
                        'status' => false,
                        'message' => 'Results not yet announced'
                    ]);
                }
            } else {
                // \Log::info('Contest has other status: ' . $contest->status);
                return Response::json([
                    'status' => false,
                    'message' => 'Contest has other status'
                ]);
            }
            
            // Get contest prizes
            $prizes = json_decode($contest->contest_prices, true);
            if (!$prizes) {
                return Response::json([
                    'status' => false,
                    'message' => 'No prizes found for this contest'
                ]);
            }
            $prizeCount = count($prizes);
            
            // Debug logging for prizes
            // \Log::info('Prizes structure: ' . json_encode($prizes));
            // \Log::info('Prize count: ' . $prizeCount);
            
            // Get top participants based on frozen equity (for closed contests) or total profit
            $participants = ContestJoin::where('contest_id', $request->contest_id)
                ->orderByRaw('CASE WHEN frozen_equity IS NOT NULL AND frozen_equity > 0 THEN frozen_equity ELSE total_profit END DESC')
                ->limit($prizeCount)
                ->get();
            
                    // Debug logging for participants
        // \Log::info('Participants count: ' . $participants->count());
        // foreach ($participants as $index => $participant) {
        //     \Log::info('Participant ' . ($index + 1) . ': ' . $participant->account_number . ' - Profit: ' . $participant->total_profit . ' - Frozen Equity: ' . ($participant->frozen_equity ?? 'null'));
        // }
            
            if ($participants->isEmpty()) {
                return Response::json([
                    'status' => false,
                    'message' => 'No participants found for this contest'
                ]);
            }
            
            $winners = [];
            foreach ($participants as $index => $participant) {
                // Calculate rank (1st, 2nd, 3rd, etc.)
                $rank = $index + 1;
                
                // Get prize amount based on rank (Rank 1 gets highest prize, Rank 2 gets second highest, etc.)
                $prizeAmount = 0;
                $prizeIndex = $index; // Use direct index: 0 for 1st rank, 1 for 2nd rank, etc.
                if (isset($prizes[$prizeIndex])) {
                    $prizeKey = array_keys($prizes[$prizeIndex])[0];
                    $prizeAmount = $prizes[$prizeIndex][$prizeKey];
                }
                
                // Get user name safely
                $userName = 'Unknown';
                try {
                    if ($participant->user_id) {
                        $user = \App\Models\User::find($participant->user_id);
                        $userName = $user ? $user->name : 'Unknown';
                    }
                } catch (\Exception $e) {
                    $userName = 'Unknown';
                }
                
                // Calculate equity based on contest status
                if ($contest->status === 'active') {
                    // For active contests, try to get live MT5 equity
                    try {
                        $equity = $this->getRealTimeEquityForAccount($participant->account_number);
                        if ($equity <= 0) {
                            // Fallback to total_profit if live equity is not available
                            $equity = $participant->total_profit ?? 0;
                        }
                    } catch (\Exception $e) {
                        // If live calculation fails, use total_profit
                        $equity = $participant->total_profit ?? 0;
                    }
                } else {
                    // For closed contests, use frozen equity if available, otherwise use total_profit
                    if ($participant->frozen_equity !== null && $participant->frozen_equity > 0) {
                        $equity = $participant->frozen_equity;
                    } else {
                        $equity = $participant->total_profit ?? 0;
                    }
                }
                
                // Debug logging for prize assignment and equity
                // \Log::info('Rank ' . $rank . ' - Participant: ' . $participant->account_number . ' - Prize Index: ' . $prizeIndex . ' - Prize Amount: ' . $prizeAmount . ' - Total Profit: ' . $participant->total_profit . ' - Frozen Equity: ' . ($participant->frozen_equity ?? 'null') . ' - Calculated Equity: ' . $equity . ' - Contest Status: ' . $contest->status);
                
                $winners[] = [
                    'rank' => $rank,
                    'user_name' => $userName,
                    'account_number' => $participant->account_number ?? 'N/A',
                    'equity' => number_format($equity, 2),
                    'profit' => number_format($participant->total_profit ?? 0, 2),
                    'prize_amount' => number_format($prizeAmount, 2)
                ];
            }
            
            return Response::json([
                'status' => true,
                'data' => [
                    'contest_id' => $contest->id,
                    'contest_name' => $contest->contest_name,
                    'winners' => $winners,
                    'prize_count' => $prizeCount
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error checking announced results: ' . $e->getMessage());
            return Response::json([
                'status' => false,
                'message' => 'Error checking announced results: ' . $e->getMessage()
            ]);
        }
    }

} 