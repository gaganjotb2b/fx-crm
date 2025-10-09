<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KycIdType;
use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class KycConfigController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'purpose'=>'nullable|in:id proof, address proof'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status'=>false,
                    'message'=>$validator->errors()->first('purpose'),
                    'data'=>[]
                ]);
            }
            $result = KycIdType::select('id','id_type as document_type', 'group as purpose', 'has_issue_date as require_issue_date');
            if ($request->input('purpose')) {
                $result = $result->where('group',$request->input('purpose'));
            }
            $result = $result->get();
            return Response::json([
                'status' => true,
                'data' => $result
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'status' => false,
                'data' => [],
                'error' => $th->getMessage()
            ]);
        }
    }
    // kcy document status
    public function kyc_document_status(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $trader_user = $user;
            if (strtolower($user->type) === 'ib') {
                $trader_user = $user->TraderAccount()->first();
            }
            
            
            $id_document = KycVerification::where('user_id', $trader_user->id)
                ->with(['documentType' => function ($query) {
                    $query->select('id','id_type', 'group');
                }])->where('perpose', 'id proof')->latest()->first();
                
            $address_document = KycVerification::where('user_id', $trader_user->id)
                ->with(['documentType' => function ($query) {
                    $query->select('id','id_type', 'group');
                }])
                ->where('perpose', 'address proof')->latest()->first();
            // return $address_document;
            $id_proof_status = $address_proof_status = $id_document_name = $address_document_name = null;
            if ($id_document) {
                if ($id_document->status === 0) {
                    $id_proof_status = 'Pending';
                } elseif ($id_document->status === 1) {
                    $id_proof_status = 'Approved';
                } elseif ($id_document->status == 2) {
                    $id_proof_status = 'Declined';
                }
                $id_document_name = $id_document->documentType->id_type;
            }
            if ($address_document) {
                if ($address_document->status === 0) {
                    $address_proof_status = 'Pending';
                } elseif ($address_document->status === 1) {
                    $address_proof_status = 'Approved';
                } elseif ($address_document->status == 2) {
                    $address_proof_status = 'Declined';
                }
                $address_document_name = $address_document->documentType->id_type;
            }
            return Response::json([
                'status' => true,
                'document_status' => [
                    'id_proof' => [
                        'document' => $id_document_name,
                        'status' => $id_proof_status
                    ],
                    'address_proof' => [
                        'document' => $address_document_name,
                        'status' => $address_proof_status
                    ],
                ]
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
