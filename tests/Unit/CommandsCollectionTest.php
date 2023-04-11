<?php

use GSpataro\CLI\CommandsCollection;
use GSpataro\CLI\Exception\CommandFoundException;
use GSpataro\CLI\Exception\CommandNotFoundException;
use GSpataro\CLI\Exception\InvalidCommandCallbackException;
use GSpataro\CLI\Exception\IncompleteCommandDefinitionException;

uses(\Tests\TestCase::class);

beforeEach(function () {
    $this->commandsCollection = new CommandsCollection();
});

it('verifies that a command exists', function () {
    expect($this->commandsCollection->has('test'))->toBeFalse();

    $this->setPrivateProperty($this->commandsCollection, 'commands', ['test' => []]);

    expect($this->commandsCollection->has('test'))->toBeTrue();
});

it('adds a command', function () {
    $callback = fn() => 'bar';
    $options = [];

    $this->commandsCollection->add('foo', $callback, $options);

    $commands = $this->readPrivateProperty($this->commandsCollection, 'commands');
    expect($commands['foo'])->toEqual([
        'callback' => $callback,
        'options' => $options,
        'description' => null
    ]);
});

it('throws an exception with an invalid command callback class', function () {
    $this->commandsCollection->add('test', [new \Tests\Utilities\InvalidController(), 'method'], []);
})->throws(
    InvalidCommandCallbackException::class,
    "Invalid callback for command 'test'. The first element of the array must be a class."
);

it('throws an exception with an invalid command callback method', function () {
    $this->commandsCollection->add('test', [new \Tests\Utilities\Controller(), 'nonexisting'], []);
})->throws(
    InvalidCommandCallbackException::class,
    "Invalid callback for command 'test'. " .
    "The second element of the array must be a method of 'Tests\Utilities\Controller'."
);

it('throws an exception if a command already exists', function () {
    $this->commandsCollection->add('test', fn() => 'first', []);
    $this->commandsCollection->add('test', fn() => 'second', []);
})->throws(
    CommandFoundException::class,
    "Command 'test' already exists in the collection."
);

it('throws an exception for incomplete command definitions', function () {
    $this->commandsCollection->feed([
        'test' => []
    ]);
})->throws(
    IncompleteCommandDefinitionException::class,
    "Incomplete command 'test' definition. A command must include at least a valid callback."
);

it('returns a command', function () {
    $this->setPrivateProperty($this->commandsCollection, 'commands', [
        'test' => []
    ]);

    $result = $this->commandsCollection->get('test');

    expect($result)->toBe([]);
});

it('throws an exception when a command doesn\'t exist', function () {
    $this->commandsCollection->get('test');
})->throws(
    CommandNotFoundException::class,
    "Command 'test' not found in the collection."
);
