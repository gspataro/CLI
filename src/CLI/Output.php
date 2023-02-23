<?php

namespace GSpataro\CLI;

final class Output
{
    /**
     * Store regular expressions for ANSI text formatting
     *
     * @var array
     */

    private array $ansiRegex = [
        "c" => 0, // Clear
        "b" => 1, // Bold
        "d" => 2, // Dim
        "i" => 3, // Italic
        "u" => 4, // Underline
        "r" => 31, // Red,
        "g" => 32 // Green
    ];

    /**
     * Store text normalizer code
     *
     * @var int
     */

    private int $normalizerCode = 0;

    /**
     * Prepare the text replacing regular expressions with ANSI codes
     *
     * @param string $text
     * @return string
     */

    private function format(string $text): string
    {
        $output = str_replace("@nl", "\n", $text);

        foreach ($this->ansiRegex as $keyword => $code) {
            $search = "@{$keyword};";

            if (!str_contains($output, $search)) {
                continue;
            }

            $output = str_replace($search, "\033[{$code}m", $output);
        }

        return $output;
    }

    /**
     * Print text to the console
     *
     * @param string $text
     * @param bool $finalNewLine
     * @param bool $autoclear
     * @param bool $raw
     * @return void
     */

    public function print(string $text, bool $finalNewLine = true, bool $autoclear = true, bool $raw = false): void
    {
        if ($finalNewLine) {
            $text .= "\n";
        }

        if (!$raw) {
            $text .= $autoclear ? "@c;" : null;
            $text = $this->format($text);
        }

        printf($text);
    }

    /**
     * Print a table to the console
     *
     * @param array $table
     * @param int $columns
     * @param bool $heading
     * @param int $pad
     * @return void
     */

    public function printTable(array $table, int $columns, bool $heading = true, int $pad = 5): void
    {
        $mask = "";

        for ($i = 0; $i < $columns; $i++) {
            $colSize = max(array_map("strlen", array_column($table, $i)));
            $colWidth = $colSize + $pad;
            $mask .= "%-{$colWidth}.{$colWidth}s";
        }

        $mask .= "\n";

        foreach ($table as $row) {
            call_user_func_array("printf", array_merge((array) $mask, $row));
        }
    }
}
