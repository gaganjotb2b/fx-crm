<?php

namespace Database\Seeders;

use App\Models\DepositSetting;
use App\Models\DepositSettings as ModelsDepositSettings;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepositSettings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DepositSetting::create(
        //     [
        //         'deposit_method'
        //     ]
        // )
        $users = User::find(1);
        $users->deposit_settings()->createMany([
            [
                'deposit_method' => 'bank',
                'min_amount' => 0.00,
                'max_amount' => 0.00,
                'created_by' => 1
            ], [
                'deposit_method' => 'help2pay',
                'min_amount' => 0.00,
                'max_amount' => 0.00,
                'created_by' => 1
            ],
            [
                'deposit_method' => 'praxis',
                'min_amount' => 0.00,
                'max_amount' => 0.00,
                'created_by' => 1
            ],
            [
                'deposit_method' => 'paypal',
                'min_amount' => 0.00,
                'max_amount' => 0.00,
                'created_by' => 1
            ],
            [
                'deposit_method' => 'neteler',
                'min_amount' => 0.00,
                'max_amount' => 0.00,
                'created_by' => 1
            ],
            [
                'deposit_method' => 'gcash',
                'min_amount' => 0.00,
                'max_amount' => 0.00,
                'created_by' => 1
            ],
            [
                'deposit_method' => 'm2pay',
                'min_amount' => 0.00,
                'max_amount' => 0.00,
                'created_by' => 1
            ],
            [
                'deposit_method' => 'crypto',
                'min_amount' => 0.00,
                'max_amount' => 0.00,
                'created_by' => 1
            ],
            [
                'deposit_method' => 'perfect_money',
                'min_amount' => 0.00,
                'max_amount' => 0.00,
                'created_by' => 1
            ],
            [
                'deposit_method' => 'b2b',
                'min_amount' => 0.00,
                'max_amount' => 0.00,
                'created_by' => 1
            ]
        ]);
    }
}
