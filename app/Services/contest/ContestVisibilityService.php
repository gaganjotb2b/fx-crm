<?php

namespace App\Services\contest;

use App\Models\Contest;
use App\Models\TradingAccount;
use Illuminate\Support\Facades\Auth;

class ContestVisibilityService
{
    /**
     * Check if contest menu should be visible for the current user
     * 
     * @return bool
     */
    public static function shouldShowContestMenu()
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        
        // Clear any cached results for this user
        \Cache::forget('contest_visibility_' . $user->id);

        // Get user's client_groups from pro_users table
        $userClientGroups = [];
        if ($user->client_groups) {
            $userClientGroups = json_decode($user->client_groups, true);
            if (is_array($userClientGroups)) {
                $userClientGroups = array_map('intval', $userClientGroups); // Convert to integers
            } else {
                $userClientGroups = [];
            }
        }

        // \Log::info('Contest visibility check from pro_users', [
        //     'user_id' => $user->id,
        //     'user_client_groups' => $userClientGroups,
        //     'user_client_groups_count' => count($userClientGroups),
        //     'raw_client_groups' => $user->client_groups
        // ]);

        if (empty($userClientGroups)) {
            // \Log::info('User has no client groups, showing contest menu');
            return true; // If user has no client groups, show contest menu
        }

        // Check if any active contest has hidden groups that match user's groups
        $activeContests = Contest::where('status', 'active')->get();
        
        foreach ($activeContests as $contest) {
            if ($contest->hidden_groups) {
                $hiddenGroups = json_decode($contest->hidden_groups, true);
                if (is_array($hiddenGroups)) {
                    // Convert hidden groups to integers for proper comparison
                    $hiddenGroups = array_map('intval', $hiddenGroups);
                    
                    // \Log::info('Checking contest hidden groups', [
                    //     'contest_id' => $contest->id,
                    //     'contest_name' => $contest->contest_name,
                    //     'hidden_groups' => $hiddenGroups,
                    //     'user_client_groups' => $userClientGroups,
                    //     'intersection' => array_intersect($userClientGroups, $hiddenGroups)
                    // ]);
                    
                    // If user has any group that is in hidden groups, hide contest menu
                    if (array_intersect($userClientGroups, $hiddenGroups)) {
                        // \Log::info('User has hidden group, hiding contest menu', [
                        //     'user_id' => $user->id,
                        //     'hidden_groups' => $hiddenGroups,
                        //     'user_client_groups' => $userClientGroups
                        // ]);
                        return false;
                    }
                }
            }
        }

        // \Log::info('User has no hidden groups, showing contest menu');
        return true;
    }

    /**
     * Get hidden groups for a specific contest
     * 
     * @param int $contestId
     * @return array
     */
    public static function getHiddenGroups($contestId)
    {
        $contest = Contest::find($contestId);
        if (!$contest || !$contest->hidden_groups) {
            return [];
        }

        return json_decode($contest->hidden_groups, true) ?? [];
    }
} 