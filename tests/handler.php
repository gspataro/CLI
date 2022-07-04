<?php

use GSpataro\CLI\Handler;
use GSpataro\CLI\OptionsCollection;
use GSpataro\CLI\Request;
use GSpataro\CLI\Response;

require_once __DIR__ . "/bootstrap.php";

$options = new OptionsCollection();
$request = new Request($argv);
$response = new Response();

$options->feed([
    "set" => [
        "callback" => function (Request $request, Response $response, array $args) {
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
        "callback" => function (Request $request, Response $response, array $args) {
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
