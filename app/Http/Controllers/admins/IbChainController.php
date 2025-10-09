<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserDescription;
use App\Models\BankAccount;
use App\Models\Country;
use App\Models\IB;
use App\Models\TradingAccount;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use App\Services\IbService;
use Illuminate\Support\Facades\Response;

class IbChainController extends Controller
{
    public function __construct()
    {
        // system module control
        $this->middleware(AllFunctionService::access('ib_management', 'admin'));
        $this->middleware(AllFunctionService::access('ib_chain', 'admin'));
    }
    public function ibChain(Request $request)
    {
        try {
            if ($request->ajax()) {
                $ib_id = "";
                // filter by ib name / email / phone
                if ($request->ib_info != "") {
                    $ib_info = $request->ib_info;
                    $ib_result = User::where(function ($query) use ($ib_info) {
                        $query->where('users.name', 'like', '%' . $ib_info . '%')
                            ->orWhere('users.email', 'like', '%' . $ib_info . '%')
                            ->orWhere('users.phone', 'like', '%' . $ib_info . '%');
                    });
                    if (CombinedService::is_combined()) {
                        $ib_result = $ib_result->where('combine_access',1);
                    }
                    $ib_result = $ib_result->where('users.type', CombinedService::type())->select('id')->first();
                    $ib_id = ($ib_result) ? $ib_result->id : '';
                }
                // filter by trader name / email / phone
                if ($request->trader_info != "") {
                    $trader_info = $request->trader_info;
                    $trader_result = User::where(function ($query) use ($trader_info) {
                        $query->where('users.name', 'like', '%' . $trader_info . '%')
                            ->orWhere('users.email', 'like', '%' . $trader_info . '%')
                            ->orWhere('users.phone', 'like', '%' . $trader_info . '%');
                    })->where('users.type', 0)->first();
                    $ib_id = IbService::instant_parent(($trader_result)?$trader_result->id:'');
                    
                    $ib_id = (!$ib_id)?'':$ib_id;
                }
                // filter by trading account
                if ($request->account_number != "") {
                    $trading_account = TradingAccount::where('account_number',$request->trading_account)->select('user_id')->first();
                    $ib_result = IbService::instant_parent(($trading_account)?$trading_account->user_id:'');
                    $ib_id = ($ib_result)?$ib_result:'';
                }
                // generate 
                $result = AllFunctionService::ib_chain($ib_id, $request);
                $count = $result['count'];
                $result = $result['ib_chain'];
                $data = [];
                for ($i = 0; $i < count($result); $i++) {
                    $data[$i]['name'] = $result[$i]['name'];
                    $data[$i]['email'] =  $result[$i]['email'];
                    $data[$i]['level'] =  $result[$i]['level'];;
                    $data[$i]['commission_earned'] =  $result[$i]['commission_earned'];
                    $data[$i]['commission_volume'] = $result[$i]['commission_volume'];
                    $data[$i]['join_date'] = $result[$i]['join_date'];
                    $data[$i]['kyc_status'] = $result[$i]['kyc_status'];
                }

                return Response::json([
                    'draw' => $request->draw,
                    'recordsTotal' => $count,
                    'recordsFiltered' => $count,
                    'data' => $data
                ]);
            }
            // return AllFunctionService::ib_chain(4);
            return view('admins.ib-management.ib-chain');
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'draw' => $request->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }
    // ib chain fetch data
    public function ibChainFetchData(Request $request)
    {
        $search = ($request->search != "") ? $request->search : "";

        if ($search) {
            $ib_chain = '<table>
            <thead>
                <tr>
                    <th>IB Name</th>
                    <th>IB Email</th>
                    <th>IB Lavel</th>
                </tr>
            </thead>';
            $ib_id = User::where("email", $search)->where('type', CombinedService::type());
            // check crm is combined
            if (CombinedService::is_combined()) {
                $ib_id = $ib_id->where('users.combine_access', 1);
            }
            $ib_id = $ib_id->first();
            $ib_id = isset($ib_id->id) ? $ib_id->id : "";

            $result = IB::where('ib.ib_id', $ib_id)->where('type', CombinedService::type());
            // check crm is combined
            if (CombinedService::is_combined()) {
                $result = $result->where('users.combine_access', 1);
            }
            $result = $result->join('users', 'ib.reference_id', '=', 'users.id')
                ->get();

            foreach ($result as $value) {
                $level = count($value->parents);
                $level++;
                $ib_chain .= '<tr>
                    <td>' . $value->name . '</td>
                    <td>' . $value->email . '</td>
                    <td>' . $level . '</td>
                </tr>';
                if (count($value->childs)) {
                    $ib_chain .= $this->childView($value);
                }
            }
            $ib_chain .= '</table>';
            return Response::json($ib_chain);
        }
    }

    public function childView($ib_user)
    {
        $ib_chain = '';
        $level = 0;
        foreach ($ib_user->childs as $arr) {
            if (count($arr->childs)) {

                $user_as = $arr->users;
                $parent = IB::where('reference_id', $user_as->id)->first();
                if (count($parent->parents)) {
                    $parent = $this->parent_view($parent);
                }
                // $parent = $this->parent_view($user_as);
                if ($user_as->type === 'ib') {
                    $level++;
                    $ib_chain .= '<tr>
                        <td>' . $user_as->name . '</td>
                        <td>' . $user_as->email . '</td>
                        <td>' . $parent . '</td>
                    </tr>';
                }
                // $html.= $this->traderChildView($arr);
                $ib_chain .= $this->childView($arr);
            } else {
                $user_as = $arr->users;
                $parent = IB::where('reference_id', $user_as->id)->first();
                if (count($parent->parents)) {
                    $parent = count($parent->parents);
                }
                if ($user_as->type === 'ib') {
                    // $level++;
                    $ib_chain .= '<tr>
                        <td>' . $user_as->name . '</td>
                        <td>' . $user_as->email . '</td>
                        <td>' . $parent . '</td>
                    </tr>';
                }
            }
        }
        return $ib_chain;
    } //ending ib child view

    public function parent_view($ib_user)
    {
        $level = 0;
        foreach ($ib_user->parents as $arr) {
            if (count($arr->parents)) {

                $user_as = $arr->users;
                if ($user_as->type === 'ib') {
                    $level++;
                }
                // $html.= $this->traderChildView($arr);
                $level = $this->childView($arr);
            } else {
                $user_as = $arr->users;
                if ($user_as->type === 'ib') {
                    // $level++;
                    $level++;
                }
            }
        }
        return $level;
    } //ending ib child view
}
