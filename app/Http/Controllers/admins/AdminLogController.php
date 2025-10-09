<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class AdminLogController extends Controller
{
    public function log_report()
    {
        return view('admins.admin-log-report');
    }

    // logs datatable ajax proccess
    // fetch data for log report datatable
    public function log_dt_fetch_data()
    {
        $result = User::select()
            ->join('admins', 'users.id', '=', 'admins.user_id')
            ->join('roles', 'admins.role_id', '=', 'roles.id')
            ->count();

        $recordsTotal = $result;
        $recordsFiltered = $result;

        $limit = '';
        $sortBy = $_REQUEST['order'][0]['dir'];
        $order_a =  $_REQUEST['order'];
        $order = $order_a[0]['dir'];
        $oc = $order_a[0]['column'];
        $ocd = $_REQUEST['columns'][$oc]['data'];

        if (isset($_REQUEST['start']) && $_REQUEST['length'] != -1) {
            $limit = " ORDER BY copy_rebalances.$ocd $sortBy LIMIT " . intval($_REQUEST['start']) . ", " . intval($_REQUEST['length']);
        }

        $result = User::select()
            ->join('admins', 'users.id', '=', 'admins.user_id')
            ->join('roles', 'admins.role_id', '=', 'roles.id')
            ->get();
        $data = array();
        $i = 0;

        foreach ($result as $key => $value) {
            $data[$i]["date"]         = '<a href="#" data-id=' . $value->id . ' class="dt-description d-flex justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' . date("d F Y,  h.i A", strtotime($value->created_at)) . '</span></a>';
            $data[$i]["user_type"]     = ucwords($value->type);
            $data[$i]["role"]         = ucwords($value->role_name);
            $data[$i]["name"]     = $value->name;
            $data[$i]["email"] = $value->email;
            $i++;
        }

        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered);
        $output['data'] = $data;
        // echo json_encode($output);
        return Response::json($output);
    }

    // log datatable description
    public function log_dt_description()
    {
        $description = '<tr class="description" style="display:none">
            <td colspan="6">
                <div class="details-section-dark border-start-3 border-start-primary p-2">
                    <span class="details-text">
                        Details
                    </span>
                    <table class="datatable-inner table dt-inner-table-dark">
                        <thead>
                            <tr>
                                <th>IP Address</th>
                                <th>Browser</th>
                                <th>Country</th>
                                <th>Region</th>
                                <th>City</th>
                                <th>Logout at</th>
                            </tr>
                        </thead>
                        
                    </table>
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

    public function deposit_dt_fetch_data(Request $request, $id)
    {
        $result = User::where('users.id', $id)->select()->join('logs', 'users.id', '=', 'logs.user_id')->count();

        $recordsTotal = $result;
        $recordsFiltered = $result;

        $limit      = '';
        $sortBy     = $_REQUEST['order'][0]['dir'];
        $order_a    =  $_REQUEST['order'];
        $order      = $order_a[0]['dir'];
        $oc         = $order_a[0]['column'];
        $ocd        = $_REQUEST['columns'][$oc]['data'];

        if (isset($_REQUEST['start']) && $_REQUEST['length'] != -1) {
            $limit = " ORDER BY copy_rebalances.$ocd $sortBy LIMIT " . intval($_REQUEST['start']) . ", " . intval($_REQUEST['length']);
        }

        $result = User::where('users.id', $id)->select()->join('logs', 'users.id', '=', 'logs.user_id')->get();


        // $locationData = \Location::get('123.253.65.230');
        // return Response::json($locationData);
        $data = array();
        $i = 0;

        foreach ($result as $key => $value) {
            $userIp = $value->IP;
            $locationData = \Location::get($userIp);
            $country = $locationData->countryName;
            $region = $locationData->regionName;
            $city   = $locationData->cityName;

            $data[$i]["ip_address"] = $userIp;
            $data[$i]["browser"]     = ucwords($value->browser);
            $data[$i]["country"]     = $country;
            $data[$i]["region"]     = $region;
            $data[$i]["city"]       = $city;
            $data[$i]["logout_at"]     = date("d F Y,  h.i A", strtotime($value->updated_at));
            $i++;
        }

        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered);
        $output['data'] = $data;
        // echo json_encode($output);
        return Response::json($output);
    }
    
}
