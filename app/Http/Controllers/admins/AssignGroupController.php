<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\IB;
use App\Models\ClientGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignGroupController extends Controller
{
    /**
     * Show the assign group page
     */
    public function index()
    {
        return view('admins.assign-group.index');
    }



    /**
     * Get all client groups for dropdown
     */
    public function getClientGroups()
    {
        try {
            $clientGroups = ClientGroup::where('visibility', 'visible')
                ->where('active_status', 1)
                ->select('id', 'group_name', 'group_id', 'server', 'account_category')
                ->orderBy('group_name', 'asc')
                ->get();
            
            return response()->json([
                'status' => true,
                'data' => $clientGroups
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getClientGroups: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching client groups',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Assign selected groups to selected users (with batch processing for large datasets)
     */
    public function assignGroupsToUsers(Request $request)
    {
        \Log::info('AssignGroup assignGroupsToUsers called with data: ' . json_encode($request->all()));
        
        // Check if this is a batch request
        $batchId = $request->input('batch_id');
        $batchNumber = $request->input('batch_number', 0);
        $totalBatches = $request->input('total_batches', 1);
        $isFirstBatch = $request->input('is_first_batch', false);
        
        if ($isFirstBatch) {
            \Log::info("Starting batch processing - Total batches: {$totalBatches}");
        }
        
        \Log::info("Processing batch {$batchNumber} of {$totalBatches}");
        
        // Ensure we have arrays
        $userIds = $request->input('user_ids', []);
        $groupIds = $request->input('group_ids', []);
        
        // Convert to arrays if they're not already
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }
        if (!is_array($groupIds)) {
            $groupIds = [$groupIds];
        }
        
        \Log::info('Batch user_ids count: ' . count($userIds));
        \Log::info('Processed group_ids: ' . json_encode($groupIds));
        
        $validator = Validator::make([
            'user_ids' => $userIds,
            'group_ids' => $groupIds
        ], [
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|integer|exists:users,id',
            'group_ids' => 'required|array|min:1',
            'group_ids.*' => 'required|integer|exists:client_groups,id'
        ]);

        if ($validator->fails()) {
            \Log::info('Validation failed: ' . json_encode($validator->errors()));
            return response()->json([
                'status' => false,
                'message' => 'Please provide valid user IDs and group IDs',
                'errors' => $validator->errors()
            ]);
        }

        try {
            \Log::info('Updating batch users: ' . implode(', ', $userIds) . ' with groups: ' . implode(', ', $groupIds));
            
            // Use database transaction for better performance
            \DB::beginTransaction();
            
            // Update users in batch using chunk processing
            $updatedCount = 0;
            $errors = [];
            
            // Process users in smaller chunks to avoid memory issues
            $chunkSize = 100;
            $userChunks = array_chunk($userIds, $chunkSize);
            
            foreach ($userChunks as $chunk) {
                try {
                    // Use bulk update for better performance
                    $result = User::whereIn('id', $chunk)
                        ->update(['client_groups' => json_encode($groupIds)]);
                    
                    $updatedCount += $result;
                    
                    \Log::info("Updated chunk of " . count($chunk) . " users, result: {$result}");
                } catch (\Exception $e) {
                    $errors[] = "Error updating chunk: " . $e->getMessage();
                    \Log::error("Error updating chunk: " . $e->getMessage());
                }
            }
            
            \DB::commit();
            
            $response = [
                'status' => true,
                'message' => "Successfully updated {$updatedCount} users in batch {$batchNumber}",
                'data' => [
                    'batch_number' => $batchNumber,
                    'total_batches' => $totalBatches,
                    'updated_count' => $updatedCount,
                    'batch_users' => count($userIds),
                    'group_ids' => $groupIds,
                    'errors' => $errors,
                    'is_complete' => ($batchNumber >= $totalBatches)
                ]
            ];
            
            \Log::info('Batch response: ' . json_encode($response));
            return response()->json($response);

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Error in assignGroupsToUsers batch: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while assigning groups to users',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Search for users by email and get their associated reference users
     */
    public function searchUsers(Request $request)
    {
        // Add debugging
        \Log::info('AssignGroup searchUsers called with email: ' . $request->email);
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            \Log::info('Validation failed: ' . json_encode($validator->errors()));
            return response()->json([
                'status' => false,
                'message' => 'Please enter a valid email address',
                'errors' => $validator->errors()
            ]);
        }

        try {
            // Find the user by email
            $user = User::where('email', $request->email)->first();
            \Log::info('User search result: ' . ($user ? 'Found user ID: ' . $user->id . ', Name: ' . $user->name : 'User not found'));

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'No user found with this email address'
                ]);
            }

            // Get all associated users recursively (all levels)
            $startTime = microtime(true);
            $allAssociatedUserIds = $this->getAllAssociatedUserIds($user->id);
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);
            \Log::info("Recursive search completed in {$executionTime} seconds");
            \Log::info('All associated user IDs (all levels): ' . count($allAssociatedUserIds) . ' users found');

            // For testing purposes, let's also check if there are any direct IB records
            $directIbRecords = IB::where('ib_id', $user->id)->get();
            \Log::info('Direct IB records for user ' . $user->id . ': ' . $directIbRecords->count());
            if ($directIbRecords->count() > 0) {
                \Log::info('Direct reference IDs: ' . $directIbRecords->pluck('reference_id')->implode(', '));
            }

            if (empty($allAssociatedUserIds)) {
                // Fallback: try simple first-level search
                \Log::info('No users found with recursive search, trying simple first-level search...');
                $directIbRecords = IB::where('ib_id', $user->id)->get();
                
                if ($directIbRecords->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No associated users found for this IB. Please check if this user has any associated users in the IB table.'
                    ]);
                }
                
                // Use simple first-level search
                $allAssociatedUserIds = $directIbRecords->pluck('reference_id')->toArray();
                \Log::info('Using simple first-level search, found user IDs: ' . implode(', ', $allAssociatedUserIds));
            }

            // Get all users with those reference_ids - optimized query with chunking for large datasets
            $associatedUsers = collect();
            $chunkSize = 1000; // Process in chunks of 1000
            
            foreach (array_chunk($allAssociatedUserIds, $chunkSize) as $chunk) {
                $chunkUsers = User::whereIn('id', $chunk)
                    ->select('id', 'name', 'email', 'client_groups')
                    ->get();
                $associatedUsers = $associatedUsers->merge($chunkUsers);
            }
            
            \Log::info('Associated users found (all levels): ' . $associatedUsers->count());

            // Get all client groups for mapping
            $clientGroups = ClientGroup::select('id', 'group_name', 'group_id')
                ->get()
                ->keyBy('id');

            // Create a combined list with IB user first, then associated users
            $allUsers = collect();
            
            // Add the IB user first with a special flag
            $ibUserGroups = $this->getUserGroupNames($user->client_groups, $clientGroups);
            $allUsers->push([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_ib_user' => true,
                'groups' => $ibUserGroups,
                'level' => 0
            ]);
            
            // Add all associated users with their levels
            $levelCounts = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            
            foreach ($associatedUsers as $associatedUser) {
                $userGroups = $this->getUserGroupNames($associatedUser->client_groups, $clientGroups);
                $level = $this->getUserLevel($user->id, $associatedUser->id);
                $levelCounts[$level]++;
                
                $allUsers->push([
                    'id' => $associatedUser->id,
                    'name' => $associatedUser->name,
                    'email' => $associatedUser->email,
                    'is_ib_user' => false,
                    'groups' => $userGroups,
                    'level' => $level
                ]);
            }
            
            \Log::info("Final level distribution - Level 0: {$levelCounts[0]}, Level 1: {$levelCounts[1]}, Level 2: {$levelCounts[2]}, Level 3: {$levelCounts[3]}, Level 4: {$levelCounts[4]}, Level 5: {$levelCounts[5]}");

            $response = [
                'status' => true,
                'message' => 'Users found successfully',
                'data' => [
                    'ib_user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ],
                    'associated_users' => $allUsers,
                    'total_users' => $allUsers->count()
                ]
            ];
            
            \Log::info('Response: ' . json_encode($response));
            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('Error in searchUsers: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while searching for users',
                'error' => $e->getMessage()
            ]);
        }
    }

        /**
     * Get all associated user IDs recursively (all levels) - Optimized for speed
     */
    private function getAllAssociatedUserIds($ibUserId, $maxDepth = 10)
    {
        \Log::info("Starting optimized recursive search for IB user ID: {$ibUserId}");
        
        // Use a single optimized query to get all associated users at once
        $allAssociatedUsers = \DB::table('ib as i1')
            ->select('i1.reference_id as level1_id')
            ->where('i1.ib_id', $ibUserId)
            ->union(
                \DB::table('ib as i1')
                    ->join('ib as i2', 'i1.reference_id', '=', 'i2.ib_id')
                    ->select('i2.reference_id as level1_id')
                    ->where('i1.ib_id', $ibUserId)
            )
            ->union(
                \DB::table('ib as i1')
                    ->join('ib as i2', 'i1.reference_id', '=', 'i2.ib_id')
                    ->join('ib as i3', 'i2.reference_id', '=', 'i3.ib_id')
                    ->select('i3.reference_id as level1_id')
                    ->where('i1.ib_id', $ibUserId)
            )
            ->union(
                \DB::table('ib as i1')
                    ->join('ib as i2', 'i1.reference_id', '=', 'i2.ib_id')
                    ->join('ib as i3', 'i2.reference_id', '=', 'i3.ib_id')
                    ->join('ib as i4', 'i3.reference_id', '=', 'i4.ib_id')
                    ->select('i4.reference_id as level1_id')
                    ->where('i1.ib_id', $ibUserId)
            )
            ->union(
                \DB::table('ib as i1')
                    ->join('ib as i2', 'i1.reference_id', '=', 'i2.ib_id')
                    ->join('ib as i3', 'i2.reference_id', '=', 'i3.ib_id')
                    ->join('ib as i4', 'i3.reference_id', '=', 'i4.ib_id')
                    ->join('ib as i5', 'i4.reference_id', '=', 'i5.ib_id')
                    ->select('i5.reference_id as level1_id')
                    ->where('i1.ib_id', $ibUserId)
            )
            ->get();
        
        $allUserIds = $allAssociatedUsers->pluck('level1_id')->unique()->filter(function($id) use ($ibUserId) {
            return $id != $ibUserId;
        })->values()->toArray();
        
        // Debug: Let's also get the breakdown by levels
        $level1Users = IB::where('ib_id', $ibUserId)->pluck('reference_id')->toArray();
        $level2Users = IB::whereIn('ib_id', $level1Users)->pluck('reference_id')->toArray();
        $level3Users = IB::whereIn('ib_id', $level2Users)->pluck('reference_id')->toArray();
        
        \Log::info("Level breakdown - Level 1: " . count($level1Users) . ", Level 2: " . count($level2Users) . ", Level 3: " . count($level3Users));
        \Log::info("Optimized query found " . count($allUserIds) . " unique associated users across all levels");
        
        return $allUserIds;
    }

    /**
     * Get the level of a user in the hierarchy from the main IB user - Optimized
     */
    private function getUserLevel($mainIbUserId, $targetUserId, $maxDepth = 5)
    {
        // Check level 1
        $level1Users = IB::where('ib_id', $mainIbUserId)->pluck('reference_id')->toArray();
        if (in_array($targetUserId, $level1Users)) {
            return 1;
        }
        
        // Check level 2
        $level2Users = IB::whereIn('ib_id', $level1Users)->pluck('reference_id')->toArray();
        if (in_array($targetUserId, $level2Users)) {
            return 2;
        }
        
        // Check level 3
        $level3Users = IB::whereIn('ib_id', $level2Users)->pluck('reference_id')->toArray();
        if (in_array($targetUserId, $level3Users)) {
            return 3;
        }
        
        // Check level 4
        $level4Users = IB::whereIn('ib_id', $level3Users)->pluck('reference_id')->toArray();
        if (in_array($targetUserId, $level4Users)) {
            return 4;
        }
        
        // Check level 5
        $level5Users = IB::whereIn('ib_id', $level4Users)->pluck('reference_id')->toArray();
        if (in_array($targetUserId, $level5Users)) {
            return 5;
        }
        
        // If not found in any level, log it for debugging
        \Log::warning("User ID {$targetUserId} not found in any level for IB user {$mainIbUserId}");
        return 1; // Default to level 1 if not found
    }

    /**
     * Get group names for a user based on their client_groups field
     */
    private function getUserGroupNames($clientGroups, $allGroups)
    {
        if (empty($clientGroups) || $clientGroups === 'null' || $clientGroups === '[]') {
            return 'No groups assigned';
        }

        try {
            $groupIds = json_decode($clientGroups, true);
            
            if (!is_array($groupIds) || empty($groupIds)) {
                return 'No groups assigned';
            }

            $groupNames = [];
            foreach ($groupIds as $groupId) {
                if (isset($allGroups[$groupId])) {
                    $groupNames[] = $allGroups[$groupId]->group_name . ' (' . $allGroups[$groupId]->group_id . ')';
                }
            }

            return empty($groupNames) ? 'No groups assigned' : implode(', ', $groupNames);
        } catch (\Exception $e) {
            \Log::error('Error parsing client_groups for user: ' . $e->getMessage());
            return 'Error parsing groups';
        }
    }
} 