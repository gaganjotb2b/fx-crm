<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_comment_id',
        'filename',
        'path',
        'mime_type',
        'size'
    ];

    /**
     * Get the comment that owns the attachment.
     */
    public function comment()
    {
        return $this->belongsTo(TaskComment::class, 'task_comment_id');
    }
}
