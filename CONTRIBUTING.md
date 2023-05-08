# Contributing

If you want to help me updating this component you're welcome. If you want to submit any pull request please make sure to follow this guidelines.

## Guidelines

- Please follow the [PSR-12](https://www.php-fig.org/psr/psr-12/) code style
- Ensure that all the current tests pass and consider writing new ones (where relevant) if you add some new features
- Please follow the [Conventional Commits](https://www.conventionalcommits.org) specification
- Remember to update the documentation when changing something behavior/API related

## Conventional Commits

Remember the structure:

```
<type>(optional scope): <description>

example:

feat(Output): add something to handle output
```

### Types

- **feat** &rarr; introduces new features
- **update** &rarr; updates an existing feature
- **fix** &rarr; patches a bug
- **refactor** &rarr; remake a piece of code
- **chore** &rarr; make a change to code comments or anything that doesn't make a difference in terms of code
- **test** &rarr; any change in tests

### Other Informations

When adding a breaking change please consider adding a '!' before the type.
