<?php

namespace GSpataro\CLI\Helper;

use GSpataro\CLI\Interface\InputInterface;
use GSpataro\CLI\Interface\OutputInterface;

abstract class BaseCommand
{
    private readonly array $args;
    protected readonly InputInterface $input;
    protected readonly OutputInterface $output;

    /**
     * Set Input and Output command dependencies
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */

    public function setIO(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * Set command arguments
     *
     * @param array $args
     * @return void
     */

    public function setArgs(array $args): void
    {
        $this->args = $args;
    }

    /**
     * Get a command argument
     *
     * @param string $arg
     * @return mixed
     */

    protected function argument(string $arg): mixed
    {
        return $this->args[$arg] ?? null;
    }

    /**
     * Command main
     *
     * @return void
     */

    abstract public function main(): void;
}
