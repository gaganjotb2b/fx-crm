<?php

namespace Database\Seeders;

use App\Models\PasswordSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class passwordSettingsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PasswordSettings::create([ 
            'master_password' => 1,
            'investor_password' => 1,
            'leverage' => 1, 
            'admin_id' => null, 
        ]);
    }
}
