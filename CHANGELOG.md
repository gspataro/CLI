# CHANGELOG

## 2.0.1 - 10/05/2023

### Changed

- Removed readonly from Command::$options property

## 2.0.0 - 08/05/2023


> **IMPORTANT:** This update contains breaking changes.

This list of changes is an excerpt all of the features. This component went through a major restructuring.

### Added

- Helpers

  - Manpage

    - Localization

    - Customization

  - Prompt

   - Confirmation prompt with `confirm()`

   - Choice prompt with `choice()`

  - Table

    - Styles

  - Stopwatch

- Handler

  - `setManpage()` method

  - `setHeader()` method

- Interfaces for Input and Output

- Input

  - `getArgs()` method

  - `getStandardInput()` method

- Functions

### Changed

- Interaction between Handler/Input/Output

- Handler

- CommandsCollection

- Command

- Enums
