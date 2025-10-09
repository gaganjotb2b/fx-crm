<?php

namespace App\Services\commission;

use App\Models\IB;

class TreeService
{
    public function countIBsForReferenceID($reference_id)
    {
        $data = IB::all();
        $tree = $this->buildTree($data);
        return $tree;
        $totalIBs = $this->countIBIDs($tree, $reference_id);
        return $totalIBs;
    }
    
    public function buildTree($data)
    {
        $tree = [];
        $references = [];
        $childrenMap = [];

        foreach ($data as $row) {
            $references[$row->ib_id] = $row;
            $childrenMap[$row->ib_id] = [];
        }

        foreach ($references as $ib_id => $row) {
            if (isset($references[$row->reference_id])) {
                $parent = $references[$row->reference_id];
                $childrenMap[$parent->ib_id][] = $row;
            } else {
                $tree[] = $row;
            }
        }

        foreach ($tree as $node) {
            $this->addChildren($node, $childrenMap);
        }

        return $tree;
    }

    private function addChildren($node, $childrenMap)
    {
        if (isset($childrenMap[$node->ib_id])) {
            $node->children = $childrenMap[$node->ib_id];
            foreach ($node->children as $child) {
                $this->addChildren($child, $childrenMap);
            }
        }
    }



    public function countIBIDs($tree, $reference_id)
    {
        $count = 0;
        foreach ($tree as $node) {
            if ($node->reference_id === $reference_id) {
                $count++;
            }
            if (isset($node->children)) {
                $count += $this->countIBIDs($node->children, $reference_id);
            }
        }
        return $count;
    }
}
