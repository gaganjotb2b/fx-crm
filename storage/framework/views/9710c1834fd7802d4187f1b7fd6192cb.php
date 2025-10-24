
<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('vendor-css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/editors/quill/katex.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/editors/quill/monokai-sublime.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/editors/quill/quill.snow.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/editors/quill/quill.bubble.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/forms/select/select2.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/animate/animate.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/vendors/css/extensions/sweetalert2.min.css')); ?>">
<?php $__env->stopSection(); ?>
<!-- BEGIN: page css -->
<?php $__env->startSection('page-css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/forms/form-quill-editor.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/extensions/ext-component-sweet-alerts.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/css/plugins/forms/form-validation.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin-assets/app-assets/fonts/font-awesome/css\font-awesome.css')); ?>">
<?php $__env->stopSection(); ?>
<!-- END: page css -->
<!-- BEGIN: content -->
<?php $__env->startSection('content'); ?>
<!-- BEGIN: Content-->

<?php
use App\Services\AllFunctionService;
$all_fun = new AllFunctionService();

?>



<div class="app-content content dash-admin-tbl-color">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Ecommerce Starts -->
            <section id="dashboard-ecommerce">
                <div class="row match-height">
                    <!-- Medal Card -->
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="card card-congratulation-medal">
                            <div class="card-body">
                                <h5><?php echo e(__('page.Today_Deposit')); ?></h5>
                                <p class="card-text font-small-3"><?php echo e(__('page.all')); ?> <?php echo e(__('page.trader')); ?>

                                    <?php echo e(__('page.deposit')); ?>

                                </p>
                                <h3 class="mb-75 mt-2 pt-50">
                                    <a href="#">$ <?php echo e($all_fun->today_deposit()); ?></a>
                                </h3>
                                <a href="<?php echo e(route('admin.manage.deposit')); ?>" class="btn btn-primary btn_prop_disabled"><?php echo e(__('page.view')); ?>

                                    <?php echo e(__('page.deposit')); ?></a>
                                <img src="<?php echo e(asset('admin-assets/app-assets/images/illustration/badge.svg')); ?>" class="congratulation-medal" alt="Medal Pic" />
                            </div>
                        </div>
                    </div>
                    <!--/ Medal Card -->

                    <!-- Statistics Card -->
                    <div class="col-xl-8 col-md-6 col-12">
                        <div class="card card-statistics">
                            <div class="card-header">
                                <h4 class="card-title"><?php echo e(__('page.Statistics')); ?></h4>
                                <div class="d-flex align-items-center">
                                    <p class="card-text font-small-2 me-25 mb-0" id="al_reload_time">Updated 1 seconds
                                        ago</p>
                                </div>
                            </div>
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-primary me-2">
                                                <div class="avatar-content">
                                                    <i data-feather="trending-up" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0"><?php echo e($all_fun->all_trader()); ?></h4>
                                                <p class="card-text font-small-3 mb-0"><?php echo e(__('page.total-trader')); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-info me-2">
                                                <div class="avatar-content">
                                                    <i data-feather="user" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0"><?php echo e($all_fun->total_ib()); ?></h4>
                                                <p class="card-text font-small-3 mb-0"><?php echo e(__('page.total_ib')); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-danger me-2">
                                                <div class="avatar-content">
                                                    <i data-feather="box" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0">$ <?php echo e($all_fun->total_deposit()); ?></h4>
                                                <p class="card-text font-small-3 mb-0"><?php echo e(__('page.total_deposit')); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-success me-2" style="width: 48px; height: 48px;">
                                                <div class="avatar-content">
                                                    <i data-feather="dollar-sign" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0">$ <?php echo e($all_fun->total_withdraw()); ?></h4>
                                                <p class="card-text font-small-3 mb-0"><?php echo e(__('page.total_withdraw')); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Statistics Card -->
                </div>

                <div class="row match-height">
                    <div class="col-lg-4 col-12">
                        <div class="row match-height">
                            <!-- Bar Chart - Orders -->
                            <div class="col-lg-6 col-md-3 col-6">
                                <a href="<?php echo e(route('admin.manage.withdraw')); ?>">
                                    <div class="card">
                                        <div class="card-body pb-50">
                                            <h6><?php echo e(__('page.pending')); ?> <?php echo e(__('page.withdraw')); ?></h6>
                                            <h2 class="fw-bolder mb-1">$ <?php echo e($all_fun->pending_withdraw()); ?></h2>
                                            <div id="statistics-order-chart"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!--/ Bar Chart - Orders -->

                            <!-- Line Chart - Profit -->
                            <div class="col-lg-6 col-md-3 col-6">
                                <a href="<?php echo e(route('admin.manage.deposit')); ?>">
                                    <div class="card card-tiny-line-stats">
                                        <div class="card-body pb-50">
                                            <h6><?php echo e(__('page.pending')); ?> <?php echo e(__('page.deposit')); ?></h6>
                                            <h2 class="fw-bolder mb-1">$ <?php echo e($all_fun->pending_deposit()); ?></h2>
                                            <div id="statistics-profit-chart"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!--/ Line Chart - Profit -->

                            <!-- Earnings Card -->
                            <div class="col-lg-12 col-md-6 col-12">
                                <a href="<?php echo e(route('admin.ib-commission.report')); ?>">
                                    <div class="card earnings-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h4 class="card-title mb-1"><?php echo e(__('page.ib_commission')); ?></h4>
                                                    <div class="font-small-2">This Month</div>
                                                    <?php
                                                    $commission_chart_data = json_decode($commission_chart['chart_data']);
                                                    if ($commission_chart_data[0] + $commission_chart_data[1] + $commission_chart_data[2] == 0) {
                                                    $commisstion_percent = 0;
                                                    } else {
                                                    $commisstion_percent = round(($commission_chart_data[2] * 100) / ($commission_chart_data[0] + $commission_chart_data[1] + $commission_chart_data[2]), 2);
                                                    }

                                                    ?>
                                                    <h5 class="mb-1">$ <?php echo e($commission_chart_data[2]); ?></h5>
                                                    <p class="card-text text-muted font-small-2">
                                                        <span class="fw-bolder"><?php echo e($commission_chart['percent']); ?>%</span><span>
                                                            more earnings than last month.</span>
                                                    </p>
                                                </div>
                                                <div class="col-6">
                                                    <div id="earnings-chart"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!--/ Earnings Card -->
                        </div>
                    </div>

                    <!-- Revenue Report Card -->
                    <div class="col-lg-8 col-12">
                        <div class="card card-revenue-budget">
                            <div class="row mx-0">
                                <div class="col-md-8 col-12 revenue-report-wrapper">
                                    <div class="d-sm-flex justify-content-between align-items-center mb-3">
                                        <h4 class="card-title mb-50 mb-sm-0"><?php echo e(__('page.Revenue')); ?>

                                            <?php echo e(__('page.reports')); ?>

                                        </h4>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center me-2">
                                                <span class="bullet bullet-primary font-small-3 me-50 cursor-pointer"></span>
                                                <span><?php echo e(__('page.deposit')); ?></span>
                                            </div>
                                            <div class="d-flex align-items-center ms-75">
                                                <span class="bullet bullet-warning font-small-3 me-50 cursor-pointer"></span>
                                                <span><?php echo e(__('page.withdraw')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="revenue-report-chart"></div>
                                </div>
                                <div class="col-md-4 col-12 budget-wrapper">
                                    <div class="btn-group justify-content-between d-flex">
                                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle budget-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo(date('Y')) ?>
                                        </button>
                                        <span class="d-block w-50"><?php echo(date('F')) ?></span>
                                        <div class="dropdown-menu" style="max-height: 200px; overflow:scroll;">
                                            <?php $months_name = AllFunctionService::months_with_name(); ?>
                                            <?php for($i = 0; $i < (int) date('m'); $i++): ?> 
                                                <a class="dropdown-item" href="#" data-month="<?php echo e($months_name['month'][$i]); ?>"><?php echo e($months_name['name'][$i]); ?></a>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <h2 class="mb-25 d-flex font-medium-3" id="deposit-amount"><span class="bg-primary me-2 rounded" title="Deposit" style="width: 28px"></span> $<?php echo e($deposit_per_month); ?>

                                    </h2>
                                    <div class="d-flex">
                                        <span class="fw-bolder me-25 bg-secondary  me-2 rounded" title="Withdraw" style="width: 28px;"></span>
                                        <span id="withdraw-amount">$<?php echo e($withdraw_per_month); ?></span>
                                    </div>
                                    <div id="budget-chart"></div>
                                    <a href="<?php echo e(route('admin.finance-report')); ?>" class="btn btn-primary"><?php echo e(__('page.Check')); ?> <?php echo e(__('page.finance')); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Revenue Report Card -->
                </div>

                <div class="row match-height">
                    <!-- Company Table Card -->
                    <div class="col-lg-8 col-12">
                        <div class="card card-company-table">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__('page.name')); ?></th>
                                                <th><?php echo e(__('page.action')); ?></th>
                                                <th><?php echo e(__('page.Time')); ?></th>
                                                <th><?php echo e(__('page.Details')); ?></th>
                                                <th><?php echo e(__('finance.Type')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($last_withdraw_request): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo e(route('admin.manage.withdraw')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar rounded">
                                                            <div class="avatar-content">
                                                                <img class="rounded-circle bg-primary" src="<?php echo e(AllfunctionService::user_profile($last_withdraw_request->user_id)); ?>" width="35" alt="Users" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bolder text-body">
                                                                <?php echo e(AllFunctionService::user_name($last_withdraw_request->user_id)); ?>

                                                            </div>
                                                            <div class="font-small-2 text-muted">
                                                                <?php echo e(AllFunctionService::user_email($last_withdraw_request->user_id)); ?>

                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('admin.manage.withdraw')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar bg-light-primary me-1">
                                                            <div class="avatar-content">
                                                                <img class="w-100" src="<?php echo e(asset('comon-icon/request-icon/withdraw.png')); ?>" style="width:21px !important; height: 21px !important">
                                                            </div>
                                                        </div>
                                                        <span class="text-body">Withdraw</span>
                                                    </a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <a href="<?php echo e(route('admin.manage.withdraw')); ?>" class="d-flex flex-column text-body">
                                                        <span class="fw-bolder mb-25 text-body"><?php echo e(date('d M Y', strtotime($last_withdraw_request->created_at))); ?></span>
                                                        <span class="font-small-2 text-muted"><?php echo e($last_withdraw_request->created_at->diffForHumans()); ?></span>
                                                    </a>
                                                </td>
                                                <td><a href="<?php echo e(route('admin.manage.withdraw')); ?>" class="d-flex align-items-center">&dollar;
                                                        <?php echo e($last_withdraw_request->amount); ?></a></td>
                                                <td>
                                                    <a href="<?php echo e(route('admin.manage.withdraw')); ?>" class="d-flex align-items-center">
                                                        <span class="fw-bolder me-1 text-body"><?php echo e($last_withdraw_request->transaction_type); ?></span>
                                                        <i data-feather="trending-down" class="text-danger font-medium-1"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <!-- last deposit request -->
                                            <?php if($last_deposit_request): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo e(route('admin.manage.deposit')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar rounded">
                                                            <div class="avatar-content">
                                                                <img class="rounded-circle bg-primary" src="<?php echo e(AllfunctionService::user_profile($last_deposit_request->user_id)); ?>" width="35" alt="Users" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bolder text-body">
                                                                <?php echo e(AllFunctionService::user_name($last_deposit_request->user_id)); ?>

                                                            </div>
                                                            <div class="font-small-2 text-muted">
                                                                <?php echo e(AllFunctionService::user_email($last_deposit_request->user_id)); ?>

                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('admin.manage.deposit')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar bg-light-primary me-1">
                                                            <div class="avatar-content">
                                                                <img class="w-100" src="<?php echo e(asset('comon-icon/request-icon/deposit.png')); ?>" alt="" srcset="">
                                                            </div>
                                                        </div>
                                                        <span class="text-body">Deposit</span>
                                                    </a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <a href="<?php echo e(route('admin.manage.deposit')); ?>" class="d-flex flex-column">
                                                        <span class="fw-bolder mb-25 text-body"><?php echo e(date('d M Y', strtotime($last_deposit_request->created_at))); ?></span>
                                                        <span class="font-small-2 text-muted"><?php echo e($last_deposit_request->created_at->diffForHumans()); ?></span>
                                                    </a>
                                                </td>
                                                <td><a href="<?php echo e(route('admin.manage.deposit')); ?>" class="d-flex align-items-center">&dollar;
                                                        <?php echo e($last_deposit_request->amount); ?></a></td>
                                                <td>
                                                    <a href="<?php echo e(route('admin.manage.deposit')); ?>" class="d-flex align-items-center">
                                                        <span class="fw-bolder me-1 text-body"><?php echo e($last_deposit_request->transaction_type); ?></span>
                                                        <i data-feather="trending-up" class="text-success font-medium-1"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <!-- kyc request -->
                                            <?php if($last_kyc_request): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo e(route('kyc.management.request')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar rounded">
                                                            <div class="avatar-content">
                                                                <img class="rounded-circle bg-primary" src="<?php echo e(AllfunctionService::user_profile($last_kyc_request->user_id)); ?>" width="35" alt="Users" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bolder text-body">
                                                                <?php echo e(AllFunctionService::user_name($last_kyc_request->user_id)); ?>

                                                            </div>
                                                            <div class="font-small-2 text-muted">
                                                                <?php echo e(AllFunctionService::user_email($last_kyc_request->user_id)); ?>

                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('kyc.management.request')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar bg-light-primary me-1">
                                                            <div class="avatar-content">
                                                                <img class="w-100" src="<?php echo e(asset('comon-icon/request-icon/kyc.png')); ?>" style="width:21px !important; height: 21px !important">
                                                            </div>
                                                        </div>
                                                        <span class="text-body">KYC</span>
                                                    </a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <a href="<?php echo e(route('kyc.management.request')); ?>" class="d-flex flex-column text-body">
                                                        <span class="fw-bolder mb-25 text-body"><?php echo e(date('d M Y', strtotime($last_kyc_request->created_at))); ?></span>
                                                        <span class="font-small-2 text-muted"><?php echo e($last_kyc_request->created_at->diffForHumans()); ?></span>
                                                    </a>
                                                </td>
                                                <td><a href="<?php echo e(route('kyc.management.request')); ?>" class="text-body"> <span class="text-body"><?php echo e(ucwords($last_kyc_request->id_type)); ?></span></a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('kyc.management.request')); ?>" class="d-flex align-items-center">
                                                        <span class="fw-bolder me-1 text-body"><?php echo e(ucwords($last_kyc_request->perpose)); ?></span>
                                                        <!-- <i data-feather="trending-up" class="text-success font-medium-1"></i> -->
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <!-- bank request -->
                                            <?php if($last_bank_request): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo e(route('admin.manage_banks.bank_account_list')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar rounded">
                                                            <div class="avatar-content">
                                                                <img class="rounded-circle bg-primary" src="<?php echo e(AllfunctionService::user_profile($last_bank_request->user_id)); ?>" width="35" alt="Users" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bolder text-body">
                                                                <?php echo e(AllFunctionService::user_name($last_bank_request->user_id)); ?>

                                                            </div>
                                                            <div class="font-small-2 text-muted">
                                                                <?php echo e(AllFunctionService::user_email($last_bank_request->user_id)); ?>

                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('admin.manage_banks.bank_account_list')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar bg-light-primary me-1">
                                                            <div class="avatar-content">
                                                                <img class="w-100" src="<?php echo e(asset('comon-icon/request-icon/bank.png')); ?>" style="width:21px !important; height: 21px !important">
                                                            </div>
                                                        </div>
                                                        <span class="text-body">Bank Request</span>
                                                    </a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <a href="<?php echo e(route('admin.manage_banks.bank_account_list')); ?>" class="d-flex flex-column">
                                                        <span class="fw-bolder mb-25 text-body"><?php echo e(date('d M Y', strtotime($last_bank_request->created_at))); ?></span>
                                                        <span class="font-small-2 text-muted"><?php echo e($last_bank_request->created_at->diffForHumans()); ?></span>

                                                    </a>
                                                </td>
                                                <td><a href="<?php echo e(route('admin.manage_banks.bank_account_list')); ?>" class="d-flex align-items-center">
                                                        <span class="text-body"><?php echo e(ucwords($last_bank_request->bank_ac_name)); ?></span>
                                                    </a></td>
                                                <td>
                                                    <a href="<?php echo e(route('admin.manage_banks.bank_account_list')); ?>" class="d-flex align-items-center">
                                                        <span class="fw-bolder me-1 text-body"><?php echo e(ucwords($last_bank_request->bank_name)); ?></span>
                                                        <!-- <i data-feather="trending-up" class="text-success font-medium-1"></i> -->
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <!-- external transfer request -->
                                            <?php if($last_external_transfer != null): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo e(route('admin.balance-transfer')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar rounded">
                                                            <div class="avatar-content">
                                                                <img class="rounded-circle bg-primary" src="<?php echo e(AllfunctionService::user_profile($last_external_transfer->sender_id)); ?>" width="35" alt="Users" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bolder text-body">
                                                                <?php echo e(AllFunctionService::user_name($last_external_transfer->sender_id)); ?>

                                                            </div>
                                                            <div class="font-small-2 text-muted">
                                                                <?php echo e(AllFunctionService::user_email($last_external_transfer->sender_id)); ?>

                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('admin.balance-transfer')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar bg-light-primary me-1">
                                                            <div class="avatar-content">
                                                                <img class="w-100" src="<?php echo e(asset('comon-icon/request-icon/external-transfer.png')); ?>" style="width:21px !important; height: 21px !important">
                                                            </div>
                                                        </div>
                                                        <span class="text-body">External Transfer</span>
                                                    </a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <a href="<?php echo e(route('admin.balance-transfer')); ?>" class="d-flex flex-column">
                                                        <span class="fw-bolder mb-25 text-body"><?php echo e(date('d M Y', strtotime($last_external_transfer->created_at))); ?></span>
                                                        <span class="font-small-2 text-muted"><?php echo e($last_external_transfer->created_at->diffForHumans()); ?></span>
                                                    </a>
                                                </td>
                                                <td><a href="<?php echo e(route('admin.balance-transfer')); ?>" class="d-flex flex-column text-body">&dollar;
                                                        <?php echo e(ucwords($last_external_transfer->amount)); ?></a></td>
                                                <td>
                                                    <a href="<?php echo e(route('admin.balance-transfer')); ?>" class="d-flex align-items-center">
                                                        <span class="fw-bolder me-1 text-body"><?php echo e(ucwords(AllfunctionService::user_type($last_external_transfer->sender_id))); ?></span>
                                                        <i data-feather="trending-down" class="text-danger font-medium-1"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <!-- internal transfer request -->
                                            <?php if($last_internal_transfer): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo e(route('admin.balance-transfer')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar rounded">
                                                            <div class="avatar-content">
                                                                <img class="rounded-circle bg-primary" src="<?php echo e(AllfunctionService::user_profile($last_internal_transfer->sender_id)); ?>" width="35" alt="Users" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bolder text-body">
                                                                <?php echo e(AllFunctionService::user_name($last_internal_transfer->user_id)); ?>

                                                            </div>
                                                            <div class="font-small-2 text-muted">
                                                                <?php echo e(AllFunctionService::user_email($last_internal_transfer->user_id)); ?>

                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('admin.balance-transfer')); ?>" class="d-flex align-items-center">
                                                        <div class="avatar bg-light-primary me-1">
                                                            <div class="avatar-content">
                                                                <img class="w-100" src="<?php echo e(asset('comon-icon/request-icon/internal-transfer.png')); ?>" style="width:21px !important; height: 21px !important">
                                                            </div>
                                                        </div>
                                                        <span class="text-body">Internal Transfer</span>
                                                    </a>
                                                </td>
                                                <td class="text-nowrap">
                                                    <a href="<?php echo e(route('admin.balance-transfer')); ?>" class="d-flex flex-column">
                                                        <span class="fw-bolder mb-25 text-body"><?php echo e(date('d M Y', strtotime($last_internal_transfer->created_at))); ?></span>
                                                        
                                                    </a>
                                                </td>
                                                <td><a href="<?php echo e(route('admin.balance-transfer')); ?>"><span class="text-body">&dollar;
                                                            <?php echo e(ucwords($last_internal_transfer->amount)); ?></span></a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('admin.balance-transfer')); ?>" class="d-flex align-items-center">
                                                        <span class="fw-bolder me-1 text-body"><?php echo e(ucwords(AllfunctionService::internal_trans_type($last_internal_transfer->type))); ?></span>
                                                        <?php if($last_external_transfer != null && $last_external_transfer->type === 'wta'): ?>
                                                        <i data-feather="trending-down" class="text-danger font-medium-1"></i>
                                                        <?php else: ?>
                                                        <i data-feather="trending-up" class="text-success font-medium-1"></i>
                                                        <?php endif; ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Company Table Card -->

                    <!-- Browser States Card -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-browser-states">
                            <div class="card-header">
                                <div>
                                    <h4 class="card-title"><?php echo e(__('page.Login_History')); ?></h4>
                                    <!-- <p class="card-text font-small-2">12 August 2020</p> -->
                                </div>
                                <!-- <div class="dropdown chart-dropdown">
                                                                <i data-feather="more-vertical" class="font-medium-3 cursor-pointer" data-bs-toggle="dropdown"></i>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Last 28 Days</a>
                                                                    <a class="dropdown-item" href="#">Last Month</a>
                                                                    <a class="dropdown-item" href="#">Last Year</a>
                                                                </div>
                                                            </div> -->
                            </div>
                            <div class="card-body">
                                <?php for($i = 0; $i < count($login_history); $i++): ?> <?php $device=$i + 1; $browser=$login_history["device_$device"]['name']; $browser_logo=AllFunctionService::login_browser($browser); $login_device=$login_history["device_$device"]['platform']; $device_icon=AllFunctionService::login_device($login_device); $ip_address=$login_history["device_$device"]['ip_address']; $login_at=$login_history["device_$device"]['login_at']; ?> <div class="browser-states">
                                    <div class="d-flex">
                                        <img src="<?php echo e(asset('admin-assets/app-assets/images/icons/' . $browser_logo)); ?>" class="rounded me-1" height="30" alt="Google Chrome" />
                                        <div class="d-flex flex-column">
                                            <span class="fw-bolder mb-25"><?php echo e($browser); ?></span>
                                            <span class="font-small-2 text-muted"><?php echo e($login_at); ?></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="fw-bold text-body-heading me-1"><?php echo e($ip_address); ?></div>
                                        <!--<i class="fas <?php echo e($device_icon); ?>"></i>-->
                                        <img src="<?php echo e(AllFunctionService::device_icon($login_device)); ?>" height="25">
                                    </div>
                            </div>
                            <?php endfor; ?>
                            <!-- <div class="browser-states">
                                                                <div class="d-flex">
                                                                    <img src="<?php echo e(asset('admin-assets/app-assets/images/icons/mozila-firefox.png')); ?>" class="rounded me-1" height="30" alt="Mozila Firefox" />
                                                                    <div class="d-flex flex-column">
                                                                        <span class="fw-bolder mb-25">Mozila Firefox</span>
                                                                        <span class="font-small-2 text-muted">in 24 hours</span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="fw-bold text-body-heading me-1">1.14.12.0</div>
                                                                    <img src="https://iconape.com/wp-content/files/fu/369254/svg/android-logo-icon-png-svg.png" height="30">
                                                                </div>
                                                            </div>
                                                            <div class="browser-states">
                                                                <div class="d-flex">
                                                                    <img src="<?php echo e(asset('admin-assets/app-assets/images/icons/apple-safari.png')); ?>" class="rounded me-1" height="30" alt="Apple Safari" />
                                                                    <div class="d-flex flex-column">
                                                                        <span class="fw-bolder mb-25">Apple Safari</span>
                                                                        <span class="font-small-2 text-muted">in 14 hours</span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="fw-bold text-body-heading me-1">14.6%</div>
                                                                    <img src="https://cdn.pixabay.com/photo/2022/03/18/05/51/logo-7075932__340.png" height="30">
                                                                </div>
                                                            </div>
                                                            <div class="browser-states">
                                                                <div class="d-flex">
                                                                    <img src="<?php echo e(asset('admin-assets/app-assets/images/icons/internet-explorer.png')); ?>" class="rounded me-1" height="30" alt="Internet Explorer" />
                                                                    <h6 class="align-self-center mb-0">Internet Explorer</h6>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="fw-bold text-body-heading me-1">4.2%</div>
                                                                    <div id="browser-state-chart-info"></div>
                                                                </div>
                                                            </div>
                                                            <div class="browser-states">
                                                                <div class="d-flex">
                                                                    <img src="<?php echo e(asset('admin-assets/app-assets/images/icons/opera.png')); ?>" class="rounded me-1" height="30" alt="Opera Mini" />
                                                                    <h6 class="align-self-center mb-0">Opera Mini</h6>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="fw-bold text-body-heading me-1">8.4%</div>
                                                                    <div id="browser-state-chart-danger"></div>
                                                                </div>
                                                            </div> -->
                        </div>
                    </div>
                </div>
                <!--/ Browser States Card -->


                <!-- Support Tracker Chart Card starts -->
                <div class="col-lg-4 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between pb-0">
                            <h4 class="card-title"><?php echo e(__('page.Support_Tracker')); ?></h4>
                            <div class="dropdown chart-dropdown">
                                <button class="btn btn-sm border-0 dropdown-toggle p-50" type="button" id="dropdownItem4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    All
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownItem4">
                                    <a data-filter-by="7" class="dropdown-item" href="#">Last 7 Days</a>
                                    <a data-filter-by="30" class="dropdown-item" href="#">Last Month</a>
                                    <a data-filter-by="last_year" class="dropdown-item" href="#">Last
                                        Year</a>
                                    <a data-filter-by="all" class="dropdown-item" href="#">all</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                    <h1 id="allTicket" class="font-large-2 fw-bolder mt-2 mb-0">
                                        <?php echo e($supportTracker['allTicket'] ?? 0); ?>

                                    </h1>
                                    <p class="card-text"><?php echo e(__('page.ticket')); ?></p>
                                </div>
                                <div class="col-sm-10 col-12 d-flex justify-content-center">
                                    <div id="support-trackers-chart"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <div class="text-center">
                                    <p class="card-text mb-50"><?php echo e(__('page.new')); ?> <?php echo e(__('page.ticket')); ?></p>
                                    <span class="font-large-1 fw-bold" id="newTicket"><?php echo e($supportTracker['newTicket'] ?? 0); ?></span>
                                </div>
                                <div class="text-center">
                                    <p class="card-text mb-50"><?php echo e(__('page.open')); ?> <?php echo e(__('page.ticket')); ?>

                                    </p>
                                    <span class="font-large-1 fw-bold" id="openTicket"><?php echo e($supportTracker['openTicket'] ?? 0); ?></span>
                                </div>
                                <div class="text-center">
                                    <p class="card-text mb-50"><?php echo e(__('page.Response')); ?> <?php echo e(__('page.Time')); ?>

                                    </p>
                                    <span class="font-large-1 fw-bold" id="avgTime"><?php echo e($supportTracker['avgTime'] ?? 0); ?>d</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Support Tracker Chart Card ends -->


                <!-- Transaction Card -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card card-transaction">
                        <div class="card-header">
                            <h4 class="card-title"><?php echo e(__('page.Transactions')); ?></h4>
                            <!-- <div class="dropdown chart-dropdown">
                                                                <i data-feather="more-vertical" class="font-medium-3 cursor-pointer" data-bs-toggle="dropdown"></i>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="#">Last 28 Days</a>
                                                                    <a class="dropdown-item" href="#">Last Month</a>
                                                                    <a class="dropdown-item" href="#">Last Year</a>
                                                                </div>
                                                            </div> -->
                        </div>
                        <div class="card-body">

                            <div class="transaction-item">
                                <div class="d-flex">
                                    <div class="avatar bg-light-success rounded float-start">
                                        <div class="avatar-content">
                                            <i data-feather="credit-card" class="avatar-icon font-medium-3"></i>
                                        </div>
                                    </div>
                                    <div class="transaction-percentage">
                                        <h6 class="transaction-title"><?php echo e(__('page.bank_deposit')); ?></h6>
                                        <small><?php echo e(__('page.approved')); ?></small>
                                    </div>
                                </div>
                                <div class="fw-bolder text-success">+ $<?php echo e($all_fun->bank_deposit()); ?></div>
                            </div>

                            <div class="transaction-item">
                                <div class="d-flex">
                                    <div class="avatar bg-light-danger rounded float-start">
                                        <div class="avatar-content">
                                            <i data-feather="credit-card" class="avatar-icon font-medium-3"></i>
                                        </div>
                                    </div>
                                    <div class="transaction-percentage">
                                        <h6 class="transaction-title"><?php echo e(__('page.bank_withdraw')); ?></h6>
                                        <small><?php echo e(__('page.approved')); ?></small>
                                    </div>
                                </div>
                                <div class="fw-bolder text-danger">- $<?php echo e($all_fun->bank_withdraw()); ?></div>
                            </div>
                            <div class="transaction-item">
                                <div class="d-flex">
                                    <div class="avatar bg-light-success rounded float-start">
                                        <div class="avatar-content">
                                            <i data-feather="trending-up" class="avatar-icon font-medium-3"></i>
                                        </div>
                                    </div>
                                    <div class="transaction-percentage">
                                        <h6 class="transaction-title"><?php echo e(__('page.crypto_deposit')); ?></h6>
                                        <small><?php echo e(__('page.approved')); ?></small>
                                    </div>
                                </div>
                                <div class="fw-bolder text-success">+ <?php echo e($all_fun->crypto_deposit()); ?></div>
                            </div>

                            <div class="transaction-item">
                                <div class="d-flex">
                                    <div class="avatar bg-light-danger rounded float-start">
                                        <div class="avatar-content">
                                            <i data-feather="trending-down" class="avatar-icon font-medium-3"></i>
                                        </div>
                                    </div>
                                    <div class="transaction-percentage">
                                        <h6 class="transaction-title"><?php echo e(__('page.crypto_ withdraw')); ?></h6>
                                        <small><?php echo e(__('page.approved')); ?></small>
                                    </div>
                                </div>
                                <div class="fw-bolder text-danger">- <?php echo e($all_fun->crypto_withdraw()); ?></div>
                            </div>

                            <div class="transaction-item">
                                <div class="d-flex">
                                    <div class="avatar bg-light-success rounded float-start">
                                        <div class="avatar-content">
                                            <i data-feather="pocket" class="avatar-icon font-medium-3"></i>
                                        </div>
                                    </div>
                                    <div class="transaction-percentage">
                                        <h6 class="transaction-title"><?php echo e(__('page.Other')); ?>

                                            <?php echo e(__('page.deposit')); ?>

                                        </h6>
                                        <small><?php echo e(__('page.approved')); ?></small>
                                    </div>
                                </div>
                                <div class="fw-bolder text-success">+ $<?php echo e($all_fun->other_deposit()); ?></div>
                            </div>

                            <div class="transaction-item">
                                <div class="d-flex">
                                    <div class="avatar bg-light-danger rounded float-start">
                                        <div class="avatar-content">
                                            <i data-feather="pocket" class="avatar-icon font-medium-3"></i>
                                        </div>
                                    </div>
                                    <div class="transaction-percentage">
                                        <h6 class="transaction-title"><?php echo e(__('page.Other')); ?>

                                            <?php echo e(__('page.withdraw')); ?>

                                        </h6>
                                        <small><?php echo e(__('page.approved')); ?></small>
                                    </div>
                                </div>
                                <div class="fw-bolder text-danger">- $<?php echo e($all_fun->other_withdraw()); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Transaction Card -->

                <!-- Goal Overview Card -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">KYC <?php echo e(__('page.Overview')); ?></h4>
                            <i data-feather="help-circle" class="font-medium-3 text-muted cursor-pointer"></i>
                        </div>
                        <div class="card-body p-0">
                            <div id="goal-overview-radial-bar-chart" class="my-2"></div>
                            <div class="row border-top text-center mx-0">
                                <?php
                                $kyc_verified = $kyc_status['verified'];
                                $kyc_unverified = $kyc_status['unverified'];
                                $kyc_percent = $kyc_status['percent'];
                                ?>
                                <div class="col-6 border-end py-1">
                                    <p class="card-text text-muted mb-0"><?php echo e(__('page.Verified')); ?></p>
                                    <h3 class="fw-bolder mb-0"><?php echo e($kyc_verified); ?></h3>
                                </div>
                                <div class="col-6 py-1">
                                    <p class="card-text text-muted mb-0"><?php echo e(__('page.Unverified')); ?></p>
                                    <h3 class="fw-bolder mb-0"><?php echo e($kyc_unverified); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Goal Overview Card -->


        </div>
        </section>
        <!-- Dashboard Ecommerce ends -->

    </div>
</div>
</div>
<!-- END: Content-->
<?php
$revinue_deposit = json_encode($revenue_report['deposit']);
$revinue_month = json_encode($revenue_report['months']);
$revinue_withdraw = json_encode($revenue_report['withdraw']);
?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-js'); ?>
<script src="<?php echo e(asset('admin-assets/app-assets/vendors/js/charts/apexcharts.min.js')); ?>"></script>
<!-- <script src="<?php echo e(asset('admin-assets/app-assets/js/scripts/pages/dashboard-ecommerce.js')); ?>"></script> -->
<script>
    // On load Toast
    var isRtl = $('html').attr('data-textdirection') === 'rtl';
    setTimeout(function() {
        toastr['success']('You have successfully logged in to <?php echo e(config("app.name")); ?>. Now you can start to explore!',
            '👋 <?php echo e(auth()->user()->name); ?>!', {
                closeButton: true,
                tapToDismiss: false,
                rtl: isRtl
            }
        );
    }, 2000);
    $('.btn_prop_disabled').click(function() {
        $(this).prop('disabled', true);
    })
    // goad chart
    //------------ Goal Overview Chart ------------
    //---------------------------------------------
    /*=========================================================================================
        File Name: dashboard-ecommerce.js
        Description: dashboard ecommerce page content with Apexchart Examples
        ----------------------------------------------------------------------------------------
        Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
        Author: PIXINVENT
        Author URL: http://www.themeforest.net/user/pixinvent
    ==========================================================================================*/

    $(window).on('load', function() {
        'use strict';

        var $barColor = '#f3f3f3';
        var $trackBgColor = '#EBEBEB';
        var $textMutedColor = '#b9b9c3';
        var $budgetStrokeColor2 = '#dcdae3';
        var $goalStrokeColor2 = '#51e5a8';
        var $strokeColor = '#ebe9f1';
        var $textHeadingColor = '#5e5873';
        var $earningsStrokeColor2 = '#28c76f66';
        var $earningsStrokeColor3 = '#28c76f33';
        var $supportTrackerChart = document.querySelector('#support-trackers-chart');

        var $statisticsOrderChart = document.querySelector('#statistics-order-chart');
        var $statisticsProfitChart = document.querySelector('#statistics-profit-chart');
        var $earningsChart = document.querySelector('#earnings-chart');
        var $revenueReportChart = document.querySelector('#revenue-report-chart');
        var $budgetChart = document.querySelector('#budget-chart');
        var $browserStateChartPrimary = document.querySelector('#browser-state-chart-primary');
        var $browserStateChartWarning = document.querySelector('#browser-state-chart-warning');
        var $browserStateChartSecondary = document.querySelector('#browser-state-chart-secondary');
        var $browserStateChartInfo = document.querySelector('#browser-state-chart-info');
        var $browserStateChartDanger = document.querySelector('#browser-state-chart-danger');
        var $goalOverviewChart = document.querySelector('#goal-overview-radial-bar-chart');

        var statisticsOrderChartOptions;
        var statisticsProfitChartOptions;
        var earningsChartOptions;
        var revenueReportChartOptions;
        var budgetChartOptions;
        var browserStatePrimaryChartOptions;
        var browserStateWarningChartOptions;
        var browserStateSecondaryChartOptions;
        //   var browserStateInfoChartOptions;
        var browserStateDangerChartOptions;
        var goalOverviewChartOptions;
        var supportTrackerChartOptions;

        var statisticsOrderChart;
        var statisticsProfitChart;
        var earningsChart;
        var revenueReportChart;
        var budgetChart;
        var browserStatePrimaryChart;
        var browserStateDangerChart;
        //   var browserStateInfoChart;
        var supportTrackerChart;
        var browserStateSecondaryChart;
        var browserStateWarningChart;
        var goalOverviewChart;
        var isRtl = $('html').attr('data-textdirection') === 'rtl';

        // // On load Toast
        // setTimeout(function () {
        //   toastr['success'](
        //     'You have successfully logged in to Vuexy. Now you can start to explore!',
        //     '👋 Welcome John Doe!',
        //     {
        //       closeButton: true,
        //       tapToDismiss: false,
        //       rtl: isRtl
        //     }
        //   );
        // }, 2000);

        //------------ Statistics Bar Chart ------------
        //----------------------------------------------
        statisticsOrderChartOptions = {
            chart: {
                height: 70,
                type: 'bar',
                stacked: true,
                toolbar: {
                    show: false
                }
            },
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0,
                    top: -15,
                    bottom: -15
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '20%',
                    startingShape: 'rounded',
                    colors: {
                        backgroundBarColors: [$barColor, $barColor, $barColor, $barColor, $barColor],
                        backgroundBarRadius: 5
                    }
                }
            },
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            colors: [window.colors.solid.warning],
            series: [{
                name: 'withdraw',
                data: JSON.parse('<?php echo $pending_withdraw_chart; ?>')
            }],
            xaxis: {
                labels: {
                    show: false
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                show: false
            },
            tooltip: {
                x: {
                    show: false
                }
            }
        };
        statisticsOrderChart = new ApexCharts($statisticsOrderChart, statisticsOrderChartOptions);
        statisticsOrderChart.render();

        //------------ Statistics Line Chart ------------
        //-----------------------------------------------
        statisticsProfitChartOptions = {
            chart: {
                height: 70,
                type: 'line',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            grid: {
                borderColor: $trackBgColor,
                strokeDashArray: 5,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                },
                padding: {
                    top: -30,
                    bottom: -10
                }
            },
            stroke: {
                width: 3
            },
            colors: [window.colors.solid.info],
            series: [{
                data: JSON.parse('<?php echo $pending_deposit_chart; ?>')
            }],
            markers: {
                size: 2,
                colors: window.colors.solid.info,
                strokeColors: window.colors.solid.info,
                strokeWidth: 2,
                strokeOpacity: 1,
                strokeDashArray: 0,
                fillOpacity: 1,
                discrete: [{
                    seriesIndex: 0,
                    dataPointIndex: 5,
                    fillColor: '#ffffff',
                    strokeColor: window.colors.solid.info,
                    size: 5
                }],
                shape: 'circle',
                radius: 2,
                hover: {
                    size: 3
                }
            },
            xaxis: {
                labels: {
                    show: true,
                    style: {
                        fontSize: '0px'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                show: false
            },
            tooltip: {
                x: {
                    show: false
                }
            }
        };
        statisticsProfitChart = new ApexCharts($statisticsProfitChart, statisticsProfitChartOptions);
        statisticsProfitChart.render();

        //--------------- Earnings Chart ---------------
        //----------------------------------------------
        earningsChartOptions = {
            chart: {
                type: 'donut',
                height: 120,
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            series: JSON.parse('<?php echo $commission_chart['chart_data']; ?>'),
            legend: {
                show: false
            },
            comparedResult: [2, -3, 8],
            labels: ['Deposit', 'Withdraw', 'Commission'],
            stroke: {
                width: 0
            },
            colors: [$earningsStrokeColor2, $earningsStrokeColor3, window.colors.solid.success],
            grid: {
                padding: {
                    right: -20,
                    bottom: -8,
                    left: -20
                }
            },
            plotOptions: {
                pie: {
                    startAngle: -10,
                    donut: {
                        labels: {
                            show: true,
                            name: {
                                offsetY: 15
                            },
                            value: {
                                offsetY: -15,
                                formatter: function(val) {
                                    return parseInt(val) + '%';
                                }
                            },
                            total: {
                                show: true,
                                offsetY: 15,
                                label: 'Commission',
                                formatter: function(w) {
                                    return "<?php echo $commisstion_percent; ?>%";
                                }
                            }
                        }
                    }
                }
            },
            responsive: [{
                    breakpoint: 1325,
                    options: {
                        chart: {
                            height: 100
                        }
                    }
                },
                {
                    breakpoint: 1200,
                    options: {
                        chart: {
                            height: 120
                        }
                    }
                },
                {
                    breakpoint: 1045,
                    options: {
                        chart: {
                            height: 100
                        }
                    }
                },
                {
                    breakpoint: 992,
                    options: {
                        chart: {
                            height: 120
                        }
                    }
                }
            ]
        };
        earningsChart = new ApexCharts($earningsChart, earningsChartOptions);
        earningsChart.render();

        //------------ Revenue Report Chart ------------
        //----------------------------------------------
        revenueReportChartOptions = {
            chart: {
                height: 230,
                stacked: true,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '17%',
                    endingShape: 'rounded'
                },
                distributed: true
            },
            colors: [window.colors.solid.primary, window.colors.solid.warning],
            series: [{
                    name: 'Deposit',
                    data: JSON.parse("<?php echo $revinue_deposit; ?>")
                },
                {
                    name: 'Withdraw',
                    data: JSON.parse('<?php echo $revinue_withdraw; ?>')
                }
            ],
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            grid: {
                padding: {
                    top: -20,
                    bottom: -10
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                }
            },
            xaxis: {
                categories: JSON.parse('<?php echo $revinue_month; ?>'),
                labels: {
                    style: {
                        colors: $textMutedColor,
                        fontSize: '0.86rem'
                    }
                },
                axisTicks: {
                    show: false
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: $textMutedColor,
                        fontSize: '0.86rem'
                    },
                    formatter: function(value){
                        return value.toFixed(2);
                    }
                }
            }
        };
        revenueReportChart = new ApexCharts($revenueReportChart, revenueReportChartOptions);
        revenueReportChart.render();

        //---------------- Budget Chart ----------------
        //----------------------------------------------
        budgetChartOptions = {
            chart: {
                height: 80,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                type: 'line',
                sparkline: {
                    enabled: true
                }
            },
            stroke: {
                curve: 'smooth',
                dashArray: [0, 5],
                width: [2]
            },
            colors: [window.colors.solid.primary, $budgetStrokeColor2],
            series: [{
                    data: JSON.parse('<?php echo $per_month_depo_chart; ?>')
                },
                {
                    data: JSON.parse('<?php echo $per_month_withdraw_chart; ?>')
                }
            ],
            tooltip: {
                enabled: false
            }
        };
        budgetChart = new ApexCharts($budgetChart, budgetChartOptions);
        budgetChart.render();

        //------------ Browser State Charts ------------
        //----------------------------------------------

        // State Primary Chart
        // browserStatePrimaryChartOptions = {
        //   chart: {
        //     height: 30,
        //     width: 30,
        //     type: 'radialBar'
        //   },
        //   grid: {
        //     show: false,
        //     padding: {
        //       left: -15,
        //       right: -15,
        //       top: -12,
        //       bottom: -15
        //     }
        //   },
        //   colors: [window.colors.solid.primary],
        //   series: [54.4],
        //   plotOptions: {
        //     radialBar: {
        //       hollow: {
        //         size: '22%'
        //       },
        //       track: {
        //         background: $trackBgColor
        //       },
        //       dataLabels: {
        //         showOn: 'always',
        //         name: {
        //           show: false
        //         },
        //         value: {
        //           show: false
        //         }
        //       }
        //     }
        //   },
        //   stroke: {
        //     lineCap: 'round'
        //   }
        // };
        // browserStatePrimaryChart = new ApexCharts($browserStateChartPrimary, browserStatePrimaryChartOptions);
        // browserStatePrimaryChart.render();

        // State Warning Chart
        // browserStateWarningChartOptions = {
        //   chart: {
        //     height: 30,
        //     width: 30,
        //     type: 'radialBar'
        //   },
        //   grid: {
        //     show: false,
        //     padding: {
        //       left: -15,
        //       right: -15,
        //       top: -12,
        //       bottom: -15
        //     }
        //   },
        //   colors: [window.colors.solid.warning],
        //   series: [6.1],
        //   plotOptions: {
        //     radialBar: {
        //       hollow: {
        //         size: '22%'
        //       },
        //       track: {
        //         background: $trackBgColor
        //       },
        //       dataLabels: {
        //         showOn: 'always',
        //         name: {
        //           show: false
        //         },
        //         value: {
        //           show: false
        //         }
        //       }
        //     }
        //   },
        //   stroke: {
        //     lineCap: 'round'
        //   }
        // };
        // browserStateWarningChart = new ApexCharts($browserStateChartWarning, browserStateWarningChartOptions);
        // browserStateWarningChart.render();

        // State Secondary Chart 1
        // browserStateSecondaryChartOptions = {
        //   chart: {
        //     height: 30,
        //     width: 30,
        //     type: 'radialBar'
        //   },
        //   grid: {
        //     show: false,
        //     padding: {
        //       left: -15,
        //       right: -15,
        //       top: -12,
        //       bottom: -15
        //     }
        //   },
        //   colors: [window.colors.solid.secondary],
        //   series: [14.6],
        //   plotOptions: {
        //     radialBar: {
        //       hollow: {
        //         size: '22%'
        //       },
        //       track: {
        //         background: $trackBgColor
        //       },
        //       dataLabels: {
        //         showOn: 'always',
        //         name: {
        //           show: false
        //         },
        //         value: {
        //           show: false
        //         }
        //       }
        //     }
        //   },
        //   stroke: {
        //     lineCap: 'round'
        //   }
        // };
        // browserStateSecondaryChart = new ApexCharts($browserStateChartSecondary, browserStateSecondaryChartOptions);
        // browserStateSecondaryChart.render();

        // State Info Chart
        //   browserStateInfoChartOptions = {
        //     chart: {
        //       height: 30,
        //       width: 30,
        //       type: 'radialBar'
        //     },
        //     grid: {
        //       show: false,
        //       padding: {
        //         left: -15,
        //         right: -15,
        //         top: -12,
        //         bottom: -15
        //       }
        //     },
        //     colors: [window.colors.solid.info],
        //     series: [4.2],
        //     plotOptions: {
        //       radialBar: {
        //         hollow: {
        //           size: '22%'
        //         },
        //         track: {
        //           background: $trackBgColor
        //         },
        //         dataLabels: {
        //           showOn: 'always',
        //           name: {
        //             show: false
        //           },
        //           value: {
        //             show: false
        //           }
        //         }
        //       }
        //     },
        //     stroke: {
        //       lineCap: 'round'
        //     }
        //   };
        //   browserStateInfoChart = new ApexCharts($browserStateChartInfo, browserStateInfoChartOptions);
        //   browserStateInfoChart.render();

        // State Danger Chart
        //   browserStateDangerChartOptions = {
        //     chart: {
        //       height: 30,
        //       width: 30,
        //       type: 'radialBar'
        //     },
        //     grid: {
        //       show: false,
        //       padding: {
        //         left: -15,
        //         right: -15,
        //         top: -12,
        //         bottom: -15
        //       }
        //     },
        //     colors: [window.colors.solid.danger],
        //     series: [8.4],
        //     plotOptions: {
        //       radialBar: {
        //         hollow: {
        //           size: '22%'
        //         },
        //         track: {
        //           background: $trackBgColor
        //         },
        //         dataLabels: {
        //           showOn: 'always',
        //           name: {
        //             show: false
        //           },
        //           value: {
        //             show: false
        //           }
        //         }
        //       }
        //     },
        //     stroke: {
        //       lineCap: 'round'
        //     }
        //   };
        //   browserStateDangerChart = new ApexCharts($browserStateChartDanger, browserStateDangerChartOptions);
        //   browserStateDangerChart.render();

        //------------ Goal Overview Chart ------------
        //---------------------------------------------
        goalOverviewChartOptions = {
            chart: {
                height: 245,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                },
                dropShadow: {
                    enabled: true,
                    blur: 3,
                    left: 1,
                    top: 1,
                    opacity: 0.1
                }
            },
            colors: [$goalStrokeColor2],
            plotOptions: {
                radialBar: {
                    offsetY: -10,
                    startAngle: -150,
                    endAngle: 150,
                    hollow: {
                        size: '77%'
                    },
                    track: {
                        background: $strokeColor,
                        strokeWidth: '50%'
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            color: $textHeadingColor,
                            fontSize: '2.86rem',
                            fontWeight: '600'
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: [window.colors.solid.success],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                }
            },
            series: ["<?php echo $kyc_percent; ?>"],
            stroke: {
                lineCap: 'round'
            },
            grid: {
                padding: {
                    bottom: 30
                }
            }
        };
        goalOverviewChart = new ApexCharts($goalOverviewChart, goalOverviewChartOptions);
        goalOverviewChart.render();

        function tracketPercentage(pvalue) {

            supportTrackerChartOptions = {
                chart: {
                    height: 270,
                    type: 'radialBar'
                },
                plotOptions: {
                    radialBar: {
                        size: 150,
                        offsetY: 20,
                        startAngle: -150,
                        endAngle: 150,
                        hollow: {
                            size: '65%'
                        },
                        track: {
                            background: '#eee',
                            strokeWidth: '100%'
                        },
                        dataLabels: {
                            name: {
                                offsetY: -5,
                                color: $textHeadingColor,
                                fontSize: '1rem'
                            },
                            value: {
                                offsetY: 15,
                                color: $textHeadingColor,
                                fontSize: '1.714rem'
                            }
                        }
                    }
                },
                colors: [window.colors.solid.danger],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        shadeIntensity: 0.5,
                        gradientToColors: [window.colors.solid.primary],
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100]
                    }
                },
                stroke: {
                    dashArray: 8
                },
                series: [pvalue],
                labels: ['Completed Tickets']
            };
            supportTrackerChart = new ApexCharts($supportTrackerChart, supportTrackerChartOptions);
            supportTrackerChart.render();

        }


        tracketPercentage(<?php echo $supportTracker['chartPecent']; ?>);
        //support Tracker filter 
        $(document).on('click', '[data-filter-by]', function() {
            $('#dropdownItem4').html($(this).html());
            var data = {
                'filter_by': $(this).attr('data-filter-by')
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/dashboard/trackerfilter',
                dataType: 'json',
                data: data,
                method: 'post',
                success: function(data) {
                    if (data.status) {
                        $('#allTicket').html(data.allTicket);
                        $('#openTicket').html(data.openTicket);
                        $('#newTicket').html(data.newTicket);
                        $('#avgTime').html(data.avgTime);
                        supportTrackerChart.destroy();
                        tracketPercentage(data.chartPecent);
                    }
                }
            });
        });

        // Revenue Report Month Filter
        $(document).on('click', '[data-month]', function(e) {
            e.preventDefault();
            var month = $(this).attr('data-month');
            var monthName = $(this).text();
            
            console.log('Month clicked:', month, 'Month name:', monthName);
            
            // Update the dropdown button text
            $('.budget-dropdown').text(monthName);
            
            var data = {
                'month': month
            };

            console.log('Sending data:', data);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '/admin/dashboard/revenue-by-month',
                dataType: 'json',
                data: data,
                method: 'post',
                beforeSend: function() {
                    console.log('Sending AJAX request to:', '/admin/dashboard/revenue-by-month');
                },
                success: function(response) {
                    console.log('Revenue data response:', response);
                    if (response.status) {
                        // Update deposit amount using specific ID
                        $('#deposit-amount').html('<span class="bg-primary me-2 rounded" title="Deposit" style="width: 28px"></span> $' + response.deposit_per_month);
                        console.log('Updated deposit amount to: $' + response.deposit_per_month);
                        
                        // Update withdraw amount using specific ID
                        $('#withdraw-amount').text('$' + response.withdraw_per_month);
                        console.log('Updated withdraw amount to: $' + response.withdraw_per_month);
                        
                        // Update budget chart if it exists
                        if (typeof budgetChart !== 'undefined') {
                            budgetChart.updateSeries([{
                                data: response.per_month_depo_chart
                            }, {
                                data: response.per_month_withdraw_chart
                            }]);
                            console.log('Updated budget chart with new data');
                        }
                    } else {
                        console.error('Response status is false:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching revenue data:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                }
            });
        });



        // time counter 

        const sessionTimer = setInterval(getPageLoadTime, 10000);
        var beforeload = (new Date()).getTime();

        function getPageLoadTime() {
            var output = "";
            var afterload = (new Date()).getTime();
            var totalSeconds = Math.round((afterload - beforeload) / 1000);
            const hours = Math.floor(totalSeconds / 3600)
            const minutes = Math.floor((totalSeconds % 3600) / 60)
            const seconds = totalSeconds - hours * 3600 - minutes * 60

            if (hours != 0) {
                output += hours + " hours ";
            }
            if (minutes != 0) {
                output += minutes + " minutes ";
            }
            if (seconds != 0) {
                output += seconds + ' seconds'
            }
            $('#al_reload_time').html('Updated ' + output + ' ago');
        }


    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/admins/dashboard.blade.php ENDPATH**/ ?>