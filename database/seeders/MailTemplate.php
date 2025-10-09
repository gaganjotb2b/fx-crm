<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;
use App\Models\User;

class MailTemplate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::find(1);
        $users->email_templates()->createMany([
            [
                'name' => 'mail-kyc-decline',
                'use_for' => 'kyc decline',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-approve-deposit-request',
                'use_for' => 'approve-deposit',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-withdraw-approve-request',
                'use_for' => 'withdraw-approve-request',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-balance-approve-request',
                'use_for' => 'balance-approve',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-balance-decline-request',
                'use_for' => 'balance-decline',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-change-password',
                'use_for' => 'change password',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-change-transaction-pin',
                'use_for' => 'change-transaction-pin',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-decline-deposit-request',
                'use_for' => 'decline-request',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-ib-withdraw-declined',
                'use_for' => 'ib-withdraw-decline',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-ib-transfer-approve',
                'use_for' => 'ib-transfer-approve',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-ib-transfer-decline',
                'use_for' => 'ib-transfer-decline',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-ib-approve-requests',
                'use_for' => 'ib-verify-approve',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-ib-decline-request',
                'use_for' => 'ib-verify-decline',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-ib-approve-request',
                'use_for' => 'ib-verify-approve',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-kyc-approve-request',
                'use_for' => 'kyc-approve-request',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-change-password',
                'use_for' => 'kyc decline',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-reset-transaction-pin',
                'use_for' => 'reset transaction pin',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-withdraw-decline-request',
                'use_for' => 'withdraw-decline-request',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-approve-deposit-request',
                'use_for' => 'add-credit',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-update-profile',
                'use_for' => 'update-profile',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-resend-verification-email',
                'use_for' => 'resent-verification-email',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-signup',
                'use_for' => 'signup',
                'status' => 'se',
                'created_by' => 1
            ],
            // finance ----------------------
            [
                'name' => 'mail-add-credit',
                'use_for' => 'credit-add',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-crypto-address-generate',
                'use_for' => 'crypto-add-for-it-corner',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-withdraw-request-recieved',
                'use_for' => 'withdraw-request',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-crypto-withdraw-request-recieved',
                'use_for' => 'crypto-withdraw-request',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-crypto-withdraw-notification-for-itcorner',
                'use_for' => 'crypto-withdraw-notify-for-itc',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-balance-transfer',
                'use_for' => 'balance-transfer',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-admin-mail-change',
                'use_for' => 'admin-mail-change',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-transaction-pass-reset',
                'use_for' => 'transaction-pass-reset',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-crypto-address-create',
                'use_for' => 'crypto-add-generate',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-otp',
                'use_for' => 'otp-verification',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-trader-password-change',
                'use_for' => 'trader-password-change',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-open-demo-account',
                'use_for' => 'open-demo-account',
                'status' => 'se',
                'created_by' => 1
            ],

            [
                'name' => 'mail-crypto-deposit-request',
                'use_for' => 'crypto-deposit-request',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-bank-deposit-request',
                'use_for' => 'bank-deposit-request',
                'status' => 'se',
                'created_by' => 1
            ],

            [
                'name' => 'mail-ib-withdraw-approve',
                'use_for' => 'ib-withdraw-approve',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-ib-withdraw-approve',
                'use_for' => 'ib-withdraw-approve',
                'status' => 'se',
                'created_by' => 1
            ],

            [
                'name' => 'mail-user-kyc-request-profile-update',
                'use_for' => 'kyc-proflle-update',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-kyc-decline-request',
                'use_for' => 'kyc-decline-request',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-change-master-password',
                'use_for' => 'change-master-password',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-change-investor-password',
                'use_for' => 'change-investor-password',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-resend-account-credentials',
                'use_for' => 'resend-account-credentials',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-user-deposit-request-amount',
                'use_for' => 'user-deposit-request-amount',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-user-withdraw-request-amount',
                'use_for' => 'user-withdraw-request-amount',
                'status' => 'se',
                'created_by' => 1
            ],

            [
                'name' => 'mail-user-notification',
                'use_for' => 'user-notification',
                'status' => 'se',
                'created_by' => 1
            ],

            [
                'name' => 'mail-combine-app',
                'use_for' => 'combine-app',
                'status' => 'se',
                'created_by' => 1
            ],
            [
                'name' => 'mail-remove-combine-access',
                'use_for' => 'remove-combine-app',
                'status' => 'se',
                'created_by' => 1
            ],


        ]);
    }
}
