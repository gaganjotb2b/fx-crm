<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Manager;
use App\Models\ManagerUser;
use App\Models\UserDescription;
use App\Models\IB;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use App\Services\common\UserService;
use App\Services\manager\ManagerService;
use Illuminate\Support\Facades\Auth;

class Managercontroller extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:manager list"]);
        $this->middleware(["role:manager settings"]);

        // system module control
        $this->middleware(AllFunctionService::access('manager_settings', 'admin'));
        $this->middleware(AllFunctionService::access('manager_list', 'admin'));
    }
    //get all manager
    // -------------------------------------------------------------
    public function index(Request $request)
    {
        $countries = Country::all();
        return view('admins.manager-settings.all-manager', [
            'countries' => $countries,
        ]);
    }
    // get all manager in datatable
    // --------------------------------------------------------------
    public function get_managers(Request $request)
    {
        try {
            $auth_user = User::find(auth()->user()->id);
            $columns = [
                'name',
                'manager_groups.group_type',
                'managers.group_id',
                'user_descriptions.country_id',
                'active_status',
                'active_status',
            ];
            $orderby = $columns[$request->order[0]['column']];
            $result = User::select(
                'users.name',
                'users.id',
                'manager_groups.group_name',
                'users.active_status',
                'manager_groups.group_type'
            )->whereIn('type', [5, 6, 7])
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->join('managers', 'users.id', '=', 'managers.user_id')
                ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id');
            // filter for manager login
            if ((!$auth_user->hasDirectPermission('edit manager list')) &&
                (!$auth_user->hasDirectPermission('create manager list')) &&
                auth()->user()->type === 'manager'
            ) {
                // filter account manager under desk manager
                $account_manager = ManagerUser::where('manager_id', auth()->user()->id)
                    ->join('users', 'manager_users.user_id', '=', 'users.id')
                    ->where('users.type', 5)->select('user_id')->pluck('user_id');
                $result = $result->whereIn('users.id', $account_manager);
            }


            // filter by manage group
            if ($request->manager_group != "") {
                $result = $result->where('group_type', $request->manager_group);
            }


            // filter by status
            if ($request->status != "") {
                $result = $result->where('users.active_status', $request->status);
            }


            // filter by name or email
            if ($request->manager_info != "") {
                $manager_info = $request->manager_info;
                $result = $result->where(function ($query) use ($manager_info) {
                    $query->where('users.name', 'like', '%' . $manager_info . '%')
                        ->orWhere('users.email', 'like', '%' . $manager_info . '%')
                        ->orWhere('users.phone', 'like', '%' . $manager_info . '%');
                });
            }

            // filter by trader info name / email / phone
            if ($request->trader_info != "") {
                $filter_client = User::where('email', 'LIKE', '%' . $request->trader_info . '%')
                    ->orWhere('name', 'LIKE', '%' . $request->trader_info . '%')->where("type", 0)->first('id');
                $manager_id = ManagerUser::where('user_id', $filter_client->id)->get('manager_id')->pluck('manager_id');
                $result = $result->whereIn('users.id', $manager_id);
            }

            // filter by country
            if ($request->country != "") {
                $result = $result->where('user_descriptions.country_id', $request->country);
            }
            // filter by trader info name / email / phone
            if ($request->ib_info != "") {
                $ib_info = $request->ib_info;
                $filter_client = User::where(function ($query) use ($ib_info) {
                    $query->where('email', 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('name', 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $ib_info . '%');
                })->where("type", CombinedService::type())->get('id')->pluck('id');
                $manager_id = ManagerUser::whereIn('user_id', $filter_client)->get('manager_id')->pluck('manager_id');
                $result = $result->whereIn('users.id', $manager_id);
            }


            // filter by search
            if ($request->search != "") {
                $search = $request->search['value'];
                $result = $result->where(function ($query) use ($search) {
                    $query->where('users.email', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $search . '%');
                });
            }
            $count = $result->count(); // <------count total rows
            $result = $result->orderby($orderby, $request->order[0]['dir'])
                ->skip($request->start)
                ->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
                $manager_type = '';
                if ($value->group_type == 0) {
                    $manager_type = 'Desk Manager';
                } elseif ($value->group_type == 1) {
                    $manager_type = 'Account Manager';
                } elseif ($value->group_type == 6) {
                    $manager_type = 'Admin Manager';
                } elseif ($value->group_type == 7) {
                    $manager_type = 'Country Manager';
                } else {
                    $manager_type = 'Account Manager';
                }
                //permisson script

                if ($auth_user->hasDirectPermission('edit manager groups')) {
                    $edit_button = ' <a data-id="' . $value->id . '" href="javascript:;" class="role-edit-modal edit-manager-info"   data-bs-toggle="modal" data-bs-target="#addRoleModal" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit manager info here">
                                    <i data-feather="edit"></i>
                                    Edit
                                </a> ';
                    $block_unbloack = ' <span class="dropdown-item btn-block" data-bs-toggle="tooltip" data-bs-placement="top" title="Click Action to block manager" data-id="' . $value->id . '">
                                    <i data-feather="user-x"></i>
                                    Block
                                </span>
                                <span class="dropdown-item btn-enable" data-bs-toggle="tooltip" data-bs-placement="top" title="Click Action to unblock manager" data-id="' . $value->id . '">
                                    <i data-feather="user-check"></i>
                                    Enable
                                </span>';
                } else {
                    $edit_button = '<span class="text-danger">' . __('page.no_permisson_to_access') . '</span>';
                    $block_unbloack = '<span class="text-danger">
                                       ' . __('page.no_permisson_to_access') . '
                                    </span> ';
                }

                // check status
                // ------------------------------------------------------------------------------------------------
                $status = '';
                if ($value->active_status == 0) {
                    $status = '<a href="#" class="text-warning">' . __('page.disabled') . '</a>';
                } elseif ($value->active_status == 1) {
                    $status = '<a href="#" class="text-success">' . __('page.active') . '</a>';
                } else {
                    $status = '<a href="#" class="text-danger">' . __('page.block') . '</a>';
                }
                $data[$i]["name"]         = '<a data-id="' . $value->id . '" href="#" class="dt-description justify-content-start text-truncate"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span><span>' . $value->name . '</span></a>';
                $data[$i]["manager_type"]         = $manager_type;
                $data[$i]["group"]         = ucwords((isset($value->group_name)) ? $value->group_name : '');
                $data[$i]["country"]      = ucwords(UserService::get_country($value->id));
                $data[$i]["status"]       = $status;
                $data[$i]["actions"]      = '<div class="d-flex justify-content-between" data-bs-toggle="tooltip" data-bs-placement="top" title="Click Action to block or unblock user">
                                            <a href="#" class="more-actions dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i data-feather="more-vertical"></i>
                                            </a>
                                            ' . $edit_button . '
                                            <div  class="dropdown-menu dropdown-menu-end">
                                               ' . $block_unbloack . '
                                            </div>
                                        </div>';
                $i++;
            }

            return Response::json([
                'draw' => $_REQUEST['draw'],
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
                'data' => [],
            ]);
        }
    }

    // get manager descriptions
    // ------------------------------------------------------------------------------------------
    public function get_all_manager_description(Request $request, $id)
    {
        // count total ib
        // -------------------------------------------------
        $ib_s = ManagerUser::where('manager_id', $id)->where('users.type', CombinedService::type());
        if (CombinedService::is_combined()) {
            $ib_s = $ib_s->where('combine_access', 1);
        }
        $ib_s = $ib_s->join('users', 'manager_users.user_id', '=', 'users.id')
            ->get();
        $ib_id = [];
        $total_ib = 0;
        foreach ($ib_s as $key => $value) {
            array_push($ib_id, $value->user_id);
            $total_ib++;
        }
        // manager info
        // --------------------------------------------------------------------
        $manager_info = User::find($id);
        $manager_email = (isset($manager_info->email)) ? $manager_info->email : '';
        $joining_date = (isset($manager_info->created_at)) ? date('d F y, h:i A', strtotime($manager_info->created_at)) : '';

        // get manager group
        // ---------------------------------------------------------------------
        $manager_grops = Manager::where('user_id', $id)
            ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
            ->first();
        $manager_group_name = (isset($manager_grops->group_name)) ? $manager_grops->group_name : '';

        // count total trader
        // ----------------------------------------------------------------
        $total_trader = ManagerUser::where('manager_id', $id)->where('users.type', 0)
            ->join('users', 'manager_users.user_id', '=', 'users.id')
            ->count();
        $auth_user = User::find(auth()->user()->id);
        $assign_btn = '';
        $ibs_btn = '';
        $trader_btn = '';
        if ($auth_user->hasDirectPermission('edit manager list')) {
            $ibs_btn = '<button type="button" data-loading="<div class=\'spinner-border spinner-border-sm\' role=\'status\'><span class=\'visually-hidden\'>Loading...</span></div>" class="btn btn-primary float-end btn-asigning-users mt-3">Save Change</button>';
            $trader_btn = ' <button type="button" data-loading="<div class=\'spinner-border spinner-border-sm\' role=\'status\'><span class=\'visually-hidden\'>Loading...</span></div>" class="btn btn-primary float-end btn-asigning-users mt-3">Save Change</button>';
        }
        // create manager tab
        $manager_tab = '';
        if ($manager_grops->group_type == 0) { // group_type = 0 (desk manager)
            $manager_tab .= '<li class="nav-item border-end-2 border-end-secondary">
                                <a data-id="' . $id . '" class="nav-link manager-tab manager-tab-fill text-truncate" id="manager-tab-fill-' . $id . '" data-bs-toggle="tab" href="#manager-fill-' . $id . '" role="tab" aria-controls="manager-fill" aria-selected="false" style="width:168px">' . __('page.account_manager') . '</a>
                            </li>';
        }
        // get total manager
        $total_manager = '';
        if ($manager_grops->group_type == 0) {
            $total_manager = '<tr>
                                <th>Total Manager</th>
                                <td>' . ManagerService::total_manager($id) . '</td>
                            </tr>';
        }
        // tab ib and trader
        $ib_trader_tab = $radio_filter = $btn_client_select_all = '';
        if ($auth_user->hasDirectPermission('edit manager list') && $manager_grops->group_type == 1) {
            $ib_trader_tab = '<li class="nav-item border-end-2 border-end-secondary">
                                <a data-id="' . $id . '" class="nav-link ib-tab ib-tab-fill" id="ib-tab-fill-' . $id . '" data-bs-toggle="tab" href="#ib-fill-' . $id . '" role="tab" aria-controls="ib-fill" aria-selected="false">' . __('page.ib`s') . '</a>
                            </li>
                            <li class="nav-item border-end-2 border-end-secondary">
                                <a data-id="' . $id . '" class="nav-link trader-tab trader-tab-fill" id="trader-tab-fill-' . $id . '" data-bs-toggle="tab" href="#trader-fill-' . $id . '" role="tab" aria-controls="trader-fill" aria-selected="false">' . __('page.trader') . '</a>
                            </li>';
        }
        // radio filter 
        if ($auth_user->hasDirectPermission('edit manager list') && $manager_grops->group_type == 1) {
            $radio_filter = '<div class="col-lg-3 rounded-end bg-light-danger p-1  demo-inline-spacing">
                                <div class="form-check form-check-success">
                                    <input type="radio" class="form-check-input unselected-users" id="all-users-' . $id . '" value="all" name="asigneable" />
                                    <label class="form-check-label" for="all-users-' . $id . '">' . __('page.all') . '</label>
                                </div>
                                <div class="form-check form-check-success">
                                    <input type="radio" class="form-check-input unselected-users" id="asigned-users-' . $id . '" value="asigned" name="asigneable" checked />
                                    <label class="form-check-label" for="asigned-users-' . $id . '">' . __('page.asigned') . '</label>
                                </div>
                                <div class="form-check form-check-success">
                                    <input type="radio" class="form-check-input unselected-users" id="unasinged-users-' . $id . '" value="unasigned" name="asigneable" />
                                    <label class="form-check-label" for="unasinged-users-' . $id . '">' . __('page.unasigned') . '</label>
                                </div>
                            </div>';
            $btn_client_select_all = '<div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="form-check form-check-success mt-3">
                                                        <input type="checkbox" class="form-check-input select-all-users" id="colorCheck6-' . $id . '" name="user" />
                                                        <label class="form-check-label" for="colorCheck6-' . $id . '">' . __('page.select_all') . '</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
            $assign_btn = ' <button type="button" data-loading="<div class=\'spinner-border spinner-border-sm\' role=\'status\'><span class=\'visually-hidden\'>Loading...</span></div>" class="btn btn-primary float-end btn-asigning-users">Save Change</button>';
        }
        $description = '<tr class="description" style="display:none">
            <td colspan="6">
                <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="rounded-0 w-75">
                                <table class="table table-responsive tbl-balance">
                                    <tr>
                                        <th class="border-bottom-0">' . __('page.total_ib') . '</th>
                                        <td>' . $total_ib . '</td>
                                    </tr>
                                    <tr>
                                        <th class="border-bottom-0">' . __('page.total_trader') . '</th>
                                        <td>' . $total_trader . '</td>
                                    </tr>
                                    ' . $total_manager . '
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justfy-content-between">
                            <div class="rounded-0 w-100">
                                <table class="table table-responsive tbl-trader-details users">
                                    <tr>
                                        <th class="border-bottom-0">' . __('page.group') . '</th>
                                        <td>' . $manager_group_name . '</td>
                                    </tr>
                                    <tr>
                                        <th class="border-bottom-0">' . __('page.country') . '</th>
                                        <td>' . ucwords(UserService::get_country($id)) . '</td>
                                    </tr>
                                    <tr>
                                        <th class="border-bottom-0">' . __('page.email') . '</th>
                                        <td>' . $manager_email . '</td>
                                    </tr>
                                    <tr>
                                        <th class="border-bottom-0">' . __('page.joining_date') . '</th>
                                        <td>' . $joining_date . '</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="rounded ms-1 dt-trader-img w-100" style="width:198px !important">
                                <div class="h-100">
                                    <img class="img img-fluid" src="' . asset('admin-assets/app-assets/images/avatars/avater-men.png') . ' "alt="avatar">
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
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs  mb-1 tab-inner-dark" id="myTab' . $id . '" role="tablist">
                                            <li class="nav-item">
                                                <a data-id="' . $id . '" class="nav-link active" id="trading_account-tab-fill-' . $id . '" data-bs-toggle="tab" href="#trading_account-fill-' . $id . '" role="tab" aria-controls="home-fill" aria-selected="true">' . __('page.assign_users') . '</a>
                                            </li>
                                            ' . $ib_trader_tab . '
                                            ' . $manager_tab . '
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="trading_account-fill-' . $id . '" role="tabpanel" aria-labelledby="home-tab-fill">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4>' . __('page.filter_user') . '</h4>
                                                    </div>
                                                    <hr>
                                                    <div class="card-body">
                                                        <form class="row inseide-filterform form-asigne-users">
                                                            <div class="col-lg-3 bg-light-info p-1 rounded-start demo-inline-spacing">
                                                                <div class="form-check form-check-success">
                                                                    <input type="checkbox" class="form-check-input trader-users" id="colorCheck3-' . $id . '" name="user_type" value="trader" checked />
                                                                    <label class="form-check-label" for="colorCheck3-' . $id . '">' . __('page.trader') . '</label>
                                                                </div>
                                                                <div class="form-check form-check-success">
                                                                    <input type="checkbox" class="form-check-input ib-users" id="colorCheck4-' . $id . '" name="user_type" value="ib" />
                                                                    <label class="form-check-label" for="colorCheck4-' . $id . '">' . __('page.ib`s') . '</label>
                                                                </div>
                                                            </div>
                                                            ' . $radio_filter . '
                                                            
                                                            <div class="col-lg-3 p-1">
                                                                <input type="text" placeholder="Name/Email" class="form-control form-input" name="email_phone">
                                                            </div>

                                                            <div class="col-lg-3 p-1">
                                                                <button type="button" class="btn btn-primary float-end btn-filter">' . __('page.filter') . '</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                ' . $btn_client_select_all . '
                                                <form method="post" action="' . route('admin.assigne-user-to-manager') . '" class="user-assign-form">
                                                    <input type="hidden" name="manager_id" value="' . $id . '">
                                                    <div class="table-responsive">
                                                        <table class="datatable-inner accessible-user table ' . table_color() . ' m-0"  style="margin:0px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>' . __('page.name') . '</th>
                                                                    <th>' . __('page.user_type') . '</th>
                                                                    <th>' . __('page.joining_date') . '</th>
                                                                    <th>' . __('page.email') . '</th>
                                                                    <th>' . __('page.select') . '</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                        ' . $assign_btn . '
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane" id="ib-fill-' . $id . '" role="tabpanel" aria-labelledby="ib-tab-fill">
                                                <form method="post" action="' . route('admin.assigne-user-to-manager') . '" class="user-assign-form">
                                                        <input type="hidden" name="manager_id" value="' . $id . '">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="form-check form-check-success mt-3">
                                                                        <input type="checkbox" class="form-check-input select-all-users" id="colorCheck7-' . $id . '" name="user[]" />
                                                                        <label class="form-check-label" for="colorCheck7-' . $id . '">' . __('page.select_all') . '</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9">
                                                                ' . $ibs_btn . '
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="datatable-inner ib table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>' . __('page.name') . '</th>
                                                                    <th>' . __('page.user_type') . '</th>
                                                                    <th>' . __('page.joining_date') . '</th>
                                                                    <th>' . __('page.email') . '</th>
                                                                    <th>' . __('page.actions') . '</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane" id="trader-fill-' . $id . '" role="tabpanel" aria-labelledby="trader-tab-fill">
                                                <form method="post" action="' . route('admin.assigne-user-to-manager') . '" class="user-assign-form">
                                                    <input type="hidden" name="manager_id" value="' . $id . '">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="form-check form-check-success mt-3">
                                                                        <input type="checkbox" class="form-check-input select-all-users" id="colorCheck8-' . $id . '" name="user[]" />
                                                                        <label class="form-check-label" for="colorCheck8-' . $id . '">' . __('page.select_all') . '</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9">
                                                                ' . $trader_btn . '
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="datatable-inner trader table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                                            <thead>
                                                                <tr>
                                                                <th>' . __('page.name') . '</th>
                                                                <th>' . __('page.user_type') . '</th>
                                                                <th>' . __('page.joining_date') . '</th>
                                                                <th>' . __('page.email') . '</th>
                                                                <th>' . __('page.actions') . '</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane" id="manager-fill-' . $id . '" role="tabpanel" aria-labelledby="manager-tab-fill">
                                                <div>
                                                    <form class="row px-1 mb-25 inseide-filterform manager-filter">
                                                        <div class="col-lg-3 col-12 p-1 bg-light-success rounded-start demo-inline-spacing">
                                                            <div class="form-check form-check-success">
                                                                <input type="radio" class="form-check-input unselected-users" id="all-managers-' . $id . '" value="all" name="asigneable" />
                                                                <label class="form-check-label" for="all-managers-' . $id . '">' . __('page.all') . '</label>
                                                            </div>
                                                            <div class="form-check form-check-success">
                                                                <input type="radio" class="form-check-input unselected-users" id="asigned-managers-' . $id . '" value="asigned" name="asigneable" checked />
                                                                <label class="form-check-label" for="asigned-managers-' . $id . '">' . __('page.asigned') . '</label>
                                                            </div>
                                                            <div class="form-check form-check-success">
                                                                <input type="radio" class="form-check-input unselected-users" id="unasinged-managers-' . $id . '" value="unasigned" name="asigneable" />
                                                                <label class="form-check-label" for="unasinged-managers-' . $id . '">' . __('page.unasigned') . '</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-9 col-12 p-1 rounded-end bg-light-success p-1">
                                                            <button type="button" class="btn btn-primary float-end btn-filter-manager specific-button">' . __('page.filter') . '</button>
                                                        </div>
                                                    </form>
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="form-check form-check-success mt-3">
                                                                        <input type="checkbox" class="form-check-input select-all-users" id="colorCheck90-' . $id . '" name="user[]" />
                                                                        <label class="form-check-label" for="colorCheck90-' . $id . '">' . __('page.select_all') . '</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-9">
                                                                ' . $trader_btn . '
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <form class="table-responsive user-assign-form" method="post" action="' . route('admin.assigne-user-to-manager') . '">
                                                        <input type="hidden" name="manager_id" value="' . $id . '">
                                                        <table class="datatable-inner manager table dt-inner-table-dark m-0" id="manager-tbl-' . $id . '"  style="margin:0px !important;">
                                                            <thead>
                                                                <tr>
                                                                <th>' . __('page.name') . '</th>
                                                                <th>' . __('page.user_type') . '</th>
                                                                <th>' . __('page.joining_date') . '</th>
                                                                <th>' . __('page.email') . '</th>
                                                                <th>' . __('page.actions') . '</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    }

    // get asignable users for managers
    // ----------------------------------------------------------
    public function get_all_manager_description_users(Request $request, $id)
    {
        try {
            $auth_user = User::find(auth()->user()->id);
            $columns = ['name', 'type', 'created_at', 'email', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];

            $result = User::where('type', 0);
            // Filter by trader
            if (isset($request->user_type) && $request->user_type === 'trader') {
                $result = User::where('type', 0);
            }
            // Filter by ib
            if (isset($request->user_type) && $request->user_type === 'ib') {
                $result = User::where('type', CombinedService::type());
                if (CombinedService::is_combined()) {
                    $result = $result->where('combine_access', 1);
                }
            }
            $result = $result->select('name', 'email', 'created_at', 'type', 'combine_access', 'id');
            // get manager group
            // ---------------------------------------------------------------------
            $manager_groups = Manager::where('user_id', $id)
                ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
                ->first();
            // Filter by asined
            if (
                strtolower($request->asigneable) === 'asigned' ||
                (strtolower(auth()->user()->type) === 'manager' &&
                    $auth_user->hasDirectPermission('edit manager list') == false) ||
                ($manager_groups->group_type == 0)
            ) {
                $managerUserIds = ManagerUser::where('manager_id', $id)->pluck('user_id');
                $result = $result->whereIn('id', $managerUserIds);
            }
            // filter by unasigned
            elseif (strtolower($request->asigneable) === 'unasigned') {
                $managerUserIds = ManagerUser::where('manager_id', $id)->pluck('user_id');
                $result = $result->whereNotIn('id', $managerUserIds);
            }
            // filter by email/name
            if ($request->email_phone != "") {
                $email_phone = $request->email_phone;
                $result = $result->where(function ($query) use ($email_phone) {
                    $query->where('users.email', 'LIKE', '%' . $email_phone)
                        ->orWhere('users.name', 'LIKE', '%' . $email_phone);
                });
            }
            $count = $result->count(); // <------count total rows
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            foreach ($result as $key => $value) {
                $user_under_managers = ManagerUser::where('manager_id', $id)->where('user_id', $value->id)->first();
                $is_checked = '';
                if (isset($user_under_managers->id)) {
                    $is_checked = 'checked';
                }
                // rename client type
                $client_type = '';
                if ($value->combine_access == 1 && $request->user_type === 'trader') {
                    $client_type = 'Trader';
                } elseif ($value->combine_access == 1 && $request->user_type === 'ib') {
                    $client_type = 'IB';
                } else {
                    $client_type = ucwords($value->type);
                }
                $data[] = [
                    "name"      =>  $value->name,
                    "type"      => $client_type,
                    "date"      => date('d F y, h:i A', strtotime($value->created_at)),
                    "email"     => $value->email,
                    "actions"   => '<div class="form-check form-check-success">
                                        <input type="checkbox" class="form-check-input assigneable-user" id="user-checkbox-' . $value->id . '-' . $id . '" name="asignable_users[]" ' . $is_checked . ' value="' . $value->id . '"/>
                                        <label class="form-check-label" for="user-checkbox-' . $value->id . '-' . $id . '">' . __('page.assign') . '</label>
                                    </div>
                                    <input type="checkbox" class="deselected-user visually-hidden" id="deselected-user-' . $value->id . '-' . $id . '" name="deselected_users[]" value="' . $value->id . '">
                                    ',
                ];
            }
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
                'data' => [],
            ]);
        }
    }
    // get asignable ib for managers
    // ----------------------------------------------------------
    public function get_all_manager_description_ib(Request $request, $id)
    {
        try {
            $columns = ['name', 'type', 'created_at', 'email', 'email'];
            // select type= 0 for trader
            $result = User::where('type', CombinedService::type());
            if (CombinedService::is_combined()) {
                $result = $result->where('combine_access', 1);
            }
            $result = $result->select('name', 'type', 'created_at', 'email', 'id', 'combine_access');
            // Filter by unselected
            $manager_users = ManagerUser::where('manager_id', $id)->pluck('user_id');
            $result = $result->whereNotIn('id', $manager_users);
            // filter by search
            if ($request->search != "") {
                $search = $request->search['value'];
                $result = $result->where(function ($query) use ($search) {
                    $query->where('users.email', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $search . '%');
                });
            }
            $count = $result->count('id');
            $result = $result->orderBy($columns[$request->order[0]['column']], $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            foreach ($result as $key => $value) {
                $user_under_managers = ManagerUser::where('manager_id', $id)->where('user_id', $value->id)->first();
                $is_checked = '';
                if (isset($user_under_managers->id)) {
                    $is_checked = 'checked';
                }
                $data[] = [
                    "name" =>  $value->name,
                    "user_type" => ($value->combine_access == 1 && CombinedService::is_combined()) ? 'IB' : strtoupper($value->type),
                    "joining_date" => date('d F y, h:i A', strtotime($value->created_at)),
                    "email" => $value->email,
                    "actions" => '<div class="form-check form-check-success">
                                            <input type="checkbox" class="form-check-input assigneable-user" id="user-checkbox-ib-' . $value->id . '-' . $id . '" name="asignable_users[]" ' . $is_checked . ' value="' . $value->id . '"/>
                                            <label class="form-check-label" for="user-checkbox-ib-' . $value->id . '-' . $id . '">' . __('page.assign') . '</label>
                                        </div>
                                        <input type="checkbox" class="deselected-user visually-hidden" id="deselected-user-' . $value->id . '-' . $id . '" name="deselected_users[]" value="' . $value->id . '">
                                    ',
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }
    // get asignable trader for managers
    // ----------------------------------------------------------
    public function get_all_manager_description_trader(Request $request, $id)
    {
        try {
            $order_col = ['name', 'type', 'users.created_at', 'email', 'created_at'];
            $result = User::where('type', 0);
            // Filter by unselected
            $manager_users = ManagerUser::where('manager_id', $id)->pluck('user_id');
            $result = $result->whereNotIn('id', $manager_users);
            // filter by search
            if ($request->search != "") {
                $search = $request->search['value'];
                $result = $result->where(function ($query) use ($search) {
                    $query->where('users.email', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $search . '%');
                });
            }
            $count = $result->count();
            $result = $result->orderBy($order_col[$request->order[0]['column']], $request->order[0]['dir'])
                ->skip($request->start)->take($request->length)->get();
            $data = array();
            foreach ($result as $value) {
                $user_under_managers = ManagerUser::where('manager_id', $id)->where('user_id', $value->id)->first();
                $is_checked = '';
                if (isset($user_under_managers->id)) {
                    $is_checked = 'checked';
                }
                $data[] = [
                    "name" =>  $value->name,
                    "user_type" => ucwords($value->type),
                    "joining_date" => date('d F y, h:i A', strtotime($value->created_at)),
                    "email" => $value->email,
                    "actions" => '<div class="form-check form-check-success">
                                            <input type="checkbox" class="form-check-input assigneable-user" id="user-checkbox-trader-' . $value->id . '-' . $id . '" name="asignable_users[]" ' . $is_checked . ' value="' . $value->id . '"/>
                                            <label class="form-check-label" for="user-checkbox-trader-' . $value->id . '-' . $id . '">' . __('page.assign') . '</label>
                                        </div>
                                        <input type="checkbox" class="deselected-user visually-hidden" id="deselected-user-' . $value->id . '-' . $id . '" name="deselected_users[]" value="' . $value->id . '">
                                    ',
                ];
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }

    // datatable cusom manager under desk manager
    public function get_all_manager_description_manager(Request $request)
    {
        try {
            $columns = ['name', 'type', 'created-at', 'email', 'email', 'action'];
            $result = User::whereIn('type', [5, 6, 7])->where('group_type', 1)
                ->join('managers', 'users.id', '=', 'managers.user_id')
                ->join('manager_groups', 'managers.group_id', '=', 'manager_groups.id')
                ->select('users.*');
            // Filter by asigned

            if ($request->asigneable === 'asigned') {
                $manager_users = ManagerUser::where('manager_id', $request->id)->pluck('user_id');
                $result = $result->whereIn('users.id', $manager_users);
            }
            // filter by unasigned
            elseif ($request->asigneable === 'unasigned') {
                $manager_users = ManagerUser::where('manager_id', $request->id)->pluck('user_id');
                $result = $result->whereNotIn('users.id', $manager_users);
            }
            // filter by search
            if ($request->search != "") {
                $search = $request->search['value'];
                $result = $result->where(function ($query) use ($search) {
                    $query->where('users.email', 'LIKE', '%' . $search . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $search . '%');
                });
            }
            $count = $result->count();
            $result = $result->orderBy($columns[$request->order[0]['column']], $request->order[0]['dir'])
                ->skip($request->start)->take($request->length)->get();
            $data = array();
            foreach ($result as $key => $value) {
                $user_under_managers = ManagerUser::where('manager_id', $request->id)->where('user_id', $value->id)->first();
                $is_checked = '';
                if (isset($user_under_managers->id)) {
                    $is_checked = 'checked';
                }
                $data[] = [
                    "name" =>  $value->name,
                    "user_type" => ucwords('Account Manager'),
                    "joining_date" => date('d F y, h:i A', strtotime($value->created_at)),
                    "email" => $value->email,
                    "actions" => '<div class="form-check form-check-success">
                                            <input type="checkbox" class="form-check-input assigneable-user" id="user-checkbox-trader-' . $value->id . '-' . $request->id . '" name="asignable_users[]" ' . $is_checked . ' value="' . $value->id . '"/>
                                            <label class="form-check-label" for="user-checkbox-trader-' . $value->id . '-' . $request->id . '">' . __('page.assign') . '</label>
                                        </div>
                                        <input type="checkbox" class="deselected-user visually-hidden" id="deselected-user-' . $value->id . '-' . $request->id . '" name="deselected_users[]" value="' . $value->id . '">
                                    ',
                ];
            }

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
                'data' => [],
            ]);
        }
    }

    // Assigning user to a manager
    // -----------------------------------------------------------------------
    public function assigen_user_to_manager(Request $request)
    {
        try {
            $delete = false;
            $validation_rules = [
                'manager_id' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please checke atleast one user'
                ]);
            } else {
                $properties = ManagerUser::where('manager_id', $request->manager_id)->get();
                if (isset($request->deselected_users)) {
                    // check request manager type
                    // requested manager is account manager
                    if (ManagerService::manager_type($request->manager_id) == 1) {
                        // check have any desk manager
                        // remove client from desk manager of requested account manager
                        $desk_manager_id = ManagerService::find_desk_manager($request->manager_id);
                        if ($desk_manager_id != "") {
                            $delete1 = ManagerUser::whereIn('user_id', $request->deselected_users)
                                ->whereIn('manager_id', $desk_manager_id)->delete();
                        }
                        // remove client from requested account manager
                        $delete = ManagerUser::whereIn('user_id', $request->deselected_users)
                            ->where('manager_id', $request->manager_id)->delete();
                    }
                    // requested manager is desk manager
                    else {
                        // get client under account manager
                        $removeable_client = ManagerUser::whereIn('manager_id', $request->deselected_users)
                            ->get()->pluck('user_id');
                        // delete client under desk manager
                        $delete = ManagerUser::whereIn('user_id', $removeable_client)
                            ->where('manager_id', $request->manager_id)->delete();
                        // delete manager under desk manager
                        $delete = ManagerUser::whereIn('user_id', $request->deselected_users)
                            ->where('manager_id', $request->manager_id)->delete();
                    }
                }
                // ********************************************************
                // asigne new user
                // ********************************************************
                if (isset($request->asignable_users)) {
                    for ($i = 0; $i < count($request->asignable_users); $i++) {
                        // ******************************************************************************************
                        // check requested manager is desk manager or account manager
                        // **************************************************************************************
                        if (ManagerService::manager_type($request->manager_id) == 1) { // 1 for account manager
                            $desk_manager_id = ManagerService::find_desk_manager($request->manager_id);
                            if ($desk_manager_id != "") {
                                foreach ($desk_manager_id as $key => $value) {
                                    $create = ManagerUser::updateOrCreate([
                                        'manager_id' => $value,
                                        'user_id' => $request->asignable_users[$i]
                                    ]);
                                }

                                // echo json_encode($create);
                            }
                            $create = ManagerUser::updateOrCreate([
                                'manager_id' => $request->manager_id,
                                'user_id' => $request->asignable_users[$i]
                            ]);
                        }
                        // if request manager is desk manager
                        else {
                            // asign all account manager under requested desk manger
                            $create = ManagerUser::updateOrCreate([
                                'manager_id' => $request->manager_id, //desk manager
                                'user_id' => $request->asignable_users[$i] // account manager
                            ]);
                            // get all client under account manager
                            // asigne all client from account manager
                            $acc_manager_client = ManagerUser::where('manager_id', $create->user_id)->get(); //user_id as account manager
                            foreach ($acc_manager_client as $value) {
                                $create = ManagerUser::updateOrCreate([
                                    'manager_id' => $request->manager_id, //desk manager
                                    'user_id' => $value->user_id, // client from account manager
                                ]);
                            }
                        }
                    }
                    if ($create) {
                        // store activity log---------------------
                        $ip_address = request()->ip();
                        $description = "The IP address $ip_address has been users assigned to manger";
                        $user = User::find($request->manager_id);

                        activity('users assigne to manager')
                            ->causedBy(auth()->user()->id)
                            ->withProperties($properties)
                            ->event('manager assigne')
                            ->performedOn($user)
                            ->log($description);
                        // <----------------------
                        return Response::json([
                            'status' => true,
                            'message' => 'User Assigned Successfully done'
                        ]);
                    } else {
                        return Response::json([
                            'status' => false,
                            'message' => 'Something went wrong! please try again later.'
                        ]);
                    }
                } else {
                    if ($delete) {
                        // store activity log---------------------
                        $ip_address = request()->ip();
                        $description = "The IP address $ip_address has been removed from manager";
                        $user = User::find($request->manager_id);

                        activity('users remove from manager')
                            ->causedBy(auth()->user()->id)
                            ->withProperties($properties)
                            ->event('manager assigne')
                            ->performedOn($user)
                            ->log($description);
                        // <----------------------
                        return Response::json([
                            'status' => true,
                            'message' => 'User Assigned Successfully updated'
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
}
