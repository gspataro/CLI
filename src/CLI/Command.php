<?php

namespace GSpataro\CLI;

use Closure;
use GSpataro\CLI\Helper\BaseCommand;

final class Command
{
    /**
     * Store callback
     *
     * @var Closure|BaseCommand|string
     */

    private readonly Closure|BaseCommand|string $callback;

    /**
     * Store options
     *
     * @var array
     */

    private readonly array $options;

    /**
     * Initialize Command object
     *
     * @param string $name
     */

    public function __construct(
        private readonly string $name,
        private readonly ?string $description = null
    ) {
    }

    /**
     * Set command callback
     *
     * @param BaseCommand|string|callable $callback
     * @return static
     */

    public function setCallback(BaseCommand|string|callable $callback): static
    {
        if (is_string($callback) && !is_subclass_of($callback, BaseCommand::class)) {
            throw new Exception\InvalidCommandCallbackException(
                "Invalid callback for command '{$this->getName()}'. " .
                "A command class must extend the GSpataro\\CLI\\Helper\\BaseCommand class."
            );
        }

        $this->callback = is_string($callback) || $callback instanceof BaseCommand
            ? $callback
            : Closure::fromCallable($callback);
        return $this;
    }

    /**
     * Set command options
     *
     * @param array $options
     * @return static
     */

    public function setOptions(array $options): static
    {
        foreach ($options as $name => &$option) {
            if (!is_array($option)) {
                throw new Exception\InvalidCommandOptionsDefinitionException(
                    "Invalid option '{$name}' definition for command '{$this->getName()}'. " .
                    "An option must be an array containing the informations needed."
                );
            }

            $option['shortname'] ??= null;

            if (!is_null($option['shortname']) && strlen($option['shortname']) !== 1) {
                throw new Exception\InvalidCommandOptionsDefinitionException(
                    "Invalid option '{$name}' definition for command '{$this->getName()}'. " .
                    "An option shortname must be only one character long."
                );
            }

            $option['type'] = in_array($option['type'] ?? null, ['required', 'optional', 'toggle'])
                ? $option['type']
                : 'optional';
            $option['description'] ??= null;
        }

        $this->options = $options;
        return $this;
    }

    /**
     * Get command name
     *
     * @return string
     */

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get command description
     *
     * @return string|null
     */

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get command callback
     *
     * @return BaseCommand|string|callable
     */

    public function getCallback(): BaseCommand|string|callable
    {
        return $this->callback;
    }

    /**
     * Get command options
     *
     * @return array
     */

    public function getOptions(): array
    {
        return $this->options;
    }
}
