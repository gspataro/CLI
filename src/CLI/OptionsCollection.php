<?php

namespace GSpataro\CLI;

final class OptionsCollection
{
    /**
     * Store options
     *
     * @var array
     */

    private array $options = [];

    /**
     * Verify if the collection has an option
     *
     * @param string $tag
     * @return bool
     */

    public function has(string $tag): bool
    {
        return isset($this->options[$tag]);
    }

    /**
     * Add an option to the collection
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
            throw new Exception\OptionFoundException(
                "An option named '{$tag}' already exists in the collection."
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

        $this->options[$tag] = [
            "callback" => $callback,
            "args" => $args,
            "manpage" => $manpage
        ];
    }

    /**
     * Add multiple options at a time
     *
     * @param array $options
     * @return void
     */

    public function feed(array $options): void
    {
        foreach ($options as $tag => $params) {
            if (!isset($params['callback'])) {
                throw new Exception\IncompleteOptionParamsException(
                    "Incomplete option '{$tag}' definition. An option must include at least a valid callback."
                );
            }

            $params['args'] = $params['args'] ?? [];
            $params['manpage'] = $params['manpage'] ?? null;

            $this->add($tag, $params['callback'], $params['args'], $params['manpage']);
        }
    }

    /**
     * Get an option from the collection
     *
     * @param string $tag
     * @return array
     */

    public function get(string $tag): array
    {
        if (!$this->has($tag)) {
            throw new Exception\OptionNotFoundException(
                "Option named '{$tag}' not found."
            );
        }

        return $this->options[$tag];
    }

    /**
     * Get all the options
     *
     * @return array
     */

    public function getAll(): array
    {
        return $this->options;
    }
}
