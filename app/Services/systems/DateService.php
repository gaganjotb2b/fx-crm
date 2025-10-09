<?php

namespace App\Services\systems;

use DateInterval;
use DateTime;

class DateService
{
    public static function conver_date($days)
    {
        try {
            $result = '';
            $years = intval($days / 365);
            $days = $days % 365;

            $months = intval($days / 30);
            $days = $days % 30;
            if ($years > 0) {
                $result .= $years . ' Years' . ($months > 0) ? ', ' : '';
            }
            if ($months > 0) {
                $result .= $months . ' Months' . (($days > 0) ? ', ' : '');
            }
            if ($days > 0) {
                $result .= $days . ' Days';
            }

            return $result;
        } catch (\Throwable $th) {
            // throw $th;
            return $days . 'Days';
        }
    }
    public static function addDurationToDate($days = 0, $months = 0, $years = 0)
    {
        // Get the current date
        $currentDate = new DateTime();

        // Add the specified duration
        $currentDate->add(new DateInterval("P{$years}Y{$months}M{$days}D"));

        // Return the calculated date as a string
        return $currentDate->format('Y-m-d');
    }
}
