<?php

namespace App\Http\Controllers\admins\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use App\Models\PopupImage;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use Illuminate\Support\Facades\Validator;

class DashboardPopupImageController extends Controller
{
    //basic view
    public function popupSetup(Request $request)
    {
        return view('admins.settings.dashboard-popup');
    }

    // get data in datatable for banner 160_600
    public function dt_banner(Request $request)
    {
        // return $request->use_for;
        // Start datatable operation
        // ----------------------------------------------------------------------------

        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');

        // select type= 0 for trader 
        $result = Banner::select()->where('size', $request->size)->where('use_for', $request->use_for);
        // Filter by finance

        $count = $result->count(); // <------count total rows
        $result = $result->skip($start)->take($length)->get();
        $data = array();

        $banners = [];
        $banner_rows['banner_1'] = $banner_rows['banner_2'] = $banner_rows['banner_3'] = '';
        $i =  0;
        $j = 1;
        // count collumn
        foreach ($result as $key => $value) {
            if ($value->column == 1) {
                $banner_rows['size'] = $value->size;
                $banner_rows['id_1'] = $value->id;
                $banner_rows['status_1'] = $value->active_status;
                $banner_rows['banner_1'] = $value->banner_name;
            }
            if ($value->column == 2) {
                $banner_rows['id_2'] = $value->id;
                $banner_rows['status_2'] = $value->active_status;
                $banner_rows['banner_2'] = $value->banner_name;
            }
            if ($value->column == 3) {
                $banner_rows['id_3'] = $value->id;
                $banner_rows['status_3'] = $value->active_status;
                $banner_rows['banner_3'] = $value->banner_name;
            }
        }
        $banners[] = (object) $banner_rows;
        $data = array();
        foreach ($banners as $key => $value) {
            if ($value->banner_1 != "") {
                // active status
                $status = ($value->status_1 == 0) ? 'disable' : 'enable';
                $data_feather = ($value->status_1 == 0) ? 'user-x' : 'user-check';
                // banner from contabo
                $banner_first = FileApiService::contabo_file_path($value->banner_1);
                $banner_first_url = $banner_first['dataUrl'];
                // $banner_first_url_file_type  = $banner_first['file_type'];
                $banner_1 = "   <div class='d-flex justify-content-between overflow-hidden position-relative banner-img-container'>
                                    <img class='img-ad-banner img img-fluid img-thumbnail' src='" . $banner_first_url . "' alt='image not found'/>
                                    <div class='bg-facebook buttons-banners'>
                                        <a href='javascript:void(0)' class='btn-delete-banner d-block text-white' data-id='" . $value->id_1 . "'><i data-feather='delete' class='me-1'></i> Delete</a>
                                        <a href='javascript:void(0)' class='btn-banner-status d-block text-white' data-id='" . $value->id_1 . "' data-status = '" . $status . "'><i data-feather='" . $data_feather . "' class='me-1'></i> $status</a>
                                    </div>
                                </div>";
            } else {
                $banner_1 = '';
            }
            if ($value->banner_2 != "") {
                // active status
                $status = ($value->status_2 == 0) ? 'disable' : 'enable';
                $data_feather = ($value->status_2 == 0) ? 'user-x' : 'user-check';
                // banner from contabo
                $banner_2nd = FileApiService::contabo_file_path($value->banner_2);
                $banner_2nd_url = $banner_2nd['dataUrl'];

                $banner_2 = "<div class='d-flex justify-content-between overflow-hidden position-relative banner-img-container'>
                                    <img class='img-ad-banner img img-fluid img-thumbnail' src='" . $banner_2nd_url . "' alt='image not found'/>
                                    <div class='bg-facebook buttons-banners'>
                                        <a href='javascript:void(0)' class='btn-delete-banner d-block text-white' data-id='" . $value->id_2 . "'><i data-feather='delete' class='me-1'></i> Delete</a>
                                        <a href='javascript:void(0)' class='btn-banner-status d-block text-white' data-id='" . $value->id_2 . "' data-status = '" . $status . "'><i data-feather='" . $data_feather . "' class='me-1'></i> $status</a>
                                    </div>
                                </div>";
            } else {
                $banner_2 = '';
            }
            if ($value->banner_3 != "") {
                // active status
                $status = ($value->status_3 == 0) ? 'disable' : 'enable';
                $data_feather = ($value->status_3 == 0) ? 'user-x' : 'user-check';
                // banner from contabo
                $banner_3rd = FileApiService::contabo_file_path($value->banner_3);
                $banner_3rd_url = $banner_3rd['dataUrl'];
                $banner_3 =  "   <div class='d-flex justify-content-between overflow-hidden position-relative banner-img-container'>
                                    <img class='img-ad-banner img img-fluid img-thumbnail' src='" . $banner_3rd_url . "' alt='image not found'/>
                                    <div class='bg-facebook buttons-banners'>
                                        <a href='javascript:void(0)' class='btn-delete-banner d-block text-white' data-id='" . $value->id_3 . "'><i data-feather='delete' class='me-1'></i> Delete</a>
                                        <a href='javascript:void(0)' class='btn-banner-status d-block text-white' data-id='" . $value->id_3 . "' data-status = '" . $status . "'><i data-feather='" . $data_feather . "' class='me-1'></i> $status</a>
                                    </div>
                                </div>";
            } else {
                $banner_3 = '';
            }
            $data[$i]['banner_1'] = $banner_1;
            $data[$i]['banner_2'] = $banner_2;
            $data[$i]['banner_3'] = $banner_3;
            $i++;
        }
        // return Response::json($banners);
        $output = array('draw' => $_REQUEST['draw'], 'recordsTotal' => $count, 'recordsFiltered' => $count);
        $output['data'] = $data;
        return Response::json($output);
    }

