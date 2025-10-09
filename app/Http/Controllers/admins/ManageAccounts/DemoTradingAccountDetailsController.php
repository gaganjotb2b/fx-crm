<?php

namespace App\Http\Controllers\admins\ManageAccounts;

use App\Http\Controllers\Controller;
use App\Mail\ChangePassword;
use App\Mail\ResendAccountCredential;
use App\Mail\ResetPassword;
use App\Models\admin\AdminUser;
use App\Models\admin\SystemConfig;
use App\Models\BonusUser;
use App\Models\Category;
use App\Models\ClientGroup;
use App\Models\Comment;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\IB;
use App\Models\KycVerification;
use App\Models\PasswordReset;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\DataTableService;
use App\Services\Mt5WebApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class DemoTradingAccountDetailsController extends Controller
{
    public function __construct()
    {
        // module permission
        if (request()->is('/admin/trading-account-details-demo')) {
            $this->middleware(AllFunctionService::access('manage_accounts', 'admin'));
            $this->middleware(AllFunctionService::access('live_trading_account', 'admin'));
        } elseif (request()->is('/admin/trading-account-details-live')) {
            $this->middleware(AllFunctionService::access('manage_accounts', 'admin'));
            $this->middleware(AllFunctionService::access('demo_trading_account', 'admin'));
        }
    }
    public function index(Request $request)
    {
        $op = $request->op;
        if ($op == 'trader-data-table') {
            return $this->traderListDT($request);
        }
        if ($op == 'trader-description') {
            return $this->traderListDescription($request);
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
        if ($op == 'change-group') {
            return $this->traderChangeGroup($request);
        }
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
        if ($op == 'change-leverage') {
            return $this->traderChangeLeverage($request);
        }
        if ($op == 'get-data-for-live-account') {
            return $this->getDataForLiveAccount($request);
        }
        if ($op == 'create-live-account') {
            return $this->createLiveAccount($request);
        }
        $demoGroups = ClientGroup::select('id', 'group_name')->where('account_category', 'demo')->get();

        return view('admins.manage_accounts.demo_trading_account_details', compact('demoGroups'));
    }

    private function traderListDT($request)
    {
        try {
            $dts = new DataTableService($request);
        $columns = $dts->get_columns();

        $result = TradingAccount::select('*', 'trading_accounts.id as id')
            ->join('users', 'trading_accounts.user_id', '=', 'users.id')
            ->join('client_groups', 'trading_accounts.group_id', '=', 'client_groups.id')
            ->where('client_groups.account_category', '=', 'demo');

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

        //----------------------------------------------------------------------------------------------
        //Filter Start
        //----------------------------------------------------------------------------------------------
        //Filter by trader info like name,email,phone
        if ($request->info != "") {
            $result = $result->where('users.name', $request->info)->orwhere('users.email', $request->info)->orwhere('users.phone', $request->info);
        }

        //filter by trading account
        if ($request->trading_acc != "") {
            $result = $result->where('account_number', $request->trading_acc);
        }
        
        $count = $result->count();
        $result = $result->orderBy(($dts->orderBy() == 'id') ? "trading_accounts.{$dts->orderBy()}" : $dts->orderBy(), $dts->orderDir)->skip($dts->start)->take($dts->length)->get();

        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i]['id'] = $row['trading_accounts.id'];
            $data[$i]['name'] = '<a href="#" data-userid=' . $row->user_id . ' data-accountid=' . $row->id . ' class="dt-description d-flex justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' . $row->account_number . '</span></a>';
            $data[$i]['account_category'] = $row->account_category;
            $data[$i]['group_name'] = $row->group_name;
            $data[$i]['email'] = $row->email;
            $data[$i]['server'] = $row->server;
            $i++;
        }
        return Response::json([
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data
        ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    private function traderListDescription($request)
    {
        $id = $request->userId;
        $accountId = $request->accountId;
        $user = User::find($id);
        $user_descriptions = UserDescription::where('user_id', $user->id)->first(); //<---user description
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png'; //<----avatar url
        }
        $account = TradingAccount::find($accountId);
        $accountNo = TradingAccount::find($accountId)->account_number;
        $group = ClientGroup::find($account->group_id);
        $country = Country::find($user->user_description->country_id)->name;

        $accountDisableBtn = $account->account_status == 0 ? 'd-none' : '';
        $accountEnableBtn = $account->account_status == 1 ? 'd-none' : '';
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

        $description = '<tr class="description" style="display:none">
            <td colspan="5">
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
                                        <th>Country</th>
                                        <td>' . $country . '</td>
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
                                        <ul class="nav nav-tabs  mb-1 tab-inner-dark" id="myTab' . $user->id . '" role="tablist">
                                            <li class="nav-item border-0">
                                                <a data-id="' . $id . '" class="nav-link comment-tab comment-tab-fill active" id="comment-tab-fill-' . $user->id . '" data-bs-toggle="tab" href="#comment-fill-' . $user->id . '" role="tab" aria-controls="comment-fill" aria-selected="true">Comments</a>
                                            </li>
                                            <button type="button" class="ms-1 btn btn-sm btn-primary btn-add-comment" data-id="' . $id . '" data-name="' . $user->name . '" data-bs-toggle="modal" data-bs-target="#primary">Add Comment</button>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="comment-fill-' . $user->id . '" role="tabpanel" aria-labelledby="comment-tab-fill">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">

                        <div class="demo-inline-spacing">
                            <button type="button" class="btn btn-primary float-end btn-change-group" data-user="' . $user->id . '" data-accountid="' . $accountId . '">Change Group</button>
                            <button type="button" class="btn btn-primary float-end btn-resend-accinfo" data-accountno="' . $accountNo . '" data-accountid="' . $accountId . '">Resend Account Credentials</button>
                            <!-- 
                            <button type="button" class="btn btn-primary float-end btn-remove-from-trader ' . $accountDisableBtn . '" data-user="' . $user->id . '" data-accountid="' . $accountId . '">Remove From Trader</button>

                            <button type="button" class="btn btn-primary float-end btn-add-as-trader ' . $accountEnableBtn . '" data-user="' . $user->id . '" data-accountid="' . $accountId . '">Add As A Trader</button>
                            -->
                            <button type="button" class="btn btn-primary float-end btn-change-leverage" data-user="' . $user->id . '" data-accountid="' . $accountId . '">Change Leverage</button>
                            <button type="button" class="btn btn-primary float-end btn-create-live-account" data-user="' . $user->id . '" data-accountid="' . $accountId . '">Create Live Account</button>
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
            $data[$i]["date"]    = date('d F y, h:i A', strtotime($value->created_at));
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
        return Response::json([
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data
        ]);
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
        $rules = [
            'group_id' => 'required'
        ];
        $request->validate($rules);
        // $update = TradingAccount::where('id', $request->trader_account_id)->Update([
        //     'group_id' => $request->group_name
        // ]);
        // if ($update) {
        //     return [
        //         'status' => 'success',
        //         'msg' => 'Group Changed Successfully'
        //     ];
        // } else {
        //     return [
        //         'status' => 'failed',
        //         'msg' => 'Unable To Change Group'
        //     ];
        // }

        // mt4 leverage-----------
        $trading_account = TradingAccount::find($request->trader_account_id);
        $trading_group = ClientGroup::find($request->group_id);
        if (strtolower($trading_account->platform) == 'mt4') {


            // $data = array(
            //     'command' => 'user_update',
            //     'data' => array(
            //         'account_id' => $mt4acc,
            //         'leverage' => $leverage
            //     ),
            // );
            // $pass_result = $mt4api->execute($data);

            // if (isset($pass_result['success'])) {
            //     if ($pass_result['success']) {
            //         $dbObj->update("UPDATE hb_ac SET leverage='$leverage' WHERE subcode='$subcode' AND cusername='$mt4acc'");
            //         $msg = lang('Leverage has been changed successfully.');
            //         $error = 2;
            //     } else {
            //         $error = 1;
            //         $msg = lang('Server Error please try again');
            //     }
            // } else {
            //     $error = 1;
            //     $msg = lang('Server Error please try again');
            // }
        }
        // mt5 leverage--------------- 
        else if (strtolower($trading_account->platform) == 'mt5') {
            $mt5_api = new Mt5WebApi();
            $action = 'AccountUpdate';
            $data = array(
                "Login" => $trading_account->account_number,
                "Group" => $trading_group->group_name,
            );
            $pass_result = $mt5_api->execute($action, $data);
            $updated = 0;
            if (isset($pass_result['success'])) {
                if ($pass_result['success']) {
                    $trading_account->group_id = $request->group_id;
                    $trading_account->client_type = $trading_group->account_category;
                    $trading_account->platform = $trading_group->server;
                    $updated = $trading_account->save();
                }
            }
            if ($updated) {
                $status_data['status'] = true;
                $status_data['message'] = 'Group has been changed successfully.';
                return Response::json($status_data);
            }
            $status_data['status'] = false;
            $status_data['message'] = 'Network error please try again later.';
            return Response::json($status_data);
        }
    }

    // sending account credentials mail
    public function resendAccountCredentialsMail(Request $request)
    {
        $trading_account = TradingAccount::find($request->account_id);
        $user = User::find($trading_account->user_id);
        $compay_info = SystemConfig::select('support_email')->first();
        $support_email = ($compay_info) ? $compay_info->support_email : default_support_email();
        $email_data = [
            'clientName'    => ($user) ? $user->name : '',
            'clientEmail'    => ($user) ? $user->email : '',
            'clientAccountNo'    => ($trading_account) ? $trading_account->account_number : '',
            'clientPhonePassword'    => ($trading_account) ? $trading_account->phone_password : '',
            'clientInvestorPassword'    => ($trading_account) ? $trading_account->investor_password : '',
            'clientMasterPassword'    => ($trading_account) ? $trading_account->master_password : '',
            'companyName'   => $compay_info->com_name,
            'website'       => $compay_info->com_website,
            'accountActivationLink' => route('login'),
            'loginUrl' => route('login'),
            'emailSupport'  => $support_email,
            'phone1'        => $compay_info->com_contact_no,
            'emailCommon'   => $compay_info->com_email,
            'authority'     => $compay_info->com_authority,
            'license'       => $compay_info->com_license,
            'copy_right'       => $compay_info->copyright,
        ];
        if (Mail::to($user->email)->send(new ResendAccountCredential($email_data))) {
            return Response::json([
                'status' => true,
                'message' => 'Mail successfully sent for Account Credentials',
                'success_title' => 'Account Credentials'
            ]);
        } else {
            return Response::json(['status' => false, 'message' => 'Mail sending failed, Please try again later!', 'success_title' => 'Account Credentials']);
        }
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
        if (strtolower($trading_account->platform) == 'mt4') {
            // $data = array(
            //     'command' => 'user_update',
            //     'data' => array(
            //         'account_id' => $mt4acc,
            //         'leverage' => $leverage
            //     ),
            // );
            // $pass_result = $mt4api->execute($data);

            // if (isset($pass_result['success'])) {
            //     if ($pass_result['success']) {
            //         $dbObj->update("UPDATE hb_ac SET leverage='$leverage' WHERE subcode='$subcode' AND cusername='$mt4acc'");
            //         $msg = lang('Leverage has been changed successfully.');
            //         $error = 2;
            //     } else {
            //         $error = 1;
            //         $msg = lang('Server Error please try again');
            //     }
            // } else {
            //     $error = 1;
            //     $msg = lang('Server Error please try again');
            // }
        }
        // mt5 leverage--------------- 
        else if (strtolower($trading_account->platform) == 'mt5') {
            $mt5_api = new Mt5WebApi();
            $action = 'AccountUpdate';
            $data = array(
                "Login" => $trading_account->account_number,
                "Leverage" => $request->leverage,
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

    public function getDataForLiveAccount(Request $request)
    {
        if ($request->type == 'group-data') {

            $groups = ClientGroup::where('server', $request->platform)->where('account_category', 'live')->get();
            $groupOptions = "<option value='' disabled selected>Choose Group</option>";
            foreach ($groups as $group) {

                $groupOptions .= "<option value=\"$group->id\">$group->group_id</option>";
            }
            return $groupOptions;
        }
        if ($request->type == 'leverage-data') {
            $groupLiverage = json_decode(ClientGroup::find($request->group)->leverage);
            $liverageOptions = "";
            foreach ($groupLiverage as $liverageValue) {
                $liverageOptions .= "<option value=\"$liverageValue\">$liverageValue</option>";
            }
            return $liverageOptions;
        }
    }

    // creating live account
    public function createLiveAccount(Request $request)
    {
        // mt4 leverage-----------
        $trading_account = TradingAccount::find($request->trader_account_id);
        $user = User::find($trading_account->user_id);
        $group = ClientGroup::find($request->group_id);
        $country = Country::find($user->user_description->country_id)->name;

        if (strtolower($request->platform) == 'mt4') {
            // $data = array(
            //     'command' => 'user_update',
            //     'data' => array(
            //         'account_id' => $mt4acc,
            //         'leverage' => $leverage
            //     ),
            // );
            // $pass_result = $mt4api->execute($data);

            // if (isset($pass_result['success'])) {
            //     if ($pass_result['success']) {
            //         $dbObj->update("UPDATE hb_ac SET leverage='$leverage' WHERE subcode='$subcode' AND cusername='$mt4acc'");
            //         $msg = lang('Leverage has been changed successfully.');
            //         $error = 2;
            //     } else {
            //         $error = 1;
            //         $msg = lang('Server Error please try again');
            //     }
            // } else {
            //     $error = 1;
            //     $msg = lang('Server Error please try again');
            // }
        }
        // mt5 leverage--------------- 
        else if (strtolower($request->platform) == 'mt5') {
            $mt5_api = new Mt5WebApi();
            $action = 'AccountCreate';
            $data = array(
                "Login"             => rand(999, 9999) . date('hms'),
                "Name"                 => $user->name,
                "Email"             => $user->email,
                "Group"             => $group->group_name,
                "Leverage"             => $request->leverage,
                "Comment"             => "",
                "Phone"             => $user->phone,
                "Country"             => $country,
                "City"                 => $user->user_description->city,
                "State"             => $user->user_description->state,
                "ZipCode"             => $user->user_description->zip_code,
                "Address"             => $user->user_description->address,
                'Password'             => $trading_account->master_password,
            );
            $pass_result = $mt5_api->execute($action, $data);
            $new_trading_account = 0;
            if (isset($pass_result['success'])) {
                if ($pass_result['success']) {
                    $new_trading_account = TradingAccount::create([
                        'user_id' => $user->id,
                        'account_number' => $pass_result['data']['Login'],
                        'platform' => $group->server,
                        'group_id' => $group->id,
                        'leverage' => $pass_result['data']['Leverage'],
                        'client_type' => $group->account_category,
                        'master_password' => $trading_account->master_password,
                        'investor_password' => $trading_account->investor_password,
                    ]);
                }
            }
            if ($new_trading_account) {
                $status_data['status'] = true;
                $status_data['accountId'] = $new_trading_account->id;
                $status_data['message'] = 'New Accout Created successfully.';
                return Response::json($status_data);
            }
            $status_data['status'] = false;
            $status_data['message'] = 'Network error please try again later.';
            return Response::json($status_data);
        }
    }
}
