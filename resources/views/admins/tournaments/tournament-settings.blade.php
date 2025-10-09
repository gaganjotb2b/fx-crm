@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Tournament Settings')
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
                        <h2 class="content-header-title float-start mb-0">Tournament Settings</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('admin-management.home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Tournament</a>
                                </li>
                                <li class="breadcrumb-item active">Tournament Settings
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
            <!-- Note cards -->
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4><b>{{__('page.note')}}</b><br></h4>
                            <p>{{__('page.i_note')}}</p>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                <p><strong>Profit Share:</strong> This is the portion of the participant's profit that will be shared with the admin. <em>Add Margin</em> adds an extra percentage for the admin.</p>
                            </div>
                            <div class="border-start-3 border-start-success p-1 mb-1 bg-light-info">
                                <p><strong>Participant Limit:</strong> Set how many users can join the Trading Tournament. Leave blank for unlimited entries.</p>
                            </div>
                            <div class="border-start-3 border-start-info p-1 mb-1 bg-light-info">
                                <p><strong>Minimum Deposit (Global Tournament):</strong> Leave blank if you don’t want to require a minimum deposit to join.</p>
                            </div>
                            <div class="border-start-3 border-start-danger p-1 mb-1 bg-light-info">
                                <p><strong>Minimum Deposit (Registration):</strong> Leave blank if no deposit is needed. Use the <em>Balance</em> field to require minimum wallet funds, or the <em>Account Balance</em> field for minimum trading account balance.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xl-8 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4><b>Tournament Settings</b></h4>
                        </div>
                        <hr>
                        <div class="card-body">
                            <form action="{{ route('admin.tournament.setting-action') }}" method="post" id="tournamentsettingsform">
                                @csrf
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="name-icon">Tournament Name<span class="text-danger">&#9734;</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="award"></i></span>
                                                <input type="text" id="tournament_name" class="form-control" name="tournament_name" value="{{$tourSetting->tour_name??''}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="name-icon">Organization Name<span class="text-danger">&#9734;</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="award"></i></span>
                                                <input type="text" id="organization_name" class="form-control" name="organization_name" value="{{$tourSetting->organization_name??''}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="name-icon">Minimum Deposit<span class="text-danger">&#9734;</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="award"></i></span>
                                                <input type="number" id="min_deposit" class="form-control" name="min_deposit" value="{{$tourSetting->min_deposit??''}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="name-icon">Special Group Only<span class="text-danger">&#9734;</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="select2 form-select" name="client_group">
                                                <option value="">{{__('page.all')}}</option>
                                                @foreach($clientGroup as $value)
                                                <option value="{{$value->id}}" {{($value->id == ($tourSetting->client_group_id ?? null))?"selected":""}}>{{$value->group_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="group_trading_duration">Group Trading Duration(Days)<span class="text-danger">&#9734;</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="award"></i></span>
                                                <input type="number" id="group_trading_duration" class="form-control" name="group_trading_duration" value="{{$tourSetting->group_trading_duration??0}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    // Format the dates for flatpickr range input
                                    $start = (isset($tourSetting) && isset($tourSetting->start_date)) ? \Carbon\Carbon::parse($tourSetting->start_date)->format('Y-m-d') : '';
                                    $end = (isset($tourSetting) && isset($tourSetting->end_date)) ? \Carbon\Carbon::parse($tourSetting->end_date)->format('Y-m-d') : '';
                                    $rangeValue = ($start && $end) ? "$start to $end" : '';
                                @endphp
                                
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="dt_date">Tournament Duration <span class="text-danger">&#9734;</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                    </svg>
                                                </span>
                                                <input type="text" id="dt_date" class="form-control dt-date flatpickr-range dt-input w-75 flatpickr-input active"
                                                    placeholder="Start Date to End Date" name="dt_date" readonly="readonly"
                                                    value="{{ $rangeValue }}">
                                            </div>
                                
                                            <!-- Hidden fields to store actual values -->
                                            <input type="hidden" id="start_date" name="start_date" value="{{ $start }}">
                                            <input type="hidden" id="end_date" name="end_date" value="{{ $end }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="name-icon">First Prize<span class="text-danger">&#9734;</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="award"></i></span>
                                                <input type="text" id="first_prize" class="form-control" name="first_prize" value="{{$tourSetting->prize_1??0}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="name-icon">Second Prize<span class="text-danger">&#9734;</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="award"></i></span>
                                                <input type="text" id="second_prize" class="form-control" name="second_prize" value="{{$tourSetting->prize_2??0}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="name-icon">Third Prize<span class="text-danger">&#9734;</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="award"></i></span>
                                                <input type="text" id="third_prize" class="form-control" name="third_prize" value="{{$tourSetting->prize_3??0}}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="name-icon">Fourth Prize<span class="text-danger">&#9734;</span></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="award"></i></span>
                                                <input type="text" id="fourth_prize" class="form-control" name="fourth_prize" value="{{$tourSetting->prize_4??0}}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-1 row mt-2 float-end">
                                    <div class="col-md-4 col-sm-4 col-4">
                                        <button class="btn btn-danger" type="button">RESET</button>
                                    </div>
                                    <div class="col-md-8 col-sm-8 col-8">
                                        <button type="button" class="btn btn-primary prop_disabled" id="settingsBtn1" data-btnid="settingsBtn1" data-loading="Processing..." data-form="tournamentsettingsform" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-validator="true" data-callback="tournamentSettingsCallBack" onclick="_run(this)" >Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Form cards -->
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


<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-number-input.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/create-manager.js')}}"> </script>
<script>

    // // Datepicker for advanced filter
    // // ---------------------------------------------------------------------------
    // var separator = ' - ',
    //     rangePickr = $('.flatpickr-range'),
    //     dateFormat = 'MM/DD/YYYY';
    // var options = {
    //     autoUpdateInput: false,
    //     autoApply: true,
    //     locale: {
    //         format: dateFormat,
    //         separator: separator
    //     },
    //     opens: $('html').attr('data-textdirection') === 'rtl' ? 'left' : 'right'
    // };
    flatpickr(".flatpickr-range", {
        mode: "range",
        dateFormat: "Y-m-d", // must match the format passed from Blade
        onChange: function(selectedDates, dateStr) {
            const [start, end] = dateStr.split(" to ");
            document.getElementById('start_date').value = start || '';
            document.getElementById('end_date').value = end || '';
        }
    });

    
    //Range Picker
    // ---------------------------------------------------------------------------------------------
    if (rangePickr.length) {
        rangePickr.flatpickr({
            mode: 'range',
            dateFormat: 'm/d/Y',
            onClose: function (selectedDates, dateStr, instance) {
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
    
    //tournamentSettingCallBack
    function tournamentSettingsCallBack(data) {
        $('#settingsBtn1').prop('disabled', false);
        if (data.success) {
            toastr['success'](data.message, 'Tournament', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
        } else {
            notify('error', data.message);
            $.validator("tournamentsettingsform", data.errors);
        }
    }
</script>
@stop
<!-- BEGIN: page JS -->