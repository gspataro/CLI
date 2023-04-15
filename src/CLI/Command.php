<?php

namespace GSpataro\CLI;

use GSpataro\CLI\Interface\InputInterface;
use GSpataro\CLI\Interface\OutputInterface;

abstract class Command
{
    protected readonly InputInterface $input;
    protected readonly OutputInterface $output;

    public function setIO(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }
}
