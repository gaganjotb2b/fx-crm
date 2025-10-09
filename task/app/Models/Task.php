<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user')
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->whereNull('parent_id');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canAddComments(): bool
    {
        return !$this->isCompleted();
    }

    public function canComplete(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function canView(User $user): bool
    {
        return $user->isSuperAdmin() || $this->assignedUsers->contains($user);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function allComments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function getNextCommentNumber()
    {
        return $this->allComments()->max('comment_number') + 1;
    }
} 