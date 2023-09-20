<?php

use GSpataro\CLI\Input;
use GSpataro\CLI\Output;
use GSpataro\CLI\Helper\Prompt;
use Tests\Utilities\FakeStream;

uses(\Tests\TestCase::class)->group('helpers');

beforeEach(function () {
    ob_start();
    $this->outputBufferingLevel = ob_get_level();

    $this->stdin = fopen('gstest://stdin', 'r+');
    $this->input = new Input(standardInput: $this->stdin);
    $this->output = new Output();
    $this->prompt = new Prompt($this->input, $this->output);
});

afterEach(function () {
    if ($this->outputBufferingLevel !== ob_get_level()) {
        while (ob_get_level() >= $this->outputBufferingLevel) {
            ob_end_clean();
        }
    }
});

it('creates a single prompt', function () {
    fwrite($this->stdin, 'foo');
    rewind($this->stdin);

    $value = $this->prompt->single('Enter something:');
    $result = ob_get_clean() . $value;

    expect($result)->toBe("Enter something: \e[0mfoo");
});

it('creates a multiple prompt', function () {
    fwrite($this->stdin, 'foo|bar');
    rewind($this->stdin);

    $value = $this->prompt->multiple('List your interests:', '|');
    $result = ob_get_clean() . implode('|', $value);

    expect($result)->toBe("List your interests: \e[0mfoo|bar");
});

it('creates a conceal prompt', function () {
    fwrite($this->stdin, 'hidden');
    rewind($this->stdin);

    $value = $this->prompt->conceal('Enter your password:');
    $result = ob_get_clean() . $value;

    expect($result)->toBe("Enter your password: \e[0m" . PHP_EOL . "hidden");
});

it('creates a confirmation prompt', function (string $input, bool $expected) {
    fwrite($this->stdin, $input);
    rewind($this->stdin);

    $result = $this->prompt->confirm('Confirm action?');
    $message = ob_get_clean();

    expect($message)->toBe("Confirm action? \e[0m");
    expect($result)->toBe($expected);
})->with([
    ['yes', true],
    ['YES', true],
    ['y', true],
    ['Y', true],
    ['no', false],
    ['NO', false],
    ['n', false],
    ['N', false]
]);

it('creates a custom confirmation prompt', function (string $input, bool $expected) {
    fwrite($this->stdin, $input);
    rewind($this->stdin);

    $result = $this->prompt->confirm(
        message: 'Confirm action?',
        acceptedAnswers: [
            'si' => true,
            'no' => false,
            's' => true,
            'n' => false
        ]
    );
    $message = ob_get_clean();

    expect($message)->toBe("Confirm action? \e[0m");
    expect($result)->toBe($expected);
})->with([
    ['si', true],
    ['SI', true],
    ['s', true],
    ['S', true],
    ['no', false],
    ['NO', false],
    ['n', false],
    ['N', false]
]);

it('creates a case sensitive confirmation prompt', function (string $input, bool $expected) {
    fwrite($this->stdin, $input);
    rewind($this->stdin);

    $result = $this->prompt->confirm(
        message: 'Confirm action?',
        acceptedAnswers: [
            'yes' => true,
            'Yes' => false,
            'YeS' => false
        ],
        caseSensitive: true
    );
    $message = ob_get_clean();

    expect($message)->toBe("Confirm action? \e[0m");
    expect($result)->toBe($expected);
})->with([
    ['yes', true],
    ['Yes', false],
    ['YeS', false]
]);

it('creates a prompt that returns false after x attempts', function () {
    ob_end_clean();

    $this->setPrivateProperty($this->prompt, 'confirmAttempts', 3);

    fwrite($this->stdin, '');
    rewind($this->stdin);

    $result = $this->prompt->confirm(
        message: 'Confirm action?',
        attempts: 3
    );

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
    $message = ob_get_clean();

    $expected = "0 - Mozart\e[0m" . PHP_EOL;
    $expected .= "1 - Beethoven\e[0m" . PHP_EOL;
    $expected .= "2 - Rachmaninoff\e[0m" . PHP_EOL;
    $expected .= PHP_EOL . "Choose your favourite composer: \e[0m";

    expect($message)->toBe($expected);
    expect($choices[$result])->toBe('Beethoven');
});
