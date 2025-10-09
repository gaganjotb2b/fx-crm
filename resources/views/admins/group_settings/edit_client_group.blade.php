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
                        <h2 class="content-header-title float-start mb-0">Group Manager</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Edit Client Group
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
                {{-- important note section --}}
                <div class="col-md-12 col-lg-5 col-xl-5">
                    <div class="card">
                        <div class="card-header pb-1">
                            <h4 class="card-title">Note</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                Important notes please read.
                            </p>
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-primary">
                                    Put raw group same as you have on your trading server.
                                </li>
                                <li class="list-group-item list-group-item-secondary">
                                    You can click the call group button to get your groups from the trading server.
                                </li>
                                <li class="list-group-item list-group-item-danger">
                                    You can active inactive group from the group management section.
                                </li>
                                <li class="list-group-item list-group-item-info">
                                    Put account type that you want to see on CRM adding groups won't make this visible
                                    on CRM you have to activate them from the group management section.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- {{-- creat form section --}}
                <div class="col-md-12 col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Vertical Form</h4>
                        </div>
                        @if(Auth::user()->hasDirectPermission('edit group manager'))
                        <div class="card-body">
                            <form class="form form-vertical" id="edit-client-group-form">
                                @csrf
                                <input type="hidden" name="id" id="client-group-id" value="{{ $clientGroup->id }}">
                                <div class="row">
                                    <div class="col-12 mb-1">
                                        <div class="form-element other-selector">
                                            <label class="form-label" for="server">Trading Platform</label>
                                            <select class="select2 form-select" name="server" id="server">
                                                <optgroup>
                                                    <option value="">Select A Trading Platform</option>
                                                    <option value="mt4" {{ $clientGroup->server == 'mt4' ? 'selected' : '' }}>MT4</option>
                                                    <option value="mt5" {{ $clientGroup->server == 'mt5' ? 'selected' : '' }}>MT5</option>
                                                    <option value="vertex" {{ $clientGroup->server == 'vertex' ? 'selected' : '' }}>Vertex</option>
                                                    <option value="UTIP" {{ $clientGroup->server == 'UTIP' ? 'selected' : '' }}>UTIP</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element other-selector">
                                            <label class="form-label" for="book">Book</label>
                                            <select class="select2 form-select" name="book" id="book">
                                                <optgroup>
                                                    <option value="">Select A Book</option>
                                                    <option value="A Book" {{ $clientGroup->book == 'A Book' ? 'selected' : '' }}>A Book</option>
                                                    <option value="B Book" {{ $clientGroup->book == 'B Book' ? 'selected' : '' }}>B Book</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element">
                                            <label class="form-label" for="raw-group-name">Raw Group Name</label>
                                            <input type="text" id="raw-group-name" class="form-control" name="group_name" value="{{ $clientGroup->group_name ?? '' }}" placeholder="" />
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element">
                                            <label class="form-label" for="group-display-name">Group Display
                                                Name</label>
                                            <input type="text" id="group-display-name" class="form-control" name="group_id" value="{{ $clientGroup->group_id ?? ''  }}" placeholder="" />
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element other-selector">
                                            <label class="form-label" for="account-type">Account Type</label>
                                            <select class="select2 form-select" name="account_category" id="account-type">
                                                <optgroup>
                                                    <option value="demo" {{ $clientGroup->account_category == 'demo' ? 'selected' : '' }}>Demo</option>
                                                    <option value="live" {{ $clientGroup->account_category == 'live' ? 'selected' : '' }}>Live</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element">
                                            <label for="leverage">Leverage</label>
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
                                            <label class="form-label" for="min-deposit">Minimum Deposit</label>
                                            <input type="text" id="min-deposit" class="form-control" name="min_deposit" value="{{ $clientGroup->min_deposit ?? '' }}" placeholder="0" />
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element other-selector">
                                            <label class="form-label" for="deposit-type">Deposit Type</label>
                                            <select class="select2 form-select" name="deposit_type" id="deposit-type">
                                                <optgroup>
                                                    <option value="">Select Deposit Type</option>
                                                    <option value="one time" {{ $clientGroup->deposit_type == 'one time' ? 'selected' : '' }}>One Time</option>
                                                    <option value="every time" {{ $clientGroup->deposit_type == 'every time' ? 'selected' : '' }}>Every Time</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <div class="form-element other-selector">
                                            <label class="form-label" for="visibility">Visibility</label>
                                            <select class="select2 form-select" name="visibility" id="visibility">
                                                <optgroup>
                                                    <option value="visible" {{ $clientGroup->visibility == 'visible' ? 'selected' : '' }}>Visible</option>
                                                    <option value="hidden" {{ $clientGroup->visibility == 'visible' ? 'selected' : '' }}>Hidden</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary me-1">Submit</button>
                                        {{-- <button type="reset" class="btn btn-outline-secondary">Reset</button> --}}
                                    </div>
                                </div>
                            </form>
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
            </div> -->
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
<!-- select2 css properties start -->
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
            $("#leverage > option").prop("selected", "selected");
            $("#leverage").trigger("change");
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
<!-- select2 css properties end -->
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#edit-client-group-form').on('submit', function(event) {
        event.preventDefault();
        const data = $(this).serialize();
        const id = $('#client-group-id').val();
        $.ajax({
            type: "PUT",
            url: `{{ url('admin/client-groups') }}/${id}`,
            data: data,
            dataType: "JSON",
            success: function(data) {
                $('#edit-client-group-form .error').text('');
                console.log(data)
                if (data.status == "success") {
                    notify('success', data.msg, 'Client groups');
                } else if (data.status == "failed") {
                    notify('error', data.msg, 'Client groups');
                }
            },
            error: function(data) {
                notify('error', 'Fix the following errors!', 'Client groups')
                $('#edit-client-group-form .error').text('');
                $.each(data.responseJSON.errors, function(field_name, error) {
                    $('#edit-client-group-form').find('[name=' + field_name + ']')
                        .closest('.form-element').after(
                            `<span class="error text-danger">${error}</span>`);
                })
            }
        });
    })
</script>
@stop
<!-- END: page JS -->