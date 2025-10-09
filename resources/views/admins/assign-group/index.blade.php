@extends(App\Services\systems\AdminLayoutControllService::admin_layout())

@section('title', 'Assign Group')

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
                <h3 class="content-header-title">Assign Group</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">{{ __('finance.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">Assign Group</li>
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
                            <h4 class="card-title">Search IB Users</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <!-- Search Form -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Enter IB Email Address</label>
                                            <div class="input-group">
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address to search">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button" id="searchBtn">
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="clientGroups">Select Client Groups</label>
                                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto; background-color: #f8f9fa;">
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
                                    </div>
                                </div>
                                


                                <!-- Loading Spinner -->
                                <div id="loadingSpinner" class="text-center" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="mt-2">Searching...</p>
                                </div>

                                <!-- Results Section -->
                                <div id="resultsSection" style="display: none;">
                                    <hr>
                                    <h5>Search Results</h5>
                                    
                                    <!-- IB User Info -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <h6><i class="fa fa-user"></i> IB User Information</h6>
                                                <div id="ibUserInfo"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- All Users (IB User + All Associated Users - All Levels) -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h6><i class="fa fa-users"></i> All Users (IB User + All Associated Users - All Levels)</h6>
                                            
                                            <!-- Info Box -->
                                            <div class="alert alert-info mb-3">
                                                <i class="fa fa-info-circle"></i>
                                                <strong>Note:</strong> This list shows the IB user and all associated users at every level. 
                                                If any associated user also has their own associated users, those are included as well.
                                            </div>
                                            
                                            <!-- Master Checkbox and Actions -->
                                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="selectAllCheckbox" checked>
                                                    <label class="form-check-label" for="selectAllCheckbox">
                                                        <strong>All Selected</strong>
                                                    </label>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-info btn-sm" onclick="showSelectedUsers()">
                                                        <i class="fa fa-eye"></i> Show Selected (<span id="selectedCount">0</span>)
                                                    </button>
                                                    <button type="button" class="btn btn-success btn-sm ml-2" onclick="assignGroupsToUsers()">
                                                        <i class="fa fa-save"></i> Assign Groups to Selected Users
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Progress Bar (hidden by default) -->
                                            <div id="progressContainer" class="mb-3" style="display: none;">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span id="progressText">Processing...</span>
                                                    <span id="progressPercent">0%</span>
                                                </div>
                                                <div class="progress">
                                                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                                         role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width="50">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" id="headerCheckbox" checked>
                                                                </div>
                                                            </th>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Groups</th>
                                                            <th>Level</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="associatedUsersTable">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Error Message -->
                                <div id="errorMessage" class="alert alert-danger" style="display: none;">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <span id="errorText"></span>
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
// Comprehensive jQuery Debug
console.log('=== JQUERY DEBUG START ===');
console.log('Window jQuery:', typeof window.jQuery);
console.log('Global jQuery:', typeof jQuery);
console.log('$ symbol:', typeof $);

// Check if jQuery loaded
if (typeof jQuery === 'undefined') {
    console.error('❌ jQuery is NOT loaded!');
    alert('jQuery is not loaded. Please check the console for details.');
} else {
    console.log('✅ jQuery is loaded!');
    console.log('jQuery version:', jQuery.fn.jquery);
}

// Test jQuery functionality
$(document).ready(function() {
    console.log('✅ jQuery document ready working!');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Document ready in Assign Group page');
});



