<?php

use GSpataro\CLI\Output;

uses()->group('io');

beforeEach(function () {
    ob_start();
    $this->outputBufferingLevel = ob_get_level();

    $this->output = new Output();
});

afterEach(function () {
    if ($this->outputBufferingLevel !== ob_get_level()) {
        while (ob_get_level() >= $this->outputBufferingLevel) {
            ob_end_clean();
        }
    }
});

it('returns a string with a new line', function () {
    $this->output->print(
        text: 'lorem ipsum',
        finalNewLine: true,
        autoclear: false,
        raw: false
    );

    $result = ob_get_clean();

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

    $result = ob_get_clean();

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

    $result = ob_get_clean();

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

    $result = ob_get_clean();

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

    $result = ob_get_clean();

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

    $result = ob_get_clean();

    expect($result)
        ->toBeString()
        ->toBe("\e[1mlorem ipsum\e[0m");
});
