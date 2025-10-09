<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KycIdType;
use App\Models\KycVerification;
use App\Models\User;
use App\Services\api\FileApiService;
use App\Services\MailNotificationService;
use App\Services\systems\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class KycUploadController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            // perpose validation
            $validator = Validator::make($request->all(), [
                'purpose' => 'required|exists:kyc_id_type,group|string',
                'document_type' => 'required|integer|max:30|exists:kyc_id_type,id',
                'issue_date' => 'nullable|date|before:today',
                'expire_date' => 'nullable|date|after:today',
                'file_front' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2084',
                'file_back' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2084',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Validation error, please fix the following error',
                    'errors' => $validator->errors(),
                ]);
            }
            // file upload to contabo
            $file_front = $request->file('file_front');
            $extention = $file_front->getClientOriginalExtension();
            $file_front_name = Uuid::uuid4()->toString() . time() . 'kyc-front.' . $extention;
            $client = FileApiService::s3_clients();
            $client->putObject([
                'Bucket' => FileApiService::contabo_bucket_name(),
                'Key' => $file_front_name,
                'Body' => file_get_contents($file_front)
            ]);
            // upload backpart if exists
            $file_back = $request->file('file_back');
            $file_back_name = '';
            if ($file_back) {
                $extention = $file_back->getClientOriginalExtension();
                $file_back_name = Uuid::uuid4()->toString() . time() . 'kyc-back.' . $extention;
                $client->putObject([
                    'Bucket' => FileApiService::contabo_bucket_name(),
                    'Kyc' => $file_back_name,
                    'Body' => file_get_contents($file_back)
                ]);
            }
            
            // store in database
            $create = KycVerification::create([
                'user_id' => $trader_user->id,
                'issue_date' => date('Y-m-d', strtotime($request->input('issue_date'))),
                'expire_date' => date('Y-m-d',strtotime($request->input('expire_date'))),
                'doc_type' => $request->input('document_type'),
                'perpose' => $request->input('purpose'),
                'status' => 0,
                'document_name' => json_encode([
                    'front_part' => $file_front_name,
                    'back_part' => $file_back_name,
                ]),
            ]);
            // return $create;
            if ($create) {
                MailNotificationService::admin_notification([
                    'user_id' => $trader_user->id,
                    'name' => $trader_user->name,
                    'email' => $trader_user->email,
                    'type' => 'kyc',
                    'client_type' => 'trader',
                ]);
                NotificationService::system_notification([
                    'type' => 'kyc_upload',
                    'user_id' => $trader_user->id,
                    'user_type' => 'trader',
                    'table_id' => $create->id,
                    'category' => 'client',
                ]);
                // insert activity-----------------
                // activity($request->perpose . " Kyc Upload")
                //     ->causedBy(auth()->user()->id)
                //     ->withProperties(KycVerification::find($created))
                //     ->event('ib verification')
                //     ->performedOn($user)
                //     ->log("The IP address " . request()->ip() . " has been upload kyc");
                // // end activity log-----------------

                $trader_user->kyc_status = 2;
                $trader_user->save();
                return Response::json([
                    'status' => false,
                    'message' => 'KYC successfully uploaded',
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Something went wrong please try again later'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error, contact for support',
                'error' => $th->getMessage(),
            ]);
        }
    }
}
