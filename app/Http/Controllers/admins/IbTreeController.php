<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IB;
use App\Models\TradingAccount;
use App\Models\User;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use App\Services\common\FindRootOfTree;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class IbTreeController extends Controller
{
    private $find_root;
    public function __construct()
    {
        $this->middleware(["role:ib tree"]);
        $this->middleware(["role:ib management"]);
        // system module control
        $this->middleware(AllFunctionService::access('ib_management', 'admin'));
        $this->middleware(AllFunctionService::access('ib_tree', 'admin'));
        // create object 
        $this->find_root = new FindRootOfTree();
    }
    //IB Tree basic view
    // ---------------------------------------------------------------------------------
    public function index(Request $request)
    {
        return view('admins.ib-management.ib-tree');
    }

    // START: Create ib tree
    // ----------------------------------------------------------------------------------------------
    public function create(Request $request,)
    {
        try {
            $user_id = '';
            // finding IB-----------------------------------------------------
            $search_result = User::select();
            // filter by ib info
            if ($request->ib_info != "") {
                $ib_info = $request->ib_info;
                $search_result = $search_result->where(function ($query) use ($ib_info) {
                    $query->where('users.name', 'like', '%' . $ib_info . '%')
                        ->orWhere('users.email', 'like', '%' . $ib_info . '%')
                        ->orWhere('users.phone', 'like', '%' . $ib_info . '%');
                });
                if (CombinedService::is_combined()) {
                    $search_result = $search_result->where('users.type', CombinedService::type());
                }
                $search_result = $search_result->first();
                $user_id = ($search_result) ? $search_result->id : '';
            }
            // filter by trader info
            if ($request->trader_info != "") {
                $trader_info = $request->trader_info;
                $trader_result = User::where('users.type', 0)->where(function ($query) use ($trader_info) {
                    $query->where('users.name', 'like', '%' . $trader_info . '%')
                        ->orWhere('users.email', 'like', '%' . $trader_info . '%')
                        ->orWhere('users.phone', 'like', '%' . $trader_info . '%');
                })->first();
                $user_id = $this->find_root->findRoot(($trader_result) ? $trader_result->id : '');
            }
            // filter by trading account
            if ($request->account_number != "") {
                $trading_account = TradingAccount::where('account_number',$request->account_number)->select('user_id')->first();
                $trader_id = ($trading_account)?$trading_account->user_id:'';
                $user_id = $this->find_root->findRoot($trader_id);
            }
            // start ib tree -------------------------------------------------

            $has_email = User::where("id", $user_id)->where('type', CombinedService::type());
            // check crm is combine
            if (CombinedService::is_combined()) {
                $has_email = $has_email->where('users.combine_access', '1');
            }
            $has_email = $has_email->exists();
            if ($user_id != "") {
                if ($has_email) {
                    // get root node data
                    $ib_id = User::where("id", $user_id)->where('type', CombinedService::type());
                    // check crm is combined
                    if (CombinedService::is_combined()) {
                        $ib_id = $ib_id->where('combine_access', '1');
                    }
                    $ib_id = $ib_id->first();
                    // get root name
                    $root_name = ($ib_id) ? $ib_id->name : 'N/A';
                    $ib_email = ($ib_id) ? $ib_id->email : 'N/A';
                    $root_phone = ($ib_id) ? $ib_id->phone : 'N/A';
                    $ib_id = isset($ib_id->id) ? $ib_id->id : "";
                    // ib's under ib
                    // -------------------------------------------------------------------------------
                    $ib_users = IB::where('ib.ib_id', $ib_id)->where('type', CombinedService::type())
                        ->join('users', 'ib.reference_id', '=', 'users.id');
                    // check crm is combined
                    if (CombinedService::is_combined()) {
                        $ib_users = $ib_users->where('users.combine_access', '1');
                    }
                    $ib_users = $ib_users->get();

                    // traders under trader
                    // ---------------------------------------------------------------------------
                    $traders = IB::where('ib.ib_id', $ib_id)->where('type', 0)
                        ->join('users', 'ib.reference_id', '=', 'users.id');
                    if (CombinedService::is_combined()) {
                        $traders = $traders->whereIn('combine_access', ['0', '2']);
                    }
                    $traders = $traders->get();

                    $tree = '<ul>';
                    foreach ($ib_users as $ib_user) {
                        $tree .= '<li rel="web" data-tradingacc="' . $this->get_tdr_account($ib_user->reference_id) . '" data-apnmae="' . $ib_user->name . '">' . $ib_user->email . '<span class="tree-more-info" onclick="tree_more(this)" data-id="' . $ib_user->id . '" id="tree-more-' . $ib_user->id . '"><i class="fa fa-caret-down"></i></span>';
                        if (count($ib_user->childs)) {
                            $tree .= $this->childView($ib_user);
                        }
                    }
                    foreach ($traders as $trader) {
                        $tree .= '<li rel="web" data-tradingacc="' . $this->get_tdr_account($trader->reference_id) . '" class="trader-tree-item" data-type="trader" data-apnmae="' . $trader->name . '">' . $trader->email . '<span class="tree-more-info" onclick="tree_more(this)" data-id="' . $trader->id . '" id="tree-more-' . $trader->id . '"><i class="fa fa-caret-down"></i></span>';
                        if (count($trader->trader_childs)) {
                            $tree .= $this->traderChildView($trader);
                        }
                    }
                    $tree .= '<ul>';
                    // root node
                    $parent_tree = '<p class="mib s-ib-email bg-light-primary"><span class="s-jstree-root-icon"><i class="fas fa-minus"></i></span><span class="s-ib-email-inner" data-tradingacc="' . $root_phone . '"  data-apnmae="' . $root_name . '">' . $ib_email . '</span> <span class="s-jstree-root-caret"><i class="fa fa-caret-down"></i></span></p>';
                    $ib_tree_admin = '<div id="ib-tree">
                ' . $tree . '
                </div>';
                    return Response::json(
                        [
                            'ib_tree' => $ib_tree_admin,
                            'status' => true,
                            'parent' => $parent_tree
                        ]
                    );
                }
                return Response::json(
                    [
                        'status' => false,
                        'message' => 'IB account not found with this information',
                        'parent' => null,
                        'ib_tree' => null
                    ]
                );
            }
            return Response::json([
                'status' => false,
                'message' => 'Tree not found, please provide atleast one info, that related to IB',
                'parent' => null,
                'ib_tree' => null
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return Response::json([
                'status' => false,
                'message' => 'Got a server error!'
            ]);
        }
    }

    /*************************
     * get trader account
    // get phone numbers
     ************************/
    public function get_tdr_account($user_id)
    {
        $trading_accounts = User::select()->where("id", $user_id)->first();
        $phone_numbers = "";
        // foreach ($trading_accounts as $tdr_ac) {
        //     $phone_numbers = $tdr_ac->phone;
        // }
        if ($trading_accounts) {
            $phone_numbers = ($trading_accounts->phone) ? $trading_accounts->phone : 'N/A';
        }
        if (empty($phone_numbers)) {
            $phone_numbers = "Phone Number not available";
        }
        return $phone_numbers;
    }

    // ib child view
    public function childView($ib_user)
    {
        $html = '<ul>';
        foreach ($ib_user->childs as $arr) {
            if (count($arr->trader_childs) || count($arr->childs)) {
                $user_as = $arr->users;
                $html .= '<li rel="reb" data-tradingacc="' . $this->get_tdr_account($arr->reference_id) . '" data-apnmae="' . $user_as->name . '">' . $user_as->email . '<span class="tree-more-info" onclick="tree_more(this)" data-id="' . $ib_user->id . '" id="tree-more-' . $ib_user->id . '"><i class="fa fa-caret-down"></i></span>';
                // $html.= $this->traderChildView($arr);
                $html .= $this->childView($arr);
            } else {
                $user_as = $arr->users;
                if ($user_as->type === 'ib') {
                    $html .= '<li rel="web" data-tradingacc="' . $this->get_tdr_account($arr->reference_id) . '" data-apnmae="' . $user_as->name . '">' . $user_as->email . '<span class="tree-more-info" onclick="tree_more(this)" data-id="' . $ib_user->id . '" id="tree-more-' . $ib_user->id . '"><i class="fa fa-caret-down"></i></span>';
                    $html .= "</li>";
                } else {
                    $html .= '<li class="trader-tree-item" data-tradingacc="' . $this->get_tdr_account($arr->reference_id) . '" data-type="trader" data-apnmae="' . $user_as->name . '">' . $user_as->email . '<span class="tree-more-info" onclick="tree_more(this)" data-id="' . $ib_user->id . '" id="tree-more-' . $ib_user->id . '"><i class="fa fa-caret-down"></i></span>';
                    $html .= "</li>";
                }
            }
        }
        $html .= "</ul>";
        return $html;
    } //ending ib child view

    // traders child view
    // --------------------------------------------------------------------
    public function traderChildView($ib_user)
    {
        $html = '<ul>';
        foreach ($ib_user->trader_childs as $arr) {
            if (count($arr->trader_childs)) {
                $html .= '<li class="trader-tree-item" data-tradingacc="' . $this->get_tdr_account($arr->reference_id) . '" data-type="trader" data-apnmae="' . $arr->name . '">' . $arr->email . '<span class="tree-more-info" onclick="tree_more(this)" data-id="' . $ib_user->id . '" id="tree-more-' . $ib_user->id . '"><i class="fa fa-caret-down"></i></span>';
                $html .= $this->traderChildView($arr);
            } else {
                $html .= '<li class="trader-tree-item" data-tradingacc="' . $this->get_tdr_account($arr->reference_id) . '" data-type="trader" data-apnmae="' . $arr->name . '">' . $arr->email . '<span class="tree-more-info" onclick="tree_more(this)" data-id="' . $ib_user->id . '" id="tree-more-' . $ib_user->id . '"><i class="fa fa-caret-down"></i></span>';
                $html .= "</li>";
            }
        }
        $html .= "</ul>";
        return $html;
    } //ending ib child view
}