// Global search function
function searchUsers() {
    const email = document.getElementById('email').value.trim();
    
    if (!email) {
        showError('Please enter an email address');
        return;
    }

    console.log('=== SEARCH DEBUG START ===');
    console.log('Searching for email:', email);
    console.log('Search URL:', '{{ route("admin.assign-group.search") }}');

    // Show loading spinner
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('resultsSection').style.display = 'none';
    document.getElementById('errorMessage').style.display = 'none';
    

    
    // Update loading text to show progress
    const loadingText = document.querySelector('#loadingSpinner p');
    if (loadingText) {
        loadingText.textContent = 'Searching for all associated users across all levels... This may take a few moments.';
    }

    // Get CSRF token
    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTokenElement) {
        console.error('CSRF token meta tag not found!');
        showError('CSRF token not found. Please refresh the page.');
        document.getElementById('loadingSpinner').style.display = 'none';
        return;
    }
    
    const csrfToken = csrfTokenElement.getAttribute('content');
    console.log('CSRF Token found:', !!csrfToken);
    console.log('CSRF Token length:', csrfToken ? csrfToken.length : 0);
    
    // Create form data
    const formData = new FormData();
    formData.append('email', email);
    formData.append('_token', csrfToken);
    
    console.log('FormData created with email:', email);
    console.log('Making fetch request...');
    
    // Make fetch request with timeout
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
    
    fetch('{{ route("admin.assign-group.search") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        body: formData,
        signal: controller.signal
    })
    .then(response => {
        console.log('Response received!');
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        console.log('Response status text:', response.statusText);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('JSON parsed successfully!');
        console.log('Fetch Success Response:', data);
        document.getElementById('loadingSpinner').style.display = 'none';
        
        if (data.status) {
            console.log('Data status is true, displaying results...');
            displayResults(data.data);
        } else {
            console.log('Data status is false, showing error:', data.message);
            showError(data.message);
        }
    })
    .then(response => {
        clearTimeout(timeoutId); // Clear timeout on successful response
        console.log('Response received!');
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        console.log('Response status text:', response.statusText);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
        }
        return response.json();
    })
    .catch(error => {
        clearTimeout(timeoutId); // Clear timeout on error
        console.log('=== FETCH ERROR DETAILS ===');
        console.log('Error type:', typeof error);
        console.log('Error:', error);
        console.log('Error message:', error.message);
        console.log('Error stack:', error.stack);
        
        document.getElementById('loadingSpinner').style.display = 'none';
        
        if (error.name === 'AbortError') {
            showError('Request timed out. The search is taking too long. Please try with a user who has fewer associated users, or contact support.');
        } else {
            showError('An error occurred while searching: ' + error.message + '. This usually happens when there are too many associated users. Please try with a different user.');
        }
        
        if (error.message.includes('419')) {
            showError('CSRF token mismatch. Please refresh the page and try again.');
        } else if (error.message.includes('404')) {
            showError('Route not found. Please check if the route is properly configured.');
        } else if (error.message.includes('500')) {
            showError('Server error. Please check the server logs.');
        } else {
            showError('An error occurred while processing your request: ' + error.message);
        }
        console.error('Fetch Error:', error);
    });
}

// Global helper functions
function displayResults(data) {
    // Display IB user info
    const ibUser = data.ib_user;
    document.getElementById('ibUserInfo').innerHTML = `
        <strong>Name:</strong> ${ibUser.name}<br>
        <strong>Email:</strong> ${ibUser.email}<br>
        <strong>ID:</strong> ${ibUser.id}<br>
        <strong>Total Associated Users:</strong> ${data.associated_users.length}
    `;

    // Display all users (IB user + associated users)
    const allUsers = data.associated_users;
    let tableRows = '';
    
    if (allUsers.length > 0) {
        allUsers.forEach((user, index) => {
            // Add special styling for IB user
            const isIbUser = user.is_ib_user;
            const rowClass = isIbUser ? 'table-primary font-weight-bold' : '';
            const ibBadge = isIbUser ? '<span class="badge badge-primary ml-2">IB User</span>' : '';
            const nameCell = isIbUser ? `<strong>${user.name}</strong>${ibBadge}` : user.name;
            const emailCell = isIbUser ? `<strong>${user.email}</strong>` : user.email;
            
            tableRows += `
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
                    <td>
                        ${user.is_ib_user ? '<span class="badge badge-primary">Main IB</span>' : 
                          user.level > 1 ? `<span class="badge badge-info text-dark">Level ${user.level}</span>` : 
                          '<span class="badge badge-secondary text-dark">Level 1</span>'}
                    </td>
                </tr>
            `;
        });
    } else {
        tableRows = '<tr><td colspan="6" class="text-center">No users found</td></tr>';
    }
    
    document.getElementById('associatedUsersTable').innerHTML = tableRows;
    document.getElementById('resultsSection').style.display = 'block';
    
    // Set up checkbox event listeners
    setupCheckboxListeners();
}

function showError(message) {
    document.getElementById('errorText').textContent = message;
    document.getElementById('errorMessage').style.display = 'block';
}

