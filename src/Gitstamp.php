<?php

namespace MTechOrg\Gitstamp;

use Illuminate\Support\Facades\File;

class Gitstamp
{
    protected ?string $cached = null;

    public function __construct(protected GitReader $gitReader) {}

    /**
     * Compute the current version string and write it to the configured
     * path. Never throws — a deploy must not fail because of this.
     */
    public function generate(): string
    {
        $sha = $this->gitReader->shortSha(base_path());

        $value = $sha === null
            ? (string) config('gitstamp.fallback', 'dev')
            : sprintf(
                (string) config('gitstamp.format', '%s-%s'),
                now()->format((string) config('gitstamp.date_format', 'Y.m.d')),
                $sha,
            );

        $path = (string) config('gitstamp.path', storage_path('app/gitstamp.txt'));

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $value);

        $this->cached = $value;

        return $value;
    }

    /**
     * The currently active version string: the last generated value if
     * present on disk, otherwise the configured fallback. Cached for the
     * lifetime of this instance (a container singleton).
     */
    public function current(): string
    {
        if ($this->cached !== null) {
            return $this->cached;
        }

        $path = (string) config('gitstamp.path', storage_path('app/gitstamp.txt'));

        $value = File::exists($path)
            ? trim(File::get($path))
            : '';

        if ($value === '') {
            $value = (string) config('gitstamp.fallback', 'dev');
        }

        return $this->cached = $value;
    }
}
