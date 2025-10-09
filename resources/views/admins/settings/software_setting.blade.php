@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Software Setting')
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
@stop
<!-- BEGIN: page css -->
@section('page-css')
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin-assets/assets/css/config-form.css')}}">
@stop
<!-- END: page css -->
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
                        <h2 class="content-header-title float-start mb-0">{{__('admin-menue-left.Software_Settings')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('category.Settings')}}</a></li>
                                <li class="breadcrumb-item active">{{__('admin-menue-left.Software_Settings')}}</li>
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
                <div class="col-5">
                    <div class="content-body">
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
                                    {{__('admin_softawre_settings.This is a one-time setup')}}
                                </div>
                                <div class="border-start-3 border-start-info p-1 bg-light-primary mb-1">
                                    CRM settings, social login, password settings means your admin Level will can set permission.
                                </div>
                                <div class="border-start-3 border-start-danger p-1 bg-light-primary mb-1">
                                    Admin can will change and set permission on crm settings crm type, meta account platform book, social account, account limit and also social login settings like facebook,google,mac then last password settings.
                                </div>
                                <div class="border-start-3 border-start-success p-1 bg-light-primary mb-1">
                                    CRM Settings when an Admin can Change CRM Settings
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <div class="card">
                        <div class="card-header border-bottom mb-0">
                            <div class="card my-0 py-0 w-100">
                                <div class="card-body my-0 py-0">
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">CRM Settings</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Login Settings</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-acount-tab" data-bs-toggle="pill" data-bs-target="#pills-acount" type="button" role="tab" aria-controls="pills-acount" aria-selected="false">Account Password</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-transaction-tab" data-bs-toggle="pill" data-bs-target="#pills-transaction" type="button" role="tab" aria-controls="pills-transaction" aria-selected="false">Transaction Setting</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <div class="card p-0">
                                                <!-- sofware setting form -->
                                                <form action="{{route('admin.settings.software_setting')}}" method="POST" enctype="multipart/form-data" id="software-setting-form">
                                                    @csrf
                                                    <div class="card-body p-0">
                                                        <div class="card">
                                                            <div class="card-body p-0">
                                                                <div class="row">
                                                                    <!-- crm type -->
                                                                    <!-- create meta account when signup type  -->
                                                                    <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                        <div class="card-body pb-0 social-media-card">
                                                                            <label class="form-label">{{__('page.soft_1')}}</label>
                                                                            <div class="social-media-filter border">
                                                                                <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Create meta account with signup">
                                                                                    <input type="checkbox" class="form-check-input input-filter" name="create_meta_acc" id="create_meta_acc" data-value="create_meta_acc" <?php echo (($configs->create_meta_acc == 1) ?  'checked' : ''); ?> />
                                                                                    <label class="form-check-label" for="create_meta_acc">{{__('page.soft_1')}}</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Platform Book -->
                                                                    <div class="col-12 col-sm-6 mb-1">
                                                                        <label class="form-label" for="type">{{__('page.platform')}} {{__('group-setting.Book')}}</label>
                                                                        <select id="platform_book" class="select2 form-select" name="platform_book">
                                                                            <option value="">{{__('page.platform')}} {{__('group-setting.Book')}}</option>
                                                                            <option value="A Book" <?php echo ((isset($configs->platform_book) && strtolower($configs->platform_book) === 'a book') ?  'selected="selected"' : '') ?>>A Book</option>
                                                                            <option value="B Book" <?php echo ((isset($configs->platform_book) && strtolower($configs->platform_book) === 'b book') ?  'selected="selected"' : '') ?>>B Book</option>
                                                                        </select>
                                                                    </div>
                                                                    <!-- Social Accounts Required  -->
                                                                    <div class="col-12 col-sm-6 mb-1" style="float: left;">
                                                                        <div class="card-body pb-0 social-media-card">
                                                                            <label class="form-label">{{__('page.soft_2')}}</label>
                                                                            <div class="social-media-filter border">
                                                                                <div class="form-check form-check-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Social Accounts Required">
                                                                                    <input type="checkbox" class="form-check-input input-filter" name="social_account" id="social_account" data-value="social_account" <?php echo (($configs->social_account == 1) ?  'checked' : ''); ?> />
                                                                                    <label class="form-check-label" for="social_account">{{__('page.soft_2')}}</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-sm-6 mb-1">
                                                                        <label class="form-label" for="acc_limit">{{__('page.account')}} {{__('page.limit')}}</label>
                                                                        <input type="number" class="form-control" id="acc_limit" name="acc_limit" placeholder="0" value="<?php echo (isset($configs->acc_limit) ? $configs->acc_limit : ''); ?>" />
                                                                    </div>

                                                                </div>
                                                                <div class="row">
                                                                    <input type="hidden" name="config_id" value="<?= (isset($configs->id) ? $configs->id : '') ?>">
                                                                    <div class="col-12">
                                                                        <label class="form-label">&nbsp;</label>
                                                                        <div>
                                                                            @if(Auth::user()->hasDirectPermission('create software settings'))
                                                                            <button type="submit" class="btn btn-primary" style="float: right">{{__('page.save-change')}}</button>
                                                                            @else

                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!--/sofware setting form -->
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                            <div class="row gx-3 align-items-center">
                                                <div class="col-md-7" style="border-right: 1px solid var(--custom-primary)">
                                                    <div class="card p-0 m-0">

                                                        <div class="card-body p-0">
                                                            <div class="title-wrapper d-flex">
                                                                <div class="d-flex flex-column float-start">
                                                                    <div class="form-check form-switch form-check-primary">
                                                                        <input type="checkbox" class="social_login_settings form-check-input" id="facebook_login" name="facebook_login" <?php echo ($social_logins->facebook == 1) ? 'checked' : ''; ?> />
                                                                        <label class="form-check-label" for="facebook_login">
                                                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <p class="todo-title"><i class="fab fa-facebook-f"></i> Facebook Login</p>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="title-wrapper d-flex">
                                                                <div class="d-flex flex-column float-start">
                                                                    <div class="form-check form-switch form-check-primary">
                                                                        <input type="checkbox" class="social_login_settings form-check-input" id="google_login" name="google_login" <?php echo ($social_logins->google == 1) ? 'checked' : ''; ?> />
                                                                        <label class="form-check-label" for="google_login">
                                                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <p class="todo-title">Google Login</p>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="title-wrapper d-flex">
                                                                <div class="d-flex flex-column float-start">
                                                                    <div class="form-check form-switch form-check-primary">
                                                                        <input type="checkbox" class="social_login_settings form-check-input" id="mac_login" name="mac_login" <?php echo ($social_logins->mac == 1) ? 'checked' : ''; ?> />
                                                                        <label class="form-check-label" for="mac_login">
                                                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <p class="todo-title">Mac Login</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="card p-0 m-0" style="border:1px solid var(--custom-primary);padding:13px 7px 0 7px !important">
                                                        <div class="card-body p-0">
                                                            <div class="title-wrapper d-flex">
                                                                <div class="d-flex flex-column float-start">
                                                                    <div class="form-check form-switch form-check-primary">
                                                                        <input type="checkbox" class="social_login_settings form-check-input" id="all_social" name="all_social" <?php echo ($social_logins->facebook == 1 && $social_logins->google == 1 && $social_logins->mac == 1) ? 'checked' : ''; ?> />
                                                                        <label class="form-check-label" for="all_social">
                                                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <p class="todo-title">All Social Login Active</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7 mt-3">
                                                    <div class="card-body p-0">
                                                        <div class="title-wrapper d-flex">
                                                            <div class="d-flex flex-column float-start">
                                                                <div class="form-check form-switch form-check-primary">
                                                                    <input type="checkbox" class="social_login_settings  form-check-input" id="brute_force_attack" name="brute_force_attack" @if (isset($configs->brute_force_attack) AND $configs->brute_force_attack == 1)
                                                                    checked
                                                                    @else
                                                                    ''
                                                                    @endif
                                                                    />
                                                                    <label class="form-check-label" for="brute_force_attack">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <p class="todo-title">Brute Force Attack</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-acount" role="tabpanel" aria-labelledby="pills-acount-tab">
                                            <div class="row gx-3 align-items-center">
                                                <div class="col-md-7" style="border-right: 1px solid var(--custom-primary)">
                                                    <div class="card p-0 m-0">

                                                        <div class="card-body p-0">
                                                            <div class="title-wrapper d-flex">
                                                                <div class="d-flex flex-column float-start">
                                                                    <div class="form-check form-switch form-check-primary">
                                                                        <input type="checkbox" class=" password_settings form-check-input" id="master" name="master" <?php echo ($password_settings->master_password == 1) ? 'checked' : ''; ?> />
                                                                        <label class="form-check-label" for="master">
                                                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <p class="todo-title"><i class="fab fa-facebook-f"></i> Master Password</p>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="title-wrapper d-flex">
                                                                <div class="d-flex flex-column float-start">
                                                                    <div class="form-check form-switch form-check-primary">
                                                                        <input type="checkbox" class=" password_settings form-check-input" id="investor" name="investor" <?php echo ($password_settings->investor_password == 1) ? 'checked' : ''; ?> />
                                                                        <label class="form-check-label" for="investor">
                                                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <p class="todo-title">Investor Password</p>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="title-wrapper d-flex">
                                                                <div class="d-flex flex-column float-start">
                                                                    <div class="form-check form-switch form-check-primary">
                                                                        <input type="checkbox" class="password_settings form-check-input" id="leverage" name="leverage" <?php echo ($password_settings->leverage == 1) ? 'checked' : ''; ?>/>
                                                                        <label class="form-check-label" for="leverage">
                                                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <p class="todo-title">Leverage</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="card p-0 m-0" style="border:1px solid var(--custom-primary);padding:13px 7px 0 7px !important">
                                                        <div class="card-body p-0">
                                                            <div class="title-wrapper d-flex">
                                                                <div class="d-flex flex-column float-start">
                                                                    <div class="form-check form-switch form-check-primary">
                                                                        <input type="checkbox" class=" password_settings form-check-input" id="all_password" name="all_password" <?php echo ($password_settings->master_password == 1 && $password_settings->investor_password == 1 && $password_settings->leverage == 1) ? 'checked' : ''; ?>/>
                                                                        <label class="form-check-label" for="all_password">
                                                                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <p class="todo-title">All Password Active</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Transaction Settings -->
                                        <div class="tab-pane fade" id="pills-transaction" role="tabpanel" aria-labelledby="pills-transaction-tab">
                                            <div class="row gx-3 align-items-center">
                                                <div class="col-md-6" style="border-right: 1px solid var(--custom-primary)">
                                                    <div class="card p-0 m-0">
                                                        <div class="card-body p-0">
                                                            <div class="title-wrapper d-flex">
                                                                <p class="todo-title">CRM / Account Deposit</p>
                                                            </div>
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" class="deposit_setting form-check-input" id="deposit_setting" name="deposit_setting" <?php echo ($softwareSettings->direct_deposit === "account") ? 'checked' : ''; ?> />
                                                                <label class="form-check-label" for="deposit_setting">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                            <p class="todo-title">CRM / Account Deposit</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="border-right: 1px solid var(--custom-primary)">
                                                    <div class="card p-0 m-0">
                                                        <div class="card-body p-0">
                                                            <div class="title-wrapper d-flex">
                                                                <p class="todo-title">Crypto Deposit Manual/Auto</p>
                                                            </div>
                                                            <div class="form-check form-switch form-check-primary">
                                                                <?php $unique_id = 'crypto_deposit_setting_' . uniqid(); ?>
                                                                <input type="checkbox"
                                                                       class="crypto_deposit_setting form-check-input"
                                                                       id="<?= $unique_id ?>"
                                                                       name="<?= $unique_id ?>"
                                                                       <?= ($softwareSettings->crypto_deposit === "auto") ? 'checked' : ''; ?> />
                                                                
                                                                <label class="form-check-label" for="<?= $unique_id ?>">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                            <p class="todo-title">Crypto Deposit Manual/Auto</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card p-0 m-0">
                                                        <div class="card-body p-0">
                                                            <div class="title-wrapper d-flex">
                                                                <p class="todo-title">Withdraw</p>
                                                            </div>
                                                            <div class="form-check form-switch form-check-primary">
                                                                <input type="checkbox" class="withdraw_setting form-check-input" id="withdraw_setting" name="withdraw_setting" <?php echo ($softwareSettings->direct_withdraw === "account") ? 'checked' : ''; ?> />
                                                                <label class="form-check-label" for="withdraw_setting">
                                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                </label>
                                                            </div>
                                                            <p class="todo-title">CRM / Account Withdraw</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@stop
