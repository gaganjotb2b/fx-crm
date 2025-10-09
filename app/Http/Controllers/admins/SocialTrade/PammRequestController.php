<?php

namespace App\Http\Controllers\admins\SocialTrade;

use App\Http\Controllers\Controller;
use App\Models\PammRequest;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\CopyApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PammRequestController extends Controller
{
    public function __construct()
    {
        // system module control
        $this->middleware(AllFunctionService::access('pamm_request', 'admin'));
        $this->middleware(AllFunctionService::access('social_trade', 'admin'));
    }
    public function pamm_request(Request $request)
    {
        return view('admins.socialTrade.pamm-request');
    }

    public function pamm_request_dt(Request $request)
    {
        try {
            $from = $request->input('from');
            $to = $request->input('to');
            $min = $request->input('min');
            $max = $request->input('max');
            $status = $request->input('status');
            $info = $request->input('info');

            $columns = ['name', 'email', 'account', 'min_deposit', 'max_deposit', 'status', 'created_at', 'share_profit', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];

            $result = PammRequest::select()->where('status', 'P');

            if ($status != "") {
                $result = $result->where('status', '=', $status);
            }

            if ($info != "") {
                $result = $result->where('name', 'LIKE', '%' . $info . '%')->orwhere('email', 'LIKE', '%' . $info . '%');
            }

            if ($min != "") {
                $result = $result->where("min_deposit", '>=', $min);
            }
            if ($max != "") {
                $result = $result->where("min_deposit", '<=', $max);
            }

            if ($from != "") {
                $result = $result->whereDate("created_at", '>=', $from);
            }

            if ($to != "") {
                $result = $result->whereDate("created_at", '<=', $to);
            }


            /*<-------filter search script End here------------->*/
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = [];
            $i = 0;

            foreach ($result as $user) {

                if ($user->status == 'P') {
                    $status = 'Pending';
                } elseif ($user->status == 'A') {
                    $status = 'Approved';
                } elseif ($user->status == 'D') {
                    $status = 'Declined';
                }

                // if ($user->status == 'A') {
                //     $data[$i]['name']   = '<a href="#" data-id=' . $user->id . '  class="dt-description text-color justify-content-start"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' .  ucwords($user->name) . '</span></a>';
                // } else {
                //     $data[$i]['name'] = ucwords($user->name);
                // }

                $data[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'account' => $user->account,
                    'min_deposit' => '$' . $user->min_deposit,
                    'max_deposit' => '$' . $user->max_deposit,
                    'status' => $status,
                    'request_date' => date('d M y, h:i A', strtotime($user->created_at)),
                    'profit_share' => $user->share_profit . '%',
                ];
                if ($user->status == 'A') {
                    $data[$i]['action'] = '---';
                } else {
                    $data[$i]['action'] = '<button   data-type="button"  class="btn btn-primary waves-effect waves-float waves-light"  data-loading="processing..." data-id="' . $user->id . '"     onclick="approve_request(this)">Approve</button>';
                }

                $i++;
            }

            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    public function pamm_request_approve(Request $request)
    {
        $copy_mt = new CopyApiService();
        $id = $request->id;
        $pamm_request = PammRequest::select()->where('id', $id)->first();
        if ($pamm_request->account) {
            if (Response::json(['success' => true])) {
                if ($id) {
                    $req_data = [
                        'command' => 'Custom',
                        'data' => [
                            "sql" => "INSERT INTO copy_users (name, email, username, account, min_deposit, max_deposit, share_profit, created_at) VALUES('$pamm_request->name', '$pamm_request->email', '$pamm_request->username', '$pamm_request->account', '$pamm_request->min_deposit', '$pamm_request->max_deposit', '$pamm_request->share_profit', '$pamm_request->created_at')"
                        ]
                    ];
                    $result = json_decode($copy_mt->apiCall($req_data));
                    //Check master exits
                    $req_data_x = [
                        'command' => 'Custom',
                        'data' => [
                            "sql" => "SELECT COUNT(*) AS check_master FROM copy_masters WHERE master = '$pamm_request->account'"
                        ]
                    ];

                    $result_me = json_decode($copy_mt->apiCall($req_data_x));
                    if ($result_me->data[0]->check_master < 1) {
                        $req_data2 = [
                            'command' => 'Custom',
                            'data' => [
                                "sql" => "INSERT INTO copy_masters (master , created_at) VALUES('$pamm_request->account', '$pamm_request->created_at')"
                            ]
                        ];
                        $result_master = json_decode($copy_mt->apiCall($req_data2));
                    }

                    //admin pc track scirpt
                    $ipAddress = $request->ip();
                    $userAgent = $request->header('User-Agent');

                    $operatingSystems = [
                        'Windows\sNT\s(\d+\.\d+)' => 'Windows',
                        'Macintosh|Mac OS X\s(\d+\.\d+)' => 'macOS',
                        'iOS\s(\d+\.\d+)' => 'iOS',
                        'Android\s(\d+\.\d+)' => 'Android',
                        'Windows\sPhone\sOS\s(\d+\.\d+)' => 'Windows Phone',
                        'BlackBerry\s(\d+\.\d+)' => 'BlackBerry',
                        'Linux\s(.+)' => 'Linux',
                        'FreeBSD\s(\d+\.\d+)' => 'FreeBSD',
                        'OpenBSD\s(\d+\.\d+)' => 'OpenBSD',
                        'NetBSD\s(\d+\.\d+)' => 'NetBSD',
                    ];

                    $operatingSystem = 'Unknown';
                    foreach ($operatingSystems as $pattern => $name) {
                        if (preg_match('/' . $pattern . '/', $userAgent, $matches)) {
                            $version = isset($matches[1]) ? $matches[1] : '';
                            $operatingSystem = $name . ' ' . $version;
                            break;
                        }
                    }
                    $jsonData = ['ip' => $ipAddress, 'wname' => $operatingSystem];
                    $pamm = PammRequest::where('id', $id)->update([
                        'status' => 'A',
                        'approved_by' => auth()->user()->id,
                        'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                        'admin_log' => json_encode($jsonData)
                    ]);
                    if ($pamm) {
                        return Response::json(['success' => true, 'message' => '<span style="color:green;">This User PAMM profile has been created successfully!']);
                    }
                }
            }
        };
    }

    public function PammApproveDescription(Request $request)
    {
        $pamm = PammRequest::find($request->id);
        //===========================Admin Information condition=================================////
        $innerTH1 = "";
        $innerTD1 = "";
        $approved_by = "";
        if ($pamm->status === 'A') {
            $approved_by = ($pamm->status == 'A') ? "Approved By:" : "Declined By:";
            $admin_info = User::select('name', 'email')->where('id', $pamm->approved_by)->first();
            $admin_name = isset($admin_info->name) ? $admin_info->name : '---';
            $admin_email = isset($admin_info->email) ? $admin_info->email : '---';
            $admin_json_data = json_decode($pamm->admin_log);
            $ip = isset($admin_json_data->ip) ? $admin_json_data->ip : '---';
            $wname = isset($admin_json_data->wname) ? $admin_json_data->wname : '---';
            $action_date = isset($pamm->approved_date) ? date('d M Y, h:i A', strtotime($pamm->approved_date)) : '---';

            $innerTH1 .= '
                <th>ADMIN Name</th>
                <th>Admin Email</th>
                <th>IP</th>
                <th>Device</th>
                <th>Action Date</th>';
            $innerTD1 .= '
                <td>' . $admin_name . '</td>
                <td>' . $admin_email . '</td>
                <td>' . $ip . '</td>
                <td>' . $wname . '</td>
                <td>' . $action_date . '</td>';
        }
        //===========================Admin Information condition End=================================////

        $description = '
        <tr class="description" style="display:none">
            <td colspan="12">
                <div class="details-section-dark border-start-3 border-start-primary p-2 " style="display: flow-root;">
                    <span class="details-text">
                          $approved_by
                    </span>
                    <table id="deposit-details' . $request->id . '" class="deposit-details table dt-inner-table-dark">
                        <thead>
                            <tr>
                             ' . $innerTH1 . ' 
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                ' . $innerTD1 . ' 
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>';

        $data = [
            'status' => true,
            'description' => $description,
        ];
        return Response::json($data);
    }
}
