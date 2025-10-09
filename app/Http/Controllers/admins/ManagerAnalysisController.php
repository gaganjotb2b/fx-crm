<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\IB;
use App\Models\ManagerUser;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use App\Services\manager\ManagerAnalysisService;
use App\Services\manager\ManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ManagerAnalysisController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:manager analysis"]);
        $this->middleware(["role:manager settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('manager_settings', 'admin'));
        $this->middleware(AllFunctionService::access('manager_analysis', 'admin'));
    }
    //basic view 
    // ------------------------------------------------
    public function index(Request $request)
    {
        return view('admins.manager-settings.manager-analysis');
    }

    // data filter
    // ----------------------------------------------------------------
    public function filter(Request $request)
    {
        //load from service
        $data = ManagerAnalysisService::manager_analysis([
            'search_email' => $request->search_email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        $manager = User::where('email', $request->search_email)->where('type', 5)->first();
        if (!$manager) {
            return Response::json([
                'status' => false,
                'message' => 'Invalid Email, Please Enter valid manager email'
            ]);
        }
        // deposits
        $pending_deposit_all = ManagerAnalysisService::deposit([
            'manager_id' => $manager->id,
            'approved_status' => 'P'
        ]);
        $pending_deposit_direct = ManagerAnalysisService::deposit([
            'manager_id' => $manager->id,
            'approved_status' => 'P',
            'direct' => true,
        ]);
        $pending_deposit_affiliat = ManagerAnalysisService::deposit([
            'manager_id' => $manager->id,
            'approved_status' => 'P',
            'affiliated' => true,
        ]);
        $approved_deposit_all = ManagerAnalysisService::deposit([
            'manager_id' => $manager->id,
            'approved_status' => 'A',
        ]);
        $approved_deposit_direct = ManagerAnalysisService::deposit([
            'manager_id' => $manager->id,
            'approved_status' => 'A',
            'direct' => true,
        ]);
        $approved_deposit_affiliat = ManagerAnalysisService::deposit([
            'manager_id' => $manager->id,
            'approved_status' => 'A',
            'affiliated' => true,
        ]);
        // withdraws
        $approved_withdraw_all = ManagerAnalysisService::withdraw([
            'manager_id' => $manager->id,
            'approved_status' => 'A',
        ]);
        $approved_withdraw_direct = ManagerAnalysisService::withdraw([
            'manager_id' => $manager->id,
            'approved_status' => 'A',
            'direct' => true,
        ]);
        $approved_withdraw_affiliate = ManagerAnalysisService::withdraw([
            'manager_id' => $manager->id,
            'approved_status' => 'A',
            'arriliated' => true,
        ]);
        $pending_withdraw_all = ManagerAnalysisService::withdraw([
            'manager_id' => $manager->id,
            'approved_status' => 'P',
        ]);
        $pending_withdraw_direct = ManagerAnalysisService::withdraw([
            'manager_id' => $manager->id,
            'approved_status' => 'P',
            'direct' => true,
        ]);
        $pending_withdraw_affiliat = ManagerAnalysisService::withdraw([
            'manager_id' => $manager->id,
            'approved_status' => 'P',
            'affiliated' => true,
        ]);
        // client data get
        $referral_ib = ManagerService::manager_refer_link($manager->id, 'ib');
        $referral_client = ManagerService::manager_refer_link($manager->id, 'trader');
        return Response::json([
            'status' => true,
            'user_info' => $data['user_info'],
            'total_trader' => $data['total_trader'], //1
            'total_ib' => $data['total_ib'], //2
            'total_deposit' => $data['total_deposit'], //3
            'total_withdraw' => $data['total_withdraw'], //4
            'total_trade_volume' => $data['total_trade_volume'], //5
            'total_ib_commission' => $data['total_ib_commission'], //6
            'ib_commission_all' => $data['ib_commission_all'],
            'trading_accounts' => $data['trading_accounts'], //7
            'total_bonus' => $data['total_bonus'], //8
            'total_leads' => 0,
            // pending deposit
            'pending_deposit_all' => $pending_deposit_all,
            'pending_deposit_direct' => $pending_deposit_direct,
            'pending_deposit_affiliat' => $pending_deposit_affiliat,
            // approved deposit
            'approved_deposit_all' => $approved_deposit_all,
            'approved_deposit_direct' => $approved_deposit_direct,
            'approved_deposit_affiliat' => $approved_deposit_affiliat,
            // deposit total
            'total_deposit_all' => ($pending_deposit_all + $approved_deposit_all),
            'total_deposit_direct' => ($pending_deposit_direct + $approved_deposit_direct),
            'total_deposit_affiliat' => ($pending_deposit_affiliat + $approved_deposit_affiliat),
            // withdraw approved
            'approved_withdraw_all' => $approved_withdraw_all,
            'approved_withdraw_direct' => $approved_withdraw_direct,
            'approved_withdraw_affiliat' => $approved_withdraw_affiliate,
            // withdraw pending
            'pending_withdraw_all' => $pending_withdraw_all,
            'pending_withdraw_direct' => $pending_withdraw_direct,
            'pending_withdraw_affiliat' => $pending_withdraw_affiliat,
            // withdraw total
            'total_withdraw_all' => ($approved_deposit_all + $pending_deposit_all),
            'total_withdraw_direct' => ($approved_deposit_direct + $pending_deposit_direct),
            'total_withdraw_affiliat' => ($approved_deposit_affiliat + $pending_deposit_affiliat),
            // clients data
            'active_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'active',
                'user_type' => CombinedService::type(),
            ]),
            'active_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'active',
                'direct' => true,
                'user_type' => CombinedService::type(),
            ]),
            'active_affiliat' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'active',
                'affiliated' => true,
                'user_type' => CombinedService::type(),
            ]),
            // disabled clients
            'disabled_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'disabled',
                'user_type' => CombinedService::type(),
            ]),
            'disabled_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'disabled',
                'affiliated' => true,
                'user_type' => CombinedService::type(),
            ]),
            'disabled_affiliat' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'disabled',
                'affiliated' => true,
                'user_type' => CombinedService::type(),
            ]),
            // lived clients
            'live_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'live',
                'user_type' => CombinedService::type(),
            ]),
            'live_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'live',
                'direct' => true,
                'user_type' => CombinedService::type(),
            ]),
            'live_affiliat' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'live',
                'affiliatd' => true,
                'user_type' => CombinedService::type(),
            ]),
            // demo clients
            'demo_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'demo',
                'user_type' => CombinedService::type(),
            ]),
            'demo_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'demo',
                'direct' => true,
                'user_type' => CombinedService::type(),
            ]),
            'demo_affiliat' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => $manager->id,
                'status' => 'demo',
                'affiliatd' => true,
                'user_type' => CombinedService::type(),
            ]),
            // active ib
            // start ib area
            'ib_active_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'active',
                'user_type' => CombinedService::type(),
                'combine_access' => 1,
            ]),
            'ib_active_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'active',
                'direct' => true,
                'user_type' => CombinedService::type(),
                'combine_access' => 1,
            ]),
            'ib_active_affiliat' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'active',
                'affiliated' => true,
                'user_type' => CombinedService::type(),
                'combine_access' => 1,
            ]),
            // disabled ib
            'ib_disabled_total' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'disabled',
                'user_type' => CombinedService::type(),
                'combine_access' => 1,
            ]),
            'ib_disabled_direct' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'disabled',
                'affiliated' => true,
                'user_type' => CombinedService::type(),
                'combine_access' => 1,
            ]),
            'ib_disabled_affiliat' => ManagerAnalysisService::count_clients_by_status([
                'manager_id' => auth()->user()->id,
                'status' => 'disabled',
                'affiliated' => true,
                'user_type' => CombinedService::type(),
                'combine_access' => 1,
            ]),
            'ib_referral_link' => $referral_ib,
            'trader_referral_link' => $referral_client,
        ]);
    }
}
