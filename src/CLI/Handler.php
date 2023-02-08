<?php

namespace GSpataro\CLI;

final class Handler
{
    /**
     * Initialize Handler object
     *
     * @param OptionsCollection $options
     * @param Input $input
     * @param Output $output
     */

    public function __construct(
        private OptionsCollection $options,
        private Input $input,
        private Output $output
    ) {
    }

    /**
     * Generate options manpage
     *
     * @return void
     */

    public function printManpage(): void
    {
        $this->output->print("Usage: {$this->input->getScriptName()} *_option_* <args>");
        $this->output->print("");
        $this->output->print("Available options:");

        foreach ($this->options->getAll() as $optionName => $option) {
            $this->output->print("*_{$optionName}_*\t\t_{$option['manpage']}_");

            foreach ($option['args'] as $argName => $arg) {
                $separator = is_null($arg['manpage']) ? null : " ";
                $this->output->print("<{$argName}>\t\t{$arg['manpage']}" . (
                    $arg['required'] ? "{$separator}(required)" : null
                ));
            }

            $this->output->print("");
        }

        $this->output->print("*_help_*\t\t_List available options_");
    }

    /**
     * Start the input handling process and execute requested option callback
     *
     * @return void
     */

    public function deploy(): void
    {
        if ($this->input->getOptionName() == "help" || !$this->options->has($this->input->getOptionName())) {
            $this->printManpage();
            return;
        }

        $optionName = $this->input->getOptionName();
        $option = $this->options->get($optionName);
        $args = [];
        $i = 0;

        foreach ($option['args'] as $argName => $params) {
            if (!isset($this->input->getArgs()[$i]) && $params['required']) {
                $this->printManpage();
                return;
            }

            $args[$argName] = $this->input->getArgs()[$i] ?? null;
            $i++;
        }

        call_user_func_array($option['callback'], [
            "input" => $this->input,
            "output" => $this->output,
            "args" => $args
        ]);
    }
}
