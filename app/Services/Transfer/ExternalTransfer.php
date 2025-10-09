<?php

namespace App\Services\Transfer;

use App\Models\ExternalFundTransfers;

class ExternalTransfer
{
    // get external transfer
    public static function external_transfer($data)
    {
        $user_id = $data['user_id'];
        $external_transfer = ExternalFundTransfers::where(function ($query) use ($user_id) {
            $query->where('sender_id', $user_id)
                ->where('receiver_id', $user_id);
        });
        
    }
}
