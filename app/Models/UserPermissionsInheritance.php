<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Permission;

class UserPermissionsInheritance extends Model
{
    protected $table = 'user_permissions_inheritance';
    
    protected $fillable = [
        'user_id',
        'inherited_from_user_id',
        'permission_id',
        'can_edit',
        'can_view',
        'can_delete'
    ];

    protected $casts = [
        'can_edit' => 'boolean',
        'can_view' => 'boolean',
        'can_delete' => 'boolean'
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who granted the permission
     */
    public function inheritedFromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inherited_from_user_id');
    }

    /**
     * Get the permission
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    /**
     * Get all permissions for a user
     */
    public static function getUserPermissions($userId)
    {
        return self::where('user_id', $userId)
            ->with(['permission', 'inheritedFromUser'])
            ->get();
    }

    /**
     * Get inheritable permissions from a parent user
     */
    public static function getInheritablePermissions($parentUserId)
    {
        return self::where('user_id', $parentUserId)
            ->with('permission')
            ->get();
    }

    /**
     * Check if user has specific permission
     */
    public static function hasPermission($userId, $permissionId, $action = 'view')
    {
        $permission = self::where('user_id', $userId)
            ->where('permission_id', $permissionId)
            ->first();

        if (!$permission) {
            return false;
        }

        switch ($action) {
            case 'view':
                return $permission->can_view;
            case 'edit':
                return $permission->can_edit;
            case 'delete':
                return $permission->can_delete;
            default:
                return false;
        }
    }

    /**
     * Assign permissions to a user
     */
    public static function assignPermissions($userId, $inheritedFromUserId, $permissions)
    {
        foreach ($permissions as $permissionId => $actions) {
            self::updateOrCreate(
                [
                    'user_id' => $userId,
                    'permission_id' => $permissionId
                ],
                [
                    'inherited_from_user_id' => $inheritedFromUserId,
                    'can_view' => $actions['view'] ?? true,
                    'can_edit' => $actions['edit'] ?? false,
                    'can_delete' => $actions['delete'] ?? false
                ]
            );
        }
    }

    /**
     * Get available permissions for inheritance
     */
    public static function getAvailablePermissionsForInheritance($parentUserId)
    {
        $parentPermissions = self::where('user_id', $parentUserId)->get();
        $availablePermissions = [];

        foreach ($parentPermissions as $permission) {
            $availablePermissions[] = [
                'permission_id' => $permission->permission_id,
                'permission_name' => $permission->permission->name ?? 'Unknown',
                'can_view' => $permission->can_view,
                'can_edit' => $permission->can_edit,
                'can_delete' => $permission->can_delete
            ];
        }

        return $availablePermissions;
    }

    /**
     * Validate inheritance chain
     */
    public static function validateInheritance($userId, $parentUserId)
    {
        // Check if parent has permissions to grant
        $parentPermissions = self::where('user_id', $parentUserId)->count();
        
        if ($parentPermissions === 0) {
            return false;
        }

        // Check if user already has permissions from another source
        $existingPermissions = self::where('user_id', $userId)->count();
        
        return true;
    }
}
