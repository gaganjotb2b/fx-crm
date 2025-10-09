<?php

namespace Database\Seeders;

use App\Services\currency\GoogleCurrencyService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencyRate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // insert currency rate
        GoogleCurrencyService::currency_rate();
    }
}
