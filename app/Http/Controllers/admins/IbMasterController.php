<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDescription;
use App\Models\Country;
use App\Models\Category;
use App\Models\KycVerification;
use Illuminate\Http\Request;
use App\Models\IB;
use App\Models\Deposit;
use App\Models\IbGroup;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\Withdraw;
use App\Services\AllFunctionService;
use App\Services\BalanceService;
use App\Services\CombinedService;
use App\Services\IbService;
use Illuminate\Support\Facades\Response;
use App\Services\systems\VersionControllService;
class IbMasterController extends Controller
{
    public function __construct()
    {
        // system module control
        $this->middleware(AllFunctionService::access('ib_management', 'admin'));
        $this->middleware(AllFunctionService::access('master_ib', 'admin'));
    }
    public function masterIbReport()
    {
        $ib_group = IbGroup::select()->get();
        $categories =  Category::where('client_type', 'ib')->select()->get();
        $crmVarsion = VersionControllService::check_version();
        $countries = Country::all();
        return view('admins.ib-management.master_ib_report', ['ib_groups' => $ib_group,'categories' => $categories,'varsion' => $crmVarsion,'countries' => $countries]);
    }
    // get master ib data 
    public function getMaterIbReport(Request $request)
    {
        try {
            $columns = ['users.name', 'users.email', 'users.phone', 'countries.name', 'users.ib_group_id', 'users.created_at', 'users.created_at'];
            $orderby = $columns[$request->order[0]['column']];

            // find all ib from ib table
            $reference_id = IB::where('type', CombinedService::type());
            // check crm is bombined
            if (CombinedService::is_combined()) {
                $reference_id = $reference_id->where('users.combine_access', 1);
            }
            $reference_id = $reference_id->select('reference_id')
                ->join('users', 'ib.reference_id', '=', 'users.id')->get()->pluck('reference_id');

            // find master ib and its details
            $result = User::whereNotIn('users.id', $reference_id)
                ->select(
                    'users.id',
                    'users.kyc_status',
                    'users.name as master_ib_name',
                    'email',
                    'phone',
                    'ib_group_id',
                    'country_id',
                    'countries.name as country_name',
                    'users.created_at as joining_date',
                )
                ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                ->leftJoin('countries', 'user_descriptions.country_id', 'countries.id');
            if (CombinedService::is_combined()) {
                $result = $result->where('combine_access', 1);
            }
            $result = $result->where('users.type', CombinedService::type());

            // **********************************************************************
            // $result = User::whereNotIn('users.id',$reference_id)

            // filter start
            if ($request->status != "") {
                $status = $request->status;
                $result = $result->where(function ($query) use ($status) {
                    $query->where('users.kyc_status', $status);
                });
            }

            //Filter by IBG Group
            if ($request->ibg_group != "") {
                $ib_group_id  = IbGroup::select('id')->where('group_name', $request->ibg_group)->first();
                $result = $result->where('ib_group_id', $ib_group_id['id']);
            }

            //Filter by ib name,email,phone,country
            if ($request->ib_info != "") {
                $ib_info = $request->ib_info;
                // return $ib_info;
                $result = $result->where(function ($query) use ($ib_info) {
                    $query->where('users.name', 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $ib_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $ib_info . '%');
                });
            }

            //Filter by country
            if ($request->country != "") {
                $ib_country = $request->country;
                // return $ib_country;
                $result = $result->where(function ($query) use ($ib_country) {
                    $query->where('countries.name', 'LIKE', '%' . $ib_country . '%') ;
                      
                });
            }

            //Filter by trader name,email,phone,country
            if ($request->trader_info != "") {
                $trader = $request->trader_info;
                $users_id = User::select('id')
                    ->where(function ($query) use ($trader) {
                        $query->where('users.name', $trader)
                            ->orWhere('users.email', $trader)
                            ->orWhere('users.phone', $trader);
                    })->first();
                $trader_id = IB::whereIn('ib.reference_id', $users_id)->pluck('ib_id');

                $result = $result->whereIn('users.id', $trader_id);
            }

            //Filter by trading account number
            if ($request->trading_acc != "") {
                $users_id = TradingAccount::select('user_id')
                    ->where('account_number', $request->trading_acc)->first();
                $trader_id = IB::whereIn('ib.reference_id', $users_id)->pluck('ib_id');
                $result = $result->whereIn('users.id', $trader_id);
            }

            //Filter by account manager deskl manager
            if ($request->manager != "") {
                $manager = $request->manager;
                $manager_id = User::select('id')
                    ->where(function ($query) use ($manager) {
                        $query->where('name', $manager)
                            ->orWhere('email', $manager)
                            ->orWhere('phone', $manager);
                    })->get()->pluck('id');
                $users_id = ManagerUser::select('user_id')->where('manager_id', $manager_id)->get()->pluck('user_id');
                // return $users_id;
                $result = $result->whereIn('users.id', $users_id);
            }

            //Filter by category

            if ($request->category != "") {
                $catg_id = Category::select('id')->where('name', $request->category)->first();
                $result = $result->where('users.category_id', $catg_id);
            }
            if ($request->date_from != "") {
                $result = $result->whereDate('users.created_at', '>=', $request->date_from);
            }
            // filter by date to
            if ($request->date_to != "") {
                $result = $result->whereDate('users.created_at', '<=', $request->date_to);
            }
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = [];
            foreach ($result as $value) {
                
                $ib_group  = IbGroup::select('group_name')->where('id', $value->ib_group_id)->first();
                $data[] = [
                    "name"   => '<a href="#" data-ib_id="' . $value->id . '" class="dt-description d-flex justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span><span style="padding-top:3px;margin-left:3px;">' . $value->master_ib_name . '</span></a>',
                    "email"  => $value->email,
                    "phone"  => $value->phone,
                    "country"=> $value->country_name,
                    "ibg" => ($ib_group)?$ib_group->group_name:'',
                    "joining_date" => date('d M, Y H:i:s A', strtotime($value->joining_date)),
                    "action"=> '<a href="/admin/master-ib-details/' . $value->id . '" target="_blank" type="button"  class="btn btn-primary">View Details</a>',
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
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    // master ib report description
    public function getMaterIbReportDescription(Request $request, $ib_id)
    {
        // find ib
        $ib = User::find($ib_id);
        $ib = User::where('users.id', $ib_id)
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')->select(
                'users.name',
                'users.phone',
                'countries.name as country',
                'users.id',
                'user_descriptions.gender'
            )->first();

        if (isset($ib->gender)) {
            $avatar = ($ib->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png'; //<----avatar url
        }
        $country = ($ib->country != "") ? $ib->country : 'N/A';

        $description = '<tr class="description" style="display:none;">
            <td colspan="8">
                <div class="details-section-dark border-start-3 border-start-primary p-2 bg-light-secondary">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="rounded-0 w-75">
                                <div class="p-0">    
                                    <table class="table table-responsive tbl-balance">
                                        <tbody>
                                            <tr>
                                                <th class="border-end-2 top-table-border-bottom-3 border-bottom-0">Current Balance</th>
                                                <td class="top-table-border-bottom-3 border-bottom-0">' . BalanceService::ib_balance($ib_id) . '</td>
                                            </tr>
                                            <tr>
                                                <th class="border-end-2 top-table-border-bottom-3 border-bottom-0">Total IBs</th>
                                                <td class="top-table-border-bottom-3 border-bottom-0">' . AllFunctionService::total_sub_ib($ib_id) . '</td>
                                            </tr>
                                            <tr>
                                                <th class="border-end-2 border-bottom-0">Affiliate Traders</th>
                                                <td class="border-bottom-0">' . AllFunctionService::total_trader($ib_id) . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 d-flex justfy-content-between">    
                            <div class="rounded-0 w-100">
                                <table class="table table-responsive tbl-trader-details">
                                    <tbody>
                                        <tr>
                                            <th class="border-end-2 border-bottom-3 border-bottom-0">Name</th>
                                            <td class="border-bottom-3 border-bottom-0">' . $ib->name . '</td>
                                        </tr>
                                        <tr>
                                            <th class="border-end-2 border-bottom-3 border-bottom-0">Phone</th>
                                            <td class="border-bottom-3 border-bottom-0">' . $ib->phone . '</td>
                                        </tr>
                                        <tr>
                                            <th class="border-end-2 border-bottom-0">Country</th>
                                            <td class="border-bottom-0 border-bottom-0">' . $country . '</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> 
                            <div class="rounded ms-1 dt-trader-img">
                                <div class="h-100">
                                <img class="img img-fluid" src="' . asset("admin-assets/app-assets/images/avatars/$avatar") . ' "alt="avatar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <!-- Filled Tabs starts -->
                            <div class="col-xl-12 col-lg-12">
                                <div class=" p-0">
                                    <div class="p-0">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs  mb-1 tab-inner-dark" id="myTab' . $ib->id . '" role="tablist">
                                            <li class="nav-item">
                                                <a data-ib_id="' . $ib->id . '" class="nav-link total-ib-tab-fill active" id="total-ib-tab-fill-' . $ib->id . '" data-bs-toggle="tab" href="#total-ib-fill-' . $ib->id . '" role="tab" aria-controls="total-ib-fill" aria-selected="false">Total IB</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card p-0 bg-transparent">
                        <div class="card-body p-0 bg-transparent">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="table-responsive">
                                    <table class="datatable-inner sub-ib-list table dt-inner-table-dark m-0"  style="margin:0px !important;">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Trader</th>
                                                <th>Sponser</th>
                                            </tr>
                                        </thead>
                                    </table>
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
            'description' => $description,
        ];
        return Response::json($data);
    }

    // master ib description inner datatable fetch data 
    public function getMaterIbReportDescriptionInner(Request $request, $ib_id)
    {
        try {
            $data = array();
            $i = 0;

            // find master ib and its details
            $result = IB::where('users.type', CombinedService::type());
            // check if crm is combined
            if (CombinedService::is_combined()) {
                $result = $result->where('combine_access', 1);
            }
            $result = $result->select('ib.reference_id', 'users.*')
                ->join('users', 'ib.reference_id', '=', 'users.id');
            // get all fub ibs
            $subibs = AllFunctionService::my_sub_ib_id($ib_id);
            $result = $result->where(function ($query) use ($subibs, $ib_id) {
                $query->whereIn('ib_id', $subibs)
                    ->orWhere('ib_id', $ib_id);
            });
            $count = $result->count();
            $result = $result->orderby('users.id', 'DESC')->skip($request->start)->take($request->length)->get();

            // if reference id not empty
            foreach ($result as $row) {
                $affiliate_by = AllFunctionService::user_email(IbService::instant_parent($row->id));
                $data[$i]["name"]     = $row->name;
                $data[$i]["email"]    = $row->email;
                $data[$i]["trader"]  = AllFunctionService::total_trader($row->id);
                $data[$i]["sponsor"] = $affiliate_by;
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
    // total sub ib 
    public function totalSubIb()
    {
    }
}
