<?php

namespace Database\Seeders;

use App\Models\CryptoCurrency;
use App\Models\OnlineBank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            CountrySeeder::class,
            Administration::class,
            MailTemplate::class,
            ThemeSeeder::class,
            SoftwareSetings::class,
            PAMM::class,
            otpSettings::class,
            passwordSettingsSeed::class,
            NotificationSeeder::class,
            SmtpSeeder::class,
            TriggerFlugs::class,
            DepositSettings::class,
            WithdrawSettings::class,
            OnlineBanks::class,
            CurrencyRate::class,
            CryptoCurrencys::class,
        ]);
    }
}
