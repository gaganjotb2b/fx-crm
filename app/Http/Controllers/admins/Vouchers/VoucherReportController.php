<?php

namespace App\Http\Controllers\admins\Vouchers;

use App\Http\Controllers\Controller;
use App\Models\ManagerUser;
use App\Models\Voucher;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VoucherReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(["role:voucher report"]);
        $this->middleware(["role:offers"]);
        // system module control
        $this->middleware(AllFunctionService::access('offers', 'admin'));
        $this->middleware(AllFunctionService::access('voucher_report', 'admin'));
    }
    public function voucherReport(Request $request)
    {
        $op = $request->input('op');

        if ($op == "data_table") {
            return $this->voucherReportDT($request);
        }
        return view('admins.vouchers.voucher-report');
    }

    public function voucherReportDT($request)
    {
        try {
            $columns = ['token', 'expire_date', 'security', 'amount', 'send_to', 'use_status', 'created_at', 'status'];
            $orderby = $columns[$request->order[0]['column']];
            $result = Voucher::select('*');
            // check if login is manager
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
                $result = $result->whereIn('vouchers.user_id', $users_id);
            }
            $use_status = $request->input('use_status');
            $from = $request->input('from');
            $to = $request->input('to');
            $min = $request->input('min');
            $max = $request->input('max');
            $email = $request->input('email');

            //-------------------------------------------------------------------
            //Filter Start
            //-------------------------------------------------------------------


            if ($use_status != "") {
                $result = $result->where('use_status', '=', $use_status);
            }

            if ($email != "") {
                $result = $result->where('send_to', '=', $email);
            }
            
            if ($min != "") {
                $result = $result->where("amount", '>=', $min);
            }
            if ($max != "") {
                $result = $result->where("amount", '<=', $max);
            }
            if ($from != "") {
                $result = $result->whereDate("expire_date", '>=', $from);
            }
            if ($to != "") {
                $result = $result->whereDate("expire_date", '<=', $to);
            }



            $count = $result->count();
            $result = $result->orderby($orderby, $request)->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $user) {


                // dd($user);
                $st = 'checked="checked"';
                if ($user->status == 0) {
                    $st = '';
                }
                //status permisson
                $permisson_status = '';
                if (auth()->user()->hasDirectPermission('edit voucher report')) {

                    $permisson_status = '<div class="form-check form-switch user-switch"><input ' . $st . ' value="' . $user->status . '" role="switch" class="form-check-input status-switch" type="checkbox" 
                                        name="status" id="user_switch" data-id="' . $user->id . '""><label class="form-check-label user-switch" for="switcher-id-' . $user->user_id . '"></label></div>';
                } else {
                    $permisson_status = '<span class="text-danger">No Permission to Access</span>';
                }

                if ($user->use_status == 'P') {
                    $status = '<span class="badge badge-light-warning">Pending</span>';
                } elseif ($user->use_status == 'U') {
                    $status = '<span class="badge badge-light-success">Used</span>';
                } elseif ($user->use_status == 'E') {
                    $status = '<span class="badge badge-light-danger">Expired</span>';
                }

                $data[$i]['token']       = $user->token;
                $data[$i]['trader']      = $user->send_to;
                $data[$i]['amount']      = '$ ' . $user->amount;
                $data[$i]['use_status']  = $status;
                $data[$i]['exp_date']    = date('d M y, h:i A', strtotime($user->expire_date));
                $data[$i]['security']    = $user->security;
                $data[$i]['create_date'] = date('d M y, h:i A', strtotime($user->created_at));
                $data[$i]['status']      = $permisson_status;
                $i++;
            }

            return Response::json([
                'draw'=>$request->draw,
                'recordsTotal'=>$count,
                'recordsFiltered'=>$count,
                'data'=>$data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return Response::json([
                'draw'=>$request->draw,
                'recordsTotal'=>0,
                'recordsFiltered'=>0,
                'data'=>[],
            ]);
        }
    }
    // change status-----------------------------------

    public function change_status(Request $request)
    {
        $response = [];
        if (isset($request->status) && $request->status == 1) {
            $update = Voucher::find($request->voucher_id);
            $update->status = 0;
            $change = $update->save();
            if ($change) {
                $response['success'] = true;
                $response['message'] = 'The voucher was Deactivated';
            } else {
                $response['success'] = false;
                $response['message'] = 'The voucher deactivation failed!';
            }
        } else {
            $update = Voucher::find($request->voucher_id);
            $update->status = 1;
            //$update->use_status='P';
            $change = $update->save();
            if ($change) {
                $response['success'] = true;
                $response['message'] = 'The voucher was activated';
            } else {
                $response['success'] = false;
                $response['message'] = 'The voucher activation failed!';
            }
        }
        return Response::json($response);
    }
    // ending chnage status--------------------------
}
