<?php

use GSpataro\CLI\Input;
use GSpataro\CLI\Output;
use GSpataro\CLI\CommandsCollection;
use GSpataro\CLI\Helper\Manpage;

uses(\Tests\TestCase::class)->group('helpers');

beforeEach(function () {
    $this->input = new Input([
        'script.php',
        'help'
    ]);
    $this->output = new Output();
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
    $table = $this->readPrivateProperty($this->manpage, 'table');
    $result = $table->build();

    $expected = '{bold}login             {clear}{bold}Sign in{clear}{nl}';
    $expected .= '{italic}-e, -email        {clear}{italic}Your email address (required){clear}{nl}';
    $expected .= '{italic}-p, -password     {clear}{italic}Your password (required){clear}{nl}';
    $expected .= '{nl}';
    $expected .= '{bold}register          {clear}{bold}Sign up{clear}{nl}';
    $expected .= '{italic}-e, -email        {clear}{italic}Your email address (required){clear}{nl}';
    $expected .= '{italic}-p, -password     {clear}{italic}Choose a password (required){clear}{nl}';
    $expected .= '{italic}--name            {clear}{italic}Your full name{clear}{nl}';
    $expected .= '{nl}';
    $expected .= '{bold}help              {clear}{bold}List available commands{clear}{nl}';

    expect($result)->toBe($expected);
});

it('can be translated', function () {
    $this->manpage->setLocale('required', 'richiesto');
    $this->manpage->setLocale('list_available_commands', 'Mostra comandi disponibili');
    $this->manpage->render();
    $table = $this->readPrivateProperty($this->manpage, 'table');
    $result = $table->build();

    $expected = '{bold}login             {clear}{bold}Sign in{clear}{nl}';
    $expected .= '{italic}-e, -email        {clear}{italic}Your email address (richiesto){clear}{nl}';
    $expected .= '{italic}-p, -password     {clear}{italic}Your password (richiesto){clear}{nl}';
    $expected .= '{nl}';
    $expected .= '{bold}register          {clear}{bold}Sign up{clear}{nl}';
    $expected .= '{italic}-e, -email        {clear}{italic}Your email address (richiesto){clear}{nl}';
    $expected .= '{italic}-p, -password     {clear}{italic}Choose a password (richiesto){clear}{nl}';
    $expected .= '{italic}--name            {clear}{italic}Your full name{clear}{nl}';
    $expected .= '{nl}';
    $expected .= '{bold}help              {clear}{bold}Mostra comandi disponibili{clear}{nl}';

    expect($result)->toBe($expected);
});
