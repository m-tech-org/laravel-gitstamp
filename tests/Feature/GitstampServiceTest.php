<?php

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use MTechOrg\Gitstamp\GitReader;
use MTechOrg\Gitstamp\Gitstamp;
use MTechOrg\Gitstamp\Tests\Fakes\FakeGitReader;

beforeEach(function () {
    $this->path = storage_path('app/gitstamp-test.txt');
    config(['gitstamp.path' => $this->path]);
    File::deleteDirectory(dirname($this->path));
});

afterEach(function () {
    File::deleteDirectory(dirname($this->path));
});

it('generates a date + short-sha stamp and writes it to the configured path', function () {
    $this->app->instance(GitReader::class, new FakeGitReader('6601bf7'));
    Date::setTestNow('2026-07-18');

    $value = $this->app->make(Gitstamp::class)->generate();

    expect($value)->toBe('2026.07.18-6601bf7')
        ->and(File::get($this->path))->toBe('2026.07.18-6601bf7');
});

it('falls back without throwing when no git sha can be resolved', function () {
    $this->app->instance(GitReader::class, new FakeGitReader(null));
    config(['gitstamp.fallback' => 'dev']);

    $value = $this->app->make(Gitstamp::class)->generate();

    expect($value)->toBe('dev')
        ->and(File::get($this->path))->toBe('dev');
});

it('reads the generated file for current()', function () {
    File::ensureDirectoryExists(dirname($this->path));
    File::put($this->path, '2026.07.18-abc1234');

    $value = $this->app->make(Gitstamp::class)->current();

    expect($value)->toBe('2026.07.18-abc1234');
});

it('falls back to the configured value when no file has been generated', function () {
    config(['gitstamp.fallback' => 'dev']);

    $value = $this->app->make(Gitstamp::class)->current();

    expect($value)->toBe('dev');
});

it('caches current() for the lifetime of the instance', function () {
    File::ensureDirectoryExists(dirname($this->path));
    File::put($this->path, 'first');

    $gitstamp = $this->app->make(Gitstamp::class);
    expect($gitstamp->current())->toBe('first');

    File::put($this->path, 'second');
    expect($gitstamp->current())->toBe('first');
});
