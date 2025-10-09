<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CombinedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ClientsController extends Controller
{
    public function search_trader(Request $request)
    {
        try {
            $result = User::where('type', 0)->where('active_status', 1)->with(['user_description' => function ($query) {
                $query->select('user_id', 'profile_avater');
            }])
                ->select('id', 'name', 'email', 'phone', 'type');
            if ($request->input('search')) {
                $result = $result->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->input('search') . '%')
                        ->orWhere('email', 'LIKE', '%' . $request->input('search') . '%')
                        ->orWhere('name', 'LIKE', '%' . $request->input('search') . '%');
                });
            }
            $result = $result->limit(100)->get();
            if ($result) {
                return Response::json([
                    'status' => true,
                    'data' => $result,

                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function search_ib(Request $request)
    {
        try {
            $result = User::where('active_status', 1)
                ->with(['user_description' => function ($query) {
                    $query->select('user_id', 'profile_avater');
                }])
                ->select('id', 'name', 'email', 'phone', 'type');
            if (CombinedService::is_combined()) {
                $result = $result->where(function ($query) {
                    $query->where('type', 0)
                        ->where('combine_access', 1);
                });
            } else {
                $result = $result->where('type', 4);
            }
            if ($request->input('search')) {
                $result = $result->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->input('search') . '%')
                        ->orWhere('email', 'LIKE', '%' . $request->input('search') . '%')
                        ->orWhere('name', 'LIKE', '%' . $request->input('search') . '%');
                });
            }
            $result = $result->limit(100)->get();
            if ($result) {
                return Response::json([
                    'status' => true,
                    'data' => $result,

                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
