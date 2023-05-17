<?php

namespace GSpataro\CLI;

use Closure;
use GSpataro\CLI\Interface\InputInterface;
use GSpataro\CLI\Interface\OutputInterface;

class Command
{
    protected readonly InputInterface $input;
    protected readonly OutputInterface $output;

    /**
     * Store callback
     *
     * @var Closure
     */

    protected readonly Closure $callback;

    /**
     * Store name
     *
     * @var string
     */

    protected string $name;

    /**
     * Store description
     *
     * @var string|null
     */

    protected ?string $description = null;

    /**
     * Store options
     *
     * @var array
     */

    protected array $options;

    /**
     * Store arguments
     *
     * @var array
     */

    private array $args = [];

    /**
     * Set command name
     *
     * @param string $name
     * @return static
     */

    final public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set command description
     *
     * @param string $description
     * @return static
     */

    final public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set command callback to execute
     *
     * @param callable $callback
     * @return static
     */

    final public function execute(callable $callback): static
    {
        $this->callback = Closure::fromCallable($callback);
        return $this;
    }

    /**
     * Set command options
     *
     * @param array $options
     * @return static
     */

    final public function setOptions(array $options): static
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
     * Set arguments
     *
     * @param array $args
     * @return static
     */

    final public function setArgs(array $args): static
    {
        $this->args = $args;
        return $this;
    }

    /**
     * Get command name
     *
     * @return string
     */

    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get command description
     *
     * @return string|null
     */

    final public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get command options
     *
     * @return array
     */

    final public function getOptions(): array
    {
        if (empty($this->options)) {
            $this->setOptions($this->options());
        }

        return $this->options;
    }

    /**
     * Get an argument
     *
     * @param string $name
     * @return mixed
     */

    final protected function argument(string $name): mixed
    {
        return $this->args[$name] ?? null;
    }

    /**
     * Register command options
     * Override this method to register command options
     *
     * @return array
     */

    public function options(): array
    {
        return [];
    }

    /**
     * Default command main method
     * Override this method to create command main logic
     *
     * @return void
     */

    public function main(): void
    {
        $this->output->print('{bg_green}{fg_white}{bold}Congratulations, you registered your first command!');
        $this->output->print('{bold}Now override the main() method to add functionality to this command.');
    }

    /**
     * Run the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param array $args
     * @return void
     */

    final public function run(InputInterface $input, OutputInterface $output, array $args = []): void
    {
        if (isset($this->callback)) {
            call_user_func_array($this->callback, [
                'input' => $input,
                'output' => $output
            ] + $args);
            return;
        }

        $this->input = $input;
        $this->output = $output;
        $this->setArgs($args);
        $this->main();
    }
}