<!-- BEGIN: vendor JS -->
@section('vendor-js')
@stop
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
@section('page-vendor-js')
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')}}"></script>
@stop
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
@section('page-js')
<!-- datatable -->
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
<script src="{{asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
<!-- toaster js -->
<script src="{{ asset('admin-assets/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<!-- <script src="{{asset('admin-assets/app-assets/js/scripts/pages/page-account-settings-account.js')}}"></script> -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-page-configuration.js')}}"></script>
<!-- form hide/show -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-config-form.js')}}"></script>
<script>
    $('#all_social[type="checkbox"]').click(function() {
        if ($(this).is(":checked")) {
            $('.social_login_settings ').prop('checked', true);
        } else if ($(this).is(":not(:checked)")) {
            $('.social_login_settings ').prop('checked', false);
        }
    });
    // social login 
    $(document).on('change', '.social_login_settings', function(event) {
        let check_value = ($(this).prop("checked") == true) ? 1 : 0;
        let name = ($(this).attr("name"));
        $(this).confirm2({
            request_url: '/admin/social_login/' + name + '/' + check_value,
            click: false,
            title: ($(this).prop("checked") == true) ? 'All social login activated' : 'All social login disabled',
            message: 'Are you confirm to activated all social login ?',
            button_text: 'Enable',
            method: 'POST'
        }, function(data) {
                if (data.status) {
                    notify('success', data.message, 'Socail Login');
                } else {
                    notify('error', data.message, 'Socail Login');
                }

        });
    });

    // passowrd change 
    $('#all_password[type="checkbox"]').click(function() {
        if ($(this).is(":checked")) {
            $('.password_settings').prop('checked', true);
        } else if ($(this).is(":not(:checked)")) {
            $('.password_settings').prop('checked', false);
        }
    });


    $(document).on('change', '.password_settings', function(event) {
        let check_value = ($(this).prop("checked") == true) ? 1 : 0;
        let name = ($(this).attr("name"));
        $(this).confirm2({
            request_url: '/admin/password_settings/' + name + '/' + check_value,
            click: false,
            title: ($(this).prop("checked") == true) ? 'All password activated' : 'All password disabled',
            message: 'Are you confirm to activated all password ?',
            button_text: 'Enable',
            method: 'POST'
        }, function(data) {
                if (data.status) {
                    notify('success', data.message, 'Password Setting');
                } else {
                    notify('error', data.message, 'Password Setting');
                }

        });
    });

    //Transaction Deposit Setting
    $(document).on('change', '.deposit_setting', function(event) {
        let check_value = ($(this).prop("checked") == true) ? 'account' : 'wallet';
        // console.log(check_value);
        $(this).confirm2({
            request_url: '/admin/transaction_settings/deposit-settings',
            data: {
                status: check_value
            },
            click: false,
            title: ($(this).prop("checked") == true) ? 'Auto crypto deposit' : 'Manual crypto deposit',
            message: 'Are you confirm to Enable ' + check_value + ' deposit?',
            button_text: 'Enable',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Deposit settings');
            } else {
                notify('error', data.message, 'Desosit settings');
            }

        });
    });
    //crypto Deposit Setting
    $(document).on('change', '.crypto_deposit_setting', function(event) {
        let crypto_deposit = ($(this).prop("checked") == true) ? 'auto' : 'manual';
        // console.log(crypto_deposit);
    
        $(this).confirm2({
            request_url: '/admin/transaction_settings/crypto-deposit-settings',
            data: {
                crypto_deposit: crypto_deposit
            },
            click: false,
            title: ($(this).prop("checked") == true) ? 'Auto crypto deposit' : 'Manual crypto deposit',
            message: 'Are you sure you want to enable ' + crypto_deposit + ' crypto deposit?',
            button_text: 'Enable',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Crypto deposit settings');
            } else {
                notify('error', data.message, 'Crypto xeposit settings');
            }
        });
    });

    //Transaction Withdraw Setting
    $(document).on('change', '.withdraw_setting', function(event) {
        let check_value = ($(this).prop("checked") == true) ? 'account' : 'wallet';
        // console.log(check_value);
        $(this).confirm2({
            request_url: '/admin/transaction_settings/withdraw-settings',
            data: {
                status: check_value
            },
            click: false,
            title: ($(this).prop("checked") == true) ? 'Enable account withdraw' : 'Enable wallet withdraw',
            message: 'Are you confirm to Enable ' + check_value + ' withdraw?',
            button_text: 'Enable',
            method: 'POST'
        }, function(data) {
            if (data.status == true) {
                notify('success', data.message, 'Deposit settings');
            } else {
                notify('error', data.message, 'Desosit settings');
            }

        });
    });
</script>
@stop
<!-- BEGIN: page JS -->