<?php

if (!function_exists('row')) {
    /**
     * Returns a table row
     *
     * @param array $cols
     * @param ?string $style
     * @return array
     */

    function row(array $cols, ?string $style = null): array
    {
        return [$style => $cols];
    }

    /**
     * Returns a table column
     *
     * @param string $value
     * @param ?string $style
     * @return array
     */

    function col(string $value, ?string $style): array
    {
        return [$style => $value];
    }
}
