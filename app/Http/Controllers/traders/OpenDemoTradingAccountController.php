<?php

namespace App\Http\Controllers\traders;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\DemoAccountMail;
use App\Models\admin\SystemConfig;
use App\Models\ClientGroup;
use App\Models\Country;
use App\Models\Log;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\accounts\OpenDemoAccountService;
use App\Services\AllFunctionService;
use App\Services\Mt5WebApi;
use App\Services\OpenAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\checkSettingsService;
use App\Services\EmailService;
use App\Services\MT4API;

class OpenDemoTradingAccountController extends Controller
{
    public function __construct()
    {
        switch (request()->is('user/trading-account/get-client-group')) {
            case true:
                // $this->middleware(AllFunctionService::access('open_demo_account', 'trader'));
                $this->middleware(AllFunctionService::access('trading_accounts', 'trader'));
                break;

            default:
                $this->middleware(AllFunctionService::access('open_demo_account', 'trader'));
                $this->middleware(AllFunctionService::access('trading_accounts', 'trader'));
                break;
        }
    }
    //basic view------------------------
    public function open_demo_account(Request $request)
    {
        // user profile data
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)->first();
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }
        // system configure data
        $system_config = SystemConfig::select('platform_type')->first();
        $server = '';
        $single_platform = true;
        if ($system_config) {
            if ($system_config->platform_type === 'both') {
                $server .= '<option value="mt5">mt5</option>';
                $server .= '<option value="mt4">mt4</option>';
                $single_platform = false;
            } else {
                $server .= '<option value="' . $system_config->platform_type . '">' . strtoupper($system_config->platform_type) . '</option>';
            }
        }
        $params = [
            'single_platform' => $single_platform, // or some condition logic
            'accountType' => 'demo' // or any account type you need to pass
        ];
        // return $server;
        return view('traders.trading-account.open-demo-trading-account', [
            'server' => $server, 
            'avatar' => $avatar,
            'params' => $params
        ]);
    }
    // get client group---------------------
    
    
    
   

    // public function get_client_group(Request $request)
    // {
    //     $account_type = '';
    //     $leverage_option = '';
    //     $platform_logo = asset('trader-assets/assets/img/logos/platform-logo/mt5.png');
    //     $leverage = [];
    //     // get client group
    //     if ($request->op === 'server') {
    //         $user = auth()->user();
    //         $userGroupIds = json_decode($user->client_groups, true);
    
    //         // Base query
    //         $client_group_query = ClientGroup::where('server', $request->platform)
    //             ->where('account_category', $request->account_type)
    //             ->where('active_status', 1)
    //             ->where('visibility', 'visible');
    
    //         // Apply filtering based on user's assigned groups
    //         if ($request->account_type != 'demo'){
    //             if (!empty($userGroupIds) && is_array($userGroupIds)) {
    //                 // User has assigned groups - show only those groups
    //                 $client_group_query = $client_group_query->whereIn('id', $userGroupIds);
    //             } else {
    //                 // User has no assigned groups - show only unassigned groups
    //                 // Get all group IDs that are currently assigned to any user
    //                 $assignedGroupIds = User::whereNotNull('client_groups')
    //                     ->where('client_groups', '!=', '')
    //                     ->where('client_groups', '!=', 'null')
    //                     ->pluck('client_groups')
    //                     ->filter(function($groups) {
    //                         return !empty($groups) && $groups !== 'null';
    //                     })
    //                     ->map(function($groups) {
    //                         return json_decode($groups, true);
    //                     })
    //                     ->flatten()
    //                     ->unique()
    //                     ->values()
    //                     ->toArray();
                    
    //                 \Log::info('User has no assigned groups - showing unassigned groups only');
    //                 \Log::info('Currently assigned group IDs across all users: ' . json_encode($assignedGroupIds));
                    
    //                 // Exclude assigned groups and special accounts
    //                 $client_group_query = $client_group_query->whereNotIn('id', $assignedGroupIds)
    //                     ->whereNotIn('group_id', ['Cent Account', 'Special Account', 'Tournament']);
    //             }
    //         }
           
    //         $client_group = $client_group_query->get();
    
    //         // Log for debugging
    //         \Log::info('Client group filtering for user ID: ' . $user->id);
    //         \Log::info('User client_groups: ' . json_encode($userGroupIds));
    //         \Log::info('Platform: ' . $request->platform . ', Account type: ' . $request->account_type);
    //         \Log::info('Available groups count: ' . $client_group->count());
    //         \Log::info('Available group IDs: ' . $client_group->pluck('id')->implode(', '));
    
    //         if (!$client_group->isEmpty()) {
    //             foreach ($client_group as $key => $value) {
    //                 $account_type .= '<option value="' . encrypt($value->id) . '">' . $value->group_id . '</option>';
    //             }
    //             $leverage = json_decode($client_group[0]->leverage);
    //         }
    //         if ($request->platform === 'mt4') {
    //             $platform_logo = asset('trader-assets/assets/img/logos/platform-logo/mt4.png');
    //         }
    //     }
    //     // get leverage
    //     if ($request->op === 'client-group') {
    //         $leverage = ClientGroup::find(decrypt($request->client_group));
    //         $leverage = json_decode($leverage->leverage);
    //     }
    //     // create leverage options
    //     for ($i = 0; $i < count($leverage); $i++) {
    //         $leverage_option .= '<option value="' . $leverage[$i] . '">' . $leverage[$i] . '</option>';
    //     }
    //     return Response::json([
    //         'account_type' => $account_type,
    //         'leverage' => $leverage_option,
    //         'platform_logo' => $platform_logo
    //     ]);
    // }
    
    public function get_client_group(Request $request)
    {
        $account_type = '';
        $leverage_option = '';
        $platform_logo = asset('trader-assets/assets/img/logos/platform-logo/mt5.png');
        $leverage = [];
    
        if ($request->op === 'server') {
            $user = auth()->user();
            
            // Decode user group IDs safely
            $userGroupIds = json_decode($user->client_groups ?? '[]', true);
            if (!is_array($userGroupIds)) {
                $userGroupIds = [];
            }
    
            // Base query
            $client_group_query = ClientGroup::where('server', $request->platform)
                ->where('account_category', $request->account_type)
                ->where('active_status', 1)
                ->where('visibility', 'visible');
    
            // Apply filtering for live accounts
            if (strtolower($request->account_type) !== 'demo') {
                if (!empty($userGroupIds)) {
                    // User has assigned groups
                    $client_group_query->whereIn('id', $userGroupIds);
                } else {
                    // User has no assigned groups - show unassigned groups only
                    $assignedGroupIds = User::whereNotNull('client_groups')
                        ->pluck('client_groups')
                        ->filter(function ($groups) {
                            return !empty($groups) && $groups !== 'null';
                        })
                        ->map(function ($groups) {
                            $decoded = json_decode($groups, true);
                            return is_array($decoded) ? $decoded : [];
                        })
                        ->flatten()
                        ->unique()
                        ->values()
                        ->toArray();
    
                    \Log::info('User has no assigned groups - showing unassigned groups only');
                    \Log::info('Assigned group IDs: ' . json_encode($assignedGroupIds));
    
                    $client_group_query->whereNotIn('id', $assignedGroupIds)
                        ->whereNotIn('group_id', ['Cent Account', 'Special Account', 'Tournament']);
                }
            }
    
            $client_group = $client_group_query->get();
    
            \Log::info("User ID: {$user->id}, Client groups found: " . $client_group->count());
            \Log::info('Group IDs available: ' . $client_group->pluck('id')->implode(', '));
    
            if (!$client_group->isEmpty()) {
                foreach ($client_group as $group) {
                    $account_type .= '<option value="' . encrypt($group->id) . '">' . $group->group_id . '</option>';
                }
                $leverage = json_decode($client_group[0]->leverage ?? '[]');
            }
    
            // Set logo depending on platform
            if (strtolower($request->platform) === 'mt4') {
                $platform_logo = asset('trader-assets/assets/img/logos/platform-logo/mt4.png');
            }
        }
    
        if ($request->op === 'client-group') {
            $group = ClientGroup::find(decrypt($request->client_group));
            $leverage = json_decode($group->leverage ?? '[]');
        }
    
        // Generate leverage options
        if (is_array($leverage)) {
            foreach ($leverage as $value) {
                $leverage_option .= '<option value="' . $value . '">' . $value . '</option>';
            }
        }
    
        return Response::json([
            'account_type' => $account_type,
            'leverage' => $leverage_option,
            'platform_logo' => $platform_logo
        ]);
    }
    

    
    // open demo trading account---------------
    public function open_demo_account_form(Request $request)
    {
        try {
            $validation_rules = [
                'platform' => 'required',
                'account_type' => 'required',
                'leverage' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                // dd("validation fails");
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            
            $result = OpenDemoAccountService::open_demo_account([
                'user_id' => auth()->user()->id,
                'group_id' => decrypt($request->account_type),
                'leverage' => $request->leverage,
                'platform' => $request->platform,
                // 'is_register' => false,
            ]);
            // dd("validation done ",$result);
            return Response::json($result);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error',
                'error'=>$th->getMessage()
            ]);
        }
    }
}
