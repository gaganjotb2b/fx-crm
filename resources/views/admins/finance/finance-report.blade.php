@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Finance Report')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/vendors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
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
                        <h2 class="content-header-title float-start mb-0">{{ __('page.finance') }}
                            {{ __('page.reports') }}
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">{{ __('admin-breadcrumbs.home') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{ __('page.finance') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('page.finance') }} {{ __('page.reports') }}
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
            <!-- Ajax Sourced Server-side -->
            <section id="ajax-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom d-flex justfy-content-between">
                                <h4 class="card-title">{{ __('client-management.Report Filter') }}</h4>
                                <div class="btn-exports d-flex justify-content-between">
                                    <select data-placeholder="Select a state..." class="select2-icons form-select" id="fx-export">
                                        <option value="download" data-icon="download" selected>
                                            {{ __('client-management.Export') }}
                                        </option>
                                        <option value="csv" data-icon="file">CSV</option>
                                        <option value="excel" data-icon="file">Excel</option>
                                    </select>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form class="dt_adv_search" method="POST" id="filter-form">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4">
                                            <label class="form-label" for="month">{{ __('page.search_by_month') }}</label>
                                            <select class="select2 form-select" id="month" name="month">
                                                <option value="">{{ __('page.all') }}</option>
                                                <option value="this_month">{{ __('page.this_month') }}</option>
                                                <option value="last_month">{{ __('page.last_month') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('client-management.Date') }}</label>
                                            <div class="mb-0">
                                                <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="5" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
                                                <input type="hidden" class="form-control dt-date start_date dt-input" data-column="5" data-column-index="4" name="value_from_start_date" />
                                                <input type="hidden" class="form-control dt-date end_date dt-input" name="value_from_end_date" data-column="5" data-column-index="4" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('page.transaction_for') }}</label>
                                            <select class="select2 form-select" id="transaction_for" name="transaction_for">
                                                <option value="">{{ __('client-management.All') }}</option>
                                                @foreach ($admin_group as $value)
                                                <option value="admin_{{ $value->id }}">
                                                    {{ $value->group_name }}
                                                </option>'
                                                @endforeach
                                                @foreach ($manager_group as $value)
                                                <option value="manager_{{ $value->id }}">
                                                    {{ $value->group_name }}
                                                </option>'
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <label for="">Transaction Type</label>
                                            <select class="select2 form-select" name="transaction_type" id="transaction_type">
                                                <optgroup label="Search By Transaction Type">
                                                    <option value="">All</option>
                                                    <option value="add">Add</option>
                                                    <option value="deduct">Deduct</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Admin Email</label>
                                            <input id="email" type="text" name="email" class="form-control dt-input" data-column="4" placeholder="Admin Email" data-column-index="3" />
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Account / Desk Manager</label>
                                            <input id="manager" type="text" name="manager_info" class="form-control dt-input" data-column="4" placeholder="Manager Name / Email" data-column-index="3" />
                                        </div>
                                        
                                    </div>
                                    <div class="row mt-2">
                                            <div class="col-lg-4"></div>
                                            <div class="col-lg-4 d-grid">
                                                <button id="btn-reset" type="button" class="btn btn-secondary">{{ __('client-management.Reset') }}</button>
                                            </div>
                                            <div class="col-lg-4 d-grid">
                                                <button id="btn-filter" type="button" class="btn btn-primary">{{ __('client-management.Filter') }}</button>
                                            </div>
                                        </div>
                                </form>
                            </div>
                            <hr class="my-0" />
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body mt-2  table-responsive">
                                        <table class="datatables-ajax table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('finance.Name') }}</th>
                                                    <th>{{ __('page.source') }}</th>
                                                    <th>{{ __('page.Transactions') }}</th>
                                                    <th>{{ __('finance.Amount') }}</th>
                                                    <th>{{ __('page.status') }}</th>
                                                    <th>{{ __('page.date') }}</th>
                                                    <th>{{ __('page.action') }}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Ajax Sourced Server-side -->
        </div>
    </div>
