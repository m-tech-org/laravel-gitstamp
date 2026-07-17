<?php

use Illuminate\Support\Facades\File;

it('publishes the config file under the gitstamp-config tag', function () {
    $published = config_path('gitstamp.php');
    File::delete($published);

    $this->artisan('vendor:publish', ['--tag' => 'gitstamp-config'])
        ->assertSuccessful();

    expect(File::exists($published))->toBeTrue();

    File::delete($published);
});
