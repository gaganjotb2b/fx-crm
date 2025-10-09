<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\systems\AdminLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BlockedUserListController extends Controller
{
    public function __construct()
    {
        // system module control
        $this->middleware(AllFunctionService::access('blocked_users', 'admin'));
        $this->middleware(AllFunctionService::access('reports', 'admin'));
    }
    public function blockedUserList(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->blockedUserListReport($request);
        }
        return view('admins.reports.blocked-user-list');
    }
    public function blockedUserListReport($request)
    {
        try {

            $columns = ['name', 'email', 'type', 'active_status', 'id'];
            $orderby = $columns[$request->order[0]['column']];
            $result = User::whereNot('active_status', 1);

            //Filter By User Type
            if ($request->user_type != "") {
                $result = $result->where('type', $request->user_type);
            }
            //Filter By KYC Verification Status
            if ($request->verification_status === "0") {
                $result = $result->where('kyc_status', $request->verification_status);
            } elseif ($request->verification_status === "1") {
                $result = $result->where('kyc_status', $request->verification_status);
            } elseif ($request->verification_status === "2") {
                $result = $result->where('kyc_status', $request->verification_status);
            }
            //Filter By Trader Name / Email /Phone /Country
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
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
            //Filter By Manager Name / Email / Phone
            if ($request->manager_info != "") {
                $manager_id = User::select('id')->where('email', 'LIKE', '%' . $request->manager_info . '%')
                    ->orWhere('name', 'LIKE', '%' . $request->manager_info . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request->manager_info . '%')
                    ->first();
                if (isset($manager_id)) {
                    $user_id = ManagerUser::where('manager_id', $manager_id->id)->get()->pluck('user_id');
                    $result = $result->whereIn('users.id', $user_id);
                } else {
                    $result = $result->where('users.id', null);
                }
            }
            //Filter By Trading Account Number
            if ($request->trading_account != "") {
                $user_id = TradingAccount::select('user_id')
                    ->where('account_number', $request->trading_account)->first();
                $result = $result->whereIn('users.id', $user_id);
            }
            //Filter By Joining Date
            if ($request->from != "") {
                $result = $result->whereDate('users.created_at', '>=', $request->from);
            }
            if ($request->to != "") {
                $result = $result->whereDate('users.created_at', '<=', $request->to);
            }
            //Filter By Blocked Date
            if ($request->block_from != "") {
                $result = $result->whereDate('users.created_at', '>=', $request->block_from);
            }
            if ($request->block_to != "") {
                $result = $result->whereDate('users.created_at', '<=', $request->block_to);
            }

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->stat)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $user) {
                if ($user->type == "trader") {
                    $user_type = '<span class="bg-light-success badge badge-success">Trader</span>';
                } else {
                    $user_type = '<span class="bg-light-warning badge badge-warning">IB</span>';
                }
                $data[$i]['name'] = $user->name;
                $data[$i]['email'] = $user->email;
                $data[$i]['user_type'] = $user_type;
                $data[$i]['active_status'] = ($user->active_status != 1) ? "Blocked" : "Unblocked";
                $data[$i]['join_date'] = date('Y-M-d', strtotime($user->created_at));
                $data[$i]['block_date'] = date('Y-M-d', strtotime($user->updated_at));
                $data[$i]['action'] = '<a type="button" data-id="' . $user->id . '" class="badge bg-danger text-light" data-bs-toggle="modal" id="enable_btn">Unblock</a>';
                $i++;
            }

            return Response::json([
                "draw" => $request->draw,
                "recordsTotal" => $count,
                "recordsFiltered" => $count,
                "data" => $data,
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                "draw" => $request->draw,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ]);
        }
    }
    public function unblockUser($id)
    {
        $update = User::where('id', $id)->update([
            'active_status' => 1
        ]);
        if ($update) {
            $ip_address = request()->ip();
            $description = "The IP address $ip_address has been Unblocked an user";

            $client = User::find($id);
            // insert activity
            activity('User Unblock')
                ->causedBy(auth()->user()->id)
                ->withProperties(AdminLogService::admin_log())
                ->event('Unblock')
                ->performedOn($client)
                ->log($description);
            return ['success' => true];
        } else {
            return ['success' => false];
        }
    }
}
