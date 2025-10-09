<?php

namespace App\Services\commission;

use App\Models\CommissionStatus;

class AllCommissionService
{
    public static   function commission_status($ticket)
    {
        try {
            $result = CommissionStatus::where('ticket', $ticket)->select('status')->first();
            return $result->status;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
