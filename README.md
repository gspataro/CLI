# GSpataro\CLI

A component to create easily extendible and executable CLI scripts.
Includes a class to format text as terminal output in a simplified way.

## Quick Start

Here is what you need to start a new CLI script:

```php
<?php

use GSpataro\CLI\Handler;
use GSpataro\CLI\CommandsCollection;
use GSpataro\CLI\Input;
use GSpataro\CLI\Output;

$commands = new CommandsCollection();
$input = new Input();
$output = new Output();

$commands->add("helloworld", function (Input $input, Output $output) {
    $output->print("Hello world!");
}, []);

$cli = new Handler($commands, $input, $output);
$cli->deploy();

```

## CommandsCollection

The commands collection is the archive of the commands that your script will run. Each command is composed of:

- **command** &rarr; *the name of the command*

- **callback** &rarr; *the function/method the option will execute*

- **options** &rarr; *the options that the command accepts*

- **description** &rarr; *the informations about the option that will be shown by the help command*

### Registering commands

You can register your commands one by one:

```php
<?php

use GSpataro\CLI\CommandsCollection;
use GSpataro\CLI\Input;
use GSpataro\CLI\Output;

$commands = new CommandsCollection();

/**
 * Add a command to the collection
 *
 * @param string      $command      The name of your command
 * @param callable    $callback     The callback that will be executed by the command
 * @param array       $options = [] The options that the command accepts
 * @param string|null $description  The informations that will be shown by the help command
 * @return void
 */

$commands->add(
    "login",
    function (Input $input, Output $output, mixed $username, mixed $password) {
        echo "Logged in as {$username}:{$password}!";
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
$commands->feed([
    "login" => [
        "callback" => function (Input $input, Output $output, mixed $username, mixed $password) {
            echo "Logged in as {$username}:{$password}!";
        },
        "options" => ["username", "password"],
        "description" => "Use this command to login"
    ]
]);

/**
 * This will generate same output as previous example
 */
```

### Command options

The options of a command can have some additional informations:

- **short** &rarr; *the short name of the option*

- **type** &rarr; *the type of option: required, optional or novalue*
  default: optional

- **description** &rarr; *the informations about the argument that will be shown by the help command*
  default: null

To provide the additional informations you can define the option like this:

```php
[
    // Input: --requiredOption="value" or -r "value"
    // Output: "value"
    "requiredOption" => [
        "short" => "r",
        "type" => "required",
        "description" => "Required value"
    ],

    // Input: --optionalOption="value", -o "value", --optionalOption or -o
    // Output: "value" or false
    "optionalOption" => [
        "short" => "o",
        "type" => "optional",
        "description" => "Optional value"
    ],

    // Input: --novalueOption, -n
    // Output: false or null
    "novalueOption" => [
        "short" => "n",
        "type" => "novalue",
        "description" => "Novalue option"
    ]
]
```

## Input

The Input class stores user input informations that can be accessed by the commands.

```php
<?php

use GSpataro\CLI\Input;

/**
 * The constructor of Input accepts an array of arguments
 * If the array is not provided, $_SERVER['argv'] will be used as default
 */

$input = new Input();

/**
 * Retrieves the name of the script
 * If the name of the file is 'script.php' it will retrieve script.php
 */

$input->getScriptName();

/**
 * Retrieves the command requested by the user
 *
 * Input: script.php login
 * Output: login
 */

$input->getCommandName();

/**
 * Retrieves the arguments provided by the user
 *
 * Input: script.php login --username="foo" --password="bar"
 * Output: [username => foo, password => bar]
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
 * @param bool   $autoclear = true     Automatically clear formatting
 * @param bool   $raw = false          Print a message without formatting it
 */

$output->print("Lorem ipsum");

/**
 * Output: Lorem ipsum
 */
```

### Prompt

The Output class provides a method to prompt the user to enter a value during the execution of the script:

