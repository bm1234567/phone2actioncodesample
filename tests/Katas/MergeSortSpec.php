<?php

namespace phpspec\Katas;

use PhpSpec\ObjectBehavior;
use Katas\MergeSort;

class MergeSortSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(\Katas\MergeSort::class);
    }

    public function it_returns_same_array_for_single_element_array()
    {
        $this->sort([1])->shouldReturn([1]);
    }

    public function it_returns_1_2_for_array_2_1()
    {
        $this->sort([2,1])->shouldReturn([1,2]);
    }

    public function it_returns_1_2_for_array_1_2()
    {
        $this->sort([1,2])->shouldReturn([1,2]);
    }

    public function it_returns_1_2_3_for_array_2_3_1()
    {
        $this->sort([2,3,1])->shouldReturn([1,2,3]);
    }

    public function it_returns_1_2_3_5_7_57_100_for_array_5_57_1_100_2_7_3()
    {
        $this->sort([5,57,1,100,2,7,3])->shouldReturn([1,2,3,5,7,57,100]);
    }

    /**
     * If algorithm is robust enough then it should work with all possible
     * combos this test would come up with.
     *
     * If this ever comes up with a failure then we've likely missed an edge case
     * somewhere.
     */
    public function it_returns_sorted_array_for_random_array()
    {
        $randomStart = mt_rand(0, 100);
        $randomEnd = mt_rand(101, pow(2, 15));

        $sortedArray = $arrayToShuffle = range($randomStart, $randomEnd);

        shuffle($arrayToShuffle);

        echo "\n";
        echo 'STARTED: it_returns_sorted_array_for_random_array()' . "\n";
        echo '$randomStart: ' . $randomStart . "\n";
        echo '$randomEnd: ' . $randomEnd . "\n";
        echo 'array size: ' . count($arrayToShuffle) . "\n";


        $start = microtime(true);
        $this->sort($arrayToShuffle)->shouldReturn($sortedArray);
        $end = microtime(true);

        echo "\n" . 'total time (last test only): ' . ($end - $start) . "\n";

    }
}
