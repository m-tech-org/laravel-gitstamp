# Stop SSHing into production to ask what's deployed

*Introducing [laravel-gitstamp](https://github.com/m-tech-org/laravel-gitstamp) — deploy-time version stamping for Laravel apps that don't have (and don't need) a release process.*

A bug report comes in with a screenshot. Something looks off in the UI. Your first question isn't "what's wrong" — it's **"which commit is this environment actually running?"**

If your app ships through a CI pipeline with tagged releases, you already know. But a huge number of Laravel apps — internal tools, client dashboards, small SaaS backends — are deployed the simple way: SSH into the host, `git pull`, `composer install`, done. No build artifact, no git tags, no version numbers anywhere. The only way to answer the question is to SSH in and run `git log` — once for production, again for staging.

For apps like that, introducing full release discipline is more process than the problem deserves. What you actually want is small: a version string in the footer that updates itself on every deploy.

That's what **laravel-gitstamp** does.

## What it looks like

One command in your deploy script:

```bash
git pull origin main
php artisan gitstamp:generate
composer install --no-dev --optimize-autoloader --no-interaction
```

One line in your Blade footer:

```blade
<x-gitstamp::badge class="float-right" />
```

And every environment now tells you what it's running:

```
Version 2026.07.18-6601bf7
```

Date plus short SHA. The date tells you *how stale* the deploy is at a glance; the SHA tells you *exactly* which commit to check out when you're reproducing a bug. There's also a `gitstamp()` helper and a `Gitstamp` facade if you'd rather surface it in an admin bar, a `/health` endpoint, or an error report.

## The design decisions that matter

**Deploy-time, not request-time.** The obvious quick fix — shelling out to `git rev-parse` in a Blade view — resolves git on every request. That's wasteful, and on shared hosting it often just doesn't work: `exec()` and `shell_exec()` are frequently disabled or rate-limited. `gitstamp:generate` runs *once* per deploy and writes the computed value to a plain file (`storage/app/gitstamp.txt` by default). Nothing in the request path ever touches git.

**It never fails your deploy.** If `.git` isn't present, or `git` isn't on `$PATH`, or the process errors for any reason, generation falls back to a configurable default (`'dev'`) instead of throwing. A version badge is not worth a broken deploy. This also means local dev needs zero setup — skip the generate step and the helper just returns the fallback.

**Zero versioning discipline required.** No tags to remember, no version file to bump, no changelog to maintain. If your team could reliably do those things, you probably wouldn't need this package.

**It renders a value, not a design.** The Blade component outputs minimal markup and forwards whatever attributes you pass it. Where the version appears and how it's styled is your app's decision, not the package's.

Everything is configurable — file path, date format, stamp format, fallback value — via a publishable config file.

## What it deliberately isn't

It's not a release-management tool, not a changelog generator, and not a replacement for real semver on properly-released packages. It's for apps that have *no* release process, giving them the one thing they're missing: an honest answer to "what's live right now?"

There's a natural upgrade path on the roadmap: preferring `git describe --tags` when an annotated tag exists at `HEAD`, so an app can graduate to real semantic versions later without changing a line of display code. For v1, date + SHA covers the actual need.

## Try it

The package is MIT-licensed, tested against Laravel 10–12 in CI (Laravel 9 supported at runtime), and lives on GitHub:

**[github.com/m-tech-org/laravel-gitstamp](https://github.com/m-tech-org/laravel-gitstamp)**

```bash
composer require m-tech-org/laravel-gitstamp
```

If you've ever squinted at a support screenshot wondering which deploy it came from — this is the fifteen-minute fix. Issues and PRs welcome.

---

*laravel-gitstamp is maintained by [Morph Technologies](https://github.com/m-tech-org).*
