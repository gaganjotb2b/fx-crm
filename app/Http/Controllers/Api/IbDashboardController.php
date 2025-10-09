<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class IbDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $ib_user = $user;
            if (strtolower($user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }
            // get total commission
            $total_commission = $ib_user->IbIncome();
            $today_earning = (clone $total_commission)->whereDate('close_time', now()->format('Y-m-d'))->sum('amount');
            $yesterday_earning = (clone $total_commission)->whereDate('close_time', now()->subDay()->format('Y-m-d'))->sum('amount');
            $total_commission = $total_commission->sum('amount');
            // total Trader and IB
            $ib_array = [];
            $traders = [];
            $this->getAllSubIBClients($ib_user, $ib_array, $traders);
            $my_traders = $ib_user->traders()->select('users.id', 'name', 'email', 'type')->get()->toArray('traders');
            $traders = array_merge($traders, $my_traders);
            // total client deposit
            $clients = new Collection($traders);
            $client_ids = $clients->pluck('id')->toArray();
            $deposit = Deposit::where('approved_status', 'A')->whereIn('user_id', $client_ids)->sum('amount');
            // return $ib_array;
            return response()->json([
                'status' => true,
                'data' => [
                    'total_commission' => $total_commission,
                    'today_earning' => $today_earning,
                    'yesterday_earning' => $yesterday_earning,
                    'total_trader' => count($traders),
                    'total_ib' => count($ib_array),
                    'client_deposits' => $deposit,

                ]
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'status' => false,
                'message' => 'Got a server error, please contact for support',
                'error' => $th->getMessage(),
                'data' => []
            ]);
        }
    }
    // get total trader
    public function getAllSubIBClients($ib_user, &$ib_array, &$traders)
    {
        $result = $ib_user->myIb()
            ->with(['masterIb' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }, 'traders' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }, 'traders.parentIb' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }])
            ->withCount('traders')
            ->where('type', CombinedService::type());
        if (CombinedService::is_combined()) {
            $result = $result->where('combine_access', 1);
        }
        $result = $result->select('users.id', 'name', 'email', 'type')
            ->get()
            ->toArray();

        foreach ($result as $value) {
            $ib_array[] = $value;
            if (!empty($value['traders'])) {
                $traders = array_merge($traders, $value['traders']);
            }
            $this->getAllSubIBClients(User::find($value['id']), $ib_array, $traders);
        }
    }
}
