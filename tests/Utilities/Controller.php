<?php

namespace Tests\Utilities;

use GSpataro\CLI\Command;

final class Controller extends Command
{
    protected string $name = 'set';

    public function options(): array
    {
        return [
            'key' => [
                'type' => 'required'
            ],
            'value' => [
                'type' => 'required'
            ]
        ];
    }

    public function main(): void
    {
        $this->output->print('Key: ' . $this->argument('key'));
        $this->output->print('Value: ' . $this->argument('value'));
    }
}
