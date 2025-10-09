@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Group Manager')
@section('vendor-css')
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
@stop
@section('page-css')
<!-- select2 css properties start -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/multiselect-leverage.css') }}">
<!-- select2 css properties end -->
@stop
<!-- BEGIN: content -->
@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('group-setting.Group Manager') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('group-setting.Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('admin-menue-left.group_settins') }}
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
            <div class="row">
                {{-- important note section --}}
                <div class="col-md-12 col-lg-5 col-xl-5">
                    <div class="card">
                        <div class="card-header pb-1">
                            <h4 class="card-title">{{ __('group-setting.Note') }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                {{ __('group-setting.Important notes please read') }}
                            </p>
                            <ul class="list-group">
                                <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                    {{ __('group-setting.Put raw group same as you have on your trading server') }}
                                </div>
                                <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                    {{ __('group-setting.You can click the call group button to get your groups from the trading server') }}
                                </div>
                                <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                    {{ __('group-setting.You can active inactive group from the group management section') }}
                                </div>
                                <div class="border-start-3 border-start-primary p-1 mb-1 bg-light-info">
                                    {{ __('group-setting.Put account type that you want to see on CRM adding groups won\'t make this visible on CRM you have to activate them from the group management section') }}
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- creat form section --}}
                @if (Auth::user()->hasDirectPermission('create group manager'))
                <div class="col-md-12 col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Create A Client Group</h4>
                        </div>
                        <div class="card-body">
                            <form class="form form-vertical" action="{{ url('admin/client-groups') }}" method="POST" id="create-client-group-form">
                                @csrf
                                <div class="row">
                                    {{-- <div class="col-12 mb-1">
                                        <div class="form-element other-selector">
                                            <label class="form-label" for="platform">{{ __('group-setting.Trading Platform') }}</label>
                                            <select class="select2 form-select" name="platform" id="platform">
                                                <optgroup>
                                                    <option value="" selected>{{ __('group-setting.Select A Trading Platform') }}</option>
                                                    <option value="mt4">MT4</option>
                                                    <option value="mt5">MT5</option>
                                                    <option value="UTIP">UTIP</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- single or multiple platform handle from the component --}}
                                    {{-- check condition single platform true or false --}}
                                    {{-- if platform is single then platform field will be hidden and not otherwise --}}
                                    <x-platform-option account-type="live" use-for="admin_portal_client_group"></x-platform-option>

                                    <div class="col-12 mb-1">
                                        <div class="form-element other-selector">
                                            <label class="form-label" for="book">{{ __('group-setting.Book') }}</label>
                                            <select class="select2 form-select" name="book" id="book">
                                                <optgroup>
                                                    <option value="" selected>
                                                        {{ __('group-setting.Select A Book') }}
                                                    </option>
                                                    <option value="A Book">A {{ __('group-setting.Book') }}
                                                    </option>
                                                    <option value="B Book">B {{ __('group-setting.Book') }}
                                                    </option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element">
                                            <label class="form-label" for="raw-group-name">{{ __('group-setting.Raw Group Name') }}</label>
                                            <input type="text" id="raw-group-name" class="form-control" name="group_name" placeholder="" />
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element">
                                            <label class="form-label" for="group-display-name">{{ __('group-setting.Group Display Name') }}</label>
                                            <input type="text" id="group-display-name" class="form-control" name="group_id" placeholder="" />
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element other-selector">
                                            <label class="form-label" for="account-type">{{ __('group-setting.Account Type') }}</label>
                                            <select class="select2 form-select" name="account_category" id="account-type">
                                                <optgroup>
                                                    <option value="demo">{{ __('group-setting.Demo') }}</option>
                                                    <option value="live">{{ __('group-setting.Live') }}</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element other-selector">
                                            <label class="form-label" for="leverage">Leverage</label>
                                            <select class="js-select2" multiple="multiple" name="leverage[]" id="leverage">
                                                <option value="&nbsp;" data-badge="">Select All</option>
                                                @foreach($leverages as $leverage)
                                                <option value="{{$leverage->leverage}}" data-badge="">1 : {{$leverage->leverage}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element">
                                            <label class="form-label" for="min-deposit">{{ __('group-setting.Minimum Deposit') }}</label>
                                            <input type="text" id="min-deposit" class="form-control" name="min_deposit" placeholder="0" />
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element other-selector">
                                            <label class="form-label" for="deposit-type">{{ __('group-setting.Deposit Type') }}</label>
                                            <select class="select2 form-select" name="deposit_type" id="deposit-type">
                                                <optgroup>
                                                    <option value="" selected>
                                                        {{ __('group-setting.Select Deposit Type') }}
                                                    </option>
                                                    <option value="one time">{{ __('group-setting.One Time') }}
                                                    </option>
                                                    <option value="every time">
                                                        {{ __('group-setting.Every Time') }}
                                                    </option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element other-selector">
                                            <label class="form-label" for="visibility">{{ __('group-setting.Visibility') }}</label>
                                            <select class="select2 form-select" name="visibility" id="visibility">
                                                <optgroup>
                                                    <option value="visible">{{ __('group-setting.Visible') }}
                                                    </option>
                                                    <option value="hidden">{{ __('group-setting.Hidden') }}
                                                    </option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="loader" data-loader="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>"></div>
                                    <div class="col-12">
                                        <!-- <button type="button" class="btn btn-primary" id="create_group_btn" style="float: right; width:180px;" onclick="_run(this)" data-el="fg" data-form="create-client-group-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="create_client_call_back" data-btnid="create_group_btn">{{ __('group-setting.Submit') }}</button> -->
                                        <button type="button" class="btn btn-primary" id="create_group_btn" style="float: right; width:180px;" data-form="create-client-group-form" data-loader="loader">Create</button>
                                        {{-- <button type="reset" class="btn btn-outline-secondary">Reset</button> --}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-md-12 col-lg-7">
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
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script src="{{asset('common-js/rz-plugins/rz-ajax.js')}}"></script>
<!-- select2 js properties start -->
<script>
    $(".js-select2").select2({
        closeOnSelect: false,
        placeholder: "Select Leverage",
        allowHtml: true,
        allowClear: true,
        tags: true
    });

    $('.icons_select2').select2({
        width: "100%",
        templateSelection: iformat,
        templateResult: iformat,
        allowHtml: true,
        placeholder: "Select Leverage",
        dropdownParent: $('.select-icon'),
        allowClear: true,
        multiple: false
    });


    function iformat(icon, badge, ) {
        var originalOption = icon.element;
        var originalOptionBadge = $(originalOption).data('badge');

        return $('<span><i class="fa ' + $(originalOption).data('icon') + '"></i> ' + icon.text + '<span class="badge">' + originalOptionBadge + '</span></span>');
    }

    $('#leverage').on("select2:select", function(e) {
        if (e.params.data.text == "Select All") {
            // Loop through the options starting from the second one
            // $("#leverage > option").prop("selected", true);
            $("#leverage > option[value!='']").prop("selected", "selected");
            // Deselect the first option
            $("#leverage > option:first").prop("selected", false);
            $("#leverage").trigger("change");
            // Initialize the Select2 instance with updated options
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select Leverage",
                allowHtml: true,
                allowClear: true,
                tags: true
            });
        }
    });
