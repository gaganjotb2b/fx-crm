<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\ManagerGroup;
use App\Models\Manager;
use App\Models\ManagerUser;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ManagerGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:manager groups"]);
        $this->middleware(["role:manager settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('manager_settings', 'admin'));
        $this->middleware(AllFunctionService::access('manager_groups', 'admin'));
    }
    //view  manager group
    // -----------------------------------
    public function index(Request $request)
    {
        $manager_group = ManagerGroup::paginate(5);
        $countries = Country::all();
        return view(
            'admins.manager-settings.manager-groups',
            [
                'countries' => $countries,
                'manager_groups' => $manager_group
            ]
        );
    }

    public function managerGroup(Request $request)
    {
        if ($request->ajax()) {
            $list = ManagerGroup::select();
            $total_record = $list->limit(10)->count('id');
            $list = $list->skip($request->current)->take($request->limit)->get();
            // $admin_groups = AdminGroup::paginate(5);
            // $group_list = '';
            $data = array();
            $avatar = asset(avatar());

            foreach ($list as $group) :
                $admins = Manager::where('group_id', $group->id)
                    ->get();
                // return $admin;
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
                            <img class="rounded-circle" src="' . $avatar . '" alt="Avatar" />
                        </li>';
                    $total_admins++;
                }
                // check has permission of loggedin users
                $edit_button = $delete_button = '';
                $auth_user = User::where('id', auth()->user()->id)->select('id')->first();
                if ($auth_user->hasDirectPermission('edit admin groups')) {
                    $edit_button .= '<a href="javascript:void();" data-id="' . $group->id . '" data-name="' . $group->group_name . '" class="role-edit-modal stretched-link text-nowrap edit-group" data-bs-toggle="offcanvas" data-bs-target="#editGroup">
                    <small class="fw-bolder">' . __('page.edit_group') . '</small>
                </a>';
                }
                $group_list = '
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
                                                ' . $delete_button . '
                                            </div>
                                            <a href="javascript:void(0);" class="text-body"><i data-feather="copy" class="font-medium-5"></i></a>
                                        </div>
                                    </div>
                            </div>';
                array_push($data, $group_list);
            endforeach;
            return Response::json([
                'list' => $data,
                'totalRecord' => $total_record
            ]);
        }
    }
    public function get_manager_group(Request $request)
    {
        try {
            $group = ManagerGroup::select();
            // filter datatable group manager
            if ($request->manager_group != "") {
                $group = $group->where('group_type', $request->manager_group);
            }

            // filter status active ot disabled
            if ($request->status) {
                $group = $group->where("active_status", $request->status);
            }

            // filter manager name / email /phone
            if ($request->manager_info != "") {
                $manager_info = $request->manager_info;
                $user_id = User::where("name", "LIKE", "%$manager_info%")->orWhere("email", "LIKE", "%$manager_info%")->orWhere("phone", "LIKE", "%$manager_info%")->where("type", 5)->pluck("id");
                $group_id = Manager::whereIn("user_id", $user_id)->pluck("group_id");
                $group = $group->whereIn("id", $group_id);
            }

            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $user_id = User::where("name", "LIKE", "%$trader_info%")->orWhere("email", "LIKE", "%$trader_info%")->orWhere("phone", "LIKE", "%$trader_info%")->where("type", 0)->pluck("id");
                $manager_id = ManagerUser::whereIn("user_id", $user_id)->pluck("manager_id");
                $group_id = Manager::whereIn("user_id", $manager_id)->pluck("group_id");
                $group = $group->whereIn("id", $group_id);
            }

            // filter ib name / email /phone
            if ($request->ib_info != "") {
                $ib_info = $request->ib_info;
                $ib_id = User::where("name", "LIKE", "%$ib_info%")->orWhere("email", "LIKE", "%$ib_info%")->orWhere("phone", "LIKE", "%$ib_info%")->where("type", 4)->pluck("id");
                $manager_id = ManagerUser::whereIn("user_id", $ib_id)->pluck("manager_id");
                $group_id = Manager::whereIn("user_id", $manager_id)->pluck("group_id");;
                $group = $group->whereIn("id", $group_id);
            }

            // filter country
            if ($request->country != "") {
                $country = Country::where("id", $request->country)->pluck("id");
                $user_id = UserDescription::whereIn("country_id", $country)->pluck("user_id");
                $group_id = Manager::whereIn("user_id", $user_id)->pluck("group_id");
                $group = $group->whereIn("id", $group_id);
            }




            $count = $group->count();
            $result = $group->orderBy('id', 'DESC')->skip($request->start)->take($request->length)->get();
            $data = array();
            $avatar = asset(avatar());

            foreach ($result as $value) :
                // edit and delete buttons
                $edit_button = $delete_button = '';
                $auth_user = User::where('id', auth()->user()->id)->select('id')->first();
                // check has edit permission
                if ($auth_user->hasDirectPermission('edit admin groups')) {
                    $edit_button .= '<a href="javascript:void();" data-id="' . $value->id . '" data-name="' . $value->group_name . '" class="role-edit-modal stretched-link text-nowrap edit-group" data-bs-toggle="offcanvas" data-bs-target="#editGroup">
                    <small class="fw-bolder">' . __('page.edit_group') . '</small>
                </a>';
                }
                // check has delete permission
                if ($auth_user->hasDirectPermission('delete manager groups')) {
                    $delete_button .= '<a href="javascript:void(0);" class="delete-manager-group text-warning ms-1"  data-id="' . $value->id . '">
                        <small class="fw-bolder">Delete Group</small>
                    </a>';
                }
                // manager list
                // -----------------------------------
                $manager = Manager::where('group_id', $value->id);;
                $total_manager = $manager->count();
                $manager = $manager->get();
                $manager_list = '';
                foreach ($manager as $list) {
                    $manager_list .= '<li data-popup="tooltip-custom" title="Vinnie Mostowy" class="avatar avatar-sm pull-up">
                            <img class="rounded-circle" src="' . asset(avatar()) . '" alt="Avatar" />
                        </li>';
                }
                // make datatable formate
                // -----------------------------------
                $data[] = [
                    'total_manager' => $total_manager,
                    'manager_list' => $manager_list,
                    'group_name' => $value->group_name,
                    'edit_button' => $edit_button,
                    'delete_button' => $delete_button,
                    'group_type' => $value->group_type,
                ];
            endforeach;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
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
    // add or store new manager group
    // --------------------------------------------------------------------------------------
    public function store(Request $request)
    {
        $validation_rules = [
            'group_name' => 'required|min:4|max:191',
            'group_type' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $create = ManagerGroup::create([
                'group_name' => $request->group_name,
                'created_by' => auth()->user()->id,
                'group_type' => $request->group_type,
            ])->id;
            if ($create) {
                // insert activity log-------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been add new manager group";
                $properties = ManagerGroup::find($create);
                activity('Add new manager group')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($properties)
                    ->event('add new manager group')
                    ->log($description);
                return Response::json([
                    'status' => true,
                    'message' => 'New Manager Group Successfully Added'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Something went wrong! please try again later.'
                ]);
            }
        }
    }
    // function edit manager group----------------------
    public function edit_manager_group(Request $request)
    {
        $validation_rules = [
            'group_name' => 'required|min:4|max:191',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        } else {
            $group = ManagerGroup::find($request->group_id);
            $group->group_name = $request->group_name;
            $create = $group->save();
            if ($create) {
                // insert activity log
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been edited manager group";

                activity('edit manager group')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($group)
                    ->event('updated')
                    ->log($description);
                return Response::json([
                    'status' => true,
                    'message' => 'manager Group Successfully Updated'
                ]);
            } else {
                if ($request->ajax()) {
                    return Response::json([
                        'status' => false,
                        'message' => 'Something went wrong! please try again later.'
                    ]);
                }
            }
        }
    }
    // delete manager group-------------------------------------------
    public function delete_manager_group(Request $request)
    {
        $manager_group = ManagerGroup::find($request->id);
        $delete = ManagerGroup::where('id', $request->id)->update([
            'active_status' => 2,
        ]);
        if ($delete) {
            // store activity log---------------------
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been delete manager group";

            activity('delete manager group')
                ->causedBy(auth()->user()->id)
                ->withProperties($manager_group)
                ->event('deleted')
                ->log($description);
            // <----------------------
            return Response::json([
                'status' => true,
                'message' => 'Manager group deleted successfully'
            ]);
        } else {
            return Response::json([
                'status' => true,
                'message' => 'Somthing went wrong please try again later!'
            ]);
        }
    }
}
