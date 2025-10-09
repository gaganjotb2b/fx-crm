<?php

namespace App\Services\contest;

use App\Models\ContestTradingHistory;
use App\Models\ContestJoin;
use App\Models\Contest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ContestTradingHistoryService
{
    /**
     * Update trading history for all participants in a contest
     */
    public static function updateContestTradingHistory($contestId)
    {
        try {
            Log::info('Starting contest trading history update', ['contest_id' => $contestId]);
            
            $contest = Contest::find($contestId);
            if (!$contest) {
                Log::error('Contest not found', ['contest_id' => $contestId]);
                return false;
            }

            // Get all participants for this contest
            $participants = ContestJoin::where('contest_id', $contestId)->get();
            
            Log::info('Found participants for contest', [
                'contest_id' => $contestId,
                'participants_count' => $participants->count()
            ]);

            foreach ($participants as $participant) {
                self::updateParticipantTradingHistory($participant, $contest);
            }

            Log::info('Contest trading history update completed', [
                'contest_id' => $contestId,
                'participants_processed' => $participants->count()
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error updating contest trading history', [
                'contest_id' => $contestId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Update trading history for a specific participant
     */
    public static function updateParticipantTradingHistory($participant, $contest)
    {
        try {
            $accountNumber = $participant->account_number;
            $userId = $participant->user_id;
            $contestId = $participant->contest_id;

            Log::info('Updating trading history for participant', [
                'account_number' => $accountNumber,
                'user_id' => $userId,
                'contest_id' => $contestId
            ]);

            // Get trading data for this account during contest period
            $tradingData = self::getTradingDataForAccount($accountNumber, $contest->start_date, $contest->end_date);

            // Calculate statistics
            $stats = self::calculateTradingStats($tradingData);

            // Update or create trading history record
            ContestTradingHistory::updateOrCreate(
                [
                    'contest_id' => $contestId,
                    'user_id' => $userId,
                    'account_number' => $accountNumber
                ],
                [
                    'total_profit' => $stats['total_profit'],
                    'total_lot' => $stats['total_lot'],
                    'total_trades' => $stats['total_trades'],
                    'win_rate' => $stats['win_rate'],
                    'best_trade' => $stats['best_trade'],
                    'trading_data' => $tradingData,
                    'last_updated' => now()
                ]
            );

            // Also update the ContestJoin table for backward compatibility
            $participant->update([
                'total_profit' => $stats['total_profit'],
                'total_lot' => $stats['total_lot'],
                'position' => 0 // Will be calculated separately
            ]);

            Log::info('Trading history updated successfully', [
                'account_number' => $accountNumber,
                'total_profit' => $stats['total_profit'],
                'total_lot' => $stats['total_lot'],
                'total_trades' => $stats['total_trades']
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating participant trading history', [
                'account_number' => $participant->account_number ?? 'unknown',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get trading data for an account during contest period
     */
    private static function getTradingDataForAccount($accountNumber, $startDate, $endDate)
    {
        try {
            // Try to get data from live MT5 first
            $liveData = self::getLiveMT5Data($accountNumber, $startDate, $endDate);
            if (!empty($liveData)) {
                return $liveData;
            }

            // Fallback to local database
            $localData = self::getLocalTradingData($accountNumber, $startDate, $endDate);
            if (!empty($localData)) {
                return $localData;
            }

            // Generate mock data for testing
            return self::generateMockTradingData($accountNumber, $startDate, $endDate);

        } catch (\Exception $e) {
            Log::error('Error getting trading data', [
                'account_number' => $accountNumber,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Get live MT5 data
     */
    private static function getLiveMT5Data($accountNumber, $startDate, $endDate)
    {
        try {
            // This would connect to live MT5 server
            // For now, return empty array
            return [];
        } catch (\Exception $e) {
            Log::error('Error getting live MT5 data', [
                'account_number' => $accountNumber,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Get local trading data
     */
    private static function getLocalTradingData($accountNumber, $startDate, $endDate)
    {
        try {
            // Try different table names without prefix
            $tables = ['com_trades', 'mt5_trades', 'trades'];
            
            foreach ($tables as $tableName) {
                try {
                    $trades = DB::table($tableName)
                        ->where('account_no', $accountNumber)
                        ->whereDate('close_time', '>=', $startDate)
                        ->whereDate('close_time', '<=', $endDate)
                        ->where('cmd', '<=', 2)
                        ->get();
                    
                    if ($trades->count() > 0) {
                        Log::info('Found data in table', [
                            'table' => $tableName,
                            'account' => $accountNumber,
                            'count' => $trades->count()
                        ]);
                        return $trades->toArray();
                    }
                } catch (\Exception $e) {
                    Log::info('Table not found or error', [
                        'table' => $tableName,
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }
            
            return [];

        } catch (\Exception $e) {
            Log::error('Error getting local trading data', [
                'account_number' => $accountNumber,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Generate mock trading data for testing
     */
    private static function generateMockTradingData($accountNumber, $startDate, $endDate)
    {
        $trades = [];
        $numTrades = rand(5, 20);
        
        for ($i = 0; $i < $numTrades; $i++) {
            $profit = rand(-500, 1000);
            $volume = rand(1, 50) / 10;
            
            $trades[] = [
                'ticket' => rand(100000, 999999),
                'account_no' => $accountNumber,
                'symbol' => ['EURUSD', 'GBPUSD', 'USDJPY', 'XAUUSD'][rand(0, 3)],
                'cmd' => rand(0, 1),
                'volume' => $volume,
                'profit' => $profit,
                'open_time' => date('Y-m-d H:i:s', strtotime("-$i hours")),
                'close_time' => date('Y-m-d H:i:s', strtotime("-$i hours")),
                'comment' => 'Mock trade'
            ];
        }

        return $trades;
    }

    /**
     * Calculate trading statistics from trades data
     */
    private static function calculateTradingStats($trades)
    {
        if (empty($trades)) {
            return [
                'total_profit' => 0,
                'total_lot' => 0,
                'total_trades' => 0,
                'win_rate' => 0,
                'best_trade' => 0
            ];
        }

        $totalProfit = 0;
        $totalLot = 0;
        $winningTrades = 0;
        $bestTrade = 0;
        $totalTrades = count($trades);

        foreach ($trades as $trade) {
            $profit = $trade['profit'] ?? 0;
            $volume = $trade['volume'] ?? 0;

            $totalProfit += $profit;
            $totalLot += $volume;

            if ($profit > 0) {
                $winningTrades++;
            }

            if ($profit > $bestTrade) {
                $bestTrade = $profit;
            }
        }

        $winRate = $totalTrades > 0 ? round(($winningTrades / $totalTrades) * 100, 2) : 0;

        return [
            'total_profit' => $totalProfit,
            'total_lot' => $totalLot,
            'total_trades' => $totalTrades,
            'win_rate' => $winRate,
            'best_trade' => $bestTrade
        ];
    }

    /**
     * Get trading history for a specific contest and account
     */
    public static function getTradingHistory($contestId, $accountNumber)
    {
        return ContestTradingHistory::forContestAndAccount($contestId, $accountNumber)->first();
    }

    /**
     * Get all trading history for a contest
     */
    public static function getContestTradingHistory($contestId)
    {
        return ContestTradingHistory::forContest($contestId)
            ->with(['user', 'contest'])
            ->orderBy('total_profit', 'desc')
            ->get();
    }
} 