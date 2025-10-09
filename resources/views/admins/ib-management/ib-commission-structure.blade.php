@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'IB Commission Structure')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
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
<style>
    .al-col-m {
        transition: 1s;
    }

    @media only screen and (max-width: 400px) {
        .al-col-m {
            flex: 0 0 auto;
            width: 100%;
        }
    }

    /* .details-section-dark.dt-details.border-start-3.border-start-primary.p-2 {
        padding: 0px !important;
    } */
    .form-group.touchspin-parent-modal button {
        padding: 25%;
    }
</style>
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
                        <h2 class="content-header-title float-start mb-0">
                            {{ __('ib-management.IB Commission Structure') }}
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('ib-management.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('ib-management.Ib-Management') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('ib-management.IB Commission Structure') }}
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
            <form action="{{ route('admin.ib-commission-structure') }}" method="post" id="ib-commission-form">
                @csrf
                <input type="hidden" name="id" value="" id="structure-id">
                <input type="hidden" name="op" value="add" id="structure-op">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="form-group" style="width: 250px;" data-bs-toggle="tooltip" data-bs-placement="top" title="Select Trader Group">
                                    <label for="min-withdraw" class="">Client Groups</label>
                                    <select class="select2 form-select" id="client_group" name="client_group">
                                        @foreach ($groups as $value)
                                        <option value="{{ $value->id }}">{{ $value->group_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if (Auth::user()->hasDirectPermission('create ib commission structure'))
                                <div class="btn-group mt-1">
                                    <button type="button" class="btn btn-danger" id="btn-delete-currency" title="Delete only for selected groups">Delete All</button>
                                    @if ($ib_level == 0)
                                    <div class="waves-effect" title="" disabled data-bs-trigger="click" data-bs-toggle="tooltip" data-bs-original-title="First Setup IB">
                                        <button class="btn btn-success waves-effect" disabled type="button">
                                            <i data-feather='upload'></i>
                                            {{ __('ib-management.import_csv') }}
                                        </button>
                                    </div>
                                    <div class="waves-effect" title="" disabled data-bs-trigger="click" data-bs-toggle="tooltip" data-bs-original-title="First Setup IB">
                                        <button class="btn btn-primary waves-effect" disabled type="button"><i data-feather='plus'></i>
                                            {{ __('ib-management.Add') }}
                                        </button>
                                    </div>
                                    @else
                                    <button class="btn btn-primary btn-success" type="button" id="add_ib_svg"><i data-feather='upload'></i>
                                        {{ __('ib-management.import_csv') }}
                                    </button>

                                    <button class="btn btn-primary" type="button" data-ib_level="{{ $ib_level }}" id="add-ib-commission-structure"><i data-feather='plus'></i>
                                        {{ __('ib-management.Add') }}
                                    </button>
                                    @endif

                                </div>
                                @else
                                <div class="btn-groups">
                                </div>
                                @endif
                            </div>
                            <hr>
                            <div class="card-body">
                                @if ($ib_level == 0)
                                <div class="alert alert-warning border-start-info border-end-info" role="alert">
                                    <h4 class="alert-heading"> <i data-feather="info" class="me-50"></i> Warning
                                        : IB Setup not found !</h4>
                                    <div class="alert-body d-flex justify-content-between align-items-center">
                                        <p>Can't use IB Commission Structure without setup IB. Please setup an IB
                                            then try again.</p>
                                        <a href="{{ route('admin.ib-setup-view') }}" class="btn btn-warning waves-effect waves-float waves-light">{{ __('admin-menue-left.ib_setup') }}</a>
                                    </div>
                                </div>
                                @else
                                <div class="table-responsive">
                                    <table class="user-list-table table " id="ib-commission-structure">
                                        <thead class="table-light">
                                            <tr>

                                                <th>{{ __('ib-management.CURRENCY PAIR') }}</th>
                                                <th>{{ __('ib-management.TIMING') }}</th>
                                                <th>{{ __('ib-management.TOTAL') }}</th>
                                                <?php
                                                for ($i = 1; $i <= $ib_level; $i++) :
                                                ?>
                                                    <th>{{ __('ib-management.Level') }} {{ $i }}</th>
                                                <?php endfor; ?>
                                                <th>{{ __('ib-management.ACTIONS') }}</th>
                                            </tr>
                                            <tr id="ib-com-field" style="display:none">
                                                <th>
                                                    <div class="input-group mb-1 ib-com-cureency">
                                                        <span class="input-group-text" id="symbol-field"><i data-feather='dollar-sign'></i></span>
                                                        <input type="text" name="symbol" class="form-control" id="symbol" placeholder="Currency" aria-label="USD" aria-describedby="basic-addon1" />
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="input-group mb-1 ib-com-timing">
                                                        <span class="input-group-text" id="timing-field"><i data-feather='watch'></i></span>
                                                        <input type="text" name="timing" id="fp-time" class="form-control text-start" placeholder="ii:ss" id="timing" />
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="input-group mb-1">
                                                        <!-- <input type="hidden" class="total-hidden" name="total" value="0"> -->
                                                        <input type="text" name="total" class="touchspin-color ib-com-total" value="0" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" min="0" data-bts-decimals="2" data-bts-step="0.01" />
                                                    </div>
                                                </th>
                                                @for ($i = 1; $i <= $ib_level; $i++) <th>
                                                    <div class="input-group mb-1 touchspin-wrapper">
                                                        <input type="text" name="commission{{$i}}" class="touchspin-color ib-levels" value="0" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" data-bts-decimals="2" data-bts-step="0.01" min="0" />
                                                    </div>
                                                    </th>
                                                    @endfor
                                                    <th>
                                                        <div class="input-group mb-1 d-flex justify-content-between">
                                                            <!-- <a href="javascript:void(0)" id="btn-ib-com-save"><i data-feather='save' class="ib-com-save-icon"></i></a> -->
                                                            <button class="btn btn-flat-primary btn-structure-save" type="button" id="submit-request" data-label="Submit Request" data-form="ib-commission-form" data-el="fg" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="createCallBack" data-btnid="submit-request" data-i18n="Submit Request" onclick="_run(this)"><i data-feather='save' class="ib-com-save-icon"></i></button>
                                                            <a href="#" class="btn-delete-form-field"><i data-feather='delete' class="ib-com-x-icon"></i></a>
                                                        </div>
                                                    </th>
                                            </tr>
                                        </thead>

                                        <tbody></tbody>
                                    </table>
                                </div>
                                @endif

                                <div class="row custom-options-checkable g-1 mt-1">
                                    <?php
                                    $i = 0;
                                    $checked = 'checked';
                                    $iconVal = 'eye';
                                    ?>
                                    @foreach ($IbGroup as $value)
                                    <div class="al-col-m col-6 col-sm-4 col-md-3 col-lg-2">
                                        <input class="custom-option-item-check" type="radio" name="customOptionsCheckableRadios" id="customOptionsCheckableRadios-{{ $i }}" {{ $checked }} value="{{ $value->id }}">
                                        <label class="custom-option-item p-1" for="customOptionsCheckableRadios-{{ $i }}">
                                            <span class="d-flex justify-content-between flex-wrap mb-50">
                                                <span class="fw-bolder">{{ $value->group_name }}</span>
                                                <span class="fw-bolder"><i data-feather="{{ $iconVal }}" class="me-50"></i></span>
                                            </span>
                                        </label>
                                    </div>
                                    <?php
                                    $i++;
                                    $checked = '';
                                    $iconVal = 'eye-off';
                                    ?>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Content-->
<!--start upload modal / CSV -->
<div class="modal fade text-start" id="modal-upload-csv" tabindex="-1" aria-labelledby="Upload CSV" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-header-update-admin">Import IB Commission Structure from CSV</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="content-header row px-5">
                    <div class="content-header-left col-md-9 col-12 mb-2">
                        <!-- left side of modal top -->
                    </div>
                    <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                        <div class="mb-1 breadcrumb-right">
                            <div class="dropdown">
                                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="grid"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <button class="dropdown-item btn w-100" id="btn-view-not-modal">
                                        <i class="me-1" data-feather="info"></i>
                                        <span class="align-middle">Note</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.ib.csv-import') }}" method="post" id="import-csv-form" class="form-block p-5 pt-0" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="al_select_group" name="client_group">
                    <input type="hidden" id="al_select_ibGroup" name="ib_group">

                    <div class="mb-1 row">
                        <div class="col-sm-12 col-md-12">
                            <label for="csv_file" class="col-sm-3 col-form-label">Select CSV File<span class="text-danger">&#9734;</span></label>
                            <input type="file" name="csv_file" id="csv_file" class="form-control " />
                        </div>
                    </div>
                    <!-- start custom commission structure for csv upload modal -->
                    @if (\App\Services\commission\IbCommissionService::remaining_setup())
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-sm table-flush-spacing table-md">
                                @php $system_level = \App\Services\IbService::system_ibCommission_level(); @endphp
                                @for($i = 1; $i < $system_level; $i++) <tr>
                                    @for($j = 0; $j < $system_level - $i; $j++) <td class="bg-transparent">
                                        <div class="form-group touchspin-parent-modal">
                                            <label for="level-col-{{$j+1}}">Level {{$j+1}}</label>
                                            <input type="text" name="level_commission_{{$i}}[]" class="touchspin-color ib-levels" value="0" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" data-bts-decimals="2" data-bts-step="0.01" min="0" />
                                        </div>
                                        </td>
                                        @endfor
                                        </tr>
                                        @endfor
                            </table>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer px-5">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <button type="button" class="btn btn-danger float-end" data-bs-dismiss="modal">{{ __('page.close') }}</button>
                        <button type="button" class="btn btn-success text-center float-end me-1" id="btn-import-csv" data-file='true' onclick="_run(this)" data-el="fg" data-form="import-csv-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="csv_import_call_back" data-btnid="btn-import-csv" style="width:200px"> <i data-feather='upload'></i> {{ __('ib-management.import') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end upload modal -->
<div class="modal fade text-start" id="modal-viw-demo-csv" tabindex="-1" aria-labelledby="View demo CSV" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent px-1">
            <div class="modal-body bg-black bg-bitbucket p-5 rounded-3 pb-2 ">
                <p class="text-white-50">Your CSV file formate should be look like this image.</p>
                <img src="{{asset('admin-assets/images/csv-demo-3.PNG')}}" class="w-100" alt="CSV Demo">
                <button type="button" class="btn btn-secondary float-end mt-2" data-bs-dismiss="modal">Hide demo</button>
            </div>
        </div>
    </div>
</div>
<!-- ending update modal -->
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
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>

<!-- picker js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<!-- number input -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{asset('common-js/rz-plugins/z-datatable.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/ib-commission-structure.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script>
    // save ib commission structure--------------------------------
    function createCallBack(data) {
        if (data.status == true) {
            notify('success', data.message, data.message_title);
            if ($("#structure-op").val() === 'edit') {
                $("#structure-op").val('add');
                $("#ib-com-field").slideUp();
            }
            $('#ib-commission-structure').DataTable().draw();
            empty_input();
        } else {
            notify('error', data.message, data.message_title);
        }
        $.validator("ib-commission-form", data.errors);
    }

    // csv upload js 
    $(document).on('click', '#add_ib_svg', function() {
        var traderGroupid = $('#client_group').val();
        var ibGroupid = $('[name=customOptionsCheckableRadios]:checked').val();
        $('#al_select_group').val(traderGroupid);
        $('#al_select_ibGroup').val(ibGroupid);
        $("#modal-upload-csv").modal("show");
    });
    // csv import-------------------------
    // disable submit button
    $(document).on('click', '#btn-import-csv', function() {
        $(this).prop('disabled', true);
    });

    function csv_import_call_back(data) {
        if (data.status) {
            notify('success', data.message, 'Import CSV');
            $("#modal-upload-csv").modal("hide");
            $('#ib-commission-structure').DataTable().draw();
            $('#import-csv-form').trigger('reset');
        } else {
            notify('error', data.message, 'Import CSV');
            if (data.fromError) {
                $('#fromError').remove();
                $('#import-csv-form').append('<p class="error text-center" id="fromError">“' + data.message + '”</p>');
            } else {
                $('#fromError').remove();
            }
        }
        $('#btn-import-csv').prop('disabled', false);
        $.validator("import-csv-form", data.errors);

    }

    $(document).on('change', '#client_group, input[name="customOptionsCheckableRadios"]', function() {
        var traderGroup = $('#client_group').val();
        var ibGroup = $('[name=customOptionsCheckableRadios]:checked').val();
        $('#ib-commission-structure').DataTable().ajax.url(
            '/admin/ib-management/ib-commission-structure-dt?trader_group=' + traderGroup + '&ib_group=' +
            ibGroup).load();
    });
    $('#modal-upload-csv').on('hidden.bs.modal', function() {
        $('#import-csv-form').trigger('reset');
        $('#fromError').remove();
    });


    //  trader group change  function 

    $(document).on('change', '[name=customOptionsCheckableRadios]', function() {
        var allIcon = $('[name=customOptionsCheckableRadios]').parent('div').find('.feather');
        allIcon.replaceWith(feather.icons['eye-off'].toSvg());
        const powerOn = $(this).parent('div').find('.feather');
        powerOn.replaceWith(feather.icons['eye'].toSvg());
    });
    // custom level***********************************
    var previous_data;
    $(document).on('click', '.btn-edit-custom', function() {
        $(this).next('button').removeClass('d-none');
        $(this).addClass('d-none');
        let element = $("#ib-com-field").find(".touchspin-wrapper:first-child").clone();
        let touchspin = element.first().clone();
        let row = $(this).data('row');
        let total = $(this).data('total');
        // nth cild control
        let nth_child = '';
        for (let i = total; i >= (total - row); i--) {
            nth_child += ':nth-child(' + (i + 1) + ')';
            nth_child += (i != (total - row)) ? ',' : '';
        }
        let value_prev = new Array();
        previous_data = value_prev;
        for (let i = 1; i <= (total - row); i++) {
            value_prev[i] = parseInt($(this).closest('tr').find('td:nth-child(' + i + ')').text());
        }

        // render html
        $(this).closest('tr').find('td').not(nth_child).html(touchspin);
        for (let i = 1; i <= value_prev.length; i++) {
            $(this).closest('tr').find('td:nth-child(' + i + ')').find('input').val(value_prev[i]);
        }
    });
    // save custom in datatabse
    $(document).on("click", ".btn-save-custom", function() {
        let data = new Array();
        $.each($(this).closest('tr').find('input'), function(index, object) {
            data[index] = $(object).val();
        });
        $.ajax({
            url: '/admin/ib-management/custom-commission',
            method: 'post',
            dataType: 'json',
            data: {
                commission: data,
                _token: "{{ csrf_token() }}",
                commission_id: $(this).data('commission_id'),
                id: $(this).data('id'),
            },
            success: function(data) {
                if (data.status === true) {
                    notify('success', data.message, 'Custom Commission');
                } else {
                    notify('error', data.message, 'Custom Commission');
                }
            }
        })
    });
    // delete operations
    // ------------------------------------------------------------------
    $(document).on('click', '#btn-delete-currency', function() {
        $(this).confirm2({
            request_url: '/admin/ib-management/ib-commission-structure/delete',
            data: {
                client_group: $('#client_group').val(),
                ib_group: $('input[name="customOptionsCheckableRadios"]:checked').val()
            },
            click: false,
            title: 'Delete all currency',
            message: 'Are you confirm to delete all currency from selected groups? Press OK to Confirm',
            button_text: 'Delete',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Delete all currency');
            } else {
                notify('error', data.message, 'Delete all currency');
            }
            $('#ib-commission-structure').DataTable().draw();
        });
    });
    // upload csv and its note
    // ------------------------------------------------------------------
    $(document).on('click', '#btn-view-not-modal', function() {
        $("#modal-viw-demo-csv").modal('show');
    })
</script>
@stop
<!-- BEGIN: page JS -->