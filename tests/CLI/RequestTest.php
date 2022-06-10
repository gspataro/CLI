<?php

namespace GSpataro\Test;

use GSpataro\CLI;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
    /**
     * @testdox Test object initialization
     * @return void
     */

    public function testInitialization(): void
    {
        $request = new CLI\Request([
            "index.php",
            "optionName",
            "argumentOne",
            "argumentTwo"
        ]);

        $this->assertEquals("index.php", $request->getScriptName());
        $this->assertEquals("optionName", $request->getOptionName());
        $this->assertEquals(["argumentOne", "argumentTwo"], $request->getArgs());
    }
}