function showWarning(message) {
    // Create warning alert if it doesn't exist
    let warningAlert = document.getElementById('warningMessage');
    if (!warningAlert) {
        warningAlert = document.createElement('div');
        warningAlert.id = 'warningMessage';
        warningAlert.className = 'alert alert-warning';
        warningAlert.innerHTML = '<i class="fa fa-exclamation-triangle"></i> <span id="warningText"></span>';
        
        // Insert after the error message
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.parentNode.insertBefore(warningAlert, errorMessage.nextSibling);
    }
    
    document.getElementById('warningText').textContent = message;
    warningAlert.style.display = 'block';
}

// Checkbox functionality
function setupCheckboxListeners() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const headerCheckbox = document.getElementById('headerCheckbox');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    
    // Master checkbox functionality
    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        headerCheckbox.checked = isChecked;
        
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateSelectedCount();
    });
    
    // Header checkbox functionality
    headerCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        selectAllCheckbox.checked = isChecked;
        
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateSelectedCount();
    });
    
    // Individual checkbox functionality
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateMasterCheckbox();
            updateSelectedCount();
        });
    });
    
    // Initial count update
    updateSelectedCount();
}

function updateMasterCheckbox() {
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const headerCheckbox = document.getElementById('headerCheckbox');
    
    const totalCheckboxes = userCheckboxes.length;
    const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked').length;
    
    // Update master checkboxes based on individual selections
    if (checkedCheckboxes === 0) {
        selectAllCheckbox.checked = false;
        headerCheckbox.checked = false;
    } else if (checkedCheckboxes === totalCheckboxes) {
        selectAllCheckbox.checked = true;
        headerCheckbox.checked = true;
    } else {
        selectAllCheckbox.checked = false;
        headerCheckbox.checked = false;
    }
}

// Get selected user IDs
function getSelectedUserIds() {
    const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    const selectedIds = [];
    
    selectedCheckboxes.forEach(checkbox => {
        selectedIds.push(checkbox.value);
    });
    
    return selectedIds;
}

// Show selected users
function showSelectedUsers() {
    const selectedIds = getSelectedUserIds();
    const selectedCount = selectedIds.length;
    
    if (selectedCount === 0) {
        alert('No users selected!');
        return;
    }
    
    const selectedUsers = [];
    selectedIds.forEach(id => {
        const checkbox = document.getElementById(`user_${id}`);
        const row = checkbox.closest('tr');
        const name = row.cells[2].textContent;
        const email = row.cells[3].textContent;
        const groups = row.cells[4].textContent;
        const level = row.cells[5].textContent.trim();
        selectedUsers.push({ id, name, email, groups, level });
    });
    
    let message = `Selected Users (${selectedCount}):\n\n`;
    selectedUsers.forEach((user, index) => {
        message += `${index + 1}. ${user.name} (${user.email})\n   Groups: ${user.groups}\n   Level: ${user.level}\n`;
    });
    
    alert(message);
}

// Update selected count
function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = selectedCount;
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Document ready');
    
    // Load client groups on page load
    loadClientGroups();
    
    // Search button click handler
    document.getElementById('searchBtn').addEventListener('click', function() {
        console.log('Search button clicked');
        searchUsers();
    });

    // Enter key press handler
    document.getElementById('email').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') { // Enter key
            console.log('Enter key pressed');
            searchUsers();
        }
    });
    

});

// Load client groups from server
function loadClientGroups() {
    console.log('Loading client groups...');
    
    fetch('{{ route("admin.assign-group.client-groups") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Client groups response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Client groups loaded:', data);
        if (data.status) {
            populateClientGroupsDropdown(data.data);
        } else {
            console.error('Failed to load client groups:', data.message);
            document.getElementById('clientGroups').innerHTML = '<option value="">Error loading groups</option>';
        }
    })
    .catch(error => {
        console.error('Error loading client groups:', error);
        document.getElementById('clientGroups').innerHTML = '<option value="">Error loading groups</option>';
    });
}

