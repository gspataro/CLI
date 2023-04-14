<?php

namespace GSpataro\CLI\Enum;

/**
 * Escape sequences for control characters
 */

enum ControlsEnum: string
{
    case nl = PHP_EOL;
    case tab = "\t";
}
