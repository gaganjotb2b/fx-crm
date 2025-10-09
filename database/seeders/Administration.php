<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Admin;
use App\Models\AdminGroup;
use App\Models\FinanceOp;
use App\Models\IB;
use App\Models\IbGroup;
use App\Models\Log;
use App\Models\UserDescription;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Hash;
// namespace App\Traits;
use Illuminate\Support\Facades\Crypt;

class Administration extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Insert data to roles table
        // ----------------------------------------------------------
        $data_permissions = [];
        $data_roles = [
            // admin management
            'manage admin',
            'admin groups',
            'admin registration',
            'admin right management',
            // ib management
            'ib management',
            'ib setup',
            'ib commission structure',
            'ib tree',
            'master ib',
            'panding commission list',
            'no commission list',
            'bank account list',
            'ib-chain',
            'ib admin',
            'ib verification request',
            'ib analysis',
            'ib registration request',

            'request management',
            'group settings',
            'dashboard',
            'category management',
            //Manage Client
            'manage client',
            'trader admin',
            'trader analysis',
            //Manager Settings
            'manager settings',
            'manager groups',
            'add manager',
            'manager list',
            'manager right',
            'manager analysis',
            //Finance management
            'finance',
            'balance management',
            'credit management',
            'fund management',
            'finance report',
            //support
            'support',
            'client ticket',

            //category manager
            'category manager',
            //Kyc Management
            'kyc management',
            'kyc upload',
            'kyc reports',
            'kyc request',
            // Request Management
            'manage request',
            'deposit request',
            'withdraw request',
            'balance transfer',
            'ib transfer',
            'ib withdraw request',
            //Fund Transfer
            'fund transfer',
            'internal fund transfer',
            'external fund transfer',
            //Reports
            'reports',
            'ib withdraw',
            'trader withdraw',
            'deposit request report',
            'activity log',
            'trader deposit report',
            'bonus report',
            'ib fund transfer',
            'balance upload report',
            'ledger report',
            'individual ledger report',
            //Voucher Genertate
            'offers',
            'voucher generate',
            'voucher report',
            //Group settings
            'group manager',
            'group list',
            'manage ib group',
            //settings
            'settings',
            'add crypto address',
            'announcement',
            'api configuration',
            'bank setting',
            'banner setup',
            'company setup',
            'currency pair',
            'finance settings',
            'ib setting',
            'notification setting',
            'security setting',
            'smtp setup',
            'software settings',
            'trader setting',
            //manage trade management
            'manage trade',
            'trading trade report',
            'trade commission status',
            //admin profile 
            'admin profile',
            'change profile',
            //trader settings 
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
            //IB settings
            'KYC Verification System',
            'Balance Transfer To Trader',
            // social trade
            'social trade',
            'social dashboard',
            'pamm settings',
            'pamm manager',
            'copy trades report',
            'social trades activity report',
            'manage mamm',
            'manage account',
            'manage accounts',
            'demo account',
            'live account',
            'notification',
            'blocked user',
            'admin withdraw',
            'manage banks',
            'company bank list',
            'create bonus',
            'bonus list',
            'offer bonus report',
            'pamm request',
            'currency setup',
            'copy symbols',
            'contest',
            'create contest',
            'contest list',
            'contest participant',
        ];
        for ($i = 0; $i < count($data_roles); $i++) {
            $insert_id = Role::create(['name' => strtolower($data_roles[$i])])->id;

            if ($insert_id != "") {
                // create permissions
                for ($j = 0; $j < 4; $j++) {
                    if ($j == 0) {
                        $permission_name = 'read ' . strtolower($data_roles[$i]);
                        $permission_id = Permission::create(['name' => $permission_name])->id;
                        if ($permission_id != "") {
                            $role = Role::findById($insert_id);
                            $role->givePermissionTo($permission_name);
                        }
                        array_push($data_permissions, $permission_name);
                    } elseif ($j == 1) {
                        $permission_name = 'edit ' . strtolower($data_roles[$i]);
                        $permission_id = Permission::create(['name' => $permission_name])->id;
                        if ($permission_id != "") {
                            $role = Role::findById($insert_id);
                            $role->givePermissionTo($permission_name);
                        }
                        array_push($data_permissions, $permission_name);
                    } elseif ($j == 2) {
                        $permission_name = 'delete ' . strtolower($data_roles[$i]);
                        $permission_id = Permission::create(['name' => $permission_name])->id;
                        if ($permission_id != "") {
                            $role = Role::findById($insert_id);
                            $role->givePermissionTo($permission_name);
                        }
                        array_push($data_permissions, $permission_name);
                    } else {
                        $permission_name = 'create ' . strtolower($data_roles[$i]);
                        $permission_id = Permission::create(['name' => $permission_name])->id;
                        if ($permission_id != "") {
                            $role = Role::findById($insert_id);
                            $role->givePermissionTo($permission_name);
                        }
                        array_push($data_permissions, $permission_name);
                    }
                }
            }
        }

        // END: roles and permission
        // ------------------------------------------------------------------------------------------------

        // START: Create admin groups
        // -------------------------------------------------------------------------------------------------
        $group_id = AdminGroup::create([
            'group_name' => 'Administration',
            'created_at' => date("Y-m-d h:i:s", strtotime('now'))

        ])->id;

        // ib group
        IbGroup::create([
            'group_name' => "IBG-1",
            'status' => 1
        ]);

        // START: create user
        // --------------------------------------------------------------------------------------------------------
        // create supper admin
        $created_id = User::create([
            'name' => 'Super Admin',
            'email' => 'super_admin@' . join_app_name() . '.net',
            'password' => Hash::make('A12345'),
            'transaction_password' => Hash::make('A12345'),
            'email_verified_at' => date("Y-m-d h:i:s", strtotime('now')),
            'created_at' => date("Y-m-d h:i:s", strtotime('now')),
            'type' => 2,
            'phone' => '+880174789XX'
        ])->id;

        // create system admin
        $system_admin_id = User::create([
            'name' => 'System Admin',
            'email' => 'system_admin@' . join_app_name() . '.net',
            'password' => Hash::make('A12345'),
            'transaction_password' => Hash::make('A12345'),
            'email_verified_at' => date("Y-m-d h:i:s"),
            'created_at' => date("Y-m-d h:i:s", strtotime('now')),
            'type' => 1,
            'phone' => '+880174789XX'
        ])->id;
        // create default IB
        $default_ib_id = User::create([
            'name' => 'default IB',
            'email' => 'default_ib@' . join_app_name() . '.net',
            'password' => Hash::make('A12345'),
            'transaction_password' => Hash::make('A12345'),
            'email_verified_at' => date("Y-m-d h:i:s"),
            'email_verification' => 1,
            'created_at' => date("Y-m-d h:i:s", strtotime('now')),
            'type' => 4,
            'phone' => '+880174789XX',
            'ib_group_id' => 1
        ])->id;
        // create demo IB
        $demo_ib_id = User::create([
            'name' => 'Demo IB',
            'email' => 'demo_ib@' . join_app_name() . '.net',
            'password' => Hash::make('A12345'),
            'transaction_password' => Hash::make('A12345'),
            'email_verified_at' => date("Y-m-d h:i:s"),
            'email_verification' => 1,
            'created_at' => date("Y-m-d h:i:s", strtotime('now')),
            'type' => 4,
            'phone' => '+880174789XX',
            'ib_group_id' => 1
        ])->id;
        // create demo user
        $demo_user_id = User::create([
            'name' => 'demo user',
            'email' => 'demo_user@' . join_app_name() . '.net',
            'password' => Hash::make('A12345'),
            'transaction_password' => Hash::make('A12345'),
            'email_verified_at' => date("Y-m-d h:i:s"),
            'commission_operation' => 1,
            'trading_ac_limit' => 3,
            'created_at' => date("Y-m-d h:i:s", strtotime('now')),
            'type' => 0,
            'phone' => '+880174789XX'
        ])->id;

        // create demo user 2
        $default_user_id = User::create([
            'name' => 'default user',
            'email' => 'default_user@' . join_app_name() . '.net',
            'password' => Hash::make('A12345'),
            'transaction_password' => Hash::make('A12345'),
            'email_verified_at' => date("Y-m-d h:i:s"),
            'commission_operation' => 1,
            'trading_ac_limit' => 3,
            'created_at' => date("Y-m-d h:i:s", strtotime('now')),
            'type' => 0,
            'phone' => '+880174789XX'
        ])->id;

        // create user description
        // --------------------------------------------------------------------
        UserDescription::create([
            'user_id' => $demo_ib_id,
            'country_id' => 1,
            'city' => 'Dhaka',
            'address' => '#121-Elephant road',
            'zip_code' => 1212,
            'gender' => 'Male',
            'state' => 'Dhaka',
            'date_of_birth' => '1996-12-10'
        ]);
        FinanceOp::create([
            'user_id' => $demo_ib_id,
            'deposit_operation' => 1,
            'withdraw_operation' => 1,
            'internal_transfer' => 1,
            'wta_transfer' => 1,
            'trader_to_trader' => 1,
            'trader_to_ib' => 1,
            'ib_to_trader' => 1,
            'ib_to_ib' => 1,
            'kyc_verify' => 1,
        ]);
        UserDescription::create([
            'user_id' => $default_ib_id,
            'country_id' => 1,
            'city' => 'Dhaka',
            'address' => '#121-Elephant road',
            'zip_code' => 1212,
            'gender' => 'Male',
            'state' => 'Dhaka',
            'date_of_birth' => '1996-12-10'
        ]);
        FinanceOp::create([
            'user_id' => $default_ib_id,
            'deposit_operation' => 1,
            'withdraw_operation' => 1,
            'internal_transfer' => 1,
            'wta_transfer' => 1,
            'trader_to_trader' => 1,
            'trader_to_ib' => 1,
            'ib_to_trader' => 1,
            'ib_to_ib' => 1,
            'kyc_verify' => 1,
        ]);
        UserDescription::create([
            'user_id' => $default_user_id,
            'country_id' => 1,
            'city' => 'Dhaka',
            'address' => '#121-Elephant road',
            'zip_code' => 1212,
            'gender' => 'Male',
            'state' => 'Dhaka',
            'date_of_birth' => '1996-12-10'
        ]);
        FinanceOp::create([
            'user_id' => $default_user_id,
            'deposit_operation' => 1,
            'withdraw_operation' => 1,
            'internal_transfer' => 1,
            'wta_transfer' => 1,
            'trader_to_trader' => 1,
            'trader_to_ib' => 1,
            'ib_to_trader' => 1,
            'ib_to_ib' => 1,
            'kyc_verify' => 1,
        ]);
        UserDescription::create([
            'user_id' => $demo_user_id,
            'country_id' => 1,
            'city' => 'Dhaka',
            'address' => '#121-Elephant road',
            'zip_code' => 1212,
            'gender' => 'Male',
            'state' => 'Dhaka',
            'date_of_birth' => '1996-12-10'
        ]);
        FinanceOp::create([
            'user_id' => $demo_user_id,
            'deposit_operation' => 1,
            'withdraw_operation' => 1,
            'internal_transfer' => 1,
            'wta_transfer' => 1,
            'trader_to_trader' => 1,
            'trader_to_ib' => 1,
            'ib_to_trader' => 1,
            'ib_to_ib' => 1,
            'kyc_verify' => 1,
        ]);
        UserDescription::create([
            'user_id' => $created_id,
            'country_id' => 1,
            'city' => 'Dhaka',
            'address' => '#121-Elephant road',
            'zip_code' => 1212,
            'gender' => 'Male',
            'state' => 'Dhaka',
            'date_of_birth' => '1996-12-10'
        ]);
        FinanceOp::create([
            'user_id' => $created_id,
            'deposit_operation' => 1,
            'withdraw_operation' => 1,
            'internal_transfer' => 1,
            'wta_transfer' => 1,
            'trader_to_trader' => 1,
            'trader_to_ib' => 1,
            'ib_to_trader' => 1,
            'ib_to_ib' => 1,
            'kyc_verify' => 1,
        ]);
        UserDescription::create([
            'user_id' => $system_admin_id,
            'country_id' => 1,
            'city' => 'Dhaka',
            'address' => '#121-Elephant road',
            'zip_code' => 1212,
            'gender' => 'Male',
            'state' => 'Dhaka',
            'date_of_birth' => '1996-12-10'
        ]);
        FinanceOp::create([
            'user_id' => $system_admin_id,
            'deposit_operation' => 1,
            'withdraw_operation' => 1,
            'internal_transfer' => 1,
            'wta_transfer' => 1,
            'trader_to_trader' => 1,
            'trader_to_ib' => 1,
            'ib_to_trader' => 1,
            'ib_to_ib' => 1,
            'kyc_verify' => 1,
        ]);

        // create ib references
        // -------------------------------------------------------------------
        IB::create([
            'ib_id' => $default_ib_id,
            'reference_id' => $demo_ib_id
        ]);
        IB::create([
            'ib_id' => $default_ib_id,
            'reference_id' => $default_user_id
        ]);
        IB::create([
            'ib_id' => $demo_ib_id,
            'reference_id' => $demo_user_id
        ]);

        // START: Create user as admin
        // ---------------------------------------------------------------------------------------------
        Admin::create([
            'user_id' => $created_id,
            'group_id' => $group_id,
            'accessible_country' => 1,
            // 'created_at' => date("Y-m-d h:i:s",strtotime('now'))
        ]);

        // START: Get all access permission to supper admin
        // ----------------------------------------------------------------------------------------------
        $user = User::find($created_id);
        $user->syncRoles($data_roles);
        $user->syncPermissions($data_permissions);
        // all access permission to system admin
        $system_admin = User::find($system_admin_id);
        $system_admin->syncRoles($data_roles);
        $system_admin->syncPermissions($data_permissions);

        // create logs for demo users
        // --------------------------------------------------------------------
        Log::create([
            'user_id' => 1,
            'password' => Crypt::encrypt('A12345'),
            'transaction_password' => Crypt::encrypt('A12345'),
        ]);
        Log::create([
            'user_id' => 2,
            'password' => Crypt::encrypt('A12345'),
            'transaction_password' => Crypt::encrypt('A12345'),
        ]);
        Log::create([
            'user_id' => 3,
            'password' => Crypt::encrypt('A12345'),
            'transaction_password' => Crypt::encrypt('A12345'),
        ]);
        Log::create([
            'user_id' => 4,
            'password' => Crypt::encrypt('A12345'),
            'transaction_password' => Crypt::encrypt('A12345'),
        ]);
        Log::create([
            'user_id' => 5,
            'password' => Crypt::encrypt('A12345'),
            'transaction_password' => Crypt::encrypt('A12345'),
        ]);
        Log::create([
            'user_id' => 6,
            'password' => Crypt::encrypt('A12345'),
            'transaction_password' => Crypt::encrypt('A12345'),
        ]);
        // PermissionService::creae_permission('trader');
        // PermissionService::creae_permission('ib');
    }
}
