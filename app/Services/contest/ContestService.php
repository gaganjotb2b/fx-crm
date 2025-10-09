<?php

namespace App\Services\contest;

use App\Models\Contest;
use App\Models\ContestCountry;
use App\Models\ContestJoin;
use App\Services\api\FileApiService;
use App\Services\Trader\ClientService;
use App\Services\trades\ProfitService;
use Carbon\Carbon;

class ContestService
{
    public static function client_has_contest($user_id)
    {
        try {
            // check for all client / and user type trader
            $all_client_status = false;
            $contest_id = '';
            $client_country = ClientService::user_country_id($user_id);
            $all_client = Contest::where('user_type', 'trader')
                ->where('allowed_for', 'all_clients')
                ->whereDate('end_date', '>=', Carbon::now())
                ->where('status', 'active')
                ->get();
            foreach ($all_client as $key => $value) {
                // check global or specific country
                if ($value->is_global != 0) {
                    $all_client_status = true;
                    $contest_id = $value->id;
                } else {
                    $has_country = ContestCountry::where('contest_id', $value->id)->where('country_id', $client_country)->exists();
                    if ($has_country) {
                        $all_client_status = true;
                        $contest_id = $value->id;
                    }
                }
            }
            // check new registrations
            $new_registration_status = false;
            $register_date = ClientService::profile_created_at($user_id);
            $new_register = Contest::where('start_date', '<=', $register_date)
                ->where('end_date', '>=', $register_date)
                ->where('allowed_for', 'new_registration')
                ->where('status', 'active')
                ->get();
            foreach ($new_register as $key => $value) {
                // check is global or specific country
                if ($value->is_global != 0) {
                    $new_registration_status = true;
                    $contest_id = $value->id;
                } else {
                    $has_country = ContestCountry::where('contest_id', $value->id)->where('country_id', $client_country)->exists();
                    if ($has_country) {
                        $new_registration_status = true;
                        $contest_id = $value->id;
                    }
                }
            }
            // check new account
            $new_account_status = false;
            // last account open date
            $last_account_created_at = ClientService::last_account_created_at($user_id);
            // return $last_account_created_at;
            $new_account = Contest::whereDate('start_date', '<=', $last_account_created_at)
                ->whereDate('end_date', '>=', $last_account_created_at)
                ->where('allowed_for', 'new_account')->where('status', 'active')->get();
            foreach ($new_account as $key => $value) {
                // check is global or specific country
                if ($value->is_global != 0) {
                    $new_account_status = true;
                    $contest_id = $value->id;
                } else {
                    $has_country = ContestCountry::where('contest_id', $value->id)->where('country_id', $client_country)->exists();
                    if ($has_country) {
                        $new_account_status = true;
                        $contest_id = $value->id;
                    }
                }
            }
            if ($all_client_status == true || $new_registration_status == true || $new_account_status == true) {
                // Check if user should see contest based on hidden groups
                if (!\App\Services\contest\ContestVisibilityService::shouldShowContestMenu()) {
                    return [
                        'status' => false,
                        'contest_id' => '',
                    ];
                }
                
                return [
                    'status' => true,
                    'contest_id' => $contest_id,
                ];
            }
            return [
                'status' => false,
                'contest_id' => '',
            ];
        } catch (\Throwable $th) {
            // throw $th;
            return [
                'status' => false,
                'contest_id' => '',
            ];
        }
    }
    // get contest 
    // contest for client 
    // contest that active and fill the condition
    public static function get_contest($user_id)
    {
        try {
            $result = [];
            $contest = self::client_has_contest($user_id);
            if ($contest['status']) {
                $result = Contest::find($contest['contest_id']);
            }
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // count total participant
    public static function count_total_participant($contest_id)
    {
        try {
            $result = ContestJoin::where('contest_id', $contest_id)->count();
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    // get not participate contest
    public static function non_participate_contest($user_id)
    {
        try {
            $result = self::get_all_active_contest($user_id);
            // filter non participant contest
            $contest_id = [];
            foreach ($result as $key => $value) {
                array_push($contest_id, $value->id);
            }
            $parcipate_contest_id = [];
            $parcipate_contest = ContestJoin::where('user_id', $user_id)->get();
            foreach ($parcipate_contest as $value) {
                array_push($parcipate_contest_id, $value->contest_id);
            }
            $filter_result = Contest::whereIn('id', $contest_id)->whereNotIn('id', $parcipate_contest_id)->first();
            return $filter_result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // count non_participate_contest
    public static function count_non_participate_contest($user_id)
    {
        try {
            try {
                $result = self::get_all_active_contest($user_id);
                // filter non participant contest
                $contest_id = [];
                foreach ($result as $key => $value) {
                    array_push($contest_id, $value->id);
                }
                $parcipate_contest_id = [];
                $parcipate_contest = ContestJoin::where('user_id', $user_id)->get();
                foreach ($parcipate_contest as $value) {
                    array_push($parcipate_contest_id, $value->contest_id);
                }
                $filter_result = Contest::whereIn('id', $contest_id)->whereNotIn('id', $parcipate_contest_id)->count();
                return $filter_result;
            } catch (\Throwable $th) {
                throw $th;
                return 0;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // has non participate contest
    public static function has_non_participate_contest($user_id)
    {
        try {
            // First check if user should see contest menu at all (based on hidden groups)
            if (!\App\Services\contest\ContestVisibilityService::shouldShowContestMenu()) {
                return false; // User's groups are hidden, don't show popup
            }
            
            if (self::count_non_participate_contest($user_id) > 0) {
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            throw $th;
            return false;
        }
    }
    // get all active contest
    public static function get_all_active_contest($user_id)
    {
        try {
            $all_client_status = false;
            $contest_id = [];
            $client_country = ClientService::user_country_id($user_id);
            $all_client = Contest::where('user_type', 'trader')
                ->where('allowed_for', 'all_clients')
                ->whereDate('end_date', '>=', Carbon::now())
                ->where('status', 'active')
                ->get();
            foreach ($all_client as $key => $value) {
                // check global or specific country
                if ($value->is_global == 0) {
                    // for global
                    $all_client_status = true;
                    array_push($contest_id, $value->id);
                } else {
                    // for specific country
                    $has_country = ContestCountry::where('contest_id', $value->id)->where('country_id', $client_country)->exists();
                    if ($has_country) {
                        $all_client_status = true;
                        array_push($contest_id, $value->id);
                    }
                }
            }
            // check new registrations
            $new_registration_status = false;
            $register_date = ClientService::profile_created_at($user_id);
            $new_register = Contest::where('start_date', '<=', $register_date)
                ->where('end_date', '>=', $register_date)
                ->where('allowed_for', 'new_registration')
                ->where('status', 'active')
                ->get();
            foreach ($new_register as $key => $value) {
                // check is global or specific country
                if ($value->is_global != 0) {
                    $new_registration_status = true;
                    array_push($contest_id, $value->id);
                } else {
                    $has_country = ContestCountry::where('contest_id', $value->id)->where('country_id', $client_country)->exists();
                    if ($has_country) {
                        $new_registration_status = true;
                        array_push($contest_id, $value->id);
                    }
                }
            }
            // check new account
            $new_account_status = false;
            // last account open date
            $last_account_created_at = ClientService::last_account_created_at($user_id);
            // return $last_account_created_at;
            $new_account = Contest::whereDate('start_date', '<=', $last_account_created_at)
                ->whereDate('end_date', '>=', $last_account_created_at)
                ->where('allowed_for', 'new_account')->where('status', 'active')->get();
            foreach ($new_account as $key => $value) {
                // check is global or specific country
                if ($value->is_global != 0) {
                    $new_account_status = true;
                    array_push($contest_id, $value->id);
                } else {
                    $has_country = ContestCountry::where('contest_id', $value->id)->where('country_id', $client_country)->exists();
                    if ($has_country) {
                        $new_account_status = true;
                        array_push($contest_id, $value->id);
                    }
                }
            }
            // get all contest
            $contest = Contest::whereIn('id', $contest_id)->get();
            
            // Filter out contests where user's groups are hidden
            $filteredContests = $contest->filter(function($contest) use ($user_id) {
                if (!$contest->hidden_groups) {
                    return true; // No hidden groups, show contest
                }
                
                $hiddenGroups = json_decode($contest->hidden_groups, true);
                if (!is_array($hiddenGroups) || empty($hiddenGroups)) {
                    return true; // No hidden groups, show contest
                }
                
                // Get user's trading accounts and their groups
                $userGroups = \App\Models\TradingAccount::where('user_id', $user_id)
                    ->whereNotNull('group_id')
                    ->pluck('group_id')
                    ->unique()
                    ->toArray();
                
                if (empty($userGroups)) {
                    return true; // User has no groups, show contest
                }
                
                // If user has any group that is in hidden groups, hide contest
                if (array_intersect($userGroups, $hiddenGroups)) {
                    return false; // Hide contest
                }
                
                return true; // Show contest
            });
            
            return $filteredContests;
        } catch (\Throwable $th) {
            throw $th;
            return [];
        }
    }
    // client active contest
    public static function total_active($user_id)
    {
        try {
            $result = count(self::get_all_active_contest($user_id));
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return 0;
        }
    }
    public static function col_size($user_id)
    {
        try {
            $result = self::total_active($user_id);
            if ($result >= 2) {
                return 6;
            } else {
                return 12;
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 12;
        }
    }
    // get contst position
    public static function contest_position()
    {
        try {
            $active_contest =  Contest::whereDate('end_date', '>=', Carbon::now())
                ->select('contest_type', 'id')
                ->where('status', 'active')
                ->get();
            foreach ($active_contest as $value) {
                $start_date = date('Y-m-d', strtotime($value->start_date));
                $end_date = date('Y-m-d', strtotime($value->end_date));
                if ($value->contest_type === 'on_profit') {
                    $position = self::on_profit_position($value->id, $start_date, $end_date);
                    
                    // Get all participants for this contest
                    $participants = ContestJoin::where('contest_id', $value->id)->get();
                    $accountNumbers = $participants->pluck('account_number')->toArray();
                    
                    // Single bulk query to get all lot data
                    $lotData = \DB::connection('alternate')->table('MT4_TRADES')
                        ->select('LOGIN', \DB::raw('SUM(VOLUME) as total_lot'))
                        ->whereIn('LOGIN', $accountNumbers)
                        ->whereDate('CLOSE_TIME', '>=', $start_date)
                        ->whereDate('CLOSE_TIME', '<=', $end_date)
                        ->groupBy('LOGIN')
                        ->get()
                        ->keyBy('LOGIN');
                    
                    for ($i = 0; $i < count($position); $i++) {
                        $participant = $participants->where('user_id', $position[$i]['client'])->first();
                        $lot = $lotData->get($participant->account_number);
                        
                        // update contest join table for position, profit and lot
                        ContestJoin::where('contest_id', $value->id)->where('user_id', $position[$i]['client'])
                            ->update([
                                'total_profit' => $position[$i]['total_profit'],
                                'total_lot' => $lot ? $lot->total_lot : 0,
                                'position' => $i + 1,
                            ]);
                    }
                } elseif ($value->contest_type === 'on_profit_lot') {
                    $position = self::on_lot_position($value->id, $start_date, $end_date);
                    
                    // Get all participants for this contest
                    $participants = ContestJoin::where('contest_id', $value->id)->get();
                    $accountNumbers = $participants->pluck('account_number')->toArray();
                    
                    // Single bulk query to get all profit data
                    $profitData = \DB::connection('alternate')->table('MT4_TRADES')
                        ->select('LOGIN', \DB::raw('SUM(PROFIT) as total_profit'))
                        ->whereIn('LOGIN', $accountNumbers)
                        ->whereDate('CLOSE_TIME', '>=', $start_date)
                        ->whereDate('CLOSE_TIME', '<=', $end_date)
                        ->groupBy('LOGIN')
                        ->get()
                        ->keyBy('LOGIN');
                    
                    for ($i = 0; $i < count($position); $i++) {
                        $participant = $participants->where('user_id', $position[$i]['client'])->first();
                        $profit = $profitData->get($participant->account_number);
                        
                        // update contest join table for position, lot and profit
                        ContestJoin::where('contest_id', $value->id)->where('user_id', $position[$i]['client'])
                            ->update([
                                'total_lot' => $position[$i]['total_lot'],
                                'total_profit' => $profit ? $profit->total_profit : 0,
                                'position' => $i + 1,
                            ]);
                    }
                }
            }
            if ($active_contest) {
                return 'position successfully updated';
            }
            return 'No active contest found';
        } catch (\Throwable $th) {
            // throw $th;
        }
    }
    // on_profit position
    public static function on_profit_position($contest_id, $start_date, $end_date)
    {
        try {
            $all_client = ContestJoin::where('contest_id', $contest_id)->select('user_id', 'account_number')->get();
            
            if ($all_client->isEmpty()) {
                return [];
            }
            
            // Get all account numbers
            $accountNumbers = $all_client->pluck('account_number')->toArray();
            
            // Single bulk query to get all profit data
            $profitData = \DB::connection('alternate')->table('MT4_TRADES')
                ->select('LOGIN', \DB::raw('SUM(PROFIT) as total_profit'))
                ->whereIn('LOGIN', $accountNumbers)
                ->whereDate('CLOSE_TIME', '>=', $start_date)
                ->whereDate('CLOSE_TIME', '<=', $end_date)
                ->groupBy('LOGIN')
                ->get()
                ->keyBy('LOGIN');
            
            $profit_array = [];
            foreach ($all_client as $value) {
                $profit = $profitData->get($value->account_number);
                $profit_array[] = [
                    'client' => $value->user_id,
                    'total_profit' => $profit ? $profit->total_profit : 0,
                ];
            }
            
            // Sort the array based on 'profit' in descending order
            usort($profit_array, function ($a, $b) {
                return $b['total_profit'] - $a['total_profit'];
            });
            return $profit_array;
        } catch (\Throwable $th) {
            // \Log::error('Error in on_profit_position', [
            //     'contest_id' => $contest_id,
            //     'error' => $th->getMessage()
            // ]);
            return [];
        }
    }
    // on_lot position
    public  static function on_lot_position($contest_id, $start_date, $end_date)
    {
        try {
            $all_client = ContestJoin::where('contest_id', $contest_id)->select('user_id', 'account_number')->get();
            
            if ($all_client->isEmpty()) {
                return [];
            }
            
            // Get all account numbers
            $accountNumbers = $all_client->pluck('account_number')->toArray();
            
            // Single bulk query to get all lot data
            $lotData = \DB::connection('alternate')->table('MT4_TRADES')
                ->select('LOGIN', \DB::raw('SUM(VOLUME) as total_lot'))
                ->whereIn('LOGIN', $accountNumbers)
                ->whereDate('CLOSE_TIME', '>=', $start_date)
                ->whereDate('CLOSE_TIME', '<=', $end_date)
                ->groupBy('LOGIN')
                ->get()
                ->keyBy('LOGIN');
            
            $lot_array = [];
            foreach ($all_client as $value) {
                $lot = $lotData->get($value->account_number);
                $lot_array[] = [
                    'client' => $value->user_id,
                    'total_lot' => $lot ? $lot->total_lot : 0,
                ];
            }

            // Sort the array based on 'lot' in descending order
            usort($lot_array, function ($a, $b) {
                return $b['total_lot'] - $a['total_lot'];
            });
            return $lot_array;
        } catch (\Throwable $th) {
            // \Log::error('Error in on_lot_position', [
            //     'contest_id' => $contest_id,
            //     'error' => $th->getMessage()
            // ]);
            return [];
        }
    }
    // get contest popup by contest ID
    public  static function contest_popup_file($contest_id)
    {
        try {
            $contest = Contest::find($contest_id);
            $contest_file = FileApiService::contabo_file_path(isset($contest->popup_image) ? $contest->popup_image : '')['dataUrl'];
            return $contest_file;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Calculate equity-based position for contest participants
     */
    public static function on_equity_position($contest_id, $start_date, $end_date)
    {
        try {
            $all_client = ContestJoin::where('contest_id', $contest_id)->select('user_id', 'account_number')->get();
            
            if ($all_client->isEmpty()) {
                return [];
            }
            
            // Get all account numbers
            $accountNumbers = $all_client->pluck('account_number')->toArray();
            
            $equity_array = [];
            foreach ($all_client as $value) {
                $equity_score = self::calculateEquityScore($value->account_number, $start_date, $end_date);
                $equity_array[] = [
                    'client' => $value->user_id,
                    'total_equity' => $equity_score,
                ];
            }
            
            // Sort the array based on 'equity' in descending order
            usort($equity_array, function ($a, $b) {
                return $b['total_equity'] - $a['total_equity'];
            });
            return $equity_array;
        } catch (\Throwable $th) {
            // \Log::error('Error in on_equity_position', [
            //     'contest_id' => $contest_id,
            //     'error' => $th->getMessage()
            // ]);
            return [];
        }
    }

    /**
     * Calculate equity score for a specific account
     */
    private static function calculateEquityScore($account_number, $start_date, $end_date)
    {
        try {
            // Get current equity from MT4/MT5
            $current_equity = self::getCurrentEquity($account_number);
            
            // Get starting balance from contest start date
            $starting_balance = self::getStartingBalance($account_number, $start_date);
            
            if ($starting_balance <= 0) {
                return 0;
            }
            
            // Calculate equity growth percentage
            $equity_growth = (($current_equity - $starting_balance) / $starting_balance) * 100;
            
            return $equity_growth;
            
        } catch (\Throwable $th) {
            // \Log::error('Error calculating equity score', [
            //     'account_number' => $account_number,
            //     'error' => $th->getMessage()
            // ]);
            return 0;
        }
    }

    /**
     * Get current equity from MT4/MT5
     */
    private static function getCurrentEquity($account_number)
    {
        try {
            // Try to get from MT4_TRADES table first (for balance)
            $balance = \DB::connection('alternate')->table('MT4_TRADES')
                ->where('LOGIN', $account_number)
                ->orderBy('CLOSE_TIME', 'desc')
                ->value('BALANCE') ?? 0;
                
            // Get floating P&L from open trades
            $floating_pnl = \DB::connection('alternate')->table('MT4_TRADES')
                ->where('LOGIN', $account_number)
                ->where('CLOSE_TIME', '0000-00-00 00:00:00') // Open trades
                ->sum('PROFIT');
                
            $current_equity = $balance + $floating_pnl;
            
            return $current_equity;
            
        } catch (\Throwable $th) {
            // \Log::error('Error getting current equity', [
            //     'account_number' => $account_number,
            //     'error' => $th->getMessage()
            // ]);
            return 0;
        }
    }

    /**
     * Get starting balance from contest start date
     */
    private static function getStartingBalance($account_number, $start_date)
    {
        try {
            // Get balance at contest start date
            $starting_balance = \DB::connection('alternate')->table('MT4_TRADES')
                ->where('LOGIN', $account_number)
                ->where('CLOSE_TIME', '<=', $start_date)
                ->orderBy('CLOSE_TIME', 'desc')
                ->value('BALANCE') ?? 0;
                
            return $starting_balance;
            
        } catch (\Throwable $th) {
            // \Log::error('Error getting starting balance', [
            //     'account_number' => $account_number,
            //     'start_date' => $start_date,
            //     'error' => $th->getMessage()
            // ]);
            return 0;
        }
    }
}
