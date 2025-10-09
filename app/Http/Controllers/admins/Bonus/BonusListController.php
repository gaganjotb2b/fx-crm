<?php

namespace App\Http\Controllers\admins\Bonus;

use App\Http\Controllers\Controller;
use App\Models\BonusCountry;
use App\Models\BonusFor;
use App\Models\BonusGroup;
use App\Models\BonusPackage;
use App\Models\BonusUser;
use App\Models\ManagerUser;
use App\Services\systems\DateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BonusListController extends Controller
{
    public function BonusList()
    {
        return view('admins.bonus.bonus-list');
    }
    public function BonusListProcess(Request $request)
    {

        try {
            $columns = ['pkg_name', 'bonus_amount', 'start_date', 'end_date', 'active_status', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];

            $result = BonusPackage::select();
            // check if login is manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
                $result = $result->whereIn('created_by', $users_id);
            }
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $value) {
                if ($value->active_status == 0) {
                    $status = '<span class="badge badge-danger bg-danger">Deactive</span>';
                } else {
                    $status = '<span class="badge badge-success bg-success">Active</span>';
                }
                $data[] = [
                    'bonus_name' => '<a href="#" data-id=' . $value->id . ' class="dt-description  justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' .  ucwords($value->pkg_name) . '</span></a>',
                    'price' => $value->bonus_amount,
                    'bonus_category' => ucwords(str_replace('_', ' ', $value->bonus_on)),
                    'start_end' => 'Start: ' . date('d M y', strtotime($value->start_date)) . '<br/>End: ' . date('d M y', strtotime($value->end_date)),
                    'status' => $status,
                    'create_date' => date('d M y', strtotime($value->created_at)),
                    'action' => '<button type="button" class="btn btn-sm btn-success btn-edit-package" data-package_id="' . $value->id . '" data-bonus_for="' . $value->bonus_for . '"><i data-feather="edit" ></i></button>
                                <button type="button" class="btn btn-sm btn-warning"><i data-feather="eye" ></i></button>',
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

    public function BonusListDetails(Request $request, $id)
    {
        try {
            $bonus = BonusPackage::where('bonus_packages.id', $id)->first();
            $client_group = '';
            $bonus_group = BonusGroup::where('bonus_package', $id)->count();
            if ($bonus_group != 0) {
                $client_group = 'All Countryies';
                $groups = '<button type="bottom" class="btn btn-sm btn-success btn-view-group" data-package_id="' . $id . '">Groups <span class="badge badge-c-danger bg-body">' . $bonus_group . '</span></button>';
                $client_group = '<tr>
                                    <th style="border-left: 3px solid #7367f0 !important;" class="border-end-2 w-50 border-0">Trading group</th>
                                    <td class="border-end-0 w-50"> ' . $groups . '</td>
                                </tr>';
            }
            // count countrys
            $country = '';
            if ($bonus->is_global == 1) {
                $country = 'All Countryies';
            } else {
                $count_country = BonusCountry::where('bonus_package', $id)->count();
                $country = '<button type="bottom" class="btn btn-sm btn-success btn-view-country" data-package_id="' . $id . '">Countries <span class="badge badge-c-danger bg-body">' . $count_country . '</span></button>';
            }
            // count clients
            $clients = '';
            if ($bonus->bonus_for === 'specific_client') {
                $count_client = BonusFor::where('bonus_package', $id)->count();
                $clients = '<button type="bottom" class="btn btn-sm btn-success btn-view-client" data-package_id="' . $id . '">Clients <span class="badge badge-c-danger bg-body">' . $count_client . '</span></button>';
            } else {
                $clients = ucwords(str_replace('_', ' ', $bonus->bonus_for));
            }
            // expire after
            $expire_after = $bonus->expire_after . ' ' . $bonus->expire_type;
            // bonus type 
            $bonus_type = str_replace('_', ' ', $bonus->bonus_type);
            $min_depost = '';
            if ($bonus->bonus_type === 'specific_deposit') {
                $min_depost .= '<tr>
                                    <th style="border-left: 3px solid #7367f0 !important;" class="border-end-2 w-50 border-0">Min Deposit</th>
                                    <td class="border-end-0 w-50"> &dollar;' . ucwords($bonus->min_deposit) . '</td>
                                </tr>
                                <tr>
                                    <th style="border-left: 3px solid #7367f0 !important;" class="border-end-2 w-50 border-0">Max Deposit</th>
                                    <td class="border-end-0 w-50"> &dollar;' . ucwords($bonus->max_deposit) . '</td>
                                </tr>
                                ';
            }
            $description = '<tr class="description" style="display:none;">
                                <td colspan="8">
                                    <div class="details-section-dark border-start-3 border-start-primary p-2 bg-light-secondary">
                                        <div class="row">
                                            <div class="">
                                                <div class="rounded-0 w-70">
                                                    <div class="card-body">    
                                                        <table class="table table-responsive tbl-balance">
                                                            <tr>
                                                                <th class="border-bottom-3 border-bottom-success" colspan="2"><h3>Bonus Description :</h3></th>
                                                            </tr>
                                                            <tr>
                                                                <th style="border-left: 3px solid #7367f0 !important;" class="border-end-2 w-50 border-0">Bonus Type</th>
                                                                <td class="border-end-0 w-50">' . ucwords($bonus_type) . '</td>
                                                            </tr>
                                                            ' . $min_depost . $client_group . '
                                                            <tr>
                                                                <th style="border-left: 3px solid #7367f0 !important;" class="border-end-2 w-50 border-0">Clients</th>
                                                                <td class="border-end-0 w-50"> ' . $clients . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th style="border-left: 3px solid #7367f0 !important;" class="border-end-2 w-50 border-0">Country</th>
                                                                <td class="border-end-0 w-50"> ' . $country . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th style="border-left: 3px solid #7367f0 !important;" class="border-end-2 w-50 border-0">Credit Expire after</th>
                                                                <td class="border-end-0 w-50"> ' . ucwords($expire_after) . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th style="border-left: 3px solid #7367f0 !important;" class="border-end-2 w-50 border-0">Credit Type</th>
                                                                <td class="border-end-0 w-50"> ' . ucwords($bonus->credit_type) . '</td>
                                                            </tr>
                                                            <tr>
                                                                <th style="border-left: 3px solid #7367f0 !important;" class="border-end-2 w-50 border-0">Withdraw Requirement</th>
                                                                <td class="border-end-0 w-50"> Lot: ' . ucwords($bonus->min_lot) . '</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
            return Response::json([
                'status' => true,
                'description' => $description
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => true,
                'description' => ''
            ]);
        }
    }
    // get bonus groups
    public function get_bonus_group(Request $request)
    {
        try {
            $result = BonusGroup::where('bonus_package', $request->package_id)
                ->join('client_groups', 'bonus_groups.group_id', '=', 'client_groups.id')
                ->select('client_groups.group_id as group_name')->get();
            return $result;
        } catch (\Throwable $th) {
            // throw $th;
            return ([]);
        }
    }
    // get bonus countries
    public function get_bonus_country(Request $request)
    {
        try {
            $result = BonusCountry::where('bonus_package', $request->package_id)
                ->join('countries', 'bonus_countries.country', '=', 'countries.id')
                ->select('countries.name as country')->get();
            return $result;
        } catch (\Throwable $th) {
            // throw $th;
            return ([]);
        }
    }
    // get bonus clients
    public function get_bonus_client(Request $request)
    {
        try {
            $result = BonusFor::where('bonus_package', $request->package_id)
                ->join('users', 'bonus_for.user_id', '=', 'users.id')
                ->select('users.email')->get();
            return $result;
        } catch (\Throwable $th) {
            throw $th;
            return ([]);
        }
    }
    // get modal data form bonus edit
    public function get_bonus_data(Request $request)
    {
        try {
            $result = BonusPackage::find($request->id);
            $bonus_country = BonusCountry::where('bonus_package', $result->id)
                ->leftJoin('countries', 'bonus_countries.country', '=', 'countries.id')
                ->get();
            $bonus_group = BonusGroup::where('bonus_package', $result->id)
                ->select('bonus_groups.*', 'client_groups.id as group_id', 'client_groups.group_id as group_name')
                ->leftJoin('client_groups', 'bonus_groups.group_id', '=', 'client_groups.id')
                ->get();
            $bonus_clients = BonusFor::where('bonus_package', $result->id)
                ->select('bonus_for.*', 'users.name', 'users.email')
                ->leftJoin('users', 'bonus_for.user_id', '=', 'users.id')->get();

            return Response::json([
                'bonus' => $result,
                'bonus_country' => ($result->is_global == 0) ? $bonus_country : [],
                'groups' => $bonus_group,
                'clients' => $bonus_clients,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            // return
        }
    }
}
