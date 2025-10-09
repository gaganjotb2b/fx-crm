<?php

namespace App\Http\Controllers\IB\MyAdmin;


use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\IB;
use App\Models\KycIdType;
use App\Models\KycVerification;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AgeCalculatorService;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use App\Services\FileUploadService;
use App\Services\MailNotificationService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;

class IbAccountVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('verification', 'ib'));
        $this->middleware(AllFunctionService::access('my_admin', 'ib'));
    }
    //basic view----------------
    public function verification(Request $request)
    {
        $copyright = SystemConfig::select('copyright')->first();
        $kyc_id_type = KycIdType::where('group', 'id proof')->get();
        $kyc_address_type = KycIdType::where('group', 'address proof')->get();

        $avatar = avatar();
        // get all details of this user---------
        $user = User::where('users.id', auth()->user()->id)
            ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->select(
                'users.name',
                'users.email',
                'users.phone',
                'users.created_at',
                'users.email_verified_at'
            )
            ->first();


        // kyc documents status----------------
        $kyc_status = KycVerification::where('user_id', auth()->user()->id);
        $kyc_documents = $kyc_status->get();

        // kyc verification status----------
        $kyc_status = $kyc_status->where('status', '!=', 2);
        $kyc_status = $kyc_status->first();
        if ($kyc_status == null) {
            $kyc_verifiction_status = 'Unverified';
        } elseif ($kyc_status->status == 0) {
            $kyc_verifiction_status = 'Pending';
        } elseif ($kyc_status->status == 1) {
            $kyc_verifiction_status = 'Verified';
        }
        // email verification status---------------
        if ($user->email_verified_at != "") {
            $email_verification_status = 'Verified';
        } else {
            $email_verification_status = 'Unverified';
        }
        return view(
            'ibs.ib-admins.account-verification',
            [
                'avatar' => $avatar,
                'id_type' => $kyc_id_type,
                'address_type' => $kyc_address_type,
                'user' => $user,
                'kyc_status' => $kyc_verifiction_status,
                'email_verification_status' => $email_verification_status,
                'kyc_documents' => $kyc_documents,
                'copyright'     => $copyright,
                'check_kyc_status' => $kyc_status,
            ]
        );
    }
    // upload file-----------------------
    public function file_upload(Request $request)
    {
        try {
            $kyc_id_type = KycIdType::find($request->document_type);
            $check_config = SystemConfig::select('kyc_back_part')->first();
            if ($request->perpose === 'address proof') {
                $validation_rules = [
                    'document_type' => 'required',
                    'issue_date' => 'required',
                    'expire_date' => 'required',
                    'file_document' => 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:3072', // Adjust the max file size as needed (in kilobytes).
                ];
            }
            // id proof
            else {
                $validation_rules = [
                    'document_type' => 'required',
                    'file_front_part' => 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:3072', // Adjust the max file size as needed (in kilobytes).
                    'id_number' => 'required',
                ];
                // check backpart reuired or not
                if ($check_config->kyc_back_part == 0) {
                    $validation_rules['file_back_part'] = 'nullable|file|mimes:jpeg,png,gif,pdf,jpg|max:3072'; // Adjust the max file size as needed (in kilobytes);
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
            $getAgeOfInput = new AgeCalculatorService();

            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following errors!'
                ]);
            }

            // issudate validation
            if ($kyc_id_type->has_issue_date) {
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

            // address proof=>mime type validation
            $kyc_status_check = User::select('kyc_status')->where('id', auth()->user()->id)->first();
            if ($request->perpose === 'address proof') {
                // check address proof kyc exist or not
                $check_exist = KycVerification::where('user_id', auth()->user()->id)->where('status', '!=', 2)->where('perpose', 'address proof')->exists();
                if ($check_exist) {
                    return Response::json([
                        'status' => false,
                        'message' => 'KYC already exist for address proof, Please contact your manger'
                    ]);
                } else {

                    $address_document = $request->file('file_document');
                    $filename_address = time() . '_address_document_' . $address_document->getClientOriginalName();
                    $client = FileApiService::s3_clients();
                    $client->putObject([
                        'Bucket' => FileApiService::contabo_bucket_name(),
                        'Key' => $filename_address,
                        'Body' => file_get_contents($address_document)
                    ]);

                    $created = KycVerification::create([
                        'user_id' => auth()->user()->id,
                        'issue_date' => $request->issue_date,
                        'exp_date' => $request->expire_date,
                        'doc_type' => $request->document_type,
                        'perpose' => $request->perpose,
                        'document_name' => json_encode(['front_part' => $filename_address, 'back_part' => ''])
                    ])->id;
                    // pending status

                    if ($kyc_status_check->kyc_status != 1) {
                        User::where('id', auth()->user()->id)->update([
                            'kyc_status' => 2
                        ]);
                    }
                }
            } else {
                $check_exist = KycVerification::where('user_id', auth()->user()->id)->where('status', '!=', 2)->where('perpose', 'id proof')->exists();
                if ($check_exist) {
                    return Response::json([
                        'status' => false,
                        'message' => 'KYC already exist for ID proof, Please contact your manger'
                    ]);
                } else {
                    $front_part = $request->file('file_front_part');
                    $filename_front_part = time() . '_id_front_part_' . $front_part->getClientOriginalName();
                    // file upload in contabo s3
                    $client = FileApiService::s3_clients();
                    $client->putObject([
                        'Bucket' => FileApiService::contabo_bucket_name(),
                        'Key' => $filename_front_part,
                        'Body' => file_get_contents($front_part)
                    ]);
                    // store back part
                    if ($request->file('file_back_part')) {
                        $back_part = $request->file('file_back_part');
                        $filename_back_part = time() . '_id_front_part_' . $back_part->getClientOriginalName();
                        // file upload in contabo s3
                        $client = FileApiService::s3_clients();
                        $client->putObject([
                            'Bucket' => FileApiService::contabo_bucket_name(),
                            'Key' => $filename_back_part,
                            'Body' => file_get_contents($back_part)
                        ]);
                    }

                    $document_name = [
                        'front_part' => $filename_front_part,
                        'back_part' => ($request->file('file_back_part')) ? $filename_back_part : '',
                    ];
                    $created = KycVerification::create([
                        'user_id' => auth()->user()->id,
                        'issue_date' => (isset($kyc_id_type->has_issue_date) && ($kyc_id_type->has_issue_date == 1)) ? $request->issue_date : null,
                        'exp_date' => (isset($kyc_id_type->has_issue_date) && ($kyc_id_type->has_issue_date == 1)) ? $request->expire_date : null,
                        'doc_type' => $request->document_type,
                        'perpose' => $request->perpose,
                        'document_name' => json_encode($document_name)
                    ])->id;
                    // pending status

                    if ($kyc_status_check->kyc_status != 1) {
                        User::where('id', auth()->user()->id)->update([
                            'kyc_status' => 2
                        ]);
                    }
                }
            }

            // END: of validation
            // id proof
            if ($created) {
                $user = User::select('name')->where('id', auth()->user()->id)->first();
                //notification mail to admin
                MailNotificationService::admin_notification([
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'type' => 'kyc',
                    'client_type' => 'ib',
                ]);

                // insert activity-----------------
                activity($request->perpose . " Kyc Upload")
                    ->causedBy(auth()->user()->id)
                    ->withProperties(KycVerification::find($created))
                    ->event('ib verification')
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been upload kyc");
                // end activity log-----------------
                return Response::json([
                    'status' => true,
                    'message' => 'KYC Success fully uploaded'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
}
