@extends(App\Services\systems\AdminLayoutControllService::admin_layout())

@section('title', 'Manager Groups')

@section('vendor-css')
<link rel="stylesheet" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
@endsection

@section('page-css')
<link rel="stylesheet" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title">Assign Groups to Managers</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">{{ __('finance.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">Manager Groups</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Manager Groups Assignment</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Left Side - Manager Selection and Users -->
                                    <div class="col-md-8">
                                        <!-- Manager Selection -->
                                        <div class="form-group mb-3">
                                            <label for="managerSelect">Select Manager</label>
                                            <select class="form-control" id="managerSelect" name="manager_id">
                                                <option value="">-- Select Manager --</option>
                                                @foreach($managers as $manager)
                                                    <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Loading Spinner -->
                                        <div id="loadingSpinner" class="text-center" style="display: none;">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <p class="mt-2">Loading users...</p>
                                        </div>

                                        <!-- Users Section -->
                                        <div id="usersSection" style="display: none;">
                                            <h5 class="mb-3">Assigned Users</h5>
                                            
                                            <!-- Master Checkbox and Actions -->
                                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="selectAllUsersCheckbox" checked>
                                                    <label class="form-check-label" for="selectAllUsersCheckbox">
                                                        <strong>All Selected</strong>
                                                    </label>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-info btn-sm" onclick="showSelectedUsers()">
                                                        <i class="fa fa-eye"></i> Show Selected (<span id="selectedUsersCount">0</span>)
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width="50">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" id="headerUsersCheckbox" checked>
                                                                </div>
                                                            </th>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Groups</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="usersTable">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Error Message -->
                                        <div id="errorMessage" class="alert alert-danger" style="display: none;">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            <span id="errorText"></span>
                                        </div>
                                    </div>

                                    <!-- Right Side - Client Groups -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="clientGroups">Select Client Groups</label>
                                            <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto; background-color: #f8f9fa;">
                                                <div id="clientGroupsContainer">
                                                    <div class="text-center text-muted">
                                                        <i class="fa fa-spinner fa-spin"></i> Loading groups...
                                                    </div>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">Click to select/deselect groups. Multiple selections allowed.</small>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-info btn-sm" onclick="showSelectedGroups()">
                                                    <i class="fa fa-eye"></i> Show Selected Groups (<span id="selectedGroupsCount">0</span>)
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm ml-2" onclick="selectAllGroups()">
                                                    <i class="fa fa-check-square"></i> Select All
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm ml-2" onclick="deselectAllGroups()">
                                                    <i class="fa fa-square"></i> Deselect All
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Assign Groups Button -->
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-success btn-block" onclick="assignGroupsToUsers()" id="assignGroupsBtn" disabled>
                                                <i class="fa fa-save"></i> Assign Groups to Selected Users
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('vendor-js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
@endsection

@section('page-js')
<script>
$(document).ready(function() {
    console.log('Manager Groups page loaded');
    
    // Load client groups on page load
    loadClientGroups();
    
    // Manager selection change event
    $('#managerSelect').on('change', function() {
        const managerId = $(this).val();
        if (managerId) {
            loadAssignedUsers(managerId);
        } else {
            $('#usersSection').hide();
            $('#assignGroupsBtn').prop('disabled', true);
        }
    });
});

// Load client groups
function loadClientGroups() {
    $.ajax({
        url: '{{ route("admin.client-management.manager-groups.client-groups") }}',
        method: 'GET',
        success: function(response) {
            if (response.status) {
                displayClientGroups(response.data);
            } else {
                $('#clientGroupsContainer').html('<div class="text-danger">Error loading groups</div>');
            }
        },
        error: function() {
            $('#clientGroupsContainer').html('<div class="text-danger">Error loading groups</div>');
        }
    });
}

// Display client groups
function displayClientGroups(groups) {
    let html = '';
    groups.forEach(function(group) {
        html += `
            <div class="form-check">
                <input class="form-check-input group-checkbox" type="checkbox" value="${group.id}" id="group_${group.id}">
                <label class="form-check-label" for="group_${group.id}">
                    ${group.group_name} (${group.group_id}) - ${group.server.toUpperCase()}
                </label>
            </div>
        `;
    });
    $('#clientGroupsContainer').html(html);
    updateSelectedGroupsCount();
}

// Load assigned users
function loadAssignedUsers(managerId) {
    $('#loadingSpinner').show();
    $('#usersSection').hide();
    $('#errorMessage').hide();
    
    $.ajax({
        url: '{{ route("admin.client-management.manager-groups.assigned-users") }}',
        method: 'POST',
        data: {
            manager_id: managerId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#loadingSpinner').hide();
            if (response.users && response.users.length > 0) {
                displayUsers(response.users);
                $('#usersSection').show();
                $('#assignGroupsBtn').prop('disabled', false);
            } else {
                $('#errorMessage').show().find('#errorText').text('No users found for this manager');
            }
        },
        error: function() {
            $('#loadingSpinner').hide();
            $('#errorMessage').show().find('#errorText').text('Error loading users');
        }
    });
}

// Display users
function displayUsers(users) {
    let html = '';
    users.forEach(function(user, index) {
        // Add special styling for manager
        const isManager = user.is_manager;
        const rowClass = isManager ? 'table-primary font-weight-bold' : '';
        const managerBadge = isManager ? '<span class="badge badge-primary ml-2">Manager</span>' : '';
        const nameCell = isManager ? `<strong>${user.name}</strong>${managerBadge}` : user.name;
        const emailCell = isManager ? `<strong>${user.email}</strong>` : user.email;
        
        html += `
            <tr class="${rowClass}">
                <td>
                    <div class="form-check">
                        <input class="form-check-input user-checkbox" type="checkbox" value="${user.id}" id="user_${user.id}" checked>
                    </div>
                </td>
                <td>${index + 1}</td>
                <td>${nameCell}</td>
                <td>${emailCell}</td>
                <td>${user.groups || 'No groups assigned'}</td>
            </tr>
        `;
    });
    $('#usersTable').html(html);
    setupUserCheckboxListeners();
}

// Setup user checkbox listeners
function setupUserCheckboxListeners() {
    // Header checkbox
    $('#headerUsersCheckbox').off('change').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.user-checkbox').prop('checked', isChecked);
        $('#selectAllUsersCheckbox').prop('checked', isChecked);
        updateSelectedUsersCount();
    });
    
    // Individual user checkboxes
    $('.user-checkbox').off('change').on('change', function() {
        updateSelectedUsersCount();
        updateHeaderCheckbox();
    });
    
    // Select all users checkbox
    $('#selectAllUsersCheckbox').off('change').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.user-checkbox').prop('checked', isChecked);
        $('#headerUsersCheckbox').prop('checked', isChecked);
        updateSelectedUsersCount();
    });
}

