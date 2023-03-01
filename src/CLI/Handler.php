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
        $this->output->print("Usage: {$this->input->getScriptName()} {bold}{underline}command{clear} {italic}option{nl}");
        $this->output->print("Available commands:");

        $table = [];

        foreach ($this->commands->getAll() as $commandName => $commandDefinition) {
            $table[] = [
                "heading" => [$commandName, $commandDefinition['description']]
            ];

            foreach ($commandDefinition['options'] as $optionName => $optionDefinition) {
                $separator = is_null($optionDefinition['description']) ? null : " ";
                $prefix = $optionDefinition['type'] == "required" ? "-" : "--";
                $shortopt = $optionDefinition['short'] ? "{$prefix}{$optionDefinition['short']}, " : null;

                $table[] = [
                    "row" => [
                        $shortopt . $prefix . $optionName,
                        $optionDefinition['description'] . ($optionDefinition['type'] == "required" ? "{$separator}(required)" : null)
                    ]
                ];
            }

            $table[] = [];
        }

        $table[] = [
            "heading" => [
                "help",
                "List available commands"
            ]
        ];

        $this->output->printTable($table, 2, 5, [
            "row" => [
                "prefix" => "\033[3m",
                "suffix" => "\033[0m"
            ]
        ]);
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
                $value = isset($args[$i+1]) && !str_starts_with($args[$i+1], '-') && $keyOffset == 1 ? $args[$i+1] : false;
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

        $inputArgs = $this->translateArguments($this->input->getArgs());
        $outputArgs = [];
        $i = 0;

        foreach ($command['options'] as $optionName => $option) {
            if (!isset($inputArgs[$optionName]) && !isset($inputArgs[$option['short']]) && $option['type'] == "required") {
                $this->printManpage();
                return;
            }

            $outputArgs[$optionName] = $inputArgs[$optionName] ?? $inputArgs[$option['short']] ?? null;
            $i++;
        }

        call_user_func_array($command['callback'], [
            "input" => $this->input,
            "output" => $this->output,
        ] + $outputArgs);
    }
}
