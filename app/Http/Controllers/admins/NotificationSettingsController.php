<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class NotificationSettingsController extends Controller
{
    public function __construct()
    {
        // $this->middleware(["role:support"]);
        // $this->middleware(["role:client ticket"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('notification_template', 'admin'));
    }
    public function index(Request $request)
    {
        return view('admins.notification-settings.notification-settings');
    }
    // datatable
    public function data_table(Request $request)
    {
        try {
            $columns = ['type', 'description', 'notification_body', 'notification_body', 'notification_body', 'created_at', 'status'];
            $result = Notification::where('status', 1);

            $count = $result->count();
            $result = $result->orderBy($columns[$request->order[0]['column']], $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = [];
            foreach ($result as $value) {
                $auth_user = User::find(auth()->user()->id);
                if ($auth_user->hasDirectPermission('edit trader admin')) {
                    $button = ' <span class="dropdown-item btn-edit-template" data-id="' . $value->id . '">Edit</span>';
                } else {
                    $button = '<span class="text-danger">' . __('page.you_dont_have_right_permission') . '</span>';
                }
                if ($value->status == '1') {
                    $status = "<span class='badge bg-success'>Enable</span>";
                } else {
                    $status =  "<span class='badge bg-danger'>Disable</span>";
                }
                $data[] = [
                    'type' => $value->type,
                    'subject' => $value->description,
                    'notification_body' => $value->notification_body,
                    'notification_footer' => $value->notification_footer,
                    'status' => $status,
                    'action' => '<div class="d-flex justify-content-between">
                                    <a href="#" class="more-actions dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                            ' . $button . '
                                            <span class="dropdown-item btn-view-template" data-id="' . $value->id . '">Preview</span>
                                    </div>

                                </div>',
                ];
            }
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }
    public function edit($id)
    {

        try {
            $getData = Notification::where('id', $id)->select()->first();
            return response()->json([
                'type' => $getData->tpye,
                'description' => $getData->description,
                'notification_body' => $getData->notification_body,
                'notification_footer' => $getData->notification_footer,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function updateNotify(Request $request)
    {
        // return $request->id;
        try {

            $validator = Validator::make($request->all(), [
                'notification_subject' => 'required',
                'notification_body' => 'required',
                'notification_footer' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => true,
                    'errors' => $validator->messages(),
                    'message' => 'Something went wrong please fix the following error!'
                ]);
            } else {

                $updateData = Notification::find($request->dataId)->update([
                    'description' => $request->notification_subject,
                    'notification_body' => $request->notification_body,
                    'notification_footer' => $request->notification_footer
                ]);
                if ($updateData) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Notifiction message Successfully Updated',
                    ]);
                }
                return response()->json([
                    'status' => false,
                    'message' => 'Update Fail', 'success_title' => 'Notification'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }
}
