<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\ClientGroup;
use App\Models\TradingAccount;
use App\Models\Deposit;
use App\Models\Withdraw;
use App\Models\BonusUser;
use App\Models\KycVerification;
use App\Models\Comment;
use App\Models\Category;
use App\Models\IB;
use App\Models\PasswordReset;
use App\Models\admin\SystemConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetTransactionPin;
use App\Models\admin\BalanceTransfer;
use App\Models\admin\InternalTransfer;
use App\Models\Country;
use App\Models\Credit;
use App\Models\ExternalFundTransfers;
use App\Models\FinanceOp;
use App\Models\IbTransfer;
use App\Models\KycIdType;
use App\Models\Log;
use App\Models\ManagerUser;
use App\Models\UserDescription;
use App\Services\AgeCalculatorService;
use App\Services\AllFunctionService;
use App\Services\BalanceService;
use App\Services\CombinedService;
use App\Services\EmailService;
use App\Services\OpenAccountService;
use App\Services\OpenLiveTradingAccountService;
use App\Services\systems\VersionControllService;
use Illuminate\Support\Facades\Mail;


class AdminTraderAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:trader admin"]);
        $this->middleware(["role:manage client"]);
        // system module control
        $this->middleware(AllFunctionService::access('manage_client', 'admin'));
        $this->middleware(AllFunctionService::access('trader_admin', 'admin'));
    }
    public function trader_admin_report(Request $request)
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
        session()->forget('new_user'); 
        return view(
            'admins.admin-trader-admin-report',
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
    
    public function lead_admin_report(Request $request)
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
        
        // Get lead statistics
        $leadStats = $this->getLeadStatistics();
        
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
        session()->forget('new_user');
        return view(
            'admins.admin-lead-admin-report',
            [
                'categories' => $categories,
                'countries' => $countries,
                'platform' => $system_config->platform_type,
                'varsion' => $crmVarsion,
                'address_proof' => $address_proof->where('group', 'id proof')->get(),
                'params' => $params,
                'leadStats' => $leadStats,
            ]
        );
    }
    
    /**
     * Get lead statistics for dashboard cards
     */
    private function getLeadStatistics()
    {
        $now = now();
        $startOfWeek = $now->copy()->startOfWeek();
        $startOfMonth = $now->copy()->startOfMonth();
        $sixMonthsAgo = $now->copy()->subMonths(6);
        
        // Check if current user is a manager
        $currentUser = auth()->user();
        $isManager = ($currentUser->type === 'manager');
        
        // Base query for leads
        $baseQuery = User::where('is_lead', 0);
        
        // If user is a manager, only show their assigned leads
        if ($isManager) {
            $assignedUserIds = ManagerUser::where('manager_id', $currentUser->id)
                ->pluck('user_id')
                ->toArray();
            
            $baseQuery = $baseQuery->whereIn('id', $assignedUserIds);
        }
        
        // Total leads
        $totalLeads = $baseQuery->count();
        
        // This week leads
        $thisWeekLeads = (clone $baseQuery)
            ->where('created_at', '>=', $startOfWeek)
            ->count();
        
        // This month leads
        $thisMonthLeads = (clone $baseQuery)
            ->where('created_at', '>=', $startOfMonth)
            ->count();
        
        // 6 months leads
        $sixMonthsLeads = (clone $baseQuery)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->count();
            
        // Last month leads for trend comparison
        $lastMonthLeads = (clone $baseQuery)
            ->whereBetween('created_at', [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ])
            ->count();
        
        // Assigned leads (leads that have a manager assigned)
        $assignedLeads = (clone $baseQuery)
            ->whereExists(function($query) {
                $query->select(\DB::raw(1))
                      ->from('manager_users')
                      ->whereRaw('pro_manager_users.user_id = pro_users.id');
            })
            ->count();
        
        // Unassigned leads (leads that don't have a manager assigned)
        $unassignedLeads = (clone $baseQuery)
            ->whereNotExists(function($query) {
                $query->select(\DB::raw(1))
                      ->from('manager_users')
                      ->whereRaw('pro_manager_users.user_id = pro_users.id');
            })
            ->count();
        
        // KYC Verified leads (kyc_status = 1)
        $kycVerifiedLeads = (clone $baseQuery)
            ->where('kyc_status', 1)
            ->count();
        
        // KYC Unverified leads (kyc_status != 1)
        $kycUnverifiedLeads = (clone $baseQuery)
            ->where('kyc_status', '!=', 1)
            ->count();
        
        // Country-wise leads
        $countryWiseLeads = (clone $baseQuery)
            ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->select('countries.name as country_name', \DB::raw('count(*) as lead_count'))
            ->groupBy('countries.id', 'countries.name')
            ->orderBy('lead_count', 'desc')
            ->limit(10)
            ->get();
        
        return [
            'total_leads' => $totalLeads ?? 0,
            'this_week_leads' => $thisWeekLeads ?? 0,
            'this_month_leads' => $thisMonthLeads ?? 0,
            'six_months_leads' => $sixMonthsLeads ?? 0,
            'last_month_leads' => $lastMonthLeads ?? 0,
            'assigned_leads' => $assignedLeads ?? 0,
            'unassigned_leads' => $unassignedLeads ?? 0,
            'kyc_verified_leads' => $kycVerifiedLeads ?? 0,
            'kyc_unverified_leads' => $kycUnverifiedLeads ?? 0,
            'country_wise_leads' => $countryWiseLeads,
            'is_manager_view' => $isManager, // Add flag to identify manager view
        ];
    }
    
    // trader admin datatable ajax proccess
    // fetch data for trader admin report datatable
    public function trader_admin_dt_fetch_data(Request $request)
    {
        try {
            if ($request->op === 'description') {
                return $this->trader_admin_dt_description($request, $request->id);
            }
            if ($request->op === 'description_table') {
                return $this->trader_admin_inner_fetch_data($request, $request->id);
            }

            $columns = ['name', 'email', 'phone', 'users.created_at', 'active_status', 'active_status'];
            $orderby = $columns[$request->order[0]['column']];
            $result = User::where('users.type', 0)
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    'users.created_at',
                    'users.active_status',
                    'users.kyc_status',
                    'users.category_id',
                    'users.ip_address'
                )
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id');
            
            // Debug: Log the raw SQL query
            \Log::info('Raw SQL Query Debug', [
                'sql' => $result->toSql(),
                'bindings' => $result->getBindings(),
                'query_builder' => get_class($result)
            ]);

            // Check if this is a lead admin request - use referer header as primary method
            $referer = request()->header('referer');
            $isLeadAdminRequest = $request->has('is_lead_admin') || 
                                request()->is('admin/client-management/lead-admin*') || 
                                ($referer && strpos($referer, '/admin/client-management/lead-admin') !== false);
            
            // Debug: Check database state for IP addresses
            \Log::info('Database State Debug', [
                'total_users_type_0' => User::where('type', 0)->count(),
                'users_with_ip' => User::where('type', 0)->whereNotNull('ip_address')->count(),
                'users_without_ip' => User::where('type', 0)->whereNull('ip_address')->count(),
                'sample_users_ip' => User::where('type', 0)->select('id', 'name', 'ip_address')->limit(3)->get()->toArray()
            ]);
            
            // Debug logging
            \Log::info('Lead Admin Debug', [
                'has_is_lead_admin' => $request->has('is_lead_admin'),
                'is_lead_admin_value' => $request->input('is_lead_admin'),
                'request_path' => request()->path(),
                'request_url' => request()->url(),
                'request_full_url' => request()->fullUrl(),
                'is_lead_admin_request' => $isLeadAdminRequest,
                'session_has_new_user' => session()->has('new_user'),
                'session_new_user_value' => session('new_user'),
                'all_request_data' => $request->all(),
                'user_agent' => request()->userAgent(),
                'referer' => $referer,
                'referer_contains_lead_admin' => $referer ? strpos($referer, '/admin/client-management/lead-admin') !== false : false
            ]);
            
            if ($isLeadAdminRequest) {
                // For lead admin, always show leads (is_lead = 0)
                $result = $result->where('users.is_lead', 0);
                \Log::info('Applied lead admin filter: is_lead = 0');
            } else {
                // For regular trader admin, use session logic
                if (session()->has('new_user')){
                    $is_lead = false; 
                }else{
                    $is_lead = true; 
                }
                $result = $result->where('users.is_lead', $is_lead);
                \Log::info('Applied trader admin filter: is_lead = ' . ($is_lead ? '1' : '0'));
            }
               
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
            
            // Debug: Check if ip_address column exists in the first result
            if ($result->count() > 0) {
                $firstUser = $result->first();
                \Log::info('First User Debug', [
                    'user_id' => $firstUser->id,
                    'user_name' => $firstUser->name,
                    'has_ip_address_property' => property_exists($firstUser, 'ip_address'),
                    'ip_address_value' => $firstUser->ip_address ?? 'NULL',
                    'all_attributes' => $firstUser->getAttributes(),
                    'raw_object' => $firstUser
                ]);
            }
            
            $data = array();
            
            // Debug: Log the raw result data
            \Log::info('Raw Result Debug', [
                'result_count' => $result->count(),
                'first_user_sample' => $result->first() ? [
                    'id' => $result->first()->id,
                    'name' => $result->first()->name,
                    'ip_address' => $result->first()->ip_address,
                    'all_properties' => get_object_vars($result->first())
                ] : 'No users found'
            ]);
            
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
                
                // find manager
                $manager = ManagerUser::where('manager_users.user_id', $value->id)->where('group_type', 1)
                ->join('users', 'manager_users.manager_id', '=', 'users.id')
                ->join('managers', 'users.id', '=', 'managers.user_id')
                ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
                ->first();
                $manager_name = 'N/A';
                if (isset($manager->name)) {
                    $manager_name = $manager->name;
                }
                
                $trader_user = User::find($value->id);
                // FIND CATEGORY
                $category = Category::find($trader_user->category_id);
                if (isset($category->name)) {
                    $category = ucwords($category->name);
                } else {
                    $category = ucwords('N/A');
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
                
                $dashboard = '<a class="dropdown-item" href="' . route("admin.trader.dashboard", ["id" => $value->id]) . '">Dashboard</a>';

                
                $delete = '<span class="dropdown-item delete-trader" data-id='.$value->id.'>
                Delete</span>';

                $conver_to_lead = '<span class="dropdown-item delete-trader" data-id='.$value->id.'>
                Convert to Lead</span>';

                if(session()->has('new_user')){
                    $drop_down_items = $button .''.$dashboard.''.$delete.$conver_to_lead;
                }else{
                    $drop_down_items = $button .''.$dashboard.''.$delete;
                }

                // tabl column
                // -------------------------------------
                $data[] = [
                    "select" => '<input type="checkbox" class="assign-to-manager" data-email = '. $value->email .' data-phone = '. $value->phone .' data-name = '. $value->name .' data-id="' . $value->id . '" ' . ($manager_name !== 'N/A' ? 'checked disabled' : '') . '>',
                    "name" => '<a data-id="' . $value->id . '" href="#" class="dt-description justify-content-start text-truncate"><span class="w"> <i class="plus-minus text-dark" data-feather="plus"></i> </span><span class="' . $kyc_color . '">' . $value->name . '</span></a>',
                    "email" => $value->email,
                    "phone" => ucwords($value->phone),
                    "joined" => date('d F y, h:i A', strtotime($value->created_at)),
                    "status" => $status,
                    "account_manager" => $manager_name,
                    "category" => $category,
                    "actions" => '<div class="d-flex justify-content-between">
                                    <a href="#" class="more-actions dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                            ' . $drop_down_items . '
                                    </div>
                                </div>'
                ];
                
                // Debug: Log IP field processing for each user
                \Log::info('IP Field Processing Debug', [
                    'user_id' => $value->id,
                    'user_name' => $value->name,
                    'raw_ip_address' => $value->ip_address,
                    'ip_field_exists' => isset($value->ip_address),
                    'ip_field_null' => is_null($value->ip_address),
                    'ip_field_empty' => empty($value->ip_address),
                    'final_ip_value' => isset($value->ip_address) ? ($value->ip_address ?: 'N/A') : 'N/A',
                    'has_attribute' => $value->getAttribute('ip_address') !== null,
                    'attribute_value' => $value->getAttribute('ip_address')
                ]);
                
                // Debug logging for each user
                \Log::info('User IP Debug', [
                    'user_id' => $value->id,
                    'user_name' => $value->name,
                    'ip_address_raw' => $value->ip_address,
                    'ip_address_display' => $value->ip_address ?? 'N/A',
                    'has_ip_address' => isset($value->ip_address),
                    'ip_address_null' => is_null($value->ip_address),
                    'final_ip_value' => isset($value->ip_address) ? ($value->ip_address ?: 'N/A') : 'N/A',
                    'all_user_properties' => get_object_vars($value),
                    'ip_field_exists' => property_exists($value, 'ip_address')
                ]);
            }

            // Debug: Log the final response data
            \Log::info('Final Response Debug', [
                'total_records' => count($data),
                'sample_data' => array_slice($data, 0, 3),
                'ip_column_data' => array_map(function($item) {
                    return [
                        'user_id' => $item['name'] ?? 'N/A',
                        'ip_value' => $item['ip'] ?? 'MISSING'
                    ];
                }, array_slice($data, 0, 3))
            ]);
            
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
    
    /**
     * Convert all users with deposits from lead to trader
     * This method can be called manually to update existing data
     */
    public function convertLeadsWithDeposits()
    {
        try {
            $updatedCount = \App\Models\Deposit::convertLeadsWithDeposits();
            
            return response()->json([
                'success' => true, 
                'message' => "Successfully converted {$updatedCount} users from lead to trader status.",
                'updated_count' => $updatedCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error converting leads with deposits: ' . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'message' => 'Error occurred while converting leads: ' . $e->getMessage()
            ], 500);
        }
    }

    public function goto_trader_dashboard($id){
        $customer = User::findOrFail($id);

         // Store the admin's ID and role in the session
         session(['admin_id' => Auth::id()]);

        // Log in as the customer
        Auth::login($customer);

        return redirect()->route('trader.dashboard');

    }

    
    public function delete_trader($id){
        $trader = User::findOrFail($id);

        if ($trader){
            InternalTransfer::where('user_id', $id)->delete();
            TradingAccount::where('user_id', $id)->delete();
            Deposit::where('user_id', $id)->delete();
            Withdraw::where('user_id', $id)->delete();
            $trader->delete();
            return response()->json(['success' => true, 'message' => 'Trader deleted successfully']);

        }
        
        return response()->json(['success' => false, 'message' => 'Trader not found'], 404);

    }

    // datatable descriptions----------------------------------------------
    public function trader_admin_dt_description(Request $request, $id, $is_json=true)
    {
        $user = User::find($id);
        $user_descriptions = UserDescription::where('user_id', $user->id)->first(); //<---user description
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
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
        
                $user_info = '';

        if ($is_json == false){
            $user_info = '
                <table class="table table-responsive tbl-balance">
                        <tbody><tr>
                            <th>Name</th>
                            <td class="btn-load-balance">
                                <span class="balance-value amount"> '.$user->name.'</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td class="btn-load-equity">
                                <span class="balance-value amount"> '.$user->email.'</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td class="btn-load-equity">
                                <span class="balance-value amount"> '.$user->phone.'</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            ';
        }



        $description = '<tr class="description" style="display:none">
            <td colspan="12">
                <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="rounded-0 w-75">
                            '.$user_info.'
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
                                                <a data-id="' . $id . '" class="nav-link trd-nav-link active trading-account-tab-fill" id="trading_account-tab-fill-' . $user->id . '" data-bs-toggle="tab" href="#trading_account-fill-' . $user->id . '" role="tab" aria-controls="home-fill" aria-selected="true">' . __('page.trading_account') . '</a>
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
                                                                <th>Actions</th>
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
                                                            <th>Sender Email</th>
                                                            <th>Receiver Email</th>
                                                            <th>' . __('page.type') . '</th>
                                                            <th>' . __('page.date') . '</th>
                                                            <th>' . __('page.status') . '</th>
                                                            <th>' . __('page.charge') . '</th>
                                                            <th>' . __('page.amount') . '</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot class="d-none">
                                                        <tr>
                                                            <th colspan="6" class="text-end">' . __('page.total') . '=</th>
                                                            <th id="total_1" class="external_inner_total"></th>
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
        
        
        if($is_json){
            $data = [
                'status' => true,
                'description' => $description
            ];
            return Response::json($data);
        }else{
            return $description;
        }
    }
    
    
    public function get_single_admin_trader_report($id){
        $request = new Request();
        $description = $this->trader_admin_dt_description($request, $id, false);
        $countries = Country::all();
        return view('admins.admin-report-admin-single-view')->with(['description' => $description, 'countries' => $countries]);
    }

    // trading accounts tab data table
    // ---------------------------------------------------------------------------------------------------------
    public function trader_admin_inner_fetch_data(Request $request, $id)
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
                $data[$i]["actions"]        = '<div class="d-flex justify-content-between">
                                                <a href="#" class="more-actions dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i data-feather="more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item">Balance & Equity</a>
                                                    <a class="dropdown-item">Check Credential</a>
                                                    <a class="dropdown-item">Change Leverage</a>
                                                    <a class="dropdown-item">Change Group</a>
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

    // Deposit tab datatable
    // -------------------------------------------------------------------------------------------------------------------------------
    public function trader_admin_deposit_fetch_data(Request $request, $id)
    {
        try {
            $columns = ['amount', 'transaction_type', 'approved_status', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];
            // select type= 0 for trader
            $result = Deposit::where('user_id', $id)->select();
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {

                $status = '';
                if (strtolower($value->approved_status) === 'p') {
                    $status = '<span class="badge badge-light-warning">Pending</span>';
                } elseif (strtolower($value->approved_status) === 'a') {
                    $status = '<span class="badge badge-light-success">Approved</span>';
                } elseif (strtolower($value->approved_status) === 'd') {
                    $status = '<span class="badge badge-light-danger">Declined</span>';
                }
                $data[$i]["date"]       = date('d F y, h:i A', strtotime($value->created_at));
                $data[$i]["Ammount"]     = $value->amount;
                $data[$i]["Method"]     = ucwords($value->transaction_type);
                $data[$i]["Status"]     = $status;
                // $data[$i]["actions"]    = '<a href="#" class="more-actions"><i data-feather="more-vertical"></i></a> <i data-feather="edit"></i>';
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

    // withdraw reports datatable
    // --------------------------------------------------------------------------------------
    public function trader_admin_withdraw_fetch_data(Request $request, $id)
    {
        try {
            $columns = ['amount', 'transaction_type', 'approved_status', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];
            // select type= 0 for trader
            $result = Withdraw::where('user_id', $id)->select(
                'withdraws.created_at',
                'withdraws.amount',
                'withdraws.transaction_type',
                'withdraws.approved_status'
            );

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                $status = '';
                if (strtolower($value->approved_status) === 'p') {
                    $status = '<span class="badge badge-light-warning">Pending</span>';
                } elseif (strtolower($value->approved_status) === 'a') {
                    $status = '<span class="badge badge-light-success">Approved</span>';
                } elseif (strtolower($value->approved_status) === 'd') {
                    $status = '<span class="badge badge-light-danger">Declined</span>';
                }

                $data[$i]["date"]       = date('d F y, h:i A', strtotime($value->created_at));
                $data[$i]["Ammount"]     = $value->amount;
                $data[$i]["Method"]     = ucwords($value->transaction_type);
                $data[$i]["Status"]     = $status;
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
    // bonus reports datatabke
    // BONUS: TAB
    // -------------------------------------------------------------------------------------------
    public function trader_admin_bonus_fetch_data(Request $request, $id)
    {
        try {
            $columns = ['credits.created_at', 'amount', 'credits.type', 'credits.expire_date', 'credited_by'];
            $orderby = $columns[$request->order[0]['column']];
            // select type= 0 for trader
            $result = Credit::where('trading_accounts.user_id', $id)->select(
                'credits.*',
                'trading_accounts.account_number'
            )
                ->join('trading_accounts', 'credits.trading_account', '=', 'trading_accounts.id');
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                $type = '';
                if (strtolower($value->type) === 'add') {
                    $type = '<span class="badge badge-success bg-light-success">Add</span>';
                } else {
                    $type = '<span class="badge badge-warning bg-light-warning">Deduct</span>';
                }

                $data[$i]["date"]       = date('d F y, h:i A', strtotime($value->created_at));
                $data[$i]["amount"]     = $value->amount;
                $data[$i]["type"]     = $type;
                $data[$i]["account_number"]     = $value->account_number;
                $data[$i]["credit_expire"]     = date('d F y h:i A', strtotime($value->credit_expire));
                $data[$i]["created_by"]     = AllFunctionService::user_email($value->credited_by);
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
    // kyy tab datatable
    // ------------------------------------------------------------------------
    public function trader_admin_kyc_fetch_data(Request $request, $id)
    {
        try {
            $columns = ['kyc_verifications.created_at', 'id_type', 'status'];
            $orderby = $columns[$request->order[0]['column']];
            // select type= 0 for trader
            $result = KycVerification::where('user_id', $id)->select()
                ->join('kyc_id_type', 'kyc_verifications.doc_type', '=', 'kyc_id_type.id');
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                $status = '';

                if ($value->status == 0) {
                    $status = '<span class="badge badge-light-warning">Pending</span>';
                } elseif ($value->status == 1) {
                    $status = '<span class="badge badge-light-success">Approved</span>';
                } else {
                    $status = '<span class="badge badge-light-danger">Declined</span>';
                }

                $data[$i]["date"]           = date('d F y, h:i A', strtotime($value->created_at));
                $data[$i]["document_type"]  = ucwords($value->id_type);
                $data[$i]["status"]         = $status;
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

    // comments reports
    // comments tab datatable
    // --------------------------------------------------------------------------
    public function trader_admin_comment_fetch_data(Request $request, $id)
    {
        try {
            $columns = ['comment', 'created_at', 'comment'];
            $orderby = $columns[$request->order[0]['column']];
            // select type= 0 for trader
            $result = Comment::where('user_id', $id)->select();
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                $user = User::find($id);
                $data[$i]["date"]    = date('d F y, h:i A', strtotime($value->created_at));
                $data[$i]["comment"] = $value->comment;
                $data[$i]["actions"] = '<div class="btn-group">
                                    <button class="btn btn-flat-primary dropdown-toggle comment-actions" type="button" id="dropdownMenuButton100" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i data-feather="more-vertical"></i>

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
    // comment tab
    // ------------------------------------------------------------------------------------
    public function trader_admin_comment_save_data(Request $request)
    {
        try{
            $validation_rules = [
                'comment' => 'required|min:5',
                'trader_id' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                if ($request->ajax()) {
                    return Response::json(['status' => false, 'message' => 'Please Fix the following error',  'errors' => $validator->errors()]);
                } else {
                    return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
                }
            } else {
                $create = Comment::create([
                    'user_id' => $request->trader_id,
                    'type' => 'Trader',
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
            return Response::json($request->trader_id);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
    // update exist comment
    public function comment_update(Request $request)
    {
        $validation_rules = [
            'comment' => 'required|min:5',
            'trader_id' => 'required',
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
        return Response::json($request->trader_id);
    }
    // delete exist comment
    public function comment_delete(Request $request)
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
        return Response::json($request->trader_id);
    }
    // Block unblock trader------------------------------------
    public function block_unblock(Request $request)
    {
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

    // google 2 steps authentications
    public function two_step_auth(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        } else {
            $user = User::find($request->id);
            $user->g_auth = ($request->request_for === 'enable') ? 1 : 0;
            $update = $user->save();
            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Google 2 step authentication successfully Enabled";
                $success_title = 'Google 2 Step Enabled';
            } else {
                $update_message = $user->name . " " . "Google 2 step authentication successfully Disabled";
                $success_title = 'Google 2 Step Disabled';
            }
            if ($update) {
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                // insert activity-----------------
                activity('google 2 step')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($user)
                    ->event($request->request_for)
                    ->performedOn($user)
                    ->log($description);
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => $update_message,
                    'success_title' => $success_title
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong please try again later',
                'success_title' => $success_title
            ]);
        }
        return Response::json($request->trader_id);
    }

    // Email authentications
    public function email_auth(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $user = User::find($request->id);
            $user->email_auth = ($request->request_for === 'enable') ? 1 : 0;
            $update = $user->save();

            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Email Authentication Successfully Enabled";
                $success_title = 'Email Authentication Enabled';
            } else {
                $update_message = $user->name . " " . "Google 2 step authentication successfully Disabled";
                $success_title = 'Google 2 Step Disabled';
            }
            if ($update) {
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                // insert activity-----------------
                activity('email authentication')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($user)
                    ->event($request->request_for)
                    ->performedOn($user)
                    ->log($description);
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => $update_message,
                    'success_title' => $success_title
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong! please try again later',
                'success_title' => $success_title
            ]);
        }
        return Response::json($request->trader_id);
    }
    // Email verification
    public function email_verification(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $user = User::find($request->id);
            $user->email_verification = ($request->request_for === 'enable') ? 1 : 0;
            if ($request->request_for === 'enable') {
                $user->email_verified_at = date('Y-m-d h:i:s', strtotime(now()));
            }
            $update = $user->save();

            if ($request->request_for === 'enable') {
                $update_message = $user->name . " " . "Email Verification Successfully Enabled & email activation done";
                $success_title = 'Email Verification Enabled';
            } else {
                $update_message = $user->name . " " . "Email verification successfully Disabled";
                $success_title = 'Email Verification Disabled';
            }
            if ($update) {
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                // insert activity-----------------
                activity('email verification')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($user)
                    ->event($request->request_for)
                    ->performedOn($user)
                    ->log($description);
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => $update_message,
                    'success_title' => $success_title
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong! please try again later.',
                'success_title' => $success_title
            ]);
        }
        return Response::json($request->trader_id);
    }
    // deposit operation-----------------------------------------
    public function deposit_operation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['deposit_operation' => ($request->request_for === 'enable') ? 1 : 0]
            );

            if ($request->request_for === 'enable') {
                $update_message = $create_or_update->name . " " . "Deposit operation Successfully Enabled";
                $success_title = 'Deposit Operation Enabled';
            } else {
                $update_message = $create_or_update->name . " " . "Deposit Operation successfully Disabled";
                $success_title = 'Deposit Operation Disabled';
            }
            if ($create_or_update) {
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                // insert activity-----------------
                activity('deposit operation')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($create_or_update)
                    ->event($request->request_for)
                    ->performedOn($create_or_update)
                    ->log($description);
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => $update_message,
                    'success_title' => $success_title
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong! please try again later.',
                'success_title' => $success_title
            ]);
        }
        return Response::json($request->trader_id);
    }
    // withdraw operation-------------------------------------
    public function withdraw_operation(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['withdraw_operation' => ($request->request_for === 'enable') ? 1 : 0]
            );

            if ($request->request_for === 'enable') {
                $update_message = $create_or_update->name . " " . "Withdraw operation Successfully Enabled";
                $success_title = 'Withdraw Operation Enabled';
            } else {
                $update_message = $create_or_update->name . " " . "Withdraw Operation successfully Disabled";
                $success_title = 'Withdraw Operation Disabled';
            }
            if ($create_or_update) {
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                // insert activity-----------------
                activity('withdraw operation')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($create_or_update)
                    ->event($request->request_for)
                    ->performedOn($create_or_update)
                    ->log($description);
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => $update_message,
                    'success_title' => $success_title
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong! please try again later.',
                'success_title' => $success_title
            ]);
        }
        return Response::json($request->trader_id);
    }

    // Internal transfer
    public function internal_transfer(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'request_for' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $create_or_update = FinanceOp::updateOrCreate(
                ['user_id' => $request->id],
                ['internal_transfer' => ($request->request_for === 'enable') ? 1 : 0]
            );

            if ($request->request_for === 'enable') {
                $update_message = $create_or_update->name . " " . "Account to Wallet Transfer Successfully Enabled";
                $success_title = 'Account to Wallet Transfer Enabled';
            } else {
                $update_message = $create_or_update->name . " " . "Account to Wallet Transfer successfully Disabled";
                $success_title = 'Account to Wallet Transfer Disabled';
            }
            if ($create_or_update) {
                // insert activity log----------------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been " . $request->request_for;
                activity('internal transer')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($create_or_update)
                    ->event($request->request_for)
                    ->performedOn($create_or_update)
                    ->log($description);
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => $update_message,
                    'success_title' => $success_title
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong! please try again later.',
                'success_title' => $success_title
            ]);
        }
    }

    // set category for traders
    public function set_category(Request $request)
    {
        $validation_rules = [
            'id' => 'required',
            'category' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        } else {
            $user = User::find($request->id);
            $user->category_id = $request->category;
            $update = $user->save();
            if ($update) {
                // insert activity log----------------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been assign category";
                activity('assign category')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($user)
                    ->event('assign category')
                    ->performedOn($user)
                    ->log($description);
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'Category set successfully done',
                    'success_title' => 'Category Set'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong! please try again later.',
                'success_title' => 'Category Set'
            ]);
        }
    }

    // change password--------------------------------------
    public function change_password(Request $request)
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

    // sending password change mail
    public function change_password_mail(Request $request)
    {
        $user = User::find($request->trader_id);
        $find_password = Log::where('user_id', $request->trader_id)->first();

        $mail_status = EmailService::send_email('change-password', [
            'user_id' => $user->id,
            'clientPassword'    => ($find_password) ? decrypt($find_password->password) : '',
            'accountActivationLink' => route('login'),
            'admin' => auth()->user()->name,
        ]);
        if ($mail_status) {
            return Response::json([
                'status' => true,
                'message' => 'Mail successfully sent for Password Change',
                'success_title' => 'Change password'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Mail sending failed, Please try again later!',
            'success_title' => 'Change password'
        ]);
    }

    // change pin
    public function change_pin(Request $request)
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

    // sending pin change mail
    public function change_pin_mail(Request $request)
    {
        $user = User::find($request->trader_id);
        // get log password
        $logPass = Log::where('user_id', $request->trader_id)->first();
        // sending mail
        $email_status = EmailService::send_email('change-transaction-password', [
            'user_id' => $user->id,
            'transaction_pin'    => ($logPass) ? decrypt($logPass->transaction_password) : '',
            'accountActivationLink' => route('login'),
            'admin' => auth()->user()->name,
        ]);
        if ($email_status) {
            return Response::json([
                'status' => true,
                'message' => 'Mail successfully sent for Transaction pin Change'
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Mail sending failed, Please try again later!'
            ]);
        }
    }
    // reset password
    public function reset_password(Request $request)
    {
        try {
            // generate random password
            $random_password = str_random(5) . '@' . random_int(11, 99);
            $user = User::find($request->trader_id);
            // update password
            $update_user = User::where('id', $request->trader_id)->update([
                'tmp_pass' => 1,
                'password' => Hash::make($random_password),
            ]);

            // update log tabble for retrieve later
            $old_password = Log::where('user_id', $request->trader_id)->first();
            $update_log = Log::UpdateOrCreate(
                [
                    'user_id' => $request->trader_id,
                ],
                [
                    'password' => encrypt($random_password),
                ]
            );
            // update reset table

            $expire = date("Y-m-d h:i:s", strtotime('+1 hour'));

            $insert = PasswordReset::insert([
                'email' => $user->email,
                'old_password' => $user->password,
                'created_at' => date('Y-m-d h:i:s', time()),
                'token' => csrf_token(),
                'expried_on' => $expire
            ]);
            if ($update_user) {
                // insert activity log----------------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been reset trader password";
                activity('trader password reset')
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
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }

    // sending reset password mail-------------------------
    public function password_reset_mail(Request $request)
    {
        $user = User::find($request->trader_id);
        $log = Log::where('user_id', $request->trader_id)->first();

        $email_status = EmailService::send_email('reset-password', [
            'admin' => auth()->user()->name,
            'new_password'    => decrypt($log->password),
            'user_id' => $user->id,
        ]);
        if ($email_status) {
            return Response::json([
                'status' => true,
                'message' => 'Mail successfully sent for reset password',
                'success_title' => 'Reset password'
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Mail sending failed, Please try again later!',
                'success_title' => 'Reset Password'
            ]);
        }
    }
    // end: sending reset password mail

    // reset transaction pin---------------------------------------------
    public function reset_transaction_pin(Request $request)
    {
        // generate random password
        $random_pin = random_int(000001, 999999);
        // return decrypt($hashed_random_pin);
        // update pin
        $expire = date("Y-m-d h:i:s", strtotime('+1 hour'));
        $user = User::find($request->trader_id);
        $update = User::where('id', $request->trader_id)->update([
            'tmp_tran_pass' => 1,
            'transaction_password'  => Hash::make($random_pin),
            'tmp_trans_pass_expired' => $expire
        ]);
        if ($update) {
            // update log tabble for retrieve later
            $log = Log::where('user_id', $request->trader_id)->first();
            $update_log = Log::UpdateOrCreate(
                [
                    'user_id' => $request->trader_id,
                ],
                [
                    'transaction_password' => encrypt($random_pin),
                ]
            );
            // insert activity log----------------
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been reset transaction pin";
            activity('reset transaction pin')
                ->causedBy(auth()->user()->id)
                ->withProperties($user)
                ->event('reset pin')
                ->performedOn($user)
                ->log($description);
            // end activity log-----------------
            return Response::json([
                'status' => true,
                'message' => 'Transaction pin successfully reset'
            ]);
        }
        return Response::json([
            'status' => true,
            'message' => 'Somthing went wrong please try again later'
        ]);
    }

    // transaction pin reset mail------------------
    public function transaction_pin_reset_mail(Request $request)
    {
        $user = User::find($request->trader_id);
        $log = Log::where('user_id', $request->trader_id)->first();
        $support_email = SystemConfig::select('support_email')->first();
        $support_email = ($support_email) ? $support_email->support_email : default_support_email();
        $email_data = [
            'name'              => ($user) ? $user->name : config('app.name') . ' User',
            'account_email'     => ($user) ? $user->email : '',
            'admin'             => Auth::user()->name,
            'login_url'         => route('login'),
            'support_email'     => $support_email,
            'new_pin'    => decrypt($log->transaction_password)
        ];
        if (Mail::to($user->email)->send(new ResetTransactionPin($email_data))) {
            return Response::json([
                'status' => true,
                'message' => 'Mail successfully sent for reset transaction pin',
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Mail sending failed, Please try again later!',
            ]);
        }
    }

    // get desk manager----------------------------------------
    public function get_desk_manager(Request $request, $email)
    {
        $desk_manager = User::where('email', $email)
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->join('manager_countries', 'users.id', '=', 'manager_countries.manager_id')
            ->join('countries', 'manager_countries.accessible_country', '=', 'countries.id')
            ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
            ->where('group_type', 0)
            ->select(
                'users.name',
                'manager_groups.group_name',
                'countries.name as country',
                'users.phone',
                'users.email'
            )->first();
        // return Response::json($desk_manager);
        if ($desk_manager) {
            return Response::json([
                'status' => true,
                'desk_manager_info' => $desk_manager
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Desk manager Not found'
        ]);
    }

    // assing to desk manager---------------------------------------
    public function assign_desk_manager(Request $request)
    {
        $manager_id = User::where('email', $request->desk_manager_email)->first();
        // check already assigned or not
        if (ManagerUser::where('user_id', $request->trader_id)->where('manager_id', $manager_id->id)->exists()) {
            return Response::json([
                'status' => false,
                'message' => 'Manager aleady assigned!'
            ]);
        }
        // assigned to manager
        $create = ManagerUser::create([
            'user_id' => $request->trader_id,
            'manager_id' => $manager_id->id
        ]);
        if ($create) {
            return Response::json([
                'status' => true,
                'message' => 'Desk manager successfully assigned'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Somthing went wrong! please try again later'
        ]);
    }
    // end: assign to desk manager---------------------------------

    // get account manager----------------------------------------
    public function get_account_manager(Request $request, $email)
    {
        $account_manager = User::where('email', $email)
            ->join('managers', 'users.id', '=', 'managers.user_id')
            ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->join('manager_countries', 'users.id', '=', 'manager_countries.manager_id')
            ->join('countries', 'manager_countries.accessible_country', '=', 'countries.id')
            ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
            ->where('group_type', 1)
            ->select(
                'users.name',
                'manager_groups.group_name',
                'countries.name as country',
                'users.phone',
                'users.email'
            )->first();
        if ($account_manager) {
            return Response::json([
                'status' => true,
                'account_manager_info' => $account_manager
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Account manager Not found'
        ]);
    }
    // end: account amanger info-----------------------------------
    // assing to account manager---------------------------------------
    public function assign_account_manager(Request $request)
    {
        $manager_id = User::where('email', $request->account_manager_email)->first();
        // check already assigned or not
        if (ManagerUser::where('user_id', $request->trader_id)->where('manager_id', $manager_id->id)->exists()) {
            return Response::json([
                'status' => false,
                'message' => 'Manager aleady assigned!'
            ]);
        }
        // assigned to manager
        $create = ManagerUser::create([
            'user_id' => $request->trader_id,
            'manager_id' => $manager_id->id
        ]);
        if ($create) {
            return Response::json([
                'status' => true,
                'message' => 'Account manager successfully assigned'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Somthing went wrong! please try again later'
        ]);
    }
    // end: assign to desk manager---------------------------------

    // resent verification email---------------------------------
    public function resent_verification_email(Request $request, $trader_id)
    {
        $user = User::find($trader_id);
        $activation_link = url('/activation/user/' . encrypt($user->id));
        // get log data
        $password_log = Log::where('user_id', $user->id)->first();
        $mail_status = EmailService::send_email('resent-verification-email', [
            'user_id' => $user->id,
            'accountActivationLink' => $activation_link,
            'activation_link'            => $activation_link,
            'clientPassword'             => ($password_log) ? decrypt($password_log->password) : '',
            'password'                   => ($password_log) ? decrypt($password_log->password) : '',
            'password'                   => ($password_log) ? decrypt($password_log->password) : '',
            'clientTransactionPassword'  => ($password_log) ? decrypt($password_log->transaction_password) : '',
            'transaction_password'       => ($password_log) ? decrypt($password_log->transaction_password) : '',
            'server'                     => $request->platform,
            'user_id' => $user->id,
            'admin' => ucwords(auth()->user()->name)
        ]);
        if ($mail_status) {
            // save activity log
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been send verification mail";
            activity('Resent verification mail')
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
    // ending: resend verification email-----------------------------------
    // send welcome mail---------------------------------
    public function send_welcome_mail(Request $request, $trader_id)
    {
        $user = User::find($trader_id);
        $password_log = Log::where('user_id', $trader_id)->first();
        $activation_link = url('/activation/user/' . encrypt($user->id));

        $mail_status = EmailService::send_email('trader-registration', [
            'loginUrl'                   => $activation_link,
            'activation_link'                   => $activation_link,
            'clientPassword'             => decrypt($password_log->password),
            'password'             => decrypt($password_log->password),
            'clientTransactionPassword'  => decrypt($password_log->transaction_password),
            'transaction_password'  => decrypt($password_log->transaction_password),
            'server'                     => $request->platform,
            'user_id' => $user->id,
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
    // finance report------------------------------------------
    public function finance_report(Request $request, $id)
    {
        //Get Total Deposit
        $total_deposit = Deposit::where('user_id', $id)->where('approved_status', 'A')->sum('amount');

        //Get Total withdraw
        $total_withdraw = Withdraw::where('user_id', $id)->where('approved_status', 'A')->sum('amount');

        //Get Total pending withdraw
        $total_pending_withdraw = Withdraw::where('user_id', $id)->where('approved_status', 'P')->sum('amount');

        // Get total fund send
        $total_send_fund = BalanceTransfer::where('sender_id', $id)->where('status', 'D')->sum('amount');

        // Get total fund received
        $total_rec_fund = BalanceTransfer::where('receiver_id', $id)->where('status', 'A')->sum('amount');

        $total_ib_transfer = IbTransfer::where('trader_id', $id)->where('status', 'A')->sum('amount');

        return Response::json([
            'total_deposit_amount' => $total_deposit,
            'total_deposit_counter' => $total_deposit,
            'total_rec_fund_amount' => $total_rec_fund,
            'total_rec_fund_counter' => ($total_deposit + $total_rec_fund),

            'total_up_balance_counter' => ($total_deposit + $total_rec_fund),
            'total_withdraw_amount' => $total_withdraw,
            'total_withdraw_counter' => (($total_deposit + $total_rec_fund) - ($total_withdraw)),
            'total_pending_withdraw_amount' => $total_pending_withdraw,
            'total_pending_withdraw_counter' => (($total_deposit + $total_rec_fund) - ($total_withdraw + $total_pending_withdraw)),
            'total_send_fund_amount' => $total_send_fund,
            'total_send_fund_counter' => (($total_deposit + $total_rec_fund) - ($total_withdraw + $total_pending_withdraw + $total_send_fund)),
            'total_ib_balance_amount' => $total_send_fund,
            'total_ib_balance_counter' => (($total_deposit + $total_rec_fund + $total_ib_transfer) - ($total_withdraw + $total_pending_withdraw + $total_send_fund)),
            'total_current_balance' => (($total_deposit + $total_rec_fund) - ($total_withdraw + $total_pending_withdraw + $total_send_fund)),
        ]);
    }
    // end finance reports------------------------------------------------------------

    // Start: add new trader---------------------------------
    public function add_new_trader(Request $request)
    {
        try {
            $response = [];
            $system_config = SystemConfig::select('create_meta_acc', 'acc_limit', 'social_account')->first();
            $result['success'] = false;
            // check first step personal validation
            $validation_rules = [
                'full_name' => 'required|max:100',
                'email' => 'required|unique:users|email',
                'phone' => 'required|max:20',
                'data_of_birth' => 'required',
                'gender' => 'required',
            ];
            // check step 2 address validation
            if ($request->op === 'step-address' || $request->op === 'step-confirm') {
                $validation_rules['country'] = 'required';
                $validation_rules['state'] = 'nullable|max:70';
                $validation_rules['city'] = 'required|max:70';
                $validation_rules['zip_code'] = 'required|max:20';
                $validation_rules['address'] = 'nullable|max:100';
            }
            // check step 3 social validation
            if ($request->op === 'step-social') {
                $validation_rules['twitter'] = 'nullable|max:100';
                $validation_rules['facebook'] = 'nullable|max:100';
                $validation_rules['telegram'] = 'nullable|max:100';
                $validation_rules['linkedin'] = 'nullable|max:100';
                $validation_rules['skype'] = 'nullable|max:100';
                $validation_rules['whatsapp'] = 'nullable|max:100';
            }
            // check step 4 account validation
            if ($request->op === 'step-account') {
                $validation_rules['server_name'] = 'required';
                $validation_rules['client_type'] = 'required'; //<----like as demo or live
                $validation_rules['account_type'] = 'required'; //<----client group id
                $validation_rules['leverage'] = 'required';
            }

            // check step confirm password validation
            if ($request->op === 'step-confirm') {
                if ($system_config->create_meta_acc == 1) {
                    $validation_rules['server_name'] = 'required';
                    $validation_rules['client_type'] = 'required'; //<----like as demo or live
                    $validation_rules['account_type'] = 'required'; //<----client group id
                    $validation_rules['leverage'] = 'required';
                }
                if ($system_config->social_account == 1) {
                    $validation_rules['twitter'] = 'nullable|max:100';
                    $validation_rules['facebook'] = 'nullable|max:100';
                    $validation_rules['telegram'] = 'nullable|max:100';
                    $validation_rules['linkedin'] = 'nullable|max:100';
                    $validation_rules['skype'] = 'nullable|max:100';
                    $validation_rules['whatsapp'] = 'nullable|max:100';
                }
                $validation_rules['password'] = 'required|min:8|max:20|same:confirm_password';
                $validation_rules['confirm_password'] = 'required';
            }
            $getAgeOfInput = new AgeCalculatorService();
            if ($request->op === 'step-persional' || $request->op === 'step-address' || $request->op === 'step-account' ||   $request->op    === 'step-social' || $request->op === 'step-confirm') {
                $validator = Validator::make($request->all(), $validation_rules);
                if ($validator->fails()) {
                    return Response::json([
                        'status' => false,
                        'errors' => $validator->errors(),
                        'message' => 'Please fix the following errors!'
                    ]);
                }

                if ($getAgeOfInput->getAgeDiffer($request->data_of_birth)) {
                    return Response::json([
                        'status' => false,
                        'error' => ['data_of_birth' => "Minimum age required 12 years old"],
                        'message' => 'Please fix the following errors!'
                    ]);
                }
            }

            // confirm step personal info
            if ($request->op === 'step-persional') {
                return Response::json([
                    'persional_status' => true,
                ]);
            }
            // confirm step address info
            if ($request->op === 'step-address') {
                return Response::json([
                    'address_status' => true,
                ]);
            }
            // confirm step social info
            if ($request->op === 'step-social') {
                return Response::json([
                    'social_status' => true,
                ]);
            }
            // confirm step account info
            if ($request->op === 'step-account') {
                return Response::json([
                    'account_status' => true,
                ]);
            }
            if ($request->op === 'step-confirm') {
                $response['trader_registration'] = false;
                $response['create_user_description'] = false;
                $response['create_trading_account'] = false;
                $transaction_password = generatePassword();
                $master_password        = ("M") . date('His') . rand(100, 9999);
                $investor_password        = ("I") . date('His') . rand(100, 9999);
                $phone_password        = ("P") . date('His') . rand(100, 9999);
                $countries = Country::find($request->country);

                // if auto trading account create is true
                $result['success'] = true;
                if ($system_config->create_meta_acc == 1) {
                    if ($request->server_name === 'mt5') {
                        $country = $countries->name;
                        $crm_api = new OpenAccountService($request->server_name, 'live');
                        $data = $crm_api->UserCreate();
                        $data->Name = $request->name;
                        $data->Email = $request->email;
                        $data->Group = $request->account_type;
                        $data->Leverage = $request->leverage;
                        $data->Comment = 'Trader By Admin Registration #' . $request->email;
                        $data->Phone = $request->phone;
                        $data->Country = $country;
                        $result = $crm_api->AccountCreate($data);
                    }
                }

                if ($result['success'] == true) {

                    // trader registrations----------------------------
                    $user = User::create([
                        'name' => $request->full_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'password' => Hash::make($request->password),
                        'transaction_password' => Hash::make($transaction_password),
                        'active_status' => (isset($request->mark_as_activated)) ? 1 : 0,
                        'client_group_id' => $request->account_type,
                        'trading_ac_limit' => ($system_config->acc_limit != "") ? $system_config->acc_limit : 0,
                        'type' => 0,
                        'client_type' => $request->client_type,
                    ])->id;

                    if ($user) {

                        $response['trader_registration'] = true;
                        $response['trader_id'] = $user;
                        // log for retrive password-------------
                        $log = Log::create([
                            'user_id' => $user,
                            'password' => encrypt($request->password),
                            'transaction_password' => encrypt($transaction_password),
                        ]);
                        // user descriptions-------------------------
                        $user_description = UserDescription::create([
                            'country_id' => $request->country,
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                            'zip_code' => $request->zip_code,
                            'user_id' => $user,
                            'date_of_birth' => $request->data_of_birth,
                            'gender' => $request->gender,
                        ]);
                        // create activity log
                        $ip_address = request()->ip();
                        $description = "The IP address $ip_address has been add new trader";
                        $properties = User::find($user);
                        activity('add new trader')
                            ->causedBy(auth()->user()->id)
                            ->withProperties($properties)
                            ->event('add new trader')
                            ->performedOn($properties)
                            ->log($description);
                        if ($user_description) {
                            $response['create_user_description'] = true;
                        }


                        // create trading account------------------
                        if ($system_config->create_meta_acc == 1) {
                            // return $result;
                            $investor_password = $result['InvestPassword'];
                            $master_password = $result['MainPassword'];
                            $phone_password = $result['PhonePassword'];
                            $result = $result['result'];
                            $trading_account = TradingAccount::create([
                                'user_id' => $user,
                                'account_number' => $result['data']['Login'],
                                'comment' => $result['data']['Comment'],
                                'client_type' => $result['data']['Group'],
                                'leverage' => $result['data']['Leverage'],
                                'master_password' => $master_password,
                                'investor_password' => $investor_password,
                                'phone_password' => $phone_password,
                                'platform' => $request->server_name,
                                'group_id' => $request->account_type,
                                'approve_status' => 1,
                                'approve_date' => date('Y-m-d h:i:s', strtotime('now')),
                                'approved_by' => auth()->user()->id,

                            ]);
                            if ($trading_account) {
                                //<---client email as user id
                                $user = User::find($user);
                                activity("Admin open trding account")
                                    ->causedBy(auth()->user()->id)
                                    ->withProperties($trading_account)
                                    ->event("open trading account")
                                    ->performedOn($user)
                                    ->log("The IP address " . request()->ip() . " has been " .  "Open trading account");
                                // end activity log----------------->>
                                $response['create_trading_account'] = true;
                            }
                        }
                    }
                    if (isset($request->welcome_email)) {
                        $response['welcome_mail'] = true;
                    }
                }
            }
            return Response::json($response);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
    // end: add new trader

    // add account manaually
    public function account_manually(Request $request)
    {
        try {
            if ($request->op === 'email') {
                $trading_account = TradingAccount::where('id', $request->id)->first();
                $user = User::where('id', $trading_account->user_id)->first();
                $password_log = Log::where('user_id', $trading_account->user_id)->first();
                $clientPassword = decrypt($password_log->password);
                $clientTransactionPassword = decrypt($password_log->transaction_password);
                $email_status = EmailService::send_email('open-demo-account', [
                    'user_id' => $user->id,
                    'clientTransactionPassword' => $clientTransactionPassword,
                    'clientMt4AccountNumber'    => $trading_account->account_number,
                    'clientMt4AccountPassword'  => $trading_account->master_password,
                    'clientMt4InvestorPassword' => $trading_account->investor_password,
                    'clientPassword'            => $clientPassword,
                    'server'                    => strtoupper($trading_account->platform),
                    'clientUsername'            => ($user) ? $user->email : '',
                ]);
                if ($email_status) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Mail successfully sent to the user'
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Mail sending failed please try again later to send email'
                ]);
            }
            $validation_rules = [
                'account_number' => 'required|numeric|unique:trading_accounts,account_number', //<----client group id
                'leverage' => 'required',
                'platform' => 'required',
                'group' => 'required',
                'master_password' => 'required|max:32',
                'investor_password' => 'required|max:32',
                'phone_password' => 'nullable|max:32',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following errors!'
                ]);
            }
            $client_group = ClientGroup::where('id', $request->group)->first();
            $created = TradingAccount::create([
                'user_id' => $request->user_id,
                'account_number' => $request->account_number,
                'account_status' => 1,
                'platform' => $request->platform,
                'group_id' => $request->group,
                'leverage' => $request->leverage,
                'client_type' => $client_group->account_category,
                'phone_password' => $request->phone_password,
                'master_password' => $request->master_password,
                'investor_password' => $request->investor_password,
                'comment' => 'Trading account added by admin and manually',
                'block_status' => 1,
                'commission_status' => 1,
                'deposit_status' => 1,
                'commission_status' => 1,
                'approve_status' => 1,
                'approve_date' => date('Y-m-d h:i:s', strtotime(now())),
                'approved_by' => auth()->user()->id,
            ]);
            if ($created) {
                return Response::json([
                    'status' => true,
                    'message' => 'Account successfully added',
                    'id' => $created->id,
                    'has_mail' => ($request->has('has_mail')) ? true : false
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    // add account auto / open live account
    public function account_auto(Request $request)
    {
        if ($request->op === 'email') {
            $trading_account = TradingAccount::where('id', $request->id)->first();
            $user = User::where('users.id', $trading_account->user_id)
                ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->first();
            $password_log = Log::where('user_id', $trading_account->user_id)->first();
            $clientPassword = decrypt($password_log->password);
            $clientTransactionPassword = decrypt($password_log->transaction_password);

            $email_status = EmailService::send_email('open-demo-account', [
                'user_id' => $user->id,
                'clientUsername'            => ($user) ? $user->email : '',
                'clientPassword'            => $clientPassword,
                'clientTransactionPassword' => $clientTransactionPassword,
                'clientMt4AccountNumber'    => $trading_account->account_number,
                'clientMt4AccountPassword'  => $trading_account->master_password,
                'clientMt4InvestorPassword' => $trading_account->investor_password,
                'server'                    => strtoupper($trading_account->platform),
            ]);
            if ($email_status) {
                return Response::json([
                    'status' => true,
                    'message' => 'Mail successfully sent to the user'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Mail sending failed please try again later to send email'
            ]);
        }
        $validation_rules = [

            'leverage' => 'required',
            'platform' => 'required',
            'group' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        }
        $user = User::where('users.id', $request->user_id)
            // ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            // ->select('users.*','user_descriptins.country_id,')
            ->first();
        // return $user;
        // account limit
        if ($user->trading_ac_limit == 0) {
            return Response::json([
                'status' => false,
                'message' => 'This user account limit is 0'
            ]);
        }
        // return $request->user_id;
        $response = OpenLiveTradingAccountService::open_live_account([
            'user_id' => $user->id,
            'platform' => $request->platform,
            'leverage' => $request->leverage,
            'account_type' => $request->group,
        ]);
        return Response::json($response);
    }

    // all deleted trading account list
    function allDeletedTradingAccount(Request $request)
    {
        $removed_accounts = '';
        $trading_accounts = TradingAccount::select('account_number')->where('account_status', 0)->get();
        if ($trading_accounts) {
            $removed_accounts = '<option value="">Find trading account</option>';
            foreach ($trading_accounts as $account) {
                $removed_accounts .= '<option value="' . $account->account_number . '">' . $account->account_number . '</option>';
            }
        }
        return Response::json($removed_accounts);
    }

    // trading account transfer
    public function tradingAccountTransfer(Request $request)
    {
        $validation_rules = [
            'account_no' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Trading Account Is Required!'
            ]);
        }
        $account_exists = "";
        foreach ($request->account_no as $account) {
            $account_exists = TradingAccount::where('account_number', $account)->where('user_id', $request->user_id)->first();
        }
        // $update = "";
        // if (!$account_exists) {
        $update = TradingAccount::where('account_number', $request->account_no)->update([
            'user_id' => $request->user_id,
            'account_status' => 1
        ]);
        // }
        if ($update) {
            return Response::json(['status' => true, 'message' => 'Successfully Transfered']);
        } else {
            return Response::json(['status' => false, 'message' => 'Failed To Transfer!']);
        }
    }
    //ib access for trader
    public function TraderToIB(Request $request)
    {
        switch ($request->op) {
                // sending mail
            case 'mail':
                $logPass = Log::where('user_id', $request->userID)->first();
                $mail_status = EmailService::send_email('convert-to-ib', [
                    'user_id' => $request->userID,
                    'password' => decrypt($logPass->password),
                    'transaction_password' => decrypt($logPass->transaction_password),
                ]);
                if ($mail_status) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Mail successfully send to user',
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Connection failed! mail could not send in this time, try again later!',
                ]);
                break;

            default:
                // convert to IB
                // update user table
                $update = User::where('id', $request->user_id)->update([
                    'combine_access' => 1
                ]);
                if ($update) {
                    return Response::json([
                        'success' => true,
                        'message' => 'Trader to IB Convert Successfully done',
                        'success_title' => 'Convert To IB',
                        'user_id' => $request->user_id,
                    ]);
                }
                return Response::json([
                    'success' => false,
                    'message' => 'Mail sending failed, Please try again later!',
                    'success_title' => 'Convert To IB'
                ]);
                break;
        }
    }
    //remove ib access script
    public function RemoveIBAccess(Request $request)
    {
        switch ($request->op) {
            case 'mail':
                // sending mail to user
                // remove from ib
                $logPass = Log::where('user_id', $request->userID)->first();
                $mail_status = EmailService::send_email('remove-from-ib', [
                    'user_id' => $request->userID,
                    'password' => decrypt($logPass->password),
                    'transaction_password' => decrypt($logPass->transaction_password),
                ]);
                if ($mail_status) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Mail successfully send to user',
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Connection failed! mail could not send in this time, try again later!',
                ]);
                break;

            default:
                // update user table combine_access
                // remove from ib
                $update = User::where('id', $request->user_id)->update(['combine_access' => 0]);
                if ($update) {
                    return Response::json([
                        'success' => true,
                        'message' => 'Remove from IB operation successfully done!',
                        'user_id' => $request->user_id,
                    ]);
                }
                return Response::json([
                    'success' => false,
                    'message' => 'Remove from IB operation failed, Please try again later!',
                ]);
                break;
        }
    }

    // finance operation start
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
                ['trader_to_ib' => ($request->request_for === 'enable') ? 1 : 0]
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
    // internal transfer report
    public function internal_transfer_report(Request $request, $id)
    {
        try {
            $columns = ['trading_accounts.account_number', 'trading_accounts.platform', 'internal_transfers.type', 'internal_transfers.status', 'internal_transfers.created_at', 'internal_transfers.amount'];
            $orderby = $columns[$request->order[0]['column']];
            // select type= 0 for trader
            $result = InternalTransfer::where('internal_transfers.user_id', $id)
                ->join('trading_accounts', 'internal_transfers.account_id', '=', 'trading_accounts.id')
                ->where('trading_accounts.account_status', 1)
                ->select(
                    'trading_accounts.account_number',
                    'trading_accounts.platform',
                    'internal_transfers.type',
                    'internal_transfers.status',
                    'internal_transfers.created_at',
                    'internal_transfers.charge',
                    'internal_transfers.amount'
                );
            $total_balance[0] = $result->sum('amount');
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                // status
                $status = '';
                if ($value->status === 'P') {
                    $status = '<span class="badge badge-secondary bg-secondary">Pending</span>';
                } elseif ($value->status === 'A') {
                    $status = '<span class="badge badge-success bg-success">Aproved</span>';
                } elseif ($value->status === 'D') {
                    $status = '<span class="badge badge-danger bg-danger">Aproved</span>';
                }
                $user = User::find($id);
                $data[$i]["account_number"]    = $value->account_number;
                $data[$i]["platform"] = $value->platform;
                $data[$i]["method"] = strtoupper($value->type);
                $data[$i]["status"] = $status;
                $data[$i]["date"] = date('d M Y', strtotime($value->created_at));
                $data[$i]["amount"] = $value->amount;
                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total' => $total_balance,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'total' => 0,
                'data' => []
            ]);
        }
    }
    // external transfer report
    public function external_transfer_report(Request $request, $id)
    {
        try {
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $order = $_GET['order'][0]["column"];
            $orderDir = $_GET["order"][0]["dir"];

            $columns = ['status', 'status', 'type', 'status', 'charge', 'created_at', 'amount'];
            $orderby = $columns[$order];
            
            // select type= 0 for trader
            // $result = ExternalFundTransfers::where('external_fund_transfers.sender_id', $id)
            //     ->join('users', 'external_fund_transfers.receiver_id', '=', 'users.id')
            //     ->select(
            //         'users.email',
            //         'users.type',
            //         'external_fund_transfers.status',
            //         'external_fund_transfers.charge',
            //         'external_fund_transfers.amount',
            //         'external_fund_transfers.created_at'
            //     );
            
            // $result = ExternalFundTransfers::join('users', function ($join) {
            //         $join->on('external_fund_transfers.receiver_id', '=', 'users.id')
            //              ->orOn('external_fund_transfers.sender_id', '=', 'users.id');
            //     })
            //     ->where(function ($query) use ($id) {
            //         $query->where('external_fund_transfers.sender_id', $id)
            //               ->orWhere('external_fund_transfers.receiver_id', $id);
            //     })
            //     ->distinct()
            //     ->select(
            //         'users.email',
            //         'users.type',
            //         'external_fund_transfers.status',
            //         'external_fund_transfers.charge',
            //         'external_fund_transfers.amount',
            //         'external_fund_transfers.created_at'
            //     );  
            
            $result = ExternalFundTransfers::select(
                'external_fund_transfers.sender_id',
                'external_fund_transfers.receiver_id',
                'external_fund_transfers.status',
                'external_fund_transfers.charge',
                'external_fund_transfers.type',
                'external_fund_transfers.created_at',
                'external_fund_transfers.amount',
                'external_fund_transfers.id as transaction_id'
            )
            ->with(['sender', 'receiver'])
            ->where(function ($query) use ($id) { // Pass $id to the closure
                $query->where('receiver_id', $id)
                      ->orWhere('sender_id', $id);
            })
            ->where(function ($query) use ($id) { // Pass $id to the closure
                $query->where(function ($q) use ($id) { // Pass $id to the closure
                    $q->where('sender_id', $id)
                      ->where('sender_wallet_type', 'trader')
                      ->orWhere('sender_wallet_type', 'ib');
                })
                ->orWhere(function ($q) use ($id) { // Pass $id to the closure
                    $q->where('receiver_id', $id)
                      ->where('receiver_wallet_type', 'trader');
                });
            });


            $count = $result->count();
            $result = $result->orderby($orderby, $orderDir)->skip($start)->take($length)->get();
            $data = array();
            $i = 0;
            $amount = 0;

            foreach ($result as $user) {
                if ($user->status == 'P') {
                    $status = '<span class="bg-light-warning badge badge-warning">Pending</span>';
                } elseif ($user->status == 'A') {
                    $status = '<span class="bg-light-success badge badge-success">Approved</span>';
                } elseif ($user->status == 'D') {
                    $status = '<span class="bg-light-danger badge badge-danger">Declined</span>';
                }
                // sender or receiver
                $sender = isset($user->sender->email) ? $user->sender->email : '---';
                if (strtolower(auth()->user()->email) === strtolower($sender)) {
                    $sender = '<i class="fas fa-circle text-info" style="font-size: 10px;margin-right: 4px;"></i>' . $sender;
                }
                $receiver = isset($user->receiver->email) ? $user->receiver->email : '---';
                if (strtolower(auth()->user()->email) === strtolower($receiver)) {
                    $receiver = '<i class="fas fa-circle text-info" style="font-size: 10px;margin-right: 4px;"></i>' . $receiver;
                }
                $type = (auth()->user()->id == $user->sender_id) ? 'Send' : 'Receive';
                $data[$i]['sender_email'] = $sender;
                $data[$i]['receiver_email'] = $receiver;
                $data[$i]['type'] = $type;
                $data[$i]['date'] = date('d F y, h:i A', strtotime($user->created_at));
                $data[$i]['status'] = $status;
                $data[$i]['charge'] = '$' . $user->charge;
                $data[$i]['amount'] = '$' . $user->amount;
                $amount += $user->amount;
                $i++;
            }
            
            // foreach ($result as $key => $value) {
            //     // status
            //     $status = '';
            //     if ($value->status === 'P') {
            //         $status = '<span class="badge badge-secondary bg-secondary">Pending</span>';
            //     } elseif ($value->status === 'A') {
            //         $status = '<span class="badge badge-success bg-success">Aproved</span>';
            //     } elseif ($value->status === 'D') {
            //         $status = '<span class="badge badge-danger bg-danger">Aproved</span>';
            //     }
            //     $user = User::find($id);
            //     $data[$i]["receiver_email"]    = $value->email;
            //     $data[$i]["type"] = strtoupper(($value->type == 4) ? 'IB' : 'Trader');
            //     $data[$i]["date"] = date('d M Y', strtotime($value->created_at));
            //     $data[$i]["status"] = $status;
            //     $data[$i]["charge"] = $value->charge;
            //     $data[$i]["amount"] = $value->amount;
            //     $i++;
            // }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'total' => '$'.round($amount, 2),
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'total' => 0,
                'data' => []
            ]);
        }
    }
    
    
    public function bulkassignManager(Request $request)
    {
        // Validate input
        $request->validate([
            'manager_email' => 'required|email',
            'user_ids' => 'required|string'
        ]);

        // Find manager by email
        $manager = User::where('email', $request->manager_email)->where('type', 5)->first();
        
        if (!$manager) {
            return response()->json([
                'status' => false,
                'message' => 'Manager not found!'
            ]);
        }

        // Convert user_ids from comma-separated string to array
        $userIds = explode(',', $request->user_ids);

        // Remove already assigned users to prevent duplicate assignments
        $existingAssignments = ManagerUser::whereIn('user_id', $userIds)
            ->where('manager_id', $manager->id)
            ->pluck('user_id')
            ->toArray();

        $newUserIds = array_diff($userIds, $existingAssignments);

        if (empty($newUserIds)) {
            return response()->json([
                'status' => false,
                'message' => 'All selected users are already assigned to this manager.'
            ]);
        }

        // Assign users in bulk using insert
        $insertData = array_map(function($userId) use ($manager) {
            return [
                'user_id' => $userId,
                'manager_id' => $manager->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $newUserIds);

        ManagerUser::insert($insertData);

        return response()->json([
            'status' => true,
            'message' => 'Account manager successfully assigned.',
            'assigned_users' => $newUserIds
        ]);
    }

    
    
}
