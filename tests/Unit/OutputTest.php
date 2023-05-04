<?php

use GSpataro\CLI\Output;

uses(\Tests\TestCase::class)->group('io');

beforeEach(function () {
    $this->output = new Output();
})->startOutputBuffer();

afterEach()->endOutputBuffer();

it('returns a string with a new line', function () {
    $this->output->print(
        text: 'lorem ipsum',
        finalNewLine: true,
        autoclear: false,
        raw: false
    );

    $result = $this->getOutput();

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

    $result = $this->getOutput();

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

    $result = $this->getOutput();

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

    $result = $this->getOutput();

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

    $result = $this->getOutput();

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

    $result = $this->getOutput();

    expect($result)
        ->toBeString()
        ->toBe("\e[1mlorem ipsum\e[0m");
});
