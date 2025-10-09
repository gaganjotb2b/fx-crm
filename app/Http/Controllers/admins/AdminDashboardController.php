<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\admin\InternalTransfer;

use App\Models\BankAccount;
use App\Models\Deposit;
use App\Models\ExternalFundTransfers;
use App\Models\KycVerification;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\GetBrowserService;
use Illuminate\Http\Request;
use App\Models\Withdraw;

use Spatie\Activitylog\Models\Activity;
use App\Models\tickets;
use App\Services\common\UserService;
use App\Services\manager\ManagerService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        if (auth()->user()->type == "manager") {
            // // get manager country
            // $referral_ib = ManagerService::manager_refer_link(auth()->user()->id, 'ib');
            // $referral_client = ManagerService::manager_refer_link(auth()->user()->id, 'trader');
            // return view(
            //     'managers.index',
            //     [
            //         'country' => UserService::get_country(),
            //         'ib_referral_link' => $referral_ib,
            //         'trader_referral_link' => $referral_client,
            //     ]
            // );
            $user_agent = new GetBrowserService();
            $data = [];
            $devices = Activity::where(function ($query) {
                $query->where('causer_id', auth()->user()->id)
                    ->where('event', 'login');
            })->latest()->limit(5)->select('properties', 'description', 'id', 'created_at')->get();
            $user_descriptions = UserDescription::where('user_id', auth()->user()->id)
                ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
                ->first();
    
            $i = 0;
            // return $devices;
            foreach ($devices as $key => $value) {
                $check_loging = Activity::where(function ($query) {
                    $query->where('causer_id', auth()->user()->id)
                        ->where('event', 'logout');
                })->where('batch_uuid', $value->id)->exists();
                $login_device = json_decode($value->properties);
                // if($i==1){
                //     return $login_device;
                // }
    
                $data['device_' . ($i + 1)] = $user_agent->user_agent($login_device[0]);
                $ip_address = explode(' ', $value->description);
                $data['device_' . ($i + 1)]['ip_address'] = $ip_address[3];
                $data['device_' . ($i + 1)]['login_at'] = $value->created_at->diffForHumans();
                $i++;
            }
            // return $data;
            $total_kyc_user = AllFunctionService::kyc_verified_unverified();
            $revenue_report = AllFunctionService::get_revenue_report();
            $per_month_chart = AllFunctionService::per_month_chart(date('m'));
            $deposit_per_month = AllFunctionService::deposit_per_month(date('m'));
            $withdraw_per_month = AllFunctionService::withdraw_per_month(date('m'));
            // return AllFunctionService::withdraw_per_month(date('m'));
    
            // pending withdraw
            $last_withdraw_request = Withdraw::where('approved_status', 'P')->latest()->first();
            $last_deposit_request = Deposit::where('approved_status', 'P')->latest()->first();
            $last_kyc_request = KycVerification::where('status', 0)
                ->join('kyc_id_type', 'kyc_verifications.doc_type', '=', 'kyc_id_type.id')
                ->select('kyc_verifications.id', 'kyc_verifications.created_at', 'kyc_verifications.perpose', 'kyc_verifications.user_id', 'kyc_id_type.id_type')
                ->latest()->first();
            $last_bank_request = BankAccount::where('approve_status', 'p')->latest()->first();
            $last_internal_transfer = InternalTransfer::where('status', 'P')->latest()->first();
            $last_external_transfer = ExternalFundTransfers::where('status', 'P')->latest()->first();
            return view(
                'admins.dashboard',
                [
                    'login_history' => $data,
                    'kyc_status' => $total_kyc_user,
                    'revenue_report' => $revenue_report,
                    'pending_deposit_chart' => json_encode(AllFunctionService::pending_deposit_chart()),
                    'pending_withdraw_chart' => json_encode(AllFunctionService::pending_withdraw_chart()),
                    'per_month_depo_chart' => json_encode($per_month_chart['deposit']),
                    'per_month_withdraw_chart' => json_encode($per_month_chart['withdraw']),
                    'commission_chart' => AllFunctionService::commission_chart(),
                    'deposit_per_month' => $deposit_per_month,
                    'withdraw_per_month' => $withdraw_per_month,
                    'last_withdraw_request' => $last_withdraw_request,
                    'last_deposit_request' => $last_deposit_request,
                    'last_kyc_request' => $last_kyc_request,
                    'last_bank_request' => $last_bank_request,
                    'last_external_transfer' => $last_external_transfer,
                    'last_internal_transfer' => $last_internal_transfer,
                    'supportTracker' => $this->supportTracker()
                ]
            );
        }
        $user_agent = new GetBrowserService();
        $data = [];
        $devices = Activity::where(function ($query) {
            $query->where('causer_id', auth()->user()->id)
                ->where('event', 'login');
        })->latest()->limit(5)->select('properties', 'description', 'id', 'created_at')->get();
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)
            ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->first();

        $i = 0;
        // return $devices;
        foreach ($devices as $key => $value) {
            $check_loging = Activity::where(function ($query) {
                $query->where('causer_id', auth()->user()->id)
                    ->where('event', 'logout');
            })->where('batch_uuid', $value->id)->exists();
            $login_device = json_decode($value->properties);
            // if($i==1){
            //     return $login_device;
            // }

            $data['device_' . ($i + 1)] = $user_agent->user_agent($login_device[0]);
            $ip_address = explode(' ', $value->description);
            $data['device_' . ($i + 1)]['ip_address'] = $ip_address[3];
            $data['device_' . ($i + 1)]['login_at'] = $value->created_at->diffForHumans();
            $i++;
        }
        // return $data;
        $total_kyc_user = AllFunctionService::kyc_verified_unverified();
        $revenue_report = AllFunctionService::get_revenue_report();
        $per_month_chart = AllFunctionService::per_month_chart(date('m'));
        $deposit_per_month = AllFunctionService::deposit_per_month(date('m'));
        $withdraw_per_month = AllFunctionService::withdraw_per_month(date('m'));
        // return AllFunctionService::withdraw_per_month(date('m'));

        // pending withdraw
        $last_withdraw_request = Withdraw::where('approved_status', 'P')->latest()->first();
        $last_deposit_request = Deposit::where('approved_status', 'P')->latest()->first();
        $last_kyc_request = KycVerification::where('status', 0)
            ->join('kyc_id_type', 'kyc_verifications.doc_type', '=', 'kyc_id_type.id')
            ->select('kyc_verifications.id', 'kyc_verifications.created_at', 'kyc_verifications.perpose', 'kyc_verifications.user_id', 'kyc_id_type.id_type')
            ->latest()->first();
        $last_bank_request = BankAccount::where('approve_status', 'p')->latest()->first();
        $last_internal_transfer = InternalTransfer::where('status', 'P')->latest()->first();
        $last_external_transfer = ExternalFundTransfers::where('status', 'P')->latest()->first();
        return view(
            'admins.dashboard',
            [
                'login_history' => $data,
                'kyc_status' => $total_kyc_user,
                'revenue_report' => $revenue_report,
                'pending_deposit_chart' => json_encode(AllFunctionService::pending_deposit_chart()),
                'pending_withdraw_chart' => json_encode(AllFunctionService::pending_withdraw_chart()),
                'per_month_depo_chart' => json_encode($per_month_chart['deposit']),
                'per_month_withdraw_chart' => json_encode($per_month_chart['withdraw']),
                'commission_chart' => AllFunctionService::commission_chart(),
                'deposit_per_month' => $deposit_per_month,
                'withdraw_per_month' => $withdraw_per_month,
                'last_withdraw_request' => $last_withdraw_request,
                'last_deposit_request' => $last_deposit_request,
                'last_kyc_request' => $last_kyc_request,
                'last_bank_request' => $last_bank_request,
                'last_external_transfer' => $last_external_transfer,
                'last_internal_transfer' => $last_internal_transfer,
                'supportTracker' => $this->supportTracker()
            ]
        );
    }

    private function supportTracker()
    {
        $date = Carbon::now()->subDays(7);
        // $allTicket = tickets::whereDate('created_at', '>=', $date)->count();
        // $newTicket = tickets::whereDate('created_at', '>=', $date)->where('status', 'On-Hold')->count();
        // $openTicket = tickets::whereDate('created_at', '>=', $date)->where('status', 'Open')->count();

        $allTicket = tickets::count();
        $newTicket = tickets::where('status', 'On-Hold')->count();
        $openTicket = tickets::where('status', 'Open')->count();

        $complate  = tickets::where('status', 'Closed')->count();
        $percent = 0;
        if ($allTicket != 0) {
            $percent  = ((100 * $complate) / $allTicket);
        }
        return [
            'allTicket' => $allTicket,
            'newTicket' => $newTicket,
            'openTicket' => $openTicket,
            'avgTime' => 1,
            'chartPecent'  => round($percent, 2)
        ];
    }
    public function supportTrackerFilter(Request $request)
    {

        if ($request->filter_by ==  'all') {
            $allTicket = tickets::count();
            $newTicket = tickets::where('status', 'On-Hold')->count();
            $openTicket = tickets::where('status', 'Open')->count();
            $complate  = tickets::where('status', 'Closed')->count();
        } else if ($request->filter_by ==  'last_year') {
            $date = Carbon::now()->format('Y');
            $allTicket = tickets::whereDate('created_at', '>=', $date)->count();
            $newTicket = tickets::whereDate('created_at', '>=', $date)->where('status', 'On-Hold')->count();
            $openTicket = tickets::whereDate('created_at', '>=', $date)->where('status', 'Open')->count();
            $complate  = tickets::whereDate('created_at', '>=', $date)->whereNotIn('status', ['Closed'])->count();
        } else {
            $filter_by = $request->filter_by;
            $date = Carbon::now()->subDays($filter_by);
            $allTicket = tickets::whereDate('created_at', '>=', $date)->count();
            $newTicket = tickets::whereDate('created_at', '>=', $date)->where('status', 'On-Hold')->count();
            $openTicket = tickets::whereDate('created_at', '>=', $date)->where('status', 'Open')->count();
            $complate  = tickets::whereDate('created_at', '>=', $date)->whereNotIn('status', ['Closed'])->count();
        }

        $percent =  0;
        if ($allTicket != 0) {
            $percent  = ((100 * $complate) / $allTicket);
        }
        return Response::json([
            'status' => true,
            'allTicket' => $allTicket,
            'newTicket' => $newTicket,
            'openTicket' => $openTicket,
            'avgTime' => 1,
            'chartPecent'  => round($percent, 2)
        ]);
    }

    public function getRevenueDataByMonth(Request $request)
    {
        $month = $request->input('month');
        
        \Log::info('getRevenueDataByMonth called with month: ' . $month);
        
        if (!$month) {
            return Response::json([
                'status' => false,
                'message' => 'Month parameter is required'
            ]);
        }

        // Get deposit and withdraw data for the selected month
        $deposit_per_month = AllFunctionService::deposit_per_month($month);
        $withdraw_per_month = AllFunctionService::withdraw_per_month($month);
        
        // Get per month chart data
        $per_month_chart = AllFunctionService::per_month_chart($month);
        
        \Log::info('Revenue data for month ' . $month . ': deposit=' . $deposit_per_month . ', withdraw=' . $withdraw_per_month);
        
        return Response::json([
            'status' => true,
            'deposit_per_month' => $deposit_per_month,
            'withdraw_per_month' => $withdraw_per_month,
            'per_month_depo_chart' => $per_month_chart['deposit'],
            'per_month_withdraw_chart' => $per_month_chart['withdraw'],
            'debug' => [
                'month' => $month,
                'deposit_count' => count($per_month_chart['deposit']),
                'withdraw_count' => count($per_month_chart['withdraw'])
            ]
        ]);
    }

    public function testRevenueData()
    {
        $currentMonth = date('m');
        $deposit_per_month = AllFunctionService::deposit_per_month($currentMonth);
        $withdraw_per_month = AllFunctionService::withdraw_per_month($currentMonth);
        
        // Debug: Check if there are any deposits in the database
        $totalDeposits = \App\Models\Deposit::count();
        $approvedDeposits = \App\Models\Deposit::where('approved_status', 'A')->count();
        $currentMonthDeposits = \App\Models\Deposit::whereMonth('created_at', $currentMonth)->count();
        
        return Response::json([
            'status' => true,
            'current_month' => $currentMonth,
            'deposit_per_month' => $deposit_per_month,
            'withdraw_per_month' => $withdraw_per_month,
            'debug' => [
                'total_deposits' => $totalDeposits,
                'approved_deposits' => $approvedDeposits,
                'current_month_deposits' => $currentMonthDeposits,
                'user_type' => auth()->user()->type ?? 'not_logged_in'
            ],
            'message' => 'Test successful'
        ]);
    }
}
