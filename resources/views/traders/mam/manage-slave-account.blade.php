@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Manage Slave Account')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/mam.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/color.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/pamm/vanillaSelectBox.css') }}" />
<!-- datatable -->
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}"> -->
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css') }}">
<style>
    table.dataTable>thead .sorting::before,
    table.dataTable>thead .sorting::after,
    table.dataTable>thead .sorting_asc::before,
    table.dataTable>thead .sorting_asc::after,
    table.dataTable>thead .sorting_desc::before,
    table.dataTable>thead .sorting_desc::after,
    table.dataTable>thead .sorting_asc_disabled::before,
    table.dataTable>thead .sorting_asc_disabled::after,
    table.dataTable>thead .sorting_desc_disabled::before,
    table.dataTable>thead .sorting_desc_disabled::after {
        top: 11px;
    }

    table.dataTable tfoot th,
    table.dataTable thead th {
        color: inherit !important;
    }

    td.details-control {
        background: url("{{ asset('trader-assets/assets/img/plus.png') }}") no-repeat center center;
        cursor: pointer;
    }

    tr.details td.details-control {
        background: url("{{ asset('trader-assets/assets/img/minus.png') }}") no-repeat center center;
    }

    .has-error .error-msg {
        position: absolute;
        bottom: 12px;
        left: 34px;
    }

    select.form-control.action:focus {
        background: transparent !important;
    }

    .multiselect-dropdown-list-wrapper {
        background: #ced4da !important;
    }

    /* .multiselect-dropdown-search.form-control {
        background: #ced4da !important;
    } */

    /* .form-select.form-select-sm {
        color: #b8b9bd;
        background-color: #161d31;
        border: 1px solid #161d31;
    } */

    /* .select-dropdown optgroup,
    .select-dropdown option {
        background: #151a2c !important;
    } */

    .btn-check:focus+.btn-primary,
    .btn-primary:focus {
        color: #fff;
        background-color: #d1b970;
        border-color: #d1b970;
        box-shadow: #d1b970;
    }

    .multiselect-dropdown-list div:hover {
        background-color: #3599fd !important;
    }

    .multiselect-dropdown span.optext,
    .multiselect-dropdown span.placeholder {
        margin-right: 0.5em;
        margin-bottom: 2px;
        padding: 1px 0;
        border-radius: 4px;
        display: inline-block;
        background: var(--background-color) !important;
    }

    .multiselect-dropdown span.optext .optdel {
        float: right;
        margin: 0 -6px 1px 5px;
        font-size: 0.7em;
        margin-top: 2px;
        cursor: pointer;
        color: #666;
        background: var(--background-color);
        padding: 2px 5px !important;
        border-radius: 5px;
    }

    .multiselect-dropdown span.optext,
    .multiselect-dropdown span.placeholder {
        margin-right: 0.5em;
        margin-bottom: 2px;
        padding: 1px 0;
        border-radius: 4px;
        display: inline-block;
        background: var(--background-color) !important;
    }
