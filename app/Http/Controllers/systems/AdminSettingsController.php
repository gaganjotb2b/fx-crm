<?php

namespace App\Http\Controllers\systems;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminSettingsController extends Controller
{
    public function index(Request $request)
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
        return view('systems.configurations.admin-settings', ['admin_groups' => $admin_groups, 'group_list' => $group_list]);
    }

    // get all admins

    public function get_all_admins(Request $request)
    {
        try {
            $result = User::where('type', 2)->count();
            $recordsTotal = $result;
            $recordsFiltered = $result;

            $limit = '';
            $sortBy = $_REQUEST['order'][0]['dir'];
            $order_a =  $_REQUEST['order'];
            $order = $order_a[0]['dir'];
            $oc = $order_a[0]['column'];
            $ocd = $_REQUEST['columns'][$oc]['data'];
            $start = $request->input('start');
            $length = $request->input('length');
            $search  = $request->input('search');

            // select type= 0 for trader
            $result = User::whereIn('type', [1, 2]);
            // Filter by finance
            if ($search != "") {
                $result = $result->where('name', 'LIKE', '%' . $search['value'] . '%');
            }
            $result = $result->take($length)->skip($start)->get();
            $data = array();
            $i = 0;

            foreach ($result as $key => $value) {
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
                $edit_action = '<a href="#"  class="more-actions"><i data-feather="more-vertical"></i></a> <a data-id="' . $value->id . '" href="javascript:;" class="role-edit-modal asign-permission" data-bs-toggle="modal" data-bs-target="#addRoleModal"><i data-feather="edit"></i></a>';

                $data[$i]["name"]         = $value->name;
                $data[$i]["group"]         = ucwords((isset($admin_des->group_name)) ? $admin_des->group_name : '');
                $data[$i]["country"]      = ucwords((isset($admin_des->name)) ? $admin_des->name : '');
                $data[$i]["status"]       = $status;
                $data[$i]["actions"]      = $edit_action;
                $i++;
            }

            return Response::json([
                'draw' => $request->draw, 
                'recordsTotal' => $recordsTotal, 
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;\
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
        // admin group
        // -------------------------------------------------
        $admins = Admin::where('admins.user_id', $id)
            ->join('admin_groups', 'admins.group_id', '=', 'admin_groups.id')
            ->join('user_descriptions', 'admins.user_id', '=', 'user_descriptions.user_id')
            ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
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
                                        <td>' . $admins->user_email . '</td>
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
    }
}
