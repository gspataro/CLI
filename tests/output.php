<?php

use GSpataro\CLI\Output;
use GSpataro\CLI\EscapeCodesEnum;

require_once __DIR__ . '/bootstrap.php';

$output = new Output();

/**
 * Test styles
 */

foreach (EscapeCodesEnum::toArray() as $key => $value) {
    if ($key == "nl") {
        continue;
    }

    $output->print("{{$key}}{$key}\033[0m");
}

/**
 * Test the table functionality of the output class
 */

// Test default table

$output->print("{nl}Default table:");
$output->printTable([
    ["heading" => ["Name", "Surname", "City"]],
    ["row" => ["Wolfgang Amadeus", "Mozart", "Vienna"]],
    ["row" => ["Ludwig", "van Beethoven", "Bonn"]],
    ["row" => ["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]],
    ["row" => ["Vincenzo", "Bellini", "Catania"]]
], 3);

// Test customized table

$output->print("{nl}Customized Table:");
$output->printTable(
    structure: [
        ["heading" => ["Command Name", "Command Description"]],
        ["row" => ["ls", "List the content of a directory"]],
        ["row" => ["cd", "Enters a directory"]],
        [],
        ["custom" => ["test", "This is a test of a new style"]]
    ],
    columnsNumber: 2,
    styles: [
        "heading" => [
            "prefix" => EscapeCodesEnum::fg_red->value . EscapeCodesEnum::italic->value,
            "suffix" => EscapeCodesEnum::clear->value
        ],
        "row" => [
            "prefix" => EscapeCodesEnum::dim->value,
            "suffix" => EscapeCodesEnum::clear->value
        ],
        "custom" => [
            "prefix" => EscapeCodesEnum::fg_green->value,
            "suffix" => EscapeCodesEnum::clear->value
        ]
    ]
);

/**
 * Test the stopwatch functionality
 */

$output->print("{nl}Stopwatch:");

$output->startStopwatch('test');
sleep(3);
$output->print("Elapsed: {$output->stopStopwatch('test')}");

// Test stopwatch step

$output->print("{nl}Stopwatch step:");

$output->startStopwatch('step');
sleep(3);
$output->print("First step: {$output->stepStopwatch('step')}");
sleep(3);
$output->print("Elapsed: {$output->stopStopwatch('step')}");

// Test multiple stopwatches

$output->print("{nl}Multiple stopwatches:");

$output->startStopwatch('first');
$output->startStopwatch('second');

sleep(3);
$output->print("First stopwatch elapsed time: {$output->stopStopwatch('first')}");

sleep(5);
$output->print("Second stopwatch elapsed time: {$output->stopStopwatch('second')}");
