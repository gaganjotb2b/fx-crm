<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super.admin')->only(['create', 'store', 'complete']);
    }

    public function index()
    {
        $user = Auth::user();
        $query = $user->isSuperAdmin()
            ? Task::query()
            : Task::whereHas('assignedUsers', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            });

        $tasks = $query->with(['creator', 'assignedUsers'])
            ->latest()
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        return view('tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_users' => 'required|array|min:1',
            'assigned_users.*' => 'exists:users,id',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'new',
            'created_by' => Auth::id(),
        ]);

        $task->assignedUsers()->attach($validated['assigned_users']);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        if (!$task->canView(Auth::user())) {
            abort(403);
        }

        $task->load(['creator', 'assignedUsers', 'comments.user']);
        return view('tasks.show', compact('task'));
    }

    public function complete(Task $task)
    {
        if (!$task->canComplete(Auth::user())) {
            abort(403);
        }

        $task->update(['status' => 'completed']);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task marked as completed.');
    }

    public function addComment(Request $request, Task $task)
    {
        if (!$task->canAddComments()) {
            return back()->with('error', 'Cannot add comments to completed tasks.');
        }

        if (!$task->canView(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:png,jpg,jpeg,pdf,doc,docx|max:10240',
        ]);

        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('task-attachments');
            
            $comment->update([
                'attachment_path' => $path,
                'attachment_name' => $file->getClientOriginalName(),
                'attachment_type' => $file->getClientMimeType(),
            ]);
        }

        return back()->with('success', 'Comment added successfully.');
    }

    public function downloadAttachment(Task $task, $commentId)
    {
        if (!$task->canView(Auth::user())) {
            abort(403);
        }

        $comment = $task->comments()->findOrFail($commentId);
        
        if (!$comment->hasAttachment()) {
            abort(404);
        }

        return Storage::download(
            $comment->attachment_path,
            $comment->attachment_name,
            ['Content-Type' => $comment->attachment_type]
        );
    }

    public function assignUsers(Request $request, Task $task)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id'
        ]);

        $task->assignedUsers()->syncWithoutDetaching($validated['user_ids']);

        return redirect()->back()->with('success', 'Users assigned successfully.');
    }
} 