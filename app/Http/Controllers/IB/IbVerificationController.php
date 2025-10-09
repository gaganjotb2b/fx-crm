<?php

namespace App\Http\Controllers\IB;

use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\IB;
use App\Models\KycIdType;
use App\Models\KycVerification;
use App\Services\MailNotificationService;
use App\Models\User;
use App\Models\UserDescription;
use App\Services\AgeCalculatorService;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;

class IbVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $type = auth()->user()->type; // returns user
            switch ($type) {
                case 'trader':
                    $this->middleware(AllFunctionService::access('verification', 'trader'));
                    $this->middleware(AllFunctionService::access('my_admin', 'trader'));
                    break;

                default:
                    $this->middleware(AllFunctionService::access('verification', 'ib'));
                    $this->middleware(AllFunctionService::access('my_admin', 'ib'));
                    $this->middleware('is_ib'); // check the combined user is an IB
                    break;
            }
            return $next($request);
        });
    }
    //basic view----------------
    public function verification(Request $request)
    {
        $copyright = SystemConfig::select('copyright')->first();
        $kyc_id_type = KycIdType::where('group', 'id proof')->get();
        $kyc_address_type = KycIdType::where('group', 'address proof')->get();
        $user_descriptions = UserDescription::where('user_id', auth()->user()->id)->first();
        if (isset($user_descriptions->gender)) {
            $avatar = ($user_descriptions->gender === 'Male') ? 'avater-men.png' : 'avater-lady.png'; //<----avatar url
        } else {
            $avatar = 'avater-men.png';
        }
        // get all details of this user---------
        $user = User::where('users.id', auth()->user()->id)
            ->join('user_descriptions', 'users.id', '=', 'user_descriptions.user_id')
            ->join('countries', 'user_descriptions.country_id', '=', 'countries.id')
            ->select(
                'users.name',
                'users.email',
                'users.phone',
                'users.created_at',
                'countries.name as country',
                'users.email_verified_at'
            )
            ->first();
        // get last login------------------------
        // $login_activity = Activity::all()->last()->where('log_name', 'login')->where('causer_id', auth()->user()->id)->first();
        $login_activity = [];
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
            'ibs.ib-admins.verification',
            [
                'avatar' => $avatar,
                'id_type' => $kyc_id_type,
                'address_type' => $kyc_address_type,
                'user' => $user,
                'login_activity' => $login_activity,
                'kyc_status' => $kyc_verifiction_status,
                'email_verification_status' => $email_verification_status,
                'kyc_documents' => $kyc_documents,
                'copyright' => $copyright,
            ]
        );
    }
    // upload file-----------------------
    public function file_upload(Request $request)
    {
        try {
            $user = auth()->user();
            $kyc_id_type = KycIdType::find($request->document_type);
            $config = SystemConfig::select('kyc_back_part')->first();
            $kyc_status = User::select('kyc_status')->find($user->id);
    
            // Validation rules
            $rules = [
                'document_type' => 'required',
            ];
    
            if ($request->perpose === 'address proof') {
                $rules += [
                    'issue_date' => 'required|date',
                    'expire_date' => 'required|date',
                    'file_document' => 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:10240',
                ];
            } else {
                $rules += [
                    'file_front_part' => 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:10240',
                    'id_number' => 'required',
                ];
    
                if ($config->kyc_back_part == 1) {
                    $rules['file_back_part'] = 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:10240';
                } else {
                    $rules['file_back_part'] = 'nullable|file|mimes:jpeg,png,gif,pdf,jpg|max:10240';
                }
    
                if ($kyc_id_type && $kyc_id_type->has_issue_date == 1) {
                    $rules['issue_date'] = 'required|date';
                    $rules['expire_date'] = 'required|date';
                } else {
                    $rules['issue_date'] = 'nullable|date';
                    $rules['expire_date'] = 'nullable|date';
                }
            }
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Please fix the following errors!'
                ]);
            }
    
            // Validate issue/expire dates if applicable
            $ageChecker = new AgeCalculatorService();
    
            if ($kyc_id_type && $kyc_id_type->has_issue_date == 1) {
                if ($ageChecker->checkExpairDate($request->issue_date, 'invalid')) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['issue_date' => "Invalid issue date"],
                        'message' => 'Please fix the following errors!'
                    ]);
                }
    
                if ($ageChecker->checkExpairDate($request->expire_date)) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expire_date' => "Can't send Date Expired Document"],
                        'message' => 'Please fix the following errors!'
                    ]);
                }
            }
    
            $document_name = ['front_part' => '', 'back_part' => ''];
            $uploadPath = public_path('Uploads/kyc');
    
            // Address proof
            if ($request->perpose === 'address proof') {
                $exists = KycVerification::where('user_id', $user->id)
                    ->where('status', '!=', 2)
                    ->where('perpose', 'address proof')
                    ->exists();
    
                if ($exists) {
                    return response()->json([
                        'status' => false,
                        'message' => 'KYC already exists for address proof. Please contact your manager.'
                    ]);
                }
    
                $file = $request->file('file_document');
                $filename = time() . '_address.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $document_name['front_part'] = $filename;
    
            } else {
                // ID proof
                $exists = KycVerification::where('user_id', $user->id)
                    ->where('status', '!=', 2)
                    ->where('perpose', 'id proof')
                    ->exists();
    
                if ($exists) {
                    return response()->json([
                        'status' => false,
                        'message' => 'KYC already exists for ID proof. Please contact your manager.'
                    ]);
                }
    
                // Front part
                $front = $request->file('file_front_part');
                $filename_front = time() . '_front.' . $front->getClientOriginalExtension();
                $front->move($uploadPath, $filename_front);
                $document_name['front_part'] = $filename_front;
    
                // Back part (optional)
                if ($request->hasFile('file_back_part')) {
                    $back = $request->file('file_back_part');
                    $filename_back = time() . '_back.' . $back->getClientOriginalExtension();
                    $back->move($uploadPath, $filename_back);
                    $document_name['back_part'] = $filename_back;
                }
            }
    
            // Create KYC record
            $kyc = KycVerification::create([
                'user_id' => $user->id,
                'issue_date' => $request->issue_date ?? null,
                'exp_date' => $request->expire_date ?? null,
                'doc_type' => $request->document_type,
                'perpose' => $request->perpose,
                'id_number' => $request->id_number ?? null,
                'document_name' => json_encode($document_name),
            ]);
    
            if ($kyc) {
                if ($kyc_status->kyc_status != 1) {
                    User::where('id', $user->id)->update(['kyc_status' => 2]);
                }
    
                MailNotificationService::admin_notification([
                    'name' => $user->name,
                    'email' => $user->email,
                    'type' => 'kyc',
                    'client_type' => 'trader',
                ]);
    
                activity($request->perpose . " Kyc Upload")
                    ->causedBy($user->id)
                    ->withProperties($kyc)
                    ->event('trader verification')
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has uploaded KYC.");
    
                return response()->json([
                    'status' => true,
                    'message' => 'KYC successfully uploaded'
                ]);
            }
    
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Got a server error!',
                'error' => $th->getMessage() // helpful in dev, hide in prod
            ]);
        }
    }

    
    // upload file-----------------------
    // public function file_upload(Request $request)
    // {
    //     try {
    //         $kyc_id_type = KycIdType::find($request->document_type);
    //         $check_config = SystemConfig::select('kyc_back_part')->first();
    //         if ($request->perpose === 'address proof') {
    //             $validation_rules = [
    //                 'document_type' => 'required',
    //                 'issue_date' => 'required',
    //                 'expire_date' => 'required',
    //                 'file_document' => 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:10240', // Adjust the max file size as needed (in kilobytes).
    //             ];
    //         }
    //         // id proof
    //         else {
    //             $validation_rules = [
    //                 'document_type' => 'required',
    //                 'file_front_part' => 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:10240', // Adjust the max file size as needed (in kilobytes).
    //                 'id_number' => 'required',
    //             ];
    //             // check backpart reuired or not
    //             if ($check_config->kyc_back_part == 0) {
    //                 $validation_rules['file_back_part'] = 'nullable|file|mimes:jpeg,png,gif,pdf,jpg|max:10240'; // Adjust the max file size as needed (in kilobytes);
    //             } else {
    //                 $validation_rules['file_back_part'] = 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:10240'; // Adjust the max file size as needed (in kilobytes).
    //             }
    //             // check kyc id typ
    //             if (isset($kyc_id_type->has_issue_date) && ($kyc_id_type->has_issue_date == 1)) {
    //                 $validation_rules['issue_date'] = 'required';
    //                 $validation_rules['expire_date'] = 'required';
    //             } else {
    //                 $validation_rules['issue_date'] = 'nullable';
    //                 $validation_rules['expire_date'] = 'nullable';
    //             }
    //         }
    //         $getAgeOfInput = new AgeCalculatorService();

    //         $validator = Validator::make($request->all(), $validation_rules);
    //         if ($validator->fails()) {
    //             return Response::json([
    //                 'status' => false,
    //                 'errors' => $validator->errors(),
    //                 'message' => 'Please fix the following errors!'
    //             ]);
    //         }

    //         // end file size validation
    //         // issudate validation
    //         if ($kyc_id_type->has_issue_date) {
    //             if ($getAgeOfInput->checkExpairDate($request->issue_date, 'invalid')) {
    //                 return Response::json([
    //                     'status' => false,
    //                     'errors' => ['issue_date' => "Invalid issue date"],
    //                     'message' => 'Please fix the following errors!'
    //                 ]);
    //             }
    //             // expire date validation
    //             if ($getAgeOfInput->checkExpairDate($request->expire_date)) {
    //                 return Response::json([
    //                     'status' => false,
    //                     'errors' => ['expire_date' => "Can't send Date Expired Document"],
    //                     'message' => 'Please fix the following errors!'
    //                 ]);
    //             }
    //         }

    //         // address proof=>mime type validation
    //         $kyc_status_check = User::select('kyc_status')->where('id', auth()->user()->id)->first();
    //         if ($request->perpose === 'address proof') {
    //             // check address proof kyc exist or not
    //             $check_exist = KycVerification::where('user_id', auth()->user()->id)->where('status', '!=', 2)->where('perpose', 'address proof')->exists();
    //             if ($check_exist) {
    //                 return Response::json([
    //                     'status' => false,
    //                     'message' => 'KYC already exist for address proof, Please contact your manager'
    //                 ]);
    //             } else {
    //                 $file_document = time() . '.' . $request->file_document->getClientOriginalExtension();
    //                 $request->file_document->move(public_path('Uploads/kyc'), $file_document);
    //                 // return $file_document;
    //                 $created = KycVerification::create([
    //                     'user_id' => auth()->user()->id,
    //                     'issue_date' => $request->issue_date,
    //                     'exp_date' => $request->expire_date,
    //                     'doc_type' => $request->document_type,
    //                     'perpose' => $request->perpose,
    //                     'document_name' => json_encode(['front_part' => $file_document, 'back_part' => ''])
    //                     // 'document_name' => json_encode(['front_part' => $filename_address, 'back_part' => ''])
    //                 ])->id;
    //                 // pending status

    //                 if ($kyc_status_check->kyc_status != 1) {
    //                     User::where('id', auth()->user()->id)->update([
    //                         'kyc_status' => 2
    //                     ]);
    //                 }
    //             }
    //         } else {
    //             // id proof
    //             $check_exist = KycVerification::where('user_id', auth()->user()->id)->where('status', '!=', 2)->where('perpose', 'id proof')->exists();
    //             if ($check_exist) {
    //                 return Response::json([
    //                     'status' => false,
    //                     'message' => 'KYC already exist for ID proof, Please contact your manger'
    //                 ]);
    //             } else {
    //                 $file_front_part = time() . '.' . $request->file_front_part->getClientOriginalExtension();
    //                 $request->file_front_part->move(public_path('Uploads/kyc'), $file_front_part);
                    
    //                 // store back part
    //                 if ($request->file('file_back_part')) {
    //                     $file_back_part = time() . '.' . $request->file_back_part->getClientOriginalExtension();
    //                     $request->file_back_part->move(public_path('Uploads/kyc'), $file_back_part);
    //                     // return file_back_part;
    //                 }

    //                 $document_name = [
    //                     'front_part' => $file_front_part,
    //                     'back_part' => ($request->file('file_back_part')) ? $file_back_part : '',
    //                 ];
    //                 $created = KycVerification::create([
    //                     'user_id' => auth()->user()->id,
    //                     'issue_date' => (isset($kyc_id_type->has_issue_date) && ($kyc_id_type->has_issue_date == 1)) ? $request->issue_date : null,
    //                     'exp_date' => (isset($kyc_id_type->has_issue_date) && ($kyc_id_type->has_issue_date == 1)) ? $request->expire_date : null,
    //                     'doc_type' => $request->document_type,
    //                     'perpose' => $request->perpose,
    //                     'id_number' => $request->id_number,
    //                     'document_name' => json_encode($document_name)
    //                 ])->id;
    //                 // pending status

    //                 if ($kyc_status_check->kyc_status != 1) {
    //                     User::where('id', auth()->user()->id)->update([
    //                         'kyc_status' => 2
    //                     ]);
    //                 }
    //             }
    //         }

    //         // END: of validation
    //         // id proof

    //         if ($created) {
    //             $user = User::select('name')->where('id', auth()->user()->id)->first();
    //             //notification mail to admin
    //             // MailNotificationService::notification('kyc', 'trader', 1, $user->name, null);
    //             MailNotificationService::admin_notification([
    //                 'name' => auth()->user()->name,
    //                 'email' => auth()->user()->email,
    //                 'type' => 'kyc',
    //                 'client_type' => 'trader'
    //             ]);
    //             // insert activity-----------------
    //             activity($request->perpose . " Kyc Upload")
    //                 ->causedBy(auth()->user()->id)
    //                 ->withProperties(KycVerification::find($created))
    //                 ->event('trader verification')
    //                 ->performedOn($user)
    //                 ->log("The IP address " . request()->ip() . " has been upload kyc");
    //             // end activity log-----------------
    //             return Response::json([
    //                 'status' => true,
    //                 'message' => 'KYC Success fully uploaded'
    //             ]);
    //         }
    //         return Response::json([
    //             'status' => false,
    //             'message' => 'Something went wrong please try again later'
    //         ]);
    //     } catch (\Throwable $th) {
    //         throw $th;
    //         return Response::json([
    //             'status' => false,
    //             'message' => 'Got a server error!'
    //         ]);
    //     }
    // }
}
