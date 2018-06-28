<?php

namespace Katas;

class MergeSort
{
    public function sort($arr)
    {
        if (count($arr) <= 1) {
            return $arr;
        }

        $mid = count($arr) / 2;

        $left = array_slice($arr, 0, $mid);
        $right = array_slice($arr, $mid, count($arr));

        $left = $this->sort($left);
        $right = $this->sort($right);

        return $this->merge($left, $right);
    }

    protected function merge($left, $right)
    {
        $sortedArr = [];
        while (count($left) > 0 || count($right) > 0) {
            if (isset($left[0]) && isset($right[0])) {
                if ($left[0] > $right[0]) {
                    $sortedArr[] = array_shift($right);

                } else {
                    $sortedArr[] = array_shift($left);
                }
            } else {
                while (isset($left[0])) {
                    $sortedArr[] = array_shift($left);
                }
                while (isset($right[0])) {
                    $sortedArr[] = array_shift($right);
                }
            }
        }

        return $sortedArr;
    }
}