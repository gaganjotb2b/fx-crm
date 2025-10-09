<?php

namespace Database\Seeders;

use App\Models\OtpSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class otpSettings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OtpSetting::create([ 
            'account_create' => 1,
            'deposit' => 1,
            'withdraw' => 1,
            'transfer' => 1,
            'admin_id' => null, 
        ]);
    }
}
