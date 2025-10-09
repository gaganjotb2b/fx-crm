@php use App\Services\CombinedService; @endphp
@extends(App\Services\systems\VersionControllService::get_ib_layout())

@section('title','My IB,s')
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />

<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<style>
    .table tbody tr:last-child td {
        border-bottom-width: 1px;
        border-left-width: 1px;
    }

    input:focus {
        outline: none !important;
        border: 1px solid #d8d6de;
    }

    #datatable-my-ib tr,
    #datatable-my-ib td:first-child {
        border-left: 3px solid var(--custom-primary);
    }

    #datatable-my-ib tr,
    #datatable-my-ib th:first-child {
        border-left: 3px solid;
    }

    #datatable-my-ib tr,
    #datatable-my-ib td {
        background-color: #f7fafc;
        vertical-align: middle;
    }

    #datatable-my-ib {
        border-collapse: separate !important;
        border-spacing: 2px 8px;
    }

    .dataTables_length {
        float: left;
    }

    div.dataTables_wrapper div.dataTables_length select {
        width: 160px;
        display: inline-block;
    }

    .dataTables_info {
        float: left;
        padding: 0;
        padding-top: 7px;
    }
    .form-control-lg{
        padding: 0.5rem 0.7rem;
    }
</style>
@endsection
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_ib_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4 page-my-ibclients">
    <!-- filter sections -->
    <div class="card">
        <div class="card-header">
            <h4>Filter Report</h4>
        </div>
        <div class="card-body">
            <form class="row" id="filter-form">
                <div class="col-md-3 mb-3">
                    <select class="form-control form-control-lg" name="subib">
                        <option value=" ">All</option>
                        <option value="mydir">My Direct IB</option>
                        <option value="mysub">My Sub IB</option>
                    </select>
                </div>
                <!-- Filter By Ib & Sub Ib Name/Email/Phone -->
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="ib_info" class="form-control" id="ib_info" placeholder="IB Email / Name / Phone">
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-secondary w-100 text-white" id="btn-reset">Reset</button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary w-100" id="btn-filter">Filter</button>
                </div>
            </form>
        </div>
    </div>
    {{-- START: Data table --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100 datatables-ajax" id="datatable-my-ib">
                            <thead>
                                <tr class="table-secondary">
                                    <th>{{ __('page.Level') }}</th>
                                    <th>{{ __('page.Name') }}</th>
                                    <th>{{ __('page.Email') }}</th>
                                    <th>{{ __('page.Country') }}</th>
                                    <th>{{ __('page.Phone') }}</th>
                                    <th>{{ __('page.Registration Date') }}</th>
                                    <th>{{ __('page.Affiliate By') }}</th>
                                    <th>{{ __('page.Total Trader') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Data table --}}
</div>
@endsection
@section('corejs')
<script type="text/javascript" src="{{ asset('trader-assets/assets/js/datatables.min.js') }}"></script>

@endsection
@section('customjs')
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>
<script>
    $(document).ready(function() {
        var trade_report = dt_fetch_data(
            '/ib/affiliates/my-ib',
            [{
                    "data": "level"
                },
                {
                    "data": "name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "country"
                },
                {
                    "data": "phone"
                },
                {
                    "data": "reg_date"
                },
                {
                    "data": "affiliate_by"
                },
                {
                    "data": "total_trader"
                },
            ],
            true, false, true, [0, 1, 2, 3, 4, 5, 6, 7], '', true, true
        )
    });
</script>
@endsection