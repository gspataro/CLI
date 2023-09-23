<?php

namespace GSpataro\CLI;

final class System
{
    /**
     * Get the operating system name
     *
     * @return string
     */

    public static function getOs(): string
    {
        return php_uname('s');
    }

    /**
     * Get the operating system release
     *
     * @return string
     */

    public static function getOsRelease(): string
    {
        return php_uname('r');
    }

    /**
     * Get the operating system version
     *
     * @return string
     */

    public static function getOsVersion(): string
    {
        return php_uname('v');
    }

    /**
     * Get the machine hostname
     *
     * @return string
     */

    public static function getHostname(): string
    {
        return php_uname('n');
    }

    /**
     * Get the machine platform
     *
     * @return string
     */

    public static function getPlatform(): string
    {
        return php_uname('m');
    }
}
