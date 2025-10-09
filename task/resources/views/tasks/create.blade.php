@extends('layouts.dashboard')

@section('title', 'Create New Task')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base
                    @error('title') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                    required>
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base
                    @error('description') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                    required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">Assign Users</label>
                    <button type="button" id="selectAllUsers" class="text-sm text-blue-600 hover:text-blue-800">
                        Select All
                    </button>
                </div>
                <div class="mt-1 border border-gray-300 rounded-md shadow-sm divide-y max-h-[50vh] overflow-y-auto bg-white">
                    @foreach($users as $user)
                        <div class="flex items-start sm:items-center p-3 hover:bg-gray-50">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       name="assigned_users[]" 
                                       id="user_{{ $user->id }}" 
                                       value="{{ $user->id }}"
                                       {{ in_array($user->id, old('assigned_users', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            <label for="user_{{ $user->id }}" class="ml-3 flex-1 min-w-0 cursor-pointer">
                                <div class="flex items-center">
                                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                                         alt="{{ $user->name }}" 
                                         class="h-8 w-8 rounded-full flex-shrink-0">
                                    <div class="ml-3 flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-700 truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('assigned_users')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs sm:text-sm text-gray-500">Select users to assign to this task</p>
            </div>

            <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-4">
                <a href="{{ route('tasks.index') }}" 
                    class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Task
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllButton = document.getElementById('selectAllUsers');
            const checkboxes = document.querySelectorAll('input[name="assigned_users[]"]');
            let allSelected = false;

            selectAllButton.addEventListener('click', function() {
                allSelected = !allSelected;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = allSelected;
                });
                this.textContent = allSelected ? 'Unselect All' : 'Select All';
            });
        });
    </script>
    @endpush
@endsection 