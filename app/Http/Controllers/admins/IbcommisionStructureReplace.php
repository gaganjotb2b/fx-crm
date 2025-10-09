<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\IbSetup;
use Illuminate\Http\Request;

class IbcommisionStructureReplace extends Controller
{
    public function index()
    {
        $level = IbSetup::select('ib_level')->get()->pluck('ib_level')->first();
        $clientGroup = ClientGroup::select('group_name','group_id')->get()->pluck('group_name','group_id')->all();
        return view('admins/ib-management/ib-commision-structure-replace', ['level' => $level,'clientGroup' => $clientGroup]);
    }
}
