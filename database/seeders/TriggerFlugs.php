<?php

namespace Database\Seeders;

use App\Models\TriggerFlug;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TriggerFlugs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        TriggerFlug::create([
            'id'=>1,
            'admin_bank'=>0,
            'client_bank'=>0,
            'deposit'=>0,
            'withdraw'=>0,
            'admin'=>0,
            'admin_notification'=>0,
            'other_transaction'=>0,
        ]);
    }
}
