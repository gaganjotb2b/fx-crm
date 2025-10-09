@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Social Trade Settings')
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
                        <h2 class="content-header-title float-start mb-0">Social Trade Settings</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('admin-management.home')}}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Social Trade</a>
                                </li>
                                <li class="breadcrumb-item active">Social Trade Settings
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
                                <p>Profit share is income against the profit that Social Trade clients make add margin is margin on top of that profit share for ADMIN.</p>
                            </div>
                            <div class="border-start-3 border-start-success p-1 mb-1 bg-light-info">
                                <p>Master Limit and Slave limit is how many Social Trade registration an user can do and how many max slave (copier) he can have.</p>
                            </div>
                            <div class="border-start-3 border-start-info p-1 mb-1 bg-light-info">
                                <p>If you dont want minimum deposit for Global Social Trade leave it blank</p>
                            </div>
                            <div class="border-start-3 border-start-danger p-1 mb-1 bg-light-info">
                                {{-- <p>{{__('page.i_note4')}}</p> --}}
                                <p>If you dont want minimum deposit for Social Trade Registration leave it blank. if you need minimum balance fill only balance field if you want minimum account balance fill only Account balance field</p>
                            </div>
                        </div>
                        <hr>
                        <h3 style="margin-left: 15px;">Social Trade Settings Action</h3>
                        <div class="col-sm-12">
                            <div class="card-body">
                                <div class="form-check form-switch">
                                    <label class="form-check-label" for="myCheck4">Social Trade Requirement</label>
                                    <input type="checkbox" id="myCheck4" name="pamm_requirement_status" onchange='pammRequirementStatus(this);' <?= ($data->pamm_requirement_status == 1) ? 'checked="checked"' : ''; ?> data-columnname="pamm_requirement_status" class="form-check-input switch-btn" data-fieldtitle="Social Trade Requirement" />
                                </div>
                                <hr>

                                <div class="form-check form-switch">
                                    <label class="form-check-label" for="myCheck">Profit Share</label>
                                    <input type="checkbox" class="form-check-input switch-btn verified" id="myCheck" name="profit_share_status" data-plugin-ios-switch onchange='profitShareStatus(this);' <?= ($data->profit_share_status == 1) ? 'checked="checked"' : ''; ?> data-columnname="profit_share_status" data-fieldtitle="Profit Share" />
                                </div>
                                <hr>
                                <div class="form-check form-switch">
                                    <label class="form-check-label" for="myCheck2">Flexible Profit Share</label>
                                    <input type="checkbox" class="form-check-input switch-btn flex_verified" id="myCheck2" name="flexible_profit_share_status" onchange='flexibleProfitShareStatus(this);' <?= ($data->flexible_profit_share_status == 1) ? 'checked="checked"' : ''; ?> data-columnname="flexible_profit_share_status" data-fieldtitle="Flexible Profit Share" />
                                </div>
                                <!-- <hr>
                                <div class="form-check form-switch" style="display: none;">
                                    <label class="form-check-label" for="myCheck3">Profit Share Commission</label>
                                    <input type="checkbox" class="form-check-input switch-btn" id="myCheck3" name="profit_share_commission" onchange='profitShareCommission(this);' <?= ($data->profit_share_commission == 1) ? 'checked="checked"' : ''; ?> data-columnname="profit_share_commission" data-fieldtitle=" Profit Share Commission" />
                                </div>
                                <hr>
                                <div class="form-check form-switch">
                                    <label class="form-check-label" for="myCheck6">Manual Approve Social Trade Registration</label>
                                    <input type="checkbox" class="form-check-input switch-btn" id="myCheck6" name="manual_approve_pamm_reg" onchange='manualApprovePammReg(this);' <?= ($data->manual_approve_pamm_reg == 1) ? 'checked="checked"' : ''; ?> data-columnname="manual_approve_pamm_reg" data-fieldtitle="Manual Approve Social Trade Registration" />
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4><b>Social Trade Settings</b></h4>
                        </div>
                        <hr>
                        <div class="card-body">
                            <form action="{{ route('admin.pamm.process') }}" method="post" id="pammsettingsform">
                                @csrf
                                <input type="hidden" name="op" value="settings_pamm_values">
                                <input type="hidden" name="id" value="1">
                                <input type="hidden" name="profit_share_margin_value">
                                <input type="hidden" name="manual_approve_pamm_reg">

                                <div id="text4" <?= ($data->pamm_requirement_status == 1) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                    <h4 class="header">Social Trade Requirement</h4>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="name-icon">Social Trade Account Limit<span class="text-danger">&#9734;</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather="user"></i></span>
                                                    <input type="text" id="pamm_account_limit" class="form-control" value="" name="pamm_account_limit" placeholder="" data-bs-toggle="tooltip" data-bs-placement="top" title="Maximum number of Social Trade Account Your broker can have once this limit reached no more Social Trade registration will be possible" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12" style="display: none;">
                                        <div class="mb-1 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="name-icon">Social Trade Requirement<span class="text-danger">&#9734;</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather="book"></i></span>
                                                    <input type="text" id="pamm_requirement" class="form-control" value="" name="pamm_requirement" placeholder="" data-bs-toggle="tooltip" data-bs-placement="top" title="Social Trade requirement" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="name-icon">Master Limit<span class="text-danger">&#9734;</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather="user"></i></span>
                                                    <input type="number" id="master_limit" class="form-control" value="" name="master_limit" placeholder="" data-bs-toggle="tooltip" data-bs-placement="top" title="Maximum number of Social Trade master a slave account can copy at a time" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="name-icon">Slave Limit<span class="text-danger">&#9734;</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i data-feather="user"></i></span>
                                                    <input type="number" id="slave_limit" class="form-control" value="" name="slave_limit" placeholder="" data-bs-toggle="tooltip" data-bs-placement="top" title="Maximum number of slave a Social Trade manager can have at a time" />
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
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" name="minimum_deposit" id="minimum_deposit" class="form-control" placeholder="100" aria-label="Amount (to the nearest dollar)" data-bs-toggle="tooltip" data-bs-placement="top" title="To become a Social Trade Manager This is the minimum deposit requirement" />
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="name-icon">Minimum Wallet Balance<span class="text-danger">&#9734;</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" id="minimum_wallet_balance" name="minimum_wallet_balance" class="form-control" placeholder="100" aria-label="Amount (to the nearest dollar)" data-bs-toggle="tooltip" data-bs-placement="top" title="Minimum wallet balance required to become a Social Trade Manager" />
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="name-icon">Minimum Account Balance<span class="text-danger">&#9734;</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" name="minimum_account_balance" id="minimum_account_balance" class="form-control" placeholder="100" aria-label="Amount (to the nearest dollar)" data-bs-toggle="tooltip" data-bs-placement="top" title="Minimum balance in trading account (trading account that will act as master) required to become a Social Trade Manager" />
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>



                                <div id="text" <?= (($data->profit_share_status == 1) ? 'style="display:block"' : 'style="display:none"'); ?>>
                                    <h4 class="header">Profit Share</h4>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="name-icon">Standard Profit Share<span class="text-danger">&#9734;</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text">%</span>
                                                    <input type="number" id="profit_share_value" name="profit_share_value" class="form-control" placeholder="100" aria-label="Amount (to the nearest dollar)" data-bs-toggle="tooltip" data-bs-placement="top" title="Standard profit share if Social Trade manager don't edit profit share or flexible profit share is disable this is the standard/default profit share" />
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <div id="text2" <?= ($data->flexible_profit_share_status == 1) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                    <h4 class="header">Flexible Profit Share</h4>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="name-icon">Minimum Profit Share<span class="text-danger">&#9734;</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text">%</span>
                                                    <input type="number" id="minimum_profit_share_value" name="minimum_profit_share_value" class="form-control" placeholder="100" aria-label="Amount (to the nearest dollar)" data-bs-toggle="tooltip" data-bs-placement="top" title="Minimum offered profit share by Social Trade Manager this can be more or less then the default profit share" />
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="name-icon">Maximum Profit Share<span class="text-danger">&#9734;</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text">%</span>
                                                    <input type="number" id="maximum_profit_share_value" name="maximum_profit_share_value" class="form-control" placeholder="100" aria-label="Amount (to the nearest dollar)" data-bs-toggle="tooltip" data-bs-placement="top" title="Minimum Offered Profit share by Social Trade Manager this can be more or less then the default profit share" />
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <div id="text3" <?= ($data->profit_share_commission == 1) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="name-icon">Profit Share Commission<span class="text-danger">&#9734;</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text">%</span>
                                                    <input type="number" id="profit_share_commission_value" name="profit_share_commission_value" class="form-control" placeholder="100" aria-label="Amount (to the nearest dollar)" data-bs-toggle="tooltip" data-bs-placement="top" title="Broker Commission from Social Trade Managers Profit Share" />
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="name-icon">Profit Share Duration<span class="text-danger">&#9734;</span></label>
                                            </div>
                                            <div class="col-sm-8">
                                            <select class="select2 form-select" name="profit_duration" id="profit_duration">
                                                <optgroup>
                                                    <option value="daily" <?=($data->profit_duration == "daily" ? "selected" : "")?>>Daily</option>
                                                    <option value="weekly" <?=($data->profit_duration == "weekly" ? "selected" : "")?>>Weekly</option>
                                                    <option value="biweekly" <?=($data->profit_duration == "biweekly" ? "selected" : "")?>>Biweekly</option>
                                                    <option value="monthly" <?=($data->profit_duration == "monthly" ? "selected" : "")?>>Monthly</option>
                                                </optgroup>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-1 row mt-2 float-end">
                                    <div class="col-md-6 col-sm-6 col-6">
                                        <button class="btn btn-danger" type="button">RESET</button>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-6">
                                        <button type="button" class="btn btn-primary prop_disabled" id="settingsBtn1" data-btnid="settingsBtn1" data-loading="Processing..." data-form="pammsettingsform" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-validator="true" data-callback="pammSettingsCallBack" onclick="_run(this)" >Submit</button>
                                        {{-- <button class="btn btn-primary float-end prop_disabled" type="button" id="add-crypto" onclick="_run(this)" data-el="fg" data-form="form-crypto-address" data-loading="<div class='spinner-border spinner-border-sm' role='status'><span class='visually-hidden'>Loading...</span></div>" data-callback="crypto_call_back" data-btnid="add-crypto" style="width:180px">SAVE</button> --}}
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
    //pammSettingCallBack
    function pammSettingsCallBack(data) {
        $('#settingsBtn1').prop('disabled', false);
        if (data.success) {
            toastr['success'](data.message, 'Create', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                closeButton: true,
                tapToDismiss: false,
                progressBar: true,
                timeOut: 2000,
            });
        } else {
            notify('error', data.message);
            $.validator("pammsettingsform", data.errors);
        }
    }

    function pammRequirementStatus(checkbox) {
        // Get the checkbox
        var checkBox = document.getElementById("myCheck4");
        // Get the output text
        var text = document.getElementById("text4");

        // If the checkbox is checked, display the output text
        if (checkBox.checked == true) {
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
    }


    function profitShareStatus(checkbox) {
        // Get the checkbox
        var checkBox = document.getElementById("myCheck");
        // Get the output text
        var text = document.getElementById("text");

        //Flexible profit share checkbox disable with disign
        // if(checkBox.checked == true){
        //     $(".flex_verified").prop('checked', false);
        //     $("#text2").hide();

        // }
        // else{
        //     $(".flex_verified").prop('checked', true);
        //     $("#text2").show();
        // }

        // If the checkbox is checked, display the output text
        if (checkBox.checked == true) {
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
    }

    function flexibleProfitShareStatus(checkbox) {
        // Get the checkbox
        var checkBox = document.getElementById("myCheck2");
        // Get the output text
        var text = document.getElementById("text2");



        // //profit share button and display none 
        // if(checkBox.checked == true){
        //     $(".verified").prop('checked', false);
        //     $("#text").hide();
        //     // text.style.display = "none";
        // }
        // else{
        //     $(".verified").prop('checked', true);
        //     $("#text").show();
        // }
        // If the checkbox is checked, display the output text
        if (checkBox.checked == true) {
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
    }

    function profitShareCommission(checkbox) {
        // Get the checkbox
        var checkBox = document.getElementById("myCheck3");
        // Get the output text
        var text = document.getElementById("text3");

        // If the checkbox is checked, display the output text
        if (checkBox.checked == true) {
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
    }

    function globalPammStatus(checkbox) {
        // Get the checkbox
        var checkBox = document.getElementById("myCheck5");
        // Get the output text
        var text = document.getElementById("text5");

        // If the checkbox is checked, display the output text
        if (checkBox.checked == true) {
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
    }

    function manualApprovePammReg(checkbox) {
        // Get the checkbox
        var checkBox = document.getElementById("myCheck6");

    }

    //  Buttons Settings
    $(".switch-btn").change(function() {

        let objIs = $(this);
        let valueIs = null;
        let fieldName = objIs.data('columnname');
        let fieldTitle = objIs.data('fieldtitle');

        if (this.checked) {
            valueIs = 1;
        } else {
            valueIs = 0;
        }

        // Send AJAX Request

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/admin/pamm/pamm-settings-process',
            type: 'POST',
            dataType: 'json',
            data: {
                op: 'switch-enable-disable',
                columnname: fieldName,
                valueis: valueIs
            },
            success: function(data) {

                if (data.success) {

                    if (valueIs) {
                        // alert(valueIs);
                        notify('success', fieldTitle + ' is Enabled');
                        // $(".flex_verified").prop('checked', false);

                    } else {
                        notify('warning', fieldTitle + ' is Disabled');
                    }
                }
            },
            error: function(e) {
                console.log(e)
            }
        });


    });

    function handleChange(checkbox) {
        // Get the checkbox
        var checkBox = document.getElementById("myCheck");
        // Get the output text
        var text = document.getElementById("text");

        // If the checkbox is checked, display the output text
        if (checkBox.checked == true) {
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
    }

    //content show in input field
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/pamm/pamm-ready-content-process',
            type: 'POST',
            dataType: 'json',
            data: {
                page_name: 'pamm_settings'
            },
            success: function(data) {

                if (data.success) {

                    console.log(data.pageContents.manual_approve_pamm_reg);
                    // Social Trade Settings Switch

                    // Social Trade Settings Input Fields
                    $("[name=pamm_requirement]").val(data.pageContents.pamm_requirement);
                    // $("[name=pamm_global_deposit]").val(data.pageContents.pamm_global_deposit);
                    $("[name=master_limit]").val(data.pageContents.master_limit);
                    $("[name=slave_limit]").val(data.pageContents.slave_limit);
                    $("[name=pamm_account_limit]").val(data.pageContents.pamm_account_limit);
                    $("[name=profit_share_value]").val(data.pageContents.profit_share_value);
                    $("[name=minimum_profit_share_value]").val(data.pageContents.minimum_profit_share_value);
                    $("[name=maximum_profit_share_value]").val(data.pageContents.maximum_profit_share_value);
                    $("[name=profit_share_commission_value]").val(data.pageContents.profit_share_commission_value);
                    $("[name=profit_share_margin_value]").val(data.pageContents.profit_share_margin_value);
                    $("[name=minimum_deposit]").val(data.pageContents.minimum_deposit);
                    $("[name=minimum_wallet_balance]").val(data.pageContents.minimum_wallet_balance);
                    $("[name=minimum_account_balance]").val(data.pageContents.minimum_account_balance);


                } else {
                    console.log(data);
                }
            },
            error: function(e) {
                console.log(e)
            }
        });
        // Profit Share Boxes Display
        handleChange();
    });
</script>
@stop
<!-- BEGIN: page JS -->