</script>
<!-- select2 js properties end -->
<script>
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });
    // $('#create-client-group-form').on('submit', function(event) {
    //     event.preventDefault();
    //     const data = $(this).serialize();
    //     $.ajax({
    //         type: "POST",
    //         url: "{{ url('admin/client-groups') }}",
    //         data: data,
    //         dataType: "JSON",
    //         success: function(data) {
    //             $('#create-client-group-form .error').text('');
    //             console.log(data)
    //             if (data.status == "success") {
    //                 notify('success', data.msg, 'Client groups');
    //                 $('#create-client-group-form')[0].reset();
    //             } else if (data.status == "failed") {
    //                 notify('error', data.msg, 'Client groups');
    //             }
    //         },
    //         error: function(data) {
    //             notify('error', 'Fix the following errors!', 'Client groups');
    //             $('#create-client-group-form .error').text('');
    //             $.each(data.responseJSON.errors, function(field_name, error) {
    //                 $('#create-client-group-form').find('[name=' + field_name + ']').closest('.form-element').after(`<span class="error text-danger">${error}</span>`);
    //             })
    //         }
    //     });
    // })

    // function create_client_call_back(data) {
    //     if (data.status == true) {
    //         toastr['success'](data.message, 'Add Client', {
    //             showMethod: 'slideDown',
    //             hideMethod: 'slideUp',
    //             closeButton: true,
    //             tapToDismiss: false,
    //             progressBar: true,
    //             timeOut: 2000,
    //         });
    //         $.validator("create-client-group-form", data.errors);
    //         $("#create-client-group-form").trigger('reset');
    //     } else {
    //         $.validator("create-client-group-form", data.errors);
    //     }
    // }


    $("#create_group_btn").form_submit({
        form_id: "create-client-group-form",
        title: 'Client Group',

    }, function(data) {
        console.log(data);
    })
</script>
@stop
<!-- END: page JS -->