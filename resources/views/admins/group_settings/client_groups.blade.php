@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title', 'Client Groups')
@section('vendor-css')

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<!-- BEGIN: content -->
@section('page-css')
<style>
    /* for Laptop */
    td,
    th {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(4),
        .ib-withdraw tbody tr td:nth-child(4) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }

    @media screen and (max-width: 1280px) and (min-width: 800px) {

        .ib-withdraw thead tr th:nth-child(2),
        .ib-withdraw tbody tr td:nth-child(2) {
            display: none;
        }

        .small-none-two {
            display: none;
        }
    }



    @media screen and (max-width: 1440px) and (min-width: 900px) {

        .ib-withdraw thead tr th:nth-child(5),
        .ib-withdraw tbody tr td:nth-child(5) {
            display: none;
        }

        .small-none {
            display: none;
        }
    }
</style>
@stop
@section('content')
<!-- BEGIN: Content-->
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{__('group-setting.Client Groups')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('group-setting.Home')}}</a>
                                </li>
                                <li class="breadcrumb-item active">{{__('admin-menue-left.group_settins')}}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown float-left">
                        <button class="btn-icon btn btn-primary btn-round btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#get-client-group" data-bs-placement="top" title="Get Client Groups">
                            <i data-feather="download"></i>
                        </button>

                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>
                        <div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="#"><i class="me-1" data-feather="info"></i>
                                <span class="align-middle">Note</span></a><a class="dropdown-item" href="#"><i class="me-1" data-feather="play"></i><span class="align-middle">Vedio</span></a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Basic table -->
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="datatables-basic ib-withdraw table" id="client-groups-table">
                                        <thead>
                                            <tr>
                                                <th>{{__('group-setting.Platform')}}</th>
                                                <th>{{__('group-setting.Book')}}</th>
                                                <th>{{__('group-setting.Mask Group Name')}}</th>
                                                <th>{{__('group-setting.Raw Group Name')}}</th>
                                                <th>{{__('group-setting.Max Leverage')}}</th>
                                                <th>{{__('group-setting.Min Deposit')}}</th>
                                                <th>{{__('group-setting.Visibility')}}</th>
                                                <th>{{__('group-setting.Action')}}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Basic table -->
        </div>
    </div>
</div>
<!-- END: Content-->

<!-- creat form section  -->
<div class="modal fade" id="edit-client-group">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Client Group</h4>
                    </div>
                    @if(Auth::user()->hasDirectPermission('edit group manager'))
                    <div class="card-body">
                        <form class="form form-vertical" id="edit-client-group-form" action="{{ route('admin.client-group.update-data')}}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="client-group-id" value="">
                            <div class="row">
                                {{-- single or multiple platform handle from the component --}}
                                {{-- check condition single platform true or false --}}
                                {{-- if platform is single then platform field will be hidden and not otherwise --}}
                                <x-platform-option account-type="live" use-for="admin_portal_client_group"></x-platform-option>
                                
                                <div class="col-12 mb-1">
                                    <div class="form-element other-selector">
                                        <label class="form-label" for="book">Book</label>
                                        <select class="select2 form-select" name="book" id="book">
                                            <optgroup>
                                                <option value="">Select A Book</option>
                                                <option value="A Book">A Book</option>
                                                <option value="B Book">B Book</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 mb-1">
                                    <div class="form-element">
                                        <label class="form-label" for="raw-group-name">Raw Group Name</label>
                                        <input type="text" id="raw-group-name" class="form-control" name="group_name" value="" placeholder="" />
                                    </div>
                                </div>

                                <div class="col-12 mb-1">
                                    <div class="form-element">
                                        <label class="form-label" for="group-display-name">Group Display
                                            Name</label>
                                        <input type="text" id="group-display-name" class="form-control" name="group_id" value="" placeholder="" />
                                    </div>
                                </div>

                                <div class="col-12 mb-1">
                                    <div class="form-element other-selector">
                                        <label class="form-label" for="account-type">Account Type</label>
                                        <select class="select2 form-select" name="account_category" id="account-type">
                                            <optgroup>
                                                <option value="demo">Demo</option>
                                                <option value="live">Live</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 mb-1">
                                    <div class="form-element">
                                        <label class="form-label" for="leverage">Leverage</label>
                                        <select class="js-select2 select2 form-select" multiple="multiple" name="leverage[]" id="leverage">
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
                                                <option value="one time">One Time</option>
                                                <option value="every time">Every Time</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 mb-1">
                                    <div class="form-element other-selector">
                                        <label class="form-label" for="visibility">Visibility</label>
                                        <select class="select2 form-select" name="visibility" id="visibility">
                                            <optgroup>
                                                <option value="visible">Visible</option>
                                                <option value="hidden">Hidden</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="button" class="btn btn-primary me-1" id="edit-client-group-btn" onclick="_run(this)" data-el="fg" data-form="edit-client-group-form" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="edit_client_call_back" data-btnid="edit-client-group-btn" style="width:180px">
                                        Save
                                    </button>

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
        </div>
    </div>
