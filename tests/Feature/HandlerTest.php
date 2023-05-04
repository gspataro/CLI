<?php

use GSpataro\CLI\Input;
use GSpataro\CLI\Handler;
use GSpataro\CLI\CommandsCollection;
use GSpataro\CLI\Interface\InputInterface;
use GSpataro\CLI\Interface\OutputInterface;
use Tests\Utilities\Controller;

uses(\Tests\TestCase::class)->group('core');

beforeEach(function () {
    $this->collection = new CommandsCollection();
});

it('recognizes long options', function () {
    $input = new Input(['script.php', 'set', '--key=foo', '--value=bar']);
    $result = [];

    $this->collection->create('set')
        ->setCallback(function ($input, $output, $key, $value) use (&$result) {
            $result['key'] = $key;
            $result['value'] = $value;
        })
        ->setOptions([
            'key' => ['type' => 'required'],
            'value' => ['type' => 'required']
        ]);

    $handler = new Handler($this->collection, $input);
    $handler->deploy();

    expect($result)->toBe([
        'key' => 'foo',
        'value' => 'bar'
    ]);
});

it('recognizes short options', function () {
    $input = new Input(['script.php', 'set', '-k', 'foo', '-v', 'bar']);
    $result = [];

    $this->collection->create('set')
        ->setCallback(function ($input, $output, $key, $value) use (&$result) {
            $result['key'] = $key;
            $result['value'] = $value;
        })
        ->setOptions([
            'key' => [
                'shortname' => 'k',
                'type' => 'required'
            ],
            'value' => [
                'shortname' => 'v',
                'type' => 'required'
            ]
        ]);

    $handler = new Handler($this->collection, $input);
    $handler->deploy();

    expect($result)->toBe([
        'key' => 'foo',
        'value' => 'bar'
    ]);
});

it('recognizes required options', function () {
    $input = new Input(['script.php', 'set', '--key=foo']);
    $result = [];

    $this->collection->create('set')
        ->setCallback(function ($input, $output, $key, $value) use (&$result) {
            $result['key'] = $key;
            $result['value'] = $value;
        })
        ->setOptions([
            'key' => [
                'type' => 'required'
            ],
            'value' => [
                'type' => 'required'
            ]
        ]);

    $handler = new Handler($this->collection, $input);
    $handler->deploy();

    expect($result)->toBe([]);
});

it('recognizes optional options', function () {
    $input = new Input(['script.php', 'set', '--key=foo', '--value=bar', '--type=config']);
    $result = [];

    $this->collection->create('set')
        ->setCallback(function ($input, $output, $key, $value, $type, $description) use (&$result) {
            $result['key'] = $key;
            $result['value'] = $value;
            $result['type'] = $type;
            $result['description'] = $description ?? 'not set';
        })
        ->setOptions([
            'key' => [
                'type' => 'required'
            ],
            'value' => [
                'type' => 'required'
            ],
            'type' => [
                'type' => 'optional'
            ],
            'description' => [
                'type' => 'optional'
            ]
        ]);

    $handler = new Handler($this->collection, $input);
    $handler->deploy();

    expect($result)->toBe([
        'key' => 'foo',
        'value' => 'bar',
        'type' => 'config',
        'description' => 'not set'
    ]);
});

it('recognizes novalue options', function () {
    $input = new Input(['script.php', 'set', '--key=foo', '--overwrite', '--value=bar']);
    $result = [];

    $this->collection->create('set')
        ->setCallback(function ($input, $output, $key, $value, $overwrite) use (&$result) {
            $result['key'] = $key;
            $result['value'] = $value;
            $result['overwrite'] = $overwrite === false ? 'yes' : 'no';
        })
        ->setOptions([
            'key' => [
                'type' => 'required'
            ],
            'value' => [
                'type' => 'required'
            ],
            'overwrite' => [
                'type' => 'novalue'
            ]
            ]);

    $handler = new Handler($this->collection, $input);
    $handler->deploy();

    expect($result)->toBe([
        'key' => 'foo',
        'value' => 'bar',
        'overwrite' => 'yes'
    ]);
});

it('calls a callable command', function () {
    $input = new Input(['script.php', 'set', '--key=foo', '--value=bar']);
    $result = [];

    $this->collection->create('set')
        ->setCallback(function ($input, $output, $key, $value) use (&$result) {
            $result[] = $output->prepare('Key: ' . $key);
            $result[] = $output->prepare('Value: ' . $value);
        })
        ->setOptions([
            'key' => [
                'type' => 'required'
            ],
            'value' => [
                'type' => 'required'
            ]
        ]);

    $handler = new Handler($this->collection, $input);
    $handler->deploy();

    expect($result)->toEqual([
        "Key: foo\e[0m" . PHP_EOL,
        "Value: bar\e[0m" . PHP_EOL
    ]);
});

it('calls an object command', function () {
    $this->startOutputBuffer();

    $input = new Input(['script.php', 'set', '--key=foo', '--value=bar']);
    $command = new Controller();

    $this->collection->register($command);

    $handler = new Handler($this->collection, $input);
    $handler->deploy();

    $result = $this->getOutput();
    $expected = "Key: foo\e[0m" . PHP_EOL;
    $expected .= "Value: bar\e[0m" . PHP_EOL;

    expect($result)->toBe($expected);
});
