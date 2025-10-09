<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WithdrawSettings  extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = User::find(1);
        $users->withdraw_settings()->createMany([
            [
                'withdraw_method'   => 'bank',
                'min_amount'    => 0.00,
                'max_amount'    => 0.00,
                'created_by'    => 1
            ],
            [
                'withdraw_method'   => 'crypto',
                'min_amount'    => 0.00,
                'max_amount'    => 0.00,
                'created_by'    => 1
            ],
            [
                'withdraw_method'   => 'paypal',
                'min_amount'    => 0.00,
                'max_amount'     => 0.00,
                'created_by'    => 1
            ], [
                'withdraw_method'   => 'gcash',
                'min_amount'    => 0.00,
                'max_amount'    => 0.00,
                'created_by'    => 1
            ]
        ]);
    }
}
