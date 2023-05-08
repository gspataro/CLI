# GSpataro\CLI

A component to create easily extendible and executable CLI scripts.
Includes a class to format text as terminal output in a simplified way.

## Installation

> requires **PHP ^8.2**

Require GSpataro/CLI via composer:

```
composer require gspataro/cli
```

## Quick Start

```php
use GSpataro\CLI;

// Initialize the commands collection
$commands = new CLI\CommandsCollection();

// Create a command called 'hi' that will print 'hello world' to the console
$commands->create('hi')
    ->setCallback(fn(CLI\Input $input, CLI\Output $output) => $output->print('Hello world!'));

// Initialize the component by providing a collection of commands
$handler = new CLI\Handler($commands);
$handler->deploy();
```

## Contribute

If you want to contribute to this repository please follow the guidelines here: [CONTRIBUTING.md](CONTRIBUTING.md)

Consider also following me on twitter: [@gspataro96](https://twitter.com/gspataro96)

## Changelog

To see all the latest changes see the [CHANGELOG](CHANGELOG.md).

## License

The GSpataro/CLI component is licensed under the [MIT License](LICENSE.md).
