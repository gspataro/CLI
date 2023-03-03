<?php

namespace GSpataro\CLI;

enum OutputFormatEnum: string
{
    // Line breaks
    case nl = "\n";

    // Styles
    case clear = "\033[0m";
    case bold = "\033[1m";
    case dim = "\033[2m";
    case italic = "\033[3m";
    case underline = "\033[4m";

    // Colors
    case red = "\033[31m";
    case green = "\033[32m";

    /**
     * Get cases as array
     *
     * @return array
     */

    public static function toArray(): array
    {
        $array = [];

        foreach (self::cases() as $case) {
            $array[$case->name] = $case->value;
        }

        return $array;
    }
}
