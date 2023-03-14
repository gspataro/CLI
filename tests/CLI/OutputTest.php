<?php

namespace GSpataro\Test;

use GSpataro\CLI;
use PHPUnit\Framework\TestCase;

final class OutputTest extends TestCase
{
    /**
     * Get an instance of Output
     *
     * @return CLI\Output
     */

    public function getResponse(): CLI\Output
    {
        return new CLI\Output();
    }

    /**
     * Get a mockup text
     *
     * @return array
     */

    public function getMockupText(): array
    {
        $normal = "\033[0m";
        $bold = "\033[1m";
        $dim = "\033[2m";
        $underline = "\033[4m";
        $red = "\033[31m";
        $green = "\033[32m";

        return [
            "raw" => "normal {bold}bold{clear} {dim}dim{clear} {underline}underline{clear} {fg_red}red{clear} {fg_green}green{clear}",
            "formatted" =>
                "normal {$bold}bold{$normal} {$dim}dim{$normal} " .
                "{$underline}underline{$normal} {$red}red{$normal} {$green}green{$normal}"
        ];
    }

    /**
     * @testdox Test Output::print() method
     * @covers Output::print
     * @return void
     */

    public function testPrint(): void
    {
        $text = "This is a test!";
        $output = $this->getResponse();

        $this->expectOutputString("{$text}\n\033[0m");
        $output->print($text);
    }

    /**
     * @testdox Test Output::print() without final new line
     * @covers Output::print
     * @return void
     */

    public function testPrintNoNl(): void
    {
        $text = "This is a test!";
        $output = $this->getResponse();

        $this->expectOutputString("{$text}\033[0m");
        $output->print(
            text: $text,
            finalNewLine: false
        );
    }

    /**
     * @testdox Test Output::print() without autoclear
     * @covers Output::print
     * @return void
     */

    public function testPrintNoAutoclear(): void
    {
        $text = "This is a test!";
        $output = $this->getResponse();

        $this->expectOutputString("{$text}\n");
        $output->print(
            text: $text,
            autoclear: false
        );
    }

    /**
     * @testdox Test Output::print() with formatting
     * @covers Output::print
     * @return void
     */

    public function testPrintWithFormatting(): void
    {
        $text = $this->getMockupText();
        $output = $this->getResponse();

        $this->expectOutputString("{$text['formatted']}\n\033[0m");
        $output->print($text['raw']);
    }

    /**
     * @testdox Test Output::print() without formatting
     * @covers Output::print
     * @return void
     */

    public function testPrintWithoutFormatting(): void
    {
        $text = $this->getMockupText();
        $output = $this->getResponse();

        $this->expectOutputString("{$text['raw']}\n");
        $output->print(
            text: $text['raw'],
            raw: true
        );
    }
}
