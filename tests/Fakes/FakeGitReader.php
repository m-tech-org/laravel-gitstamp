<?php

namespace MTechOrg\Gitstamp\Tests\Fakes;

use MTechOrg\Gitstamp\GitReader;

class FakeGitReader extends GitReader
{
    public function __construct(protected ?string $sha) {}

    public function shortSha(string $cwd): ?string
    {
        return $this->sha;
    }
}
