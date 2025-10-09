<?php

namespace App\Policies;

use App\Models\TaskComment;
use App\Models\User;

class TaskCommentPolicy
{
    public function update(User $user, TaskComment $comment)
    {
        // Check if user is assigned to the task
        return $comment->task->assignedUsers->contains($user);
    }
} 