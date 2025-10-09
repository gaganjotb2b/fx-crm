<?php

namespace App\Http\Controllers\admins;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\User;
use App\Services\AllFunctionService;
use Carbon\Carbon;
use PhpParser\Builder\Trait_;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:announcement"]);
        $this->middleware(["role:settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('announcement', 'admin'));
    }
    public function announcement()
    {
        return view('admins.settings.announcement');
    }
    public function announcementFetchData(Request $request)
    {
        try {
            $result = Announcement::select();
            // Filter by finance
            $count = $result->count(); // <------count total rows
            $result = $result->orderby('id', 'DESC')->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            // $serial = 1;
            foreach ($result as $row) {
                $data[$i]['title']       = $row->title;
                $data[$i]['dashboard']   = $row->dashboard;
                $data[$i]['status']      = ($row->status == 1) ? "Open" : "Close";
                $data[$i]['date']        = date('d M, Y H:i:s A', strtotime($row->created_at));
                $data[$i]['action']      = '<td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a data-id="' . $row->id . '" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#announcement-edit-form" id="announcement-edit-button">
                                                        <i data-feather="edit-2" class="me-50"></i>
                                                        <span>Edit</span>
                                                    </a>
                                                    <a type="button" data-id="' . $row->id . '" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#announcement-delete-modal" id="announcement-delete-button">
                                                        <i data-feather="trash" class="me-50"></i>
                                                        <span>Delete</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>';
                $i++;
            }
            return Response::json([
                'draw' => $request->draw, 
                'recordsTotal' => $count, 
                'recordsFiltered' => $count,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw' => $request->draw, 
                'recordsTotal' => 0, 
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
    // add announcement
    public function announcementAdd(Request $request)
    {
        $validation_rules = [
            'title'         => 'required',
            'comment'       => 'required',
            'dashboard'     => 'required',
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return Response::json(['success' => false, 'errors' => $validator->errors()]);
            } else {
                return Redirect()->back()->with(['success' => false, 'errors' => $validator->errors()]);
            }
        } else {
            $announcement_id = Announcement::create([
                'title'         => $request->title,
                'comment'       => $request->comment,
                'dashboard'     => $request->dashboard,
                'status' => $request->status
            ])->id;
            // if ($announcement_id) {
            //     if (strtolower($request->dashboard) == "trader") {
            //         User::where('type', 0)->update([
            //             // 'announcement_id' =
            //         ]);
            //     } elseif (strtolower($request->dashboard) == "ib") {
            //     } elseif (strtolower($request->dashboard) == "staff") {
            //     } elseif (strtolower($request->dashboard) == "all") {
            //     }
            // }
            if ($announcement_id) {
                if ($request->ajax()) {
                    return Response::json(['success' => true, 'message' => 'Successfully Inserted.']);
                } else {
                    return Redirect()->back()->with(['success' => true, 'message' => 'Successfully Inserted.']);
                }
            } else {
                if ($request->ajax()) {
                    return Response::json(['success' => false, 'message' => 'Insertion Failed!']);
                } else {
                    return Redirect()->back()->with(['success' => false, 'message' => 'Insertion Failed!']);
                }
            }
        }
    }
    // announcement modal get data
    public function announcementGetData(Request $request, $id)
    {
        $announcements = Announcement::where('id', $id)->first();
        if ($announcements) {
            if ($request->ajax()) {
                return Response::json([
                    'success' => true,
                    'title' => $announcements->title,
                    'comment' => $announcements->comment,
                    'dashboard' => $announcements->dashboard,
                    'status' => $announcements->status,
                ]);
            } else {
                return Redirect()->back()->with(['success' => true, 'message' => 'Successfully Updated.']);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['success' => false, 'message' => 'Failed To Get Data!']);
            } else {
                return Redirect()->back()->with(['success' => false, 'message' => 'Failed To Get Data!']);
            }
        }
    }
    // update announcement
    public function announcementUpdate(Request $request)
    {
        $id = $request->announcement_id;
        $title = (isset($request->title)) ? $request->title : "";
        $comment = (isset($request->comment)) ? $request->comment : "";
        $dashboard = (isset($request->dashboard)) ? $request->dashboard : "all";
        $status = (isset($request->status)) ? $request->status : 0;

        $edit_announcement = Announcement::where('id', $id)->update([
            'title'     => $title,
            'comment'   => $comment,
            'dashboard' => $dashboard,
            'status'    => $status,
        ]);
        if ($edit_announcement) {
            if ($request->ajax()) {
                return Response::json(['success' => true, 'message' => 'Successfully Updated.']);
            } else {
                return Redirect()->back()->with(['success' => true, 'message' => 'Successfully Updated.']);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['success' => false, 'message' => 'Failed To Update!']);
            } else {
                return Redirect()->back()->with(['success' => false, 'message' => 'Failed To Update!']);
            }
        }
    }

    // announcement delete
    public function announcementDelete(Request $request)
    {
        $id = $request->id;
        $delete_announcement = Announcement::find($id)->delete();
        if ($delete_announcement) {
            if ($request->ajax()) {
                return Response::json(['success' => true, 'message' => 'Successfully Deleted.']);
            } else {
                return Redirect()->back()->with(['success' => true, 'message' => 'Successfully Deleted.']);
            }
        } else {
            if ($request->ajax()) {
                return Response::json(['success' => false, 'message' => 'Failed To Delete!']);
            } else {
                return Redirect()->back()->with(['success' => false, 'message' => 'Failed To Delete!']);
            }
        }
    }
}
