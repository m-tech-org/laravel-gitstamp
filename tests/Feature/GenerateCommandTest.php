<?php

use Illuminate\Support\Facades\File;
use MTechOrg\Gitstamp\GitReader;
use MTechOrg\Gitstamp\Tests\Fakes\FakeGitReader;

beforeEach(function () {
    $this->path = storage_path('app/gitstamp-test.txt');
    config(['gitstamp.path' => $this->path]);
    File::deleteDirectory(dirname($this->path));
});

afterEach(function () {
    File::deleteDirectory(dirname($this->path));
});

it('writes the generated stamp and reports success', function () {
    $this->app->instance(GitReader::class, new FakeGitReader('6601bf7'));

    $this->artisan('gitstamp:generate')
        ->assertSuccessful();

    expect(File::exists($this->path))->toBeTrue();
});

it('succeeds even when git info is unavailable', function () {
    $this->app->instance(GitReader::class, new FakeGitReader(null));

    $this->artisan('gitstamp:generate')
        ->assertSuccessful();

    expect(File::get($this->path))->toBe(config('gitstamp.fallback'));
});
