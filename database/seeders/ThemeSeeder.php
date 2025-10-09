<?php

namespace Database\Seeders;

use App\Models\admin\SystemConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemConfig::create([
            
            'platform_type' => 'mt5',
            'server_type' => json_encode(
                [
                    "mt4_server_type" => "",
                    "mt5_server_type" => "live"
                ]
            ),
            'platform_download_link' => json_encode([

                "mt4_download_link" => "",
                "mt5_download_link" => "http://127.0.0.1:8000/admin/settings/api_configuration"

            ]),
            'com_name' => 'IT Corner Online Limited',
            'com_license' => 'IT Corner 12121',
            'com_email' => json_encode([

                "com_email_1" => "demo@company.com",
                "com_email_2" => "demo@company.com"

            ]),
            'com_phone' => json_encode([

                "com_phone_1" => "02154887545",
                "com_phone_2" => "2154455221445"

            ]),
            'com_website' => '',
            'com_authority' => 'demo authority',
            'com_address' => 'Sun Fruncisco',
            'copyright' => ucwords(config('app.name')),
            'support_email' => default_support_email(),
            'auto_email' => 'auto@'.join_app_name().'.net',
            'com_social_info' => json_encode([

                "skype" => "",
                "twitter" => "",
                "youtube" => "",
                "facebook" => "",
                "linkedin" => "",
                "livechat" => "",
                "telegram" => ""

            ]),
            'create_meta_acc' => 1,
            'acc_limit' => 3,
            'notification_tour' => 1,
        ]);
    }
}
