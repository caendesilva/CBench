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
    use ConsoleHelpers;

    protected const VERSION = 'dev-master';

	protected int $iterations;
    protected float $time_start;
    protected float $time_end;
    protected ?string $name;

	public function __construct(int $iterations, ?string $name = null)
    {
		$this->iterations = $iterations;
        $this->name = $name;

        $this->init();
	}

    public function __destruct()
    {
        $this->disengage();
    }


    protected function init(): void
    {
        $this->comment(str_repeat('=', 40));
        $this->line('Preparing Benchmark script');
        $this->comment(str_repeat('-', 40));
        $this->line('Script version:    ' . self::VERSION);
        $this->line('Current time:      ' . date('Y-m-d H:i:s'));
        $this->line();
        $this->line('Iterations to run: ' . $this->iterations);
        $this->line('Name of benchmark: ' . ($this->name ?? '[not set]'));
        $this->comment(str_repeat('=', 40));
        $this->line();
    }

    protected function disengage(): void
    {
        $this->line();
        $this->comment(str_repeat('=', 40));
        $this->line('Benchmark script complete');
        $this->comment(str_repeat('-', 40));
        $this->line('Total execution time:   ' . $this->getExecutionTimeInMs() . 'ms');
        $this->line('Avg.  iteration time:   ' . $this->getAverageExecutionTimeInMs() . 'ms');
        $this->line('Total iterations:       ' . $this->iterations);
        $this->comment(str_repeat('=', 40));

    }

	protected function execute(callable $callback): void
    {
        $this->start();
		for ($i = 0; $i < $this->iterations; $i++) {
			$callback();
		}
        $this->end();
	}

    protected function start(): void
    {
        $this->time_start = microtime(true);

        $this->info('Starting benchmark...')->newline();
    }

    protected function end(): void
    {
        $this->time_end = microtime(true);

        $this->newline(2)->info('Benchmark complete!');
    }

    protected function getExecutionTimeInMs(int $precision = 2): float
    {
        return round(($this->time_end - $this->time_start) * 1000, $precision);
    }

    protected function getAverageExecutionTimeInMs(int $precision = 8): float
    {
        return round($this->getExecutionTimeInMs(32) / $this->iterations, $precision);
    }

	public static function run(callable $callback, int $iterations = 100, ?string $name = null): Benchmark
    {
		$benchmark = new Benchmark($iterations, $name);
		$benchmark->execute($callback);
		return $benchmark;
	}
}

trait ConsoleHelpers {
    protected function line(string $message = ''): self
    {
        echo $message . PHP_EOL;

        return $this;
    }

    protected function info(string $message): self
    {
        $this->line("\033[32m" . $message . "\033[0m");

        return $this;
    }

    protected function warn(string $message): self
    {
        $this->line("\033[33m" . $message . "\033[0m");

        return $this;
    }

    protected function error(string $message): self
    {
        $this->line("\033[31m" . $message . "\033[0m");

        return $this;
    }

    protected function success(string $message): self
    {
        $this->line("\033[32m" . $message . "\033[0m");

        return $this;
    }

    protected function comment(string $message): self
    {
        $this->line("\033[37m" . $message . "\033[0m");

        return $this;
    }

    protected function debug(string $message): self
    {
        $this->line("\033[36m" . $message . "\033[0m");

        return $this;
    }

    protected function newline(int $count = 1): self
    {
        $this->line(str_repeat(PHP_EOL, $count - 1));

        return $this;
    }
}