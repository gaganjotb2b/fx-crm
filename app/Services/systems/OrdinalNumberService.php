<?php

namespace App\Services\systems;

use Exception;

class OrdinalNumberService
{
    public  static function getOrdinal($number)
    {
        $suffix = 'th';
        if ($number % 100 >= 11 && $number % 100 <= 13) {
            $suffix = 'th';
        } else {
            switch ($number % 10) {
                case 1:
                    $suffix = 'st';
                    break;
                case 2:
                    $suffix = 'nd';
                    break;
                case 3:
                    $suffix = 'rd';
                    break;
            }
        }

        return $number . $suffix;
    }
}
