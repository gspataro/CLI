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
     * Store table styles
     *
     * @var array
     */

    private array $styles = [];

    /**
     * Initialize Table object
     *
     * @param OutputInterface $output
     */

    public function __construct(
        private readonly OutputInterface $output
    ) {
        $this->styles = [
            'heading' => '{bold}',
            'row' => ''
        ];
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
     * @param string $rowStyle
     * @return void
     */

    public function addRow(array $cols, string $rowStyle = 'row'): void
    {
        $this->structure[] = [$rowStyle => $cols];
    }

    /**
     * Add a separator
     *
     * @return void
     */

    public function addSeparator(): void
    {
        $this->structure[] = [];
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
     * Set row style
     *
     * @param string $name
     * @param string $style
     * @return void
     */

    public function setStyle(string $name, string $style): void
    {
        $this->styles[$name] = $style;
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

                $col = $cols[$i];
                $colValue = is_array($col) ? (array_values($col)[0] ?? '') : $col;
                $lengths[] = strlen($colValue);
            }

            $this->colSizes[$i] = max($lengths);
        }
    }

    /**
     * Build a column
     *
     * @param int $index
     * @param array|string $col
     * @return string
     */

    private function buildColumn(int $i, array|string $col): string
    {
        $colStyle = is_array($col) ? (array_keys($col)[0] ?? '') : '';
        $value = is_array($col) ? (array_values($col)[0] ?? '') : $col;
        $style = $this->styles[$colStyle] ?? '';

        $rawCol = $this->output->removeFormat($value);
        $colLength = strlen($rawCol);
        $colWidth = $this->colSizes[$i];

        if ($i < ($this->colsNumber - 1)) {
            $colPad = $colLength < $colWidth
                ? str_repeat($this->padCharacter, $colWidth + $this->colPad - $colLength)
                : str_repeat($this->padCharacter, $this->colPad);
        } else {
            $colPad = '';
        }

        return $style . $value . $colPad;
    }

    /**
     * Build a row
     *
     * @param string $style
     * @param array $row
     * @return string
     */

    private function buildRow(string $style, array $cols): string
    {
        $row = '';

        foreach ($cols as $i => $col) {
            $row .= $this->output->prepare(
                $style . $this->buildColumn($i, $col ?? ''),
                $i == ($this->colsNumber - 1),
                true,
                true
            );
        }

        return $row;
    }

    /**
     * Build the table and return the raw string
     *
     * @return string
     */

    public function build(): string
    {
        $this->calculateWidths();

        $table = '';

        foreach ($this->structure as $row) {
            $rowStyle = array_keys($row)[0] ?? 'row';
            $cols = array_values($row)[0] ?? [];
            $colsCount = count($cols);
            $style = $this->styles[$rowStyle] ?? $this->styles['row'];

            if (empty($cols)) {
                $table .= $this->output->prepare('', true, false, true);
                continue;
            }

            if ($colsCount < $this->colsNumber) {
                $cols += array_fill($colsCount, $this->colsNumber - $colsCount, '');
            }

            $table .= $this->buildRow($style, $cols);
        }

        return $table;
    }

    /**
     * Render the table
     *
     * @return array
     */

    public function render(): void
    {
        $table = $this->build();
        $this->output->print($table);
    }
}
