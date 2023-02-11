<?php

namespace GSpataro\Test;

use GSpataro\CLI;
use GSpataro\Test\Utilities\Controller;
use PHPUnit\Framework\TestCase;

final class CommandsCollectionTest extends TestCase
{
    /**
     * Read the content of a private property
     *
     * @param object $object
     * @param string $property
     * @return mixed
     */

    public function readPrivateProperty(object $object, string $property): mixed
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Set the content of a private property
     *
     * @param object $object
     * @param string $property
     * @param mixed $value
     * @return void
     */

    public function setPrivateProperty(object $object, string $property, mixed $value): void
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }

    /**
     * @testdox Test CommandsCollection::has() method
     * @covers CommandsCollection::has
     * @return void
     */

    public function testHas(): void
    {
        $collection = new CLI\CommandsCollection();
        $this->assertFalse($collection->has("nonexisting"));
    }

    /**
     * @testdox Test CommandsCollection::add() method
     * @covers CommandsCollection::add
     * @return void
     */

    public function testAdd(): void
    {
        $collection = new CLI\CommandsCollection();

        $this->assertFalse($collection->has("test"));
        $collection->add("test", [new Controller(), "method"], []);
        $this->assertTrue($collection->has("test"));

        $commands = $this->readPrivateProperty($collection, "commands");
        $this->assertEquals($commands['test'], [
            "callback" => [new Controller(), "method"],
            "args" => [],
            "manpage" => null
        ]);
    }

    /**
     * @testdox Test CommandsCollection::add() method with existing command
     * @covers CommandsCollection::add
     * @return void
     */

    public function testAddExisting(): void
    {
        $this->expectException(CLI\Exception\CommandFoundException::class);

        $collection = new CLI\CommandsCollection();
        $collection->add("test", [new Controller(), "method"], []);
        $collection->add("test", [new Controller(), "method"], []);
    }

    /**
     * @testdox Test CommandsCollection::feed() method with incomplete definition
     * @covers CommandsCollection::feed
     * @return void
     */

    public function testFeedIncompleteDefinition(): void
    {
        $this->expectException(CLI\Exception\IncompleteCommandParamsException::class);

        $collection = new CLI\CommandsCollection();
        $collection->feed([
            "test" => []
        ]);
    }

    /**
     * @testdox Test CommandsCollection::get() method
     * @covers CommandsCollection::get
     * @return void
     */

    public function testGet(): void
    {
        $collection = new CLI\CommandsCollection();
        $this->setPrivateProperty($collection, "commands", [
            "test" => []
        ]);

        $this->assertTrue($collection->has("test"));
        $this->assertEquals($collection->get("test"), []);
    }

    /**
     * @testdox Test CommandsCollection::get() method with non existing command
     * @covers CommandsCollection::get
     * @return void
     */

    public function testGetNonExisting(): void
    {
        $this->expectException(CLI\Exception\CommandNotFoundException::class);

        $collection = new CLI\CommandsCollection();
        $collection->get("nonexisting");
    }
}
