<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use App\Models\Banner;
use App\Services\AllFunctionService;
use App\Services\api\FileApiService;
use Illuminate\Support\Facades\Validator;

class BannerControllerAdmin extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:banner setup"]);
        $this->middleware(["role:settings"]);
        // system module control
        $this->middleware(AllFunctionService::access('settings', 'admin'));
        $this->middleware(AllFunctionService::access('banner_setup', 'admin'));
    }
    //basic view
    public function index(Request $request)
    {
        return view('admins.settings.banner-setup');
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

    // ====================================================================
    // START: store banner 
    // --------------------------------------------------------------------
    public function upload(Request $request)
    {
        // validation check
        $validation_rules = [
            'file'=>'file|mimes:jpeg,jpg,gif,png'
        ];
        $validator = Validator::make($request->all(),$validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'status'=>false,
                'message'=>$validator->errors()['file']
            ]);
        }
        $uploadedFile = $request->file('file');
        $filename = time() . '_banner_' . $request->size . '_' . $request->column . '_' . $uploadedFile->getClientOriginalName();
        // $uploadedFile->move(public_path('/Uploads/banners'), $filename);
        $client = FileApiService::s3_clients();
        $client->putObject([
            'Bucket' => FileApiService::contabo_bucket_name(),
            'Key' => $filename,
            'Body' => file_get_contents($uploadedFile)
        ]);

        $data = [
            'size' => $request->size,
            'banner_name' => $filename,
            'column' => $request->column,
            'use_for' => $request->use_for,
            'language' => $request->choosen_language,
            'uploaded_by' => auth()->user()->id
        ];

        $banner_db = Banner::where('size', $request->size)->where('column', $request->column)->where('use_for', $request->use_for)->first();
        if ($banner_db) {
            // start previous image unlink
            $path = public_path('Uploads/banners/');
            if (isset($banner_db->banner_name)) {
                if ($banner_db->banner_name != "") {
                    $path .= $banner_db->banner_name;
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
            }
            // end previous image unlink

            $old_banner = $banner_db->banner_name;
            $banner_db->banner_name = $filename;
            $banner_db->size = $request->size;
            $banner_db->use_for = $request->use_for;
            $banner_db->language = $request->choosen_language;
            $banner_db->uploaded_by = auth()->user()->id;
            $update = $banner_db->save();
            if ($update) {
                Storage::disk('local')->delete('Uploads/banners/' . $old_banner);
                // insert activity-----------------
                activity("Banner uploads")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($banner_db)
                    ->event("updated")
                    ->log("The IP address " . request()->ip() . " has been updated banner");
                // end activity log-----------------
                return response()->json([
                    'name' =>  $filename,
                    'status' => true,
                ]);
            } else {
                // insert activity-----------------
                activity("Banner uploads")
                    ->causedBy(auth()->user()->id)
                    ->withProperties($banner_db)
                    ->event("uploaded")
                    ->log("The IP address " . request()->ip() . " has been uploaded banner");
                // end activity log-----------------
                return response()->json([
                    'name' =>  $filename,
                    'status' => false
                ]);
            }
        } else {
            $create = Banner::create($data);
            if ($create) {
                return response()->json([
                    'name' =>  $filename,
                    'status' => true
                ]);
            } else {
                return response()->json([
                    'name' =>  $filename,
                    'status' => false
                ]);
            }
        }
    }

    // banner enable disable--------------------------
    public function enable_disable(Request $request)
    {
        $banner = Banner::find($request->id);
        $banner->active_status  = ($request->request_for === 'enable') ? 1 : 0;
        $update = $banner->save();
        // insert activity-----------------
        activity("Banner " . $request->request_for)
            ->causedBy(auth()->user()->id)
            ->withProperties($banner)
            ->event($request->request_for)
            ->log("The IP address " . request()->ip() . " has been " . $request->request_for . " banner");
        // end activity log-----------------
        if ($update) {
            return response()->json([
                'status' => true,
                'message' => 'Banner successfully ' . $request->request_for
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Somthing went wrong please try again later!'
            ]);
        }
    }
    // delete banner---------------------------
    public function delete_banner(Request $request)
    {
        $banner = Banner::find($request->id);
        $delete = $banner->delete();
        if ($delete) {
            // start previous image unlink
            $path = public_path('Uploads/banners/');
            if (isset($banner->banner_name)) {
                if ($banner->banner_name != "") {
                    $path .= $banner->banner_name;
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
            }
            // end previous image unlink
            Storage::disk('local')->delete('Uploads/banners/' . $banner->banner_name);
            // insert activity-----------------
            activity("Banner deleted")
                ->causedBy(auth()->user()->id)
                ->withProperties($banner)
                ->event('deleted')
                ->log("The IP address " . request()->ip() . " has been deleted banner");
            // end activity log-----------------
            return response()->json([
                'status' => true,
                'message' => 'Banner successfully deleted'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Somthing went wrong please try again later!'
            ]);
        }
    }
}
