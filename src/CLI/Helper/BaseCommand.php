<?php

namespace GSpataro\CLI\Helper;

use GSpataro\CLI\Interface\InputInterface;
use GSpataro\CLI\Interface\OutputInterface;

abstract class BaseCommand
{
    protected readonly InputInterface $input;
    protected readonly OutputInterface $output;

    public function setIO(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }
}
