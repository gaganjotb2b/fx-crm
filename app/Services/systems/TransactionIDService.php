<?php

namespace App\Services\systems;

use Exception;

class TransactionIDService
{
    public static function generateRandomTransactionID()
    {
        // Generate 16 bytes of random data (16 bytes = 32 characters in hexadecimal representation)
        $randomBytes = random_bytes(16);

        // Convert random bytes to hexadecimal
        $transactionID = bin2hex($randomBytes);

        return $transactionID;
    }
}
