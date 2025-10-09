<?php

namespace App\Services\tournaments;

use App\Models\CommissionStatus;
use App\Models\CustomCommission;
use App\Models\IbCommissionStructure;
use App\Models\IbIncome;
use App\Models\Mt5Trade;
use App\Models\PammUser;
use App\Models\admin\InternalTransfer;
use App\Models\PammProfitShare;
use App\Services\Mt5WebApi;
use App\Models\User;
use App\Services\IbService;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\tournaments\TourParticipant;
use App\Models\tournaments\TourGroup;
use App\Models\tournaments\TourSetting;


class GroupTradeCalculationService
{
    public static function groupTradeCalculation()
    {
        try {
            $tourSetting = TourSetting::select()->first();

            $tourGroups = TourGroup::with('participants')
                ->whereNotNull('start_trading')
                ->where('status', 'enabled')
                ->has('participants')
                ->first();
            
            if (!$tourGroups) return "No active tour group found.";
            
            // Shift start time 6 hours earlier
            // $startTrading = Carbon::parse($tourGroups->start_trading);
            $startTrading = Carbon::parse($tourGroups->start_trading);
            $tradingEnd = $startTrading->copy()->addDays($tourSetting->group_trading_duration);
            
            // Check if trading is still ongoing
            if ($tradingEnd->lessThanOrEqualTo(Carbon::now())) {
                // return "true";
                foreach ($tourGroups->participants as $row) {
                    // Use open time for filtering trades within the range
                    $result = Mt5Trade::where('LOGIN', $row->account_num)
                        ->whereBetween('OPEN_TIME', [$startTrading, $tradingEnd]);
            
                    // Calculate total profit and volume
                    $total_profit = $result->sum('PROFIT');
                    $total_volume = $result->sum('VOLUME');
            
                    // Update the participant record
                    TourParticipant::where('group_id', $row->group_id)
                        ->where('account_num', $row->account_num)
                        ->update([
                            'group1_profit' => $total_profit,
                            'group1_volume' => $total_volume/100,
                            'status'        => 'disable',
                        ]);
                }
            
                return 'Profit/Volume updated for all participants.';
            } else {
                return "false"; // Still within trading period
            }
        } catch (\Throwable $th) {
            // throw $th;
            // return 0;
        }
    }
}
