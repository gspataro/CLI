<?php

use GSpataro\CLI\Output;
use Tests\Utilities\FakeStream;

uses(\Tests\TestCase::class)->group('io');

beforeEach(function () {
    $this->outputStream = fopen('gstest://output', 'w+');
    $this->output = new Output($this->outputStream);
});

it('returns a string with a new line', function () {
    $this->output->print(
        text: 'lorem ipsum',
        finalNewLine: true,
        autoclear: false,
        raw: false
    );

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)
        ->toBeString()
        ->toBe("lorem ipsum" . PHP_EOL);
});

it('returns a string without a new line', function () {
    $this->output->print(
        text: 'lorem ipsum',
        finalNewLine: false,
        autoclear: false,
        raw: false
    );

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)
        ->toBeString()
        ->toBe("lorem ipsum");
});

it('returns a string without formatting', function () {
    $text = '{bold}lorem ipsum';
    $this->output->print(
        text: $text,
        finalNewLine: true,
        autoclear: true,
        raw: true
    );

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)
        ->toBeString()
        ->toBe($text . '{clear}{nl}');
});

it('returns a string with autoclear', function () {
    $this->output->print(
        text: 'lorem ipsum',
        finalNewLine: false,
        autoclear: true,
        raw: false
    );

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)
        ->toBeString()
        ->toBe("lorem ipsum\e[0m");
});

it('returns a string without autoclear', function () {
    $this->output->print(
        text: 'lorem ipsum',
        finalNewLine: false,
        autoclear: false,
        raw: false
    );

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)
        ->toBeString()
        ->toBe('lorem ipsum');
});

it('returns a formatted string', function () {
    $this->output->print(
        text: "{bold}lorem ipsum",
        finalNewLine: false,
        autoclear: true,
        raw: false
    );

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)
        ->toBeString()
        ->toBe("\e[1mlorem ipsum\e[0m");
});