</div>
<!-- ########## START MODALS ########## -->
<!-- Modal to delete record -->
<div class="modal fade" id="delete-client-group">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalCenterTitle">Please Confirm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" class="modal-content pt-0">
                @csrf
                <input type="hidden" id="delete_client-group-id" name="id" />

                <div class="modal-body my-3">
                    <h5 class="text-center">
                        Do You Really Want To Delete This Client Group?
                    </h5 class="text-center">
                </div>
                <div class="modal-footer">
                    @if(Auth::user()->hasDirectPermission('delete group list'))
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger data-submit me-1">Yes Delete</button>
                    @else
                    <span class="text-danger">Sorry No Access To Write</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal to get client group -->
<div class="modal fade" id="get-client-group">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalCenterTitle">Please Confirm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" class="modal-content pt-0">
                @csrf
                <div class="modal-body my-3">
                    <h5 class="text-center">
                        Do You Really Want To Get Client Groups?
                    </h5 class="text-center">
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary get-group-button me-1" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ########## END MODALS ########## -->

@stop
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{ asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}">
</script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}">
</script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/components/components-tooltips.min.js') }}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<script src="{{ asset('admin-assets/app-assets/js/scripts/forms/form-select2.js') }}"></script>
<script>
    const clientGroupData = "{{ url('admin/client-groups') }}?op=data-table";
    const addClientGroup = "{{ url('admin/client-groups') }}";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<!-- <script src="{{ asset('admin-assets/assets/js/group_settings/client_groups.js') }}"></script> -->
