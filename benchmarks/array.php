<?php

// composer vendor autoload
include __DIR__ .'/../../../autoload.php';

// benckmark
include __DIR__ .'/../vendor/autoload.php';

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
