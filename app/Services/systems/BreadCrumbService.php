<?php

namespace App\Services\systems;

class BreadCrumbService
{
    public static function bread_crumb($menu)
    {
        switch (request()->route()->getName()) {
                // page profile overview
            case 'user.user-admin.profile-overview':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.my-admin'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.profile-overview'),
                        'link' => '',
                    ]);
                }
                break;
                // page user settings
            case 'user.user-admin-account-settings':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.my-admin'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.settings'),
                        'link' => '',
                    ]);
                }
                break;
                // page verification
            case 'user.user-admin-account-verification':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.my-admin'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.verification'),
                        'link' => '',
                    ]);
                }
                break;
                // page banking
            case 'user.user-admin.user-banking':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.my-admin'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.banking'),
                        'link' => '',
                    ]);
                }
                break;
                // open trading account demo
                // trader
            case 'user.trading-account.open-demo-account':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.trading_account'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.open-demo-account'),
                        'link' => '',
                    ]);
                }
                break;
                // open trading account live
                // trader
            case 'user.trading.open-account':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.trading_account'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.open-live-account'),
                        'link' => '',
                    ]);
                }
                break;
                // trading account settings
            case 'user.trading-account.settings':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.trading_account'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.settings'),
                        'link' => '',
                    ]);
                }
                break;
                // page deposit report
                // trader
            case 'user.deposit-report':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.reports'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.deposit_report'),
                        'link' => '',
                    ]);
                }
                break;
                // page withdraw report
                // trader
            case 'user.withdraw-report':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.reports'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.withdraw') . ' ' . __('page.reports'),
                        'link' => '',
                    ]);
                }
                break;
                // page external transfer report
                // trader
            case 'user.external-report':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.reports'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.external_transfer_report'),
                        'link' => '',
                    ]);
                }
                break;
                // page internal transfer report
                // trader
            case 'user.internal-report':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.reports'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.internal_transfer_report'),
                        'link' => '',
                    ]);
                }
                break;
                // page trading reports
                // trader
            case 'user.trading-report':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.reports'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.trading_report'),
                        'link' => '',
                    ]);
                }
                break;
                // page ib transfer reports
                // trader
            case 'user.trading.ib-report':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.reports'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.ib_transfer_report'),
                        'link' => '',
                    ]);
                }
                break;
                // page bank withdraw
                // trader
            case 'user.withdraw.bank-withdraw-form':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.withdraw'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.bank_withdraw'),
                        'link' => '',
                    ]);
                }
                break;
            case 'user.withdraw.gcash-index':
                // page gcash withdraw
                // trader
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.withdraw'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => 'Gcash Withdraw',
                        'link' => '',
                    ]);
                }
                break;
                // crypto withdraw
                // trader
            case 'user.withdraw.crypto-withdraw-form':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.withdraw'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.crypto_withdraw'),
                        'link' => '',
                    ]);
                }
                break;
                // crypto deposit
                // trader
            case 'user.deposit.crypto-deposit-form':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.deposit'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.crypto_deposit'),
                        'link' => '',
                    ]);
                }
                break;
                // bank deposit
                // trader
            case 'user.deposit.bank-deposit-form':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.deposit'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('finance.Bank Deposit'),
                        'link' => '',
                    ]);
                }
                break;
                // perfect money deposit
                // trader
            case 'user.deposit.perfect-money-deposit':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.deposit'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.perfect_money_deposit'),
                        'link' => '',
                    ]);
                }
                break;
                // skrill withdraw
                // trader
            case 'user.withdraw.skrill-withdraw-form':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.withdraw'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('ad-reports.skrill'),
                        'link' => '',
                    ]);
                }
                break;
                // netteler withdraw
                // trader
            case 'user.withdraw.neteller-withdraw-form':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.withdraw'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('ad-reports.neteller') . ' ' . __('page.withdraw'),
                        'link' => '',
                    ]);
                }
                break;
                // wallet to account transfer
                // trader
            case 'user.transfer.wallet-to-account-transfer':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.transfer'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.wallet-to-account'),
                        'link' => '',
                    ]);
                }
                break;
                // account to wallet transfer
                // trader
            case 'user.transfer.account-to-wallet-transfer':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.transfer'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.account-to-wallet'),
                        'link' => '',
                    ]);
                }
                break;
                // trader to trader
                // trader
            case 'user.transfer.trader-to-trader-transfer':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.transfer'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.trader-to-trader'),
                        'link' => '',
                    ]);
                }
                break;
                // trader to IB
                // trader
            case 'user.transfer.trader-to-ib-transfer':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.transfer'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.trader-to-ib'),
                        'link' => '',
                    ]);
                }
                break;
                // support ticket 
                // trader
            case 'user.support.ticket':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.support'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('support-ticket.support_ticket'),
                        'link' => '',
                    ]);
                }
                break;
                // pamm profile
                // trader
            case 'user.pamm.profile':
                if ($menu == 'root') {
                    return ([
                        'label' => 'PAMM Profile',
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => 'PAMM Copy Traders Details',
                        'link' => '',
                    ]);
                }
                break;
                // pamm registration
                // trader
            case 'user.pamm.registraion':
                if ($menu == 'root') {
                    return ([
                        'label' => 'PAMM Profile',
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => 'PAMM Registration',
                        'link' => '',
                    ]);
                }
                break;
                // manage slave accounts
                // trader
            case 'user.mam.manage.slave.account':
                if ($menu == 'root') {
                    return ([
                        'label' => 'MAM', //multiple account manager
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => 'Manage Slave Account',
                        'link' => '',
                    ]);
                }
                break;
                // social compy trading report
                // trader
            case 'user.copy.social.traders.report':
                if ($menu == 'root') {
                    return ([
                        'label' => 'Copy Trading',
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => 'Social Copy Trading Reports',
                        'link' => '',
                    ]);
                }
                break;
                // social trades activity reports
                // trader
            case 'user.copy.traders.activities.report':
                if ($menu == 'root') {
                    return ([
                        'label' => 'Copy Trading',
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => 'Social Trade Activity Reports',
                        'link' => '',
                    ]);
                }
                break;
                // contest
                // contest participate
                // trader
            case 'users.participate-contest':
                if ($menu == 'root') {
                    return ([
                        'label' => 'Contest',
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => 'Contest participate',
                        'link' => '',
                    ]);
                }
                break;
                // contest status
            case 'users.contest-status':
                if ($menu == 'root') {
                    return ([
                        'label' => 'Contest',
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => 'Contest Status',
                        'link' => '',
                    ]);
                }
                break;
                // profile overview 
                // ib
            case 'ib.ib-admin.profile-overview':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.my-admin'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.profile-overview'),
                        'link' => '',
                    ]);
                }
                break;
                // settings/my admin
                // ib
            case 'ib.ib-admin-account-settings':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.my-admin'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.settings'),
                        'link' => '',
                    ]);
                }
                break;
                // myadmins/verifications
                // ib
            case 'ib.ib-admin-account-verification':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.my-admin'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.verification'),
                        'link' => '',
                    ]);
                }
                break;
                // myadmins/banking
                // ib
            case 'ib.ib-admin.ib-banking':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.my-admin'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.banking'),
                        'link' => '',
                    ]);
                }
                break;
                // myadmins/ib-tree
                // ib
            case 'ib.my-ib.tree':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.Affiliate'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.IB Tree'),
                        'link' => '',
                    ]);
                }
                break;
                // affiliates/my clitens
                // ib
            case 'ib.myclients.report':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.Affiliate'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' =>  __('page.My Clients'),
                        'link' => '',
                    ]);
                }
                break;
                // affiliates / my ib
                // ib
            case 'ib.my-ib.report':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.Affiliate'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' =>   __('page.My IB'),
                        'link' => '',
                    ]);
                }
                break;
                // affiliat / deposit reports
                // ib
            case 'ib.affilates.deposit-reports':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.Affiliate'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' =>   __('page.deposit-report'),
                        'link' => '',
                    ]);
                }
                break;
                // affiliate / withdraw report
                // ib
            case 'affiliats.withdraw-reports':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.Affiliate'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' =>   'Withdraw Reports',
                        'link' => '',
                    ]);
                }
                break;
                // reprots / tradecommission
                // ib
            case 'ib.ib-commission-report.ib-area':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.reports'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' =>   'Trade Commission',
                        'link' => '',
                    ]);
                }
                break;
                // reports / withdraw reports
                // ib
            case 'ib.reports.ib-withdraw-reports':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.reports'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' =>   __('page.withdraw-report'),
                        'link' => '',
                    ]);
                }
                break;
                // reports / ib to trader balance transfer
                // ib
            case 'ib.reports.ib-to-trader-transfer':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.reports'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' =>   __('page.ib-to-trader-transfer'),
                        'link' => '',
                    ]);
                }
                break;
                // reports / trader to ib balance transfer
                // ib
            case 'ib.reports.trader-to-ib-transfer':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.reports'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' =>   __('page.trader-to-ib-transfer'),
                        'link' => '',
                    ]);
                }
                break;
                // withdraw / Bank Withdraw
                // ib
            case 'ib.withdraw.bank-withdraw':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.withdraw'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' =>   __('page.bank_withdraw'),
                        'link' => '',
                    ]);
                }
                break;
                // withdraw / crypto withdraw
                // ib
            case 'ib.withdraw.crypto-withdraw':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.withdraw'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.crypto_withdraw'),
                        'link' => '',
                    ]);
                }
                break;
                // ib to trader transfer
                // ib
            case 'ib.transfer.ib-to-trader':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.transfer'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => 'IB to Trader',
                        'link' => '',
                    ]);
                }
                break;
            case 'ib.transfer.ib-to-ib':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.transfer'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => 'IB to IB',
                        'link' => '',
                    ]);
                }
                break;
                // ib to trader transfer
                // ib
            case 'ib.transfer.ib-to-trader':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.support'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('support-ticket.support_ticket'),
                        'link' => '',
                    ]);
                }
                break;
                // ib balance send
            case 'ib.reports.balance-send-reports':
                if ($menu == 'root') {
                    return ([
                        'label' => 'Transfer',
                        'link' => 'IB Balance Send',
                    ]);
                } else {
                    return ([
                        'label' => 'IB Balance Send',
                        'link' => '',
                    ]);
                }
                break;
                // ib balance Recived
            case 'ib.reports.balance-recived-reports':
                if ($menu == 'root') {
                    return ([
                        'label' => 'Transfer',
                        'link' => 'IB Balance Recived',
                    ]);
                } else {
                    return ([
                        'label' => 'IB Balance Recived',
                        'link' => '',
                    ]);
                }
                break;
                // pamm profile details
                // trader
            case 'user.pamm.copy.traders':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.pamm-profile'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => __('page.copy-traders-details'),
                        'link' => '',
                    ]);
                }
                break;
                // trader pamm overview
            case 'user.pamm.trader.overview':
                if ($menu == 'root') {
                    return ([
                        'label' => __('page.pamm-profile'),
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => 'PAMM Profile Overview',
                        'link' => '',
                    ]);
                }
                break;
            default:
                if ($menu == 'root') {
                    return ([
                        'label' => '',
                        'link' => '',
                    ]);
                } else {
                    return ([
                        'label' => '',
                        'link' => '',
                    ]);
                }
                break;
        }
    }
    // *******************************************************************
    // get ib breadcrumb
    // *******************************************************************
    public static function get_ib_breadcrumb()
    {
        $root_item = self::bread_crumb('root');
        $root_item_label = $root_item['label'];
        $child_item = self::bread_crumb('child');
        $child_item_label = $child_item['label'];
        if (VersionControllService::check_version() === 'lite') {
            return ('<nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-container breadcrumb-container-light bg-body mb-0">
                            <li class="breadcrumb-item"><a href="/">' . __('page.home') . '</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a href="/">' . $root_item_label . '</a></li>
                            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">' . $child_item_label . '</li>
                        </ol>
                    </nav>');
        } else {
            return ('<nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                            <li class="breadcrumb-item text-sm">
                                <a class="opacity-3 text-dark" href="javascript:;">
                                    <svg width="12px" height="12px" class="mb-1" viewBox="0 0 45 40" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>My Admin</title>
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
                                    href="javascript:;">' . $root_item_label . '</a></li>
                            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">' . $child_item_label . '</li>
                        </ol>
                        <h6 class="font-weight-bolder mb-0">' . __('page.ib-area') . '</h6>
                    </nav>');
        }
    }
    // get trader breadcrumb
    public static function get_trader_breadcrumb()
    {
        if (VersionControllService::check_version() === 'lite') {
            return (view('traders.breadcrumb.breadcurmb-lite'));
        } else {
            return (view('traders.breadcrumb.breadcrumb-pro'));
        }
    }
}
