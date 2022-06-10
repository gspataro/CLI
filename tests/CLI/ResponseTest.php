<?php

namespace GSpataro\Test;

use GSpataro\CLI;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    /**
     * Get an instance of Response
     *
     * @return CLI\Response
     */

    public function getResponse(): CLI\Response
    {
        return new CLI\Response();
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
            "raw" => "normal *bold* -dim- _underline_ !red! #green#",
            "formatted" =>
                "normal {$bold}bold{$normal} {$dim}dim{$normal} " .
                "{$underline}underline{$normal} {$red}red{$normal} {$green}green{$normal}"
        ];
    }

    /**
     * @testdox Test Response::print() method
     * @covers Response::print
     * @return void
     */

    public function testPrint(): void
    {
        $text = "This is a test!";
        $response = $this->getResponse();

        $this->expectOutputString("{$text}\n");
        $response->print($text);
    }

    /**
     * @testdox Test Response::print() without final new line
     * @covers Response::print
     * @return void
     */

    public function testPrintNoNl(): void
    {
        $text = "This is a test!";
        $response = $this->getResponse();

        $this->expectOutputString($text);
        $response->print(
            text: $text,
            finalNewLine: false
        );
    }

    /**
     * @testdox Test Response::print() with formatting
     * @covers Response::print
     * @return void
     */

    public function testPrintWithFormatting(): void
    {
        $text = $this->getMockupText();
        $response = $this->getResponse();

        $this->expectOutputString("{$text['formatted']}\n");
        $response->print($text['raw']);
    }

    /**
     * @testdox Test Response::print() without formatting
     * @covers Response::print
     * @return void
     */

    public function testPrintWithoutFormatting(): void
    {
        $text = $this->getMockupText();
        $response = $this->getResponse();

        $this->expectOutputString("{$text['raw']}\n");
        $response->print(
            text: $text['raw'],
            raw: true
        );
    }
}
