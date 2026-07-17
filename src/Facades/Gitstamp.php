<?php

namespace MTechOrg\Gitstamp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string current()
 * @method static string generate()
 *
 * @see \MTechOrg\Gitstamp\Gitstamp
 */
class Gitstamp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MTechOrg\Gitstamp\Gitstamp::class;
    }
}
