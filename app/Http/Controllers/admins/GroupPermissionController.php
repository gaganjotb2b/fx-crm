<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\ManagerGroup;
use App\Models\Manager;
use App\Models\User;
use App\Services\AllFunctionService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class GroupPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:manager groups"]);
        $this->middleware(["role:manager settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('manager_settings', 'admin'));
        $this->middleware(AllFunctionService::access('manager_right', 'admin'));
    }
    //view  manager group
    // -----------------------------------
    public function index(Request $request)
    {
        $countries = Country::all();
        return view('admins.manager-settings.group-permission', [
            'countries' => $countries,
        ]);
    }

    // get all Gropus
    // --------------------------------------------------------------
    public function get_groups(Request $request)
    {
        try {
            $columns = ['group_name', 'group_name', 'active_status', 'active_status'];
            $orderby = $columns[$request->order[0]['column']];
            $search  = $request->input('search');
            // select type= 0 for trader
            $result = new ManagerGroup;
            // Filter by finance

            $count = $result->count(); // <------count total rows
            if ($search != "") {
                $result = $result->where('group_name', 'LIKE', '%' . $search['value'] . '%');
            }

            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {

                $status = '';
                if ($value->active_status == 0) {
                    $status = '<a href="#" class="text-warning">' . __('page.disabled') . '</a>';
                } elseif ($value->active_status == 1) {
                    $status = '<a href="#" class="text-success">' . __('page.active') . '</a>';
                }
                $type = '';
                if ($value->group_type == 0) {
                    $type = '<a href="#" >' . __('admin-management.Desk Manager') . '</a>';
                } elseif ($value->group_type == 1) {
                    $type = '<a href="#" >' . __('admin-management.Account Manager') . '</a>';
                }
                $data[$i]["group_name"]         = '<a data-id="' . $value->id . '" href="#" class="dt-description justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span><span>' . $value->group_name . '</span></a>';
                $data[$i]["type"]         = $type;
                $data[$i]["status"]       = $status;
                $i++;
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    // group description with right
    // -------------------------------------------------------------------------------------------
    public function group_des_right(Request $request, $id)
    {
        $groupUserCount  = Manager::where('group_id', $id)->count();
        if ($groupUserCount != 0) {
            $groupBody  = '
                    <div class="col-xl-12 col-lg-12">
                        <form class="manager-right-form" action="' . route('admin.set-group-roles-permissions') . '"  method="post" id="form-asign-role-perimission-' . $id . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="id" value="' . $id . '">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-lg-6">
                                    <h4>Available Rights</h4>
                                </div>
                                <div class="col-lg-6">
                                    <button type="button" class="btn btn-primary float-end save-permission" data-message="true">Save Permission</button>
                                
                                </div>
                            </div>
                            <div class="table-responsive p-2" >
                                <table class=" table role-permission-datatable" >
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Read</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                            <th>Create</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        </form>
                    </div>';
        } else {
            $groupBody  = '
                    <div class="col-xl-12 col-lg-12">
                        <div class="border-start-3 border-start-danger p-1 mb-1 bg-light-info">
                            <p>Manager not found of this group!</p>
                            <p>To set role and permission of this group please add minimum one manager </p>
                        </div>
                    </div>';
        }


        $description = '<tr class="description" style="display:none">
            <td colspan="6">
                <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <!-- Filled Tabs starts -->
                            ' . $groupBody . '
                        </div>
                    </div>
                </div>
            </td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
            <td class="d-none">&nbsp;</td>
        </tr>';
        $data = [
            'status' => true,
            'description' => $description
        ];
        return Response::json($data);
    }
    //Get group role permission 
    //----------------------------------------------------------------------------------------
    public function get_group_roles_permission(Request $request, $id)
    {
        $groups_users  = Manager::select('user_id')->where('group_id', $id)->first();
        // return $groups_users;
        if (isset($groups_users->user_id)) {
            $admin = User::find($groups_users->user_id);
        }


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
        $data[0]["read"] = '';
        $data[0]["edit"] = '';
        $data[0]["delete"] = '';
        $data[0]["create"] = '<div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll"/>
                            <input class="form-check-input" type="hidden" name="id" value="' . $id . '"/>
                            <label class="form-check-label" for="selectAll"> Select All </label>
                        </div>';
        $i = 1;

        if ($search != "") {
            $result = Role::where('name', 'LIKE', '%' . $search['value'] . '%')->take($length)->skip($start)->get();
        } else {
            $result = Role::take($length)->skip($start)->get();
        }


        foreach ($result as $role) {
            $role_name = str_replace(' ', '_', $role->name);
            $role_permission = '';
            $all_perimissions = $role->permissions;
            $pc = 1;
            $has_rol = '';
            if (isset($admin)) {
                if ($admin->hasRole($role->name)) {
                    $has_rol = 'checked';
                }
            }

            $data[$i]["name"]         = '<div class="form-check form-check-success">
                                            <input type="checkbox" name="roles[]" class="form-check-input role-checkbox" id="colorCheck3-' . $role->id . '" value="' . $role->id . '" ' . $has_rol . ' />
                                            <label class="form-check-label" for="colorCheck3-' . $role->id . '">' . ucwords($role->name) . '</label>
                                        </div>';
            foreach ($all_perimissions as $key => $value) {
                $has_permission = '';
                if (isset($admin)) {
                    if ($admin->hasDirectPermission($value->name)) {
                        $has_permission = 'checked';
                    }
                }

                if ($pc == 1) {
                    $data[$i]["read"] = '<div class="form-check me-3 me-lg-5">
                                            <input class="form-check-input permission-check" name="permission[]" type="checkbox" id="' . $value->id . '" value="' . $value->name . '" ' . $has_permission . '/>
                                            <label class="form-check-label" for="' . $value->id . '">' . ucwords(strtok($value->name, " ")) . ' </label>
                                        </div>';
                } else if ($pc == 2) {
                    $data[$i]["edit"] = '<div class="form-check me-3 me-lg-5">
                                            <input class="form-check-input permission-check" name="permission[]" type="checkbox" id="' . $value->id . '" value="' . $value->name . '" ' . $has_permission . '/>
                                            <label class="form-check-label" for="' . $value->id . '">' . ucwords(strtok($value->name, " ")) . ' </label>
                                        </div>';
                } else if ($pc == 3) {
                    $data[$i]["delete"] = '<div class="form-check me-3 me-lg-5">
                                                <input class="form-check-input permission-check" name="permission[]" type="checkbox" id="' . $value->id . '" value="' . $value->name . '" ' . $has_permission . '/>
                                                <label class="form-check-label" for="' . $value->id . '">' . ucwords(strtok($value->name, " ")) . ' </label>
                                            </div>';
                } else if ($pc == 4) {
                    $data[$i]["create"] = '<div class="form-check me-3 me-lg-5">
                                                <input class="form-check-input permission-check" name="permission[]" type="checkbox" id="' . $value->id . '" value="' . $value->name . '" ' . $has_permission . '/>
                                                <label class="form-check-label" for="' . $value->id . '">' . ucwords(strtok($value->name, " ")) . ' </label>
                                            </div>';
                }

                $pc++;
            }
            $i++;
        }



        $output = array(
            'draw' => $_REQUEST['draw'], 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered,
        );
        $output['data'] = $data;
        return Response::json($output);
    }

    // set permission to each users
    /******************************************************************** */
    public function set_group_permission_to_role(Request $request)
    {
        $groupId = $request->id;
        $groups_users  = Manager::select('user_id')->where('group_id', $groupId)->get();
        if (count($groups_users) ==  0) {
            return Response::json([
                'status' => false,
                'message' => "Can't find  user's  of this Group"
            ]);
        }
        // return $groups_users;
        foreach ($groups_users as $single_user => $single) {
            $id = $single->user_id;

            if ($request->checkRoles != null) {
                $checkRoles = $request->checkRoles;
            } else {
                $checkRoles = [];
            }
            $unCheckRoles = $request->unCheckRoles;
            $user = User::find($id);
            $curent_rolename = $user->getRoleNames();
            if ($request->unCheckRoles != null) {
                if (count($curent_rolename) != 0) {
                    foreach ($curent_rolename as $role) {
                        $rolenid = Role::select('id')->where('name', $role)->first();
                        $rolenid = strval($rolenid->id);
                        if (!in_array($rolenid, $unCheckRoles)) {
                            array_push($checkRoles, $rolenid);
                        }
                    }
                }
            } else {
                if (count($curent_rolename) != 0) {
                    foreach ($curent_rolename as $role) {
                        $rolenid = Role::select('id')->where('name', $role)->first();
                        $rolenid = strval($rolenid->id);
                        array_push($checkRoles, $rolenid);
                    }
                }
            }
            if ($user->syncRoles($checkRoles)) {
                $unCheckPermission = $request->unCheckPermission;
                $checkPermission = $request->checkPermission;
                if ($request->checkPermission != null) {
                    $checkPermission = $request->checkPermission;
                } else {
                    $checkPermission = [];
                }
                if ($unCheckPermission != null) {
                    foreach ($unCheckPermission as $uncheck) {
                        if ($user->hasDirectPermission($uncheck)) {
                            $user->revokePermissionTo($uncheck);
                        }
                    }
                }
                $user->givePermissionTo($checkPermission);
                $ip_address = request()->ip();
                $description = "The IP address " . $ip_address . "has been set permission";
                activity('assign ' . $user->type . ' permission')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($user)
                    ->event($user->type . ' permission')
                    ->performedOn($user)
                    ->log($description);
            }
        }
        return Response::json([
            'status' => true,
            'message' => 'Permission and Roles setting successfully done.'
        ]);
    }
}
