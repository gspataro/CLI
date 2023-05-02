<?php

namespace Tests\Utilities;

use GSpataro\CLI\Helper\BaseCommand;

final class Controller extends BaseCommand
{
    public function main(): void
    {
        $this->output->print('Key: ' . $this->argument('key'));
        $this->output->print('Value: ' . $this->argument('value'));
    }
}
