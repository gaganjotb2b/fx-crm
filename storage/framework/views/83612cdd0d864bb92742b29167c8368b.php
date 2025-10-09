
<?php $__env->startSection('title', 'Trader Dashboard'); ?>
<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('common-css/data-list-style.css')); ?>">
    <style>
        .list-row:last-child {
            border-bottom: none !important;
        }

        .card.trade_account_card_dash {
            min-height: 355px !important;
            display: flex;
        }

        .modal-header {
            justify-content: flex-start;
        }

        .data-list-total {
            display: flex;
            font-size: 13px;
            float: left;
            margin-top: 5px;
        }

        .lgrp-paginate {
            float: right;
            display: flex;
        }

        .btn-close.text-dark.btn-popup-close {
            position: absolute;
            right: 19px;
            top: 68px;
            z-index: 2;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('bread_crumb'); ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
                <a class="opacity-3 text-dark" href="javascript:;">
                    <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>shop </title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-1716.000000, -439.000000)" fill="#252f40" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                    <g transform="translate(0.000000, 148.000000)">
                                        <path
                                            d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">
                                        </path>
                                        <path
                                            d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                        </path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                    href="javascript:;"><?php echo e(__('page.dashboard')); ?></a></li>
            <!-- <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Withdraw Report</li> -->
        </ol>
        <h6 class="font-weight-bolder mb-0"><?php echo e(__('page.trader-area')); ?></h6>
    </nav>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php
        use App\Services\AllFunctionService;
        $all_fun = new AllFunctionService();
    ?>
    <div class="container-fluid py-4">
        <!-- contest alert -->
        <?php $banner = 0; ?>
        <?php if(\App\Services\contest\ContestService::client_has_contest(auth()->user()->id)['status'] && $banner != 0): ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-dismissible fade show z-index-1 p-0 bg-white" role="alert">
                        <div class="row">
                            <?php $i = 1; ?>
                            <?php $__currentLoopData = \App\Services\contest\ContestService::get_all_active_contest(auth()->user()->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($i <= 2): ?>
                                    <div
                                        class="col-md-<?php echo e(\App\Services\contest\ContestService::col_size(auth()->user()->id)); ?>">
                                        <a href="<?php echo e(route('users.participate-contest')); ?>"><img class="img-fluid"
                                                src="<?php echo e(asset('Uploads/contest/' . $value->popup_image)); ?>"
                                                alt="<?php echo e($value->contest_name); ?>"></a>
                                    </div>
                                <?php endif; ?>
                                <?php $i++ ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <button type="button" class="btn-close bg-danger rounded-circle badge-dot badge p-0"
                            data-bs-dismiss="alert" style="width: 32px; height:32px" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <!-- start main content -->
        <div class="row">
            <div class="col-lg-6 position-relative z-index-2">
                <div class="card card-plain mb-4">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex flex-column h-100">
                                    <h2 class="font-weight-bolder mb-0"><?php echo e(ucwords(auth()->user()->name)); ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="card mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                <?php echo e(__('Wallet Balance')); ?>

                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                $ <?php echo e($total_balance); ?>

                                                <span class="text-success text-sm font-weight-bolder"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Pending deposit">+$
                                                    <?php echo e($all_fun->get_pending_balance(auth()->user()->id) ? $all_fun->get_pending_balance(auth()->user()->id) : 0); ?></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                <?php echo e(__('page.total_withdraw')); ?>

                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                $ <?php echo e($all_fun->get_total_withdraw(auth()->user()->id)); ?>

                                                <span class="text-danger text-sm font-weight-bolder"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Pending withdraw">-$<?php echo e(\App\Services\BalanceService::trader_total_pending_withdraw(auth()->user()->id)); ?></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 mt-sm-0 mt-4">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                <?php echo e(__('page.trader')); ?> <?php echo e(__('page.volume')); ?>

                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                <?php echo e($all_fun->get_total_volume(auth()->user()->id)); ?>

                                                <span class="text-danger text-sm font-weight-bolder"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Todays volume"><?php echo e($all_fun->get_today_volume(auth()->user()->id)); ?></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card ">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                <?php echo e(__('page.total_deposit')); ?>

                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                $<?php echo e($all_fun->get_total_deposit(auth()->user()->id)); ?>

                                                <span class="text-success text-sm font-weight-bolder"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Pending deposit">$<?php echo e(\App\Services\AllFunctionService::trader_total_deposit(auth()->user()->id, 'pending')); ?></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 mt-sm-0 mt-4">
                        <div class="card  mb-4">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                <?php echo e(__('Account Deposit')); ?>

                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                $<?php echo e($all_fun->get_wta_transfer(auth()->user()->id)); ?>

                                                <span class="text-danger text-sm font-weight-bolder"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Pending Account Deposit"><?php echo e($all_fun->get_wta_transfer_pending(auth()->user()->id)); ?></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card ">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                                <?php echo e(__('Account Withdraw')); ?>

                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                $<?php echo e($all_fun->get_atw_transfer(auth()->user()->id)); ?>

                                                <span class="text-success text-sm font-weight-bolder"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Pending Account Withdraw">$<?php echo e($all_fun->get_atw_transfer_pending(auth()->user()->id)); ?></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 col-lg-12">
                        <div class="card trade_account_card_dash">
                            <div class="card-header pb-0 p-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-2"><?php echo e(__('page.trading_accounts')); ?></h6>
                                </div>
                            </div>
                            <?php if($trading_account_exists): ?>
                                <ul class="table-responsive px-0 list-group-flush" id="data-list">

                                </ul>
                            <?php else: ?>
                                <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
                                    <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                                    <span class="alert-text">
                                        <strong>Warning!</strong>
                                        Currently you don't have any account
                                        <a href="<?php echo e(route('user.trading.open-account')); ?>"
                                            class="text-decoration-underline">
                                            Please open an account first
                                        </a>
                                    </span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- trading account reports modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-flex">
                        <img class="modal-platform-logo" src="" alt="" height="30">
                        <h5 class="modal-title modal-account-number d-inline" id="exampleModalCenterTitle">
                            <div class="d-flex align-items-center">
                                <strong>Loading...</strong>
                                <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                            </div>
                        </h5>
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tr class="bg-light rounded top-left">
                                <th>
                                    <p class="font-weight-bold m-0 p-0 float-start">Leverage:</p>
                                </th>
                                <td>
                                    <h6 class="mt-1 pt-1 cursor-pointer modal-leverage">
                                        1:---
                                    </h6>
                                </td>
                            </tr>
                            <tr class="bg-light rounded top-left">
                                <th>
                                    <p class="font-weight-bold m-0 p-0 float-start">Balance:</p>
                                </th>
                                <td>
                                    <h6 class="mt-1 pt-1 cursor-pointer modal-account-balance"> --- </h6>
                                </td>
                            </tr>
                            <tr class="bg-light rounded top-left">
                                <th style="border:none">
                                    <p class="font-weight-bold m-0 p-0 float-start">Equity:</p>
                                </th>
                                <td>
                                    <h6 class="mt-1 pt-1 cursor-pointer modal-account-equity"> --- </h6>
                                </td>
                            </tr>
                            <tr class="bg-light rounded top-left">
                                <th style="border:none">
                                    <p class="font-weight-bold m-0 p-0 float-start">Free Margin:</p>
                                </th>
                                <td>
                                    <h6 class="mt-1 pt-1 cursor-pointer modal-free-margin"> --- </h6>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-5 mb-lg-0 mb-4">
                <div class="card z-index-2">
                    <div class="card-header pb-2">
                        <h6> <?php echo e(__('page.seven_days_trade')); ?></h6>
                    </div>
                    <div class="card-body p-3">

                        <div class="bg-gradient-dark border-radius-lg py-3 pe-1 mb-3">
                            <div class="chart">
                                <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                            </div>
                        </div>
                        <h6 class="ms-2 mt-4 mb-0">
                            <img src="<?php echo e(asset('comon-icon/metatrader.png')); ?>" style="width:50px">
                            <?php if(isset($platform->platform_type)): ?>
                                <?php echo e(__('page.MetaTrader')); ?>

                                <?php echo e($platform->platform_type != 'both' ? substr($platform->platform_type, 2) : ''); ?>

                            <?php endif; ?>
                        </h6>

                        <div class="container border-radius-lg">
                            <div class="row">
                                <div class="col-4 py-3 ps-0">
                                    <a class="col-4 py-3 ps-0" href="<?php echo e(meta_download_link('windows')); ?>"
                                        target="_blank">
                                        <div class="d-flex mb-2">
                                            <i class="fa fa-windows" style="font-size:20px;"></i>&nbsp
                                            <p class="text-xs mt-1 mb-0 font-weight-bold">Desktop</p>
                                        </div>
                                        <h4 class="font-weight-bolder"><i class="fa fa-cloud-download"></i> <span
                                                style="font-weight:600 !important; font-size:12px !important;">DOWNLOAD</span>
                                        </h4>
                                        <div class="progress w-100">
                                            <div class="progress-bar bg-dark w-100" role="progressbar" aria-valuenow="60"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-4 py-3 ps-0">
                                    <a class="col-4 py-3 ps-0" href="<?php echo e(meta_download_link('ios')); ?>" target="_blank">
                                        <div class="d-flex mb-2">
                                            <i class="fa fa-apple" style="font-size:20px; "></i>&nbsp
                                            <p class="text-xs mt-1 mb-0 font-weight-bold">iOS</p>

                                        </div>
                                        <h4 class="font-weight-bolder"><i class="fa fa-cloud-download"></i>
                                            <span
                                                style="font-weight:600 !important; font-size:12px !important;">DOWNLOAD</span>
                                        </h4>
                                        <div class="progress w-100">
                                            <div class="progress-bar bg-dark w-100" role="progressbar" aria-valuenow="90"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-4 py-3 ps-0">
                                    <a class="col-4 py-3 ps-0" href="<?php echo e(meta_download_link('android')); ?>"
                                        target="_blank">
                                        <div class="d-flex mb-2">
                                            <i class="fa fa-android" style="font-size:20px;"></i>&nbsp
                                            <p class="text-xs mt-1 mb-0 font-weight-bold">Android</p>
                                        </div>
                                        <h4 class="font-weight-bolder"><i class="fa fa-cloud-download"></i> <span
                                                style="font-weight:600 !important; font-size:12px !important;">DOWNLOAD</span>
                                        </h4>
                                        <div class="progress w-100">
                                            <div class="progress-bar bg-dark w-100" role="progressbar" aria-valuenow="30"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card z-index-2">
                    <div class="card-header pb-0">
                        <h6> <?php echo e(__('page.overview')); ?></h6>
                        <p class="text-sm d-none">
                            <i class="fa fa-arrow-up text-success d-none"></i>
                            <span class="font-weight-bold d-none">4% more</span> in 2021
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" height="388"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div id="globe" class="position-absolute end-0 top-10 mt-sm-3 mt-7 me-lg-7">
                    <canvas width="700" height="600"
                        class="w-lg-100 h-lg-100 w-75 h-75 me-lg-0 me-n10 mt-lg-5"></canvas>
                </div>
            </div>
        </div>
        <!-- contest modal -->
        <!-- Modal -->
        <div class="modal fade" id="modal_contest" tabindex="-1" role="dialog" aria-labelledby="contest-modal-label"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contest-modal-label">Joining to contest</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="contest-table">
                            <tr>
                                <th>Title</th>
                                <th>:</th>
                                <td><span id="contest-title">Super X-10</span></td>
                            </tr>
                            <tr>
                                <th>Contest</th>
                                <th>:</th>
                                <td><span id="contest-on">On profit</span></td>
                            </tr>
                            <tr>
                                <th>Start date</th>
                                <th>:</th>
                                <td><span id="start-date">20 jun 2021</span></td>
                            </tr>
                            <tr>
                                <th>End date</th>
                                <th>:</th>
                                <td><span id="end-date">30 jun 2023</span></td>
                            </tr>
                            <tr>
                                <th>
                                    Prices
                                </th>
                                <th>:</th>
                                <td>
                                    <table id="prices-table">
                                        <tr>
                                            <th>First price </th>
                                            <th>: </th>
                                            <td>$ 23</td>
                                        </tr>
                                        <tr>
                                            <th>Second price </th>
                                            <th>: </th>
                                            <td>$ 20</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <form class="modal-footer" id="form-join-contest" action="" method="post">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn bg-gradient-primary" id="btn-request-join"
                            data-btnid="btn-request-join" data-form="form-join-contest"
                            data-callback="join_contest_callback"
                            data-loading="<i class='fa-spin fas fa-circle-notch'></i>" onclick="_run(this)">Join
                            Now</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- contest popup -->
        <?php
            $hasNonParticipateContest = \App\Services\contest\ContestService::has_non_participate_contest(auth()->user()->id);
            \Log::info('Contest popup visibility check', [
                'user_id' => auth()->user()->id,
                'has_non_participate_contest' => $hasNonParticipateContest,
                'timestamp' => now()
            ]);
        ?>
        <?php if($hasNonParticipateContest): ?>
            <div class="modal fade" id="contest-popup" tabindex="-1" role="dialog"
                aria-labelledby="modal-notification" aria-hidden="true">
                <form class="modal-dialog modal-danger modal-dialog-centered modal-lg" role="document">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="contest_id"
                        value="<?php echo e(array_key_exists('id', $contest_data) ? $contest_data['id'] : ''); ?>">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-header border-0">
                            <button type="button" class="btn-close text-dark btn-popup-close" data-bs-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="py-3 text-center">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="<?php echo e(route('users.participate-contest')); ?>"><img class="img-fluid"
                                                src="<?php echo e(array_key_exists('file_url', $contest_data) ? $contest_data['file_url'] : ''); ?>"
                                                alt="<?php echo e(array_key_exists('contest_name', $contest_data) ? $contest_data['contest_name'] : ''); ?>"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>
        <!-- include footer -->
        <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <!--<div class="modal fade" id="popup-image" tabindex="-1" role="dialog" aria-labelledby="modal-notification"-->
    <!--    aria-hidden="true">-->
    <!--    <form class="modal-dialog modal-danger modal-dialog-centered modal-lg" role="document" id="popup-form">-->
    <!--        <?php echo csrf_field(); ?>-->
    <!--        <input type="hidden" id="popup-id" name="popup_id"-->
    <!--            value="<?php echo e($popup_data ? $popup_data['popup_id'] : ''); ?>">-->
    <!--        <input type="hidden" id="popup-visibility"-->
    <!--            value="<?php echo e($popup_visibility); ?>">-->
    <!--        <div class="modal-content bg-transparent border-0">-->
    <!--            <div class="modal-body">-->
    <!--                <div class="py-3 text-center">-->
    <!--                    <div class="row">-->
    <!--                        <div class="col-md-12 m-0 p-0">-->
    <!--                            <a href="">-->
    <!--                                <img class="img-fluid"-->
    <!--                                    src="<?php echo e(array_key_exists('file_url', $popup_data) ? $popup_data['file_url'] : ''); ?>"-->
    <!--                                    alt="">-->
    <!--                            </a>-->
    <!--                        </div>-->
    <!--                    </div>-->
                        <!-- Add the button here -->
    <!--                    <div class="row mt-1 float-end">-->
    <!--                        <div class="col-md-12 p-0">-->
    <!--                            <button type="button" class="btn btn-sm btn-danger btn-permanently-close float-start m-1"-->
    <!--                                data-bs-dismiss="modal" aria-label="Close">Permanently Close</button>-->
    <!--                            <button type="button" class="btn btn-sm btn-warning float-end m-1" data-bs-dismiss="modal"-->
    <!--                                aria-label="Close">Close</button>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </form>-->
    <!--</div>-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
    <script src="<?php echo e(asset('/common-js/finance.js')); ?>"></script>
    <script src="<?php echo e(asset('/common-js/data-list.js')); ?>"></script>
    <script>
        // contest popup
        $(document).ready(function() {
            var popup_visibility = $('#popup-visibility').val();
            if (popup_visibility=="visible") {
                $("#popup-image").modal('show');
            } else {
                $("#popup-image").modal('hide');
            }

            $("#contest-popup").modal('show');
        });

        // view popup modal
        $(document).on("click", ".btn-permanently-close", function() {
            var popup_id = $('#popup-id').val();
            $.ajax({
                method: 'GET',
                url: '/user/dashboard/popup-permanently-close/' + popup_id,
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == false) {
                        notify('error', data.message, 'Popup');
                    }
                    if (data.status == true) {
                        notify('success', data.message, 'Popup');
                    }
                }
            });
        });

        // get balance
        $(document).on("click", ".btn-load-ac-balance", function() {
            let $this = $(this);
            let account = $(this).data('id');
            let b = balance_equity($this, account, 'balance'); //finance js

        });
        // get equity
        $(document).on("click", ".btn-load-ac-equity", function() {
            let $this = $(this);
            let account = $(this).data('id');
            let b = balance_equity($this, account, 'equity'); //finance js

        });
        // view contest modal
        $(document).on("click", ".btn-view-contest", function() {
            $("#modal_contest").modal('show');
            $.ajax({
                method: 'GET',
                url: '/user/dashboard/get-contest',
                dataType: 'JSON',
                success: function(data) {
                    $('#contest-title').text(data.title);
                    $("#contest_on").text(data.contest_on);
                    $('#start-date').text(data.start_date);
                    $("#end-date").text(data.end_date);
                    var prices_row = '';
                    $.each(data.prices[0], function(index, value) {
                        prices_row += '<tr><th>' + index + ' </th><th>: </th><td>$ ' + value +
                            '</td></tr>';
                    });
                    $("#prices-table").html(prices_row);
                }
            });
        });
        // join contest callback
        function join_contest_callback(data) {
            if (data.status) {
                notify('success', data.message, 'Join Contest');
            } else {
                notify('error', data.message, 'Join Contest');
            }
        }
        // list plugin start
        var data_list = $("#data-list");
        var dataList = data_list.data_list({
            serverSide: true,
            url: '/user/dashboard',
            listPerPage: 3
        });
        var ctx = document.getElementById("chart-bars").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: JSON.parse('<?php echo $dates; ?>'),
                datasets: [{
                    label: "Trades",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "#fff",
                    data: JSON.parse('<?php echo $trade_count; ?>'),
                    maxBarThickness: 6
                }, ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: 500,
                            beginAtZero: true,
                            padding: 15,
                            font: {
                                size: 14,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#fff"
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            display: false
                        },
                    },
                },
            },
        });


        var ctx2 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
        gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

        new Chart(ctx2, {
            type: "line",
            data: {
                labels: JSON.parse('<?php echo $withdraw_months; ?>'),
                datasets: [{
                        label: "Withdraws",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "var(--custom-primary)",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: JSON.parse('<?php echo $withdraw_amounts; ?>'),
                        maxBarThickness: 6
                    },
                    {
                        label: "Deposits",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#3A416F",
                        borderWidth: 3,
                        backgroundColor: gradientStroke2,
                        fill: true,
                        data: JSON.parse('<?php echo $deposit_amounts; ?>'),
                        maxBarThickness: 6
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#b2b9bf',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#b2b9bf',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });


        (function() {
            const container = document.getElementById("globe");
            const canvas = container.getElementsByTagName("canvas")[0];

            const globeRadius = 100;
            const globeWidth = 4098 / 2;
            const globeHeight = 1968 / 2;

            function convertFlatCoordsToSphereCoords(x, y) {
                let latitude = ((x - globeWidth) / globeWidth) * -180;
                let longitude = ((y - globeHeight) / globeHeight) * -90;
                latitude = (latitude * Math.PI) / 180;
                longitude = (longitude * Math.PI) / 180;
                const radius = Math.cos(longitude) * globeRadius;

                return {
                    x: Math.cos(latitude) * radius,
                    y: Math.sin(longitude) * globeRadius,
                    z: Math.sin(latitude) * radius
                };
            }

            function makeMagic(points) {
                const {
                    width,
                    height
                } = container.getBoundingClientRect();

                // 1. Setup scene
                const scene = new THREE.Scene();
                // 2. Setup camera
                const camera = new THREE.PerspectiveCamera(45, width / height);
                // 3. Setup renderer
                const renderer = new THREE.WebGLRenderer({
                    canvas,
                    antialias: true
                });
                renderer.setSize(width, height);
                // 4. Add points to canvas
                // - Single geometry to contain all points.
                const mergedGeometry = new THREE.Geometry();
                // - Material that the dots will be made of.
                const pointGeometry = new THREE.SphereGeometry(0.5, 1, 1);
                const pointMaterial = new THREE.MeshBasicMaterial({
                    color: "#989db5",
                });

                for (let point of points) {
                    const {
                        x,
                        y,
                        z
                    } = convertFlatCoordsToSphereCoords(
                        point.x,
                        point.y,
                        width,
                        height
                    );

                    if (x && y && z) {
                        pointGeometry.translate(x, y, z);
                        mergedGeometry.merge(pointGeometry);
                        pointGeometry.translate(-x, -y, -z);
                    }
                }

                const globeShape = new THREE.Mesh(mergedGeometry, pointMaterial);
                scene.add(globeShape);

                container.classList.add("peekaboo");

                // Setup orbital controls
                camera.orbitControls = new THREE.OrbitControls(camera, canvas);
                camera.orbitControls.enableKeys = false;
                camera.orbitControls.enablePan = false;
                camera.orbitControls.enableZoom = false;
                camera.orbitControls.enableDamping = false;
                camera.orbitControls.enableRotate = true;
                camera.orbitControls.autoRotate = true;
                camera.position.z = -265;

                function animate() {
                    // orbitControls.autoRotate is enabled so orbitControls.update
                    // must be called inside animation loop.
                    camera.orbitControls.update();
                    requestAnimationFrame(animate);
                    renderer.render(scene, camera);
                }
                animate();
            }

            function hasWebGL() {
                const gl =
                    canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
                if (gl && gl instanceof WebGLRenderingContext) {
                    return true;
                } else {
                    return false;
                }
            }

            function init() {
                if (hasWebGL()) {
                    window
                    window.fetch(
                            "https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard-pro/assets/js/points.json"
                        )
                        .then(response => response.json())
                        .then(data => {
                            makeMagic(data.points);
                        });
                }
            }
            init();
        })();
        // get balance
        $(document).on("click", ".btn-load-ac-balance", function() {
            let $this = $(this);
            let account = $(this).data('id');
            let b = balance_equity($this, account, 'balance'); //finance js

            function hasWebGL() {
                const gl = canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
                if (gl && gl instanceof WebGLRenderingContext) {
                    return true;
                } else {
                    return false;
                }
            }

            function init() {
                if (hasWebGL()) {
                    window
                    window.fetch(
                            "https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard-pro/assets/js/points.json"
                        )
                        .then(response => response.json())
                        .then(data => {
                            makeMagic(data.points);
                        });
                }
            }
            init();
        });
        // // get balance
        // $(document).on("click", ".btn-load-ac-balance", function() {
        //     let $this = $(this);
        //     let account = $(this).data('id');
        //     let b = balance_equity($this, account, 'balance'); //finance js

        // });
        // view contest modal
        $(document).on("click", ".btn-view-contest", function() {
            $("#modal_contest").modal('show');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(App\Services\systems\VersionControllService::get_layout('trader'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\b2b\Downloads\crm\resources\views/traders/dashboard.blade.php ENDPATH**/ ?>