<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Withdraw;
use App\Models\Deposit;
use App\Models\WalletUpDown;
use App\Models\StaffTransaction;
use App\Models\BankAccount;
use App\Models\KycVerification;
use App\Models\KycIdType;
use App\Models\admin\SystemConfig;
use App\Mail\KycDecline;
use App\Models\UserDescription;
use App\Services\AgeCalculatorService;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class KycUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:kyc upload"]);
        $this->middleware(["role:kyc management"]);
        // system module control
        $this->middleware(AllFunctionService::access('kyc_management', 'admin'));
        $this->middleware(AllFunctionService::access('kyc_upload', 'admin'));
    }
    //Basic view
    // ------------------------------------------------------------------------
    public function index(Request $request)
    {
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)
            ->join('users', 'users.id', '=', 'user_descriptions.user_id')
            ->first();
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender == 'male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }
        $id_document_type = KycIdType::where('group', 'id proof')->get();
        $address_document_type = KycIdType::where('group', 'address proof')->get();
        return view('admins.kyc.kyc-upload', [
            'avatar' => $avatar,
            'id_document_type' => $id_document_type,
            'address_document_type' => $address_document_type
        ]);
    }

    // ================================================================================
    // get id type for select option 
    // ----------------------------------------------------------------------------
    public function get_id_type(Request $request, $id_type)
    {
        $id_types = KycIdType::where('group', $id_type)->get();
        $options = '';
        foreach ($id_types as $key => $value) {
            $options .= '<option value="' . $value->id . '">' . ucwords($value->id_type) . '</option>';
        }
        return Response::json(['options' => $options]);
    }

    // get client 
    // ------------------------------------------------------------------------
    public function get_client(Request $request, $user_type)
    {
        $users = User::where('type', $user_type)->get();
        $client_options = '';
        foreach ($users as $value) :
            $client_options .= '<option value="' . $value->id . '">' . $value->email . '</option>';
        endforeach;
        $data = [
            'status' => true,
            'users' => $client_options
        ];
        return Response::json($data);
    }
    public function search_client(Request $request, $user_type, $value)
    {
        $users = User::where('type', $user_type)
            ->where('name', 'like', '%' . $value . '%')
            ->orWhere('email', 'like', '%' . $value . '%')->limit(5)
            ->get();
        $client_options = '';
        foreach ($users as $value) :
            $client_options .= '<a href="#' . $value->email . '" class="fill-input" data-value="' . $value->email . '">
                                    ' . $value->email . '
                                </a>';
        endforeach;
        $data = [
            'status' => true,
            'users' => $client_options
        ];
        return Response::json($data);
    }
    // upload kyc file
    public function file_upload(Request $request)
    {
        $kyc_id_type = KycIdType::find($request->document_type);
        $check_config = SystemConfig::select('kyc_back_part')->first();
        if ($request->perpose === 'address proof') {
            $validation_rules = [
                'document_type' => 'required',
                'issue_date' => 'required',
                'expire_date' => 'required',
                'file_document' => 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:3072', // Adjust the max file size as needed (in kilobytes).
                'client_email' => 'required',
                'status' => 'required',
                'decline_reason' => 'nullable'
            ];
        }
        // id proof
        else {
            $validation_rules = [
                'document_type' => 'required', // Adjust the max file size as needed (in kilobytes).
                'issue_date' => 'required',
                'client_email' => 'required',
                'file_front_part' => 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:3072', // Adjust the max file size as needed (in kilobytes).
                'status' => 'required',
                'decline_reason' => 'nullable'
            ];
            // check backpart required or not

            if ($check_config->kyc_back_part == 0) {
                $validation_rules['file_back_part'] = 'nullable|file|mimes:jpeg,png,gif,pdf,jpg|max:3072'; // Adjust the max file size as needed (in kilobytes).
            } else {
                $validation_rules['file_back_part'] = 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:3072'; // Adjust the max file size as needed (in kilobytes).
            }
            // check kyc id typ
            if (isset($kyc_id_type->has_issue_date) && ($kyc_id_type->has_issue_date == 1)) {
                $validation_rules['issue_date'] = 'required';
                $validation_rules['expire_date'] = 'required';
            } else {
                $validation_rules['issue_date'] = 'nullable';
                $validation_rules['expire_date'] = 'nullable';
            }
        }


        $validator = Validator::make($request->all(), $validation_rules);
        // default laravel validtion
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        }
        $getAgeOfInput = new AgeCalculatorService();
        // end file size validation
        if ($kyc_id_type->has_issue_date) {
            // issuedate validation
            if ($getAgeOfInput->checkExpairDate($request->issue_date, 'invalid')) {
                return Response::json([
                    'status' => false,
                    'errors' => ['issue_date' => "Invalid issue date"],
                    'message' => 'Please fix the following errors!'
                ]);
            }
            // expire date validation
            if ($getAgeOfInput->checkExpairDate($request->expire_date)) {
                return Response::json([
                    'status' => false,
                    'errors' => ['expire_date' => "Can't send Date Expired Document"],
                    'message' => 'Please fix the following errors!'
                ]);
            }
        }
        // address proof / file uplaod
        if ($request->perpose === 'address proof') {
            $check_exist = KycVerification::where('user_id', auth()->user()->id)->where('status', '!=', 2)->where('perpose', 'address proof')->exists();
            if ($check_exist) {
                return Response::json([
                    'status' => false,
                    'message' => 'KYC already exist for address proof, Please contact your manger'
                ]);
            } else {
                $file_document = time() . '.' . $request->file_document->getClientOriginalExtension();
                $request->file_document->move(public_path('Uploads/kyc'), $file_document);
                // $address_document = $request->file('file_document');
                // $filename_address = time() . '_address_document_' . $address_document->getClientOriginalName();
                // // contabo file upload
                // $client = FileApiService::s3_clients();
                // $client->putObject([
                //     'Bucket' => FileApiService::contabo_bucket_name(),
                //     'Key' => $filename_address,
                //     'Body' => file_get_contents($address_document)
                // ]);
                
                $user = User::where('id', $request->client_email)->first();
                $created = KycVerification::create([
                    'user_id' => $user->id,
                    'issue_date' => $request->issue_date,
                    'exp_date' => $request->expire_date,
                    'doc_type' => $request->document_type,
                    'perpose' => $request->perpose,
                    'note' => $request->decline_reason,
                    'status' => $request->status,
                    'document_name' => json_encode(['front_part' => $file_document, 'back_part' => ''])
                    // 'document_name' => json_encode(['front_part' => $filename_address, 'back_part' => ''])
                ])->id;
                // update kyc verification status
                if ($request->status == 1) {
                    User::where('id', $user->id)->update([
                        'kyc_status' => 1
                    ]);
                } else {
                    // pending status
                    User::where('id', $user->id)->update([
                        'kyc_status' => 2
                    ]);
                }
            }
        }
        // id proof / file upload
        else {
            $file_front_part = time() . '.' . $request->file_front_part->getClientOriginalExtension();
            $request->file_front_part->move(public_path('Uploads/kyc'), $file_front_part);
            
            // $front_part = $request->file('file_front_part');
            // $filename_front_part = time() . '_id_front_part_' . $front_part->getClientOriginalName();
            // // $front_part->move(public_path('/Uploads/kyc'), $filename_front_part);
            // // FileApiService::file_move($front_part, $filename_front_part);
            // $client = FileApiService::s3_clients();
            // $client->putObject([
            //     'Bucket' => FileApiService::contabo_bucket_name(),
            //     'Key' => $filename_front_part,
            //     'Body' => file_get_contents($front_part)
            // ]);
            
            if ($check_config->kyc_back_part != 0) {
                $file_back_part = time() . '.' . $request->file_back_part->getClientOriginalExtension();
                $request->file_back_part->move(public_path('Uploads/kyc'), $file_back_part);
                
                // $back_part = $request->file('file_back_part');
                // $filename_back_part = time() . '_id_back_part_' . $back_part->getClientOriginalName();
                // // $back_part->move(public_path('/Uploads/kyc'), $filename_back_part);
                // // FileApiService::file_move($back_part, $filename_back_part);
                // $client = FileApiService::s3_clients();
                // $client->putObject([
                //     'Bucket' => FileApiService::contabo_bucket_name(),
                //     'Key' => $filename_back_part,
                //     'Body' => file_get_contents($back_part)
                // ]);
            }
            // $document_name = [
            //     'front_part' => $filename_front_part,
            //     'back_part' => ($check_config->kyc_back_part != 0) ? $filename_back_part : '',
            // ];
            
            $document_name = [
                'front_part' => $file_front_part,
                'back_part' => ($check_config->kyc_back_part != 0) ? $file_back_part : '',
            ];
            $user = User::where('id', $request->client_email)->first();
            $created = KycVerification::create([
                'user_id' => $user->id,
                'issue_date' => $request->issue_date,
                'exp_date' => $request->expire_date,
                'doc_type' => $request->document_type,
                'perpose' => $request->perpose,
                'note' => $request->decline_reason,
                'status' => $request->status,
                'document_name' => json_encode($document_name)
            ])->id;
            // update kyc verification status
            if ($request->status == 1) {
                User::where('id', $user->id)->update([
                    'kyc_status' => 1
                ]);
            } else {
                // pending status
                User::where('id', $user->id)->update([
                    'kyc_status' => 2
                ]);
            }
        }
        if ($created) {
            // insert activity-----------------
            $auth_user = User::find(auth()->user()->id);
            activity($request->perpose . " Kyc Upload")
                ->causedBy(auth()->user()->id)
                ->withProperties(KycVerification::find($created))
                ->event('ib verification')
                ->performedOn($auth_user)
                ->log("The IP address " . request()->ip() . " has been upload kyc");
            // end activity log-----------------
            return Response::json([
                'status' => true,
                'message' => 'KYC Success fully uploaded'
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later'
            ]);
        }
    }

    // get client details
    // ------------------------------------------------------------------------------------------
    public function get_client_details(Request $request, $id)
    {
        $user = User::where('users.email', $id)
            ->leftJoin('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->first();
        $data = [
            'name' => ucwords((isset($user->name)) ? $user->name : ''),
            'address' => ucwords((isset($user->address)) ? $user->address : ''),
            'zip_code' => ((isset($user->zip_code)) ? $user->zip_code : ''),
            'city' => ucwords((isset($user->city)) ? $user->city : ''),
            'state' => ucwords((isset($user->state)) ? $user->state : ''),
            'type' => strtoupper((isset($user->type)) ? $user->type : '')
        ];
        return Response::json($data);
    }

    // ============================================================================
    // submit and store kyc data to dtabase
    // -------------------------------------------------------------------------------------------
    public function store(Request $request)
    {
        $multiple_submission = false;
        $validation_rules = [
            'document_type' => 'required',
            'client_type' => 'required',
            'client' => 'required',
            'status' => 'required',
            'id_type' => 'required',
        ];

        // id proof validation
        if ($request->document_type === 'id proof') {
            // front part validation
            if (Session::has('front_part')) {
                $validation_rules['front_part'] = 'nullable';
            } else {
                $validation_rules['front_part'] = 'required';
            }

            // back part validatioln
            if (Session::has('back_part')) {
                $validation_rules['back_part'] = 'nullable';
            } else {
                $validation_rules['back_part'] = 'required';
            }
        }

        // address proof validation
        if ($request->document_type === 'address proof') {
            if (Session::has('address_proof')) {
                $validation_rules['address_proof'] = 'nullable';
            } else {
                $validation_rules['address_proof'] = 'required';
            }
        }

        // note or decline validation
        if ($request->status == 2) {
            $validation_rules['decline_reason'] = 'nullable|min:10|max:100';
        }

        // start session of form submit
        // $multiple_submission = has_multi_submit('finance-balance',60);
        // multi_submit('finance-balance',60);
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails() || $multiple_submission == true) {
            if ($request->ajax()) {
                return Response::json(['status' => false, 'errors' => $validator->errors(), 'multiple_submission' => $multiple_submission, 'submit_wait' => submit_wait('finance-balance', 60)]);
            }
        } else {
            $document_name = [];
            if ($request->document_type === 'address proof') {
                $document_name['address_proof'] = session()->get('address_proof');
            }
            // document name for id proof
            if ($request->document_type === 'id proof') {
                $document_name['front_part'] = session()->get('front_part');
                $document_name['back_part'] = session()->get('back_part');
            }
            $data = [
                'user_id' => $request->client,
                'status' => $request->status,
                'perpose' => $request->document_type,
                'doc_type' => $request->id_type,
                'document_name' => json_encode($document_name),
                'approved_by' => auth()->user()->id
            ];
            if (isset($request->decline_reason)) {
                $data['note'] = $request->decline_reason;
            }
            if (isset($request->issue_date)) {
                $data['issue_date'] = $request->issue_date;
            }
            if (isset($request->expire_date)) {
                $data['exp_date'] = $request->exp_date;
            }
            $create = KycVerification::create($data)->id;
            if ($create) {
                $request->session()->forget('address_proof');
                $request->session()->forget('front_part');
                $request->session()->forget('back_part');
                return Response::json(['status' => true, 'kyc_decline' => ($request->status == 2) ? true : false, 'last_id' => $create, 'message' => 'KYC Uploaded successfully', 'multiple_submission' => $multiple_submission, 'submit_wait' => submit_wait('finance-balance', 60)]);
            } else {
                return Response::json(['status' => false, 'message' => 'KYC Upload failed', 'multiple_submission' => $multiple_submission, 'submit_wait' => submit_wait('finance-balance', 60)]);
            }
        }
    }

    // ================================================================================
    // sending mail for kyc decline
    // mail to users, why decline the email
    // --------------------------------------------------------------------------------
    public function kyc_decline_mail(Request $request)
    {
        $kyc = KycVerification::find($request->last_id);
        $user = User::find($kyc->user_id);
        // return $user;
        $support_email = SystemConfig::select('support_email')->first();
        $support_email = ($support_email) ? $support_email->support_email : default_support_email();
        $email_data = [
            'name' => ($user) ? $user->name : config('app.name') . ' User',
            'account_email' => ($user) ? $user->email : '',
            'admin' => auth()->user()->name,
            'login_url' => route('login'),
            'support_email' => $support_email,
            'message_custom' => (isset($kyc->note)) ? $kyc->note : '',
            'phone' => ($user) ? $user->phone : ''
        ];
        if (Mail::to($user->email)->send(new KycDecline($email_data))) {
            if ($request->ajax()) {
                return Response::json(['status' => true, 'message' => 'Mail successfully sent for kyc decline reason', 'success_title' => 'Change password']);
            }
        } else {
            return Response::json(['status' => false, 'message' => 'Mail sending failed, Please try again later!', 'success_title' => 'Change password']);
        }
    }
}
