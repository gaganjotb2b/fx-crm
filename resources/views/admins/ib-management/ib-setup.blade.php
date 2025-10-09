@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','IB Setup')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
<!-- number input -->
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
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
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{__('ib-management.Ib-Setup')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('ib-management.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{__('ib-management.Ib-Management')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('ib-management.Ib-Setup')}}
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
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4> {{__('ib-management.Note')}}</h4>
                                <code class="bg">{{__('ib-management.please read carefully')}}</code>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="border-start-3 border-start-primary p-1 bg-light-primary mb-1">
                                {{__('ib-management.This is a one-time setup')}}
                            </div>
                            <div class="border-start-3 border-start-info p-1 bg-light-primary mb-1">
                                {{__('ib-management.Level means your IB Level is applicable for all in structures')}}
                            </div>
                            <div class="border-start-3 border-start-danger p-1 bg-light-primary mb-1">
                                {{__('ib-management.The requirement for subib is if you want to restrict IB to have subib then you can give a limit to a certain amount of clients required to have subib referral link. If you don\'t want such a thing to leave it or place 0')}}
                            </div>
                            <div class="border-start-3 border-start-success p-1 bg-light-primary mb-1">
                                {{__('ib-management.Withdrawal periods are when an IB can make withdrawals')}}
                            </div>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->hasDirectPermission('create ib setup'))
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{__('ib-management.IB Program Setup')}}</h4>
                        </div>
                        <hr>
                        <div class="card-body">
                            <form action="{{route('admin.ib-setup')}}" method="post" id="ib-setup-form">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <!-- ib level -->
                                        <div class="mb-1 row">
                                            <label for="level" class="col-sm-3 col-form-label">{{__('ib-management.Level')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="ib_level" class="form-control" id="level" placeholder="0" value="{{$ib_level}}" min="0" />
                                            </div>
                                        </div>
                                        <!-- requirement for sub ib -->
                                        <div class="mb-1 row">
                                            <label for="sub-ib-requirement" class="col-sm-3 col-form-label">{{__('ib-management.Requirement for SUB-IB')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="require_sub_ib" class="form-control" id="sub-ib-requirement" placeholder="0" value="{{$sub_ib_req}}" min="0" />
                                            </div>
                                        </div>
                                        <!-- min withdraw -->
                                        <div class="mb-1 row">
                                            <label for="min-withdraw" class="col-sm-3 col-form-label">{{__('ib-management.Min Withdraw')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="min_withdraw" class="form-control" id="min-withdraw" placeholder="0" value="{{$min_withdraw}}" min="0" />
                                            </div>
                                        </div>
                                        <!-- max withdraw -->
                                        <div class="mb-1 row">
                                            <label for="max-withdraw" class="col-sm-3 col-form-label">{{__('ib-management.Max Withdraw')}}</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="max_withdraw" class="form-control" id="max-withdraw" placeholder="0" value="{{$max_withdraw}}" min="0" />
                                            </div>
                                        </div>
                                        <!-- withdraw period -->
                                        <div class="mb-2 row">
                                            <label for="withdraw-period" class="col-sm-3 col-form-label">{{__('ib-management.Withdraw Period')}}</label>
                                            <div class="col-sm-9">
                                                <!-- select withdraw period -->
                                                <select class="select2 form-select withdraw-period" name="withdraw_period" id="withdraw-period select_option_design">
                                                    <option value="monthly" {{$withdraw_period['monthly']}}>{{__('ib-management.Monthly')}}</option>
                                                    <option value="by-weekly" {{$withdraw_period['by-weekly']}}>{{__('ib-management.By-Weekly')}}</option>
                                                    <option value="weekly" {{$withdraw_period['weekly']}}>{{__('ib-management.weekly')}}</option>
                                                    <option value="daily" {{$withdraw_period['daily']}}>{{__('ib-management.Daily')}}</option>
                                                </select>
                                                <!-- update code -->
                                                <!-- period days -->
                                                <div class="mt-2" style="display: none;" id="period-days-container">
                                                    <select class="select2 form-select withdraw-period-days mt-2" name="period_days" id="withdraw-period-days">
                                                        @for($i=0; $i < 7; $i++) <!-- aoption for all days of current week -->
                                                            @php $selected = '' @endphp
                                                            @if(\Carbon\Carbon::now()->addDays($i)->format('l') === \App\Services\IBManagementService::get_period('weekly'))
                                                            @php $selected = 'selected' @endphp
                                                            @endif
                                                            <option value="{{\Carbon\Carbon::now()->addDays($i)->format('l')}}" {{$selected}}>{{\Carbon\Carbon::now()->addDays($i)->format('l')}}</option>
                                                            @endfor
                                                    </select>
                                                </div>
                                                <!-- period date -->
                                                <div class="mt-2" style="display: none;" id="period-date-container">
                                                    <select class="select2 form-select withdraw-period-date" name="period_date" id="withdraw-period-date">
                                                        @for($i=1; $i <= date('t'); $i++) <!-- option for all date of current month -->
                                                            @php $selected = '' @endphp
                                                            @if(str_pad($i, 2, '0', STR_PAD_LEFT) === \App\Services\IBManagementService::get_period('monthly'))
                                                            @php $selected = 'selected' @endphp
                                                            @endif
                                                            <option value="{{str_pad($i, 2, '0', STR_PAD_LEFT)}}" {{$selected}}>{{date('F') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT) . "-" .date('Y') }}</option>
                                                            @endfor
                                                    </select>
                                                </div>
                                                <!-- period by weekly -->
                                                <div class="mt-2" style="display: none;" id="byweekly-date-container">
                                                    <select class="select2 form-select byweekly-period-date" name="by_weekly_period_date" id="byweekly-period-date">
                                                        @for($i=1; $i <= (int)(date('t')/2); $i++) <!-- option for all date of current month -->
                                                            @php $selected = '' @endphp
                                                            @if(str_pad($i, 2, '0', STR_PAD_LEFT) === \App\Services\IBManagementService::get_period('by-weekly'))
                                                            @php $selected = 'selected' @endphp
                                                            @endif
                                                            <option value="{{str_pad($i, 2, '0', STR_PAD_LEFT)}}" {{$selected}}>{{date('F') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT) . "-" .date('Y') }}</option>
                                                            @endfor
                                                    </select>
                                                </div>
                                                <!-- update code end -->
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="mb-2 row">
                                            <div class="col-lg-4">
                                                <div class="form-check form-check-primary">
                                                    <input type="checkbox" name="withdraw_kyc" class="form-check-input" id="kyc-withdraw" {{$withdraw_kyc}} value="1" />
                                                    <label class="form-check-label" for="kyc-withdraw">{{__('ib-management.KYC Required for Withdraw')}}</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-check form-check-primary">
                                                    <input type="checkbox" name="refer_kyc" class="form-check-input" id="kyc-referal-link" {{$refer_kyc}} value="1" />
                                                    <label class="form-check-label" for="kyc-referal-link">{{__('ib-management.KYC Required for Referral Link')}}</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-check form-check-primary">
                                                    <input type="checkbox" name="ib_commission_kyc" class="form-check-input" id="kyc-ib-commission" {{$ib_commission_kyc}} value="1" />
                                                    <label class="form-check-label" for="kyc-ib-commission">{{__('ib-management.KYC Required for IB Commission')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2 row mt-3">
                                            <div>
                                                <button type="button" class="btn btn-primary  float-end" id="btn-save" onclick="_run(this)" data-el="fg" data-form="ib-setup-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="ib_setup_call_back" data-btnid="btn-save" style="width:180px">{{__('ib-management.Save IB Config')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            @include('errors.permission')
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/editors/quill/quill.min.js')}}"></script>
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- number input -->
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/ib-setup.js')}}"> </script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js')}}"></script>
<script>
    // ib setup form call back
    // --------------------------------------------------------------
    function ib_setup_call_back(data) {
        if (data.status == true) {
            toastr['success'](data.message, 'IB Setup', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
            $.validator("ib-setup-form", data.errors);
        } else {
            $.validator("ib-setup-form", data.errors);
        }
    }
    // update code
    // withdraw period select box controll
    $(document).on("change", ".withdraw-period", function() {
        let period = $(this).val();
        get_period(period)
    });
    get_period($('.withdraw-period').val())

    function get_period(period) {
        switch (period.toLowerCase()) {
            case 'weekly':
                $("#period-days-container").slideDown();
                $("#period-date-container").slideUp();
                $("#byweekly-date-container").slideUp();
                break;
            case 'by-weekly':
                $("#byweekly-date-container").slideDown();
                $("#period-days-container").slideUp();
                $("#period-date-container").slideUp();
                break;
            case 'monthly':
                $("#period-days-container").slideUp();
                $("#period-date-container").slideDown();
                $("#byweekly-date-container").slideUp();
                break;
            default:
                $("#period-days-container").slideUp();
                $("#period-date-container").slideUp();
                $("#byweekly-date-container").slideUp();
                break;
        }
    }
    // update code
</script>
@stop
<!-- BEGIN: page JS -->