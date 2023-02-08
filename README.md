# GSpataro\CLI

A component to create easily extendible and executable CLI scripts.
Includes a class to format text as terminal output in a simplified way.

---

## OptionsCollection

The options collection is the archive of the options that your script will run. Each option is composed of:

- **tag** &rarr; *the name of the option*

- **callback** &rarr; *the function/method the option will execute*

- **args** &rarr; *the user provided parameters that will be passed to the callback*

- **manpage** &rarr; *the informations about the option that will be shown in the help page*

You can register your options one by one:

```php
<?php

$options = new \GSpataro\CLI\OptionsCollection();

/**
 * Add an option to the collection
 *
 * @param string      $tag          The name of your option
 * @param callable    $callback     The callback that will be executed by the option
 * @param array       $args = []    The arguments that the option accepts
 * @param string|null $manpage      The informations that will be shown in the help page
 * @return void
 */

$options->add(
    "login",
    function ($username, $password) {
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
$options->feed([
    "login" => [
        "callback" => function ($username, $password) {
            echo "Logged in as {$username}:{$password}!";
        },
        "args" => ["username", "password"],
        "manpage" => "Use this command to login"
    ]
]);

/**
 * This will generate same output as previous example
 */
```
