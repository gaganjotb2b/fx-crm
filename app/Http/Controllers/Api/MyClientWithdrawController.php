<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdraw;
use App\Services\CombinedService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class MyClientWithdrawController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $user = User::find(auth()->guard('api')->user()->id);
            $ib_user = $user;
            if (strtolower($ib_user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }

            $ib_array = [];
            $traders = [];
            if ($request->input('level') === 'direct') {
                $ib_array = $this->onlyMyIBClients($ib_user, $ib_array, $request, $traders);
            } elseif ($request->input('level') === 'sub-ib') {
                $this->onlySubIBClients($ib_user, $ib_array, $request, $traders);
            } else {
                $this->getAllSubIBClients($ib_user, $ib_array, $request, $traders);
                $my_traders = $ib_user->traders()->select('users.id', 'name', 'email', 'type')->get()->toArray('traders');
                $traders = array_merge($traders, $my_traders);
            }
            $trader_collection = new Collection($traders);
            $trader_ids = $trader_collection->pluck('id');
            // now get the deposit data
            $result = Withdraw::select(
                'user_id',
                'transaction_id',
                'transaction_type as mehtod',
                'amount',
                'charge',
                'bank_account_id',
                'approved_status as status',
                'approved_date'
            )->where('wallet_type', 'trader')
                ->whereIn('user_id', $trader_ids)
                ->with([
                    'trader' => function ($query) use ($request) {
                        $query->select('id', 'name', 'email', 'type');
                    }, 'trader.parentIb' => function ($query) {
                        $query->select('name as ib_name', 'email as ib_email', 'type');
                    },
                ]);
            // filter by min amount
            if ($request->input('min_amount')) {
                $result = $result->where('amount', '>=', $request->input('min_amount'));
            }
            // filter by max amount
            if ($request->input('max_amount')) {
                $result = $result->where('amount', '<=', $request->input('max_amount'));
            }

            // filter by date from
            if ($request->input('date_from')) {
                $date_from = Carbon::parse($request->input('date_from'));
                $result = $result->whereDate('created_at', '>=', $date_from);
            }
            // filter by close time
            if ($request->input('date_to')) {
                $to  = Carbon::parse($request->input('date_to'));
                $result = $result->whereDate('created_at', '<=', $to);
            }
            // filter by trader info
            if ($request->input('trader_info')) {
                $result = $result->whereHas('trader', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->input('trader_info') . '%')
                        ->orWhere('email', 'LIKE', '%' . $request->input('trader_info') . '%')
                        ->orWhere('phone', 'LIKE', '%' . $request->input('trader_info') . '%');
                });
            }
            $total_amount = clone $result;
            $total_amount = $total_amount->sum('amount');
            $result = $result->OrderBy('created_at', 'desc')->paginate($request->input('per_page', 10));

            if ($result) {
                return Response::json([
                    'status' => true,
                    'total_amount' => $total_amount,
                    'data' => $result,
                ]);
            }
            return Response::json([
                'status' => false,
                'total_amount' => 0,
                'data' => [],
                'message' => 'Data not found'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'total_amount' => 0,
                'data' => [],
                'message' => 'got a server error, please contact for support'
            ]);
        }
    }
    public function getAllSubIBClients($ib_user, &$ib_array, $request, &$traders)
    {
        // Eager load the 'hasIb' relationship and select specific fields
        $sub_ib = $ib_user->myIb()
            ->with(['masterIb' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }, 'traders' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }, 'traders.parentIb' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }])
            ->withCount('traders')
            ->where('type', CombinedService::type());

        // Filter by sub-IB name, email, phone
        if ($request->input('info')) {
            $sub_ib = $sub_ib->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->input('info') . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->input('info') . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request->input('info') . '%');
            });
        }
        if ($request->input('trader_info')) {
            $sub_ib = $sub_ib->whereHas('traders', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->input('trader_info') . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->input('trader_info') . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request->input('trader_info') . '%');
            });
        }

        // Get traders referred by the current IB and their sub-IBs' traders
        $sub_ib = $sub_ib->select('users.id', 'name', 'email', 'type')
            ->get()
            ->toArray();

        foreach ($sub_ib as $sub_ib_user) {
            $ib_array[] = $sub_ib_user;
            if (!empty($sub_ib_user['traders'])) {
                $traders = array_merge($traders, $sub_ib_user['traders']);
            }
            // Recursively fetch sub-IBs
            $this->getAllSubIBClients(User::find($sub_ib_user['id']), $ib_array, $request, $traders);
        }
    }
    // get clients of subibs
    public function onlySubIBClients($ib_user, &$ib_array, $request, &$traders)
    {
        // Eager load the 'hasIb' relationship and select specific fields
        $sub_ib = $ib_user->myIb()
            ->with(['masterIb' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }, 'traders' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }, 'traders.parentIb' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }])
            ->withCount('traders')
            ->where('type', CombinedService::type());
        // filter by sub ib name , email , phone
        if ($request->input('info')) {
            $sub_ib = $sub_ib->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->input('info') . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->input('info') . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request->input('info') . '%');
            });
        }
        $sub_ib = $sub_ib->select('users.id', 'name', 'email', 'type')
            ->get()
            ->toArray();

        foreach ($sub_ib as $sub_ib_user) {
            if ($sub_ib_user['pivot']['ib_id'] != '3') {
                $ib_array[] = $sub_ib_user;
                if (!empty($sub_ib_user['traders'])) {
                    $traders = array_merge($traders, $sub_ib_user['traders']);
                }
            }

            // Recursively fetch sub-IBs
            $this->onlySubIBClients(User::find($sub_ib_user['id']), $ib_array, $request, $traders);
        }
    }
    // get only myibs
    public function onlyMyIBClients($ib_user, &$ib_array, $request, &$traders)
    {
        $traders = $ib_user->traders()->with(['parentIb' => function ($query) {
            $query->select('users.id', 'name', 'email', 'type');
        }])
            ->select('users.id', 'name', 'email', 'type')
            ->where('type', 0);
        // filter by sub ib name , email , phone
        if ($request->input('info')) {
            $traders = $traders->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->input('info') . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->input('info') . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request->input('info') . '%');
            });
        }
        $traders = $traders->select('users.id', 'name', 'email', 'type')
            ->get()
            ->toArray();
        return $traders;
    }
}
