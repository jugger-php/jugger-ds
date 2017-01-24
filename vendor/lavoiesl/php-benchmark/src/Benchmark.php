<?php

namespace Lavoiesl\PhpBenchmark;

class Benchmark
{
    /**
     * @var array [Test]
     */
    private $tests = array();

    private $n = null;

    private $base_results;

    public function addTest(AbstractTest $test)
    {
        $this->tests[$test->getName()] = $test;
    }

    /**
     * Utility method to create tests on the fly
     * You may chain the test: 
     * 
     * @param string   $name
     * @param \Closure $closure function to execute
     * @return SimpleTest
     */
    public function add($name, \Closure $closure)
    {
        $test = new SimpleTest($name, $closure);
        $this->addTest($test);

        return $test;
    }

    /**
     * Runs an empty test to determine the benchmark overhead and run each test once
     */
    private function warmup()
    {
        $warmup = new SimpleTest('warmup', function(){});
        $warmup->run();

        foreach ($this->tests as $test) {
            $test->run();
        }

        $this->base_results = $warmup->run($this->n);
    }

    public function run($output = true)
    {
        $results = array();

        if (null === $this->n) {
            $this->guessCount(2); // aim for around 2 seconds per test
        }

        if ($output) {
            echo "Running tests {$this->n} times.\n";
        }

        $this->warmup();

        $i = 0;
        foreach ($this->tests as $name => $test) {
            if ($output) {
                echo "Testing ".++$i."/".count($this->tests)." : $name\r";
            }
            $results[$name] = $test->run($this->n);
        }

        if ($output) {
            echo "\n\n";
            self::outputTable(self::formatResults($results));
        }

        return $results;
    }

    public function setCount($n)
    {
        $this->n = $n;
    }

    /**
     * Average the guessCount of each test, determining the best n
     *
     * @param  float $max_seconds
     * @return int
     */
    public function guessCount($max_seconds)
    {
        if (!$this->tests) {
            throw new \RuntimeException('No test in Benchmark.');
        }

        $n = INF;

        foreach ($this->tests as $test) {
            $n = min($n, $test->guessCount($max_seconds));
        }

        return $this->n = Util::round($n);
    }

    /**
     * Output results in columns, padding right if values are string, left if numeric
     *
     * @param  array   $lines array(array('Name' => 'Value'));
     * @param  integer $padding   space between columns
     */
    public static function outputTable(array $lines, $padding = 3)
    {
        if (!$lines) {
            return;
        }

        $pad = function ($string, $width) use ($padding) {
            if ($width > 0) {
                return str_pad($string, $width, " ") . str_repeat(' ' , $padding);
            } else {
                return str_pad($string, -$width, " ", STR_PAD_LEFT) . str_repeat(' ' , $padding);
            }
        };

        // init width with keys' length
        $cols = array_combine(array_keys($lines[0]), array_map('strlen', array_keys($lines[0])));

        foreach ($cols as $col => $width) {
            foreach ($lines as $line) {
                $width = max($width, strlen($line[$col]));
            }

            // pad left if numeric
            if (preg_match('/^[0-9]/', $line[$col])) {
                $width = -$width;
            }

            echo $pad($col, $width);
            $cols[$col] = $width;
        }
        echo "\n";

        foreach ($lines as $line) {
            foreach ($cols as $col => $width) {
                echo $pad($line[$col], $width);
            }
            echo "\n";
        }
    }

    /**
     * Format the results, rounding numbers, showing difference percentages
     * and removing a flat time based on the benchmark overhead
     *
     * @param  array  $results array($name => array('time' => 1.0))
     * @return array array(array('Test' => $name, 'Time' => '1000 ms', 'Perc' => '100 %'))
     */
    public function formatResults(array $results)
    {
        uasort($results, function($a, $b) {
            if ($a['time'] == $b['time'])
                return 0;
            else
                return ($a['time'] < $b['time']) ? -1 : 1;
        });

        $min_time = INF;
        $min_memory = INF;

        foreach ($results as $name => $result) {
            $time = $result['time'];
            $time -= $this->base_results['time']; // Substract base_time
            $time *= 1000; // Convert to ms
            $time = round($time);
            $time = max(1, $time); // min 1 ms
            $min_time = min($min_time, $time);
            $results[$name]['time'] = $time;

            $min_memory = min($min_memory, $results[$name]['memory']);
        }

        $output = array();

        foreach ($results as $name => $result) {
            $output[] = array(
                'Test'       => $name,
                'Time'       => $result['time'] . ' ms',
                'Time (%)'   => Util::relativePerc($min_time, $result['time']),
                'Memory'     => Util::convertToSI($result['memory']),
                'Memory (%)' => Util::relativePerc($min_memory, $result['memory']),
            );
        }

        return $output;
    }
}