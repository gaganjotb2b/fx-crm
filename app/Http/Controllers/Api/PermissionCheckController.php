<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IbSetting;
use App\Models\TraderSetting;
use App\Services\CombinedService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PermissionCheckController extends Controller
{
    //check permission
    public function check_permission(Request $request)
    {
        try {
            $validation_rules = [
                'client_type' => 'required|max:8'
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => "The request data is invalid.",
                    'error' => "Validation Error",
                    'errors' => $validator->errors(),
                ], 400);
            }
            switch ($request->client_type) {
                case 'ib':
                    $result = IbSetting::whereNull('parent_id');
                    $result = $result->orderby('id', 'ASC')->get();
                    $data = array();
                    foreach ($result as $value) {
                        $childs = IbSetting::where('parent_id', $value->id);
                        $childs = $childs->get();
                        $child_rows = [];
                        foreach ($childs as $ch) {
                            // check child active status
                            $child_rows[strtolower(str_replace(' ', '', $ch->settings))] = [
                                'status' => $ch->status,
                            ];
                        }
                        $data[strtolower(str_replace(' ', '', $value->settings))] = [
                            'status' => $value->status,
                            'child' => $child_rows
                        ];
                    }
                    return Response::json([
                        'status' => true,
                        'data' => $data
                    ]);
                    break;

                default:
                    $result = TraderSetting::whereNull('parent_id');
                    $result = $result->orderby('id', 'ASC')->get();
                    $data = array();
                    foreach ($result as $value) {
                        $childs = TraderSetting::where('parent_id', $value->id);
                        $childs = $childs->get();
                        $child_rows = [];
                        foreach ($childs as $ch) {
                            // check child active status
                            $child_rows[strtolower(str_replace(' ', '', $ch->settings))] = [
                                'status' => $ch->status,
                            ];
                        }
                        $data[strtolower(str_replace(' ', '', $value->settings))] = [
                            'status' => $value->status,
                            'child' => $child_rows
                        ];
                    }
                    return Response::json([
                        'status' => true,
                        'data' => $data
                    ]);
                    break;
            }
        } catch (\Throwable $th) {
            //throw $th;
            //throw $th;
            return Response::json([
                'status' => false,
                "error" => "Internal Server Error",
                "message" => "An unexpected error occurred while processing your request."
            ], 500);
        }
    }
}
