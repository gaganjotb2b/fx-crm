<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class GetGroupController extends Controller
{
    public function group_get(Request $request)
    {
        try {
            $groups = ClientGroup::where('visibility', 'visible');
            if ($request->input('category')) {
                $groups = $groups->where('account_category', $request->input('category'));
            }
            if ($request->input('server')) {
                $groups = $groups->where('server', $request->input('platform'));
            }

            $groups = $groups->get();
            if ($groups) {
                $all_groups = [];
                foreach ($groups as $value) {
                    $all_groups[] = [
                        'id' => $value->id,
                        'group_name' => $value->group_id,
                        'platform' => $value->server,
                        'category' => $value->account_category,
                        'leverage' => json_decode($value->leverage),
                        'max_leverage' => $value->max_leverage,
                        'min_deposit' => $value->min_deposit,
                        'deposit_type' => $value->deposit_type,
                    ];
                }
                return Response::json([
                    'status' => true,
                    'groups' => $all_groups,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Groups not found'
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error'
            ]);
        }
    }
    // get levrage of selected group
    public function group_get_leverage(Request $request, ClientGroup $group)
    {
        try {
            $client_group = $group;

            return Response::json([
                'status' => true,
                'leverage' => json_decode($client_group->leverage)
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
