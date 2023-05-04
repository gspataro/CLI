<?php

namespace GSpataro\CLI;

use GSpataro\CLI\Interface\InputInterface;

final class Input implements InputInterface
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
     * Store raw arguments
     *
     * @var array
     */

    private readonly array $rawArgs;

    /**
     * Store arguments
     *
     * @var array
     */

    private array $args;

    /**
     * Initialize Input object
     *
     * @param array|null $argv
     * @param mixed $standardInput
     */

    public function __construct(
        private ?array $argv = null,
        private readonly mixed $standardInput = STDIN
    ) {
        $this->argv = $this->argv ?? $_SERVER['argv'];
        $this->scriptName = $this->argv[0];
        $this->commandName = $this->argv[1] ?? 'help';
        $this->rawArgs = array_slice($this->argv, 2);

        if (!is_resource($standardInput)) {
            throw new Exception\InvalidResourceException("Invalid resource provided as standard input.");
        }
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
     * Get raw arguments
     *
     * @return array
     */

    public function getRawArgs(): array
    {
        return $this->rawArgs;
    }

    /**
     * Get all the arguments
     *
     * @return array
     */

    public function getArgs(): array
    {
        if (isset($this->args)) {
            return $this->args;
        }

        $this->args = [];
        $rawArgs = $this->getRawArgs();

        foreach ($rawArgs as $i => $arg) {
            if (!str_starts_with($arg, '-')) {
                continue;
            }

            if (strlen($arg) > 2 && substr($arg, 0, 2) != '--') {
                continue;
            }

            // Offset values determins wheter an option is a short option or a long one
            // Offset 1: short option
            // Offset 2: long option
            $keyOffset = strlen($arg) == 2 ? 1 : 2;

            if (!str_contains($arg, '=')) {
                $key = substr($arg, $keyOffset);
                $value = isset($rawArgs[$i + 1]) && !str_starts_with($rawArgs[$i + 1], '-') && $keyOffset == 1
                    ? $rawArgs[$i + 1]
                    : false;
            } elseif (str_contains($arg, '=') && $keyOffset == 2) {
                [$key, $value] = explode('=', $arg);

                $key = substr($key, $keyOffset);
            } else {
                continue;
            }

            // If an argument is provided multiple time store values as array
            if (isset($this->args[$key])) {
                $values = is_array($this->args[$key]) ? $this->args[$key] : [$this->args[$key]];
                $values[] = $value;
                $this->args[$key] = $values;
            } else {
                $this->args[$key] = $value;
            }
        }

        return $this->args;
    }

    /**
     * Get a single argument
     *
     * @param string $name
     * @return mixed
     */

    public function getArg(string $name): mixed
    {
        return $this->getArgs()[$name] ?? null;
    }

    /**
     * Get standard input
     *
     * @return mixed
     */

    public function getStandardInput(): mixed
    {
        return $this->standardInput;
    }
}
