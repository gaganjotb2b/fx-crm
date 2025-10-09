<?php
ini_set('serialize_precision', 14);

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AccountToWalletController;
use App\Http\Controllers\Api\AdminBankAccountController;
use App\Http\Controllers\Api\SoftwareSettingsController;
use App\Http\Controllers\Api\ApiFileUploadController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\AuthSettingsController;
use App\Http\Controllers\Api\BalanceApiController;
use App\Http\Controllers\Api\BankDepositController;
use App\Http\Controllers\Api\banking\BankingController;
use App\Http\Controllers\Api\ClientsController;
use App\Http\Controllers\Api\country\CountryController;
use App\Http\Controllers\Api\CurrencyConvertController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\EmailServiceController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\GetGroupController;
use App\Http\Controllers\Api\IbBalanceReceiveController;
use App\Http\Controllers\Api\IbBalanceSendController;
use App\Http\Controllers\Api\IbBalanceTransferController;
use App\Http\Controllers\Api\IBBankGetController;
use App\Http\Controllers\Api\IbBankingController;
use App\Http\Controllers\Api\IBBankUpdateController;
use App\Http\Controllers\Api\IbCryptoWithdraw;
use App\Http\Controllers\Api\IbDashboardController;
use App\Http\Controllers\Api\IbEmail2StepController;
use App\Http\Controllers\Api\IbPermissionController;
use App\Http\Controllers\Api\IbProfileController;
use App\Http\Controllers\Api\IbWithdrawController;
use App\Http\Controllers\Api\KycConfigController;
use App\Http\Controllers\Api\KycUploadController;
use App\Http\Controllers\Api\pamm\PammProfileListController;
use App\Http\Controllers\Api\pamm\PammOverviewController;
use App\Http\Controllers\Api\pamm\PammProfileController;
use App\Http\Controllers\Api\mam\MamController;
use App\Http\Controllers\Api\copyTrading\CopyTradesReportController;
use App\Http\Controllers\Api\myadmin\TraderProfileController;
use App\Http\Controllers\Api\myadmin\TraderProfileUpdateController;
use App\Http\Controllers\Api\MyClientDepositController;
use App\Http\Controllers\Api\MyClientsController;
use App\Http\Controllers\Api\MyClientWithdrawController;
use App\Http\Controllers\Api\MyIbController;
use App\Http\Controllers\Api\MyIbTreeController;
use App\Http\Controllers\Api\MyLastTransactionController;
use App\Http\Controllers\Api\OpenAccountController;
use App\Http\Controllers\Api\PammController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\PermissionCheckController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SignupSettingsController;
use App\Http\Controllers\Api\SupportTicketController;
use App\Http\Controllers\Api\SuspendController;
use App\Http\Controllers\Api\TestMt4ApiController;
use App\Http\Controllers\Api\TradeCommisionController;
use App\Http\Controllers\Api\TraderBankUpdateController;
use App\Http\Controllers\Api\TraderBankWithdraw;
use App\Http\Controllers\Api\TraderCryptoDepositController;
use App\Http\Controllers\Api\TraderCryptoWithdraw;
use App\Http\Controllers\Api\TraderSignupController;
use App\Http\Controllers\Api\TraderToIbController;
use App\Http\Controllers\Api\TraderToTraderController;
use App\Http\Controllers\Api\TradesController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\WalletToAccountController;
use App\Http\Controllers\Api\WithdrawController;
use App\Http\Controllers\ConfigApiController;
use App\Http\Controllers\traders\B2bDepositController;
use App\Http\Controllers\traders\deposit\nowpay\NowPayController;
use App\Http\Controllers\traders\deposit\WebPayCcGateWayController;
use App\Http\Controllers\traders\praxis\PraxisPaymentController;
use App\Services\OpenLiveTradingAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/position/add', [TradeCommisionController::class, 'store_trade']);
Route::post('/deal/add', [TradeCommisionController::class, 'store_closed_trade']);


