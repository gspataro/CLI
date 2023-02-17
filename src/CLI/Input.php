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
     * @param array|null $argv
     */

    public function __construct(
        private ?array $argv = null
    ) {
        $this->argv = $this->argv ?? $_SERVER['argv'];
        $this->scriptName = $this->argv[0];
        $this->commandName = $this->argv[1] ?? "help";
        $this->args = array_slice($this->argv, 2);
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
