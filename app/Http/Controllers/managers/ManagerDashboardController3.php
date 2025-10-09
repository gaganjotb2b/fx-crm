<?php

namespace App\Http\Controllers\managers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\KycIdType;
use App\Models\Category;
use App\Models\admin\SystemConfig;
use App\Models\ManagerUser;
use App\Models\IB;
use App\Models\TradingAccount;
use App\Services\common\UserService;
use App\Services\manager\ManagerAnalysisService;
use App\Services\manager\ManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\systems\VersionControllService;


use App\Models\ClientGroup;
use App\Models\UserDescription;
use App\Models\FinanceOp;
use App\Services\AllFunctionService;
use App\Services\BalanceService;
use App\Services\CombinedService;


class ManagerDashboardController3 extends Controller
{
    public function index()
    {
        // get manager country
        $referral_ib = ManagerService::manager_refer_link(auth()->user()->id, 'ib');
        $referral_client = ManagerService::manager_refer_link(auth()->user()->id, 'trader');
        return view(
            'managers.index',
            [
                'country' => UserService::get_country(),
                'ib_referral_link' => $referral_ib,
                'trader_referral_link' => $referral_client,
            ]
        );
    }
    // get analysis data
    public function manager_analysis(Request $request)
    {
        //load from service
        $data = ManagerAnalysisService::manager_analysis([
            'search_email' => auth()->user()->email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        return Response::json($data);
    }
    public function finance_details(Request $request)
    {
        // pending deposit all
        $pending_deposit_all = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'P'
        ]);
        $pending_deposit_direct = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'P',
            'direct' => true,
        ]);
        $pending_deposit_affiliat = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'P',
            'affiliated' => true,
        ]);
        $approved_deposit_all = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'A',
        ]);
        $approved_deposit_direct = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'A',
            'direct' => true,
        ]);
        $approved_deposit_affiliat = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'A',
            'affiliated' => true,
        ]);
        $approved_withdraw_all = ManagerAnalysisService::withdraw([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'A',
        ]);
        $approved_withdraw_direct = ManagerAnalysisService::withdraw([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'A',
            'direct' => true,
        ]);
        $approved_withdraw_affiliate = ManagerAnalysisService::withdraw([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'A',
            'arriliated' => true,
        ]);
        $pending_withdraw_all = ManagerAnalysisService::withdraw([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'P',
        ]);
        $pending_withdraw_direct = ManagerAnalysisService::withdraw([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'P',
            'direct' => true,
        ]);
        $pending_withdraw_affiliat = ManagerAnalysisService::withdraw([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'P',
            'affiliated' => true,
        ]);
        return ([
            // deposit pending
            'pending_deposit_all' => $pending_deposit_all,
            'pending_deposit_direct' => $pending_deposit_direct,
            'pending_deposit_affiliated' => $pending_deposit_affiliat,
            // deposit approved
            'approved_deposit_all' => $approved_deposit_all,
            'approved_deposit_direct' => $approved_deposit_direct,
            'approved_deposit_affiliated' => $approved_deposit_affiliat,
            // declined deposit all
            // 'declined_deposit_all' => ManagerAnalysisService::deposit([
            //     'manager_id' => auth()->user()->id,
            //     'approved_status' => 'D'
            // ]),
            // // declined deposit direct
            // 'declined_deposit_direct' => ManagerAnalysisService::deposit([
            //     'manager_id' => auth()->user()->id,
            //     'approved_status' => 'D',
            //     'direct' => true,
            // ]),
            // // declined deposit affiliated
            // 'declined_deposit_affiliated' => ManagerAnalysisService::deposit([
            //     'manager_id' => auth()->user()->id,
            //     'approved_status' => 'D',
            //     'affiliated' => true,
            // ]),
            // deposit total
            'total_deposit_all' => ($approved_deposit_all + $pending_deposit_all),
            'total_deposit_direct' => ($approved_deposit_direct + $pending_deposit_direct),
            'total_deposit_affiliated' => ($approved_deposit_affiliat + $pending_deposit_affiliat),
            // get all category withdraw
            /***************************************************** */
            // withdraw approved
            'approved_withdraw_all' => $approved_withdraw_all,
            'approved_withdraw_direct' => $approved_withdraw_direct,
            'approved_withdraw_affiliated' => $approved_withdraw_affiliate,
            // withdraw pending
            'pending_withdraw_all' => $pending_withdraw_all,
            'pending_withdraw_direct' => $pending_withdraw_direct,
            'pending_withdraw_affiliated' => $pending_withdraw_affiliat,
            // withdraw declined
            // 'declined_withdraw_all' => ManagerAnalysisService::withdraw([
            //     'manager_id' => auth()->user()->id,
            //     'approved_status' => 'D',
            // ]),
            // 'declined_withdraw_direct' => ManagerAnalysisService::withdraw([
            //     'manager_id' => auth()->user()->id,
            //     'approved_status' => 'D',
            //     'direct' => true,
            // ]),
            // 'declined_withdraw_affiliated' => ManagerAnalysisService::withdraw([
            //     'manager_id' => auth()->user()->id,
            //     'approved_status' => 'D',
            //     'affiliated' => true,
            // ]),
            'total_withdraw_all' => ($approved_deposit_all + $pending_deposit_all),
            'total_withdraw_direct' => ($approved_deposit_direct + $pending_deposit_direct),
            'total_withdraw_affiliated' => ($approved_deposit_affiliat + $pending_deposit_affiliat),

        ]);
    }
    // get total client details
    public function client_detailes(Request $request)
    {
        return ([
            // active clients
            'active_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'active',
                'user_type' => 0,
            ]),
            'active_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'active',
                'direct' => true,
                'user_type' => 0,
            ]),
            'active_affiliated' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'active',
                'affiliated' => true,
                'user_type' => 0,
            ]),
            // disabled clients
            'disabled_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'disabled',
                'user_type' => 0,
            ]),
            'disabled_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'disabled',
                'affiliated' => true,
                'user_type' => 0,
            ]),
            'disabled_affiliated' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'disabled',
                'affiliated' => true,
                'user_type' => 0,
            ]),
            // lived clients
            'live_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'live',
                'user_type' => 0,
            ]),
            'live_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'live',
                'direct' => true,
                'user_type' => 0,
            ]),
            'live_affiliated' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'live',
                'affiliatd' => true,
                'user_type' => 0,
            ]),
            // demo clients
            'demo_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'demo',
                'user_type' => 0,
            ]),
            'demo_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'demo',
                'direct' => true,
                'user_type' => 0,
            ]),
            'demo_affiliated' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'demo',
                'affiliatd' => true,
                'user_type' => 0,
            ]),
        ]);
    }
    // ib clients details
    public function ib_clients_detailes(Request $request)
    {
        return ([
            // active clients
            'active_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'active',
                'user_type' => 4,
            ]),
            'active_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'active',
                'direct' => true,
                'user_type' => 4,
            ]),
            'active_affiliated' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'active',
                'affiliated' => true,
                'user_type' => 4,
            ]),
            // disabled clients
            'disabled_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'disabled',
                'user_type' => 4,
            ]),
            'disabled_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'disabled',
                'affiliated' => true,
                'user_type' => 4,
            ]),
            'disabled_affiliated' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'disabled',
                'affiliated' => true,
                'user_type' => 4,
            ]),
        ]);
    }
    // get client deposit details
    public function deposit_detailes(Request $request)
    {
        $pending_deposit_all = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'P'
        ]);
        $pending_deposit_direct = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'P',
            'direct' => true,
        ]);
        $pending_deposit_affiliat = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'P',
            'affiliated' => true,
        ]);
        $approved_deposit_all = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'A',
        ]);
        $approved_deposit_direct = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'A',
            'direct' => true,
        ]);
        $approved_deposit_affiliat = ManagerAnalysisService::deposit([
            'manager_id' => auth()->user()->id,
            'approved_status' => 'A',
            'affiliated' => true,
        ]);
        return ([
            // pending deposit all 
            'pending_deposit_all' => $pending_deposit_all,
            'pending_deposit_direct' => $pending_deposit_direct,
            'pending_deposit_affiliated' => $pending_deposit_affiliat,
            // approved deposit all
            'approved_deposit_all' => $approved_deposit_all,
            'approved_deposit_direct' => $approved_deposit_direct,
            'approved_deposit_affiliated' => $approved_deposit_affiliat,
            // declined deposit all
            // 'declined_deposit_all' => ManagerAnalysisService::deposit([
            //     'manager_id' => auth()->user()->id,
            //     'approved_status' => 'D'
            // ]),
            // // declined deposit direct
            // 'declined_deposit_direct' => ManagerAnalysisService::deposit([
            //     'manager_id' => auth()->user()->id,
            //     'approved_status' => 'D',
            //     'direct' => true,
            // ]),
            // // declined deposit affiliated
            // 'declined_deposit_affiliated' => ManagerAnalysisService::deposit([
            //     'manager_id' => auth()->user()->id,
            //     'approved_status' => 'D',
            //     'affiliated' => true,
            // ]),
            'total_deposit_all' => ($pending_deposit_all + $approved_deposit_all),
            'total_deposit_direct' => ($pending_deposit_direct + $approved_deposit_direct),
            'total_deposit_affiliated' => ($pending_deposit_affiliat + $approved_deposit_affiliat)
        ]);
    }
    
    
        public function lead_manager_report(Request $request)
    {
        // kyc selector ajax
        $address_proof = KycIdType::select();
        if ($request->ajax()) {
            $document_type = $address_proof->where('group', $request->perpose)->get();
            $select_options = '';
            foreach ($document_type as $key => $value) {
                $select_options .= '<option value="' . $value->id_type . '">' . $value->id_type . '</option>';
            }
            return Response::json($select_options);
        }
        $countries = Country::all();
        $crmVarsion = VersionControllService::check_version();

        $categories =  Category::where('client_type', 'trader')->select()->get();
        $system_config = SystemConfig::select(
            'platform_type',
            'acc_limit as account_limit'
        )->first();
        $single_platform = true;
        if ($system_config->platform_type === 'both') {
            $single_platform = false;
        }
        $params = [
            'single_platform' => $single_platform, // or some condition logic
            'accountType' => 'live' // or any account type you need to pass
        ];
        return view(
            'managers.manager-lead-manager-report',
            [
                'categories' => $categories,
                'countries' => $countries,
                'platform' => $system_config->platform_type,
                'varsion' => $crmVarsion,
                'address_proof' => $address_proof->where('group', 'id proof')->get(),
                'params' => $params,
            ]
        );
    }
    
    public function trader_manager_dt_fetch_data(Request $request)
    {
        try {
            if ($request->op === 'description') {
                return $this->trader_manager_dt_description($request, $request->id);
            }
            if ($request->op === 'description_table') {
                return $this->trader_manager_dt_description($request, $request->id);
            }

            $columns = ['name', 'email', 'phone', 'users.created_at', 'active_status', 'active_status'];
            $orderby = $columns[$request->order[0]['column']];
            $result = User::where('users.type', 0)
                ->select(
                    'users.*'
                )
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id');


            $is_lead = false; 

            $result = $result->where('users.is_lead', $is_lead);
               
            // filter by auth manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $users_id);
            }
            //---------------------------------------------------------------------------------------------
            //Filter Start
            //---------------------------------------------------------------------------------------------
            // filter by category
            if ($request->category != "") {
                $result = $result->where('category_id', $request->category);
            }
            // Filter by verfification status
            if ($request->verification_status != "") {
                $result = $result->where('users.kyc_status', $request->verification_status);
            }

            //Filter By Active Status
            if ($request->active_status != "") {
                $result = $result->where('users.active_status', $request->active_status);
            }
            //Filte By IB / No IB
            if ($request->ib === 'ib') {
                $ib = IB::select('reference_id')
                    ->join('users', 'ib.ib_id', '=', 'users.id')
                    ->pluck('reference_id');
                $result = $result->whereIn('users.id', $ib);
            }
            if ($request->ib === 'no_ib') {
                $ib = IB::select('reference_id')
                    ->join('users', 'ib.ib_id', '=', 'users.id')
                    ->pluck('reference_id');
                $result = $result->whereNotIn('users.id', $ib);
            }
            //filter by Date
            $start_date = $request->input('value_from_start_date');
            $end_date = $request->input('value_from_end_date');

            if ($start_date != "") {
                $result = $result->whereDate('users.created_at', '>=', date('Y-m-d', strtotime($start_date)));
            }

            if ($end_date != "") {
                $result = $result->whereDate('users.created_at', '<=', date('Y-m-d', strtotime($end_date)));
            }
            //Filter By Trader Name / Email / Phone / Country
            if ($request->info != "") {
                $trader_info = $request->info;
                $user_id = User::select('countries.name')->where(function ($query) use ($trader_info) {
                    $query->where('users.name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $trader_info . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }

            //Filter By Country
            if ($request->country != "") {
                $trader_country = $request->country;
                $user_id = User::select('countries.name')->where(function ($query) use ($trader_country) {
                    $query->where('countries.name', 'LIKE', '%' . $trader_country . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }

            //Filter By IB Name / Email /Phone /Country
            if ($request->ib_info != "") {
                $ib = $request->ib_info;
                $user_id = User::select('countries.name')->where('users.type', 4)->where(function ($query) use ($ib) {
                    $query->where('users.name', 'LIKE', '%' . $ib . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $ib . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $ib . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $ib . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $trader_id = IB::whereIn('ib_id', $user_id)->get()->pluck('reference_id');
                $result = $result->whereIn('users.id', $trader_id);
            }

            // filter by auth manager
            // filter for pro crm
            if ($request->desk_manager != "") {
                $manager = $request->desk_manager;
                $manager_id = User::select('id')->where(function ($query) use ($manager) {
                    $query->where('name', 'LIKE', '%' . $manager . '%')
                        ->orwhere('email', 'LIKE', '%' . $manager . '%')
                        ->orwhere('phone', 'LIKE', '%' . $manager . '%');
                })->get()->pluck('id');
                $users_id = ManagerUser::select('user_id')->where('manager_id', $manager_id)->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $users_id);
            }


            //filter by trading account
            if ($request->trading_acc != "") {
                $users_id = TradingAccount::select('user_id')
                    ->where('account_number', $request->trading_acc)->first();
                $trader_id = IB::whereIn('ib.reference_id', $users_id)->pluck('ib_id');
                $result = $result->whereIn('users.id', $trader_id);
            }

            $count = $result->count(); // <------count total rows
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            foreach ($result as $key => $value) {
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
                        $check_uncheck = '<span class="text-warning">Pending</span>';
                        $kyc_color = 'text-warning';
                    } elseif ($value->kyc_status == 1) {
                        $check_uncheck = '<span class="text-success">Verified</span>';
                        $kyc_color = 'text-success';
                    } else {
                        $check_uncheck = '<span class="text-danger">Unverified</span>';
                        $kyc_color = 'text-danger';
                    }
                } else {
                    $check_uncheck = '<span class="text-danger">Unverified</span>';
                    $kyc_color = 'text-danger';
                }
                
                // $dashboard = '<a class="dropdown-item" href="' . route("admin.trader.dashboard", ["id" => $value->id]) . '">Dashboard</a>';

                
                // $delete = '<span class="dropdown-item delete-trader" data-id='.$value->id.'>
                // Delete</span>';

                $conver_to_lead = '<span class="dropdown-item delete-trader" data-id='.$value->id.'>
                Convert to Lead</span>';
                $drop_down_items = $conver_to_lead;
                

                // tabl column
                // -------------------------------------
                $data[] = [
                    "name" => '<a data-id="' . $value->id . '" href="#" class="dt-description justify-content-start text-truncate"><span class="w"> <i class="plus-minus text-dark" data-feather="plus"></i> </span><span class="' . $kyc_color . '">' . $value->name . '</span></a>',
                    "email" => $value->email,
                    "phone" => ucwords($value->phone),
                    "joined" => date('d F y, h:i A', strtotime($value->created_at)),
                    "status" => $status,
                    "actions" => '<div class="d-flex justify-content-between">
                                    <a href="#" class="more-actions dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                            ' . $drop_down_items . '
                                    </div>
                                </div>',
                ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
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

    public function convert_to_lead($id){
        $trader = User::findOrFail($id);

        if ($trader){
            $trader->is_lead = false; // Set to false (0) to make it a lead
            $trader->save();
            return response()->json(['success' => true, 'message' => 'Trader convert to lead successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Trader not found'], 404);
        
    }

    public function trader_manager_inner_fetch_data(Request $request, $id)
    {
        try {
            $columns = ['account_number', 'platform', 'leverage', 'group_id', 'group_id', 'trading_accounts.created_at'];
            $orderby = $columns[$request->order[0]['column']];
            // select type= 0 for trader
            $result = User::where('users.id', $id)->select()
                ->join('trading_accounts', 'users.id', '=', 'trading_accounts.user_id')
                ->where('trading_accounts.account_status', 1);
            $count = $result->count(); // <------count total rows
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                $groups = ClientGroup::where('id', $value->group_id)->select()->first();

                $data[$i]["account_number"] = $value->account_number;
                $data[$i]["platform"]         = $groups->server;
                $data[$i]["leverage"]         = $value->leverage;
                $data[$i]["group"]          = $groups->group_id;
                $data[$i]["raw_group"]      = $groups->group_name;
                $data[$i]["created_at"]     = date('d F y, h:i A', strtotime($value->created_at));
                // $data[$i]["actions"]        = '<a href="#" class="more-actions"><i data-feather="more-vertical"></i></a> <i data-feather="edit"></i>';
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

    public function trader_manager_dt_description(Request $request, $id)
    {
        $user = User::find($id);
        $user_descriptions = UserDescription::where('user_id', $user->id)->first(); //<---user description
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png'; //<----avatar url
        }

        $active_status = ($user->active_status == 2) ? 'checked' : ' ';
        $two_step_status = ($user->g_auth == 1) ? 'checked' : ' '; //google 2 step auth status
        $email_a_status = ($user->email_auth == 1) ? 'checked' : ' '; // email auth status
        $email_v_status = ($user->email_verification == 1) ? 'checked' : ' '; // email verification status
        // finance operation
        $finance_operation = FinanceOp::where('user_id', $user->id)->first();
        $deposit_operation = (($finance_operation) && $finance_operation->deposit_operation == 1) ? 'checked' : ' '; // deposit_operation enable or disable
        $withdraw_operation = (($finance_operation) && $finance_operation->withdraw_operation == 1) ? 'checked' : ' '; // deposit_operation enable or disable
        $internal_transfer = (($finance_operation) && $finance_operation->internal_transfer == 1) ? 'checked' : ' '; // deposit_operation enable or disable

        $wta_transfer = (($finance_operation) && $finance_operation->wta_transfer == 1) ? 'checked' : ' '; // wallet to account operation enable or disable
        $trader_to_trader = (($finance_operation) && $finance_operation->trader_to_trader == 1) ? 'checked' : ' '; // tader to trader operation enable or disable

        $trader_to_ib = (($finance_operation) && $finance_operation->trader_to_ib == 1) ? 'checked' : ' '; // trader to ib operation enable or disable
        $kyc_verify = ($user->kyc_status == 1) ? 'checked' : ' '; // kyc verify enable or disable

        $set_category = '';
        $categories = Category::where('client_type', 'trader')->select()->get();
        foreach ($categories as $category) {
            $set_category .= '<option value="' . $category->id . '">' . ucwords($category->name) . '</option>';
        }
        // kyc status
        if (isset($user->kyc_status)) {
            if ($user->kyc_status == 2) {
                $check_uncheck = '<span class="text-warning">Pending</span>';
            } elseif ($user->kyc_status == 1) {
                $check_uncheck = '<span>Verified</span>';
            } else {
                $check_uncheck = '<span class="text-danger">Unverified</span>';
            }
        } else {
            $check_uncheck = '<span class="text-danger">Unverified</span>';
        }

        // FIND CATEGORY
        $category = Category::find($user->category_id);
        if (isset($category->name)) {
            $category = ucwords($category->name);
        } else {
            $category = ucwords('N/A');
        }

        // Find IB
        $ib = IB::where('reference_id', $id)
            ->join('users', 'ib.ib_id', '=', 'users.id')
            ->first();

        if (isset($ib->name)) {
            $ib_name = $ib->name;
        } else {
            $ib_name = 'N/A';
        }
        // find manager
        $manager = ManagerUser::where('manager_users.user_id', $id)->where('group_type', 1)
            ->join('users', 'manager_users.manager_id', '=', 'users.id')
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
            ->first();

        if (isset($manager->name)) {
            $manager_name = $manager->name;
        } else {
            $manager_name = 'N/A';
        }

        // trader total balance
        $total_balance = AllFunctionService::trader_total_balance($id);
        // $total_withdraw = AllFunctionService::trader_total_withdraw($id);
        $total_withdraw = BalanceService::trader_total_withdraw($id);
        $total_deposit = AllFunctionService::trader_total_deposit($id);

        //show or hide convert to ib button
        $convert_to_ib = "";
        if (CombinedService::is_combined()) {
            if (CombinedService::is_combined('client', $user->id)) {
                $convert_to_ib = '<button type="button" class="btn btn-danger remove-ib-access mb-1" data-user="' . $user->id . '">Remove IB Access</button>';
            } else {
                $convert_to_ib = '<button type="button" class="btn btn-warning convert-to-ib mb-1" data-user="' . $user->id . '">Convert To IB</button>';
            }
        }
        // manager info for pro crm
        $manager_signe = '';
        if (VersionControllService::check_version() === 'pro') {
            $manager_signe = '<button type="button" class="btn btn-primary float-end btn-account-manager" data-user="' . $user->id . '">' . __('page.assign_to_account_manager') . '</button>';
        }

        $checkVarsion = new VersionControllService();
        $crmVarsion = $checkVarsion->check_version();


        $description = '<tr class="description" style="display:none">
            <td colspan="6">
                <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="rounded-0 w-75">
                                <table class="table table-responsive tbl-balance">
                                    <tr>
                                        <th>' . __('page.wallet_balance') . '</th>
                                        <td class="btn-load-balance">
                                            <span>&dollar;<span class="balance-value amount"> ' . $total_balance . '</span></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.wallet_deposit') . '</th>
                                        <td class="btn-load-equity">
                                            <span>&dollar;<span class="balance-value amount"> ' . $total_deposit . '</span></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.wallet_withdraw') . '</th>
                                        <td class="btn-load-equity">
                                            <span>&dollar;<span class="balance-value amount"> ' . $total_withdraw . '</span></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justfy-content-between">
                            <div class="rounded-0 w-100">
                                <table class="table table-responsive tbl-trader-details">
                                    <tr>
                                        <th>' . __('page.category') . '</th>
                                        <td>' . $category . '</td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.kyc') . '</th>
                                        <td>' . $check_uncheck . '</td>
                                    </tr>
                                    <tr>
                                        <th>' . __('page.ib') . '</th>
                                        <td>' . $ib_name . '</td>
                                    </tr>';

        if ($crmVarsion === 'pro') {
            $description .= '<tr>
                                        <th>' . __('page.account_manager') . '</th>
                                        <td>' . $manager_name . '</td>
                                    </tr>';
        }
        $description .= ' </table>
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
                                            <li class="nav-item">
                                                <a data-id="' . $id . '" class="nav-link trd-nav-link active" id="trading_account-tab-fill-' . $user->id . '" data-bs-toggle="tab" href="#trading_account-fill-' . $user->id . '" role="tab" aria-controls="home-fill" aria-selected="true">' . __('page.trading_account') . '</a>
                                            </li>
                                            <li class="nav-item border-end-2 border-end-secondary">
                                                <a data-id="' . $id . '" class="nav-link trd-nav-link deposit-tab deposit-tab-fill" id="deposit-tab-fill-' . $user->id . '" data-bs-toggle="tab" href="#deposit-fill-' . $user->id . '" role="tab" aria-controls="deposit-fill" aria-selected="false">' . __('page.deposit') . '</a>
                                            </li>
                                            <li class="nav-item">
                                                <a data-id="' . $id . '" class="nav-link trd-nav-link withdraw-tab-fill" id="withdraw-tab-fill-' . $user->id . '" data-bs-toggle="tab" href="#withdraw-fill-' . $user->id . '" role="tab" aria-controls="withdraw-fill" aria-selected="false">' . __('page.withdraw') . '</a>
                                            </li>
                                            <li class="nav-item">
                                                <a data-id="' . $id . '" class="nav-link trd-nav-link bonus-tab-fill" id="bonus-tab-fill-' . $user->id . '" data-bs-toggle="tab" href="#bonus-fill-' . $user->id . '" role="tab" aria-controls="bonus-fill" aria-selected="false">' . __('page.bonus') . '</a>
                                            </li>
                                            <li class="nav-item btn-kyc-tab1">
                                                <a data-id="' . $id . '" class="nav-link trd-nav-link kyc-tab-fill" id="kyc-tab-fill-' . $user->id . '" data-bs-toggle="tab" href="#kyc-fill-' . $user->id . '" role="tab" aria-controls="kyc-fill" aria-selected="false">' . __('page.kyc') . '</a>
                                            </li>
                                            <li class="nav-item btn-comments-tab1">
                                                <a data-id="' . $id . '" class="nav-link trd-nav-link comment-tab-fill" id="comment-tab-fill-' . $user->id . '" data-bs-toggle="tab" href="#comment-fill-' . $user->id . '" role="tab" aria-controls="comment-fill" aria-selected="false">' . __('page.comments') . '</a>
                                            </li>
                                            <li class="nav-item border-end-2 btn-settings-tab1">
                                                <a data-id="' . $id . '" class="nav-link trd-nav-link" id="action-tab-fill-' . $user->id . '" data-bs-toggle="tab" href="#action-fill-' . $user->id . '" role="tab" aria-controls="action-fill" aria-selected="false">' . __('page.settings') . '</a>
                                            </li>
                                            <li class="nav-item border-end-2 ">
                                                <a href="#" class="nav-link trd-nav-link more-actions" data-bs-toggle="dropdown" aria-expanded="false">
                                                    More Options
                                                    <i data-feather="more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a data-id="' . $id . '" class="nav-link trd-nav-link dd-child dropdown-item btn-block btn-internal-trans" id="internal-tab-fill-' . $user->id . '" data-bs-toggle="tab" href="#internal-fill-' . $user->id . '" role="tab" aria-controls="internal-fill" aria-selected="false">' . __('page.internal-transfer') . '</a>
                                                    <a data-id="' . $id . '" class="nav-link dropdown-item dd-child trd-nav-link btn-block btn-external-trans" id="btn-external-transfer-' . $user->id . '" data-bs-toggle="tab" href="#external-fill-' . $user->id . '" role="tab" aria-controls="external-fill" aria-selected="false">' . __('page.external-transfer') . '</a>
                                                    <a data-id="' . $id . '" class="nav-link trd-nav-link dd-child dropdown-item btn-settings-tab2" id="action-tab-fill-2-' . $user->id . '" data-bs-toggle="tab" href="#action-fill-' . $user->id . '" role="tab" aria-controls="action-fill" aria-selected="false">' . __('page.settings') . '</a>
                                                    <a data-id="' . $id . '" class="nav-link trd-nav-link dd-child dropdown-item comment-tab-fill btn-comment-tab2" id="comment-tab-fill-2-' . $user->id . '" data-bs-toggle="tab" href="#comment-fill-' . $user->id . '" role="tab" aria-controls="comment-fill" aria-selected="false">' . __('page.comments') . '</a>
                                                    <a data-id="' . $id . '" class="nav-link trd-nav-link dd-child dropdown-item kyc-tab-fill btn-kyc-tab2" id="kyc-tab-fill-2-' . $user->id . '" data-bs-toggle="tab" href="#kyc-fill-' . $user->id . '" role="tab" aria-controls="kyc-fill" aria-selected="false">' . __('page.kyc') . '</a>
                                                </div>
                                            </li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="trading_account-fill-' . $user->id . '" role="tabpanel" aria-labelledby="home-tab-fill">
                                                <button type="button" class="btn btn-primary btn-add-account mb-1" data-user="' . $user->id . '">Add Trading Account</button>
                                                <div class="table-responsive">
                                                    <table class="datatable-inner trading_account table dt-inner-table-dark' . table_color() . ' m-0"  style="margin:0px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>' . __('page.account-number') . '</th>
                                                                <th>' . __('page.platform') . '</th>
                                                                <th>' . __('page.leverage') . '</th>
                                                                <th>' . __('page.GROUP') . '</th>
                                                                <th>' . __('page.Raw_Group') . '</th>
                                                                <th>' . __('page.Openning_Date') . '</th>
                                                                <!--<th>Actions</th> -->
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="deposit-fill-' . $user->id . '" role="tabpanel" aria-labelledby="deposit-tab-fill">
                                                <div class="table-responsive">
                                                    <table class="datatable-inner deposit table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>' . __('page.date') . '</th>
                                                                <th>' . __('page.amount') . '</th>
                                                                <th>' . __('page.Method') . '</th>
                                                                <th>' . __('page.status') . '</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="withdraw-fill-' . $user->id . '" role="tabpanel" aria-labelledby="withdraw-tab-fill">
                                                <div class="table-responsive">
                                                    <table class="datatable-inner withdraw table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>' . __('page.date') . '</th>
                                                                <th>' . __('page.amount') . '</th>
                                                                <th>' . __('page.Method') . '</th>
                                                                <th>' . __('page.status') . '</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="bonus-fill-' . $user->id . '" role="tabpanel" aria-labelledby="bonus-tab-fill">
                                                <table class="datatable-inner bonus table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>Credit Date</th>
                                                            <th>' . __('page.amount') . '</th>
                                                            <th>Type</th>
                                                            <th>Account number</th>
                                                            <th>Credit expire</th>
                                                            <th>Created By</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="kyc-fill-' . $user->id . '" role="tabpanel" aria-labelledby="kyc-tab-fill">
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
                                            <div class="tab-pane" id="comment-fill-' . $user->id . '" role="tabpanel" aria-labelledby="comment-tab-fill">
                                                <button type="button" class="btn btn-primary float-end btn-add-comment mb-2" data-id="' . $id . '" data-name="' . $user->name . '" data-bs-toggle="modal" data-bs-target="#primary"><i data-feather="plus"></i> Add Comment</button>
                                                <table class="datatable-inner comment table dt-inner-table-dark m-0 mt-3"  style="margin:0px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>' . __('page.commented_date') . '</th>
                                                            <th>' . __('page.comments') . '</th>
                                                            <th style="width: 66px;">' . __('page.actions') . '</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="action-fill-' . $user->id . '" role="tabpanel" aria-labelledby="action-tab-fill">
                                                <table class="action-table-inner-dark action table m-0"  style="margin:0px !important;">
                                                    <tbody>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="block-unblock-swtich' . $id . '">Unblock / Block</label>
                                                                <div class="form-check form-switch form-check-danger">
                                                                    <input type="checkbox" class="form-check-input switch-user-block block-unblock-swtich" id="block-unblock-swtich' . $id . '" value="' . $id . '" ' . $active_status . '/>
                                                                    <label class="form-check-label" for="block-unblock-swtich' . $id . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class=" text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <div class="row">
                                                                    <div class="d-grid col-lg-6 col-md-12"></div>
                                                                    <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                                        <button type="button" id="reset-password-btn' . $id . '" data-id="' . $id . '" data-name="' . $user->name . '" class="reset-password-btn btn btn-primary">' . __('page.reset_password') . '</button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="2-step-swtich' . $id . '">' . __('page.google_2_step_authentication') . '</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input 2-step-swtich" id="2-step-swtich' . $id . '" value="' . $id . '" ' . $two_step_status . '/>
                                                                    <label class="form-check-label" for="2-step-swtich' . $id . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class=" text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <div class="row">
                                                                    <div class="d-grid col-lg-6 col-md-12"></div>
                                                                    <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                                        <button type="button" data-id="' . $id . '" data-name="' . $user->name . '" id="transaction-pin-reset" class=" transaction-pin-reset btn btn-primary">' . __('page.transaction_pin_reset') . '</button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="email-a-swtich' . $id . '">' . __('page.email_authentication') . '</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input email-a-swtich" id="email-a-swtich' . $id . '" value="' . $id . '" ' . $email_a_status . '/>
                                                                    <label class="form-check-label" for="email-a-swtich' . $id . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class=" text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <div class="row">
                                                                    <div class="d-grid col-lg-6 col-md-12"></div>
                                                                    <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                                        <button type="button" class="btn btn-primary change-password-btn" data-id="' . $id . '" data-name="' . $user->name . '" data-bs-toggle="modal" data-bs-target="#password-change-modal"><i data-feather="trello"></i>' . __('page.change_password') . '</button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="email-v-switch' . $id . '">Email Verification</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input email-v-switch" id="email-v-switch' . $id . '" value="' . $id . '" ' . $email_v_status . '/>
                                                                    <label class="form-check-label" for="email-v-switch' . $id . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class=" text-end border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <div class="row">
                                                                    <div class="d-grid col-lg-6 col-md-12"></div>
                                                                    <div class="d-grid col-lg-6 col-md-12 mb-1 mb-lg-0">
                                                                        <button type="button" class="btn btn-primary change-pin-btn" data-id="' . $id . '" data-name="' . $user->name . '" data-bs-toggle="modal" data-bs-target="#pin-change-modal"><i data-feather="trello"></i> ' . __('page.transaction_pin_change') . '</button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="deposit-switch' . $id . '">' . __('page.deposit') . '</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input deposit-switch" id="deposit-switch' . $id . '"  value="' . $id . '" ' . $deposit_operation . '/>
                                                                    <label class="form-check-label" for="deposit-switch' . $id . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <div class="row">
                                                                    <div class="d-grid col-lg-6">
                                                                        <select class="select2 form-select" id="set-category' . $id . '">
                                                                            <option value="">' . __('page.set_category') . '</option>
                                                                            ' . $set_category . '
                                                                        </select>
                                                                    </div>
                                                                    <div class="d-grid col-lg-6">
                                                                        <button type="button" id="save-category' . $id . '" data-id="' . $id . '" class="btn btn-primary save-category">' . __('page.Save_category') . '</button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="withdraw-switch' . $id . '">' . __('page.withdraw') . '</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input withdraw-switch" id="withdraw-switch' . $id . '" value="' . $id . '" ' . $withdraw_operation . '/>
                                                                    <label class="form-check-label" for="withdraw-switch' . $id . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="kyc_verify-switch' . $id . '">KYC Verify</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input kyc_verify-switch" id="kyc_verify-switch' . $id . '" value="' . $id . '" ' . $kyc_verify . '/>
                                                                    <label class="form-check-label" for="kyc_verify-switch' . $id . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="atw-switch' . $id . '">Account To Wallet</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input atw-switch" id="atw-switch' . $id . '" value="' . $id . '" ' . $internal_transfer . '/>
                                                                    <label class="form-check-label" for="atw-switch' . $id . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="wta-switch' . $id . '">Wallet To Account</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input wta-switch" id="wta-switch' . $id . '" value="' . $id . '" ' . $wta_transfer . '/>
                                                                    <label class="form-check-label" for="wta-switch' . $id . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="border-start-3 border-start-primary">
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="trader_to_trader-switch' . $id . '">Trader To Trader</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input trader_to_trader-switch" id="trader_to_trader-switch' . $id . '" value="' . $id . '" ' . $trader_to_trader . '/>
                                                                    <label class="form-check-label" for="trader_to_trader-switch' . $id . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="border-start-3 border-start-primary" style="border-left-color:var(--custom-primary) !important">
                                                                <label class="form-check-label mb-50" for="trader_to_ib-switch' . $id . '">Trader to IB</label>
                                                                <div class="form-check form-switch form-check-success">
                                                                    <input type="checkbox" class="form-check-input trader_to_ib-switch" id="trader_to_ib-switch' . $id . '" value="' . $id . '" ' . $trader_to_ib . '/>
                                                                    <label class="form-check-label" for="trader_to_ib-switch' . $id . '">
                                                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="internal-fill-' . $user->id . '" role="tabpanel" aria-labelledby="internal-tab-fill">
                                                <table class="datatable-inner tbl-internal table dt-inner-table-dark m-0 mt-3"  style="margin:0px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>' . __('page.account-number') . '</th>
                                                            <th>' . __('page.platform') . '</th>
                                                            <th>' . __('page.method') . '</th>
                                                            <th>' . __('page.status') . '</th>
                                                            <th>' . __('page.date') . '</th>
                                                            <th>' . __('page.amount') . '</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbodoy>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="5" class="text-end">' . __('page.total') . '=</th>
                                                            <th id="total_1" ></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="external-fill-' . $user->id . '" role="tabpanel" aria-labelledby="external-tab-fill">
                                                <table class="datatable-inner tbl-external table dt-inner-table-dark m-0 mt-3"  style="margin:0px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>Receiver Email</th>
                                                            <th>' . __('page.type') . '</th>
                                                            <th>' . __('page.date') . '</th>
                                                            <th>' . __('page.status') . '</th>
                                                            <th>' . __('page.charge') . '</th>
                                                            <th>' . __('page.amount') . '</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="5" class="text-end">' . __('page.total') . '=</th>
                                                            <th id="total_1" ></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    ' . $convert_to_ib . '
                    <div class="demo-inline-spacing">
                    ' . $manager_signe . '
                        <button type="button" class="btn btn-primary float-end btn-resent-verification-email" data-user="' . $user->id . '">Resend Activation Mail</button>
                        <button type="button" class="btn btn-primary float-end btn-send-welcome-mail" data-user="' . $user->id . '">' . __('page.send_welcome_mail') . '</button>
                        <button type="button" class="btn btn-primary float-end btn-update-profile" data-user="' . $user->id . '">' . __('page.update_profile') . '</button>
                        <button type="button" class="btn btn-primary float-end btn-finance-report" data-user="' . $user->id . '">' . __('page.finance') . '</button>
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
            'description' => $description
        ];
        return Response::json($data);
    }
    
}
