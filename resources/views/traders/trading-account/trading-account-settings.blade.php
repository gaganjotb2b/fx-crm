@extends(App\Services\systems\VersionControllService::get_layout('trader'))
@section('title', 'Trading Account Settings')
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('trader-assets/assets/css/custom_datatable.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css') }}">
<style>
    .error-msg {
        color: red;
    }

    #b-icon-dollar {
        font-size: 3rem;
    }

    #datatables-ajax tr,
    #datatables-ajax td:first-child {
        border-left: 3px solid var(--custom-primary);
    }

    #datatables-ajax tr,
    #datatables-ajax th:first-child {
        border-left: 3px solid;
    }

    /* #datatables-ajax tr,
        #datatables-ajax td {
            background-color: #f7fafc;
            vertical-align: middle;
        } */

    #datatables-ajax {
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

    .light-version .datatables-ajax tr,
    .datatables-ajax td {
        background-color: #f7fafc !important;
    }
</style>
@if ($PasswordSettings)
@if (
$PasswordSettings->master_password == 0 &&
$PasswordSettings->investor_password == 0 &&
$PasswordSettings->leverage == 0)
<style>
    .datatables-ajax tbody tr td:nth-child(8) {
        display: none;
    }

    .datatables-ajax thead tr th:nth-child(8) {
        display: none;
    }
</style>
@endif
@endif
@stop
<!-- breadcrumb -->
@section('bread_crumb')
{!!App\Services\systems\BreadCrumbService::get_trader_breadcrumb()!!}
@stop
<!-- main content -->
@section('content')
<div class="container-fluid py-4">
    <div class="custom-height-con">
        <div class="card">
            <div class="card-body">
                <table class="table datatables-ajax table-hover w-100" id="datatables-ajax">
                    <thead>
                        <tr>
                            <th>{{ __('page.account') }}</th>
                            <th>{{ __('page.paasword') }}</th>
                            {{-- <th>{{ __('page.inv-password') }}</th> --}}
                            <th>{{ __('page.account-type') }}</th>
                            <th>{{ __('page.server') }}</th>
                            <th>{{ __('page.leverage') }}</th>
                            <th>{{ __('page.balance') }}</th>
                            <th>{{ __('page.equity') }}</th>
                            <th>{{ __('page.action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>
<!-- all modal -->
<!-- Modal -->
<div class="modal fade" id="modal-change-password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="change-password-form" action="{{ route('user.trading-account.settings-form') }}" method="post">
            @csrf
            <input type="hidden" name="op" value="password">
            <input type="hidden" name="account" id="m-password-account">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('page.change-password') }}</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="old-password">{{ __('page.current-password') }}</label>
                    <div class="input-group">
                        <input class="form-control" name="current_password" placeholder="Current Password" type="password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="old-password">{{ __('page.new-password') }}</label>
                    <div class="input-group">
                        <input class="form-control" name="new_password" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" placeholder="New Password" type="password" id="new-password">
                        <span class="input-group-text position-relative bg-gradient-primary cursor-pointer btn-gen-password" style="padding:13px">
                            <i class="fas fa-key"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm-password">{{ __('page.confirm-new-password') }}</label>
                    <div class="input-group">
                        <input class="form-control" name="confirm_new_password" placeholder="Confirm Password" type="password" id="confirm-password">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">{{ __('page.close') }}</button>
                <button type="button" data-label="Submit Request" id="btn-submit-request" data-btnid="btn-submit-request" data-callback="change_password_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="change-password-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto float-end">{{ __('page.submit-request') }}</button>

            </div>
        </form>
    </div>
</div>
<!-- investor password modal -->
<div class="modal fade" id="modal-inv-password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="investor-password-form" method="post" action="{{ route('user.trading-account.settings-form') }}">
            @csrf
            <input type="hidden" name="op" value="investor-password">
            <input type="hidden" name="account" id="inv-password-account">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('page.change-investor-password') }}</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="current-investor-password">{{ __('page.current-password') }}</label>
                    <div class="input-group">
                        <input class="form-control" name="current_password" placeholder="Current Password" type="password" id="current-investor-password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="new-investor-password">{{ __('page.new-password') }}</label>
                    <div class="input-group">
                        <input class="form-control" data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" name="new_password" placeholder="New Password" type="password" id="new-investor-password">
                        <span class="input-group-text position-relative bg-gradient-primary cursor-pointer btn-gen-password" style="padding:13px">
                            <i class="fas fa-key"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm-investor-password">{{ __('page.confirm-new-password') }}</label>
                    <div class="input-group">
                        <input class="form-control" name="confirm_new_password" placeholder="Confirm New Password" type="password" id="confirm-investor-password">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">{{ __('page.close') }}</button>
                <button type="button" data-label="Submit Request" id="btn-inv-request" data-btnid="btn-inv-request" data-callback="investor_password_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="investor-password-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto float-end">{{ __('page.submit-request') }}</button>
            </div>
        </form>
    </div>
