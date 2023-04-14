<?php

namespace GSpataro\CLI;

use GSpataro\CLI\Helper\Manpage;
use GSpataro\CLI\Interface\InputInterface;
use GSpataro\CLI\Interface\OutputInterface;

final class Handler
{
    /**
     * Store manpage
     *
     * @var Manpage
     */

    private Manpage $manpage;

    /**
     * Initialize Handler object
     *
     * @param CommandsCollection $commands
     * @param InputInterface $input
     * @param OutputInterface $output
     */

    public function __construct(
        private readonly CommandsCollection $commands,
        private readonly InputInterface $input = new Input(),
        private readonly OutputInterface $output = new Output()
    ) {
        $this->manpage = new Manpage(
            $this->commands,
            $this->input,
            $this->output
        );
    }

    /**
     * Set a different manpage helper
     *
     * @param Manpage $manpage
     * @return void
     */

    public function setManpage(Manpage $manpage): void
    {
        $this->manpage = $manpage;
    }

    /**
     * Process arguments and return key=value structure
     *
     * @param array $args
     * @return array
     */

    public function translateArguments(array $args): array
    {
        $output = [];

        foreach ($args as $i => $arg) {
            if (!str_starts_with($arg, '-')) {
                continue;
            }

            if (strlen($arg) > 2 && substr($arg, 0, 2) != "--") {
                continue;
            }

            // Offset values determins wheter an option is a short option or a long one
            // Offset 1: short option
            // Offset 2: long option
            $keyOffset = strlen($arg) == 2 ? 1 : 2;

            if (!str_contains($arg, '=')) {
                $key = substr($arg, $keyOffset);
                $value = isset($args[$i + 1]) && !str_starts_with($args[$i + 1], '-') && $keyOffset == 1
                    ? $args[$i + 1]
                    : false;
            } elseif (str_contains($arg, '=') && $keyOffset == 2) {
                [$key, $value] = explode('=', $arg);

                $key = substr($key, $keyOffset);
            } else {
                continue;
            }

            if (isset($output[$key])) {
                $values = is_array($output[$key]) ? $output[$key] : [$output[$key]];
                $values[] = $value;
                $output[$key] = $values;
            } else {
                $output[$key] = $value;
            }
        }

        return $output;
    }

    /**
     * Start the input handling process and execute requested command callback
     *
     * @return void
     */

    public function deploy(): void
    {
        if ($this->input->getCommandName() == "help" || !$this->commands->has($this->input->getCommandName())) {
            $this->manpage->render();
            return;
        }

        $commandName = $this->input->getCommandName();
        $command = $this->commands->get($commandName);

        $inputArgs = $this->translateArguments($this->input->getArgs());
        $outputArgs = [];
        $i = 0;

        foreach ($command['options'] as $optionName => $option) {
            if (
                !isset($inputArgs[$optionName]) &&
                !isset($inputArgs[$option['short']]) &&
                $option['type'] == "required"
            ) {
                $this->manpage->render();
                return;
            }

            $outputArgs[$optionName] = $inputArgs[$optionName] ?? $inputArgs[$option['short']] ?? null;
            $i++;
        }

        if (is_array($command['callback'])) {
            $object = $command['callback'][0];
            $object->setIO($this->input, $this->output);

            call_user_func_array($command['callback'], $outputArgs);
        } else {
            call_user_func_array($command['callback'], [
                "input" => $this->input,
                "output" => $this->output,
            ] + $outputArgs);
        }
    }
}
