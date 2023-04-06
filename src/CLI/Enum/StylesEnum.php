<?php

namespace GSpataro\CLI\Enum;

/**
 * Escape sequences for text styles and weights
 */

enum StylesEnum: string
{
    case clear = "\033[0m";
    case bold = "\033[1m";
    case dim = "\033[2m";
    case italic = "\033[3m";
    case underline = "\033[4m";
    case reverse = "\033[7m";
    case conceal = "\033[8m";
    case strike = "\033[9m";
    case double_underline = "\033[21m";
}
