<?php

namespace App\Services;

use App\Models\TraderSetting;

class checkSettingsService
{


    public function TraderSettings($SettingsNames)
    {

        $allsetting = explode(',', $SettingsNames);
        foreach ($allsetting as $key => $value) {
            if ($this->TraderSettingsSingle($value) == true) {
                return true;
            }
        }
        return false;
    }

    private function TraderSettingsSingle($SettingsName)
    {
        $query = TraderSetting::where('settings', $SettingsName);
        if ($query->count() != 0) {
            $sst = $query->select('status')->first();
            if ($sst->status == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
