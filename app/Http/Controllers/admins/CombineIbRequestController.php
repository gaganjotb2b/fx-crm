<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\IB;
use App\Models\Log;
use App\Models\ManagerUser;
use App\Models\TradingAccount;
use App\Models\User;
use App\Models\Country;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\systems\VersionControllService;
class CombineIbRequestController extends Controller
{
    //
    public function index(Request $request)
    {
        $countries = Country::all();
        $crmVarsion = VersionControllService::check_version();
        return view('admins.ib-management.ib-request',['countries' => $countries,'varsion' => $crmVarsion]);
    }
    public function ib_request(Request $request)
    {
        try {
            $columns = ['name', 'email', 'phone', 'created_at', 'active_status', 'active_status'];
            $orderby = $columns[$request->order[0]['column']];

            $result = User::where('users.type', 0)->where('combine_access', 2);
            // Filter Start Here
            //Filter By KYC Status
            if ($request->status != '') {
                $result = $result->where('kyc_status', $request->status);
            }
            //Filter By Trader Name / Email /Phone /Country
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $user_id = User::select('countries.name')->where('users.type', 0)->where(function ($query) use ($trader_info) {
                    $query->where('users.name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $trader_info . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                $ib_id = IB::select('ib_id')->where('reference_id',$user_id)->get()->pluck('ib_id');
                $result = $result->whereIn('withdraws.user_id', $ib_id);
            }
            //Filter By IB Name / Email /Phone /Country
            if ($request->ib_info != "") {
                $ib = $request->ib_info;
                $user_id = User::select('countries.name')->where('users.type', 4)->where(function ($query) use ($ib) {
                    $query->where('users.name', 'LIKE', '%' . $ib . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $ib . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $ib . '%')
                        ->orWhere('countries.name', 'LIKE', '%' . $ib . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as client_id')->get()->pluck('client_id');
                $result = $result->whereIn('withdraws.user_id', $user_id);
            }

              //Filter By Country
              if ($request->country != "") {
                $user_country = $request->country;
                $user_id = User::select('countries.name')->where(function ($query) use ($user_country) {
                    $query->where('countries.name', 'LIKE', '%' . $user_country . '%');  
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }
            //Filter By Trading Account Number
            if ($request->trading_acc != '') {
                $user_id = TradingAccount::select('user_id')->where('account_number', $request->trading_acc)->pluck('user_id');
                $result = $result->whereIn('id', $user_id);
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

            //Filter By Date
            if ($request->date_from != "") {
                $result = $result->whereDate('users.created_at', '>=', $request->date_from);
            }

            if ($request->date_to != "") {
                $result = $result->whereDate('users.created_at', '<=', $request->date_to);
            }

            // Filter End Here

            $count = $result->count(); // <------count total rows
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;
            foreach ($result as $key => $value) {
                if (isset($value->kyc_status)) {
                    if ($value->kyc_status == 2) {
                        $check_uncheck = '<span class="text-warning">Pending</span>';
                        $kyc_color = 'text-warning';
                    } elseif ($value->kyc_status == 1) {
                        $check_uncheck = '<span class="text-success">Verified</span>';
                        $kyc_color = 'text-success';
                    } else {
                        $check_uncheck = '<span class="text-danger">Unverified</span>';
                        $kyc_color = 'text-danger';
                    }
                } else {
                    $check_uncheck = '<span class="text-danger">Unverified</span>';
                    $kyc_color = 'text-danger';
                }
                $data[$i]["name"]       = ucwords($value->name);
                $data[$i]["email"]      = $value->email;
                $data[$i]["phone"]      = ucwords($value->phone);
                $data[$i]["status"]     = $check_uncheck;
                $data[$i]["joining_date"]     = date('d F y, h:i A', strtotime($value->created_at));

                $data[$i]["actions"]    = '<div class="d-flex justify-content-between">
                                            <a href="#" class="more-actions dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i data-feather="more-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item text-success btn-ib-request-approve" href="#" data-id="' . $value->id . '">Approve</a>
                                                <a class="dropdown-item text-danger btn-ib-request-decline" href="#" data-id="' . $value->id . '">Decline</a>
                                            </div>
                                        </div>';
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
    // approve ib request 
    // conver this user to IB
    public function approve(Request $request)
    {
        switch ($request->op) {
            case 'mail':
                $logPass = Log::where('user_id', decrypt($request->id))->first();
                $mail_status = EmailService::send_email('convert-to-ib', [
                    'user_id' => decrypt($request->id),
                    'password' => decrypt($logPass->password),
                    'transaction_password' => decrypt($logPass->transaction_password),
                ]);
                if ($mail_status) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Mail successfully send to user',
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Connection failed! mail could not send in this time, try again later!',
                ]);
                break;

            default:
                $update = User::where('id', $request->id)->update([
                    'combine_access' => 1,
                ]);
                if ($update) {
                    //<---client email as user id
                    $user = User::find(auth()->user()->id);
                    activity("IB registration request approve")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("IB request approved")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been " .  "IB registration request approved");
                    // end activity log----------------->>
                    return Response::json([
                        'status' => true,
                        'message' => 'IB Registration Request successfully approve',
                        'id' => encrypt($request->id)
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'IB Registration Request could not approve, network error!'
                ]);
                break;
        }
    }
    // ib request decline
    public function decline(Request $request)
    {
        switch ($request->op) {
            case 'mail':
                $logPass = Log::where('user_id', decrypt($request->id))->first();
                $mail_status = EmailService::send_email('decline-ib-request', [
                    'user_id' => decrypt($request->id),
                    'password' => decrypt($logPass->password),
                    'transaction_password' => decrypt($logPass->transaction_password),
                ]);
                if ($mail_status) {
                    //<---client email as user id
                    $user = User::find(auth()->user()->id);
                    activity("IB registration request declined")
                        ->causedBy(auth()->user()->id)
                        ->withProperties($request->all())
                        ->event("IB request declined")
                        ->performedOn($user)
                        ->log("The IP address " . request()->ip() . " has been " .  "IB registration request declined");
                    // end activity log----------------->>
                    return Response::json([
                        'status' => true,
                        'message' => 'Mail successfully send to user',
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'Connection failed! mail could not send in this time, try again later!',
                ]);
                break;

            default:
                $update = User::where('id', $request->id)->update([
                    'combine_access' => 0,
                ]);
                if ($update) {
                    return Response::json([
                        'status' => true,
                        'message' => 'IB Registration Request successfully approve',
                        'id' => encrypt($request->id)
                    ]);
                }
                return Response::json([
                    'status' => false,
                    'message' => 'IB Registration Request could not approve, network error!'
                ]);
                break;
        }
    }
}
