<?php

use GSpataro\CLI\Handler;
use GSpataro\CLI\CommandsCollection;
use GSpataro\CLI\Helper\Prompt;
use GSpataro\CLI\Input;
use GSpataro\CLI\Output;
use GSpataro\Test\Utilities\TestCommand;

require_once __DIR__ . "/bootstrap.php";

$commands = new CommandsCollection();

$commands->feed([
    "set" => [
        "callback" => function (Input $input, Output $output, string $key, string $value, ?string $type, ?bool $overwrite) {
            $output->print("key: {$key}");
            $output->print("value: {$value}");
            $output->print("type: {$type}");
            $output->print("overwrite: " . (is_null($overwrite) ? "false" : "true"));
            $output->print("{bg_green}Configuration updated successfully!");
        },
        "options" => [
            "key" => [
                "type" => "required",
                "short" => "k",
                "description" => "The key of the configuration to update"
            ],
            "value" => [
                "type" => "required",
                "short" => "v",
                "description" => "The new value"
            ],
            "type" => [],
            "overwrite" => [
                "type" => "novalue"
            ]
        ],
        "description" => "Update a configuration value"
    ],
    "login" => [
        "callback" => fn(Input $input, Output $output, string $username, string $password) => $output->print("{fg_green}Logged in as {$username}:{$password}!"),
        "options" => [
            "username" => [
                "type" => "required",
                "short" => "u",
                "description" => "The username"
            ],
            "password" => [
                "type" => "required",
                "short" => "p",
                "description" => "The password"
            ]
        ],
        "description" => "Execute build"
    ],
    "register" => [
        "callback" => function (Input $input, Output $output) {
            $output->print("Welcome to the registration prompt!{nl}");
            $prompt = new Prompt($output);

            $username = $prompt->single('Username:');
            $password = $prompt->conceal('{fg_red}Password:');
            $hobbies = $prompt->multiple('Hobbies (separated by |):', '|');

            $output->print(
                "{fg_green}Welcome {$username}, your password is {$password} and your hobbies are: "
                . implode(", ", $hobbies)
                . "!"
            );
        },
        "options" => [],
        "description" => "Register"
    ],
    "setfoo" => [
        "callback" => [new TestCommand(), 'main'],
        'options' => [
            'foo' => [
                'type' => 'required',
                'short' => 'f',
                'description' => 'The value of foo'
            ]
        ],
        'description' => 'Set the content of foo'
    ]
]);

$handler = new Handler($commands);
$handler->deploy();
