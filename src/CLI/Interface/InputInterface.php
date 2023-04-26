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
     * Get other arguments
     *
     * @return array
     */

    public function getArgs(): array;

    /**
     * Get standard input
     *
     * @return mixed
     */

    public function getStandardInput(): mixed;
}
