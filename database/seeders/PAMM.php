<?php

namespace Database\Seeders;

use App\Models\Traders\PammSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PAMM extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PammSetting::create([
            'profit_share_commission_value'=>1,
            'maximum_profit_share_value'=>1,
            'minimum_profit_share_value'=>1,
            'minimum_account_balance'=>1,
            'minimum_wallet_balance'=>1,
            'minimum_deposit'=>1,
            'pamm_account_limit'=>1,
            'slave_limit'=>1,
            'master_limit'=>1,
            'pamm_global_deposit'=>1,
            'profit_share_value'=>1,
            'pamm_requirement'=>1,
            'profit_share_commission_status'=>1,
            'pamm_requirement_status'=>1,
            'manual_approve_pamm_reg'=>1,
            'profit_share_commission'=>1,
            'flexible_profit_share_status'=>1,
            'profit_share_status'=>1,
            'global_pamm_status'=>1,
            'profit_share_margin_value'=>1,
        ]);
    }
}
