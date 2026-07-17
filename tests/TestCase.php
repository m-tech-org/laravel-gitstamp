<?php

namespace MTechOrg\Gitstamp\Tests;

use MTechOrg\Gitstamp\GitstampServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            GitstampServiceProvider::class,
        ];
    }
}
