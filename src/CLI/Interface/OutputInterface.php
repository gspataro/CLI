<?php

namespace GSpataro\CLI\Interface;

interface OutputInterface
{
    /**
     * Prepare the text replacing placeholders with formats
     *
     * @param string $text
     * @return string
     */

    public function format(string $text): string;

    /**
     * Remove the format from a text
     *
     * @param string $text
     * @return string
     */

    public function removeFormat(string $text): string;

    /**
     * Prepare a text to be printed
     *
     * @param string $text
     * @param bool $finalNewLine
     * @param bool $autoclear
     * @param bool $raw
     * @return string
     */

    public function prepare(string $text, bool $finalNewLine = true, bool $autoclear = true, bool $raw = false): string;

    /**
     * Print text to the console
     *
     * @param string $text
     * @param bool $finalNewLine
     * @param bool $autoclear
     * @param bool $raw
     * @return void
     */

    public function print(string $text, bool $finalNewLine = true, bool $autoclear = true, bool $raw = false): void;
}
