<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IbSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IbPermissionController extends Controller
{
    public function get_permission(Request $request)
    {
        try {

            $result = IbSetting::whereNull('parent_id');
            $result = $result->where('system_disable', 0);
            $count = $result->count(); // <------count total rows
            $result = $result->orderby('id', 'ASC')->get();
            $data = array();
            foreach ($result as $value) {
                $childs = IbSetting::where('parent_id', $value->id);
                if (auth()->user()->type !== 'system') {
                    $childs = $childs->where('system_disable', 0);
                }
                $childs = $childs->get();
                $child_rows = [];
                foreach ($childs as $ch) {
                    // check child active status
                    $child_rows[] = [
                        'status' => $ch->status,
                        'permission_name' => $ch->settings,
                    ];
                }
                $data[] = [
                    'status' => $value->status,
                    'root_permission' => $value->settings,
                    'child_permission' => $child_rows,
                ];
            }
            return Response::json([
                'recordsTotal' => $count,
                'permission' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ]);
        }
    }
}
