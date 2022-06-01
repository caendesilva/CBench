<?php

require_once 'benchmark.php';

Benchmark::run(function () {
    echo 'Hello World!';
});