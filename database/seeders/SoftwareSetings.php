<?php

namespace Database\Seeders;

use App\Models\IbGroup;
use App\Models\IbSetting;
use App\Models\IbSetup;
use App\Models\KycIdType;
use App\Models\Leverage;
use App\Models\MtSerial;
use App\Models\OnlinePaymentMethod;
use App\Models\OtpSetting;
use App\Models\RequiredField;
use App\Models\SocialLogin;
use App\Models\SoftwareSetting;
use App\Models\TraderSetting;
use App\Models\User;
use App\Models\UserOtpSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoftwareSetings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::find(1);
        $users->kyc_id_type()->createMany([
            [
                'id_type' => 'passport',
                'group' => 'id proof',
                'created_by' => 1,
            ],
            [
                'id_type' => 'driving license',
                'group' => 'id proof',
                'created_by' => 1
            ],
            [
                'id_type' => 'adhar card',
                'group' => 'id proof',
                'created_by' => 1,
                'has_issue_date' => 0
            ],
            [
                'id_type' => 'bank statement',
                'group' => 'address proof',
                'created_by' => 1
            ],
            [
                'id_type' => 'bank certificate',
                'group' => 'address proof',
                'created_by' => 1
            ],
            [
                'id_type' => 'credit card statement',
                'group' => 'address proof',
                'created_by' => 1
            ],
        ]);
        // client groups demo
        $leverage = json_encode([1, 10, 20, 25, 50, 75, 100, 125, 150, 175, 200]);
        $users->client_group()->createMany([
            [
                'group_name' => 'demo_group_1',
                'group_id' => 'demo group 1',
                'server' => 'mt4',
                'account_category' => 'demo',
                'leverage' => $leverage,
                'max_leverage' => 200,
                'book' => 'A Book',
                'min_deposit' => 5.00,
                'deposit_type' => 'one time',
                'active_status' => 1,
                'stat' => 1,
                'visibility' => 'visible',
                'created_by' => 1,
            ],
            [
                'group_name' => 'demo_group_2',
                'group_id' => 'demo group 2',
                'server' => 'mt5',
                'account_category' => 'demo',
                'leverage' => $leverage,
                'max_leverage' => 200,
                'book' => 'A Book',
                'min_deposit' => 0,
                'deposit_type' => 'every time',
                'active_status' => 1,
                'stat' => 1,
                'visibility' => 'visible',
                'created_by' => 1,
            ],
            [
                'group_name' => 'live_group_2',
                'group_id' => 'live group 2',
                'server' => 'mt5',
                'account_category' => 'live',
                'leverage' => $leverage,
                'max_leverage' => 200,
                'book' => 'A Book',
                'min_deposit' => 0,
                'deposit_type' => 'every time',
                'active_status' => 1,
                'stat' => 1,
                'visibility' => 'visible',
                'created_by' => 1,
            ],
            [
                'group_name' => 'live_group_1',
                'group_id' => 'live group 1',
                'server' => 'mt5',
                'account_category' => 'live',
                'leverage' => $leverage,
                'max_leverage' => 200,
                'book' => 'A Book',
                'min_deposit' => 0,
                'deposit_type' => 'every time',
                'active_status' => 1,
                'stat' => 1,
                'visibility' => 'visible',
                'created_by' => 1,
            ],
        ]);
        MtSerial::create([
            'login_start' => 1,
            'login_end' => 10000,
            'last' => 10000,
            'login_gen' => 'custom',
            'server' => 'mt5'
        ]);

        // otp settings
        OtpSetting::create([
            'account_create' => 0,
            'deposit' => 0,
            'withdraw' => 0,
            'transfer' => 0,
            'admin_id' => 1
        ]);

        // online payment methods
        OnlinePaymentMethod::create([
            'name' => 0,
            'info' => json_encode([
                "PAYEE_ACCOUNT" => "",
                "MEMBER_ID" => "",
                "PAYEE_NAME" => ""
            ]),
            'status' => 0,
            'live_demo' => 0
        ]);

        //Ib settings 
        $ibSettingArr = [
            'Contest Feature',
            'KYC Verification System',
            'Support Ticket',
            'IB Admin',
            'Bonus Feature',
            'Daily Market Analysis',
            'Forex Signals',
            'Forex Calculators',
            'Balance Transfer To Trader'
        ];
        foreach ($ibSettingArr as $key => $value) {
            IbSetting::create([
                'settings' => $value,
                'status' => 1
            ]);
        }
        //Trader settings 
        $traderSettingArr = [
            'Trading Account Opening',
            'Trading Account Setting',
            'Deposits',
            'Withdrawals',
            'Trading Account Leverage Change',
            'Internal Balance Transfer',
            'External Balance Transfer',
            'Account To Wallet Balance Transfer',
            'Wallet To Account Balance Transfer',
            'Support Ticket',
            'Verification System',
            'User Admin',
            'Contest Feature',
            'Bonus Feature',
            'Daily Market Analysis',
            'Forex Signals',
            'Economic Calendar',
            'Forex Calculators',
        ];

        IbSetup::create([
            'ib_level' => 3,
            'require_sub_ib' => 3,
            'min_withdraw' => 0,
            'max_withdraw' => 0,
            'withdraw_period' => 'daily',
            'withdraw_kyc' => 1,
            'refer_kyc' => 1,
            'ib_commission_kyc' => 1,
        ]);

        foreach ($traderSettingArr as $key => $value) {
            TraderSetting::create([
                'settings' => $value,
                'status' => 1
            ]);
        }

        // leverage seeds
        Leverage::create([
            'leverage' => 20
        ]);
        Leverage::create([
            'leverage' => 35
        ]);
        Leverage::create([
            'leverage' => 50
        ]);
        Leverage::create([
            'leverage' => 75
        ]);
        Leverage::create([
            'leverage' => 100
        ]);
        Leverage::create([
            'leverage' => 150
        ]);
        Leverage::create([
            'leverage' => 175
        ]);
        Leverage::create([
            'leverage' => 200
        ]);
        Leverage::create([
            'leverage' => 300
        ]);
        Leverage::create([
            'leverage' => 500
        ]);
        Leverage::create([
            'leverage' => 1000
        ]);
        Leverage::create([
            'leverage' => 2000
        ]);

        // softwaresettings seeds
        SoftwareSetting::create([
            'email_template' => 'v2',
            'account_move' => 0
        ]);
        SocialLogin::updateOrCreate(
            [
                'id' => 1,
            ],
            [
                'facebook' => 0,
                'google' => 0,
                'mac' => 0,

            ]
        );
        // softwaresettings seeds
        RequiredField::create([
            'phone'     => 1,
            'gender'    => 1,
            'password'  => 1,
            'state'     => 1,
            'city'      => 1,
            'zip_code'  => 1,
            'address'   => 1
        ]);
    }
}
