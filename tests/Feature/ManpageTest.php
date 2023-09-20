<?php

use GSpataro\CLI\Input;
use GSpataro\CLI\Output;
use GSpataro\CLI\CommandsCollection;
use GSpataro\CLI\Helper\Manpage;

uses(\Tests\TestCase::class)->group('helpers');

beforeEach(function () {
    $this->outputStream = fopen('gstest://output', 'r+');
    $this->input = new Input([
        'script.php',
        'help'
    ]);
    $this->output = new Output($this->outputStream);
    $this->commands = new CommandsCollection();

    $this->commands->create('login')
        ->setDescription('Sign in')
        ->execute(fn($input, $output, $email, $password) => 'login')
        ->setOptions([
            'email' => [
                'type' => 'required',
                'shortname' => 'e',
                'description' => 'Your email address'
            ],
            'password' => [
                'type' => 'required',
                'shortname' => 'p',
                'description' => 'Your password'
            ]
        ]);

    $this->commands->create('register')
        ->setDescription('Sign up')
        ->execute(fn($input, $output, $email, $password, $name) => 'login')
        ->setOptions([
            'email' => [
                'type' => 'required',
                'shortname' => 'e',
                'description' => 'Your email address'
            ],
            'password' => [
                'type' => 'required',
                'shortname' => 'p',
                'description' => 'Choose a password'
            ],
            'name' => [
                'type' => 'optional',
                'description' => 'Your full name'
            ]
        ]);

    $this->manpage = new Manpage($this->commands, $this->input, $this->output);
});

it('prints an help table', function () {
    $this->manpage->render();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    $expected = "Usage: script.php \033[1m\033[4mcommand\033[0m \033[3moption\n\033[0m\n";
    $expected .= "Available commands\033[0m\n";
    $expected .= "\033[1mlogin             \033[0m\033[1mSign in\033[0m\n";
    $expected .= "\033[3m-e, -email        \033[0m\033[3mYour email address (required)\033[0m\n";
    $expected .= "\033[3m-p, -password     \033[0m\033[3mYour password (required)\033[0m\n";
    $expected .= "\n";
    $expected .= "\033[1mregister          \033[0m\033[1mSign up\033[0m\n";
    $expected .= "\033[3m-e, -email        \033[0m\033[3mYour email address (required)\033[0m\n";
    $expected .= "\033[3m-p, -password     \033[0m\033[3mChoose a password (required)\033[0m\n";
    $expected .= "\033[3m--name            \033[0m\033[3mYour full name\033[0m\n";
    $expected .= "\n";
    $expected .= "\033[1mhelp              \033[0m\033[1mList available commands\033[0m\n";
    $expected .= "\033[0m\n";

    expect($result)->toBe($expected);
});

it('can be translated', function () {
    $this->manpage->setLocale('usage', 'Utilizzo');
    $this->manpage->setLocale('command', 'comando');
    $this->manpage->setLocale('option', 'opzione');
    $this->manpage->setLocale('available_commands', 'Comandi disponibili');
    $this->manpage->setLocale('required', 'richiesto');
    $this->manpage->setLocale('list_available_commands', 'Mostra comandi disponibili');
    $this->manpage->render();

    rewind($this->outputStream);
    $result = stream_get_contents($this->outputStream);

    $expected = "Usage: script.php \033[1m\033[4mcomando\033[0m \033[3mopzione\n\033[0m\n";
    $expected .= "Comandi disponibili\033[0m\n";
    $expected .= "\033[1mlogin             \033[0m\033[1mSign in\033[0m\n";
    $expected .= "\033[3m-e, -email        \033[0m\033[3mYour email address (richiesto)\033[0m\n";
    $expected .= "\033[3m-p, -password     \033[0m\033[3mYour password (richiesto)\033[0m\n";
    $expected .= "\n";
    $expected .= "\033[1mregister          \033[0m\033[1mSign up\033[0m\n";
    $expected .= "\033[3m-e, -email        \033[0m\033[3mYour email address (richiesto)\033[0m\n";
    $expected .= "\033[3m-p, -password     \033[0m\033[3mChoose a password (richiesto)\033[0m\n";
    $expected .= "\033[3m--name            \033[0m\033[3mYour full name\033[0m\n";
    $expected .= "\n";
    $expected .= "\033[1mhelp              \033[0m\033[1mMostra comandi disponibili\033[0m\n";
    $expected .= "\033[0m\n";

    expect($result)->toBe($expected);
});
