<?php

namespace App\Http\Controllers\systems;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SoftwareSetting;
use Illuminate\Support\Facades\Response;

class AdminTradingAccountConfigController extends Controller
{
    public function index()
    {
        $software_settings = SoftwareSetting::first();
        return view('systems.configurations.admin-account-config', [
            'software_settings' => $software_settings,
        ]);
    }
    // software setting
    public function configAdd(Request $request)
    {
        // end company social media
        $account_move = ($request->account_move == "on") ? 1 : 0;

        $data = [
            'account_move' => $account_move
        ];
        if (SoftwareSetting::where('id', $request->update_id)->update($data)) {
            return Response::json(['status' => true, 'message' => 'Successfully Updated.']);
        } else {
            return Response::json(['status' => false, 'message' => 'Failed To Update!']);
        }
    }
}