</div>
<!-- leverage -->
<div class="modal fade" id="modal-leverage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="leverage-form" action="{{ route('user.trading-account.settings-leverage-form') }}" method="post">
            <input type="hidden" name="account" id="leverage-account">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('page.change-leverage') }}</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="leverage">{{ __('page.leverage') }}</label>
                    <select class="form-control multisteps-form__input" id="leverage" name="leverage">
                        <option value="">{{ __('page.choose-a-leverage') }}</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">{{ __('page.close') }}</button>
                <button type="button" data-label="Submit Request" id="btn-leverage-request" data-btnid="btn-leverage-request" data-callback="leverage_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="leverage-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto float-end">{{ __('page.submit-request') }}</button>
            </div>
        </form>
    </div>
</div>
<!-- Passowrd show model -->
{{-- <div class="modal fade" id="show_password_model" tabindex="-1" role="dialog" aria-labelledby="modelLebel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="show-password-form" action="{{route('user.trading-account.show-password')}}" method="post">
@csrf
<input type="hidden" name="account" id="account_id-show">
<input type="hidden" name="pass_type" id="pass_type-show">
<div class="modal-header">
    <h5 class="modal-title" id="modelLebel">To See <span class="modelLebel"></span> </h5>
    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group">
        <label for="old-password">{{__('page.transaction_password')}}</label>
        <div class="input-group">
            <input class="form-control" name="transaction_password" placeholder="Current Password" type="password">
        </div>
    </div>
    <div class="form-group" id="al_show_password" style="display:none">
        <label><span class="modelLebel"></span> is : </label>
        <div class="input-group">
            <input class="form-control al_copy_input" id="al-copy-input" type="text">
            <span class="input-group-text position-relative bg-gradient-primary cursor-pointer btn-copy-input" style="padding:13px">
                <i class="fas fa-copy"></i>
            </span>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">{{ __('page.close') }}</button>
    <button type="button" data-label="Submit Request" id="btn-submit-request-showPassword" data-btnid="btn-submit-request-showPassword" data-callback="show_password_call_back" data-loading="<i class='fa-spin fas fa-circle-notch'></i>" data-form="show-password-form" data-el="fg" onclick="_run(this)" class="btn bg-gradient-primary ms-auto float-end">{{ __('page.submit-request') }}</button>

</div>
</form>
</div>
</div> --}}
<!--only show Passowrd show duplicate model------------------------------------------------------------ -->
<div class="modal fade" id="show_password" tabindex="-1" role="dialog" aria-labelledby="modelLebel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" action="{{ route('trading_account.reset.pass') }}" method="post" id="show-reset-password-form">
            @csrf
            <input type="hidden" name="account" id="account_id_show">
            <input type="hidden" name="pass_type" id="pass_type-show">
            <div class="modal-header">
                <h5 class="modal-title" id="modelLebel">To See <span class="modelLebel"></span> </h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group" id="show_pass_div">
                    <label><span class="modelLebel"></span> is : </label>
                    <div class="input-group">
                        <input class="form-control al_copy_input password_show" id="al-copy-input" value="" type="text">
                        <span class="input-group-text position-relative bg-gradient-primary cursor-pointer btn-copy-input" style="padding:13px">
                            <i class="fas fa-copy"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group reset_all_pass" style="display: none" id="show_reset_btn">
                    {{-- <input type="text" name="account" id="account_id">
                <input type="text" name="pass_type" id="pass_type_show"> --}}
                    <label>Please reset your password</label><br>

                    <button type="button" data-label="Submit Request" id="reset-password" data-btnid="reset-password" data-callback="show_reset_password_call_back" data-loading="<i class='fa-spinner fas fa-circle-notch'></i>" data-form="show-reset-password-form" data-el="fg" onclick="_run(this)" class="btn btn-primary ms-auto float-end">Reset Password</button>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('page.close') }}</button>
            </div>
        </form>
    </div>
