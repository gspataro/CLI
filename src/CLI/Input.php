<?php

namespace GSpataro\CLI;

final class Input
{
    /**
     * Store script name
     *
     * @var string
     */

    private readonly string $scriptName;

    /**
     * Store command name
     *
     * @var string
     */

    private readonly string $commandName;

    /**
     * Store other arguments
     *
     * @var array
     */

    private readonly array $args;

    /**
     * Initialize Input object
     *
     * @param array $argv
     */

    public function __construct(array $argv)
    {
        $this->scriptName = $argv[0];
        $this->commandName = $argv[1] ?? "help";
        $this->args = array_slice($argv, 2);
    }

    /**
     * Get script name
     *
     * @return string
     */

    public function getScriptName(): string
    {
        return $this->scriptName;
    }

    /**
     * Get command name
     *
     * @return string
     */

    public function getCommandName(): string
    {
        return $this->commandName;
    }

    /**
     * Get other arguments
     *
     * @return array
     */

    public function getArgs(): array
    {
        return $this->args;
    }
}
