<?php

namespace GSpataro\CLI\Helper;

use GSpataro\CLI\Enum\StylesEnum;
use GSpataro\CLI\Interface\OutputInterface;

final class Prompt
{
    /**
     * Initialize Prompt object
     *
     * @param OutputInterface $output
     */

    public function __construct(
        private readonly OutputInterface $output
    ) {
    }

    /**
     * Create a prompt that accepts only one value
     *
     * @param string $message
     * @return mixed
     */

    public function single(string $message): mixed
    {
        $value = readline($this->output->format($message) . " ");
        printf(StylesEnum::clear->value); // make sure the text is clear after the prompt

        return $value;
    }

    /**
     * Create a prompt that accepts multiple values
     *
     * @param string $message
     * @param string $separator
     * @return mixed
     */

    public function multiple(string $message, string $separator = ', '): mixed
    {
        $value = readline($this->output->format($message) . " ");
        printf(StylesEnum::clear->value); // make sure the text is clear after the prompt

        return explode($separator, $value);
    }

    /**
     * Create a prompt with hidden user input
     *
     * @param string $message
     * @return mixed
     */

    public function conceal(string $message): mixed
    {
        $value = readline($this->output->format($message) . " " . StylesEnum::conceal->value);
        printf(StylesEnum::clear->value); // make sure the text is clear after the prompt

        return $value;
    }
}
