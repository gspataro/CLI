<?php

namespace GSpataro\CLI\Helper;

use GSpataro\CLI\CommandsCollection;
use GSpataro\CLI\Interface\InputInterface;
use GSpataro\CLI\Interface\OutputInterface;

class Manpage
{
    /**
     * Store the table
     *
     * @var Table
     */

    private readonly Table $table;

    /**
     * Store locale
     *
     * @var array
     */

    private array $locale = [];

    /**
     * Initialize Manpage object
     *
     * @param CommandsCollection $commands
     * @param OutputInterface $output
     */

    public function __construct(
        private readonly CommandsCollection $commands,
        private readonly InputInterface $input,
        private readonly OutputInterface $output
    ) {
        $this->locale = [
            'usage' => 'Usage',
            'command' => 'command',
            'option' => 'option',
            'available_commands' => 'Available commands',
            'required' => 'required',
            'list_available_commands' => 'List available commands'
        ];
    }

    /**
     * Set locale string
     *
     * @param string $key
     * @param string $value
     * @return void
     */

    public function setLocale(string $key, string $value): void
    {
        $this->locale[$key] = $value;
    }

    /**
     * Prepare the table
     *
     * @return void
     */

    private function prepareTable(): void
    {
        $this->table = new Table($this->output);
        $this->table->setStyle('row', '{italic}');

        foreach ($this->commands->getAll() as $commandName => $command) {
            $this->table->addRow([$commandName, $command->getDescription()], 'heading');

            foreach ($command->getOptions() as $optionName => $option) {
                $separator = is_null($option['description']) ? null : " ";
                $prefix = $option['type'] == "required" ? "-" : "--";
                $longopt = $option['longname'] ?? null;
                $shortopt = $option['shortname'] ?? null;

                if ($longopt && !$shortopt) {
                    $names = $prefix . $longopt;
                } elseif (!$longopt && $shortopt) {
                    $names = $prefix . $shortopt;
                } else {
                    $names = $prefix . $shortopt . ', ' . $prefix . $longopt;
                }

                $this->table->addRow([
                    $names,
                    $option['description'] .
                    ($option['type'] == "required" ? "{$separator}({$this->locale['required']})" : null)
                ]);
            }

            $this->table->addSeparator();
        }

        $this->table->addRow(['help', $this->locale['list_available_commands']], 'heading');
    }

    /**
     * Print manpage
     *
     * @return void
     */

    public function render(): void
    {
        $this->prepareTable();

        $this->output->print(
            "Usage: {$this->input->getScriptName()} " .
            "{bold}{underline}{$this->locale['command']}{clear} " .
            "{italic}{$this->locale['option']}{nl}"
        );
        $this->output->print($this->locale['available_commands']);
        $this->table->render();
    }
}
