<?php

namespace GSpataro\CLI;

final class Response
{
    /**
     * Store regular expressions for ANSI text formatting
     *
     * @var array
     */

    private array $ansiRegex = [
        [
            // Bold: *bold*
            "regex" => "/[\*]{1}(.*?)[\*]{1}/",
            "code" => 1
        ],
        [
            // Dim: -dim-
            "regex" => "/[\-]{1}(.*?)[\-]{1}/",
            "code" => 2
        ],
        [
            // Underline: _underline_
            "regex" => "/[\_]{1}(.*?)[\_]{1}/",
            "code" => 4
        ],
        [
            // Color red: !red!
            "regex" => "/[\!]{1}(.*?)[\!]{1}/",
            "code" => 31
        ],
        [
            // Color green: #green#
            "regex" => "/[\#]{1}(.*?)[\#]{1}/",
            "code" => 32
        ]
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

        foreach ($this->ansiRegex as $format) {
            if (!preg_match($format['regex'], $output)) {
                continue;
            }

            $output = preg_replace($format['regex'], "\033[{$format['code']}m$1\033[{$this->normalizerCode}m", $output);
        }

        return $output;
    }

    /**
     * Print text to the console
     *
     * @param string $text
     * @param bool $finalNewLine
     * @param bool $raw
     * @return void
     */

    public function print(string $text, bool $finalNewLine = true, bool $raw = false): void
    {
        if ($finalNewLine) {
            $text .= "\n";
        }

        if (!$raw) {
            $text = $this->format($text);
        }

        printf($text);
    }
}
