<?php

namespace App\Services;

use App\Models\KycVerification;
use Illuminate\Support\Facades\DB;

class KycService
{
    public static function  __callStatic($name, $data)
    {
        // check kyc uploaded or not
        if ($name === 'has_kyc') {
            return (new self)->check_kyc_uploaded($data[0], $data[1]);
        }
        // kyc approved
        if ($name === 'kyc_approve') {
            return (new self)->kyc_update($data[0], $data[1], $data[2]);
        }
    }
    // check kyc uploaded or not
    private function check_kyc_uploaded($user_id, $type = null)
    {
        if ($user_id == null) {
            $user_id = auth()->user()->id;
        }
        switch ($type) {
            case 'id proof':
                $kyc = KycVerification::where('perpose', $type)->where('user_id', $user_id)->exists();
                if ($kyc) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'address proof':
                $kyc = KycVerification::where('perpose', $type)->where('user_id', $user_id)->exists();
                if ($kyc) {
                    return true;
                } else {
                    return false;
                }
                break;

            default:
                $kyc = KycVerification::where('user_id', $user_id)->exists();
                if ($kyc) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }
    // kyc update functions
    private function kyc_update($user_id = null, $perpose, $status)
    {
        if ($user_id == null) {
            $user_id = auth()->user()->id;
        }
        $kyc = KycVerification::where('perpose', $perpose)->where('user_id', $user_id)->first();
        $kyc->status  = $status;
        $kyc->approved_by  = auth()->user()->id;
        $kyc->approved_date = date('Y-m-d h:i:s', strtotime(now()));
        $update = $kyc->save();
        if ($update) {
            return true;
        } else {
            return false;
        }
    }
    // check id verificatino status
    public  static function id_verification_status($user_id, $perpose = 'id proof')
    {
        try {
            $kyc_statuses = KycVerification::where('user_id', $user_id)
                ->where('perpose', $perpose)
                ->get();  // Get all KYC verifications
            if ($kyc_statuses->isEmpty()) {
                return false; //'KYC status not found for the user ID';
            }
            $overall_status = 'Pending';  // Default status
            foreach ($kyc_statuses as $kyc_status) {
                $status = $kyc_status->status;
                // Update overall status based on the file status
                if ($status == 1) {
                    $overall_status = 'Approved';
                    break;  // Stop checking if any file is declined
                } elseif ($status == 0) {
                    $overall_status = 'Pending';
                    break;  // Stop checking if any file is declined
                } elseif ($status == 2) {
                    $overall_status = 'Declined';
                } elseif ($status == 3 && $overall_status != 'Declined') {
                    $overall_status = 'Expired';
                }
            }
            return $overall_status;
        } catch (\Throwable $th) {
            // throw $th;
            return false;
        }
    }
    // enable id proof form
    public  static function need_upload($perpose = 'id proof', $user_id)
    {
        try {
            $status = (new self)->id_verification_status($user_id, $perpose);
            // return $status;
            if (!$status) {
                return true;
            } elseif (strtolower($status) === 'declined') {
                return true;
            } elseif (strtolower($status) === 'expired') {
                return true;
            }
            return $status;
        } catch (\Throwable $th) {
            // throw $th;
            return true;
        }
    }
    // check how many document approved
    public  static function approved_both($user_id)
    {
        try {
            $status = KycVerification::select([
                DB::raw('SUM(CASE WHEN perpose = "id proof" AND status = 1 THEN 1 ELSE 0 END) as id_proof_status'),
                DB::raw('SUM(CASE WHEN perpose = "address proof" AND status = 1 THEN 1 ELSE 0 END) as address_proof_status')
            ])
                ->where('user_id', $user_id)
                ->whereIn('perpose', ['id proof', 'address proof'])
                ->first();

            if ($status->id_proof_status > 0 && $status->address_proof_status == 0) {
                return 'id approved';
            } elseif ($status->address_proof_status > 0 && $status->id_proof_status == 0) {
                return 'address approved';
            } elseif ($status->id_proof_status > 0 && $status->address_proof_status > 0) {
                return 'both approved';
            } else {
                return 'No approved proofs found';
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 'No approved proofs found';
        }
    }
    // chack have approved doc
    public  static function has_approved_doc($user_id)
    {
        try {
            $approved_doc = (new self)->approved_both($user_id);
            if (strtolower($approved_doc) === 'both approved') {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
}
