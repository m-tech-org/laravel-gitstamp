# laravel-gitstamp

[![run-tests](https://github.com/m-tech-org/laravel-gitstamp/actions/workflows/run-tests.yml/badge.svg)](https://github.com/m-tech-org/laravel-gitstamp/actions/workflows/run-tests.yml)
[![code-quality](https://github.com/m-tech-org/laravel-gitstamp/actions/workflows/code-quality.yml/badge.svg)](https://github.com/m-tech-org/laravel-gitstamp/actions/workflows/code-quality.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)

Know what's actually running in production, without SSHing in and running `git log`.

`laravel-gitstamp` generates a version stamp (date + short git SHA, e.g. `2026.07.18-6601bf7`)
once at deploy time and gives you a helper, a facade, and a Blade component to display it anywhere in your app — no git
tags, no manual version bumping, no shelling out to git on every request.

See [ABOUT.md](ABOUT.md) for the problem this solves and the reasoning behind the design.

## Requirements

| laravel-gitstamp | PHP  | Laravel    |
|------------------|------|------------|
| ^1.0             | ^8.1 | 9.x – 12.x |

Laravel 10–12 are covered by CI. Laravel 9 is supported at runtime (nothing in this package's code is version-specific)
but isn't covered by automated CI — Pest has never shipped a release compatible with the PHPUnit version Laravel 9's
test tooling pins to. See
[CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Installation

This package isn't published on Packagist. Install it via Composer's VCS repository support:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:m-tech-org/laravel-gitstamp.git"
        }
    ],
    "require": {
        "m-tech-org/laravel-gitstamp": "^1.0"
    }
}
```

```bash
composer require m-tech-org/laravel-gitstamp
```

The package auto-registers its service provider and `Gitstamp` facade via Laravel package discovery — no manual
registration needed.

Optionally publish the config file:

```bash
php artisan vendor:publish --tag=gitstamp-config
```

## Usage

### 1. Generate the stamp at deploy time

Run this once per deploy, in the app's working directory (a real git checkout):

```bash
git pull origin develop
php artisan gitstamp:generate
composer install --no-dev --optimize-autoloader --no-interaction
php artisan migrate --force
```

This writes a value like `2026.07.18-6601bf7` to `storage/app/gitstamp.txt` (configurable). It never fails the deploy —
if `.git` isn't present or `git` isn't on `$PATH`, it falls back to a configurable default (`'dev'` by default) instead
of throwing.

Skip this step in local/dev environments; `gitstamp()` will simply return the fallback value.

### 2. Display it

**Helper:**

```blade
<div class="float-right d-none d-sm-inline-block">
    <b>Version</b> {{ gitstamp() }}
</div>
```

**Facade:**

```php
use MTechOrg\Gitstamp\Facades\Gitstamp;

Gitstamp::current(); // "2026.07.18-6601bf7"
```

**Blade component:**

```blade
<x-gitstamp::badge class="float-right d-none d-sm-inline-block" />
```

The component renders minimal markup (`Version {{ ... }}`) and forwards any attributes you pass it — styling and
placement are up to your app's theme.

## Configuration

```php
// config/gitstamp.php
return [
    'path' => storage_path('app/gitstamp.txt'),
    'date_format' => 'Y.m.d',
    'format' => '%s-%s', // sprintf(format, date, short_sha)
    'fallback' => 'dev',
];
```

## Testing

```bash
composer install
composer test
composer format   # Pint, code style
composer analyse   # PHPStan / Larastan, static analysis
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a history of changes.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md).

## Security

If you discover a security issue, please open an issue on
[GitHub](https://github.com/m-tech-org/laravel-gitstamp/issues) rather than a public PR with exploit details.

## Credits

See [CREDITS.md](CREDITS.md).

## License

The MIT License (MIT). See [LICENSE.md](LICENSE.md) for details.
