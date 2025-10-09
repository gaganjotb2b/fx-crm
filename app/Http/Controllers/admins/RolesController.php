<?php

namespace App\Http\Controllers\admins;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRightRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\AdminGroup;
use App\Services\AllFunctionService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:admin right management"]);
        $this->middleware(["role:manage admin"]);
        // system module control
        $this->middleware(AllFunctionService::access('manage_admin', 'admin'));
        $this->middleware(AllFunctionService::access('admin_right_management', 'admin'));
    }
    public function view_roles(Request $request)
    {
        $admin_groups = AdminGroup::select()
            ->paginate(5);
        $group_list = '';

        foreach ($admin_groups as $group) :
            $avatar = asset(avatar());

            $admins = Admin::where('group_id', $group->id)
                ->get();
            if (count($admins) == 0) {
                $admin_list = '<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Vinnie Mostowy" class="avatar avatar-sm pull-up">
                                <img class="rounded-circle" src="' . $avatar . '" alt="Avatar" />
                            </li>';
            } else {
                $admin_list = '';
            }
            $total_admins = 0;
            foreach ($admins as $key => $value) {
                $admin_list .= '<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Vinnie Mostowy" class="avatar avatar-sm pull-up">
                            <img class="rounded-circle" src="' . $admin_list . '" alt="Avatar" />
                        </li>';
                $total_admins++;
            }
            // check has permission of loggedin users
            $edit_button = '';
            if (auth()->user()->hasDirectPermission('edit admin groups')) {
                $edit_button .= '<a href="javascript:void();" data-id="' . $group->id . '" data-name="' . $group->group_name . '" class="role-edit-modal stretched-link text-nowrap edit-group" data-bs-toggle="offcanvas" data-bs-target="#editGroup">
                    <small class="fw-bolder">' . __('page.edit_group') . '</small>
                </a>';
            }
            $group_list .= '<div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span>' . __('page.total') . ' ' . $total_admins . ' ' . __('page.admins') . '</span>
                        <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                            ' . $admin_list . '
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                        <div class="role-heading">
                            <h4 class="fw-bolder">' . $group->group_name . '</h4>
                            ' . $edit_button . '
                        </div>
                        <a href="javascript:void(0);" class="text-body"><i data-feather="copy" class="font-medium-5"></i></a>
                    </div>
                </div>
            </div>
        </div>';
        endforeach;
        return view('admins.rolesPermission.admin-roles', ['admin_groups' => $admin_groups, 'group_list' => $group_list]);
    }
    public function get_all_roles(Request $request)
    {
        try {
            $columns = ['users.name', 'group_name', 'countries.name', 'users.active_status', 'users.created_at'];
            $result = User::where('users.type', 2)
                ->whereNot('users.id', auth()->user()->id)
                ->select(
                    'users.*',
                    'admin_groups.group_name',
                    'countries.name as country',
                )
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                ->join('admins', 'users.id', '=', 'admins.user_id')
                ->join('admin_groups', 'admins.group_id', '=', 'admin_groups.id');

            // ------------------start filter------------------------

            // Search by datatable search field
            $search  = $request->input('search');
            if ($search != "") {
                $result = $result->where('users.name', 'LIKE', '%' . $search['value'] . '%')
                ->orWhere('admin_groups.group_name', 'LIKE', '%' . $search['value'] . '%')
                ->whereNot('users.id', auth()->user()->id);
            }
            // Filter by admin permission
            if ($request->admin_permission != "") {
                $admin_id = DB::table('model_has_permissions')
                    ->select('model_id')
                    ->where('permission_id', $request->admin_permission)
                    ->get()->pluck('model_id');

                $result = $result->whereIn('users.id', $admin_id);
            }

            // Filter by active status
            if ($request->status != "") {
                $result = $result->where('users.active_status', $request->status);
            }

            // Filter by Admin info.
            if ($request->admin_info != "") {
                $admin_info = $request->admin_info;
                $admin = User::where(function ($query) use ($admin_info) {
                    $query->where('users.name', 'LIKE', '%' . $admin_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $admin_info . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $admin_info . '%');
                })->get()->pluck('id');
                $result = $result->whereIn('users.id', $admin);
            }

            // Filter By country
            if ($request->country != "") {
                $result = $result->where('user_descriptions.country_id', $request->country);
            }

            $count = $result->count();
            $result = $result->orderBy($columns[$request->order[0]['column']], $request->order[0]['dir'])->take($request->length)->skip($request->start)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                $status = '';
                if ($value->active_status == 1) {
                    $status = '<a href="#" class="text-success">' . __('page.active') . '</a>';
                } else {
                    $status = '<a href="#" class="text-danger">' . __('page.block') . '</a>';
                }

                $auth = User::find(auth()->user()->id);
                if ($auth->hasDirectPermission('edit admin right management')) {
                    if (auth()->user()->id == $value->id) {
                        $edit_action = '<span class="text-danger">You dont Change your permission</span>';
                    } else {
                        // return $value->id;
                        $edit_action = '<a href="#"  class="more-actions"><i data-feather="more-vertical"></i></a> <a data-id="' . $value->id . '" href="javascript:;" class="role-edit-modal asign-permission" data-bs-toggle="modal" data-bs-target="#addRoleModal"><i data-feather="edit"></i></a>';
                    }
                } else {
                    $edit_action = '<span class="text-danger">You dont have right permisson</span>';
                }

                $data[$i]["name"]         = $value->name;
                $data[$i]["group"]         = ucwords((isset($value->group_name)) ? $value->group_name : '');
                $data[$i]["country"]      = ucwords((isset($value->country)) ? $value->country : '');
                $data[$i]["status"]       = $status;
                $data[$i]["actions"]      = $edit_action;
                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
    // add new role
    /*************************************************** */
    public function store_role(Request $request)
    {
        $validation_rules = [
            'name' => 'required|unique:Spatie\Permission\Models\Role,name',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            if (Role::where('name', strtolower($request->name))->exists()) {
                return Response::json([
                    'status' => false,
                    'errors' => ['message' => 'Right already exist! Please try another.']
                ]);
            } else {
                $insert_id = Role::create(['name' => strtolower($request->name)])->id;
            }

            if ($insert_id != "") {
                // make permissions
                for ($i = 0; $i < 4; $i++) {
                    if ($i == 0) { // <---create read permission
                        $permission_name = 'read ' . strtolower($request->name);
                        $permission_id = Permission::create(['name' => $permission_name])->id;
                        if ($permission_id != "") {
                            $role = Role::findById($insert_id);
                            $role->givePermissionTo($permission_name);
                        }
                    } elseif ($i == 1) { // <---create edit permission
                        $permission_name = 'edit ' . strtolower($request->name);
                        $permission_id = Permission::create(['name' => $permission_name])->id;
                        if ($permission_id != "") {
                            $role = Role::findById($insert_id);
                            $role->givePermissionTo($permission_name);
                        }
                    } elseif ($i == 2) { // <---create delete permission
                        $permission_name = 'delete ' . strtolower($request->name);
                        $permission_id = Permission::create(['name' => $permission_name])->id;
                        if ($permission_id != "") {
                            $role = Role::findById($insert_id);
                            $role->givePermissionTo($permission_name);
                        }
                    } else { // <---create permission
                        $permission_name = 'create ' . strtolower($request->name);
                        $permission_id = Permission::create(['name' => $permission_name])->id;
                        if ($permission_id != "") {
                            $role = Role::findById($insert_id);
                            $role->givePermissionTo($permission_name);
                        }
                    }
                }
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been add new right";
                $properties = Role::find($insert_id);
                // insert activity
                activity('add new right')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($properties)
                    ->event('add new right')
                    ->log($description);
                return Response::json([
                    'status' => true,
                    'message' => 'A New role Successfully Added'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Sorry, Somthing wen wrong! Please try again later.'
                ]);
            }
        }
    }
}
