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
}
