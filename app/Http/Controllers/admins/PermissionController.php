<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Admin;

class PermissionController extends Controller
{
    public function get_roles_permission(Request $request, $id)
    {

        $admin = User::find($id);
        $admin_group = Admin::where('user_id', $id)
            ->join('admin_groups', 'admins.group_id', '=', 'admin_groups.id')
            ->first();

        $all_perimissions = Permission::all();
        $roles = Role::all();
        $recordsTotal = count($roles);
        $recordsFiltered = count($roles);
        $start = $request->input('start');
        $length = $request->input('length');
        $search  = $request->input('search');
  
        $data = array();
        $data[0]["name"] = '<div class="text-nowrap fw-bolder admin-id" data-id="' . $id . '">
                                Administrator Access
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system">
                                    <i data-feather="info"></i>
                                </span>
                            </div>';
        $data[0]["read"] ='';
        $data[0]["edit"] ='';
        $data[0]["delete"] ='';
        $data[0]["create"] ='<div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll"/>
                            <input class="form-check-input" type="hidden" name="id" value="' . $id . '"/>
                            <label class="form-check-label" for="selectAll"> Select All </label>
                        </div>';
        $i = 1;
    
        if ($search != "") {
            $result = Role::where('name', 'LIKE', '%' . $search['value'] . '%')->take($length)->skip($start)->get();
        }
        else{
            $result = Role::take($length)->skip($start)->get();
        }


        foreach ($result as $role) {
            $role_name = str_replace(' ', '_', $role->name);
            $role_permission = '';
            $all_perimissions = $role->permissions;
            $pc = 1;
            $has_rol = '';
            if ($admin->hasRole($role->name)) {
                $has_rol = 'checked';
            }
            $data[$i]["name"]         = '<div class="form-check form-check-success">
                                            <input type="checkbox" name="roles[]" class="form-check-input role-checkbox" id="colorCheck3-' . $role->id . '" value="' . $role->id . '" ' . $has_rol . ' />
                                            <label class="form-check-label" for="colorCheck3-' . $role->id . '">' . ucwords($role->name) . '</label>
                                        </div>';
            foreach ($all_perimissions as $key => $value) {
                $has_permission = '';
                if ($admin->hasDirectPermission($value->name)) {
                    $has_permission = 'checked';
                }

                if($pc == 1){
                    $data[$i]["read"] = '<div class="form-check me-3 me-lg-5">
                                            <input class="form-check-input permission-check" name="permission[]" type="checkbox" id="' . $value->id . '" value="' . $value->name . '" ' . $has_permission . '/>
                                            <label class="form-check-label" for="' . $value->id . '">' . ucwords(strtok($value->name, " ")) . ' </label>
                                        </div>';
                }
                else if($pc == 2){
                    $data[$i]["edit"] = '<div class="form-check me-3 me-lg-5">
                                            <input class="form-check-input permission-check" name="permission[]" type="checkbox" id="' . $value->id . '" value="' . $value->name . '" ' . $has_permission . '/>
                                            <label class="form-check-label" for="' . $value->id . '">' . ucwords(strtok($value->name, " ")) . ' </label>
                                        </div>';
                }
                else if($pc == 3){
                    $data[$i]["delete"] = '<div class="form-check me-3 me-lg-5">
                                                <input class="form-check-input permission-check" name="permission[]" type="checkbox" id="' . $value->id . '" value="' . $value->name . '" ' . $has_permission . '/>
                                                <label class="form-check-label" for="' . $value->id . '">' . ucwords(strtok($value->name, " ")) . ' </label>
                                            </div>';
                }
                else if($pc == 4){
                    $data[$i]["create"] = '<div class="form-check me-3 me-lg-5">
                                                <input class="form-check-input permission-check" name="permission[]" type="checkbox" id="' . $value->id . '" value="' . $value->name . '" ' . $has_permission . '/>
                                                <label class="form-check-label" for="' . $value->id . '">' . ucwords(strtok($value->name, " ")) . ' </label>
                                            </div>';
                }
                
                $pc++;

            }
            $i++;
        }

     

        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered, 'name' => $admin->name,
        'role_name' => (isset($admin_group->name)) ? $admin_group->name : '',);
        $output['data'] = $data;
        return Response::json($output);
    }

    // store new permission to permission table

    public function store_permission(Request $request)
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
        } else {
            $insert = Permission::create(['name' => $request->permission]);
            if ($insert) {
                if ($request->ajax()) {
                    return Response::json(['status' => true, 'message' => 'A New Permission Successfully Added']);
                }
            } else {
                if ($request->ajax()) {
                    return Response::json(['status' => false, 'message' => 'Sorry, Somthing wen wrong! Please try again later.']);
                }
            }
        }
    }

    // set permission to each users
    /******************************************************************** */
    public function set_permission_to_role(Request $request)
    {
        $id = $request->id;
        if($request->checkRoles != null){
            $checkRoles = $request->checkRoles;
        }
        else{
            $checkRoles = [];
        }
        $unCheckRoles = $request->unCheckRoles;
        $user = User::find($id);
        $curent_rolename = $user->getRoleNames();
        if($request->unCheckRoles != null){
            if(count($curent_rolename) != 0){
                foreach($curent_rolename as $role){
                    $rolenid = Role::select('id')->where('name',$role)->first();
                    $rolenid = strval($rolenid->id);
                    if (!in_array($rolenid, $unCheckRoles)) {
                        array_push($checkRoles,$rolenid);
                    }
                }
            }
        }
        else{
            if(count($curent_rolename) != 0){
                foreach($curent_rolename as $role){
                    $rolenid = Role::select('id')->where('name',$role)->first();
                    $rolenid = strval($rolenid->id);
                    array_push($checkRoles,$rolenid);
                }
            }
        }
        if ($user->syncRoles($checkRoles)) {
            $unCheckPermission = $request->unCheckPermission;
            $checkPermission = $request->checkPermission;
            if($request->checkPermission != null){
                $checkPermission = $request->checkPermission;
            }
            else{
                $checkPermission =[];
            }
            if($unCheckPermission != null){
                foreach($unCheckPermission as $uncheck){
                    if ($user->hasDirectPermission($uncheck)) {
                        $user->revokePermissionTo($uncheck);
                    }
                }
            }
            $user->givePermissionTo($checkPermission);
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been set permission";
            activity('assign '.$user->type.' permission')
                ->causedBy(auth()->user()->id)
                ->withProperties($user)
                ->event($user->type.' permission')
                ->performedOn($user)
                ->log($description);
            return Response::json([
                'status' => true,
                'message' => 'Permission and Roles setting successfully done.'
            ]);
        }
       
        
    }
}
