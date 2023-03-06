<?php

namespace GSpataro\CLI;

final class Output
{
    /**
     * Store format placeholders
     *
     * @var array
     */

    private array $formatPlaceholders = [];

    /**
     * Initialize Output object
     */

    public function __construct()
    {
        // Convert EscapeCodesEnum to array
        foreach (EscapeCodesEnum::toArray() as $key => $value) {
            $this->formatPlaceholders['{' . $key . '}'] = $value;
        }
    }

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
            array_keys($this->formatPlaceholders),
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
            $text .= EscapeCodesEnum::nl->value;
        }

        if (!$raw) {
            $text = $this->format($text);
            $text .= $autoclear ? EscapeCodesEnum::clear->value : null;
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
            "prefix" => EscapeCodesEnum::bold->value . EscapeCodesEnum::underline->value,
            "suffix" => EscapeCodesEnum::clear->value
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

        $mask .= EscapeCodesEnum::nl->value;

        foreach ($structure as $i => $row) {
            $type = array_keys($row)[0] ?? null;
            $cols = array_values($row)[0] ?? array_fill(0, $columnsNumber, null);
            $style = $styles[$type] ?? $styles['row'];

            call_user_func_array("printf", array_merge((array) ($style['prefix'] . $mask . $style['suffix']), $cols));
        }
    }

    /**
     * Prompt the user to enter a value
     * If obfuscate is set to true the user input will be obfuscated
     * If multiple is set to true the promp will accept multiple values separated by the separator
     *
     * @param string $message
     * @param bool $obfuscate
     * @param bool $multiple
     * @param string $separator
     * @return mixed
     */

    public function prompt(string $message, bool $obfuscate = false, bool $multiple = false, string $separator = " "): mixed
    {
        $value = readline($this->format($message) . " " . ($obfuscate ? EscapeCodesEnum::conceal->value : null));
        printf(EscapeCodesEnum::clear->value);

        return $multiple ? explode($separator, $value) : $value;
    }
}
