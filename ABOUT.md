# About laravel-gitstamp

## The problem

Plenty of Laravel apps are deployed the simple way: SSH into a shared host, `git pull`,
`composer install`, done. There's no build pipeline that produces a versioned artifact, and no
discipline around git tags or semver — and for small internal apps, introducing one is often
more process than the problem deserves.

The trouble shows up later: something looks wrong in the UI — a bug report, a support
screenshot — and there's no quick way to tell which commit is actually running on a given
environment (production vs. staging) without SSHing in and running `git log`.

The original ask behind this package was small: show a version string next to a footer's
copyright line, aligned right, updated automatically on every deploy — without introducing git-tag
maintenance. Since that need recurs across any app deployed the same way, it made more sense as a
small reusable package than a one-off helper copy-pasted into each app.

## Goals

- Give every consuming app a one-line way to display "what commit/build is currently live" in its
  UI (footer, admin bar, a `/health` endpoint, etc.).
- Zero manual versioning discipline required — no git tags, no manually bumped version files.
- Deploy-time generation: the version string is computed once per deploy, not resolved by
  shelling out to git on every request — shared hosting may have `exec()`/`shell_exec()` disabled
  or rate-limited, and repeated git calls in a request path are wasteful.
- Work the same way whether the deploy is "SSH + `git pull`" or something more sophisticated
  later (CI-built artifact, Docker image, etc.) — the package doesn't assume any particular
  deploy pipeline beyond "there's a point in the deploy where a command can run in the app's
  directory."

## Non-goals

- **Not** a replacement for semantic versioning on packages/libraries that *are* properly
  released — this is for internal apps with no release process, not for versioning the package
  itself.
- **Not** a full release-management or changelog tool.
- **Not** responsible for *how* the version appears in every app's UI — it provides the value and
  a couple of easy ways to render it (helper, facade, Blade component); each consuming app
  decides where and how it appears.

## Design notes

- **Deploy-time, not request-time.** `gitstamp:generate` runs once during deploy and writes the
  computed value to a file. Nothing in the request path shells out to git.
- **Fails soft.** If `.git` isn't present or `git` isn't on `$PATH`, generation falls back to a
  configurable default instead of failing the deploy.
- **Format is date + short SHA by default** (`2026.07.18-6601bf7`) rather than a bare SHA, for
  at-a-glance recency — how long ago was this deployed, at a glance, without cross-referencing a
  commit timestamp.
- A future extension (not in v1) is to prefer `git describe --tags` over the date+SHA format when
  an annotated tag is present at `HEAD` — letting an app "graduate" to real semver later without
  changing how the value is displayed, without requiring tags now.

## Maintainer

Maintained by [Morph Technologies](https://github.com/m-tech-org).
