<?php

use GSpataro\CLI\Input;
use GSpataro\CLI\Output;
use GSpataro\CLI\Helper\Prompt;
use Tests\Utilities\FakeStream;

uses()->group('helpers');

beforeAll(function () {
    stream_wrapper_register('gstest', FakeStream::class);
});

beforeEach(function () {
    $this->stdin = fopen('gstest://stdin', 'r+');
    $this->input = new Input(standardInput: $this->stdin);
    $this->output = new Output();
    $this->prompt = new Prompt($this->input, $this->output);
});

test('single prompt accepts a user input', function () {
    fwrite($this->stdin, 'foo');
    rewind($this->stdin);

    $result = $this->prompt->single('');

    expect($result)->toBe('foo');
});

test('multiple prompt accepts multiple user inputs', function () {
    fwrite($this->stdin, 'foo|bar');
    rewind($this->stdin);

    $result = $this->prompt->multiple('', '|');

    expect($result)->toBe([
        'foo',
        'bar'
    ]);
});

test('conceal prompt accepts a user input', function () {
    fwrite($this->stdin, 'hidden');
    rewind($this->stdin);

    $result = $this->prompt->conceal('');

    expect($result)->toBe('hidden');
});
