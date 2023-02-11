<?php

namespace GSpataro\CLI;

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
     * Add an command to the collection
     *
     * @param string $tag
     * @param callable $callback
     * @param array $args
     * @param string|null $manpage
     * @return void
     */

    public function add(string $tag, callable $callback, array $args = [], ?string $manpage = null): void
    {
        if ($this->has($tag)) {
            throw new Exception\CommandFoundException(
                "An command named '{$tag}' already exists in the collection."
            );
        }

        if (!empty($args)) {
            foreach ($args as $argName => $arg) {
                $key = is_array($arg) ? $argName : $arg;

                $args[$key]['required'] = $arg['required'] ?? false;
                $args[$key]['manpage'] = $arg['manpage'] ?? null;

                if (!is_array($arg)) {
                    unset($args[$argName]);
                }
            }
        }

        $this->commands[$tag] = [
            "callback" => $callback,
            "args" => $args,
            "manpage" => $manpage
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
        foreach ($commands as $tag => $params) {
            if (!isset($params['callback'])) {
                throw new Exception\IncompleteCommandParamsException(
                    "Incomplete command '{$tag}' definition. An command must include at least a valid callback."
                );
            }

            $params['args'] = $params['args'] ?? [];
            $params['manpage'] = $params['manpage'] ?? null;

            $this->add($tag, $params['callback'], $params['args'], $params['manpage']);
        }
    }

    /**
     * Get an command from the collection
     *
     * @param string $tag
     * @return array
     */

    public function get(string $tag): array
    {
        if (!$this->has($tag)) {
            throw new Exception\CommandNotFoundException(
                "Command named '{$tag}' not found."
            );
        }

        return $this->commands[$tag];
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
