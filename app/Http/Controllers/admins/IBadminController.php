<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\IB;
use App\Models\Deposit;
use App\Models\Withdraw;
use App\Models\Category;
use App\Models\Comment;
use App\Models\KycIdType;
use App\Models\KycVerification;
use App\Models\WalletUpDown;
use App\Models\admin\BalanceTransfer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetPassword;
use App\Mail\ChangeTransactionPin;
use App\Mail\UpdateProfile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Models\admin\SystemConfig;
use App\Models\ClientGroup;
use App\Models\Country;
use App\Models\ExternalFundTransfers;
use App\Models\FinanceOp;
use App\Models\IbGroup;
use App\Models\IbIncome;
use App\Models\Log;
use App\Models\ManagerUser;
use App\Models\Traders\SocialLink;
use App\Models\TradingAccount;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\balance\BalanceSheetService;
use App\Services\BalanceService;
use App\Services\CombinedService;
use App\Services\IbService;
use App\Services\systems\VersionControllService;
use App\Services\EmailService;

class IBadminController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:ib admin"]);
        $this->middleware(["role:ib management"]);
        // system module control
        $this->middleware(AllFunctionService::access('ib_management', 'admin'));
        $this->middleware(AllFunctionService::access('ib_admin', 'admin'));
    }
    public function ibAdminReport()
    {
        $countries = Country::all();
        $ib_group = IbGroup::all();
        $category = Category::all();
        $countries = Country::all();
        $crmVarsion = VersionControllService::check_version();
        return view(
            'admins.ib-management.ib-admin-report',
            [
                'countries' => $countries,
                'ib_group' => $ib_group,
                'countries' => $countries,
                'varsion' => $crmVarsion,
                'category' => $category
            ]
        );
    }
    //ib admin datatable ajax process
    public function ibAdminReportProcess(Request $request)
    {
        try {
            $date_from = date($request->date_from);
            $date_to   = date($request->date_to);
            // sorting column
            $columns = ['users.name', 'users.email', 'users.phone', 'countries.name', 'ib_groups.group_name', 'users.created_at', 'users.kyc_status', 'users.kyc_status'];
            // data query
            $result = User::select('users.*', 'countries.name as country', 'ib_groups.group_name as ib_group_name')
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->leftJoin('ib_groups', 'users.ib_group_id', '=', 'ib_groups.id')
                ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id');
            // gent only managers user/ for manager dashboard
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $users_id);
            }
            $result = $result->where(function ($query) {
                $query->where('users.type', CombinedService::type());
            });

            // check crm is combined
            if (CombinedService::is_combined()) {
                $result = $result->where('users.combine_access', 1);
            }
            // Filter by verfification status
            if ($request->verification_status != "") {
                $result = $result->where('users.kyc_status', $request->verification_status);
            }
            //Filter By Active Status
            if ($request->active_status != "") {
                $result = $result->where('users.active_status', $request->active_status);
            }
            // filter by ib info
            if ($request->ib_info != "") {
                $ib_info = $request->ib_info;
                // check the ib_info is a country or not
                $country = Country::where('name', 'LIKE', '%' . $ib_info . '%')->first();
                if ($country && isset($country->id) && $country->id != "") {
                    $result = $result->where('user_descriptions.country_id', $country->id);
                } else {
                    $result = $result->where(function ($query) use ($ib_info) {
                        $query->where('users.name', $ib_info)
                            ->orWhere('users.email', $ib_info)
                            ->orWhere('users.phone', $ib_info);
                    });
                }
            }

            //    //Filter By Country
            if ($request->country != "") {
                $user_country = $request->country;
                $user_id = User::select('countries.name')->where(function ($query) use ($user_country) {
                    $query->where('countries.name', 'LIKE', '%' . $user_country . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }


            // filter by trader info
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $filter_trader = User::where(function ($query) use ($trader_info) {
                    $query->where('users.name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('countries.name', $trader_info);
                })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                // get instant parants ib
                $instant_parent_ib = IB::whereIn('reference_id', $filter_trader)->select('ib_id')->get()->pluck('ib_id');
                // filter result
                $result = $result->whereIn('users.id', $instant_parent_ib);
            }
            // filter by ib group
            if ($request->ib_group != "") {
                $result = $result->where('users.ib_group_id', $request->ib_group);
            }
            // filter by master ib and sub ib
            if ($request->ib_type != "") {
                if ($request->ib_type === 'sub_ib') {
                    // check if crm is combined
                    if (CombinedService::is_combined()) {
                        // for combined crm
                        $sub_ib = User::where('combine_access', 1)
                            ->whereExists(function ($query) {
                                $query->select(DB::raw(1))
                                    ->from('ib')
                                    ->whereColumn('ib.reference_id', 'users.id');
                            })
                            ->select('users.id as user_id')
                            ->get()->pluck('user_id');
                    } else {
                        // for non combined crm
                        $sub_ib = User::where('type', 4)
                            ->whereExists(function ($query) {
                                $query->select(DB::raw(1))
                                    ->from('ib')
                                    ->whereColumn('ib.reference_id', 'users.id');
                            })
                            ->select('users.id as user_id')
                            ->get()->pluck('user_id');
                    }

                    $result = $result->whereIn('users.id', $sub_ib);
                } else {
                    // check crm is combined
                    if (CombinedService::is_combined()) {
                        // for combined crm
                        $sub_ib = User::where('combine_access', 1)
                            ->whereNotExists(function ($query) {
                                $query->select(DB::raw(1))
                                    ->from('ib')
                                    ->whereColumn('ib.reference_id', 'users.id');
                            })
                            ->select('users.id as user_id')
                            ->get()->pluck('user_id');
                    } else {
                        // non combined crm
                        $sub_ib = User::where('type', 4)
                            ->whereNotExists(function ($query) {
                                $query->select(DB::raw(1))
                                    ->from('ib')
                                    ->whereColumn('ib.reference_id', 'users.id');
                            })
                            ->select('users.id as user_id')
                            ->get()->pluck('user_id');
                    }
                    $result = $result->whereIn('users.id', $sub_ib);
                }
            }
            // filter by account number
            if ($request->trading_account != "") {
                // get trader by trading account
                $trading_account = TradingAccount::where('account_number', $request->trading_account)->select('user_id')->first();
                // get instant parent ib
                $instant_ib = IB::where('reference_id', $trading_account->user_id)->select('ib_id')->first();
                $result = $result->where('users.id', $instant_ib->ib_id);
            }
            // filter by date range
            // date from
            if ($request->date_from != "") {
                $result = $result->whereDate('users.created_at', '>=', $date_from);
            }
            // filter by date to
            if ($request->date_to != "") {
                $result = $result->whereDate('users.created_at', '<=', $date_to);
            }

            $count = $result->count();
            $result = $result->orderBy($columns[$request->order[0]['column']], $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            // filter end

            $data = array();
            foreach ($result as $value) {
                $block_button_text = '';
                $request_for = '';

                if ($value->active_status == 0) {
                    $status = '<span class="badge badge-light-warning">Inactive</span>'; // <------status badge
                    $block_button_text .= '<i data-feather="user-x"></i> ' . __('page.block'); //  <------block button text
                    $request_for = 'block'; //   <-----request for block

                } elseif ($value->active_status == 1) {
                    $status = '<span class="badge badge-light-success">Active</span>'; // <------status badge
                    $block_button_text .= '<i data-feather="user-x"></i> ' . __('page.block'); //  <------block button text
                    $request_for = 'block'; //   <-----request for block

                } else {
                    $status = '<span class="badge badge-light-danger">Block</span>'; //  <----Status badge
                    $block_button_text .= '<i data-feather="user-check"></i> ' . __('page.unblock'); // <------block button text
                    $request_for = 'unblock'; //   <-----request for unblock
                }

                //permission script
                $auth_user = User::find(auth()->user()->id);
                if ($auth_user->hasDirectPermission('edit trader admin')) {
                    $button = ' <span class="dropdown-item btn-block btn-block-unblock" data-request_for = "' . $request_for . '" data-id="' . $value->id . '">
                                ' . $block_button_text . '</span>';
                } else {
                    $button = '<span class="text-danger">' . __('page.you_dont_have_right_permission') . '</span>';
                }

                if (isset($value->kyc_status)) {
                    if ($value->kyc_status == 2) {
                        $check_uncheck = '<span class="badge badge-light-warning bg-light-warning">Pending</span>';
                        $kyc_color = 'text-warning';
                    } elseif ($value->kyc_status == 1) {
                        $check_uncheck = '<span class="badge badge-light-success bg-light-success">Verified</span>';
                        $kyc_color = 'text-success';
                    } else {
                        $check_uncheck = '<span class="badge badge-light-danger bg-light-danger">Unverified</span>';
                        $kyc_color = 'text-danger';
                    }
                } else {
                    $check_uncheck = '<span class="text-danger">Unverified</span>';
                    $kyc_color = 'text-danger';
                }
                // end kyc status
                $auth_user = User::find(auth()->user()->id);
                if ($auth_user->hasDirectPermission('edit ib admin')) {
                    if ($value->active_status != 1) {
                        $buttons = '<a type="button" data-id="' . $value->id . '" data-value="deactive" class="dropdown-item btn-unblock-ib" data-bs-toggle="modal">
                                     <i data-feather="shield-off"></i>
                                     <span>Unblock</span>
                                 </a>';
                        $dashboard = '<a class="dropdown-item" href="' . route("admin.ib.dashboard", ["id" => $value->id]) . '">IB Dashboard</a>';
                    } else {
                        $buttons = '<a type="button" data-id="' . $value->id . '" data-value="active" class="dropdown-item btn-block-ib" data-bs-toggle="modal">
                                     <i data-feather="shield"></i>
                                     <span>Block</span>
                                 </a>';
                        $dashboard = '<a class="dropdown-item" href="' . route("admin.ib.dashboard", ["id" => $value->id]) . '">
                            <i style="margin: -1px 4px 0 0;" data-feather="home"></i>
                            <span>IB Dashboard</span>
                        </a>';
                    }
                } else {
                    $buttons = '<span class="text-danger"> No permission to access </span>';
                    $dashboard = '';
                }
                // datatable collumn
                $data[] = [
                    "name" => '<a href="#" data-ib_id="' . $value->id . '" class="dt-description d-flex ' . $kyc_color . '"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span><span class="text-truncate">' . $value->name . '</span></a>',
                    "email" => $value->email,
                    "phone" => $value->phone,
                    "country" => $value->country,
                    "group" => $value->ib_group_name,
                    "joined" => date('d M, Y H:i:s A', strtotime($value->created_at)),
                    "status" => $status,
                    "action" => '<td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            ' . $buttons . ''.$dashboard.'
                                        </div>
                                    </div>
                                </td>',
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
    
    public function goto_ib_dashboard($id){
        $customer = User::findOrFail($id);

         // Store the admin's ID and role in the session
         session(['admin_id' => Auth::id()]);

        // Log in as the customer
        Auth::login($customer);

        return redirect()->route('ib.dashboard');

    }

    public function ibAdminChangeStatus(Request $request)
    {
        // $update_time = date('d-m-y h:i:s');
        // $email_verified_at = (($request->value == 'active') ? $update_time : null);
        // $status = User::where('id', $request->id)->Update([
        //     'email_verified_at' => $email_verified_at,
        // ]);
        // if ($status) {
        //     return Response::json(['status' => true, 'message' => 'Status Successfully Updated.']);
        // } else {
        //     return Response::json(['status' => false, 'message' => 'Failed To Update!']);
        // }
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            } else {
                return Redirect()->back()->with([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
        } else {
            $user = User::find($request->id);
            $user->active_status = ($request->request_for === 'block') ? 2 : 1;
            $update = $user->save();
            if ($request->request_for === 'block') {
                $update_message = $user->name . " " . "successfully Blocked";
                $success_title = $user->type . ' Blocked';
                $activity = 'block';
            } else {
                $update_message = $user->name . " " . "successfully Un-Blocked";
                $success_title = $user->type . ' Un-Blocked';
                $activity = 'unblock';
            }
            if ($update) {
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been $activity";
                // insert activity
                activity($activity)
                    ->causedBy(auth()->user()->id)
                    ->withProperties($user)
                    ->event($activity)
                    ->performedOn($user)
                    ->log($description);

                return Response::json([
                    'status' => true,
                    'message' => $update_message,
                    'success_title' => $success_title
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Something went wrong please try again later',
                    'success_title' => $success_title
                ]);
            }
        }
        return Response::json($request->trader_id);
    }

    // ib admin report description
    public function ibAdminReportDescription(Request $request, $ib_id)
    {
        // find ib
        $ib = User::find($ib_id);

        $user_descriptions = UserDescription::where('user_id', $ib)->first(); //<---user description
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png'; //<----avatar url
        }
        // find ib category
        $category_name = "N/A";
        $categories =  Category::where('id', $ib->category_id)->first();
        if ($categories != null) {
            $category_name = $categories->name;
        }
        // set category
        $set_category = '';
        $categories = Category::where('client_type', 'IB')->select()->get();
        foreach ($categories as $category) {
            $set_category .= '<option value="' . $category->id . '">' . ucwords($category->name) . '</option>';
        }

        $active_status = ($ib->active_status == 2) ? 'checked' : ' ';
        $two_step_status = ($ib->g_auth == 1) ? 'checked' : ' '; //google 2 step auth status
        $email_a_status = ($ib->email_auth == 1) ? 'checked' : ' '; // email auth status
        $email_v_status = ($ib->email_verified_at != null || $ib->email_verified_at != "") ? 'checked' : ' '; // email verification status

        // finance operation
        $finance_operation = FinanceOp::where('user_id', $ib_id)->first();
        $withdraw_operation = (($finance_operation) && $finance_operation->withdraw_operation == 1) ? 'checked' : ' '; // withdraw_operation enable or disable
        $ib_to_ib = (($finance_operation) && $finance_operation->ib_to_ib == 1) ? 'checked' : ' '; // ib to ib operation enable or disable
        $ib_to_trader = (($finance_operation) && $finance_operation->ib_to_trader == 1) ? 'checked' : ' '; // ib to trader operation enable or disable
        // kyc status
        $kyc_verify = ($ib->kyc_status == 1) ? 'checked' : ' '; // kyc verify enable or disable

        // // finance report process start
        // //Get All Commission
        // $ib_commissions = 0;

        // //Get Total withdraw
        // $total_withdraw_approved = Withdraw::where('user_id', $ib_id)->where('approved_status', 'A')->sum('amount');
        // // finance report process end
        // finance report process start
        //Get All Commission
        $total_ib_commission = IbIncome::where('ib_id', $ib_id)->sum('amount');
        $total_ib_commission = ($total_ib_commission) ? (($total_ib_commission > 0) ? "$" . abs($total_ib_commission) : "$" . abs($total_ib_commission)) : "$0";

        // total ib balance
        $total_ib_balance = BalanceSheetService::ib_wallet_balance($ib_id);
        // $total_ib_balance = ($total_ib_balance) ? (($total_ib_balance > 0) ? "$" . abs($total_ib_balance) : "$" . abs($total_ib_balance)) : "$0";


        // Get total fund send
        $total_send_fund = ExternalFundTransfers::where('sender_id', $ib_id)
            ->where('sender_wallet_type', 'ib')
            ->whereIn('status', ['P', 'A'])->sum('amount');
        $total_send_fund = ($total_send_fund) ? (($total_send_fund > 0) ? "$" . abs($total_send_fund) : "$" . abs($total_send_fund)) : "$0";

        // Get total fund received
        $total_rec_fund = ExternalFundTransfers::where('receiver_id', $ib_id)
            ->where('receiver_wallet_type', 'ib')
            ->where('status', 'A')->sum('amount');
        $total_rec_fund = ($total_rec_fund) ? (($total_rec_fund > 0) ? "$" . abs($total_rec_fund) : "$" . abs($total_rec_fund)) : "$0";

        // deposit from admin
        $total_admin_deposit = Deposit::where('approved_status', 'A')
            ->where('wallet_type', 'ib')->where('user_id', $ib_id)->sum('amount');

        //Get Total withdraw approved
        $total_withdraw_approved = Withdraw::where('user_id', $ib_id)->where('wallet_type', 'ib')->where('approved_status', 'A')->sum('amount');
        $total_withdraw_approved = ($total_withdraw_approved) ? (($total_withdraw_approved > 0) ? "$" . abs($total_withdraw_approved) : "$" . abs($total_withdraw_approved)) : "$0";
        //Get Total withdraw pending
        $total_withdraw_pending = Withdraw::where('user_id', $ib_id)->where('wallet_type', 'ib')->where('approved_status', 'P')->sum('amount');
        $total_withdraw_pending = ($total_withdraw_pending) ? (($total_withdraw_pending > 0) ? "$" . abs($total_withdraw_pending) : "$" . abs($total_withdraw_pending)) : "$0";

        // finance report process end

        //Find manager
        $ib_id = $ib->id;
        $managerName = ManagerUser::join('users', 'manager_users.manager_id', '=', 'users.id')
            ->select('users.name')
            ->where('manager_users.user_id', $ib_id)
            ->first();
        // return $managerName->name;
        $manager_name = isset($managerName->name) ? $managerName->name : 'N/A';
        $ib_name = AllFunctionService::user_name(IbService::instant_parent($ib_id));

        // find kyc verification for ib
        if (isset($ib->kyc_status)) {
            if ($ib->kyc_status == 2) {
                $check_uncheck = '<span class="text-warning">Pending</span>';
            } elseif ($ib->kyc_status == 1) {
                $check_uncheck = '<span>Verified</span>';
            } else {
                $check_uncheck = '<span class="text-danger">Unverified</span>';
            }
        } else {
            $check_uncheck = '<span class="text-danger">Unverified</span>';
        }

        $total_trader = AllFunctionService::total_trader($ib_id);
        $total_sub_ib = AllfunctionService::total_sub_ib($ib_id);

        $checkVarsion = new VersionControllService();
        $crmVarsion = $checkVarsion->check_version();

        $has_parent = IbService::has_parent($ib_id);
        $type = '';
        if (!$has_parent) {
            $type = '<tr class="master-ib">
            <th class="border-end-2 border-bottom-0">' . __('page.ib-type') . '</th>
            <td class="border-end-0 border-bottom-0">Master IB</td>
        </tr>';
        }

        $description = '<tr class="description" style="display:none;">
        <td colspan="8">
            <div class="details-section-dark border-start-3 border-start-primary p-2 bg-light-secondary">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="rounded-0 w-75">
                            <div class="card-body p-0">
                                <table class="table table-responsive tbl-balance">
                                    <tr>
                                        <th class="border-end-2 border-bottom-3">Wallet Balance</th>
                                        <td class="border-end-0 border-bottom-3">' . BalanceSheetService::ib_wallet_balance($ib_id) . '</td>
                                    </tr>
                                    <tr>
                                        <th class="border-end-2 border-bottom-3">' . __('page.total-trader') . '</th>
                                        <td class="border-end-0 border-bottom-3">' . $total_trader . '</td>
                                    </tr>
                                    <tr>
                                        <th class="border-end-2 border-bottom-0">' . __('page.total-subIB') . '</th>
                                        <td class="border-end-0 border-bottom-0">' . ($total_sub_ib ? ($total_sub_ib) : 0) . '</td>
                                    </tr>
                                    ' . $type . '
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex justfy-content-between">
                        <div class="rounded-0 w-100">
                            <table class="table table-responsive tbl-trader-details">
                                <tbody>
                                    <tr>
                                        <th class="border-end-2 border-bottom-3">' . __('page.category') . '</th>
                                        <td class="border-bottom-3">' . $category_name . '</td>
                                    </tr>
                                    <tr>
                                        <th class="border-end-2 border-bottom-3">' . __('page.kyc') . '</th>
                                        <td class="border-bottom-3">' . $check_uncheck . '</td>
                                    </tr>
                                    <tr>
                                        <th class="border-end-2 border-bottom-3">' . __('page.ib') . '</th>
                                        <td class="border-bottom-3">' . $ib_name . '</td>
                                    </tr>';
        if ($crmVarsion === 'pro') {
            $description .= ' <tr>
                                        <th class="border-end-2">' . __('page.account_manager') . '</th>
                                        <td class="border-bottom-0">' . $manager_name . '</td>
                                    </tr>';
        }

        $description .= '</tbody>
                            </table>
                        </div>
                        <div class="rounded ms-1 dt-trader-img">
                            <div class="h-100">
                                <img class="img img-fluid" src="' . asset("admin-assets/app-assets/images/avatars/$avatar") . ' "alt="avatar">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <!-- Filled Tabs starts -->
                        <div class="col-xl-12 col-lg-12">
                            <div class=" p-0">
                                <div class="p-0">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs  mb-1 tab-inner-dark" id="myTab' . $ib->id . '" role="tablist">
                                        <li class="nav-item d-flex">
                                            <div class="btn-group w-100">
                                                <button class="btn dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    ' . __('page.trader_finance') . '
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a data-ib_id="' . $ib->id . '" class="w-100 dropdown-item custom-dropdown pt-1 pb-1 pl-1-pr-1 trader-list-tab active" id="trader-list-tab-' . $ib->id . '" data-bs-toggle="tab" href="#trader-list-fill-' . $ib->id . '" role="tab" aria-controls="trader-list-fill" aria-selected="false">' . __('page.trader_list') . '</a>
                                                    <a data-ib_id="' . $ib->id . '" class="w-100 dropdown-item custom-dropdown pt-1 pb-1 pl-1-pr-1 trading-account-tab" id="trading-account-tab-' . $ib->id . '" data-bs-toggle="tab" href="#trading-account-fill-' . $ib->id . '" role="tab" aria-controls="trading-account-fill" aria-selected="true">' . __('page.trading_account') . '</a>
                                                    <a data-ib_id="' . $ib->id . '" class="w-100 dropdown-item custom-dropdown pt-1 pb-1 pl-1-pr-1 trading-deposit-tab" id="trading-deposit-tab-' . $ib->id . '" data-bs-toggle="tab" href="#trading-deposit-fill-' . $ib->id . '" role="tab" aria-controls="trading-deposit-fill">' . __('page.trader_deposit') . '</a>
                                                    <a data-ib_id="' . $ib->id . '" class="w-100 dropdown-item custom-dropdown pt-1 pb-1 pl-1-pr-1 trading-withdraw-tab" id="trading-withdraw-tab-' . $ib->id . '" data-bs-toggle="tab" href="#trading-withdraw-fill-' . $ib->id . '" role="tab" aria-controls="trading-withdraw-fill">' . __('page.trader_withdraw') . '</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="nav-item d-flex">
                                            <div class="btn-group w-100">
                                                <button class="btn dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    ' . __('page.ib_finance') . '
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a data-ib_id="' . $ib->id . '" class="w-100 dropdown-item custom-dropdown pt-1 pb-1 pl-1-pr-1 sub-ib-tab" id="sub-ib-tab-' . $ib->id . '" data-bs-toggle="tab" href="#sub-ib-fill-' . $ib->id . '" role="tab" aria-controls="sub-ib-fill" aria-selected="false">' . __('page.sub_ib') . '</a>
                                                    <a data-ib_id="' . $ib->id . '" class="w-100 dropdown-item custom-dropdown pt-1 pb-1 pl-1-pr-1 ib-commission-tab" id="ib-commission-tab-' . $ib->id . '" data-bs-toggle="tab" href="#ib-commission-fill-' . $ib->id . '" role="tab" aria-controls="ib-commission-fill" aria-selected="false">' . __('page.ib_commission') . '</a>
                                                    <a data-ib_id="' . $ib->id . '" class="w-100 dropdown-item custom-dropdown pt-1 pb-1 pl-1-pr-1 self-withdraw-tab" id="self-withdraw-tab-' . $ib->id . '" data-bs-toggle="tab" href="#self-withdraw-fill-' . $ib->id . '" role="tab" aria-controls="self-withdraw-fill">' . __('page.self/iB_withdraw_report') . '</a>
                                                    <a data-ib_id="' . $ib->id . '" class="w-100 dropdown-item custom-dropdown pt-1 pb-1 pl-1-pr-1 finance-report-tab" id="finance-report-tab-' . $ib->id . '" data-bs-toggle="tab" href="#finance-report-fill-' . $ib->id . '" role="tab" aria-controls="finance-report-fill" aria-selected="false">' . __('page.finance') . '</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="nav-item">
                                            <a data-ib_id="' . $ib->id . '" class="nav-link kyc-tab-fill" id="kyc-tab-fill-' . $ib->id . '" data-bs-toggle="tab" href="#kyc-fill-' . $ib->id . '" role="tab" aria-controls="kyc-fill" aria-selected="false">' . __('page.kyc') . '</a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-ib_id="' . $ib->id . '" class="nav-link comment-tab-fill" id="comment-tab-fill-' . $ib->id . '" data-bs-toggle="tab" href="#comment-fill-' . $ib->id . '" role="tab" aria-controls="comment-fill" aria-selected="false">' . __('page.comments') . '</a>
                                        </li>
                                        <li class="nav-item border-end-2 ">
                                            <a data-ib_id="' . $ib->id . '" class="nav-link" id="action-tab-fill-' . $ib->id . '" data-bs-toggle="tab" href="#action-fill-' . $ib->id . '" role="tab" aria-controls="action-fill" aria-selected="false">' . __('page.action') . '</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-0 bg-transparent">
                    <div class="card-body p-0 bg-transparent">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- trader list -->
                            <div class="tab-pane active" id="trader-list-fill-' . $ib->id . '" role="tabpanel" aria-labelledby="trader-list-tab-fill">
                            <button type="button" class="btn btn-primary  manage-trader-added-btn" data-ib_id="' . $ib->id . '" data-bs-toggle="modal" data-bs-target="#trader-added-modal">Add Trader</button>
                                <div class="table-responsive">
                                    <table class="datatable-inner trader-list table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                        <thead>
                                            <tr>
                                                <th>' . __('page.name') . '</th>
                                                <th>' . __('page.email') . '</th>
                                                <th>' . __('page.deposit') . '</th>
                                                <th>' . __('page.withdraw') . '</th>
                                                <th>' . __('page.balance') . '</th>
                                                <th>' . __('page.action') . '</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- trading account -->
                            <div class="tab-pane" id="trading-account-fill-' . $ib->id . '" role="tabpanel" aria-labelledby="trading-account-tab-fill">
                                <div class="table-responsive">
                                    <table class="datatable-inner trader-account table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                        <thead>
                                        <tr>
                                            <th>' . __('page.account-number') . '</th>
                                            <th>' . __('page.platform') . '</th>
                                            <th>' . __('page.leverage') . '</th>
                                            <th>' . __('page.GROUP') . '</th>
                                            <th>' . __('page.Raw_Group') . '</th>
                                            <th>' . __('page.Openning_Date') . '</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- Trader Deposit -->
                            <div class="tab-pane" id="trading-deposit-fill-' . $ib->id . '" role="tabpanel" aria-labelledby="trading-deposit-tab-fill">
                                <h4 class="p-1 waves-effect mb-0 bg-light-primary border-4 border-start-success">Trader Deposit</h4>
                                <div class="table-responsive">
                                    <table class="datatable-inner trading-deposit table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                        <thead>
                                            <tr>
                                                <th>' . __('page.client_name') . '</th>
                                                <th>' . __('page.email') . '</th>
                                                <th>' . __('page.ib') . '</th>
                                                <th>' . __('page.amount') . ' (' . __('page.approved') . ')</th>
                                                <th>' . __('page.amount') . ' (' . __('page.pending') . ')</th>
                                                <th>' . __('page.amount') . ' (' . __('page.total') . ')</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- trader withdraw -->
                            <div class="tab-pane" id="trading-withdraw-fill-' . $ib->id . '" role="tabpanel" aria-labelledby="trading-withdraw-tab-fill">
                                <h4 class="p-1 waves-effect mb-0 bg-light-primary border-4 border-start-success">Trader Withdraw</h4>
                                <div class="table-responsive">
                                    <table class="datatable-inner trading-withdraw table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                        <thead>
                                            <tr>
                                            <th>' . __('page.client_name') . '</th>
                                            <th>' . __('page.email') . '</th>
                                            <th>' . __('page.ib') . '</th>
                                            <th>' . __('page.amount') . ' (' . __('page.approved') . ')</th>
                                            <th>' . __('page.amount') . ' (' . __('page.pending') . ')</th>
                                            <th>' . __('page.amount') . ' (' . __('page.total') . ')</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <!-- Finance Report -->
                            <div class="tab-pane" id="finance-report-fill-' . $ib->id . '" role="tabpanel" aria-labelledby="finance-report-tab-fill">
                                <div class="table-responsive">
                                    <div class="rounded-0">
                                        <div class="p-0 fw-bolder">
                                            <table class="table table-responsive tbl-trader-details">
                                                <tr>
                                                    <th style="border-left: 2px solid #7367f0 !important;" class="border-end-2">Description</th>
                                                    <td class="border-end-2">Amount</td>
                                                </tr>
                                                <tr>
                                                    <th style="border-left: 2px solid #7367f0 !important;" class="border-end-2">Deposit From Admin</th>
                                                    <td class="border-end-2">+$' . $total_admin_deposit . '</td>
                                                </tr>
                                                <tr>
                                                    <th style="border-left: 2px solid #7367f0 !important;" class="border-end-2">' . __('page.total_commission') . '</th>
                                                    <td class="border-end-2">+' . $total_ib_commission . '</td>
                                                </tr>
                                                <tr>
                                                    <th style="border-left: 2px solid #7367f0 !important;" class="border-end-2">Total Recieved Balance (Approved)</th>
                                                    <td class="border-end-2">+' . $total_rec_fund . '</td>
                                                </tr>
                                                <tr>
                                                    <th style="border-left: 2px solid #7367f0 !important;" class="border-end-2">' . __('page.total_withdraw_approved') . '</th>
                                                    <td class="border-end-2">-' . $total_withdraw_approved . '</td>
                                                </tr>
                                                <tr>
                                                    <th style="border-left: 2px solid #7367f0 !important;" class="border-end-2">' . __('page.total_withdraw_panding') . '</th>
                                                    <td class="border-end-2">-' . $total_withdraw_pending . '</td>
                                                </tr>
                                                <tr>
                                                    <th style="border-left: 2px solid #7367f0 !important;" class="border-end-2">Total Sent Balance</th>
                                                    <td class="border-end-2">-' . $total_send_fund . '</td>
                                                </tr>
                                                <tr>
                                                    <th style="border-left: 2px solid #7367f0 !important;" class="border-end-2 text-primary">Total Current Balance</th>
                                                    <td class="border-end-2 text-primary">$' . $total_ib_balance . '</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Sub IB -->
                            <div class="tab-pane" id="sub-ib-fill-' . $ib->id . '" role="tabpanel" aria-labelledby="sub-ib-tab-fill">
                                <div class="table-responsive">
                                <button type="button" class="btn btn-primary  manage-sub-ib-btn" data-ib_id="' . $ib->id . '" data-bs-toggle="modal" data-bs-target="#sub-ib-modal">Add Sub IB</button>
                                    <table class="datatable-inner sub-ib table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                        <thead>
                                            <tr>
                                                <th>' . __('page.name') . '</th>
                                                <th>' . __('page.email') . '</th>
                                                <th>' . __('page.withdraw') . '</th>
                                                <th>' . __('page.balance') . '</th>
                                                <th>' . __('page.action') . '</th>
                                                
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <!-- self withdraw -->
                            <div class="tab-pane" id="self-withdraw-fill-' . $ib->id . '" role="tabpanel" aria-labelledby="self-withdraw-tab-fill">
                                <div class="table-responsive">
                                    <table class="datatable-inner self-withdraw table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                        <thead>
                                            <tr>
                                                <th>' . __('page.txnid') . '</th>
                                                <th>' . __('page.amount') . '</th>
                                                <th>' . __('page.method') . '</th>
                                                <th>' . __('page.status') . '</th>
                                                <th>' . __('page.date') . '</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- IB commission -->
                            <div class="tab-pane" id="ib-commission-fill-' . $ib->id . '" role="tabpanel" aria-labelledby="ib-commission-tab-fill">
                                <div class="table-responsive">
                                    <table class="datatable-inner ib-commission table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                        <thead>
                                            <tr>
                                                <th>' . __('page.trader') . '</th>
                                                <th>' . __('page.ticket') . '</th>
                                                <th>' . __('page.login') . '</th>
                                                <th>' . __('page.symbol') . '</th>
                                                <th>' . __('page.amount') . '</th>
                                                <th>' . __('page.date') . '</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- kyc verification -->
                            <div class="tab-pane" id="kyc-fill-' . $ib->id . '" role="tabpanel" aria-labelledby="kyc-tab-fill">
                                <table class="datatable-inner kyc table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                    <thead>
                                        <tr>
                                            <th>' . __('page.submit_date') . '</th>
                                            <th>' . __('page.document_type') . '</th>
                                            <th>' . __('page.status') . '</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tab-pane" id="comment-fill-' . $ib->id . '" role="tabpanel" aria-labelledby="comment-tab-fill">
                                <button type="button" class="btn btn-primary float-end btn-add-comment" data-id="' . $ib_id . '" data-name="' . $ib->name . '" data-bs-toggle="modal" data-bs-target="#primary"><i data-feather="plus"></i> Add Comment</button>
                                <table class="datatable-inner comment table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                    <thead>
                                        <tr>
                                            <th>' . __('page.commented_date') . '</th>
                                            <th>' . __('page.comments') . '</th>
                                            <th>' . __('page.actions') . '</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tab-pane" id="action-fill-' . $ib->id . '" role="tabpanel" aria-labelledby="action-tab-fill">
                                <table class="action-table-inner-dark action table m-0"  style="margin:0px !important;">
                                    <tbody>
                                        <tr class="border-start-3 border-start-primary">
                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                <label class="form-check-label mb-50" for="block-unblock-swtich' . $ib_id . '">Unblock/Block</label>
                                                <div class="form-check form-switch form-check-danger">
                                                    <input type="checkbox" class="form-check-input block-unblock-swtich" id="block-unblock-swtich' . $ib_id . '" value="' . $ib_id . '" ' . $active_status . '/>
                                                    <label class="form-check-label" for="block-unblock-swtich' . $ib_id . '">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <th class="text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                <div class="row">
                                                    <div class="d-grid col-lg-6 col-md-12"></div>
                                                    <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                        <button type="button" class="btn btn-primary reset-password-btn-2" data-ib_id="' . $ib_id . '" data-id="' . $ib_id . '" data-name="' . $ib->name . '" data-bs-toggle="modal" data-bs-target=".reset-password-modal"><i data-feather="trello"></i>' . __('page.reset_password') . '</button>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr class="border-start-3 border-start-primary">
                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                <label class="form-check-label mb-50" for="two-step-swtich">' . __('page.google_2_step_authentication') . '</label>
                                                <div class="form-check form-switch form-check-success">
                                                    <input type="checkbox" class="form-check-input" id="two-step-swtich" value="' . $ib_id . '" ' . $two_step_status . '/>
                                                    <label class="form-check-label" for="two-step-swtich">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <th class=" text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                            <div class="row">
                                                <div class="d-grid col-lg-6 col-md-12"></div>
                                                <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                    <button type="button" class="btn btn-primary reset-transaction-password-btn" data-ib_id="' . $ib_id . '" data-id="' . $ib_id . '" data-name="' . $ib->name . '" data-bs-toggle="modal" data-bs-target=".reset-transaction-password-modal"><i data-feather="trello"></i>' . __('page.transaction_password_reset') . '</button>
                                                </div>
                                            </div>
                                        </tr>
                                        <tr class="border-start-3 border-start-primary">
                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                <label class="form-check-label mb-50" for="email-a-swtich">Email Authentication</label>
                                                <div class="form-check form-switch form-check-success">
                                                    <input type="checkbox" class="form-check-input" id="email-a-swtich" value="' . $ib_id . '" ' . $email_a_status . '/>
                                                    <label class="form-check-label" for="email-a-swtich">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <th class=" text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                <div class="row">
                                                    <div class="d-grid col-lg-6 col-md-12"></div>
                                                    <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                        <button type="button" class="btn btn-primary change-password-btn" data-ib_id="' . $ib_id . '" data-name="' . $ib->name . '" data-bs-toggle="modal" data-bs-target=".change-password-modal"><i data-feather="trello"></i>' . __('page.change_password') . '</button>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr class="border-start-3 border-start-primary">
                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                <label class="form-check-label mb-50" for="email-v-switch">' . __('page.email_verification') . '</label>
                                                <div class="form-check form-switch form-check-success">
                                                    <input type="checkbox" class="form-check-input" id="email-v-switch" value="' . $ib_id . '" ' . $email_v_status . '/>
                                                    <label class="form-check-label" for="email-v-switch">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <th class=" text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                <div class="row">
                                                    <div class="d-grid col-lg-6 col-md-12"></div>
                                                    <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                    <button type="button" class="btn btn-primary change-transaction-password-btn" data-ib_id="' . $ib_id . '" data-name="' . $ib->name . '" data-bs-toggle="modal" data-bs-target=".change-transaction-password-modal"><i data-feather="trello"></i>' . __('page.change_transaction_password') . '</button>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr class="border-start-3 border-start-primary">
                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                <label class="form-check-label mb-50" for="kyc_verify-switch' . $ib_id . '">KYC Verify</label>
                                                <div class="form-check form-switch form-check-success">
                                                    <input type="checkbox" class="form-check-input kyc_verify-switch" id="kyc_verify-switch' . $ib_id . '" value="' . $ib_id . '" ' . $kyc_verify . '/>
                                                    <label class="form-check-label" for="kyc_verify-switch' . $ib_id . '">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                <label class="form-check-label mb-50" for="withdraw-switch' . $ib_id . '">Withdraw</label>
                                                <div class="form-check form-switch form-check-success">
                                                    <input type="checkbox" class="form-check-input withdraw-switch" id="withdraw-switch' . $ib_id . '" value="' . $ib_id . '" ' . $withdraw_operation . '/>
                                                    <label class="form-check-label" for="withdraw-switch' . $ib_id . '">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr class="border-start-3 border-start-primary">
                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                <label class="form-check-label mb-50" for="ib_to_ib-switch' . $ib_id . '">IB To IB</label>
                                                <div class="form-check form-switch form-check-success">
                                                    <input type="checkbox" class="form-check-input ib_to_ib-switch" id="ib_to_ib-switch' . $ib_id . '" value="' . $ib_id . '" ' . $ib_to_ib . '/>
                                                    <label class="form-check-label" for="ib_to_ib-switch' . $ib_id . '">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                <label class="form-check-label mb-50" for="ib_to_trader-switch' . $ib_id . '">IB To Trader</label>
                                                <div class="form-check form-switch form-check-success">
                                                    <input type="checkbox" class="form-check-input ib_to_trader-switch" id="ib_to_trader-switch' . $ib_id . '" value="' . $ib_id . '" ' . $ib_to_trader . '/>
                                                    <label class="form-check-label" for="ib_to_trader-switch' . $ib_id . '">
                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="demo-inline-spacing">
                    <button type="button" class="btn btn-primary float-end manage-trader-btn" data-ib_id="' . $ib->id . '" data-bs-toggle="modal" data-bs-target="#trader-sub-ib-modal">Add Client</button>
                    <button type="button" class="btn btn-danger waves-effect waves-float waves-light  manage-sub-ib-btn" data-ib_id="' . $ib->id . '" data-bs-toggle="modal" data-bs-target="#remove-trader-ib-modal">Remove Client</button>
                    <button type="button" class="btn btn-primary float-end btn-send-welcome-mail" data-ib_id="' . $ib_id . '" >Send Verification Email</button>
                    <button type="button" class="btn btn-primary float-end update-profile-btn" data-ib_id="' . $ib_id . '" data-bs-toggle="modal" data-bs-target="#profile-update-modal">' . __('page.update_profile') . '</button>
                </div>
                <div class="clearfix"></div>
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

    // profile update getdata
    public function ibAdminProfileUpdateGetdata(Request $request)
    {
        // start find ib group 
        $ib = User::where('id', $request->id)->select('ib_group_id')->first();
        $ib_group = IbGroup::where('id', $ib->ib_group_id)->first();
        // end find ib group

        $user = User::where('users.id', $request->id)
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.phone',
                'countries.name as country_name',
                'countries.id as country_id',
                'user_descriptions.city',
                'user_descriptions.state',
                'user_descriptions.zip_code',
                'user_descriptions.address',
                'users.password',
                'users.transaction_password',
                'users.active_status',
                'users.kyc_status'
            )->first();
        // return $user;
        $user_passwords = Log::where('user_id', $request->id)->first();
        $password = decrypt($user_passwords->password);
        $transaction_password = decrypt($user_passwords->transaction_password);
        // social links
        $social_link = SocialLink::where('user_id', $request->id)->first();
        if ($social_link) {
            $social_link = $social_link;
        } else {
            $social_link = [
                'facebook' => '',
                'twitter' => '',
                'whatsapp' => '',
                'telegram' => '',
                'linkedin' => '',
                'skype' => '',
            ];
        }
        return Response::json([
            'user' => $user,
            'password' => $password,
            'transaction_password' => $transaction_password,
            'ib_group' => $ib_group,
            'social' => $social_link,
        ]);
    }
    // profile update 
    public function ibAdminProfileUpdate(Request $request)
    {

        $unique_user = User::find($request->pro_user_id)->first();
        $validation_rules = [
            'pro_name'                  => 'required',
            'email'                     => 'required|email',
            'pro_phone'                 => 'required',
            'pro_password'              => 'required',
            'pro_transaction_pin'       => 'required',
            'pro_verification_status'   => 'required',
            'pro_city'                  => 'required',
            'pro_state'                 => 'required',
            'pro_zip_code'              => 'required',
            'pro_address'               => 'required',
            'pro_country'               => 'required',
            'pro_group'                 => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        }
        // start find ib group 
        $update = IB::where('ib_id', $request->pro_user_id)->update(['ib_group_id' => $request->pro_group]);
        $update = UserDescription::where('user_id', $request->pro_user_id)->update([
            'city'          => $request->pro_city,
            'state'         => $request->pro_state,
            'zip_code'      => $request->pro_zip_code,
            'address'       => $request->pro_address,
            'country_id'    => $request->pro_country,
        ]);
        $update = User::where('id', $request->pro_user_id)->update([
            'name'                  => $request->pro_name,
            'email'                 => $request->email,
            'phone'                 => $request->pro_phone,
            'password'              => Hash::make($request->pro_password),
            'transaction_password'  => Hash::make($request->pro_transaction_pin),
            'active_status'         => $request->pro_verification_status,
        ]);
        // if mail send field checked
        if ($request->pro_send_email == 'on') {
            $system_config = SystemConfig::select()->first();
            $support_email = ($system_config) ? $system_config->support_email : default_support_email();
            $email_data = [
                'emailSupport' => $support_email,
                'clientName' => $unique_user->name,
                'customMessage' => (isset($request->note)) ? $request->note : '',
                'phone1' => (isset($unique_user->phone)) ? $unique_user->phone : '',
                'companyName' => (isset($system_config->com_name)) ? $system_config->com_name : '',
                'emailCommon' => $request->email,
                'loginUrl' => route('login'),
                'website' => (isset($system_config->com_website)) ? $system_config->com_website : '',
                'copy_right' => (isset($system_config->copyright)) ? $system_config->copyright : '',
                'authority' => (isset($system_config->com_authority)) ? $system_config->com_authority : '',
                'license' => (isset($system_config->com_license)) ? $system_config->com_license : ''
            ];
            // sending mail
            Mail::to($unique_user->email)->send(new UpdateProfile($email_data));
        }
        if ($update) {
            if ($request->ajax()) {
                return Response::json(['status' => true, 'message' => 'Successfully Updated.']);
            } else {
                return Redirect()->back()->with(['status' => true, 'message' => 'Successfully Updated.']);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['status' => false]);
            } else {
                return Redirect()->back()->with(['status' => false]);
            }
        }
    }

    // trader list
    public function ibAdminReportDescriptionInner(Request $request, $ib_id)
    {
        try {
            $search =  $_GET['search']['value'];
            $columns = ['name', 'email', 'reference_id', 'reference_id', 'reference_id'];
            $orderby = $columns[$request->order[0]['column']];
            $result = IB::where('ib.ib_id', $ib_id)
                ->where('users.type', 0)
                ->select('users.id as trader_id', 'users.name', 'users.email')
                ->join('users', 'ib.reference_id', '=', 'users.id');
            if ($search != "") {
                $result = $result->where('name', 'LIKE', '%' . $search . '%')
                    ->where('email', 'LIKE', '%' . $search . '%');
            }

            $data = array();
            $i = 0;
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();

            // if reference id not empty
            foreach ($result as $reference) {
                // trader total deposit
                $total_deposit = AllFunctionService::trader_total_deposit($reference->trader_id);
                // trader total withdraw
                $total_withdraw = AllFunctionService::trader_total_withdraw($reference->trader_id);
                // trader total balance
                $total_balance = AllfunctionService::trader_total_balance($reference->trader_id);

                $data[$i]["name"]     = $reference->name;
                $data[$i]["email"]    = $reference->email;
                $data[$i]["deposit"]  = $total_deposit;
                $data[$i]["withdraw"] = $total_withdraw;
                $data[$i]["balance"]  = $total_balance;
                $data[$i]["action"]  =  '<button type="button" class="btn btn-sm btn-danger" data-ib_id="' . $reference->trader_id . '" onclick=delete_trader(this)>Delete</button>';
                $i++;
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    //trading account
    public function ibAdminReportDescriptionInnerTradingAccount(Request $request, $ib_id)
    {
        try {
            $columns = ['account_number', 'platform', 'leverage', 'group_id', 'group_id', 'trading_accounts.created_at'];
            $orderby = $columns[$request->order[0]['column']];
            // select type= 0 for trader

            $result = IB::select()
                ->where('ib.ib_id', '=', $ib_id)
                ->where('users.type', '=', 0)
                ->where('account_number', '<>', '')
                ->join('users', 'ib.reference_id', '=', 'users.id')
                ->join('trading_accounts', 'users.id', 'trading_accounts.user_id')
                ->where('trading_accounts.account_status', 1);

            $count = $result->count(); // <------count total rows
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                $groups = ClientGroup::where('id', $value->group_id)->select()->first();

                $data[$i]["account_number"] = $value->account_number;
                $data[$i]["platform"]       = $groups->server;
                $data[$i]["leverage"]       = $value->leverage;
                $data[$i]["group"]          = $groups->group_id;
                $data[$i]["raw_group"]      = $groups->group_name;
                $data[$i]["created_at"]     = date('d F y, h:i A', strtotime($value->created_at));
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
    //sub ib show from here
    public function ibAdminReportDescriptionInnerSubIB(Request $request, $ib_id)
    {
        try {
            $search =  $_GET['search']['value'];

            $columns = ['name', 'email', 'reference_id', 'reference_id', 'reference_id'];
            $orderby = $columns[$request->order[0]['column']];
            // find references under this ib
            $subibs = AllFunctionService::my_sub_ib_id($ib_id);
            array_push($subibs, $ib_id);
            $result = IB::whereIn('ib_id', $subibs)
                ->where('type', CombinedService::type())
                ->select('users.*', 'reference_id')
                ->join('users', 'ib.reference_id', '=', 'users.id');
            // check crm is combined
            if (CombinedService::is_combined()) {
                $result = $result->where('users.combine_access', 1);
            }

            if ($search != "") {
                $result = $result
                    ->where('name', 'LIKE', '%' . $search . '%')
                    ->where('email', 'LIKE', '%' . $search . '%');
            }

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;
            // if reference id not empty
            foreach ($result as $reference) {
                // count number of rows
                // find sub ib
                $sub_ib = User::where('type', '=', CombinedService::type())
                    ->where('id', '=', $reference->reference_id);
                // check crm is combined
                if (CombinedService::is_combined()) {
                    $sub_ib = $sub_ib->where('users.combine_access', 1);
                }
                $sub_ib = $sub_ib->select()->get();
                if (!empty($sub_ib[0]['name'])) {
                    // if sub ib found

                    // trader total deposit
                    $total_deposit = Deposit::where('user_id', '=', $reference->reference_id)->sum('amount');

                    // trader total withdraw
                    $total_withdraw = AllFunctionService::trader_total_withdraw($reference->reference_id);


                    $balance = BalanceService::ib_balance($reference->reference_id);
                    $data[$i]["name"]     = $sub_ib[0]['name'];
                    $data[$i]["email"]    = $sub_ib[0]['email'];
                    // $data[$i]["deposit"]  = $total_deposit;
                    $data[$i]["withdraw"] = $total_withdraw;
                    $data[$i]["balance"]  = $balance;
                    $data[$i]["action"]  =  '<button type="button" class="btn btn-sm btn-danger" data-ib_id="' . $reference->reference_id . '" onclick=delete_sub_ib(this)>Delete</button>';
                    $i++;
                }
            }
            // }
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
    // trader deposit
    public function ibAdminReportDescriptionInnerTradingDeposit(Request $request, $ib_id)
    {
        try {
            $data = array();
            $i = 0;

            // find traders under this ib
            $all_traders = AllFunctionService::sub_ib_traders_id($ib_id, 'all');
            $result = User::select('users.*');
            $result = $result->whereIn('users.id', $all_traders);
            $count = $result->count();
            $result = $result->orderby('id', 'DESC')->skip($request->start)->take($request->length)->get();
            // return $result;
            foreach ($result as $value) {
                $amount_approved = AllFunctionService::trader_total_deposit($value->id, 'approved');
                $amount_pending = AllFunctionService::trader_total_deposit($value->id, 'pending');
                $amount_total = $amount_approved + $amount_pending;
                $data[$i]["c_name"]     = $value->name;
                $data[$i]["c_email"]    = $value->email;
                $data[$i]["ib_email"]  = AllFunctionService::user_email(IbService::instant_parent($value->id));
                $data[$i]["t_a_amount"] = $amount_approved;
                $data[$i]["t_p_amount"]  = $amount_pending;
                $data[$i]["t_amount"]   = $amount_total;
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
    // trading withdraw
    public function ibAdminReportDescriptionInnerTradingWithdraw(Request $request, $ib_id)
    {
        try {
            $data = array();
            $i = 0;
            // find traders under this ib
            $all_traders = AllFunctionService::sub_ib_traders_id($ib_id, 'all');
            $result = User::select('users.*');
            $result = $result->whereIn('users.id', $all_traders);
            $count = $result->count();
            $result = $result->orderby('id', 'DESC')
                ->skip($request->start)->take($request->length)->get();

            foreach ($result as $value) {
                $amount_pending = AllFunctionService::trader_total_withdraw($value->id, 'approved');
                $amount_approved = AllFunctionService::trader_total_withdraw($value->id, 'pending');
                $amount_total = $amount_approved + $amount_pending;
                $data[$i]["c_name"]     = $value->name;
                $data[$i]["c_email"]    = $value->email;
                $data[$i]["ib_email"]   = AllFunctionService::user_email(IbService::instant_parent($value->id));;
                $data[$i]["t_a_amount"] = $amount_pending;
                $data[$i]["t_p_amount"] = $amount_approved;
                $data[$i]["t_amount"]   = $amount_total;
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

    // self withdraw
    public function ibAdminReportDescriptionInnerSelfWithdraw(Request $request, $ib_id)
    {
        try {
            $data = array();
            $i = 0;
            $count = 0;

            // find self withdraw details
            $result = Withdraw::where('user_id', $ib_id)->select(
                'withdraws.transaction_id',
                'withdraws.amount',
                'withdraws.transaction_type',
                'withdraws.approved_status',
                'withdraws.created_at'
            )->where('wallet_type', 'ib');

            // count number of row
            $count = $result->count();
            // get result
            $result = $result->orderby('id', 'DESC')
                ->skip($request->start)->take($request->length)->get();

            foreach ($result as $withdraw) {
                if ($withdraw->approved_status == "P") {
                    $status = "<span class='text-warning'>Pending</span>";
                } elseif ($withdraw->approved_status == "A") {
                    $status = "<span class='text-success'>Approved</span>";
                } elseif ($withdraw->approved_status == "D") {
                    $status = "<span class='text-danger'>Declined</span>";
                }
                $data[$i]["txnid"]  = $withdraw->transaction_id;
                $data[$i]["amount"] = $withdraw->amount;
                $data[$i]["method"] = $withdraw->transaction_type;
                $data[$i]["status"] = $status;
                $data[$i]["date"]   = date('M, d Y H:i:s A', strtotime($withdraw->created_at));
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

    // ib commission
    public function ibAdminReportDescriptionInnerIBcommission(Request $request, $ib_id)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $_GET['order'][0]["column"];
        $orderDir = $_GET["order"][0]["dir"];
        $columns = ['trader_id', 'order_num', 'trading_account', 'symbol', 'amount', 'created_at'];
        $orderby = $columns[$order];
        try {
            $data = array();
            $j = 0;
            $i = 0;
            $count = 0;
            // find self withdraw details
            $result = IbIncome::where('ib_id', $ib_id)->select();
            // count number of row
            $count = $result->count();
            $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();

            foreach ($result as $row) {
                // find trader email
                $trader = User::where('id', $row->trader_id)->select('email')->first();

                $data[$i]["trader"]  = $trader->email;
                $data[$i]["ticket"]  = $row->order_num;
                $data[$i]["login"]   = $row->trading_account;
                $data[$i]["symbol"]  = $row->symbol;
                $data[$i]["amount"]  = $row->amount;
                $data[$i]["date"]    = date('d F y, h:i A', strtotime($row->created_at));
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

    // kyc reports
    public function ibAdminReportDescriptionInnerKycFetchData(Request $request, $id)
    {
        try {
            $result = KycVerification::where('user_id', $id)
                ->count();
            $recordsTotal = $result;
            $recordsFiltered = $result;

            $limit = '';
            $sortBy = $_REQUEST['order'][0]['dir'];
            $order_a =  $_REQUEST['order'];
            $order = $order_a[0]['dir'];
            $oc = $order_a[0]['column'];
            $ocd = $_REQUEST['columns'][$oc]['data'];

            if (isset($_REQUEST['start']) && $_REQUEST['length'] != -1) {
                $limit = " ORDER BY copy_rebalances.$ocd $sortBy LIMIT " . intval($_REQUEST['start']) . ", " . intval($_REQUEST['length']);
            }
            // select type= 0 for trader
            $result = KycVerification::where('user_id', $id)->select()
                ->get();
            $data = array();
            $i = 0;

            foreach ($result as $row) {
                $kyc_id_type = KycIdType::where('id', $row->doc_type)->first();
                $status = '';
                if ($row->status == 0) {
                    $status = '<span class="badge badge-light-warning">Pending</span>';
                } elseif ($row->status == 1) {
                    $status = '<span class="badge badge-light-success">Approved</span>';
                } else {
                    $status = '<span class="badge badge-light-danger">Decline</span>';
                }
                $data[$i]["date"]           = date('d F y, h:i A', strtotime($row->created_at));
                $data[$i]["document_type"]  = ($kyc_id_type->id_type) ? ucwords($kyc_id_type->id_type) : "";
                $data[$i]["status"]         = $status;
                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
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
    // comments reports
    public function ibAdminReportDescriptionInnerCommentFetchData(Request $request, $id)
    {
        try {
            $data = array();
            $j = 0;
            $i = 0;
            $count = 0;

            // select type= 0 for trader
            $result = Comment::where('user_id', $id)->select();
            // count number of rows
            $count = $result->count();
            // get comments
            $result = $result->orderby('id', 'DESC')
                ->skip($request->start)->take($request->length)->get();

            foreach ($result as $row) {
                $user = User::find($id);
                $data[$i]["date"]    = date('d F y, h:i A', strtotime($row->created_at));
                $data[$i]["comment"] = $row->comment;
                $data[$i]["actions"] = '<div class="btn-group">
                                        <button class="btn btn-flat-primary dropdown-toggle comment-actions" type="button" id="dropdownMenuButton100" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                        <i data-feather="edit"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton100">
                                            <a class="dropdown-item text-success btn-update-comment" href="javascript:void(0)" data-id="' . $id . '" data-name="' . $user->name . '" data-commentid="' . $row->id . '" data-comment="' . $row->comment . '" data-bs-toggle="modal" data-bs-target="#comment-edit"> <i data-feather="edit"></i>' . __('page.edit') . ' </a>
                                            <a class="dropdown-item text-danger btn-delete-comment" href="#" data-id="' . $row->id . '"><i data-feather="trash"></i> ' . __('page.delete') . '</a>
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
    // add new comment
    public function ibAdminInnerCommentAdd(Request $request)
    {

        $validation_rules = [
            'comment' => 'required',
            'ib_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $create = Comment::create([
                'user_id' => $request->ib_id,
                'type' => 'IB',
                'comment' => $request->comment,
                'commented_by' => auth()->user()->id,
            ]);
            if ($create) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => 'A new comment successfully added']);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => 'A new comment successfully added']);
                }
            }
        }
        return Response::json($request->ib_id);
    }
    // update exist comment
    public function ibAdminReportDescriptionInnerCommentUpdateData(Request $request)
    {
        $validation_rules = [
            'comment' => 'required|min:5',
            'ib_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $create = Comment::where('id', $request->comment_id)->Update([
                'comment' => $request->comment,
                'commented_by' => Auth::id(),
            ]);
            if ($create) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => 'A new comment successfully added']);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => 'A new comment successfully added']);
                }
            }
        }
        return Response::json($request->ib_id);
    }
    // delete exist comment
    public function ibAdminReportDescriptionInnerCommentDeleteData(Request $request)
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
                    return Response::json(['status' => true, 'message' => 'A new comment successfully added']);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => 'A new comment successfully added']);
                }
            }
        }
        return Response::json($request->ib_id);
    }
    // action tab start
    // Block unblock ib
    public function adminBlockedIB(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $update = User::where('id', $request->id)->update([
                'active_status' => ($request->request_for === 'block') ? 2 : 1
            ]);
            $user = User::find($request->id);
            if ($request->request_for === 'block') {
                $update_message = $user->name . " " . "successfully Blocked";
                $success_title = 'IB';
            } else {
                $update_message = $user->name . " " . "successfully Un-Blocked";
                $success_title = 'IB';
            }
            if ($update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }

    // google two step authentication
    public function googleTwoStepAuth(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $update = User::where('id', $request->id)->update([
                'g_auth' => ($request->request_for === 'enable') ? 1 : 0
            ]);
            $user = User::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Successfully Enabled";
                $success_title = 'Google Two Step Authentication';
            } else {
                $update_message = $user->name . " " . "Successfully Disabled";
                $success_title = 'Google Two Step Authentication';
            }
            if ($update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }

    // Email authentications
    public function emailAuth(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $update = User::where('id', $request->id)->update([
                'email_auth' => ($request->request_for === 'enable') ? 1 : 0
            ]);
            $user = User::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Successfully Enabled";
                $success_title = 'Email Authentication';
            } else {
                $update_message = $user->name . " " . "Successfully Disabled";
                $success_title = 'Email Authentication';
            }
            if ($update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }
    // Email verification
    public function emailVerification(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $update = User::where('id', $request->id)->update([
                'email_verification' => ($request->request_for === 'enable') ? 1 : 0
            ]);
            $user = User::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Successfully Enabled";
                $success_title = 'Email Verification';
            } else {
                $update_message = $user->name . " " . "successfully Disabled";
                $success_title = 'Email Verification';
            }
            if ($update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }
    // deposit operation
    public function ibDepositOperation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['deposit_operation' => ($request->request_for === 'enable') ? 1 : 0]
            );
            $user = User::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Successfully Enabled";
                $success_title = 'Deposit Operation';
            } else {
                $update_message = $user->name . " " . "successfully Disabled";
                $success_title = 'Deposit Operation';
            }
            if ($create_or_update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }
    // withdraw operation
    public function ibWithdrawOperation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['withdraw_operation' => ($request->request_for === 'enable') ? 1 : 0]
            );
            $user = User::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Successfully Enabled";
                $success_title = 'Withdraw Operation';
            } else {
                $update_message = $user->name . " " . "Successfully Disabled";
                $success_title = 'Withdraw Operation';
            }
            if ($create_or_update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }

    // Internal transfer
    public function ibInternalTransferOperation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['internal_transfer' => ($request->request_for === 'enable') ? 1 : 0]
            );
            $user = User::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Successfully Enabled";
                $success_title = 'Account to Wallet Transfer';
            } else {
                $update_message = $user->name . " " . "Successfully Disabled";
                $success_title = 'Account to Wallet Transfer';
            }
            if ($create_or_update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }
    // wta transfer operation
    public function wtaTransferOperation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['wta_transfer' => ($request->request_for === 'enable') ? 1 : 0]
            );
            $user = User::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Successfully Enabled";
                $success_title = 'Wallet to Account Transfer';
            } else {
                $update_message = $user->name . " " . "Successfully Disabled";
                $success_title = 'Wallet to Account Transfer';
            }
            if ($create_or_update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }

    // ib to ib transfer operation
    public function ibToIbTransferOperation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['ib_to_ib' => ($request->request_for === 'enable') ? 1 : 0]
            );
            $user = User::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Successfully Enabled";
                $success_title = 'IB To IB Transfer';
            } else {
                $update_message = $user->name . " " . "Successfully Disabled";
                $success_title = 'IB To IB Transfer';
            }
            if ($create_or_update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }
    // ib to trader transfer operation
    public function ibToTraderTransferOperation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['ib_to_trader' => ($request->request_for === 'enable') ? 1 : 0]
            );
            $user = User::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Successfully Enabled";
                $success_title = 'IB To Trader Transfer';
            } else {
                $update_message = $user->name . " " . "Successfully Disabled";
                $success_title = 'IB To Trader Transfer';
            }
            if ($create_or_update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }

    // trader to trader transfer operation
    public function traderToTraderTransferOperation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['trader_to_trader' => ($request->request_for === 'enable') ? 1 : 0]
            );
            $user = User::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Successfully Enabled";
                $success_title = 'Trader To Trader Transfer';
            } else {
                $update_message = $user->name . " " . "Successfully Disabled";
                $success_title = 'Trader To Trader Transfer';
            }
            if ($create_or_update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }

    // kyc verify operation
    public function kycVerifyOperation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['kyc_verify' => ($request->request_for === 'enable') ? 1 : 0]
            );
            $user = User::find($request->id);
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Successfully Enabled";
                $success_title = 'KYC Verification';
            } else {
                $update_message = $user->name . " " . "Successfully Disabled";
                $success_title = 'KYC Verification';
            }
            if ($create_or_update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => $update_message, 'success_title' => $success_title]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => $update_message, 'success_title' => $success_title]);
                }
            }
        }
        return Response::json($request->ib_id);
    }

    // set category for traders
    public function setIBcategory(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'category' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $update = User::where('id', $request->id)->update([
                'category_id' => $request->category
            ]);
            if ($update) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => 'Category successfully seted', 'success_title' => 'Category Set']);
                } else {
                    return Redirect()->back()->with(['status' => false, 'message' => 'Category successfully seted', 'success_title' => 'Category Set']);
                }
            }
        }
        return Response::json($request->ib_id);
    }

    // change ib password
    public function ibAdminChangePassword(Request $request)
    {
        $validation_rules = [
            'password' => 'required|confirmed|min:6', // Use 'confirmed' to validate password_confirmation field
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            // Attempt to update or create the user
            $user = User::updateOrCreate(
                ['id' => $request->trader_id],
                ['password' => Hash::make($request->password)]
            );

            // Attempt to update or create the log
            $log = Log::updateOrCreate(
                ['user_id' => $request->trader_id],
                ['password' => encrypt($request->password)]
            );

            if ($user && $log) {
                // Insert activity log
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has changed the password";
                activity('change password')
                    ->causedBy(auth()->user())
                    ->withProperties(['user_id' => $user->id]) // Use the user model instance
                    ->event('change password')
                    ->performedOn($user)
                    ->log($description);

                return response()->json([
                    'status' => true,
                    'id' => $request->trader_id,
                    'message' => 'Password has been changed',
                    'success_title' => 'Change password'
                ]);
            }

            return response()->json([
                'status' => false,
                'id' => $request->trader_id,
                'message' => 'Something went wrong! Please try again later.',
            ]);
        }
    }

    // change ib password mail
    public function ibAdminChangePasswordMail(Request $request)
    {
        $user = User::find($request->ib_id);
        // dd($user);
        $support_email = SystemConfig::select('support_email')->first();
        $support_email = ($support_email) ? $support_email->support_email : default_support_email();
        $email_data = [
            'clientName'                => ($user) ? $user->name : config('app.name') . ' IB',
            'clientUsername'            => ($user) ? $user->email : '',
            'admin'                     => Auth::user()->name,
            'accountActivationLink'     => route('login'),
            'emailSupport'              => $support_email,
            'clientTransactionPassword' => $request->password
        ];
        // sending mail
        if (Mail::to($user->email)->send(new ChangeTransactionPin($email_data))) {
            if ($request->ajax()) {
                return Response::json(['status' => true, 'message' => 'Mail successfully sent for Transaction pin Change', 'success_title' => 'Change Transaction Password']);
            } else {
                return Redirect()->back()->with(['status' => false, 'message' => 'Mail successfully sent for Transaction Pin Change', 'success_title' => 'Change Transaction Password']);
            }
        } else {
            return Response::json(['status' => false, 'message' => 'Mail sending failed, Please try again later!', 'success_title' => 'Change Transaction Password']);
        }
    }
    // ib admin change transaction password
    public function ibAdminChangeTransactionPass(Request $request)
    {
        $validation_rules = [
            'transaction_pin' => 'required|confirmed|same:transaction_pin_confirm|min:4',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        } else {
            // update user table
            $user = User::updateOrCreate(
                ['id' => $request->trader_id],
                ['transaction_password' => Hash::make($request->transaction_pin)]
            );
            // udpate log table
            $log = Log::updateOrCreate(
                ['user_id' => $request->trader_id] .
                    ['transaction_password' => encrypt($request->transaction_pin)]
            );
            if ($user && $log) {
                // insert activity log----------------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been change transaction pin";
                activity('change transaction pin')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($user)
                    ->event('change pin')
                    ->performedOn($user)
                    ->log($description);
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'id' => $request->trader_id,
                    'message' => 'Transaction pin has been changes',
                ]);
            }
            return Response::json([
                'status' => false,
                'id' => $request->trader_id,
                'message' => 'Transaction pin hasbeen changes',
            ]);
        }
    }
    // ib admin change transaction password mail
    public function ibAdminChangeTransactionPassMail(Request $request)
    {
        $user = User::find($request->ib_id);
        $support_email = SystemConfig::select('support_email')->first();
        $support_email = ($support_email) ? $support_email->support_email : default_support_email();
        $email_data = [
            'name'              => ($user) ? $user->name : config('app.name') . ' IB',
            'account_email'     => ($user) ? $user->email : '',
            'admin'             => Auth::user()->name,
            'login_url'         => route('login'),
            'support_email'     => $support_email,
            'transaction_pin'    => $request->password
        ];
        // sending mail
        if (Mail::to($user->email)->send(new ChangeTransactionPin($email_data))) {
            if ($request->ajax()) {
                return Response::json(['status' => true, 'message' => 'Mail successfully sent for Transaction pin Change', 'success_title' => 'Change Transaction Password']);
            } else {
                return Redirect()->back()->with(['status' => false, 'message' => 'Mail successfully sent for Transaction Pin Change', 'success_title' => 'Change Transaction Password']);
            }
        } else {
            return Response::json(['status' => false, 'message' => 'Mail sending failed, Please try again later!', 'success_title' => 'Change Transaction Password']);
        }
    }

    // ib admin change transaction password
    public function ibAdminResetTransactionPassword(Request $request)
    {
        // generate random password
        $random_password = Str::random(5) . '@' . random_int(11, 99);
        $hashed_random_password = Hash::make($random_password);

        $user = User::find($request->ib_id);
        $support_email = SystemConfig::select('support_email')->first();
        $support_email = ($support_email) ? $support_email->support_email : default_support_email();
        $email_data = [
            'name'              => ($user) ? $user->name : config('app.name') . ' IB',
            'account_email'     => ($user) ? $user->email : '',
            'admin'             => Auth::user()->name,
            'login_url'         => route('login'),
            'support_email'     => $support_email,
            'new_password'    => $random_password
        ];
        // get old password
        $old_password = $user->password;
        // update password
        $update = User::where('id', $request->ib_id)->update([
            'password' => $hashed_random_password,
            'tmp_pass' => 1
        ]);
        // update reset table

        $expire = date("Y-m-d h:i:s", strtotime('+1 hour'));

        $insert = PasswordReset::insert([
            'email' => $user->email,
            'old_password' => $old_password,
            'created_at' => date('Y-m-d h:i:s', time()),
            'token' => csrf_token(),
            'expried_on' => $expire
        ]);
        if ($update && $insert) {
            if (Mail::to($user->email)->send(new ResetPassword($email_data))) {
                return Response::json(['status' => true, 'message' => 'Mail successfully sent for reset password', 'success_title' => 'Reset password']);
            } else {
                return Response::json(['status' => false, 'message' => 'Mail sending failed, Please try again later!', 'success_title' => 'Reset Password']);
            }
        }
    }

    // ib admin change  password
    public function ibAdminResetPassword(Request $request)
    {
        // generate random password
        $random_password = str_random(5) . '@' . random_int(11, 99);
        // get old password
        $user = User::find($request->ib_id);
        $old_password = $user->password;
        $user = User::updateOrCreate(
            ['id' => $request->ib_id],
            [
                'password' => Hash::make($random_password),
                'tmp_pass' => 1,
            ]
        );

        // update log tabble for retrieve later
        $log = Log::updateOrCreate(
            ['user_id' => $request->ib_id],
            ['password' => encrypt($random_password)]
        );
        // update reset table
        $expire = date("Y-m-d h:i:s", strtotime('+1 hour'));

        $insert = PasswordReset::insert([
            'email' => $user->email,
            'old_password' => $old_password,
            'created_at' => date('Y-m-d h:i:s', time()),
            'token' => csrf_token(),
            'expried_on' => $expire
        ]);
        if ($user && $insert && $log) {
            // insert activity log----------------
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been reset IB password";
            activity('IB password reset')
                ->causedBy(auth()->user()->id)
                ->withProperties($user)
                ->event('reset password')
                ->performedOn($user)
                ->log($description);
            // end activity log-----------------

            return Response::json([
                'status' => true,
                'message' => 'Password Successfully reset, Please wait for sending mail',
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Somthing went wrong please try again later!',
        ]);
    }


    //delete sub ib function
    public function subIBDelete(Request $request)
    {
        $delete = IB::where('reference_id', $request->sub_id)->delete();
        if ($delete) {
            return Response::json([
                'success' => true,
                'message' => 'Sub-IB(s) Successfully Removed',
                'success_title' => 'Client Removed'
            ]);
        }

        return Response::json([
            'success' => false,
            'message' => 'Failed to  Removed Client',
            'success_title' => 'Client Removed'
        ]);
    }
    //delete trader from ib admin 
    public function traderDelete(Request $request)
    {
        $delete = IB::where('reference_id', $request->trader_id)->delete();
        if ($delete) {
            return Response::json([
                'success' => true,
                'message' => 'Trader Successfully Removed',
                'success_title' => 'Client Removed'
            ]);
        }
        return Response::json([
            'success' => false,
            'message' => 'Failed to  Removed Trader',
            'success_title' => 'Client Removed'
        ]);
    }

    // send IB welcome mail---------------------------------
    public function ibAdminWelcomeMail(Request $request, $ib_id)
    {
        // return $ib_id;
        $user = User::find($ib_id);
        $password_log = Log::where('user_id', $ib_id)->first();
        $activation_link = url('/ib/activation/ac/' . encrypt($user->id));

        $mail_status = EmailService::send_email('ib-registration', [
            'loginUrl'                   => $activation_link,
            'activation_link'            => $activation_link,
            'clientPassword'             => decrypt($password_log->password),
            'password'                   => decrypt($password_log->password),
            'clientTransactionPassword'  => decrypt($password_log->transaction_password),
            'transaction_password'       => decrypt($password_log->transaction_password),
            'server'                     => $request->platform,
            'user_id'                    => $user->id,
        ]);
        if ($mail_status) {
            // save activity log
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been send verification mail";
            activity('send welcome mail')
                ->causedBy(auth()->user()->id)
                ->withProperties($user)
                ->event('email send')
                ->performedOn($user)
                ->log($description);
            // end: activity log------------------
            return Response::json([
                'status' => true,
                'message' => 'Mail successfully send'
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong please try again later!'
            ]);
        }
    }
    // ending: send mail-----------------------------------
}