// Populate client groups container with checkboxes
function populateClientGroupsDropdown(groups) {
    const container = document.getElementById('clientGroupsContainer');
    let html = '';
    
    if (groups.length === 0) {
        html = '<div class="text-center text-muted">No client groups found</div>';
    } else {
        groups.forEach(group => {
            const displayText = `${group.group_name} (${group.group_id}) - ${group.server} ${group.account_category}`;
            html += `
                <div class="form-check mb-2">
                    <input class="form-check-input group-checkbox" type="checkbox" 
                           value="${group.id}" 
                           id="group_${group.id}"
                           data-group-name="${group.group_name}" 
                           data-group-id="${group.group_id}">
                    <label class="form-check-label" for="group_${group.id}" style="cursor: pointer;">
                        ${displayText}
                    </label>
                </div>
            `;
        });
    }
    
    container.innerHTML = html;
    console.log('Client groups container populated with', groups.length, 'groups');
    
    // Add change event listeners to update count
    const checkboxes = container.querySelectorAll('.group-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedGroupsCount();
        });
    });
    
    // Initial count update
    updateSelectedGroupsCount();
}

// Get selected client groups
function getSelectedClientGroups() {
    const checkboxes = document.querySelectorAll('.group-checkbox:checked');
    const selectedGroups = [];
    
    checkboxes.forEach(checkbox => {
        if (checkbox.value) {
            selectedGroups.push({
                id: parseInt(checkbox.value), // Ensure it's an integer
                name: checkbox.getAttribute('data-group-name'),
                groupId: checkbox.getAttribute('data-group-id')
            });
        }
    });
    
    console.log('Selected groups from checkboxes:', selectedGroups);
    return selectedGroups;
}

// Show selected groups
function showSelectedGroups() {
    const selectedGroups = getSelectedClientGroups();
    const selectedCount = selectedGroups.length;
    
    if (selectedCount === 0) {
        alert('No groups selected!');
        return;
    }
    
    let message = `Selected Groups (${selectedCount}):\n\n`;
    selectedGroups.forEach((group, index) => {
        message += `${index + 1}. ${group.name} (${group.groupId})\n`;
    });
    
    alert(message);
}

// Update selected groups count
function updateSelectedGroupsCount() {
    const selectedCount = document.querySelectorAll('.group-checkbox:checked').length;
    document.getElementById('selectedGroupsCount').textContent = selectedCount;
}

