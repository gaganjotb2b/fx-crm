<?php

use App\Http\Controllers\admins\ActivityLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\MigrationController;

use App\Http\Controllers\systems\SystemDashboardController;
use App\Http\Controllers\systems\SystemConfigController;
use App\Http\Controllers\systems\SystemApiConfigurationController;
use App\Http\Controllers\systems\SystemSmtpSetupController;
use App\Http\Controllers\admins\PasswordSettingsController;

// Trader controller
use App\Http\Controllers\traders\TraderDashboardController;

// IB controller
use App\Http\Controllers\IB\IbDashboardController;

use App\Http\Controllers\admins\AdminIBCommissionController;

// Admin controller
use App\Http\Controllers\admins\AdminDashboardController;
use App\Http\Controllers\admins\AdminDepositController;
use App\Http\Controllers\admins\TraderDepositController;
use App\Http\Controllers\admins\AdminLogController;
use App\Http\Controllers\admins\IBadminController;
use App\Http\Controllers\admins\AdminTraderAdminController;
use App\Http\Controllers\admins\RolesController;
use App\Http\Controllers\admins\AdminGroupsController;
use App\Http\Controllers\admins\AdminRegistrationController;
use App\Http\Controllers\admins\RightController;
use App\Http\Controllers\admins\PermissionController;
use App\Http\Controllers\admins\AddManagerController;
use App\Http\Controllers\admins\ManagerGroupController;
use App\Http\Controllers\admins\Managercontroller;
use App\Http\Controllers\admins\ManagerRightController;
use App\Http\Controllers\admins\ManageAllController;

use App\Http\Controllers\admins\LeadManagementController;
use App\Http\Controllers\admins\BankAccountSetupController;
use App\Http\Controllers\admins\settings\DashboardPopupImageController;
use App\Http\Controllers\admins\TradingAccountRequestController;
use App\Http\Controllers\admins\BankRequestController;

// IB Controllers
// ---------------------------------------------------------------------------
use App\Http\Controllers\admins\IBsetupController;
use App\Http\Controllers\admins\IBcommisionStructureController;
use App\Http\Controllers\admins\IbTreeController;
// finance controllers
use App\Http\Controllers\admins\FinanceBalanceController;
use App\Http\Controllers\admins\AddCreditController;
use App\Http\Controllers\admins\AdminDepositReportController;
use App\Http\Controllers\admins\AdminLedgerReportController;
//client Support controller
use App\Http\Controllers\admins\SupportControllerForAdmin;
use App\Http\Controllers\traders\SupportControllerForUser;
use App\Http\Controllers\IB\SupportControllerForIb;

// Settings Controller
// ------------------------------------------------------------------------------
use App\Http\Controllers\admins\SetttingController;
use App\Http\Controllers\admins\BannerControllerAdmin;
use App\Http\Controllers\admins\ApiConfigurationController;

use App\Http\Controllers\admins\AdminWithdrawController;
use App\Http\Controllers\admins\AdminWithdrawReportController;
use App\Http\Controllers\admins\AnnouncementController;
use App\Http\Controllers\admins\NotificationController;
use App\Http\Controllers\admins\TraderSettingController;
use App\Http\Controllers\admins\IbSettingController;
use App\Http\Controllers\admins\BalanceTransferController;
use App\Http\Controllers\admins\BalanceUploadController;
use App\Http\Controllers\admins\BankAccountListController;
use App\Http\Controllers\admins\BlockedUserListController;
use App\Http\Controllers\admins\Bonus\BonusCreateController;
use App\Http\Controllers\admins\Bonus\BonusListController;
use App\Http\Controllers\admins\Bonus\BonusReportController;
use App\Http\Controllers\admins\ExternalFundTransferController;
use App\Http\Controllers\admins\CategoryController;
use App\Http\Controllers\admins\DepositRequestController;
use App\Http\Controllers\admins\InternalFundTransferController;
use App\Http\Controllers\admins\WithdrawRequestController;
use App\Http\Controllers\admins\ClientGroupController;
use App\Http\Controllers\admins\CombineIbRequest;
use App\Http\Controllers\admins\CombineIbRequestController;
use App\Http\Controllers\admins\CompanySetupController;
use App\Http\Controllers\admins\CompnayBankController;
use App\Http\Controllers\admins\Contest\ContentListController;
use App\Http\Controllers\admins\Contest\ContestParticipantController;
use App\Http\Controllers\admins\Contest\CreateContestController;
use App\Http\Controllers\admins\CryptoDeposit\CryptoActivateController;
use App\Http\Controllers\admins\CryptoDeposit\CryptoDepositSettings;
use App\Http\Controllers\admins\CurrencySetupController;
use App\Http\Controllers\admins\DepositSettingsController;
use App\Http\Controllers\admins\FinanceSettingController;
use App\Http\Controllers\admins\FinanceReportController;
use App\Http\Controllers\admins\FundManageController;
use App\Http\Controllers\admins\GroupPermissionController;
use App\Http\Controllers\admins\IbAnalysisController;
use App\Http\Controllers\admins\IbBalanceAddController;
use App\Http\Controllers\admins\IBFundTransferController;
use App\Http\Controllers\admins\IbGroupController;
use App\Http\Controllers\admins\IbChainController;
use App\Http\Controllers\admins\IbcommisionStructureReplace;
use App\Http\Controllers\admins\ibManagement\CustomCommissionController;
use App\Http\Controllers\admins\ibManagement\IbBlockController;
use App\Http\Controllers\admins\ibManagement\IbUnblockController;
use App\Http\Controllers\admins\IbMasterController;
use App\Http\Controllers\admins\IbNoCommissionController;
use App\Http\Controllers\admins\IbPendingCommissionController;
use App\Http\Controllers\admins\IbProfileUpdateController;
use App\Http\Controllers\admins\IBTransferController;
use App\Http\Controllers\admins\IBVerificationController;
use App\Http\Controllers\admins\IBWithdrawController;
use App\Http\Controllers\admins\IbWithdrawReportController as AdminsIbWithdrawReportController;
use App\Http\Controllers\admins\ManageAccounts\LiveTradingAccountDetailsController;

// kyc controllers
// -------------------------------------------------------------------------------------
use App\Http\Controllers\admins\KycReportController;
use App\Http\Controllers\admins\KycRequestController;
use App\Http\Controllers\admins\KycRequiredController;
use App\Http\Controllers\admins\KycSettingController;
use App\Http\Controllers\admins\KycUploadController;
use App\Http\Controllers\admins\LocalizationController;
use App\Http\Controllers\admins\ManageAccounts\DemoTradingAccountDetailsController;
use App\Http\Controllers\admins\ManagerAnalysisController;
use App\Http\Controllers\admins\MasterIBDetailsController;
use App\Http\Controllers\admins\notification\SystemNotificationController;
use App\Http\Controllers\admins\NotificationSettingsController;
use App\Http\Controllers\admins\Profile\AdminProfileController;
use App\Http\Controllers\admins\SecuritySettingController;
use App\Http\Controllers\admins\SmtpSetupController;
use App\Http\Controllers\admins\SoftwareSettingController;
use App\Http\Controllers\admins\TradeCommissionStatusController;
use App\Http\Controllers\admins\TraderClientController;
use App\Http\Controllers\admins\TradingTradeReportController;
use App\Http\Controllers\admins\UserBonusController;
use App\Http\Controllers\admins\AssignGroupController;
use App\Http\Controllers\admins\Vouchers\VoucherController;
use App\Http\Controllers\admins\Vouchers\VoucherReportController;
use App\Http\Controllers\admins\SocialLoginController;
use App\Http\Controllers\managers\ManagerDashboardController;
use App\Http\Controllers\managers\ManagerDashboardController3;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SignupSuccessController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\IB\MarketingController;
use App\Http\Controllers\IB\Affiliate\IbClientDepositReport;
use App\Http\Controllers\IB\Affiliate\IbClientWithdrawReport;
use App\Http\Controllers\IB\Affiliate\ibAffiliateClientController;
use App\Http\Controllers\IB\Affiliate\IbTreeController as AffiliateIbTreeController;
use App\Http\Controllers\IB\Affiliate\myIbAffiliateController;
use App\Http\Controllers\IB\IBtoTraderTransferController;
use App\Http\Controllers\IB\IbVerificationController as IBIbVerificationController;
use App\Http\Controllers\IB\MyAdmin\IbAccountVerificationController;
use App\Http\Controllers\IB\Reports\BalanceTransferToIbReportController;
use App\Http\Controllers\IB\Reports\BalanceTransferToTraderReportController;
use App\Http\Controllers\IB\Reports\IbComissionReportController;
use App\Http\Controllers\traders\UserDepositController;
use App\Http\Controllers\IB\Reports\IbWithdrawReportController;
use App\Http\Controllers\IB\MyAdmin\IbBankingController;
use App\Http\Controllers\IB\MyAdmin\IbProfileOverviewController;
use App\Http\Controllers\IB\MyAdmin\IbSettingsController;
use App\Http\Controllers\systems\SystemActivityLog;
use App\Http\Controllers\systems\SystemCompanySetupController;
use App\Http\Controllers\systems\SystemFinanceSettingController;
use App\Http\Controllers\systems\SystemSoftwareSettingController;
use App\Http\Controllers\systems\SystemThemeSetupController;
use App\Http\Controllers\traders\AccountSettingsController;
use App\Http\Controllers\traders\AccountVerificationController;
use App\Http\Controllers\traders\AtwTransferController;
use App\Http\Controllers\traders\AtAtransferController;
use App\Http\Controllers\traders\UserBankingController;
use App\Http\Controllers\traders\BankDepositController;
use App\Http\Controllers\traders\BankWithdrawController;
use App\Http\Controllers\traders\CryptoDepositController;
use App\Http\Controllers\traders\CryptoWithdrawController;
use App\Http\Controllers\traders\ExternalTransController;
use App\Http\Controllers\traders\IbTransferReportController;
use App\Http\Controllers\traders\InternalTransferController;
use App\Http\Controllers\traders\NetellerWithdrawController;
use App\Http\Controllers\traders\OpenTradingAccount;
use App\Http\Controllers\traders\OpenDemoTradingAccountController;
use App\Http\Controllers\traders\OpenTradingAccountController;
use App\Http\Controllers\traders\ProfileOverviewController;
use App\Http\Controllers\traders\SettingsController;
use App\Http\Controllers\traders\SkrillWithdrawController;
use App\Http\Controllers\traders\TraderToIbTransferController;
use App\Http\Controllers\traders\TraderToTraderTransferController;
use App\Http\Controllers\traders\TradingAccountSettingsController;
use App\Http\Controllers\traders\TradingReportController;
use App\Http\Controllers\traders\UserWithdrawController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WtaTransferController;
use App\Mail\transfer\BalanceTransfer;
use App\Models\CryptoAddress;
use App\Models\User;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\traders\CopytradeController;
use App\Http\Controllers\traders\PerfectMoneyDepositController;
use App\Http\Controllers\traders\PublicPerfectMoneyDepositController;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\traders\UserOTPVerificationController;
use App\Http\Controllers\admins\OTPVerificationController;
use App\Http\Controllers\admins\settings\PaymentGateWaySettingsController;
use App\Http\Controllers\admins\SocialTrade\AdminCopyTradeReportController;
use App\Http\Controllers\admins\SocialTrade\AdminManageMammController;
use App\Http\Controllers\admins\SocialTrade\MasterProfitShareController;
use App\Http\Controllers\admins\SocialTrade\AdminSocialTradesController;
use App\Http\Controllers\admins\SocialTrade\CopyDashboardController;
use App\Http\Controllers\admins\SocialTrade\CopySymbolController;
use App\Http\Controllers\admins\SocialTrade\PammManagerController;
use App\Http\Controllers\admins\SocialTrade\PammRequestController;
use App\Http\Controllers\admins\SocialTrade\PammProfileController as SocialTradePammProfileController;
use App\Http\Controllers\admins\SocialTrade\PammSettingController;
use App\Http\Controllers\admins\TraderadminFinanceOpController;
use App\Http\Controllers\admins\TraderAdminSecurityController;
use App\Http\Controllers\admins\TraderSubIBAddedController;
use App\Http\Controllers\admins\TraderAdminUpdateProfileController;
use App\Http\Controllers\admins\TraderAnalysisController;
use App\Http\Controllers\CopyTradeGetController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\export\ExportController;
use App\Http\Controllers\Help2paySettingsController;
use App\Http\Controllers\HelpTwoPayController;
use App\Http\Controllers\IB\gcash\GcashWithdrawController as IBGcashWithdrawController;
use App\Http\Controllers\IB\IbOTPVerificationController;
use App\Http\Controllers\IB\IbToIbTransferController;
use App\Http\Controllers\IB\Reports\IbBalanceRecivedController;
use App\Http\Controllers\IB\Reports\IbBalanceSendController;
use App\Http\Controllers\LockScreenController;
use App\Http\Controllers\MobileBankingController;
use App\Http\Controllers\PayPalWithDrawController;
use App\Http\Controllers\select2\BankController;
use App\Http\Controllers\select2\ClientController;
use App\Http\Controllers\select2\ClientGroupController as Select2ClientGroupController;
use App\Http\Controllers\select2\CountryController;
use App\Http\Controllers\systems\AdminModuleController;
use App\Http\Controllers\systems\AdminSettingsController;
use App\Http\Controllers\systems\AdminTradingAccountConfigController;
use App\Http\Controllers\systems\B2bSettingsController;
use App\Http\Controllers\systems\banks\H2payConfigController as BanksH2payConfigController;
use App\Http\Controllers\systems\banks\OnlineBankController;
use App\Http\Controllers\systems\commission\RemainingCommissionController;
use App\Http\Controllers\systems\crypto\CryptoCurrencyController;
use App\Http\Controllers\systems\DepositMigrationController;
use App\Http\Controllers\systems\FooterLinkSetupController;
use App\Http\Controllers\systems\Help2paySettingsController as SystemsHelp2paySettingsController;
use App\Http\Controllers\systems\IbcommisionStructureReplace as SystemsIbcommisionStructureReplace;
use App\Http\Controllers\systems\IbSettingsController as SystemsIbSettingsController;
use App\Http\Controllers\systems\m2pay\M2payConfigController;
use App\Http\Controllers\systems\migration\ClientReferenceController;
use App\Http\Controllers\systems\migration\CombineIbConvertController;
use App\Http\Controllers\systems\migration\IbReferenceController;
use App\Http\Controllers\systems\migration\ManagerAsigneController;
use App\Http\Controllers\systems\migration\NameEmailMigrationController;
use App\Http\Controllers\systems\mobile\ApplicationController;
use App\Http\Controllers\systems\PraxisSettingsController;
use App\Http\Controllers\systems\SystemCompnayLinks;
use App\Http\Controllers\systems\SystemPammController;
use App\Http\Controllers\systems\TraderSettingsController;
use App\Http\Controllers\systems\UserMigrationController;

use App\Http\Controllers\TestController;
use App\Http\Controllers\traders\CopyTradesController;
use App\Http\Controllers\traders\MasterProfitReportController;
use App\Http\Controllers\traders\MamController;
use App\Http\Controllers\traders\PammProfileController;
use App\Http\Controllers\traders\PammProfileListController;
use App\Models\IbSetting;
use App\Services\IBManagementService;
use App\Http\Controllers\systems\SoftwareSettingsController as SestemSettingsController;
use App\Http\Controllers\systems\theme_setup\LogoUploadController;
use App\Http\Controllers\systems\VersionController;
use App\Http\Controllers\systems\WithdrawMigrationController;
use App\Http\Controllers\traders\B2bDepositController;
use App\Http\Controllers\traders\CombinedController;
use App\Http\Controllers\traders\contest\ContestController;
use App\Http\Controllers\traders\contest\ContestStatusController;
use App\Http\Controllers\traders\EconomicCalendarController;


use App\Http\Controllers\traders\NoCopyPamm\NoCopyPammListController;
use App\Http\Controllers\traders\NoCopyPamm\NoCopyPammOverviewController;
use App\Http\Controllers\traders\NoCopyPamm\NoCopyPammRegistrationController;
use App\Http\Controllers\traders\NoCopyPamm\PammOverviewChartController;
use App\Http\Controllers\traders\NoCopyPamm\PammOverviewTradeController;
use App\Http\Controllers\traders\NoCopyPamm\Report\MyIncomeReportController;
use App\Http\Controllers\traders\NoCopyPamm\Report\MyInvestmentAnalysisController;
use App\Http\Controllers\traders\NoCopyPamm\Report\MyInvestmentController;
use App\Http\Controllers\traders\NoCopyPamm\Report\PammIncomeReportController;
use App\Http\Controllers\traders\NoCopyPamm\Report\PammInvestmentController;
use App\Http\Controllers\admins\SocialTrade\NoCopyPamm\NoCopyPammTradeController;
use App\Http\Controllers\admins\SocialTrade\NoCopyPamm\NoCopyPammRequestController;
use App\Http\Controllers\admins\SocialTrade\NoCopyPamm\NoCopyPammDashboardController;
use App\Http\Controllers\admins\SocialTrade\NoCopyPamm\NoCopyPammInvestmentReportController;


use App\Http\Controllers\traders\gcash\GcashWithdrawController;
use App\Http\Controllers\traders\m2pay\M2payController;
use App\Http\Controllers\traders\PayPalController;
use App\Http\Controllers\traders\praxis\PraxisPaymentController;
use App\Http\Controllers\traders\UserBecomePartner;
use App\Http\Controllers\traders\socialtrade\PammOverviewController;
use App\Http\Controllers\traders\socialtrade\MasterProfitShareReportController;
use App\Http\Controllers\TraderWithdrawSettingsController;
use App\Http\Controllers\TransactionSettingsController;
use App\Services\systems\VersionControllService;
use App\Http\Controllers\traders\deposit\KosmosDepositController;
use App\Http\Controllers\traders\deposit\nowpay\NowPayController;
use App\Http\Controllers\traders\deposit\WebPayCcGateWayController;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\traders\reward\RewardTraderController;
use App\Http\Controllers\admins\Reward\RewardController;

use App\Http\Controllers\traders\tournaments\TournamentDashboardController;
use App\Http\Controllers\traders\tournaments\TradingAccountHistoryController;
use App\Http\Controllers\traders\tournaments\TournamentLeaderBoardController;

use App\Http\Controllers\admins\tournaments\TournamentSettingController;
use App\Http\Controllers\admins\tournaments\GroupListController;
// suspended
Route::middleware(['unsuspend.status'])->get('/suspended', function (Request $request) {
    return view('suspended');
})->name('suspended');

Route::get('/migration', [MigrationController::class, 'showMigration'])->name('migration');
Route::post('/migration', [MigrationController::class, 'submitMigration'])->name('migration');

