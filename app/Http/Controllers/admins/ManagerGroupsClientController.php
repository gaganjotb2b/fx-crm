<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ManagerUser;
use App\Models\ClientGroup;

class ManagerGroupsClientController extends Controller
{
    public function index()
    {
        $managers = User::where('type', 5)->where('id', 3787)->get();
        $clientGroups = ClientGroup::where('visibility', 'visible')
            ->where('active_status', 1)
            ->select('id', 'group_name', 'group_id', 'server', 'account_category')
            ->orderBy('group_name', 'asc')
            ->get();
        return view('admins.client-management.manager-groups', compact('managers', 'clientGroups'));
    }

    public function getAssignedUsers(Request $request)
    {
        $managerId = $request->input('manager_id');
        
        // Get the manager details first
        $manager = User::find($managerId);
        if (!$manager) {
            return response()->json(['users' => []]);
        }
        
        // Get all users assigned to this manager
        $allAssignedUsers = ManagerUser::join('users', 'manager_users.user_id', '=', 'users.id')
            ->where('manager_users.manager_id', $managerId)
            ->select('users.id', 'users.name', 'users.email', 'users.client_groups')
            ->orderBy('users.name', 'asc')
            ->get();
        
        // Get all client groups for mapping
        $clientGroups = ClientGroup::select('id', 'group_name', 'group_id')
            ->get()
            ->keyBy('id');
        
        // Process manager to include their groups
        $managerGroups = $this->getUserGroupNames($manager->client_groups, $clientGroups);
        $managerData = [
            'id' => $manager->id,
            'name' => $manager->name,
            'email' => $manager->email,
            'groups' => $managerGroups,
            'is_manager' => true
        ];
        
        // Process each user to include their groups
        $usersWithGroups = $allAssignedUsers->map(function($user) use ($clientGroups) {
            $userGroups = $this->getUserGroupNames($user->client_groups, $clientGroups);
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'groups' => $userGroups,
                'is_manager' => false
            ];
        });
        
        // Combine manager and users, with manager at the top
        $allUsers = collect([$managerData])->merge($usersWithGroups);
        
        return response()->json(['users' => $allUsers]);
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

    public function assignGroupsToUsers(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        $groupIds = $request->input('group_ids', []);
        
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }
        if (!is_array($groupIds)) {
            $groupIds = [$groupIds];
        }
        
        try {
            $updatedCount = 0;
            
            foreach ($userIds as $userId) {
                $user = User::find($userId);
                if ($user) {
                    $user->client_groups = json_encode($groupIds);
                    $user->save();
                    $updatedCount++;
                }
            }
            
            return response()->json([
                'status' => true,
                'message' => "Successfully assigned groups to {$updatedCount} users",
                'data' => [
                    'updated_count' => $updatedCount,
                    'user_ids' => $userIds,
                    'group_ids' => $groupIds
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in assignGroupsToUsers: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while assigning groups to users',
                'error' => $e->getMessage()
            ]);
        }
    }
} 