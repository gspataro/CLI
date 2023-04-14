<?php

use GSpataro\CLI\Output;
use GSpataro\CLI\Helper\Prompt;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$output = new Output();
$prompt = new Prompt($output);

// Test single prompt

$singlePromptValue = $prompt->single('Single prompt');
$output->print("Single prompt value: " . $singlePromptValue);

// Test multiple prompt

$multiplePromptValue = $prompt->multiple('Multiple prompt');
$output->print("Multiple prompt value: " . implode(" | ", $multiplePromptValue));

// Test conceal prompt

$concealPromptValue = $prompt->conceal('Conceal prompt');
$output->print("Conceal prompt value: " . $concealPromptValue);
