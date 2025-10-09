<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Country;
use App\Models\Log;
use App\Models\Manager;
use App\Models\ManagerGroup;
use App\Models\ManagerCountry;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\EmailService;
use App\Services\password\PasswordService;
use Illuminate\Support\Facades\Hash;


class AddManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:add manager"]);
        $this->middleware(["role:manager settings"]);

        // system module control
        $this->middleware(AllFunctionService::access('manager_settings', 'admin'));
        $this->middleware(AllFunctionService::access('add_manager', 'admin'));
    }

    //SATAR: View manager form
    // ---------------------------------------------------------------------------
    public function index(Request $request)
    {
        $countries = Country::all();
        $groups = ManagerGroup::all();
        return view('admins.manager-settings.index', ['countries' => $countries, 'groups' => $groups]);
    }

    // START: Create manager
    public function store(Request $request)
    {
        $validation_rules = [
            'name' => 'required|min:4|max:191',
            'email' => 'required|min:4|max:191|email|unique:users',
            'phone' => 'required|min:10|max:20',
            'agent_country' => 'required',
            'type' => 'required',
            'manager_group' => 'required',
            'priority' => 'required|numeric',
            'is_mailable' => 'required',
            'monthly_limit' => 'required|numeric',
            'daily_limit' => 'required|numeric',
            'password' => 'required|same:confirm_password',
            'confirm_password' => 'required',
        ];
        // return Response::json($request->is_mailable);
        if (!isset($request->is_global)) {
            if (!isset($request->client_country)) {
                $validator = Validator::make($request->all(), $validation_rules);
                if ($validator->fails()) {
                    return Response::json([
                        'status' => false,
                        'errors' => $validator->errors(),
                        'message' => 'Please choose atleast one client country!'
                    ]);
                }
            }
        }
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!',
            ]);
        } else {
            $transaction_pin = PasswordService::reset_transaction_pin();
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'type' => $request->type,
                'password' => Hash::make($request->password),
                'transaction_password' => Hash::make($transaction_pin),
            ];
            // if ($request->is_mailable == false) {
            //     $data['email_verified_at'] = date("Y-m-d h:i:s", strtotime('now'));
            // }
            $data['email_verified_at'] = date("Y-m-d h:i:s", strtotime('now'));
            $create_id = User::Create($data)->id;
            if ($create_id != "") {
                // user description
                $description_create = UserDescription::create([
                    'user_id' => $create_id,
                    'country_id' => $request->agent_country
                ]);
                // manager table
                // return [
                //     'user_id' => $create_id,
                //     'group_id' => $request->manager_group,
                //     'priority' => $request->priority,
                //     'is_mailable' => isset($request->is_mailable) ? 1 : 0,
                //     'monthly_limit' => $request->monthly_limit,
                //     'daily_limit' => $request->daily_limit,
                // ];
                $create_manager = Manager::Create([
                    'user_id' => $create_id,
                    'group_id' => (int)$request->manager_group,
                    'priority' => (int)$request->priority,
                    'is_mailable' => isset($request->is_mailable) ? 1 : 0,
                    'monthly_limit' => (int)$request->monthly_limit,
                    'daily_limit' => (int)$request->daily_limit,
                ]);
                // update log table
                Log::create([
                    'user_id' => $create_id,
                    'password' => encrypt($request->password),
                    'transaction_password' => encrypt($transaction_pin),
                ]);
                if ($create_manager) {
                    $this->giveManagerPermissonBygroup($request->manager_group, $create_id);
                    // manager accessible country
                    if (isset($request->client_country)) {

                        for ($i = 0; $i < count($request->client_country); $i++) {
                            $create_m_country = ManagerCountry::Create([
                                'manager_id' => $create_id,
                                'accessible_country' => $request->client_country[$i],
                            ]);
                        }
                    } else {
                        // if set global
                        $countries = Country::all();
                        foreach ($countries as $key => $value) {
                            $create_m_country = ManagerCountry::Create([
                                'manager_id' => $create_id,
                                'accessible_country' => $value->id,
                            ]);
                        }
                    }

                    if ($create_m_country) {
                        // store activity log---------------------
                        $ip_address = request()->ip();
                        $description = "The IP address $ip_address has been register a manager";
                        $user = User::find($create_id);
                        activity('manager registration')
                            ->causedBy(auth()->user()->id)
                            ->withProperties($user)
                            ->event('manager registration')
                            ->performedOn($user)
                            ->log($description);
                        // <----------------------

                        // sending mail
                        if ($request->is_mailable === '1') {
                            EmailService::send_email('manager-registration', [
                                'user_id' => $create_id,
                                'password' => $request->password,
                                'transaction_password' => $transaction_pin,
                                'login' => route('manager.login'),
                            ]);
                        }
                        return Response::json([
                            'status' => true,
                            'message' => 'New Manager Successfully Added'
                        ]);
                    } else {
                        return Response::json([
                            'status' => false,
                            'message' => 'Something went wrong! please try again later.'
                        ]);
                    }
                }
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Something went wrong! please try again later.'
                ]);
            }
        }
    }

    // START: get manger group
    // ----------------------------------------------------------------------
    public function get_group(Request $request, $type)
    {
        $groups = ManagerGroup::where('group_type', $type)->select()->get();
        $group_options = '';
        foreach ($groups as $key => $value) {
            $group_options .= '<option value="' . $value->id . '">' . $value->group_name . '</option>';
        }
        return Response::json($group_options);
    }

    // START: get manager info
    // ---------------------------------------------------------------------------------------
    public function get_manager_info(Request $request, $id)
    {
        $users = User::where('users.id', $id)
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->leftJoin('managers', 'users.id', '=', 'managers.user_id')
            ->leftJoin('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
            ->first();
        $log_password = Log::where('user_id', $id)->first();
        $password = $transaction_pin = "";
        if ($log_password) {
            $password = decrypt($log_password->password);
            $transaction_pin = decrypt($log_password->transaction_password);
        }
        // get all groups
        // -------------------------------------------------------------------------------------------------
        $manager_groups = ManagerGroup::all();
        $group_options = '';
        $group_name = '';
        foreach ($manager_groups as $key => $value) {
            $selected = ($value->id == $users->group_id) ? 'selected' : "";
            $group_name = ($value->id == $users->group_id) ? $value->group_name : "";
            $group_options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->group_name . '</option>';
        }

        // get all countries
        // --------------------------------------------------------------------------------------------------------
        $countries = Country::all();
        $country_options = '';
        foreach ($countries as $key => $value) {
            $selected = ($value->id == $users->country_id) ? 'selected' : "";
            $country_options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->name . '</option>';
        }

        // -------------------------------------------------------------------------------------------------------------
        $data = '<div class="col-12 mb-1">
                    <input type="hidden" name="manager_id" value="' . $id . '">
                    <label class="form-label" for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="John Arifin" tabindex="-1" data-msg="Please enter name" value="' . $users->name . '" />
                </div>
                <div class="col-6 mb-1">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="arifin@example.com" tabindex="-1" data-msg="Please enter email" value="' . $users->email . '" disabled/>
                    <!-- Permission table -->
                </div>
                <div class="col-6 mb-1">
                    <label class="form-label" for="Phone">Phone</label>
                    <input type="text" id="Phone" name="phone" class="form-control" placeholder="+880174789404X" tabindex="-1" data-msg="Please enter phone number" value="' . $users->phone . '" />
                    <!-- Permission table -->
                </div>
                <div class="col-6 mb-1">
                    <label class="form-label" for="manager-country">Country</label>
                    <select class="select2 form-select" id="manager-country" name="agent_country">
                        ' . $country_options . '
                    </select>
                    <!-- Permission table -->
                </div>
                <div class="col-6 mb-1">
                    <label class="form-label" for="manager-group">Manager Group</label>
                    <select class="select2 form-select" id="manager-group" name="manager_group">
                        ' . $group_options . '
                    </select>
                    <!-- Permission table -->
                </div>
                <div class="col-6 mb-1">
                    <label class="form-label" for="monthly_limit">Monthly Limit</label>
                    <input type="number" id="monthly_limit" name="monthly_limit" class="form-control" placeholder="0" tabindex="-1" data-msg="Please enter role name" value="' . $users->monthly_limit . '" />
                    <!-- Permission table -->
                </div>
                <div class="col-6 mb-1">
                    <label class="form-label" for="daily_limit">Daily Limit</label>
                    <input type="number" id="daily_limit" name="daily_limit" class="form-control" placeholder="0" tabindex="-1" data-msg="Please enter role name" value="' . $users->daily_limit . '" />
                    <!-- Permission table -->
                </div>
                <div class="col-6 mb-1">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" tabindex="-1" data-msg="Please enter role name" value="' . $password . '" />
                    <!-- Permission table -->
                </div>
                <div class="col-6 mb-1">
                    <label class="form-label" for="transaction-pin">Transaction Pin</label>
                    <input type="password" id="transaction_pin" name="transaction-pin" class="form-control" tabindex="-1" data-msg="Please enter role name" value="' . $transaction_pin . '" />
                    <!-- Permission table -->
                </div>';
        return Response::json(['data' => $data, 'manager_group' => $group_name]);
    }

    // START: Edit managers
    // ---------------------------------------------------------------------
    public function edit_manager(Request $request)
    {
        $validation_rules = [
            'name' => 'required|min:4|max:191',
            'phone' => 'required|min:10|max:20',
            'agent_country' => 'required',
            'manager_group' => 'required',
            'monthly_limit' => 'required|numeric',
            'daily_limit' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false, 'errors' => $validator->errors()
            ]);
        } else {

            // update user table
            $user = User::find($request->manager_id);
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->transaction_password = Hash::make($request->transaction_pin);
            $user->save();
            // update log table
            Log::where('user_id',$user->id)->update([
                'password'=>encrypt($request->password),
                'transaction_password'=>encrypt($request->transaction_pin),
            ]);
            // update user description table
            $manager_des = UserDescription::where('user_id', $request->manager_id)->first();
            $manager_des->country_id = $request->agent_country;
            $manager_des->save();

            // update manager table
            $manager = Manager::where('user_id', $request->manager_id)->first();
            $manager->group_id = $request->manager_group;
            $manager->monthly_limit = $request->monthly_limit;
            $manager->daily_limit = $request->daily_limit;
            $update = $manager->save();

            if ($update) {
                // store activity log---------------------
                $ip_address = request()->ip();
                $description = "The IP address $ip_address has been manager info updated";

                activity('edit manager info')
                    ->causedBy(auth()->user()->id)
                    ->withProperties($user)
                    ->event('updated')
                    ->performedOn($user)
                    ->log($description);
                // <----------------------
                return Response::json([
                    'status' => true,
                    'message' => 'Manager Successfully Updated'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Something went wrong! please try again later.'
                ]);
            }
        }
    }

    // START: Disable manager
    // ---------------------------------------------------------------------------------------
    public function disable_manager(Request $request)
    {
        $users = User::find($request->manager_id);
        $users->active_status = 0;
        if ($users->save()) {
            // store activity log---------------------
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been block manager";

            activity('block manager')
                ->causedBy(auth()->user()->id)
                ->withProperties($users)
                ->event('block')
                ->performedOn($users)
                ->log($description);
            // <----------------------
            return Response::json([
                'status' => true,
                'message' => 'Manager Successfully Disabled'
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong! please try again later.'
            ]);
        }
    }
    // START: Enable manager
    // ---------------------------------------------------------------------------------------
    public function enable_manager(Request $request)
    {
        $users = User::find($request->manager_id);
        $users->active_status = 1;
        if ($users->save()) {
            // store activity log---------------------
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been unblock manager";

            activity('unblock manager')
                ->causedBy(auth()->user()->id)
                ->withProperties($users)
                ->event('unblock')
                ->performedOn($users)
                ->log($description);
            // <----------------------
            return Response::json([
                'status' => true,
                'message' => 'Manager Successfully Enable'
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong! please try again later.'
            ]);
        }
    }
    // START: Block manager
    // ---------------------------------------------------------------------------------------
    public function Block_manager(Request $request)
    {
        $users = User::find($request->manager_id);
        $users->active_status = 2;
        if ($users->save()) {
            // store activity log---------------------
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been block manager";

            activity('block manager')
                ->causedBy(auth()->user()->id)
                ->withProperties($users)
                ->event('block')
                ->performedOn($users)
                ->log($description);
            // <----------------------
            if ($request->ajax()) {

                return Response::json(['status' => true, 'message' => 'Manager Successfully Block']);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'message' => 'Something went wrong! please try again later.']);
            }
        }
    }



    // START: Give manager permission when it create
    //----------------------------------------------------------------------------------------
    public function giveManagerPermissonBygroup($groupId, $user_id)
    {
        $user = User::find($user_id);
        $groups_users  = Manager::select('user_id')->where('group_id', $groupId)->first();
        $groupRoles = [];
        $groupPermission = [];

        if ($groups_users && $groups_users->user_id) {
            $groupFUser = User::find($groups_users->user_id);
            if ($groupFUser) {
                $curent_rolename = $groupFUser->getRoleNames();
                if (count($curent_rolename) != 0) {
                    foreach ($curent_rolename as $role) {
                        $rolenid = Role::select('id')->where('name', $role)->first();
                        if ($rolenid) {
                            $rolenid = strval($rolenid->id);
                            array_push($groupRoles, $rolenid);
                        }
                    }
                }

                $groupPermission = $groupFUser->getPermissionNames();
            }
        }
        // return  $groupPermission;

        if ($user->syncRoles($groupRoles)) {
            $user->givePermissionTo($groupPermission);
            return  true;
        } else {
            return false;
        }
    }
}
