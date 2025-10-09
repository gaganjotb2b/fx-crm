<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MyClientsController extends Controller
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
                $my_traders = $ib_user->traders()->with(['parentIb' => function ($q) {
                    $q->select('ib_id', 'reference_id', 'name', 'email');
                }])->select('users.id', 'name', 'email', 'type')->get()->toArray('traders');
                $traders = array_merge($traders, $my_traders);
            }
            return Response::json([
                'status' => true,
                'data' => $traders,
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => true,
                'data' => [],
            ]);
        }
    }
    // get all clients
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
