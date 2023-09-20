<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Store output buffering status
     *
     * @var bool
     */

    private bool $outputBufferActive = false;

    /**
     * Store output buffering level
     *
     * @var int
     */

    private int $outputBufferLevel;

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
}
