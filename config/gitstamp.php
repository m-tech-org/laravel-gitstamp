<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gitstamp file path
    |--------------------------------------------------------------------------
    |
    | Where `gitstamp:generate` writes the computed version string, and where
    | it's read from at runtime. Keep this outside of version control (the
    | default, under storage/, already is).
    |
    */

    'path' => storage_path('app/gitstamp.txt'),

    /*
    |--------------------------------------------------------------------------
    | Date format
    |--------------------------------------------------------------------------
    |
    | PHP date() format used for the date portion of the generated stamp.
    |
    */

    'date_format' => 'Y.m.d',

    /*
    |--------------------------------------------------------------------------
    | Stamp format
    |--------------------------------------------------------------------------
    |
    | sprintf() format combining the date and the short git SHA, in that
    | order. The default produces something like "2026.07.18-6601bf7".
    |
    */

    'format' => '%s-%s',

    /*
    |--------------------------------------------------------------------------
    | Fallback value
    |--------------------------------------------------------------------------
    |
    | Used whenever a git SHA can't be resolved (no .git, git not on PATH,
    | not a real repository) or the generated file doesn't exist yet — e.g.
    | local development, where gitstamp:generate is typically never run.
    |
    */

    'fallback' => 'dev',

];