// Select all groups
function selectAllGroups() {
    const checkboxes = document.querySelectorAll('.group-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelectedGroupsCount();
    console.log('All groups selected');
}

// Deselect all groups
function deselectAllGroups() {
    const checkboxes = document.querySelectorAll('.group-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedGroupsCount();
    console.log('All groups deselected');
}

// Assign groups to selected users with batch processing
function assignGroupsToUsers() {
    console.log('=== ASSIGN GROUPS FUNCTION START ===');
    
    try {
        const selectedUserIds = getSelectedUserIds();
        const selectedGroups = getSelectedClientGroups();
        
        console.log('Selected User IDs:', selectedUserIds);
        console.log('Selected Groups:', selectedGroups);
        
        if (selectedUserIds.length === 0) {
            alert('Please select at least one user!');
            return;
        }
        
        if (selectedGroups.length === 0) {
            alert('Please select at least one client group!');
            return;
        }
        
        // Confirm the action
        const confirmMessage = `Are you sure you want to assign ${selectedGroups.length} group(s) to ${selectedUserIds.length} selected user(s)?\n\nThis will update the client_groups field for all selected users.`;
        
        if (!confirm(confirmMessage)) {
            console.log('User cancelled the operation');
            return;
        }
        
        // Show loading state and progress bar
        const assignButton = document.querySelector('button[onclick="assignGroupsToUsers()"]');
        const originalText = assignButton.innerHTML;
        assignButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        assignButton.disabled = true;
        
        // Show progress bar
        const progressContainer = document.getElementById('progressContainer');
        const progressText = document.getElementById('progressText');
        const progressPercent = document.getElementById('progressPercent');
        const progressBar = document.getElementById('progressBar');
        
        progressContainer.style.display = 'block';
        progressText.textContent = 'Starting batch processing...';
        progressPercent.textContent = '0%';
        progressBar.style.width = '0%';
        progressBar.setAttribute('aria-valuenow', '0');
        
        // Prepare data
        const groupIds = selectedGroups.map(group => group.id);
        console.log('Group IDs to send:', groupIds);
        
        // Process in batches if there are many users
        const batchSize = 500; // Process 500 users at a time
        const totalBatches = Math.ceil(selectedUserIds.length / batchSize);
        
        console.log(`Processing ${selectedUserIds.length} users in ${totalBatches} batches of ${batchSize} each`);
        
        // Start batch processing
        processBatch(selectedUserIds, groupIds, 1, totalBatches, assignButton, originalText, progressText, progressPercent, progressBar);
        
    } catch (error) {
        console.error('=== ASSIGN GROUPS FUNCTION ERROR ===');
        console.error('Error in assignGroupsToUsers function:', error);
        alert('❌ Error in function: ' + error.message);
        
        // Restore button state
        const assignButton = document.querySelector('button[onclick="assignGroupsToUsers()"]');
        if (assignButton) {
            assignButton.innerHTML = '<i class="fa fa-save"></i> Assign Groups to Selected Users';
            assignButton.disabled = false;
        }
    }
}

// Process users in batches
function processBatch(allUserIds, groupIds, currentBatch, totalBatches, assignButton, originalText, progressText, progressPercent, progressBar) {
    const batchSize = 500;
    const startIndex = (currentBatch - 1) * batchSize;
    const endIndex = Math.min(startIndex + batchSize, allUserIds.length);
    const batchUserIds = allUserIds.slice(startIndex, endIndex);
    
    console.log(`Processing batch ${currentBatch}/${totalBatches} - Users ${startIndex + 1} to ${endIndex}`);
    
    // Update progress
    const progress = Math.round((currentBatch / totalBatches) * 100);
    progressText.textContent = `Processing batch ${currentBatch} of ${totalBatches} (${batchUserIds.length} users)...`;
    progressPercent.textContent = `${progress}%`;
    progressBar.style.width = `${progress}%`;
    progressBar.setAttribute('aria-valuenow', progress);
    
    // Update button text to show progress
    assignButton.innerHTML = `<i class="fa fa-spinner fa-spin"></i> Processing Batch ${currentBatch}/${totalBatches}...`;
    
    // Create form data for this batch
    const formData = new FormData();
    
    // Append each user ID individually
    batchUserIds.forEach(userId => {
        formData.append('user_ids[]', userId);
    });
    
    // Append each group ID individually
    groupIds.forEach(groupId => {
        formData.append('group_ids[]', groupId);
    });
    
    // Add batch information
    formData.append('batch_number', currentBatch);
    formData.append('total_batches', totalBatches);
    formData.append('is_first_batch', currentBatch === 1);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    console.log(`Making batch ${currentBatch} request with ${batchUserIds.length} users`);
    
    // Make the request
    fetch('{{ route("admin.assign-group.assign-groups") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => {
        console.log(`Batch ${currentBatch} response status:`, response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            throw new Error('Response is not JSON. Content-Type: ' + contentType);
        }
    })
    .then(data => {
        console.log(`Batch ${currentBatch} success response:`, data);
        
        if (data.status) {
            console.log(`Batch ${currentBatch} completed successfully`);
            
            // Check if this is the last batch
            if (data.data.is_complete || currentBatch >= totalBatches) {
                // All batches completed
                let message = `✅ All batches completed successfully!\n\n`;
                message += `Total batches processed: ${totalBatches}\n`;
                message += `Total users updated: ${allUserIds.length}\n`;
                message += `Groups assigned: [${groupIds.join(', ')}]`;
                
                if (data.data.errors && data.data.errors.length > 0) {
                    message += `\n\nErrors:\n${data.data.errors.join('\n')}`;
                }
                
                alert(message);
                console.log('All batches completed successfully!');
                
                // Hide progress bar and restore button state
                document.getElementById('progressContainer').style.display = 'none';
                assignButton.innerHTML = originalText;
                assignButton.disabled = false;
            } else {
                // Process next batch
                setTimeout(() => {
                    processBatch(allUserIds, groupIds, currentBatch + 1, totalBatches, assignButton, originalText, progressText, progressPercent, progressBar);
                }, 100); // Small delay between batches
            }
        } else {
            throw new Error('Batch failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error(`=== BATCH ${currentBatch} ERROR ===`);
        console.error('Error:', error);
        console.error('Error message:', error.message);
        
        alert(`❌ Error in batch ${currentBatch}: ${error.message}`);
        
        // Hide progress bar and restore button state
        document.getElementById('progressContainer').style.display = 'none';
        assignButton.innerHTML = originalText;
        assignButton.disabled = false;
    });
}
</script>
@endsection
