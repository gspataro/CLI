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
        $this->output->print("Usage: {$this->input->getScriptName()} *_command_* <args>");
        $this->output->print("");
        $this->output->print("Available commands:");

        foreach ($this->commands->getAll() as $commandName => $command) {
            $this->output->print("*_{$commandName}_*\t\t_{$command['manpage']}_");

            foreach ($command['args'] as $argName => $arg) {
                $separator = is_null($arg['manpage']) ? null : " ";
                $this->output->print("<{$argName}>\t\t{$arg['manpage']}" . (
                    $arg['required'] ? "{$separator}(required)" : null
                ));
            }

            $this->output->print("");
        }

        $this->output->print("*_help_*\t\t_List available commands_");
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

        foreach ($command['args'] as $argName => $params) {
            if (!isset($this->input->getArgs()[$i]) && $params['required']) {
                $this->printManpage();
                return;
            }

            $args[$argName] = $this->input->getArgs()[$i] ?? null;
            $i++;
        }

        call_user_func_array($command['callback'], [
            "input" => $this->input,
            "output" => $this->output,
            "args" => $args
        ]);
    }
}
