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

it('creates a single prompt', function () {
    fwrite($this->stdin, 'foo');
    rewind($this->stdin);

    $result = $this->prompt->single('Enter something:');

    expect($result)->toBe('foo');
});

it('creates a multiple prompt', function () {
    fwrite($this->stdin, 'foo|bar');
    rewind($this->stdin);

    $result = $this->prompt->multiple('List your interests:', '|');

    expect($result)->toBe([
        'foo',
        'bar'
    ]);
});

it('creates a conceal prompt', function () {
    fwrite($this->stdin, 'hidden');
    rewind($this->stdin);

    $result = $this->prompt->conceal('Enter your password:');

    expect($result)->toBe('hidden');
});

it('creates a confirmation prompt that accepts yes', function () {
    fwrite($this->stdin, 'yes');
    rewind($this->stdin);

    $result = $this->prompt->confirm('Confirm action?');

    expect($result)->toBeTrue();
});

it('creates a confirmation prompt that accepts y', function () {
    fwrite($this->stdin, 'y');
    rewind($this->stdin);

    $result = $this->prompt->confirm('Confirm action?');

    expect($result)->toBeTrue();
});

it('creates a confirmation prompt that accepts no', function () {
    fwrite($this->stdin, 'no');
    rewind($this->stdin);

    $result = $this->prompt->confirm('Confirm action?');

    expect($result)->toBeFalse();
});

it('creates a confirmation prompt that accepts n', function () {
    fwrite($this->stdin, 'n');
    rewind($this->stdin);

    $result = $this->prompt->confirm('Confirm action?');

    expect($result)->toBeFalse();
});

it('creates a choice prompt', function () {
    fwrite($this->stdin, 1);
    rewind($this->stdin);

    $choices = [
        'Mozart',
        'Beethoven',
        'Rachmaninoff'
    ];

    $result = $this->prompt->choice('Choose your favourite composer:', $choices);

    expect($choices[$result])->toBe('Beethoven');
});
