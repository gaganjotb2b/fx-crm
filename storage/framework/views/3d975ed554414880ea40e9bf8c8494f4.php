
<?php $__env->startSection('title','IB Setting'); ?>
<?php $__env->startSection('vendor-css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')); ?>">
<!-- datatable -->
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css')); ?>">
<?php $__env->stopSection(); ?>
<!-- BEGIN: page css -->
<?php $__env->startSection('page-css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/forms/pickers/form-flat-pickr.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')); ?>">

<link rel="stylesheet" type="text/css" href="http://127.0.0.1:8000/admin-assets/app-assets/vendors/css/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/assets/css/config-form.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/assets/css/admin.css')); ?>">
<style>
    h4.card-title.float-start.d-flex {
        padding-top: 8px;
    }
</style>
<?php $__env->stopSection(); ?>
<!-- END: page css -->
<!-- BEGIN: content -->
<?php $__env->startSection('content'); ?>
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">IB <?php echo e(__('page.settings')); ?></h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('category.Home')); ?></a></li>
                                <li class="breadcrumb-item"><a href="#"><?php echo e(__('category.Settings')); ?></a></li>
                                <li class="breadcrumb-item active">IB <?php echo e(__('page.settings')); ?></li>
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
                                <h4> <?php echo e(__('ib-management.Note')); ?></h4>
                                <code class="bg-light-primary"><?php echo e(__('ib-management.please read carefully')); ?></code>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="border-start-3 border-start-primary p-1 bg-light-primary mb-1">
                                <?php echo e(__('page.sent_1')); ?>

                            </div>
                            <div class="border-start-3 border-start-info p-1 bg-light-primary mb-1">
                                <?php echo e(__('page.sent_2')); ?>

                            </div>
                            <div class="border-start-3 border-start-danger p-1 bg-light-primary mb-1">
                                <?php echo e(__('page.sent_3')); ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-7">
                    <div class="card">
                        <div class="card-header border-bottom mb-0">
                            <div class="card my-0 py-0 w-100">
                                <div class="card-body my-0 py-0">
                                    <h4 class="card-title float-start d-flex">IB <?php echo e(__('page.settings')); ?></h4>
                                    <div>
                                        <button type="button" class="btn btn-primary me-3 float-end" id="create-all-permission">Create All</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--add ib form -->
                        <div class="card mt-0 mb-0">
                            <div class="col-12">
                                <form action="<?php echo e(route('admin.settings.ib_setting.update')); ?>" class="mt-2 pt-50" method="POST" enctype="multipart/form-data" id="ib-setting-update-form">
                                    <?php echo csrf_field(); ?>
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
                        <!--/add ib  form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<!-- BEGIN: vendor JS -->
<?php $__env->startSection('vendor-js'); ?>
<?php $__env->stopSection(); ?>
<!-- END: vendor JS -->
<!-- BEGIN: Page vendor js -->
<?php $__env->startSection('page-vendor-js'); ?>
<script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/forms/select/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/extensions/sweetalert2.all.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/forms/cleave/cleave.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<!-- END: page vendor js -->
<!-- BEGIN: page JS -->
<?php $__env->startSection('page-js'); ?>
<!-- datatable -->
<script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js')); ?>"></script>
<script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')); ?>"></script>
<!-- admin settings common ajax -->
<script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/admin-settings.js')); ?>"></script>
<script src="<?php echo e(asset('common-js/ib-settings.js')); ?>"></script>
<script>
    //add ib setting callback
    function ibSettingAddCallBack(data) {
        if (data.success) {
            notify('success', data.message, 'IB Setting');
            location.reload();
        } else {
            notify('error', 'Insertion Failed!', 'IB Setting');
            $.validator("ib-setting-add-form", data.errors);
        }
    }
    //ib setting update callback
    function ibSettingUpdateCallBack(data) {
        if (data.success) {
            notify('success', data.message, 'IB Setting');
        } else {
            notify('error', 'Failed To Update!', 'IB Setting');
            $.validator("ib-setting-update-form", data.errors);
        }
    }
</script>
<?php $__env->stopSection(); ?>
<!-- BEGIN: page JS -->
<?php echo $__env->make('layouts.system-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/systems/configurations/ib-settings.blade.php ENDPATH**/ ?>