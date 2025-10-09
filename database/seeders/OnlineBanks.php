<?php

namespace Database\Seeders;

use App\Models\OnlineBank;
use App\Models\User;
use App\Services\systems\AdminLogService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OnlineBanks extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // online banks for help2pay
        $user = User::find(1);
        $user->online_banks()->createMany([
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'AFF',
                'bank_name' => 'Affin Bank',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'bank_code' => 'DANAQRIS',
                'bank_name' => 'DANA QRIS',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'bank_code' => 'GOPAYQRIS',
                'bank_name' => 'GO PAY QRIS',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'bank_code' => 'ISTB',
                'bank_name' => 'IDR Semi Deposit',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'bank_code' => 'LINKAJAQRIS',
                'bank_name' => 'LINK AJA QRIS',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'bank_code' => 'OVOQRIS',
                'bank_name' => 'OVO QRIS',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'bank_code' => 'QRIS',
                'bank_name' => 'QRIS',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'bank_code' => 'SHOPEEQRIS',
                'bank_name' => 'SHOPEE PAY QRIS',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'bank_code' => 'BCA',
                'bank_name' => 'Bank Central Asia',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'bank_code' => 'BNI',
                'bank_name' => 'Bank Negara Indonesia',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'bank_code' => 'BRI',
                'bank_name' => 'Bank Rakyat Indonesia',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Indonesia',
                'currency' => 'IDR',
                'bank_code' => 'MDR',
                'bank_name' => 'Mandiri Bank',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'IDR',
                'bank_code' => 'CIMBN',
                'bank_name' => 'CIMB Niaga',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'ALB',
                'bank_name' => 'Alliance Bank Malaysia Berhad',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'AMB',
                'bank_name' => 'AmBank Group',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'BIMB',
                'bank_name' => 'Bank Islam Malaysia Berha',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'BSN',
                'bank_name' => 'Bank Simpanan Nasional',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'CIMB',
                'bank_name' => 'CIMB Bank Berhad',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'HLB',
                'bank_name' => 'Hong Leong Bank Berhad',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'HSBC',
                'bank_name' => 'HSBC Bank (Malaysia) Berhad',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'OCBC',
                'bank_name' => 'OCBC Bank (Malaysia) Berhad',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'MBB',
                'bank_name' => 'Maybank Berhad',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'PBB',
                'bank_name' => 'Public Bank Berhad',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'RHB',
                'bank_name' => 'RHB Banking Group',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
            [
                'country' => 'Malaysia',
                'currency' => 'MYR',
                'bank_code' => 'UOB',
                'bank_name' => 'United Overseas Bank (Malaysia) Bhd',
                'status' => 'active',
                'ip_address' => request()->ip(),
                'admin_log' => AdminLogService::admin_log(),
            ],
        ]);
    }
}
