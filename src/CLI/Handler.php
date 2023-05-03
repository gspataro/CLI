<?php

namespace GSpataro\CLI;

use GSpataro\CLI\Helper\BaseCommand;
use GSpataro\CLI\Helper\Manpage;
use GSpataro\CLI\Interface\InputInterface;
use GSpataro\CLI\Interface\OutputInterface;

final class Handler
{
    /**
     * Store header
     *
     * @var string
     */

    private string $header;

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
     * Set script header
     * The header will be printed every time the script is executed
     *
     * @param string $text
     * @return void
     */

    public function setHeader(string $text): void
    {
        $this->header = $text;
    }

    /**
     * Start the input handling process and execute requested command callback
     *
     * @return void
     */

    public function deploy(): void
    {
        if (isset($this->header)) {
            $this->output->print($this->header);
        }

        $commandName = $this->input->getCommandName();

        if ($commandName == 'help' || !$this->commands->has($commandName)) {
            $this->manpage->render();
            return;
        }

        $command = $this->commands->get($commandName);
        $outputArgs = [];
        $i = 0;

        foreach ($command->getOptions() as $optionName => $option) {
            $shortname = $option['shortname'] ?? null;
            $type = $option['type'];

            if (
                is_null($this->input->getArg($optionName))
                && (is_null($shortname) || is_null($this->input->getArg($shortname)))
                && $type == 'required'
            ) {
                $this->manpage->render();
                return;
            }

            if (!is_null($this->input->getArg($optionName))) {
                $outputArgs[$optionName] = $this->input->getArg($optionName);
            } elseif (!is_null($shortname) && !is_null($this->input->getArg($shortname))) {
                $outputArgs[$optionName] = $this->input->getArg($shortname);
            } else {
                $outputArgs[$optionName] = null;
            }

            $i++;
        }

        $callback = $command->getCallback();

        if (is_string($callback) || $callback instanceof BaseCommand) {
            $object = is_string($callback) ? new $callback() : $callback;
            $object->setIO($this->input, $this->output);
            $object->setArgs($outputArgs);
            $object->main();
        } else {
            call_user_func_array($callback, [
                'input' => $this->input,
                'output' => $this->output,
            ] + $outputArgs);
        }
    }
}
