# Changelog

All notable changes to `laravel-gitstamp` are documented here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to
[Semantic Versioning](https://semver.org/).

## [1.0.0] - 2026-07-18

### Added

- `php artisan gitstamp:generate` Artisan command — computes a `date + short-git-sha` version
  stamp (default format `Y.m.d-<sha>`, e.g. `2026.07.18-6601bf7`) and writes it to
  `storage/app/gitstamp.txt` (configurable). Falls back gracefully to a configurable default
  when `.git`/`git` aren't available, rather than failing the deploy.
- `gitstamp()` global helper and `Gitstamp` facade — read the generated stamp at runtime, cached
  per request, with the same fallback behavior.
- `<x-gitstamp::badge />` Blade component — minimal `Version {{ ... }}` markup, forwards any
  attributes passed to it.
- Publishable `config/gitstamp.php` — output path, date format, stamp format, fallback value.
- Support for Laravel 9–12 on PHP 8.1–8.4.
