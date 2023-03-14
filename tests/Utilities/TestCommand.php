<?php

namespace GSpataro\Test\Utilities;

use GSpataro\CLI\Command;

final class TestCommand extends Command
{
    public function main(string $foo): void
    {
        $this->output->print("This is the content of foo: {$foo}");
    }
}
