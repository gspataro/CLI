<?php

namespace GSpataro\CLI;

use GSpataro\CLI\Exception\CommandNotFoundException;

final class CommandsCollection
{
    /**
     * Store commands
     *
     * @var array
     */

    private array $commands = [];

    /**
     * Verify if the collection has an command
     *
     * @param string $tag
     * @return bool
     */

    public function has(string $tag): bool
    {
        return isset($this->commands[$tag]);
    }

    /**
     * Verify callback
     *
     * @param string $command
     * @param array|callable $callback
     * @return void
     */

    private function verifyCallback(string $command, array|callable $callback): void
    {
        if (is_array($callback)) {
            if (!isset($callback[0]) || !$callback[0] instanceof Command) {
                throw new Exception\InvalidCommandCallbackException(
                    "Invalid callback for command '{$command}'. The first element of the array must be a class."
                );
            }

            if (!isset($callback[1]) || !method_exists($callback[0], $callback[1])) {
                throw new Exception\InvalidCommandCallbackException(
                    "Invalid callback for command '{$command}'. The second element of the array must be a method of '"
                    . $callback[0]::class . "'."
                );
            }
        }
    }

    /**
     * Add an command to the collection
     *
     * @param string $command
     * @param array|callable $callback
     * @param array $options
     * @param string|null $description
     * @return void
     */

    public function add(
        string $command,
        array|callable $callback,
        array $options = [],
        ?string $description = null
    ): void {
        if ($this->has($command)) {
            throw new Exception\CommandFoundException(
                "Command '{$command}' already exists in the collection."
            );
        }

        $this->verifyCallback($command, $callback);

        foreach ($options as $option => &$definition) {
            if (!is_array($definition)) {
                throw new Exception\InvalidCommandOptionsDefinitionException(
                    "Invalid option '{$option}' definition for command '{$command}'." .
                    "An option must include an empty array or an array with validation informations."
                );
            }

            $definition['type'] = in_array($definition['type'] ?? null, ['required', 'optional', 'novalue'])
                ? $definition['type']
                : "optional";
            $definition['short'] = $definition['short'] ?? null;
            $definition['description'] = $definition['description'] ?? null;
        }

        $this->commands[$command] = [
            "options" => $options,
            "callback" => $callback,
            "description" => $description
        ];
    }

    /**
     * Add multiple commands at a time
     *
     * @param array $commands
     * @return void
     */

    public function feed(array $commands): void
    {
        foreach ($commands as $command => $definition) {
            if (!isset($definition['callback'])) {
                throw new Exception\IncompleteCommandDefinitionException(
                    "Incomplete command '{$command}' definition. A command must include at least a valid callback."
                );
            }

            $definition['description'] = $definition['description'] ?? null;
            $this->add($command, $definition['callback'], $definition['options'], $definition['description']);
        }
    }

    /**
     * Get a command
     *
     * @return array
     */

    public function get(string $command): array
    {
        if (!$this->has($command)) {
            throw new CommandNotFoundException(
                "Command '{$command}' not found in the collection."
            );
        }

        return $this->commands[$command];
    }

    /**
     * Get all the commands
     *
     * @return array
     */

    public function getAll(): array
    {
        return $this->commands;
    }
}
