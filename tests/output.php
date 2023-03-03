<?php

use GSpataro\CLI\Output;
use GSpataro\CLI\OutputFormatEnum;

/**
 * Test the table functionality of the output class
 */

require_once __DIR__ . '/bootstrap.php';

$output = new Output();

// Test default table

$output->print("Default table:");
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
            "prefix" => OutputFormatEnum::red->value . OutputFormatEnum::italic->value,
            "suffix" => OutputFormatEnum::clear->value
        ],
        "row" => [
            "prefix" => OutputFormatEnum::dim->value,
            "suffix" => OutputFormatEnum::clear->value
        ],
        "custom" => [
            "prefix" => OutputFormatEnum::green->value,
            "suffix" => OutputFormatEnum::clear->value
        ]
    ]
);
