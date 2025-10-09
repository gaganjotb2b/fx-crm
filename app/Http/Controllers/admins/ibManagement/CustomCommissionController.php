<?php

namespace App\Http\Controllers\admins\ibManagement;

use App\Http\Controllers\Controller;
use App\Models\CustomCommission;
use App\Models\IbCommissionStructure;
use App\Services\IbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CustomCommissionController extends Controller
{
    public function custom_commission(Request $request)
    {
        try {
            $custom_commission = $request->commission;
            for ($i = count($custom_commission); $i < IbService::system_ibCommission_level(); $i++) {
                $custom_commission[$i] = '--';
            }

            $update = CustomCommission::updateOrCreate(
                [
                    'id' => $request->id,
                    'commission_id' => $request->commission_id,
                ],
                [
                    'custom_commission' => json_encode($custom_commission),
                ]
            );
            if ($update) {
                return Response::json([
                    'status' => true,
                    'message' => 'Custom commission successfully updated!',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
