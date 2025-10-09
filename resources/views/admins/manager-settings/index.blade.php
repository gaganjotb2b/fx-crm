@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Add New Manager')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css') }}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-validation.css') }}">
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
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('admin-management.add_manager') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin-management.home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('admin-management.manager-settings') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('admin-management.add_manager') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i class="me-1" data-feather="info"></i>
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Note cards -->
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4><b>{{ __('admin-management.not') }}</b></h4>
                            <p>{{ __('admin-management.sen3') }}</p>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                <p>{{ __('admin-management.sen4') }}</p>
                            </div>
                            <div class="border-start-3 border-start-success p-1 mb-1 bg-light-info">
                                <p>{{ __('admin-management.sen5') }}</p>
                            </div>
                            <div class="border-start-3 border-start-info p-1 mb-1 bg-light-info">
                                <p>{{ __('admin-management.sen6') }}</p>
                            </div>
                            <div class="border-start-3 border-start-danger p-1 mb-1 bg-light-info">
                                <p>{{ __('admin-management.sen7') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if (Auth::user()->hasDirectPermission('create add manager'))
                <div class="col-xl-8 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4><b>{{ __('admin-management.Manager Registration') }}</b></h4>
                        </div>
                        <hr>
                        <div class="card-body">
                            <form action="{{ route('admin.add-manager') }}" method="post" id="form-create-manager">
                                @csrf
                                <div class="mb-1 row">
                                    <label for="name" class="col-sm-3 col-form-label">{{ __('admin-management.Name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Full Name" />
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <label for="email" class="col-sm-3 col-form-label">{{ __('admin-management.Email') }}</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Manager Email" />
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <label for="phone" class="col-sm-3 col-form-label">{{ __('admin-management.Phone') }}</label>
                                    <div class="col-sm-9">
                                        <input type="phone" class="form-control" id="phone" name="phone" placeholder="+88017478941XX" />
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-1 row">
                                    <div class="col-lg-4">
                                        <div class="d-flex flex-column">
                                            <label class="form-check-label mb-50" for="clieant-area">{{ __('admin-management.Area of Client is Global') }}
                                                &quest;</label>
                                            <div class="form-check form-check-primary form-switch">
                                                <input type="checkbox" name="is_global" class="form-check-input" id="clieant-area" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <!-- Multiple Select -->
                                        <div class="mb-1" id="multiple-countries">
                                            <label class="form-label" for="client-country">{{ __('admin-management.Client Countries') }}</label>
                                            <select class="select2 form-select" id="client-country" name="client_country[]" multiple>
                                                @foreach ($countries as $value)
                                                <option value="{{ $value->id }}" {{ $value->id == 1 ? 'selected' : '' }}>{{ $value->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <div class="col-lg-12">
                                        <label class="form-label" for="agent-country">{{ __('admin-management.Agent Countries') }}</label>
                                        <select class="select2 form-select" id="agent-country" name="agent_country">
                                            @foreach ($countries as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <div class="col-lg-6">
                                        <label class="form-label" for="type">{{ __('admin-management.Type') }}</label>
                                        <select class="select2 form-select" id="type" name="type">
                                            <!-- <option value="">Select Type</option> -->
                                            <option value="1" selected>
                                                {{ __('admin-management.Account Manager') }}
                                            </option>
                                            <option value="0">{{ __('admin-management.Desk Manager') }}
                                            </option>
                                            <option value="6">{{ __('admin-management.Admin Manager') }}
                                            </option>
                                            <option value="7">{{ __('admin-management.Country Manager') }}
                                            </option>
                                        </select>
                                    </div>
                                    @if (count($groups) != 0)
                                    <div class="col-lg-6">
                                        <label class="form-label" for="group-type">{{ __('admin-management.Manager Group') }}</label>
                                        <select class="select2 form-select" id="group-type" name="manager_group">
                                            <option value="">
                                                {{ __('admin-management.Select Manager Group') }}
                                            </option>
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-lg-6">
                                        <label class="form-label d-flex justify-content-between" for="group-type"><span>{{ __('admin-management.Manager Group') }}</span></label>
                                        <div class="alert alert-danger  alert-validation-msg mb-0" role="alert">
                                            <div class="alert-body d-flex align-items-center ">
                                                <i data-feather="info" class="me-50"></i>
                                                <span>{{ __("Can't find any manager group") }}</span>
                                            </div>

                                        </div>
                                        <div style="text-align: right"> <a class="text-success" href='{{ route('admin.manager-groups') }}'>{{ __('admin-management.Add New Group') }}</a>
                                        </div>


                                        <input type="hidden" name="manager_group">

                                    </div>
                                    @endif
                                </div>
                                <div class="mb-1 row">
                                    <div class="col-lg-6">
                                        <label class="form-label" for="priority">{{ __('admin-management.Priority') }}</label>
                                        <select class="select2 form-select" id="priority" name="priority">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label" for="mailable">{{ __('admin-management.Send Account Details on Email') }}
                                            &quest;</label>
                                        <div id="mailable" style="margin-top:6px">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_mailable" id="mailable-yes" value="1" checked />
                                                <label class="form-check-label" for="mailable-yes">{{ __('admin-management.Yes') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_mailable" id="mailable-no" value="0" />
                                                <label class="form-check-label" for="mailable-no">{{ __('admin-management.No') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <div class="col-lg-6">
                                        <label class="form-label" for="monthly-limit">{{ __('admin-management.Monthly Limit') }}</label>
                                        <input type="number" class="form-control" id="monthly-limit" name="monthly_limit" placeholder="0" value="0">
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label" for="daily-limit">{{ __('admin-management.Daily Limit') }}</label>
                                        <input type="number" class="form-control" id="daily-limit" name="daily_limit" placeholder="0" value="0">
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <div class="col-lg-6">
                                        <label class="form-label" for="password">{{ __('admin-management.Password') }}</label>
                                        <div class="input-group form-password-toggle mb-2">
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                            <input data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" type="password" class="form-control" id="password" name="password" placeholder="Your Password" aria-describedby="basic-default-password" />
                                            <button class="btn btn-primary waves-effect waves-float waves-light btn-gen-password" type="button" id="rstButton"><i class="fas fa-key"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label" for="confirm-password">{{ __('admin-management.Confirm Password') }}</label>
                                        <div class="input-group form-password-toggle mb-2">
                                            <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Confirm Your Password" aria-describedby="basic-default-password" />
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-1 row mt-2">
                                    <div class="col-lg-8"></div>
                                    <div class="col-lg-4">
                                        <button class="btn btn-primary float-end" type="button" id="create-manager" onclick="_run(this)" data-el="fg" data-form="form-create-manager" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="create_manager_call_back" data-btnid="create-manager" style="width:180px">{{ __('admin-management.Create Manager') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-xl-8 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            @include('errors.permission')
                        </div>
                    </div>
                </div>
                @endif

            </div>
            <!--/ Form cards -->
        </div>
    </div>
</div>
<!-- END: Content-->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<!-- number input -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/create-manager.js') }}"></script>
<script src="{{ asset('/common-js/copy-js.js') }}"></script>
<script src="{{ asset('/common-js/password-gen.js') }}"></script>
<script>
    // disable manage submit button
    $(document).on("click", "#create-manager", function() {
        $(this).prop('disabled', true);
    })

    function create_manager_call_back(data) {
        console.log('Response data:', data); // Debug log
        if (data.status === true || data.status === 'true') {
            notify('success', data.message, 'Manager registration')
            $("#form-create-manager").trigger('reset');
            // Reset the manager group dropdown
            $("#group-type").html('<option value="">Select Manager Group</option>');
        } else {
            notify('error', data.message, 'Manager registration')
        }
        $("#create-manager").prop('disabled', false);
        if (data.errors) {
            $.validator("form-create-manager", data.errors);
        }
    }

    // genrate randome password
    $(document).on('click', ".btn-gen-password", function() {
        var field = $(this).closest('div').find('input[rel="gp"]');
        field.val(rand_string(field));
        field.attr('type', 'text');
    });
    // select password for copy
    $('input[rel="gp"]').on("click", function() {
        let id = $(this).attr('id');
        $(this).select();
        if ($(this).val() != "") {
            copy_to_clipboard(id)
        }
        $(this).attr('type', 'password');
    });
</script>
@stop
<!-- BEGIN: page JS -->