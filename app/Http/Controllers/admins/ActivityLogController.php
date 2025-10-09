<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\IB;
use App\Models\KycVerification;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use Spatie\Activitylog\Contracts\Activity as ContractsActivity;
use Spatie\Activitylog\Models\Activity;
use Stevebauman\Location\Facades\Location;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:activity log"]);
        $this->middleware(["role:reports"]);
        // system module control
        $this->middleware(AllFunctionService::access('reports', 'admin'));
        $this->middleware(AllFunctionService::access('activity_log', 'admin'));
    }
    //basic view------------------------------------
    public function activity_log(Request $request)
    {
        $events = Activity::select('event')->distinct()->get();
        $activitys = Activity::select('log_name')->distinct()->get();
        return view('admins.reports.activity-log', compact('activitys','events'));
    }
    // end: basic view-------------------------------

    // activity log datatable-------------------------------
    public function activity_log_dt(Request $request)
    {
        try {
            $columns = ['users.name', 'users.type', 'users.email', 'activity_log.log_name', 'activity_log.event', 'activity_log.created_at'];
            $orderby = $columns[$request->order[0]['column']];
            $result = Activity::select(
                'activity_log.*',
                'activity_log.id as activityId',
                'activity_log.created_at',
                'users.id'
            )->leftJoin('users', 'activity_log.causer_id', '=', 'users.id');
            // check login is manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
                $result = $result->whereIn('users.id', $users_id);
            }

            //-------------------------------------------------------------------------------------
            //Filter Start
            //-------------------------------------------------------------------------------------

            //Filter By Month
            if ($request->month != "") {
                if ($request->month == 'this_month') {
                    $currentMonth = now()->month;
                    $result = $result->whereMonth("activity_log.created_at", '=', $currentMonth);
                } else {
                    $date = Carbon::now()->subMonth()->format('Y-m-d');
                    $result = $result->where("activity_log.created_at", '>=', $date);
                }
            }
            //Filter By User Type
            if ($request->user_type != "") {
                $result = $result->where('type', $request->user_type);
            }
            //Filter By Activity
            if ($request->activity != "") {
                $result = $result->where('activity_log.log_name', $request->activity);
            }
            //Filter By Event
            if ($request->event != "") {
                $result = $result->where('activity_log.event', $request->event);
            }
            //Filter By Trading Account Number
            if ($request->trading_account != "") {
                $user_id = TradingAccount::select('user_id')
                    ->where('account_number', $request->trading_acc)->first();
                $result = $result->whereIn('users.id', $user_id);
            }
            //Filter By Trader Name / Email /Phone /Country
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                return $trader_info;
                $user_id = User::select('countries.name')->where(function ($query) use ($trader_info) {
                    $query->where('users.name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $trader_info . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }
            //Filter By IB Name / Email /Phone /Country
            if ($request->ib_info != "") {
                $ib = $request->ib_info;
                $user_id = User::select('countries.name')->where('users.type', 4)->where(function ($query) use ($ib) {
                    $query->where('name', 'LIKE', '%' . $ib . '%')
                        ->orWhere('email', 'LIKE', '%' . $ib . '%')
                        ->orWhere('phone', 'LIKE', '%' . $ib . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $ib . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }
            //Filter by account manager desk manager
            if ($request->manager_info != "") {
                $manager = $request->manager_info;
                $manager_id = User::select('id')
                    ->where(function ($query) use ($manager) {
                        $query->where('name', 'LIKE', '%' . $manager . '%')
                            ->orWhere('email', 'LIKE', '%' . $manager . '%')
                            ->orWhere('phone', 'LIKE', '%' . $manager . '%');
                    })->get()->pluck('id');
                $users_id = ManagerUser::select('user_id')->where('manager_id', $manager_id)->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $users_id);
            }

            //Filter By Request Date
            if ($request->value_from_start_date != "") {
                $result = $result->whereDate("activity_log.created_at", '>=', date('y-m-d', strtotime($request->value_from_start_date)));
            }
            if ($request->value_from_end_date != "") {
                $result = $result->whereDate("activity_log.created_at", '<=', date('y-m-d', strtotime($request->value_from_end_date)));
            }
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {

                $user = User::find($value->causer_id);
                // if register by admin
                if (isset($user->name)) {
                    $user_name = $user->name;
                    $email = $user->email;
                    $user_type = $user->type;
                }
                // if register by self 
                else {
                    $self_data = $value->properties;
                    $self_data = json_decode($self_data);
                    $user_name = isset($self_data->name) ? $self_data->name : '---';
                    $email = isset($self_data->email) ? $self_data->email : '---';
                    $user_type = isset($self_data->type) ? $self_data->type : '---';
                }

                // get activity from log_name and event
                if ($value->log_name === 'login' || $value->log_name === 'logout') {
                    $activity = $user_type . ' ' . $value->log_name;
                } elseif ($value->log_name === 'block' || $value->log_name === 'unblock') {
                    $properties = json_decode($value->properties);
                    if ($value->subject_type === 'App\Models\User') {
                        $activity = $properties->type . ' ' . $value->log_name;
                    }
                } elseif ($value->event === 'email send') {
                    $activity = $value->log_name;
                } elseif (strtolower($value->event) === 'change password') {
                    $properties = json_decode($value->properties);
                    if ($value->subject_type === 'App\Models\User') {
                        $activity = $properties->type . ' pasword changes';
                    }
                } elseif (strtolower($value->log_name === 'change transaction pin')) {
                    $properties = json_decode($value->properties);
                    if ($value->subject_type === 'App\Models\User') {
                        $activity = $properties->type . ' transaction pin changes';
                    }
                } elseif (strtolower($value->log_name)) {
                    $activity = $value->log_name;
                } else {
                    $activity = $value->log_name . ' ' . $value->event;
                }

                // replace for user registration
                if (strtolower($activity) === 'user created') {
                    $properties = json_decode($value->properties);
                    $reg_user_type = $properties->attributes->type;
                    $activity = $reg_user_type . ' Registrtion';
                }

                // change deleted color
                if ($value->event === 'deleted') {
                    $event = '<span class="badge bg-danger">' . ucwords($value->event) . '</span>';
                } elseif ($value->event === 'updated') {
                    $event = '<span class="badge bg-secondary">' . ucwords($value->event) . '</span>';
                    $properties = json_decode(($value->properties));
                    // echo($properties->old->active_status);
                } elseif ($value->event === 'login') {
                    $event = '<span class="badge bg-success">' . ucwords($value->event) . '</span>';
                } elseif ($value->event === 'logout') {
                    $event = '<span class="badge bg-warning">' . ucwords($value->event) . '</span>';
                } elseif ($value->event === 'block') {
                    $event = '<span class="badge bg-danger">' . ucwords($value->event) . '</span>';
                } else {
                    $event = '<span class="badge bg-primary">' . ucwords($value->event) . '</span>';
                }

                $user_name = (isset($user->name)) ? $user_name : '';
                // for manager unsinged
                if (strtolower($activity) === strtolower('Manager User Deleted')) {
                    $activity = 'User remove from manager';
                }
                // for manager assigned
                if (strtolower($activity) === strtolower('Manager User Created')) {
                    $activity = 'User assigned to manager';
                }

                if ($user_type === "system") {
                    $user_type = '<span class="bg-light-success badge badge-success">System</span>';
                }
                if ($user_type === "trader") {
                    $user_type = '<span class="bg-light-warning badge badge-warning">Trader</span>';
                }
                if ($user_type === "ib") {
                    $user_type = '<span class="bg-light-info badge badge-info">IB</span>';
                }
                if ($user_type === "admin") {
                    $user_type = '<span class="bg-light-primary badge badge-primary">Admin</span>';
                }
                if ($user_type === "manager") {
                    $user_type = '<span class="bg-light-danger badge badge-danger">Manager</span>';
                }
                // tabl column
                // -------------------------------------
                $data[$i]["name"]         = '<a data-id="' . $value->activityId . '" href="#" class="dt-description  justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span><span>' . $user_name . '</span></a>';
                $data[$i]["user_type"]         = ucwords($user_type);
                $data[$i]["email"]         = $email;
                $data[$i]["activity"]     = ucwords($activity);
                $data[$i]["event"]     = $event;
                $data[$i]["date"]    = date('d M y h:i:s', strtotime($value->created_at));
                $i++;
            }

            return Response::json([
                "draw" => $request->draw,
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                "draw" => $request->draw,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
        }
    }
    // end activity log datatable--------------------------
    // activity log datatable descriptions----------------
    public function activity_log_dt_description(Request $request, $id)
    {
        $causer = Activity::find($id);
        $user = User::find($causer->causer_id);
        $user_descriptions = UserDescription::where('user_id', $user->id)
            ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')->first(); //<---user description
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }

        // get last login---------------
        $login_activity = Activity::where('log_name', 'login')->where('causer_id', $causer->causer_id)->latest()->first();
        // return $login_activity;
        // return Response::json($login_activity);
        // calculate last login---------------
        if (!isset($login_activity->created_at)) {
            $last_login_at =  'null';
        } else {
            $last_login_at = $login_activity->created_at->diffForHumans();
        }

        // login status-----------------
        $login_status = (isset($user->login_status) && $user->login_status == true) ? 'Online' : 'Offline';
        // action ip
        $activity_description = $causer->description;
        $action_ip = explode(' ', $activity_description);
        $action_ip = $action_ip[3];
        // action locations
        $locationData = Location::get($action_ip);
        $country = (isset($locationData->countryName)) ? $locationData->countryName : (isset($user_descriptions->name) ? $user_descriptions->name : '');
        $region = (isset($locationData->regionName)) ? $locationData->regionName : (isset($user_descriptions->address) ? $user_descriptions->address : '');
        $city   = (isset($locationData->cityName)) ? $locationData->cityName : (isset($user_descriptions->city) ? $user_descriptions->city : '');
        // active status----
        $active_status = ($user->active_status == 2) ? 'checked' : ' ';
        // action at
        $action_at = $causer->created_at->diffForHumans();

        $auth_user = User::find(auth()->user()->id);
        if ($auth_user->hasDirectPermission('edit activity log')) {
            $block_unblock = ' <div class="demo-inline-spacing float-end">
            <div class="form-check form-switch form-check-danger">
                <input type="checkbox" class="form-check-input switch-user-block" id="block-unblock-swtich-' . $user->id . '" value="' . $user->id . '" ' . $active_status . '/>
                <label class="form-check-label" for="block-unblock-swtich-' . $user->id . '">
                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                </label>
            </div>
            <label class="form-check-label mb-50" for="block-unblock-swtich-' . $user->id . '">Unblock &frasl; Block</label>
            </div>';
        } else {
            $block_unblock = "";
        }
        $description = '<tr class="description" style="display:none">
            <td colspan="6">
                <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="rounded-0 w-75">
                                <table class="table table-responsive tbl-balance">
                                    <tr>
                                        <th>Last Login</th>
                                        <td>' . $last_login_at . '</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>' . $login_status . '</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justfy-content-between">    
                            <div class="rounded-0 w-100">
                                <table class="table table-responsive tbl-trader-details">
                                    <tr>
                                        <th>User Category</th>
                                        <td>' . ucwords($user->type) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <td>' . ucwords(isset($user_descriptions->name) ? $user_descriptions->name : '') . '</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>' . $user->email . '</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>' . $user->phone . '</td>
                                    </tr>
                                </table>
                            </div> 
                            <div class="rounded ms-1 dt-trader-img">
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
                                <div class=" p-0">
                                    <div class=" p-0">
                                        <table class="tbl-activity-report table datatable-inner dt-inner-table-dark">
                                            <theader>
                                                <tr>
                                                    <th>Action IP</th>
                                                    <th>Action Country</th>
                                                    <th>Action city</th>
                                                    <th>Action Region</th>
                                                    <th>Action at</th>
                                                </tr>
                                            </theader>
                                            <theader>
                                                <tr>
                                                    <td>' . $action_ip . '</td>
                                                    <td>' . $country . '</td>
                                                    <td>' . $city . '</td>
                                                    <td>' . $region . '</td>
                                                    <td>' . $action_at . '</td>
                                                </tr>
                                            </theader>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    
                   ' . $block_unblock . '
                    <div class="clearfix"></div>
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
    // end activity log datatable descriptions-------------
}
