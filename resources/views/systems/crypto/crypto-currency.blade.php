@extends('layouts.system-layout')
@section('title','Online Banks')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/vendors.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
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
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Banks</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="index.html">{{__('admin-breadcrumbs.home')}}</a>
                                </li>
                                <li class="breadcrumb-item active">Online Bank</li>
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
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
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
                                <h4 class="card-title">{{__('client-management.Report Filter')}}</h4>
                                <div class="btn-exports d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#success">
                                        Add new bank
                                    </button>
                                </div>
                            </div>
                            <!--Search Form -->
                            <div class="card-body mt-2">
                                <form class="dt_adv_search" method="POST" id="filter-form">
                                    <div class="row g-1 mb-md-1">
                                        <div class="col-md-4">
                                            <label class="form-label" for="status">Status</label>
                                            <select class="select2 form-select" id="status" name="status">
                                                <option value="">{{__('finance.All')}}</option>
                                                <option value="active">Active</option>
                                                <option value="disable">Disable</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{__('client-management.Date')}}</label>
                                            <div class="mb-0">
                                                <input type="text" class="form-control dt-date flatpickr-range dt-input" data-column="5" placeholder="StartDate to EndDate" data-column-index="4" name="dt_date" />
                                                <input type="hidden" class="form-control dt-date start_date dt-input" data-column="5" data-column-index="4" name="value_from_start_date" />
                                                <input type="hidden" class="form-control dt-date end_date dt-input" name="value_from_end_date" data-column="5" data-column-index="4" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="flter-country">Country</label>
                                            <select class="select2 form-select" id="filter-country" name="country">
                                                <option value="">{{__('client-management.All')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <label class="form-label" for="filter-name">{{__('finance.name')}}</label>
                                            <input id="filter-name" type="text" class="form-control dt-input" data-column="4" placeholder="{{__('finance.name')}}" data-column-index="3" />
                                        </div>

                                        <div class="col-md-8">
                                            <div class="row mt-2">
                                                <div class="col-lg-6 d-grid">
                                                    <button id="btn-reset" type="button" class="btn btn-secondary">{{__('client-management.Reset')}}</button>
                                                </div>
                                                <div class="col-lg-6 d-grid">
                                                    <button id="btn-filter" type="button" class="btn btn-primary">{{__('client-management.Filter')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr class="my-0" />
                        </div>

                        <div class="card">
                            <div class="card-datatable">
                                <table class="datatables-ajax table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Bank Name</th>
                                            <th>Bank Country</th>
                                            <th>Bank Code</th>
                                            <th>Currency</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
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
<!-- Modal add banks -->
<div class="modal fade text-start modal-success" id="success" tabindex="-1" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{route('system.crypto-currency.store')}}" method="post" id="form-add-new-bank" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">Add new crypto currency</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- crypto symbol -->
                <div class="form-group mt-1">
                    <label for="crypto-symbol">Crypto symbol</label>
                    <input type="text" name="crypto_symbol" id="crypto-symbol" class="form-input form-control" placeholder="USDT">
                </div>
                <!-- crypto currency -->
                <div class="form-group mt-1">
                    <label for="crypto-currency">Crypto currency</label>
                    <input type="text" id="crypto-currency" class="form-input form-control" name="crypto_currency" placeholder="ERC20">
                </div>
                <!-- PAYMENT CURRENCY -->
                <div class="form-group mt-1">
                    <label for="payment-currency">Payment Currency</label>
                    <input type="text" id="payment-currency" class="form-input form-control" name="payment_currency" placeholder="payment_currency">
                </div>
            </div>
            <div class="modal-footer mb-2">
                <button type="button" class="btn btn-success" id="btn-save-bank" data-el="fg" data-btnid="btn-save-bank" data-form="form-add-new-bank" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" onclick="_run(this)" data-callback="add_crypto_callback">
                    <i data-feather='save'></i>
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
<!-- bank edit modal -->
<div class="modal fade text-start modal-success" id="bank-edit-modal" tabindex="-1" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{route('systems.online-bank.edit-bank')}}" method="post" id="form-edit-bank" class="modal-content">
            @csrf
            <input type="hidden" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">Edit online bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mt-1">
                    <label for="bank-country">Bank Country</label>
                    <select name="bank_country" id="bank-country-edit" class="select2 form-select">
                        <!-- country from select2 -->
                    </select>
                </div>
                <div class="form-group mt-1">
                    <label for="bank-name">Bank Name</label>
                    <input type="text" id="bank-name-edit" class="form-input form-control" name="bank_name" placeholder="Bank name">
                </div>
                <div class="form-group mt-1">
                    <label for="bank-code">Bank Code</label>
                    <input type="text" id="bank-code-edit" class="form-input form-control" name="bank_code" placeholder="Bank code">
                </div>
                <!-- currency -->
                <div class="form-group mt-1">
                    <label for="currency">Currency</label>
                    <input type="text" name="currency" id="currency-edit" class="form-input form-control" placeholder="Currency">
                </div>
            </div>
            <div class="modal-footer mb-2">
                <button type="button" class="btn btn-success" id="btn-save-edit-bank" data-el="fg" data-btnid="btn-save-edit-bank" data-form="form-edit-bank" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" onclick="_run(this)" data-callback="edit_bank_callback">
                    <i data-feather='save'></i>
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')

<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>

<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>
<!-- <script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.flash.min.js')}}"></script> -->
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<script src="{{asset('common-js/select2-get-country.js')}}"></script>
<script src="{{asset('common-js/rz-plugins/get-edit-data.js')}}"></script>

<script>
    // get country in select2
    $("#bank-country").get_country({
        modal_id: '#success',
        url: '/search/country/with-name',
    });
    $("#bank-country-edit").get_country({
        modal_id: '#bank-edit-modal',
        url: '/search/country/with-name',
    });

    // Datepicker for advanced filter
    // ---------------------------------------------------------------------------
    var separator = ' - ',
        rangePickr = $('.flatpickr-range'),
        dateFormat = 'MM/DD/YYYY';
    var options = {
        autoUpdateInput: false,
        autoApply: true,
        locale: {
            format: dateFormat,
            separator: separator
        },
        opens: $('html').attr('data-textdirection') === 'rtl' ? 'left' : 'right'
    };

    //Range Picker
    // ---------------------------------------------------------------------------------------------
    if (rangePickr.length) {
        rangePickr.flatpickr({
            mode: 'range',
            dateFormat: 'm/d/Y',
            onClose: function(selectedDates, dateStr, instance) {
                var startDate = '',
                    endDate = new Date();
                if (selectedDates[0] != undefined) {
                    startDate =
                        selectedDates[0].getMonth() + 1 + '/' + selectedDates[0].getDate() + '/' + selectedDates[0].getFullYear();
                    $('.start_date').val(startDate);
                }
                if (selectedDates[1] != undefined) {
                    endDate =
                        selectedDates[1].getMonth() + 1 + '/' + selectedDates[1].getDate() + '/' + selectedDates[1].getFullYear();
                    $('.end_date').val(endDate);
                }
                $(rangePickr).trigger('change').trigger('keyup');
            }
        });
    }

    // datatable
    var datatable = $(".datatables-ajax").fetch_data({
        url: '/system/crypto/crypto-currency/datatable',
        columns: [{
                "data": "crypto_symbol"
            },
            {
                data: "crypto_currency"
            },
            {
                data: "payment_currency"
            },
            {
                data: "status",
            },
            {
                data: "date"
            },
            {
                data: "action"
            }
        ],

    })
    // edit bank
    $(".btn-edit").push_data({
        url: '/system/banks/online-bank-list/get/edit-data',
        data: {
            id: 1
        },
        button: '.btn-edit',
        modal: '#bank-edit-modal',
        elements: [{
            el: 'bank_country'
        }, {
            el: 'bank_name'
        }, {
            el: 'bank_code'
        }, {
            el: 'id'
        }, {
            el: 'currency'
        }],
    });
    // add new crypto callback
    function add_crypto_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Add new crypto currency');
            datatable.draw();
            $("#form-add-new-bank").trigger('reset');
        } else {
            notify('error', data.message, 'Add new crypto currency');
        }
        $.validator("form-add-new-bank", data.errors);
    }

    // submit edit form
    function edit_bank_callback(data) {
        if (data.status) {
            notify('success', data.message, 'Edit Bank');
            datatable.draw();
        } else {
            notify('error', data.message, 'Edit Bank');
        }
        $.validator("form-edit-bank", data.errors);
    }
</script>

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
</script>
@stop
<!-- BEGIN: page JS -->