<?php

namespace App\Services;

use DateTime;
use Illuminate\Support\Facades\Response;

class AgeCalculatorService
{
    static function getAgeDiffer($birthDate,$difY=18){
        $currentDate = date("Y-m-d");
        $difY = $difY-1;
        $age = date_diff(date_create($birthDate), date_create($currentDate));
        $defAge = $age->format("%y");
        if($defAge > $difY){
            return false;
        }
        else{
            return true;
        }
    }

    static function checkExpairDate($inputDate,$checkType='expair'){
        // checkType = expair/invalid 
        $today = date("Y-m-d");
        $expire = $inputDate; 
        $today_time = strtotime($today);
        $expire_time = strtotime($expire);
        if($checkType == "invalid"){
            if ($expire_time > $today_time) { 
                return true;    
            }
            else{
                return false;
            }
        }
        else{
            if ($expire_time < $today_time) { 
                return true;    
            }
            else{
                return false;
            }
        }
       
    }
}
