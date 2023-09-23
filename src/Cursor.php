<?php

namespace GSpataro\CLI;

use GSpataro\CLI\Interface\OutputInterface;

final class Cursor
{
    /**
     * Initialize Cursor object
     *
     * @param OutputInterface $output
     */

    public function __construct(
        private readonly OutputInterface $output
    ) {
    }

    /**
     * Move cursor up
     *
     * @param int $lines
     * @return static
     */

    public function moveUp(int $lines = 1): static
    {
        $this->output->print("\033[{$lines}A", false, false);

        return $this;
    }

    /**
     * Move cursor right
     *
     * @param int $columns
     * @return static
     */

    public function moveRight(int $columns = 1): static
    {
        $this->output->print("\033[{$columns}C", false, false);

        return $this;
    }

    /**
     * Move cursor down
     *
     * @param int $lines
     * @return static
     */

    public function moveDown(int $lines): static
    {
        $this->output->print("\033[{$lines}B", false, false);

        return $this;
    }

    /**
     * Move cursor left
     *
     * @param int $lines
     * @return static
     */

    public function moveLeft(int $columns = 1): static
    {
        $this->output->print("\033[{$columns}D", false, false);

        return $this;
    }

    /**
     * Move cursor to specific column
     *
     * @param int $column
     * @return static
     */

    public function moveToColumn(int $column): static
    {
        $this->output->print("\033[{$column}G", false, false);

        return $this;
    }

    /**
     * Erase the line at current cursor position
     *
     * @return static
     */

    public function clearLine(): static
    {
        $this->output->print("\033[2K", false, false);

        return $this;
    }

    /**
     * Erase the entire screen
     *
     * @return static
     */

    public function clearScreen(): static
    {
        $this->output->print("\033[2J", false, false);

        return $this;
    }
}
