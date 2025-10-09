<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Mail\IbVerificationApproveRequest;
use App\Mail\IbVerificationDeclineRequest;
use App\Models\admin\SystemConfig;
use App\Models\IbGroup;
use App\Models\KycIdType;
use App\Models\Country;
use App\Models\IB;
use App\Models\KycVerification;
use App\Models\ManagerUser;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use App\Services\CombinedService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use App\Services\systems\VersionControllService;

class IBVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:ib verification request"]);
        $this->middleware(["role:ib management"]);
        // system module control
        $this->middleware(AllFunctionService::access('ib_management', 'admin'));
        $this->middleware(AllFunctionService::access('ib_verification_request', 'admin'));
    }
    public function ibVerificationRequest(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->VerificationRequestDT($request);
        }
        $ib_groups = IbGroup::select()->get();
        $kyc_types = KycIdType::select()->get();
        $countries = Country::all();
        $crmVarsion = VersionControllService::check_version();
        return view('admins.reports.ib-verify-request-report', [
            'ib_groups' => $ib_groups,
            'types' => $kyc_types,
            'countries' => $countries,
            'varsion' => $crmVarsion
        ]);
    }

    public function VerificationRequestDT($request)
    {
        try {
            $columns = ['users.name', 'users.email', 'users.ib_group_id', 'kyc_verifications.doc_type', 'kyc_verifications.status', 'kyc_verifications.created_at'];
            $orderby = $columns[$request->order[0]['column']];
            $result = KycVerification::select(
                'kyc_verifications.id as table_id',
                'kyc_verifications.user_id',
                'kyc_verifications.doc_type',
                'kyc_verifications.status',
                'kyc_verifications.created_at',
                'users.name',
                'users.email',
                'users.phone',
                'users.type',
                'users.ib_group_id',
                'users.email_verified_at'
            )
                ->join('users', 'kyc_verifications.user_id', '=', 'users.id')
                ->where('users.type', '=', CombinedService::type());
            // check crm is combined
            if (CombinedService::is_combined()) {
                $result = $result->where('users.combine_access', 1);
            }

            //-------------------------------------------------------------------------
            //Filter Start
            //-------------------------------------------------------------------------

            //Filter By Status
            if ($request->verification_status != "") {
                $result = $result->where('status', $request->verification_status);
            }

            //filter by ib group
            if ($request->ib_group != "") {
                $groupIb = IbGroup::select('id')->pluck('id')->toArray();
                $result = $result->whereIn('users.ib_group_id', $groupIb);
            }

            //filter by kyc verification type
            if ($request->kyc_type != "") {
                $result = $result->where('kyc_verifications.doc_type', $request->kyc_type);
            }

            //filter by manager
            if ($request->manager != "") {
                $manager = $request->manager;
                $manager_id = User::select('id')
                    ->where(function ($query) use ($manager) {
                        $query->where('name', $manager)
                            ->orWhere('email', $manager)
                            ->orWhere('phone', $manager);
                    })->get()->pluck('id');
                $users_id = ManagerUser::select('user_id')->where('manager_id', $manager_id)->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $users_id);
            }

            //filter by ib name / email / phone
            if ($request->ib_info != "") {
                $ib_info = $request->ib_info;
                $result = $result->where('users.type', 4)->where(function ($query) use ($ib_info) {
                    $query->where('name', 'LIKE', '%' . $ib_info . '%')
                        ->orwhere('email', $ib_info)
                        ->orwhere('phone', $ib_info);
                });
            }

            // filter by trader info
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $filter_trader = User::where(function ($query) use ($trader_info) {
                    $query->where('users.name', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $trader_info . '%')
                        ->orWhere('countries.name', $trader_info);
                })
                    ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                // get instant parants ib
                $instant_parent_ib = IB::whereIn('reference_id', $filter_trader)->select('ib_id')->get()->pluck('ib_id');
                // filter result
                $result = $result->whereIn('users.id', $instant_parent_ib);
            }

            //Filter By Country
            if ($request->country != "") {
                $trader_country = $request->country;
                $user_id = User::select('countries.name')->where(function ($query) use ($trader_country) {
                    $query->where('countries.name', 'LIKE', '%' . $trader_country . '%');
                })->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
                    ->leftJoin('countries', 'user_descriptions.country_id', '=', 'countries.id')
                    ->select('users.id as user_id')->get()->pluck('user_id');
                $result = $result->whereIn('users.id', $user_id);
            }

            //filter by date
            if ($request->from != "") {
                $result = $result->whereDate('kyc_verifications.created_at', '>=', $request->from);
            }
            if ($request->to != "") {
                $result = $result->whereDate('kyc_verifications.created_at', '<=', $request->to);
            }

            /*<-------filter search script End here------------->*/
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $user) {
                // dd($user->table_id);
                if ($user->status == 0) {
                    $status = '<span class="badge badge-warning bg-warning">Pending</span>';
                } elseif ($user->status == 1) {
                    $status = '<span class="badge badge-success bg-success">Verified</span>';
                } elseif ($user->status == 2) {
                    $status = 'Declined';
                    $status = '<span class="badge badge-danger bg-danger">Declined</span>';
                }

                $group = IbGroup::select()->where('id', $user->ib_group_id)->first();

                $document = KycIdType::select()->where('id', $user->doc_type)->first()->id_type;
                $data[] = [
                    'name' => '<a href="#" data-table_id=' . $user->table_id . ' data-id=' . $user->user_id . '   class="dt-description d-flex justify-content-between"><span class="w"> <i class="plus-minus" data-feather="plus"></i> </span> <span>' .  $user->name . '</span></a>',
                    'email' => $user->email,
                    'ib_group' => $group->group_name,
                    'type' => ucfirst($document),
                    'status' => $status,
                    'date' => date('d F y, h:i A', strtotime($user->created_at)),
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
    public function ibVerificationDescription(Request $request, $id, $table_id)
    {
        $check = KycVerification::select('status')->where('id', $table_id)->first();
        $buttons = "";
        if ($check->status === 0) {
            // authentication check
            $auth_user = User::find(auth()->user()->id);
            if ($auth_user->hasDirectPermission('edit ib verification request')) {
                $buttons = '<p class="details-text" style="float:right;">

                        <button   data-type="button"  class="btn btn-success waves-effect waves-float waves-light"  data-loading="processing..." data-bs-toggle="modal" data-bs-target="#addNewAddressModal"  data-id="' . $id . '"  onclick="identify_request(this)">Identify</button>
                    </p>';
            } else {
                $buttons = "";
            }
        } else {
            $buttons = '<p class="details-text" style="float:right;">
                        <button   data-type="button"  class="btn btn-success waves-effect waves-float waves-light"  data-loading="processing..." data-bs-toggle="modal" data-bs-target="#addNewAddressModal" data-id="' . $id . '"  onclick="identify_request(this)">Identify</button>
                    </p>';
        }
        $description = '<tr class="description" style="display:none">
        <td colspan="7">
            <div class="details-section-dark border-start-3 border-start-primary p-2 " style="display: flow-root;">
                <span class="details-text">
                    Details
                </span>
                <table id="ib-verification-details' . $id . '" class="ib-verification-details table dt-inner-table-dark">
                    <thead>
                        <tr>
                            <th>NID Number</th>
                            <th>Issue Date</th>
                            <th>Exp Date</th>
                        </tr>
                    </thead>
                </table>
                <br>
                ' . $buttons . '
            </div>
        </td>

    </tr>';
        $data = [
            'status' => true,
            'description' => $description
        ];
        return Response::json($data);
    }

    public function verificationInnerDescription(Request $request, $id, $table_id)
    {

        $result = KycVerification::select('kyc_verifications.id_number', 'kyc_verifications.issue_date', 'kyc_verifications.exp_date', 'kyc_verifications.approved_by')
            ->where('kyc_verifications.id', $table_id);

        $count_row = $result->count();
        $recordsTotal = $count_row;
        $recordsFiltered = $count_row;
        $result = $result->orderBy('kyc_verifications.id', 'DESC')->get();
        $data = array();

        foreach ($result as $user) {
            $admin = User::select('name')->where('id', $user->approved_by)->first();
            $data[] = [
                'nid_number' =>  isset($user->nid) ? $user->nid : '---',
                'issue_date' =>  date('d F y, h:i A', strtotime($user->issue_date)),
                'exp_date' =>  date('d F y, h:i A', strtotime($user->exp_date)),
            ];
        }
        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered);
        $output['data'] = $data;

        return Response::json($output);
    }
    //IB verification appprove script
    public function IBVerificationApprove(Request $request, $id, $table_id)
    {
        $user = User::find($id);
        $support_email = SystemConfig::select('support_email')->first();
        $support_email = ($support_email) ? $support_email->support_email : default_support_email();
        $email_data = [
            'name'              => ($user) ? $user->name : config('app.name') . ' User',
            'account_email'     => ($user) ? $user->email : '',
            'admin'             => Auth::user()->name,
            'login_url'         => route('login'),
            'support_email'     => $support_email,

        ];

        $update = KycVerification::where('id', $table_id)->update([
            'status' => 1,
            'approved_by' => auth()->user()->id
        ]);
        if ($update) {
            if (Mail::to($user->email)->send(new IbVerificationApproveRequest($email_data))) {
                return Response::json(['success' => true, 'message' => 'Mail successfully sent for IB Verification Approved request', 'success_title' => 'Approve request']);
            } else {
                return Response::json(['success' => false, 'message' => 'Mail sending failed, Please try again later!', 'success_title' => 'Approve request']);
            }
        }
    }

    public function IBVerificationDecline(Request $request)
    {
        $user_id = $request->decline_id;
        $table_id = $request->table_id;
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

        $reason = $request->input('reason');
        $update = KycVerification::where('id', $table_id)->update(['status' => 2, 'note' => $reason]);
        if ($update) {

            if (Mail::to($user->email)->send(new IbVerificationDeclineRequest($email_data))) {
                return Response::json(['success' => true, 'message' => 'Mail successfully sent for IB Declined request', 'success_title' => 'Declined request']);
            } else {
                return Response::json(['success' => false, 'message' => 'Mail sending failed, Please try again later!', 'success_title' => 'Declined request']);
            }
        }
    }

    public function IBVerificationProof(Request $request, $id)
    {
        $document = KycVerification::select('document_name', 'group')
            ->where('user_id', $id)
            ->join('kyc_id_type', 'kyc_verifications.doc_type', '=', 'kyc_id_type.id')
            ->first();
        $doc_name = json_decode($document->document_name);
        // contabo file get
        $file_front_part = FileApiService::contabo_file_path($doc_name->front_part);
        $file_back_part = FileApiService::contabo_file_path($doc_name->back_part);
        $image_path = asset("Uploads/kyc");


        return response()->json(
            [
                'doc_name' => $doc_name,
                'image_path' => $image_path,
                'front_part' => $file_front_part['dataUrl'],
                'back_part' => $file_back_part['dataUrl'],
                'front_part_file_type' => $file_front_part['file_type'],
                'back_part_file_type' => $file_back_part['file_type'],
                'group_name' => $document->group,
            ]
        );
    }
}
