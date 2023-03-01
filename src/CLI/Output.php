<?php

namespace GSpataro\CLI;

final class Output
{
    /**
     * Store format placeholders
     *
     * @var array
     */

    private array $formatPlaceholders = [
        "{nl}" => "\n",
        "{clear}" => "\033[0m",
        "{bold}" => "\033[1m",
        "{dim}" => "\033[2m",
        "{italic}" => "\033[3m",
        "{underline}" => "\033[4m",
        "{red}" => "\033[31m",
        "{green}" => "\033[32m"
    ];

    /**
     * Prepare the text replacing placeholders with formats
     *
     * @param string $text
     * @return string
     */

    private function format(string $text): string
    {
        return strtr($text, $this->formatPlaceholders);
    }

    /**
     * Get text without format tags
     *
     * @param string $text
     * @return string
     */

    private function striptags(string $text): string
    {
        return str_replace(
            array_merge(["@nl"], array_keys($this->formatPlaceholders)),
            "",
            $text
        );
    }

    /**
     * Print text to the console
     *
     * @param string $text
     * @param bool $finalNewLine
     * @param bool $autoclear
     * @param bool $raw
     * @return void
     */

    public function print(string $text, bool $finalNewLine = true, bool $autoclear = true, bool $raw = false): void
    {
        if ($finalNewLine) {
            $text .= "\n";
        }

        if (!$raw) {
            $text .= $autoclear ? "\033[0m" : null;
            $text = $this->format($text);
        }

        printf($text);
    }

    /**
     * Print a table to the console
     *
     * @param array $structure
     * @param int $columnsNumber
     * @param int $pad
     * @param array $styles
     * @return void
     */

    public function printTable(array $structure, int $columnsNumber, int $pad = 5, array $styles = []): void
    {
        $styles['heading'] = $styles['heading'] ?? [
            "prefix" => "\033[1m\033[4m",
            "suffix" => "\033[0m"
        ];

        $styles['row'] = $styles['row'] ?? [
            "prefix" => "",
            "suffix" => ""
        ];

        $mask = "";

        for ($i = 0; $i < $columnsNumber; $i++) {
            $colSize = 0;

            foreach ($structure as $row) {
                $cols = array_values($row)[0] ?? null;

                if (is_null($cols)) {
                    continue;
                }

                $colLength = strlen($cols[$i]);

                if ($colLength > $colSize) {
                    $colSize = $colLength;
                }
            }

            $colWidth = $colSize;

            if ($i < $columnsNumber - 1) {
                $colWidth += $pad;
            }

            $mask .= "%-{$colWidth}.{$colWidth}s";
        }

        $mask .= "\n";

        foreach ($structure as $i => $row) {
            $type = array_keys($row)[0] ?? null;
            $cols = array_values($row)[0] ?? array_fill(0, $columnsNumber, null);
            $style = $styles[$type] ?? $styles['row'];

            call_user_func_array("printf", array_merge((array) ($style['prefix'] . $mask . $style['suffix']), $cols));
        }
    }
}
