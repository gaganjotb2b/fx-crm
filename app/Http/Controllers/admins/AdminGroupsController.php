<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\AdminGroup;
use App\Models\Admin;
use App\Models\User;
use App\Models\IB;
use App\Models\admin\AdminUser;
use App\Models\Country;
use App\Models\Log;
use App\Models\Traders\SocialLink;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\EmailService;
use Illuminate\Support\Facades\Hash;

class AdminGroupsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:admin groups"]);
        $this->middleware(["role:manage admin"]);
        // system module control
        $this->middleware(AllFunctionService::access('manage_admin', 'admin'));
        $this->middleware(AllFunctionService::access('admin_groups', 'admin'));
    }
    public function index(Request $request)
    {
        $admin_groups = AdminGroup::paginate(5);
        if ($request->ajax()) {
            $list = AdminGroup::select();
            $total_record = $list->limit(10)->count('id');
            $list = $list->skip($request->current)->take($request->limit)->get();
            // $admin_groups = AdminGroup::paginate(5);
            // $group_list = '';
            $data = array();
            $avatar = asset(avatar());

            foreach ($list as $group) :
                $admins = Admin::where('group_id', $group->id)
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
                if (auth()->user()->hasDirectPermission('edit admin groups')) {
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
        return view('admins.rolesPermission.admin-groups', [
            'admin_groups' => $admin_groups
        ]);
    }

    // add new admin group
    // ---------------------------------------------------------

    public function store(Request $request)
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
            $create = AdminGroup::create([
                'group_name' => $request->group_name,
                'created_by' => auth()->user()->id,
            ]);
            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'New Admin Group Successfully Added'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Something went wrong! please try again later.'
                ]);
            }
        }
    }

    // update admin groups
    // --------------------------------------------------

    public function update(Request $request)
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
            $group = AdminGroup::find($request->group_id);
            $group->group_name = $request->group_name;
            $create = $group->save();
            if ($create) {
                // insert activity log
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been edited admin group";

                activity('edit admin group')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($group)
                    ->event('updated')
                    ->log($description);
                return Response::json([
                    'status' => true,
                    'message' => 'Admin Group Successfully Updated'
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

    // get all admins

    public function get_all_admins(Request $request)
    {
        try {
            $permit_user = User::find(auth()->user()->id);

            $result = User::where('type', 2)->count();
            $recordsTotal = $result;
            $recordsFiltered = $result;

            // $limit = '';
            // $sortBy = $_REQUEST['order'][0]['dir'];
            // $order_a =  $_REQUEST['order'];
            // $order = $order_a[0]['dir'];
            // $oc = $order_a[0]['column'];
            // $ocd = $_REQUEST['columns'][$oc]['data'];
            // $start = $request->input('start');
            // $length = $request->input('length');
            $search  = $request->input('search');

            // select type = 0 for trader
            $result = User::where('type', 2);
            // Filter by finance
            if ($search != "") {
                $result = $result->where('name', 'LIKE', '%' . $search['value'] . '%');
            }
            $result = $result->select()->take($request->length)->skip($request->start)->get();
            $data = array();
            $i = 0;
            foreach ($result as $key => $value) {
                $block_button_text = '';
                $request_for = '';
                if ($value->active_status == 0) {
                    $status = '<span class="badge badge-light-warning">' . __('page.pending') . '</span>'; // <------status badge
                    $block_button_text .= '<i data-feather="user-x"></i> Block'; //  <------block button text
                    $request_for = 'block'; //   <-----request for block
                } elseif ($value->active_status == 1) {
                    $status = '<span class="badge badge-light-success">' . __('page.active') . '</span>'; // <------status badge
                    $block_button_text .= '<i data-feather="user-x"></i> Block'; //  <------block button text
                    $request_for = 'block'; //   <-----request for block
                } else {
                    $status = '<span class="badge badge-light-danger">' . __('page.block') . '</span>'; //  <----Status badge
                    $block_button_text .= '<i data-feather="user-check"></i>' . __('page.unblock') . ''; // <------block button text
                    $request_for = 'unblock'; //   <-----request for unblock
                }
                $admin_des = Admin::where('user_id', $value->id)
                    ->join('admin_groups', 'admins.group_id', '=', 'admin_groups.id')
                    ->join('countries', 'admins.accessible_country', '=', 'countries.id')->first();

                $status = '';
                if ($value->active_status == 0) {
                    $status = '<a href="#" class="text-warning">' . __('page.disabled') . '</a>';
                } elseif ($value->active_status == 1) {
                    $status = '<a href="#" class="text-success">' . __('page.active') . '</a>';
                } else {
                    $status = '<a href="#" class="text-danger">' . __('page.block') . '</a>';
                }

                // check rigt permission
                if ($permit_user->hasDirectPermission('edit admin groups')) {
                    $action_btn = '<span class="dropdown-item btn-block" data-request_for = "' . $request_for . '" data-id="' . $value->id . '">
                                    ' . $block_button_text . '
                                </span>
                                <span class="dropdown-item btn-edit-admin" data-id="' . $value->id . '">
                                <i data-feather="edit"></i> edit
                                </span>';
                } else {
                    $action_btn = '<span class="dropdown-item text-danger">
                                    You dont have right permission
                                </span>';
                }
                $data[$i]['name']         = '<a data-id="' . $value->id . '" href="#" class="dt-description justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span><span>' . $value->name . '</span></a>';
                if (isset($admin_des->group_name)) {
                    $grouptName = ucwords($admin_des->group_name);
                } else {
                    $grouptName = '';
                }
                $data[$i]['group']         = $grouptName;
                if (isset($admin_des->group_name)) {
                    $countryName = ucwords($admin_des->name);
                } else {
                    $countryName = '';
                }
                $data[$i]['country']      = $countryName;
                $data[$i]['status']       = $status;
                $data[$i]['actions']      = '<div class="d-flex justify-content-between">
                                            <a href="#" class="more-actions dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i data-feather="more-vertical"></i>
                                                <i data-feather="edit"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-end">
                                                ' . $action_btn . '
                                            </div>
                                        </div>';

                $i++;
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
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


    // datable description
    // ------------------------------------------------------------------

    public function get_all_admin_description(Request $request, $id)
    {
        try {
            // admin group
            // -------------------------------------------------
            $admins = Admin::where('admins.user_id', $id)
                ->join('admin_groups', 'admins.group_id', '=', 'admin_groups.id')
                ->Leftjoin('user_descriptions', 'admins.user_id', '=', 'user_descriptions.user_id')
                ->Leftjoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                ->join('users', 'admins.user_id', '=', 'users.id')
                ->select(
                    'countries.name as country_name',
                    'users.name as user_name',
                    'users.email as user_email',
                    'users.phone as user_phone',
                    'users.created_at as joining_date',
                    'admin_groups.group_name',
                    'gender',
                )
                ->first();
            if (isset($admins->gender)) {
                $avatar = ($admins->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
            } else {
                $avatar = "avater-men.png";
            }


            // count total trader
            // ----------------------------------------------------------------

            $permit_user = User::find(auth()->user()->id);
            if ($permit_user->hasDirectPermission('create admin groups')) {
                $save_permisson = '  <button type="button" class="btn btn-primary float-end" id="save-permission-' . $id . '" onclick="_run(this)" data-el="fg" data-form="form-asign-role-perimission-' . $id . '" data-loading="<div class=\'spinner-border spinner-border-sm\' role=\'status\'><span class=\'visually-hidden\'>Loading...</span></div>" data-callback="assing_permission_call_back" data-btnid="save-permission-' . $id . '">Save Permission</button>';
            } else {
                $save_permisson = "";
            }

            $description = '<tr class="description" style="display:none">
                <td colspan="6">
                    <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="rounded-0 w-75">
                                    <table class="table table-responsive tbl-balance">
                                        <tr>
                                            <th>' . __('page.total_ib') . '</th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>' . __('page.total_trader') . '</th>
                                            <td></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex justfy-content-between">
                                <div class="rounded-0 w-100">
                                    <table class="table table-responsive tbl-trader-details users">
                                        <tr>
                                            <th>Group</th>
                                            <td>' . ucwords(isset($admins->group_name) ? $admins->group_name : '') . '</td>
                                        </tr>
                                        <tr>
                                            <th>Country</th>
                                            <td>' . ucwords(isset($admins->country_name) ? $admins->country_name : '') . '</td>
                                        </tr>
                                        <tr>
                                            <th>' . __('page.email') . '</th>
                                            <td>' . ($admins->user_email ? $admins->user_email : "") . '</td>
                                        </tr>
                                        <tr>
                                            <th>' . __('page.joining_date') . '</th>
                                            <td>' . $admins->joining_date . '</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="rounded ms-1 dt-trader-img w-100" style="width:198px !important">
                                    <div class="h-100">
                                        <img class="img img-fluid bg-light-primary img-trader-admin" src="' . asset("admin-assets/app-assets/images/avatars/$avatar") . ' "alt="avatar">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <!-- Filled Tabs starts -->
                                <div class="col-xl-12 col-lg-12">
                                    <form class="manager-right-form" action="' . route('admin.set-all-roles-permissions') . '"  method="post" id="form-asign-role-perimission-' . $id . '">
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
                                </div>
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
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'errors' => $th->getMessage(),
            ]);
        }
    }

    // get datatable add user table data
    // -----------------------------------------------------------------------

    public function get_all_admin_description_users(Request $request, $id)
    {
        try {
            $admin = User::find($id);
            $columns = ['role', 'name', 'gurad_name', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];
            // select type = 0 for trader
            $result  = Role::select();
            // Filter by finance

            $count = $result->count(); // <------count total rows
            $result = $result->orderby($orderby, $request->order[0]['dir'])->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $role) {
                $all_perimissions = $role->permissions;
                $has_rol = '';
                if ($admin->hasRole($role->name)) {
                    $has_rol = 'checked';
                }
                $data[$i]['available_right']      = '<div class="form-check form-check-success">
                                                    <input type="checkbox" name="roles[]" class="form-check-input role-checkbox" id="colorCheck3-' . $role->id . '" value="' . $role->id . '" ' . $has_rol . ' />
                                                    <label class="form-check-label" for="colorCheck3-' . $role->id . '">' . ucwords($role->name) . '</label>
                                                </div>';
                // permission of each role--------------
                $j = 1;
                foreach ($all_perimissions as $key => $value) {
                    $has_permission = '';
                    if ($admin->hasDirectPermission($value->name)) {
                        $has_permission = 'checked';
                    }
                    $data[$i]['permission_' . $j] = '<div class="form-check me-3 me-lg-5">
                                                    <input class="form-check-input permission-check" name="permission[]" type="checkbox" id="' . $value->id . '" value="' . $value->name . '" ' . $has_permission . '/>
                                                    <label class="form-check-label" for="' . $value->id . '">' . ucwords(strtok($value->name, " ")) . ' </label>
                                                </div>';
                    $j++;
                }

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
    // update admin account details
    public function update_account_details(Request $request)
    {
        $validation_rules = [
            'phone' => 'nullable|min:3|max:32',
            'password' => 'required|min:6|max:32',
            'confirm_password' => 'required|same:password|min:6|max:32',
            'user_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Please fix the following errors!',
                'errors' => $validator->errors(),
            ]);
        }
        $update = User::where('users.id', $request->user_id)->update([
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        $update_log = Log::where('user_id', $request->user_id)->update([
            'password' => encrypt($request->password),
        ]);
        if ($request->sending_mail === 'yes') {
            EmailService::send_email('change-password', [
                'clientPassword' => $request->password,
                'user_id' => $request->user_id,
            ]);
        }
        if ($update) {
            return Response::json([
                'status' => true,
                'message' => 'Admin Account details successfully update',
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Update failed, Please try again later',
        ]);
    }
    // update personal info
    public function update_personal_info(Request $request)
    {
        $validation_rules = [
            'name' => 'nullable|min:3|max:32',
            'gender' => 'required|max:32',
            'date_of_birth' => 'nullable',
            'user_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Please fix the following errors!',
                'errors' => $validator->errors(),
            ]);
        }
        // update user table
        $update = User::where('users.id', $request->user_id)->update([
            'name' => $request->name,
        ]);
        // update user description table
        $update = UserDescription::where('user_id', $request->user_id)->update([
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
        ]);
        if ($update) {
            return Response::json([
                'status' => true,
                'message' => 'Admin personal info successfully update',
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Update failed, Please try again later',
        ]);
    }
    // update address
    public function update_address(Request $request)
    {
        $validation_rules = [
            'country' => 'nullable',
            'state' => 'nullable|max:100',
            'city' => 'nullable|max:100',
            'zipcode' => 'nullable|max:30',
            'user_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Please fix the following errors!',
                'errors' => $validator->errors(),
            ]);
        }

        // update user description table
        $update = UserDescription::where('user_id', $request->user_id)->update([
            'country_id' => $request->country,
            'state' => $request->state,
            'zip_code' => $request->zipcode,
            'address' => $request->address,
            'city' => $request->city,
        ]);
        if ($update) {
            return Response::json([
                'status' => true,
                'message' => 'Admin address successfully update',
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Update failed, Please try again later',
        ]);
    }
    public function update_social_link(Request $request)
    {
        $validation_rules = [
            'facebook' => 'nullable|max:191',
            'twitter' => 'nullable|max:191',
            'telegram' => 'nullable|max:191',
            'linkedin' => 'nullable|max:191',
            'skype' => 'nullable|max:191',
            'whatsapp' => 'nullable|max:191',
            'user_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Please fix the following errors!',
                'errors' => $validator->errors(),
            ]);
        }

        // update user description table
        $update = SocialLink::where('user_id', $request->user_id)->update([
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'telegram' => $request->telegram,
            'linkedin' => $request->linkedin,
            'skype' => $request->skype,
            'whatsapp' => $request->whatsapp,
        ]);
        if ($update) {
            return Response::json([
                'status' => true,
                'message' => 'Admin social link successfully update',
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Update failed, Please try again later',
        ]);
    }
}
