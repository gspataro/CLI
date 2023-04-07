<?php

use GSpataro\CLI\Output;
use GSpataro\CLI\Helper\Table;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Output();
$table = new Table($output);

$structure = [
    ["heading" => ["Name", "Surname", "City"]],
    ["row" => ["Wolfgang Amadeus", "Mozart", "Vienna"]],
    ["row" => ["Ludwig", "van Beethoven", "Bonn"]],
    ["special" => ["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]],
    ["row" => ["Vincenzo", "Bellini", "Catania"]]
];

$table->setRows($structure);

$table->render();
