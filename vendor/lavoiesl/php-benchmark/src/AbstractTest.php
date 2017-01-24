<?php

namespace Lavoiesl\PhpBenchmark;

abstract class AbstractTest
{
    /**
     * @var string
     */
    private $name;


    private $profiler;

    public function __construct($name)
    {
        $this->name = $name;
        $this->profiler = new Profiler;
    }

    public function getName()
    {
        return $this->name;
    }

    public function run($n = 1)
    {
        $this->prepare();

        gc_collect_cycles(); // clear memory before start

        $this->profiler->start();

        for ($i=0; $i < $n; $i++) {
            // Store the result so it appears in memory profiling
            $result = $this->execute();
            unset($result);
        }

        $this->profiler->stop();

        $results = array(
            'time'   => $this->profiler->getTime(),
            'memory' => $this->profiler->getMemoryUsage(),
            'n'      => $n,
        );

        $this->cleanup();

        return $results;
    }

    protected function prepare()
    {
    }

    abstract protected function execute();

    protected function cleanup()
    {
    }

    public function guessCount($max_seconds = 1)
    {
        $this->run(); // warmup
        $once = $this->run();

        if ($once['time'] >= $max_seconds) {
            return 1;
        } else {
            return round($max_seconds / $once['time']);
        }
    }
}
