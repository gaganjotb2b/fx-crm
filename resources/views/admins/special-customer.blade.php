@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Special Customer')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">

<!-- number input -->
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">

@stop
<!-- END: page css -->
<!-- BEGIN: content -->
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <!-- Content Header Code -->
        </div>
        <div class="row g-1">
            <div class="col-md-4"></div>
            <div class="card col-md-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.trader-make-special-customer') }}">
                        @csrf
                        <div class="row g-1">
                            <div class="col-md-12 mb-1 form-group">
                                <label class="form-label">Trader Email</label>
                                <input name="email" id="trader-email" type="text" class="form-control dt-input @error('email') is-invalid @enderror" placeholder="Trader Email">
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-1 form-group">
                                <label class="form-label">Select Groups</label>
                                <select id="group-dropdown" class="form-control select2" multiple>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}">
                                            {{ $group->group_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Display tags -->
                            <div id="selected-tags" class="mb-2"></div>

                            <!-- Hidden field to store selected IDs -->
                            <input type="hidden" name="groups" id="hidden-field">

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary form-control waves-float waves-light">Submit</button>
                            </div>
                        </div>
                    </form>
                    @if (session('success'))
                        <div style="color: green;">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- END: Content-->

<!-- Enable backdrop (default) -->
<div class="enable-backdrop">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasBackdrop" aria-labelledby="offcanvasBackdropLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasBackdropLabel" class="offcanvas-title">Add New Admin Group</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
            <p>
                <b>Group Name as Rols Name.</b>
                A role provided access to predefined menus and features so that depending
                on assigned role an administrator can have access to what he need
            </p>
            <form action="{{route('admin.add-admin-group')}}" method="post" id="admin-group-form">
                @csrf
                <label class="form-label" for="group-name">Group Name</label>
                <input id="group-name" class="form-control" type="text" placeholder="Normal Input" name="group_name" />
                <button type="button" id="save-group" class="btn btn-primary mb-1 d-grid w-100 mt-1">Save Group</button>
                <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                    Cancel
                </button>
            </form>

        </div>
    </div>
</div>
<!--/ Enable backdrop (default) -->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<!-- here add vendor js -->
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>

<!-- datatable -->

@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script>
alert(4);
document.addEventListener("DOMContentLoaded", function () {
    const groupDropdown = document.getElementById("group-dropdown");
    const selectedTagsContainer = document.getElementById("selected-tags");
    const hiddenField = document.getElementById("hidden-field");

    // Array to store selected IDs
    let selectedIds = [];

    groupDropdown.addEventListener("change", function () {
        // Get selected options
        const selectedOptions = Array.from(groupDropdown.selectedOptions);

        // Loop through selected options
        selectedOptions.forEach((option) => {
            if (!selectedIds.includes(option.value)) {
                // Add to selected IDs
                selectedIds.push(option.value);

                // Create a tag
                const tag = document.createElement("span");
                tag.className = "badge bg-primary me-1 mt-1";
                tag.textContent = option.text;

                // Add remove button to tag
                const removeBtn = document.createElement("span");
                removeBtn.className = "ms-2 cursor-pointer text-white";
                removeBtn.style.cursor = "pointer";
                removeBtn.textContent = "x";

                // Add remove functionality
                removeBtn.addEventListener("click", function () {
                    // Remove from selected IDs
                    const index = selectedIds.indexOf(option.value);
                    if (index > -1) {
                        selectedIds.splice(index, 1);
                    }

                    // Remove tag from DOM
                    selectedTagsContainer.removeChild(tag);

                    // Unselect in dropdown
                    option.selected = false;

                    // Update hidden field
                    hiddenField.value = selectedIds.join(",");
                });

                tag.appendChild(removeBtn);

                // Append to container
                selectedTagsContainer.appendChild(tag);
            }
        });

        // Update hidden field
        hiddenField.value = selectedIds.join(",");
    });
});

</script>
@stop
<!-- BEGIN: page JS -->