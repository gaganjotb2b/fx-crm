<?php

namespace App\Services\commission;

use App\Models\CustomCommission;
use App\Models\IbCommissionStructure;
use App\Services\IbService;

class CommissionStructureService
{
    public static function reset_custom_commission()
    {
        try {


            $commissions = IbCommissionStructure::get();
            // flush custom commission
            CustomCommission::truncate();
            foreach ($commissions as $key => $value) {
                self::create_custom_commission($value->id); //create new custom structure
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    // CREATE NEW CUSTOM COMMISSION
    // CREATE CUSTOM STRUCTURE
    // public static function create_custom_commission($commission_id)
    // {
    //     try {
    //         $check = CustomCommission::where('commission_id', $commission_id)->exists();
    //         if (!$check) {
    //             // create new custom commission from this commission
    //             $commission_structure = IbCommissionStructure::find($commission_id);
    //             $commission = json_decode($commission_structure->commission);
    //             $table = [];
    //             for ($i = IbService::system_ibCommission_level(); $i >= 1; $i--) {
    //                 for ($j = 1; $j <= IbService::system_ibCommission_level(); $j++) {
    //                     $level_com = 0;
    //                     if ($i < $j) {
    //                         $level_com = 0;
    //                     } else {
    //                         $level_com = array_key_exists(($j - 1), $commission) ? $commission[$j - 1] : 0;
    //                     }
    //                     $table[(IbService::system_ibCommission_level() - $i) + 1][$j - 1] = $level_com;
    //                 }
    //             }

    //             // modify table array sum
    //             for ($i = 1; $i <= count($table); $i++) {
    //                 for ($j = 0; $j < count($table[$i]); $j++) {
    //                     if ($table[$i][$j] == 0) {
    //                         $table[$i][0] += (float)$table[1][$j];
    //                         $table[$i][$j] = 0;
    //                     }
    //                 }
    //             }
    //             // modify zero with --
    //             for ($i = 2; $i <= count($table); $i++) {
    //                 for ($j = count($table) - 1; $j > count($table) - $i; $j--) {
    //                     $table[$i][$j] = '--';
    //                 }
    //             }
    //             // return $table;
    //             for ($i = 2; $i <= count($table); $i++) {
    //                 $data = [];
    //                 for ($j = 0; $j < count($table); $j++) {
    //                     $data[] = $table[$i][$j];
    //                 }
    //                 $create = CustomCommission::create([
    //                     'commission_id' => $commission_id,
    //                     'custom_commission' => json_encode($data),
    //                 ]);
    //             }
    //             return true;
    //         }
    //         return false;
    //     } catch (\Throwable $th) {
    //         throw $th;
    //         return false;
    //     }
    // }
    
    public static function create_custom_commission($commission_id)
    {
        try {
            // return $check = CustomCommission::where('commission_id', $commission_id)->exists();
            // if (!$check) {
            //     return "sdfsdf";
                // Step 1: Fetch the IB Commission Structure
                $structure = IbCommissionStructure::find($commission_id);
                if (!$structure) return false;
                $raw_commission = json_decode($structure->commission);

                $custom_commission_found = CustomCommission::where('commission_id', $commission_id)->first();
                if ($custom_commission_found) return false;

                // Step 2: Build the table
                $table = [];
                $level_count = IbService::system_ibCommission_level();

                for ($i = $level_count; $i >= 1; $i--) {
                    for ($j = 1; $j <= $level_count; $j++) {
                        if ($i < $j) {
                            $level_com = 0;
                        } else {
                            $level_com = $raw_commission[$j - 1] ?? 0;
                        }
                        $table[($level_count - $i) + 1][$j - 1] = $level_com;
                    }
                }

                // Step 3: Adjust values (sum 0s)
                for ($i = 1; $i <= count($table); $i++) {
                    for ($j = 0; $j < count($table[$i]); $j++) {
                        if ($table[$i][$j] == 0) {
                            $table[$i][0] += (float)$table[1][$j];
                            $table[$i][$j] = 0;
                        }
                    }
                }

                // Step 4: Replace trailing values with '--'
                for ($i = 2; $i <= count($table); $i++) {
                    for ($j = $level_count - 1; $j > $level_count - $i; $j--) {
                        $table[$i][$j] = '--';
                    }
                }
                // 🧹 Step 5: Delete old rows (clean slate)
                // CustomCommission::where('commission_id', $commission_id)->delete();

                // ✅ Step 6: Create 4 new rows (levels 1–4)
                for ($i = 2; $i <= $level_count; $i++) {
                    $data = [];
                    for ($j = 0; $j < $level_count; $j++) {
                        $data[] = $table[$i][$j];
                    }

                    CustomCommission::create([
                        'commission_id' => $commission_id,
                        'custom_commission' => json_encode($data),
                        'admin_log' => 'Created from IB structure ID ' . $commission_id,
                    ]);
                }
                return true;
            // }
            // return false;
        } catch (\Throwable $th) {
            throw $th;
            return false;
        }
    }
    
    // create custom commision for a given structure
    public static function given_structure_commission($ib_group, $client_group)
    {
        try {
            $commissions = IbCommissionStructure::where('client_group_id', $client_group)->where('ib_group_id', $ib_group)->get();
            foreach ($commissions as $key => $value) {
                self::given_structure($value->id); //create new custom structure
            }
            return true;
        } catch (\Throwable $th) {
            throw $th;
            return false;
        }
    }
    // create custom with given structure
    public static function given_structure($commission_id)
    {
        try {
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
                for ($i = 2; $i <= count($table); $i++) {
                    $data = [];
                    for ($j = 0; $j < count($table); $j++) {
                        $data[] = $table[$i][$j];
                    }
                    if ($i == 2) {
                        $data[$i][0] = 4;
                        $data[$i][1] = 1;
                        $data[$i][2] = 5;
                        $data[$i][3] = 1;
                    } elseif ($i == 3) {
                        $data[$i][0] = 5;
                        $data[$i][1] = 5;
                        $data[$i][2] = 1;
                    } elseif ($i == 4) {
                        $data[$i][0] = 10;
                        $data[$i][1] = 1;
                    } elseif ($i == 5) {
                        $data[$i][0] = 11;
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
    // update ibcommission structure
    public static function update_level_first($client_group, $ib_group)
    {
        try {
            
            $strtucture = [4, 3, 2, 2, 2];
            $result = IbCommissionStructure::where('client_group_id', $client_group)->where('ib_group_id', $ib_group)->update([
                'commission' => json_encode($strtucture),
            ]);
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
