<?php

namespace GSpataro\CLI;

use GSpataro\CLI\Interface\OutputInterface;

final class Output implements OutputInterface
{
    /**
     * Store format placeholders
     *
     * @var array
     */

    private array $formatPlaceholders = [];

    /**
     * Store stopwatches
     *
     * @var array
     */

    private array $stopwatches = [];

    /**
     * Initialize Output object
     */

    public function __construct()
    {
        // Get the format enums and prepare an array of usable placeholders
        $enums = [
            Enum\ColorsEnum::class,
            Enum\ControlsEnum::class,
            Enum\StylesEnum::class
        ];

        foreach ($enums as $enum) {
            foreach ($enum::cases() as $case) {
                $this->formatPlaceholders['{' . $case->name . '}'] = $case->value;
            }
        }
    }

    /**
     * Prepare the text replacing placeholders with formats
     *
     * @param string $text
     * @return string
     */

    public function format(string $text): string
    {
        return strtr($text, $this->formatPlaceholders);
    }

    /**
     * Remove the format from a text
     *
     * @param string $text
     * @return string
     */

    public function removeFormat(string $text): string
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
            $text .= Enum\ControlsEnum::nl->value;
        }

        if (!$raw) {
            $text = $this->format($text);
            $text .= $autoclear ? Enum\StylesEnum::clear->value : null;
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
            "prefix" => Enum\StylesEnum::bold->value . Enum\StylesEnum::underline->value,
            "suffix" => Enum\StylesEnum::clear->value
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

        $mask .= Enum\ControlsEnum::nl->value;

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

    public function prompt(
        string $message,
        bool $obfuscate = false,
        bool $multiple = false,
        string $separator = " "
    ): mixed {
        $value = readline($this->format($message) . " " . ($obfuscate ? Enum\StylesEnum::conceal->value : null));
        printf(Enum\StylesEnum::clear->value);

        return $multiple ? explode($separator, $value) : $value;
    }
}