<script>
    $(document).on('click', '.btn-edit-group', function() {
        let group_id = $(this).data('id');

        $("#edit-client-group").modal('show');
        $.ajax({
            url: '/admin/edit-client-groups',
            method: 'GET',
            dataType: 'JSON',
            data: {
                group_id: group_id,
            },
            success: function(data) {
                $('#client-group-id').val(data.id);
                $("#raw-group-name").val(data.group_name);
                $('#group-display-name').val(data.group_id);
                $('#min-deposit').val(data.min_deposit);
                $('#server option[value="' + data.server + '"]').prop('selected', true).trigger('change');
                $('#book option[value="' + data.book + '"]').prop('selected', true).trigger('change');
                $('#account-type option[value="' + data.account_category + '"]').prop('selected', true).trigger('change');
                $('#deposit-type option[value="' + data.deposit_type + '"]').prop('selected', true).trigger('change');
                $('#visibility option[value="' + data.visibility + '"]').prop('selected', true).trigger('change');
                var $newOption;
                var leverageOptions = JSON.parse(data.leverage);
                console.log(leverageOptions);
                $("#leverage").val(leverageOptions).trigger("change");
            }
        });
    });


    // --------------------------------------------------------------------------------------------
    // Update Client Group List
    // --------------------------------------------------------------------------------------------
    // $('#edit-client-group-btn').on('click', function(e) {
    //     e.preventDefault();
    //     var formData = $('#edit-client-group-form').serialize();
    //     $.ajax({
    //         url: '/admin/update-client-groups',
    //         method: 'post',
    //         data: formData,
    //         dataType: 'JSON',
    //         success: function(data) {
    //             notify('success', 'data.message', 'Client group update');
    //             $("#client-groups-table").DataTable().draw();
    //             $("#edit-client-group").modal('hide');
    //         },
    //         error: function(xhr, status, error) {
    //             notify('error', 'Client Update Failed!', 'Client group update');
    //         }

    //     })
    // })

    function edit_client_call_back(data) {
        if (data.status == true) {
            $("#client-groups-table").DataTable().draw();
            $("#edit-client-group").modal('hide');
            toastr['success'](data.message, 'Edit Client', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,

            });
            // comment_table_obj.DataTable().draw();
            $.validator("edit-client-group-form", data.errors);
        } else {
            $.validator("edit-client-group-form", data.errors);
        }
    }

    // datatable for client group
    var dt_basic_table = $(".datatables-basic");

    // DataTable with buttons
    // --------------------------------------------------------------------

    if (dt_basic_table.length) {
        var dt_basic = dt_basic_table.DataTable({
            processing: true,
            serverSide: true,
            ajax: clientGroupData,
            columns: [
                // { data: "responsive_id" },
                // { data: "id" }, // used for sorting so will hide this column
                {
                    data: "server",
                },
                {
                    data: "book",
                },
                {
                    data: "group_id",
                },
                {
                    data: "group_name",
                },
                {
                    data: "max_leverage",
                },
                {
                    data: "min_deposit",
                },
                {
                    data: "visibility",
                },
                {
                    data: "",
                },
            ],
            columnDefs: [{
                // Actions
                targets: -1,
                title: "Actions",
                orderable: false,
                render: function(data, type, full, meta) {
                    return (
                        `<a href="#" class="client-group-edit me-2 btn-edit-group" data-id="${full.id}" data-bs-toggle="modal" data-bs-target="#edit-client-group" data-bs-placement="top" title="Edit Group">` +
                        feather.icons["edit"].toSvg({
                            class: "font-small-4",
                        }) +
                        "</a>" +
                        `<a href="javascript:;" class="client-group-delete me-2" data-id="${full.id}" data-bs-toggle="modal" data-bs-target="#delete-client-group" data-bs-placement="top" title="Delete Group">` +
                        feather.icons["trash"].toSvg({
                            class: "font-small-4",
                        }) +
                        "</a>"
                    );
                },
            }, ],
            order: [
                [0, "desc"]
            ],
            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 10,
            lengthMenu: [10, 25, 50, 75, 100],
            language: {
                paginate: {
                    // remove previous & next text from pagination
                    previous: "&nbsp;",
                    next: "&nbsp;",
                },
            },
        });
    }

    // Delete Record
    $(".datatables-basic tbody").on("click", ".client-group-delete", function() {
        // console.log($(this).data('id'))
        // console.log('clicked')
        $("#delete-client-group #delete_client-group-id").val($(this).data('id'));
    });

    $("#delete-client-group").on("submit", "form", function(e) {
        e.preventDefault();
        const id = $(this).find('#delete_client-group-id').val();
        $.ajax({
            type: "DELETE",
            url: `${addClientGroup}/${id}`,
            data: {
                id
            },
            dataType: "JSON",

            success: function(data) {
                // console.log(data)
                if (data.status == "success") {
                    Swal.fire("Success!", `${data.msg}`, "success");
                    dt_basic.draw();
                    $(".modal").modal("hide");
                } else if (data.status == "failed") {
                    Swal.fire("Failed!", `${data.msg}`, "error");
                }
            },
        });
    });

    $(".get-group-button").on("click", function(e) {
        e.preventDefault();
        const id = null;
        $.ajax({
            url: '/admin/client-groups/get',
            dataType: "JSON",
            method: "POST",

            success: function(data) {
                // console.log(data)
                if (data.status == "success") {
                    Swal.fire("Success!", `${data.msg}`, "success");
                    dt_basic.draw();
                    $(".modal").modal("hide");
                } else if (data.status == "failed") {
                    Swal.fire("Failed!", `${data.msg}`, "error");
                    dt_basic.draw();
                    $(".modal").modal("hide");
                }
            },
        });
    });
</script>
@stop
<!-- END: page JS -->