</style>
@stop
@section('bread_crumb')
<!-- bread crumb -->
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-left">
                    <div class="card-body">
                        <h4 class="card-title">Account</h4>
                        <p class="card-text">
                        <div class="form-group fg">
                            <select class="form-control multisteps-form__input choice-colors" id="master_ac" name="platform">
                                <option value="" selected>Account Number</option>
                                @foreach ($trading_account as $account)
                                <option value="{{ $account->account_number }}">&nbsp;{{ $account->account_number }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-left">
                    <div class="card-body">
                        <h4 class="card-title">Balance</h4>
                        <p class="card-text">
                            <strong class="amount" id="balance">...</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 ">
                <div class="card text-left">
                    <div class="card-body">
                        <h4 class="card-title">Equity</h4>
                        <p class="card-text">
                            <strong class="amount" id="equity">...</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-4">
            <div class="card text-left">
                <div class="card-body">
                    <button style="display: none" class="btn mb-5 custom-btn add_new_slave_btn" id="add-new-trading-account" type="button" data-toggle="modal" data-target="#staticBackdrop"> <i class="fas fa-plus"></i> &nbsp;Add New
                        Tradding Account</button>
                    <div class="tab-pane active table-responsive" id="tabs-1" role="tabpanel">
                        <table id="trading_report_datatable" class="datatables-ajax deposit-request table table-responsive">
                            <thead class="thead-light cell-border compact stripe">
                                <tr>
                                    <th></th>
                                    <th>Account Number</th>
                                    <th>Allocation (%)</th>
                                    <th>Maximum Trade</th>
                                    <th>Minimum Volume</th>
                                    <th>Maximum Volume</th>
                                    <th>Platform</th>
                                    <th>Group</th>
                                    <th>Leverage</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" tabindex="-1" role="dialog" aria-labelledby="contest-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"> Add Slave Account</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contact" action="{{ route('user.addSlaveAccount') }}" method="post">
                    @csrf
                    <input type="hidden" name="master_account" value="" id="master_ac_hide">
                    <input type="hidden" name="platform" value="" id="master_server">
                    <fieldset>
                        <label for="">Acount Number</label>
                        <input placeholder="Trading Account Number" class="account" name="slave_account" type="text" tabindex="1" required autofocus>
                    </fieldset>
                    <fieldset>
                        <fieldset>
                            <label for="">Password</label>
                            <input placeholder="******" type="password" name="password" tabindex="1" required autofocus>
                        </fieldset>
                        <!-- <div class="form-group text-center">
                                                                                            <p>Risk Management ( Advance Settings ) </p>
                                                                                        </div> -->
                        <hr>
                        <p class="text-center">
                            <button class="btn custom-btn" id="btn-resk-management" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <i class="fa-solid fa-gear"></i>&nbsp;Risk Management ( Advance Settings )
                            </button>
                        </p>

                        <hr>
                        <div class="collapse show" id="collapseExample">
                            <div>
                                <fieldset>
                                    <label for="">Symbol</label>
                                    {{-- <div class="select-dropdown mam-dropdown">
                                            <select name="symbol[]" id="">
                                                <?= copy_symbols() ?>
                                            </select>
                                        </div> --}}
                                    <div class="select-dropdown mam-dropdown ">
                                        <select name="symbol[]" id="field2" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="3" onchange="console.log(this.selectedOptions)">
                                            <?= copy_symbols() ?>
                                        </select>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <label for="">Allocation</label>
                                    <div class="select-dropdown mam-dropdown select-color">
                                        <select name="allocation" id="">
                                            <option value="100">100%</option>
                                            <option value="30">30%</option>
                                            <option value="40">40%</option>
                                            <option value="50">50%</option>
                                            <option value="80">80%</option>
                                        </select>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <label for="">Maximum Number of Trade</label>
                                    <input placeholder="100" name="max_trade_number" type="tel" tabindex="3" required>
                                </fieldset>
                                <fieldset>
                                    <label for="">Maximum Trade Volume </label>
                                    <input placeholder="10" name="max_trade_vol" type="tel" tabindex="3" required>
                                </fieldset>
                                <fieldset>
                                    <label for="">Minimum Trade Volume</label>
                                    <input placeholder="10" name="min_trade_vol" type="tel" tabindex="3" required>
                                </fieldset>
                            </div>
                        </div>

                        <fieldset class="d-flex align-items-center">
                            <button type="button" style="background: rgb(46, 46, 46);" data-dismiss="modal" class="btn" data-submit="...Sending">No</button>

                            <button class="btn btn-success custom-btn" id="contact-submit" type="button" onclick="_run(this)" data-form="contact" data-loading="<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>" data-callback="addSlaveCallBack" data-submit="...Sending" data-file="true" data-btnid="contact-submit" data-animation="modalAddSlave">Confirm</button>
                        </fieldset>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- Button trigger modal -->
<button type="button" id="deleteSlaveButton" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
    Check Button
</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Delete Slave Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="del_id">
                <input type="hidden" id="del_ma">
                <input type="hidden" id="del_sa">
                Are you sure that you want to delete this <span class="show_id"></span> account?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="del_confirm">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Button trigger modal -->
<button type="button" id="deleteSymbolButton" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#delete_symbol">
    Check Button
</button>
<!-- Modal -->
<div class="modal fade" id="delete_symbol" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Delete Symbol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="del_sym_id">
                <input type="hidden" id="del_sym_slave">
                <input type="hidden" id="del_sym_symbol">
                Are you sure that you want to delete this <span class="show_symbol" style="color: red"></span> Symbol?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="del_symbol">Yes</button>
            </div>
        </div>
    </div>
</div>

<!--add symbol modal-->
<div id="modalSymbolAdd" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Symbol</h5>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addSymbolForm" action="/user/meta5_mam_add/add_symbol" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="col-sm-12 control-label pt-2">Symbol Name<span class="required"></span></label>
                        <div class="col-sm-12">

                            <select name="add_new_symbol" id="add_symbol" class="form-control select_option_design">
                                <option value="">Select A Symbol</option>
                                <?= copy_symbols() ?>
                            </select>
                        </div>
                    </div>
                    <input name="slave" value="" type="hidden" class="form-control" id="symbol-slave">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button class="btn btn-primary" id="add_new_symbol" type="button" onclick="_run(this)" data-form="addSymbolForm" data-loading="<i class='fa fa-refresh fa-spin fa-1x fa-fw'></i>" data-callback="addSymbolCallBack" data-file="true" data-btnid="add_new_symbol" data-animation="modalAddSlave">Add</button>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
@stop
@section('page-js')
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
</script> -->
<!-- datatable -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/server-side-button-action.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/datatable-functions.js') }}"></script>

<!-- <script src="{{ asset('trader-assets/assets/js/pamm/vanillaSelectBox.js') }}"></script> -->
<script src="{{ asset('trader-assets/assets/js/filter.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/common.js') }}"></script>
<script src="{{ asset('common-js/custom-multiselect-v2.js') }}"></script>

<script>
    // open modal
    // ---------------------
    $(document).on('click', '#add-new-trading-account', function() {
        $('#staticBackdrop').modal('show');
    });
    $(document).on("#btn-resk-management", function() {
        $("#collapseExample").slideToggle();
    })
    //default all selected in multiselect
    $('#field2 option').prop('selected', true);

    $("#master_ac").change(function() {
        $('.add_new_slave_btn').show();
        var master_login = $(this).val();
        dt.ajax.url("/user/meta-copy-slave-report?login=" + master_login).load();
        var master_login = $(this).val();
        $("#master_ac_hide").val(master_login);
        var server = $(this).find(':selected').data('server');
        $("#master_server").val(server);
        if (master_login != "") {
            $("#balance").html('Loading..');
            $("#equity").html('Loading..');
            $("#total-slave").html(0);

            $.ajax({
                url: '/user/trading-account-balance-equity',
                type: 'GET',
                dataType: 'json',
                data: {
                    login: master_login
                },
                success: function(data) {

                    if (data.success) {
                        $("#balance").html(data.balance);
                        $("#equity").html(data.equity);
                        // $("#balance").html('120');
                        // $("#equity").html('120');

                        $("#balance").html((!data.balance != 'faild') ? data.balance : 'Not Found');
                        $("#equity").html((!data.equity != 'faild') ? data.equity : 'Not Found');

                        $("#total_volume").html(data.total_volume);
                        $("#total_trade").html(data.total_trade);
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }
    });

    var dt = $('#trading_report_datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ordering": true,
        "ajax": "/user/meta-copy-slave-report?login=0",
        "columns": [{
                "class": "details-control",
                "orderable": false,
                "data": null,
                "defaultContent": ""
            },
            {
                "data": "slave_account"
            },
            {
                "data": "allocation"
            },
            {
                "data": "max_number_of_trade"
            },
            {
                "data": "min_trade_volume"
            },
            {
                "data": "max_trade_volume"
            },
            {
                "data": "platform"
            },
            {
                "data": "group"
            },
            {
                "data": "leverage"
            },
            {
                "data": "status"
            },
            {
                "data": "action"
            }
        ],
        language: {
            paginate: {
                previous: "<",
                next: ">",
            },
        }
    });

    function openDeleteModal(id, ma, sa) {
        $('#deleteSlaveButton').trigger('click');
        $("#del_id").val(id);
        $("#del_ma").val(ma);
        $("#del_sa").val(sa);
        $(".show_id").html(sa);
    }

    function format(d) {
        return d.extra;
    }


    // Array to track the ids of the details displayed rows
    var detailRows = [];

    $('#trading_report_datatable tbody').on('click', 'tr td.details-control', function() {
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

    $("#del_confirm").click(function() {
        var id = $("#del_id").val();
        var ma = $("#del_ma").val();
        var sa = $("#del_sa").val();
        $("#del_confirm").html('<i class="fa fa-spinner fa-pulse"></i>');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/user/meta5_mam_delete",
            type: "POST",
            dataType: "json",
            data: {
                'id': id,
                'ma': ma,
                'sa': sa
            },
            success: function(response) {
                $("#del_confirm").html('Confirm');
                $("#staticBackdrop2").modal('hide');
                if (response.status == true) {
                    notify('success', 'Account successfully deleted!', "Success");
                    dt.ajax.reload(null, true);
                } else {
                    notify('error', 'Fail To Delete Account', "Error");
                }
            }
        })

    });
    // $("body").filterInput();

    //open delete symbol modal 
    function deleteSymbol(e) {
        $('#deleteSymbolButton').trigger('click');
        var parentObj = $(e).closest('tr');
        id = parentObj.data('id');
        slave = parentObj.data('slave');
        symbol = parentObj.data('symbolname'),

            $("#del_sym_id").val(id);
        $("#del_sym_slave").val(slave);
        $("#del_sym_symbol").val(symbol);
        $('.show_symbol').html(symbol);
    }
    $(document).on('click', '#del_symbol', function() {
        var id = $("#del_sym_id").val();
        var slave = $("#del_sym_slave").val();
        var symbol = $("#del_sym_symbol").val();
        $("#del_symbol").html('<i class="fa fa-spinner fa-pulse"></i>');


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/user/meta5_mam_delete/symbol_delete",
            type: "POST",
            dataType: "json",
            data: {
                'id': id,
                'slave': slave,
                'symbol': symbol
            },
            success: function(response) {
                $("#del_symbol").html('Confirm');
                $("#delete_symbol").modal('hide');
                if (response.status == true) {
                    notify('success', 'Symbol successfully deleted!', "Success");
                    dt.ajax.reload(null, true);
                } else {
                    notify('error', 'Fail To Delete Symbol', "Error");
                }
            }
        })
    });
    //delete function end

    //add symbol modal function
    function addSymbolReady(sl) {
        $('#modalSymbolAdd').modal('show');
        $('#addSymbolForm #symbol-slave').val(sl);
    }


    function addSymbolCallBack(data) {
        $('#add_new_symbol').prop('disabled', false);
        if (data.status) {
            notify('success', data.message);
            $("#modalSymbolAdd").modal('hide');
            dt.ajax.reload(null, true);
        } else {
            notify('error', data.message);
            if (data.errors)
                $.validator("addSymbolForm", data.errors);
        }
    }

    //End add symbol modal function

    //symbol edit function here
    function editSymbol(e) {
        var parentObj = $(e).closest('tr');
        var fields = parentObj.find('td');


        parentObj.find('.fa-trash').hide();
        parentObj.find('.fa-edit').hide();
        parentObj.find('.fa-save').show();
        parentObj.find('.fa-times').show();

        $.each(fields, function(k, v) {
            var obj = $(v);


            if (obj.data('name') == "symbol") {
                obj.html(obj.html() + '<input class="form-control filter" type="hidden" value="' + obj.html() +
                    '" name="' + obj.data('name') + '">');
            } else {
                if (obj.data('name') == 'status') {
                    obj.html(
                        '<select name="status" class="form-control action"><option value="active">Active</option><option value="inactive">Inactive</option></select>'
                    );
                }
            }

        });
        // $("body").filterInput();
    }


    function editSymbolUpdate(e) {
        var _self = $(e);
        var parentObj = $(e).closest('tr');
        var fields = parentObj.find('td');

        _self.removeClass('.fa-save');
        _self.addClass('fa-spinner fa-pulse');
        var postData = {
            master: parentObj.data('master'),
            slave: parentObj.data('slave'),
            role_id: parentObj.data('role'),


            id: parentObj.data('id'),
            status: parentObj.find('td select[name="status"]').val(),
            symbol: parentObj.find('td input[name="symbol"]').val(),
            allocation: parentObj.find('td input[name="allocation"]').val(),
            sl: parentObj.find('td input[name="sl"]').val(),
            max_trade: parentObj.find('td input[name="max_trade"]').val(),
            max_volume: parentObj.find('td input[name="max_volume"]').val(),
            min_volume: parentObj.find('td input[name="min_volume"]').val(),
        };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $.ajax({
            url: '/user/meta5_mam_delete/submit_symbol',
            type: 'POST',
            dataType: 'json',
            data: postData,
            success: function(data) {
                if (data.status) {
                    notify('success', 'Symbol successfully Updated!', "Success");
                    // notify('success',data.message);
                    dt.ajax.reload(null, true);
                } else {
                    notify('success', 'Failed To Update!', "Success");
                    // notify('error', data.message);
                }
                _self.removeClass('fa-spinner fa-pulse');
                _self.addClass('.fa-save');
            },
            error: function(e) {
                console.log(e);
            }
        });
    }

    function editSymbolCancel(e) {
        dt.ajax.reload(null, true);
    }

    //add slave account
    function addSlaveCallBack(data) {
        if (data.status == true) {
            notify('success', "Slave Account Added Successfully!", "Success");
            $("#staticBackdrop").hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            dt.draw();
        } else {
            notify('error', data.message, "Error");
            $.validator("contact", data.errors);
        }
    }
</script>
@stop