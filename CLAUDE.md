# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project status

Implemented and published. `m-tech-org/laravel-gitstamp` is a public, open-source Laravel package
maintained by [Morph Technologies](https://github.com/m-tech-org). See `ABOUT.md` for the problem
it solves and the reasoning behind its design; see `README.md` for install/usage.

## Commands

```bash
composer install
composer test       # Pest test suite (Orchestra Testbench)
composer format      # Laravel Pint, code style (--test to check without fixing)
composer analyse      # PHPStan / Larastan, static analysis
```

Run a single test file: `vendor/bin/pest tests/Feature/GitstampServiceTest.php`.

CI (`run-tests.yml`) matrix-tests Laravel 10–12 only, pinning both `orchestra/testbench` and
`pestphp/pest` per leg (see the workflow's comments). Laravel 9 is excluded from CI — not
unsupported, just untestable with Pest, since every Pest 2.x release requires PHPUnit `^10` and
Laravel 9's Testbench pins PHPUnit to `^9.5.10` only. `composer.json`'s `config.policy.advisories.block`
is `false` (root-package-only) so Composer doesn't refuse to resolve Laravel majors past their
security-support window.

## What this package is

`m-tech-org/laravel-gitstamp` (PHP namespace `MTechOrg\Gitstamp`) is a small, reusable Composer
package — a Laravel package, not an application — for apps deployed by SSHing into a host and
running `git pull` + `composer install` in place, with no CI-built artifact and no git-tag
discipline. It lets such an app display "what commit/build is currently live" without shelling
out to git per-request.

## Core architecture

- **`src/GitReader::shortSha(string $cwd): ?string`** — the only place that shells out to git
  (via Symfony `Process`). Returns `null` on any failure instead of throwing; this is the seam
  that makes the fallback path testable (bind a fake `GitReader` in the container, see
  `tests/Fakes/FakeGitReader.php`).
- **`src/Gitstamp`** — container singleton with `generate()` (deploy-time: computes
  `date + short-sha` per `config('gitstamp.*')`, writes it to `config('gitstamp.path')`, falls
  back to `config('gitstamp.fallback')` when the SHA can't be resolved) and `current()`
  (runtime: reads the generated file, cached on the instance for the request).
- **`php artisan gitstamp:generate`** (`src/Commands/GenerateGitstampCommand.php`) — thin wrapper
  around `Gitstamp::generate()`, runs once per deploy. Never throws; a deploy must not fail
  because of this command.
- **Runtime consumption surfaces**, all reading the same generated file: the `gitstamp()` global
  helper (`src/helpers.php`), the `Gitstamp` facade (`src/Facades/Gitstamp.php`), and the
  `<x-gitstamp::badge />` Blade component (`resources/views/components/badge.blade.php`, resolved
  automatically via `loadViewsFrom(..., 'gitstamp')` in the service provider — no manual
  `Blade::component()` registration). The component renders bare `Version {{ ... }}` markup only;
  styling/placement belongs in the consuming app's views, not this package.
- **`config/gitstamp.php`** (publishable, tag `gitstamp-config`): `path`, `date_format`, `format`,
  `fallback`.
- Nothing in the request path ever shells out to git — only `gitstamp:generate` does, and only at
  deploy time.

## Non-goals (don't build these)

- Not a general release-management or changelog tool.
- Not responsible for where/how the version is displayed in a consuming app's UI beyond the
  helper/facade/Blade component — no app-specific styling belongs in this package.
- Not a replacement for semver on properly-released packages/libraries.
- The optional `git describe --tags` upgrade path (preferring an annotated tag over date+SHA when
  present at `HEAD`) is intentionally not implemented — documented in `ABOUT.md` as a future
  extension only.

## Distribution & versioning

- Published on [Packagist](https://packagist.org/packages/m-tech-org/laravel-gitstamp); consuming
  apps install it with a plain `composer require m-tech-org/laravel-gitstamp`.
- Releases happen on `master`, tagged with real semver (`v1.0.0`, ...). Day-to-day work happens on
  `develop`.

## Note on PRD.md

`PRD.md` is the original internal planning document and predates the public-repo/open-source
decision — it references an internal client project by name as the motivating example. Do not
copy that reference into any committed doc, code, or commit message; describe the origin/problem
generically instead (see `ABOUT.md`).
