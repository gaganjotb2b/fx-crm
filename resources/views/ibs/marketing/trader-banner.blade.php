@php use App\Services\CombinedService; @endphp
@extends((\App\Services\CombinedService::is_combined('client') == true && \App\Services\CombinedService::is_single_portal()==true)?'layouts.trader-layout':'layouts.ib-layout')
@section('title','Marketing')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<style>
    .img-thumbnail {
        padding: 0.25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0rem;
        max-width: 100%;
        height: 170px;
        display: flex;
    }

    .datatables-ajax tr,
    .datatables-ajax td {
        padding-left: 1.3rem !important;
    }

    .modal-content {
        position: relative;
        display: flex;
        width: auto;
        flex-direction: column;
        pointer-events: auto;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 0.75rem;
        outline: 0;
    }



    .dataTables_length .form-select {
        background-position: right 3px center;
        background-size: 12px 12px;
        padding-right: 1.25rem;
        margin-top: 3px;
    }

    #banner-report tr,
    #banner-report td:first-child {
        border-left: 3px solid var(--custom-primary);
    }

    #banner-report tr,
    #banner-report th:first-child {
        border-left: 3px solid;
    }

    #banner-report tr,
    #banner-report td {
        background-color: #f7fafc;
        vertical-align: middle;
    }

    #banner-report {
        border-collapse: separate !important;
        border-spacing: 2px 8px;
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

    button#banner-report-list-delete-button {
        margin-right: .5rem;
    }

    /* -------------------end date range style */
    /* datatable ajax style---------------------------------------------- */
    .datatables-ajax {
        border-collapse: separate !important;
        border-spacing: 0px 4px !important;
    }

    td.details-control {
        width: 10px !important;
    }

    /* #banner-report tr,
    #banner-report td:first-child {
        border-left: none;
    } */
    td.td-font.column-width {
        border-left: none !important;
        padding-left: 0px !important;
    }

    .column-width {
        width: 33.33% !important;
    }

    .form-control {
        padding: 0rem 0.75rem !important;
    }

    .table-striped>tbody>tr:nth-of-type(odd)>* {
        --bs-table-accent-bg: none !important;
        color: var(--bs-table-striped-color);
    }

    .form-control {
        border-radius: 0rem;
        border-top-left-radius: 0rem !important;
        border-bottom-left-radius: 0rem !important;
        border-left-width: 1px;
    }

    .btn-sm,
    .btn-group-sm>.btn {
        padding: 0.5rem 2rem;
        font-size: 0.75rem;
        border-radius: 0rem;
    }

    tr.odd.details.dt-hasChild>td {
        background-color: var(--custom-primary) !important;
    }

    tr.even.details.dt-hasChild>td {
        background-color: var(--custom-primary) !important;
    }

    /* datatable search hidden property */
    input.form-control.form-control-sm {
        display: none !important;
    }

    select.form-select.form-select-sm {
        display: none !important;
    }

    .col-sm-12.col-md-6 {
        height: 0px !important;
    }

    .light-version .input-group-text {
        color: var(--font-color);
        background-color: rgb(247, 250, 252);
        margin-top: 0;
        line-height: 1.5;
        border: 1px solid var(--border-color) !important;
        color: #fff !important;
    }

    /* datatable search hidden property */
</style>
@endsection
@section('bread_crumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm">
            <a class="opacity-3 text-dark" href="javascript:;">
                <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>shop </title>
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-1716.000000, -439.000000)" fill="#252f40" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(0.000000, 148.000000)">
                                    <path d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                                    <path d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>
        </li>
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Marketing</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Trader Banner</li>
    </ol>
    <h6 class="font-weight-bolder mb-0">{{ __('page.IB Area') }}</h6>
</nav>
@stop
@section('content')
<div class="container-fluid py-4">
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card ">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="banner-report table table-flush datatables-ajax w-100" id="banner-report">
                            <thead class="thead-light">
                                <tr class="d-none">
                                    <th style="width: 30px !important;"></th>
                                    <th>{{ __('page.bank-name') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="img-modal" tabindex="-1" aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <!-- <p>Placeholder text for this demonstration of a vertically centered modal dialog.</p> -->
                <img class="modal-img" src="" alt="" width="600">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm m-0" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--End image popup-->
@endsection
@section('corejs')
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-picker-for-report-filter.js') }}"></script>
@endsection

@section('page-js')
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>

<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="{{asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js')}}"></script>
<script src="{{ asset('/common-js/copy-js.js') }}"></script>
@endsection
@section('customjs')
<script>
    $(document).on('click', '.img-ad-banner', function() {
        var $src = $(this).attr("src");
        console.log($src)
        $(".modal-img").attr("src", $src);
    });
    // refer link
    $(document).on("click", ".referral-link", function() {
        var copy_item = $(this).prev("#ib-referral-link").select();

        // copy section start 
        var TextToCopy = copy_item.val();
        var TempText = document.createElement("input");
        TempText.value = TextToCopy;
        document.body.appendChild(TempText);
        TempText.select();

        document.execCommand("copy");
        document.body.removeChild(TempText);
        notify('success', 'Copied Successfully, Please save it in your safe zone', 'Copy to Clipboard');
        // copy section end

    });
</script>

<script>
    function format(d) {
        return d.extra;
    }
    var dt = $('.banner-report').DataTable({
        language: {
            search: "",
            lengthMenu: " _MENU_ ",

            paginate: {
                // remove previous & next text from pagination
                previous: "<",
                next: ">",
            },
        },
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/ib/marketing/banner/table-description/trader",
            "data": function(d) {
                return $.extend({}, d, {});
            }
        },
        "columns": [{
                "class": "details-control",
                "orderable": false,
                "data": null,
                "defaultContent": ""
            },

            {
                "data": "size"
            },
        ],
        "drawCallback": function(settings) {
            $("#filterBtn").html("FILTER");
        },
        "order": [
            [1, 'desc']
        ]
    });
    // Array to track the ids of the details displayed rows
    var detailRows = [];

    $('.banner-report tbody').on('click', 'tr td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = dt.row(tr);
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
    dt.on('draw', function() {
        $.each(detailRows, function(i, id) {
            $('#' + id + ' td.details-control').trigger('click');
        });
    });
    // bank account list fetch data end
</script>
@endsection