<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\adminRegistratoinMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\Country;
use App\Models\AdminGroup;
use App\Models\User;
use App\Models\Admin;
use App\Models\DummyUser;
use App\Models\Log;
use App\Models\UserDescription;
use App\Services\AgeCalculatorService;
use App\Services\AllFunctionService;
use App\Services\EmailService;
use App\Services\password\PasswordService;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class AdminRegistrationController extends Controller
{

    public function __construct()
    {
        $this->middleware(["role:admin registration"]);
        $this->middleware(["role:manage admin"]);
        // system module control
        $this->middleware(AllFunctionService::access('manage_admin', 'admin'));
        $this->middleware(AllFunctionService::access('admin_registration', 'admin'));
    }
    public function index(Request $request)
    {
        $countries = Country::all();
        $groups = AdminGroup::all();
        $pending_registration = DummyUser::where('ip_address', $request->ip())->first();
        return view(
            'admins.rolesPermission.admin-registration',
            [
                'groups' => $groups,
                'group' => ($pending_registration) ? $pending_registration->group : '',
                'name' => ($pending_registration) ? $pending_registration->name : '',
                'email' => ($pending_registration) ? $pending_registration->email : '',
                'phone' => ($pending_registration) ? $pending_registration->phone : '',
                'password' => ($pending_registration) ? $pending_registration->password : '',
                'sending_mail' => ($pending_registration) ? $pending_registration->sending_mail : '',
                'auto_activate' => ($pending_registration) ? $pending_registration->auto_activate : '',
                'gender' => ($pending_registration) ? $pending_registration->gender : '',
                'date_of_birth' => ($pending_registration) ? $pending_registration->date_of_birth : '',
                'country' => ($pending_registration) ? $pending_registration->country : '',
                'state' => ($pending_registration) ? $pending_registration->state : '',
                'city' => ($pending_registration) ? $pending_registration->city : '',
                'zipcode' => ($pending_registration) ? $pending_registration->zipcode : '',
                'address' => ($pending_registration) ? $pending_registration->address : '',
                'countries' => $countries
            ]
        );
    }
    public function store(Request $request)
    {
        $validation_rules = [
            'twitter' => 'nullable|max:191',
            'facebook' => 'nullable|max:191',
            'telegram' => 'nullable|max:191',
            'linkedin' => 'nullable|max:191',
            'skype' => 'nullable|max:191',
            'whatsapp' => 'nullable|max:191',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => 'Fix the following errors',
                'errors' => $validator->errors()
            ]);
        }
        // check account details exist or not
        $dummy_user = DummyUser::whereNotNull('name')->whereNotNull('email')->whereNotNull('password')->where('ip_address', $request->ip())->first();
        if ($dummy_user) {
            $create = User::create([
                'name' => $dummy_user->name,
                'email' => $dummy_user->email,
                'phone' => $dummy_user->phone,
                'password' => Hash::make($dummy_user->password),
                'transaction_password' => Hash::make($dummy_user->transaction_pin),
                'email_verified_at' => ($dummy_user->auto_activate === '1') ? now() : null,
                'type' => 2,
            ]);
            $description = UserDescription::create([
                'user_id' => $create->id,
                'country_id' => $dummy_user->country,
                'state' => $dummy_user->state,
                'zip_code' => $dummy_user->zipcode,
                'date_of_birth' => $dummy_user->date_of_birth,
                'gender' => $dummy_user->gender,
            ]);
            // update log table 
            Log::create([
                'user_id' => $create->id,
                'password' => encrypt($dummy_user->password),
                'transaction_pin' => encrypt($dummy_user->transaction_pin),
            ]);
            // update admin table
            Admin::updateOrCreate(
                [
                    'user_id' => $create->id,
                ],
                [
                    'group_id' => $dummy_user->group,
                    'user_id' => $create->id,
                ]
            );
            // sending amail
            if ($dummy_user->sending_mail === '1') {
                if ($dummy_user->auto_activate === '1') {
                    EmailService::send_email('admin-registration', [
                        'user_id' => $create->id,
                        'login' => route('admin.login'),
                        'password' => $dummy_user->password,
                        'transaction_password' => $dummy_user->transaction_pin,
                    ]);
                } else {
                    EmailService::send_email('admin-activation', [
                        'user_id' => $create->id,
                        'activation_link' => url('/admin/activation/ac/' . encrypt($create->id)),
                        'password' => $dummy_user->password,
                        'transaction_password' => $dummy_user->transaction_pin,
                    ]);
                }
            }
            // return status
            if ($create) {
                // clear pending registration
                DummyUser::where('ip_address', $request->ip())->delete();
                return Response::json([
                    'status' => true,
                    'message' => 'Admin registration successfully done!',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later!',
            ]);
        }
        return Response::json([
            'status' => false,
            'message' => 'Account details missing, Please first enter account details!',
        ]);
    }
    // new admin registration
    // account details
    public function acctoun_details(Request $request)
    {
        try {
            $validation_rules = [
                'name' => 'required|min:3|max:32',
                'admin_group' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6|max:32',
                'confirm_password' => 'required|min:6|max:32|same:password',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            // check mail already taken across all user types
            $user = User::where('email', $request->email)->exists();
            if ($user) {
                return Response::json([
                    'status' => false,
                    'message' => 'This email is already registered. Please use a different email address.',
                    'errors' => ['email' => 'This email is already registered. Please use a different email address.'],
                ]);
            }

            // check phone number already taken across all user types
            $phone_user = User::where('phone', $request->phone)->exists();
            if ($phone_user) {
                return Response::json([
                    'status' => false,
                    'message' => 'This phone number is already registered. Please use a different phone number.',
                    'errors' => ['phone' => 'This phone number is already registered. Please use a different phone number.'],
                ]);
            }
            $transaction_pin = PasswordService::reset_transaction_pin();
            $create = DummyUser::updateOrCreate(
                [
                    'ip_address' => request()->ip(),
                ],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'transaction_pin' => $transaction_pin,
                    'auto_activate' => ($request->auto_activate === 'yes') ? '1' : '0',
                    'sending_mail' => ($request->sending_mail === 'yes') ? '1' : '0',
                    'ip_address' => $request->ip(),
                    'group' => $request->admin_group,
                ]
            );

            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Admin account details  successfully save!',
                    'user_id' => $create->id,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later!',
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong, please try again later!',
            ]);
        }
    }
    public function personal_info(Request $request)
    {
        try {
            $validation_rules = [
                'phone' => 'nullable|min:3|max:32',
                'gender' => 'nullable',
                'date_of_birth' => 'nullable',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            $create = DummyUser::updateOrCreate(
                [
                    'ip_address' => request()->ip(),
                ],
                [
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'date_of_birth' => $request->date_of_birth,
                    'ip_address' => $request->ip()
                ]
            );

            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Admin personal info  successfully save!',
                    'user_id' => $create->id,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later!',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later!',
            ]);
        }
    }
    public function address(Request $request)
    {
        try {
            $validation_rules = [
                'country' => 'nullable',
                'state' => 'nullable|max:100',
                'city' => 'nullable|max:100',
                'zipcode' => 'nullable|max:100',
                'address' => 'nullable|max:100',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors!',
                    'errors' => $validator->errors(),
                ]);
            }
            $create = DummyUser::updateOrCreate(
                [
                    'ip_address' => request()->ip(),
                ],
                [
                    'address' => $request->address,
                    'state' => $request->state,
                    'zipcode' => $request->zipcode,
                    'city' => $request->city,
                    'country' => $request->country,
                    'ip_address' => $request->ip()
                ]
            );

            if ($create) {
                return Response::json([
                    'status' => true,
                    'message' => 'Admin address  successfully save!',
                    'user_id' => $create->id,
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later!',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, please try again later!',
            ]);
        }
    }
}