    public function popupSetupFetchData(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $result = PopupImage::select()->whereNot('status', 2);
        // Filter by finance
        $count = $result->count(); // <------count total rows
        $result = $result->orderby('id', 'DESC')->skip($start)->take($length)->get();
        $data = array();
        $i = 0;

        // $serial = 1;
        foreach ($result as $row) {
            $update_button = "";
            if ($row->status == 1) {
                $update_button = '<a data-id="' . $row->id . '" data-status="0" class="dropdown-item text-warning update-status">
                                        <i data-feather="toggle-left" class="me-50"></i>
                                        <span>Inactive</span>
                                    </a>';
            } else {
                $update_button = '<a data-id="' . $row->id . '" data-status="1" class="dropdown-item text-primary update-status">
                                        <i data-feather="toggle-right" class="me-50"></i>
                                        <span>Active</span>
                                    </a>';
            }
            $popup_file = FileApiService::contabo_file_path(isset($row->image) ? $row->image : '');
            $image_src = $popup_file['dataUrl'];
            $data[$i]['image']       = '<img style="height:60px !important;" src="' . $image_src . '" alt="">';
            $data[$i]['issue_date']  = date('d M, Y', strtotime($row->issue_date));
            $data[$i]['expire_date'] = date('d M, Y', strtotime($row->expire_date));
            $data[$i]['user_type']   = strtoupper($row->user_type);
            $data[$i]['status']      = ($row->status == 1) ? '<span class="badge badge-light-success">Active</span>' : '<span class="badge badge-light-warning">Inactive</span>';
            $data[$i]['action']      = '<td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    ' . $update_button . '
                                                    <a type="button" data-id="' . $row->id . '" data-status="2" class="dropdown-item text-danger update-status">
                                                        <i data-feather="trash" class="me-50"></i>
                                                        <span>Delete</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>';
            $i++;
        }
        $output = array('draw' => $draw, 'recordsTotal' => $count, 'recordsFiltered' => $count);
        $output['data'] = $data;
        return Response::json($output);
    }

    // ====================================================================
    // START: add popup setup
    // --------------------------------------------------------------------
    public function popupUpload(Request $request)
    {
        // return $request->all();
        $validation_rules = [
            'file_front_part' => 'required|file|mimes:jpeg,png,gif,pdf,jpg|max:2048',
            'issue_date' => 'required',
            'expire_date' => 'required',
            'user_type' => 'required',
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_rules);
        // default laravel validtion
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Please fix the following errors!'
            ]);
        }
        $popup_image = $request->file('file_front_part');
        $filename = time() . '_popup_image_' . $popup_image->getClientOriginalName();
        // filt move to contabo
        $client = FileApiService::s3_clients();
        $client->putObject([
            'Bucket' => FileApiService::contabo_bucket_name(),
            'Key' => $filename,
            'Body' => file_get_contents($popup_image)
        ]);
        // create PopupImage
        $created = PopupImage::create([
            'image' => $filename,
            'issue_date' => $request->issue_date,
            'expire_date' => $request->expire_date,
            'user_type' => $request->user_type,
            'status' => $request->status,
        ])->id;
        if ($created) {
            return Response::json([
                'status' => true,
                'message' => 'Popup image successfully uploaded.'
            ]);
        } else {
            return Response::json([
                'status' => false,
                'message' => 'Somthing went wrong, please try agian later!.'
            ]);
        }
    }

    // Popup Update
    public function popupUpdate(Request $request)
    {
        $popup = PopupImage::find($request->popup_id);
        $popup->status = $request->status;
        $msg = "";
        if ($request->status == 0) {
            $msg = 'Inactived';
        } elseif ($request->status == 1) {
            $msg = 'Actived';
        } else {
            $msg = 'Deleted';
        }
        $update = $popup->save();
        if ($update) {
            return response()->json([
                'status' => true,
                'message' => 'Popup successfully ' . $msg
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Somthing went wrong please try again later!'
            ]);
        }
    }
}
