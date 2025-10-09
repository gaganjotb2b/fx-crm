<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_id',
        'user_id',
        'parent_id',
        'content',
        'is_completed',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TaskComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'parent_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(CommentAttachment::class, 'task_comment_id');
    }

    public function hasAttachment(): bool
    {
        return !is_null($this->attachment_path);
    }

    public function getAttachmentUrl(): ?string
    {
        return $this->hasAttachment() 
            ? Storage::url($this->attachment_path)
            : null;
    }

    public function deleteAttachment(): void
    {
        if ($this->hasAttachment()) {
            Storage::delete($this->attachment_path);
            $this->update([
                'attachment_path' => null,
                'attachment_name' => null,
                'attachment_type' => null,
            ]);
        }
    }
} 