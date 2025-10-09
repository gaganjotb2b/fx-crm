<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\Country;
use App\Models\AdminGroup;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RightController extends Controller
{
    public function add_new_right(Request $request)
    {
        $validation_rules = [
            'permission' => 'required|min:4|max:64',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['status' => false, 'errors' => $validator->errors()]);
            }
        }
        else {
            $Permission = Permission::create(['name'=>$request->permission]);
            if ($Permission) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => 'A New Right Successfully Added']);
                }
                
            }
            else {
                return Response::json(['status' => false, 'message' => 'Somthing wen wrong! Please try again later']);
            }
        } 
    }
}
