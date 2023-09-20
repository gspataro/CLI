<?php

use GSpataro\CLI\Input;
use GSpataro\CLI\Output;
use GSpataro\CLI\Command;
use GSpataro\CLI\Exception\InvalidCommandOptionsDefinitionException;
use Tests\Utilities\Controller;
use Tests\Utilities\FakeStream;

uses(\Tests\TestCase::class)->group('core', 'command');

beforeEach(function () {
    $this->outputStream = fopen('gstest://output', 'w+');
});

it('sets and retrieves the command name', function () {
    $command = new Command();
    $result = $command->setName('test')->getName();

    expect($result)->toBe('test');
});

it('sets and retrieves the command description', function () {
    $command = new Command();
    $result = $command->setDescription('lorem ipsum dolor')->getDescription();

    expect($result)->toBe('lorem ipsum dolor');
});

it('sets the command callback', function () {
    $command = new Command();
    $command->execute(fn() => 'lorem ipsum');
    $result = $this->readPrivateProperty($command, 'callback');

    expect($result)->toBeCallable();
});

it('throws an exception if an option is not an array', function () {
    $command = new Command();
    $command->setName('test')->setOptions([
        'option' => 'invalid'
    ]);
})->throws(
    InvalidCommandOptionsDefinitionException::class,
    "Invalid option 'option' definition for command 'test'. " .
    "An option must be an array containing the informations needed."
);

it('throws an exception if an option has a too long shortname', function () {
    $command = new Command();
    $command->setName('test')->setOptions([
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
    $command = new Command();
    $result = $command->setName('test')->setOptions([
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

it('runs a command callback', function () {
    $result = null;
    $command = new Command();
    $command->execute(function ($input, $output, $key, $value) use (&$result) {
        $result = [
            'key' => $key,
            'value' => $value
        ];
    });

    $command->run(new Input(), new Output(), ['key' => 'foo', 'value' => 'bar']);
    expect($result)->toBe([
        'key' => 'foo',
        'value' => 'bar'
    ]);
});

it('runs a command class', function () {
    $command = new Controller();
    $command->run(new Input(), new Output($this->outputStream), ['key' => 'foo', 'value' => 'bar']);

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);
    $expected = "Key: foo\e[0m" . PHP_EOL;
    $expected .= "Value: bar\e[0m" . PHP_EOL;

    expect($result)->toBe($expected);
});
