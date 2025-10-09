<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\systems\AdminLogService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CryptoCurrencys extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(1);
        $user->crypto_currency()->createMany(
            [
                [
                    'symbol' => 'USDT',
                    'currency' => 'ERC20',
                    'ip_address' => request()->ip(),
                    'status' => 'active',
                    'admin_log' => AdminLogService::admin_log(),
                ],
                [
                    'symbol' => 'USDT',
                    'currency' => 'TRC20',
                    'ip_address' => request()->ip(),
                    'status' => 'active',
                    'admin_log' => AdminLogService::admin_log(),
                ],
                [
                    'symbol' => 'BTC',
                    'currency' => 'bitcoin',
                    'ip_address' => request()->ip(),
                    'status' => 'active',
                    'admin_log' => AdminLogService::admin_log(),
                ],
                [
                    'symbol' => 'ETH',
                    'currency' => 'ethereum',
                    'ip_address' => request()->ip(),
                    'status' => 'active',
                    'admin_log' => AdminLogService::admin_log(),
                ],
            ]
        );
    }
}
