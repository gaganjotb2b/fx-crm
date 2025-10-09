<?php

namespace App\Http\Controllers\IB\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\IB;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class myIbAffiliateController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('my_ib', 'ib'));
        $this->middleware(AllFunctionService::access('affiliate', 'ib'));
        $this->middleware('is_ib'); // check the combined user is an IB
    }
    public function myIb(Request $request)
    {
        $op = $request->input('op');
        // call datatable
        if ($request->ajax()) {
            return $this->myIbDT($request);
        }
        return view('ibs.affiliate.myIb-clients');
    }

    public function myIbDT($request)
    {
        try {
            $columns = ['ib_id', 'name', 'ib_id', 'country_id', 'created_at', 'phone', 'ib_id', 'ib_id', 'ib_id'];
            $orderby = $columns[$request->order[0]['column']];
            $result = IB::select(
                'ib.ib_id',
                'ib.reference_id',
                'users.id',
                'users.name',
                'users.email',
                'users.created_at',
                'users.phone',
                'user_descriptions.user_id',
                'user_descriptions.country_id'
            )->join('users', 'ib.reference_id', '=', 'users.id')
                ->leftJoin('user_descriptions', 'ib.reference_id', '=', 'user_descriptions.user_id')
                ->where('users.type', CombinedService::type());
            // check crm is combined
            if (CombinedService::is_combined()) {
                $result = $result->where('users.combine_access', 1);
            }
            // start filter 
            // filter by ib
            if ($request->subib == 'mydir') {
                $result = $result->where('ib_id', auth()->user()->id);
            }
            // filter by my sub ib
            elseif ($request->subib === 'mysub') {
                $subibs = AllFunctionService::my_sub_ib_id(auth()->user()->id);
                $result = $result->whereIn('ib_id', $subibs);
            }
            // filter all 
            else {
                $subibs = AllFunctionService::my_sub_ib_id(auth()->user()->id);
                $result = $result->where(function ($query) use ($subibs) {
                    $query->whereIn('ib_id', $subibs)
                        ->orWhere('ib_id', auth()->user()->id);
                });
            }

            //Filter By IB & Sub IB Name/Email/phone
            if ($request->ib_info != "") {
                $affiliate_by = User::where(function ($query) use ($request) {
                    $query->where('email', 'LIKE', '%' . $request->ib_info . '%')
                        ->orWhere('name', 'LIKE', '%' . $request->ib_info . '%')
                        ->orWhere('phone', 'LIKE', '%' . $request->ib_info . '%');
                })->first();
                $checkIb = IB::where(function ($query) use ($affiliate_by) {
                    $query->where('reference_id', $affiliate_by['id'])
                        ->orWhere('ib_id', $affiliate_by['id']);
                })->pluck('reference_id','ib_id');
                // return $checkIb;
                $result = $result->where('users.id',$checkIb);
            }
            // /*<-------filter search script end here------------->*/f      

            $count = $result->count();
            $result = $result->orderby($orderby, $request->order[0]['dir'])->skip($request->start)->take($request->length)->get();
            $data = array();
            $i = 0;

            foreach ($result as $ib) {
                $country = Country::select('name')->where('id', $ib->country_id)->get();
                foreach ($country as $name) {
                    $country_name = $name->name;
                }
                $affiliate_by = User::select('email')->where('id', $ib->ib_id)->get();
                foreach ($affiliate_by as $user) {
                    $email = $user->email;
                }

                $total_trader = IB::select('reference_id')->where('ib_id', $ib->ib_id)
                    ->join('users', 'users.id', '=', 'ib.reference_id')->where('users.type', 0)->count();


                $data[$i]['level'] = AllFunctionService::get_node_level($ib->ib_id);
                $data[$i]['name'] = $ib->name;
                $data[$i]['email'] = $ib->email;
                $data[$i]['country'] = $country_name;
                $data[$i]['phone'] = $ib->phone;
                $data[$i]['reg_date'] = date('d F y, h:i:sa', strtotime($ib->created_at));
                $data[$i]['affiliate_by'] = $email;
                $data[$i]['total_trader'] = $total_trader;
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
}
