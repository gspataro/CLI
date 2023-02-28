<?php

namespace GSpataro\CLI;

use function PHPSTORM_META\map;

final class Output
{
    /**
     * Store ANSI codes
     *
     * @var array
     */

    private array $ansiCodes = [
        "@c;" => "\033[0m", // Clear
        "@b;" => "\033[1m", // Bold
        "@d;" => "\033[2m", // Dim
        "@i;" => "\033[3m", // Italic
        "@u;" => "\033[4m", // Underline
        "@r;" => "\033[31m", // Red,
        "@g;" => "\033[32m" // Green
    ];

    /**
     * Store text normalizer code
     *
     * @var int
     */

    private int $normalizerCode = 0;

    /**
     * Prepare the text replacing regular expressions with ANSI codes
     *
     * @param string $text
     * @return string
     */

    private function format(string $text): string
    {
        return strtr($text, array_merge(["@nl" => "\n"], $this->ansiCodes));
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
            array_merge(["@nl"], array_keys($this->ansiCodes)),
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
            $text .= $autoclear ? "@c;" : null;
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
