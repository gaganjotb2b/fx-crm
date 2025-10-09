<?php

namespace App\Services;

use App\Models\admin\SystemConfig;
use Illuminate\Support\Facades\DB;

class CompanyInfoService
{
    public function __call($name, $data)
    {
        if ($name == 'company_info') {
            return $this->GetCompanyInfo($data[0]);
        }
    }
    public static function __callStatic($name, $data)
    {
        if ($name == 'company_info') {
            return (new self)->GetCompanyInfo();
        }
    }
    private function GetCompanyInfo()
    {
        $company_info = SystemConfig::all();
        $data = [];
        foreach ($company_info as $key => $value) {
            $platform_download_link = json_decode($value->platform_download_link);
            $company_phone = json_decode($value->com_phone);
            $company_social_info = json_decode($value->com_social_info);
            $data = [
                'platform' => $value->platform_type,
                'mt4_doanload_link' => $platform_download_link->mt4_download_link,
                'mt5_doanload_link' => $platform_download_link->mt4_download_link,
                'company_name' => $value->com_name,
                'company_license' => $value->com_license,
                'company_email' => $value->com_email,
                'company_phone_1' => $company_phone->com_phone_1,
                'company_phone_2' => $company_phone->com_phone_2,
                'company_website' => $value->com_website,
                'company_authority' => $value->com_authority,
                'company_address' => $value->com_address,
                'copyright' => $value->copyright,
                'support_email' => $value->support_email,
                'auto_email' => $value->auto_email,
                'facebook' => $company_social_info->facebook,
                'twitter' => $company_social_info->twitter,
                'youtube' => $company_social_info->youtube,
                'trader_login' => route('login'),
                'ib_login' => route('ib.login'),
                'activation_link' => '',
            ];
            return $data;
        }
    }
}
