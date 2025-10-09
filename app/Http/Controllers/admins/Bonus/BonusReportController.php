<?php

namespace App\Http\Controllers\admins\Bonus;

use App\Http\Controllers\Controller;
use App\Models\BonusPackage;
use App\Models\BonusUser;
use App\Models\ManagerUser;
use App\Services\AllFunctionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BonusReportController extends Controller
{
    public function __construct()
    {
        // system module control
        $this->middleware(AllFunctionService::access('offer_bonus_report', 'admin'));
        $this->middleware(AllFunctionService::access('offers', 'admin'));
    }
    public function BonusReport()
    {
        return view('admins.bonus.bonus-report');
    }
    public function BonusReportProcess(Request $request)
    {
        try {

            $columns = ['pkg_name', 'bonus_amount', 'start_date', 'end_date', 'status', 'created_at'];
            $orderby = $columns[$request->order[0]['column']];

            $result = BonusUser::select(
                'bonus_users.*',
                'users.email',
                'bonus_packages.pkg_name',
            )
                ->join('users', 'bonus_users.user_id', '=', 'users.id')
                ->join('bonus_packages', 'bonus_users.bonus_package', '=', 'bonus_packages.id');
            if (auth()->user()->type === 'manager') {
                $users_id = ManagerUser::where('manager_id', auth()->user()->id)->select('user_id')->get();
                $result = $result->whereIn('user_id', $users_id);
            }
            // filter by 
            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();

            foreach ($result as $user) {
                // status
                $status = ($user->status == 1) ? 'Credited' : 'Pending';
                $data[] = [
                    'email' => $user->email,
                    'bonus_name' => ucwords($user->pkg_name),
                    'account_number' => $user->account_number,
                    'price' => $user->amount,
                    'credit_expire' => date('d M y h:i:s', strtotime($user->credit_expire)),
                    'status' => ucwords($status),
                    'credit_date' => date('d M y h:i:s', strtotime($user->created_at)),
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
}
