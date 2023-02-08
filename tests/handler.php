<?php

use GSpataro\CLI\Handler;
use GSpataro\CLI\OptionsCollection;
use GSpataro\CLI\Input;
use GSpataro\CLI\Output;

require_once __DIR__ . "/bootstrap.php";

$options = new OptionsCollection();
$request = new Input($argv);
$response = new Output();

$options->feed([
    "set" => [
        "callback" => function (Input $request, Output $response, array $args) {
            $response->print("key: {$args['key']}");
            $response->print("value: {$args['value']}");
            $response->print("#Configuration updated successfully!#");
        },
        "args" => [
            "key" => [
                "required" => true,
                "manpage" => "The key of the configuration to update"
            ],
            "value" => [
                "required" => true,
                "manpage" => "The new value"
            ]
        ],
        "manpage" => "Update a configuration value"
    ],
    "build" => [
        "callback" => function (Input $request, Output $response, array $args) {
            $response->print("Under construction...");
        },
        "args" => [
            "mode" => [
                "required" => false,
                "manpage" => "Pass mode argument to builder, e.g. reset"
            ]
        ],
        "manpage" => "Execute build"
    ]
]);

$handler = new Handler($options, $request, $response);
$handler->deploy();