```php
<?php

use GSpataro\CLI\Output;

$output = new Output();

/**
 * Prompt user to enter a value
 * 
 * @param string $message            The message of the prompt
 * @param bool   $obfuscate = false  Hide the user input
 * @param bool   $multiple = false   If true, accept multiple input divided by a separator
 * @param string $separator = " "   The separator to provide multiple inputs
 */

$output->prompt("Lorem ipsum"); // Will print "Lorem ipsum:"
```

### Tables

Another useful method is the printTable method which will print some data in an ordered way. The table will adapt the width of the columns based on the longest column.

```php
<?php

use GSpataro\CLI\Output;

$output = new Output();

/**
 * Print a table
 * 
 * @param array $structure   The actual content of the table
 * @param int $columnsNumber The number of columns
 * @param int $pad = 5       The distance between columns
 * @param array $styles = [] The styles to apply to the rows
 */

$output->printTable([
    ["heading" => ["Name", "Surname", "City"]],
    ["row" => ["Wolfgang Amadeus", "Mozart", "Vienna"]],
    ["row" => ["Ludwig", "van Beethoven", "Bonn"]],
    ["row" => ["Sergej Vasil'Evic", "Rachmaninoff", "Moscow"]],
    ["row" => ["Vincenzo", "Bellini", "Catania"]]
], 3);
```

You can provide some custom styles to the table by overriding the defaults (heading and row) or by providing some new ones:

```php

$output->printTable(
    structure: [
        ["heading" => ["Command Name", "Command Description"]],
        ["row" => ["ls", "List the content of a directory"]],
        ["row" => ["cd", "Enters a directory"]],
        [], // empty rows will be translated to empty lines as separators
        ["custom" => ["test", "This is a test of a new style"]]
    ],
    columnsNumber: 2,
    styles: [
        "heading" => [
            "prefix" => EscapeCodesEnum::fg_red->value . EscapeCodesEnum::italic->value,
            "suffix" => EscapeCodesEnum::clear->value
        ],
        "row" => [
            "prefix" => EscapeCodesEnum::dim->value,
            "suffix" => EscapeCodesEnum::clear->value
        ],
        "custom" => [
            "prefix" => EscapeCodesEnum::fg_green->value,
            "suffix" => EscapeCodesEnum::clear->value
        ]
    ]
);
```

### Output format

If you want you can use wildcards to easily format the text you want to output. Here are some example:

```php
/**
 * Text between {bold} an {clear} will be bold
 */

$output->print("Lorem {bold}ipsum{clear} dolor");

/**
 * Text between {fg_red} and {clear} will be red
 */

$output->print("Lorem {fg_red}ipsum{clear} dolor");

/**
 * Text between {bg_green} and {clear} will have green background
 */

$output->print("Lorem {bg_green}ipsum{clear} dolor");
```

Here is a table with all available wildcards:

