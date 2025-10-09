<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\IB;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\IbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TraderSubIBAddedController extends Controller
{
    public function AddedTraderSubIB(Request $request)
    {
        $validation_rules = [
            'ib_id'                     => 'required',
            'reference_id'              => 'required',
        ];
        $msg = [
            'ib_id.required' => 'Somthing wend wrong! please reload this page and try again',
            'reference_id' => 'Atleast one trader or Sub IB required'
        ];
        $validator = Validator::make($request->all(), $validation_rules, $msg);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => ['reference_id[]' => $validator->errors()->first('reference_id')],
                'message' => 'Please fix the following errors!'
            ]);
        }
        // check already have a parent
        for ($i = 0; $i < count($request->reference_id); $i++) {
            $err_mail = AllFunctionService::user_email($request->reference_id[$i]);
            // check aready have a parent
            if (IbService::has_parent($request->reference_id[$i])) {
                return Response::json([
                    'status' => false,
                    'errors' => ['reference_id[]' => 'User <span class="badge badge-light-warning">' . $err_mail . '</span> already have a parent IB'],
                    'message' => 'Please fix the following errors!'
                ]);
            }
            // check self
            if ($request->ib_id == $request->reference_id[$i]) {
                return Response::json([
                    'status' => false,
                    'errors' => ['reference_id[]' => 'User <span class="badge badge-light-warning">' . $err_mail . '</span> Cannot refer to its self'],
                    'message' => 'Please fix the following errors!'
                ]);
            }
            // check instant parent
            if ($request->reference_id[$i] == IbService::instant_parent($request->ib_id)) {
                return Response::json([
                    'status' => false,
                    'errors' => ['reference_id[]' => 'User <span class="badge badge-light-warning">' . $err_mail . '</span> parent IB of This IB'],
                    'message' => 'Please fix the following errors!'
                ]);
            }
        }

        $ib = User::find($request->ib_id);
        // replace aray key
        $reference_id = $this->replace_key($request->reference_id, 'reference_id', $request->ib_id);

        $create = $ib->sub_ib_create()->createMany($reference_id);
        if ($create) {
            return Response::json([
                'status' => true,
                'message' => 'Sub IB/Trader successfully added'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Somthing went wrong please try again later'
        ]);
    }

    // change array key
    private function replace_key($arr, $newkey, $ib_id)
    {
        $next_array = [];
        foreach ($arr as $key => $val) {
            array_push($next_array, [$newkey => $val, 'ib_id' => $ib_id]);
        }
        return $next_array;
    }
    // remove ib and trader 
    public function RemoveIBEmail(Request $request)
    {
        $validation_rules = [
            'remove_ib_id'              => 'required',
            'reference_id'              => 'required',
        ];
        $msg = [
            'remove_ib_id.required' => 'Somthing wend wrong! please reload this page and try again',
            'reference_id' => 'Atleast one trader or Sub IB required'
        ];
        $validator = Validator::make($request->all(), $validation_rules, $msg);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => ['reference_id[]' => $validator->errors()->first('reference_id')],
                'message' => 'Please fix the following errors!'
            ]);
        }
        $delete = IB::whereIn('reference_id', $request->reference_id)->delete();
        if ($delete) {
            return Response::json([
                'status' => true,
                'message' => 'Trader(s)/Sub-IB(s) Successfully Removed'
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Failed to remove Trader(s)/Sub-IB(s)'
        ]);
    }

   
}
