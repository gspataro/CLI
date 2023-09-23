<?php

use GSpataro\CLI\Cursor;
use GSpataro\CLI\Output;

uses(\Tests\TestCase::class)->group('core');

beforeEach(function () {
    $this->outputStream = fopen('gstest://output', 'w+');
    $this->output = new Output($this->outputStream);
    $this->cursor = new Cursor($this->output);
});

it('moves the cursor up x lines', function (int $lines) {
    $this->cursor->moveUp($lines);

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)->toBe("\033[{$lines}A");
})->with([1, 6, 12]);

it('moves the cursor right x columns', function (int $columns) {
    $this->cursor->moveRight($columns);

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)->toBe("\033[{$columns}C");
})->with([1, 6, 12]);

it('moves the cursor bottom x lines', function (int $lines) {
    $this->cursor->moveDown($lines);

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)->toBe("\033[{$lines}B");
})->with([1, 6, 12]);

it('moves the cursor left x columns', function (int $columns) {
    $this->cursor->moveLeft($columns);

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)->toBe("\033[{$columns}D");
})->with([1, 6, 12]);

it('moves the cursor to a specific column', function (int $column) {
    $this->cursor->moveToColumn($column);

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)->toBe("\033[{$column}G");
})->with([5, 10, 20]);

it('clears the current line', function () {
    $this->cursor->clearLine();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)->toBe("\033[2K");
});

it('clears the entire screen', function () {
    $this->cursor->clearScreen();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    expect($result)->toBe("\033[2J");
});