</div>

@stop
@section('corejs')
<script src="{{ asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
@stop
@section('page-js')
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

<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('admin-assets/app-assets/js/scripts/tables/buttons.print.min.js') }}"></script>

<!-- SweetAlert2 for confirmation popups -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('trader-assets/assets/js/scripts/pages/get-client-group.js') }}"></script>
<script src="{{ asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js') }}"></script>
<script src="{{ asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js') }}"></script>
<script src="{{ asset('/common-js/copy-js.js') }}"></script>
<script src="{{ asset('/common-js/password-gen.js') }}"></script>
<script src="{{ asset('/common-js/finance.js') }}"></script>


<script>
    var trading_account_dt = dt_fetch_data(
        '/user/trading-account/settings/fetch-data', //request url
        [{
                "data": "account"
            },
            {
                "data": "password"
            },
            {
                "data": "account_type"
            },
            {
                "data": "server"
            },
            {
                "data": "leverage"
            },
            {
                "data": "balance"
            },
            {
                "data": "equity"
            },
            {
                "data": "action"
            }
        ],
        false, //filter
        false, //feather icon
        false //exports
        ,
        "", //exports collumns,
        "", //footer sum collumn
        true, // change length
        true, //language
        true //search
    );
    // change pasword--------------------------
    $(document).on('click', ".btn-change-password", function() {
        $("#m-password-account").val($(this).data('id'))
        $("#modal-change-password").modal('show');
    });
    $("#change-password-form").trigger("reset");
    // disable button
    $(document).on('click', "#btn-submit-request", function() {
        $(this).prop('disabled', true);
    });
    // change password callback--------------
    function change_password_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Change Password');
            $("#change-password-form").trigger("reset");
            $("#js-btn-next").trigger('click');
            $("#c-account-no").text(data.account_no);
            $("#c-investor-pass").text(data.inv_password);
            $("#c-masster-pass").text(data.master_password);
            $("#c-phone-pass").text(data.phone_password);
            $("#modal-change-password").modal('hide');
            trading_account_dt.draw()
        }
        if (data.status == false) {
            notify('error', data.message, 'Change Password');
        }
        $("#btn-submit-request").prop('disabled', false);
        $.validator("change-password-form", data.errors);
    }
    // change investor pasword--------------------------
    $(document).on('click', ".btn-inv-password", function() {
        $("#inv-password-account").val($(this).data('id'))
        $("#modal-inv-password").modal('show');
    });
    $("#investor-password-form").trigger("reset");
    // diabled submit button for 
    $(document).on('click', "#btn-inv-request", function() {
        $(this).prop('disabled', true);
    });

    function investor_password_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Change Investor Password');
            $("#investor-password-form").trigger("reset");
            $("#js-btn-next").trigger('click');
            $("#c-account-no").text(data.account_no);
            $("#c-investor-pass").text(data.inv_password);
            $("#c-masster-pass").text(data.master_password);
            $("#c-phone-pass").text(data.phone_password);
            $("#modal-inv-password").modal('hide');
            trading_account_dt.draw()
        }
        if (data.status == false) {
            notify('error', data.message, 'Change Investor Password');
        }
        $("#btn-inv-request").prop('disabled', false);
        $.validator("investor-password-form", data.errors);
    }
    // change leverage--------------------------
    $(document).on('click', ".btn-change-leverage", function() {
        $("#leverage-account").val($(this).data('id'))
        let server = $(this).data('server');
        let client_type = $(this).data('accounttype');
        let client_group = $(this).data('clientgroup');
        console.log(server);
        console.log(client_type);
        console.log(client_group);
        get_client_group(server, client_type, 'client-group', client_group);
        $("#modal-leverage").modal('show');
    });
    // disable button
    $(document).on('click', '#btn-leverage-request', function() {
        $(this).prop('disabled', true);
    })
    // change leverage callback
    function leverage_call_back(data) {
        if (data.status) {
            notify('success', data.message, 'Change Leverage');
            $("#modal-leverage").modal('hide');
            trading_account_dt.ajax.reload();
        } else {
            notify('error', data.message, 'Change Leverage');
        }
        $.validator('leverage-form', data.errors);
    }

    // Delete account functionality
    $(document).on('click', '.btn-delete-account', function() {
        let accountId = $(this).data('id');
        let accountNumber = $(this).data('account');
        
        // Show confirmation popup
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to delete trading account ${accountNumber}? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete your account.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Make AJAX call to delete account
                $.ajax({
                    url: '{{ route("user.trading-account.delete") }}',
                    method: 'POST',
                    data: {
                        id: accountId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                            // Reload datatable
                            trading_account_dt.ajax.reload();
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    });
    // genrate randome password
    $(document).on('click', ".btn-gen-password", function() {
        var field = $(this).closest('div').find('input[rel="gp"]');
        field.val(rand_string(field));
        field.attr('type', 'text');
    });
    // select password for copy
    $('input[rel="gp"]').on("click", function() {
        let id = $(this).attr('id');
        $(this).select();
        if ($(this).val() != "") {
            copy_to_clipboard(id)
        }
        $(this).attr('type', 'password');
    });

    $(document).on("click", ".btn-load-balance", function() {
        let $this = $(this);
        let account = $(this).data('id');
        balance_equity($this, account, 'balance'); //finance js
    });
    $(document).on("click", ".btn-load-equity", function() {
        let $this = $(this);
        let account = $(this).data('id');
        balance_equity($this, account, 'equity'); // finance js
    });
    // show password model work 
    $(document).on("click", ".btn-show-password", function() {
        $("#show-password-form").trigger('reset');
        $('#al-copy-input').val('');
        $('#al_show_password').hide();
        // $("#account_id-show").val($(this).data('id'));
        $("#account_id_show").val($(this).data('id'));
        $("#pass_type-show").val($(this).data('colomn'));
        $('.modelLebel').html($(this).html());
        // $("#show_password_model").modal('show');
        $("#show_password").modal('show');
    });

    $(document).on("click", ".btn-copy-input", function() {
        let id = $('.al_copy_input').attr('id');
        $('.al_copy_input').select();
        if ($('.al_copy_input').val() != "") {
            copy_to_clipboard(id)
        }
    });
    $('.al_copy_input').on("click", function() {
        let id = $(this).attr('id');
        $(this).select();
        if ($(this).val() != "") {
            copy_to_clipboard(id)
        }
    });
    // show password call back 
    function show_password_call_back(data) {
        console.log(data);
        if (data.status == true) {
            $("#show-password-form").trigger('reset');
            $('#al-copy-input').val(data.password);
            $('#al_show_password').show();
        }
        if (data.status == false) {
            notify('error', data.message, 'Password seen');
        }
        $.validator("show-password-form", data.errors);
    }


    //show password modal
    $(document).on('click', '.btn-show-password', function() {
        var id = $(this).data('id');
        var pass_type = $(this).data('colomn');
        $.ajax({
            type: "GET",
            url: '/user/trading-account/all-password-show',
            data: {
                id: id,
                pass_type: pass_type
            },
            dataType: 'json',
            success: function(data) {

                if (data.status == 0) {
                    $('#show_pass_div').hide();
                    $('#show_reset_btn').show();
                }
                if (data.status == 1) {
                    $('#show_pass_div').show();
                    $('#show_reset_btn').hide();
                }

                $('#pass_type').val(data.password);
                $('.password_show').val(data.password);
            }
        }); //END: get client
    }) //

    //for password reset opration
    $(document).on('click', '.pass_show', function() {
        $("#account_id").val($(this).data('id'));
        $("#pass_type_show").val($(this).data('colomn'));
    })


    function show_reset_password_call_back(data) {
        if (data.status == true) {
            notify('success', data.message, 'Password');
            trading_account_dt.draw();
            $('#show_password').modal('hide');
            $('#show_password').find('#show_reset_btn').hide();
            $('#show_password').find('#show_pass_div').show();

        }
        if (data.status == false) {
            notify('error', data.message, 'Password');
        }
    }
</script>
@stop