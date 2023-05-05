<?php

namespace GSpataro\CLI\Helper;

use GSpataro\CLI\Interface\InputInterface;
use GSpataro\CLI\Interface\OutputInterface;

final class Prompt
{
    /**
     * Initialize Prompt object
     *
     * @param OutputInterface $output
     */

    public function __construct(
        private readonly InputInterface $input,
        private readonly OutputInterface $output
    ) {
    }

    /**
     * Get user input
     *
     * @return mixed
     */

    private function getUserInput(): mixed
    {
        return trim(fgets($this->input->getStandardInput()));
    }

    /**
     * Create a prompt that accepts only one value
     *
     * @param string $message
     * @return mixed
     */

    public function single(string $message): mixed
    {
        $this->output->print($message . ' ', false);
        $input = $this->getUserInput();

        return $input;
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
        $this->output->print($message . ' ', false);
        $input = $this->getUserInput();

        return explode($separator, $input);
    }

    /**
     * Create a prompt with hidden user input
     *
     * @param string $message
     * @return mixed
     */

    public function conceal(string $message): mixed
    {
        $this->output->print($message . ' ', false);

        system('stty -echo');
        $input = $this->getUserInput();
        system('stty echo');

        print(PHP_EOL);

        return trim($input);
    }

    /**
     * Create a prompt that accepts yes/no user input
     *
     * @param string $message
     * @return bool
     */

    public function confirm(string $message): bool
    {
        $this->output->print($message . ' ', false);
        $input = strtolower($this->getUserInput());
        $acceptedAnswers = [
            'yes' => true,
            'y' => true,
            'no' => false,
            'n' => false
        ];

        if (!isset($acceptedAnswers[$input])) {
            return $this->confirm($message);
        }

        return $acceptedAnswers[$input];
    }

    /**
     * Create a prompt that accepts user choice
     *
     * @param string $message
     * @param array $choices
     * @return int|string
     */

    public function choice(string $message, array $choices): int|string
    {
        foreach ($choices as $i => $choice) {
            $this->output->print($i . ' - ' . $choice);
        }

        $this->output->print('{nl}' . $message . ' ', false);

        $input = $this->getUserInput();

        if (!in_array($input, array_keys($choices))) {
            return $this->choice($message, $choices);
        }

        return $input;
    }
}
