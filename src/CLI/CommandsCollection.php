<?php

namespace GSpataro\CLI;

use GSpataro\CLI\Exception\CommandNotFoundException;
use GSpataro\CLI\Helper\BaseCommand;

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
     * @return Command
     */

    public function create(string $name): Command
    {
        if ($this->has($name)) {
            throw new Exception\CommandFoundException(
                "Command '{$name}' already exists in the collection."
            );
        }

        $command = new Command();
        $command->setName($name);

        $this->commands[$name] = $command;
        return $this->commands[$name];
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
