<?php

namespace App\Http\Controllers\admins\ManageAccounts;

use App\Http\Controllers\Controller;
use App\Mail\ChangeInvestorPassword;
use App\Mail\ChangeMasterPassword;
use App\Mail\ChangePassword;
use App\Mail\ResendAccountCredential;
use App\Mail\ResetPassword;
use App\Models\admin\AdminUser;
use App\Models\admin\InternalTransfer;
use App\Models\admin\SystemConfig;
use App\Models\BonusUser;
use App\Models\Category;
use App\Models\ClientGroup;
use App\Models\Comment;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\IB;
use App\Models\KycVerification;
use App\Models\Log;
use App\Models\ManagerGroup;
use App\Models\ManagerUser;
use App\Models\Mt5Trade;
use App\Models\PasswordReset;
use App\Models\SoftwareSetting;
use App\Models\Trade;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\Withdraw;
use App\Services\common\UserService;
use App\Services\DataTableService;
use App\Services\EmailService;
use App\Services\MT4API;
use App\Services\Mt5WebApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class LiveTradingAccountDetailsController extends Controller
{
    public function index(Request $request)
    {
        $op = $request->op;
        if ($op == 'trader-data-table') {
            return $this->traderListDT($request);
        }
        if ($op == 'trader-description') {
            return $this->traderListDescription($request);
        }
        if ($op == 'trader-deposit') {
            return $this->depositListDT($request);
        }
        if ($op == 'trader-withdraw') {
            return $this->withdrawListDT($request);
        }
        if ($op == 'trader-bonus') {
            return $this->bonusListDT($request);
        }
        if ($op == 'trade-list') {
            return $this->tradeListDT($request);
        }
        if ($op == 'block-unblock') {
            return $this->blockUnblock($request);
        }
        if ($op == 'ib-commission-operation-trader') {
            return $this->IBCommissionOperation($request);
        }
        if ($op == 'deposit-operation-trader') {
            return $this->depositOperation($request);
        }
        if ($op == 'withdraw-operation-trader') {
            return $this->withdrawOperation($request);
        }
        if ($op == 'change-password') {
            return $this->changePassword($request);
        }
        if ($op == 'change-master-password-mail') {
            return $this->changeMasterPasswordMail($request);
        }
        if ($op == 'change-investor-password-mail') {
            return $this->changeInvestorPasswordMail($request);
        }
        if ($op == 'reset-password') {
            return $this->resetPassword($request);
        }
        if ($op == 'trader-comment') {
            return $this->traderComment($request);
        }
        if ($op == 'create-comment') {
            return $this->traderAddComment($request);
        }
        if ($op == 'update-comment') {
            return $this->traderUpdateComment($request);
        }
        if ($op == 'delete-comment') {
            return $this->traderDeleteComment($request);
        }
        // if ($op == 'change-group') {
        //     return $this->traderChangeGroup($request);
        // }
        if ($op == 'resend-account-credentials-mail') {
            return $this->resendAccountCredentialsMail($request);
        }
        if ($op == 'remove-from-trader') {
            return $this->removeFromTrader($request);
        }
        if ($op == 'add-as-trader') {
            return $this->addAsTrader($request);
        }
        if ($op == 'get-leverage-data') {
            return $this->getLeverageData($request);
        }
        // if ($op == 'change-leverage') {
        //     return $this->traderChangeLeverage($request);
        // }
        if ($op == 'check-account-balance') {
            return $this->checkAccountBalance($request);
        }
        $clientGroups = ClientGroup::select('id', 'group_name', 'group_id')->whereNot('visibility', 'deleted')->get();
        $categories =  Category::where('client_type', 'trader')->select()->get();

        return view('admins.manage_accounts.live_trading_account_details', compact('clientGroups', 'categories'));
    }

    private function traderListDT($request)
    {
        $dts = new DataTableService($request);
        $columns = $dts->get_columns();

        $result = TradingAccount::select('*', 'trading_accounts.user_id as user_id', 'trading_accounts.id as id', 'trading_accounts.leverage as leverage')
            ->join('users', 'trading_accounts.user_id', '=', 'users.id')
            ->join('client_groups', 'trading_accounts.group_id', '=', 'client_groups.id')
            ->where('client_groups.account_category', '=', 'live')
            ->where('trading_accounts.account_status', '=', 1);
        // if auth user type is manager
        if (strtolower(auth()->user()->type) === 'manager') {
            $result = $result->join('manager_users', 'users.id', '=', 'manager_users.user_id')->where('manager_id', auth()->user()->id);
        }

        //Search if columns field has search data
        $result = $result->where(function ($q) use ($dts, $columns) {
            if ($dts->search) {
                foreach ($columns as $col) {
                    if ($col['data'] != 'responsive_id' && $col['data'] != 'id' && !empty($col['data'])) {
                        $tf = $col['data'];
                        $st = $dts->search;
                        $q->orWhere("$tf", 'LIKE', '%' . $st . '%');
                    }
                }
            }
        });
        //----------------------------------------------------------------------------------------
        //Filter Start
        //----------------------------------------------------------------------------------------
        // Filter by finance
        if ($request->finance != "") {
            // Filter by withdraw
            if (strtolower($request->finance) === 'withdraw') {
                $withdrawIds = InternalTransfer::where('type', 'atw')->pluck('account_id');
                $result = $result->whereIn("trading_accounts.id", $withdrawIds);
            }
            // Filter by Deposits
            if (strtolower($request->finance) === 'deposit') {
                $depositIds = InternalTransfer::where('type', 'wta')->pluck('account_id');
                $result = $result->whereIn("trading_accounts.id", $depositIds);
            }
        }

        // filter by platform
        if ($request->platform != "") {
            $result = $result->where('platform', $request->platform);
        }

        // Filter by verfification status
        if ($request->verification_status != "") {
            $result = $result->where('approve_status', $request->verification_status);
        }

        // manager filter script
        if ($request->manager != "") {
            $manager_info = $request->manager;
            $manager = User::select('id')->where('type', 5)->where(function ($query) use ($manager_info) {
                $query->where('name', 'LIKE', '%' . $manager_info . '%')
                    ->orWhere('email', 'LIKE', '%' . $manager_info . '%');
            })->first();
            $userId = ManagerUser::where('manager_id', $manager->id)->get()->pluck('user_id');
            $result = $result->whereIn('trading_accounts.user_id', $userId);
        }
        //Filter by info like name,email,phone
        if ($request->info != "") {
            $result = $result->where('users.name', $request->info)->orwhere('users.email', $request->info)->orwhere('users.phone', $request->info);
        }

        //filter by IB Info
        if ($request->ib_info != "") {
            $result = $result->where('users.type', 4)->where('users.email', $request->ib_info)->orWhere('users.name', $request->ib_info);
        }

        // filter by leverage
        if ($request->leverage != "") {
            $result = $result->where('trading_accounts.leverage', $request->leverage);
        }

        //filter by trading account
        if ($request->trading_acc != "") {
            $result = $result->where('account_number', $request->trading_acc);
        }
        // filter by account group
        if ($request->account_groups != "") {
            $result = $result->where('trading_accounts.group_id', $request->account_groups);
        }
        $count = $result->count();
        $result = $result->orderBy(($dts->orderBy() == 'id' || $dts->orderBy() == 'leverage') ? "trading_accounts.{$dts->orderBy()}" : $dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();

        $data = [];
        $i = 0;
        foreach ($result as $row) {
            if ($row->approve_status == 0) {
                $status = "<span class='badge badge-light-warning'>Pending</span>";
            } else if ($row->approve_status == 1) {
                $status = "<span class='badge badge-light-success'>Active</span>";
            } else {
                $status = "<span class='badge badge-light-danger'>Unverified</span>";
            }
            $data[$i]['id'] = $row['trading_accounts.id'];
            $data[$i]['account_number'] = '<a href="#" data-userid=' . $row->user_id . ' data-accountid=' . $row->id . ' class="dt-description justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' . $row->account_number . '</span></a>';
            $data[$i]['leverage'] = $row->leverage;
            $data[$i]['group_name'] = $row->group_name;
            $data[$i]['email'] = $row->email;
            $data[$i]['server'] = $row->platform;
            $data[$i]['approve_status'] = $status;
            $i++;
        }
        $res['draw'] = $dts->draw;
        $res['recordsTotal'] = $count;
        $res['recordsFiltered'] = $count;
        $res['data'] = $data;
        return json_encode($res);
    }

    private function traderListDescription($request)
    {
        $id = $request->userId;
        $accountId = $request->accountId;
        $user = User::where('users.id', $id)
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->select(
                'users.*',
                'user_descriptions.gender',
                'countries.name as country'
            )
            ->first();
        if (isset($user->gender)) {
            $avatar = (strtolower($user->gender) === 'male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png'; //<----avatar url
        }
        $totalDeposit = InternalTransfer::where('account_id', $accountId)->where('type', 'wta')->sum('amount');
        $totalWithdraw = InternalTransfer::where('account_id', $accountId)->where('type', 'atw')->sum('amount');
        $totalTradingAccount = TradingAccount::where('user_id', $id)->count();
        $accountNo = TradingAccount::find($accountId)->account_number;
        $account = TradingAccount::find($accountId);
        $group = ClientGroup::find($account->group_id);
        $accountDisableBtn = $account->account_status == 0 ? 'd-none' : '';
        $accountEnableBtn = $account->account_status == 1 ? 'd-none' : '';
        $block_operation = ($account->block_status == 0) ? 'checked' : ' ';
        $commission_operation = ($account->commission_status == 1) ? 'checked' : ' '; //IB commission operation
        $deposit_operation = ($account->deposit_status == 1) ? 'checked' : ' '; // deposit_operation enable or disable
        $withdraw_operation = ($account->withdraw_status == 1) ? 'checked' : ' '; // deposit_operation enable or disable
        $set_category = '';
        $categories = Category::where('client_type', 'trader')->select()->get();
        foreach ($categories as $category) {
            $set_category .= '<option value="' . $category->id . '">' . ucwords($category->name) . '</option>';
        }
        $kyc_status = KycVerification::where('user_id', $id)->first();
        if (isset($kyc_status->status)) {
            if ($kyc_status->status == 0) {
                $check_uncheck = '<span class="badge badge-light-warning">Pending</span>';
            } elseif ($kyc_status->status == 1) {
                $check_uncheck = '<span class="badge badge-light-success">Verified</span>';
            } else {
                $check_uncheck = '<span class="text-danger">Unverified</span>';
            }
        } else {
            $check_uncheck = '<span class="badge badge-light-danger">Unverified</span>';
        }

        // FIND CATEGORY
        $category = Category::find($user->category_id);
        if (isset($category->name)) {
            $category = ucwords($category->name);
        } else {
            $category = ucwords('N/A');
        }

        $software_setting = SoftwareSetting::first();
        $account_remove_button = ($software_setting->account_move == 0) ? "d-none" : "";
        $deskManager = User::select('*')
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
            ->join('manager_users', 'managers.id', '=', 'manager_users.manager_id')
            ->where('manager_groups.group_type', 0)
            ->where('manager_users.user_id', '=', $id)
            ->first();
        $deskManagerName = $deskManager ? $deskManager->name : 'N/A';
        $accountManager = User::select('*')
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
            ->join('manager_users', 'managers.id', '=', 'manager_users.manager_id')
            ->where('manager_groups.group_type', 1)
            ->where('manager_users.user_id', '=', $id)
            ->first();
        $accountManagerName = $accountManager ? $accountManager->name : 'N/A';

        $password_visibility = (get_platform() == "mt4") ? "d-none" : "";

        // control action permition
        $auth_user = User::find(auth()->user()->id);
        $button_bottom = $btn_settings_tab = $btn_add_comment = '';
        if ($auth_user->hasDirectPermission('edit live trading account') || $auth_user->hasDirectPermission('create live trading account')) {
            $button_bottom = '<button href="#"  class="btn btn-primary float-end more-actions dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="ManageFundOrCredit' . $user->id . '">
                                </i>Funds And Credits
                            </button> 
                            <ul class="dropdown-menu" aria-labelledby="ManageFundOrCredit' . $user->id . '">                                              
                                <li>
                                    <a class="dropdown-item btn-manage-fund" href="javascript:;" data-user="' . $user->id . '" data-accountno="' . $accountNo . '" data-accountid="' . $accountId . '">
                                        Manage Funds
                                    </a>
                                </li>                                             
                                <li>
                                    <a class="dropdown-item btn-manage-credit" href="javascript:;" data-user="' . $user->id . '" data-accountno="' . $accountNo . '" data-accountid="' . $accountId . '">
                                        Manage Credit
                                    </a>
                                </li>                                               
                            </ul>

                            <button href="#"  class="btn btn-primary float-end more-actions dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="ManageGroupOrLeverage' . $user->id . '">
                                Group And Leverage
                            </button> 
                            <ul class="dropdown-menu" aria-labelledby="ManageGroupOrLeverage' . $user->id . '">                                              
                                <li>
                                    <a class="dropdown-item btn-change-group" href="javascript:;" data-user="' . $user->id . '" data-accountid="' . $accountId . '">
                                        Change Group
                                    </a>
                                </li>                                             
                                <li>
                                    <a class="dropdown-item btn-change-leverage" href="javascript:;" data-user="' . $user->id . '" data-accountid="' . $accountId . '">
                                        Change Leverage
                                    </a>
                                </li>                                               
                            </ul>
                            <button type="button" class="btn btn-primary float-end pass_show btn-show-password" data-accountno="' . $accountNo . '" data-accountid="' . $accountId . '">Check Password</button>
                            <button type="button" class="' . $account_remove_button . ' btn btn-primary float-end btn-remove-from-trader ' . $accountDisableBtn . '" data-user="' . $user->id . '" data-accountid="' . $accountId . '">Remove From Trader</button>
                            <button type="button" class="btn btn-primary float-end btn-add-as-trader ' . $accountEnableBtn . '" data-user="' . $user->id . '" data-accountid="' . $accountId . '">Add As A Trader</button>   
                            <button type="button" class="btn btn-primary float-end btn-resend-accinfo" data-accountno="' . $accountNo . '" data-accountid="' . $accountId . '">Resend Account Credentials</button>';

            // settings tab 
            $btn_settings_tab = '<li class="nav-item border-end-2 ">
                                                    <a data-id="' . $id . '" class="nav-link" id="action-tab-fill-' . $accountId . '" data-bs-toggle="tab" href="#action-fill-' . $accountId . '" role="tab" aria-controls="action-fill" aria-selected="false">Settings</a>
                                                </li>';
            // add comment button
            $btn_add_comment = '<button type="button" class="btn btn-primary float-end btn-add-comment" data-id="' . $id . '" data-name="' . $user->name . '" data-bs-toggle="modal" data-bs-target="#primary">Add Comment</button>';
        }

        $description = '<tr class="description" style="display:none">
            <td colspan="6">
                <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="rounded-0 w-75">
                                <table class="table table-responsive tbl-balance">
                                    <tr>
                                        <th>Account</th>
                                        <td>#' . $accountNo . '</td>
                                    </tr>
                                    <tr>
                                        <th>Leverage</th>
                                        <td>' . $account->leverage . '</td>
                                    </tr>
                                    <tr>
                                        <th>Display Group Name</th>
                                        <td>' . $group->group_id . '</td>
                                    </tr>
                                    <tr>
                                        <th>Account Balance</th>
                                        <td class="check-account-balance" data-accountid="' . $accountId . '">
                                            <span class="text-primary" style="cursor:pointer">check<span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justfy-content-between">
                            <div class="rounded-0 w-100">
                                <table class="table table-responsive tbl-trader-details">
                                    <tr>
                                        <th>Name</th>
                                        <td>' . $user->name . '</td>
                                    </tr>
                                    <tr>
                                        <th>KYC</th>
                                        <td>' . $check_uncheck . '</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>' . $user->phone . '</td>
                                    </tr>
                                    <tr>
                                        <th>Equity</th>
                                        <td class="check-account-balance" data-accountid="' . $accountId . '">
                                            <span class="text-primary" style="cursor:pointer">check<span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="rounded ms-1 dt-trader-img">
                                <div class="h-100">
                                    <img class="img img-fluid bg-light-primary img-trader-admin" src="' . asset("admin-assets/app-assets/images/avatars/$avatar") . ' "alt="avatar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <!-- Filled Tabs starts -->
                            <div class="col-xl-12 col-lg-12">
                                <div class=" p-0">
                                    <div class=" p-0">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs  mb-1 tab-inner-dark" id="myTab' . $accountId . '" role="tablist">
                                            <li class="nav-item">
                                                <a data-id="' . $id . '" data-accountid="' . $accountId . '" class="nav-link deposit-tab deposit-tab-fill active" id="deposit-tab-fill-' . $accountId . '" data-bs-toggle="tab" href="#deposit-fill-' . $accountId . '" role="tab" aria-controls="deposit-fill" aria-selected="true">Deposit</a>
                                            </li>
                                            <li class="nav-item">
                                                <a data-id="' . $id . '" data-accountid="' . $accountId . '" class="nav-link withdraw-tab-fill" id="withdraw-tab-fill-' . $accountId . '" data-bs-toggle="tab" href="#withdraw-fill-' . $accountId . '" role="tab" aria-controls="withdraw-fill" aria-selected="false">Withdraw</a>
                                            </li>
                                            <li class="nav-item">
                                                <a data-id="' . $id . '" data-accountid="' . $accountId . '" class="nav-link bonus-tab-fill" id="bonus-tab-fill-' . $accountId . '" data-bs-toggle="tab" href="#bonus-fill-' . $accountId . '" role="tab" aria-controls="bonus-fill" aria-selected="false">Bonus</a>
                                            </li>
                                            <li class="nav-item">
                                                <a data-id="' . $id . '" data-accountid="' . $accountId . '" class="nav-link comment-tab-fill" id="comment-tab-fill-' . $accountId . '" data-bs-toggle="tab" href="#comment-fill-' . $accountId . '" role="tab" aria-controls="comment-fill" aria-selected="false">Comments</a>
                                            </li>
                                            ' . $btn_settings_tab . '
                                            <li class="nav-item">
                                                <a data-id="' . $id . '" data-accountid="' . $accountId . '" class="nav-link trade-tab-fill" id="trade-tab-fill-' . $accountId . '" data-bs-toggle="tab" href="#trade-fill-' . $accountId . '" role="tab" aria-controls="trade-fill" aria-selected="false">Trades</a>
                                            </li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="deposit-fill-' . $accountId . '" role="tabpanel" aria-labelledby="deposit-tab-fill">
                                                <div class="table-responsive">
                                                    <table class="datatable-inner deposit table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>id</th>
                                                                <th>Amount</th>
                                                                <th>Method</th>
                                                                <th>Status</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="withdraw-fill-' . $accountId . '" role="tabpanel" aria-labelledby="withdraw-tab-fill">
                                                <div class="table-responsive">
                                                    <table class="datatable-inner withdraw table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>id</th>
                                                                <th>Amount</th>
                                                                <th>Method</th>
                                                                <th>Status</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="bonus-fill-' . $accountId . '" role="tabpanel" aria-labelledby="bonus-tab-fill">
                                                <table class="datatable-inner bonus table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>id</th>
                                                            <th>SL</th>
                                                            <th>Amount</th>
                                                            <th>Bonus Title</th>
                                                            <th>Ending Date</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="comment-fill-' . $accountId . '" role="tabpanel" aria-labelledby="comment-tab-fill">
                                                ' . $btn_add_comment . '
                                                <table class="datatable-inner comment table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>Commented Date</th>
                                                            <th>Comment</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="action-fill-' . $accountId . '" role="tabpanel" aria-labelledby="action-tab-fill">
                                                <table class="action-table-inner-dark action table m-0"  style="margin:0px !important;">
                                                    <tbody>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="block-unblock-swtich-' . $accountId . '">Unblock &frasl; Block</label>
                                                                <div class="form-check form-switch form-check-danger">
                                                                    <input type="checkbox" class="form-check-input block-unblock-swtich" id="block-unblock-swtich-' . $accountId . '" value="' . $accountId . '" ' . $block_operation . '/>
                                                                    <label class="form-check-label" for="block-unblock-swtich-' . $accountId . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <th class=" text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <div class="row">
                                                                    <div class="d-grid col-lg-6 col-md-12"></div>
                                                                    <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                                        <button type="button" data-accountid="' . $accountId . '" data-accountno="' . $accountNo . '" data-type="master-password" class="reset-password-btn btn btn-primary">Reset Password</button>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="ib-commission-switch-' . $accountId . '">IB Commission</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input ib-commission-switch" id="ib-commission-switch-' . $accountId . '" value="' . $accountId . '" ' . $commission_operation . '/>
                                                                    <label class="form-check-label" for="ib-commission-switch-' . $accountId . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <th class=" text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                            <div class="row">
                                                                <div class="d-grid col-lg-6 col-md-12"></div>
                                                                <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                                    <button type="button" data-accountno="' . $accountNo . '" data-accountid="' . $accountId . '" data-type="investor-password" class="reset-investor-password-btn btn btn-primary">Reset Investor Password</button>
                                                                </div>
                                                            </div>
                                                        </tr>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="deposit-switch-' . $accountId . '">Deposit</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input deposit-switch" id="deposit-switch-' . $accountId . '"  value="' . $accountId . '" ' . $deposit_operation . '/>
                                                                    <label class="form-check-label" for="deposit-switch-' . $accountId . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <th class=" text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <div class="row">
                                                                    <div class="d-grid col-lg-6 col-md-12"></div>
                                                                    <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                                        <button type="button" class="btn btn-primary change-master-password-btn" data-accountid="' . $accountId . '" data-bs-toggle="modal" data-bs-target="#password-change-modal"><i data-feather="trello"></i> Change Password</button>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="withdraw-switch-' . $accountId . '">Withdraw</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input withdraw-switch" id="withdraw-switch-' . $accountId . '" value="' . $accountId . '" ' . $withdraw_operation . '/>
                                                                    <label class="form-check-label" for="withdraw-switch-' . $accountId . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <th class=" text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <div class="row">
                                                                    <div class="d-grid col-lg-6 col-md-12"></div>
                                                                    <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                                        <button type="button" class="btn btn-primary change-investor-password-btn" data-accountid="' . $accountId . '" data-bs-toggle="modal" data-bs-target="#password-change-modal"><i data-feather="trello"></i> Investor Password Change</button>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="trade-fill-' . $accountId . '" role="tabpanel" aria-labelledby="trade-tab-fill">
                                                <div class="table-responsive">
                                                    <table class="datatable-inner trade table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>Ticket</th>
                                                                <th>Login</th>
                                                                <th>Symbol</th>
                                                                <th>Volume</th>
                                                                <th>Open Time</th>
                                                                <th>Close Time</th>
                                                                <th>Open Price</th>
                                                                <th>Close Price</th>
                                                                <th>Profit</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="rounded-0 w-100">
                                <table class="table table-responsive tbl-trader-details">
                                    <tr>
                                        <th>Desk Manager:</th>
                                        <td>' . $deskManagerName . '</td>
                                        <th>Account Manager:</th>
                                        <td>' . $accountManagerName . '</td>
                                    </tr>
                                    <tr>
                                        <th>Total Bonus:</th>
                                        <td>$0</td>
                                        <th>Total Trading Account:</th>
                                        <td>' . $totalTradingAccount . '</td>
                                    </tr>
                                    <tr>
                                        <th>Total Deposit:</th>
                                        <td>$' . $totalDeposit . '</td>
                                        <th>Total Withdraw:</th>
                                        <td>$' . $totalWithdraw . '</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="demo-inline-spacing w-100" >
                            ' . $button_bottom . '        
                        </div>
                    </div>
                </div>
            </td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
        </tr>';
        $data = [
            'status' => true,
            'description' => $description,
        ];
        return Response::json($data);
    }

    public function depositListDT(Request $request)
    {
        $accountId = $request->accountId;
        $dts = new DataTableService($request);
        $columns = $dts->get_columns();

        $result = InternalTransfer::where('account_id', $accountId)->where('type', 'wta');
        $count = $result->count();

        //Search if columns field has search data
        $result = $result->where(function ($q) use ($dts, $columns) {
            if ($dts->search) {
                foreach ($columns as $col) {
                    if (!empty($col['data'])) {
                        $tf = ($col['data'] == 'SL') ? 'id' : $col['data'];
                        $st = $dts->search;
                        $q->orWhere($tf, 'LIKE', '%' . $st . '%');
                    }
                }
            }
        });

        $result = $result->orderBy($dts->orderBy() == 'SL' ? 'id' : $dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();

        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $status = (strtolower($row->status) === 'p') ? '<span class="badge badge-light-warning">Pending</span>' : '<span class="badge badge-light-success">Approved</span>';
            $data[$i]['id'] = $row->id;
            $data[$i]['SL'] = $i + 1;

            $data[$i]['amount'] = $row->amount;
            $data[$i]['type'] = "Wallet To Account";
            $data[$i]['status'] = $status;
            $i++;
        }
        $res['draw'] = $dts->draw;
        $res['recordsTotal'] = $count;
        $res['recordsFiltered'] = $count;
        $res['data'] = $data;
        return Response::json($res);
    }

    public function withdrawListDT(Request $request)
    {
        $accountId = $request->accountId;
        $dts = new DataTableService($request);
        $columns = $dts->get_columns();

        $result = InternalTransfer::where('account_id', $accountId)->where('type', 'atw');
        $count = $result->count();

        //Search if columns field has search data
        $result = $result->where(function ($q) use ($dts, $columns) {
            if ($dts->search) {
                foreach ($columns as $col) {
                    if (!empty($col['data'])) {
                        $tf = ($col['data'] == 'SL') ? 'id' : $col['data'];
                        $st = $dts->search;
                        $q->orWhere($tf, 'LIKE', '%' . $st . '%');
                    }
                }
            }
        });

        $result = $result->orderBy($dts->orderBy() == 'SL' ? 'id' : $dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();

        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $status = (strtolower($row->status) === 'p') ? '<span class="badge badge-light-warning">Pending</span>' : '<span class="badge badge-light-success">Approved</span>';
            $data[$i]['id'] = $row->id;
            $data[$i]['SL'] = $i + 1;

            $data[$i]['amount'] = $row->amount;
            $data[$i]['type'] = "Account To Wallet";
            $data[$i]['status'] = $status;
            $i++;
        }
        $res['draw'] = $dts->draw;
        $res['recordsTotal'] = $count;
        $res['recordsFiltered'] = $count;
        $res['data'] = $data;
        return Response::json($res);
    }

    public function bonusListDT(Request $request)
    {
        $accountId = $request->accountId;

        $dts = new DataTableService($request);
        $columns = $dts->get_columns();

        $result = BonusUser::select('*', 'bonus_users.id as id')
            ->join('bonus_packages', 'bonus_users.bonus_package', '=', 'bonus_packages.id')
            ->join('internal_transfers', 'bonus_users.internal_transfer_id', '=', 'internal_transfers.id')
            ->join('trading_accounts', 'internal_transfers.account_id', '=', 'trading_accounts.id')
            ->where('trading_accounts.id', $accountId);

        $count = $result->count();

        //Search if columns field has search data
        $result = $result->where(function ($q) use ($dts, $columns) {
            if ($dts->search) {
                foreach ($columns as $col) {
                    if (!empty($col['data'])) {
                        $tf = ($col['data'] == 'SL' || $col['data'] == 'id') ? 'bonus_users.id' : $col['data'];
                        $st = $dts->search;
                        $q->orWhere($tf, 'LIKE', '%' . $st . '%');
                    }
                }
            }
        });

        $result = $result->orderBy($dts->orderBy() == 'SL' || $dts->orderBy() == 'id' ? 'bonus_users.id' : $dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();

        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i]['id'] = $row->id;
            $data[$i]['SL'] = $i + 1;
            $data[$i]["bonus_amount"] = '<span>&dollar; ' . $row->bonus_amount . '</span>';
            $data[$i]["pkg_name"] = $row->pkg_name;
            $data[$i]["end_date"] = date('d M, Y', strtotime($row->end_date));
            $i++;
        }
        $res['draw'] = $dts->draw;
        $res['recordsTotal'] = $count;
        $res['recordsFiltered'] = $count;
        $res['data'] = $data;
        return Response::json($res);
    }

    public function tradeListDT(Request $request)
    {
        $accountId = $request->accountId;
        $trading_account = TradingAccount::where('id', $accountId)->first();
        $dts = new DataTableService($request);
        $columns = $dts->get_columns();

        $platform = SystemConfig::select('platform_type')->first();
        if (strtolower($platform->platform_type) === 'mt5') {
            $result = Mt5Trade::where('LOGIN', $trading_account->account_number);
        } else {
            $result = DB::connection('alternate')->table('MT4_TRADES')
                ->where('LOGIN', $trading_account->account_number);
        }


        $count = $result->count();

        //Search if columns field has search data
        $result = $result->where(function ($q) use ($dts, $columns) {
            if ($dts->search) {
                foreach ($columns as $col) {
                    if (!empty($col['data'])) {
                        $tf = ($col['data'] == 'SL') ? 'MODIFY_TIME' : $col['data'];
                        $st = $dts->search;
                        $q->orWhere($tf, 'LIKE', '%' . $st . '%');
                    }
                }
            }
        });

        $result = $result->orderBy("MODIFY_TIME", "DESC")->skip($dts->start)->take($dts->length)->get();

        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i]['ticket'] = $row->TICKET;
            $data[$i]['account_no'] = $row->LOGIN;
            $data[$i]['symbol'] = $row->SYMBOL;
            $data[$i]['volume'] = round(($row->VOLUME / 100), 2);
            $data[$i]['open_time'] = $row->OPEN_TIME;
            $data[$i]['close_time'] = $row->CLOSE_TIME;
            $data[$i]['open_price'] = $row->OPEN_PRICE;
            $data[$i]['close_price'] = $row->CLOSE_PRICE;
            $data[$i]['profit'] = $row->PROFIT;
            $i++;
        }
        $res['draw'] = $_REQUEST['draw'];
        $res['recordsTotal'] = $count;
        $res['recordsFiltered'] = $count;
        $res['data'] = $data;
        return Response::json($res);
    }

    // Block unblock trader
    public function blockUnblock(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $update = TradingAccount::where('id', $request->id)->update([
                'block_status' => ($request->request_for === 'block') ? 0 : 1,
            ]);
            $account = TradingAccount::find($request->id);
            if ($request->request_for === 'block') {
                $update_message = "#" . $account->account_number . " " . "successfully Blocked";
                $success_title = 'Trader Blocked';
            } else {
                $update_message = "#" . $account->account_number . " " . "successfully Un-Blocked";
                $success_title = 'Trader Un-Blocked';
            }
            if ($update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Response::json(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->trader_id);
    }

    // commission operation
    public function IBCommissionOperation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $update = TradingAccount::where('id', $request->id)->update([
                'commission_status' => ($request->request_for === 'enable') ? 1 : 0,
            ]);
            $account = TradingAccount::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = "#" . $account->account_number . " " . "IB Commission Operation Successfully Enabled";
                $success_title = 'Commission Operation Enabled';
            } else {
                $update_message = "#" . $account->account_number . " " . "IB Commission Operation successfully Disabled";
                $success_title = 'Commission Operation Disabled';
            }
            if ($update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Response::json(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->trader_id);
    }

    // deposit operation
    public function depositOperation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $update = TradingAccount::where('id', $request->id)->update([
                'deposit_status' => ($request->request_for === 'enable') ? 1 : 0,
            ]);
            $account = TradingAccount::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = "#" . $account->account_number . " " . "Deposit operation Successfully Enabled";
                $success_title = 'Deposit Operation Enabled';
            } else {
                $update_message = "#" . $account->account_number . " " . "Deposit Operation successfully Disabled";
                $success_title = 'Deposit Operation Disabled';
            }
            if ($update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Response::json(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->trader_id);
    }

    // withdraw operation
    public function withdrawOperation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $update = TradingAccount::where('id', $request->id)->update([
                'withdraw_status' => ($request->request_for === 'enable') ? 1 : 0,
            ]);
            $account = TradingAccount::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = "#" . $account->account_number . " " . "Withdraw operation Successfully Enabled";
                $success_title = 'Withdraw Operation Enabled';
            } else {
                $update_message = "#" . $account->account_number . " " . "Withdraw Operation successfully Disabled";
                $success_title = 'Withdraw Operation Disabled';
            }
            if ($update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Response::json(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->trader_id);
    }

    // change password
    public function changePassword(Request $request)
    {
        try {
            $validation_rules = [
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
            ];

            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'statuss' => false,
                    'message' => 'Please fix this following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            $trading_account = TradingAccount::find($request->account_id);
            if (strtolower($trading_account->platform) == 'mt4') {

                $mt4_api = new MT4API();
                $data = [];
                if ($request->change_type === 'master-password') {
                    $data = array(
                        'command' => 'user_password_set',
                        'data' => array(
                            'account_id' => $trading_account->account_number,
                            'password' => $request->password,
                            'change_investor' => 0
                        ),
                    );
                }
                // change investor pasword
                if ($request->change_type === 'investor-password') {
                    $data = array(
                        'command' => 'user_password_set',
                        'data' => array(
                            'account_id' => $trading_account->account_number,
                            'password' => $request->password,
                            'change_investor' => 1
                        ),
                    );
                }
                $result = $mt4_api->execute($data, 'live');
            } else if (strtolower($trading_account->platform) == 'mt5') {
                $mt5_api = new Mt5WebApi();
                // change master password
                if ($request->change_type === 'master-password') {
                    $action = 'AccountChangePassword';
                    $data = array(
                        "Login" => (int)$trading_account->account_number,
                        "Password" => $request->password,
                    );
                }
                // change investor pasword
                if ($request->change_type === 'investor-password') {
                    $action = 'AccountChangeInvestorPassword';
                    $data = array(
                        "Login" => (int)$trading_account->account_number,
                        "Password" => $request->password,
                    );
                }

                $result = $mt5_api->execute($action, $data);
            }
            $updated = 0;
            if (isset($result['success'])) {
                if ($result['success']) {
                    $trading_account->master_password = $request->password;
                    $updated = $trading_account->save();
                }
            }
            if ($updated) {
                $user = User::find($trading_account->user_id);
                if ($request->change_type === 'investor-password') {
                    //<---client email as user id
                    activity("Change investor password")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("change investor password")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been change change investor password");
                } else {
                    //<---client email as user id
                    activity("Change master password")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("change master password")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been change change master password");
                }
                return [
                    'status' => true,
                    'traderAccountId' => $trading_account->id,
                    'op' => $request->change_type,
                    'message' => 'Password has been changed successfully'
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Server Error please try again'
                ];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    // sending master password change mail
    public function changeMasterPasswordMail(Request $request)
    {
        try {
            $trading_account = TradingAccount::find($request->trader_account_id);
            $user = User::find($trading_account->user_id);
            $email_success = EmailService::send_email('change-master-password', [
                'user_id' => $user->id,
                'master_password'    => ($trading_account) ? $trading_account->master_password : '',
                'clientAccountNo'    => ($trading_account) ? $trading_account->account_number : '',
            ]);
            return Response::json([
                'status' => true,
                'message' => 'Mail successfully sent for Password Change',
                'success_title' => 'Change password'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error',
                'success_title' => 'Change password'
            ]);
        }
    }

    // sending investor password change mail
    public function changeInvestorPasswordMail(Request $request)
    {
        try {
            $trading_account = TradingAccount::find($request->trader_account_id);
            $user = User::find($trading_account->user_id);
            $email_success = EmailService::send_email('change-investor-password', [
                'user_id' => $user->id,
                'clientInvestorPassword'    => ($trading_account) ? $trading_account->investor_password : '',
                'clientAccountNo'    => ($trading_account) ? $trading_account->account_number : '',
            ]);
            return Response::json([
                'status' => true,
                'message' => 'Mail successfully sent for Password Change',
                'success_title' => 'Change password'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error',
                'success_title' => 'Change password'
            ]);
        }
    }

    // reset password
    public function resetPassword(Request $request)
    {
        try {
            // generate random password
            $random_password = str_random(5) . '@' . random_int(11, 99);
            $trading_account = TradingAccount::find($request->account_id);
            // mt4 password reset
            if (strtolower($trading_account->platform) == 'mt4') {
                // api password reset
                $mt4_api = new MT4API();
                $data = [];
                if ($request->change_type === 'master-password') {
                    $data = array(
                        'command' => 'user_password_set',
                        'data' => array(
                            'account_id' => $trading_account->account_number,
                            'password' => $random_password,
                            'change_investor' => 0
                        ),
                    );
                }
                // change investor pasword
                if ($request->change_type === 'investor-password') {
                    $data = array(
                        'command' => 'user_password_set',
                        'data' => array(
                            'account_id' => $trading_account->account_number,
                            'password' => 'INV' . $random_password,
                            'change_investor' => 1
                        ),
                    );
                }
                $result = $mt4_api->execute($data, 'live');
            } else if (strtolower($trading_account->platform) == 'mt5') {
                $mt5_api = new Mt5WebApi();
                // change master password
                if ($request->change_type === 'master-password') {
                    $action = 'AccountChangePassword';
                    $data = array(
                        "Login" => (int)$trading_account->account_number,
                        "Password" => $random_password,
                    );
                }
                // change investor pasword
                if ($request->change_type === 'investor-password') {
                    $action = 'AccountChangeInvestorPassword';
                    $data = array(
                        "Login" => (int)$trading_account->account_number,
                        "Password" => $random_password,
                    );
                }

                $result = $mt5_api->execute($action, $data);
            }
            $updated = 0;
            if (isset($result['success'])) {
                if ($result['success']) {
                    if ($request->change_type === 'master-password') {
                        $trading_account->master_password = $random_password;
                    }
                    if ($request->change_type === 'investor-password') {
                        $trading_account->investor_password = $random_password;
                    }
                    $updated = $trading_account->save();
                }
            }
            if ($updated) {
                $user = User::find($trading_account->user_id);
                if ($request->change_type === 'investor-password') {
                    //<---client email as user id
                    activity("reset investor password")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("reset investor password")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been reset investor password");
                    $email_success = EmailService::send_email('reset-investor-password', [
                        'user_id' => $user->id,
                        'clientInvestorPassword'    => $random_password,
                        'clientAccountNo'    => ($trading_account) ? $trading_account->account_number : '',
                    ]);
                } else {
                    //<---client email as user id
                    activity("reset master password")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("reset master password")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been reset master password");
                    $email_success = EmailService::send_email('reset-master-password', [
                        'user_id' => $user->id,
                        'master_password'    => $random_password,
                        'clientAccountNo'    => ($trading_account) ? $trading_account->account_number : '',
                    ]);
                }
                return [
                    'status' => true,
                    'message' => 'Password has been reseted successfully'
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Server Error please try again'
                ];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    // comments reports
    // comments tab datatable
    // -------------------------------------------------------------------------
    public function traderComment(Request $request)
    {
        $id = $request->userId;
        $dts = new DataTableService($request);

        $result = Comment::where('user_id', $id)->select();

        $count = $result->count();

        $result = $result->orderBy($dts->orderBy() == 'SL' ? 'id' : $dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();

        $data = [];
        $i = 0;
        foreach ($result as $value) {
            $user = User::find($id);
            $data[$i]["commented_date"]    = date('d F y, h:i A', strtotime($value->created_at));
            $data[$i]["comment"] = $value->comment;
            $data[$i]["actions"] = '
            <div class="btn-group">
                <button class="btn btn-flat-primary dropdown-toggle comment-actions" type="button" id="dropdownMenuButton100" data-bs-toggle="dropdown" aria-expanded="false">
                <i data-feather="more-vertical"></i>
                <i data-feather="edit"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton100">
                    <a class="dropdown-item text-success btn-update-comment" href="javascript:void(0)" data-id="' . $id . '" data-name="' . $user->name . '" data-commentid="' . $value->id . '" data-comment="' . $value->comment . '" data-bs-toggle="modal" data-bs-target="#comment-edit"> <i data-feather="edit"></i> Edit</a>
                    <a class="dropdown-item text-danger btn-delete-comment" href="#" data-id="' . $value->id . '"><i data-feather="trash"></i> Delete</a>
                </div>
            </div>';
            $i++;
        }
        $res['draw'] = $dts->draw;
        $res['recordsTotal'] = $count;
        $res['recordsFiltered'] = $count;
        $res['data'] = $data;
        return Response::json($res);
    }


    // add new comment
    // comment tab
    // -------------------------------------------------------------------------
    public function traderAddComment(Request $request)
    {
        $rules = [
            'comment' => 'required|min:5',
            'trader_id' => 'required',
        ];
        $request->validate($rules);
        $newComment = Comment::create([
            'user_id' => $request->trader_id,
            'type' => 'Trader',
            'comment' => $request->comment,
            'commented_by' => Auth::id(),
        ]);
        if ($newComment) {
            // insert activity-----------------
            activity("add new comment")
                ->causedBy(auth()->user()->id)
                ->withProperties($request->all())
                ->event("comment add")
                ->log("The IP address " . request()->ip() . " has been add new comment");
            // end activity log-----------------
            return [
                'status' => 'success',
                'msg' => 'Comment Created Successfully'
            ];
        } else {
            return [
                'status' => 'failed',
                'msg' => 'Unable To Create New Comment'
            ];
        }
    }

    // update comment
    // comment tab
    // -------------------------------------------------------------------------
    public function traderUpdateComment(Request $request)
    {
        $rules = [
            'comment' => 'required|min:5',
            'trader_id' => 'required',
        ];
        $request->validate($rules);
        $update = Comment::where('id', $request->comment_id)->Update([
            'comment' => $request->comment,
            'commented_by' => Auth::id(),
        ]);
        if ($update) {
            return [
                'status' => 'success',
                'msg' => 'Comment Updated Successfully'
            ];
        } else {
            return [
                'status' => 'failed',
                'msg' => 'Unable To Update Comment'
            ];
        }
    }

    // delete exist comment
    public function traderDeleteComment(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $delete = Comment::where('id', $request->id)->delete();
            if ($delete) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => 'Comment Deleted Successfully!']);
                } else {
                    return Response::json(['status' => false, 'message' => 'Unable To Delete Comment']);
                }
            }
        }
        return Response::json($request->trader_id);
    }

    // change group
    public function traderChangeGroup(Request $request)
    {
        try {
            $rules = [
                'group_id' => 'required'
            ];
            $request->validate($rules);
            $trading_account = TradingAccount::find($request->trader_account_id);
            $trading_group = ClientGroup::find($request->group_id);
            $users = User::where('users.id', $trading_account->user_id)
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')->first();
            if ($users == "") {
                return Response::json([
                    'status' => false,
                    'message' => 'Got a server error, Invalid request found'
                ]);
            }
            // mt4 change group-----------
            if (strtolower($trading_account->platform) == 'mt4') {
                $mt4api = new MT4API();
                // $data = array(
                //     'command' => 'user_update',
                //     'data' => array(
                //         'account_id' => $trading_account->account_number,
                //         'group' => $trading_group->group_name
                //     ),
                // );
                $data = array(
                    'command' => 'AccountUpdate',
                    'data' => array(
                        "Login" => (int)$trading_account->account_number,
                        "Group" => $trading_group->group_name,
                    ),
                );
                $result = $mt4api->execute($data);
                $updated = 0;
                if (isset($result['success']) || isset($result['status'])) {
                    // update trading account table
                    $updated = TradingAccount::where('id', $request->trader_account_id)->update([
                        'group_id' => $request->group_id,
                        'client_type' => $trading_group->account_category,
                        'platform' => $trading_group->server,
                    ]);
                }
                if ($updated) {
                    //<---client email as user id
                    activity("Change group")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($trading_account)
                        ->event("change group")
                        ->performedOn($users)
                        ->log("The IP address " . request()->ip() . " has been change change group");
                    // end activity log
                    $status_data['status'] = true;
                    $status_data['message'] = 'Group has been changed successfully.';
                    return Response::json($status_data);
                }
                $status_data['status'] = false;
                $status_data['message'] = 'Network error please try again later.';
                return Response::json($status_data);
            } elseif (strtolower($trading_account->platform) == 'mt5') {
                $mt5_api = new Mt5WebApi();
                $action = 'AccountUpdate';
                $data = array(
                    "Login" => (int)$trading_account->account_number,
                    "Group" => $trading_group->group_name,
                );
                $result = $mt5_api->execute($action, $data);
                $updated = 0;
                if (isset($result['success'])) {
                    if ($result['success']) {
                        $trading_account->group_id = $request->group_id;
                        $trading_account->client_type = $trading_group->account_category;
                        $trading_account->platform = $trading_group->server;
                        $updated = $trading_account->save();
                    }
                }
                if ($updated) {
                    //<---client email as user id
                    activity("Change group")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($trading_account)
                        ->event("change group")
                        ->performedOn($users)
                        ->log("The IP address " . request()->ip() . " has been change change group");
                    // end activity log
                    $status_data['status'] = true;
                    $status_data['message'] = 'Group has been changed successfully.';
                    return Response::json($status_data);
                }
                $status_data['status'] = false;
                $status_data['message'] = 'Network error please try again later.';
                return Response::json($status_data);
            }
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error'
            ]);
        }
    }

    // sending account credentials mail
    public function resendAccountCredentialsMail(Request $request)
    {
        $trading_account = TradingAccount::find($request->account_id);
        $user = User::find($trading_account->user_id);
        // password log
        $log_password = Log::where('user_id', $user->id)->first();
        // sending mail
        $mail_status = EmailService::send_email('resend-account-credentials', [
            'user_id' => $user->id,
            'clientAccountNo'    => ($trading_account) ? $trading_account->account_number : '',
            'account_number'    => ($trading_account) ? $trading_account->account_number : '',
            'clientPhonePassword'    => ($trading_account) ? $trading_account->phone_password : '',
            'phone_password'    => ($trading_account) ? $trading_account->phone_password : '',
            'clientInvestorPassword'    => ($trading_account) ? $trading_account->investor_password : '',
            'investor_password'    => ($trading_account) ? $trading_account->investor_password : '',
            'clientMasterPassword'    => ($trading_account) ? $trading_account->master_password : '',
            'master_password'    => ($trading_account) ? $trading_account->master_password : '',
            'new_password' => ($log_password) ? decrypt($log_password->password) : '',
            'admin' => ucwords(auth()->user()->name)
        ]);
        if ($mail_status) {
            return Response::json([
                'status' => true,
                'message' => 'Mail successfully sent for Account Credentials',
                'success_title' => 'Account Credentials'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Mail sending failed, Please try again later!',
            'success_title' => 'Account Credentials'
        ]);
    }

    // get leverage data
    public function getLeverageData(Request $request)
    {
        $account = TradingAccount::find($request->accountId);
        $groupLiverage = json_decode(ClientGroup::find($account->group_id)->leverage);
        $liverageOptions = "";
        foreach ($groupLiverage as $liverageValue) {
            if ($account->leverage == $liverageValue)
                $liverageOptions .= "<option value=\"$liverageValue\" selected>$liverageValue</option>";
            else
                $liverageOptions .= "<option value=\"$liverageValue\">$liverageValue</option>";
        }
        return $liverageOptions;
    }

    // change leverage
    public function traderChangeLeverage(Request $request)
    {
        // mt4 leverage-----------
        $trading_account = TradingAccount::find($request->trader_account_id);
        $trading_group = ClientGroup::find($trading_account->group_id);
        $users = User::where('users.id', $trading_account->user_id)->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')->first();
        if (strtolower($trading_account->platform) == 'mt4') {
            $mt4api = new MT4API();
            $data = array(
                'command' => 'user_update',
                'data' => array(
                    'account_id' => $trading_account->account_number,
                    'leverage' => $request->leverage
                ),
            );
            $pass_result = $mt4api->execute($data);

            $updated = 0;
            if (isset($pass_result['success'])) {
                if ($pass_result['success']) {
                    $trading_account->leverage = $request->leverage;
                    $updated = $trading_account->save();
                }
            }
            if ($updated) {
                $status_data['status'] = true;
                $status_data['message'] = 'Leverage has been changed successfully.';
                return Response::json($status_data);
            }
            $status_data['status'] = false;
            $status_data['message'] = 'Network error please try again later.';
            return Response::json($status_data);
        }
        // mt5 leverage--------------- 
        else if (strtolower($trading_account->platform) == 'mt5') {
            $mt5_api = new Mt5WebApi();
            $action = 'AccountUpdate';
            $data = array(
                "Login" => $trading_account->account_number,
                "Leverage" => (int)$request->leverage,
                // "Group" => $trading_group->group_name,
                // 'MainPassword' => $trading_account->master_password,
                // 'InvestPassword' => $trading_account->investor_password,
                // 'PhonePassword' => $trading_account->phone_password,
                // "Phone" => (isset($users->phone) && $users->phone != null) ? $users->phone : '',
                // "Name" => (isset($users->name) && $users->name != null) ? $users->name : '',
                // "Country" => UserService::get_country($users->id),
                // "City" => (isset($users->city) && $users->city != null) ? $users->city : '',
                // "State" => (isset($users->state) && $users->state != null) ? $users->state : '',
                // "ZipCode" => (isset($users->zip_close) && $users->zip_close != null) ? $users->zip_close : '',
                // "Address" => (isset($users->address) && $users->address != null) ? $users->address : ''
            );
            $pass_result = $mt5_api->execute($action, $data);
            $updated = 0;
            if (isset($pass_result['success'])) {
                if ($pass_result['success']) {
                    $trading_account->leverage = $request->leverage;
                    $updated = $trading_account->save();
                }
            }
            if ($updated) {
                $status_data['status'] = true;
                $status_data['message'] = 'Leverage has been changed successfully.';
                return Response::json($status_data);
            }
            $status_data['status'] = false;
            $status_data['message'] = 'Network error please try again later.';
            return Response::json($status_data);
        }
    }

    // remove from trader
    public function removeFromTrader(Request $request)
    {
        // return "remove form trader";
        $update = TradingAccount::where('id', $request->accountId)->Update([
            'account_status' => 0
        ]);
        if ($update) {
            return [
                'status' => true,
                'message' => 'Remove From Trader Operation Successfull!'
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Remove From Trader Operation Failed!'
            ];
        }
    }
    // add as a trader
    public function addAsTrader(Request $request)
    {
        return "add as trader";
        $update = TradingAccount::where('id', $request->accountId)->Update([
            'account_status' => 1
        ]);
        if ($update) {
            return [
                'status' => true,
                'message' => 'Add As A Trader Operation Successfull!'
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Add As A Trader Operation Failed!'
            ];
        }
    }

    // Check Account Balance
    public function checkAccountBalance(Request $request)
    {
        $trading_account = TradingAccount::find($request->accountId);
        $response['success'] = false;
        if (strtolower($trading_account->platform) == 'mt4') {
            $mt4api = new MT4API();
            $data = array(
                'command' => 'user_data_get',
                'data' => array('account_id' => $trading_account->account_number),
            );

            $result = $mt4api->execute($data, $trading_account->client_type);

            if ($result["success"]) {
                $result1 = $result['data'];
                $response['success'] = true;
                $response['credit'] = 0;
                $response['equity'] = $result1['equity'];
                $response['balance'] = $result1['balance'];
                $response['free_margin'] = 0;
                $response['amount']  = ($request->search === 'balance') ? $result1['balance'] : $result1['equity'];
                return Response::json($response);
            } else {
                return Response::json([
                    'success' => false,
                    'message' => $result['info']['message']
                ]);
            }
        }
        // for mt5 api-------------------
        else {
            $mt5_api = new Mt5WebApi();
            $action = 'AccountGetMargin';

            $data = array(
                "Login" => $trading_account->account_number
            );
            $result = $mt5_api->execute($action, $data);
            $mt5_api->Disconnect();

            if (isset($result['success'])) {
                if ($result['success']) {
                    $response['success'] = true;
                    $response['credit'] = $result['data']['Credit'];
                    $response['equity'] = $result['data']['Equity'];
                    $response['balance'] = $result['data']['Balance'];
                    $response['free_margin'] = isset($result['data']['MarginFree']) ? $result['data']['MarginFree'] : 0;
                    $response['amount']  = ($request->search === 'balance') ? $result['data']['Balance'] : $result['data']['Equity'];
                    return Response::json($response);
                } else if (isset($result['error'])) {
                    $response['message'] = $result['error']['Description'];
                } else {
                    $response = [
                        'success' => false,
                        'message' => $result['message']
                    ];
                }
            }
            return Response::json($response);
        }
    }
    //show password in modal
    public function showPass(Request $request)
    {
        $pass_type = $request->pass_type;

        $password = TradingAccount::select($pass_type)->where('id', $request->id)->first();
        $status = 1;
        if ($password->$pass_type == '') {
            $status = 0;
        }
        $data = [
            'status' => $status,
            'password' => $password->$pass_type,
        ];
        return $data;
    }
    //reset password
    public function passReset(Request $request)
    {
        $pass = $request->pass_type;

        if ($pass === 'phone_password') {
            $generate_pass = 'P' . mt_rand(10000, 99999);
        }
        if ($pass === 'master_password') {
            $generate_pass = 'M' . mt_rand(10000, 99999);
        }
        if ($pass === 'investor_password') {
            $generate_pass = 'I' . mt_rand(10000, 99999);
        }

        $id = $request->account_id;
        $trading_account = TradingAccount::find($id);

        if (strtolower($trading_account->platform) == 'mt4') {
            $mt4api = new MT4API();

            // user or master password
            if ($pass === 'master_password') {
                $data = array(
                    'command' => 'user_password_set',
                    'data' => array(
                        'account_id' => (int)$trading_account->account_number,
                        'password' => $generate_pass,
                        'change_investor' => 0
                    ),
                );
            }

            $result = $mt4api->execute($data);
            if (isset($result['success']) && ($result['success'])) {
                EmailService::send_email('change-master-password', [
                    'user_id' => $trading_account->user_id,
                    'clientAccountNo' => $trading_account->account_number,
                    'clientInvestorPassword' => $generate_pass,
                    'by' => auth()->user()->email
                ]);
                TradingAccount::where('id', $id)->update([$pass => $generate_pass]);
                return Response::json([
                    'status' => true,
                    'message' => 'Transaction Password Reset Successfully',
                ]);
            }
        } else {

            $mt5_api = new Mt5WebApi();
            $action = "";
            $data = [];

            // change investor pasword
            if ($pass === 'investor_password') {
                $action = 'AccountChangeInvestorPassword';
                $data = array(
                    "Login" => (int)$trading_account->account_number,
                    'Password' => "$generate_pass"
                );
            }

            // change master pasword
            if ($pass === 'master_password') {
                $action = 'AccountChangePassword';
                $data = array(
                    "Login" => (int)$trading_account->account_number,
                    'Password' => "$generate_pass"
                );
            }

            $result = $mt5_api->execute($action, $data);

            if (isset($result['success']) && ($result['success'])) {
                TradingAccount::where('id', $id)->update([$pass => $generate_pass]);
                // sending mail for invetor password
                if ($pass === 'investor_password') {
                    EmailService::send_email('change-investor-password', [
                        'user_id' => $trading_account->user_id,
                        'clientAccountNo' => $trading_account->account_number,
                        'clientInvestorPassword' => $generate_pass,
                        'by' => auth()->user()->email
                    ]);
                } else {
                    EmailService::send_email('change-master-password', [
                        'user_id' => $trading_account->user_id,
                        'clientAccountNo' => $trading_account->account_number,
                        'clientInvestorPassword' => $generate_pass,
                        'by' => auth()->user()->email
                    ]);
                }
                $status_data['status'] = true;
                $status_data['message'] = 'Password has been changed successfully';
                return Response::json($status_data);
            }
        }
        return Response::json([
            'status' => false,
            'message' => 'Failed  To Reset Transaction Password',
        ]);
    }

    public function showPassData(Request $request)
    {
        // $id = TradingAccount::find('id', $request->passId);
        $id = TradingAccount::join('client_groups', 'client_groups.id', '=', 'trading_accounts.group_id')
            ->where('trading_accounts.id', $request->passId)
            ->select('trading_accounts.account_number', 'trading_accounts.leverage', 'client_groups.group_name', 'trading_accounts.master_password', 'trading_accounts.investor_password')
            ->first();
        return $id;
    }
}
