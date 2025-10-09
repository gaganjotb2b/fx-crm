@extends(App\Services\systems\AdminLayoutControllService::admin_layout())
@section('title','Trader Setting')
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
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/admin.css') }}">
<style>
    h4.card-title.float-start.d-flex {
        padding-top: 8px;
    }

    form#trader-setting-add-form {
        min-height: 300px;
    }
</style>
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
                        <h2 class="content-header-title float-start mb-0">{{__('page.trader')}} {{__('page.settings')}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('category.Home')}}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{__('category.Settings')}}</a></li>
                                <li class="breadcrumb-item active">{{__('page.trader')}} {{__('page.settings')}}</li>
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
                <div class="col-12 col-sm-5">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4> {{__('ib-management.Note')}}</h4>
                                <code class="bg-light-primary">{{__('ib-management.please read carefully')}}</code>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="border-start-3 border-start-primary p-1 bg-light-primary mb-1">
                                {{__('page.sent_1')}}
                            </div>
                            <div class="border-start-3 border-start-info p-1 bg-light-primary mb-1">
                                {{__('page.sent_2')}}
                            </div>
                            <div class="border-start-3 border-start-danger p-1 bg-light-primary mb-1">
                                {{__('page.sent_3')}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-7">
                    @if(Auth::user()->hasDirectPermission('edit trader setting'))
                    <div class="card">
                        <div class="card-header border-bottom mb-0">
                            <div class="card my-0 py-0 w-100">
                                <div class="card-body my-0 py-0 d-flex justify-content-between">
                                    <h4 class="card-title float-start d-flex">{{__('page.trader')}} {{__('page.settings')}}</h4>
                                    @if(system_disable('trader'))
                                    <div>
                                        <button type="button" class="btn btn-primary me-3" id="create-all-permission">Create All</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--add trader form -->
                        <div class="card mt-0 mb-0">
                            <div class="col-12">
                                <!-- trader kyc requirement permission -->
                                <div class="demo-inline-spacing p-3 pb-0">
                                    <?php
                                        $deposit = $withdraw = $account = '';
                                        if ($kyc_setup) {
                                            $deposit = ($kyc_setup->deposit) ? 'checked' : '' ;
                                            $withdraw = ($kyc_setup->withdraw) ? 'checked' : '' ;
                                            $account = ($kyc_setup->open_account) ? 'checked' : '' ;
                                        }
                                    ?>
                                    <div class="form-check form-check-primary">
                                        <input type="checkbox" class="form-check-input kyc-required" data-type="deposit" id="deposit" name="deposit" {{$deposit}}/>
                                        <label class="form-check-label" for="deposit">KYC Required for Deposit</label>
                                    </div>
                                    <div class="form-check form-check-secondary">
                                        <input type="checkbox" class="form-check-input kyc-required" data-type="withdraw" id="withdraw" name="withdraw" {{$withdraw}}/>
                                        <label class="form-check-label" for="withdraw">KYC Required for Withdraw</label>
                                    </div>
                                    <div class="form-check form-check-success">
                                        <input type="checkbox" class="form-check-input kyc-required" data-type="open_account" name="open_account" id="open-account" {{$account}}/>
                                        <label class="form-check-label" for="open-account">KYC Required for Open Account</label>
                                    </div>
                                </div>
                                <!-- trader permission create -->
                                <form action="{{ route('admin.settings.trader_setting.update') }}" class="" method="POST" enctype="multipart/form-data" id="trader-setting-update-form">
                                    @csrf
                                    <div class="card-body py-2 my-25">
                                        <div class="card mb-0">
                                            <div class="card-body">
                                                <div class="row table-responsive">
                                                    <table class="datatables-ajax table w-100">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 100px;">Action</th>
                                                                <th>Title</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--/add trader  form -->
                    </div>
                    @else
                    <div class="col-12 col-sm-7">
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
<!-- admin settings common ajax -->
<script src="{{asset('admin-assets/app-assets/js/scripts/pages/admin-settings.js')}}"></script>

<script src="{{asset('admin-assets/app-assets/js/scripts/forms/form-select2.js')}}"></script>
<script src="{{asset('common-js/trader-settings.js')}}" type="text/javascript"></script>
<script>
    $(document).on('change', '.kyc-required', function() {

        let type = $(this).data('type')
        let value = ($(this).is(":checked")) ? 1 : 0;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/admin/settings/kyc-required/for',
            method: 'POST',
            dataType: 'JSON',
            data: {
                status: value,
                type: type
            },
            success: function(data) {
                if (data.status) {
                    notify('success', data.message, 'KYC Setup');
                } else {
                    notify('error', data.message, 'KYC Setup');
                }
            }
        })
    })
</script>
@stop
<!-- BEGIN: page JS -->