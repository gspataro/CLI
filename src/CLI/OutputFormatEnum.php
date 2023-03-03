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
    case reverse = "\033[7m";
    case conceal = "\033[8m";
    case strike = "\033[9m";
    case double_underline = "\033[21m";

    // Colors
    case gray = "\033[30m";
    case red = "\033[31m";
    case green = "\033[32m";
    case yellow = "\033[33m";
    case blue = "\033[34m";
    case violet = "\033[35m";
    case lightblue = "\033[36m";
    case white = "\033[37m";

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
