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
     * Calculate the number of columns
     *
     * @return void
     */

    private function calculateColumnsNumber(): void
    {
        $columns = [];

        foreach ($this->structure as $row) {
            $cols = array_values($row)[0];

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
                $cols = array_values($row)[0];

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
            $cols = array_values($row)[0];
            $colsCount = count($cols);

            if ($colsCount < $this->colsNumber) {
                $cols += array_fill($colsCount, $this->colsNumber - $colsCount, '');
            }

            foreach ($cols as $i => $col) {
                $rawCol = $this->output->removeFormat($col);
                $colLength = strlen($rawCol);
                $colWidth = $this->colSizes[$i];
                $colPad = $colLength < $colWidth
                    ? str_repeat(' ', $colWidth + $this->colPad - $colLength)
                    : str_repeat(' ', $this->colPad);

                $this->output->print($col . $colPad, $i == ($this->colsNumber - 1));
            }
        }
    }
}
