<?php

namespace GSpataro\CLI;

final class Handler
{
    /**
     * Initialize Handler object
     *
     * @param CommandsCollection $commands
     * @param Input $input
     * @param Output $output
     */

    public function __construct(
        private CommandsCollection $commands,
        private Input $input,
        private Output $output
    ) {
    }

    /**
     * Generate commands manpage
     *
     * @return void
     */

    public function printManpage(): void
    {
        $this->output->print("Usage: {$this->input->getScriptName()} @b;@u;command@c; @i;option");
        $this->output->print("");
        $this->output->print("Available commands:");

        foreach ($this->commands->getAll() as $commandName => $commandDefinition) {
            $this->output->print("@b;@u;{$commandName}\t\t{$commandDefinition['description']}@c;");

            foreach ($commandDefinition['options'] as $optionName => $optionDefinition) {
                $separator = is_null($optionDefinition['description']) ? null : " ";
                $prefix = $optionDefinition['type'] == "required" ? "-" : "--";
                $shortopt = $optionDefinition['short'] ? ", {$prefix}{$optionDefinition['short']}" : null;

                $this->output->print("@i;{$prefix}{$optionName}{$shortopt}\t\t{$optionDefinition['description']}" . (
                    $optionDefinition['type'] == "required" ? "{$separator}(required)" : null
                ));
            }

            $this->output->print("");
        }

        $this->output->print("@b;@u;help\t\tList available commands");
        echo "manpage";
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

            $keyOffset = strlen($arg) == 2 ? 1 : 2;

            if (!str_contains($arg, '=') && $keyOffset == 1) {
                $key = substr($arg, $keyOffset);
                $value = isset($args[$i+1]) && !str_starts_with($args[$i+1], '-') ? $args[$i+1] : false;
            } else if (str_contains($arg, '=') && $keyOffset == 2) {
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
            $this->printManpage();
            return;
        }

        $commandName = $this->input->getCommandName();
        $command = $this->commands->get($commandName);
        $args = [];
        $i = 0;

        foreach ($command['options'] as $optionName => $option) {
            if (!isset($this->input->getArgs()[$i]) && $option['type'] == "required") {
                $this->printManpage();
                return;
            }

            $args[$optionName] = $this->input->getArgs()[$i] ?? null;
            $i++;
        }

        call_user_func_array($command['callback'], [
            "input" => $this->input,
            "output" => $this->output,
            "args" => $args
        ]);
    }
}
