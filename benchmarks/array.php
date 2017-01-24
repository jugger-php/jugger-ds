<?php

/*
Результаты теста:

Count: 1048576
Running tests 10 times.
Testing 3/3 : ArrayyedArray

Test               Time   Time (%)   Memory   Memory (%)
SplFixedArray    493 ms                 0 B
Array            653 ms       32 %      0 B
JArray          3484 ms      607 %      0 B

 */

// composer vendor autoload
include __DIR__ .'/../../../autoload.php';

use jugger\ds\Ds;
use Lavoiesl\PhpBenchmark\Benchmark;

$count = pow(2, 20);
echo "Count: $count\n";

$benchmark = new Benchmark;

$benchmark->add('SplFixedArray', function() use($count) {
    $arr = new SplFixedArray($count);
    for ($i=0; $i < $count; $i++) {
        $arr[$i] = null;
    }
});
$benchmark->add('JArray', function() use($count) {
    $arr = Ds::arr();
    for ($i=0; $i < $count; $i++) {
        $arr[$i] = null;
    }
});
$benchmark->add('Array', function() use($count) {
    $arr = [];
    for ($i=0; $i < $count; $i++) {
        $arr[$i] = null;
    }
});

$benchmark->setCount(10);

$benchmark->run();
