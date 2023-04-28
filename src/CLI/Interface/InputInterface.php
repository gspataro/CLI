<?php

namespace GSpataro\CLI\Interface;

interface InputInterface
{
    /**
     * Get script name
     *
     * @return string
     */

    public function getScriptName(): string;

    /**
     * Get command name
     *
     * @return string
     */

    public function getCommandName(): string;

    /**
     * Get raw arguments
     *
     * @return array
     */

    public function getRawArgs(): array;

    /**
     * Get arguments
     *
     * @return array
     */

    public function getArgs(): array;

    /**
     * Get a single argument
     *
     * @param string $name
     * @return mixed
     */

    public function getArg(string $name): mixed;

    /**
     * Get standard input
     *
     * @return mixed
     */

    public function getStandardInput(): mixed;
}
