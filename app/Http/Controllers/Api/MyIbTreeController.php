<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MyIbTreeController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $user = User::find(auth()->guard('api')->user()->id);
            $ib_user = $user;
            if (strtolower($ib_user->type) === 'trader') {
                $ib_user = $user->IbAccount()->first();
            }

            $ib_tree = [];
            $this->getAllSubIBs($ib_user, $ib_tree, $request);
            return Response::json([
                'status' => true,
                'data' => $ib_tree,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getAllSubIBs($ib_user, &$ib_tree, $request)
    {
        // Eager load the 'hasIb' relationship and select specific fields
        $sub_ib = $ib_user->myIb()
            ->where(function ($query) {
                $query->where('type', 0)
                    ->orWhere('type', 4);
            });
        // Filter by sub ib name , email , phone
        // if ($request->input('info')) {
        //     $sub_ib = $sub_ib->where(function ($query) use ($request) {
        //         $query->where('name', 'LIKE', '%' . $request->input('info') . '%')
        //             ->orWhere('email', 'LIKE', '%' . $request->input('info') . '%')
        //             ->orWhere('phone', 'LIKE', '%' . $request->input('info') . '%');
        //     });
        // }

        $sub_ib_users = $sub_ib->select('users.id', 'name', 'email', 'type')->get()->toArray();
        foreach ($sub_ib_users as $sub_ib_user) {
            if ($sub_ib_user['type'] != 'trader') {
                $sub_ib_user['children'] = [];
            }
            // Recursively fetch sub-IBs
            $this->getAllSubIBs(User::find($sub_ib_user['id']), $sub_ib_user['children'], $request);

            $ib_tree[] = $sub_ib_user;
        }
    }
}
