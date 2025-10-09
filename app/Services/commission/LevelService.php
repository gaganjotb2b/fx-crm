<?php

namespace App\Services\commission;

use App\Models\IB;

class LevelService
{
    public static function get_level($ib_id, $reference_id, $level = 0)
    {
        try {
            if ($ib_id == $reference_id) {
                return $level;
            }
            $ib = IB::where('ib_id', $ib_id)->get();
            if ($ib) {
                foreach ($ib as $value) {
                    $result = self::get_level($value->reference_id, $reference_id, $level + 1);
                    if ($result !== false) {
                        return $result;
                    }
                }
            }
            return false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
