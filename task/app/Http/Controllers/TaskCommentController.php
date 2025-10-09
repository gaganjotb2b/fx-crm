<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\CommentAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskCommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:task_comments,id',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        $comment = $task->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('comment-attachments');
                
                $comment->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Comment added successfully');
    }

    public function markAsCompleted(TaskComment $comment)
    {
        $this->authorize('update', $comment);
        
        $comment->update(['is_completed' => true]);
        
        return redirect()->back()->with('success', 'Comment marked as completed');
    }

    public function downloadAttachment(CommentAttachment $attachment)
    {
        $this->authorize('view', $attachment->comment->task);
        
        return Storage::download($attachment->path, $attachment->filename);
    }
}
