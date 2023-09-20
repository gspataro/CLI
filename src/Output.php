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
     * Store output stream
     *
     * @var resource
     */

    private readonly mixed $outputStream;

    /**
     * Initialize Output object
     *
     * @param mixed $resource
     */

    public function __construct(
        mixed $resource = null
    ) {
        // Open and store output stream
        if (is_null($resource)) {
            $this->outputStream = fopen('php://output', 'w');
        } elseif (is_resource($resource)) {
            $this->outputStream = $resource;
        } else {
            throw new Exception\InvalidResourceException("Invalid resource provided as output stream.");
        }

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
            '',
            $text
        );
    }

    /**
     * Prepare a text to be printed
     *
     * @param string $text
     * @param bool $finalNewLine
     * @param bool $autoclear
     * @param bool $raw
     * @return string
     */

    public function prepare(string $text, bool $finalNewLine = true, bool $autoclear = true, bool $raw = false): string
    {
        if ($autoclear) {
            $text .= '{clear}';
        }

        if ($finalNewLine) {
            $text .= '{nl}';
        }

        if (!$raw) {
            $text = $this->format($text);
        }

        return $text;
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
        $text = $this->prepare($text, $finalNewLine, $autoclear, $raw);
        fwrite($this->outputStream, $text);
    }
}
