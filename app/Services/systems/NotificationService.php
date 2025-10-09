<?php

namespace App\Services\systems;

use App\Models\AdminBank;
use App\Models\CryptoAddress;
use App\Models\Deposit;
use App\Models\ManagerUser;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class NotificationService
{


    // create notification for system
    public  static function system_notification($data = [])
    {
        try {
            $notification = '';
            // store message by type
            if (array_key_exists('type', $data) && strtolower($data['type']) === 'deposit') {
                // check has message from function call
                if (array_key_exists('message', $data) && $data['message'] != "") {
                    $notification = $data['message'];
                } else {
                    $notification = 'A trader make a deposit, please check it.';
                }
            }
            // withdraw notification
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'withdraw') {
                if (array_key_exists('user_type', $data) && strtolower($data['user_type']) === 'ib') {
                    $notification = 'An IB make a withdraw please check it';
                } elseif (array_key_exists('user_type', $data) && strtolower($data['trader']) === 'trader') {
                    $notification = 'A Trader make a withdraw please try it';
                } else {
                    $notification = 'An user make a withdraw please check it';
                }
            }
            // ib to trader transfer notifiction
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'ib_to_trader') {
                $notification = 'An IB send balance to a trader please check it.';
            }
            // ib to ib transfer nofification
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'ib_to_ib') {
                $notification = 'An IB send balance to another IB please check it.';
            }
            // trader to ib transfer notification
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'tradr_to_ib') {
                $notification = 'A Trader send balance to an IB please check it.';
            }
            // trader to trader balance transfer
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'tradr_to_trader') {
                $notification = 'A Trader send balance to another Trader please check it.';
            }
            // trader registration 
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'trader_registration') {
                $notification = 'Got a new trader registration, please check it.';
            }
            // IB registration
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'ib_registration') {
                $notification = 'Got a new IB registration, please check it.';
            }
            // account to wallet transfer
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'account_to_wallet_transfer') {
                $notification = 'An user transfer balance from account to wallet, please check it';
            }
            // wallet to account transfer
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'wallet_to_account_transfer') {
                $notification = 'An user transfer balance from wallet to account, please check it.';
            }
            // ib registration request from client
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'ib_request') {
                $notification = 'An user want to be an IB, please check it.';
            }
            // kyc upload
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'kyc_upload') {
                if (array_key_exists('user_type', $data) && strtolower($data['user_type']) === 'ib') {
                    $notification = 'An IB upload a KYC document, please check it.';
                } elseif (array_key_exists('user_type', $data) && strtolower($data['user_type']) === 'trader') {
                    if (array_key_exists('message', $data) && $data['message'] != "") {
                        $notification = $data['notification'];
                    } else {
                        $notification = 'A trader upload a KYC document, please check it.';
                    }
                } else {
                    $notification = 'An user uplaod a KYC document, Please check it.';
                }
            }
            // add new bank account to client
            elseif (array_key_exists('type', $data) && strtolower($data['type']) === 'client_bank_add') {
                if (array_key_exists('user_type', $data) && strtolower($data['user_type']) === 'ib') {
                    $notification = 'A trader add a bank account, please check it.';
                } elseif (array_key_exists('user_type', $data) && strtolower($data['user_type']) === 'trader') {
                    if (array_key_exists('message', $data) && $data['message'] != "") {
                        $notification = $data['notification'];
                    } else {
                        $notification = 'A trader add a new bank account, please check it.';
                    }
                } else {
                    $notification = 'An user a new bank account, Please check it.';
                }
            }
            if ($notification != "") {

                // get all admin ids
                $all_admin = User::where('active_status', '1')->whereIn('users.type', ['2', '1'])
                    ->select('id as admin_id')
                    ->get()
                    ->pluck('admin_id');

                if (array_key_exists('user_id', $data)) {
                    $manager = ManagerUser::where('manager_users.user_id', $data['user_id'])
                        ->select('manager_id as admin_id')
                        ->get()
                        ->pluck('admin_id');

                    // Merge manager IDs with all admin IDs
                    $all_admin = $all_admin->merge($manager);
                }

                $notifications = $all_admin->map(function ($adminId) use ($data, $notification) {
                    return [
                        'notification_type' => $data['type'],
                        'user_id' => array_key_exists('user_id', $data) ? $data['user_id'] : null,
                        'user_type' => array_key_exists('user_type', $data) ? $data['user_type'] : null,
                        'admin_id' => $adminId,
                        'table_id' => array_key_exists('table_id', $data) ? $data['table_id'] : null,
                        'category' => array_key_exists('category', $data) ? $data['category'] : null,
                        'notification' => $notification,
                        'ip_address' => request()->ip(),
                        'created_at' => date('Y-m-d h:i:s', strtotime('now')),
                        'updated_at' => date('Y-m-d h:i:s', strtotime('now')),
                    ];
                });

                $create = SystemNotification::insert($notifications->toArray());
                return $create;
            }
        } catch (\Throwable $th) {
            // throw $th;
        }
    }
    // get system notfication
    public  static function get_client_notification()
    {
        try {
            $notifications = SystemNotification::where('category', 'client')
                ->where('status', 'unread')
                ->where('admin_id', auth()->user()->id)->latest()->limit(5)->get();
            return $notifications;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