// Update selected users count
function updateSelectedUsersCount() {
    const selectedCount = $('.user-checkbox:checked').length;
    $('#selectedUsersCount').text(selectedCount);
}

// Update header checkbox
function updateHeaderCheckbox() {
    const totalUsers = $('.user-checkbox').length;
    const selectedUsers = $('.user-checkbox:checked').length;
    
    if (selectedUsers === 0) {
        $('#headerUsersCheckbox').prop('indeterminate', false).prop('checked', false);
        $('#selectAllUsersCheckbox').prop('checked', false);
    } else if (selectedUsers === totalUsers) {
        $('#headerUsersCheckbox').prop('indeterminate', false).prop('checked', true);
        $('#selectAllUsersCheckbox').prop('checked', true);
    } else {
        $('#headerUsersCheckbox').prop('indeterminate', true);
        $('#selectAllUsersCheckbox').prop('checked', false);
    }
}

// Group checkbox listeners
$(document).on('change', '.group-checkbox', function() {
    updateSelectedGroupsCount();
});

// Update selected groups count
function updateSelectedGroupsCount() {
    const selectedCount = $('.group-checkbox:checked').length;
    $('#selectedGroupsCount').text(selectedCount);
}

// Select all groups
function selectAllGroups() {
    $('.group-checkbox').prop('checked', true);
    updateSelectedGroupsCount();
}

// Deselect all groups
function deselectAllGroups() {
    $('.group-checkbox').prop('checked', false);
    updateSelectedGroupsCount();
}

// Show selected groups
function showSelectedGroups() {
    const selectedGroups = [];
    $('.group-checkbox:checked').each(function() {
        const groupId = $(this).val();
        const groupName = $(this).next('label').text();
        selectedGroups.push({ id: groupId, name: groupName });
    });
    
    if (selectedGroups.length === 0) {
        Swal.fire('No Groups Selected', 'Please select at least one group.', 'info');
    } else {
        let groupList = selectedGroups.map(group => `• ${group.name}`).join('\n');
        Swal.fire({
            title: 'Selected Groups',
            html: `<pre style="text-align: left;">${groupList}</pre>`,
            icon: 'info'
        });
    }
}

// Show selected users
function showSelectedUsers() {
    const selectedUsers = [];
    $('.user-checkbox:checked').each(function() {
        const userId = $(this).val();
        const userName = $(this).closest('tr').find('td:eq(2)').text();
        const userEmail = $(this).closest('tr').find('td:eq(3)').text();
        selectedUsers.push({ id: userId, name: userName, email: userEmail });
    });
    
    if (selectedUsers.length === 0) {
        Swal.fire('No Users Selected', 'Please select at least one user.', 'info');
    } else {
        let userList = selectedUsers.map(user => `• ${user.name} (${user.email})`).join('\n');
        Swal.fire({
            title: 'Selected Users',
            html: `<pre style="text-align: left;">${userList}</pre>`,
            icon: 'info'
        });
    }
}

// Assign groups to users
function assignGroupsToUsers() {
    const selectedUsers = $('.user-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    const selectedGroups = $('.group-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedUsers.length === 0) {
        Swal.fire('No Users Selected', 'Please select at least one user.', 'warning');
        return;
    }
    
    if (selectedGroups.length === 0) {
        Swal.fire('No Groups Selected', 'Please select at least one group.', 'warning');
        return;
    }
    
    Swal.fire({
        title: 'Confirm Assignment',
        text: `Are you sure you want to assign ${selectedGroups.length} group(s) to ${selectedUsers.length} user(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, assign groups!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("admin.client-management.manager-groups.assign-groups") }}',
                method: 'POST',
                data: {
                    user_ids: selectedUsers,
                    group_ids: selectedGroups,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire('Success!', response.message, 'success');
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'An error occurred while assigning groups.', 'error');
                }
            });
        }
    });
}
</script>
@endsection 