<?php

namespace GSpataro\CLI\Enum;

/**
 * Escape sequences for colors
 */

enum ColorsEnum: string
{
    // Foreground Colors
    case fg_black = "\033[30m";
    case fg_black_bright = "\033[90m";
    case fg_red = "\033[31m";
    case fg_red_bright = "\033[91m";
    case fg_green = "\033[32m";
    case fg_green_bright = "\033[92m";
    case fg_yellow = "\033[33m";
    case fg_yellow_bright = "\033[93m";
    case fg_blue = "\033[34m";
    case fg_blue_bright = "\033[94m";
    case fg_magenta = "\033[35m";
    case fg_magenta_bright = "\033[95m";
    case fg_cyan = "\033[36m";
    case fg_cyan_bright = "\033[96m";
    case fg_white = "\033[37m";
    case fg_white_bright = "\033[97m";

    // Background colors
    case bg_black = "\033[40m";
    case bg_black_bright = "\033[100m";
    case bg_red = "\033[41m";
    case bg_red_bright = "\033[101m";
    case bg_green = "\033[42m";
    case bg_green_bright = "\033[102m";
    case bg_yellow = "\033[43m";
    case bg_yellow_bright = "\033[103m";
    case bg_blue = "\033[44m";
    case bg_blue_bright = "\033[104m";
    case bg_magenta = "\033[45m";
    case bg_magenta_bright = "\033[105m";
    case bg_cyan = "\033[46m";
    case bg_cyan_bright = "\033[106m";
    case bg_white = "\033[47m";
    case bg_white_bright = "\033[107m";
}
