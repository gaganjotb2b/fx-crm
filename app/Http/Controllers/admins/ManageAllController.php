<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\UserHierarchy;
use App\Models\UserPermissionsInheritance;
use Spatie\Permission\Models\Permission;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;

class ManageAllController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:manage all"]);
        $this->middleware(AllFunctionService::access('manager_settings', 'admin'));
    }

    /**
     * Main view with tree structure
     */
    public function index()
    {
        $hierarchy = $this->getHierarchyTree();
        $permissions = Permission::all();
        $users = User::whereIn('type', [5, 6, 7])->get(); // 5 = Manager, 6 = Admin Manager, 7 = Country Admin
        
        return view('admins.manager-settings.manage-all', [
            'hierarchy' => $hierarchy,
            'permissions' => $permissions,
            'users' => $users
        ]);
    }

    /**
     * Get hierarchical tree data
     */
    public function getHierarchyTree()
    {
        // Build tree structure starting from admin managers
        $adminManagers = User::where('type', 6)->get(); // 6 = Admin Manager
        
        $tree = [];
        foreach ($adminManagers as $adminManager) {
            $tree[] = [
                'id' => $adminManager->id,
                'name' => $adminManager->name,
                'email' => $adminManager->email,
                'type' => 'admin_manager',
                'children' => $this->getCountryAdmins($adminManager->id)
            ];
        }
        
        return $tree;
    }

    /**
     * Get country admins under admin manager
     */
    private function getCountryAdmins($adminManagerId)
    {
        $countryAdmins = UserHierarchy::where('parent_user_id', $adminManagerId)
            ->where('hierarchy_level', 'country_admin')
            ->with('childUser')
            ->get();

        $countryAdminTree = [];
        foreach ($countryAdmins as $countryAdmin) {
            $countryAdminTree[] = [
                'id' => $countryAdmin->childUser->id,
                'name' => $countryAdmin->childUser->name,
                'email' => $countryAdmin->childUser->email,
                'type' => 'country_admin',
                'children' => $this->getManagers($countryAdmin->childUser->id)
            ];
        }
        return $countryAdminTree;
    }

    /**
     * Get managers under country admin
     */
    private function getManagers($countryAdminId)
    {
        $managers = UserHierarchy::where('parent_user_id', $countryAdminId)
            ->where('hierarchy_level', 'manager')
            ->with('childUser')
            ->get();

        $managerTree = [];
        foreach ($managers as $manager) {
            $managerTree[] = [
                'id' => $manager->childUser->id,
                'name' => $manager->childUser->name,
                'email' => $manager->childUser->email,
                'type' => 'manager',
                'children' => []
            ];
        }
        return $managerTree;
    }

    /**
     * Add new user to hierarchy
     */
    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_user_id' => 'required|exists:users,id',
            'child_user_id' => 'required|exists:users,id',
            'hierarchy_level' => 'required|in:country_admin,manager'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }

        // Check if user is already in hierarchy
        $existingHierarchy = UserHierarchy::where('child_user_id', $request->child_user_id)->first();
        if ($existingHierarchy) {
            return Response::json([
                'status' => false,
                'message' => 'User is already assigned to a hierarchy'
            ]);
        }

        // Validate that child user type matches the hierarchy level
        $childUser = User::find($request->child_user_id);
        if (!$this->validateUserTypeForLevel($childUser, $request->hierarchy_level)) {
            return Response::json([
                'status' => false,
                'message' => 'User type does not match the selected hierarchy level'
            ]);
        }

        // Validate hierarchy level
        if (!$this->validateHierarchyLevel($request->parent_user_id, $request->hierarchy_level)) {
            return Response::json([
                'status' => false,
                'message' => 'Invalid hierarchy level for this parent'
            ]);
        }

        try {
            UserHierarchy::create([
                'parent_user_id' => $request->parent_user_id,
                'child_user_id' => $request->child_user_id,
                'hierarchy_level' => $request->hierarchy_level
            ]);

            return Response::json([
                'status' => true,
                'message' => 'User added to hierarchy successfully'
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to add user to hierarchy: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Validate hierarchy level
     */
    private function validateHierarchyLevel($parentUserId, $childLevel)
    {
        $parentUser = User::find($parentUserId);
        
        if (!$parentUser) {
            return false;
        }

        // Admin Manager (type 6) can add Country Admins (type 7) as 'country_admin' level
        if ($parentUser->type === 6 && $childLevel === 'country_admin') {
            return true;
        }

        // Country Admin (type 7) can add Managers (type 5) as 'manager' level
        if ($parentUser->type === 7 && $childLevel === 'manager') {
            return true;
        }

        return false;
    }

    /**
     * Validate user type matches hierarchy level
     */
    private function validateUserTypeForLevel($user, $level)
    {
        if (!$user) {
            return false;
        }

        // Map hierarchy levels to user types (new structure)
        $validTypes = [
            'country_admin' => [7], // Country Admin
            'manager' => [5] // Manager
        ];

        return isset($validTypes[$level]) && in_array($user->type, $validTypes[$level]);
    }

    /**
     * Remove user from hierarchy
     */
    public function removeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }

        try {
            // Remove user from hierarchy
            UserHierarchy::where('child_user_id', $request->user_id)->delete();
            
            // Remove all permissions
            UserPermissionsInheritance::where('user_id', $request->user_id)->delete();

            return Response::json([
                'status' => true,
                'message' => 'User removed from hierarchy successfully'
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to remove user: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Assign permissions to user
     */
    public function assignPermissions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'permissions' => 'required|array',
            'permissions.*.permission_id' => 'required|exists:permissions,id',
            'permissions.*.can_edit' => 'boolean',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_delete' => 'boolean'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }

        try {
            // Validate that current user can assign these permissions
            if (!UserPermissionsInheritance::validateInheritance(
                auth()->user()->id, 
                $request->user_id, 
                $request->permissions
            )) {
                return Response::json([
                    'status' => false,
                    'message' => 'You can only assign permissions that you have'
                ]);
            }

            // Assign permissions
            UserPermissionsInheritance::assignPermissions(
                $request->user_id,
                auth()->user()->id,
                $request->permissions
            );

            return Response::json([
                'status' => true,
                'message' => 'Permissions assigned successfully'
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to assign permissions: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get available permissions for user
     */
    public function getAvailablePermissions($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return Response::json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }

        // Get parent user
        $parentHierarchy = UserHierarchy::where('child_user_id', $userId)->first();
        
        if (!$parentHierarchy) {
            return Response::json([
                'status' => false,
                'message' => 'User not in hierarchy'
            ]);
        }

        // Get parent's permissions
        $parentPermissions = UserPermissionsInheritance::getInheritablePermissions($parentHierarchy->parent_user_id);
        
        // Get user's current permissions
        $userPermissions = UserPermissionsInheritance::getUserPermissions($userId);

        return Response::json([
            'status' => true,
            'parent_permissions' => $parentPermissions,
            'user_permissions' => $userPermissions
        ]);
    }

    /**
     * Get users available for hierarchy
     */
    public function getAvailableUsers(Request $request)
    {
        $level = $request->get('level', 'admin');
        $parentId = $request->get('parent_id');
        
        // Get all managers from the existing endpoint
        $result = User::select(
            'users.name',
            'users.id',
            'users.email',
            'users.type',
            'manager_groups.group_name',
            'manager_groups.group_type'
        )->whereIn('type', [5, 6, 7])
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
            ->get();
        
        // Filter based on level
        if ($level === 'admin_manager') {
            // Filter for Admin Managers (type=6 or group_type indicates admin manager)
            $users = $result->filter(function($user) {
                return $user->type == 6 || $user->group_type == 6 || $user->type == 'admin_user';
            })->values();
        } elseif ($level === 'country_admin') {
            // Filter for Country Managers (type=7 or group_type indicates country manager)
            $users = $result->filter(function($user) {
                return $user->type == 7 || $user->group_type == 7 || $user->type == 'country_admin';
            })->values();
        } elseif ($level === 'manager') {
            // Filter for Account Managers (type=5 or group_type indicates manager)
            $users = $result->filter(function($user) {
                return $user->type == 5 || $user->group_type == 5 || $user->type == 'manager' || $user->type == 'account_manager';
            })->values();
        } else {
            $users = collect([]);
        }
        
        // Format the response
        $formattedUsers = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type,
                'group_name' => $user->group_name,
                'group_type' => $user->group_type
            ];
        });
        
        // Add debugging information
        // \Log::info('getAvailableUsers called with level: ' . $level);
        // \Log::info('Total users found: ' . $result->count());
        // \Log::info('Filtered users for level ' . $level . ': ' . $users->count());
        // \Log::info('Users data: ' . $formattedUsers->toJson());
        
        // Debug: Show all unique types in the database
        $allTypes = $result->pluck('type')->unique()->values();
        $allGroupTypes = $result->pluck('group_type')->unique()->values();
        // \Log::info('All types found in database: ' . $allTypes->toJson());
        // \Log::info('All group_types found in database: ' . $allGroupTypes->toJson());
        
        return Response::json([
            'status' => true,
            'users' => $formattedUsers,
            'debug' => [
                'level' => $level,
                'total_users' => $result->count(),
                'filtered_users' => $users->count()
            ]
        ]);
    }

    /**
     * Get hierarchy data for AJAX
     */
    public function getHierarchyData()
    {
        $hierarchy = $this->getHierarchyTree();
        
        return Response::json([
            'status' => true,
            'hierarchy' => $hierarchy
        ]);
    }

    /**
     * Get available hierarchy levels based on parent user
     */
    public function getAvailableHierarchyLevels(Request $request)
    {
        $parentId = $request->get('parent_id');
        $parentUser = User::find($parentId);
        
        if (!$parentUser) {
            return Response::json([
                'status' => false,
                'message' => 'Parent user not found'
            ]);
        }

        $levels = [];
        
        // Admin Manager (type 6) can add Country Admins
        if ($parentUser->type === 6) {
            $levels[] = [
                'value' => 'country_admin',
                'label' => 'Country Admin'
            ];
        }
        
        // Country Admin (type 7) can add Managers
        if ($parentUser->type === 7) {
            $levels[] = [
                'value' => 'manager',
                'label' => 'Manager'
            ];
        }

        return Response::json([
            'status' => true,
            'levels' => $levels
        ]);
    }

    /**
     * Get assigned Country Managers for a specific Admin Manager
     */
    public function getAssignedCountryManagers(Request $request)
    {
        $adminManagerId = $request->get('admin_manager_id');
        
        if (!$adminManagerId) {
            return Response::json([
                'status' => false,
                'message' => 'Admin Manager ID is required'
            ]);
        }
        
        try {
            // Get assigned Country Managers from the assignments table
            $assignedCountryManagers = DB::table('admin_country_assignments')
                ->join('users', 'admin_country_assignments.country_admin_id', '=', 'users.id')
                ->where('admin_country_assignments.admin_manager_id', $adminManagerId)
                ->select('users.id', 'users.name', 'users.email')
                ->get();
            
            return Response::json([
                'status' => true,
                'country_managers' => $assignedCountryManagers
            ]);
            
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to get assigned Country Managers: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get all assigned Country Managers (globally)
     */
    public function getAllAssignedCountryManagers(Request $request)
    {
        try {
            // Get all assigned Country Managers from the assignments table
            $allAssignedCountryManagers = DB::table('admin_country_assignments')
                ->join('users', 'admin_country_assignments.country_admin_id', '=', 'users.id')
                ->select('users.id', 'users.name', 'users.email')
                ->distinct()
                ->get();
            
            return Response::json([
                'status' => true,
                'country_managers' => $allAssignedCountryManagers
            ]);
            
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to get all assigned Country Managers: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get assigned Managers for a specific Country Manager
     */
    public function getAssignedManagers(Request $request)
    {
        $countryManagerId = $request->get('country_manager_id');
        
        if (!$countryManagerId) {
            return Response::json([
                'status' => false,
                'message' => 'Country Manager ID is required'
            ]);
        }
        
        try {
            // Get assigned Managers from the assignments table
            $assignedManagers = DB::table('country_manager_assignments')
                ->join('users', 'country_manager_assignments.manager_id', '=', 'users.id')
                ->where('country_manager_assignments.country_manager_id', $countryManagerId)
                ->select('users.id', 'users.name', 'users.email')
                ->get();
            
            return Response::json([
                'status' => true,
                'managers' => $assignedManagers
            ]);
            
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to get assigned Managers: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get all assigned Managers (globally)
     */
    public function getAllAssignedManagers(Request $request)
    {
        try {
            // Get all assigned Managers from the assignments table
            $allAssignedManagers = DB::table('country_manager_assignments')
                ->join('users', 'country_manager_assignments.manager_id', '=', 'users.id')
                ->select('users.id', 'users.name', 'users.email')
                ->distinct()
                ->get();
            
            return Response::json([
                'status' => true,
                'managers' => $allAssignedManagers
            ]);
            
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to get all assigned Managers: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Assign Managers to Country Manager
     */
    public function assignManagers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_manager_id' => 'required|integer',
            'manager_ids' => 'required|array',
            'manager_ids.*' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }

        try {
            $countryManagerId = $request->country_manager_id;
            $managerIds = $request->manager_ids;
            
            // Validate country manager from users table with manager groups
            $countryManager = User::select(
                'users.name',
                'users.id',
                'users.email',
                'users.type',
                'manager_groups.group_name',
                'manager_groups.group_type'
            )->where('users.id', $countryManagerId)
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->join('managers', 'users.id', '=', 'managers.user_id')
                ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
                ->first();
                
            if (!$countryManager) {
                return Response::json([
                    'status' => false,
                    'message' => 'Country Manager not found'
                ]);
            }
            
                         // Check for Country Manager (type=7 or group_type=7 or type='country_admin')
             if ($countryManager->type != 7 && $countryManager->group_type != 7 && $countryManager->type != 'country_admin') {
                 return Response::json([
                     'status' => false,
                     'message' => "Selected user '{$countryManager->name}' has type '{$countryManager->type}' and group_type '{$countryManager->group_type}', but Country Manager should have type 7, group_type 7, or type 'country_admin'"
                 ]);
             }
            
            // Validate managers from users table with manager groups
            $managers = User::select(
                'users.name',
                'users.id',
                'users.email',
                'users.type',
                'manager_groups.group_name',
                'manager_groups.group_type'
            )->whereIn('users.id', $managerIds)
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->join('managers', 'users.id', '=', 'managers.user_id')
                ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
                ->get();
                
                         foreach ($managers as $manager) {
                 // Check for Manager (type=5 or group_type=5 or type='manager' or type='account_manager')
                 if ($manager->type != 5 && $manager->group_type != 5 && $manager->type != 'manager' && $manager->type != 'account_manager') {
                     return Response::json([
                         'status' => false,
                         'message' => "Selected user '{$manager->name}' has type '{$manager->type}' and group_type '{$manager->group_type}', but Manager should have type 5, group_type 5, or type 'manager'/'account_manager'"
                     ]);
                 }
             }
            
            $insertedCount = 0;
            $duplicateCount = 0;
            
            foreach ($managerIds as $managerId) {
                // Check if assignment already exists
                $exists = \DB::table('country_manager_assignments')
                    ->where('country_manager_id', $countryManagerId)
                    ->where('manager_id', $managerId)
                    ->exists();
                    
                if (!$exists) {
                    \DB::table('country_manager_assignments')->insert([
                        'country_manager_id' => $countryManagerId,
                        'manager_id' => $managerId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $insertedCount++;
                } else {
                    $duplicateCount++;
                }
            }
            
            $message = "Successfully assigned {$insertedCount} Manager(s)";
            if ($duplicateCount > 0) {
                $message .= " ({$duplicateCount} were already assigned)";
            }
            
            return Response::json([
                'status' => true,
                'message' => $message,
                'inserted_count' => $insertedCount,
                'duplicate_count' => $duplicateCount
            ]);
            
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to assign Managers: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Assign Country Admins to Admin Manager
     */
    public function assignCountryAdmins(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_manager_id' => 'required|integer',
            'country_admin_ids' => 'required|array',
            'country_admin_ids.*' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]);
        }

        try {
            $adminManagerId = $request->admin_manager_id;
            $countryAdminIds = $request->country_admin_ids;
            
            // Validate admin manager from users table with manager groups
            $adminManager = User::select(
                'users.name',
                'users.id',
                'users.email',
                'users.type',
                'manager_groups.group_name',
                'manager_groups.group_type'
            )->where('users.id', $adminManagerId)
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->join('managers', 'users.id', '=', 'managers.user_id')
                ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
                ->first();
                
            if (!$adminManager) {
                return Response::json([
                    'status' => false,
                    'message' => 'Admin Manager not found'
                ]);
            }
            
                         // Check for Admin Manager (type=6 or group_type=6 or type='admin_user')
             if ($adminManager->type != 6 && $adminManager->group_type != 6 && $adminManager->type != 'admin_user') {
                 return Response::json([
                     'status' => false,
                     'message' => "Selected user '{$adminManager->name}' has type '{$adminManager->type}' and group_type '{$adminManager->group_type}', but Admin Manager should have type 6, group_type 6, or type 'admin_user'"
                 ]);
             }
            
            // Validate country admins from users table with manager groups
            $countryAdmins = User::select(
                'users.name',
                'users.id',
                'users.email',
                'users.type',
                'manager_groups.group_name',
                'manager_groups.group_type'
            )->whereIn('users.id', $countryAdminIds)
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->join('managers', 'users.id', '=', 'managers.user_id')
                ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
                ->get();
                
                         foreach ($countryAdmins as $countryAdmin) {
                 // Check for Country Manager (type=7 or group_type=7 or type='country_admin')
                 if ($countryAdmin->type != 7 && $countryAdmin->group_type != 7 && $countryAdmin->type != 'country_admin') {
                     return Response::json([
                         'status' => false,
                         'message' => "Selected user '{$countryAdmin->name}' has type '{$countryAdmin->type}' and group_type '{$countryAdmin->group_type}', but Country Manager should have type 7, group_type 7, or type 'country_admin'"
                     ]);
                 }
             }
            
            $insertedCount = 0;
            $duplicateCount = 0;
            
            foreach ($countryAdminIds as $countryAdminId) {
                // Check if assignment already exists
                $exists = \DB::table('admin_country_assignments')
                    ->where('admin_manager_id', $adminManagerId)
                    ->where('country_admin_id', $countryAdminId)
                    ->exists();
                    
                if (!$exists) {
                    \DB::table('admin_country_assignments')->insert([
                        'admin_manager_id' => $adminManagerId,
                        'country_admin_id' => $countryAdminId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $insertedCount++;
                } else {
                    $duplicateCount++;
                }
            }
            
            $message = "Successfully assigned {$insertedCount} Country Admin(s)";
            if ($duplicateCount > 0) {
                $message .= " ({$duplicateCount} were already assigned)";
            }
            
            return Response::json([
                'status' => true,
                'message' => $message,
                'inserted_count' => $insertedCount,
                'duplicate_count' => $duplicateCount
            ]);
            
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to assign Country Admins: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get assigned traders for a specific manager
     */
    public function getAssignedTraders(Request $request)
    {
        try {
            $managerId = $request->get('manager_id');
            
            if (!$managerId) {
                return Response::json([
                    'status' => false,
                    'message' => 'Manager ID is required'
                ]);
            }
            
            // Get traders assigned to this manager from ManagerUser table
            $traders = DB::table('manager_users')
                ->join('users', 'manager_users.user_id', '=', 'users.id')
                ->where('manager_users.manager_id', $managerId)
                ->whereIn('users.type', [0, 4]) // 0 for trader, 4 for IB
                ->select('users.id', 'users.name', 'users.email', 'users.type')
                ->get();
            
            // Convert type numbers to readable names
            $traders = $traders->map(function($trader) {
                $trader->type = $trader->type == 0 ? 'Trader' : 'IB';
                return $trader;
            });
            
            return Response::json([
                'status' => true,
                'traders' => $traders
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to fetch assigned traders: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get manager statistics
     */
    public function getManagerStats(Request $request)
    {
        try {
            $managerId = $request->get('manager_id');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            
            if (!$managerId) {
                return Response::json([
                    'status' => false,
                    'message' => 'Manager ID is required'
                ]);
            }
            
            // Get users assigned to this manager
            $assignedUsers = DB::table('manager_users')
                ->where('manager_id', $managerId)
                ->pluck('user_id');
            
            // Calculate statistics with date filter
            $stats = $this->calculateHierarchicalStats($assignedUsers, $startDate, $endDate);
            
            return Response::json([
                'status' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to fetch manager statistics: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get revenue data for a manager, admin manager, or country manager
     */
    public function getRevenueData(Request $request)
    {
        try {
            $managerId = $request->get('manager_id');
            $adminManagerId = $request->get('admin_manager_id');
            $countryManagerId = $request->get('country_manager_id');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $month = $request->get('month'); // For month filter
            
            if (!$managerId && !$adminManagerId && !$countryManagerId) {
                return Response::json([
                    'status' => false,
                    'message' => 'Manager ID, Admin Manager ID, or Country Manager ID is required'
                ]);
            }
            
            // Get users based on the type of manager
            $assignedUsers = collect();
            
            if ($managerId) {
                // Account Manager - get direct users
                $assignedUsers = DB::table('manager_users')
                    ->where('manager_id', $managerId)
                    ->pluck('user_id');
            } elseif ($countryManagerId) {
                // Country Manager - get users from all managers under this country manager
                $managerIds = DB::table('country_manager_assignments')
                    ->where('country_manager_id', $countryManagerId)
                    ->pluck('manager_id');
                    
                $assignedUsers = DB::table('manager_users')
                    ->whereIn('manager_id', $managerIds)
                    ->pluck('user_id');
            } elseif ($adminManagerId) {
                // Admin Manager - get users from all country managers under this admin manager
                $countryManagerIds = DB::table('admin_country_assignments')
                    ->where('admin_manager_id', $adminManagerId)
                    ->pluck('country_admin_id');
                    
                $managerIds = DB::table('country_manager_assignments')
                    ->whereIn('country_manager_id', $countryManagerIds)
                    ->pluck('manager_id');
                    
                $assignedUsers = DB::table('manager_users')
                    ->whereIn('manager_id', $managerIds)
                    ->pluck('user_id');
            }
            
            // Build date filter
            $dateFilter = [];
            if ($startDate && $endDate) {
                $dateFilter = [
                    ['created_at', '>=', $startDate . ' 00:00:00'],
                    ['created_at', '<=', $endDate . ' 23:59:59']
                ];
            }
            
            // Handle month filter
            if ($month) {
                $currentYear = date('Y');
                $monthNum = (int)$month;
                
                // Create date range for the selected month
                $startOfMonth = $currentYear . '-' . str_pad($monthNum, 2, '0', STR_PAD_LEFT) . '-01 00:00:00';
                $endOfMonth = $currentYear . '-' . str_pad($monthNum, 2, '0', STR_PAD_LEFT) . '-' . date('t', mktime(0, 0, 0, $monthNum, 1, $currentYear)) . ' 23:59:59';
                
                $dateFilter = [
                    ['created_at', '>=', $startOfMonth],
                    ['created_at', '<=', $endOfMonth]
                ];
            }
            
            // Get deposit data
            $depositsQuery = DB::table('deposits')
                ->whereIn('user_id', $assignedUsers)
                ->where('approved_status', 'A');
                
            if (!empty($dateFilter)) {
                $depositsQuery->where($dateFilter);
            }
            
            $deposits = $depositsQuery->sum('amount');
            
            // Get withdrawal data
            $withdrawalsQuery = DB::table('withdraws')
                ->whereIn('user_id', $assignedUsers)
                ->where('approved_status', 'A');
                
            if (!empty($dateFilter)) {
                $withdrawalsQuery->where($dateFilter);
            }
            
            $withdrawals = $withdrawalsQuery->sum('amount');
            
            // Get daily data for chart
            $dailyData = [];
            if ($startDate && $endDate) {
                $dailyDeposits = DB::table('deposits')
                    ->whereIn('user_id', $assignedUsers)
                    ->where('approved_status', 'A')
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                    ->groupBy('date')
                    ->get();
                    
                $dailyWithdrawals = DB::table('withdraws')
                    ->whereIn('user_id', $assignedUsers)
                    ->where('approved_status', 'A')
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                    ->groupBy('date')
                    ->get();
                    
                $dailyData = [
                    'deposits' => $dailyDeposits,
                    'withdrawals' => $dailyWithdrawals
                ];
            }
            
            // Get monthly revenue data similar to dashboard
            $monthlyData = $this->getMonthlyRevenueData($assignedUsers, $month);
            
            return Response::json([
                'status' => true,
                'data' => [
                    'deposits' => round($deposits, 2),
                    'withdrawals' => round($withdrawals, 2),
                    'net' => round($deposits - $withdrawals, 2),
                    'daily_data' => $dailyData
                ],
                'monthly_data' => $monthlyData
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to fetch revenue data: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get hierarchical statistics for admin manager
     */
    public function getAdminManagerStats(Request $request)
    {
        try {
            $adminManagerId = $request->get('admin_manager_id');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            
            if (!$adminManagerId) {
                return Response::json([
                    'status' => false,
                    'message' => 'Admin Manager ID is required'
                ]);
            }
            
            // Get all country managers under this admin manager
            $countryManagerIds = DB::table('admin_country_assignments')
                ->where('admin_manager_id', $adminManagerId)
                ->pluck('country_admin_id');
            
            // Get all managers under these country managers
            $managerIds = DB::table('country_manager_assignments')
                ->whereIn('country_manager_id', $countryManagerIds)
                ->pluck('manager_id');
            
            // Get all users under these managers
            $userIds = DB::table('manager_users')
                ->whereIn('manager_id', $managerIds)
                ->pluck('user_id');
            
            // Calculate statistics with date filter
            $stats = $this->calculateHierarchicalStats($userIds, $startDate, $endDate);
            
            return Response::json([
                'status' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to fetch admin manager statistics: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get hierarchical statistics for country manager
     */
    public function getCountryManagerStats(Request $request)
    {
        try {
            $countryManagerId = $request->get('country_manager_id');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            
            if (!$countryManagerId) {
                return Response::json([
                    'status' => false,
                    'message' => 'Country Manager ID is required'
                ]);
            }
            
            // Get all managers under this country manager
            $managerIds = DB::table('country_manager_assignments')
                ->where('country_manager_id', $countryManagerId)
                ->pluck('manager_id');
            
            // Get all users under these managers
            $userIds = DB::table('manager_users')
                ->whereIn('manager_id', $managerIds)
                ->pluck('user_id');
            
            // Calculate statistics with date filter
            $stats = $this->calculateHierarchicalStats($userIds, $startDate, $endDate);
            
            return Response::json([
                'status' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => false,
                'message' => 'Failed to fetch country manager statistics: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Calculate hierarchical statistics
     */
    private function calculateHierarchicalStats($userIds, $startDate = null, $endDate = null)
    {
        $totalTraders = DB::table('users')
            ->whereIn('id', $userIds)
            ->where('type', 0) // Traders
            ->count();
            
        $totalIbs = DB::table('users')
            ->whereIn('id', $userIds)
            ->where('type', 4) // IBs
            ->count();
        
        // Build date filter for deposits and withdrawals
        $dateFilter = [];
        if ($startDate && $endDate) {
            $dateFilter = [
                ['created_at', '>=', $startDate . ' 00:00:00'],
                ['created_at', '<=', $endDate . ' 23:59:59']
            ];
        }
            
        $depositsQuery = DB::table('deposits')
            ->whereIn('user_id', $userIds)
            ->where('approved_status', 'A');
            
        if (!empty($dateFilter)) {
            $depositsQuery->where($dateFilter);
        }
        
        $totalDeposit = $depositsQuery->sum('amount');
            
        $withdrawalsQuery = DB::table('withdraws')
            ->whereIn('user_id', $userIds)
            ->where('approved_status', 'A');
            
        if (!empty($dateFilter)) {
            $withdrawalsQuery->where($dateFilter);
        }
        
        $totalWithdraw = $withdrawalsQuery->sum('amount');
        
        // Calculate Today's statistics
        $today = date('Y-m-d');
        $todayStart = $today . ' 00:00:00';
        $todayEnd = $today . ' 23:59:59';
        
        $todayTraders = DB::table('users')
            ->whereIn('id', $userIds)
            ->where('type', 0) // Traders
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();
            
        $todayIbs = DB::table('users')
            ->whereIn('id', $userIds)
            ->where('type', 4) // IBs
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();
        
        $todayDeposits = DB::table('deposits')
            ->whereIn('user_id', $userIds)
            ->where('approved_status', 'A')
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('amount');
            
        $todayWithdrawals = DB::table('withdraws')
            ->whereIn('user_id', $userIds)
            ->where('approved_status', 'A')
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('amount');
        
        // Calculate This Month's statistics
        $thisMonthStart = date('Y-m-01 00:00:00');
        $thisMonthEnd = date('Y-m-t 23:59:59');
        
        $thisMonthTraders = DB::table('users')
            ->whereIn('id', $userIds)
            ->where('type', 0) // Traders
            ->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->count();
            
        $thisMonthIbs = DB::table('users')
            ->whereIn('id', $userIds)
            ->where('type', 4) // IBs
            ->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->count();
        
        $thisMonthDeposits = DB::table('deposits')
            ->whereIn('user_id', $userIds)
            ->where('approved_status', 'A')
            ->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->sum('amount');
            
        $thisMonthWithdrawals = DB::table('withdraws')
            ->whereIn('user_id', $userIds)
            ->where('approved_status', 'A')
            ->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->sum('amount');
        
        // Calculate Last Month's statistics
        $lastMonthStart = date('Y-m-01 00:00:00', strtotime('first day of last month'));
        $lastMonthEnd = date('Y-m-t 23:59:59', strtotime('last day of last month'));
        
        $lastMonthTraders = DB::table('users')
            ->whereIn('id', $userIds)
            ->where('type', 0) // Traders
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();
            
        $lastMonthIbs = DB::table('users')
            ->whereIn('id', $userIds)
            ->where('type', 4) // IBs
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();
        
        $lastMonthDeposits = DB::table('deposits')
            ->whereIn('user_id', $userIds)
            ->where('approved_status', 'A')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');
            
        $lastMonthWithdrawals = DB::table('withdraws')
            ->whereIn('user_id', $userIds)
            ->where('approved_status', 'A')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');
        
        return [
            'total_traders' => $totalTraders,
            'total_ibs' => $totalIbs,
            'total_deposit' => round($totalDeposit, 2),
            'total_withdraw' => round($totalWithdraw, 2),
            'today_traders' => $todayTraders,
            'today_ibs' => $todayIbs,
            'today_deposit' => round($todayDeposits, 2),
            'today_withdraw' => round($todayWithdrawals, 2),
            'this_month_traders' => $thisMonthTraders,
            'this_month_ibs' => $thisMonthIbs,
            'this_month_deposit' => round($thisMonthDeposits, 2),
            'this_month_withdraw' => round($thisMonthWithdrawals, 2),
            'last_month_traders' => $lastMonthTraders,
            'last_month_ibs' => $lastMonthIbs,
            'last_month_deposit' => round($lastMonthDeposits, 2),
            'last_month_withdraw' => round($lastMonthWithdrawals, 2)
        ];
    }
    
    /**
     * Get monthly revenue data similar to dashboard
     */
    private function getMonthlyRevenueData($userIds, $month = null)
    {
        try {
            // If a specific month is selected, return data for that month only
            if ($month) {
                $currentYear = date('Y');
                $monthNum = (int)$month;
                
                // Create date range for the selected month
                $startOfMonth = $currentYear . '-' . str_pad($monthNum, 2, '0', STR_PAD_LEFT) . '-01 00:00:00';
                $endOfMonth = $currentYear . '-' . str_pad($monthNum, 2, '0', STR_PAD_LEFT) . '-' . date('t', mktime(0, 0, 0, $monthNum, 1, $currentYear)) . ' 23:59:59';
                
                // Get daily data for the selected month
                $dailyDeposits = DB::table('deposits')
                    ->whereIn('user_id', $userIds)
                    ->where('approved_status', 'A')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                    ->groupBy('date')
                    ->get();
                    
                $dailyWithdrawals = DB::table('withdraws')
                    ->whereIn('user_id', $userIds)
                    ->where('approved_status', 'A')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                    ->groupBy('date')
                    ->get();
                
                // Build arrays for daily chart
                $days = [];
                $depositAmounts = [];
                $withdrawalAmounts = [];
                
                // Get all days in the month
                $daysInMonth = date('t', mktime(0, 0, 0, $monthNum, 1, $currentYear));
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = $currentYear . '-' . str_pad($monthNum, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    $days[] = $day;
                    
                    $depositAmount = $dailyDeposits->where('date', $date)->first();
                    $withdrawalAmount = $dailyWithdrawals->where('date', $date)->first();
                    
                    $depositAmounts[] = $depositAmount ? $depositAmount->total : 0;
                    $withdrawalAmounts[] = $withdrawalAmount ? -$withdrawalAmount->total : 0; // Make withdrawals negative
                }
                
                return [
                    'months' => $days,
                    'deposits' => $depositAmounts,
                    'withdrawals' => $withdrawalAmounts
                ];
            }
            
            // Generate months array (last 12 months) for default view
            $months = [];
            $currentMonth = date('m');
            $currentYear = date('Y');
            
            for ($i = 11; $i >= 0; $i--) {
                $monthNum = $currentMonth - $i;
                if ($monthNum <= 0) {
                    $monthNum += 12;
                    $year = $currentYear - 1;
                } else {
                    $year = $currentYear;
                }
                
                $months[] = [
                    'month' => $monthNum,
                    'year' => $year,
                    'name' => date('M', mktime(0, 0, 0, $monthNum, 1, $year))
                ];
            }
            
            // Get deposit data by month
            $depositData = DB::table('deposits')
                ->whereIn('user_id', $userIds)
                ->where('approved_status', 'A')
                ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
                ->groupBy('month', 'year')
                ->get()
                ->keyBy(function($item) {
                    return $item->month . '_' . $item->year;
                });
            
            // Get withdrawal data by month
            $withdrawalData = DB::table('withdraws')
                ->whereIn('user_id', $userIds)
                ->where('approved_status', 'A')
                ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
                ->groupBy('month', 'year')
                ->get()
                ->keyBy(function($item) {
                    return $item->month . '_' . $item->year;
                });
            
            // Build arrays for chart
            $monthNames = [];
            $depositAmounts = [];
            $withdrawalAmounts = [];
            
            foreach ($months as $monthData) {
                $monthNames[] = $monthData['name'];
                $key = $monthData['month'] . '_' . $monthData['year'];
                
                $depositAmounts[] = $depositData->get($key, (object)['total' => 0])->total;
                $withdrawalAmounts[] = $withdrawalData->get($key, (object)['total' => 0])->total;
            }
            
            return [
                'months' => $monthNames,
                'deposits' => $depositAmounts,
                'withdrawals' => array_map(function($amount) {
                    return -$amount; // Make withdrawals negative for chart
                }, $withdrawalAmounts)
            ];
        } catch (\Exception $e) {
            // \Log::error('Error in getMonthlyRevenueData: ' . $e->getMessage());
            return [
                'months' => [],
                'deposits' => [],
                'withdrawals' => []
            ];
        }
    }
}
