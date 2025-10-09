<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\Manager;
use App\Models\ManagerUser;
use App\Models\UserDescription;
use App\Models\IB;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ManagerRightController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:manager right"]);
        $this->middleware(["role:manager settings"]);
    }
    
    //Get all manager
    // ---------------------------------------------------------------------------------------
    public function index(Request $request)
    {
        return view('admins.manager-settings.manager-right');
    }

    // manager description with right
    // -------------------------------------------------------------------------------------------
    public function manager_des_right(Request $request, $id)
    {
        // count total ib
        // -------------------------------------------------
        $ib_s = ManagerUser::where('manager_id',$id)->where('users.type',4)
        ->join('users','manager_users.user_id','=','users.id')
        ->get();
        $ib_id = [];
        $total_ib = 0;
        foreach ($ib_s as $key => $value) {
            array_push($ib_id,$value->user_id);
            $total_ib ++;
        }

        // manager info
        // --------------------------------------------------------------------
        $manager_info = User::find($id);
        $manager_email = (isset($manager_info->email))?$manager_info->email:'';
        $joining_date = (isset($manager_info->created_at))?date('d F y, h:i A', strtotime($manager_info->created_at)):'';

        // get manager group
        // ---------------------------------------------------------------------
        $manager_grops = Manager::where('user_id',$id)
        ->join('manager_groups','managers.group_id','=','manager_groups.id')
        ->first();
        $manager_group_name = (isset($manager_grops->group_name))?$manager_grops->group_name:'';

        // count total trader
        // ----------------------------------------------------------------
        $total_trader = ManagerUser::where('manager_id',$id)->where('users.type',0)
        ->join('users','manager_users.user_id','=','users.id')
        ->count();

        // get manager country
        // ---------------------------------------------------------------
        $manager_description = UserDescription::where('user_id',$id)
        ->join('countries','user_descriptions.country_id','=','countries.id')
        ->first();
        $manager_country = (isset($manager_description->name))?$manager_description->name:'';

        // Get roles permissions
        // --------------------------------------------------------------------------------------------------
        $manager = User::find($id);
        $manager_group = Manager::where('user_id',$id)
        ->join('manager_groups','managers.group_id','=','manager_groups.id')
        ->first();
       
        // ---------------------------------------------------------------------------------------------------
        
        $description = '<tr class="description" style="display:none">
            <td colspan="6">
                <div class="details-section-dark dt-details border-start-3 border-start-primary p-2">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="rounded-0 w-75">
                                <table class="table table-responsive tbl-balance">
                                    <tr>
                                        <th>Total IB</th>
                                        <td>'.$total_ib.'</td>
                                    </tr>
                                    <tr>
                                        <th>Total Trader</th>
                                        <td>'.$total_trader.'</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justfy-content-between">    
                            <div class="rounded-0 w-100">
                                <table class="table table-responsive tbl-trader-details users">
                                    <tr>
                                        <th>Group</th>
                                        <td>'.$manager_group_name.'</td>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <td>'.$manager_country.'</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>'.$manager_email.'</td>
                                    </tr>
                                    <tr>
                                        <th>Joining Date</th>
                                        <td>'.$joining_date.'</td>
                                    </tr>
                                </table>
                            </div> 
                            <div class="rounded ms-1 dt-trader-img w-100" style="width:198px !important">
                                <div class="h-100">
                                    <img class="img img-fluid" src="' . asset("admin-assets/app-assets/images/portrait/small/avatar-s-11.jpg") . ' "alt="avatar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <!-- Filled Tabs starts -->
                            <div class="col-xl-12 col-lg-12">
                                <form class="manager-right-form" action="'.route('admin.set-all-roles-permissions').'"  method="post" id="form-asign-role-perimission-'.$id.'">
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
