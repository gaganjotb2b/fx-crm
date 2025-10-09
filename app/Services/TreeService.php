<?php

namespace App\Services;

use App\Models\IB;
use App\Models\User;

class TreeService
{
    public function __call($name, $data)
    {
        if ($name === 'ib_tree') {
            return $this->create_ib_tree($data[0]);
        }
    }
    // static call of function
    public static function __callStatic($name, $data)
    {
        if ($name === 'ib_tree') {
            return (new self)->create_ib_tree($data[0]);
        }
    }
    // generate ib tree
    private function create_ib_tree($ib_id = null)
    {
        if ($ib_id == null) {
            $ib_id = auth()->user()->id;
        }
        $master_ib = User::find($ib_id);
        
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
            // check crm is combined
            if (CombinedService::is_combined()) {
                $traders = $traders->where('users.combine_access','0');
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

        $parent_tree = '<p class="mib s-ib-email bg-light-primary align-items-center"><span class="s-jstree-root-icon"><i class="fas fa-minus"></i></span><span class="s-ib-email-inner" data-tradingacc="'.$master_ib->phone.'" data-apnmae="'.ucwords($master_ib->name).'">' . $master_ib->email . '</span> <span class="s-jstree-root-caret"><i class="fa fa-caret-down"></i></span></p>';
        $ib_tree = '<div id="ib-tree">
                ' . $tree . '
                </div>';
        return ['ib_tree' => $ib_tree, 'parent_ib' => $parent_tree];
    }
    /*************************
     * get trader account
    // get phone numbers
     ************************/
    private function get_tdr_account($user_id)
    {
        $trading_accounts = User::select()->where("id", $user_id)->get();
        $phone_numbers = "";
        foreach ($trading_accounts as $tdr_ac) {
            $phone_numbers = $tdr_ac->phone;
        }
        if (empty($phone_numbers)) {
            $phone_numbers = "Phone Number not available";
        }
        return $phone_numbers;
    }

    // ib child view
    private function childView($ib_user)
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
    private function traderChildView($ib_user)
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
