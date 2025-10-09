<?php

namespace App\Http\Controllers\admins\notification;

use App\Http\Controllers\Controller;
use App\Models\SystemNotification;
use App\Models\User;
use App\Services\systems\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SystemNotificationController extends Controller
{
    public function view_all(Request $request)
    {
        return view('admins.allNotification.allNotification');
    }
    // get notificaton for notification bell
    public function index(Request $request)
    {
        try {
            $notfications = SystemNotification::where('admin_id', auth()->user()->id)->where('status', 'unread')->where('category', 'system')->get();
            $notification_html = '';
            foreach ($notfications as $value) {
                $notification_html .= '<a class="d-flex" href="/admin/settings/software_setting">
                <div class="list-item d-flex align-items-start">
                    <div class="me-1">
                        <div class="avatar bg-light-warning">
                            <div class="avatar-content">
                                <i class="avatar-icon" data-feather="alert-triangle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="list-item-body flex-grow-1">
                        <p class="media-heading">
                            <span class="fw-bolder">
                                ' . $value->notification_type . '
                            </span>
                        </p>
                        <small class="notification-text">
                            ' . $value->notification . '
                        </small>
                    </div>
                </div>
            </a>';
            }
            return Response::json($notification_html);
        } catch (\Throwable $th) {
            //throw $th;
            return (['Notification not available']);
        }
    }
    public function notification_count(Request $request)
    {
        try {
            $count = SystemNotification::where('admin_id', auth()->user()->id)->where('status', 'unread')->count();
            return $count;
        } catch (\Throwable $th) {
            //throw $th;
            return (0);
        }
    }
    // fetch data for all notification
    public function fetch_data(Request $request)
    {
        try {
            $result = SystemNotification::where('admin_id', auth()->user()->id);

            $count = $result->count();
            $result = $result->orderBy('id', 'DESC')->skip($request->start)->take($request->length)->get();
            $data = [];
            foreach ($result as $value) {
                $url = '';
                if (strtolower($value->notification_type) === 'deposit') {
                    $url = route('admin.manage.deposit', ['id' => $value->table_id]);
                    $url = $url . '?not=' . $value->id;
                }
                $user = User::where('id', $value->user_id)->select('email')->first();
                $email = '---';
                if ($user) {
                    $email = $user->email;
                }
                $data[] = [
                    'notification_type' => $value->notification_type,
                    'notification_text' => $value->notification,
                    'location_url' => $url,
                    'status' => $value->status,
                    'email' => $email,
                ];
            }
            return Response::json([
                'draw' => intval($request->draw),
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    // Get notifications by type
    public function getNotificationsByType(Request $request, $type)
    {
        try {
            $query = SystemNotification::where('admin_id', auth()->user()->id)
                ->where('status', 'unread');

            // Filter by notification type
            switch ($type) {
                case 'client':
                    $query->where('category', 'client');
                    break;
                case 'deposit':
                    $query->where('notification_type', 'deposit');
                    break;
                case 'withdraw':
                    $query->where('notification_type', 'withdraw');
                    break;
                case 'transfer':
                    $query->whereIn('notification_type', ['ib_to_trader', 'ib_to_ib', 'trader_to_ib']);
                    break;
                case 'account':
                    $query->whereIn('notification_type', ['account_creation', 'account_update', 'profile_update']);
                    break;
                case 'ib_request':
                    $query->where('notification_type', 'ib_request');
                    break;
                case 'bank_account':
                    $query->whereIn('notification_type', ['client_bank_add', 'bank_account_update', 'bank_account_delete'])
                          ->join('bank_accounts', 'system_notifications.table_id', '=', 'bank_accounts.id')
                          ->where('bank_accounts.approve_status', 'p');
                    break;
                case 'system':
                    $query->where('category', 'system');
                    break;
                default:
                    $query->where('notification_type', $type);
            }

            $notifications = $query->latest()->limit(5)->get();
            $count = $query->count();

            $html = '';
            foreach ($notifications as $notification) {
                $icon = $this->getNotificationIcon($notification->notification_type);
                $bgColor = $this->getNotificationBgColor($notification->notification_type);
                
                $html .= '<a class="d-flex" href="' . $this->getNotificationUrl($notification) . '">
                    <div class="list-item d-flex align-items-start">
                        <div class="me-1">
                            <div class="avatar ' . $bgColor . '">
                                <div class="avatar-content">
                                    <i class="avatar-icon" data-feather="' . $icon . '"></i>
                                </div>
                            </div>
                        </div>
                        <div class="list-item-body flex-grow-1">
                            <p class="media-heading">
                                <span class="fw-bolder">' . ucwords(str_replace('_', ' ', $notification->notification_type)) . '</span>
                            </p>
                            <small class="notification-text">' . $notification->notification . '</small>
                        </div>
                    </div>
                </a>';
            }

            if (empty($html)) {
                $html = '<p style="margin-left:1.28rem;">No notifications available.</p>';
            }

            return Response::json([
                'html' => $html,
                'count' => $count
            ]);
        } catch (\Throwable $th) {
            return Response::json([
                'html' => '<p style="margin-left:1.28rem;">Error loading notifications.</p>',
                'count' => 0
            ]);
        }
    }

    // Get notification icon based on type
    private function getNotificationIcon($type)
    {
        switch (strtolower($type)) {
            case 'deposit':
                return 'plus-circle';
            case 'withdraw':
                return 'minus-circle';
            case 'ib_to_trader':
            case 'ib_to_ib':
            case 'trader_to_ib':
                return 'repeat';
            case 'account_creation':
            case 'account_update':
            case 'profile_update':
                return 'settings';
            case 'ib_request':
                return 'user-plus';
            case 'client_bank_add':
            case 'bank_account_update':
            case 'bank_account_delete':
                return 'credit-card';
            default:
                return 'bell';
        }
    }

    // Get notification background color based on type
    private function getNotificationBgColor($type)
    {
        switch (strtolower($type)) {
            case 'deposit':
                return 'bg-light-success';
            case 'withdraw':
                return 'bg-light-warning';
            case 'ib_to_trader':
            case 'ib_to_ib':
            case 'trader_to_ib':
                return 'bg-light-info';
            case 'account_creation':
            case 'account_update':
            case 'profile_update':
                return 'bg-light-secondary';
            case 'ib_request':
                return 'bg-light-purple';
            case 'client_bank_add':
            case 'bank_account_update':
            case 'bank_account_delete':
                return 'bg-light-info';
            default:
                return 'bg-light-primary';
        }
    }

    // Get notification URL based on type
    private function getNotificationUrl($notification)
    {
        switch (strtolower($notification->notification_type)) {
            case 'deposit':
                return route('admin.manage.deposit', ['id' => $notification->table_id]) . '?not=' . $notification->id;
            case 'withdraw':
                return route('admin.manage.withdraw', ['id' => $notification->table_id]) . '?not=' . $notification->id;
            case 'ib_request':
                return route('admin.combine-ib-request') . '?not=' . $notification->id;
            case 'client_bank_add':
            case 'bank_account_update':
            case 'bank_account_delete':
                return route('admin.manage_banks.bank_account_list') . '?not=' . $notification->id . '&status=pending';
            default:
                return route('admin.system-notification.view-all') . '?type=' . $notification->notification_type;
        }
    }

    // Test method to create sample notifications
    public function createSampleNotifications()
    {
        try {
            $adminId = auth()->user()->id;
            
            // Create sample notifications for each type
            $sampleNotifications = [
                [
                    'notification_type' => 'deposit',
                    'notification' => 'New deposit request of $1000 from trader John Doe',
                    'category' => 'client',
                    'user_id' => 1,
                    'table_id' => 1
                ],
                [
                    'notification_type' => 'withdraw',
                    'notification' => 'Withdrawal request of $500 from trader Jane Smith',
                    'category' => 'client',
                    'user_id' => 2,
                    'table_id' => 2
                ],
                [
                    'notification_type' => 'ib_to_trader',
                    'notification' => 'IB transfer of $200 to trader account',
                    'category' => 'client',
                    'user_id' => 3,
                    'table_id' => 3
                ],
                [
                    'notification_type' => 'account_creation',
                    'notification' => 'New trader account created for user ID 123',
                    'category' => 'client',
                    'user_id' => 4,
                    'table_id' => 4
                ],
                [
                    'notification_type' => 'ib_request',
                    'notification' => 'New IB registration request from trader Alex Johnson',
                    'category' => 'client',
                    'user_id' => 5,
                    'table_id' => 5
                ],
                [
                    'notification_type' => 'client_bank_add',
                    'notification' => 'New bank account request from trader Sarah Wilson',
                    'category' => 'client',
                    'user_id' => 6,
                    'table_id' => 6
                ],
                [
                    'notification_type' => 'system_alert',
                    'notification' => 'System maintenance scheduled for tomorrow',
                    'category' => 'system',
                    'user_id' => null,
                    'table_id' => null
                ]
            ];

            foreach ($sampleNotifications as $notification) {
                SystemNotification::create([
                    'notification_type' => $notification['notification_type'],
                    'user_id' => $notification['user_id'],
                    'user_type' => 'trader',
                    'admin_id' => $adminId,
                    'category' => $notification['category'],
                    'notification' => $notification['notification'],
                    'status' => 'unread',
                    'ip_address' => request()->ip(),
                    'table_id' => $notification['table_id'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sample notifications created successfully!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating sample notifications: ' . $th->getMessage()
            ]);
        }
    }
}
