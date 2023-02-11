<?php

namespace GSpataro\Test;

use GSpataro\CLI;
use PHPUnit\Framework\TestCase;

final class InputTest extends TestCase
{
    /**
     * @testdox Test object initialization
     * @return void
     */

    public function testInitialization(): void
    {
        $input = new CLI\Input([
            "index.php",
            "commandName",
            "argumentOne",
            "argumentTwo"
        ]);

        $this->assertEquals("index.php", $input->getScriptName());
        $this->assertEquals("commandName", $input->getCommandName());
        $this->assertEquals(["argumentOne", "argumentTwo"], $input->getArgs());
    }
}
