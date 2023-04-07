<?php

use GSpataro\CLI\Output;
use GSpataro\CLI\Helper\Table;
use GSpataro\CLI\Enum\ColorsEnum;
use GSpataro\CLI\Enum\StylesEnum;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Output();
$structure = [
    ["heading" => ["Name", "Surname", "City"]],
    ["row" => ["Wolfgang Amadeus", "Mozart", "Vienna"]],
    ["row" => ["Ludwig", "van Beethoven", "Bonn"]],
    ["special" => ["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]],
    ["row" => ["Vincenzo", "Bellini", "Catania"]]
];

// Test basic functionality

$basicTable = new Table($output);
$basicTable->setRows($structure);
$basicTable->addRow(['lorem', 'ipsum', 'dolor']);
$basicTable->render();

// Test custom padding

$customPaddingTable = new Table($output);
$customPaddingTable->setPadding(10);
$customPaddingTable->setPaddingCharacter('.');
$customPaddingTable->setRows($structure);
$customPaddingTable->render();

// Test custom styles

$customStylesTable = new Table($output);
$customStylesTable->setStyle("row", StylesEnum::bold->value . ColorsEnum::fg_green->value);
$customStylesTable->setStyle("special", StylesEnum::strike->value);
$customStylesTable->setRows($structure);
$customStylesTable->render();
