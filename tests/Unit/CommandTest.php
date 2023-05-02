<?php

use GSpataro\CLI\Command;
use GSpataro\CLI\Exception\InvalidCommandCallbackException;
use GSpataro\CLI\Exception\InvalidCommandOptionsDefinitionException;

uses()->group('core');

it('sets and retrieves the command name', function () {
    $command = new Command('test');
    $result = $command->getName();

    expect($result)->toBe('test');
});

it('sets and retrieves the command description', function () {
    $command = new Command('test', 'lorem ipsum dolor');
    $result = $command->getDescription();

    expect($result)->toBe('lorem ipsum dolor');
});

it('sets and retrieves the command callback', function () {
    $command = new Command('test');
    $result = $command->setCallback(fn() => 'lorem ipsum')->getCallback();

    expect($result)->toBeCallable();
});

it('throws an exception with an invalid command callback class (not extending base command)', function () {
    $command = new Command('test');
    $command->setCallback(\Tests\Utilities\InvalidController::class);
})->throws(
    InvalidCommandCallbackException::class,
    "Invalid callback for command 'test'. A command class must extend the GSpataro\\CLI\\Helper\\BaseCommand class."
);

it('throws an exception if an option is not an array', function () {
    $command = new Command('test');
    $command->setOptions([
        'option' => 'invalid'
    ]);
})->throws(
    InvalidCommandOptionsDefinitionException::class,
    "Invalid option 'option' definition for command 'test'. " .
    "An option must be an array containing the informations needed."
);

it('throws an exception if an option has a too long shortname', function () {
    $command = new Command('test');
    $command->setOptions([
        'invalid' => [
            'shortname' => 'toolong'
        ]
    ]);
})->throws(
    InvalidCommandOptionsDefinitionException::class,
    "Invalid option 'invalid' definition for command 'test'. " .
    "An option shortname must be only one character long."
);

it('sets and retrieves the command options', function () {
    $command = new Command('test');
    $result = $command->setOptions([
        'noshort' => [
            'type' => 'required',
            'description' => 'no short name'
        ],
        'nolong' => [
            'shortname' => 'n',
            'type' => 'optional',
            'description' => 'no long name'
        ],
        'bothshortandlong' => [
            'shortname' => 'a',
            'type' => 'toggle',
            'description' => 'both short and long names'
        ],
        'notype' => [
            'description' => 'lorem ipsum'
        ],
        'invalidtype' => [
            'type' => 'boolean'
        ],
        'nodescription' => [
            'type' => 'required'
        ]
    ])->getOptions();

    expect($result)->toBe([
        'noshort' => [
            'type' => 'required',
            'description' => 'no short name',
            'shortname' => null
        ],
        'nolong' => [
            'shortname' => 'n',
            'type' => 'optional',
            'description' => 'no long name'
        ],
        'bothshortandlong' => [
            'shortname' => 'a',
            'type' => 'toggle',
            'description' => 'both short and long names'
        ],
        'notype' => [
            'description' => 'lorem ipsum',
            'shortname' => null,
            'type' => 'optional'
        ],
        'invalidtype' => [
            'type' => 'optional',
            'shortname' => null,
            'description' => null
        ],
        'nodescription' => [
            'type' => 'required',
            'shortname' => null,
            'description' => null
        ]
    ]);
});
