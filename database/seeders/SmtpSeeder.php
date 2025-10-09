<?php

namespace Database\Seeders;

use App\Models\SmtpSetup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmtpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        SmtpSetup::create([
            'mail_driver' => 'smtp',
            'host' => 'smtp.mailtrap.io',
            'port' => 2525,
            'mail_user' => 'ed9bee8911dfb2',
            'mail_password' => 'c214d1eb7d2ae1',
            'mail_encryption' => 'tls',
        ]);
    }
}