</div>
<!-- END: Content-->
<!-- Modal Themes start -->
<!-- add new trader modal -->
<div class="modal fade text-start modal-primary" id="add-new-trader" tabindex="-1" aria-labelledby="Add New Trader" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('admin.trader-admin-add-new-trader') }}" method="post" class="modal-content" id="trader-registration-form">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add New Trader</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <!-- full name -->
                <div class="mb-1">
                    <label class="form-label" for="full-name">Full Name</label>
                    <input type="text" class="form-control" id="full-name" name="full_name" placeholder="Ex: John Arifin" />
                </div>
                <!-- emmail -->
                <div class="mb-1">
                    <label class="form-label" for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Ex: mail@example.como" />
                </div>
                <!-- phone -->
                <div class="mb-1">
                    <label class="form-label" for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="+8801747XXXXXXX" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary mb-1 text-center" id="btn-add-new-trader" onclick="_run(this)" data-el="fg" data-form="trader-registration-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="trader_registration_call_back" data-btnid="btn-add-new-trader" style="width:200px">Save Trader</button>
            </div>
        </form>
    </div>
</div>
<!-- end add new trader modal -->
<!-- Modal Themes end -->

@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js') }}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')

<script src="{{ asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js') }}"></script>
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

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<!-- <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js') }}"></script> -->
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<!-- <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-quill-editor.js') }}"></script> -->
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/finance-report.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<!-- <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script> -->
<script>
    // start: add mew traders--------------------------------------------------
    function trader_registration_call_back(data) {
        $.validator("trader-registration-form", data.errors);
        if (data.trader_registration == true) {
            toastr['success']('New Trader Successfully Registered', 'Trader Registration', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
            $("#add-new-trader").modal('hide');
            $("#trader-registration-form").trigger('reset');
            $("#server, #client-type, #account-type, #leverage, #country").trigger("change");
        } else {
            toastr['error']('New Trader registration Failed', 'Trader Registration', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
        }
        // message for create trading account
        if (data.create_trading_account == true) {
            toastr['success']('Trading Account Successfully Created', 'Trading Account', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
        } else {
            toastr['error']('Trading Account Creation Failed', 'Trading Account', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
        }
        // sending welcome mail-----------------
        if (data.welcome_mail == true) {
            let trader_id = data.trader_id;
            Swal.fire({
                title: 'Welcome Email',
                text: 'Are You Confirm to send welcome mail ?',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Send Email',
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    $(".swal2-html-container").text("We Sending Email, Please Wait.....")
                    return fetch(`/admin/client-management/send-welcome-email/` + trader_id)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    if (result.value.status == false) {
                        toastr['error'](result.value.message, 'Welcome Email', {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            timeOut: 2000,
                        });
                    } else {
                        toastr['success'](result.value.message, 'Welcome Email', {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            closeButton: true,
                            tapToDismiss: false,
                            progressBar: true,
                            timeOut: 2000,
                        });
                    }

                }
            })
            $(".swal2-confirm").trigger("click");
        }
        $('.datatables-ajax').DataTable().draw();
    }
    // END: assign account manager-----------------------------------------------------


    //CHANGE STATUS ON CLICK 
    $(document).on('click', '.change_data_status', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/finance/finance-change-st',
            method: 'POST',
            dataType: 'json',
            data: {
                id: $(this).data('id'),
                sts: $(this).data('value'),
            },
            success: function(data) {
                if (data.success) {
                    toastr['success']('Action', 'Status Updated Success', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                    $('.datatables-ajax').DataTable().draw();
                } else {
                    toastr['error']('Action', 'Status Update Failed!', {
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        closeButton: true,
                        tapToDismiss: false,
                        progressBar: true,
                        timeOut: 2000,
                    });
                }

            }
        })
    });


    /*<---------------Datatable Descriptions for admin log Start------------>*/
    $(document).on("click", ".dt-description", function(params) {
        let __this = $(this);
        let id = $(this).data('id');
        $.ajax({
            type: "GET",
            url: '/admin/finance/finance-report/add-deduct/' + id,
            dataType: 'json',
            success: function(data) {
                if (data.status == true) {
                    if ($(__this).closest("tr").next().hasClass("description")) {
                        $(__this).closest("tr").next().remove();
                        $(__this).find('.w').html(feather.icons['plus'].toSvg());
                    } else {
                        $(__this).closest('tr').after(data.description);
                        $(__this).closest('tr').next('.description').slideDown('slow').delay(5000);
                        $(__this).find('.w').html(feather.icons['minus'].toSvg());
                    }
                }
            }
        })
    });
</script>
@stop
<!-- BEGIN: page JS -->