<table>
    <tr>
        <th>Wildcard</th>
        <th>Escape Sequence</th>
        <th>Description</th>
    </tr>
    <tr>
        <td>nl</td>
        <td>\n</td>
        <td>New line</td>
    </tr>
    <tr>
        <td>clear</td>
        <td>\033[0m</td>
        <td>Normal text</td>
    </tr>
    <tr>
        <td>bold</td>
        <td>\033[1m</td>
        <td>Bold text</td>
    </tr>
    <tr>
        <td>dim</td>
        <td>\033[2m</td>
        <td>Dim text</td>
    </tr>
    <tr>
        <td>italic</td>
        <td>\033[3m</td>
        <td>Italic text</td>
    </tr>
    <tr>
        <td>underline</td>
        <td>\033[4m</td>
        <td>Underlined text</td>
    </tr>
    <tr>
        <td>reverse</td>
        <td>\033[7m</td>
        <td>Reverse background/foreground color</td>
    </tr>
    <tr>
        <td>conceal</td>
        <td>\033[8m</td>
        <td>Hidden text</td>
    </tr>
    <tr>
        <td>strike</td>
        <td>\033[9m</td>
        <td>Text with stroke</td>
    </tr>
    <tr>
        <td>double_underline</td>
        <td>\033[21m</td>
        <td>Text with double underline</td>
    </tr>
    <tr>
        <td>fg_black</td>
        <td>\033[30m</td>
        <td>Black text</td>
    </tr>
    <tr>
        <td>fg_black_bright</td>
        <td>\033[90m</td>
        <td>Bright black text</td>
    </tr>
    <tr>
        <td>fg_red</td>
        <td>\033[31m</td>
        <td>Red text</td>
    </tr>
    <tr>
        <td>fg_red_bright</td>
        <td>\033[91m</td>
        <td>Bright red text</td>
    </tr>
    <tr>
        <td>fg_green</td>
        <td>\033[32m</td>
        <td>Green text</td>
    </tr>
    <tr>
        <td>fg_green_bright</td>
        <td>\033[92m</td>
        <td>Bright green text</td>
    </tr>
    <tr>
        <td>fg_yellow</td>
        <td>\033[33m</td>
        <td>Yellow text</td>
    </tr>
    <tr>
        <td>fg_yellow_bright</td>
        <td>\033[93m</td>
        <td>Bright yellow text</td>
    </tr>
    <tr>
        <td>fg_blue</td>
        <td>\033[34m</td>
        <td>Blue text</td>
    </tr>
    <tr>
        <td>fg_blue_bright</td>
        <td>\033[94m</td>
        <td>Bright blue text</td>
    </tr>
    <tr>
        <td>fg_magenta</td>
        <td>\033[35m</td>
        <td>Magenta text</td>
    </tr>
    <tr>
        <td>fg_magenta_bright</td>
        <td>\033[95m</td>
        <td>Bright magenta text</td>
    </tr>
    <tr>
        <td>fg_cyan</td>
        <td>\033[36m</td>
        <td>Cyan text</td>
    </tr>
    <tr>
        <td>fg_cyan_bright</td>
        <td>\033[96m</td>
        <td>Bright cyan text</td>
    </tr>
    <tr>
        <td>fg_white</td>
        <td>\033[37m</td>
        <td>White text</td>
    </tr>
    <tr>
        <td>fg_white_bright</td>
        <td>\033[97m</td>
        <td>Bright white text</td>
    </tr>
    <tr>
        <td>bg_black</td>
        <td>\033[40m</td>
        <td>Black background</td>
    </tr>
    <tr>
        <td>bg_black_bright</td>
        <td>\033[100m</td>
        <td>Bright black background</td>
    </tr>
    <tr>
        <td>bg_red</td>
        <td>\033[41m</td>
        <td>Red background</td>
    </tr>
    <tr>
        <td>bg_red_bright</td>
        <td>\033[101m</td>
        <td>Bright red background</td>
    </tr>
    <tr>
        <td>bg_green</td>
        <td>\033[42m</td>
        <td>Green background</td>
    </tr>
    <tr>
        <td>bg_green_bright</td>
        <td>\033[102m</td>
        <td>Bright green background</td>
    </tr>
    <tr>
        <td>bg_yellow</td>
        <td>\033[43m</td>
        <td>Yellow background</td>
    </tr>
    <tr>
        <td>bg_yellow_bright</td>
        <td>\033[103m</td>
        <td>Bright yellow background</td>
    </tr>
    <tr>
        <td>bg_blue</td>
        <td>\033[44m</td>
        <td>Blue background</td>
    </tr>
    <tr>
        <td>bg_blue_bright</td>
        <td>\033[104m</td>
        <td>Bright blue background</td>
    </tr>
    <tr>
        <td>bg_magenta</td>
        <td>\033[45m</td>
        <td>Magenta background</td>
    </tr>
    <tr>
        <td>bg_magenta_bright</td>
        <td>\033[105m</td>
        <td>Bright magenta background</td>
    </tr>
    <tr>
        <td>bg_cyan</td>
        <td>\033[46m</td>
        <td>Cyan background</td>
    </tr>
    <tr>
        <td>bg_cyan_bright</td>
        <td>\033[106m</td>
        <td>Bright cyan background</td>
    </tr>
    <tr>
        <td>bg_white</td>
        <td>\033[47m</td>
        <td>White background</td>
    </tr>
    <tr>
        <td>bg_white_bright</td>
        <td>\033[107m</td>
        <td>Bright white background</td>
    </tr>
</table>
