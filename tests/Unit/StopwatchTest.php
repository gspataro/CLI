<?php

use GSpataro\CLI\Helper\Stopwatch;

uses()->group('helpers');

it('returns a valid step', function () {
    $stopwatch = new Stopwatch();
    $startTime = $stopwatch->start();

    $firstStep = $stopwatch->step();
    expect($firstStep)
        ->toBeFloat()
        ->not->toBe(0.0)
        ->not->toBe($startTime);

    $secondStep = $stopwatch->step();
    expect($secondStep)
        ->toBeFloat()
        ->not->toBe(0.0)
        ->not->toBe($startTime)
        ->not->toBe($firstStep);
});

it('stops', function () {
    $stopwatch = new Stopwatch();
    $startTime = $stopwatch->start();

    $endTime = $stopwatch->stop();
    expect($endTime)
        ->toBeFloat()
        ->not->toBe(0.0)
        ->not->toBe($startTime);

    $testReset = $stopwatch->step();
    expect($testReset)
        ->toBe(0.0);
});
