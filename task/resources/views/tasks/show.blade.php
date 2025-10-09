@extends('layouts.dashboard')

@section('title', $task->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $task->title }}</h2>
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                @if(auth()->user()->isSuperAdmin() && !$task->isCompleted())
                    <form action="{{ route('tasks.complete', $task) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors"
                            onclick="return confirm('Are you sure you want to mark this task as completed?')">
                            Mark as Completed
                        </button>
                    </form>
                @endif
                <a href="{{ route('tasks.index') }}" class="text-center w-full sm:w-auto px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">
                    Back to Tasks
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="p-4 sm:p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Status</h4>
                        <p class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($task->status === 'completed') bg-green-100 text-green-800
                                @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Created By</h4>
                        <p class="mt-1 text-gray-900">{{ $task->creator->name }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Created At</h4>
                        <p class="mt-1 text-gray-900">{{ $task->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Assigned To</h4>
                        <div class="mt-1">
                            <div class="flex flex-wrap gap-2">
                                @foreach($task->assignedUsers as $assignedUser)
                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm bg-blue-50 text-blue-700">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                                        </svg>
                                        {{ $assignedUser->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        @if(auth()->user()->isSuperAdmin())
                            <button 
                                onclick="toggleUserSelection()"
                                class="mt-3 inline-flex items-center px-3 py-1.5 text-sm text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors w-full sm:w-auto justify-center sm:justify-start"
                            >
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Assign More Users
                            </button>

                            <div id="assign-users-container" class="hidden mt-3 fixed inset-0 bg-gray-500 bg-opacity-75 z-50 flex items-center justify-center p-4">
                                <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] flex flex-col">
                                    <form id="assign-users-form" action="{{ route('tasks.assign', $task) }}" method="POST" class="flex flex-col h-full">
                                        @csrf
                                        <div class="p-4 border-b">
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-lg font-medium text-gray-900">Assign Users</h3>
                                                <button type="button" onclick="toggleUserSelection()" class="text-gray-400 hover:text-gray-500">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <input type="text" 
                                                id="user-search" 
                                                placeholder="Search users..." 
                                                class="w-full px-3 py-2 text-sm border rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                oninput="filterUsers(this.value)"
                                            >
                                        </div>
                                        
                                        <div class="flex-1 overflow-y-auto p-4 space-y-2">
                                            @foreach(\App\Models\User::where('is_active', true)->whereNotIn('id', $task->assignedUsers->pluck('id'))->get() as $user)
                                                <label class="user-option flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                                    <input type="checkbox" 
                                                        name="user_ids[]" 
                                                        value="{{ $user->id }}" 
                                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                    >
                                                    <span class="ml-2 flex items-center flex-1 min-w-0">
                                                        <span class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-700 flex-shrink-0">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                        <span class="ml-2 text-sm text-gray-700 truncate">{{ $user->name }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>

                                        <div class="p-4 bg-gray-50 border-t flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                                            <button type="button" 
                                                onclick="toggleUserSelection()" 
                                                class="w-full sm:w-auto px-4 py-2 text-sm text-gray-600 hover:text-gray-700 border rounded-md hover:bg-gray-50 transition-colors"
                                            >
                                                Cancel
                                            </button>
                                            <button type="submit" 
                                                class="w-full sm:w-auto px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                                            >
                                                Assign Selected Users
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Comments</h3>
                <button 
                    onclick="document.getElementById('new-comment-form').classList.toggle('hidden')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm"
                >
                    Add Comment
                </button>
            </div>

            <form id="new-comment-form" action="{{ route('task.comments.store', $task) }}" method="POST" class="mb-6 hidden" enctype="multipart/form-data">
                @csrf
                <textarea 
                    name="content" 
                    rows="3" 
                    class="w-full border rounded-lg p-2 text-sm sm:text-base" 
                    placeholder="Write your comment..."
                ></textarea>
                
                <div class="mt-2">
                    <label class="block text-sm text-gray-600">Attachments (optional)</label>
                    <input type="file" name="attachments[]" multiple class="mt-1 text-sm">
                </div>
                
                <div class="mt-3 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                        Submit Comment
                    </button>
                </div>
            </form>
            
            <div class="space-y-4 sm:space-y-6">
                @foreach($task->comments->whereNull('parent_id')->values() as $index => $comment)
                    <div class="p-4 sm:p-5 border-2 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 {{ $comment->is_completed ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center pb-3 border-b border-gray-100 space-y-2 sm:space-y-0">
                            <div class="flex items-center flex-1 min-w-0">
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                    #{{ str_pad($index + 1, 8, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="font-medium ml-3 text-gray-900 truncate">{{ $comment->user->name }}</span>
                                <span class="text-gray-500 text-xs sm:text-sm ml-3">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-gray-700 text-sm sm:text-base">
                            {{ $comment->content }}
                        </div>
                        
                        @if($comment->attachments->count() > 0)
                            <div class="mt-4 p-3 bg-gray-50 rounded-md">
                                <p class="text-sm font-medium text-gray-600">Attachments:</p>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($comment->attachments as $attachment)
                                        <a href="{{ route('attachments.download', $attachment) }}" 
                                           class="text-xs sm:text-sm text-blue-600 hover:text-blue-700 hover:bg-blue-50 px-2 sm:px-3 py-1 rounded-md flex items-center transition-colors">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            <span class="truncate max-w-[150px] sm:max-w-xs">{{ $attachment->filename }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Replies -->
                        @if($comment->replies->count() > 0)
                            <div class="mt-4 ml-4 sm:ml-8 space-y-3 sm:space-y-4">
                                @foreach($comment->replies->values() as $replyIndex => $reply)
                                    <div class="p-3 sm:p-4 border rounded-lg shadow-sm {{ $reply->is_completed ? 'bg-green-50 border-green-100' : 'bg-gray-50 border-gray-100' }}">
                                        <div class="flex flex-col sm:flex-row sm:items-center pb-2 border-b border-gray-100 space-y-2 sm:space-y-0">
                                            <div class="flex items-center flex-1 min-w-0">
                                                <span class="text-xs bg-white text-gray-600 px-2 py-1 rounded-full">
                                                    #{{ str_pad(($index + 1) . ($replyIndex + 1), 8, '0', STR_PAD_LEFT) }}
                                                </span>
                                                <span class="font-medium ml-3 text-gray-900 truncate">{{ $reply->user->name }}</span>
                                                <span class="text-gray-500 text-xs sm:text-sm ml-3">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 text-gray-700 text-sm">
                                            {{ $reply->content }}
                                        </div>
                                        
                                        @if($reply->attachments->count() > 0)
                                            <div class="mt-2 p-3 bg-gray-50 rounded-md">
                                                <p class="text-sm font-medium text-gray-600">Attachments:</p>
                                                <div class="flex flex-wrap gap-2 mt-2">
                                                    @foreach($reply->attachments as $attachment)
                                                        <a href="{{ route('attachments.download', $attachment) }}" 
                                                           class="text-xs sm:text-sm text-blue-600 hover:text-blue-700 hover:bg-blue-50 px-2 sm:px-3 py-1 rounded-md flex items-center transition-colors">
                                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                            </svg>
                                                            <span class="truncate max-w-[150px] sm:max-w-xs">{{ $attachment->filename }}</span>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- Comment Actions -->
                        <div class="mt-4 pt-3 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-2 sm:space-y-0">
                            @if(!$comment->is_completed)
                                <div>
                                    <button 
                                        onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')" 
                                        class="text-sm bg-gray-50 text-gray-600 px-3 py-1 rounded-full hover:bg-gray-100 transition-colors"
                                    >
                                        Reply
                                    </button>
                                </div>
                                <form action="{{ route('task.comments.complete', $comment) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full sm:w-auto text-sm bg-blue-50 text-blue-600 px-4 py-2 rounded-full hover:bg-blue-100 transition-colors">
                                        Mark Comment as Completed
                                    </button>
                                </form>
                            @else
                                <div></div>
                                <span class="text-sm bg-green-50 text-green-600 px-4 py-2 rounded-full">Comment Completed</span>
                            @endif
                        </div>
                        
                        <!-- Reply Form -->
                        @if(!$comment->is_completed)
                            <form id="reply-form-{{ $comment->id }}" action="{{ route('task.comments.store', $task) }}" method="POST" class="hidden mt-4 ml-4 sm:ml-8" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <textarea 
                                    name="content" 
                                    rows="2" 
                                    class="w-full border rounded-lg p-2 text-sm" 
                                    placeholder="Write your reply..."
                                ></textarea>
                                
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-600">Attachments (optional)</label>
                                    <input type="file" name="attachments[]" multiple class="mt-1 text-sm">
                                </div>
                                
                                <div class="mt-2 flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                        Submit Reply
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function toggleUserSelection() {
            const container = document.getElementById('assign-users-container');
            container.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        function filterUsers(query) {
            query = query.toLowerCase();
            document.querySelectorAll('.user-option').forEach(option => {
                const userName = option.querySelector('span:last-child').textContent.toLowerCase();
                option.style.display = userName.includes(query) ? 'flex' : 'none';
            });
        }
    </script>
@endsection 