@php use App\Services\CombinedService; @endphp
@extends(App\Services\systems\VersionControllService::get_ib_layout())
@section('title', 'Withdrawal Report')

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <style>
        .min {
            padding: 0 !important;
            margin: 0 !important;
            border-top: 1px solid #d8d6de;
            border-right: none;
            border-bottom: 1px solid #d8d6de;
            border-left: 1px solid #d8d6de;
            text-align: center;
        }

        .dt-buttons {
            float: right;
        }

        td {
            text-align: left;
        }

        .badge {
            text-transform: capitalize !important;
        }

        td.details-control {
            background-image: url("{{ asset('datatable-icon/plus.png') }}");
            cursor: pointer;
            background-repeat: no-repeat;
            background-position: center;
        }

        tr.details td.details-control {

            background-image: url("{{ asset('datatable-icon/minus.png') }}");
            cursor: pointer;
            background-repeat: no-repeat;
            background-position: center;

        }
    </style>
@endsection
<!-- bread-crumb -->
@section('bread_crumb')
    {!! App\Services\systems\BreadCrumbService::get_ib_breadcrumb() !!}
@stop
<!-- main content -->
@section('content')
    <div class="container-fluid py-4">

        {{-- START: Header + Filter --}}
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card d-none" id="filter-form">
                    <!-- Card header -->
                    <div class="d-flex justify-content-between flex-row">
                        <!-- Card header -->
                        <div class="card-body">
                            <h5 class="mb-0">{{ __('page.filter') }} {{ __('page.reports') }}</h5>
                        </div>

                        <div class="p-4 border-bottom border-0">
                            <div class="btn-exports" style="width:160px">
                                <select data-placeholder="Select a state..." class="select2-icons form-select"
                                    id="fx-export">
                                    <option value="download" selected>Export to</option>
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="filterForm" class="dt_adv_search" method="POST">
                            <div class="row g-2">

                                <div class="col-lg-4 col-md-6 mb-3">
                                    <select class="form-select" name="status" id="status">
                                        <option value="" selected>{{ __('page.all') }}</option>
                                        <option value="A">{{ __('page.approved') }}</option>
                                        <option value="P">{{ __('page.pending') }}</option>
                                        <option value="D">{{ __('page.declined') }}</option>
                                    </select>
                                </div>


                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="">
                                        <input type="text" name="femail" class="form-control" id="femail"
                                            placeholder="{{ __('page.email') }}">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="">
                                        <input type="text" name="fname" class="form-control" id="fname"
                                            placeholder="{{ __('page.name') }}">
                                    </div>
                                </div>


                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="col-12">
                                        <div class="col-12 input-rang-group">
                                            <span class="col-2 input-rang-group-text rang-min">{{ __('page.MIN') }}</span>
                                            <input type="text" id="min" class="col-3 min" name="min">
                                            <span class="input-rang-group-text col-1">-</span>
                                            <input type="text" id="max" class="col-3 max" name="max">
                                            <span class="col-2 input-rang-group-text rang-max">{{ __('page.MAX') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="col-12">
                                        <div class="col-12 input-rang-group">
                                            <span class="col-1 input-rang-group-date-logo rang-min">
                                                <i class="ni ni-calendar-grid-58"></i>
                                            </span>
                                            <input type="text" id="from" class="col-4 min flatpickr-basic"
                                                name="from" placeholder="YY-MM-DD">
                                            <span class="input-rang-group-text col-1">-</span>
                                            <input type="text" id="to" class="col-4 max flatpickr-basic"
                                                name="to" placeholder="YY-MM-DD">
                                            <span class="col-1 input-rang-group-date-logo rang-max input-range-gpr-right">
                                                <i class="ni ni-calendar-grid-58"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!--<div class="col-lg-4 col-md-12"></div>-->

                                <div class="col-lg-2 col-md-3">
                                    <div class="col-md-12 text-right">
                                        <button id="resetBtn" type="button"
                                            class="btn btn-dark w-100 waves-effect waves-float waves-light"
                                            style="float: right;">
                                            <span class="align-middle">{{ __('page.RESET') }}</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3">
                                    <div class="col-md-12 text-right">
                                        <button id="filterBtn" type="button"
                                            class="btn btn-primary  w-100 waves-effect waves-float waves-light">
                                            <span class="align-middle">{{ __('page.FILTER') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        {{-- END: Header + Filter --}}



        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                         <button id="advance-filter-btn" type="button" class="btn bg-gradient-primary">
                            <span class="align-middle">Advance Filter</span>
                        </button>
                        <div class="table-responsive">
                            <table class="table table-flush datatables-ajax w-100" id="datatable-withdraw">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>{{ __('page.Name') }}</th>
                                        <th>{{ __('page.Email') }}</th>
                                        <th>{{ __('page.Amount') }}</th>
                                        <th>{{ __('page.method') }}</th>
                                        <th>{{ __('page.Status') }}</th>
                                        <th>{{ __('page.Date') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('corejs')
    <script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>



    <script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>

    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>

    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript"
        src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

    <script>
        const withdrawDT = $("#datatable-withdraw").DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "lengthChange": true,
            "buttons": true,
            "dom": 'B<"clear">lfrtip',
            ajax: {
                url: "{{ url('ib/reports/withdraw') }}?action=table",
                data: function(d) {
                    return $.extend({}, d, {
                        "status": $("#status").val(),
                        "femail": $("#femail").val(),
                        "fname": $("#fname").val(),
                        "min": $("#min").val(),
                        "max": $("#max").val(),
                        "from": $("#from").val(),
                        "to": $("#to").val(),
                    });

                }
            },
            columns: [{
                    "class": "details-control",
                    "orderable": false,
                    "data": null,
                    "defaultContent": ""
                },
                {
                    "data": "id",
                    "visible": false
                },
                {

                    "data": "name"
                },
                {

                    "data": "email"
                },
                {

                    "data": "amount"
                },
                {

                    "data": "transaction_type"
                },
                {

                    "data": "approved_status"
                },
                {

                    "data": "created_at"
                },
            ],
            buttons: [{
                    extend: 'csv',
                    text: 'csv',
                    className: 'btn btn-success btn-sm',
                    action: serverSideButtonAction
                },
                {
                    extend: 'copy',
                    text: 'copy',
                    className: 'btn btn-warning btn-sm',
                    action: serverSideButtonAction
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    className: 'btn btn-warning btn-sm',
                    action: serverSideButtonAction
                },
                {
                    extend: 'pdf',
                    text: 'pdf',
                    className: 'btn btn-danger btn-sm',
                    action: serverSideButtonAction
                }
            ],
            order: [
                [2, 'desc']
            ],
            oLanguage: {
                "sLengthMenu": "\_MENU_",
                "sSearch": ""
            },
            language: {
                paginate: {
                    previous: "<",
                    next: ">",
                },
            },
            drawCallback: function(settings) {
                $("#filterBtn").html("FILTER");
            }
        });

        // extra -----------------------------------------------------------
        // Array to track the ids of the details displayed rows
        var detailRows = [];

        $('#datatable-withdraw tbody').on('click', 'tr td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = withdrawDT.row(tr);
            var idx = $.inArray(tr.attr('id'), detailRows);

            if (row.child.isShown()) {
                tr.removeClass('details');
                row.child.hide();
                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            } else {
                tr.addClass('details');
                row.child(format(row.data())).show();
                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }
        });

        // On each draw, loop over the `detailRows` array and show any child rows
        withdrawDT.on('draw', function() {
            $.each(detailRows, function(i, id) {
                $('#' + id + ' td.details-control').trigger('click');
            });
        });
        // data table export function --------------------------------------
        $(document).on("change", "#fx-export", function() {
            if ($(this).val() === 'csv') {
                $(".buttons-csv").trigger('click');
            }
            if ($(this).val() === 'excel') {
                console.log($(this).val());

                $(".buttons-excel").trigger('click');
            }
        });

        // for showing datatable "extra" data
        function format(d) {
            return d.extra;
        }
        // -----------------------------------------------------------------

        // filter button click event for filtering in data table
        $('#filterBtn').click(function(e) {
            $(this).html(`<img src="{{ asset('trader-assets/assets/icon/puff.svg') }}" />`);
            withdrawDT.draw();
        });
        // click event for resetting filter form
        $('#resetBtn').click(function(e) {
            $('#filterForm')[0].reset();
            withdrawDT.draw();
        });
        
        
        $(document).on("click", "#advance-filter-btn", function() {
            $("#filter-form").toggleClass("d-none");
        });
        
        $(document).on('click', '.btn-decline', function () {
            const withdraw_id = $(this).data('id'); // Get the data-id attribute from the button
    
            // Show confirmation dialog using SweetAlert2
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to decline the withdraw request!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, decline it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make the AJAX call to delete the trader
                    $.ajax({
                        url: '/user/withdraw-decline/'+ withdraw_id, // The route to your delete function
                        type: 'GET', // HTTP method
                        success: function (response) {
                            if (response.status) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload(); // Reload the page or update the UI dynamically
                                });
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function (xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.',
                                'error'
                            );
                        },
                    });
                }
            });
        });
    </script>
@endsection
