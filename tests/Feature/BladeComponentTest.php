<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

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

it('renders the version stamp via the x-gitstamp::badge component', function () {
    $rendered = Blade::render('<x-gitstamp::badge />');

    expect($rendered)->toContain('Version 2026.07.18-6601bf7');
});

it('forwards extra attributes onto the rendered element', function () {
    $rendered = Blade::render('<x-gitstamp::badge class="float-right" />');

    expect($rendered)->toContain('class="float-right"');
});
