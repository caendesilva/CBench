<?php

/**
 * A simple opinionated script to benchmark PHP code.
 *
 * I'm not concerned about any overhead added by the helper methods,
 * as this script is intended to benchmark relative performance
 * between differing implementations. Since any overhead is
 * constant for all iterations, it does not affect the
 * comparative data results I'm interested in.
 */

class Benchmark {
	protected int $iterations;
    protected float $time_start;
    protected float $time_end;

	public function __construct(int $iterations)
    {
		$this->iterations = $iterations;
	}

	protected function execute(callable $callback): void
    {
		$this->time_start = microtime(true);
		for ($i = 0; $i < $this->iterations; $i++) {
			$callback();
		}
		$this->time_end = microtime(true);
	}

	public static function run(callable $callback, int $iterations = 100): Benchmark
    {
		$benchmark = new Benchmark($iterations);
		$benchmark->execute($callback);
		return $benchmark;
	}
}