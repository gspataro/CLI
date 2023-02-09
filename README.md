# GSpataro\CLI

A component to create easily extendible and executable CLI scripts.
Includes a class to format text as terminal output in a simplified way.

## Quick Start

Here is what you need to start a new CLI script:

```php

```

## OptionsCollection

The options collection is the archive of the options that your script will run. Each option is composed of:

- **tag** &rarr; *the name of the option*

- **callback** &rarr; *the function/method the option will execute*

- **args** &rarr; *the user provided parameters that will be passed to the callback*

- **manpage** &rarr; *the informations about the option that will be shown in the help page*

### Registering options

You can register your options one by one:

```php
<?php

use GSpataro\CLI\OptionsCollection;
use GSpataro\CLI\Input;
use GSpataro\CLI\Output;

$options = new OptionsCollection();

/**
 * Add an option to the collection
 *
 * @param string      $tag       The name of your option
 * @param callable    $callback  The callback that will be executed by the option
 * @param array       $args = [] The arguments that the option accepts
 * @param string|null $manpage   The informations that will be shown in the help page
 * @return void
 */

$options->add(
    "login",
    function (Input $input, Output $output, array $args) {
        echo "Logged in as {$args['username']}:{$args['password']}!";
    },
    ["username", "password"],
    "Use this command to login"
);

/**
 * Imagine the name of your script is 'script.php'
 *
 * User input    -> php script.php login johndoe securepassword
 * Script output -> Logged in as johndoe:securepassword!
 */
```

Or feed them with an array:

```php
$options->feed([
    "login" => [
        "callback" => function (Input $input, Output $output, array $args) {
            echo "Logged in as {$args['username']}:{$args['password']}!";
        },
        "args" => ["username", "password"],
        "manpage" => "Use this command to login"
    ]
]);

/**
 * This will generate same output as previous example
 */
```

### Options arguments

The arguments of an option can have some additional informations:

- **required** &rarr; *the user must provide the argument to execute the command*
  default: false

- **manpage** &rarr; *the informations about the argument that will be shown in the help page*
  default: null

To provide the additional informations you can define the option like this:

```php
$options->feed([
    "login" => [
        "callback" => function (Input $input, Output $output, array $args) {
            echo "Logged in as {$args['username']}:{$args['password']}!";
        },
        "args" => [
            "username" => [
                "required" => true,
                "manpage" => "Your username"
            ],
            "password" => [
                "required" => true,
                "manpage" => "Your password"
            ]
        ],
        "manpage" => "Use this command to login"
    ]
]);
```

## Input

The Input class stores user input informations and can be accessed by the callbacks of the options.

```php
<?php

use GSpataro\CLI\Input;

$input = new Input($argv);

/**
 * Retrieves the name of the script
 * 
 * If the name of the file is 'script.php' it will retrieve script.php
 */

$input->getScriptName();

/**
 * Retrieves the option requested by the user
 * 
 * Input: script.php login
 * Output: login
 */

$input->getOptionName();

/**
 * Retrieves the arguments provided by the user
 * 
 * Input: script.php login username password
 * Output: [username, password]
 */

$input->getArgs();
```

## Output

The Output class helps you managing the output that will be given to the user.

```php
<?php

use GSpataro\CLI\Output;

$output = new Output();

/**
 * Print a message to the user
 * 
 * @param string $text                 The actual message
 * @param bool   $finalNewLine = true  Append a new line
 * @param bool   $raw = false          Print a message without formatting it
 */

$output->print("Lorem ipsum");
```

If you want you can use a special syntax to easily format the text you want to output:

```php
/**
 * Text between * will be bold
 */

$output->print("Lorem *ipsum* dolor");

/**
 * Text between - will be dim
 */

$output->print("Lorem -ipsum- dolor");

/**
 * Text between _ will be underlined
 */

$output->print("Lorem _ipsum_ dolor");

/**
 * Text between ! will be red
 */

$output->print("Lorem !ipsum! dolor");

/**
 * Text between # will be green
 */

$output->print("Lorem #ipsum# dolor");
```

You can also add new lines with a simple tag:

```php
/**
 * Text after @nl will be in a new line
 */

$output->print("Lorem ipsum @nl dolor sit amet");
```
