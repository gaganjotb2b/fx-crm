<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\KycIdType;
use App\Models\KycVerification;
use App\Models\ManagerUser;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use App\Services\api\FileApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\UserDescription;
use App\Models\TradingAccount;

class KycReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:kyc reports"]);
        $this->middleware(["role:kyc management"]);
        // system module control
        $this->middleware(AllFunctionService::access('kyc_management', 'admin'));
        $this->middleware(AllFunctionService::access('kyc_reports', 'admin'));
    }

    public function kycReport(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->kycReportDT($request);
        }
        $countryList= Country::all();
        return view('admins.reports.kyc-report',compact('countryList'));
    }

    public function kycReportDT($request)
    {
        try {
            $doc_type = $request->input('type');
            $client_type = $request->input('client_type');
            $status = $request->input('status');
            $info = $request->input('info');
            $from = $request->input('from');
            $to = $request->input('to');
            $issue_from = $request->input('issue_from');
            $issue_to = $request->input('issue_to');
            $expire_from = $request->input('expire_from');
            $expire_to = $request->input('expire_to');
            $manager_email = $request->manager_email;

            $columns = ['name', 'type', 'doc_type', 'exp_date', 'issue_date', 'status', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];

            $result = KycVerification::select('kyc_verifications.*', 'kyc_verifications.id as table_id', 'users.id', 'users.type', 'users.name')
                ->join('users', 'kyc_verifications.user_id', '=', 'users.id')
                ->where('users.type', '!=', 2)
                ->where('users.type', '!=', 3)
                ->where('users.type', '!=', 5);

            /*<-------filter search script start here------------->*/
            //desk manager email filter

            //manager filter script
            if ($manager_email != "") {
                $manager_id = User::select('id')->where('email', '=', $manager_email)->orWhere('phone', 'like','%'. $manager_email.'%')->orWhere('name', 'like', '%'.$manager_email.'%')->first();
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

            if ($client_type != "") {
                if ($client_type == 'ib') {
                    $result = $result->where('type', CombinedService::type());
                    // check crm is combined
                    if (CombinedService::is_combined()) {
                        $result = $result->where('users.combine_access', 1);
                    }
                } elseif ($client_type == 'trader') {
                    $result = $result->where('type', 0);
                }
            }
        
            if ($doc_type != "") {
                $matchDoc= KycIdType::where('id_type',$doc_type)->pluck('id');
                $result = $result->whereIn('kyc_verifications.doc_type',$matchDoc);
            }

            if ($status != "") {
                $result = $result->where('status', '=', $status);
            }


            if ($info != "") {
                $result = $result->where('name', 'LIKE', '%' . $info . '%')->orwhere('email', 'LIKE', '%' . $info . '%');
            }

            // if ($issue_from != "") {
            //     $result = $result->whereDate("kyc_verifications.issue_date", '>=', $issue_from);
            // }

            // if ($issue_to != "") {
            //     $result = $result->whereDate("kyc_verifications.issue_date", '<=', $issue_to);
            // }

            // if ($expire_from != "") {
            //     $result = $result->whereDate("kyc_verifications.exp_date", '>=', $expire_from);
            // }

            // if ($expire_to != "") {
            //     $result = $result->whereDate("kyc_verifications.exp_date", '<=', $expire_to);
            // }

            //Country

            if ($request->country_info != "") {
                $country= $request->country_info;
                $countryId = UserDescription::where('country_id',$country)->pluck('user_id');
                $result = $result->whereIn('users.id',$countryId);
            }
            //trading account number
            if ($request->trading_number != ''){
                $findNumber = TradingAccount::where('account_number',$request->trading_number)->pluck('user_id')->toArray();
                $result = $result->whereIn('users.id',$findNumber);
            }

            if ($from != "") {
                $result = $result->where(function ($query) use ($from){

                    $query->whereDate("kyc_verifications.created_at", '>=', $from)->orWhereDate("kyc_verifications.issue_date", '>=', $from)->orWhereDate("kyc_verifications.exp_date", '>=', $from);
                });
            }
            if ($to != "") {
                $result = $result->where(function ($query) use ($to){

                    $query->whereDate("kyc_verifications.created_at", '<=', $to)->orWhereDate("kyc_verifications.issue_date", '<=', $to)->orWhereDate("kyc_verifications.exp_date", '<=', $to);
                });
            }

            /*<-------filter search script End here------------->*/


            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();


            $data = array();
            $i = 0;
            foreach ($result as $user) {
                $buttons = '';
                $auth_user = User::find(auth()->user()->id);
                if ($auth_user->hasDirectPermission('edit kyc request')) {
                    $buttons = '<button   data-type="button"  class="btn btn-primary waves-effect waves-float waves-light" data-bs-toggle="modal" data-bs-target="#pricingModal"  data-loading="processing..."  data-id="' . $user->user_id . '" data-table_id="' . $user->table_id . '"   onclick="view_document(this)">View</button>';
                } else {
                    $buttons = '<span class="text-danger">No Permission to Access</span>';
                }

                $document_name = KycIdType::select('id_type')->where('id', $user->doc_type)->first();


                if ($user->type == '4') {
                    $client_type = "IB";
                }
                if ($user->type == '0') {
                    // check crm is combined
                    if (CombinedService::is_combined()) {
                        if ($user->combine_access == 1) {
                            $client_type = "IB";
                        } else {
                            $client_type = "TRADER";
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
                    $issue_date = is_null($user->issue_date) ? '---' : date('d F y', strtotime($user->issue_date));
                    $expire_date = is_null($user->exp_date) ? '---' : date('d F y', strtotime($user->exp_date));
                }
                $data[$i]['client_name']   = $user->name;
                $data[$i]['client_type']   = $client_type;
                $data[$i]['document_type'] = ucwords($document_name->id_type ?: null);
                $data[$i]['issue_date']    = $issue_date;
                $data[$i]['expire_date']   = $expire_date;
                $data[$i]['status']        = $status;
                $data[$i]['date']          = date('d F y', strtotime($user->created_at));
                $data[$i]['action']        =  $buttons;

                $i++;
            }
            return Response::json([
                'draw' => $_REQUEST['draw'],
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'draw' => $_REQUEST['draw'],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    public function viewDescription(Request $request, $id, $table_id)
    {
        $user_info = User::select()->where('users.id', $id)
            ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')->first();
        $user_kyc_sts = KycVerification::select()->where('id', $table_id)->first();

        $user_country = Country::select('name')->where('id', $user_info->country_id)->first();

        $kyc_id_type = KycIdType::select('id_type', 'group')->where('id', $user_kyc_sts->doc_type)->first();

        $document_images = json_decode($user_kyc_sts->document_name);
        $image_path = asset("Uploads/kyc");
        $status = 0;
        if ($user_kyc_sts->status == 0) {
            $status = '<span class="text-warning">Pending</span>';
        }
        if ($user_kyc_sts->status == 1) {
            $status = '<span class="text-success">Verified</span>';
        }

        if ($user_kyc_sts->status == 2) {
            $status = '<span class="text-danger">Decliend</span>';
        }
        $file_front_part = (asset('Uploads/kyc/' . $document_images->front_part)) ?? '';
        $file_back_part = (asset('Uploads/kyc/' . $document_images->back_part)) ?? '';
        
        // $file_front_part = FileApiService::view_file($document_images->front_part);
        // $file_back_part = FileApiService::view_file($document_images->back_part);
        
        // $file_front_part = FileApiService::contabo_file_path($document_images->front_part);
        // $file_back_part = FileApiService::contabo_file_path($document_images->back_part);
        $data = [
            'dob' => date('Y-m-d', strtotime($user_info->date_of_birth)),
            'issue_date' => date('Y-m-d', strtotime($user_kyc_sts->issue_date)),
            'exp_date' => date('Y-m-d', strtotime($user_kyc_sts->exp_date)),
            'document_name' => $kyc_id_type->id_type,
            'group_name' => $kyc_id_type->group,
            'image_path' => $image_path,
            'images' => $document_images,
            'status' => $status,
            'country' =>  $user_country,
            'user' => $user_info,
            'user_kyc_sts' => $user_kyc_sts->status,
            'front_part' => $file_front_part,
            'back_part' => $file_back_part,
            'front_part_file_type' => null,
            'back_part_file_type' => null,
            // 'front_part' => $file_front_part['dataUrl'],
            // 'back_part' => $file_back_part['dataUrl'],
            // 'front_part_file_type' => $file_front_part['file_type'],
            // 'back_part_file_type' => $file_back_part['file_type']
        ];

        return response()->json($data);
    }
}
