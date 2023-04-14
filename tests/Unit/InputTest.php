<?php

use GSpataro\CLI\Input;

uses()->group('io');

beforeEach(function () {
    $this->input = new Input([
        'index.php',
        'test',
        'arg1',
        'arg2'
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

it('returns the arguments', function () {
    $result = $this->input->getArgs();

    expect($result)->toBe([
        'arg1',
        'arg2'
    ]);
});
