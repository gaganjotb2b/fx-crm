<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHierarchy extends Model
{
    protected $table = 'user_hierarchy';
    
    protected $fillable = [
        'parent_user_id',
        'child_user_id',
        'hierarchy_level'
    ];

    /**
     * Get the parent user
     */
    public function parentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    /**
     * Get the child user
     */
    public function childUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'child_user_id');
    }

    /**
     * Get all children of a user
     */
    public static function getChildren($userId, $level = null)
    {
        $query = self::where('parent_user_id', $userId);
        if ($level) {
            $query->where('hierarchy_level', $level);
        }
        return $query->with('childUser')->get();
    }

    /**
     * Get all parents of a user
     */
    public static function getParents($userId)
    {
        return self::where('child_user_id', $userId)
            ->with('parentUser')
            ->get();
    }

    /**
     * Check if a user is in hierarchy
     */
    public static function isInHierarchy($userId)
    {
        return self::where('parent_user_id', $userId)
            ->orWhere('child_user_id', $userId)
            ->exists();
    }

    /**
     * Get user's hierarchy level
     */
    public static function getUserLevel($userId)
    {
        $parent = self::where('child_user_id', $userId)->first();
        return $parent ? $parent->hierarchy_level : null;
    }

    /**
     * Get complete hierarchy tree
     */
    public static function getHierarchyTree()
    {
        $hierarchy = self::with(['parentUser', 'childUser'])->get();
        return self::buildTree($hierarchy);
    }

    /**
     * Build tree structure from flat hierarchy
     */
    private static function buildTree($hierarchy, $parentId = null)
    {
        $tree = [];
        
        foreach ($hierarchy as $item) {
            if ($item->parent_user_id == $parentId) {
                $children = self::buildTree($hierarchy, $item->child_user_id);
                $tree[] = [
                    'id' => $item->child_user_id,
                    'user' => $item->childUser,
                    'level' => $item->hierarchy_level,
                    'children' => $children
                ];
            }
        }
        
        return $tree;
    }
}
