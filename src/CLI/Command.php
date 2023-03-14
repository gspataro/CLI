<?php

namespace GSpataro\CLI;

abstract class Command
{
    protected readonly Input $input;
    protected readonly Output $output;

    public function setIO(Input $input, Output $output)
    {
        $this->input = $input;
        $this->output = $output;
    }
}
