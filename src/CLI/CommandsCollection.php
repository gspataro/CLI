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
     * @param string $name
     * @return bool
     */

    public function has(string $name): bool
    {
        return isset($this->commands[$name]);
    }

    /**
     * Register a command directly
     *
     * @param Command $command
     * @return void
     */

    public function register(Command $command): void
    {
        if ($this->has($command->getName())) {
            throw new Exception\CommandFoundException(
                "Command '{$command->getName()}' already exists in the collection."
            );
        }

        $this->commands[$command->getName()] = $command;
    }

    /**
     * Add a command to the collection
     *
     * @param string $name
     * @param callable $callback
     * @param array $options
     * @param string|null $description
     * @return void
     */

    public function add(
        string $name,
        callable $callback,
        array $options = [],
        ?string $description = null
    ): void {
        if ($this->has($name)) {
            throw new Exception\CommandFoundException(
                "Command '{$name}' already exists in the collection."
            );
        }

        $command = new Command($name, $description);
        $command->setCallback($callback)->setOptions($options);

        $this->commands[$name] = $command;
    }

    /**
     * Add multiple commands at a time
     *
     * @param array $commands
     * @return void
     */

    public function feed(array $commands): void
    {
        foreach ($commands as $name => $definition) {
            if (!isset($definition['callback'])) {
                throw new Exception\IncompleteCommandDefinitionException(
                    "Incomplete command '{$name}' definition. A command must include at least a valid callback."
                );
            }

            $definition['description'] = $definition['description'] ?? null;
            $this->add($name, $definition['callback'], $definition['options'], $definition['description']);
        }
    }

    /**
     * Get a command
     *
     * @return Command
     */

    public function get(string $name): Command
    {
        if (!$this->has($name)) {
            throw new CommandNotFoundException(
                "Command '{$name}' not found in the collection."
            );
        }

        return $this->commands[$name];
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
