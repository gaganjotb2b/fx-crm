<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\KycApproveRequest;
use App\Mail\KycDeclineRequest;
use App\Mail\UserKycUpdate;
use App\Models\admin\SystemConfig;
use App\Models\Country;
use App\Models\KycIdType;
use App\Models\KycVerification;
use App\Models\ManagerUser;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use App\Services\CombinedService;
use App\Services\EmailService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\TradingAccount;
use App\Services\KycService;
use App\Services\MailNotificationService;
use App\Services\systems\AdminLogService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class KycRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:kyc request"]);
        $this->middleware(["role:kyc management"]);
        // system module control
        $this->middleware(AllFunctionService::access('kyc_management', 'admin'));
        $this->middleware(AllFunctionService::access('kyc_request', 'admin'));
    }
    public function kycRequest(Request $request)
    {
        $op = $request->input('op');
        if ($op == "data_table") {
            return $this->kycRequestDT($request);
        }
        $countryList = Country::all();
        $kycId = KycIdType::all();
        return view('admins.reports.kyc-request', compact(['countryList', 'kycId']));
    }

    public function kycRequestDT($request)
    {
        try {

            $from = $request->input('from');
            $to = $request->input('to');

            $columns = ['name', 'type', 'doc_type', 'exp_date', 'issue_date', 'status', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];

            $result = KycVerification::select(
                'kyc_verifications.*',
                'kyc_verifications.id',
                'users.type',
                'users.name',
                'users.combine_access'
            )
                ->join('users', 'kyc_verifications.user_id', '=', 'users.id')
                ->where('users.type', '!=', 2)
                ->where('users.type', '!=', 3)
                ->where('users.type', '!=', 5);

            /*<-------filter search script start here------------->*/
            //manager filter script
            if ($request->manager_email != "") {
                $manager_id = User::select('id')
                    ->where('email', '=', $request->manager_email)
                    ->orWhere('phone', 'like', '%' . $request->manager_email . '%')
                    ->orWhere('name', 'like', '%' . $request->manager_email . '%')->first();
                if (isset($manager_id)) {
                    $user_id = ManagerUser::select('user_id')->where('manager_id', $manager_id->id)->get();
                    $filter_id = [];
                    foreach ($user_id as $id) {
                        array_push($filter_id, $id->user_id);
                    }
                    $result = $result->whereIn('users.id', $filter_id);
                } else {
                    $result = $result->where('users.id', null);
                }
            }
            // filter by client type
            // trader/IB
            if ($request->client_type != "") {
                if ($request->client_type == 'ib') {
                    $result = $result->where('type', CombinedService::type());
                    // check crm is combined
                    if (CombinedService::is_combined()) {
                        $result = $result->where('users.combine_access', 1);
                    }
                } elseif ($request->client_type == 'trader') {
                    $result = $result->where('type', '=', 0);
                }
            }

            if ($request->type != "") {
                $matchDoc = KycIdType::where('id_type', $request->type)->pluck('id');
                $result = $result->whereIn('kyc_verifications.doc_type', $matchDoc);
            }

            if ($request->status != "") {
                $result = $result->where('status', '=', $request->status);
            }

            if ($request->info != "") {
                $trader_info = $request->info;
                $result = $result->where(function ($query) use ($trader_info) {
                    $query->where('name', 'LIKE', '%' . $trader_info . '%')
                        ->orwhere('email', 'LIKE', '%' . $trader_info . '%')
                        ->orwhere('phone', 'LIKE', '%' . $trader_info . '%');
                });
            }
            //Country

            if ($request->country_info != "") {
                $country = $request->country_info;
                $countryId = UserDescription::where('country_id', $country)->pluck('user_id');
                $result = $result->whereIn('users.id', $countryId);
            }

            //trading account number
            if ($request->trading_number != '') {
                $findNumber = TradingAccount::where('account_number', $request->trading_number)->pluck('user_id')->toArray();
                $result = $result->whereIn('users.id', $findNumber);
            }
            //Date Filter
            if ($from != "") {
                $result = $result->where(function ($query) use ($from) {

                    $query->whereDate("kyc_verifications.created_at", '>=', $from)->orWhereDate("kyc_verifications.issue_date", '>=', $from)->orWhereDate("kyc_verifications.exp_date", '>=', $from);
                });
            }
            if ($to != "") {
                $result = $result->where(function ($query) use ($to) {

                    $query->whereDate("kyc_verifications.created_at", '<=', $to)->orWhereDate("kyc_verifications.issue_date", '<=', $to)->orWhereDate("kyc_verifications.exp_date", '<=', $to);
                });
            }


            /*<-------filter search script End here------------->*/

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();


            $data = array();
            $i = 0;

            foreach ($result as $user) {
                $auth_user = User::find(auth()->user()->id);
                $edit_button = '';
                if ($auth_user->hasDirectPermission('edit kyc request')) {
                    $edit_button = '<a href="#" data-bs-toggle="modal" data-bs-target="#updateProfileModal"  class="user-id" data-id="' . $user->id . '" onclick="update_profile(this)">
                                        <i data-feather="edit" ></i>
                                    </a>
                                    <a href="#" class="kyc-modal user-id" data-id="' . $user->id . '"><i data-feather="eye" ></i></a>';
                } else {
                    $edit_button = '<span class="text-danger">No Permission to Access</span>';
                }

                $document_name = KycIdType::select('id_type')->where('id', $user->doc_type)->first();
                if ($user->type == '4') {
                    $client_type = "IB";
                }
                if ($user->type == '0') {
                    if (CombinedService::is_combined()) {
                        if ($user->combine_access == '1') {
                            $client_type = "<span class='badge bg-warning'>IB</span>";
                        } else {
                            $client_type = "<span class='badge bg-success'>TRADER</span>";
                        }
                    } else {
                        $client_type = "TRADER";
                    }
                }

                if ($user->status == '0') {
                    $status = '<span class="badge bg-warning">Pending</span>';
                } elseif ($user->status == '1') {
                    $status = '<span class="badge bg-success">Verified</span>';
                } elseif ($user->status == '2') {
                    $status = '<span class="badge bg-danger">Declined</span>';
                }

                if (strtolower($document_name->id_type) == 'adhar card') {
                    $issue_date = "---";
                    $expire_date = "---";
                } else {
                    $issue_date = isset($user->issue_date) ? date('d F y', strtotime($user->issue_date)) : '---';
                    $expire_date = isset($user->exp_date) ? date('d F y', strtotime($user->exp_date)) : '---';
                }

                // Comment this code for disable expanded
                // if ($user->status == 1 || $user->status == 2) {
                //     $data[$i]['client_name']   = '<a href="#" data-id=' . $user->table_id . '  class="dt-description text-color justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' .  ucwords($user->name) . '</span></a>';
                // } else {
                //     $data[$i]['client_name']   = ucwords($user->name);
                // }
                $data[$i]['client_name']   = ucwords($user->name);

                $data[$i]['client_type']   = $client_type;
                $data[$i]['document_type'] = ucwords($document_name->id_type);
                $data[$i]['issue_date']    = $issue_date;
                $data[$i]['expire_date']   = $expire_date;
                $data[$i]['status']        = $status;
                $data[$i]['date']          = date('d F y', strtotime($user->created_at));
                $data[$i]['action']        =  $edit_button;

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
    //-------------update form value show-------------------------
    public function kycRequestProfile(Request $request, $id)
    {
        $user_info = KycVerification::where('kyc_verifications.id', $id)
            ->join('users', 'kyc_verifications.user_id', '=', 'users.id')
            ->join('user_descriptions', 'kyc_verifications.user_id', '=', 'user_descriptions.user_id')->first();

        $user_country = Country::select('name')->where('id', $user_info->country_id)->first();
        $date_of_birth = date('Y-m-d', strtotime($user_info->date_of_birth));
        $issue_date = date('Y-m-d', strtotime($user_info->issue_date));
        $exp_date = date('Y-m-d', strtotime($user_info->exp_date));


        $countries = Country::all();
        $country_options = '';
        foreach ($countries as $key => $value) {
            $selected = ($value->id == $user_info->country_id) ? 'selected' : "";
            $country_options .= '<option value="' . $value->id . '" ' . $selected . '>' . $value->name . '</option>';
        }

        $data = [
            'name' => $user_info->name,
            'exp_date' => $exp_date,
            'dob' => $date_of_birth,
            'issue_date' => $issue_date,
            'user_country' =>  $user_country,
            'user_info' => $user_info,
            'country_option' => $country_options
        ];
        return response()->json($data);
    }
    //-------------------KYC profile Update---------------------->
    public function kycProfileUpdate(Request $request)
    {
        $user_id = $request->data_id;
        $user_name = $request->input('name');
        $user_state = $request->input('state');
        $country_id = $request->input('country');
        $user_zip = $request->input('zip');
        $user_city = $request->input('city');
        $user_dob = $request->input('dob');
        $user_address = $request->input('address');
        $user_issue_date = $request->input('issue_date');
        $user_expire_date = $request->input('expire_date');


        $validation_rules = [
            'name' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip' => 'required',
            'city' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'expire_date' => 'required',
            'issue_date' => 'required'
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json(['success' => false, 'errors' => $validator->errors()]);
        } else {
            //user description update
            $user_data = User::where('id', $user_id)->update(['name' => $user_name]);
            $KycVerification = KycVerification::where('user_id', $user_id)
                ->update([
                    'issue_date' => $user_issue_date,
                    'exp_date' => $user_expire_date
                ]);
            $Description = UserDescription::where('user_id', $user_id)
                ->update([
                    'state' => $user_state,
                    'city' => $user_city,
                    'address' => $user_address,
                    'zip_code' => $user_zip,
                    'date_of_birth' => $user_dob,
                    'gender' => $request->input('gender')
                ]);


            //user country update
            $user_des = UserDescription::where('user_id', $request->data_id)->first();
            $user_des->country_id = $country_id;
            $user_des->save();

            //<-----------Mail Script-------------------->
            $user = User::find($user_id);
            $support_email = SystemConfig::select('support_email')->first();

            $support_email = ($support_email) ? $support_email->support_email : default_support_email();
            $email_data = [
                'name'              => ($user) ? $user->name : config('app.name') . ' User',
                'account_email'     => ($user) ? $user->email : '',
                'admin'             => Auth::user()->name,
                'login_url'         => route('login'),
                'support_email'     => $support_email,
            ];

            Mail::to($user->email)->send(new UserKycUpdate($email_data));
            if ($user_data && $KycVerification && $Description) {
                return Response::json([
                    'success' => true,
                    'message' => 'Profile Update Successful',
                    'success_title' => 'Profile Updated'
                ]);
            } else {
                return Response::json([
                    'success' => false,
                    'message' => 'Profile Update Failed, Please try again later!',
                    'success_title' => 'Failed To Update!'
                ]);
            }
        }
    }
    // public function kycStatus(Request $request)
    // {
    //     // return $request->status;
    //     $userid = $request->userid;
    //     $status = ($request->status === 'true') ? 1 : 0;

    //     $update = User::where('id', $userid)->update(['kyc_status' => $status]);
    //     if ($status == 1) {
    //         return Response::json([
    //             'success' => true,
    //             'messages' => "User KYC Authorized",
    //         ]);
    //     } else {
    //         return Response::json([
    //             'success' => false,
    //             'messages' => "User KYC Unauthorized",
    //         ]);
    //     }
    // }
    public function kycStatus(Request $request)
    {
        try {
            // return $request->status;
            $userid = $request->userid;
            // return $userid;
            $status = ($request->status === 'true') ? 1 : 0;
            // return $status;
            $getUserId= KycVerification::where('id',$userid)->select('user_id')->first();
            // return $getUserId;
            $update = User::whereIn('id', $getUserId)->update(['users.kyc_status' => $status]);
            if ($status == 1) {
                return Response::json([
                    'success' => true,
                    'messages' => "User KYC Authorized",
                ]);
            } else {
                return Response::json([
                    'success' => false,
                    'messages' => "User KYC Unauthorized",
                ]);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'success' => false,
                'messages' => "User KYC Unauthorized",
            ]);
        }

    }
    // kyc view modal data
    public function kycRequestDescription(Request $request)
    {
        try {
            $kyc_verification = KycVerification::select()->where('kyc_verifications.id', $request->id)
                ->join('users', 'kyc_verifications.user_id', '=', 'users.id')
                ->leftJoin('user_descriptions', 'users.id', 'user_descriptions.user_id')
                ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                ->join('kyc_id_type', 'kyc_verifications.doc_type', '=', 'kyc_id_type.id')
                ->select(
                    'user_descriptions.date_of_birth',
                    'kyc_verifications.issue_date',
                    'kyc_verifications.exp_date',
                    'kyc_verifications.perpose',
                    'kyc_verifications.id_number',
                    'kyc_id_type.id_type',
                    'kyc_id_type.group',
                    'kyc_verifications.document_name',
                    'countries.name as country',
                    'users.name',
                    'users.email',
                    'users.kyc_status',
                    'user_descriptions.zip_code',
                    'user_descriptions.address',
                    'user_descriptions.gender',
                    'kyc_verifications.status',
                    'city',
                    'state',
                    'phone',
                    'users.id'
                )
                ->first();
            // return $kyc_verification;
            $document_images = json_decode(isset($kyc_verification->document_name) ? $kyc_verification->document_name : '');
            $status = '';
            if (isset($kyc_verification->status) && $kyc_verification->status == 0) {
                $status = '<span class="text-warning">Pending</span>';
            }
            if (isset($kyc_verification->status) && $kyc_verification->status == 1) {
                $status = '<span class="text-success">Verified</span>';
            }

            if (isset($kyc_verification->status) && $kyc_verification->status == 2) {
                $status = '<span class="text-danger">Decliend</span>';
            }

            $file_front_part = (asset('Uploads/kyc/' . $document_images->front_part)) ?? '';
            $file_back_part = (asset('Uploads/kyc/' . $document_images->back_part)) ?? '';
            
            // view file from contabo
            // $document_front_part = $document_images->front_part ?? '';
            // $document_back_part = $document_images->back_part ?? '';

            // $file_front_part = FileApiService::$public_url . $document_front_part;
            // $file_back_part = FileApiService::$public_url . $document_back_part;

            // $front_extension = pathinfo($file_front_part, PATHINFO_EXTENSION) ?: (Str::contains($file_front_part, '.') ? Str::afterLast($file_front_part, '.') : null);
            // $back_extension = pathinfo($file_back_part, PATHINFO_EXTENSION) ?: (Str::contains($file_back_part, '.') ? Str::afterLast($file_back_part, '.') : null);
            return response()->json([
                'dob' => date('Y-m-d', strtotime($kyc_verification->date_of_birth)),
                'issue_date' => date('Y-m-d', strtotime($kyc_verification->issue_date)),
                'exp_date' => date('Y-m-d', strtotime($kyc_verification->exp_date)),
                'document_name' => $kyc_verification->id_type,
                'group_name' => $kyc_verification->group,
                'status' => $status,
                'country' =>  $kyc_verification->country,
                'name' => $kyc_verification->name,
                'email' => $kyc_verification->email,
                'phone' => $kyc_verification->phone,
                'city' => $kyc_verification->city,
                'state' => $kyc_verification->state,
                'address' => $kyc_verification->address,
                'zip_code' => $kyc_verification->zip_code,
                'user_kyc_sts' => $kyc_verification->status,
                'gender' => $kyc_verification->gender,
                'kyc_status' => $kyc_verification->kyc_status,
                'id_number' => $kyc_verification->id_number,
                'front_part' => $file_front_part,
                'back_part' => $file_back_part,
                // 'front_part' => $file_front_part,
                // 'back_part' => $file_back_part,
                // 'front_part_file_type' => $front_extension,
                // 'back_part_file_type' => $back_extension,
                'front_part_file_type' => null,
                'back_part_file_type' => null,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function kycApproveRequest(Request $request)
    {
        try {
            $kyc = KycVerification::where('kyc_verifications.id', $request->id)
                ->join('kyc_id_type', 'kyc_verifications.doc_type', '=', 'kyc_id_type.id')
                ->first();

            // update kyc table
            $update = KycVerification::where('id', $request->id)->update([
                'status' => 1,
                'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                'approved_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log()
            ]);
            // update user table when both of documnet (address nad ID prooved are appoved)
            if (KycService::has_approved_doc($kyc->user_id)) {
                User::where('id', $kyc->user_id)->update([
                    'kyc_status' => 1
                ]);
            }

            // insert activity-----------------
            $user = User::find($kyc->user_id);
            activity("KYC Approved")
                ->causedBy(auth()->user()->id)
                ->withProperties($kyc)
                ->event("KYC Approved")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been approved KYC request");
            // end activity log-----------------
            $mail_status = EmailService::send_email('kyc-approve-request', [
                'user_id' => $kyc->user_id,
                'status' => 'Approved',
                'document_name' => ucwords($kyc->id_type)
            ]);
            if ($update) {
                // send mail to all admin and account manager
                MailNotificationService::admin_notification([
                    'name' => $user->name,
                    'email' => $user->email,
                    'client_type' => ($user->type === 'ib') ? 'ib' : 'trader',
                    'type' => 'kyc_decline'
                ]);
                if ($mail_status) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Mail successfully sent for Approved request',
                        'success_title' => 'Approve request'
                    ]);
                }
                return Response::json([
                    'status' => true,
                    'message' => 'Mail sending failed, Please try again later!',
                    'success_title' => 'Approve request'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong, Please try again later!',
                'success_title' => 'Approve request'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
                'success_title' => 'Approve request'
            ]);
        }
    }

    public function kycDeclineRequest(Request $request)
    {
        try {
            $kyc = KycVerification::where('kyc_verifications.id', $request->id)
                ->join('kyc_id_type', 'kyc_verifications.doc_type', '=', 'kyc_id_type.id')
                ->first();
            $user = User::find($kyc->user_id);
            // update kyc table
            $update = KycVerification::where('id', $request->id)->update([
                'status' => 2,
                'note' => $request->note,
                'approved_date' => date('Y-m-d h:i:s', strtotime(now())),
                'approved_by' => auth()->user()->id,
                'admin_log' => AdminLogService::admin_log()
            ]);
            // udpate user table
            User::where('id', $user->id)->update([
                'kyc_status' => ($user->kyc_status == 1) ? 1 : 0
            ]);
            // insert activity-----------------
            $user = User::find($kyc->user_id);
            activity("KYC declined")
                ->causedBy(auth()->user()->id)
                ->withProperties($kyc)
                ->event("KYC declined")
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been declined KYC request");
            // end activity log-----------------
            // sending mail
            $mail_status = EmailService::send_email('kyc-decline', [
                'user_id' => $user->id,
                'document_type' => ucwords($kyc->id_type),
                'message_custom' => $request->note
            ]);
            if ($update) {
                // send mail to all admin and account manager
                MailNotificationService::admin_notification([
                    'name' => $user->name,
                    'email' => $user->email,
                    'client_type' => ($user->type === 'ib') ? 'ib' : 'trader',
                    'type' => 'kyc_decline'
                ]);
                if ($mail_status) {
                    return Response::json([
                        'status' => true,
                        'message' => 'Mail successfully sent for Declined request',
                    ]);
                }
                return Response::json([
                    'status' => true,
                    'message' => 'Mail sending failed, Please try again later!',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong, Please try again later!',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'success' => false,
                'message' => 'Got a server error!',
                'success_title' => 'Declined request'
            ]);
        }
    }

    public function kycDesUpdate(Request $request)
    {
        try {
            $validation_rules = [
                'name' => 'required',
                'phone' => 'required',
                'document_name' => 'required',
                'issue_date' => (strtolower($request->document_name) === 'adhar card') ? 'nullable' : 'required',
                'expire_date' => (strtolower($request->document_name) === 'adhar card') ? 'nullable' : 'required',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'zip_code' => 'required',
                'date_birth' => 'required',
                'gender' => 'required',
            ];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json(['success' => false, 'errors' => $validator->errors()]);
            }
            $kycs = KycVerification::select('doc_type', 'user_id')->where('id', $request->kyc_id)->first();

            // update kyc verification table
            $update = KycVerification::where('id', $request->kyc_id)
                ->update([
                    'issue_date' => $request->issue_date,
                    'exp_date' => $request->expire_date,
                    'doc_type' => $request->document_name,
                    'id_number' => $request->id_number
                ]);

            // update user table
            $update = User::where('id', $kycs->user_id)->update([
                'name' => $request->name,
                'phone' => $request->phone
            ]);

            // udpate user descriptions
            $update = UserDescription::updateOrCreate(
                [
                    'user_id' => $kycs->user_id
                ],
                [
                    'state' => $request->state,
                    'city' => $request->city,
                    'address' => $request->address,
                    'zip_code' => $request->zip_code,
                    'date_of_birth' => $request->date_birth,
                    'gender' => $request->gender
                ]
            );

            $user = User::find($kycs->user_id);
            // / insert activity-----------------
            activity("Edit KYC account")
                ->causedBy(auth()->user()->id)
                ->withProperties($kycs)
                ->event('Edit KYC account')
                ->performedOn($user)
                ->log("The IP address " . request()->ip() . " has been Edit KYC account");
            // end activity log-----------------
            if ($update) {
                return Response::json([
                    'success' => true,
                    'message' => 'Successfully Updated'
                ]);
            }
            return Response::json([
                'success' => false,
                'message' => 'Failed To Updated',
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    public function kycApproveDescription(Request $request)
    {
        $kyc = KycVerification::find($request->id);

        //===========================Admin Information condition=================================////
        $innerTH1 = "";
        $innerTD1 = "";
        $approved_by = "";
        if ($kyc->status === 1 || $kyc->status === 2) {
            $approved_by = ($kyc->status == 1) ? "Approved By:" : "Declined By:";
            $admin_info = User::select('name', 'email')->where('id', $kyc->approved_by)->first();
            $admin_name = isset($admin_info->name) ? $admin_info->name : '---';
            $admin_email = isset($admin_info->email) ? $admin_info->email : '---';
            $admin_json_data = json_decode($kyc->admin_log);
            $ip = isset($admin_json_data->ip) ? $admin_json_data->ip : '---';
            $wname = isset($admin_json_data->wname) ? $admin_json_data->wname : '---';
            $action_date = isset($kyc->approved_date) ? date('d M Y, h:i A', strtotime($kyc->approved_date)) : '---';

            $innerTH1 .= <<<EOT
                <th>ADMIN Name</th>
                <th>Admin Email</th>
                <th>IP</th>
                <th>Device</th>
                <th>Action Date</th>
            EOT;
            $innerTD1 .= <<<EOT
                <td>{$admin_name}</td>
                <td>{$admin_email}</td>
                <td>{$ip}</td>
                <td>{$wname}</td>
                <td>{$action_date}</td>

            EOT;
        }
        //===========================Admin Information condition End=================================////

        $description = <<<EOT
        <tr class="description" style="display:none">
            <td colspan="8">
                <div class="details-section-dark border-start-3 border-start-primary p-2 " style="display: flow-root;">
                    <span class="details-text">
                          $approved_by
                    </span>
                    <table id="deposit-details' . $request->id . '" class="deposit-details table dt-inner-table-dark">
                        <thead>
                            <tr>
                             $innerTH1
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                $innerTD1
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        EOT;

        $data = [
            'status' => true,
            'description' => $description,
        ];
        return Response::json($data);
    }
}
