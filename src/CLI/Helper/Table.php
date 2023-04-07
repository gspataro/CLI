<?php

namespace GSpataro\CLI\Helper;

use GSpataro\CLI\Interface\OutputInterface;

final class Table
{
    /**
     * Store table structure
     *
     * @var array
     */

    private array $structure = [];

    /**
     * Store space between columns
     *
     * @var int
     */

    private int $colPad = 5;

    /**
     * Store columns number
     *
     * @var int
     */

    private int $colsNumber = 0;

    /**
     * Store columns widths
     *
     * @var array
     */

    private array $colSizes = [];

    /**
     * Store padding character
     *
     * @var string
     */

    private string $padCharacter = ' ';

    /**
     * Initialize Table object
     *
     * @param OutputInterface $output
     */

    public function __construct(
        private readonly OutputInterface $output
    ) {
    }

    /**
     * Set table rows
     *
     * @param array $rows
     * @return static
     */

    public function setRows(array $rows): void
    {
        $this->structure = $rows;
    }

    /**
     * Add a row to the table
     *
     * @param array $cols
     * @param string $rowType
     * @return void
     */

    public function addRow(array $cols, string $rowType = 'row'): void
    {
        $this->structure[] = [$rowType => $cols];
    }

    /**
     * Set the size of the padding between columns
     *
     * @param int $padding
     * @return void
     */

    public function setPadding(int $padding): void
    {
        $this->colPad = $padding;
    }

    /**
     * Set the character used for padding
     *
     * @param string $character
     * @return void
     */

    public function setPaddingCharacter(string $character): void
    {
        $this->padCharacter = $character;
    }

    /**
     * Calculate the number of columns
     *
     * @return void
     */

    private function calculateColumnsNumber(): void
    {
        $columns = [];

        foreach ($this->structure as $row) {
            $cols = array_values($row)[0] ?? [];

            if (empty($cols)) {
                continue;
            }

            $columns[] = count($cols);
        }

        $this->colsNumber = max($columns);
    }

    /**
     * Calculate columns width
     *
     * @return void
     */

    private function calculateWidths(): void
    {
        $rows = $this->structure;
        $this->calculateColumnsNumber();

        for ($i = 0; $i < $this->colsNumber; $i++) {
            $lengths = [0];

            foreach ($rows as $row) {
                $cols = array_values($row)[0] ?? [];

                if (empty($cols)) {
                    continue;
                }

                if (!isset($cols[$i])) {
                    continue;
                }

                $lengths[] = strlen($cols[$i]);
            }

            $this->colSizes[$i] = max($lengths);
        }
    }

    /**
     * Render the table
     *
     * @return array
     */

    public function render(): void
    {
        $this->calculateWidths();

        foreach ($this->structure as $row) {
            $cols = array_values($row)[0] ?? [];
            $colsCount = count($cols);

            if ($colsCount < $this->colsNumber) {
                $cols += array_fill($colsCount, $this->colsNumber - $colsCount, '');
            }

            foreach ($cols as $i => $col) {
                $rawCol = $this->output->removeFormat($col);
                $colLength = strlen($rawCol);
                $colWidth = $this->colSizes[$i];

                if ($i < ($this->colsNumber - 1)) {
                    $colPad = $colLength < $colWidth
                        ? str_repeat($this->padCharacter, $colWidth + $this->colPad - $colLength)
                        : str_repeat($this->padCharacter, $this->colPad);
                } else {
                    $colPad = '';
                }

                $this->output->print($col . $colPad, $i == ($this->colsNumber - 1));
            }
        }
    }
}
