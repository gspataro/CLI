<?php

use GSpataro\CLI\Command;
use GSpataro\CLI\CommandsCollection;
use GSpataro\CLI\Exception\CommandFoundException;
use GSpataro\CLI\Exception\CommandNotFoundException;

uses(\Tests\TestCase::class)->group('core');

beforeEach(function () {
    $this->commandsCollection = new CommandsCollection();
});

it('verifies that a command exists', function () {
    expect($this->commandsCollection->has('test'))->toBeFalse();

    $this->setPrivateProperty($this->commandsCollection, 'commands', ['test' => new Command('test')]);

    expect($this->commandsCollection->has('test'))->toBeTrue();
});

it('registers a command', function () {
    $command = new Command('test');

    $this->commandsCollection->register($command);

    $commands = $this->readPrivateProperty($this->commandsCollection, 'commands');

    expect($commands['test'])->toBe($command);
});

it('adds a command', function () {
    $callback = fn() => 'bar';
    $options = [
        [
            'longname' => 'foo'
        ]
    ];

    $this->commandsCollection->add('foo', $callback, $options);

    $commands = $this->readPrivateProperty($this->commandsCollection, 'commands');
    $expected = new Command('foo');
    $expected->setCallback($callback);
    $expected->setOptions($options);

    expect($commands['foo'])->toEqual($expected);
});

it('throws an exception if a command already exists', function () {
    $this->commandsCollection->add('test', fn() => 'first', []);
    $this->commandsCollection->add('test', fn() => 'second', []);
})->throws(
    CommandFoundException::class,
    "Command 'test' already exists in the collection."
);

it('throws an exception if a command was already registered', function () {
    $command = new Command('test');
    $this->commandsCollection->register($command);
    $this->commandsCollection->register($command);
})->throws(
    CommandFoundException::class,
    "Command 'test' already exists in the collection."
);

it('returns a command', function () {
    $firstCommand = new Command('first');
    $secondCommand = new Command('second');

    $this->setPrivateProperty($this->commandsCollection, 'commands', [
        'first' => $firstCommand,
        'second' => $secondCommand
    ]);

    $result = $this->commandsCollection->get('first');

    expect($result)->toBe($firstCommand);
});

it('throws an exception when a command doesn\'t exist', function () {
    $this->commandsCollection->get('test');
})->throws(
    CommandNotFoundException::class,
    "Command 'test' not found in the collection."
);