Route::prefix('{app_key?}')->middleware(['mobile.app', 'validate.app.key', 'crm.status', 'url.check'])->group(function () {
    Route::get('/configs/logo', [ConfigApiController::class, 'get_logo']);
    Route::get('/system/country', [CountryController::class, 'get_country']);
    // auth----------------------------
    // login
    Route::post('/trader/login', [AuthenticationController::class, 'trader_login']);
    Route::post('/trader/login/otp-check', [AuthenticationController::class, 'login_otp']);

    Route::middleware(['auth:sanctum', 'abilities:server:update'])->group(function () {
        Route::post('/refresh-token', [AuthenticationController::class, 'refresh_token']);
    });
    // forgot password
    Route::post('/forgot/password/send-otp', [ForgotPasswordController::class, 'forgot_password']);
    Route::post('/forgot/password/otp/check', [ForgotPasswordController::class, 'otp_check']);
    Route::post('/forgot/password/set-new', [ForgotPasswordController::class, 'set_new_password']);
    // signup controller
    Route::post('/trader/signup/basic-info', [TraderSignupController::class, 'trader_signup']);
    Route::post('/trader/signup/address', [TraderSignupController::class, 'trader_address']);
    Route::post('/trader/signup/social-links', [TraderSignupController::class, 'trader_socail_link']);
    Route::post('/trader/signup/meta-account', [TraderSignupController::class, 'trader_meta_account']);
    Route::post('/trader/signup/password/confirm', [TraderSignupController::class, 'trader_password']);

    Route::get('/group/get/{platform}', [GetGroupController::class, 'group_get']);
    Route::get('/group/get/leverage/{group}', [GetGroupController::class, 'group_get_leverage']);
    Route::get('/signup/active-tab', [SignupSettingsController::class, 'get_settings']);
    Route::get('/signup/required-field', [SignupSettingsController::class, 'get_required_field']);
    // default sanctum middleware
    Route::middleware(['auth:sanctum', 'abilities:*'])->group(function () {
        Route::middleware(['check.user.block.status'])->group(function () {
            // configuration settings 
            // -------------------------
            // check permission
            Route::get('/trader/permissions', [PermissionCheckController::class, 'check_permission']);
            Route::get('/user/search/trader', [ClientsController::class, 'search_trader']);
            Route::get('/user/search/ib', [ClientsController::class, 'search_ib']);
            Route::post('/currency-convert', CurrencyConvertController::class);

            // profile 
            Route::get('/user/get-data', [TraderProfileController::class, 'trader_data_get']);
            Route::post('/trader/profile/update', [TraderProfileUpdateController::class, 'profile_update']);
            // password
            Route::post('/trader/password/reset', [PasswordController::class, 'reset_password']);
            Route::post('/trader/password/change', [PasswordController::class, 'change_password']);
            // transaction pin
            Route::post('/trader/transaction/pin/reset', [PasswordController::class, 'reset_transaction_pin']);
            Route::post('/trader/transaction/pin/change', [PasswordController::class, 'change_transaction_pin']);
            // get client groups
            Route::post('/trader/client-group', [OpenAccountController::class, 'get_groups']);
            //trading account
            Route::post('/trader/open-account', [OpenAccountController::class, 'open_account']);
            Route::post('/trader/leverage/change', [AccountController::class, 'change_leverage']);
            Route::post('/trader/change/account/password', [AccountController::class, 'change_password']);
            Route::post('/trader/change/account/investor-password', [AccountController::class, 'change_investor_password']);
            Route::get('/trader/balance/equity/get', [AccountController::class, 'balance_equity']);
            Route::get('/trader/account/group/leverage', [AccountController::class, 'get_account_leverage']);
            // finance
            Route::get('/finance/all', [BalanceApiController::class, 'all_finance']);
            Route::get('/trader/get-current-balance', [BalanceApiController::class, 'get_balance']);
            Route::get('/trader/get-deposit-amount', [BalanceApiController::class, 'deposit_amount']);
            Route::get('/trader/get-withdraw-amount', [BalanceApiController::class, 'withdraw_amount']);
            Route::get('/trader/get/external/transfer-amount', [BalanceApiController::class, 'external_transfer_amount']);
            Route::get('/trader/get/internal/transfer-amount', [BalanceApiController::class, 'internal_transfer_amount']);
            Route::get('/user/last/transactions', MyLastTransactionController::class);
            // settings
            Route::get('/trader/otp-settings/has-otp', [SoftwareSettingsController::class, 'has_otp']);
            Route::post('/trader/auth-settings/google-2step', [AuthSettingsController::class, 'google_2step']);
            Route::post('/trader/auth-settings/email-2step', [AuthSettingsController::class, 'email_2step']);
            Route::post('/trader/auth-settings/no-auth', [AuthSettingsController::class, 'disable_all']);
            // Route::get('clients/otp-settings/system/get/{otp_for}', [SoftwareSettingsController::class, 'has_admin_otp']);
            Route::post('/trader/otp/settings', [SoftwareSettingsController::class, 'otp_settings']);
            // Banking
            Route::post('/trader/add/bank', [BankingController::class, 'add_bank']);
            Route::post('/trader/update/bank', [TraderBankUpdateController::class, 'bank_update']);
            Route::get('/trader/get/banks', [BankDepositController::class, 'get_client_bank']);

            Route::get('/admin/banks/get', [AdminBankAccountController::class, 'get_admin_active_bank']);
            // deposit 
            Route::post('/trader/bank/deposit', [BankDepositController::class, 'bank_deposit']);
            Route::post('/trader/crypto/deposit', [TraderCryptoDepositController::class, 'crypto_deposit']);
            Route::post('/trader/crypto/deposit/validation', [TraderCryptoDepositController::class, 'deposit_validation']);
            // withdraw
            Route::post('/trader/bank/withdraw', [TraderBankWithdraw::class, 'bank_widraw']);
            Route::post('/trader/bank/withdraw/otp', [TraderBankWithdraw::class, 'otp_check']);
            Route::post('/trader/crypto/withdraw', [TraderCryptoWithdraw::class, 'crypto_withdraw']);
            Route::post('/trader/crypto/withdraw/otp', [TraderCryptoWithdraw::class, 'otp_check']);
            // reports 
            Route::get('/trader/deposit/report', [DepositController::class, 'get_client_deposit']);
            Route::get('/trader/withdraw/report', [WithdrawController::class, 'get_client_withdraw']);
            Route::get('/trader/external/transfer', [TransferController::class, 'get_external_transfer']);
            Route::get('/trader/internal/transfer', [TransferController::class, 'get_internal_transfer']);
            Route::get('/traders/trades', [TradesController::class, 'get_trades_report']);
            Route::get('/traders/trading-accounts', [AccountController::class, 'all_accounts']);
            // transfer, trader to trader, trader to ib, wta, atw
            Route::post('/traders/account-to-wallet/trnasfer', AccountToWalletController::class);
            Route::post('/traders/account-to-wallet/trnasfer/otp', [AccountToWalletController::class, 'otp_check']);
            Route::post('/traders/wallet-to-account/trnasfer', WalletToAccountController::class);
            Route::post('/traders/wallet-to-account/trnasfer/otp', [WalletToAccountController::class, 'otp_check']);
            // external transfer
            Route::post('/trader/trader-to-trader/trnasfer', TraderToTraderController::class);
            Route::post('/trader/trader-to-trader/trnasfer/otp', [TraderToTraderController::class, 'otp_check']);
            Route::post('/trader/trader-to-ib/trnasfer', TraderToIbController::class);
            Route::post('/trader/trader-to-ib/trnasfer/otp', [TraderToIbController::class, 'otp_check']);
            // // support tickets
            Route::post('/trader/supports/create/tickets', [SupportTicketController::class, 'create_ticket']);
            Route::get('/trader/supports/ticket/get', [SupportTicketController::class, 'get_tickets']);
            Route::get('/trader/supports/ticket/reply/{ticket_id}', [SupportTicketController::class, 'client_ticket_details']);
            Route::delete('/trader/supports/delete/ticket/{ticket}', [SupportTicketController::class, 'client_delete_ticket']);
            Route::post('/trader/supports/ticket/reply/{ticket}', [SupportTicketController::class, 'create_client_reply']);
            Route::delete('/trader/supports/ticket/reply/{reply}', [SupportTicketController::class, 'delete_client_reply']);
            // kyc
            Route::get('/trader/kyc/config', KycConfigController::class);
            Route::get('/trader/kyc/document/status', [KycConfigController::class, 'kyc_document_status']);
            Route::post('/trader/kyc/upload', KycUploadController::class);

            // pamm 
            Route::get('/trader/pamm-profile-list', [PammProfileListController::class, 'pammProfileList'])->name('trader.pamm-profile-list');
            Route::get('/trader/pamm-overview/account-details/{ac?}', [PammOverviewController::class, 'pammAccountDetails'])->name('trader.pamm-overview.account-details');
            Route::get('/trader/pamm-overview/open-order/{ac?}', [PammOverviewController::class, 'openOrderReport'])->name('trader.pamm-overview.open-order');
            Route::get('/trader/pamm-overview/close-order/{ac?}', [PammOverviewController::class, 'closeOrderReport'])->name('trader.pamm-overview.close-order');
            // get copy symbols 
            Route::get('/trader/pamm-overview/get-copy-symbols', [PammOverviewController::class, 'getCopySymbols'])->name('trader.pamm-overview.get-copy-symbols');
            // monthly line chart
            Route::get('/trader/pamm-overview/monthly-line_chart/{ac?}', [PammOverviewController::class, 'monthlyLineChart'])->name('trader.pamm-overview.monthly-line_chart');
            // daily line chart
            Route::get('/trader/pamm-overview/daily-line_chart/{ac?}', [PammOverviewController::class, 'dailyLineChart'])->name('trader.pamm-overview.daily-line_chart');
            // hourly line chart
            Route::get('/trader/pamm-overview/hourly-line_chart/{ac?}', [PammOverviewController::class, 'hourlyLineChart'])->name('trader.pamm-overview.hourly-line_chart');
            // copy master
            Route::post('/trader/pamm-overview/copy-master/{ac?}', [PammOverviewController::class, 'copyMaster'])->name('trader.pamm-overview.copy-master');
            // trader PAMM registration
            Route::post('/trader/pamm-registration', [PammProfileController::class, 'pammRegistration'])->name('trader.pamm-registration');

            // mam: manage slave account
            Route::get('/trader/mam/manage-slave-account/{ac?}', [MamController::class, 'slaveAccountList'])->name('trader.mam.manage-slave-account');
            Route::post('/trader/mam/add-slave-account', [MamController::class, 'addSlaveAccount'])->name('trader.mam.add-slave-account');
            Route::post('/trader/mam/delete-slave-account', [MamController::class, 'deleteSlaveAccount'])->name('trader.mam.delete-slave-account');
            Route::post('/trader/mam/delete-symbol', [MamController::class, 'deleteSymbol'])->name('trader.mam.delete-symbol');
            Route::post('/trader/mam/add-symbol', [MamController::class, 'addSymbol'])->name('trader.mam.add-symbol');
            Route::post('/trader/mam/update-symbol-status', [MamController::class, 'updateSymbolStatus'])->name('trader.mam.update-symbol-status');

            // mam: manage slave account
            Route::get('/trader/copy-trader/copy-trades-report', [CopyTradesReportController::class, 'copyTradesReport'])->name('trader.copy-trader.copy-trades-report');

            // for IB 
            // *******************************************************************************
            // ib dashabord
            Route::get('/ib/dashboard', [IbDashboardController::class, 'dashboard']);
            Route::post('/ib/profile/update', [IbProfileController::class, 'profile_update']);

            // security settings
            // Route::post('/ib/auth-settings/google-2step', [AuthSettingsController::class, 'google_2step']);
            Route::post('/ib/auth-settings/email-2step', [IbEmail2StepController::class, 'email_2step']);
            Route::post('/ib/auth-settings/no-auth', [IbEmail2StepController::class, 'disable_all']);

            Route::post('/ib/withdraw/bank', IbWithdrawController::class);
            Route::post('/ib/withdraw/bank/otp-check', [IbWithdrawController::class, 'otp_check']);
            Route::post('/ib/crypto/withdraw', [IbCryptoWithdraw::class, 'crypto_withdraw']);
            Route::post('/ib/crypto/withdraw/otp', [IbCryptoWithdraw::class, 'otp_check']);
            // reports
            Route::get('/ib/report/trade-commission', TradeCommisionController::class);
            Route::get('/ib/report/withdraw', [IbWithdrawController::class, 'get_ib_withdraw']);
            Route::get('ib/report/balance-send', IbBalanceSendController::class);
            Route::get('/ib/report/balance-receive', IbBalanceReceiveController::class);
            // affiliate
            Route::get('/ib/affiliate/my-ibs', MyIbController::class);
            Route::get('/ib/affiliate/my-clients', MyClientsController::class);
            Route::get('/ib/affiliate/tree', MyIbTreeController::class);
            Route::get('/ib/affiliate/my-client-deposit', MyClientDepositController::class);
            Route::get('/ib/affiliate/my-client-withdraw', MyClientWithdrawController::class);
            // transfer
            Route::post('/ib/transfer/ib-to-trader', [IbBalanceTransferController::class, 'ib_to_trader']);
            Route::post('/ib/transfer/ib-to-trader/otp', [IbBalanceTransferController::class, 'ib_to_trader_otp']);
            Route::post('/ib/transfer/ib-to-ib', [IbBalanceTransferController::class, 'ib_to_ib']);
            Route::post('/ib/transfer/ib-to-ib/otp', [IbBalanceTransferController::class, 'ib_to_ib_otp']);
            // banking
            Route::post('/ib/banking/bank-add', [IbBankingController::class, 'bank_add']);
            Route::post('/ib/banking/bank-update', [IBBankUpdateController::class, 'bank_update']);
            Route::get('/ib/banking/get-bank', [IBBankGetController::class, 'get_bank']);

            // ib permission
            Route::get('/ib/settings/get-permission', [IbPermissionController::class, 'get_permission']);
        });
    });
});
// payment gateways for crm
Route::any('nowpay/payment/notification', [NowPayController::class, 'notification']);
Route::get('praxis/payment/notification', [PraxisPaymentController::class, 'notification']);
Route::any('b2binpay/payment/notification', [B2bDepositController::class, 'notification']);
Route::any('webpay/payment/notification', [WebPayCcGateWayController::class, 'notification']);

// change crm status
Route::prefix('{app_key}')->middleware(['validate.app.key'])->group(function () {
    Route::post('/suspend/mobile/app', [SuspendController::class, 'mobile_app_suspend']);
    Route::post('/unsuspend/mobile/app', [SuspendController::class, 'mobile_app_unsuspend']);

    Route::post('/suspend/crm/app', [SuspendController::class, 'crm_suspend']);
    Route::post('/unsuspend/crm/app', [SuspendController::class, 'crm_unsuspend']);
});
