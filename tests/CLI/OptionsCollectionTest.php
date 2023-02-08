<?php

namespace GSpataro\Test;

use GSpataro\CLI;
use GSpataro\Test\Utilities\Controller;
use PHPUnit\Framework\TestCase;

final class OptionsCollectionTest extends TestCase
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
     * @testdox Test OptionsCollection::has() method
     * @covers OptionsCollection::has
     * @return void
     */

    public function testHas(): void
    {
        $collection = new CLI\OptionsCollection();
        $this->assertFalse($collection->has("nonexisting"));
    }

    /**
     * @testdox Test OptionsCollection::add() method
     * @covers OptionsCollection::add
     * @return void
     */

    public function testAdd(): void
    {
        $collection = new CLI\OptionsCollection();

        $this->assertFalse($collection->has("test"));
        $collection->add("test", [Controller::class, "method"], []);
        $this->assertTrue($collection->has("test"));

        $options = $this->readPrivateProperty($collection, "options");
        $this->assertEquals($options['test'], [
            "callback" => [Controller::class, "method"],
            "args" => [],
            "manpage" => null
        ]);
    }

    /**
     * @testdox Test OptionsCollection::add() method with existing option
     * @covers OptionsCollection::add
     * @return void
     */

    public function testAddExisting(): void
    {
        $this->expectException(CLI\Exception\OptionFoundException::class);

        $collection = new CLI\OptionsCollection();
        $collection->add("test", [Controller::class, "method"], []);
        $collection->add("test", [Controller::class, "method"], []);
    }

    /**
     * @testdox Test OptionsCollection::add() method with invalid callback
     * @covers OptionsCollection::add
     * @return void
     */

    public function testAddInvalidCallback(): void
    {
        $this->expectException(CLI\Exception\InvalidOptionCallbackException::class);

        $collection = new CLI\OptionsCollection();
        $this->assertFalse($collection->has("test"));
        $collection->add("test", [], []);
    }

    /**
     * @testdox Test OptionsCollection::feed() method with incomplete definition
     * @covers OptionsCollection::feed
     * @return void
     */

    public function testFeedIncompleteDefinition(): void
    {
        $this->expectException(CLI\Exception\IncompleteOptionParamsException::class);

        $collection = new CLI\OptionsCollection();
        $collection->feed([
            "test" => []
        ]);
    }

    /**
     * @testdox Test OptionsCollection::get() method
     * @covers OptionsCollection::get
     * @return void
     */

    public function testGet(): void
    {
        $collection = new CLI\OptionsCollection();
        $this->setPrivateProperty($collection, "options", [
            "test" => []
        ]);

        $this->assertTrue($collection->has("test"));
        $this->assertEquals($collection->get("test"), []);
    }

    /**
     * @testdox Test OptionsCollection::get() method with non existing option
     * @covers OptionsCollection::get
     * @return void
     */

    public function testGetNonExisting(): void
    {
        $this->expectException(CLI\Exception\OptionNotFoundException::class);

        $collection = new CLI\OptionsCollection();
        $collection->get("nonexisting");
    }
}
