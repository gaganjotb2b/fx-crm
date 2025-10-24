
<?php $__env->startSection('title', 'IB Dashboard'); ?>
<?php $__env->startSection('page-css'); ?>
    <style>
        .ib-balance-dollar {
            font-size: 3rem;
            display: block;
            width: 60px;
            height: 60px;
            text-align: center;
            float: right;
        }

        @media only screen and (max-width: 1718px) {
            .al-pyChart-wrapper {
                flex-direction: column;
            }

            .al-pyChart-wrapper .chart {
                margin-bottom: 10px;
            }
        }

        .light-version .input-group-text {
            background-color: transparent !important;
            border: 1px solid var(--custom-primary) !important;
            color: var(--custom-primary) !important;
        }

        .dark-version .input-group-text {
            background-color: transparent !important;
            border: 1px solid var(--custom-primary) !important;
            color: var(--custom-primary) !important;
        }

        .light-version .input-group-text {
            background-color: transparent !important;
            border: 1px solid var(--custom-primary) !important;
            color: var(--custom-primary) !important;
        }

        .dark-version .input-group-text {
            background-color: transparent !important;
            border: 1px solid var(--custom-primary) !important;
            color: var(--custom-primary) !important;
        }

        .input-group.referale-link-2.focused {
            box-shadow: 0 0 0 2px #82d616 !important;
            transition: box-shadow 0.15s ease, border-color 0.15s ease;
        }

        .input-group.referale-link-2 .form-control:focus {
            border-left-color: #82d616 !important;
            border-right-color: #82d616 !important;
        }

        .dark-version .input-group-text {
            display: inline-block !important;
            padding-top: 10px;
            padding-bottom: 10px;
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
        </ol>
        <h6 class="font-weight-bolder mb-0"><?php echo e(__('page.ib-area')); ?></h6>
    </nav>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php
        use App\Services\AllFunctionService;
        use App\Services\IBManagementService;
    ?>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-7 col-md-12 mt-4 mt-lg-0">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?php echo e(__('page.referale-links')); ?></h6>
                               
                            </div>
                            <button type="button"
                                class="btn btn-icon-only btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title=""
                                data-bs-original-title="See which ads perform better">
                                <i class="fas fa-info" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center visually-hidden">
                            <span class="badge badge-md badge-dot me-4">
                                <i class="bg-success"></i>
                                <span class="text-dark text-xs"><?php echo e(__('page.facebook-ads')); ?></span>
                            </span>
                            <span class="badge badge-md badge-dot me-4">
                                <i class="bg-dark"></i>
                                <span class="text-dark text-xs"><?php echo e(__('page.google-ads')); ?></span>
                            </span>
                        </div>
                    </div>
                    <?php $kyc_status = auth()->user()->kyc_status; ?>
                    <?php if(IBManagementService::referralLinkStatus()): ?>
                        <!-- referral links -->
                    
                        <div class="card-body p-3 trd-referal">
                            <div class="input-group mb-3 referale-link-2">
                                <!-- <span class="input-group-text bg-gradient-faded-light"><b class="ms-1">Trader</b></span> -->
                                <input type="text" class="form-control" id="trader-referral-link" placeholder="https://"
                                    aria-label="https://" aria-describedby="button-addon2" value="<?php echo e($trader_referral); ?>">
                                <button class="btn btn-outline-success mb-0 input-group-text text-white px-3" type="button"
                                    id="referale-link-2" style="color:#82d616 !important; border-color:#82d616 !important">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <!-- end referral links -->
                    <?php else: ?>
                        <!-- kyc status alart -->
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                                <span class="alert-text">
                                    <strong>Warning!</strong>
                                    <a href="<?php echo e(route('ib.ib-admin-account-verification')); ?>"
                                        class="text-decoration-underline">
                                        <?php
                                            if ($kyc_status == 2) {
                                                echo 'Please wait while your kyc approve to get your referral links';
                                            } else {
                                                echo 'Please first verify your kyc to get referral link';
                                            }
                                        ?>
                                    </a>!
                                </span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card mt-4">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0"><?php echo e(__('page.last-12-month-ib-commission')); ?></h6>
                            <button type="button"
                                class="btn btn-icon-only btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title=""
                                data-bs-original-title="See which ads perform better">
                                <i class="fas fa-info" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center d-none">
                            <span class="badge badge-md badge-dot me-4">
                                <i class="bg-success"></i>
                                <span class="text-dark text-xs"><?php echo e(__('page.facebook-ads')); ?></span>
                            </span>
                            <span class="badge badge-md badge-dot me-4">
                                <i class="bg-dark"></i>
                                <span class="text-dark text-xs"><?php echo e(__('page.google-ads')); ?></span>
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="line-chart-gradient" class="chart-canvas" height="582"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 ms-auto mt-xl-0 mt-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-gradient-primary">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8 my-auto">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-capitalize font-weight-bold opacity-7">
                                                <?php echo e(__('Wallet Balance')); ?>

                                            </p>
                                            <h5 class="text-white font-weight-bolder mb-0">
                                                <?php echo e($ib_balance); ?>

                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span
                                            class="ib-balance-dollar bg-body rounded-circle d-flex content-center justify-content-center align-items-center text-primary font-weight-bold">&dollar;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <h1 class="text-gradient text-primary"><span id="status1"
                                        countto="21"><?php echo e($total_commission); ?></span> <span
                                        class="text-lg ms-n2">$</span>
                                </h1>
                                <h6 class="mb-0 font-weight-bolder"><?php echo e(__('page.total-commission')); ?></h6>
                                <p class="opacity-8 mb-0 text-sm"><?php echo e(__('page.all-commission')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <h1 class="text-gradient text-primary"> <span id="status2"
                                        countto="<?php echo e($total_trader); ?>"><?php echo e($total_trader); ?></span> <span
                                        class="text-lg ms-n1"></span></h1>
                                <h6 class="mb-0 font-weight-bolder"><?php echo e(__('page.total-trader')); ?></h6>
                                <p class="opacity-8 mb-0 text-sm"><?php echo e(__('page.all-trader')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-md-0 mt-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h1 class="text-gradient text-primary"><span id="status4"
                                        countto="<?php echo e($total_sub_ib); ?>"><?php echo e($total_sub_ib); ?></span> <span
                                        class="text-lg ms-n2"></span></h1>
                                <h6 class="mb-0 font-weight-bolder"><?php echo e(__('page.sub-ib')); ?></h6>
                                <p class="opacity-8 mb-0 text-sm"><?php echo e(__('page.all-level')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <h1 class="text-gradient text-primary"><span id="status3"
                                        countto="87"><?php echo e($client_deposit_balance); ?></span> <span
                                        class="text-lg ms-n2"></span></h1>
                                <h6 class="mb-0 font-weight-bolder"><?php echo e(__('page.total-client-deposit')); ?></h6>
                                <p class="opacity-8 mb-0 text-sm"><?php echo e(__('page.all-time')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-md-0 mt-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h1 class="text-gradient text-primary"><span id="status3"
                                        countto="87"><?php echo e($client_withdraw_balance); ?></span> <span
                                        class="text-lg ms-n2"></span></h1>
                                <h6 class="mb-0 font-weight-bolder">Total Client Withdraw</h6>
                                <p class="opacity-8 mb-0 text-sm"><?php echo e(__('page.all-time')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <h1 class="text-gradient text-primary"><span id="status3"
                                        countto="87"><?php echo e($todays_ib_erning); ?></span> <span class="text-lg ms-n2"></span>
                                </h1>
                                <h6 class="mb-0 font-weight-bolder"><?php echo e(__('page.todays-earning')); ?></h6>
                                <p class="opacity-8 mb-0 text-sm"><?php echo e(__('page.24-hours')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-md-0 mt-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h1 class="text-gradient text-primary"><span id="status4"
                                        countto="<?php echo e($yesterday_ib_erning); ?>"><?php echo e($yesterday_ib_erning); ?></span> <span
                                        class="text-lg ms-n2"></span></h1>
                                <h6 class="mb-0 font-weight-bolder"><?php echo e(__('page.yesterday-earning')); ?></h6>
                                <p class="opacity-8 mb-0 text-sm"><?php echo e(__('page.24-hours')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-6 ms-auto">
                <div class="card" style="min-height:275px">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0"><?php echo e(__('page.commission-by-instruments')); ?></h6>
                            <button type="button"
                                class="btn btn-icon-only btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center ms-auto"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="See the consumption per room">
                                <i class="fas fa-info"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-5 text-center">
                                <div class="chart">
                                    <canvas id="chart-consumption" class="chart-canvas" height="197"></canvas>
                                </div>
                                <h4 class="font-weight-bold mt-n8">
                                    <span><?php echo e($apx_lot); ?></span>
                                    <span class="d-block text-body text-sm"><?php echo e(__('page.apx-lots')); ?></span>
                                </h4>
                            </div>
                            <div class="col-7">
                                <div class="table-responsive" id="instrument-scrollbar" style="max-height: 186px;">
                                    <table class="table align-items-center mb-0">
                                        <tbody>
                                            <?php for($i = 0; $i < count($all_instrument_percent['instruments']); $i++): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-0">
                                                            <span class="badge  me-3"
                                                                style="background-color: <?= rand_color() ?>"> </span>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">
                                                                    <?php echo e($all_instrument_percent['instruments'][$i]); ?>

                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-center text-sm">
                                                        <span class="text-xs font-weight-bold">
                                                            <?php echo e($all_instrument_percent['amount_percents'][$i]); ?> % </span>
                                                    </td>
                                                </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-lg-0 mt-4">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card" style="height: 275px">
                            <div class="card-body p-3">
                                <h6><?php echo e(__('page.commission-day-chart')); ?></h6>
                                <div class="chart pt-3">
                                    <canvas id="chart-cons-week" class="chart-canvas" height="170"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mt-sm-0 mt-4">
                        <div class="card h-100">
                            <div class="card-body text-center p-3">
                                <h6 class="text-start"><?php echo e(__('page.commission-by-referrance')); ?></h6>
                                <div class="d-flex justify-content-between align-items-center al-pyChart-wrapper">
                                    <div class="chart" style="width: 233px">
                                        <canvas id="pie-chart" class="chart-canvas" height="140"></canvas>
                                    </div>
                                    <p class="ps-1 mb-0">
                                        <span class="text-xs d-flex align-items-center">
                                            <span
                                                style="background-color:#1da2e6;width: 20px; height:20px; border-radius:7px; margin-right:5px;"></span>
                                            <?php echo e(__('page.my-trader')); ?>

                                        </span>
                                        <span class="px-3"></span>
                                        <span class="text-xs d-flex align-items-center">
                                            <span
                                                style="background-color:#cb0c9f; width: 20px; height:20px; border-radius:7px; margin-right:5px;">

                                            </span>
                                            <?php echo e(__('page.subib-trader')); ?>

                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <hr class="horizontal dark my-5"> -->
        <!-- include footer -->
        <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <!--<div class="modal fade" id="popup-image" tabindex="-1" role="dialog" aria-labelledby="modal-notification"-->
    <!--    aria-hidden="true">-->
    <!--    <form class="modal-dialog modal-danger modal-dialog-centered modal-lg" role="document" id="popup-form">-->
    <!--        <?php echo csrf_field(); ?>-->
    <!--        <input type="hidden" id="popup-id" name="popup_id"-->
    <!--            value="<?php echo e($popup_data ? $popup_data['popup_id'] : ''); ?>">-->
    <!--        <input type="hidden" id="popup-visibility" value="<?php echo e($popup_visibility); ?>">-->
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
    <!--                            <button type="button" class="btn btn-sm btn-warning float-end m-1"-->
    <!--                                data-bs-dismiss="modal" aria-label="Close">Close</button>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </form>-->
    <!--</div>-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-js'); ?>
    <script src="<?php echo e(asset('/common-js/copy-js.js')); ?>"></script>
    <script>
        // popup modal
        $(document).ready(function() {
            var popup_visibility = $('#popup-visibility').val();
            if (popup_visibility=="visible") {
                $("#popup-image").modal('show');
            } else {
                $("#popup-image").modal('hide');
            }
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
        
        // smoot scrollbar
        var Scrollbar = window.Scrollbar;
        Scrollbar.init(document.querySelector('#instrument-scrollbar'));
        // refer link
        $(document).on("click", "#referale-link-1", function() {
            // copy js
            $("#ib-referral-link").select();
            copy_to_clipboard("ib-referral-link"); //provide id of text container
        });
        $(document).on("click", "#referale-link-2", function() {
            // copy js
            $("#trader-referral-link").select();
            copy_to_clipboard("trader-referral-link"); //provide id of text container
        });
        var ctx2 = document.getElementById("line-chart-gradient").getContext("2d");

        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);


        gradientStroke1.addColorStop(1, 'rgba(0,206,146,0.2)'); // Starting color #00ce92 with 0.2 opacity
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)'); // Middle color, you can adjust as per your design
        gradientStroke1.addColorStop(0, 'rgba(0,206,146,0)'); // Ending color, transparent #00ce92

        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
        gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

        new Chart(ctx2, {
            type: "line",
            data: {
                labels: JSON.parse('<?php echo $commission_months; ?>'),
                datasets: [{
                        label: "My Trader",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#1da2e6",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: JSON.parse('<?php echo $commission_amounts; ?>'),
                        maxBarThickness: 6

                    },
                    {
                        label: "My Team",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#00ce92",
                        borderWidth: 3,
                        backgroundColor: gradientStroke2,
                        fill: true,
                        data: JSON.parse('<?php echo $withdraw_amounts; ?>'),
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

        // Chart Doughnut Consumption by room
        var ctx1 = document.getElementById("chart-consumption").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

        new Chart(ctx1, {
            type: "doughnut",
            data: {
                labels: JSON.parse('<?php echo $instruments; ?>'),
                datasets: [{
                    label: "Consumption",
                    weight: 9,
                    cutout: 90,
                    tension: 0.9,
                    pointRadius: 2,
                    borderWidth: 2,
                    backgroundColor: JSON.parse('<?php echo $instrument_backround; ?>'),
                    data: JSON.parse('<?php echo $instruments_amount; ?>'),
                    fill: false
                }],
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
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            display: false,
                        }
                    },
                },
            },
        });
        // Chart Consumption by day
        var ctx = document.getElementById("chart-cons-week").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: JSON.parse('<?php echo $commission_day_chart_days; ?>'),
                datasets: [{
                    label: "Watts",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "#3A416F",
                    data: JSON.parse('<?php echo $commission_day_chart_value; ?>'),
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
                            display: false
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
                            beginAtZero: true,
                            font: {
                                size: 12,
                                family: "Open Sans",
                                style: 'normal',
                            },
                            color: "#9ca2b7"
                        },
                    },
                    y: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#9ca2b7'
                        }
                    },
                    x: {
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
                            color: '#9ca2b7'
                        }
                    },
                },
            },
        });
        // Pie chart
        var ctx4 = document.getElementById("pie-chart").getContext("2d");

        new Chart(ctx4, {
            type: "pie",
            data: {
                labels: ['My Trader', 'Sub IB Trader'],
                datasets: [{
                    label: "Projects",
                    weight: 9,
                    cutout: 0,
                    tension: 0.9,
                    pointRadius: 2,
                    borderWidth: 2,
                    backgroundColor: ['#1da2e6', '#cb0c9f'],
                    data: ["<?php echo $my_trader_commission; ?>", "<?php echo $sub_ib_trader_com; ?>"],
                    fill: false
                }],
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
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            display: false,
                        }
                    },
                },
            },
        });

        // get balance
        $(document).on("click", ".btn-load-ac-balance", function() {
            let $this = $(this);
            let account = $(this).data('id');
            balance_equity($this, account, 'balance'); //finance js
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(\App\Services\CombinedService::is_combined('client') == true && \App\Services\CombinedService::is_single_portal() == true ? 'layouts.trader-layout' : 'layouts.ib-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\code\fxcrm\fx\crm-new-laravel\resources\views/ibs/dashboard.blade.php ENDPATH**/ ?>