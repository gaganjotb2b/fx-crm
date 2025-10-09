<?php

namespace App\Services\common;

use App\Models\IB;
use Exception;

class FindRootOfTree
{
    public function findRoot($ib_id)
    {
        // Initialize an array to hold the IB tree path
        $path = [];

        // Call the recursive function to find the root
        $this->findRootRecursive($ib_id, $path);

        // The root will be the last element in the path
        return end($path);
    }

    private function findRootRecursive($ib_id, &$path)
    {
        $ib = IB::where('reference_id', $ib_id)->first();

        if ($ib) {
            // Add the current IB ID to the path
            array_push($path, $ib->ib_id);

            // Recur for the parent IB
            $this->findRootRecursive($ib->ib_id, $path);
        }
    }
}
