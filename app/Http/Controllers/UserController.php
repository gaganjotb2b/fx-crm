<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AdminGroup;
use App\Models\Country;
use App\Models\Log;
use App\Models\Traders\SocialLink;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\common\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    //get user update form-----------------------------------------
    public function get_form(Request $request)
    {
        $data['status'] = false;
        $user_type = $request->type;
        $countries = Country::All();
        $groups = AdminGroup::All();

        // get form data for admin

        if ($user_type === 'admin') {
            $admin = User::where('type', 2)->where('users.id', $request->id)
                ->join('admins', 'users.id', '=', 'admins.user_id')
                ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->first();
            // countries----------
            $country_option = '';
            foreach ($countries as $country) :
                $selected = ($country->id == $admin->country_id) ? 'selected="selected"' : "selected=''";
                $country_option .= '<option value="' . $country->id . '" ' . $selected . '>' . $country->name . '</option>';
            endforeach;
            // admin groups-----------------
            $group_options = '';
            foreach ($groups as $group) :
                $selected = ($group->id == $admin->group_id) ? 'selected="selected"' : "selected=''";
                $group_options .= '<option value="' . $group->id . '">' . $group->group_name . '</option>';
            endforeach;
            // admin old password---------------
            $admin_old_pass = Log::where('user_id', $request->id)->first();

            $old_password = (isset($admin_old_pass->password)) ? decrypt($admin_old_pass->password) : '';
            $old_transaction_pin = (isset($admin_old_pass->transaction_password)) ? decrypt($admin_old_pass->transaction_password) : '';
            $form = '<div class="mb-1 row">
            <label for="full-name" class="col-sm-3 col-form-label">' . __("admin-management.Full Name") . '<span class="text-danger">&#9734;</span></label>
            <div class="col-sm-9">
                <input type="text" name="name" class="form-control" id="full-name" placeholder="John Arifin" value="' . $admin->name . '"/>
                <input type="hidden" name="id" value="' . $request->id . '">
            </div>
        </div>
        <div class="mb-1 row">
            <label for="email" class="col-sm-3 col-form-label">' . __("admin-management.Email") . '<span class="text-danger">&#9734;</span></label>
            <div class="col-sm-9">
                <input type="text" name="email" class="form-control" id="email" placeholder="admin@crm.com" value="' . $admin->email . '" disabled/>
            </div>
        </div>
        <div class="mb-1 row">
            <label for="phone" class="col-sm-3 col-form-label">' . __("admin-management.Phone") . '<span class="text-danger">&#9734;</span></label>
            <div class="col-sm-9">
                <input type="text" name="phone" class="form-control" id="phone" placeholder="+88017478XXXX" value="' . $admin->phone . '"/>
            </div>
        </div>
        <div class="mb-1 row">
            <label for="country" class="col-sm-3 col-form-label ">' . __('admin-management.Country') . '<span class="text-danger">&#9734;</span></label>
            <div class="col-sm-9">
                <select class="select2 form-select form-control" name="country" id="country">
                    ' . $country_option . '
                </select>
            </div>
        </div>
        <div class="mb-1 row">
            <label for="group" class="col-sm-3 col-form-label ">' . __("admin-management.Admin Group") . '<span class="text-danger">&#9734;</span></label>
            <div class="col-sm-9">
                <select class="select2 form-select form-control" name="admin_group" id="group">
                    ' . $group_options . '
                </select>
            </div>
        </div>
        <div class="mb-1 row">
            <label for="password" class="col-sm-3 col-form-label ">' . __("admin-management.Password") . ' <span class="text-danger">&#9734;</span></label>
            <div class="col-sm-9">
                <div class="input-group form-password-toggle mb-2">
                    <input data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" type="password" name="password" class="form-control" id="basic-default-password" placeholder="Login Password" aria-describedby="basic-default-password" value="' . $old_password . '"/>
                    <button class="btn btn-primary waves-effect waves-float waves-light btn-gen-password" type="button" id="rstButton"><i class="fas fa-key"></i></button>
                </div>
            </div>
        </div>
        <div class="mb-1 row">
            <label for="transaction-pin" class="col-sm-3 col-form-label ">' . __('admin-management.Transaction Pin') . '<span class="text-danger">&#9734;</span></label>
            <div class="col-sm-9">
                <div class="input-group form-password-toggle mb-2">
                    <input data-size="16" data-character-set="a-z,A-Z,0-9,#" rel="gp" type="password" name="transaction_pin" class="form-control" id="transaction-pin" placeholder="Transaction Pin" aria-describedby="basic-default-password" value="' . $old_transaction_pin . '"/>
                    <button class="btn btn-primary waves-effect waves-float waves-light btn-gen-password" type="button" id="rstButton"><i class="fas fa-key"></i></button>
                </div>
            </div>
        </div>';

            return Response::json([
                'status' => false,
                'form' => $form,
                'date' => $admin->date_of_birth,
            ]);
        }
        if ($data['status'] == false) {
            $data['message'] = 'Somthing went wrong please try again later! It may browser or data error.';
        }
        return Response::json($data);
    }

    // update user---------------------
    public function update_user(Request $request)
    {
        $type = $request->type;
        // udpate admin----------------
        if ($type === 'admin') {
            $validation_rules = [
                'name' => 'required|min:4|max:191',
                'date_of_birth' => 'required',
                // 'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'phone' => 'required|min:5|max:32',
                'country' => 'required',
                'admin_group' => 'required',
                'password' => 'required|min:6',
                'transaction_pin' => 'required|min:6|max:8',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following errors!'
                ]);
            }
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->transaction_password = Hash::make($request->transaction_pin);
            $udpate = $user->save();
            // update user table
            $update = User::where('users.id', $user->id)->update([
                'name' => $request->name,
                'password' => ''
            ]);

            if ($udpate) {
                $admin = Admin::where('user_id', $request->id)->first();
                $admin->group_id = $request->admin_group;
                $admin_update = $admin->save();

                $password_safe = Log::where('user_id', $request->id)->first();
                $password_safe->password = encrypt($request->password);
                $password_safe->transaction_password = encrypt($request->transaction_pin);
                $udpate_log = $password_safe->save();
                return Response::json([
                    'status' => true,
                    'message' => 'Admin Successfully updated'
                ]);
            } else {
                return Response::json([
                    'status' => false,
                    'message' => 'Somthing Went wrong! Please try again later'
                ]);
            }
        }
    }
    // get input user
    public function find_users(Request $request)
    {
        $users = User::where('type', $request->type)->where('email', 'LIKE', "%{$request->input_data}%")->get();
        return Response::json($users);
    }
    public function get_admin_data(Request $request)
    {
        $user = User::find($request->id);
        $user_description = UserDescription::where('user_id', $request->id)->first();
        $social_link = SocialLink::where('user_id', $request->id)->first();
        $log = Log::where('user_id', $request->id)->first();
        return Response::json([
            'user_id' => $request->id,
            'email' => ($user) ? $user->email : '',
            'phone' => ($user) ? $user->phone : '',
            'password' => ($log) ? decrypt($log->password) : '',
            'name' => ($user) ? $user->name : '',
            'country' => ($user) ? $user->country : '',
            'country_name' => UserService::get_country($request->user_id),
            'date_of_birth' => ($user_description) ? $user_description->date_of_birth : '',
            'gender' => ($user_description) ? $user_description->gender : '',
            'state' => ($user_description) ? $user_description->state : '',
            'zip_code' => ($user_description) ? $user_description->zip_code : '',
            'city' => ($user_description) ? $user_description->city : '',
            'address' => ($user_description) ? $user_description->address : '',
            'facebook' => ($social_link) ? $social_link->facebook : '',
            'twitter' => ($social_link) ? $social_link->twitter : '',
            'telegram' => ($social_link) ? $social_link->telegram : '',
            'linkedin' => ($social_link) ? $social_link->linkedin : '',
            'skype' => ($social_link) ? $social_link->skype : '',
            'whatsapp' => ($social_link) ? $social_link->whatsapp : '',
        ]);
    }
    
    
    public function searchClient(Request $request){
        $search = $request->input('q');

        $users = User::leftJoin('admins', 'users.id', '=', 'admins.user_id')
             ->whereNull('admins.user_id') // Exclude users who exist in the admins table
             ->where(function ($query) use ($search) {
                 $query->where('users.name', 'LIKE', "%{$search}%")
                       ->orWhere('users.email', 'LIKE', "%{$search}%");
             })
             ->limit(10)
             ->get(['users.id', 'users.name', 'users.email']);

        return response()->json($users);
    }
}
