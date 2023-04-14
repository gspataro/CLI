<?php

use GSpataro\CLI\Output;

uses()->group('io');

beforeEach(function () {
    $this->output = new Output();
});

it('returns a string with a new line', function () {
    $result = $this->output->prepare(
        text: 'lorem ipsum',
        finalNewLine: true,
        autoclear: false,
        raw: false
    );

    expect($result)
        ->toBeString()
        ->toBe("lorem ipsum" . PHP_EOL);
});

it('returns a string without a new line', function () {
    $result = $this->output->prepare(
        text: 'lorem ipsum',
        finalNewLine: false,
        autoclear: false,
        raw: false
    );

    expect($result)
        ->toBeString()
        ->toBe("lorem ipsum");
});

it('returns a string without formatting', function () {
    $text = '{bold}lorem ipsum';
    $result = $this->output->prepare(
        text: $text,
        finalNewLine: true,
        autoclear: true,
        raw: true
    );

    expect($result)
        ->toBeString()
        ->toBe($text . '{clear}{nl}');
});

it('returns a string with autoclear', function () {
    $result = $this->output->prepare(
        text: 'lorem ipsum',
        finalNewLine: false,
        autoclear: true,
        raw: false
    );

    expect($result)
        ->toBeString()
        ->toBe("lorem ipsum\e[0m");
});

it('returns a string without autoclear', function () {
    $result = $this->output->prepare(
        text: 'lorem ipsum',
        finalNewLine: false,
        autoclear: false,
        raw: false
    );

    expect($result)
        ->toBeString()
        ->toBe('lorem ipsum');
});

it('returns a formatted string', function () {
    $result = $this->output->prepare(
        text: "{bold}lorem ipsum",
        finalNewLine: false,
        autoclear: true,
        raw: false
    );

    expect($result)
        ->toBeString()
        ->toBe("\e[1mlorem ipsum\e[0m");
});
