<?php

namespace App\Services;

use App\Models\admin\SystemConfig;
use App\Models\IbSetting;
use App\Models\SystemModule;
use App\Models\TraderSetting;
use App\Models\User;
use App\Services\AllFunctionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

/**
 * CrmApiService Api new
 */
class PermissionService
{
    public static function has_permission($access_module, $type)
    {
        $has_access =  AllFunctionService::access($access_module, $type);
        // return $has_access;
        $has_access = explode(':', $has_access);
        $permission = false;
        if ($has_access[1] === 'access') {
            $permission = true;
        } else {
            $permission = false;
        }
        return $permission;
    }
    // check combined access
    // controll ib modules
    public static function is_combined()
    {
        $crm_type = SystemConfig::select('crm_type')->first();
        if ($crm_type) {
            // access ib module in trader portal
            if ($crm_type->crm_type === 'combined') {
                return ('demo_controll:notaccess');
            }
            // access ib portal
            else {
                return ('demo_controll:access');
            }
        }
        // access ib portal
        else {
            return ('demo_controll:access');
        }
    }
    // create trader/ib permission
    public static function creae_permission($type)
    {
        $user = User::find(auth()->user()->id);
        switch ($type) {
            case 'trader':
                $delete = TraderSetting::truncate();
                $permission_parent = [
                    // my admin code 1
                    [
                        'settings' => ucwords('my admin'),
                        'status' => 1,
                        'id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    // trading accounts code 2
                    [
                        'settings' => ucwords('trading accounts'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 2,
                    ],
                    // deposit code 3
                    [
                        'settings' => ucwords('deposit'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 3,
                    ],
                    // withdraw code 4
                    [
                        'settings' => ucwords('withdraw'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 4,
                    ],
                    // transfer code 5
                    [
                        'settings' => ucwords('transfer'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 5,
                    ],
                    // reports code 6
                    [
                        'settings' => ucwords('reports'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 6,
                    ],
                    // suport
                    [
                        'settings' => ucwords('support'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 7,

                    ],
                    // mamm
                    [
                        'settings' => ucwords('MAMM'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 8,

                    ],
                    // pamm
                    [
                        'settings' => ucwords('PAMM'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 9,

                    ],
                    // copy trading
                    [
                        'settings' => ucwords('copy trading'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 10,
                    ],
                    // trading tools
                    [
                        'settings' => ucwords('trading tools'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 11,
                    ],
                    // contest
                    [
                        'settings' => ucwords('contest'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 12,
                    ],
                    // become a partner
                    [
                        'settings' => ucwords('become a partner'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 13,
                    ],

                ];
                $user->cust_permission()->createMany($permission_parent);
                $permission_child = [
                    // parent my admin
                    [
                        'settings' => ucwords('profile overview'),
                        'status' => 1,
                        'parent_id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('settings'),
                        'status' => 1,
                        'parent_id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('verification'),
                        'status' => 1,
                        'parent_id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('banking'),
                        'status' => 1,
                        'parent_id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent trading accounts
                    [
                        'settings' => ucwords('open demo account'),
                        'status' => 1,
                        'parent_id' => 2,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('open live account'),
                        'status' => 1,
                        'parent_id' => 2,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('trading account settings'),
                        'status' => 1,
                        'parent_id' => 2,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent deposit
                    [
                        'settings' => ucwords('bank deposit'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('crypto deposit'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('perfect money deposit'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('help2pay deposit'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('match2pay deposit'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('paypal deposit'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('b2binpay deposit'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent withdraw
                    [
                        'settings' => ucwords('bank withdraw'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('crypto withdraw'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('skrill withdraw'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('neteller withdraw'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('paypal withdraw'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('gcash withdraw'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent transfer
                    [
                        'settings' => ucwords('wallet to account'),
                        'status' => 1,
                        'parent_id' => 5,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('account to wallet'),
                        'status' => 1,
                        'parent_id' => 5,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('trader to trader'),
                        'status' => 1,
                        'parent_id' => 5,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('trader to IB'),
                        'status' => 1,
                        'parent_id' => 5,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent reports
                    [
                        'settings' => ucwords('deposit report'),
                        'status' => 1,
                        'parent_id' => 6,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('withdraw reports'),
                        'status' => 1,
                        'parent_id' => 6,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('external transfer report'),
                        'status' => 1,
                        'parent_id' => 6,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('internal transfer report'),
                        'status' => 1,
                        'parent_id' => 6,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('trading report'),
                        'status' => 1,
                        'parent_id' => 6,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('IB transfer report'),
                        'status' => 1,
                        'parent_id' => 6,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent support
                    [
                        'settings' => ucwords('support ticket'),
                        'status' => 1,
                        'parent_id' => 7,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent mamm
                    [
                        'settings' => ucwords('manage slave account'),
                        'status' => 1,
                        'parent_id' => 8,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('PAMM profile'),
                        'status' => 1,
                        'parent_id' => 9,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('PAMM registration'),
                        'status' => 1,
                        'parent_id' => 9,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('social traders report'),
                        'status' => 1,
                        'parent_id' => 10,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('social activities report'),
                        'status' => 1,
                        'parent_id' => 10,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // parent 11 / trading tools
                        // daily market analysis
                        'settings' => ucwords('daily market analysis'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // parent 11 / trading tools
                        // forex signals
                        'settings' => ucwords('forex signals'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // parent 11 / trading tools
                        // forex education
                        'settings' => ucwords('forex education'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // parent 11 / trading tools
                        // echonomic calendar
                        'settings' => ucwords('echonomic calendar'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // parent 11 / trading tools
                        // forex calculators
                        'settings' => ucwords('forex calculators'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 12 / contest
                    [
                        'settings' => ucwords('participate contest'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('contest list'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('contest status'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                ];
                $create_child = $user->cust_permission()->createMany($permission_child);
                if ($create_child) {
                    return ([
                        'status' => true,
                        'message' => 'All permission successfully created to trader.'
                    ]);
                }
                return ([
                    'status' => false,
                    'message' => 'Permission generation failed, Please try again later',
                ]);
                break;
                // admin modules
            case 'admin':
                $delete = SystemModule::truncate();
                $permission_parent = [
                    // amin profile
                    [
                        'module' => ucwords('admin profile'),
                        'status' => 1,
                        'id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    // manage client
                    [
                        'module' => ucwords('manage client'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 2,
                    ],
                    // manage trade
                    [
                        'module' => ucwords('manage trade'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 3,
                    ],
                    // manage admin
                    [
                        'module' => ucwords('manage admin'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 4,
                    ],
                    // manager settings
                    [
                        'module' => ucwords('manager settings'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 5,
                    ],
                    // manage accounts
                    [
                        'module' => ucwords('manage accounts'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 6,
                    ],
                    // manage banks
                    [
                        'module' => ucwords('manage banks'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 7,
                    ],
                    // finance 
                    [
                        'module' => ucwords('finance'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 8,

                    ],
                    // supports
                    [
                        'module' => ucwords('support'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 9,

                    ],
                    // category manager
                    [
                        'module' => ucwords('category manager'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 10,

                    ],
                    
                     // lead management
                    [
                        'module' => ucwords('lead management'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 116,

                    ],
                    // ib management
                    [
                        'module' => ucwords('IB management'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 11,
                    ],
                    // settings 12

                    [
                        'module' => ucwords('settings'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 12,
                    ],
                    // kyc management 13
                    [
                        'module' => ucwords('kyc management'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 13,
                    ],
                    // manage request 14
                    [
                        'module' => ucwords('manage request'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 14,
                    ],
                    // fund transfer 15
                    [
                        'module' => ucwords('fund transfer'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 15,
                    ],
                    // reports 16
                    [
                        'module' => ucwords('reports'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 16,
                    ],
                    // offers 17
                    [
                        'module' => ucwords('offers'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 17,
                    ],
                    // group settings 18
                    [
                        'module' => ucwords('group settings'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 18,
                    ],
                    // social trade 19
                    [
                        'module' => ucwords('social trade'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 19,
                    ],
                    // contest trade 20
                    [
                        'module' => ucwords('contest'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 20,
                    ],

                ];
                $user->admin_permission()->createMany($permission_parent);
                // admin permission chiled
                $permission_child = [
                    // parent 1 admin profile
                    [
                        // change profile
                        'module' => ucwords('change profile'),
                        'status' => 1,
                        'parent_id' => 1,
                        'created_by' => auth()->user()->id,
                    ],

                    [
                        // notifications
                        'module' => ucwords('notifications'),
                        'status' => 1,
                        'parent_id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 2 manage client
                    [
                        // trader admin
                        'module' => ucwords('trader admin'),
                        'status' => 1,
                        'parent_id' => 2,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 2 manage client
                    [
                        // trader admin
                        'module' => ucwords('trader analysis'),
                        'status' => 1,
                        'parent_id' => 2,
                        'created_by' => auth()->user()->id,
                    ],
                    //parent 3 manage trade
                    [
                        // trading report
                        'module' => ucwords('trading report'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // trade commission
                        'module' => ucwords('trade commission'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 4 admin management
                    [
                        // admin groups
                        'module' => ucwords('admin groups'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // admin registration
                        'module' => ucwords('admin registration'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // admin right management
                        'module' => ucwords('admin right management'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 5 manager module
                    [
                        // manager group
                        'module' => ucwords('manager groups'),
                        'status' => 1,
                        'parent_id' => 5,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        //add manager
                        'module' => ucwords('add manager'),
                        'status' => 1,
                        'parent_id' => 5,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // manager list
                        'module' => ucwords('manager list'),
                        'status' => 1,
                        'parent_id' => 5,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // manager right
                        'module' => ucwords('manager right'),
                        'status' => 1,
                        'parent_id' => 5,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // manager analysis
                        'module' => ucwords('manager analysis'),
                        'status' => 1,
                        'parent_id' => 5,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 6 manage account
                    [
                        // live trading account
                        'module' => ucwords('live trading account'),
                        'status' => 1,
                        'parent_id' => 6,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // demo  trading account
                        'module' => ucwords('demo trading account'),
                        'status' => 1,
                        'parent_id' => 6,
                        'created_by' => auth()->user()->id,
                    ],
                    //parent 7 manage bank
                    [
                        // bank account list
                        'module' => ucwords('bank account list'),
                        'status' => 1,
                        'parent_id' => 7,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // company bank account list
                        // parent 7
                        'module' => ucwords('company bank list'),
                        'status' => 1,
                        'parent_id' => 7,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 8 finance
                    [
                        // balance management
                        'module' => ucwords('balance management'),
                        'status' => 1,
                        'parent_id' => 8,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // credit management
                        'module' => ucwords('credit management'),
                        'status' => 1,
                        'parent_id' => 8,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // fund management
                        'module' => ucwords('fund management'),
                        'status' => 1,
                        'parent_id' => 8,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // finance reports
                        'module' => ucwords('finance reports'),
                        'status' => 1,
                        'parent_id' => 8,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // admin deposit report
                        // parent 8/ finance
                        'module' => ucwords('admin deposit'),
                        'status' => 1,
                        'parent_id' => 8,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // admin withdraw report
                        // parent 8/ finance
                        'module' => ucwords('admin withdraw'),
                        'status' => 1,
                        'parent_id' => 8,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 9 support tickets
                    [
                        // client tickets
                        'module' => ucwords('support tickets'),
                        'status' => 1,
                        'parent_id' => 9,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 10 category manager
                    [
                        // trader category
                        'module' => ucwords('trader category'),
                        'status' => 1,
                        'parent_id' => 10,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // ib category
                        'module' => ucwords('IB category'),
                        'status' => 1,
                        'parent_id' => 10,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 11 ib management
                    [
                        'module' => ucwords('IB setup'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // ib commission structure
                        'module' => ucwords('IB commission structure'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // ib tree
                        'module' => ucwords('IB tree'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // master IB
                        'module' => ucwords('master IB'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // pending commission list
                        'module' => ucwords('pending commission list'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // no commission list
                        'module' => ucwords('no commission list'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // ib chain
                        'module' => ucwords('IB chain'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // ib admin
                        'module' => ucwords('IB admin'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // ib verification request
                        'module' => ucwords('IB verification request'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // ib analysis
                        // parent 11 / ib management
                        'module' => ucwords('IB analysis'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // ib regisration request
                        // parent 11 / ib management
                        'module' => ucwords('IB registration request'),
                        'status' => 1,
                        'parent_id' => 11,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 12 module
                    [
                        // add crypto address
                        'module' => ucwords('add crypto address'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],

                    [
                        // announcement
                        'module' => ucwords('announcement'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // API configuration
                        'module' => ucwords('API configuration'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // bank setting
                        'module' => ucwords('bank setting'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // settings / 12 parent
                        // bank currency setup
                        'module' => ucwords('currency setup'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // settings / 12 parent
                        // bank copy symbols
                        'module' => ucwords('copy symbols'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // banner setup
                        'module' => ucwords('banner setup'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // company setup
                        'module' => ucwords('company setup'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // currency pair
                        'module' => ucwords('currency pair'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // finaance settings
                        'module' => ucwords('finance settings'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // IB settings
                        'module' => ucwords('IB settings'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // security settings
                        'module' => ucwords('security settings'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // smtp setup
                        'module' => ucwords('SMTP setup'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // software settings
                        'module' => ucwords('software settings'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // trader settings
                        'module' => ucwords('trader settings'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // payment gateways
                        'module' => ucwords('payment gateways'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // settings / 12
                        // notification template
                        'module' => ucwords('notification template'),
                        'status' => 1,
                        'parent_id' => 12,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 13 kyc management
                    [
                        // kyc upload
                        'module' => ucwords('kyc upload'),
                        'status' => 1,
                        'parent_id' => 13,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // kyc reports
                        'module' => ucwords('kyc reports'),
                        'status' => 1,
                        'parent_id' => 13,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // kyc request
                        'module' => ucwords('kyc request'),
                        'status' => 1,
                        'parent_id' => 13,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 14 manage request
                    [
                        // deposit request
                        'module' => ucwords('deposit request'),
                        'status' => 1,
                        'parent_id' => 14,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // withdraw request
                        'module' => ucwords('withdraw request'),
                        'status' => 1,
                        'parent_id' => 14,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // balance transfer request
                        'module' => ucwords('balance transfer request'),
                        'status' => 1,
                        'parent_id' => 14,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // IB transfer request
                        'module' => ucwords('IB transfer request'),
                        'status' => 1,
                        'parent_id' => 14,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // IB withdraw request
                        'module' => ucwords('IB withdraw request'),
                        'status' => 1,
                        'parent_id' => 14,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 15 fund transfer
                    [
                        // internal fund transfer
                        'module' => ucwords('internal fund transfer'),
                        'status' => 1,
                        'parent_id' => 15,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // external fund transfer
                        'module' => ucwords('external fund transfer'),
                        'status' => 1,
                        'parent_id' => 15,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 16 reports
                    [
                        // IB withdraw
                        'module' => ucwords('IB withdraw'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // IB commission
                        'module' => ucwords('IB commission'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // trader withdraw
                        'module' => ucwords('trader withdraw'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // blocked users
                        'module' => ucwords('blocked users'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // deposit request
                        'module' => ucwords('deposit request'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // activity log
                        'module' => ucwords('activity log'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // trader deposit
                        'module' => ucwords('trader deposit'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // bonus report
                        'module' => ucwords('bonus report'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // IB fund transfer
                        'module' => ucwords('IB fund transfer'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // external fund transfer
                        // parent 16
                        'module' => ucwords('external fund transfer'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // balance upload and deduction
                        'module' => ucwords('balance upload and deduction'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // ledger report
                        'module' => ucwords('ledger report'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // individual ledger report
                        'module' => ucwords('individual ledger report'),
                        'status' => 1,
                        'parent_id' => 16,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 17 offer
                    [
                        // voucher generate
                        'module' => ucwords('voucher generate'),
                        'status' => 1,
                        'parent_id' => 17,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // vouncher report
                        'module' => ucwords('voucher report'),
                        'status' => 1,
                        'parent_id' => 17,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // create bonus
                        // parent 17 /offers
                        'module' => ucwords('create bonus'),
                        'status' => 1,
                        'parent_id' => 17,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // create bonus
                        // parent 17 /offers
                        'module' => ucwords('bonus report(offer)'),
                        'status' => 1,
                        'parent_id' => 17,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent 18 group settings
                    [
                        // group manager
                        'module' => ucwords('group manager'),
                        'status' => 1,
                        'parent_id' => 18,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // group list
                        'module' => ucwords('group list'),
                        'status' => 1,
                        'parent_id' => 18,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // manage IB group
                        'module' => ucwords('manage IB group'),
                        'status' => 1,
                        'parent_id' => 18,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent social trade -> 19
                    [
                        // social dashboard
                        'module' => ucwords('social dashboard'),
                        'status' => 1,
                        'parent_id' => 19,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // pamm settings
                        'module' => ucwords('pamm settings'),
                        'status' => 1,
                        'parent_id' => 19,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // social pamm manager
                        'module' => ucwords('pamm manager'),
                        'status' => 1,
                        'parent_id' => 19,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // social copy trades report
                        'module' => ucwords('copy trades report'),
                        'status' => 1,
                        'parent_id' => 19,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // social trades activity reports
                        'module' => ucwords('social trades activity reports'),
                        'status' => 1,
                        'parent_id' => 19,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // social manage mamm
                        'module' => ucwords('manage mamm'),
                        'status' => 1,
                        'parent_id' => 19,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // parent 19 / social trade
                        // social pamm request
                        'module' => ucwords('pamm request'),
                        'status' => 1,
                        'parent_id' => 19,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // parent 20 / contest
                        // create contest
                        'module' => ucwords('create contest'),
                        'status' => 1,
                        'parent_id' => 20,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // parent 20 / contest
                        // contest list
                        'module' => ucwords('contest list'),
                        'status' => 1,
                        'parent_id' => 20,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        // parent 20 / contest
                        // contest participant
                        'module' => ucwords('contest participant'),
                        'status' => 1,
                        'parent_id' => 20,
                        'created_by' => auth()->user()->id,
                    ],

                ];
                $create_child = $user->admin_permission()->createMany($permission_child);
                if ($create_child) {
                    return ([
                        'status' => true,
                        'message' => 'All permission successfully created to admin.'
                    ]);
                }
                return ([
                    'status' => false,
                    'message' => 'Permission generation failed, Please try again later',
                ]);
                break;

            default:
                // ib menue create
                $delete = IbSetting::truncate();
                $permission_parent = [
                    // my admin code 1
                    [
                        'settings' => ucwords('my admin'),
                        'status' => 1,
                        'id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    // trading accounts code 2
                    [
                        'settings' => ucwords('affiliate'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 2,
                    ],
                    // deposit code 3
                    [
                        'settings' => ucwords('reports'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 3,
                    ],
                    // withdraw code 4
                    [
                        'settings' => ucwords('withdraw'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 4,
                    ],
                    // transfer code 5
                    [
                        'settings' => ucwords('transfer'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 5,
                    ],
                    // reports code 6
                    [
                        'settings' => ucwords('reports'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 6,
                    ],
                    // suport
                    [
                        'settings' => ucwords('support'),
                        'status' => 1,
                        'created_by' => auth()->user()->id,
                        'id' => 7,

                    ],

                    // [
                    //     'settings' => ucwords('echonomic calendar'),
                    //     'status' => 1,
                    //     'created_by' => auth()->user()->id,
                    //     'id' => 11,
                    // ],
                    // [
                    //     'settings' => ucwords('forex signals'),
                    //     'status' => 1,
                    //     'created_by' => auth()->user()->id,
                    //     'id' => 13,
                    // ],
                    // [
                    //     'settings' => ucwords('forex education'),
                    //     'status' => 1,
                    //     'created_by' => auth()->user()->id,
                    //     'id' => 14,
                    // ],

                ];
                $user->cust_permission_ib()->createMany($permission_parent);
                $permission_child = [
                    // parent my admin
                    [
                        'settings' => ucwords('profile overview'),
                        'status' => 1,
                        'parent_id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('settings'),
                        'status' => 1,
                        'parent_id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('verification'),
                        'status' => 1,
                        'parent_id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('banking'),
                        'status' => 1,
                        'parent_id' => 1,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent affiliate
                    [
                        'settings' => ucwords('IB tree'),
                        'status' => 1,
                        'parent_id' => 2,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('my IB'),
                        'status' => 1,
                        'parent_id' => 2,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('my clients'),
                        'status' => 1,
                        'parent_id' => 2,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('deposit reports'),
                        'status' => 1,
                        'parent_id' => 2,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('withdraw reports'),
                        'status' => 1,
                        'parent_id' => 2,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent reports
                    [
                        'settings' => ucwords('trade commission'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('withdraw report'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    // IB Balance Send
                    [
                        'settings' => ucwords('IB balance send'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],
                    // IB Balance Receive
                    [
                        'settings' => ucwords('IB balance receive'),
                        'status' => 1,
                        'parent_id' => 3,
                        'created_by' => auth()->user()->id,
                    ],

                    // parent withdraw
                    [
                        'settings' => ucwords('bank withdraw'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('crypto withdraw'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('skrill withdraw'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('neteller withdraw'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('gcash withdraw'),
                        'status' => 1,
                        'parent_id' => 4,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent transfer
                    [
                        'settings' => ucwords('IB to trader transfer'),
                        'status' => 1,
                        'parent_id' => 5,
                        'created_by' => auth()->user()->id,
                    ],
                    [
                        'settings' => ucwords('IB to IB transfer'),
                        'status' => 1,
                        'parent_id' => 5,
                        'created_by' => auth()->user()->id,
                    ],
                    // parent support
                    [
                        'settings' => ucwords('support ticket'),
                        'status' => 1,
                        'parent_id' => 7,
                        'created_by' => auth()->user()->id,
                    ],

                ];
                $create_child = $user->cust_permission_ib()->createMany($permission_child);
                if ($create_child) {
                    return ([
                        'status' => true,
                        'message' => 'All permission successfully created to IB.'
                    ]);
                }
                return ([
                    'status' => false,
                    'message' => 'Permission generation failed, Please try again later',
                ]);
                break;
        }
    }
    // end: create permission
    // start update permikssion
    public static function update_permission($type, $id)
    {
        switch ($type) {
                // update trader permission
            case 'trader':
                // check request from parent
                $fetchData = TraderSetting::find($id);
                // update all child with parent
                if ($fetchData->parent_id == null) {
                    $fetchChild = TraderSetting::where('parent_id', $fetchData->id);
                    $data['status'] = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type == 'system') {
                        $data['system_disable'] = ($fetchData->status) ? 1 : 0;
                    }
                    $update = $fetchChild->update($data);
                    // update parent
                    $fetchData->status = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type === 'system') {
                        $fetchData->system_disable = ($fetchData->status) ? 0 : 1;
                    }
                    $parent_update = $fetchData->save();
                    if ($update == true && $parent_update == true) {
                        return ([
                            'status' => true,
                            'message' => 'Settings successfully update'
                        ]);
                    }
                    return ([
                        'status' => true,
                        'message' => 'updated'
                    ]);
                }
                // update single child
                else {
                    // check all child status
                    $ids = array($fetchData->parent_id, $fetchData->id);
                    $get_child = TraderSetting::where(function ($query) use ($ids) {
                        $query->where('parent_id', $ids[0])
                            ->whereNotIn('id', [$ids[1]]);
                    })->where('status', 1)->count();
                    $data['status'] = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type === 'system') {
                        $data['system_disable'] = ($fetchData->status) ? 1 : 0;
                    }
                    if ($get_child == 0) {
                        // update parent
                        $parent_update = TraderSetting::where('id', $fetchData->parent_id)->update($data);
                    }
                    $fetchData->status = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type == "system") {
                        $fetchData->system_disable = ($fetchData->status) ? 0 : 1;
                    }
                    $single_update = $fetchData->save();
                    if ($single_update == true) {
                        return ([
                            'status' => true,
                            'message' => 'Settings successfully update'
                        ]);
                    }
                    return ([
                        'status' => false,
                        'message' => 'Something went wrong, please try again later'
                    ]);
                }
                break;
                // END: trader settings
                // update admin permission
            case 'admin':
                // check request from parent
                $fetchData = SystemModule::find($id);
                // update all child with parent
                if ($fetchData->parent_id == null) {
                    $fetchChild = SystemModule::where('parent_id', $fetchData->id);
                    $data['status'] = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type == 'system') {
                        $data['system_disable'] = ($fetchData->status) ? 1 : 0;
                    }
                    $update = $fetchChild->update($data);
                    // update parent
                    $fetchData->status = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type === 'system') {
                        $fetchData->system_disable = ($fetchData->status) ? 0 : 1;
                    }
                    $parent_update = $fetchData->save();
                    if ($update == true && $parent_update == true) {
                        return ([
                            'status' => true,
                            'message' => 'Settings successfully update'
                        ]);
                    }
                    return ([
                        'status' => true,
                        'message' => 'updated'
                    ]);
                }
                // update single child
                else {
                    // check all child status
                    $ids = array($fetchData->parent_id, $fetchData->id);
                    $get_child = SystemModule::where(function ($query) use ($ids) {
                        $query->where('parent_id', $ids[0])
                            ->whereNotIn('id', [$ids[1]]);
                    })->where('status', 1)->count();
                    $data['status'] = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type === 'system') {
                        $data['system_disable'] = ($fetchData->status) ? 1 : 0;
                    }
                    if ($get_child == 0) {
                        // update parent
                        $parent_update = SystemModule::where('id', $fetchData->parent_id)->update($data);
                    }
                    $fetchData->status = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type == "system") {
                        $fetchData->system_disable = ($fetchData->status) ? 0 : 1;
                    }
                    $single_update = $fetchData->save();
                    if ($single_update == true) {
                        return ([
                            'status' => true,
                            'message' => 'Settings successfully update'
                        ]);
                    }
                    return ([
                        'status' => false,
                        'message' => 'Something went wrong, please try again later'
                    ]);
                }
                break;
                // END: admin settings
                // default option ib settings
            default:
                // update ib setings
                // check request from parent
                $fetchData = IbSetting::find($id);
                // update all child with parent
                if ($fetchData->parent_id == null) {
                    $fetchChild = IbSetting::where('parent_id', $fetchData->id);
                    $data['status'] = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type == 'system') {
                        $data['system_disable'] = ($fetchData->status) ? 1 : 0;
                    }
                    $update = $fetchChild->update($data);
                    // update parent
                    $fetchData->status = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type === 'system') {
                        $fetchData->system_disable = ($fetchData->status) ? 0 : 1;
                    }
                    $parent_update = $fetchData->save();
                    if ($update == true && $parent_update == true) {
                        return ([
                            'status' => true,
                            'message' => 'Settings successfully update'
                        ]);
                    }
                    return ([
                        'status' => false,
                        'message' => 'Something went wrong, please try again later'
                    ]);
                }
                // update single child
                else {
                    // check all child status
                    $ids = array($fetchData->parent_id, $fetchData->id);
                    $get_child = IbSetting::where(function ($query) use ($ids) {
                        $query->where('parent_id', $ids[0])
                            ->whereNotIn('id', [$ids[1]]);
                    })->where('status', 1)->count();
                    $data['status'] = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type === 'system') {
                        $data['system_disable'] = ($fetchData->status) ? 1 : 0;
                    }
                    if ($get_child == 0) {
                        // update parent
                        $parent_update = IbSetting::where('id', $fetchData->parent_id)->update($data);
                    }
                    $fetchData->status = ($fetchData->status) ? 0 : 1;
                    if (auth()->user()->type == "system") {
                        $fetchData->system_disable = ($fetchData->status) ? 0 : 1;
                    }
                    $single_update = $fetchData->save();
                    if ($single_update == true) {
                        return ([
                            'status' => true,
                            'message' => 'Settings successfully update'
                        ]);
                    }
                    return ([
                        'status' => false,
                        'message' => 'Something went wrong, please try again later'
                    ]);
                }
                break;
        }
    }
}
