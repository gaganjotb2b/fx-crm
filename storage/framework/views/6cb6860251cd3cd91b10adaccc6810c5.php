
<?php $__env->startSection('title', 'Open Demo Trading Account'); ?>
<?php $__env->startSection('page-css'); ?>
    <?php if(App\Services\systems\VersionControllService::check_version() === 'lite'): ?>
        <link id="pagestyle" href="<?php echo e(asset('trader-assets/assets/css/soft-ui-dashboard.css?v=1.0.8')); ?>" rel="stylesheet" />
    <?php endif; ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('trader-assets/assets/css/datatables.min.css')); ?>" />
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/vendors/css/file-uploaders/dropzone.min.css')); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin-assets/app-assets/css/plugins/forms/form-file-uploader.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('bread_crumb'); ?>

    <?php use App\Services\AllFunctionService; ?>
    <!-- bread crumb -->
    <?php echo App\Services\systems\BreadCrumbService::get_trader_breadcrumb(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 text-center">
                <h3 class="mt-5"><?php echo e(__('page.open-demo-account')); ?></h3>
                <h5 class="text-secondary font-weight-normal">
                    <?php echo e(__('page.this-information-will-let-us-know-more-about-you')); ?>

                </h5>
                <div class="multisteps-form mb-5">
                    <!--progress bar-->
                    <div class="row">
                        <div class="col-12 col-lg-8 mx-auto my-5">
                            <div class="multisteps-form__progress">
                                <!-- progress open account -->
                                <button class="multisteps-form__progress-btn js-active" type="button"
                                    title="Open account form">
                                    <span><?php echo e(__('page.open-account')); ?></span>
                                </button>
                                <!-- progress information -->
                                <button class="multisteps-form__progress-btn" type="button" title="account info" disabled>
                                    <span><?php echo e(__('page.information')); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!--form panels-->
                    <div class="row">
                        <div class="col-12 col-lg-8 m-auto">
                            <div class="multisteps-form__form bg-custom-dark-for rounded-3">
                                <!--single form panel-->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white js-active"
                                    data-animation="FadeIn">
                                    <div class="row text-center">
                                        <!-- first step heading adn description -->
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">
                                                <?php echo e(__('page.let\'s-start-with-the-platform-information')); ?>

                                            </h5>
                                            <p><?php echo e(__('page.please-first-choose-a-server-or-platform-then-choose-an-account-type-and-finaly-choose-leverage-if-you-need-any-help-contact-our-help-desk')); ?>

                                            </p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content ">
                                        <div class="row mt-3 bg-custom-dark-for">
                                            <!-- first step platform logo -->
                                            <div class="col-12 col-sm-4">
                                                <div class="avatar avatar-xxl position-relative">
                                                    <?php
                                                        $platform_logo =
                                                            get_platform() == 'mt4' ? 'mt4.png' : 'mt5.png';
                                                    ?>
                                                    <img id="platform-logo"
                                                        src="<?php echo e(asset('trader-assets/assets/img/logos/platform-logo/' . $platform_logo)); ?>"
                                                        class="border-radius-md" alt="team-2">
                                                </div>
                                            </div>
                                            <!-- first step form -->
                                            <div class="col-12 col-sm-6 mx-auto mt-4 mt-sm-0 text-start">
                                                <!-- check kyc required or not -->
                                                <?php if(\App\Services\AllFunctionService::kyc_required(auth()->user()->id, 'open-account') == false): ?>
                                                    <form class="form-demo"
                                                        action="<?php echo e(route('user.trading-account.open-demo-account-form')); ?>"
                                                        method="post" id="demo-account-form">
                                                        <?php echo csrf_field(); ?>
                                                        
                                                        
                                                        
                                                        
                                                        <?php if (isset($component)) { $__componentOriginal126bf795543a5dc9be01b32ec7b7fadb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal126bf795543a5dc9be01b32ec7b7fadb = $attributes; } ?>
<?php $component = App\View\Components\PlatformOption::resolve(['accountType' => 'demo','useFor' => 'user_portal'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('platform-option'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PlatformOption::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal126bf795543a5dc9be01b32ec7b7fadb)): ?>
<?php $attributes = $__attributesOriginal126bf795543a5dc9be01b32ec7b7fadb; ?>
<?php unset($__attributesOriginal126bf795543a5dc9be01b32ec7b7fadb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal126bf795543a5dc9be01b32ec7b7fadb)): ?>
<?php $component = $__componentOriginal126bf795543a5dc9be01b32ec7b7fadb; ?>
<?php unset($__componentOriginal126bf795543a5dc9be01b32ec7b7fadb); ?>
<?php endif; ?>

                                                        <div class="form-group">
                                                            <label for="client-group"><?php echo e(__('page.account-type')); ?></label>
                                                            <select class="form-control multisteps-form__input "
                                                                id="client-group" name="account_type">
                                                                <option value="">
                                                                    <?php echo e(__('page.choose-an-account-type')); ?>

                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="leverage"><?php echo e(__('page.leverage')); ?></label>
                                                            <select class="form-control multisteps-form__input"
                                                                id="leverage" name="leverage">
                                                                <option value=""><?php echo e(__('page.choose-a-leverage')); ?>

                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="button" data-label="Submit Request"
                                                                id="btn-submit-request" data-btnid="btn-submit-request"
                                                                data-callback="open_demo_account_call_back"
                                                                data-loading="<i class='fa-spin fas fa-circle-notch'></i>"
                                                                data-form="demo-account-form" data-el="fg"
                                                                onclick="_run(this)"
                                                                class="btn bg-gradient-primary ms-auto float-end mb-3 mt-4 w-50"><?php echo e(__('page.submit-request')); ?></button>
                                                            <button
                                                                class="btn bg-gradient-dark ms-auto mb-0 js-btn-next visually-hidden"
                                                                type="button" title="Next"
                                                                id="js-btn-next"><?php echo e(__('page.next')); ?></button>
                                                        </div>
                                                    </form>
                                                <?php else: ?>
                                                    <!-- warning kyc required -->
                                                    <div class="col-8 mx-auto">
                                                        <div class="alert alert-warning" role="alert">
                                                            <strong>Warning!</strong> KYC Required for Open account
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--account information -->
                                <div class="card multisteps-form__panel p-3 border-radius-xl bg-white"
                                    data-animation="FadeIn">
                                    <div class="row text-center">
                                        <div class="col-10 mx-auto">
                                            <h5 class="font-weight-normal">
                                                <?php echo e(__('page.your-account-information')); ?>(<?php echo e(__('save-it\'s')); ?>)
                                            </h5>
                                            <p><?php echo e(__('page.please-save-your-account-information-in-a-save-zone-its-need-to-access-your-account-its-also-need-to-make-your-transaction-or-other-criteria')); ?>

                                            </p>
                                        </div>
                                    </div>
                                    <div class="multisteps-form__content">
                                        <div class="row mt-4">
                                            <div class="col-8 mx-auto">
                                                <div class="form-control multisteps-form__input" contentEditable="true"
                                                    id="copy_account_info" rows="10" autofucus=>
                                                    <table class="table text-start">
                                                        <tr>
                                                            <th><?php echo e(__('page.account-no')); ?>: </th>
                                                            <td id="c-account-no"></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__('page.master-password')); ?>: </th>
                                                            <td id="c-masster-pass"></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__('page.phone-password')); ?>: </th>
                                                            <td id="c-phone-pass"></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__('page.investor-password')); ?>: </th>
                                                            <td class="border-bottom" id="c-investor-pass"></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <button class="btn bg-gradient-primary ms-auto mt-3 float-end"
                                                    id="btn-copy">
                                                    <i class="fas fa-copy"></i>
                                                    <?php echo e(__('page.copy-info')); ?>

                                                </button>
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
        <!-- footer section -->
        <!-- include footer -->
        <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('corejs'); ?>
    <script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/file-uploaders/dropzone.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-js'); ?>
    <script src="<?php echo e(asset('trader-assets/assets/js/plugins/multistep-form.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/scripts/pages/get-client-group.js')); ?>"></script>
    <script src="<?php echo e(asset('trader-assets/assets/js/scripts/pages/file-upload-with-form.js')); ?>"></script>
    <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/submit-wait.js')); ?>"></script>
    <script src="<?php echo e(asset('/common-js/copy-js.js')); ?>"></script>
    <script>
        $("#demo-account-form").trigger("reset");
        $(document).on('click', '#btn-submit-request', function() {
            $(this).prop('disabled', true);
        })
        // open demo trading account--------------
        function open_demo_account_call_back(data) {
            if (data.status == true) {
                notify('success', data.message, 'Open demo trading account');
                $("#demo-account-form").trigger("reset");
                $("#js-btn-next").trigger('click');
                $("#c-account-no").text(data.account_no);
                $("#c-investor-pass").text(data.inv_password);
                $("#c-masster-pass").text(data.master_password);
                $("#c-phone-pass").text(data.phone_password);
            }
            if (data.status == false) {
                notify('error', data.message, 'Open demo trading account');
            }
            $('#btn-submit-request').prop('disabled', false);
            $.validator("demo-account-form", data.errors);

        }
        // auto fucus
        $(window).on("load", function() {
            $("#copy_account_info").focus();
        });
        // copy content from editable div
        $(document).on("click", "#btn-copy", function() {
            // copy js
            copy_to_clipboard("copy_account_info"); //provide id of text container
        });
        // onload server-------------------
        $(document).ready(function() {
            let server = $("#server").val();
            // get client js
            get_client_group(server, 'demo', 'server');
        });
        // change server-------------------
        $(document).on("change", "#server", function() {
            let server = $(this).val();
            get_client_group(server, 'demo', 'server');
        });

        // change account type/client group
        $(document).on("change", "#client-group", function() {
            let client_group = $(this).val();
            get_client_group(server, 'demo', 'client-group', client_group);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(App\Services\systems\VersionControllService::get_layout('trader'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/traders/trading-account/open-demo-trading-account.blade.php ENDPATH**/ ?>