<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notification::truncate();
        $notification = [
            [
                "type"=> "withdraw",
                "description"=> "Trader Withdraw Notification",
                "notification_body"=> "You get a withdraw request from user below description",
                "notification_header"=> null,
                "notification_footer"=> "NB: If you dont know why this request get , or any confution please decline this",
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],

            [
                "type"=> "deposit",
                "description"=> "Trader Deposit Notification",
                "notification_body"=> "You get a deposit request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],

            [
                "type"=> "balance transfer",
                "description"=> "Trader Balance Transfer Notification",
                "notification_body"=> "You get a balance request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "kyc",
                "description"=> "Trader KYC Notification",
                "notification_body"=> "You get a KYC request from You software, that details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "registration",
                "description"=> "Trader Registration Notification",
                "notification_body"=> "You get a registration request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],

            [
                "type"=> "demo registration",
                "description"=> "Demo Registration Notification",
                "notification_body"=> "You get a demo registration request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "registration",
                "description"=> "IB Registration Notification",
                "notification_body"=> "You get a IB registration request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "withdraw",
                "description"=> "IB Withdraw Notification",
                "notification_body"=> "You get a withdraw request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "balance transfer",
                "description"=> "IB Balance Transfer Notification",
                "notification_body"=> "You get a balance request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "account to wallet transfer",
                "description"=> "Trader account to wallet Transfer Notification",
                "notification_body"=> "An user transfer balance account to wallet that details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "wallet to account transfer",
                "description"=> "Trader wallet to account Transfer Notification",
                "notification_body"=> "An user transfer balance wallet to account that details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "bank add",
                "description"=> "Trader bank add notification",
                "notification_body"=> "An user add a new bank please approved it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "bank add",
                "description"=> "IB bank add notification",
                "notification_body"=> "An user add a new bank please approved it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "bank delete",
                "description"=> "Trader bank delete notification",
                "notification_body"=> "An user delete a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "bank delete",
                "description"=> "IB bank delete notification",
                "notification_body"=> "An user delete a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "bank update",
                "description"=> "Trader bank update notification",
                "notification_body"=> "An user update a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "bank update",
                "description"=> "IB bank update notification",
                "notification_body"=> "An user update a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "bank approved",
                "description"=> "Trader bank approved notification",
                "notification_body"=> "An admin approved a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "bank declined",
                "description"=> "Trader bank declined notification",
                "notification_body"=> "An admin declined a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "bank approved",
                "description"=> "IB bank approved notification",
                "notification_body"=> "An admin approved a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "bank declined",
                "description"=> "IB bank declined notification",
                "notification_body"=> "An admin declined a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "kyc",
                "description"=> "IB KYC Notification",
                "notification_body"=> "You get a KYV request from You software, that details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "deposit approve",
                "description"=> "Trader Deposit Approved Notification",
                "notification_body"=> "You get a deposit request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "deposit decline",
                "description"=> "Trader Deposit Declined Notification",
                "notification_body"=> "You get a deposit request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "withdraw approve",
                "description"=> "Trader withdraw Approved Notification",
                "notification_body"=> "You get a withdraw request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "withdraw decline",
                "description"=> "Trader withdraw declined Notification",
                "notification_body"=> "You get a withdraw request from user below description",
                "notification_header"=> null,
                "notification_footer"=> "NB: If you not sure please check it out",
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "withdraw decline",
                "description"=> "IB withdraw declined Notification",
                "notification_body"=> "You get a withdraw request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "withdraw approve",
                "description"=> "IB withdraw apporved Notification",
                "notification_body"=> "You get a withdraw request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "balance transfer approve",
                "description"=> "IB Balance Transfer approved Notification",
                "notification_body"=> "an admin approved balance transfer request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "balance transfer decline",
                "description"=> "IB Balance Transfer declined Notification",
                "notification_body"=> "an admin declined balance transfer request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "balance transfer decline",
                "description"=> "Trader Balance Transfer declined Notification",
                "notification_body"=> "an admin declined balance transfer request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "balance transfer approve",
                "description"=> "Trader Balance Transfer approved Notification",
                "notification_body"=> "An admin approved balance transfer request from user below description",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "kyc approve",
                "description"=> "IB KYC approved Notification",
                "notification_body"=> "An admin approved KYC request from You software, that details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "kyc decline",
                "description"=> "Trader KYC declined Notification",
                "notification_body"=> "An admin declined KYC request from You software, that details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trader"
            ],
            [
                "type"=> "kyc decline",
                "description"=> "IB KYC declined Notification",
                "notification_body"=> "An admin declined KYC request from You software, that details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "ib"
            ],
            [
                "type"=> "bank delete",
                "description"=> "admin bank delete notification",
                "notification_body"=> "An admin delete a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "admin"
            ],
            [
                "type"=> "bank active",
                "description"=> "admin bank active notification",
                "notification_body"=> "An admin active a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "admin"
            ],
            [
                "type"=> "bank add",
                "description"=> "admin bank add notification",
                "notification_body"=> "An admin add a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "admin"
            ],
            [
                "type"=> "bank update",
                "description"=> "admin bank update notification",
                "notification_body"=> "An admin updated a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "admin"
            ],
            [
                "type"=> "bank update trigger",
                "description"=> "admin bank update notification",
                "notification_body"=> "Someone updated a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trigger"
            ],
            [
                "type"=> "bank delete trigger",
                "description"=> "admin bank delete notification",
                "notification_body"=> "Someone Delete a bank please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trigger"
            ],
            [
                "type"=> "deposit update trigger",
                "description"=> "deposit update notification",
                "notification_body"=> "Someone update deposit please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trigger"
            ],
            [
                "type"=> "withdraw update trigger",
                "description"=> "withdraw update notification",
                "notification_body"=> "Someone update withdraw please check it, account details given below",
                "notification_header"=> null,
                "notification_footer"=> null,
                "email"=> null,
                "status"=> 1,
                "user_type"=> "trigger"
            ],


        ];
        Notification::insert($notification);
    }
}
