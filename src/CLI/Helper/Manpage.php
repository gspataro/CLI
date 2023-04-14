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

        foreach ($this->commands->getAll() as $commandName => $commandDefinition) {
            $this->table->addRow([$commandName, $commandDefinition['description']], 'heading');

            foreach ($commandDefinition['options'] as $optionName => $optionDefinition) {
                $separator = is_null($optionDefinition['description']) ? null : " ";
                $prefix = $optionDefinition['type'] == "required" ? "-" : "--";
                $shortopt = $optionDefinition['short'] ? "{$prefix}{$optionDefinition['short']}, " : null;

                $this->table->addRow([
                    $shortopt . $prefix . $optionName,
                    $optionDefinition['description'] .
                    ($optionDefinition['type'] == "required" ? "{$separator}(required)" : null)
                ]);
            }

            $this->table->addSeparator();
        }

        $this->table->addRow(['help', 'List available commands'], 'heading');
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
            "Usage: {$this->input->getScriptName()} {bold}{underline}command{clear} {italic}option{nl}"
        );
        $this->output->print("Available commands:");
        $this->table->render();
    }
}
