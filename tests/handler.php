<?php

use GSpataro\CLI\Handler;
use GSpataro\CLI\CommandsCollection;
use GSpataro\CLI\Input;
use GSpataro\CLI\Output;

require_once __DIR__ . "/bootstrap.php";

$commands = new CommandsCollection();
$input = new Input($argv);
$output = new Output();

$commands->feed([
    "set" => [
        "callback" => function (Input $input, Output $output, array $args) {
            $output->print("key: {$args['key']}");
            $output->print("value: {$args['value']}");
            $output->print("#Configuration updated successfully!#");
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
        "callback" => function (Input $input, Output $output, array $args) {
            $output->print("Under construction...");
        },
        "args" => [
            "mode" => [
                "required" => false,
                "manpage" => "Pass mode argument to builder, e.g. reset"
            ]
        ],
        "manpage" => "Execute build"
    ],
    "test" => [
        "callback" => fn(Input $input, Output $output, $args) => $output->print("Logged in as {$args['username']}:{$args['password']}!"),
        "args" => [
            "username",
            "password"
        ],
        "manpage" => "Execute build"
    ],
]);

$handler = new Handler($commands, $input, $output);
$handler->deploy();