Route::middleware(['suspend.status'])->group(function () {
    Route::get('/access-forbidden', [ErrorController::class, 'custom_forbidden']);
    Route::post('/get-copy-trade', [CopyTradeGetController::class, 'get_trades'])->name('system.get-copy-trade');
    Route::get('/test', [TestController::class, 'test'])->name('test');
    Route::get('/user/deposit/praxis/success', [PraxisPaymentController::class, 'success'])->name('user.deposit.praxis.success');
    Route::post('/client/email-activation', [\App\Http\Controllers\TraderActivationController::class, 'trader_activation']);
    
     Route::post('/validate-email', function(Request $request) {
            $email = $request->input('email');
            
            if (!$email) {
                return response()->json([
                    'is_valid' => false,
                    'message' => 'Email is required'
                ]);
            }
            
            $emailValidation = new \App\Services\EmailValidationService();
            $result = $emailValidation->isEmailValidForRegistration($email);
            
            // Additional Gmail check
            if (str_contains($email, '@gmail.com')) {
                $gmailCheck = $emailValidation->checkGmailAccountExists($email);
                if (!$gmailCheck['exists']) {
                    $result['is_valid'] = false;
                    $result['message'] = 'This Gmail account does not exist. Please use a valid Gmail address.';
                }
            }
            
            return response()->json($result);
        })->name('validate.email');
    // if route not found then redirect to home
    Route::middleware(['guest'])->group(function () {
        Route::get('/', function () {
            Artisan::call('optimize:clear');
            return view(VersionControllService::get_login_theme('client'));
        })->name('login');
        Route::get('/trader', [LoginController::class, 'showTraderLoginForm'])->name('trader.login');
        Route::get('/ib_login', [LoginController::class, 'showIbLoginForm'])->name('ib.login');
        Route::get('/admin_login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
    });
    Auth::routes();
    Route::middleware(['auth'])->group(function () {
        // Place your protected routes here
        Route::post('/export/manage-trade/export-done', [ExportController::class, 'export_done'])->name('export.done');
    });

    // Route::middleware(['auth', 'user-access:system|trader|ib|admin'])->group(function () {
    //     Route::get('/access-forbidden', [ErrorController::class, 'custom_forbidden']);
    // });
    Route::get('/success/{hash}', [SignupSuccessController::class, 'signupSuccess'])->name('signup.success');
    Route::get('/resend/activeion/link', [SignupSuccessController::class, 'resendActiveionLink'])->name('resend.activeion.link');
    Route::get('/activeion/user/{hash}', [SignupSuccessController::class, 'activeion'])->name('activeion');
    Route::get('/activation/user/{hash}', [SignupSuccessController::class, 'activeion'])->name('activation');
    Route::get('/activation/user/demo/{hash}', [SignupSuccessController::class, 'demo_activeion'])->name('demo_activation');

    
    Route::get('/admin/client-management/get-client-type/{server}', [CommonController::class, 'get_client_type'])->name('admin.trader-admin-get_client_group');
    Route::get('/admin/client-management/get-account-type/{server}', [CommonController::class, 'get_account_type'])->name('admin.trader-admin-get_account-type');
    Route::get('/admin/client-management/get-leverage/{group_id}', [CommonController::class, 'get_leverage'])->name('common.get-leverage');
    Route::get('/admin/client-management/get-client-groups/{client_type}/meta-server/{server}', [CommonController::class, 'get_client_groups'])->name('admin.trader-admin-get_client_group');
    Route::get('/system_login', [LoginController::class, 'showSystemLoginForm'])->name('system.login');
    // Route::get('/trader', [LoginController::class, 'showTraderLoginForm'])->name('trader.login');
    Route::get('/ib_login', [LoginController::class, 'showIbLoginForm'])->name('ib.login');
    Route::get('/admin_login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
    // manager login page
    Route::get('/manager_login', [LoginController::class, 'showManagerLoginForm'])->name('manager.login');

    Route::get('/system/registration', [RegistrationController::class, 'showSystemRegistrationForm'])->name('system.registration');
    Route::any('/trader/registration', [RegistrationController::class, 'trader_registration'])->name('trader.registration');
    Route::any('/trader/demo/registration', [RegistrationController::class, 'demo_registration'])->name('trader.demo-registration');
    Route::any('/ib/registration/', [RegistrationController::class, 'ib_registration'])->name('ib.registration');
    Route::any('/admin/registration/', [RegistrationController::class, 'admin_activation_request'])->name('admin.registration-req');
    Route::get('/trader/resend/verification/{id}', [RegistrationController::class, 'resendVerificationLink'])->name('trader.resend.verification');

    // Route::any('/ib/registration/{refer}', [RegistrationController::class, 'ib_registration'])->name('ib.registration');
    Route::any('/ib/activation/ac/{hash}', [RegistrationController::class, 'ib_activation'])->name('ib.activation');
    Route::any('/admin/activation/ac/{hash}', [RegistrationController::class, 'admin_activation'])->name('admin.activation');
    Route::any('/admin/change/mail/{hash}', [RegistrationController::class, 'admin_change_mail'])->name('admin.change-mail');
    Route::any('/admin/change/phone/{hash}', [RegistrationController::class, 'admin_change_phone'])->name('admin.change-phone');
    Route::any('/admin/change/mail-req', [RegistrationController::class, 'admin_change_mail_req'])->name('admin.change-mail-req');
    Route::any('/ib/success', [RegistrationController::class, 'ib_success'])->name('ib.success');
    Route::get('/admin/registration', [RegistrationController::class, 'showAdminRegistrationForm'])->name('admin.registration');
    Route::get('/manager/registration', [RegistrationController::class, 'showManagerRegistrationForm'])->name('manager.registration');


    // search ib or trader for select2
    Route::get('/search/ib/users/reference', [CommonController::class, 'ib_user_select2'])->name('serch.ib-reference');
    Route::get('/search/ib/users/reference/remove', [CommonController::class, 'references_user'])->name('serch.references_user');
    Route::get('/search/country', [CommonController::class, 'get_country_select2'])->name('serch.get-allcountry');
    Route::get('/search/country/with-name', [CountryController::class, 'country_value_name'])->name('serch.get-allcountry.value-name');
    Route::get('/search/bank', [BankController::class, 'get_bank'])->name('serch.get-bank');
    Route::get('/search/client_group', [Select2ClientGroupController::class, 'get_client_group'])->name('serch.get-client-group');
    Route::get('/search/clients', [ClientController::class, 'get_clients'])->name('serch.get-clients');
    // search both ib and trader
    // select2
    Route::get('/search/client/users/both', [CommonController::class, 'get_trader_ib'])->name('serch.clients.ib-and-trader');
    //search only ib and trader
    Route::get('/search/ib/users', [CommonController::class, 'get_ib'])->name('search.ib-users');
    Route::get('/search/trader/users', [CommonController::class, 'get_trader'])->name('search.trader-users');
    Route::get('/get-trader/forfund/management', [CommonController::class, 'get_trader_for_fund'])->name('search.trader-users.get');
    Route::get('/search/trader/ib', [CommonController::class, 'finanace_blance_client'])->name('search.client-ib');
    Route::get('/search/trader/users/filter-client', [CommonController::class, 'get_filter_client'])->name('search.filter-clients');
    Route::get('/search/ib/users/sub-ib', [CommonController::class, 'sub_ib_user_select2'])->name('serch.sub-ib-reference');
    Route::get('/search/ib/users/trader', [CommonController::class, 'trader_user_select2'])->name('serch.trader-user-reference');

    Route::get('/search/removed_trading_account_details', [CommonController::class, 'removed_trading_account_details'])->name('serch.removed_trading_account_details');
    Route::post('/trader/login', [LoginController::class, 'traderLogin'])->name('trader.login.action');

    // forgot password for all user
    Route::post('/user/forgot_password', [LoginController::class, 'userForgotPassword'])->name('user.forgot_password');

    Route::post('/ib/login', [LoginController::class, 'ibLogin'])->name('ib.login.action');
    Route::post('/manager/login', [LoginController::class, 'managerLogin'])->name('manager.login.action');
    Route::post('/system/login', [LoginController::class, 'systemLogin'])->name('system.login.action');
    Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.action');
    Route::post('/resent/v_code/{v_email}', [LoginController::class, 'resendVerificationCode'])->name('resend.v_code');

    Route::post('/system/registration', [RegistrationController::class, 'createSystem'])->name('system.registration.action');

    // Route::post('/admin/registration', [RegistrationController::class, 'createAdmin'])->name('admin.registration.action');
    Route::post('/admin/registration/account', [RegistrationController::class, 'createAdmin'])->name('admin.registration.account');

    Route::post('/manager/registration', [RegistrationController::class, 'createManager'])->name('manager.registration.action');
    // get user by input typing-----------------
    Route::post('/user/input-user', [UserController::class, 'find_users'])->name('user.input-user.find-user');
    //crypto address activate route
    Route::get('/admin/settings/crypto_activate', [CryptoActivateController::class, 'CryptoActivate'])->name('admin.crypto.active');
    Route::post('/user/crypto-convert', [CommonController::class, 'convert_amount'])->name('admin.crypto.convert');
    Route::post('/user/crypto-instrument', [CommonController::class, 'instrument'])->name('admin.crypto.instrument');

    Route::get('/search/get-client/{user_type}/user/{value}', [KycUploadController::class, 'search_client'])->name('admin.kyc-get-client');
    Route::post('/admin/user-admin/verify-form', [KycUploadController::class, 'file_upload'])->name('admin.admin-verification-form');

    // lock screen route
    Route::any('/lock-screen/{user_id}/{current_page}', [LockScreenController::class, 'lockScreen']);
    Route::any('/lock-screen-login', [LockScreenController::class, 'lockScreenLogin'])->name('trader.lock.screen');
    // IB Lock Screen
    Route::any('/ib/lock-screen/{user_id}/{current_page}', [LockScreenController::class, 'IBlockScreen']);
    Route::any('/ib/lock-screen-login', [LockScreenController::class, 'IBlockScreenLogin'])->name('ib.lock.screen');
    // admin Lock Screen
    Route::any('/admin/lock-screen/{user_id}/{current_page}', [LockScreenController::class, 'AdminlockScreen']);
    Route::any('/admin/lock-screen-login', [LockScreenController::class, 'AdminlockScreenLogin'])->name('admin.lock.screen');
    Route::get('/currency/get-currency/{amount}/from/{from_currency}/to/{to_currency}/transaction-type/{type?}', [CurrencySetupController::class, 'convert'])->name('currency.convert');
    // get copy trade

    /*------------------------------------------
    --------------------------------------------
    All Restricted Routes
    --------------------------------------------
    --------------------------------------------*/



    // Start: public route for perfect money deposit
    Route::get('/user/deposit/perfect-money-deposit-process', [PublicPerfectMoneyDepositController::class, 'perfectMoneyDepositProcess'])->name('user.deposit.perfect-money-deposit-process');

    Route::get('/user/deposit/cancel-perfect-money-deposit', [PublicPerfectMoneyDepositController::class, 'cancelPMD'])->name('user.deposit.cancel-perfect-money-deposit');
    // End: public route for perfect money deposit
    // public route for m2pay deposit


    // public route
    Route::get('marketing/banner/{image_name}/{referral_link}/{use_for}', [MarketingController::class, 'viewReferralImage'])->name('marketing.banner');
    // ib controllers



    // ******************************************************************************************************************************************************************************************
    // admin controllers
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::middleware(['check.user.block.status'])->group(function () {
        // system controllers
        Route::middleware(['auth', 'user-access:system'])->group(function () {
            Route::get('/system/dashboard', [SystemDashboardController::class, 'dashboard'])->name('system.dashboard');

            Route::get('/system/pamm-setting', [SystemPammController::class, 'pammSetting'])->name('system.pamm');
            Route::post('/system/pamm-setting-process', [SystemPammController::class, 'pammSettingProcess'])->name('system.pamm.process');
            // Route::post('/system/add-symbol-process', [SystemPammController::class, 'addSymbol'])->name('system.add.symbol');
            // Route::get('/system/add-symbol-table-process', [SystemPammController::class, 'SymbolTable'])->name('system.symbol.table');


            Route::get('/system/configurations/api_configuration', [SystemApiConfigurationController::class, 'apiConfiguration'])->name('system.configurations.api_configuration');
            Route::post('/system/configurations/api_configuration_add', [SystemApiConfigurationController::class, 'apiConfigurationAdd'])->name('system.configarations.api_configuration_add');
            //START: IB Commission replace
            Route::get('/system/configurations/ib-commission-structure-replace', [SystemsIbcommisionStructureReplace::class, 'index'])->name('system.ib-commission-structure-replace');
            Route::post('/system/configurations/ib-commission-structure-replace/store', [SystemsIbcommisionStructureReplace::class, 'store'])->name('system.ib-commission-structure-replace.store');
            Route::post('/system/configurations/ib-commission-structure-replace/custom-structure/store', [SystemsIbcommisionStructureReplace::class, 'customStructureStore'])->name('system.ib-commission-structure-replace.customStructureStore');
            //MT4 Live API Config
            Route::post('/system/configurations/mt4-live-api-config', [SystemApiConfigurationController::class, 'mt4_live_api_config'])->name('system.configarations.mt4-live-api-config');
            //MT4 Demo API Config
            Route::post('/system/configurations/mt4-demo-api-config', [SystemApiConfigurationController::class, 'mt4_demo_api_config'])->name('system.configarations.mt4-demo-api-config');
            //Manager API Config
            Route::post('/system/configurations/manager-api-config', [SystemApiConfigurationController::class, 'manager_api_config'])->name('system.configarations.mt5-manager-api-config');
            //Web app api config
            Route::post('/system/configurations/web-app-api-config', [SystemApiConfigurationController::class, 'web_api_config'])->name('system.configarations.mt5-web-api-config');


            Route::get('/system/configurations/smtp_setup', [SystemSmtpSetupController::class, 'smtpSetup'])->name('system.configurations.smtp_setup');
            Route::post('/system/configurations/smtp_setup_add', [SystemSmtpSetupController::class, 'smtpSetupAdd'])->name('system.configarations.smtp_setup_add');

            Route::get('/system/configurations/footer_link', [FooterLinkSetupController::class, 'footerLink'])->name('system.configurations.footer_link');
            Route::post('/system/configurations/footer_link_add', [FooterLinkSetupController::class, 'footerLinkAdd'])->name('system.configarations.footer_link_add');

            Route::get('/system/configurations/company_setup', [SystemCompanySetupController::class, 'companySetup'])->name('system.configurations.company_setup');
            Route::post('/system/configurations/company_setup_add', [SystemCompanySetupController::class, 'companySetupAdd'])->name('system.configarations.company_setup_add');

            Route::get('/system/configurations/finance_setting', [SystemFinanceSettingController::class, 'financeSetting'])->name('system.configurations.finance_setting');
            Route::post('/system/configurations/finance_setting_add', [SystemFinanceSettingController::class, 'financeSettingAdd'])->name('system.configurations.finance_setting_add');
            Route::get('/system/configurations/finance_setting/fetch_data', [SystemFinanceSettingController::class, 'financeSettingFetchData'])->name('system.configurations.finance_setting_fetch_data');
            Route::post('/system/configurations/finance-settings/delete/{id}', [SystemFinanceSettingController::class, 'financeSettingDelete'])->name('system.configurations.finance_setting_delete');
            Route::post('/system/configurations/finance-settings/change_active_status/{id}/{value}', [SystemFinanceSettingController::class, 'financeSettingChangeActiveStatus'])->name('system.configurations.finance_setting.change_active_status');

            Route::get('/system/configurations/software_setting', [SystemSoftwareSettingController::class, 'softwareSetting'])->name('system.configurations.software_setting');
            Route::post('/system/configurations/software_setting_add', [SystemSoftwareSettingController::class, 'softwareSettingAdd'])->name('system.configarations.software_setting_add');
            Route::post('/system/configurations/software_setting_required-field', [SystemSoftwareSettingController::class, 'required_fields'])->name('system.configarations.software_setting_require-field');

            // theme setups
            Route::get('/system/configurations/theme_setup', [SystemThemeSetupController::class, 'configuration'])->name('system.configurations.theme_setup');
            Route::post('/system/configurations/theme_setup_add', [SystemThemeSetupController::class, 'client_theme_setup'])->name('system.configarations.update_client_theme');
            Route::post('/system/configurations/admin_theme_setup', [SystemThemeSetupController::class, 'admin_theme_setup'])->name('system.configarations.update_admin_theme');
            Route::post('/system/configurations/theme-colors', [SystemThemeSetupController::class, 'theme_colors'])->name('system.configarations.update_theme_color');
            Route::post('/system/configurations/logo-upload', [LogoUploadController::class, 'logo_upload'])->name('system.configarations.logo_upload');
            // activity log
            Route::get('/system/reports/activity_log', [SystemActivityLog::class, 'activity_log'])->name('system.reports.activity_log');
            Route::get('/system/reports/activity-log-dt', [SystemActivityLog::class, 'activity_log_dt'])->name('system.reports.activity_log_dt');
            Route::get('/system/reports/activity-log-dt-desctiption/{id}', [SystemActivityLog::class, 'activity_log_dt_description'])->name('system.reports.activity_log_dt_description');

            //company links
            Route::get('/system/company-links', [SystemCompnayLinks::class, 'view'])->name('system.company_links');
            Route::post('/system/company-links-add', [SystemCompnayLinks::class, 'store'])->name('system.company_links_add');
            // ib settings
            Route::get('/system/ib-settings', [SystemsIbSettingsController::class, 'ibSetting'])->name('system.ib-settings.by-system-admin');
            Route::get('/system/admin-settings', [AdminSettingsController::class, 'index'])->name('system.admin-settings.by-system-admin');
            // trading account configuration
            Route::get('/system/admin-account/configuration', [AdminTradingAccountConfigController::class, 'index'])->name('system.admin-account.configuration');
            Route::post('/system/admin-account/configuration', [AdminTradingAccountConfigController::class, 'configAdd'])->name('system.admin-account.configuration');
            // trader settings
            Route::get('/system/trader-settings', [TraderSettingsController::class, 'trader_settings'])->name('system.trader-settings.by-system-admin');

            // admin settings
            Route::get('/system/admin-management/get-all-admins', [AdminSettingsController::class, 'get_all_admins'])->name('system.admin-settings.get-all-admin');
            Route::get('/system/admin-management/get-all-admin-description/', [AdminSettingsController::class, 'get_all_admins'])->name('system.admin-settings.get-all-admin');
            // system module controll
            Route::get('/system/system-module', [AdminModuleController::class, 'index'])->name('system.system-modules');
            Route::get('/system/system-module/create_all', [AdminModuleController::class, 'create_all'])->name('system.system-modules.create-all');
            Route::get('/system/system-module/create-all-dt', [AdminModuleController::class, 'module_dts'])->name('system.system-modules.dt');
            // mail template settings
            Route::get('/system/mailer/choose-template', [SestemSettingsController::class, 'index'])->name('system.mailer.choose-template');
            // version controller
            Route::post('/system/software-settings/version-controll', [VersionController::class, 'virsion_upgrate'])->name('system.version-upgrate');
            // mobile aplication controll
            Route::get('/system/mobile-application/app-key-setup/view', [ApplicationController::class, 'logo_controll'])->name('mobile.app.logo_controll');
            Route::post('/system/mobile-application/app-key-setup', [ApplicationController::class, 'app_key_setup'])->name('mobile.app.key-setup');
            Route::post('/system/mobile-application/logo-controll/loader', [ApplicationController::class, 'upload_loader'])->name('mobile.app.logo_controll.loader');
            // user migration
            Route::get('/system/migration/user-migration/name-email', [NameEmailMigrationController::class, 'index'])->name('system.name-email-migration');
            Route::get('/system/migration/user-migration/manager-asigne', [ManagerAsigneController::class, 'index'])->name('system.manager-asigne-migration');
            Route::post('/system/migration/user-migration/name-email/store', [NameEmailMigrationController::class, 'store'])->name('system.name-email-migration.store');
            Route::post('/system/migration/user-migration/manager-asigne/store', [ManagerAsigneController::class, 'store'])->name('system.manager-asigne.store');
            // deposit migration
            Route::get('/system/migration/deposit-migration/view', [DepositMigrationController::class, 'index'])->name('system.deposit-migration-view');
            Route::post('/system/migration/deposit-migration/store', [DepositMigrationController::class, 'store'])->name('system.deposit-migration-store');

            // withdraw migration
            Route::get('/system/migration/withdraw-migration/view', [WithdrawMigrationController::class, 'index'])->name('system.withdraw-migration-view');
            Route::post('/system/migration/withdraw-migration/store', [WithdrawMigrationController::class, 'store'])->name('system.withdraw-migration-store');
            // migration
            Route::get('/system/migration/user-migration/ib-reference', [IbReferenceController::class, 'index'])->name('system.migration.ib-reference');
            Route::post('/system/migration/user-migration/ib-reference/store', [IbReferenceController::class, 'store'])->name('system.migration.ib-reference.store');
            Route::get('/system/migration/user-migration/clients-reference', [ClientReferenceController::class, 'index'])->name('system.migration.clients-reference');
            Route::post('/system/migration/user-migration/clients-reference/store', [ClientReferenceController::class, 'store'])->name('system.migration.clients-reference.store');
            Route::get('/system/migration/user-migration/convert-ib', [CombineIbConvertController::class, 'index'])->name('system.migration.convert-ib');
            Route::post('/system/migration/user-migration/convert-ib/store', [CombineIbConvertController::class, 'store'])->name('system.migration.convert-ib.store');

            // add online banks
            Route::get('/system/banks/online-bank-list', [OnlineBankController::class, 'index'])->name('systems.online-bank-list');
            Route::post('/system/banks/online-bank-list/add', [OnlineBankController::class, 'add_bank'])->name('systems.online-bank.add');
            Route::get('/system/banks/online-bank-list/get', [OnlineBankController::class, 'get_bank'])->name('systems.online-bank.get');
            Route::get('/system/banks/online-bank-list/get/edit-data', [OnlineBankController::class, 'get_edit_data'])->name('systems.online-bank.get-edit-data');
            Route::post('/system/banks/online-bank-list/edit', [OnlineBankController::class, 'edit_bank'])->name('systems.online-bank.edit-bank');
            //Help 2 Pay Config
            // Route::get('/system/h2pay-config', [BanksH2payConfigController::class, 'index'])->name('system.h2pay-config');
            // Route::post('/system/h2pay-config/store', [BanksH2payConfigController::class, 'store'])->name('system.h2pay-config-store');
            // REMAINING COMMISSION SETUP
            Route::post('/system/settings/remaining-commission-setup', [RemainingCommissionController::class, 'setup_store'])->name('system.remaining-commission-setup');
            // add crypto symbol
            Route::get('/system/crypto/crypto-currency', [CryptoCurrencyController::class, 'index'])->name('system.crypto-currency');
            Route::post('/system/crypto/crypto-currency/store', [CryptoCurrencyController::class, 'store'])->name('system.crypto-currency.store');
            Route::get('/system/crypto/crypto-currency/datatable', [CryptoCurrencyController::class, 'datatable'])->name('system.crypto-currency.datatable');
            // M2Pay Config
            Route::get('/system/m2pay-config', [M2payConfigController::class, 'index'])->name('system.m2pay-config');
            Route::post('/system/m2pay-config/store', [M2payConfigController::class, 'store'])->name('system.m2pay-config.store');
            //Payments Settings
            Route::get('/system/payments-settings/help2pay', [SystemsHelp2paySettingsController::class, 'index'])->name('system.help2pay-settings');
            Route::get('/system/payments-settings/praxis', [PraxisSettingsController::class, 'index'])->name('system.praxis-settings');
            Route::get('/system/payments-settings/b2b', [B2bSettingsController::class, 'index'])->name('system.b2b-settings');
        });
        // trader controllers
        Route::middleware(['auth', 'user-access:trader'])->group(function () {
            // tournament start
            // tournament dashboard
            Route::get('/user/tournament/dashboard', [TournamentDashboardController::class, 'tournamentDashboard'])->name('user.tournament.dashboard');
            Route::post('/user/tournament/delete-participant', [TournamentDashboardController::class, 'deleteTournamentParticipant'])->name('user.tournament.delete-participant');
            Route::get('/user/tournament/trading-account-history/{group_id}/{account_number}', [TradingAccountHistoryController::class, 'tradingAccountHistory'])->name('user.tournament.trading-account-history');
            // tournament leader board
            Route::get('/user/tournament/leader-board', [TournamentLeaderBoardController::class, 'leaderBoardView'])->name('user.tournament.leader-board');
            Route::post('/user/tournament/join', [TournamentLeaderBoardController::class, 'joinTournament'])->name('users.tournament.join');
            Route::get('/user/tournament/leaderboard/leaders', [TournamentLeaderBoardController::class, 'tourLeaderBoard'])->name('user.tournament.leaderboard.leaders');
            Route::get('/user/tournament/leaderboard/balance-equity', [TournamentLeaderBoardController::class, 'balance_equity'])->name('user.tournament.leaderboard.balance-equity');
            // tournament end
            
            Route::get('/user/dashboard', [TraderDashboardController::class, 'dashboard'])->name('trader.dashboard');
            Route::get('/user/dashboard/popup-permanently-close/{popup_id}', [TraderDashboardController::class, 'popupPermanentlyClose'])->name('user.dashboard.popup-permanently-close');
            Route::any('/user/become-a-partner', [UserBecomePartner::class, 'becomePartner'])->name('user.become-a-partner');
            Route::get('/admin/admin/dashboard', [TraderDashboardController::class, 'goto_admin_dashboard'])->name('admin.admin.dashboard');


            Route::any('/user/meta-copy-slave-report/', [MamController::class, 'SlaveAccountList']);
            Route::any('/user/meta5_mam_delete', [MamController::class, 'SlaveAccountDelete']);
            Route::any('/user/add-slave-account', [MamController::class, 'addSlaveAccount'])->name('user.addSlaveAccount');
            // contest in client dashboard
            Route::get('/user/dashboard/get-contest', [ContestController::class, 'get_contest'])->name('users.get.contest-data');
            Route::post('/user/dashboard/join-contest', [ContestController::class, 'join_contest'])->name('users.join.contest');
            // contest menue
            Route::get('/user/contest/participate-contest', [ContestController::class, 'participate_contest'])->name('users.participate-contest');
            //contest List
            Route::get('/user/contest/contest-list', [ContestController::class, 'contest_list'])->name('users.contest-list');
            //Contest List Description
            Route::get('/user/contest/contest-list-description',  [ContestController::class, 'ContestListDescription'])->name('user.contest.description');
            Route::get('/user/contest/contest-status', [ContestStatusController::class, 'index'])->name('users.contest-status');
            Route::get('/user/contest/leaderboard', [ContestStatusController::class, 'getLeaderboard'])->name('users.contest.leaderboard');
            Route::get('/user/contest/contest-leaderboard/{contestId}', [ContestStatusController::class, 'showContestLeaderboard'])->name('users.contest.individual-leaderboard');
            Route::get('/user/contest/contest-details/{contestId}', [ContestStatusController::class, 'getContestDetails'])->name('users.contest.details');
            Route::get('/user/contest/check-status', [ContestStatusController::class, 'checkContestStatus'])->name('users.contest.check-status');
            // START: Languate settings
            // --------------------------------------------------------------------------------------------

            // END: Language Settings
            // User OTP Verification Route
            Route::any('user/otp_verification_submit/{name}/{check}', [UserOTPVerificationController::class, 'otpVerification'])->name('otp_verification_submit');
            Route::post('/user/user-admin/settings/create-transection-password', [SettingsController::class, 'createTransectionPassword'])->name('user.user-admin.settings.create.transection.password');
            Route::post('/user/user-admin/settings/reset-user-password', [SettingsController::class, 'resetUserPassword'])->name('user.user-admin.reset.password');
            Route::post('/user/user-admin/settings/forgot-transaction-pin', [SettingsController::class, 'forgotTransactionPin'])->name('user.user-admin.settings.forgot-transaction-pin');

            //Trader report route start from here
            Route::get('/user/reports/deposit-report', [UserDepositController::class, 'depositReport'])->name('user.deposit-report');
            Route::get('/user/reports/withdraw-report', [UserWithdrawController::class, 'withdrawReport'])->name('user.withdraw-report');
            Route::get('/user/withdraw-decline/{withdraw_id}', [UserWithdrawController::class, 'userWithdrawDecline'])->name('user.withdraw-decline');
            Route::get('/user/reports/external-fund-transfer-report', [ExternalTransController::class, 'externalReport'])->name('user.external-report');
            Route::get('/user/reports/internal-transfer-report', [InternalTransferController::class, 'internalReport'])->name('user.internal-report');
            Route::get('/user/reports/trading-report', [TradingReportController::class, 'tradingReport'])->name('user.trading-report');
            // Route::get('/user/reports/ib-transfer-report', [IbTransferReportController::class, 'ibReport'])->name('user.trading.ib-report');
            //Start : user Support
            Route::get('user/support/ticket', [SupportControllerForUser::class, 'index'])->name('user.support.ticket');
            Route::any('user/support/support-ticket-get',  [SupportControllerForUser::class, 'get_support'])->name('user.support.support-ticket-get');
            Route::any('user/support/support-ticket-reply',  [SupportControllerForUser::class, 'get_support_reply'])->name('user.support.support-ticket-reply');
            Route::any('user/support/support-ticket-delete',  [SupportControllerForUser::class, 'delete_ticket'])->name('user.support.delete-ticket');
            Route::any('user/support/support-send-reply',  [SupportControllerForUser::class, 'send_support_reply'])->name('user.support.support-send-reply');
            Route::any('user/support/create-ticket',  [SupportControllerForUser::class, 'create_ticket'])->name('user.support.create-ticket');
            Route::post('user/support/get-server-replay',  [SupportControllerForUser::class, 'server_replay'])->name('user.support.server-replay');


            // Start: User admins----------------------
            Route::get('/user/user-admin/account-verification', [AccountVerificationController::class, 'verification'])->name('user.user-admin-account-verification');
            Route::post('/user/user-admin/verify-form', [IBIbVerificationController::class, 'file_upload'])->name('user.user-admin-verification-form');
            // profile overview----------
            Route::get('/user/user-admin/profile-overview', [ProfileOverviewController::class, 'profile_overview'])->name('user.user-admin.profile-overview');
            Route::post('/user/user-admin/settings/update-user-info', [ProfileOverviewController::class, 'updateBasicInfo'])->name('user.user-admin-settings.basic-info');
            Route::post('/user/profile-picture/upload', [ProfileOverviewController::class, 'profilePictureUpload'])->name('user.profile-picture.upload');
            // trading account
            Route::get('/user/user-admin/trading-account', [ProfileOverviewController::class, 'trading_account'])->name('user.user-admin.trading_account');
            // account settings---------
            Route::get('/user/user-admin/account-settings/accounts-dt', [SettingsController::class, 'get_trading_account_dt'])->name('user.user-admin.accounts-dt');


            Route::get('/user/user-admin/settings', [SettingsController::class, 'settings'])->name('user.user-admin-account-settings');
            Route::post('/user/user-admin/settings/update-password', [SettingsController::class, 'updatePassword'])->name('user.user-admin-settings.update-password');
            Route::post('/user/user-admin/settings/update-transaction-password', [SettingsController::class, 'updateTransactionPassword'])->name('user.user-admin-settings.update-transaction-password');
            Route::post('/user/user-admin/settings/add-update-social-link', [SettingsController::class, 'addUpdateSocialLink'])->name('user.user-admin-settings.add-update');
            Route::post('/user/user-admin/settings/social-link', [SettingsController::class, 'SocialLink'])->name('user.user-admin-settings.social-link');
            // user security setting
            Route::post('/user/user-admin/settings/security-setting/{check_auth}', [SettingsController::class, 'securitySettingUpdate'])->name('user.user-admin-settings.security-setting');
            Route::post('/user/user-admin/settings/google-security-setting', [SettingsController::class, 'googleAuthenticationUpdate'])->name('user.user-admin-settings.google-security-setting');

            // user banking
            Route::get('/user/user-admin/user-banking', [UserBankingController::class, 'userBanking'])->name('user.user-admin.user-banking');
            Route::get('/user/user-admin/user-banking/fetch-data', [UserBankingController::class, 'userBankingFetchData'])->name('user.user-admin.user-banking.fetch-data');
            Route::get('/user/user-admin/user-banking/table-description/{id}', [UserBankingController::class, 'userBankingDescription'])->name('user.user-admin.user-banking.table-description');
            Route::get('/user/user-admin/user-banking/fetch-data', [UserBankingController::class, 'userBankingFetchData'])->name('user.user-admin.user-banking.fetch-data');
            Route::get('/user/user-admin/user-banking/table-description/{id}', [UserBankingController::class, 'userBankingDescription'])->name('user.user-admin.user-banking.table-description');
            Route::post('/user/user-admin/user-banking-list-delete', [UserBankingController::class, 'bankAccountListDelete'])->name('user.user-admin.user-banking-list-delete');
            Route::get('/user/user-admin/user-banking/edit/fetch-data/{id}', [UserBankingController::class, 'userBankingEditFetchData'])->name('user.user-admin.user-banking.edit.fetch-data');
            Route::post('/user/user-admin/user-banking-edit', [UserBankingController::class, 'bankAccountEdit'])->name('user.user-admin.user-banking-edit');
            Route::post('/user/user-admin/user-banking-add', [UserBankingController::class, 'bankAccountAdd'])->name('user.user-admin.user-banking-add');

            // equity check route
            Route::get('/user/check-balance-equity/{account_number?}/pl/{platform?}', [CommonController::class, 'balance_equity'])->name('api.balance.equity');

            // Pamm route
            Route::get('/user/user-pamm/user-pamm-profile', [PammProfileListController::class, 'userPammProfile'])->name('user.pamm.profile');
            Route::get('/user/user-pamm/user-pamm-profile/list/version2', [PammProfileListController::class, 'pamm_list_version2'])->name('user.pamm.profilelist.version2');
            Route::get('/user/user-pamm/user-pamm-profile-list-process', [PammProfileListController::class, 'PammProfileList'])->name('user.pamm.profile.list');

            Route::any('/user/user-pamm/user-pamm-copy-traders-details/{ac?}', [PammProfileController::class, 'userPammCopy'])->name('user.pamm.copy.traders');
            // pamm overview version2
            Route::get('/user/user-pamm/pamm-overview/{ac?}', [PammOverviewController::class, 'index'])->name('user.pamm.trader.overview');
            Route::get('/user/user-pamm/pamm-overview/account-details/{ac?}', [PammOverviewController::class, 'account_details'])->name('user.pamm.trader.overview-account-details');
            Route::post('/user/user-pamm/copy-master', [PammOverviewController::class, 'copy_master'])->name('user.pamm.trader.copy-master');
            // close trade with update version
            Route::get('/user/user-pamm/close-order/version2', [PammOverviewController::class, 'close_order'])->name('user.pamm.overview.close-order');
            Route::get('/user/user-pamm/open-order/version2', [PammOverviewController::class, 'open_order'])->name('user.pamm.overview.open-order');
            // get trade state for update version
            Route::get('/user/user-pamm/trade-state', [PammOverviewController::class, 'trade_state'])->name('pamm.overview.trade-state');
            // set data to doughnut chart
            Route::get('/user/user-pamm/chart-monthly/doughnut', [PammOverviewController::class, 'monthly_doughnut'])->name('pamm.overview.monthly_doughnut');
            Route::get('/user/user-pamm/chart-daily/doughnut', [PammOverviewController::class, 'daily_doughnut'])->name('pamm.overview.daily_doughnut');
            Route::get('/user/user-pamm/chart-hourly/doughnut', [PammOverviewController::class, 'hourly_doughnut'])->name('pamm.overview.hourly');
            // set data to line chart
            Route::get('/user/user-pamm/chart-monthly/linechart', [PammOverviewController::class, 'monthly_line_chart'])->name('pamm.overview.monthly');
            Route::get('/user/user-pamm/chart-monthly/growth', [PammOverviewController::class, 'monthly_growth'])->name('pamm.overview.monthly-growth');
            Route::get('/user/user-pamm/chart-daily/linechart', [PammOverviewController::class, 'daily_line_chart'])->name('pamm.overview.daily');

            Route::any('/user/user-pamm/user-pamm-copy-traders-partial/{ac?}', [PammProfileController::class, 'partial_data'])->name('user.pamm.copy.traders-partial-data');
            Route::get('/user/user-pamm/user-pamm-registration', [PammProfileController::class, 'userPammRegistration'])->name('user.pamm.registraion');
            Route::post('/user/user-pamm/user-pamm-registration-process', [PammProfileController::class, 'PammRegAndUpdate'])->name('user.pamm.registraion.process');
            Route::post('/user/user-pamm/uncopy-master-account', [PammProfileController::class, 'uncopymaster'])->name('user.copy-trades.uncopy-master-account');

            Route::get('/user/user-pamm/master-profit-share', [MasterProfitShareReportController::class, 'profitShareReport'])->name('user.user-pamm.master-profit-share');
            // mamm route
            Route::get('/user/user-mam/manage-slave-account', [MamController::class, 'manageSlaveAccount'])->name('user.mam.manage.slave.account');
            Route::get('/user/user-mam/manage-slave-account-process', [MamController::class, 'SlaveAccountList'])->name('mam.manage.slave.account.process');
            Route::post('/user/meta5_mam_delete/symbol_delete', [MamController::class, 'SymbolDelete'])->name('mam.symbol.delete');
            Route::post('/user/meta5_mam_add/add_symbol', [MamController::class, 'AddSymbol'])->name('mam.symbol.add');
            Route::post('/user/meta5_mam_delete/submit_symbol', [MamController::class, 'UpdateSymbolStatus'])->name('mam.symbol.update');
            Route::get('/user/trading-account-balance-equity', [MamController::class, 'showTradingAccountBl'])->name('mam.trading.account-balance');

            // copy traders route
            Route::get('/user/user-copy/social-traders-report', [CopyTradesController::class, 'copyTraderReport'])->name('user.copy.social.traders.report');
            Route::post('/user/user-copy/social-traders-report-process', [CopyTradesController::class, 'SocialReport'])->name('user.copy.social.datatable_mt5');
            Route::get('/user/user-copy/traders-activities-report', [CopyTradesController::class, 'copyTradersActivitiesReport'])->name('user.copy.traders.activities.report');
            Route::post('/user/user-copy/traders-activities-report-process', [CopyTradesController::class, 'copyTradersActivitiesProcess'])->name('user.copy.traders.activities.process');
            Route::post('/user/user-copy/traders-add-slave-account', [CopyTradesController::class, 'copy_trades'])->name('user.copy-trades.add-slave-account');

            // // master profit report
            // Route::get('/user/user-copy/master-profit-report', [MasterProfitReportController::class, 'masterProfitReport'])->name('user.copy.master-profit-report');
            // withdraw operation ----------------------
            // bank withdraw------------------
            Route::get('/user/withdraw/bank-withdraw', [BankWithdrawController::class, 'form_view'])->name('user.withdraw.bank-withdraw-form');
            Route::post('/user/withdraw/bank', [BankWithdrawController::class, 'bank'])->name('user.withdraw.bank');

            Route::post('/user/withdraw/bank-withdraw-request', [BankWithdrawController::class, 'bank_withdraw'])->name('user.withdraw.bank-withdraw');
            // neteller withdraw------------
            Route::get('/user/withdraw/neteller-withdraw', [NetellerWithdrawController::class, 'neteller_view'])->name('user.withdraw.neteller-withdraw-form');
            Route::post('/user/withdraw/neteller-withdraw-request', [NetellerWithdrawController::class, 'neteller_withdraw'])->name('user.withdraw.neteller-withdraw');
            // skrill withdraw---------------
            Route::get('/user/withdraw/skrill-withdraw', [SkrillWithdrawController::class, 'skrill_view'])->name('user.withdraw.skrill-withdraw-form');
            Route::post('/user/withdraw/skrill-withdraw-request', [SkrillWithdrawController::class, 'skrill_withdraw'])->name('user.withdraw.skrill-withdraw');
            // crypto withdraw---------------
            Route::get('/user/withdraw/crypto-withdraw', [CryptoWithdrawController::class, 'crypto_view'])->name('user.withdraw.crypto-withdraw-form');
            Route::post('/user/withdraw/crypto-withdraw-request', [CryptoWithdrawController::class, 'crypto_withdraw'])->name('user.withdraw.crypto-withdraw');
            // gcash withdraw
            Route::get('/user/withdraw/gcash-withdraw', [GcashWithdrawController::class, 'index'])->name('user.withdraw.gcash-index');
            Route::post('/user/withdraw/gcash-withdraw-request', [GcashWithdrawController::class, 'gcash_withdraw'])->name('user.withdraw.gcash-index.request');

            // end: withdraw operation-------------------
            // deposit operation ----------------------
            // bank deposit------------------
            Route::get('/user/deposit/bank-deposit', [BankDepositController::class, 'form_view'])->name('user.deposit.bank-deposit-form');
            Route::get('/admin-bank-details/get/{bank_id}', [CommonController::class, 'getBankDetails'])->name('admin-bank-details.get');
            Route::post('/user/deposit/bank-deposit-request', [BankDepositController::class, 'bank_deposit'])->name('user.deposit.bank-deposit');
            // crypto deposit------------------
            Route::get('/user/deposit/crypto-deposit', [CryptoDepositController::class, 'form_view'])->name('user.deposit.crypto-deposit-form');
            Route::post('/user/deposit/crypto-deposit-request', [CryptoDepositController::class, 'crypto_deposit'])->name('user.deposit.crypto-deposit-request');

            // perfect money deposit------------------
            Route::get('/user/deposit/perfect-money-deposit', [PerfectMoneyDepositController::class, 'perfectMoneyDeposit'])->name('user.deposit.perfect-money-deposit');
            // help2pay payament gateway
            Route::get('/user/deposit/help2pay', [HelpTwoPayController::class, 'index'])->name('user.deposit.help2pay');
            Route::post('/user/deposit/help2pay/set-form-value', [HelpTwoPayController::class, 'set_form_value'])->name('user.deposit.help2pay-set-form-value');
            Route::get('/user/deposit/help2pay/client', [HelpTwoPayController::class, 'payment_success'])->name('user.deposit.help2pay.client');
            Route::get('/user/deposit/help2pay/response', [HelpTwoPayController::class, 'help2deposit'])->name('user.deposit.help2pay.response');
            // help2pay currency convert
            Route::get('/user/deposit/help2pay/currency-convert/local/{currency?}/rate/{amount?}', [HelpTwoPayController::class, 'convert'])->name('user.deposit.help2pay.convert');
            Route::get('/user/deposit/help2pay/currency-convert/usd/{currency?}/rate/{amount?}', [HelpTwoPayController::class, 'convert_reverse'])->name('user.deposit.help2pay.convert-reverse');
            // match2pay paymentgateway
            Route::get('/user/deposit/matchpay/gateway', [M2payController::class, 'index'])->name('user.deposit.match2pay');
            Route::post('/user/deposit/matchpay/gateway/callback', [M2payController::class, 'callback'])->name('user.deposit.match2pay-callback');
            Route::get('/user/deposit/matchpay/gateway/success', [M2payController::class, 'success'])->name('user.deposit.match2pay-success');
            Route::post('/user/deposit/matchpay/gateway/send-request', [M2payController::class, 'deposit'])->name('user.deposit.match2pay.send');
            Route::get('/user/crypto/crypto-currency/get-single', [CryptoCurrencyController::class, 'currency'])->name('system.crypto-currency.currency');
            Route::get('/user/crypto/crypto-currency/convert', [CryptoCurrencyController::class, 'convert'])->name('system.crypto-currency.convert');

            // praxis payment gateway
            Route::get('/user/deposit/praxis/gateway', [PraxisPaymentController::class, 'index'])->name('user.deposit.praxis');
            Route::get('/user/deposit/praxis/notify', [PraxisPaymentController::class, 'notification'])->name('user.deposit.praxis.notification');
            // Route::get('/user/deposit/praxis/success', [PraxisPaymentController::class, 'success'])->name('user.deposit.praxis.success');
            Route::post('/user/deposit/praxis/request', [PraxisPaymentController::class, 'submit_request'])->name('user.deposit.praxis.request');

            // paypal payment gateway
            Route::get('/user/deposit/paypal', [PayPalController::class, 'index'])->name('user.deposit.paypal');
            Route::post('/user/deposit/paypal/api', [PayPalController::class, 'call_api'])->name('user.deposit.paypal.api');
            Route::get('/user/deposit/paypal/api/success', [PayPalController::class, 'payment_success'])->name('paypal.paypal.success');
            Route::get('/user/deposit/paypal/api/cancel', [PayPalController::class, 'payment_cancel'])->name('paypal.paypal.cancel');

            Route::get('/user/withdraw/paypal', [PayPalWithDrawController::class, 'index'])->name('user.withdraw.paypal');
            Route::post('/user/withdraw/paypal/request', [PayPalWithDrawController::class, 'withdrawPayPal'])->name('withdraw.paypal.request');
            // b2b deposit payment gateway
            Route::get('/user/deposit/b2b', [B2bDepositController::class, 'index'])->name('user.deposit.b2b');
            Route::any('/user/deposit/b2b-client', [B2bDepositController::class, 'create_deposit'])->name('user.deposit.b2b-client');
            Route::any('/user/deposit/b2b-callback', [B2bDepositController::class, 'call_back'])->name('user.deposit.b2b-callback');
            // // kosmos deposit
            // Route::get('/user/deposit/kosmos', [KosmosDepositController::class, 'index'])->name('user.deposit.kosmos');
            // Route::get('/user/deposit/kosmos/callback', [KosmosDepositController::class, 'callback'])->name('user.deposit.kosmos.callback');
            // Route::get('/user/deposit/kosmos/back', [KosmosDepositController::class, 'callback'])->name('user.deposit.kosmos.back');
            // Route::get('/user/deposit/kosmos/success', [KosmosDepositController::class, 'callback'])->name('user.deposit.kosmos.success');
            // Route::get('/user/deposit/kosmos/pending', [KosmosDepositController::class, 'callback'])->name('user.deposit.kosmos.pending');
            // Route::post('/user/deposit/kosmos/store', [KosmosDepositController::class, 'deposit_request'])->name('user.deposit.kosmos.make-request');

            // nowpayments deposit
            Route::get('/user/deposit/nowpayments', NowPayController::class)->name('user.deposit.nowpayments');
            Route::get('/user/deposit/nowpayments/estimate-price', [NowPayController::class, 'estimate_price'])->name('user.deposit.nowpayments.estimate-price');
            Route::post('/user/deposit/nowpayments/submit', [NowPayController::class, 'request_submit'])->name('user.deposit.nowpayments.submit');
            Route::get('/user/deposit/nowpayments/callback', [NowPayController::class, 'callback'])->name('user.deposit.nowpayments.callback');
            Route::get('/user/deposit/nowpayments/success', [NowPayController::class, 'success'])->name('user.deposit.nowpayments.success');

            // end: deposit operation--------------
            //Trader account open route
            // trading account----------------
            Route::get('/user/trading-account/open-live-account', [OpenTradingAccountController::class, 'openAccount'])->name('user.trading.open-account');
            Route::post('/user/trading-account/open-live-trading-account-form', [OpenTradingAccountController::class, 'open_live_account_form'])->name('user.trading-account.open-live-account-form');
            // open demo trading account-----------------
            Route::get('/user/trading-account/open-demo-trading-account', [OpenDemoTradingAccountController::class, 'open_demo_account'])->name('user.trading-account.open-demo-account');
            Route::post('/user/trading-account/get-client-group', [OpenDemoTradingAccountController::class, 'get_client_group'])->name('user.trading-account.client_group');
            Route::post('/user/trading-account/open-demo-trading-account-form', [OpenDemoTradingAccountController::class, 'open_demo_account_form'])->name('user.trading-account.open-demo-account-form');

            // trading account settings-----------------------
            Route::get('/user/trading-account/settings', [TradingAccountSettingsController::class, 'trading_account_settings'])->name('user.trading-account.settings');
            Route::post('/user/trading-account/show-password', [TradingAccountSettingsController::class, 'show_password'])->name('user.trading-account.show-password');
            Route::post('/user/trading-account/settings-form', [TradingAccountSettingsController::class, 'change_password'])->name('user.trading-account.settings-form');
            Route::post('/user/trading-account/settings/leverage-form', [TradingAccountSettingsController::class, 'change_leverage'])->name('user.trading-account.settings-leverage-form');
            Route::get('/user/trading-account/settings/fetch-data', [TradingAccountSettingsController::class, 'fetch_data_dt'])->name('user.trading-account.settings-dt');
             Route::post('/user/trading-account/delete', [TradingAccountSettingsController::class, 'delete_account'])->name('user.trading-account.delete');
            Route::get('/user/balance-equity/{search}/account/{id}', [TradingAccountSettingsController::class, 'balance_equity'])->name('user.api-balance-equity');

            Route::get('/user/trading-account/all-password-show', [TradingAccountSettingsController::class, 'showAllpassword']);
            Route::post('/user/trading-account/password-reset', [TradingAccountSettingsController::class, 'trading_pass_reset'])->name('trading_account.reset.pass');
            // transfer--------------------
            // wta transfer
            Route::post('/user/transfer/get-meta-logo', [WtaTransferController::class, 'meta_logo'])->name('user.transfer.meta-logo');
            // atw transfer
            Route::get('/user/transfer/account-to-wallet-transfer', [AtwTransferController::class, 'atw_transfer_view'])->name('user.transfer.account-to-wallet-transfer');
            Route::post('/user/transfer/account-to-wallet-transfer-form', [AtwTransferController::class, 'atw_transfer'])->name('user.transfer.account-to-wallet-transfer-form');
            Route::get('/user/transfer/wallet-to-account-transfer', [WtaTransferController::class, 'wta_transfer_view'])->name('user.transfer.wallet-to-account-transfer');
            Route::post('/user/transfer/wallet-to-account-transfer-form', [WtaTransferController::class, 'wta_transfer'])->name('user.transfer.wallet-to-account-transfer-form');
            Route::get('/user/transfer/account-to-account-transfer', [AtAtransferController::class, 'ata_transfer_view'])->name('user.transfer.account-to-account-transfer');
            Route::post('/user/transfer/account-to-account-transfer-form', [AtAtransferController::class, 'ata_transfer'])->name('user.transfer.account-to-account-transfer-form');
            // trader fund transfer
            Route::get('/user/transfer/trader-to-trader-transfer', [TraderToTraderTransferController::class, 'trader_transfer_view'])->name('user.transfer.trader-to-trader-transfer');
            Route::post('/user/transfer/trader-to-trader-transfer-form', [TraderToTraderTransferController::class, 'trader_transfer'])->name('user.transfer.trader-to-trader-transfer-form');
            // trader ib fund transfer
            Route::get('/user/transfer/trader-to-ib-transfer', [TraderToIbTransferController::class, 'trader_ib_transfer_view'])->name('user.transfer.trader-to-ib-transfer');
            Route::post('/user/transfer/trader-to-ib-transfer-form', [TraderToIbTransferController::class, 'trader_ib_transfer'])->name('user.transfer.trader-to-ib-transfer-form');
            // session devices
            Route::get('/user/user-admin/sessions/login-device', [SettingsController::class, 'get_session_device'])->name('user.user-admin.session-login-device');
            // copy trade
            Route::get('/user/copy-trade/copy-trade-dashboard', [CopytradeController::class, 'CopyTradeDashboardView'])->name('user.copy-trade.copy-trade-dashboard');
            Route::get('/user/copy-trade/copy-trade-overview', [CopytradeController::class, 'CopyTradeOverview'])->name('user.copy-trade.copy-trade-overview');
            // request for IB
            // for combined crm
            Route::any('/user/combined/ib-request/{id}', [CombinedController::class, 'convert'])->name('user.combined.convert');
            //economic calendar
            Route::get('/user/economic-calendar', [EconomicCalendarController::class, 'economicCalendarView'])->name('user.economic-calendar');
            
            
            // non copy pamm
            // ---------------------------------
            Route::get('/user/pamm/non-copy-pamm-registration', [NoCopyPammRegistrationController::class, 'index'])->name('user.no-copy-pamm-registration');
            Route::get('/user/pamm/non-copy-pamm-registration/balance', [NoCopyPammRegistrationController::class, 'balance_equity']);
            Route::post('/user/pamm/non-copy-pamm-registration', [NoCopyPammRegistrationController::class, 'pamm_registration']);
            Route::get('/user/pamm/non-copy-pamm-list', [NoCopyPammListController::class, 'index'])->name('trader.pamm.non-copy-list');
            Route::get('/user/pamm/non-copy-pamm-list/data', [NoCopyPammListController::class, 'pamm_list']);
            Route::get('/user/pamm/non-copy-pamm-list/overview', [NoCopyPammOverviewController::class, 'index'])->name('trader.pamm.overview');
            Route::get('/user/pamm/overview/trade-details', [NoCopyPammOverviewController::class, 'trade_details']);
            Route::get('/user/pamm/overview/growth-chart', [NoCopyPammOverviewController::class, 'render_growth_chart']);
            Route::get('/user/pamm/overview/balance-equity', [NoCopyPammOverviewController::class, 'balance_equity']);
            Route::get('/user/pamm/overview/account-details', [NoCopyPammOverviewController::class, 'account_details']);
            Route::post('/user/pamm/overview/investment', [NoCopyPammOverviewController::class, 'investment'])->name('trader.pamm.investment');
            Route::get('/user/pamm/overview/monthly-mix-chart', [PammOverviewChartController::class, 'monthly_mix_chart']);
            Route::get('/user/pamm/overview/monthly-doughnut-chart', [PammOverviewChartController::class, 'monthly_doughnut_chart']);
            Route::get('/user/pamm/overview/daily-mix-chart', [PammOverviewChartController::class, 'daily_mix_chart']);
            Route::get('/user/pamm/overview/daily-doughnut-chart', [PammOverviewChartController::class, 'daily_doughnut_chart']);
            Route::get('/user/pamm/overview/hourly-mix-chart', [PammOverviewChartController::class, 'hourly_mix_chart']);
            Route::get('/user/pamm/overview/hourly-doughnut-chart', [PammOverviewChartController::class, 'hourly_doughnut_chart']);
            Route::get('/user/pamm/overview/open-trades', [PammOverviewTradeController::class, 'open_trade']);
            Route::get('/user/pamm/overview/close-trades', [PammOverviewTradeController::class, 'close_trade']);

            Route::get('/user/pamm-report/investment-report', [PammInvestmentController::class, 'index'])->name('trader.pamm-report.investment-report');
            Route::get('/user/pamm-report/investment-report/data', [PammInvestmentController::class, 'investment_report']);
            Route::get('/user/pamm-report/income-report', [PammIncomeReportController::class, 'index'])->name('trader.pamm-report.income-report');
            Route::get('/user/pamm-report/income-report/data', [PammIncomeReportController::class, 'income_report']);

            Route::get('/user/investor-report/my-investment', [MyInvestmentController::class, 'index'])->name('trader.investor-report.my-investment');
            Route::get('/user/investor-report/my-investment/data', [MyInvestmentController::class, 'investment_report']);
            Route::get('/user/investor-report/my-income', [MyIncomeReportController::class, 'index'])->name('trader.investor-report.my-income');
            Route::get('/user/investor-report/my-income/data', [MyIncomeReportController::class, 'income_report']);

            Route::get('/user/investor-report/my-invested-pamm', [MyInvestmentAnalysisController::class, 'index'])->name('trader.investor-report.my-invested-pamm');
            Route::get('/user/investor-report/my-invested-pamm/data', [MyInvestmentAnalysisController::class, 'investment_analysis']);
            
            Route::get('/user/reward/list', [RewardTraderController::class, 'rewardTraderView'])->name('users.reward.list');
            Route::get('/user/rewards', [RewardTraderController::class, 'rewardList'])->name('users.rewards');
            Route::get('/user/assign/rewards/{id}', [RewardTraderController::class, 'assignReward'])->name('users.assign.rewards');
            Route::get('/user/assigned/reward', [RewardTraderController::class, 'fetchOpenRewardWithDependency'])->name('users.assigned.reward');
            Route::get('/user/cancel/reward', [RewardTraderController::class, 'cancelReward'])->name('users.cancel.reward');
            Route::get('/user/claim/reward', [RewardTraderController::class, 'claimReward'])->name('users.claim.reward');
            Route::get('/user/claim/rewards', [RewardTraderController::class, 'claimRewardListReport'])->name('users.claim.rewards');
            Route::get('/user/claim/reward/list', [RewardTraderController::class, 'rewardClaimView'])->name('users.claim.reward.list');

            // reward transfer
            Route::get('/ib/transfer/reward/ib-to-trader/{reward_trader_id}', [RewardTraderController::class, 'rewardTransfer'])->name('ib.reward.transfer.ib-to-trader');

        });
        // client routes (IB)
        Route::middleware(['auth', 'user-access:trader|ib'])->group(function () {
            Route::get('/ib/dashboard', [IbDashboardController::class, 'index'])->name('ib.dashboard');
            Route::get('/user/dashboard/popup-permanently-close/{popup_id}', [TraderDashboardController::class, 'popupPermanentlyClose'])->name('user.dashboard.popup-permanently-close');
            Route::post('/ib/withdraw/bank', [BankWithdrawController::class, 'bank'])->name('ib.withdraw.bank');
            // START: Languate settings
            // --------------------------------------------------------------------------------------------
            Route::post('/ib/change-language', [IbDashboardController::class, 'ib_language_change'])->name('ib.change-language');
            // END: Language Settings
            // START: MY ADMIN---------------------------------------------------------------------------------------------------------
            // profile overview----------
            Route::get('/ib/ib-admin/profile-overview', [IbProfileOverviewController::class, 'profile_overview'])->name('ib.ib-admin.profile-overview');
            Route::get('/ib/ib-admin/profile-overview-social', [IbProfileOverviewController::class, 'userSocialLinkIb'])->name('ib.ib-admin.profile-overview-social');
            Route::get('/user/user-admin/profile-overview-social', [ProfileOverviewController::class, 'userSocialLink'])->name('user.user-admin.profile-overview-social');
            //account verifications
            Route::get('/ib/ib-admin/account-verification', [IbAccountVerificationController::class, 'verification'])->name('ib.ib-admin-account-verification');
            Route::post('/ib/ib-admin/verify-form', [IbAccountVerificationController::class, 'file_upload'])->name('ib.ib-admin-verification-form');
            // settings
            Route::get('/ib/ib-admin/settings', [IbSettingsController::class, 'settings'])->name('ib.ib-admin-account-settings');
            Route::post('/ib/ib-admin/settings/update-ib-info', [IbSettingsController::class, 'updateBasicInfo'])->name('ib.ib-admin-settings.basic-info');
            // password change
            Route::post('/ib/ib-admin/settings/update-password', [IbSettingsController::class, 'updatePassword'])->name('ib.ib-admin-settings.update-password');
            Route::post('/ib/ib-admin/settings/reset-password', [IbSettingsController::class, 'reset_password'])->name('ib.ib-admin-settings.reset-password');
            // change transaction password
            Route::post('/ib/ib-admin/settings/update-transaction-password', [IbSettingsController::class, 'updateTransactionPassword'])->name('ib.ib-admin-settings.update-transaction-password');
            Route::post('/ib/ib-admin/settings/create-transection-password', [IbSettingsController::class, 'createTransectionPassword'])->name('ib.ib-admin.settings.create.transection.password');
            Route::post('/ib/ib-admin/settings/add-update-social-link', [IbSettingsController::class, 'addUpdateSocialLink'])->name('ib.ib-admin-settings.add-update');
            Route::post('/ib/ib-admin/settings/social-link', [IbSettingsController::class, 'SocialLink'])->name('ib.ib-admin-settings.social-link');
            // ib security setting
            Route::post('/ib/ib-admin/settings/security-setting/{check_auth}', [IbSettingsController::class, 'securitySettingUpdate'])->name('ib.ib-admin-settings.security-setting');
            Route::post('/ib/ib-admin/settings/google-security-setting', [IbSettingsController::class, 'googleAuthenticationUpdate'])->name('ib.ib-admin-settings.google-security-setting');
            // ib banking
            Route::get('/ib/ib-admin/ib-banking', [IbBankingController::class, 'ibBanking'])->name('ib.ib-admin.ib-banking');
            Route::get('/ib/ib-admin/ib-banking/fetch-data', [IbBankingController::class, 'ibBankingFetchData'])->name('ib.ib-admin.ib-banking.fetch-data');
            Route::get('/ib/ib-admin/ib-banking/table-description/{id}', [IbBankingController::class, 'ibBankingDescription'])->name('ib.ib-admin.ib-banking.table-description');
            Route::post('/ib/ib-admin/ib-banking-list-delete', [IbBankingController::class, 'bankAccountListDelete'])->name('ib.ib-admin.ib-banking-list-delete');
            Route::get('/ib/ib-admin/ib-banking/edit/fetch-data/{id}', [IbBankingController::class, 'ibBankingEditFetchData'])->name('ib.ib-admin.ib-banking.edit.fetch-data');
            Route::post('/ib/ib-admin/ib-banking-edit', [IbBankingController::class, 'bankAccountEdit'])->name('ib.ib-admin.ib-banking-edit');
            Route::post('/ib/ib-admin/ib-banking-add', [IbBankingController::class, 'bankAccountAdd'])->name('ib.ib-admin.ib-banking-add');
            //Trader account open route
            Route::get('/ib/trading-account/open-live-account', [OpenTradingAccountController::class, 'openAccount'])->name('ib.trading.open-account');
            Route::post('/ib/trading-account/open-live-trading-account-form', [OpenTradingAccountController::class, 'open_live_account_form'])->name('ib.trading-account.open-live-account-form');
            // trading account----------------
            // open demo trading account-----------------
            Route::get('/ib/trading-account/open-demo-trading-account', [OpenDemoTradingAccountController::class, 'open_demo_account'])->name('ib.trading-account.open-demo-account');
            Route::post('/ib/trading-account/get-client-group', [OpenDemoTradingAccountController::class, 'get_client_group'])->name('ib.trading-account.client_group');
            Route::post('/ib/trading-account/open-demo-trading-account-form', [OpenDemoTradingAccountController::class, 'open_demo_account_form'])->name('ib.trading-account.open-demo-account-form');
            // account settings---------
            Route::get('/ib/ib-admin/account-settings/accounts-dt', [SettingsController::class, 'get_trading_account_dt'])->name('ib.ib-admin.accounts-dt');
            // session devices
            Route::get('/ib/ib-admin/sessions/login-device', [SettingsController::class, 'get_session_device'])->name('ib.ib-admin.session-login-device');
            // END : MY ADMIN-----------------------------------------------------------------------------------------------------------
            // ib reports
            Route::get('/ib/reports/ib-comission', [IbComissionReportController::class, 'ibComissionReports'])->name('ib.ib-commission-report.ib-area');
            Route::get('/ib/reports/withdraw', [IbWithdrawReportController::class, 'withdrawReports'])->name('ib.reports.ib-withdraw-reports');
            Route::get('/user/withdraw-decline/{withdraw_id}', [UserWithdrawController::class, 'userWithdrawDecline'])->name('user.withdraw-decline');
            Route::get('/ib/reports/balance-transfer-ib-to-trader', [BalanceTransferToTraderReportController::class, 'balanceTransferReports'])->name('ib.reports.ib-to-trader-transfer');
            Route::get('/ib/reports/balance-transfer-trader-to-ib', [BalanceTransferToIbReportController::class, 'balanceTransferReports'])->name('ib.reports.trader-to-ib-transfer');
            Route::get('/ib/reports/ib-balance/send', [IbBalanceSendController::class, 'BalanceSendingReports'])->name('ib.reports.balance-send-reports');
            Route::get('/ib/reports/ib-balance/recived', [IbBalanceRecivedController::class, 'BalanceRecivingReports'])->name('ib.reports.balance-recived-reports');
            // ib Affiliate
            Route::get('/ib/affiliates/clients-withdraw-report', [IbClientWithdrawReport::class, 'withdrawReports'])->name('affiliats.withdraw-reports');
            Route::get('/ib/affiliates/clients-deposit-report', [IbClientDepositReport::class, 'depositReports'])->name('ib.affilates.deposit-reports');
            //Affliate route
            Route::get('/ib/affiliates/my-clients', [ibAffiliateClientController::class, 'myClients'])->name('ib.myclients.report');
            Route::get('/ib/affiliates/my-ib', [myIbAffiliateController::class, 'myIb'])->name('ib.my-ib.report');
            // ib tree
            Route::get('/ib/affiliates/ib-tree', [AffiliateIbTreeController::class, 'ib_tree'])->name('ib.my-ib.tree');

            // ib bank withdraw
            Route::any('/ib/withdraw/bank-withdraw', [BankWithdrawController::class, 'ib_bank_withdraw'])->name('ib.withdraw.bank-withdraw');
            // ib crypto withdraw
            Route::any('/ib/withdraw/crypto-withdraw', [CryptoWithdrawController::class, 'ib_crypto_withdraw'])->name('ib.withdraw.crypto-withdraw');
            // gcash withdraw
            Route::get('/ib/withdraw/gcash-withdraw', [IBGcashWithdrawController::class, 'index'])->name('ib.withdraw.gcash-index');
            Route::post('/ib/withdraw/gcash-withdraw-request', [IBGcashWithdrawController::class, 'gcash_withdraw'])->name('ib.withdraw.gcash-index.request');
            // ib to trader transfer-------------------------
            Route::any('ib/transfer/ib-to-trader', [IBtoTraderTransferController::class, 'ib_to_trader_trnasfer'])->name('ib.transfer.ib-to-trader');
            Route::get('ib/transfer/ib-to-ib', [IbToIbTransferController::class, 'index'])->name('ib.transfer.ib-to-ib');
            Route::post('ib/transfer/ib-to-ib/make-request', [IbToIbTransferController::class, 'make_request'])->name('ib.transfer.ib-to-ib.request');
            //Start : user Support

            Route::get('ib/support/ticket', [SupportControllerForIb::class, 'index'])->name('ib.support.ticket');
            Route::any('ib/support/support-ticket-get',  [SupportControllerForIb::class, 'get_support'])->name('ib.support.support-ticket-get');
            Route::any('ib/support/support-ticket-reply',  [SupportControllerForIb::class, 'get_support_reply'])->name('ib.support.support-ticket-reply');
            Route::any('ib/support/support-ticket-delete',  [SupportControllerForIb::class, 'delete_ticket'])->name('ib.support.delete-ticket');
            Route::any('ib/support/support-send-reply',  [SupportControllerForIb::class, 'send_support_reply'])->name('ib.support.support-send-reply');
            Route::any('ib/support/create-ticket',  [SupportControllerForIb::class, 'create_ticket'])->name('ib.support.create-ticket');
            Route::post('ib/support/get-server-replay',  [SupportControllerForIb::class, 'server_replay'])->name('ib.support.server-replay');
            // User OTP Verification Route
            Route::any('ib/otp_verification_submit/{name}/{check}', [IbOTPVerificationController::class, 'otpVerification'])->name('ib.otp_verification_submit');

            Route::get('/ib/marketing/ib-banner', [MarketingController::class, 'ibBannerView'])->name('ib.marketing.ib-banner');
            Route::get('/ib/marketing/trader-banner', [MarketingController::class, 'traderBannerView'])->name('ib.marketing.trader-banner');
            Route::get('/ib/marketing/banner/table-description/{banner_user}', [MarketingController::class, 'bannerDescription'])->name('ib.marketing.banner.table-description');
        });
        // addmin routes
        Route::middleware(['auth', 'user-access:admin|manager|system'])->group(function () {
            Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
            Route::post('/admin/dashboard/trackerfilter', [AdminDashboardController::class, 'supportTrackerFilter'])->name('admin.dashboard.traker-filter');
            Route::post('/admin/dashboard/revenue-by-month', [AdminDashboardController::class, 'getRevenueDataByMonth'])->name('admin.dashboard.revenue-by-month');
            Route::get('/admin/trader/dashboard/{id}', [AdminTraderAdminController::class, 'goto_trader_dashboard'])->name('admin.trader.dashboard');
            Route::get('/admin/trader/delete/{id}', [AdminTraderAdminController::class, 'delete_trader'])->name('admin.trader.delete');
            // START: Languate settings
            // --------------------------------------------------------------------------------------------
            Route::post('/admin/change-language', [LocalizationController::class, 'lang_change'])->name('admin.change-language-by-admin');
            // END: Language Settings
            // -----------------------------------------------------------------------
            // admin category\
            Route::resource('admin/categories', CategoryController::class);

            // admin group settings
            Route::resource('admin/ib-groups', IbGroupController::class);

            // client group settings
            Route::resource('admin/client-groups', ClientGroupController::class);
            Route::post('/admin/client-groups/get', [ClientGroupController::class, 'getClientGroup'])->name('admin.client-group.get');
            Route::get('/admin/edit-client-groups', [ClientGroupController::class, 'get_edit_data'])->name('admin.client-group.edit-data');
            Route::post('/admin/update-client-groups', [ClientGroupController::class, 'update'])->name('admin.client-group.update-data');

            // manage trading accounts  ----------------------------------------------
            Route::any('admin/trading-account-details-live', [LiveTradingAccountDetailsController::class, 'index'])->name('admin.live.trading-acccount.de');
            Route::get('/admin/trading-account-details/show-pass', [LiveTradingAccountDetailsController::class, 'showPass']);
            Route::post('/admin/trading-account-details/reset-pass', [LiveTradingAccountDetailsController::class, 'passReset'])->name('trading-password.reset');
            Route::get('/admin/trading-account-details/show-pass-data', [LiveTradingAccountDetailsController::class, 'showPassData'])->name('trading-password.show');

            Route::post('/admin/trading-account-details/change-leverage', [LiveTradingAccountDetailsController::class, 'traderChangeLeverage'])->name('admin.trading-account-details.change-leverage');
            Route::post('/admin/trading-account-details/change-group', [LiveTradingAccountDetailsController::class, 'traderChangeGroup'])->name('admin.trading-account-details.change-group');

            Route::any('admin/trading-account-details-demo', [DemoTradingAccountDetailsController::class, 'index']);
            // / Password Settings enable route fo admin
            Route::any('/admin/password_settings/{name}/{check}', [PasswordSettingsController::class, 'passwordSettings']);
            //Transaction Setting
            Route::post('/admin/transaction_settings/deposit-settings', [TransactionSettingsController::class, 'deposit_settings'])->name('admin.transaction.settings.deposit');
            Route::post('/admin/transaction_settings/crypto-deposit-settings', [TransactionSettingsController::class, 'crypto_deposit_setting'])->name('admin.transaction.settings.crypto-deposit-setting');
            Route::post('/admin/transaction_settings/withdraw-settings', [TransactionSettingsController::class, 'withdraw_settinngs'])->name('admin.transaction.settings.withdraw');
            // -----------------------------------------------------------------------
            // Admin OTP verifcation route
            Route::any('admin/otp-settings/store', [OTPVerificationController::class, 'otpVerification'])->name('admin.otp-settings.save');
            // / social login settings enable route
            Route::any('/admin/social_login/{name}/{check}', [SocialLoginController::class, 'socialLogin']);

            //admin profile route here
            Route::get('/admin/profile/profile-settings/', [AdminProfileController::class, 'profileSetting'])->name('admin.profile-settings');

            Route::post('/admin/profile/profile-settings/setup-email', [AdminProfileController::class, 'sendEmail'])->name('admin.profile-settings.send-email');
            Route::post('/admin/profile/profile-settings/setup-phone', [AdminProfileController::class, 'sendPhoneEmail'])->name('admin.profile-settings.send-phone');
            Route::post('/admin/profile/profile-settings/reset-transactin-pass', [AdminProfileController::class, 'resetTransactionPass'])->name('admin.profile-settings.reset-transaction-pass');
            Route::post('/admin/profile/profile-settings/change-transactin-pass', [AdminProfileController::class, 'changeTransactionPass'])->name('admin.profile-settings.change-transaction-pass');
            Route::post('/admin/profile/profile-settings/update-account-details', [AdminProfileController::class, 'account_details'])->name('admin.update-account-details');
            Route::post('/admin/profile/profile-settings/update-address', [AdminProfileController::class, 'update_address'])->name('admin.update-address');
            Route::post('/admin/profile/profile-settings/update-personal-info', [AdminProfileController::class, 'update_personal_info'])->name('admin.update-personal-info');
            Route::post('/admin/profile/profile-settings/update-social-links', [AdminProfileController::class, 'update_social_links'])->name('admin.update-social-links');
            Route::post('/admin/profile/profile-settings/email-change-otp', [AdminProfileController::class, 'email_change_otp'])->name('admin.email-change-otp');
            Route::post('/admin/profile/profile-settings/phone-change-otp', [AdminProfileController::class, 'phone_change_otp'])->name('admin.phone-change-otp');
            Route::post('/admin/profile/profile-settings/email-change', [AdminProfileController::class, 'email_change'])->name('admin.email-change');
            Route::post('/admin/profile/profile-settings/phone-change', [AdminProfileController::class, 'phone_change'])->name('admin.phone-change');
            // manage live trading accounts  -----------------------------------------
            Route::get('admin/trading-account-details', [TradingAccountDetailsController::class, 'index']);
            Route::get('admin/trader-description/{userId}/{accountId}', [TradingAccountDetailsController::class, 'traderListDescription']);
            Route::get('admin/trading-account-details/deposits/{id}', [TradingAccountDetailsController::class, 'depositListDT']);
            Route::get('admin/trading-account-details/withdraws/{id}', [TradingAccountDetailsController::class, 'withdrawListDT']);
            Route::get('admin/trading-account-details/bonus/{id}', [TradingAccountDetailsController::class, 'bonusListDT']);
            Route::post('admin/trading-account-details/block-unblock-trader', [TradingAccountDetailsController::class, 'blockUnblock']);
            Route::post('admin/trading-account-details/deposit-operation-trader', [TradingAccountDetailsController::class, 'depositOperation']);
            Route::post('admin/trading-account-details/withdraw-operation-trader', [TradingAccountDetailsController::class, 'withdrawOperation']);
            Route::post('admin/trading-account-details/trader-change-password', [TradingAccountDetailsController::class, 'changePassword']);
            Route::post('admin/trading-account-details/trader-reset-password', [TradingAccountDetailsController::class, 'resetPassword']);
            // ------------------------------------------------------------------


            Route::get('/admin/report/deposit', [AdminDepositController::class, 'deposit_report'])->name('admin.deposit-report');
            Route::get('/admin/report/deposit-dt-proccess', [AdminDepositController::class, 'deposit_dt_proccess'])->name('admin.deposit-dt-proccess');
            Route::get('/admin/report/dt-description-deposit', [AdminDepositController::class, 'deposit_dt_description'])->name('admin.deposit-dt-description');
            Route::get('/admin/report/deposit-dt-inner-proccess/{id}', [AdminDepositController::class, 'deposit_dt_inner'])->name('admin.deposit-dt-description');

            // admin withdraw report
            Route::get('/admin/report/withdraw', [AdminWithdrawController::class, 'withdraw_report']);
            Route::get('/admin/report/withdraw-description', [AdminWithdrawController::class, 'withdraw_description']);
            Route::get('/admin/report/withdraw-inner-description/{id}', [AdminWithdrawController::class, 'withdraw_inner_description']);

            // admin log report log-dt-fetch-data
            Route::get('/admin/report/log', [AdminLogController::class, 'log_report'])->name('admin.log-report');
            Route::get('/admin/report/log-dt-fetch-data', [AdminLogController::class, 'log_dt_fetch_data'])->name('admin.deposit-dt-proccess');
            Route::get('/admin/report/dt-description-log', [AdminLogController::class, 'log_dt_description'])->name('admin.deposit-dt-proccess');
            Route::get('/admin/report/log-dt-inner-fetch-data/{id}', [AdminLogController::class, 'deposit_dt_fetch_data'])->name('admin.deposit-dt-description');

            Route::get('/admin/report/ib-commission', [AdminIBCommissionController::class, 'ibCommission'])->name('admin.ib-commission.report');
            Route::get('/admin/report/ib-commission/get-data', [AdminIBCommissionController::class, 'ibCommissionRP'])->name('admin.report.ib-commission.get-data');

            // admin trader admin report
            Route::get('/admin/client-management/trader-admin', [AdminTraderAdminController::class, 'trader_admin_report'])->name('admin.trader-admin');
            Route::get('/admin/client-management/trader-admin-fetch-data', [AdminTraderAdminController::class, 'trader_admin_dt_fetch_data'])->name('admin.trader-admin-proccess');
            Route::get('/admin/client-management/trader-admin-account/{id}', [AdminTraderAdminController::class, 'trader_admin_inner_fetch_data'])->name('admin.trader-admin-account');
            Route::get('/admin/client-management/trader-admin-dt-deposit-fetch-data/{id}', [AdminTraderAdminController::class, 'trader_admin_deposit_fetch_data'])->name('admin.deposit-trader-admin-description');
            Route::get('/admin/client-management/trader-admin-dt-withdraw-fetch-data/{id}', [AdminTraderAdminController::class, 'trader_admin_withdraw_fetch_data'])->name('admin.withdraw-trader-admin-description');
            Route::get('/admin/client-management/trader-admin-dt-bonus-fetch-data/{id}', [AdminTraderAdminController::class, 'trader_admin_bonus_fetch_data'])->name('admin.bonus-trader-admin-description');
            Route::get('/admin/client-management/trader-admin-dt-kyc-fetch-data/{id}', [AdminTraderAdminController::class, 'trader_admin_kyc_fetch_data'])->name('admin.kyc-trader-admin-description');
            Route::get('/admin/client-management/lead-admin', [AdminTraderAdminController::class, 'lead_admin_report'])->name('admin.lead-admin');
            Route::get('/admin/client-management/conver-to-lead-admin/{id}', [AdminTraderAdminController::class, 'convert_to_lead'])->name('admin.convert.to.lead');
            Route::get('/admin/client-management/trader-admin-single/{id}', [AdminTraderAdminController::class, 'get_single_admin_trader_report'])->name('admin.trader-admin-single');

            
            // trader admin comment report
            Route::get('/admin/client-management/trader-admin-dt-comment-fetch-data/{id}', [AdminTraderAdminController::class, 'trader_admin_comment_fetch_data'])->name('admin.comment-trader-admin-description');
            Route::post('/admin/client-management/trader-admin-dt-comment-post-data', [AdminTraderAdminController::class, 'trader_admin_comment_save_data'])->name('admin.comment-trader-admin-form');
            Route::post('/admin/client-management/trader-admin-update-comment', [AdminTraderAdminController::class, 'comment_update'])->name('admin.comment-trader-admin-update-form');
            Route::post('/admin/client-management/trader-admin-delete-comment', [AdminTraderAdminController::class, 'comment_delete'])->name('admin.comment-trader-admin-delete-comment');
            // // trader admin internal/external transfer report
            Route::get('/admin/client-management/trader-admin-dt-internal-trans/{id}', [AdminTraderAdminController::class, 'internal_transfer_report'])->name('admin.internal-trader-admin-description');
            Route::get('/admin/client-management/trader-admin-dt-external-trans/{id}', [AdminTraderAdminController::class, 'external_transfer_report'])->name('admin.external-trader-admin-description');
            // block unblock trader
            Route::post('/admin/client-management/trader-admin-block-trader', [AdminTraderAdminController::class, 'block_unblock'])->name('admin.block-unblock-trader-admin');
            Route::post('/admin/client-management/trader-admin-google-two-step', [AdminTraderAdminController::class, 'two_step_auth'])->name('admin.two-step-trader-admin');
            Route::post('/admin/client-management/trader-admin-email-auth', [AdminTraderAdminController::class, 'email_auth'])->name('admin.email-auth-trader-admin');
            Route::post('/admin/client-management/trader-admin-email-verification', [AdminTraderAdminController::class, 'email_verification'])->name('admin.email-verification-trader-admin');
            // trader admin finance operation
            Route::post('/admin/client-management/trader-admin-deposit-operation', [TraderadminFinanceOpController::class, 'deposit_operation'])->name('admin.deposit-operation-trader-admin');
            Route::post('/admin/client-management/trader-admin-withdraw-operation', [TraderadminFinanceOpController::class, 'withdraw_operation'])->name('admin.withdraw-operation-trader-admin');
            Route::post('/admin/client-management/trader-admin-internal-transfer', [TraderadminFinanceOpController::class, 'internal_transfer'])->name('admin.internal-transfer-trader-admin');
            Route::post('/admin/client-management/trader-admin-internal-transfer-wta', [TraderadminFinanceOpController::class, 'wta_finance_op'])->name('admin.internal-transfer-trader-admin.wta');
            Route::post('/admin/client-management/trader-admin-trader-to-trader', [TraderadminFinanceOpController::class, 'trader_to_trader'])->name('admin.internal-transfer-trader-to-trader');
            Route::post('/admin/client-management/trader-admin-trader-to-ib', [TraderadminFinanceOpController::class, 'trader_to_ib'])->name('admin.internal-transfer-trader-to-ib');
            Route::post('/admin/client-management/trader-admin-ib-to-trader', [TraderadminFinanceOpController::class, 'ib_to_trader'])->name('admin.internal-transfer-ib-to-trader');

            // set category

            Route::post('/admin/client-management/trader-admin-set-category', [AdminTraderAdminController::class, 'set_category'])->name('admin.set-category-trader-admin');
            Route::post('/admin/client-management/trader-admin-change-password', [AdminTraderAdminController::class, 'change_password'])->name('admin.change-password-trader-admin');
            Route::get('/admin/client-management/trader-admin-change-password-mail/{trader_id}', [AdminTraderAdminController::class, 'change_password_mail'])->name('admin.change-password-mail-trader-admin');
            Route::post('/admin/client-management/trader-admin-change-pin', [AdminTraderAdminController::class, 'change_pin'])->name('admin.change-pin-trader-admin');
            Route::get('/admin/client-management/trader-admin-change-pin-mail/{trader_id}', [AdminTraderAdminController::class, 'change_pin_mail'])->name('admin.change-pin-mail-trader-admin');
            // trader reset password-----------------
            Route::post('/admin/client-management/trader-admin-reset-password', [AdminTraderAdminController::class, 'reset_password'])->name('admin.reset-password-trader-admin');
            Route::get('/admin/client-management/trader-admin-reset-password-mail/{trader_id}', [AdminTraderAdminController::class, 'password_reset_mail'])->name('admin.reset-password-trader-admin-mail');
            // trader reset transaction pin------------
            Route::post('/admin/client-management/trader-admin-reset-transaction-pin', [AdminTraderAdminController::class, 'reset_transaction_pin'])->name('admin.reset-pin-trader-admin');
            Route::get('/admin/client-management/trader-admin-reset-transaction-pin-mail/{trader_id}', [AdminTraderAdminController::class, 'transaction_pin_reset_mail'])->name('admin.reset-pin-trader-admin');
            // change kyc status
            Route::post('/admin/client-management/trader-admin-change-kyc', [TraderAdminSecurityController::class, 'change_kyc_status'])->name('admin.trader-kyc-status');
            // transfer operation enabled / disabled for trader
            Route::post('/admin/client-management/wta-transfer', [AdminTraderAdminController::class, 'wtaTransferOperation'])->name('trader.admin.report-description-inner.wta-transfer');
            Route::post('/admin/client-management/ib-to-ib-transfer', [AdminTraderAdminController::class, 'ibToIbTransferOperation'])->name('trader.admin.report-description-inner.ib-to-ib-transfer');
            Route::post('/admin/client-management/ib-to-trader-transfer', [AdminTraderAdminController::class, 'ibToTraderTransferOperation'])->name('trader.admin.report-description-inner.ib-to-trader-transfer');
            Route::post('/admin/client-management/trader-to-trader-transfer', [AdminTraderAdminController::class, 'traderToTraderTransferOperation'])->name('trader.admin.report-description-inner.trader-to-trader-transfer');
            Route::post('/admin/client-management/kyc-verify', [AdminTraderAdminController::class, 'kycVerifyOperation'])->name('trader.admin.report-description-inner.kyc-verify');

            // assign to desk manager------------------------
            Route::get('/admin/client-management/get-desk-manager/{email}', [AdminTraderAdminController::class, 'get_desk_manager'])->name('admin.trader-admin-desk-manager');
            Route::post('/admin/client-management/assign-desk-manager', [AdminTraderAdminController::class, 'assign_desk_manager'])->name('admin.trader-admin-assign-desk-manager');
            // assign to account manager
            Route::get('/admin/client-management/get-account-manager/{email}', [AdminTraderAdminController::class, 'get_account_manager'])->name('admin.trader-admin-account-manager');
            Route::post('/admin/client-management/assign-account-manager', [AdminTraderAdminController::class, 'assign_account_manager'])->name('admin.trader-admin-assign-account-manager');
            Route::post('/admin/client-management/bulk-assign-account-manager', [AdminTraderAdminController::class, 'bulkassignManager'])->name('admin.trader-admin-bulk-assign-account-manager');

            
            // resent verification email-------
            Route::get('/admin/client-management/resent-verification-email/{trader_id}', [AdminTraderAdminController::class, 'resent_verification_email'])->name('admin.trader-admin-resent-verification-email');
            // send welcome mail-----------
            Route::get('/admin/client-management/send-welcome-email/{trader_id}', [AdminTraderAdminController::class, 'send_welcome_mail'])->name('admin.trader-admin-send-welcome-mail');
            // finance report---------------
            Route::get('/admin/client-management/finance-report/{id}', [AdminTraderAdminController::class, 'finance_report'])->name('admin.trader-admin-finance-report');
            // add new trader-----------------
            Route::post('/admin/client-management/add-new-trader', [AdminTraderAdminController::class, 'add_new_trader'])->name('admin.trader-admin-add-new-trader');
            Route::any('/admin/client-management/add-account-manually/{id?}', [AdminTraderAdminController::class, 'account_manually'])->name('admin.trader-admin-add-account-manually');
            Route::any('/admin/client-management/add-account-auto/{id?}', [AdminTraderAdminController::class, 'account_auto'])->name('admin.trader-admin-add-account-auto');
            Route::post('/admin/client-management/trader-admin-transfer-account-no', [AdminTraderAdminController::class, 'tradingAccountTransfer'])->name('admin.trader-admin-transfer-account-no');
            Route::post('/admin/client-management/deleted-account-list', [AdminTraderAdminController::class, 'allDeletedTradingAccount'])->name('admin.client-management.deleted-account-list');
            //convert trader to ib
            Route::any('/admin/client-management/convert-to-ib/{userID}', [AdminTraderAdminController::class, 'TraderToIB'])->name('admin.trader-conver-to-ib');
            Route::any('/admin/client-management/remove-ib-access/{userID}', [AdminTraderAdminController::class, 'RemoveIBAccess'])->name('admin.remove-access');

            // trader admin update profile
            Route::post('/admin/client-management/update-acc-details', [TraderAdminUpdateProfileController::class, 'update_account_details'])->name('admin.update-acc-details');
            Route::post('/admin/client-management/update-personal-details', [TraderAdminUpdateProfileController::class, 'update_personal_details'])->name('admin.update-persoanl-details');
            Route::post('/admin/client-management/update-social-details', [TraderAdminUpdateProfileController::class, 'update_social_details'])->name('admin.update-social-details');

            // trader clients------------------------------------------------------------------------------
            Route::get('/admin/client-management/trader-clients', [TraderClientController::class, 'trader_client'])->name('admin.trader-clients');
            Route::get('/admin/client-management/trader-client-datatable', [TraderClientController::class, 'trader_client_datatable'])->name('admin.trader-clients-dt');
            Route::get('/admin/client-management/dt-description-trader-clients/{id}', [TraderClientController::class, 'datatable_description'])->name('admin.trader-clients-dt-description');
            Route::get('/admin/client-management/get-user-info/{id}', [TraderClientController::class, 'get_user_info'])->name('admin.trader-clients-get-user-info');
            Route::post('/admin/client-management/update-profile', [TraderClientController::class, 'update_profile'])->name('admin.trader-clients-update-profile');

            // trader analysis
            Route::get('/admin/client-management/trader-analysis', [TraderAnalysisController::class, 'index'])->name('admin.trader-analysis'); //trader analysys
            Route::any('/admin/client-management/trader-analysis-data', [TraderAnalysisController::class, 'get_data'])->name('admin.trader-analysis-data'); //trader analysis submit
            Route::get('/admin/client-management/special-customer', [TraderAnalysisController::class, 'special_customer'])->name('admin.trader-special-customer'); 
            Route::post('/admin/client-management/make-special-customer', [TraderAnalysisController::class, 'make_special_customer'])->name('admin.trader-make-special-customer');
            // END:trader client---------------------------------------------------------------------------
            // admin management
            
            
            
            
            
             Route::get('/admin/client-management/assign-group', [AssignGroupController::class, 'index'])->name('admin.assign-group');
             Route::post('/admin/client-management/assign-group/search', [AssignGroupController::class, 'searchUsers'])->name('admin.assign-group.search');
             Route::post('/admin/client-management/assign-group/assign-groups', [AssignGroupController::class, 'assignGroupsToUsers'])->name('admin.assign-group.assign-groups');
             Route::get('/admin/client-management/assign-group/test', [AssignGroupController::class, 'test'])->name('admin.assign-group.test');
             Route::get('/admin/client-management/assign-group/client-groups', [AssignGroupController::class, 'getClientGroups'])->name('admin.assign-group.client-groups');
            
            
              // Manager Groups (Client Management)
             Route::get('/admin/client-management/manager-groups', [\App\Http\Controllers\admins\ManagerGroupsClientController::class, 'index'])->name('admin.client-management.manager-groups');
             Route::post('/admin/client-management/manager-groups/assigned-users', [\App\Http\Controllers\admins\ManagerGroupsClientController::class, 'getAssignedUsers'])->name('admin.client-management.manager-groups.assigned-users');
             Route::get('/admin/client-management/manager-groups/client-groups', [\App\Http\Controllers\admins\ManagerGroupsClientController::class, 'getClientGroups'])->name('admin.client-management.manager-groups.client-groups');
             Route::post('/admin/client-management/manager-groups/assign-groups', [\App\Http\Controllers\admins\ManagerGroupsClientController::class, 'assignGroupsToUsers'])->name('admin.client-management.manager-groups.assign-groups');

            
            
            
            // RolseControler
            Route::get('/admin/admin-management/roles', [RolesController::class, 'view_roles'])->name('admin.roles');
            Route::post('/admin/admin-management/add-new-role', [RolesController::class, 'store_role'])->name('admin.add-new-right');
            Route::get('/admin/admin-management/get-all-role', [RolesController::class, 'get_all_roles'])->name('admin.get-all-roles');

            // permission controller
            Route::get('/admin/admin-management/fetch-modal-role-permission/{id}', [PermissionController::class, 'get_roles_permission'])->name('admin.get-all-roles-permissions');
            Route::post('/admin/admin-management/assign-perimission-to-role', [PermissionController::class, 'set_permission_to_role'])->name('admin.set-all-roles-permissions');
            Route::post('/admin/admin-management/add-new-right', [PermissionController::class, 'store_permission'])->name('admin.store-new-permissions');

            // AdminGroupsController
            Route::get('/admin/admin-management/admin-groups', [AdminGroupsController::class, 'index'])->name('admin.admin-groups');
            Route::post('/admin/admin-management/add-group', [AdminGroupsController::class, 'store'])->name('admin.add-admin-group');
            Route::post('/admin/admin-management/update-group', [AdminGroupsController::class, 'update'])->name('admin.update-admin-group');
            Route::get('/admin/admin-management/get-all-admins', [AdminGroupsController::class, 'get_all_admins'])->name('admin.get-all-admins');
            Route::get('/admin/admin-management/get-all-admin-description/{id}', [AdminGroupsController::class, 'get_all_admin_description'])->name('admin.get-all-admin-decscription');
            Route::get('/admin/admin-management/get-all-admin-description-users/{id}', [AdminGroupsController::class, 'get_all_admin_description_users'])->name('admin.get-all-admin-decscription-users');

            // update admin profile
            Route::post('admin/admin-management/admin-groups/update-account-details', [AdminGroupsController::class, 'update_account_details'])->name('admin.admin-group.account-details');
            Route::post('admin/admin-management/admin-groups/update-personal-info', [AdminGroupsController::class, 'update_personal_info'])->name('admin.admin-group.personal-info');
            Route::post('admin/admin-management/admin-groups/update-address', [AdminGroupsController::class, 'update_address'])->name('admin.admin-group.update-address');
            Route::post('admin/admin-management/admin-groups/update-social-link', [AdminGroupsController::class, 'update_social_link'])->name('admin.admin-group.update-social-link');

            // Admin Registration Controller
            Route::get('/admin/admin-management/admin-registration', [AdminRegistrationController::class, 'index'])->name('admin.admin-registration');
            Route::post('/admin/admin-management/admin-registration', [AdminRegistrationController::class, 'store'])->name('admin.admin-store-registration');
            Route::post('/admin/admin-management/admin-registration/account-details', [AdminRegistrationController::class, 'acctoun_details'])->name('admin.admin-store.account-details');
            Route::post('/admin/admin-management/admin-registration/personal-info', [AdminRegistrationController::class, 'personal_info'])->name('admin.admin-store.personal-info');
            Route::post('/admin/admin-management/admin-registration/address', [AdminRegistrationController::class, 'address'])->name('admin.admin-store.address');
            // udpate users by admin
            Route::post('/admin/admin-management/user-update', [UserController::class, 'update_user'])->name('admin.user-update-by-admin');
            Route::get('/admin/admin-management/user-get-form/{type}/user/{id}', [UserController::class, 'get_form'])->name('admin.get-user-update-form');
            Route::get('/admin/admin-management/user-get-form/admin-data/{id}', [UserController::class, 'get_admin_data'])->name('admin.admin-data-get');
            // add new right to admin
            Route::post('/admin/admin-management/add-new-right/', [RightController::class, 'add_new_right'])->name('admin.admin-store');
            //START:  Manager settings
            // -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            Route::get('/admin/manager-settings/manager-group', [ManagerGroupController::class, 'index'])->name('admin.manager-groups');
            Route::post('/admin/manager-settings/manager-group', [ManagerGroupController::class, 'store'])->name('admin.add-manager-group');
            Route::get('/admin/manager-settings/manager-group-get', [ManagerGroupController::class, 'managerGroup'])->name('admin.add-manager-group-get');
            Route::get('/admin/manager-settings/manager-group/datatable-custom', [ManagerGroupController::class, 'get_manager_group'])->name('admin.manager-groups.get-manager-group');
            // edit manager group
            Route::post('/admin/manager-settings/manager-group-edit', [ManagerGroupController::class, 'edit_manager_group'])->name('admin.edit-manager-group');
            // delete manager group
            Route::post('/admin/manager-settings/manager-group-delete', [ManagerGroupController::class, 'delete_manager_group'])->name('admin.delete-manager-group');

            Route::get('/admin/manager-settings/add-manager', [AddManagerController::class, 'index'])->name('admin.add-manager');
            Route::post('/admin/manager-settings/add-manager', [AddManagerController::class, 'store'])->name('admin.add-manager');
            Route::get('/admin/manager-settings/get-manager-group-type/{type}', [AddManagerController::class, 'get_group'])->name('admin.get-manager-group-by-type');
            // edit manager info
            Route::get('/admin/manager-settings/get-manager-info/{id}', [AddManagerController::class, 'get_manager_info'])->name('admin.get-manager-info');
            Route::post('/admin/manager-settings/edit-manager', [AddManagerController::class, 'edit_manager'])->name('admin.edit-manager');
            Route::post('/admin/manager-settings/disable-manager', [AddManagerController::class, 'disable_manager'])->name('admin.disable-manager');
            Route::post('/admin/manager-settings/enable-manager', [AddManagerController::class, 'enable_manager'])->name('admin.enable-manager');
            Route::post('/admin/manager-settings/block-manager', [AddManagerController::class, 'block_manager'])->name('admin.block-manager');
            //START GROUP RIGHT PERMISSION
            Route::get('/admin/manager-settings/group-permission', [GroupPermissionController::class, 'index'])->name('admin.groups-permission');
            Route::get('/admin/manager-settings/group-permission-datatable', [GroupPermissionController::class, 'get_groups'])->name('admin.get-group-permission-dt');
            Route::get('/admin/manager-settings/group-des-with-right/{id}', [GroupPermissionController::class, 'group_des_right'])->name('admin.group-des-available-right');
            Route::get('/admin/admin-management/fetch-group-role-permission/{id}', [GroupPermissionController::class, 'get_group_roles_permission'])->name('admin.get-group-all-roles-permissions');
            Route::post('/admin/admin-management/assign-group-perimission-to-role', [GroupPermissionController::class, 'set_group_permission_to_role'])->name('admin.set-group-roles-permissions');

            // START MANAGER LIST
            // --------------------------------------------------------------------------------------
            Route::get('/admin/manager-settings/get-manager', [Managercontroller::class, 'index'])->name('admin.get-manager');
            Route::get('/admin/manager-settings/get-manager-datatable', [Managercontroller::class, 'get_managers'])->name('admin.get-managers-dt');
            Route::get('/admin/manager-settings/get-manager-datatable-description/{id}', [Managercontroller::class, 'get_all_manager_description'])->name('admin.get-managers-dtdescription');
            Route::get('/admin/manager-settings/get-manager-datatable-description-users/{id}', [Managercontroller::class, 'get_all_manager_description_users'])->name('admin.get-managers-dtdescription');
            Route::get('/admin/manager-settings/get-manager-datatable-description-ib/{id}', [Managercontroller::class, 'get_all_manager_description_ib'])->name('admin.get-managers-dtdescription-ib');
            Route::get('/admin/manager-settings/get-manager-datatable-description-trader/{id}', [Managercontroller::class, 'get_all_manager_description_trader'])->name('admin.get-managers-dtdescription-trader');
            Route::get('/admin/manager-settings/get-manager-datatable-description-manager/{id}', [Managercontroller::class, 'get_all_manager_description_manager'])->name('admin.get-managers-dtdescription-manager');
            Route::post('/admin/manager-settings/assigne-user-to-manager', [Managercontroller::class, 'assigen_user_to_manager'])->name('admin.assigne-user-to-manager');


            // Start: Manager right
            // --------------------------------------------------------------------------------------------
            // --------------------------------------------------------------------------------------------
            // Route::get('/admin/manager-settings/manager-right', [ManagerRightController::class, 'index'])->name('admin.all-manager-with-right');
            Route::get('/admin/manager-settings/manager-right', [GroupPermissionController::class, 'index'])->name('admin.all-manager-with-right');
            Route::get('/admin/manager-settings/manager-des-right/{id}', [ManagerRightController::class, 'manager_des_right'])->name('admin.manager-assign-available-right');

            // manager analys
            // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            Route::get('/admin/manager-settings/manager-analysis', [ManagerAnalysisController::class, 'index'])->name('admin.manager-analysis-view');
    Route::post('/admin/manager-settings/manager-analysis-get-data', [ManagerAnalysisController::class, 'filter'])->name('admin.manager-analysis-get-data');

        // Manage All - Hierarchy & Permissions
        Route::prefix('admin/manager-settings')->group(function () {
            Route::get('/manage-all', [ManageAllController::class, 'index'])->name('admin.manage-all');
            Route::post('/manage-all/add-user', [ManageAllController::class, 'addUser'])->name('admin.manage-all.add-user');
            Route::post('/manage-all/remove-user', [ManageAllController::class, 'removeUser'])->name('admin.manage-all.remove-user');
            Route::post('/manage-all/assign-permissions', [ManageAllController::class, 'assignPermissions'])->name('admin.manage-all.assign-permissions');
            Route::get('/manage-all/permissions/{userId}', [ManageAllController::class, 'getAvailablePermissions'])->name('admin.manage-all.permissions');
            Route::get('/manage-all/available-users', [ManageAllController::class, 'getAvailableUsers'])->name('admin.manage-all.available-users');
            Route::get('/manage-all/available-levels', [ManageAllController::class, 'getAvailableHierarchyLevels'])->name('admin.manage-all.available-levels');
            Route::get('/manage-all/hierarchy-data', [ManageAllController::class, 'getHierarchyData'])->name('admin.manage-all.hierarchy-data');
            Route::get('/manage-all/get-assigned-country-managers', [ManageAllController::class, 'getAssignedCountryManagers'])->name('admin.manage-all.get-assigned-country-managers');
            Route::get('/manage-all/get-all-assigned-country-managers', [ManageAllController::class, 'getAllAssignedCountryManagers'])->name('admin.manage-all.get-all-assigned-country-managers');
            Route::get('/manage-all/get-assigned-managers', [ManageAllController::class, 'getAssignedManagers'])->name('admin.manage-all.get-assigned-managers');
            Route::get('/manage-all/get-all-assigned-managers', [ManageAllController::class, 'getAllAssignedManagers'])->name('admin.manage-all.get-all-assigned-managers');
            Route::post('/manage-all/assign-country-admins', [ManageAllController::class, 'assignCountryAdmins'])->name('admin.manage-all.assign-country-admins');
            Route::post('/manage-all/assign-managers', [ManageAllController::class, 'assignManagers'])->name('admin.manage-all.assign-managers');
            Route::get('/manage-all/get-assigned-traders', [ManageAllController::class, 'getAssignedTraders'])->name('admin.manage-all.get-assigned-traders');
            
            // Statistics and Revenue Routes
            Route::get('/manage-all/get-manager-stats', [ManageAllController::class, 'getManagerStats'])->name('admin.manage-all.get-manager-stats');
            Route::get('/manage-all/get-revenue-data', [ManageAllController::class, 'getRevenueData'])->name('admin.manage-all.get-revenue-data');
            Route::get('/manage-all/get-admin-manager-stats', [ManageAllController::class, 'getAdminManagerStats'])->name('admin.manage-all.get-admin-manager-stats');
            Route::get('/manage-all/get-country-manager-stats', [ManageAllController::class, 'getCountryManagerStats'])->name('admin.manage-all.get-country-manager-stats');

        });
            // END: Manaager settings

            // Start: IB Management
            //IB Management Report
            Route::get('/admin/ib-management/ib-verification-request', [IBVerificationController::class, 'ibVerificationRequest'])->name('admin.verification.request-report');
            Route::get('/admin/ib-management/ib-verification-request-description/{id}/{table_id}', [IBVerificationController::class, 'ibVerificationDescription'])->name('admin.verification.description');
            Route::get('/admin/ib-management/ib-verification-request-inner-description/{id}/{table_id}', [IBVerificationController::class, 'verificationInnerDescription'])->name('admin.verification.inner.description');
            Route::post('/admin/ib-management/ib-verification-approve-request/{id}/{table_id}', [IBVerificationController::class, 'IBVerificationApprove'])->name('admin.approve.verification');
            Route::post('/admin/ib-management/ib-verification-decline-request', [IBVerificationController::class, 'IBVerificationDecline'])->name('admin.decline.verification');
            Route::get('/admin/ib-management/ib-verification-proof/{id}', [IBVerificationController::class, 'IBVerificationProof'])->name('admin.proof.verification');

            // Start: Master IB Report
            Route::get('admin/ib_management/master_ib_report', [IbMasterController::class, 'masterIbReport'])->name('admin.ib_management.master_ib_report');
            Route::get('/admin/ib-management/master-ib-report/fetch-data', [IbMasterController::class, 'getMaterIbReport'])->name('admin.ib-management.master-ib-report.fetch-data');
            Route::get('/admin/ib-management/master-ib-report/description/fetch-data/{ib_id}', [IbMasterController::class, 'getMaterIbReportDescription'])->name('admin.ib-management.master-ib-report.description.fetch-data');
            Route::get('/admin/ib-management/master-ib-report/description/inner-datatable/fetch-data/{ib_id}', [IbMasterController::class, 'getMaterIbReportDescriptionInner'])->name('admin.ib-management.master-ib-report.description.inner-datatable.fetch-data');
            // End: Master IB Report

            // Start: IB Pending Commission  Report
            Route::get('/admin/ib_management/pending_commission_list', [IbPendingCommissionController::class, 'pendingCommissionList'])->name('admin.ib_management.pending_commission_list');
            Route::get('/admin/ib_management/pending_commission_list/fetch-data', [IbPendingCommissionController::class, 'getPendingCommissionList'])->name('admin.ib_management.pending_commission_list.fetch-data');
            // End:  IB Pending Commission Report

            //Start : No Commission Report
            Route::get('/admin/ib_management/no_commission_list', [IbNoCommissionController::class, 'noCommissionList'])->name('admin.ib_management.no_commission_list');
            Route::get('/admin/ib_management/no_commission_list/fetch-data', [IbNoCommissionController::class, 'getNoCommissionList'])->name('admin.ib_management.no_commission_list.fetch-data');
            //End: No commission Report

            // Start: IB Bank Account List
            Route::get('/admin/manage_banks/bank_account_list', [BankAccountListController::class, 'bankAccountList'])->name('admin.manage_banks.bank_account_list');
            Route::get('/admin/manage_banks/bank_account_list/fetch_data', [BankAccountListController::class, 'getBankAccountList'])->name('admin.manage_banks.bank_account_list.fetch_data');
            Route::get('/admin/manage_banks/bank_account_list/description/fetch_data/{id}', [BankAccountListController::class, 'bankAccountListDescription'])->name('admin.manage_banks.bank_account_list.description.fetch_data');
            Route::post('/admin/manage_banks/bank_account_list/delete/{id}', [BankAccountListController::class, 'bankAccountListDelete'])->name('admin.manage_banks.bank_account_list.delete');
            Route::post('/admin/manage_banks/bank_account_request/update', [BankAccountListController::class, 'bankAccountRequest'])->name('admin.manage_banks.bank_account_request.update');
            Route::get('/admin/manage_banks/bank_account/edit_modal/fetch_data/{id}', [BankAccountListController::class, 'bankAccountEditModalFetchData'])->name('admin.manage_banks.bank_account.edit_modal.fetch_data');
            Route::post('/admin/manage_banks/bank_account/edit_modal/update', [BankAccountListController::class, 'bankAccountEditModalUpdate'])->name('admin.manage_banks.bank_account.edit_modal.update');
            // End:  IB Bank Account List

            // Start: IB Pending Commission  Report
            Route::any('/admin/ib-management/ib-chain', [IbChainController::class, 'ibChain'])->name('admin.ib_management.ib_chain');
            // Route::post('/admin/ib_management/ib_chain/fetch_data', [IbChainController::class, 'ibChainFetchData'])->name('admin.ib_management.ib_chain.fetch_data');
            // End:  IB Pending Commission Report

            // START: IB Admin Report
            Route::get('/admin/ib/dashboard/{id}', [IBadminController::class, 'goto_ib_dashboard'])->name('admin.ib.dashboard');
            Route::get('/admin/ib-management/ib-admin-report', [IBadminController::class, 'ibAdminReport'])->name('ib.admin.report');
            Route::get('/admin/ib-management/ib-admin-report-process', [IBadminController::class, 'ibAdminReportProcess'])->name('ib.admin.report.process');
            Route::get('/admin/ib-management/ib-admin-report-description/{ib_id}', [IBadminController::class, 'ibAdminReportDescription'])->name('ib.admin.report-description');
            Route::get('/admin/ib-management/ib-admin-report-description-inner-trader-list/{ib_id}', [IBadminController::class, 'ibAdminReportDescriptionInner'])->name('ib.admin.report-description-inner.trader-list');
            Route::get('/admin/ib-management/ib-admin-report-description-inner-trading-account/{ib_id}', [IBadminController::class, 'ibAdminReportDescriptionInnerTradingAccount'])->name('ib.admin.report-description-inner.trading-account');
            Route::get('/admin/ib-management/ib-admin-report-description-inner-sub-ib/{ib_id}', [IBadminController::class, 'ibAdminReportDescriptionInnerSubIB'])->name('ib.admin.report-description-inner.sub-ib');
            Route::get('/admin/ib-management/ib-admin-report-description-inner-trading-deposit/{ib_id}', [IBadminController::class, 'ibAdminReportDescriptionInnerTradingDeposit'])->name('ib.admin.report-description-inner.trading-deposit');
            Route::get('/admin/ib-management/ib-admin-report-description-inner-trading-withdraw/{ib_id}', [IBadminController::class, 'ibAdminReportDescriptionInnerTradingWithdraw'])->name('ib.admin.report-description-inner.trading-withdraw');
            Route::get('/admin/ib-management/ib-admin-report-description-inner-self-withdraw/{ib_id}', [IBadminController::class, 'ibAdminReportDescriptionInnerSelfWithdraw'])->name('ib.admin.report-description-inner.self-withdraw');
            Route::get('/admin/ib-management/ib-admin-report-description-inner-ib-balance-add/{ib_id}', [IBadminController::class, 'ib_balance_add'])->name('ib.admin.ib-balance-add-report');
            Route::get('/admin/ib-management/ib-admin-report-description-inner-ib-commission/{ib_id}', [IBadminController::class, 'ibAdminReportDescriptionInnerIBcommission'])->name('ib.admin.report-description-inner.ib-commission');
            Route::get('/admin/ib-management/ib-admin-dt-kyc-fetch-data/{ib_id}', [IBadminController::class, 'ibAdminReportDescriptionInnerKycFetchData'])->name('ib.admin.report-description-inner.ib-admin-dt-kyc-fetch-data');
            Route::get('/admin/ib-management/ib-admin-dt-comment-fetch-data/{ib_id}', [IBadminController::class, 'ibAdminReportDescriptionInnerCommentFetchData'])->name('ib.admin.report-description-inner.ib-admin-dt-comment-fetch-data');
            Route::post('/admin/ib-management/ib-admin-dt-comment-post-data', [IBadminController::class, 'ibAdminInnerCommentAdd'])->name('ib.admin.report-description-inner.ib-admin-dt-comment-post-data');
            Route::post('/admin/ib-management/ib-admin-update-comment', [IBadminController::class, 'ibAdminReportDescriptionInnerCommentUpdateData'])->name('ib.admin.report-description-inner.ib-admin-update-comment');
            Route::post('/admin/ib-management/ib-admin-delete-comment', [IBadminController::class, 'ibAdminReportDescriptionInnerCommentDeleteData'])->name('ib.admin.report-description-inner.ib-admin-delete-comment');
            Route::post('/admin/ib-management/admin-block-ib', [IBadminController::class, 'adminBlockedIB'])->name('ib.admin.report-description-inner.admin-block-ib');
            Route::post('/admin/ib-management/ib-admin-google-two-step-auth', [IBadminController::class, 'googleTwoStepAuth'])->name('ib.admin.report-description-inner.ib-admin-google-two-step-auth');
            Route::post('/admin/ib-management/ib-admin-email-auth', [IBadminController::class, 'emailAuth'])->name('ib.admin.report-description-inner.ib-admin-email-auth');
            Route::post('/admin/ib-management/ib-admin-email-verification', [IBadminController::class, 'emailVerification'])->name('ib.admin.report-description-inner.ib-admin-email-verification');
            Route::post('/admin/ib-management/ib-admin-deposit-operation', [IBadminController::class, 'ibDepositOperation'])->name('ib.admin.report-description-inner.ib-admin-deposit-operation');
            Route::post('/admin/ib-management/ib-admin-internal-transfer', [IBadminController::class, 'ibInternalTransferOperation'])->name('ib.admin.report-description-inner.ib-admin-internal-transfer');
            Route::post('/admin/ib-management/ib-admin-withdraw-operation', [IBadminController::class, 'ibWithdrawOperation'])->name('ib.admin.report-description-inner.ib-admin-withdraw-operation');
            Route::post('/admin/ib-management/ib-admin-set-category', [IBadminController::class, 'setIBcategory'])->name('ib.admin.report-description-inner.ib-admin-set-category');
            Route::post('/admin/ib-management/ib-admin-change-password', [IBadminController::class, 'ibAdminChangePassword'])->name('ib-admin-change-password');
            Route::post('/admin/ib-management/ib-admin-change-password-mail', [IBadminController::class, 'ibAdminChangePasswordMail'])->name('ib-admin-change-password-mail');
            Route::post('/admin/ib-management/ib-admin-change-transaction-password', [IBadminController::class, 'ibAdminChangeTransactionPass'])->name('ib-admin-change-transaction-password');
            Route::post('/admin/ib-management/ib-admin-change-transaction-password-mail', [IBadminController::class, 'ibAdminChangeTransactionPassMail'])->name('ib-admin-change-transaction-password-mail');

            Route::get('/admin/ib-management/welcome-mail/{ib_id}', [IBadminController::class, 'ibAdminWelcomeMail'])->name('admin.ib-management.welcome-mail');

            // transfer operation enabled / disabled
            Route::post('/admin/ib-management/wta-transfer', [IBadminController::class, 'wtaTransferOperation'])->name('ib.admin.report-description-inner.wta-transfer');
            Route::post('/admin/ib-management/ib-to-ib-transfer', [IBadminController::class, 'ibToIbTransferOperation'])->name('ib.admin.report-description-inner.ib-to-ib-transfer');
            Route::post('/admin/ib-management/ib-to-trader-transfer', [IBadminController::class, 'ibToTraderTransferOperation'])->name('ib.admin.report-description-inner.ib-to-trader-transfer');
            Route::post('/admin/ib-management/trader-to-trader-transfer', [IBadminController::class, 'traderToTraderTransferOperation'])->name('ib.admin.report-description-inner.trader-to-trader-transfer');
            Route::post('/admin/ib-management/kyc-verify', [IBadminController::class, 'kycVerifyOperation'])->name('ib.admin.report-description-inner.kyc-verify');

            Route::post('/admin/ib-management/ib-admin-reset-password', [IBadminController::class, 'ibAdminResetPassword'])->name('ib-admin-reset-password');
            Route::post('/admin/ib-management/ib-admin-reset-transaction-password', [IBadminController::class, 'ibAdminResetTransactionPassword'])->name('ib-admin-reset-transaction-password');

            // update profile
            Route::post('/admin/ib-management/update-profile-getdata', [IBadminController::class, 'ibAdminProfileUpdateGetdata'])->name('ib-admin.ib-update-profile-getdata');
            Route::post('/admin/ib-management/update-profile', [IBadminController::class, 'ibAdminProfileUpdate'])->name('ib-admin.ib-update-profile');
            // ib profile account details update
            Route::post('/admin/ib-management/ib/profile-update/acc-details', [IbProfileUpdateController::class, 'update_account_details'])->name('ibadmin.ib-profile-update.account-details');
            Route::post('/admin/ib-management/ib/profile-update/personal-info', [IbProfileUpdateController::class, 'update_personal_info'])->name('ibadmin.ib-profile-update.personal-info');
            Route::post('/admin/ib-management/ib/profile-update/social-info', [IbProfileUpdateController::class, 'update_social_details'])->name('ibadmin.ib-profile-update.social-info');

            Route::post('/admin/ib-management/change-status', [IBadminController::class, 'ibAdminChangeStatus'])->name('ib-admin.change-status');
            Route::post('/admin/ib-management/ib/block', [IbBlockController::class, 'ib_block'])->name('ib-admin.ib.ib-block');
            Route::post('/admin/ib-management/ib/unblock', [IbUnblockController::class, 'ib_unblock'])->name('ib-admin.ib.ib-unblock');

            Route::post('admin/ib-management/delete-sub-ib', [IBadminController::class, 'subIBDelete']);
            Route::post('admin/ib-management/delete-trader', [IBadminController::class, 'traderDelete']);

            Route::post('/admin/ib-management/added-trader-sub-ib', [TraderSubIBAddedController::class, 'AddedTraderSubIB'])->name('trader-sub-ib-update');
            Route::post('/admin/ib-management/show-sub-ib-email', [TraderSubIBAddedController::class, 'RemoveIBEmail'])->name('show.ib.email');
            Route::post('/admin/ib-management/show-trader-email', [TraderSubIBAddedController::class, 'showTradaerEmail'])->name('show-trader-email');
            // ib request for combine crm
            Route::get('/admin/ib-management/ib-request', [CombineIbRequestController::class, 'index'])->name('admin.combine-ib-request');
            Route::any('/admin/ib-management/ib-request/datatable', [CombineIbRequestController::class, 'ib_request'])->name('admin.combine-ib-request.dt');
            Route::any('/admin/ib-management/ib-request/approve', [CombineIbRequestController::class, 'approve'])->name('admin.combine-ib-request.approve');
            Route::any('/admin/ib-management/ib-request/decline', [CombineIbRequestController::class, 'decline'])->name('admin.combine-ib-request.decline');

            // END: IB Admin

            // lead Management
            Route::get('/admin/lead-management', [LeadManagementController::class, 'getLeadManagement'])->name('admin.lead-management');
            Route::post('/admin/add_new_lead', [LeadManagementController::class, 'addNewLead'])->name('admin.add_new_lead');
            Route::post('/admin/lead-management/update', [LeadManagementController::class, 'updateLeadManagement'])->name('admin.lead-management.update');
            Route::post('/admin/lead-management/delete', [LeadManagementController::class, 'deleteLeadManagement'])->name('admin.lead-management.delete');
            Route::post('/admin/lead-management/addTask', [LeadManagementController::class, 'postAddTask'])->name('admin.lead-management.addTask');
            Route::post('/admin/lead-management/updateTask', [LeadManagementController::class, 'updateTask'])->name('admin.lead-management.updateTask');
            Route::post('/admin/lead-management/delete/task', [LeadManagementController::class, 'deletTask'])->name('admin.lead-management.deletetask');
            Route::post('/admin/lead-management/add/comment', [LeadManagementController::class, 'addComment'])->name('admin.lead-management.addComment');
            Route::post('/admin/lead-management/update/comment', [LeadManagementController::class, 'updateComment'])->name('admin.lead-management.updateComment');
            Route::post('/admin/lead-management/delete/comment', [LeadManagementController::class, 'deleteComment'])->name('admin.lead-management.deleteComment');
            Route::post('/admin/lead-management/addactions', [LeadManagementController::class, 'addactions'])->name('admin.lead-management.addactions');
            Route::post('/admin/lead-management/addmanager', [LeadManagementController::class, 'addmanager'])->name('admin.lead-management.addmanager');
            Route::post('/admin/lead-management/deskmanager', [LeadManagementController::class, 'deskmanager'])->name('admin.lead-management.deskmanager');
            Route::post('/admin/lead-management/sendmail', [LeadManagementController::class, 'sendmail'])->name('admin.lead-management.sendmail');
            Route::post('/admin/lead-management/taskcomplete', [LeadManagementController::class, 'taskcomplete'])->name('admin.lead-management.taskcomplete');
            Route::post('/admin/lead-management/convertToAccount', [LeadManagementController::class, 'convertToAccount'])->name('admin.lead-management.convertToAccount');
            // Route::post('/admin/lead-management/actionView', [LeadManagementController::class, 'actionView'])->name('admin.lead-management.actionView');

            // Admin bank setup
            Route::get('/admin/settings/bank-account-setup', [BankAccountSetupController::class, 'BankAccountSetup'])->name('admin.bank-account-setup');
            Route::any('/admin/settings/add-bank-account-setup', [BankAccountSetupController::class, 'AddBankAccountSetup'])->name('admin.add-bank-account-setup');
            Route::get('/admin/settings/bank-account-setup-report', [BankAccountSetupController::class, 'BankAccountSetupReport'])->name('admin.bank-account-setup-report');
            Route::post('/admin/settings/bank-account-delete', [BankAccountSetupController::class, 'DeleteBankAccount'])->name('admin.bank-account-delete');
            Route::post('/admin/settings/add-or-remove-tab/{action}/{tab_selection}', [BankAccountSetupController::class, 'addOrRemoveBankTab'])->name('admin.settings.add-or-remove-tab');

            // Admin bank setup
            Route::get('/admin/settings/popup-setup', [DashboardPopupImageController::class, 'popupSetup'])->name('admin.settings.popup-setup');
            Route::get('/admin/settings/popup-setup-fetch-data', [DashboardPopupImageController::class, 'popupSetupFetchData'])->name('admin.settings.popup-setup-fetch-data');
            Route::post('/admin/settings/popup-upload', [DashboardPopupImageController::class, 'popupUpload'])->name('admin.settings.popup-upload');
            Route::post('/admin/settings/popup-update', [DashboardPopupImageController::class, 'popupUpdate'])->name('admin.settings.popup-update');

            // payment gatways
            Route::get('/admin/settings/payment-gateways', [PaymentGateWaySettingsController::class, 'index'])->name('admin.settings.paymentgateway');
            Route::post('/admin/settings/payment-gateways/help2pay', [PaymentGateWaySettingsController::class, 'help2pay'])->name('admin.settings.paymentgateway.help2pay');
            Route::post('/admin/settings/payment-gateways/b2binpay', [PaymentGateWaySettingsController::class, 'b2binpay'])->name('admin.settings.paymentgateway.b2binpay');
            Route::post('/admin/settings/payment-gateways/paypal', [PaymentGateWaySettingsController::class, 'paypal'])->name('admin.settings.paymentgateway.paypal');
            Route::post('/admin/settings/payment-gateways/praxis', [PaymentGateWaySettingsController::class, 'praxis'])->name('admin.settings.paymentgateway.praxis');
            Route::post('/admin/settings/payment-gateways/nowpay', [PaymentGateWaySettingsController::class, 'nowpay'])->name('admin.settings.paymentgateway.now-pay');
            // copy symbol
            Route::get('/admin/settings/add-copy-symbol', [CopySymbolController::class, 'copy_symbol'])->name('admin.add-copy-symbol');
            Route::post('/system/pamm-setting-process', [SystemPammController::class, 'pammSettingProcess'])->name('system.pamm.process');
            Route::post('/system/add-symbol-process', [SystemPammController::class, 'addSymbol'])->name('system.add.symbol');
            Route::get('/system/add-symbol-table-process', [SystemPammController::class, 'SymbolTable'])->name('system.symbol.table');

            // START: IB setup
            // ----------------------------------------------------------------------------------------------------------------------------
            Route::get('/admin/ib-management/ib-setup', [IBsetupController::class, 'index'])->name('admin.ib-setup-view');
            Route::post('/admin/ib-management/ib-setup-save', [IBsetupController::class, 'store'])->name('admin.ib-setup');

            // START: IB Commission structure
            // ----------------------------------------------------------------------------------------------------------------------------
            Route::get('/admin/ib-management/ib-commission-structure', [IBcommisionStructureController::class, 'index'])->name('admin.ib-commission-structure');
            Route::post('/admin/ib-management/ib-commission-structure', [IBcommisionStructureController::class, 'store'])->name('admin.ib-commission-structure');
            Route::post('/admin/ib-management/ib-commission-structure/delete', [IBcommisionStructureController::class, 'delete_group_wise'])->name('admin.ib-commission-structure.delete.groups');
            Route::post('/admin/ib-management/ib-commission-structure-delete', [IBcommisionStructureController::class, 'ib_commission_structure_delete'])->name('admin.ib-commission-structure-delete');
            Route::post('/admin/ib-management/ib-commission-structure-block-unblock', [IBcommisionStructureController::class, 'ib_commission_enable_disable'])->name('admin.ib-commission-structure-enable-disable');
            Route::get('/admin/ib-management/ib-commission-structure-dt', [IBcommisionStructureController::class, 'datatable_data'])->name('admin.ib-commission-structure-dt');
            Route::get('/admin/ib-management/ib-commission-structure-dt/description', [IBcommisionStructureController::class, 'description'])->name('admin.ib-commission-structure-dt-des');
            Route::post('/admin/ib-management/csv-import', [IBcommisionStructureController::class, 'importCsv'])->name('admin.ib.csv-import');
            Route::post('/admin/ib-management/custom-commission', [CustomCommissionController::class, 'custom_commission'])->name('admin.ib.custom-commission');
            //START: IB Commission replace
            // Route::get('/admin/ib-management/ib-commission-structure-replace', [IbcommisionStructureReplace::class, 'index'])->name('admin.ib-commission-structure-replace');
            // START: IB Tree
            // ------------------------------------------------------------------------------------------------------
            Route::get('/admin/ib-management/ib-tree', [IbTreeController::class, 'index'])->name('admin.ib-tree');
            Route::post('/admin/ib-management/ib-tree-create', [IbTreeController::class, 'create'])->name('admin.ib-tree-create');

            // START: IB analysis-----------------------------------------------------------------------------------------
            Route::get('/admin/ib-management/ib-analysis', [IbAnalysisController::class, 'ib_analysis'])->name('admin.ib-analysis');
            Route::post('/admin/ib-management/ib-analysis-get-data', [IbAnalysisController::class, 'filter'])->name('admin.ib-analysis-get-data');

            // END: IB Analysis-----------------------------------------------------------------------------------

            // Finance
            // START: Balance Management
            // ----------------------------------------------------------------------------------------------------
            Route::get('/admin/finance/balance-management', [FinanceBalanceController::class, 'index'])->name('admin.finance-balance');
            Route::get('/admin/finance/get-client/{user_type}', [FinanceBalanceController::class, 'get_client'])->name('admin.finance-get-client');
            Route::get('/admin/finance/get-client-finance', [FinanceBalanceController::class, 'get_finance'])->name('admin.finance-get-client-balance');
            Route::post('/admin/finance/balance-management-store', [FinanceBalanceController::class, 'store'])->name('admin.finance-balance-store');
            Route::post('/admin/finance/balance-management/add', [FinanceBalanceController::class, 'add_balance'])->name('admin.finance-balance.add');
            Route::post('/admin/finance/balance-management/deduct', [FinanceBalanceController::class, 'deduct_balance'])->name('admin.finance-balance.deduct');
            Route::get('/admin/finance/balance-management-bank/{id}', [FinanceBalanceController::class, 'banks'])->name('admin.finance-balance-bank');

            // get balance for ffianance balance management  add
            Route::get('/admin/client/ib/finance-status', [FinanceBalanceController::class, 'finance_status'])->name('admin.client-finance-status');
            Route::post('/admin/finance/balance-management/mail/add-balance', [FinanceBalanceController::class, 'mail_add_balance'])->name('admin.finance-balance.mail-add-balance');
            Route::post('/admin/finance/balance-management/mail/deduct-balance', [FinanceBalanceController::class, 'mail_withdraw_balance'])->name('admin.finance-balance.mail-withdraw-balance');
            // Deposit settings
            Route::post('/admin/finance/deposit-settings', [DepositSettingsController::class, 'manage_deposit_settings'])->name('admin.finance.deposit-settings');
            //Trader Withdraw Settings
            Route::post('/admin/finance/trader/withdraw-settings', [TraderWithdrawSettingsController::class, 'manage_withdraw_settings'])->name('admin.finance.trader.withdraw-settings');
            // credit managment
            // -----------------------------------------------------------------------------------
            Route::get('/admin/finance/credit-management', [AddCreditController::class, 'index'])->name('admin.finance-credit');

            Route::get('/admin/finance/credit-get-client', [AddCreditController::class, 'client'])->name('admin.finance-credit-client');
            Route::get('/admin/finance/credit-get-trading-account/{client_id}', [AddCreditController::class, 'trading_account'])->name('admin.finance-credit-trading-account');
            Route::post('/admin/finance/credit-store', [AddCreditController::class, 'store'])->name('admin.finance-credit-store');
            // credit add / deduct
            Route::post('/admin/finance/credit/add', [AddCreditController::class, 'credit_add'])->name('admin.finance-credit.add');
            Route::post('/admin/finance/credit/deduct', [AddCreditController::class, 'credit_deduct'])->name('admin.finance-credit.deduct');
            Route::post('/admin/finance/credit/mail/add-credit', [AddCreditController::class, 'credit_add_mail'])->name('admin.finance-credit.add-credit-mail');
            Route::post('/admin/finance/credit/mail/deduct-credit', [AddCreditController::class, 'credit_deduct_mail'])->name('admin.finance-credit.deduct-credit-mail');

            Route::get('/admin/finance/send-add-credit-mail/{account_id}/request/{credit_id}', [AddCreditController::class, 'add_credit_mail'])->name('admin.finance-add-credit-mail');

            // Finance: Fund management
            // --------------------------------------------------------------------------------------------------------------
            Route::get('/admin/finance/fund-management', [FundManageController::class, 'index'])->name('admin.finance-fund-management');
            Route::post('/admin/finance/fund-management/fund-deposit', [FundManageController::class, 'deposit'])->name('admin.finance-fund-management.deposit');
            Route::post('/admin/finance/fund-management/fund-withdraw', [FundManageController::class, 'withdraw'])->name('admin.finance-fund-management.withdraw');
            Route::post('/admin/finance/fund-management-store', [FundManageController::class, 'store'])->name('admin.finance-fund-management-store');
            Route::get('/admin/finance/fund-management-email/{account_id}/credit/{credit_id}/transaction/{type}', [FundManageController::class, 'add_credit_mail'])->name('admin.finance-fund-management-email');
            Route::get('/admin/finance/fund-management-email/{user_id}/fund/transaction/{type}', [FundManageController::class, 'fund_mail'])->name('admin.finance-fund-deposit-withdraw-mail');
            Route::get('/admin/finance/fund-management-get-client', [FundManageController::class, 'get_client'])->name('admin.finance-fund-get-client');

            // finance report-----------------------------------------------
            Route::get('/admin/finance/finance-report', [FinanceReportController::class, 'finance_report'])->name('admin.finance-report');
            Route::get('/admin/finance/deposit-report', [AdminDepositReportController::class, 'deposit_report'])->name('admin.deposit-report');
            Route::get('/admin/finance/deposit-report-dt', [AdminDepositReportController::class, 'deposit_report_dt'])->name('admin.deposit-report-dt');
            Route::get('/admin/finance/deposit-report-dt/description/{id}', [AdminDepositReportController::class, 'trader_deposit_description'])->name('admin.deposit-report-dt-des');
            Route::get('/admin/finance/withdraw-report', [AdminWithdrawReportController::class, 'withdraw_report'])->name('admin.withdraw-report');
            Route::get('/admin/finance/withdraw-report-dt', [AdminWithdrawReportController::class, 'withdraw_report_dt'])->name('admin.withdraw-report-dt');
            Route::get('/admin/finance/withdraw-report-dt/description/{id}', [AdminWithdrawReportController::class, 'withdraw_description'])->name('admin.withdraw-report-dt-desc');
            Route::get('/admin/finance/finance-report-dt', [FinanceReportController::class, 'finance_report_dt'])->name('admin.finance-report-dt');
            Route::post('/admin/finance/finance-change-st', [FinanceReportController::class, 'changeStatus'])->name('admin.finance-report-change-status');
            //add-deduct log route
            Route::get('/admin/finance/finance-report/add-deduct/{id}', [FinanceReportController::class, 'AddDeductLog'])->name('admin.finance.add-deduct');
            // end: finance report----------------------------

            // Start: Settings
            // -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            Route::get('/admin/settings/currency-pair', [SetttingController::class, 'currencyPair'])->name('admin.settings.currency-pair');
            Route::get('/admin/settings/currency-pair-get', [SetttingController::class, 'currencyPairGetData'])->name('admin.settings.currency-pair-get');
            Route::post('/admin/settings/currency-pair-add', [SetttingController::class, 'currencyPairAdd'])->name('admin.settings.currency-pair-add');
            Route::post('/admin/settings/currency-pair-delete/{id}', [SetttingController::class, 'currencyPairDelete'])->name('admin.settings.currency-pair-delete');
            Route::post('/admin/settings/currency-pair/modal-fetch-data/{id}', [SetttingController::class, 'currencyPairEditModalFetchData'])->name('admin.setting.currency-pair.modal-fetch-data');
            Route::post('/admin/settings/currency-pair/edit', [SetttingController::class, 'currencyPairEdit'])->name('admin.setting.currency-pair.edit');
            Route::get('/system/add-symbol-table-process', [SystemPammController::class, 'SymbolTable'])->name('system.symbol.table');
            Route::post('/system/add-symbol-process', [SystemPammController::class, 'addSymbol'])->name('system.add.symbol');
            // banner setup
            Route::get('/admin/settings/banner-setup', [BannerControllerAdmin::class, 'index'])->name('admin.banner-setup');
            Route::post('/admin/settings/banner-upload', [BannerControllerAdmin::class, 'upload'])->name('admin.banner-upload');
            Route::post('/admin/settings/banner-enable-disable', [BannerControllerAdmin::class, 'enable_disable'])->name('admin.banner-enable-disable');
            Route::post('/admin/settings/banner-delete', [BannerControllerAdmin::class, 'delete_banner'])->name('admin.banner-delete');

            // datatable banners
            Route::get('/admin/settings/banner-datatable/{size}/{use_for}', [BannerControllerAdmin::class, 'dt_banner'])->name('admin.banner-datatable');
            // announcement
            Route::get('/admin/settings/announcement', [AnnouncementController::class, 'announcement'])->name('admin.settings.announcement');
            Route::get('/admin/settings/announcement/fetch_data', [AnnouncementController::class, 'announcementFetchData'])->name('admin.settings.announcement.fetch_data');
            Route::post('/admin/settings/announcement/add', [AnnouncementController::class, 'announcementAdd'])->name('admin.settings.announcement.add');
            Route::get('/admin/settings/announcement/get_data_by_id/{id}', [AnnouncementController::class, 'announcementGetData'])->name('admin.settings.announcement.get_data_by_id');
            Route::post('/admin/settings/announcement/update', [AnnouncementController::class, 'announcementUpdate'])->name('admin.settings.announcement.update');
            Route::post('/admin/settings/announcement/delete', [AnnouncementController::class, 'announcementDelete'])->name('admin.settings.announcement.delete');

            // notification settings
            Route::get('/admin/settings/notification_setting', [NotificationController::class, 'allNotification'])->name('admin.settings.notification_setting');
            Route::post('/admin/settings/notification_setting/add', [NotificationController::class, 'notificationAdd'])->name('admin.settings.notification_setting.add');
            // Route::post('/admin/settings/notification_setting/update', [NotificationController::class, 'notificationUpdate'])->name('admin.settings.notification_setting.update');
            Route::get('/admin/allNotification/allNotification', [NotificationController::class, 'allNotification'])->name('admin.allNotification.allNotification');
            Route::post('/admin/allNotification/allNotification/setting/filter', [NotificationController::class, 'filter_admin'])->name('admin.allNotification.allNotification.filter');
            Route::post('/admin/allNotification/allNotification/setting/submit', [NotificationController::class, 'save_notification'])->name('admin.allNotification.allNotification.save_notification');
            // view all notifications
            Route::get('/admin/manage-report/view-all-notification', [SystemNotificationController::class, 'view_all'])->name('admin.system-notification.view-all');
            Route::get('/admin/manage-report/view-all-notification/fetch-data', [SystemNotificationController::class, 'fetch_data'])->name('admin.fetch-data-notification.view-all');
            // Route::post('/admin/notification/tour', [NotificationController::class, 'notificationTour'])->name('admin.notification.tour');
            // trader settings
            Route::get('/admin/settings/trader_setting', [TraderSettingController::class, 'traderSetting'])->name('admin.settings.trader_setting');
            Route::post('/admin/settings/trader_setting/add', [TraderSettingController::class, 'traderSettingAdd'])->name('admin.settings.trader_setting.add');
            Route::post('/admin/settings/trader_setting/update', [TraderSettingController::class, 'traderSettingUpdate'])->name('admin.settings.trader_setting.update');
            Route::any('/admin/settings/trader_setting/create-all', [TraderSettingController::class, 'create_all'])->name('admin.settings.trader_setting.create-all');
            Route::any('/admin/settings/trader_setting/create-all-dt', [TraderSettingController::class, 'trader_settings_dt'])->name('admin.settings.trader_setting.dt');
            // kyc required for trader
            Route::post('/admin/settings/kyc-required/for', [KycRequiredController::class, 'setup_remquired'])->name('admin.settings.kyc-required');
            // security settings
            Route::get('/admin/settings/security_setting', [SecuritySettingController::class, 'securitySetting'])->name('admin.settings.security_setting');
            Route::post('/admin/settings/security_setting/update/{check_auth}', [SecuritySettingController::class, 'securitySettingUpdate'])->name('admin.settings.security_setting.update');
            Route::post('/admin/settings/security_setting/google_auth_set', [SecuritySettingController::class, 'googleAuthenticationSet'])->name('admin.settings.security_setting.google_auth_set');
            Route::post('/admin/settings/kyc-back-part/{check_value}', [SecuritySettingController::class, 'kycBackPartSetting'])->name('admin.settings.kyc-back-part');

            // ib settings
            Route::get('/admin/settings/ib_setting', [IbSettingController::class, 'ibSetting'])->name('admin.settings.ib_setting');
            Route::get('/admin/settings/ib-setting/create-all', [IbSettingController::class, 'create_all'])->name('admin.settings.ib_setting.create_all');
            Route::get('/admin/settings/ib-setting/create-all-dt', [IbSettingController::class, 'ib_settings_dt'])->name('admin.settings.ib_setting.create_all');
            Route::post('/admin/settings/ib_setting/add', [IbSettingController::class, 'ibSettingAdd'])->name('admin.settings.ib_setting.add');
            Route::post('/admin/settings/ib_setting/update', [IbSettingController::class, 'ibSettingUpdate'])->name('admin.settings.ib_setting.update');

            Route::post('/admin/ib-management/added-trader-sub-ib', [TraderSubIBAddedController::class, 'AddedTraderSubIB'])->name('trader-sub-ib-update');
            Route::post('/admin/ib-management/added-sub-ib', [TraderSubIBAddedController::class, 'AddSubIB'])->name('sub-ib-added');
            Route::post('/admin/ib-management/show-sub-ib-email', [TraderSubIBAddedController::class, 'RemoveIBEmail'])->name('show.ib.email');




            // api configurations
            Route::get('/admin/settings/api_configuration', [ApiConfigurationController::class, 'apiConfiguration'])->name('admin.settings.api_configuration');
            Route::post('/admin/settings/api_configuration', [ApiConfigurationController::class, 'apiConfigurationAdd'])->name('admin.settings.api_configuration');
            //Live Api
            Route::post('admin/settings/api_configuration/mt4_live', [ApiConfigurationController::class, 'liveApiStore'])->name('admin.settings.api_configuration.mt4_live');

            // smtp setup
            Route::get('/admin/settings/smtp_setup', [SmtpSetupController::class, 'smtpSetup'])->name('admin.settings.smtp_setup');
            Route::post('/admin/settings/smtp_setup', [SmtpSetupController::class, 'smtpSetupAdd'])->name('admin.settings.smtp_setup');
            // company setup
            Route::get('/admin/settings/company_setup', [CompanySetupController::class, 'companySetup'])->name('admin.settings.company_setup');
            Route::post('/admin/settings/company_setup_add', [CompanySetupController::class, 'companySetupAdd'])->name('admin.settings.company_setup_add');
            // finance settings
            Route::get('/admin/settings/finance_setting', [FinanceSettingController::class, 'financeSetting'])->name('admin.settings.finance_setting');
            Route::get('/admin/settings/finance_setting/fetch_data', [FinanceSettingController::class, 'financeSettingFetchData'])->name('admin.settings.finance_setting.fetch_data');
            Route::post('/admin/settings/finance-settings/add', [FinanceSettingController::class, 'financeSettingAdd'])->name('admin.settings.finance_setting.add');
            // Route::get('/admin/settings/finance-settings/edit_modal/fetch_data/{id}', [FinanceSettingController::class, 'transactionSettingEditModalFetchData'])->name('admin.settings.finance_setting.edit_modal.fetch_data');
            // Route::post('/admin/settings/finance-settings/edit', [FinanceSettingController::class, 'financeSettingEdit'])->name('admin.settings.finance_setting.edit');
            Route::post('/admin/settings/finance-settings/delete/{id}', [FinanceSettingController::class, 'financeSettingDelete'])->name('admin.settings.finance_setting.delete');
            Route::post('/admin/settings/finance-settings/change-active-status/{id}/{value}', [FinanceSettingController::class, 'financeSettingChangeActiveStatus'])->name('admin.settings.finance-setting.change-active-status');
            // software settings
            Route::get('/admin/settings/software_setting', [SoftwareSettingController::class, 'softwareSetting'])->name('admin.settings.software_setting');
            Route::post('/admin/settings/software_setting', [SoftwareSettingController::class, 'softwareSettingAdd'])->name('admin.settings.software_setting');
            //crypto deposit settings
            Route::get('/admin/settings/crypto_deposit_settings', [CryptoDepositSettings::class, 'CryptoDeposit'])->name('admin.settings.crypto_deposit_settings');
            Route::get('/admin/settings/get_crypto_address/{block_chain}', [CryptoDepositSettings::class, 'CryptoAddress'])->name('admin.settings.crypto_address');
            Route::post('/admin/settings/add_crypto_address', [CryptoDepositSettings::class, 'AddCryptoAddress'])->name('admin.settings.add_crypto_address');

            // kyc settings
            Route::get('/admin/settings/kyc-setting', [KycSettingController::class, 'kycSetting'])->name('admin.settings.kyc-setting');
            // currency setup
            Route::get('/admin/settings/currency-setup', [CurrencySetupController::class, 'index'])->name('admin.settings.currency-setup');
            Route::post('/admin/settings/currency-setup-get', [CurrencySetupController::class, 'getCurrencySetup'])->name('admin.settings.currency-setup-get');
            Route::post('/admin/settings/currency-setup-store', [CurrencySetupController::class, 'store'])->name('admin.settings.currency-setup-store');
            Route::post('/admin/settings/multi-currency-setup/{is_multicurrency}', [CurrencySetupController::class, 'multiCurrency'])->name('admin.settings.multi-currency-setup');
            Route::post('/admin/settings/auto-currency-rate/{auto_c_rate}', [CurrencySetupController::class, 'autoCurrencyRate'])->name('admin.settings.auto-currency-rate');

            // notification settings
            Route::get('/admin/settings/notification-templates', [NotificationSettingsController::class, 'index'])->name('admin.settings.notification-templates');
            Route::get('/admin/settings/notification-templates/datatable', [NotificationSettingsController::class, 'data_table'])->name('admin.settings.notification-templates-dt');
            Route::get('/admin/settings/notification-templates/edit/{id}', [NotificationSettingsController::class, 'edit'])->name('admin.settings.notification-templates-edit');
            Route::post('/admin/settings/notification-templates/update', [NotificationSettingsController::class, 'updateNotify'])->name('admin.settings.notification-templates-update');

            // End: Settings
            // ===============================================================================================================================================================================================
            //Admin Report controllers added by Apel
            Route::get('admin/report/trader-deposit', [TraderDepositController::class, 'trader_report'])->name('admin.trader-report');
            Route::get('/admin/report/trader-description-deposit/{id}', [TraderDepositController::class, 'trader_deposit_description']);
            Route::get('/admin/report/trader-inner-description/{id}/{user_id}', [TraderDepositController::class, 'trader_inner_description']);

            //Internal fund transfer
            Route::get('/admin/fund/internal-fund-transfer', [InternalFundTransferController::class, 'internaFundTransfer'])->name('admin.fund-report');
            //External fund trasfer
            Route::get('/admin/fund/external-fund-transfer', [ExternalFundTransferController::class, 'externalFundTransfer'])->name('admin.external-fund-report');

            Route::resource('roles', RolesController::class);
            Route::resource('permissions', PermissionsController::class);
            //Manage Request for Deposit
            Route::get('/admin/manage-report/deposit-request/{id?}', [DepositRequestController::class, 'depositRequest'])->name('admin.manage.deposit');
            Route::get('/admin/manage-report/deposit-request-description/{id}', [DepositRequestController::class, 'depositRequestDescription'])->name('admin.deposit.description');
            Route::get('/admin/manage-report/show-modal', [DepositRequestController::class, 'showIdentifyModal'])->name('show-identify-modal');
            Route::post('/admin/manage-report/deposit-request/approve-request', [DepositRequestController::class, 'approveRequest']);
            Route::post('/admin/manage-report/deposit-request/decline-request', [DepositRequestController::class, 'declineRequest'])->name('admin.decline-request');

            //request amount update route
            Route::get('/admin/manage-report/request-amount-view/{id}', [DepositRequestController::class, 'viewAmount'])->name('admin.amount.view');
            Route::post('/admin/manage-report/request-amount-update', [DepositRequestController::class, 'amountUpdate'])->name('admin.amount.update');

            //Manage Request for Withdraw
            Route::get('/admin/manage-report/withdraw-request', [WithdrawRequestController::class, 'withdrawRequest'])->name('admin.manage.withdraw');
            Route::get('/admin/manage-report/withdraw-request-description', [WithdrawRequestController::class, 'withdrawRequestDescription'])->name('admin.withdraw.description');
            Route::post('/admin/manage-report/withdraw-request/approve-request', [WithdrawRequestController::class, 'approveWithdrawRequest']);
            Route::post('/admin/manage-report/withdraw-request/decline-request', [WithdrawRequestController::class, 'declineWithdrawRequest'])->name('withdraw.decline.request');
            Route::get('/admin/manage-report/deposit-inner-fetch-data/{id}', [WithdrawRequestController::class, 'totalDepositReport'])->name('total.deposit.report');
            Route::get('/admin/manage-report/withdraw-inner-fetch-data/{id}', [WithdrawRequestController::class, 'totalWithdrawReport'])->name('total.withdraw.report');
            Route::get('/admin/manage-report/bonus-inner-fetch-data/{id}/{user_id}', [WithdrawRequestController::class, 'totalBonusReport'])->name('total.bonus.report');
            Route::get('/admin/manage-report/withdraw-details-inner-fetch-data/{id}/{user_id}', [WithdrawRequestController::class, 'withDetailsReport'])->name('total.withdraw-details.report');
            //withdraw request amount updated
            Route::get('/admin/manage-report/withdraw-request-amount-view/{id}', [WithdrawRequestController::class, 'viewAmount'])->name('admin.withdraw.amount.view');
            Route::post('/admin/manage-report/withdraw-request-amount-update', [WithdrawRequestController::class, 'amountUpdate'])->name('admin.withdraw.amount.update');

            Route::any('/admin/manage-report/bank-request', [BankRequestController::class, 'bank_request'])->name('admin.bank-request');
            Route::any('/admin/manage-report/account-request', [TradingAccountRequestController::class, 'account_request'])->name('admin.account-request');
            Route::any('/admin/manage-report/account-request/approve', [TradingAccountRequestController::class, 'approve_decline'])->name('admin.account-request-approve');
            //Balance transfer trader to trader
            Route::get('/admin/manage-report/balance-transfer', [BalanceTransferController::class, 'balanceTransfer'])->name('admin.balance-transfer');
            Route::get('/admin/manage-report/balance-transfer-description/{id}/{table_id}', [BalanceTransferController::class, 'balanceTransferDescription'])->name('admin.balance-transfer-description');
            Route::get('/admin/manage-report/balance-deposit-inner-fetch-data/{id}', [BalanceTransferController::class, 'balanceDepositReport'])->name('balance.deposit.report');
            Route::get('/admin/manage-report/balance-withdraw-inner-fetch-data/{id}', [BalanceTransferController::class, 'balanceWithdrawReport'])->name('balance.withdraw.report');
            Route::get('/admin/manage-report/balance-bonus-inner-fetch-data/{id}', [BalanceTransferController::class, 'balanceBonusReport'])->name('balance.bonus.report');
            Route::get('/admin/manage-report/trading-account-inner-fetch-data/{id}/{table_id}', [BalanceTransferController::class, 'accountTradingReport'])->name('account.trading.report');
            Route::post('/admin/manage-report/balance-transfer/approve-request/{id}', [BalanceTransferController::class, 'approveBalanceRequest'])->name('admin.balance.approve-request');
            Route::post('/admin/manage-report/balance-transfer/decline-request', [BalanceTransferController::class, 'declineBalanceRequest'])->name('balance.decline-request');
            //IB Transfer Controller
            Route::get('/admin/manage-report/ib-transfer', [IBTransferController::class, 'IBTransfer'])->name('admin.ib-transfer');
            Route::get('/admin/manage-report/ib-transfer-description/{id}', [IBTransferController::class, 'ibTransferDescription'])->name('admin.ib-transfer.description');
            Route::post('/admin/manage-report/ib-transfer/approve-request', [IBTransferController::class, 'ibTransferApprove'])->name('admin.ib-transfer.approve');
            Route::post('/admin/manage-report/ib-transfer/decline-request', [IBTransferController::class, 'ibTransferDecline'])->name('admin.ib-transfer.decline');
            //IB Witrhdraw Request Controller
            Route::get('/admin/manage-report/ib-withdraw-request', [IBWithdrawController::class, 'ibWithdrawRequest'])->name('admin.ib-transfer.withdraw');
            Route::get('/admin/manage-report/ib-withdraw-description/{id}', [IBWithdrawController::class, 'ibWithdrawDescription'])->name('ib-transfer.description');
            Route::get('/admin/manage-report/ib-withdraw-inner-data-description/{id}/{table_id}', [IBWithdrawController::class, 'ibWithdrawInnerDescription'])->name('ib-transfer.inner.description');
            Route::post('/admin/manage-report/ib-withdraw-approve-request', [IBWithdrawController::class, 'ibApproveRequest'])->name('admin.ib-transfer.approve');
            Route::post('/admin/manage-report/ib-withdraw-decline-request', [IBWithdrawController::class, 'ibDeclinedRequest'])->name('admin.ib-transfer.declined');

            //Bonus Report
            Route::get('/admin/report/user-bonus-report', [UserBonusController::class, 'bonusReport'])->name('admin.user.bonus-report');
            //IB Fund Transfer Report
            Route::get('/admin/report/ib-fund-transfer-report', [IBFundTransferController::class, 'fundTransferReport'])->name('admin.user.ib-fund-transfer');
            Route::get('/admin/report/ib-fund-transfer-description/{id}', [IBFundTransferController::class, 'fundTransferDescription'])->name('admin.user.ib-fund-description');
            //External fund trasfer
            Route::get('/admin/report/external-fund-transfer', [ExternalFundTransferController::class, 'externalFundTransfer'])->name('admin.external-fund-transfer-report');

            //Ib Balance Add report
            Route::get('/admin/report/ib-balance-add-report', [IbBalanceAddController::class, 'ibBalanceReport'])->name('ib.balance.report');
            Route::get('/admin/report/ib-balance-add-report/datatable', [IbBalanceAddController::class, 'ibBalanceDescription'])->name('ib.balance.add-datatable');
            Route::get('/admin/report/ib-balance-add-report/dt-descriptions/{id}', [IbBalanceAddController::class, 'ib_inner_description'])->name('admin.balance-add.report.dt-des');

            //blocked user list
            Route::get('/admin/report/blocked_user', [BlockedUserListController::class, 'blockedUserList'])->name('admin.report.blocked_user');
            Route::post('/admin/report/unblock_user/{id}', [BlockedUserListController::class, 'unblockUser'])->name('admin.report.unblock_user');

            // Actvity: log reports--------------------------------------------------------------------------
            Route::get('/admin/report/activity-log', [ActivityLogController::class, 'activity_log'])->name('admin.activity-log');
            Route::get('/admin/report/activity-log-dt', [ActivityLogController::class, 'activity_log_dt'])->name('admin.activity-log-dt');
            Route::get('/admin/report/activity-log-dt-desctiption/{id}', [ActivityLogController::class, 'activity_log_dt_description'])->name('admin.activity-log-dt-description');
            // End: Activity log reports-------------------------------------------------------------

            // START: KYC management
            // -------------------------------------------------------------------------------------------

            Route::get('/admin/kyc-management/kyc-report', [KycReportController::class, 'kycReport'])->name('kyc.management.report');
            Route::get('/admin/kyc-management/kyc-report-view-descrption/{id}/{table_id}', [KycReportController::class, 'viewDescription'])->name('kyc.description.report');
            //admin approve description
            Route::get('/admin/kyc-management/kyc-description/{id}', [KycRequestController::class, 'kycApproveDescription'])->name('kyc.approve.description');

            Route::get('/admin/kyc-management/kyc-request', [KycRequestController::class, 'kycRequest'])->name('kyc.management.request');
            Route::get('/admin/kyc-management/kyc-request-profile-view/{id}', [KycRequestController::class, 'kycRequestProfile'])->name('kyc.management.profile');
            Route::post('/admin/kyc-management/kyc-request-user-profile-update', [KycRequestController::class, 'kycProfileUpdate'])->name('kyc.request.profile.update');
            Route::get('/admin/kyc-management/kyc-request-description/{id?}', [KycRequestController::class, 'kycRequestDescription'])->name('kyc.management.description');
            Route::post('/admin/kyc-management/kyc-approve-request', [KycRequestController::class, 'kycApproveRequest'])->name('kyc.management.approve');
            Route::post('/admin/kyc-management/kyc-decline-request', [KycRequestController::class, 'kycDeclineRequest'])->name('kyc.management.decline');
            Route::post('/admin/kyc-management/kyc-description-update', [KycRequestController::class, 'kycDesUpdate'])->name('kyc.des.update');
            Route::post('/admin/kyc-management/kyc-status', [KycRequestController::class, 'kycStatus'])->name('kyc.status.update');

            //Working here. look abovee-----------------------?
            Route::get('/admin/kyc-management/kyc-upload-view', [KycUploadController::class, 'index'])->name('admin.kyc-upload-view');
            // kyc front upload/delete
            Route::post('/admin/kyc-management/kyc-front-upload-file', [KycUploadController::class, 'id_front_file_upload'])->name('admin.kyc-front-upload-file');
            Route::post('/admin/kyc-management/kyc-upload-front-delete-file', [KycUploadController::class, 'id_front_file_delete'])->name('admin.kyc-front-upload-delete-file');
            // kyc back upload/delete
            Route::post('/admin/kyc-management/kyc-back-upload-file', [KycUploadController::class, 'id_back_file_upload'])->name('admin.kyc-back-upload-file');
            Route::post('/admin/kyc-management/kyc-upload-back-delete-file', [KycUploadController::class, 'id_back_file_delete'])->name('admin.kyc-back-upload-delete-file');
            // kyc address proof upload/delete
            Route::post('/admin/kyc-management/kyc-address-upload-file', [KycUploadController::class, 'address_file_upload'])->name('admin.kyc-address-upload-file');
            Route::post('/admin/kyc-management/kyc-upload-address-delete-file', [KycUploadController::class, 'address_file_delete'])->name('admin.kyc-address-upload-delete-file');
            // submit kyc upload form and store data
            Route::post('/admin/kyc-management/kyc-store', [KycUploadController::class, 'store'])->name('admin.kyc-store');

            // decline while upload kyc by admin
            Route::post('/admin/kyc-management/kyc-upload-decline-mail', [KycUploadController::class, 'kyc_decline_mail'])->name('admin.kyc-upload-decline-mail');

            Route::get('/admin/kyc-management/kyc-get-id-type/{id_type}', [KycUploadController::class, 'get_id_type'])->name('admin.kyc-get-id-type');
            Route::get('/admin/kyc-management/get-client/{user_type}', [KycUploadController::class, 'get_client'])->name('admin.kyc-get-client');

            Route::get('/admin/kyc-management/get-client-details/{id}', [KycUploadController::class, 'get_client_details'])->name('admin.kyc-get-client-details');

            //voucher operation
            Route::get('/admin/voucher', [VoucherController::class, 'voucherShow'])->name('admin.voucher.show');
            Route::post('/admin/voucher/generate-voucher', [VoucherController::class, 'createVoucher'])->name('admin.create.voucher');
            Route::get('/admin/voucher/trader-email', [VoucherController::class, 'traderEmail'])->name('trader.email.show');
            //voucher report
            Route::get('/admin/voucher/voucher-report', [VoucherReportController::class, 'voucherReport'])->name('admin.voucher.report');
            Route::post('/admin/voucher/voucher-active-inactive', [VoucherReportController::class, 'change_status'])->name('admin.voucher.active-inactive');

            // manage trade---------------------------------------------------------------
            // trade reports
            Route::get('admin/manage-trade/trading-trade-report', [TradingTradeReportController::class, 'trade_reports'])->name('admin.trading-trade-report');
            Route::get('admin/manage-trade/trading-trade-report-dt/{op}', [TradingTradeReportController::class, 'trade_reports_dt'])->name('admin.trading-trade-report-datatable');
            Route::get('admin/manage-trade/trading-trade-report/export', [TradingTradeReportController::class, 'export'])->name('admin.trade-report-export-csv');
            Route::post('admin/manage-trade/trading-trade-report-total', [TradingTradeReportController::class, 'trade_reports_dt'])->name('admin.trading-trade-report-total');
            // trade commission status---------
            Route::get('admin/manage-trade/trade-commission-status', [TradeCommissionStatusController::class, 'commission_status'])->name('admin.trade-commission-status');
            Route::get('admin/manage-trade/trade-commission-status-dt/{op}', [TradeCommissionStatusController::class, 'commission_status_dt'])->name('admin.trade-commission-status-dt');
            Route::post('admin/manage-trade/trade-commission-status-total', [TradeCommissionStatusController::class, 'commission_status_dt'])->name('admin.trade-commission-status-total');

            Route::get('admin/master-ib-details/{id}', [MasterIBDetailsController::class, 'masterIBDetails'])->name('admin.master-ib.details');
            // Route::get('/admin/master-ib-details/deposit-report/{id}', [MasterIBDetailsController::class, 'MasterdepositReport'])->name('admin.master-ib.deposit-report');
            //Client support
            Route::get('admin/support/support-ticket',  [SupportControllerForAdmin::class, 'index'])->name('admin.support.support-ticket');
            Route::any('admin/support/support-ticket-get',  [SupportControllerForAdmin::class, 'get_support'])->name('admin.support.support-ticket-get');
            Route::any('admin/support/support-ticket-reply',  [SupportControllerForAdmin::class, 'get_support_reply'])->name('admin.support.support-ticket-reply');
            Route::any('admin/support/support-send-reply',  [SupportControllerForAdmin::class, 'send_support_reply'])->name('admin.support.support-send-reply');
            Route::any('admin/support/support-ticket-delete',  [SupportControllerForAdmin::class, 'delete_ticket'])->name('admin.support.delete-ticket');
            Route::any('admin/support/support-ticket-st-update',  [SupportControllerForAdmin::class, 'update_ticket'])->name('admin.support.update-ticket');
            Route::any('admin/support/support-show-replay',  [SupportControllerForAdmin::class, 'ShowRealTimeRepay'])->name('admin.support.show-reply');
            Route::any('/admin/support/support-ticket-get-count',  [SupportControllerForAdmin::class, 'getCount'])->name('admin.support.get-count');
            Route::post('admin/support/get-server-replay',  [SupportControllerForAdmin::class, 'server_replay'])->name('admin.support.server-replay');

            //social trade route
            Route::get('/admin/pamm/copy-dashboard',  [CopyDashboardController::class, 'copyDashboard'])->name('admin.pamm.copy-dashboard');
            Route::get('/admin/pamm/copy-dashboard-process',  [CopyDashboardController::class, 'copyDashboardProcess'])->name('admin.pamm.copy-dashboard.process');


            Route::get('/admin/pamm/pamm-settings',  [PammSettingController::class, 'PammSetting'])->name('admin.pamm');
            Route::post('/admin/pamm/pamm-settings-process',  [PammSettingController::class, 'PammSettingProcess'])->name('admin.pamm.process');
            Route::post('/admin/pamm/pamm-ready-content-process',  [PammSettingController::class, 'ReadyContent'])->name('admin.pamm.ready.content');

            //pamm manager
            Route::get('/admin/pamm/pamm-manager',  [PammManagerController::class, 'PammManager'])->name('admin.manager.pamm');
            Route::get('/admin/pamm/pamm-manager-dt',  [PammManagerController::class, 'datatable'])->name('admin.manager.pamm-datatable');
            Route::any('/admin/pamm/pamm-manager-active-inactive',  [PammManagerController::class, 'active_inactive'])->name('admin.manager.master-active-inactive');
            Route::any('/admin/pamm/pamm-manager-add-slave',  [PammManagerController::class, 'add_slave'])->name('admin.manager.master-add-slave');
            Route::any('/admin/pamm/pamm-manager-edit-slave',  [PammManagerController::class, 'edit_slave'])->name('admin.manager.master-edit-slave');
            Route::any('/admin/pamm/pamm-manager-delete slave',  [PammManagerController::class, 'delete_slave'])->name('admin.manager.master-delete-slave');
            // pamm profile update
            Route::any('/admin/pamm/pamm-manager-get-profile/{account}',  [SocialTradePammProfileController::class, 'get_pamm_profile'])->name('admin.manager.get-pamm-profile');
            Route::any('/admin/pamm/pamm-manager-update-profile',  [SocialTradePammProfileController::class, 'update_pamm_profile'])->name('admin.manager.update-pamm-profile');

            Route::get('/admin/pamm/copy-trades-report',  [AdminCopyTradeReportController::class, 'CopyTradeReport'])->name('admin.pamm.copy-trade-report');
            Route::post('/admin/pamm/copy-trades-report-process',  [AdminCopyTradeReportController::class, 'CopyTradeReportProcess'])->name('admin.pamm.copy-trade-report.process');
            Route::post('/admin/pamm/copy-trades-report-process-detail',  [AdminCopyTradeReportController::class, 'CopyTradeReportProcessDetail'])->name('admin.pamm.copy-trade-report.process.detail');
            //activiy report route
            Route::get('/admin/pamm/social-trades-ativity-report',  [AdminSocialTradesController::class, 'SocialTrade'])->name('admin.social-report');
            Route::post('/admin/pamm/social-trades-ativity-report-process',  [AdminSocialTradesController::class, 'SocialTradeProcess'])->name('admin.social-report.process');

            //manage mam route
            Route::get('/admin/pamm/social-trades/manage-mam',  [AdminManageMammController::class, 'manageMamm'])->name('admin.mamm.manage');
            Route::any('/admin/pamm/social-trades/manage-mam/slave-list/', [AdminManageMammController::class, 'SlaveAccount']);
            
            

             // start : tournament
            //tournament setup 
            Route::get('/admin/tournament/setting-view',  [TournamentSettingController::class, 'tournamentSettingView'])->name('admin.tournament.setting-view');
            Route::any('/admin/tournament/setting-action', [TournamentSettingController::class, 'tournamentSettingAction'])->name('admin.tournament.setting-action');
            
            //group list
            Route::get('/admin/tournament/group-list', [GroupListController::class, 'groupList'])->name('admin.tournament.group-list');
            Route::get('/admin/tournament/group-list/datatable', [GroupListController::class, 'groupListDatatable'])->name('admin.tournament.group-list.datatable');
            Route::get('/admin/tournament/group-list/dt-descriptions/{id}', [GroupListController::class, 'groupListDescription'])->name('admin.tournament.group-list.dt-des');
            Route::post('/admin/tournament/participant-delete', [GroupListController::class, 'groupListParticipantDelete'])->name('admin.tournament.participant-delete');
            Route::post('/admin/tournament/group-trading-start', [GroupListController::class, 'groupTradingStart'])->name('admin.tournament.group-trading-start');
            Route::post('/admin/tournament/group-trading-close', [GroupListController::class, 'groupTradingClose'])->name('admin.tournament.group-trading-close');
            // end : tournament
            
            
            //Bonus Report
            Route::get('/admin/pamm/master-profit-share-report', [MasterProfitShareController::class, 'masterProfitShareView'])->name('admin.pamm.master-profit-share-report');

            //symbol operation route here
            Route::post('/admin/meta5_mam_add/add_symbol', [AdminManageMammController::class, 'AddNewSymbol'])->name('admin.mamm.symbol.add');
            Route::post('/admin/meta5_mam_add/symbol_delete', [AdminManageMammController::class, 'SymbolDelete'])->name('symbol.delete');
            Route::post('/admin/meta5_mam_delete/submit_symbol', [AdminManageMammController::class, 'UpdateSymbolStatus'])->name('symbol.update');
            Route::get('/admin/trading-account-balance-equity', [AdminManageMammController::class, 'showTradingAccountBl'])->name('trading.account-balance');
            //slave account delete
            Route::any('/admin/meta5_mam_delete', [AdminManageMammController::class, 'SlaveAccountDelete']);
            //add slave account
            Route::any('/admin/add-slave-account', [AdminManageMammController::class, 'addSlaveAccount'])->name('admin.addSlaveAccount');


            // Ledger Reports
            Route::any('/admin/report/ledger-report', [AdminLedgerReportController::class, 'view'])->name('admin.report.ledger');
            Route::any('admin/report/individual-ledger-report', [AdminLedgerReportController::class, 'individual_ledger_view'])->name('admin.report.ledger-individual');

            Route::get('/admin/report/individual-description-report', [AdminLedgerReportController::class, 'individual_description']);
            Route::get('/admin/report/individual-inner-description/{user_id}', [AdminLedgerReportController::class, 'individual_inner_description']);
            // user migration
            Route::get('/system/migration/user-migration/view', [UserMigrationController::class, 'index'])->name('system.user-migration-view');
            Route::post('/system/migration/user-migration/store', [UserMigrationController::class, 'store'])->name('system.user-migration-store');

            // admin bank list
            Route::get('/admin/manage_banks/company-bank-list/{op?}', [CompnayBankController::class, 'index'])->name('admin.company-bank-list');
            Route::post('/admin/manage_banks/company-bank-list/active/{id?}', [CompnayBankController::class, 'active'])->name('admin.company-bank-list.active');
            Route::post('/admin/manage_banks/company-bank-list/disable/{id?}', [CompnayBankController::class, 'disable'])->name('admin.company-bank-list.disable');
            Route::post('/admin/manage_banks/company-bank-list/delete/{id?}', [CompnayBankController::class, 'delete'])->name('admin.company-bank-list.delete');

            //route for contest controller
            Route::get('/admin/contest/create-contest',  [CreateContestController::class, 'createContest'])->name('admin.contest.create');
            Route::get('/admin/contest/contest-list',  [ContentListController::class, 'ContestList'])->name('admin.contest.list');
            Route::get('/admin/contest/contest-list-description',  [ContentListController::class, 'ContestListDescription'])->name('admin.contest.description');
            Route::get('/admin/contest/contest-participant',  [ContestParticipantController::class, 'ContestParticipant'])->name('admin.contest.participant');
            Route::get('/admin/contest/contest-participant-report',  [ContestParticipantController::class, 'ContentParticipantReport'])->name('admin.contest-participant.report');
            Route::get('/admin/contest/contest-popup',  [ContentListController::class, 'popup_image'])->name('admin.contest-popup.popup');
            Route::post('/admin/contest/credit',  [ContestParticipantController::class, 'contest_credit'])->name('admin.contest.credit');
            
            
            //route for reward
            Route::get('/admin/reward/create-reward',  [RewardController::class, 'createRewardView'])->name('admin.reward.create');
            Route::post('/admin/reward/save-reward',  [RewardController::class, 'createReward'])->name('admin.reward.save');
            Route::get('/admin/reward/rewards',  [RewardController::class, 'rewardList'])->name('admin.rewards');
            Route::get('/admin/reward/list-reward',  [RewardController::class, 'rewardListReport'])->name('admin.reward.list');
            Route::get('/admin/reward/update-reward-view/{id}',  [RewardController::class, 'updateRewardView'])->name('admin.reward.update.view');
            Route::post('/admin/reward/update-reward/{id}',  [RewardController::class, 'updateReward'])->name('admin.reward.update');
            Route::get('/admin/search/client', [UserController::class, 'searchClient'])->name('admin.search.client');
            Route::get('/admin/search/client/groups/{id}', [UserController::class, 'fetchClientGroupsByClientId'])->name('admin.search.client.groups');
            Route::get('/admin/claim/reward/list/data',  [RewardController::class, 'claimRewardListReport'])->name('admin.claim.reward.list.data');
            Route::get('/admin/claim/reward/list',  [RewardController::class, 'rewardClaimList'])->name('admin.claim.reward.list');
            Route::get('/ib/transfer/reward/admin-to-trader/{reward_trader_id}', [RewardTraderController::class, 'adminRewardTransfer'])->name('ib.reward.transfer.admin-to-trader');
            Route::post('/admin/reward/change-status',  [RewardController::class, 'toggleRewardStatus'])->name('admin.rewars.toggleRewardStatus');
            Route::get('/admin/reward/participants/list',  [RewardController::class, 'rewardParticipantView'])->name('admin.reward.participants.list');
            Route::get('/admin/reward/participants/report/list',  [RewardController::class, 'rewardParticipantReport'])->name('admin.reward.participants.report.list');
            Route::get('/admin/trader/reward/susspend/{id}',  [RewardController::class, 'suspendTraderReward'])->name('admin.trader.reward.susspend');


            // contest form submit
            Route::post('/admin/contest/create/trader-contest', [CreateContestController::class, 'trader_contest'])->name('admin.create.trader-contest');
            Route::post('/admin/contest/create/ib-contest', [CreateContestController::class, 'ib_contest'])->name('admin.create.ib-contest');
            Route::post('/admin/contest/close', [CreateContestController::class, 'close_contest'])->name('admin.close.contest');
            Route::post('/admin/contest/delete', [CreateContestController::class, 'contest_delete'])->name('admin.delete.contest');
            Route::post('/admin/contest/edit/{contest_id}', [CreateContestController::class, 'contest_edit'])->name('admin.edit.contest');
            Route::post('/admin/contest/trader-update', [CreateContestController::class, 'trader_contest_update'])->name('admin.trader.update.contest');
            Route::post('/admin/contest/ib-update', [CreateContestController::class, 'ib_contest_update'])->name('admin.ib.update.contest');
            Route::post('/admin/contest/check-contest-status', [ContentListController::class, 'checkContestStatus'])->name('admin.contest.check-status');
            Route::post('/admin/contest/get-contest-result-data', [ContentListController::class, 'getContestResultData'])->name('admin.contest.get-result-data');
            Route::post('/admin/contest/announce-result', [ContentListController::class, 'announceResult'])->name('admin.contest.announce-result');

            //bouns route
            Route::get('/admin/bonus/create-bonus',  [BonusCreateController::class, 'createBonus'])->name('admin.bonus.create');
            // bonus list datatable
            Route::get('/admin/bonus/bonus-list',  [BonusListController::class, 'BonusList'])->name('admin.bonus.list');
            Route::get('/admin/bonus/bonus-list-process',  [BonusListController::class, 'BonusListProcess'])->name('admin.bonus.list.process');
            Route::get('/admin/bonus/bonus-list-details/{id}',  [BonusListController::class, 'BonusListDetails'])->name('admin.bonus.list.details');
            Route::get('/admin/bonus/get-bonus-groups', [BonusListController::class, 'get_bonus_group'])->name('admin.bonus.bonus-group-get');
            // edit bonus data get
            Route::get('/admin/bonus/get-bonus-data-single', [BonusListController::class, 'get_bonus_data'])->name('admin.bonus.bonus-group-get-single');
            //
            Route::get('/admin/bonus/get-bonus-countries', [BonusListController::class, 'get_bonus_country'])->name('admin.bonus.bonus-country-get');
            Route::get('/admin/bonus/get-bonus-client', [BonusListController::class, 'get_bonus_client'])->name('admin.bonus.bonus-client-get');
            Route::get('/admin/bonus/bonus-report',  [BonusReportController::class, 'BonusReport'])->name('admin.bonus.report');
            Route::get('/admin/bonus/bonus-report-process',  [BonusReportController::class, 'BonusReportProcess'])->name('admin.bonus.report.process');

            // create all clients bonus
            Route::post('/admin/bonus/create/all-clients', [BonusCreateController::class, 'all_client_bonus'])->name('admin.create.all-client-bonus');
            Route::post('/admin/bonus/create/new-registration', [BonusCreateController::class, 'new_registration_bonus'])->name('admin.create.new-registration-bonus');
            Route::post('/admin/bonus/create/new-account', [BonusCreateController::class, 'new_account_bonus'])->name('admin.create.new-account-bonus');
            // edit new regstration bonus
            Route::post('/admin/bonus/edit/new-registration', [BonusCreateController::class, 'new_reg_bonus_edit'])->name('admin.edit.new-reg-bonus');
            Route::post('/admin/bonus/edit/all-clients', [BonusCreateController::class, 'all_client_bonus_edit'])->name('admin.edit.all-client-bonus');
            Route::post('/admin/bonus/edit/new-account', [BonusCreateController::class, 'new_account_bonus_edit'])->name('admin.edit.new-account-bonus');
            //pamm request route
            Route::get('/admin/pamm/pamm-request', [PammRequestController::class, 'pamm_request'])->name('user.pamm_request');
            Route::get('/admin/pamm/pamm-request-process', [PammRequestController::class, 'pamm_request_dt'])->name('user.pamm_request.datatable');
            Route::post('/admin/pamm/pamm-request-approve', [PammRequestController::class, 'pamm_request_approve'])->name('user.pamm_request.approve');
            Route::get('/admin/pamm/admin-description/{id}', [PammRequestController::class, 'PammApproveDescription'])->name('pamm.approve.description');
            // system notifiction
            Route::get('/admin/notification/system-notification', [SystemNotificationController::class, 'index'])->name('admin.notification.system-notification');
            Route::get('/admin/notification/count', [SystemNotificationController::class, 'notification_count'])->name('admin.notification.count');
            Route::get('/admin/notification/by-type/{type}', [SystemNotificationController::class, 'getNotificationsByType'])->name('admin.notification.by-type');
            Route::get('/admin/notification/test/create-sample', [SystemNotificationController::class, 'createSampleNotifications'])->name('admin.notification.test.create');

            // admin withdraw report (IB and TRADER)
            Route::get('/admin/report/withdraw/trader', [AdminWithdrawController::class, 'withdraw_report'])->name('admin.trader.withdraw-report');
            Route::get('/admin/report/withdraw/trader/dt', [AdminWithdrawController::class, 'withdrawReportDT'])->name('admin.trader.withdraw-report.dt');
            Route::get('/admin/report/withdraw-description/{id}', [AdminWithdrawController::class, 'withdraw_description'])->name('admin.trader.withdraw.reports.dt-descriptions');
            // ib withdraw report
            Route::get('/admin/report/withdraw/ib', [AdminsIbWithdrawReportController::class, 'index'])->name('admin.ib.withdraw-report');
            Route::get('/admin/report/withdraw/ib/dt', [AdminsIbWithdrawReportController::class, 'datatable_ib_withdraw'])->name('admin.ib.withdraw-report.dt');
            Route::get('/admin/report/withdraw/ib/dt-description/{id}', [AdminsIbWithdrawReportController::class, 'dt_description'])->name('admin.ib.withdraw-report.dt-description');

            // admin log report log-dt-fetch-data
            Route::get('/admin/report/log', [AdminLogController::class, 'log_report'])->name('admin.log-report');
            Route::get('/admin/report/log-dt-fetch-data', [AdminLogController::class, 'log_dt_fetch_data'])->name('admin.deposit-dt-proccess');
            Route::get('/admin/report/dt-description-log', [AdminLogController::class, 'log_dt_description'])->name('admin.deposit-dt-proccess');
            Route::get('/admin/report/log-dt-inner-fetch-data/{id}', [AdminLogController::class, 'deposit_dt_fetch_data'])->name('admin.deposit-dt-description');
            // Start: payment gateway
            Route::get('/admin/settings/gateway-setup', [GatewayController::class, 'gatewayList'])->name('admin.settings.gateway-setup');
            // End:  payment gateway
            
            
            Route::get('/manager/dashboard', [ManagerDashboardController3::class, 'index'])->name('manager.dashboard');
            Route::post('/manager/analysis', [ManagerDashboardController3::class, 'manager_analysis'])->name('manager.analysis');
            Route::get('/manager/analysis/finance', [ManagerDashboardController3::class, 'finance_details'])->name('manager.analisys.finance');
            Route::get('/manager/analysis/client-details', [ManagerDashboardController3::class, 'client_detailes'])->name('manager.analisys.client-detailes');
            Route::get('/manager/analysis/ib-client-details', [ManagerDashboardController3::class, 'ib_clients_detailes'])->name('manager.analisys.ib-client-detailes');
            Route::get('/manager/analysis/deposit-detailes', [ManagerDashboardController3::class, 'deposit_detailes'])->name('manager.analisys.withdraw-detailes');
            
            Route::get('/manager/lead/report', [ManagerDashboardController::class, 'lead_manager_report'])->name('manager.lead.report');
            Route::get('/manager/lead/report/data', [ManagerDashboardController::class, 'trader_manager_dt_fetch_data'])->name('manager.lead.report.data');

        });

        // manager controllers
        Route::middleware(['auth', 'user-access:manager'])->group(function () {
            // Route::get('/manager/index', [ManagerDashboardController::class, 'index'])->name('manager.index');
            Route::get('/manager/index', [ManagerDashboardController::class, 'dashboard'])->name('manager.index');
            // Route::get('/manager/index', [AdminDashboardController::class, 'dashboard'])->name('manager.index');
            // Route::post('/manager/analysis', [ManagerDashboardController::class, 'manager_analysis'])->name('manager.analysis');
            // Route::get('/manager/analysis/finance', [ManagerDashboardController::class, 'finance_details'])->name('manager.analisys.finance');
            // Route::get('/manager/analysis/client-details', [ManagerDashboardController::class, 'client_detailes'])->name('manager.analisys.client-detailes');
            // Route::get('/manager/analysis/ib-client-details', [ManagerDashboardController::class, 'ib_clients_detailes'])->name('manager.analisys.ib-client-detailes');
            // Route::get('/manager/analysis/deposit-detailes', [ManagerDashboardController::class, 'deposit_detailes'])->name('manager.analisys.withdraw-detailes');
            
            // Route::get('/manager/lead/report', [ManagerDashboardController::class, 'lead_manager_report'])->name('manager.lead.report');
            // Route::get('/manager/lead/report/data', [ManagerDashboardController::class, 'trader_manager_dt_fetch_data'])->name('manager.lead.report.data');
            
            Route::get('/manager/dashboard', [ManagerDashboardController3::class, 'index'])->name('manager.dashboard');
            Route::post('/manager/analysis', [ManagerDashboardController3::class, 'manager_analysis'])->name('manager.analysis');
            Route::get('/manager/analysis/finance', [ManagerDashboardController3::class, 'finance_details'])->name('manager.analisys.finance');
            Route::get('/manager/analysis/client-details', [ManagerDashboardController3::class, 'client_detailes'])->name('manager.analisys.client-detailes');
            Route::get('/manager/analysis/ib-client-details', [ManagerDashboardController3::class, 'ib_clients_detailes'])->name('manager.analisys.ib-client-detailes');
            Route::get('/manager/analysis/deposit-detailes', [ManagerDashboardController3::class, 'deposit_detailes'])->name('manager.analisys.withdraw-detailes');
            
            Route::get('/manager/lead/report', [ManagerDashboardController::class, 'lead_manager_report'])->name('manager.lead.report');
            Route::get('/manager/lead/report/data', [ManagerDashboardController::class, 'trader_manager_dt_fetch_data'])->name('manager.lead.report.data');

            
        });

        Route::post('/logout', [LoginController::class, 'logoutMethod'])->name('logout');

    // Contest routes - moved inside auth middleware
        Route::get('/user/contest/leaderboard', [ContestStatusController::class, 'getLeaderboard']);
        Route::get('/user/contest/user-stats', [ContestStatusController::class, 'getUserStats']);
        Route::get('/user/contest/test-db', [ContestStatusController::class, 'testDatabaseConnection']);
        Route::get('/user/contest/debug-contest', [ContestStatusController::class, 'debugContestData']);
        Route::get('/user/contest/manual-update', [ContestStatusController::class, 'manualUpdateContestData']);
        Route::get('/user/contest/test-data', [ContestStatusController::class, 'testContestData']);
        Route::get('/user/contest/test-mt5', [ContestStatusController::class, 'testMT5Connection']);
        Route::get('/user/contest/test-real-trades', [ContestStatusController::class, 'testRealTrades']);
        Route::get('/user/contest/check-mt5-data', [ContestStatusController::class, 'checkMT5Data']);
        Route::get('/user/contest/check-participant', [ContestStatusController::class, 'checkContestParticipant']);

        // Test route for debugging
        Route::get('/user/contest/test-route', function() {
            return response()->json(['status' => 'success', 'message' => 'Route is working']);
        });

        // Debug route for testing getUserStats
        Route::get('/user/contest/debug-user-stats', function() {
            $controller = new \App\Http\Controllers\traders\contest\ContestStatusController();
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'account_number' => '2107480',
                'contest_id' => '1'
            ]);
            return $controller->getUserStats($request);
        });

        // Debug route for testing specific account
        Route::get('/user/contest/debug-account/{account}', function($account) {
            $controller = new \App\Http\Controllers\traders\contest\ContestStatusController();
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'account_number' => $account,
                'contest_id' => '1'
            ]);
            return $controller->getUserStats($request);
        });

        // Test route for specific account trades
        Route::get('/user/contest/test-account-trades', [ContestStatusController::class, 'testSpecificAccount']);

        // Debug route for available data
        Route::get('/user/contest/debug-available-data', [ContestStatusController::class, 'debugAvailableData']);

        // Test route for specific account with contest
        Route::get('/user/contest/test-account-contest', function() {
            $controller = new \App\Http\Controllers\traders\contest\ContestStatusController();
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'account_number' => '2107480',
                'contest_id' => '13'
            ]);
            return $controller->testSpecificAccount($request);
        });

        // Test route for MT5 data
        Route::get('/user/contest/test-mt5-data', [ContestStatusController::class, 'testMT5DataForAccount']);

        // Check contest details
        Route::get('/user/contest/check-contest-details', [ContestStatusController::class, 'checkContestDetails']);

        // Check contest dates
        Route::get('/user/contest/check-contest-dates', [ContestStatusController::class, 'checkContestDates']);

         Route::get('/user/contest/search-account-mt5', [ContestStatusController::class, 'searchAccountInMT5']);
         
         // Check for announced results
         Route::post('/user/contest/check-announced-results', [ContestStatusController::class, 'checkAnnouncedResults']);
         
         // Test route for MT5 equity
         Route::get('/user/contest/test-mt5-equity/{account}', function($account) {
             $controller = new \App\Http\Controllers\traders\contest\ContestStatusController();
             $equity = $controller->getRealTimeEquityForAccount($account);
             return response()->json([
                 'account' => $account,
                 'equity' => $equity,
                 'success' => $equity > 0
             ]);
         });
         
         // Test route for alternative equity
         Route::get('/user/contest/test-alternative-equity/{account}', function($account) {
             $controller = new \App\Http\Controllers\traders\contest\ContestStatusController();
             $reflection = new ReflectionClass($controller);
             $method = $reflection->getMethod('getAlternativeEquityForAccount');
             $method->setAccessible(true);
             $equity = $method->invoke($controller, $account);
             return response()->json([
                 'account' => $account,
                 'alternative_equity' => $equity,
                 'success' => $equity > 0
             ]);
         });
         

         
         // Test route for debugging
         Route::get('/user/contest/test-modal', function() {
             return response()->json([
                 'status' => true,
                 'message' => 'Test route working',
                 'data' => [
                     'contest_id' => 12,
                     'contest_name' => 'Test Contest',
                     'winners' => [
                         [
                             'rank' => 1,
                             'user_name' => 'Test User 1',
                             'account_number' => '123456',
                             'equity' => '1000.00',
                             'profit' => '500.00',
                             'prize_amount' => '100.00'
                         ]
                     ]
                 ]
             ]);
         });
        // if route not found then redirect to home
        Route::any('{url}', function () {
            return redirect()->back();
        })->where('url', '.*');
    });
    // Route::get('test', function () {
    //     dd(IBManagementService::isKycVerified(Auth::user()));
    // });

});






