<?php

namespace App\Http\Controllers\Admins;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\Models\admin\SystemConfig;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\systems\AdminLogService;
use Carbon\Carbon;
use PhpParser\Builder\Trait_;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:notification setting"]);
        $this->middleware(["role:settings"]);

        // system module control
        // $this->middleware(AllFunctionService::access('admin_profile', 'admin'));
        // $this->middleware(AllFunctionService::access('notifications', 'admin'));
    }
    // view notification settings
    public function allNotification(Request $request)
    {
        $notifications = Notification::whereNot('user_type', 'trigger')->get();
        return view('admins.settings.notification_setting', ['notifications' => $notifications]);
    }

    // notification settings add
    public function notificationAdd(Request $request)
    {
        $response['success'] = false;
        $response['message'] = 'Please fix the following errors.';

        $process = $_REQUEST['process'];

        if ($process == 'ns_submit') {
            unset($_POST['process']);

            // if($dbObj->Auth("admin") == false){die('Bad Request ):');}

            $notifications = $_POST['notification'];

            foreach ($notifications as $key => $value) {
                $id = $value['id'];
                unset($value['id']);
                if (!isset($value['status'])) {
                    $value['status'] = '0';
                }
                Notification::updateOrCreate(['id' => $id], $value);
            }

            $response['success'] = true;
            $response['message'] = 'Notification Setting Successfully Updated.';
            return Response::json($response);
        }
    }
    // get filter notfication
    public function filter_admin(Request $request)
    {
        try {
            $validation_ruls = [
                'admin_info' => 'nullable|string',
                'manager_client' => 'nullable|string'
            ];
            $validator = Validator::make($request->all(), $validation_ruls);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            $admin_info = $request->admin_info;
            $user = User::where(function ($q) use ($admin_info) {
                $q->where('email', $admin_info)
                    ->orWhere('name', $admin_info)
                    ->orWhere('phone', $admin_info);
            })->select()->first();
            $notifications = Notification::whereNot('user_type', 'trigger')->get();
            $notification_fieds_id = [];
            foreach ($notifications as $value) {
                array_push($notification_fieds_id, "customSwitch" . $value->id);
            }
            if ($user) {
                $notification_status = AdminNotification::where('admin_id', $user->id)->first();

                return Response::json([
                    'status' => true,
                    'message' => 'Good we got an user for notification, please setup!',
                    'user' => $user,
                    'notificaton_status' => ($notification_status) ? true : false,
                    'setup_data' => ($notification_status) ? json_decode($notification_status->nofitication_ruls) : '',
                    'notification_fieds_id' => $notification_fieds_id,
                    'notification_email' => ($notification_status) ? $notification_status->notification_email : ''
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'An admin or manager not found with this info',
                'notification_fieds_id' => $notification_fieds_id
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
    public function save_notification(Request $request)
    {
        try {
            $validation_ruls = [
                'notification_email' => 'required|email',
                'notification_user_id' => 'required'
            ];
            $validator = Validator::make($request->all(), $validation_ruls);
            if ($validator->fails()) {
                return Response::json([
                    'status' => false,
                    'message' => 'Please fix the following errors',
                    'errors' => $validator->errors(),
                ]);
            }
            $notifications = Notification::all();
            $notification__for = [];
            foreach ($notifications as $value) {
                $notification__for[str_replace(' ', '', $value->user_type . $value->type)] = $request->{str_replace(' ', '', $value->user_type . $value->type)};
            }

            $settings = json_encode($notification__for);
            // return $settings;
            $create = AdminNotification::updateOrCreate(
                [
                    'admin_id' => $request->notification_user_id,
                ],
                [
                    'notification_email' => $request->notification_email,
                    'nofitication_ruls' => $settings,
                    'admin_log' => AdminLogService::admin_log(),
                ]
            );
            if ($create) {
                //<---client email as user id
                $user = User::find(auth()->user()->id);
                activity("Admin notification settings update")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($request->all())
                    ->event("notification settings")
                    ->performedOn($user)
                    ->log("The IP address " . request()->ip() . " has been " .  "notification settings");
                // end activity log----------------->>
                return Response::json([
                    'status' => true,
                    'message' => 'Notification settings successfully done'
                ]);
            }
            return Response::json([
                'status' => false,
                'message' => 'Notification settings faild'
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!',
            ]);
        }
    }
}
