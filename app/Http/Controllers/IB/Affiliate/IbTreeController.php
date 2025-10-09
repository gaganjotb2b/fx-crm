<?php

namespace App\Http\Controllers\IB\Affiliate;

use App\Http\Controllers\Controller;
use App\Services\AllFunctionService;
use App\Services\TreeService;
use Illuminate\Http\Request;

class IbTreeController extends Controller
{
    public function __construct()
    {
        $this->middleware(AllFunctionService::access('ib_tree', 'ib'));
        $this->middleware(AllFunctionService::access('affiliate', 'ib'));
        $this->middleware('is_ib');//check the combined user is an IB
    }
    public function ib_tree(Request $request)
    {
        $ib_tree = TreeService::ib_tree(null);
        return view('ibs.affiliate.ib-tree',['ib_tree'=>$ib_tree]);
    }
}
