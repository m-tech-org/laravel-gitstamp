# Contributing

Thanks for considering contributing to `laravel-gitstamp`! This package is open source and
contributions are welcome.

## Reporting issues

Open an issue on [GitHub](https://github.com/m-tech-org/laravel-gitstamp/issues) with a clear
description, the Laravel/PHP versions involved, and steps to reproduce if you're reporting a bug.

## Pull requests

1. Fork the repository and create a branch off `develop` (`git checkout -b feature/my-change`).
2. Install dependencies: `composer install`.
3. Make your change, with tests covering the new behavior.
4. Before opening a PR, make sure the following are all green:
   ```bash
   composer test       # Pest test suite
   composer format      # Pint code style
   composer analyse      # PHPStan / Larastan static analysis
   ```
5. Update `CHANGELOG.md` under an "Unreleased" heading describing your change.
6. Open a pull request against `develop`, describing what changed and why.

## Coding standards

- Code style is enforced by [Laravel Pint](https://github.com/laravel/pint) using the `laravel`
  preset — run `composer format` to auto-fix before committing.
- Static analysis is enforced by [Larastan](https://github.com/larastan/larastan) — run
  `composer analyse`.
- New behavior should come with [Pest](https://pestphp.com/) tests under `tests/Feature`.

## Scope

Keep in mind this package's non-goals (see [ABOUT.md](ABOUT.md)): it isn't a general
release-management tool, and it doesn't dictate how a consuming app renders the version string
beyond the helper, facade, and Blade component it already ships. PRs that grow the package well
outside that scope are more likely to need discussion first — feel free to open an issue before
investing in a larger change.
