<?php

use Illuminate\Support\Facades\File;
use MTechOrg\Gitstamp\Facades\Gitstamp;

beforeEach(function () {
    $this->path = storage_path('app/gitstamp-test.txt');
    config(['gitstamp.path' => $this->path]);
    File::deleteDirectory(dirname($this->path));
    File::ensureDirectoryExists(dirname($this->path));
    File::put($this->path, '2026.07.18-6601bf7');
});

afterEach(function () {
    File::deleteDirectory(dirname($this->path));
});

it('exposes the current stamp via the gitstamp() helper', function () {
    expect(gitstamp())->toBe('2026.07.18-6601bf7');
});

it('exposes the current stamp via the Gitstamp facade', function () {
    expect(Gitstamp::current())->toBe('2026.07.18-6601bf7');
});
