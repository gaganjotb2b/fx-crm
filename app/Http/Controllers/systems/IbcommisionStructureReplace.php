<?php

namespace App\Http\Controllers\systems;

use App\Http\Controllers\Controller;
use App\Models\ClientGroup;
use App\Models\CustomCommission;
use App\Models\IbCommissionStructure;
use App\Models\IbGroup;
use App\Models\IbSetup;
use App\Services\IbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Nette\Schema\Elements\Structure;

class IbcommisionStructureReplace extends Controller
{
    public function index()
    {
        $level = IbSetup::select('ib_level')->get()->pluck('ib_level')->first();
        $clientGroup = ClientGroup::select('group_name', 'id')->get()->pluck('group_name', 'id');
        $ibGroup = IbGroup::select('id', 'group_name')->get()->pluck('id', 'group_name');
        return view('systems/configurations/ib-commision-structure-replace', ['level' => $level, 'clientGroup' => $clientGroup, 'ibGroup' => $ibGroup]);
    }
    // -----------------------------------------------------First Level Structure--------------------------------------------
    public function store(Request $request)
    {
        try {
            $client_group = $request->client_group;
            $ib_group = $request->ib_group;
            $structure = $request->input_field;
            $result = IbCommissionStructure::where('client_group_id', $client_group)->where('ib_group_id', $ib_group)->update([
                'commission' => json_encode($structure),
            ]);
            // return $result;
            if ($result) {
                return Response::json([
                    'status'    => true,
                    'message'   => 'First level structure successfully updated.',
                ]);
            } else {
                return Response::json([
                    'status'    => false,
                    'message'   => 'Something went wrong, Please try again later!'
                ]);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return Response::json([
                'status'    => false,
                'message'   => 'Got a server error!'
            ]);
        }
    }
    // --------------------------------------------------------Custom Level Structure---------------------------------------------
    public function customStructureStore(Request $request)
    {
        try {
            $client_group = $request->client_group;
            $ib_group = $request->ib_group;
            $structure = $request->input_field;
            $commissions = IbCommissionStructure::where('client_group_id', $client_group)
                ->where('ib_group_id', $ib_group)
                ->get();
            return $this->given_structure(14, $structure);
            foreach ($commissions as $key => $value) {
                $this->given_structure($value->id, $structure); 
            }

            // return true;
        } catch (\Throwable $th) {
            throw $th;
            return false;
        }
    }

    public function given_structure($commission_id, $structure)
    {
        try {
            return $structure;
            $check = CustomCommission::where('commission_id', $commission_id)->exists();
            if (!$check) {
                // create new custom commission from this commission
                $commission_structure = IbCommissionStructure::find($commission_id);
                $commission = json_decode($commission_structure->commission);
                $table = [];
                for ($i = IbService::system_ibCommission_level(); $i >= 1; $i--) {
                    for ($j = 1; $j <= IbService::system_ibCommission_level(); $j++) {
                        $level_com = 0;
                        if ($i < $j) {
                            $level_com = 0;
                        } else {
                            $level_com = array_key_exists(($j - 1), $commission) ? $commission[$j - 1] : 0;
                        }
                        $table[(IbService::system_ibCommission_level() - $i) + 1][$j - 1] = $level_com;
                    }
                }

                // modify table array sum
                for ($i = 1; $i <= count($table); $i++) {
                    for ($j = 0; $j < count($table[$i]); $j++) {
                        if ($table[$i][$j] == 0) {
                            $table[$i][0] += (float)$table[1][$j];
                            $table[$i][$j] = 0;
                        }
                    }
                }
                // modify zero with --
                for ($i = 2; $i <= count($table); $i++) {
                    for ($j = count($table) - 1; $j > count($table) - $i; $j--) {
                        $table[$i][$j] = '--';
                    }
                }
                // return $table;
                for ($i=count($structure); $i >=1 ; $i--) { 
                    $levelNum = $i;
                    for ($j=1; $j <= $levelNum; $j++) { 
                        $data[$i] = $structure;
                    }
                    $create = CustomCommission::create([
                        'commission_id' => $commission_id,
                        'custom_commission' => json_encode($data),
                    ]);
                }
                return true;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
