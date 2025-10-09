<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MyIbController extends Controller
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
            if ($request->input('level') === 'direct') {
                $ib_array = $this->onlyMyIBs($ib_user, $ib_array, $request);
            } elseif ($request->input('level') === 'sub-ib') {
                $this->onlySubIBs($ib_user, $ib_array, $request);
            } else {
                $this->getAllSubIBs($ib_user, $ib_array, $request);
            }
            return Response::json([
                'status' => true,
                'data' => $ib_array,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAllSubIBs($ib_user, &$ib_array, $request)
    {
        // Eager load the 'hasIb' relationship and select specific fields
        $sub_ib = $ib_user->myIb()
            ->with(['masterIb' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }, 'traders' => function ($query) {
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
            $ib_array[] = $sub_ib_user;

            // Recursively fetch sub-IBs
            $this->getAllSubIBs(User::find($sub_ib_user['id']), $ib_array, $request);
        }
    }
    public function onlySubIBs($ib_user, &$ib_array, $request)
    {
        // Eager load the 'hasIb' relationship and select specific fields
        $sub_ib = $ib_user->myIb()
            ->with(['masterIb' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }, 'traders' => function ($query) {
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
            }
            // Recursively fetch sub-IBs
            $this->getAllSubIBs(User::find($sub_ib_user['id']), $ib_array, $request);
        }
    }
    // get only myibs
    public function onlyMyIBs($ib_user, &$ib_array, $request)
    {
        // Eager load the 'hasIb' relationship and select specific fields
        $sub_ib = $ib_user->myIb()
            ->with(['masterIb' => function ($query) {
                $query->select('users.id', 'name', 'email', 'type');
            }, 'traders' => function ($query) {
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
        return $sub_ib;
    }
}
