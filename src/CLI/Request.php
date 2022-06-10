<?php

namespace GSpataro\CLI;

final class Request
{
    /**
     * Store script name
     *
     * @var string
     */

    private readonly string $scriptName;

    /**
     * Store option name
     *
     * @var string
     */

    private readonly string $optionName;

    /**
     * Store other arguments
     *
     * @var array
     */

    private readonly array $args;

    /**
     * Initialize Request object
     *
     * @param array $argv
     */

    public function __construct(array $argv)
    {
        $this->scriptName = $argv[0];
        $this->optionName = $argv[1] ?? "help";
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
     * Get option name
     *
     * @return string
     */

    public function getOptionName(): string
    {
        return $this->optionName;
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
