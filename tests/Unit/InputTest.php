<?php

use GSpataro\CLI\Input;

uses()->group('io');

beforeEach(function () {
    $this->input = new Input([
        'index.php',
        'test',
        '-s',
        'shortopt',
        'unrecognized',
        '--long=long option',
        '--multiple=first value',
        '--multiple=second value',
        '-b',
        '--boolean'
    ]);
});

it('returns the script name', function () {
    $result = $this->input->getScriptName();

    expect($result)->toBe('index.php');
});

it('returns the command name', function () {
    $result = $this->input->getCommandName();

    expect($result)->toBe('test');
});

it('returns the raw arguments', function () {
    $result = $this->input->getRawArgs();

    expect($result)->toBe([
        '-s',
        'shortopt',
        'unrecognized',
        '--long=long option',
        '--multiple=first value',
        '--multiple=second value',
        '-b',
        '--boolean'
    ]);
});

it('returns the parsed arguments', function () {
    $result = $this->input->getArgs();

    expect($result)->toBe([
        's' => 'shortopt',
        'long' => 'long option',
        'multiple' => [
            'first value',
            'second value'
        ],
        'b' => false,
        'boolean' => false
    ]);
});
