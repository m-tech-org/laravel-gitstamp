<?php

use MTechOrg\Gitstamp\Gitstamp;

if (! function_exists('gitstamp')) {
    /**
     * The current deploy's version stamp (date + short git SHA), or the
     * configured fallback if none has been generated.
     */
    function gitstamp(): string
    {
        return app(Gitstamp::class)->current();
    }
}
